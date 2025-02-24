<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles)
{
    if (!auth()->check()) {
        return redirect()->route('login'); // Jika belum login, kembali ke login
    }

    $user = auth()->user();

    if (in_array($user->role, $roles)) {
        return $next($request);
    }

    // Jika kasir mencoba akses halaman pengguna, kembali ke dashboard
    if ($user->role === 'kasir') {
        return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
    }

    // Jika pengguna biasa mencoba akses halaman kasir, kembali ke login
    return redirect()->route('login')->with('error', 'Silakan login kembali.');
}

    
    
}
