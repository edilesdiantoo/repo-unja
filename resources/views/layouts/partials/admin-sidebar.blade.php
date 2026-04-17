<div class="sidebar" id="sidebar">
    <div class="p-4 fw-bold fs-5 border-bottom">
        <a href="{{ route('home') }}" class="text-white text-decoration-none">
            Admin Repositori
        </a>
    </div>
    <div class="menu p-3">
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fa fa-home me-2"></i> Dashboard
        </a>

        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="fa fa-users me-2"></i> Pengguna
        </a>

        {{-- Dropdown Repositori --}}
        <a class="nav-link" data-bs-toggle="collapse" href="#repositorySubmenu" role="button"
            aria-expanded="{{ request()->is('admin/repository*') ? 'true' : 'false' }}">
            <i class="fa fa-book me-2"></i> Repositori
            <i class="fa fa-chevron-down float-end mt-1" style="font-size: 0.8rem;"></i>
        </a>

        <div class="collapse {{ request()->is('admin/repository*') ? 'show' : '' }}" id="repositorySubmenu">
            <div class="bg-transparent p-2 rounded" style="margin-left: 15px;">
                <a href="{{ route('admin.repository.pending') }}"
                    class="d-block py-2 text-white small {{ request()->routeIs('admin.repository.pending') ? 'fw-bold active' : '' }}">
                    <i class="fa-regular fa-circle me-2" style="font-size: 0.6rem;"></i> Menunggu Validasi
                </a>
                <a href="{{ route('admin.repository.index') }}"
                    class="d-block py-2 text-white small {{ request()->routeIs('admin.repository.index') ? 'fw-bold active' : '' }}">
                    <i class="fa-regular fa-circle me-2" style="font-size: 0.6rem;"></i> Data Koleksi
                </a>
            </div>
        </div>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.report.*') ? 'active' : '' }}"
                href="{{ route('admin.report.index') }}">
                <i class="fa fa-file-lines me-2"></i> Laporan
            </a>
        </li>
        <a href="{{ route('admin.activity-log.index') }}"
            class="nav-link {{ request()->routeIs('admin.activity-log.index') ? 'active' : '' }}">
            <i class="fa fa-clock-rotate-left me-2"></i> Log Aktivitas
        </a>

        <hr class="border-light opacity-25">

        <a href="#" onclick="confirmLogout(event)" class="nav-link">
            <i class="fa fa-right-from-bracket me-2"></i> Logout
        </a>

        {{-- Pastikan form logout kamu sudah ada di bawahnya --}}
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
</div>

<script>
    function confirmLogout(event) {
        event.preventDefault(); // Mencegah link pindah halaman langsung

        Swal.fire({
            title: 'Ingin Keluar?',
            text: "Anda harus login kembali untuk mengakses halaman ini.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#8B0000', // Warna Maroon UNJA
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Logout',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan loading sebentar agar transisi smooth
                Swal.fire({
                    title: 'Sedang Keluar...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });
                // Submit form logout
                document.getElementById('logout-form').submit();
            }
        });
    }
</script>
