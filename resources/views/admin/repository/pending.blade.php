@extends('layouts.admin')

@section('content')
    <div class="card shadow rounded-4 border-0">
        <div class="card-body p-4">
            <div class="row align-items-center g-4 mb-4">
                <div class="col-6">
                    <h4 class="fw-bold text-dark"><i class="fas fa-clock text-warning me-2"></i>Menunggu Verifikasi</h4>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    {{-- SOLUSI TOTAL (EDI): Jika data kosong, jangan render tag <table> agar DataTables tidak eror --}}
                    @if ($articles->isEmpty())
                        <div class="text-center py-5 text-muted fw-semibold border rounded-3 bg-light">
                            <i class="fas fa-folder-open fa-3x mb-3 opacity-25 d-block text-secondary"></i>
                            <h5 class="fw-bold text-secondary mb-1">Tidak Ada Antrean</h5>
                            <p class="small mb-0 text-muted">Saat ini tidak ada karya ilmiah mahasiswa yang menunggu proses
                                verifikasi.</p>
                        </div>
                    @else
                        {{-- Jika ada data, baru render tabel beserta DataTables-nya --}}
                        <div class="table-responsive">
                            <table id="example" class="table table-hover align-middle border">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 50px;">No</th>
                                        <th>Jenis</th>
                                        <th>Upload</th>
                                        <th>Publikasi</th>
                                        <th>Judul Karya Ilmiah</th>
                                        <th>Penulis</th>
                                        <th>Akses</th>
                                        <th>Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($articles as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                @php
                                                    $initial = substr($item->document_type, 0, 1);
                                                @endphp
                                                <span class="badge bg-danger rounded-circle p-2"
                                                    title="{{ $item->document_type }}"
                                                    style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                                    {{ $initial }}
                                                </span>
                                            </td>
                                            <td class="small text-muted">{{ $item->created_at->format('d M Y') }}</td>
                                            <td class="small text-muted">
                                                {{ $item->status == 'published' ? $item->updated_at->format('d M Y') : '-' }}
                                            </td>
                                            <td>
                                                @if ($item->accreditation_level)
                                                    <span
                                                        class="badge bg-info text-dark border-secondary mb-1 d-inline-block">
                                                        {{ $item->accreditation_level }}
                                                    </span>
                                                @endif
                                                <div class="small text-muted mb-1">
                                                    {{ $item->study_program }} • Hukum • {{ $item->year }}
                                                </div>
                                                <div class="fw-bold text-dark text-wrap" style="max-width: 500px;">
                                                    {{ $item->title }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $item->author }}</div>
                                            </td>
                                            <td>
                                                <span class="badge btn-outline-success border text-success px-2 py-1">
                                                    {{ $item->access_type }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($item->status == 'pending')
                                                    <span class="badge bg-success px-2 py-1.5"><i
                                                            class="fas fa-hourglass-half me-1"></i>Menunggu</span>
                                                @elseif($item->status == 'revision')
                                                    <span class="badge bg-warning text-dark px-2 py-1.5"><i
                                                            class="fas fa-tools me-1"></i>Revisi</span>
                                                @else
                                                    <span class="badge bg-danger px-2 py-1.5"><i
                                                            class="fas fa-times-circle me-1"></i>Ditolak</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.repository.verify', $item->id) }}"
                                                    class="btn btn-danger btn-sm fw-bold px-3 shadow-sm rounded-3">
                                                    <i class="fa fa-check-square me-1"></i> Verifikasi
                                                </a>
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
    </div>
@endsection

@push('scripts')
    <script>
        // Mencegah alert DataTables muncul di layar jika sewaktu-waktu ada eror struktur data
        if (typeof $.fn.dataTable !== 'undefined') {
            $.fn.dataTable.ext.errMode = 'none';
        }
    </script>
@endpush
