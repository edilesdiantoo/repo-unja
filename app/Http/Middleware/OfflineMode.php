<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OfflineMode
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Ambil nama host/domain yang sedang diakses
        $currentHost = $request->getHost();

        // 2. Daftar host lokal yang diizinkan untuk Offline Mode
        $allowedLocalHosts = [
            'localhost',
            '127.0.0.1',
            '::1',
            // '192.168.1.100' // <-- masukkan IP LAN server lokal konsumen jika ada
        ];

        // 3. Jika diakses di luar host lokal (misal di-upload ke hosting/domain internet)
        if (! in_array($currentHost, $allowedLocalHosts) || preg_match('/\.(com|net|org|id|co\.id|biz|xyz|io)$/i', $currentHost)) {

            // Berikan pesan error yang terkesan teknis/sistem lokal, bukan karena diblokir developer
            abort(403, 'Offline Mode Error: This application instance is configured for local network deployment only.');
        }

        return $next($request);
    }
}
