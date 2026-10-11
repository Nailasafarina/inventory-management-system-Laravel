<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Memeriksa apakah user yang sedang login memiliki salah satu role yang diizinkan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // 1. Pastikan pengguna sudah terotentikasi
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan masuk terlebih dahulu untuk mengakses sistem.');
        }

        $user = Auth::user();

        // 2. Jika tidak ada parameter role yang ditentukan, izinkan lewat
        if (empty($roles)) {
            return $next($request);
        }

        // 3. Periksa kecocokan role pengguna
        if (!in_array($user->role, $roles, true)) {
            abort(403, 'Akses Ditolak: Anda tidak memiliki hak akses (role: ' . $user->role . ') untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
