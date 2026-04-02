<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\PlanFeature;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PlanManagementController extends Controller
{
    /**
     * Display a listing of all plans
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $isActive = $request->get('is_active');
        
        $plansQuery = Plan::query();
        
        if ($search) {
            $plansQuery->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        }
        
        if ($isActive !== null) {
            $plansQuery->where('is_active', $isActive);
        }
        
        $plans = $plansQuery->orderBy('price_month')->paginate(10);
        
        $statistics = [
            'total_plans' => Plan::count(),
            'active_plans' => Plan::where('is_active', 1)->count(),
            'inactive_plans' => Plan::where('is_active', 0)->count(),
            'total_subscribers' => \App\Models\Subscription::where('status', 'active')->count(),
            'most_popular_plan' => Plan::withCount('subscriptions')
                ->orderBy('subscriptions_count', 'desc')
                ->first()
        ];
        
        $this->logActivity(
            'VIEW_PLANS',
            'Melihat halaman manajemen paket langganan',
            $request,
            ['search' => $search, 'is_active' => $isActive]
        );
        
        return view('super.plan.index', compact('plans', 'statistics'));
    }
    
    /**
     * Show form to create new plan
     */
    public function create()
    {
        return view('super.plan.create');
    }
    
    /**
     * Store a newly created plan
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150|unique:plans,name',
            'description' => 'nullable|string',
            'price_month' => 'required|numeric|min:0',
            'price_year' => 'required|numeric|min:0',
            'is_active' => 'boolean',
            'limits' => 'nullable|array',
            'features' => 'nullable|array'
        ]);
        
        DB::beginTransaction();
        
        try {
            // Create slug from name
            $slug = Str::slug($validated['name']);
            
            // Check if slug already exists
            $originalSlug = $slug;
            $counter = 1;
            while (Plan::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            
            // Create plan
            $plan = Plan::create([
                'slug' => $slug,
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price_month' => $validated['price_month'],
                'price_year' => $validated['price_year'],
                'limits_json' => $validated['limits'] ?? null,
                'is_active' => $validated['is_active'] ?? true
            ]);
            
            // Save features if any
            if (!empty($validated['features'])) {
                foreach ($validated['features'] as $feature) {
                    if (!empty($feature['key']) && !empty($feature['value'])) {
                        PlanFeature::create([
                            'plan_id' => $plan->id,
                            'feature_key' => $feature['key'],
                            'enabled' => $feature['value'] === 'true' || $feature['value'] === true
                        ]);
                    }
                }
            }
            
            DB::commit();
            
            $this->logActivity(
                'CREATE_PLAN',
                "Menambahkan paket baru: {$plan->name}",
                $request,
                ['plan_id' => $plan->id, 'plan_data' => $validated]
            );
            
            return redirect()->route('super.plan.index')
                ->with('success', 'Paket berhasil ditambahkan.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menambahkan paket: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Display plan details
     */
    public function show($id)
    {
        $plan = Plan::with(['features', 'subscriptions' => function($query) {
            $query->with('ponpes')->limit(10);
        }])->findOrFail($id);
        
        $statistics = [
            'total_subscribers' => $plan->subscriptions()->count(),
            'active_subscribers' => $plan->subscriptions()->where('status', 'active')->count(),
            'monthly_revenue' => $plan->subscriptions()
                ->where('billing_cycle', 'monthly')
                ->where('status', 'active')
                ->count() * $plan->price_month,
            'yearly_revenue' => $plan->subscriptions()
                ->where('billing_cycle', 'yearly')
                ->where('status', 'active')
                ->count() * $plan->price_year,
        ];
        
        $this->logActivity(
            'VIEW_PLAN_DETAIL',
            "Melihat detail paket: {$plan->name}",
            request(),
            ['plan_id' => $id]
        );
        
        return view('super.plan.show', compact('plan', 'statistics'));
    }
    
    /**
     * Show form to edit plan
     */
    public function edit($id)
    {
        $plan = Plan::with('features')->findOrFail($id);
        return view('super.plan.edit', compact('plan'));
    }
    
    /**
     * Update plan
     */
    public function update(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:150|unique:plans,name,' . $id,
            'description' => 'nullable|string',
            'price_month' => 'required|numeric|min:0',
            'price_year' => 'required|numeric|min:0',
            'is_active' => 'boolean',
            'limits' => 'nullable|array',
            'features' => 'nullable|array'
        ]);
        
        DB::beginTransaction();
        
        try {
            // Update slug if name changed
            if ($plan->name != $validated['name']) {
                $slug = Str::slug($validated['name']);
                $originalSlug = $slug;
                $counter = 1;
                while (Plan::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                    $slug = $originalSlug . '-' . $counter;
                    $counter++;
                }
                $validated['slug'] = $slug;
            }
            
            $plan->update([
                'name' => $validated['name'],
                'slug' => $validated['slug'] ?? $plan->slug,
                'description' => $validated['description'],
                'price_month' => $validated['price_month'],
                'price_year' => $validated['price_year'],
                'limits_json' => $validated['limits'] ?? null,
                'is_active' => $validated['is_active'] ?? $plan->is_active
            ]);
            
            // Update features
            if (isset($validated['features'])) {
                // Delete old features
                PlanFeature::where('plan_id', $plan->id)->delete();
                
                // Save new features
                foreach ($validated['features'] as $feature) {
                    if (!empty($feature['key']) && isset($feature['value'])) {
                        PlanFeature::create([
                            'plan_id' => $plan->id,
                            'feature_key' => $feature['key'],
                            'enabled' => $feature['value'] === 'true' || $feature['value'] === true
                        ]);
                    }
                }
            }
            
            DB::commit();
            
            $this->logActivity(
                'UPDATE_PLAN',
                "Mengupdate paket: {$plan->name}",
                $request,
                ['plan_id' => $id, 'changes' => array_keys($validated)]
            );
            
            return redirect()->route('super.plan.index')
                ->with('success', 'Paket berhasil diperbarui.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal memperbarui paket: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Toggle plan active status
     */
    public function toggleStatus(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);
        
        $newStatus = !$plan->is_active;
        $plan->update(['is_active' => $newStatus]);
        
        $this->logActivity(
            'TOGGLE_PLAN_STATUS',
            "Mengubah status paket {$plan->name} menjadi " . ($newStatus ? 'Aktif' : 'Nonaktif'),
            $request,
            ['plan_id' => $id, 'new_status' => $newStatus]
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Status paket berhasil diubah',
            'is_active' => $newStatus
        ]);
    }
    
    /**
     * Delete plan
     */
    public function destroy($id)
    {
        $plan = Plan::findOrFail($id);
        
        // Check if plan has active subscriptions
        $activeSubscriptions = $plan->subscriptions()->where('status', 'active')->count();
        
        if ($activeSubscriptions > 0) {
            return redirect()->route('super.plan.index')
                ->with('error', 'Tidak dapat menghapus paket karena masih ada langganan aktif.');
        }
        
        $planName = $plan->name;
        $plan->delete();
        
        $this->logActivity(
            'DELETE_PLAN',
            "Menghapus paket: {$planName}",
            request(),
            ['plan_id' => $id]
        );
        
        return redirect()->route('super.plan.index')
            ->with('success', 'Paket berhasil dihapus.');
    }
    
    /**
     * Export plans to CSV
     */
    public function export(Request $request)
    {
        $plans = Plan::withCount('subscriptions')->orderBy('price_month')->get();
        
        $filename = 'plans_export_' . Carbon::now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($plans) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            
            fputcsv($file, [
                'Nama Paket',
                'Slug',
                'Harga Bulanan',
                'Harga Tahunan',
                'Status',
                'Jumlah Subscriber',
                'Dibuat Pada'
            ]);
            
            foreach ($plans as $plan) {
                fputcsv($file, [
                    $plan->name,
                    $plan->slug,
                    'Rp ' . number_format($plan->price_month, 0, ',', '.'),
                    'Rp ' . number_format($plan->price_year, 0, ',', '.'),
                    $plan->is_active ? 'Aktif' : 'Nonaktif',
                    $plan->subscriptions_count,
                    Carbon::parse($plan->created_at)->format('d/m/Y H:i')
                ]);
            }
            
            fclose($file);
        };
        
        $this->logActivity(
            'EXPORT_PLANS',
            'Mengekspor data paket langganan ke CSV',
            $request,
            ['total_plans' => $plans->count()]
        );
        
        return response()->stream($callback, 200, $headers);
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
            \Log::error('Failed to log activity: ' . $e->getMessage());
        }
    }
}