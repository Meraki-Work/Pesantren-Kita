<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Plan;
use App\Models\PlanFeature;
use App\Models\SubscriptionHistory;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LanggananController extends Controller
{
    /**
     * Display a listing of all subscriptions
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        $search = $request->get('search');
        $planId = $request->get('plan_id');

        $subscriptionsQuery = Subscription::with(['ponpes', 'plan']);

        if ($status) {
            $subscriptionsQuery->where('status', $status);
        }

        if ($planId) {
            $subscriptionsQuery->where('plan_id', $planId);
        }

        if ($search) {
            $subscriptionsQuery->whereHas('ponpes', function ($q) use ($search) {
                $q->where('nama_ponpes', 'like', "%{$search}%");
            });
        }

        $langganan = $subscriptionsQuery->orderBy('created_at', 'desc')->paginate(20); // Ganti nama variabel

        $statistics = [
            'total_langganan' => Subscription::count(),
            'active_langganan' => Subscription::where('status', Subscription::STATUS_ACTIVE)->count(),
            'expired_langganan' => Subscription::where('status', Subscription::STATUS_EXPIRED)->count(),
            'pending_langganan' => Subscription::where('status', Subscription::STATUS_TRIAL)->count(),
            'total_revenue' => Subscription::where('status', Subscription::STATUS_ACTIVE)
                ->with('plan')
                ->get()
                ->sum(function ($sub) {
                    $price = $sub->billing_cycle === 'monthly'
                        ? ($sub->plan->price_month ?? 0)
                        : ($sub->plan->price_year ?? 0);
                    return $price;
                }),
            'revenue_this_month' => Subscription::where('status', Subscription::STATUS_ACTIVE)
                ->whereMonth('created_at', Carbon::now()->month)
                ->with('plan')
                ->get()
                ->sum(function ($sub) {
                    $price = $sub->billing_cycle === 'monthly'
                        ? ($sub->plan->price_month ?? 0)
                        : ($sub->plan->price_year ?? 0);
                    return $price;
                })
        ];

        $plans = Plan::active()->orderBy('price_month')->get();

        return view('super.langganan.index', compact('langganan', 'statistics', 'plans'));
    }

    /**
     * Show form to create new subscription
     */
    public function create()
    {
        $plans = Plan::with('features')->active()->orderBy('price_month')->orderBy('name')->get();
        $ponpesList = \App\Models\Ponpes::where('status', 'Aktif')->orderBy('nama_ponpes')->get();

        return view('super.langganan.create', compact('plans', 'ponpesList'));
    }

    public function edit($id)
    {
        $subscription = Subscription::with(['ponpes', 'plan.features'])->findOrFail($id);
        $plans = Plan::with('features')->active()->orderBy('price_month')->orderBy('name')->get();
        $ponpesList = \App\Models\Ponpes::where('status', 'Aktif')->orderBy('nama_ponpes')->get();

        return view('super.langganan.edit', compact('subscription', 'plans', 'ponpesList'));
    }
    /**
     * Store a newly created subscription
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ponpes_id' => 'required|exists:ponpes,id_ponpes',
            'plan_id' => 'required|exists:plans,id',
            'billing_cycle' => 'required|in:monthly,yearly',
            'auto_renew' => 'boolean',
            'start_date' => 'required|date',
            'trial_days' => 'nullable|integer|min:0|max:30'
        ]);

        DB::beginTransaction();

        try {
            $plan = Plan::findOrFail($validated['plan_id']);
            $startDate = Carbon::parse($validated['start_date']);

            // Set trial period if specified
            $trialDays = $validated['trial_days'] ?? 0;
            $status = $trialDays > 0 ? Subscription::STATUS_TRIAL : Subscription::STATUS_ACTIVE;
            $periodEnd = $trialDays > 0
                ? $startDate->copy()->addDays($trialDays)
                : $startDate->copy()->addMonth();

            if ($validated['billing_cycle'] === Subscription::CYCLE_YEARLY && $status === Subscription::STATUS_ACTIVE) {
                $periodEnd = $startDate->copy()->addYear();
            }

            $subscription = Subscription::create([
                'ponpes_id' => $validated['ponpes_id'],
                'plan_id' => $validated['plan_id'],
                'status' => $status,
                'billing_cycle' => $validated['billing_cycle'],
                'start_date' => $startDate,
                'current_period_end' => $periodEnd,
                'auto_renew' => $validated['auto_renew'] ?? true,
                'metadata' => [
                    'trial_days' => $trialDays,
                    'created_by' => auth()->id(),
                    'created_by_name' => auth()->user()->username
                ]
            ]);

            // Create history log
            SubscriptionHistory::create([
                'subscription_id' => $subscription->id,
                'action' => SubscriptionHistory::ACTION_CREATED,
                'to_plan_id' => $validated['plan_id'],
                'note' => $trialDays > 0
                    ? "Langganan dibuat dengan masa trial {$trialDays} hari"
                    : "Langganan baru dibuat dengan paket {$plan->name}",
                'created_at' => now()
            ]);

            DB::commit();

            $this->logActivity(
                'CREATE_SUBSCRIPTION',
                "Membuat langganan baru untuk pondok {$subscription->ponpes->nama_ponpes} dengan paket {$plan->name}",
                $request,
                ['subscription_id' => $subscription->id, 'plan_id' => $plan->id]
            );

            return redirect()->route('super.langganan.index')
                ->with('success', 'Langganan berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menambahkan langganan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display subscription details
     */
    public function show($id)
    {
        $subscription = Subscription::with(['ponpes', 'plan', 'histories.fromPlan', 'histories.toPlan'])
            ->findOrFail($id);

        $this->logActivity(
            'VIEW_SUBSCRIPTION_DETAIL',
            "Melihat detail langganan untuk pondok {$subscription->ponpes->nama_ponpes}",
            request(),
            ['subscription_id' => $id]
        );

        return view('super.langganan.show', compact('subscription'));
    }

    /**
     * Update subscription
     */
    public function update(Request $request, $id)
    {
        $subscription = Subscription::findOrFail($id);

        $validated = $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'billing_cycle' => 'required|in:monthly,yearly',
            'status' => 'required|in:trial,active,past_due,canceled,expired',
            'auto_renew' => 'boolean',
            'current_period_end' => 'required|date',
            'note' => 'nullable|string'
        ]);

        DB::beginTransaction();

        try {
            $oldPlanId = $subscription->plan_id;
            $oldStatus = $subscription->status;

            $subscription->update([
                'plan_id' => $validated['plan_id'],
                'billing_cycle' => $validated['billing_cycle'],
                'status' => $validated['status'],
                'auto_renew' => $validated['auto_renew'] ?? $subscription->auto_renew,
                'current_period_end' => Carbon::parse($validated['current_period_end'])
            ]);

            // Create history log if plan changed
            if ($oldPlanId != $validated['plan_id']) {
                $oldPlan = Plan::find($oldPlanId);
                $newPlan = Plan::find($validated['plan_id']);

                $action = $newPlan->price_month > ($oldPlan->price_month ?? 0)
                    ? SubscriptionHistory::ACTION_UPGRADED
                    : SubscriptionHistory::ACTION_DOWNGRADED;

                SubscriptionHistory::create([
                    'subscription_id' => $subscription->id,
                    'action' => $action,
                    'from_plan_id' => $oldPlanId,
                    'to_plan_id' => $validated['plan_id'],
                    'note' => $validated['note'] ?? ($action === 'upgraded' ? 'Upgrade paket' : 'Downgrade paket'),
                    'created_at' => now()
                ]);
            }

            // Create history log if status changed
            if ($oldStatus != $validated['status']) {
                SubscriptionHistory::create([
                    'subscription_id' => $subscription->id,
                    'action' => $validated['status'],
                    'note' => $validated['note'] ?? "Status berubah dari {$oldStatus} menjadi {$validated['status']}",
                    'created_at' => now()
                ]);
            }

            DB::commit();

            $this->logActivity(
                'UPDATE_SUBSCRIPTION',
                "Mengupdate langganan untuk pondok {$subscription->ponpes->nama_ponpes}",
                $request,
                [
                    'subscription_id' => $id,
                    'old_plan_id' => $oldPlanId,
                    'new_plan_id' => $validated['plan_id'],
                    'old_status' => $oldStatus,
                    'new_status' => $validated['status']
                ]
            );

            return redirect()->route('super.langganan.index')
                ->with('success', 'Langganan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal memperbarui langganan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Cancel subscription
     */
    public function cancel(Request $request, $id)
    {
        $subscription = Subscription::findOrFail($id);

        DB::beginTransaction();

        try {
            $subscription->update([
                'status' => Subscription::STATUS_CANCELED,
                'auto_renew' => false
            ]);

            SubscriptionHistory::create([
                'subscription_id' => $subscription->id,
                'action' => SubscriptionHistory::ACTION_CANCELED,
                'note' => $request->get('note', 'Langganan dibatalkan oleh admin'),
                'created_at' => now()
            ]);

            DB::commit();

            $this->logActivity(
                'CANCEL_SUBSCRIPTION',
                "Membatalkan langganan untuk pondok {$subscription->ponpes->nama_ponpes}",
                $request,
                ['subscription_id' => $id]
            );

            return redirect()->route('super.langganan.show', $id)
                ->with('success', 'Langganan berhasil dibatalkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal membatalkan langganan: ' . $e->getMessage());
        }
    }

    /**
     * Renew subscription
     */
    public function renew(Request $request, $id)
    {
        $subscription = Subscription::findOrFail($id);

        DB::beginTransaction();

        try {
            $newPeriodEnd = $subscription->billing_cycle === Subscription::CYCLE_MONTHLY
                ? Carbon::now()->addMonth()
                : Carbon::now()->addYear();

            $subscription->update([
                'status' => Subscription::STATUS_ACTIVE,
                'current_period_end' => $newPeriodEnd,
                'auto_renew' => true
            ]);

            SubscriptionHistory::create([
                'subscription_id' => $subscription->id,
                'action' => SubscriptionHistory::ACTION_RENEWED,
                'note' => 'Langganan diperpanjang oleh admin',
                'created_at' => now()
            ]);

            DB::commit();

            $this->logActivity(
                'RENEW_SUBSCRIPTION',
                "Memperpanjang langganan untuk pondok {$subscription->ponpes->nama_ponpes}",
                $request,
                ['subscription_id' => $id]
            );

            return redirect()->route('super.langganan.show', $id)
                ->with('success', 'Langganan berhasil diperpanjang.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal memperpanjang langganan: ' . $e->getMessage());
        }
    }

    /**
     * Delete subscription
     */
    public function destroy($id)
    {
        $subscription = Subscription::findOrFail($id);
        $ponpesName = $subscription->ponpes->nama_ponpes;

        $subscription->delete();

        $this->logActivity(
            'DELETE_SUBSCRIPTION',
            "Menghapus langganan untuk pondok {$ponpesName}",
            request(),
            ['subscription_id' => $id]
        );

        return redirect()->route('super.langganan.index')
            ->with('success', 'Langganan berhasil dihapus.');
    }

    /**
     * Get subscription history
     */
    public function history($id)
    {
        $histories = SubscriptionHistory::with(['fromPlan', 'toPlan'])
            ->where('subscription_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $histories
        ]);
    }

    /**
     * Get statistics for chart
     */
    public function statistics()
    {
        $statistics = [
            'by_status' => [
                'active' => Subscription::where('status', Subscription::STATUS_ACTIVE)->count(),
                'trial' => Subscription::where('status', Subscription::STATUS_TRIAL)->count(),
                'canceled' => Subscription::where('status', Subscription::STATUS_CANCELED)->count(),
                'expired' => Subscription::where('status', Subscription::STATUS_EXPIRED)->count()
            ],
            'by_plan' => Plan::withCount('subscriptions')->get()->map(function ($plan) {
                return [
                    'name' => $plan->name,
                    'total' => $plan->subscriptions_count
                ];
            }),
            'subscription_trend' => Subscription::selectRaw('DATE(created_at) as date, COUNT(*) as total')
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get()
        ];

        return response()->json($statistics);
    }

    /**
     * Helper function to log activity
     */
    private function logActivity($action, $description, $request, $data = null)
    {
        try {
            SystemLog::create([
                'user_id' => auth()->id(),
                'username' => auth()->user()->username,
                'role' => auth()->user()->role,
                'action' => $action,
                'description' => $description,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'data' => $data ? json_encode($data) : null,
                'created_at' => Carbon::now()
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log activity: ' . $e->getMessage());
        }
    }
}
