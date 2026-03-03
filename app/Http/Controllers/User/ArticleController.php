<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function create()
    {
        $userId = auth()->id();

        // Ambil data notifikasi agar topbar tidak error
        $notifications = Notification::where('user_id', $userId)->latest()->limit(5)->get();
        $unreadCount = Notification::where('user_id', $userId)->where('is_read', false)->count();

        return view('user.upload', compact('notifications', 'unreadCount'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'pdf_file' => 'required|mimes:pdf|max:20480',
            'cover_image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'title' => 'required|string|max:255',
            'author' => 'required|string',
            'abstract' => 'required',
            'study_program' => 'required',
            'year' => 'required|digits:4',
            'document_type' => 'required',
            'access_type' => 'required|in:Fulltext,Abstrak',
        ]);

        // 2. Simpan File ke Storage
        $pdfPath = $request->file('pdf_file')->store('articles/pdf', 'public');

        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('articles/covers', 'public');
        }

        // 3. Simpan ke Database
        $article = \App\Models\Article::create([
            'user_id' => auth()->id(), // Pastikan user harus login
            'title' => $request->title,
            'author' => $request->author,
            'abstract' => $request->abstract,
            'keywords' => $request->keywords ?? '-',
            'study_program' => $request->study_program,
            'year' => $request->year,
            'document_type' => $request->document_type,
            'pembimbing_1' => $request->pembimbing_1,
            'pembimbing_2' => $request->pembimbing_2,
            'accreditation_level' => $request->tingkat_akreditasi,
            'access_type' => $request->access_type,
            'pdf_file' => $pdfPath,
            'cover_image' => $coverPath,
            'status' => 'pending',
        ]);

        // --- LOGIKA NOTIFIKASI UNTUK ADMIN ---
        // Cari semua user yang rolenya admin atau superadmin
        $admins = \App\Models\User::whereIn('role', ['admin', 'superadmin'])->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Dokumen Baru Menunggu Validasi 📄',
                'message' => 'User '.auth()->user()->name." mengunggah karya ilmiah baru: '".Str::limit($article->title, 50)."'",
                'status' => 'Pending',
                'is_read' => false,
            ]);
        }
        // --- END LOGIKA NOTIFIKASI ---

        // Catat Log Aktivitas User
        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'description' => 'Mengunggah Karya Ilmiah baru: '.$article->title,
        ]);

        return redirect()->route('user.dashboard')->with('success', 'Karya ilmiah berhasil diunggah dan sedang menunggu verifikasi admin.');
    }

    public function history()
    {
        // Mengambil semua data milik user login, diurutkan dari yang terbaru
        $articles = \App\Models\Article::where('user_id', auth()->id() ?? 1)
            ->latest()
            ->get();

        return view('user.history', compact('articles'));
    }

    public function edit($id)
    {
        $article = \App\Models\Article::where('user_id', auth()->id())->findOrFail($id);

        // dd(auth()->id());
        return view('user.revisi', compact('article'));
    }

    public function update(Request $request, $id)
    {
        // 1. Cari artikel milik user yang sedang login
        $article = \App\Models\Article::where('user_id', auth()->id())->findOrFail($id);

        // 2. Validasi input
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'abstract' => 'required|string',
            'keywords' => 'required|string',
            'year' => 'required|numeric',
            'file_utama' => 'nullable|mimes:pdf|max:10240', // Max 10MB
            'file_sampul' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // 3. Siapkan data untuk diupdate
        $data = [
            'title' => $request->title,
            'author' => $request->author,
            'abstract' => $request->abstract,
            'keywords' => $request->keywords,
            'year' => $request->year,
            'study_program' => $request->study_program,
            'document_type' => $request->document_type,
            'pesan_revisi_user' => $request->pesan_revisi_user,
            // Gunakan input baru, jika kosong pakai data lama agar tidak NULL
            'access_type' => $request->access_type ?? $article->access_type,
            'status' => 'pending',
        ];

        // 4. Cek jika ada unggahan File Utama baru
        if ($request->hasFile('file_utama')) {
            // Hapus file lama dari storage agar hemat ruang
            if ($article->file_utama) {
                Storage::delete('public/'.$article->file_utama);
            }
            // Simpan file baru
            $data['file_utama'] = $request->file('file_utama')->store('documents', 'public');
        }

        // 5. Cek jika ada unggahan Sampul baru
        if ($request->hasFile('file_sampul')) {
            // Hapus sampul lama
            if ($article->file_sampul) {
                Storage::delete('public/'.$article->file_sampul);
            }
            // Simpan sampul baru
            $data['file_sampul'] = $request->file('file_sampul')->store('covers', 'public');
        }

        // 6. Jalankan Update
        $article->update($data);
        ActivityLog::create([
            'user_id' => auth()->id(),
            'description' => 'Melakukan revisi pada karya ilmiah: "'.$article->title.'"',
        ]);

        return redirect()->route('user.article.history')->with('success', 'Revisi berhasil dikirim. Menunggu verifikasi ulang oleh Admin.');
    }
}
