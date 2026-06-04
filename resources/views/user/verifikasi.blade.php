@extends('layouts.user')

@section('title', 'Ajukan Verifikasi Karya Ilmiah')

@section('content')
    <div class="container-fluid">
        <div class="card shadow rounded-4 border-0">
            <div class="card-body p-4">
                <div class="row align-items-center g-4 mb-4">
                    <div class="col-12">
                        <h4 class="fw-bold text-dark">
                            <i class="fas fa-check-circle text-primary me-2"></i> Ajukan Verifikasi Karya Ilmiah
                        </h4>
                        <p class="text-muted small mb-0">Berikut adalah daftar draf karya ilmiah Anda yang telah disimpan.
                            Silakan klik tombol <strong>Ajukan Verifikasi</strong> agar dokumen diperiksa dan divalidasi
                            oleh Admin.</p>
                    </div>
                </div>

                @if ($drafts->isEmpty())
                    {{-- Tampilan jika data draf kosong --}}
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-folder-open fa-3x mb-3 opacity-25 text-secondary"></i>
                        <h5 class="fw-bold">Tidak Ada Draf Tersedia</h5>
                        <p class="small mb-0">Semua karya ilmiah Anda telah diajukan atau Anda belum mengunggah berkas baru.
                        </p>
                        <a href="{{ route('user.article.create') }}"
                            class="btn btn-danger btn-sm mt-3 px-3 rounded-3 shadow-sm">
                            <i class="fas fa-plus me-1"></i> Unggah Sekarang
                        </a>
                    </div>
                @else
                    {{-- Tabel Daftar Draf --}}
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-3" width="55%">Judul / Penulis / Program Studi</th>
                                    <th width="15%">Jenis Dokumen</th>
                                    <th width="15%">Status Dokumen</th>
                                    <th class="text-center" width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($drafts as $row)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="fw-bold text-dark mb-1">{{ $row->title ?? 'Judul Belum Diisi' }}
                                            </div>
                                            <div class="text-muted small">
                                                <i class="fas fa-user me-1"></i> {{ $row->author ?? '-' }}
                                                <span class="mx-2">|</span>
                                                <i class="fas fa-graduation-cap me-1"></i> {{ $row->study_program ?? '-' }}
                                            </div>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-light text-dark border px-2 py-1">{{ $row->document_type ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary px-2 py-1"><i class="fas fa-file-alt me-1"></i>
                                                Draft</span>
                                        </td>
                                        <td class="text-center">
                                            {{-- Form Submit Mengarah ke Controller submitVerification --}}
                                            <form action="{{ route('user.article.submitVerification', $row->id) }}"
                                                method="POST" id="verifyForm-{{ $row->id }}">
                                                @csrf
                                                <button type="button" onclick="verifyAction({{ $row->id }})"
                                                    class="btn btn-danger btn-sm px-3 rounded-3 shadow-sm">
                                                    <i class="fas fa-paper-plane me-1"></i> Ajukan
                                                </button>
                                            </form>
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
        function verifyAction(id) {
            Swal.fire({
                title: 'Ajukan Verifikasi?',
                text: "Setelah diajukan, data metadata akan dikunci untuk diperiksa oleh Admin dan statusnya berubah menjadi Menunggu Verifikasi.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd', // Warna Biru Primary Laravel
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Ajukan!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Animasi Loading Kirim Berkas ke Admin
                    Swal.fire({
                        title: 'Sedang Mengirim Data...',
                        html: 'Mohon tunggu sebentar, sistem sedang memberikan notifikasi ke Admin.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    });
                    // Eksekusi Form Berdasarkan ID Artikel
                    document.getElementById('verifyForm-' + id).submit();
                }
            });
        }
    </script>
@endpush
