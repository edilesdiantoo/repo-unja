@extends('layouts.admin')

@section('content')
    <div class="card shadow rounded-4 border-0">
        <div class="card-body">
            <div class="row align-items-center g-4 mb-4">
                <div class="col-6">
                    <h4>Data Pengguna</h4>
                </div>
                <div class="col-6 text-end">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-danger">Tambah</a>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="example" class="table table-bordered align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th>No.</th>
                                    <th>Peran</th>
                                    <th>Daftar</th>
                                    <th>Identitas (NIM/NIDN)</th>
                                    <th>Nama Lengkap</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Terakhir Login</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $index => $user)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            @php
                                                $roleBadge = [
                                                    'superadmin' => 'bg-danger',
                                                    'admin' => 'bg-primary',
                                                    'dosen' => 'bg-success',
                                                    'user' => 'bg-warning',
                                                ];
                                                $roleName = [
                                                    'superadmin' => 'Superadmin',
                                                    'admin' => 'Admin',
                                                    'dosen' => 'Dosen',
                                                    'user' => 'Mahasiswa',
                                                ];
                                            @endphp
                                            <span class="badge {{ $roleBadge[$user->role] ?? 'bg-secondary' }} text-white">
                                                {{ $roleName[$user->role] ?? 'User' }}
                                            </span>
                                        </td>
                                        <td>{{ $user->created_at->format('d M Y') }}</td>
                                        <td>
                                            {{-- Menampilkan label pendukung berdasarkan role --}}
                                            <small class="text-muted d-block" style="font-size: 0.7rem;">
                                                {{ $user->role == 'dosen' ? 'NIDN:' : ($user->role == 'user' ? 'NIM:' : 'ID:') }}
                                            </small>
                                            <span class="fw-bold">{{ $user->identity_number ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $user->name }}</div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <a href="#"
                                                class="badge {{ $user->is_active ? 'bg-primary' : 'bg-secondary' }} text-white text-decoration-none btn-status"
                                                data-bs-toggle="modal" data-bs-target="#statusModal"
                                                data-id="{{ $user->id }}" data-nama="{{ $user->name }}"
                                                data-status="{{ $user->is_active }}">
                                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </a>
                                        </td>
                                        <td class="small">
                                            {{ $user->last_login_at ? $user->last_login_at->format('d M Y, H:i') . ' WIB' : 'Belum Login' }}
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                {{-- Tombol Edit --}}
                                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                                    class="btn btn-sm btn-light border">
                                                    <i class="fas fa-pencil text-secondary"></i>
                                                </a>

                                                {{-- Form Hapus --}}
                                                {{-- <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-light border text-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form> --}}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Belum ada data pengguna.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formStatus" method="POST" action="">
                @csrf
                @method('PATCH')
                <div class="modal-content">
                    <div class="modal-header" id="modalHeader">
                        <h5 class="modal-title">Konfirmasi Perubahan Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p id="modalText"></p>
                        <p><strong id="modalUserName"></strong></p>
                        <p id="modalSubText"></p>
                        <p class="text-danger">Apakah Anda yakin ingin melanjutkan?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn" id="btnSubmitStatus">Konfirmasi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $('.btn-status').on('click', function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama');
            const status = $(this).data('status'); // 1 untuk aktif, 0 untuk nonaktif

            const url = `/admin/users/${id}/status`; // Sesuaikan dengan route kamu
            $('#formStatus').attr('action', url);
            $('#modalUserName').text(nama);

            if (status == 1) {
                $('#modalHeader').addClass('bg-danger text-white').removeClass('bg-success');
                $('#modalText').text('Anda akan menonaktifkan akun untuk pengguna:');
                $('#modalSubText').text('Setelah dinonaktifkan, pengguna ini tidak dapat login ke sistem.');
                $('#btnSubmitStatus').addClass('btn-danger').removeClass('btn-success').text(
                    'Ya, Nonaktifkan Akun');
            } else {
                $('#modalHeader').addClass('bg-success text-white').removeClass('bg-danger');
                $('#modalText').text('Anda akan mengaktifkan kembali akun untuk pengguna:');
                $('#modalSubText').text('Setelah diaktifkan, pengguna ini dapat kembali login ke sistem.');
                $('#btnSubmitStatus').addClass('btn-success').removeClass('btn-danger').text('Ya, Aktifkan Akun');
            }
        });
    </script>
@endpush
