<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Article; // Pastikan Model Article di-import
use App\Models\Notification;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // 1. DATA STATISTIK (Dinamis dari Database)
        $totalKarya = Article::where('user_id', $userId)->count();
        $totalDownloadUser = Article::where('user_id', $userId)->sum('downloads');
        $totalViewsUser = Article::where('user_id', $userId)->sum('views');

        // 2. DATA NOTIFIKASI (Untuk Lonceng di Topbar)
        // Ambil 5 notifikasi terbaru milik user ini
        $notifications = Notification::where('user_id', $userId)
            ->latest()
            ->limit(5)
            ->get();

        // Hitung jumlah notifikasi yang BELUM dibaca untuk tanda merah di lonceng
        $unreadCount = Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();

        // 3. AKTIVITAS TERBARU (Data 5 karya terakhir milik user)
        $aktivitasTerbaru = Article::where('user_id', $userId)
            ->latest()
            ->limit(5)
            ->get();

        // Kirim semua variabel ke view user.dashboard
        return view('user.dashboard', compact(
            'totalKarya',
            'totalDownloadUser',
            'totalViewsUser',
            'notifications',
            'unreadCount',
            'aktivitasTerbaru'
        ));
    }

    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', auth()->id())->findOrFail($id);
        $notification->update(['is_read' => true]);

        return redirect()->back();
    }

    public function profile()
    {
        $user = auth()->user();

        return view('user.profile', compact('user'));
    }

    public function profileUpdate(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            // Sesuaikan dengan kolom di Navicat: identity_number
            'identity_number' => 'required|string|max:50|unique:users,identity_number,'.$user->id,
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|min:6',
        ]);

        // Update data sesuai nama kolom di database
        $user->name = $request->nama_lengkap;
        $user->identity_number = $request->identity_number;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Profil Anda berhasil diperbarui!');
    }
}
