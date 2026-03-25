<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors([
                'error' => 'Silakan login terlebih dahulu.'
            ]);
        }

        $user = Auth::user();
        
        // Jika user adalah Super Admin, izinkan semua akses
        if ($user->role === 'Super') {
            return $next($request);
        }

        // Cek apakah role user termasuk dalam roles yang diizinkan
        if (!in_array($user->role, $roles)) {
            // Jika request dari API atau AJAX
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.',
                    'error' => 'FORBIDDEN'
                ], 403);
            }

            // Jika bukan AJAX, tampilkan halaman forbidden
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}