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
        // PERBAIKAN: Key 'Laporan' disamakan menjadi 'Laporan Magang' agar seragam dengan data series
        $chartData = [
            'Skripsi' => \App\Models\Article::where('document_type', 'Skripsi')->count(),
            'Tesis' => \App\Models\Article::where('document_type', 'Tesis')->count(),
            'Disertasi' => \App\Models\Article::where('document_type', 'Disertasi')->count(),
            'Jurnal' => \App\Models\Article::where('document_type', 'Jurnal')->count(),
            'Laporan Magang' => \App\Models\Article::where('document_type', 'Laporan Magang')->count(),
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

    /**
     * SINKRONISASI BARU: Redirect Pintar Berdasarkan Status Notifikasi
     */
    public function markAsRead($id)
    {
        $notification = \App\Models\Notification::findOrFail($id);

        // 1. Ubah status data menjadi terbaca agar badge angka merah berkurang secara realtime
        $notification->update(['is_read' => true]);

        $statusNotif = strtolower($notification->status);
        $message = $notification->message;
        $article = null;

        // 2. LOGIKA PINTAR BREAKDOWN STRING JUDUL (Membaca teks di dalam tanda kutip tunggal dari pesan notifikasi)
        // Contoh pesan: User Edi Lesdianto mengajukan verifikasi karya ilmiah baru: 'Strategi Penanganan...'
        if (preg_match("/'([^']+)'/", $message, $matches)) {
            $extractedTitle = $matches[1];

            // Cari artikel di database yang judulnya mirip/mengandung potongan string tersebut
            $article = \App\Models\Article::where('title', 'LIKE', '%'.$extractedTitle.'%')
                ->when($statusNotif == 'pending', function ($query) {
                    return $query->where('status', 'pending');
                })
                ->when($statusNotif == 'verified', function ($query) {
                    return $query->where('status', 'verified');
                })
                ->latest()
                ->first();
        }

        // 3. EKSEKUSI REDIRECT LANGSUNG KE HALAMAN FORM DETAIL (Sesuai Gambar 2)
        if ($article) {
            // Jika status pending, langsung tembak masuk ke halaman formulir Verifikasi Berkas
            if ($statusNotif == 'pending') {
                return redirect()->route('admin.repository.verify', $article->id);
            }

            // Jika status verified, langsung tembak masuk ke halaman formulir Konfirmasi Publikasi Final
            if ($statusNotif == 'verified') {
                return redirect()->route('admin.repository.detailPublikasi', $article->id);
            }
        }

        // Fallback: Jika judul unik tidak terbaca di string, oper ke halaman list tabel utamanya
        if ($statusNotif == 'pending') {
            return redirect()->route('admin.repository.pending');
        } elseif ($statusNotif == 'verified') {
            return redirect()->route('admin.repository.publikasi');
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
            'identity_number' => 'required|string|max:50|unique:users,identity_number,'.$user->id,
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|min:6',
        ]);

        // Proses Simpan ke Database
        $user->name = $request->nama_lengkap;
        $user->identity_number = $request->identity_number;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}
