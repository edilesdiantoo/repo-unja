@extends('layouts.admin')

@section('title', 'Edit Pengguna')

@section('content')
    <div class="card shadow rounded-4 border-0">
        <div class="card-body">
            <div class="row align-items-center g-4 mb-4">
                <div class="col-6">
                    <h4>Edit Pengguna</h4>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    {{-- Gunakan method PUT untuk update --}}
                    <form id="editUserForm" method="POST" action="{{ route('admin.users.update', $user->id) }}"> @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="inputRole" class="form-label fw-bold">Peran Pengguna <span
                                    class="text-danger">*</span></label>
                            <select class="form-select @error('role') is-invalid @enderror" id="inputRole" name="role"
                                required>
                                <option value="dosen" {{ $user->role == 'dosen' ? 'selected' : '' }}>Dosen</option>
                                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Mahasiswa</option>
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="superadmin" {{ $user->role == 'superadmin' ? 'selected' : '' }}>Superadmin
                                </option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="inputNama" class="form-label fw-bold">Nama Lengkap <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="inputNama"
                                name="name" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="inputIdentitas" class="form-label fw-bold">Nomor Identitas (NIM/NIDN) <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('identity_number') is-invalid @enderror"
                                id="inputIdentitas" name="identity_number"
                                value="{{ old('identity_number', $user->identity_number) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="inputEmail" class="form-label fw-bold">Email <span
                                    class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="inputEmail"
                                name="email" value="{{ old('email', $user->email) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="inputPassword" class="form-label fw-bold">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="inputPassword" name="password">
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                        </div>

                        <div class="mt-4">
                            {{-- Ubah ke type="button" --}}
                            <button type="button" onclick="confirmUpdateUser()" class="btn btn-primary px-4">
                                <i class="fas fa-save me-1"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary px-4">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    function confirmUpdateUser() {
        const form = document.getElementById('editUserForm');

        // Validasi HTML5 dasar (cek required)
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        Swal.fire({
            title: 'Update Data Pengguna?',
            text: "Pastikan perubahan role atau identitas sudah benar.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0d6efd', // Warna biru Primary
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Perbarui',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan loading
                Swal.fire({
                    title: 'Sedang Memproses...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });
                form.submit();
            }
        });
    }
</script>
