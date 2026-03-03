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
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Debug: Cek apa isi role user saat ini (opsional, hapus jika sudah jalan)
        // dd($request->user()->role);

        if (! $request->user() || ! in_array($request->user()->role, $roles)) {
            abort(403, 'ANDA TIDAK MEMILIKI HAK AKSES UNTUK HALAMAN INI.');
        }

        return $next($request);
    }
}
