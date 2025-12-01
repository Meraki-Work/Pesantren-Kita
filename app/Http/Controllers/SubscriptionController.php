<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    /**
     * Menampilkan semua plans yang tersedia
     */
    public function plans()
    {
        $plans = DB::table('plans')
            ->where('is_active', 1)
            ->orderBy('price_month', 'asc')
            ->get()
            ->map(function ($plan) {
                $plan->features = DB::table('plan_features')
                    ->where('plan_id', $plan->id)
                    ->where('enabled', 1)
                    ->get();
                $plan->limits = json_decode($plan->limits_json, true) ?? [];
                return $plan;
            });

        // Get current user's subscription
        $currentSubscription = $this->getCurrentSubscription();

        return view('subscription.plans', compact('plans', 'currentSubscription'));
    }

    /**
     * Halaman upgrade subscription
     */
    public function upgrade()
    {
        $currentSubscription = $this->getCurrentSubscription();
        
        if (!$currentSubscription) {
            return redirect()->route('subscription.plans')
                ->with('error', 'Anda belum memiliki subscription aktif.');
        }

        $currentPlan = DB::table('plans')->where('id', $currentSubscription->plan_id)->first();
        $availablePlans = DB::table('plans')
            ->where('is_active', 1)
            ->where('id', '!=', $currentSubscription->plan_id)
            ->orderBy('price_month', 'asc')
            ->get()
            ->map(function ($plan) {
                $plan->features = DB::table('plan_features')
                    ->where('plan_id', $plan->id)
                    ->where('enabled', 1)
                    ->get();
                $plan->limits = json_decode($plan->limits_json, true) ?? [];
                return $plan;
            });

        return view('subscription.upgrade', compact('currentPlan', 'availablePlans', 'currentSubscription'));
    }

    /**
     * Proses upgrade subscription
     */
    public function processUpgrade(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'billing_cycle' => 'required|in:monthly,yearly'
        ]);

        $user = Auth::user();
        $currentSubscription = $this->getCurrentSubscription();

        if (!$currentSubscription) {
            return back()->with('error', 'Tidak ada subscription aktif.');
        }

        $newPlan = DB::table('plans')->where('id', $request->plan_id)->first();

        // Cek apakah plan baru lebih tinggi dari current plan
        $currentPlanPrice = $currentSubscription->billing_cycle === 'yearly' 
            ? DB::table('plans')->where('id', $currentSubscription->plan_id)->value('price_year')
            : DB::table('plans')->where('id', $currentSubscription->plan_id)->value('price_month');

        $newPlanPrice = $request->billing_cycle === 'yearly' 
            ? $newPlan->price_year 
            : $newPlan->price_month;

        if ($newPlanPrice <= $currentPlanPrice) {
            return back()->with('error', 'Hanya bisa upgrade ke plan yang lebih tinggi.');
        }

        try {
            DB::beginTransaction();

            // Update subscription
            DB::table('subscriptions')
                ->where('id', $currentSubscription->id)
                ->update([
                    'plan_id' => $newPlan->id,
                    'billing_cycle' => $request->billing_cycle,
                    'current_period_end' => $currentSubscription->current_period_end, // Tetap sampai periode berakhir
                    'updated_at' => now()
                ]);

            // Catat history
            DB::table('subscription_histories')->insert([
                'subscription_id' => $currentSubscription->id,
                'action' => 'upgraded',
                'from_plan_id' => $currentSubscription->plan_id,
                'to_plan_id' => $newPlan->id,
                'note' => 'Upgrade dari ' . DB::table('plans')->where('id', $currentSubscription->plan_id)->value('name') . ' ke ' . $newPlan->name,
                'created_at' => now()
            ]);

            // Buat payment record (jika perlu pembayaran langsung)
            DB::table('payments')->insert([
                'subscription_id' => $currentSubscription->id,
                'ponpes_id' => $user->ponpes_id,
                'amount' => $newPlanPrice - $currentPlanPrice, // Selisih harga
                'currency' => 'IDR',
                'method' => 'upgrade',
                'status' => 'paid', // Atau 'pending' jika butuh konfirmasi
                'paid_at' => now(),
                'metadata' => json_encode([
                    'type' => 'upgrade',
                    'from_plan' => $currentSubscription->plan_id,
                    'to_plan' => $newPlan->id,
                    'billing_cycle' => $request->billing_cycle
                ]),
                'created_at' => now()
            ]);

            DB::commit();

            return redirect()->route('subscription.success')
                ->with('success', 'Upgrade berhasil! Anda sekarang menggunakan plan ' . $newPlan->name);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat proses upgrade: ' . $e->getMessage());
        }
    }

    /**
     * Halaman sukses upgrade
     */
    public function success()
    {
        return view('subscription.success');
    }

    /**
     * Helper method untuk mendapatkan subscription aktif
     */
    private function getCurrentSubscription()
    {
        $user = Auth::user();
        return DB::table('subscriptions')
            ->where('ponpes_id', $user->ponpes_id)
            ->where('status', 'active')
            ->first();
    }
}