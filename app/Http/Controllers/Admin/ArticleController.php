<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Article;
use App\Models\Notification;
use Illuminate\Http\Request;

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
            'user_id' => $article->user_id, // Ditargetkan langsung ke akun mahasiswa
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
}
