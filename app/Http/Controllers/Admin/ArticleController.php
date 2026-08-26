<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Article;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    /**
     * SUB MENU 1: Menunggu Validasi (Status: Pending)
     */
    public function pending()
    {
        $articles = Article::where('status', 'pending')
            ->latest()
            ->get();

        return view('admin.repository.pending', compact('articles'));
    }

    /**
     * Tampilan Formulir Verifikasi Berkas Kelayakan
     */
    public function verify($id)
    {
        $article = Article::with('user')->findOrFail($id);

        return view('admin.repository.verify', compact('article'));
    }

    /**
     * PROSES KEPUTUSAN VALIDASI BERKAS (REVISI / VERIFIED / REJECTED)
     * SINKRONISASI NOTIFIKASI: Murni dikirim ke mahasiswa, tidak masuk lonceng admin!
     */
    public function updateStatus(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $request->validate([
            'status' => 'required|in:verified,revision,rejected',
            'catatan_revisi' => 'required_if:status,revision,rejected|nullable|string|min:5',
        ], [
            'catatan_revisi.required_if' => 'Mohon berikan alasan/catatan mengapa dokumen ini perlu direvisi atau ditolak.',
        ]);

        // Update data artikel mahasiswa di database
        $article->update([
            'status' => $request->status,
            'catatan_revisi' => $request->catatan_revisi,
            'verified_by' => auth()->id(),
        ]);

        $title = '';
        $message = '';

        // Tentukan template pesan teks berdasarkan tombol keputusan yang diklik admin
        switch ($request->status) {
            case 'verified':
                $title = 'Berkas Lolos Verifikasi! ✔️';
                $message = "Karya ilmiah Anda berjudul '".$article->title."' telah lolos verifikasi berkas dan masuk antrean rilis publikasi.";
                break;
            case 'revision':
                $title = 'Butuh Revisi ✍️';
                $message = "Karya ilmiah '".$article->title."' memerlukan beberapa perbaikan data. Catatan Validator: ".$request->catatan_revisi;
                break;
            case 'rejected':
                $title = 'Publikasi Ditolak ❌';
                $message = "Mohon maaf, karya ilmiah '".$article->title."' ditolak sistem. Alasan: ".$request->catatan_revisi;
                break;
        }

        // KUNCI TARGET UTAMA: Notifikasi dibuat murni menembak ke user_id mahasiswa pemilik berkas!
        if ($title !== '') {
            Notification::create([
                'user_id' => $article->user_id, // Hanya mendarat di akun mahasiswa terkait
                'title' => $title,
                'message' => $message,
                'status' => ucfirst($request->status), // Menyimpan string: Verified / Revision / Rejected
                'is_read' => false,
            ]);
        }

        $statusMsg = [
            'verified' => 'Karya ilmiah berhasil diverifikasi dan dipindahkan ke antrean publikasi.',
            'revision' => 'Status berhasil diubah menjadi Butuh Revisi.',
            'rejected' => 'Karya ilmiah telah ditolak sistem.',
        ];

        // Catat jejak audit di Activity Log admin
        ActivityLog::create([
            'user_id' => auth()->id(),
            'description' => 'Melakukan Verifikasi Dokumen ID: #'.$id.' dengan keputusan status: '.$request->status,
        ]);

        return redirect()->route('admin.repository.pending')->with('success', $statusMsg[$request->status]);
    }

    /**
     * =========================================================================
     * SUB MENU 2: RILIS PUBLIKASI KARYA ILMIAH (Status: Verified)
     * =========================================================================
     */
    public function publikasi()
    {
        $articles = Article::where('status', 'verified')
            ->latest()
            ->get();

        return view('admin.repository.publikasi', compact('articles'));
    }

    /**
     * Tampilan Detail Sebelum Klik Tombol Rilis Final
     */
    public function detailPublikasi($id)
    {
        $article = Article::with('user')->findOrFail($id);

        return view('admin.repository.verify_publikasi', compact('article'));
    }

    /**
     * PROSES RILIS FINAL PUBLIKASI KE REPOSITORI PUBLIC
     * SINKRONISASI NOTIFIKASI: Mengirim selamat ke mahasiswa, lonceng admin aman bersih!
     */
    public function konfirmasiPublikasi($id)
    {
        $article = Article::findOrFail($id);

        // Ubah status berkas menjadi published (Muncul di landing page utama repository)
        $article->update([
            'status' => 'published',
        ]);

        // NOTIFIKASI SELEBRASI: Hanya dikirim ke akun mahasiswa, tidak membebani lonceng admin
        Notification::create([
            'user_id' => $article->user_id,
            'title' => 'Publikasi Resmi Disetujui! 🎉',
            'message' => "Selamat! Karya ilmiah Anda yang berjudul '".$article->title."' telah resmi dipublikasikan dan saat ini sudah dapat diakses oleh publik.",
            'status' => 'Published',
            'is_read' => false,
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'description' => 'Resmi merilis dan mempublikasikan karya ilmiah ID: #'.$id.' ke publik luas.',
        ]);

        return redirect()->route('admin.repository.publikasi')->with('success', 'Karya ilmiah resmi dipublikasikan ke sistem!');
    }

    /**
     * =========================================================================
     * SUB MENU 3: ARSIP GLOBAL KOLEKSI DATA (Status: Published)
     * =========================================================================
     */
    public function index()
    {
        $articles = Article::where('status', 'published')
            ->latest()
            ->get();

        return view('admin.repository.index', compact('articles'));
    }

    /**
     * Lihat Review Detail Dokumen Arsip
     */
    public function show($id)
    {
        $article = Article::with('user')->findOrFail($id);

        return view('admin.repository.show', compact('article'));
    }

    /**
     * Menampilkan form edit karya ilmiah untuk Admin
     */
    public function edit($id)
    {
        $article = Article::findOrFail($id);

        return view('admin.repository.edit', compact('article'));
    }

    /**
     * Memproses update data karya ilmiah oleh Admin
     */
    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'abstract' => 'required|string',
            'keywords' => 'required|string|max:255',
            'study_program' => 'required|string',
            'year' => 'required|numeric',
            'document_type' => 'required|string',
            'access_type' => 'required|in:Fulltext,Abstrak',
            'pdf_file' => 'nullable|file|mimes:pdf|max:20480',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $data = [
            'title' => $request->title,
            'author' => $request->author,
            'abstract' => $request->abstract,
            'keywords' => $request->keywords,
            'study_program' => $request->study_program,
            'year' => $request->year,
            'document_type' => $request->document_type,
            'access_type' => $request->access_type,
            'pembimbing_1' => in_array($request->document_type, ['Skripsi', 'Tesis', 'Disertasi']) ? $request->pembimbing_1 : null,
            'pembimbing_2' => in_array($request->document_type, ['Skripsi', 'Tesis', 'Disertasi']) ? $request->pembimbing_2 : null,
            'accreditation_level' => $request->document_type === 'Jurnal' ? $request->tingkat_akreditasi : null,
        ];

        // Kelola file PDF baru jika diunggah
        if ($request->hasFile('pdf_file')) {
            if ($article->pdf_file && Storage::disk('public')->exists($article->pdf_file)) {
                Storage::disk('public')->delete($article->pdf_file);
            }
            $data['pdf_file'] = $request->file('pdf_file')->store('articles/pdf', 'public');
        }

        // Kelola Cover Image baru jika diunggah
        if ($request->hasFile('cover_image')) {
            if ($article->cover_image && Storage::disk('public')->exists($article->cover_image)) {
                Storage::disk('public')->delete($article->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('articles/covers', 'public');
        }

        $article->update($data);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'description' => 'Memperbarui metadata/berkas karya ilmiah ID: #'.$id.' ('.$article->title.')',
        ]);

        return redirect()->back()->with('success', 'Data karya ilmiah berhasil diperbarui oleh Admin.');
    }

    /**
     * Hapus Karya Ilmiah beserta Berkas Fisiknya
     */
    public function destroy($id)
    {
        $article = Article::findOrFail($id);

        if ($article->pdf_file && Storage::disk('public')->exists($article->pdf_file)) {
            Storage::disk('public')->delete($article->pdf_file);
        }
        if ($article->cover_image && Storage::disk('public')->exists($article->cover_image)) {
            Storage::disk('public')->delete($article->cover_image);
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'description' => 'Menghapus permanen karya ilmiah ID: #'.$id.' ('.$article->title.')',
        ]);

        $article->delete();

        return redirect()->back()->with('success', 'Karya ilmiah berhasil dihapus dari sistem.');
    }
}
