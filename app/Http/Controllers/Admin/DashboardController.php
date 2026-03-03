<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $adminId = auth()->id();

        // Statistik Ringkas (Semua Status)
        $stats = [
            'total_karya' => \App\Models\Article::count(), // Semua status (Pending/Publish/Revisi)
            'total_user' => \App\Models\User::count(),
            'total_download' => \App\Models\Article::sum('downloads'),
            'total_views' => \App\Models\Article::sum('views'),
        ];

        // Data untuk Chart Donut (Menghitung semua status per kategori)
        $chartData = [
            'Skripsi' => \App\Models\Article::where('document_type', 'Skripsi')->count(),
            'Tesis' => \App\Models\Article::where('document_type', 'Tesis')->count(),
            'Disertasi' => \App\Models\Article::where('document_type', 'Disertasi')->count(),
            'Jurnal' => \App\Models\Article::where('document_type', 'Jurnal')->count(),
            'Laporan' => \App\Models\Article::where('document_type', 'Laporan Magang')->count(),
        ];

        // Reset dan Buat Statistik Harian (7 Hari Terakhir) untuk Area Chart
        $days = collect();
        $dataSeries = [
            'Skripsi' => [], 'Tesis' => [], 'Disertasi' => [], 'Jurnal' => [], 'Laporan Magang' => [],
        ];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $days->push(now()->subDays($i)->isoFormat('ddd'));

            foreach ($dataSeries as $type => &$values) {
                // Menghitung berapa banyak yang diupload hari tersebut (Semua Status)
                $values[] = \App\Models\Article::where('document_type', $type)
                    ->whereDate('created_at', $date)
                    ->count();
            }
        }

        // Ambil aktivitas dari semua status agar admin tahu ada yang baru upload
        $aktivitas = \App\Models\Article::latest()->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'chartData', 'aktivitas', 'days', 'dataSeries'));
    }

    public function markAsRead($id)
    {
        $notification = \App\Models\Notification::findOrFail($id);

        // Tandai sudah dibaca
        $notification->update(['is_read' => true]);

        // Jika ini notifikasi dokumen pending, arahkan admin langsung ke halaman verifikasi
        if ($notification->status == 'Pending') {
            return redirect()->route('admin.repository.pending');
        }

        return redirect()->back()->with('success', 'Notifikasi telah dibaca.');
    }

    public function getChartData($range)
    {
        $days = collect();
        $dataSeries = [
            'Skripsi' => [], 'Tesis' => [], 'Disertasi' => [], 'Jurnal' => [], 'Laporan Magang' => [],
        ];

        if ($range == 'hari') {
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $days->push($date->isoFormat('ddd'));
                foreach ($dataSeries as $type => &$values) {
                    $values[] = \App\Models\Article::where('document_type', $type)->whereDate('created_at', $date->format('Y-m-d'))->count();
                }
            }
        } elseif ($range == 'bulan') {
            for ($i = 5; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $days->push($date->isoFormat('MMM'));
                foreach ($dataSeries as $type => &$values) {
                    $values[] = \App\Models\Article::where('document_type', $type)->whereMonth('created_at', $date->month)->whereYear('created_at', $date->year)->count();
                }
            }
        } elseif ($range == 'tahun') {
            for ($i = 4; $i >= 0; $i--) {
                $date = now()->subYears($i);
                $days->push($date->format('Y'));
                foreach ($dataSeries as $type => &$values) {
                    $values[] = \App\Models\Article::where('document_type', $type)->whereYear('created_at', $date->year)->count();
                }
            }
        }

        return response()->json([
            'categories' => $days,
            'series' => [
                ['name' => 'Skripsi', 'data' => $dataSeries['Skripsi']],
                ['name' => 'Tesis', 'data' => $dataSeries['Tesis']],
                ['name' => 'Disertasi', 'data' => $dataSeries['Disertasi']],
                ['name' => 'Jurnal', 'data' => $dataSeries['Jurnal']],
                ['name' => 'Laporan Magang', 'data' => $dataSeries['Laporan Magang']],
            ],
        ]);
    }

    public function profile()
    {
        $user = auth()->user();

        return view('admin.profile', compact('user'));
    }

    public function profileUpdate(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            // Ganti nomor_identitas menjadi identity_number sesuai Navicat
            'identity_number' => 'required|string|max:50|unique:users,identity_number,'.$user->id,
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|min:6',
        ]);

        // Proses Simpan ke Database
        $user->name = $request->nama_lengkap;
        $user->identity_number = $request->identity_number; // Sesuaikan di sini juga
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}
