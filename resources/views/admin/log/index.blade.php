@extends('layouts.admin')

@section('content')
    <div class="card shadow-sm rounded-4 border-0">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold text-dark mb-0">
                    <i class="fas fa-history me-2 text-primary"></i>Log Aktivitas Sistem
                </h4>
                <span class="badge bg-light text-dark border px-3 py-2">
                    Total: {{ $logs->total() }} Aktivitas
                </span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle border-start border-end">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 60px;">No.</th>
                            <th style="width: 200px;">Nama User</th>
                            <th>Deskripsi Aktivitas</th>
                            <th style="width: 200px;">Waktu Kejadian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr>
                                <td class="text-center text-muted">
                                    {{ ($logs->currentPage() - 1) * $logs->perPage() + $loop->iteration }}
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $log->user->name }}</div>
                                    <small class="text-muted">{{ $log->user->identity_number }}</small>
                                </td>
                                <td>
                                    @php
                                        $desc = $log->description;
                                        // Mapping Warna & Ikon
                                        $config = [
                                            'rejected' => ['color' => 'danger', 'icon' => 'fa-circle-xmark'],
                                            'revision' => ['color' => 'warning text-dark', 'icon' => 'fa-pen-nib'],
                                            'published' => ['color' => 'success', 'icon' => 'fa-check-circle'],
                                            'Setuju' => ['color' => 'success', 'icon' => 'fa-check-circle'],
                                            'Mengunggah' => ['color' => 'primary', 'icon' => 'fa-cloud-upload-alt'],
                                            'Upload' => ['color' => 'primary', 'icon' => 'fa-cloud-upload-alt'],
                                            'Login' => ['color' => 'info', 'icon' => 'fa-sign-in-alt'],
                                            'Logout' => ['color' => 'secondary', 'icon' => 'fa-sign-out-alt'],
                                        ];

                                        // Cari yang cocok
                                        $match = ['color' => 'secondary', 'icon' => 'fa-history'];
                                        foreach ($config as $key => $val) {
                                            if (str_contains($desc, $key)) {
                                                $match = $val;
                                                break;
                                            }
                                        }
                                    @endphp

                                    <span class="badge bg-{{ $match['color'] }} px-3 py-2 fw-medium shadow-sm">
                                        <i class="fas {{ $match['icon'] }} me-2"></i>{{ $desc }}
                                    </span>
                                </td>
                                <td class="text-muted small">
                                    <i class="far fa-clock me-1"></i>
                                    {{ $log->created_at->translatedFormat('d M Y, H:i') }} WIB
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="fas fa-folder-open d-block mb-2 fa-2x"></i>
                                    Belum ada riwayat aktivitas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Rapi --}}
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted small">
                    Menampilkan {{ $logs->firstItem() }} sampai {{ $logs->lastItem() }} dari {{ $logs->total() }} data
                </div>
                <div>
                    {{ $logs->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
