<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    /**
     * Route-route yang HANYA bisa diakses admin.
     * Kasir yang mencoba akses akan di-redirect ke dashboard dengan pesan error.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->role !== 'admin') {
            return redirect()->route('admin.dashboard')
                ->with('error', '⛔ Akses ditolak. Halaman ini hanya untuk Admin.');
        }

        return $next($request);
    }
}
