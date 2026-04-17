@extends('layouts.admin') {{-- Sesuaikan nama layout admin kamu --}}

@section('content')
    <div class="container-fluid py-4">
        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
        @endif

        <div class="card shadow rounded-4 border-0">
            <div class="card-body p-4">
                <div class="row align-items-center mb-4">
                    <div class="col-6">
                        <h4 class="fw-bold">Pengaturan Profil Admin</h4>
                    </div>
                </div>

                <form id="profileForm" method="POST" action="{{ route('admin.profile.update') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="inputNama" class="form-label fw-bold">Nama Lengkap <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama_lengkap"
                                value="{{ old('nama_lengkap', $user->name) }}" required>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="inputIdentitas" class="form-label fw-bold">Nomor Identitas (NIDN/NIP) <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="inputIdentitas" name="identity_number"
                                value="{{ old('identity_number', $user->identity_number) }}" required>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="inputEmail" class="form-label fw-bold">Email <span
                                    class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="inputEmail" name="email"
                                value="{{ old('email', $user->email) }}" required>
                        </div>

                        <div class="col-md-12 mb-4">
                            <label for="inputPassword" class="form-label fw-bold">Password Baru</label>
                            <input type="password" class="form-control" id="inputPassword" name="password"
                                placeholder="Kosongkan jika tidak ingin mengubah password">
                            <small class="text-muted">Isi jika ingin mengganti password login admin.</small>
                        </div>

                        <div class="col-12">

                            <div class="col-12 mt-4">
                                {{-- Ganti type="submit" menjadi type="button" agar tidak langsung kirim --}}
                                <button type="button" onclick="confirmSave()" class="btn btn-danger px-4">Simpan</button>
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary px-4">Kembali</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<script>
    function confirmSave() {
        Swal.fire({
            title: 'Konfirmasi Simpan',
            text: "Pastikan semua data dan file sudah benar sebelum disimpan!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#8B0000', // Maroon UNJA
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Cek Kembali',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan loading saat proses upload (penting untuk file besar)
                Swal.fire({
                    title: 'Memperbarui Profil...',
                    html: 'Mohon tunggu sebentar.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });
                // Kirim form
                document.getElementById('profileForm').submit();
            }
        });
    }
</script>
