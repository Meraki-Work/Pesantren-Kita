<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionTimeout
{
    /**
     * Routes yang dikecualikan dari session timeout
     * (Landing page, public pages, dll)
     */
    protected $except = [
        '/',
        'login',
        'register',
        'password/*',
        'public/*',
        'landing-page'
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Cek jika route saat ini dikecualikan
        if ($this->shouldPassThrough($request)) {
            return $next($request);
        }

        // Jika user tidak login, lanjutkan request
        if (!Auth::check()) {
            return $next($request);
        }

        $lastActivity = session('last_activity');
        $timeout = config('session.lifetime') * 60; // dalam detik

        // Jika session last_activity belum ada, set sekarang
        if (!$lastActivity) {
            session(['last_activity' => time()]);
            return $next($request);
        }

        // Cek jika waktu timeout sudah lewat
        if (time() - $lastActivity > $timeout) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('message', 'Session Anda telah berakhir karena tidak ada aktivitas.');
        }

        // Update last activity time
        session(['last_activity' => time()]);

        return $next($request);
    }

    /**
     * Cek apakah request harus dilewati (tidak perlu session timeout)
     */
    protected function shouldPassThrough($request)
    {
        foreach ($this->except as $except) {
            if ($except !== '/') {
                if ($request->is($except)) {
                    return true;
                }
            } else {
                if ($request->is('/')) {
                    return true;
                }
            }
        }

        return false;
    }
}