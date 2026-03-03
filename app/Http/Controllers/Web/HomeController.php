<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog; // Pastikan model ini sudah ada
use App\Models\Article; // Untuk menghitung pengunjung/unduhan jika ada
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Mengambil 5 Karya Ilmiah terbaru yang sudah disetujui (published)
        $latestArticles = Article::with('user') // Eager loading user agar tidak berat
            ->where('status', 'published')
            ->latest()
            ->take(5)
            ->get();

        // 2. Menghitung Statistik secara Dinamis
        $stats = [
            'total_koleksi' => Article::where('status', 'published')->count(),
            // Contoh menghitung log unduhan (sesuaikan dengan deskripsi log kamu)
            'total_unduhan' => Article::sum('downloads'), // Menghitung total unduhan asli dari DB            // Menghitung log login unik sebagai representasi pengunjung
            'total_pengunjung' => ActivityLog::where('description', 'like', '%Login%')
                ->distinct('user_id')
                ->count(),
            'total_views' => Article::sum('views'), // Menghitung total dilihat
        ];

        return view('web.home', compact('latestArticles', 'stats'));
    }

    public function about()
    {
        return view('web.about');
    }

    public function browse(Request $request)
    {
        $results = Article::where('status', 'published') // Default hanya menampilkan yang published untuk publik
            ->when($request->q, function ($q) use ($request) {
                return $q->where('title', 'like', "%{$request->q}%");
            })
            ->when($request->field, function ($q) use ($request) {
                return $q->where('field', $request->field);
            })
            ->when($request->category, function ($q) use ($request) {
                return $q->where('category', $request->category);
            })
            // Filter baru: Kategori (Dosen/Mahasiswa)
            ->when($request->user_type, function ($q) use ($request) {
                return $q->where('user_type', $request->user_type);
            })
            // Filter baru: Status (Hanya jika admin yang akses, jika umum tetap published)
            ->when($request->status, function ($q) use ($request) {
                return $q->where('status', $request->status);
            })
            ->latest()
            ->paginate(5);

        return view('web.browse', compact('results'));
    }

    public function show($id)
    {
        $article = Article::findOrFail($id);

        // Logika opsional: menambah jumlah 'dilihat' setiap halaman diakses
        $article->increment('views');

        return view('web.article_detail', compact('article'));
    }

    public function download($id)
    {
        $article = Article::findOrFail($id);
        $article->increment('downloads'); // Menambah +1 setiap diunduh

        $filePath = storage_path('app/public/'.$article->pdf_file);

        return response()->download($filePath);
    }

    public function policy()
    {
        return view('web.policy');
    }
}
