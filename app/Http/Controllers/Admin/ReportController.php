<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.report.index');
    }

    public function generate(Request $request)
    {
        // 1. Validasi Input agar tidak ada tanggal yang kosong
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // 2. Query dasar: Menampilkan data yang disetujui (Published)
        // atau yang sedang dalam tahap Revisi sesuai permintaan di form
        $query = Article::query();

        // 3. Filter Berdasarkan Rentang Waktu (updated_at)
        // Kita gunakan updated_at karena itu mencerminkan tanggal verifikasi terakhir
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('updated_at', [
                $request->start_date.' 00:00:00',
                $request->end_date.' 23:59:59',
            ]);
        }

        // 4. Filter Kategori Dokumen (Skripsi, Tesis, dsb)
        if ($request->kategori && $request->kategori != 'all') {
            $query->where('document_type', $request->kategori);
        }

        // 5. Filter Program Kekhususan (Prodi)
        if ($request->kekhususan && $request->kekhususan != 'all') {
            $query->where('study_program', $request->kekhususan);
        }

        // 6. Ambil data beserta relasi user (pengunggah)
        $results = $query->with('user')->orderBy('updated_at', 'asc')->get();

        // 7. Siapkan variabel informasi filter untuk ditampilkan di header PDF
        $filter = [
            'start_date' => date('d/m/Y', strtotime($request->start_date)),
            'end_date' => date('d/m/Y', strtotime($request->end_date)),
            'kategori' => $request->kategori,
            'kekhususan' => $request->kekhususan,
        ];

        // 8. Generate PDF dengan format Landscape (A4)
        $pdf = Pdf::loadView('admin.report.result_pdf', compact('results', 'filter'))
            ->setPaper('a4', 'landscape');

        $fileName = 'Laporan_Repositori_'.now()->format('Ymd_His').'.pdf';

        // Gunakan stream agar admin bisa melihat dulu di browser sebelum simpan
        return $pdf->stream($fileName);
    }
}
