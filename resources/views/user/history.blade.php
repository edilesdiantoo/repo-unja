@extends('layouts.user')

@section('title', 'Riwayat Data Karya Ilmiah')

@section('content')
    <div class="card shadow rounded-4 border-0">
        <div class="card-body">
            <div class="row align-items-center g-4 mb-4">
                <div class="col-6">
                    <h4 class="fw-bold text-dark"><i class="fas fa-history text-danger me-2"></i>Data Karya Ilmiah</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="example" class="table table-hover align-middle border">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 50px;">No</th>
                                    <th>Jenis</th>
                                    <th>Tanggal</th>
                                    <th>Judul Karya Ilmiah</th>
                                    <th>Penulis & Identitas</th>
                                    <th>Akses</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($articles as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            @php
                                                $initial = substr($item->document_type, 0, 1);
                                                $color =
                                                    $item->document_type == 'Skripsi'
                                                        ? 'bg-primary'
                                                        : ($item->document_type == 'Tesis'
                                                            ? 'bg-danger'
                                                            : ($item->document_type == 'Disertasi'
                                                                ? 'bg-dark'
                                                                : 'bg-success'));
                                            @endphp
                                            <span class="badge {{ $color }} rounded-circle p-2"
                                                title="{{ $item->document_type }}"
                                                style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                                {{ $initial }}
                                            </span>
                                        </td>
                                        <td class="small text-muted">{{ $item->created_at->format('d M Y') }}</td>
                                        <td>
                                            <small class="text-muted d-block">{{ $item->study_program }} • Hukum •
                                                {{ $item->year }}</small>
                                            <div class="fw-bold text-dark">{{ $item->title ?? 'Judul Belum Diisi (Draft)' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $item->author ?? '-' }}</div>

                                            {{-- Menampilkan NIM / NIDN Penulis / Pengunggah --}}
                                            <span
                                                class="badge bg-light text-secondary border px-2 py-0.5 mt-1 d-inline-block">
                                                <i class="far fa-id-card me-1 text-danger"></i>
                                                {{ $item->user->identity_number ?? auth()->user()->identity_number }}
                                            </span>
                                        </td>
                                        <td>
                                            <span
                                                class="badge border {{ ($item->access_type ?? 'Fulltext') == 'Fulltext' ? 'text-success border-success' : 'text-primary border-primary' }} px-2 py-1">
                                                {{ $item->access_type ?? 'Fulltext' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($item->status == 'published')
                                                <span class="badge bg-primary px-2 py-1.5 rounded">Disetujui
                                                    (Publish)
                                                </span>
                                            @elseif($item->status == 'verified')
                                                <span class="badge bg-info text-white px-2 py-1.5 rounded">Lolos Validasi
                                                    (Antrean Rilis)</span>
                                            @elseif($item->status == 'pending')
                                                <span class="badge bg-success px-2 py-1.5 rounded">Menunggu
                                                    Verifikasi</span>
                                            @elseif($item->status == 'rejected')
                                                <span class="badge bg-danger px-2 py-1.5 rounded">Ditolak</span>
                                            @elseif($item->status == 'revision')
                                                <span class="badge bg-warning text-dark px-2 py-1.5 rounded">Butuh
                                                    Revisi</span>
                                            @elseif($item->status == 'draft')
                                                <span class="badge bg-secondary px-2 py-1.5 rounded">Draft</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($item->status == 'revision')
                                                <a href="{{ route('user.article.edit', $item->id) }}"
                                                    class="btn btn-warning btn-sm fw-bold text-dark rounded-3 px-3 shadow-sm">
                                                    <i class="fas fa-tools me-1"></i> Revisi
                                                </a>
                                            @elseif($item->status == 'draft')
                                                <form action="{{ route('user.article.submitVerification', $item->id) }}"
                                                    method="POST" id="verifyForm-{{ $item->id }}" class="d-inline">
                                                    @csrf
                                                    <button type="button" onclick="verifyAction({{ $item->id }})"
                                                        class="btn btn-danger btn-sm px-2 rounded-3 fw-bold shadow-sm">
                                                        <i class="fas fa-paper-plane small me-1"></i> Ajukan
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">Belum ada data karya ilmiah.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        if (typeof $.fn.dataTable !== 'undefined') {
            $.fn.dataTable.ext.errMode = 'none';
        }

        function verifyAction(id) {
            Swal.fire({
                title: 'Ajukan Verifikasi?',
                text: "Berkas akan dikirim ke Admin dan status berubah menjadi Menunggu Verifikasi.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#8B0000',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Ajukan!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Sedang Mengirim...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    document.getElementById('verifyForm-' + id).submit();
                }
            });
        }
    </script>
@endpush
