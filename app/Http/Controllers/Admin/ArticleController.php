<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Menampilkan daftar karya ilmiah yang butuh verifikasi
     */
    public function pending()
    {
        // Mengambil data dengan status selain 'published'
        $articles = Article::where('status', '!=', 'published')
            ->latest()
            ->get();

        return view('admin.repository.pending', compact('articles'));
    }

    /**
     * Menampilkan daftar semua karya ilmiah (Data Koleksi)
     */
    public function index()
    {
        $articles = Article::latest()->get();

        return view('admin.repository.index', compact('articles'));
    }

    /**
     * Method untuk memproses verifikasi (Akan kita buat detailnya nanti)
     */
    public function verify($id)
    {
        $article = Article::findOrFail($id);

        return view('admin.repository.verify', compact('article'));
    }

    public function updateStatus(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $request->validate([
            'status' => 'required|in:published,revision,rejected',
            'catatan_revisi' => 'required_if:status,revision,rejected|nullable|string|min:5',
        ], [
            'catatan_revisi.required_if' => 'Mohon berikan alasan/catatan mengapa dokumen ini perlu direvisi atau ditolak.',
        ]);

        // Simpan status lama untuk pengecekan (opsional)
        $oldStatus = $article->status;

        $article->update([
            'status' => $request->status,
            'catatan_revisi' => $request->catatan_revisi,
            'verified_by' => auth()->id(),
        ]);

        // --- LOGIKA NOTIFIKASI OTOMATIS ---
        $title = '';
        $message = '';

        switch ($request->status) {
            case 'published':
                $title = 'Publikasi Disetujui! 🎉';
                $message = "Selamat! Karya ilmiah Anda berjudul '".$article->title."' telah resmi dipublikasikan.";
                break;
            case 'revision':
                $title = 'Butuh Revisi ✍️';
                $message = "Karya ilmiah '".$article->title."' memerlukan perbaikan. Catatan Admin: ".$request->catatan_revisi;
                break;
            case 'rejected':
                $title = 'Publikasi Ditolak ❌';
                $message = "Mohon maaf, karya ilmiah '".$article->title."' ditolak. Alasan: ".$request->catatan_revisi;
                break;
        }

        if ($title !== '') {
            \App\Models\Notification::create([
                'user_id' => $article->user_id, // Mengirim ke penulis karya
                'title' => $title,
                'message' => $message,
                'status' => ucfirst($request->status),
                'is_read' => false,
            ]);
        }
        // --- END LOGIKA NOTIFIKASI ---

        $statusMsg = [
            'published' => 'Karya ilmiah berhasil disetujui dan dipublikasikan.',
            'revision' => 'Status berhasil diubah menjadi Revisi.',
            'rejected' => 'Karya ilmiah telah ditolak.',
        ];

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'description' => 'Melakukan Verifikasi Dokumen ID: #'.$id.' dengan hasil: '.$request->status,
        ]);

        return redirect()->route('admin.repository.pending')->with('success', $statusMsg[$request->status]);
    }

    public function show($id)
    {
        $article = Article::with('user')->findOrFail($id);

        return view('admin.repository.show', compact('article'));
    }
}
