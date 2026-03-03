<?php

namespace App\Providers;

// Tambahkan ini
// Tambahkan ini
use Illuminate\Support\ServiceProvider; // Tambahkan ini

class AppServiceProvider extends ServiceProvider
{
    // File: app/Providers/AppServiceProvider.php

    // File: app/Providers/AppServiceProvider.php

    public function boot()
    {
        // Gunakan nama file sesuai sidebar VS Code Anda: admin-topbar (pakai strip)
        view()->composer(['layouts.partials.admin-topbar', 'layouts.partials.user-topbar'], function ($view) {

            // Default nilai agar tidak error Undefined
            $notifications = collect();
            $unreadCount = 0;

            if (auth()->check()) {
                $user = auth()->user();

                // Jika yang login ADMIN atau SUPERADMIN
                if ($user->role == 'admin' || $user->role == 'superadmin') {
                    // Ambil semua notifikasi yang statusnya 'Pending' (antrean baru)
                    $notifications = \App\Models\Notification::where('status', 'Pending')
                        ->latest()->limit(5)->get();
                    $unreadCount = \App\Models\Notification::where('status', 'Pending')
                        ->where('is_read', false)->count();
                } else {
                    // Jika USER biasa, hanya ambil milik sendiri
                    $notifications = \App\Models\Notification::where('user_id', $user->id)
                        ->latest()->limit(5)->get();
                    $unreadCount = \App\Models\Notification::where('user_id', $user->id)
                        ->where('is_read', false)->count();
                }
            }

            $view->with([
                'notifications' => $notifications,
                'unreadCount' => $unreadCount,
            ]);
        });
    }
}
