<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StatusUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Contoh: cek jika user belum login
        if (!auth()->check()) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Contoh tambahan: cek role user 
        // if (auth()->user()->role !== 'admin') {
        //     abort(403, 'Anda tidak punya akses');
        // }

        return $next($request);
    }
}
