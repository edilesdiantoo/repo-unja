<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Article;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    // Helper internal untuk load data topbar agar DRY
    private function getTopbarData()
    {
        $userId = auth()->id();

        return [
            'notifications' => Notification::where('user_id', $userId)->latest()->limit(5)->get(),
            'unreadCount' => Notification::where('user_id', $userId)->where('is_read', false)->count(),
        ];
    }

    // =========================================================================
    // SUB MENU 1: UNGGAH KARYA ILMIAH (MULTI-STEP)
    // =========================================================================

    public function createStep1()
    {
        $topbar = $this->getTopbarData();

        return view('user.upload', [
            'notifications' => $topbar['notifications'],
            'unreadCount' => $topbar['unreadCount'],
        ]);
    }

    public function storeStep1(Request $request)
    {
        $request->validate([
            'pdf_file' => 'required|mimes:pdf|max:20480', // Max 20MB
            'cover_image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048', // Max 2MB
        ]);

        $pdfPath = $request->file('pdf_file')->store('articles/pdf', 'public');
        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('articles/covers', 'public');
        }

        $article = Article::create([
            'user_id' => auth()->id(),
            'pdf_file' => $pdfPath,
            'cover_image' => $coverPath,
            'status' => 'draft',
        ]);

        return redirect()->route('user.article.createStep2', $article->id)
            ->with('success', 'Berkas berhasil diunggah! Silakan lengkapi metadata di bawah ini.');
    }

    public function createStep2($id)
    {
        $article = Article::where('user_id', auth()->id())->findOrFail($id);
        $topbar = $this->getTopbarData();

        return view('user.metadata', [
            'article' => $article,
            'notifications' => $topbar['notifications'],
            'unreadCount' => $topbar['unreadCount'],
        ]);
    }

    public function storeStep2(Request $request, $id)
    {
        $article = Article::where('user_id', auth()->id())->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string',
            'abstract' => 'required',
            'study_program' => 'required',
            'year' => 'required|digits:4',
            'document_type' => 'required',
            'access_type' => 'required|in:Fulltext,Abstrak',
            'tingkat_akreditasi' => 'required_if:document_type,Jurnal|nullable|string|max:50',
        ], [
            'tingkat_akreditasi.required_if' => 'Tingkat akreditasi wajib dipilih khusus untuk jenis dokumen Jurnal.',
        ]);

        $article->update([
            'title' => $request->title,
            'author' => $request->author,
            'abstract' => $request->abstract,
            'keywords' => $request->keywords ?? '-',
            'study_program' => $request->study_program,
            'year' => $request->year,
            'document_type' => $request->document_type,
            'pembimbing_1' => $request->pembimbing_1,
            'pembimbing_2' => $request->pembimbing_2,
            'accreditation_level' => $request->document_type === 'Jurnal' ? $request->tingkat_akreditasi : null,
            'access_type' => $request->access_type,
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'description' => 'Menyimpan draf metadata karya ilmiah: '.$article->title,
        ]);

        // ALUR BARU: Langsung dialihkan ke halaman Pratinjau Detail Berkas
        return redirect()->route('user.article.previewVerification', $article->id)
            ->with('success', 'Metadata berhasil disimpan! Silakan periksa kembali detail dokumen Anda sebelum diajukan ke Admin.');
    }

    // =========================================================================
    // SUB MENU 2: STATUS JALUR VERIFIKASI DUA STATUS (DRAFT & PENDING)
    // =========================================================================

    public function statusVerifikasi()
    {
        // KUNCI FILTER: Hanya ambil data yang masih mentah (draft) atau yang dikembalikan admin (revision)
        $articles = Article::where('user_id', auth()->id())
            ->whereIn('status', ['draft', 'revision'])
            ->latest()
            ->get();

        $topbar = $this->getTopbarData();

        return view('user.status_verifikasi', [
            'articles' => $articles,
            'notifications' => $topbar['notifications'],
            'unreadCount' => $topbar['unreadCount'],
        ]);
    }

    public function previewVerification($id)
    {
        $article = Article::where('user_id', auth()->id())->findOrFail($id);
        $topbar = $this->getTopbarData();

        return view('user.preview_verifikasi', [
            'article' => $article,
            'notifications' => $topbar['notifications'],
            'unreadCount' => $topbar['unreadCount'],
        ]);
    }

    public function submitVerification($id)
    {
        $article = Article::where('user_id', auth()->id())->findOrFail($id);

        $article->update(['status' => 'pending']);

        // Kirim Notifikasi Awal ke Lonceng Seluruh Admin
        $admins = User::whereIn('role', ['admin', 'superadmin'])->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Dokumen Baru Menunggu Validasi 📄',
                'message' => 'User '.auth()->user()->name." mengajukan verifikasi karya ilmiah baru: '".Str::limit($article->title, 50)."'",
                'status' => 'Pending',
                'is_read' => false,
            ]);
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'description' => 'Mengajukan verifikasi karya ilmiah: '.$article->title,
        ]);

        return redirect()->route('user.article.statusVerifikasi')
            ->with('success', 'Karya ilmiah berhasil diajukan! Status saat ini berubah menjadi Menunggu Validasi.');
    }

    // =========================================================================
    // FITUR UTAMA REVISI 1 HALAMAN PENUH (DINAMIS & SINKRON)
    // =========================================================================

    public function history()
    {
        $articles = Article::where('user_id', auth()->id())->latest()->get();
        $topbar = $this->getTopbarData();

        return view('user.history', [
            'articles' => $articles,
            'notifications' => $topbar['notifications'],
            'unreadCount' => $topbar['unreadCount'],
        ]);
    }

    public function edit($id)
    {
        $article = Article::where('user_id', auth()->id())->findOrFail($id);
        $topbar = $this->getTopbarData();

        return view('user.revisi', [
            'article' => $article,
            'notifications' => $topbar['notifications'],
            'unreadCount' => $topbar['unreadCount'],
        ]);
    }

    public function update(Request $request, $id)
    {
        $article = Article::where('user_id', auth()->id())->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'abstract' => 'required|string',
            'keywords' => 'required|string',
            'year' => 'required|numeric',
            'pdf_file' => 'nullable|mimes:pdf|max:20480',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'tingkat_akreditasi' => 'required_if:document_type,Jurnal|nullable|string|max:50',
        ], [
            'tingkat_akreditasi.required_if' => 'Tingkat akreditasi wajib dipilih khusus untuk jenis dokumen Jurnal.',
        ]);

        // LOGIKA KONDISIONAL STATUS SINKRON:
        // Jika bermula dari revisi admin, kembalikan ke pending. Jika bermula dari draf, amankan tetap draf.
        $statusBaru = $article->status === 'revision' ? 'pending' : $article->status;

        $data = [
            'title' => $request->title,
            'author' => $request->author,
            'abstract' => $request->abstract,
            'keywords' => $request->keywords,
            'year' => $request->year,
            'study_program' => $request->study_program,
            'document_type' => $request->document_type,
            'pesan_revisi_user' => $request->pesan_revisi_user,
            'accreditation_level' => $request->document_type === 'Jurnal' ? $request->tingkat_akreditasi : null,
            'access_type' => $request->access_type ?? $article->access_type,
            'status' => $statusBaru,
        ];

        if ($request->hasFile('pdf_file')) {
            if ($article->pdf_file) {
                Storage::disk('public')->delete($article->pdf_file);
            }
            $data['pdf_file'] = $request->file('pdf_file')->store('articles/pdf', 'public');
        }

        if ($request->hasFile('cover_image')) {
            if ($article->cover_image) {
                Storage::disk('public')->delete($article->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('articles/covers', 'public');
        }

        $article->update($data);

        // PICU NOTIFIKASI BALASAN JIKA MAHASISWA SUBMIT REVISI KE MEJA ADMIN
        if ($statusBaru === 'pending') {
            $admins = User::whereIn('role', ['admin', 'superadmin'])->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => 'Berkas Revisi Telah Diperbarui 🔄',
                    'message' => 'User '.auth()->user()->name." telah mengirimkan perbaikan dokumen untuk judul: '".Str::limit($article->title, 50)."'",
                    'status' => 'Pending',
                    'is_read' => false,
                ]);
            }
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'description' => 'Melakukan pembaruan data pada karya ilmiah: "'.$article->title.'" [Status: '.$statusBaru.']',
        ]);

        // JALUR REDIRECT PINTAR
        if ($article->status === 'draft') {
            return redirect()->route('user.article.previewVerification', $article->id)
                ->with('success', 'Data draf berhasil diperbarui! Silakan periksa kembali pratinjau Anda.');
        }

        return redirect()->route('user.article.history')
            ->with('success', 'Revisi berhasil dikirim. Menunggu verifikasi ulang oleh Admin.');
    }
}
