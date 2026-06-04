<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Menyebarkan data ke topbar admin dan user secara global
        View::composer(['layouts.partials.admin-topbar', 'layouts.partials.user-topbar'], function ($view) {

            // Default nilai agar tidak error Undefined di blade
            $notifications = collect();
            $unreadCount = 0;

            if (auth()->check()) {
                $user = auth()->user();

                // =========================================================================
                // 1. JIKA YANG LOGIN ADMIN ATAU SUPERADMIN (SOLUSI UTAMA EDI)
                // =========================================================================
                if ($user->role == 'admin' || $user->role == 'superadmin') {

                    // AMANKAN TARGET: Admin HANYA melihat notifikasi yang ditujukan untuk ID dirinya sendiri
                    // dan murni berstatus 'Pending' (pemberitahuan ajuan/revisi baru dari mahasiswa)
                    $notifications = \App\Models\Notification::where('user_id', $user->id)
                        ->where('status', 'Pending')
                        ->where('is_read', false)
                        ->latest()
                        ->limit(5)
                        ->get();

                    $unreadCount = \App\Models\Notification::where('user_id', $user->id)
                        ->where('status', 'Pending')
                        ->where('is_read', false)
                        ->count();

                    // =========================================================================
                    // 2. JIKA YANG LOGIN USER / MAHASISWA BIASA
                    // =========================================================================
                } else {
                    // Mahasiswa hanya mengambil notifikasi keputusan yang tertuju untuk ID dirinya sendiri
                    $notifications = \App\Models\Notification::where('user_id', $user->id)
                        ->where('is_read', false)
                        ->latest()
                        ->limit(5)
                        ->get();

                    $unreadCount = \App\Models\Notification::where('user_id', $user->id)
                        ->where('is_read', false)
                        ->count();
                }
            }

            // Kirim data ke file topbar html
            $view->with([
                'notifications' => $notifications,
                'unreadCount' => $unreadCount,
            ]);
        });
    }
}
