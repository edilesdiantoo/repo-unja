<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // Fungsi untuk menampilkan halaman filter dan tabel preview
    public function index(Request $request)
    {
        // Ambil semua data (Query Builder dasar)
        $query = Article::query();

        // 1. Filter Rentang Waktu
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date.' 00:00:00',
                $request->end_date.' 23:59:59',
            ]);
        }

        // 2. Filter Jenis Dokumen (Sesuai Navicat: document_type)
        if ($request->filled('kategori') && $request->kategori != 'all') {
            $query->where('document_type', $request->kategori);
        }

        // 3. Filter Program Studi (Sesuai Navicat: study_program)
        if ($request->filled('kekhususan') && $request->kekhususan != 'all') {
            $query->where('study_program', $request->kekhususan);
        }

        // 4. Filter Status (Tambahkan ini agar tabel sinkron dengan PDF)
        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Ambil hasil dengan pagination (10 data per halaman)
        $articles = $query->latest()->paginate(10)->withQueryString();

        return view('admin.report.index', compact('articles'));
    }

    // Fungsi untuk cetak PDF
    public function generate(Request $request)
    {
        $query = Article::query();

        // Filter yang sama persis dengan fungsi index
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date.' 00:00:00',
                $request->end_date.' 23:59:59',
            ]);
        }

        if ($request->filled('kategori') && $request->kategori != 'all') {
            $query->where('document_type', $request->kategori);
        }

        if ($request->filled('kekhususan') && $request->kekhususan != 'all') {
            $query->where('study_program', $request->kekhususan);
        }

        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Ambil data beserta relasi user pengunggah
        $results = $query->with('user')->latest()->get();

        // Variabel untuk informasi filter di Header PDF
        $filter = [
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'kategori' => $request->kategori ?? 'all',
            'kekhususan' => $request->kekhususan ?? 'all',
            'status' => $request->status ?? 'all',
        ];

        // Generate PDF dengan format Landscape
        $pdf = Pdf::loadView('admin.report.result_pdf', compact('results', 'filter'))
            ->setPaper('a4', 'landscape');

        // Nama file unik berdasarkan waktu cetak
        $fileName = 'Laporan_Repositori_'.now()->format('Ymd_His').'.pdf';

        return $pdf->stream($fileName);
    }
}
