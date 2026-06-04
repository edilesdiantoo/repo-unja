@extends('layouts.user')

@section('title', 'Riwayat Data Karya Ilmiah')

@section('content')
    <div class="card shadow rounded-4 border-0">
        <div class="card-body">
            <div class="row align-items-center g-4 mb-4">
                <div class="col-6">
                    <h4>Data Karya Ilmiah</h4>
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
                                    <th>Penulis</th>
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
                                                            : 'bg-success');
                                            @endphp
                                            <span class="badge {{ $color }} rounded-circle p-2"
                                                title="{{ $item->document_type }}"
                                                style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                                {{ $initial }}
                                            </span>
                                        </td>
                                        <td class="small text-muted">{{ $item->created_at->format('d M Y') }}</td>
                                        <td>
                                            <small class="text-muted">{{ $item->study_program }} • Hukum •
                                                {{ $item->year }}</small>
                                            <div class="fw-bold text-dark">{{ $item->title ?? 'Judul Belum Diisi (Draft)' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $item->author ?? '-' }}</div>
                                        </td>
                                        <td>
                                            <span class="badge btn-outline-success border text-success px-2 py-1">
                                                {{ $item->access_type ?? 'Fulltext' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($item->status == 'published')
                                                <span class="badge bg-primary px-2 py-1.5 rounded">Disetujui
                                                    (Publish)</span>
                                            @elseif($item->status == 'verified')
                                                {{-- TAMBAHAN BADGE STATUS BARU UNTUK USER --}}
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
                                            {{-- MODIFIKASI AKSI DI SINI --}}
                                            @if ($item->status == 'revision')
                                                {{-- Tombol ini akan aktif jika Admin meminta revisi --}}
                                                <a href="{{ route('user.article.edit', $item->id) }}"
                                                    class="btn btn-danger btn-sm">Revisi</a>
                                            @elseif($item->status == 'draft')
                                                {{-- Tombol Ajukan khusus jika statusnya barusan diupload (Draft) --}}
                                                <form action="{{ route('user.article.submitVerification', $item->id) }}"
                                                    method="POST" id="verifyForm-{{ $item->id }}" class="d-inline">
                                                    @csrf
                                                    <button type="button" onclick="verifyAction({{ $item->id }})"
                                                        class="btn btn-danger btn-sm px-2">
                                                        <i class="fas fa-paper-plane small"></i> Ajukan
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">Belum ada data karya ilmiah.</td>
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

{{-- TAMBAHKAN PUSH SCRIPTS UNTUK SWEETALERT AJUKAN --}}
@push('scripts')
    <script>
        function verifyAction(id) {
            Swal.fire({
                title: 'Ajukan Verifikasi?',
                text: "Berkas akan dikirim ke Admin dan status berubah menjadi Menunggu Verifikasi.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
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
                            Swal.showLoading()
                        }
                    });
                    document.getElementById('verifyForm-' + id).submit();
                }
            });
        }
    </script>
@endpush
