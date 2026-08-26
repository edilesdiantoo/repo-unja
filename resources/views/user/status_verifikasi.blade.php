@extends('layouts.user')

@section('title', 'Status Verifikasi Karya Ilmiah')

@section('content')
    <div class="container-fluid">
        <div class="card shadow rounded-4 border-0">
            <div class="card-body p-4">
                <div class="row align-items-center g-4 mb-4">
                    <div class="col-12">
                        <h4 class="fw-bold text-dark">
                            <i class="fas fa-tasks text-danger me-2"></i> Status Jalur Verifikasi Berkas
                        </h4>
                        <p class="text-muted small mb-0">Pantau proses pengajuan dokumen Anda di bawah ini. Berkas berstatus
                            <strong>Draft</strong> atau <strong>Butuh Revisi</strong> memerlukan tindakan perbaikan sebelum
                            diperiksa ulang oleh Admin.
                        </p>
                    </div>
                </div>

                @if ($articles->isEmpty())
                    {{-- Tampilan jika data kosong --}}
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i>
                        <h5 class="fw-bold">Tidak Ada Antrean Berkas</h5>
                        <p class="small mb-0">Anda tidak memiliki draf aktif atau dokumen yang membutuhkan revisi saat ini.
                        </p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle border">
                            <thead class="bg-light">
                                <tr>
                                    <th>Judul / Penulis & Identitas / Prodi</th>
                                    <th>Jenis Dokumen</th>
                                    <th>Status Pemeriksaan</th>
                                    <th class="text-center">Aksi / Manajemen</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($articles as $item)
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $item->title ?? 'Judul Belum Diisi (Draft)' }}
                                            </div>

                                            <div class="mt-1">
                                                <small class="text-muted">Penulis:
                                                    <strong>{{ $item->author ?? '-' }}</strong></small>

                                                {{-- Menampilkan NIM / NIDN --}}
                                                <span class="badge bg-light text-secondary border px-2 py-0.5 ms-1">
                                                    <i class="far fa-id-card me-1 text-danger"></i>
                                                    {{ $item->user->identity_number ?? auth()->user()->identity_number }}
                                                </span>

                                                <small class="text-muted ms-1">| Prodi:
                                                    {{ $item->study_program ?? '-' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-light text-dark border">{{ $item->document_type ?? 'Belum Set' }}</span>
                                        </td>
                                        <td>
                                            @if ($item->status == 'revision')
                                                <span
                                                    class="badge bg-warning text-dark px-3 py-1.5 rounded-2 shadow-sm fw-bold">
                                                    <i class="fas fa-exclamation-triangle me-1"></i> Butuh Revisi
                                                </span>
                                            @else
                                                <span class="badge bg-secondary text-white px-3 py-1.5 rounded-2 shadow-sm">
                                                    <i class="fas fa-edit me-1"></i> Draft
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($item->status == 'revision')
                                                <a href="{{ route('user.article.edit', $item->id) }}"
                                                    class="btn btn-warning btn-sm px-3 rounded-3 fw-bold text-dark shadow-sm">
                                                    <i class="fas fa-tools me-1"></i> Perbaiki Data
                                                </a>
                                            @else
                                                <a href="{{ route('user.article.previewVerification', $item->id) }}"
                                                    class="btn btn-danger btn-sm px-3 rounded-3 fw-bold shadow-sm">
                                                    <i class="fas fa-arrow-right me-1"></i> Periksa Detail
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // DataTables mode silent
        if (typeof $.fn.dataTable !== 'undefined') {
            $.fn.dataTable.ext.errMode = 'none';
        }
    </script>
@endpush
