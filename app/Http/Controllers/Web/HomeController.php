<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Article;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Mengambil 5 Karya Ilmiah terbaru yang sudah disetujui (published)
        $latestArticles = Article::with('user')
            ->where('status', 'published')
            ->latest()
            ->take(5)
            ->get();

        // 2. Menghitung Statistik secara Dinamis
        $stats = [
            'total_koleksi' => Article::where('status', 'published')->count(),
            'total_unduhan' => Article::sum('downloads'),
            'total_pengunjung' => ActivityLog::where('description', 'like', '%Login%')
                ->distinct('user_id')
                ->count(),
            'total_views' => Article::sum('views'),
        ];

        return view('web.home', compact('latestArticles', 'stats'));
    }

    public function about()
    {
        return view('web.about');
    }

    public function browse(Request $request)
    {
        $results = Article::where('status', 'published')
            ->when($request->q, function ($q) use ($request) {
                return $q->where('title', 'like', "%{$request->q}%");
            })
            ->when($request->field, function ($q) use ($request) {
                return $q->where('study_program', $request->field);
            })
            ->when($request->category, function ($q) use ($request) {
                return $q->where('document_type', $request->category);
            })
            ->when($request->year, function ($q) use ($request) {
                return $q->where('year', $request->year);
            })
            // 1. FILTER TAMBAHAN: JIKA KLIK KATA KUNCI
            ->when($request->keyword, function ($q) use ($request) {
                return $q->where('keywords', 'like', "%{$request->keyword}%");
            })
            // 2. FILTER TAMBAHAN: JIKA KLIK NAMA DOSEN (PEMBIMBIMBING 1 atau 2)
            ->when($request->dosen, function ($q) use ($request) {
                return $q->where(function ($query) use ($request) {
                    $query->where('pembimbing_1', 'like', "%{$request->dosen}%")
                        ->orWhere('pembimbing_2', 'like', "%{$request->dosen}%");
                });
            })
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('web.browse', compact('results'));
    }

    public function show($id)
    {
        $article = Article::findOrFail($id);

        // Menambah jumlah 'dilihat' setiap detail halaman diakses
        $article->increment('views');

        return view('web.article_detail', compact('article'));
    }

    public function download($id)
    {
        $article = Article::findOrFail($id);
        $article->increment('downloads');

        // Sesuai storeStep1 tadi, file disimpan di 'articles/pdf/namafile.pdf' di dalam disk 'public'
        $filePath = storage_path('app/public/'.$article->pdf_file);

        if (! file_exists($filePath)) {
            abort(404, 'File PDF tidak ditemukan di server.');
        }

        return response()->download($filePath);
    }

    public function policy()
    {
        return view('web.policy');
    }

    public function viewArticle($id)
    {
        $article = Article::findOrFail($id);

        $article->increment('views'); // Lebih cocok increment views saat dibuka preview-nya

        $path = storage_path('app/public/'.$article->pdf_file);

        if (! file_exists($path)) {
            abort(404, 'File PDF tidak ditemukan di server.');
        }

        // Membuka PDF langsung di browser (PDF Viewer bawaan)
        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$article->title.'.pdf"',
        ]);
    }
}
