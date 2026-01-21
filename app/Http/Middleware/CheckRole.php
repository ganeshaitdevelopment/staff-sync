<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
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
        // 1. Ambil Role User yang sedang login
        $userRole = auth()->user()->role;

        // 2. Cek apakah role user ada di dalam daftar role yang diizinkan?
        // Contoh pemakaian di route nanti: middleware('role:administrator,supervisor')
        if (in_array($userRole, $roles)) {
            return $next($request); // Silakan masuk
        }

        // 3. Kalau tidak boleh, lempar ke Dashboard atau tampilkan error
        abort(403, 'Unauthorized action. You do not have access to this page.');
    }
}