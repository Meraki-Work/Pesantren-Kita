<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckFeature
{
    public function handle(Request $request, Closure $next, $featureKey)
    {
        $user = Auth::user();
        if (!$user) {
            // Redirect ke login jika tidak ada user
            return redirect()->route('login')->withErrors(['error' => 'Silakan login terlebih dahulu.']);
        }

        // Ambil ponpes_id dari user 
        $ponpesId = $user->ponpes_id;

        // Ambil subscription aktif
        $subscription = DB::table('subscriptions')
            ->where('ponpes_id', $ponpesId)
            ->where('status', 'active')
            ->first();

        if (!$subscription) {
            // Return ke halaman blade khusus untuk tidak ada subscription
            return response()->view('errors.no-subscription', [
                'message' => 'Tidak ada langganan aktif.',
                'feature' => $featureKey
            ], 403);
        }

        // Cek apakah fitur tersedia di plan
        $featureEnabled = DB::table('plan_features')
            ->where('plan_id', $subscription->plan_id)
            ->where('feature_key', $featureKey)
            ->where('enabled', 1)
            ->exists();

        if (!$featureEnabled) {
            // Return ke halaman blade khusus untuk fitur tidak tersedia
            return response()->view('errors.feature-not-available', [
                'message' => 'Fitur tidak tersedia di paket Anda.',
                'feature' => $featureKey,
                'subscription' => $subscription
            ], 403);
        }

        return $next($request);
    }
}