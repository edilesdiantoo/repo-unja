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
        $query = \App\Models\Article::query();

        // Filter Rentang Waktu
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date.' 00:00:00',
                $request->end_date.' 23:59:59',
            ]);
        }

        // Filter Jenis Dokumen (Sesuai Navicat: document_type)
        if ($request->filled('kategori') && $request->kategori != 'all') {
            $query->where('document_type', $request->kategori);
        }

        // Filter Program Studi (Sesuai Navicat: study_program)
        if ($request->filled('kekhususan') && $request->kekhususan != 'all') {
            $query->where('study_program', $request->kekhususan);
        }

        // Ambil hasil dengan pagination (10 data per halaman)
        $articles = $query->latest()->paginate(10)->withQueryString();

        return view('admin.report.index', compact('articles'));
    }

    // Fungsi untuk cetak PDF
    public function generate(Request $request)
    {
        $query = Article::query();

        // Filter yang sama dengan index
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

        $results = $query->with('user')->latest()->get();

        $filter = [
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'kategori' => $request->kategori,
            'kekhususan' => $request->kekhususan,
            'status' => $request->status,
        ];

        $pdf = Pdf::loadView('admin.report.result_pdf', compact('results', 'filter'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan_Repositori_'.now()->format('Ymd').'.pdf');
    }
}
