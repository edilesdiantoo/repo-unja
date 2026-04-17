<div class="topbar d-flex align-items-center mb-4">
    <button class="btn btn-outline-secondary d-lg-none" onclick="toggleSidebar()">
        <i class="fa fa-bars"></i>
    </button>
    <div class="fw-semibold d-lg-block d-none">User • Mahasiswa</div>
    <div class="ms-auto d-flex align-items-center gap-3">
        <div class="dropdown">
            <button type="button"
                class="position-relative rounded-circle d-flex align-items-center justify-content-center bg-light border"
                id="notifDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                style="width: 40px; height: 40px; cursor: pointer">
                <i class="fa-regular fa-bell"></i>
                <span class="position-absolute translate-middle p-1 bg-danger border border-light rounded-circle"
                    style="top: 12px; left: 25px">
                    <span class="visually-hidden">New alerts</span>
                </span>
            </button>

            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="notifDropdown"
                style="width: 300px">
                <li class="dropdown-header fw-bold border-bottom d-flex justify-content-between align-items-center">
                    <span>Notifikasi Publikasi</span>
                    @if ($unreadCount > 0)
                        <span class="badge bg-danger rounded-pill" style="font-size: 0.6rem;">{{ $unreadCount }}
                            Baru</span>
                    @endif
                </li>

                @forelse($notifications as $notif)
                    <li>
                        {{-- Klik notif akan menjalankan fungsi markAsRead --}}
                        <a class="dropdown-item py-3 {{ $notif->is_read ? 'opacity-75' : 'bg-light' }}"
                            href="{{ route('user.notifications.read', $notif->id) }}">
                            <div class="d-flex flex-column">
                                @php
                                    // Logika penentuan warna badge berdasarkan status
                                    $badgeColor = match (strtolower($notif->status)) {
                                        'published' => 'bg-success',
                                        'disetujui' => 'bg-success',
                                        'revisi' => 'bg-warning text-dark',
                                        'ditolak' => 'bg-danger',
                                        default => 'bg-primary',
                                    };
                                @endphp

                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="badge {{ $badgeColor }}"
                                        style="width: fit-content">{{ $notif->status }}</span>
                                    <small class="text-muted"
                                        style="font-size: 0.7rem;">{{ $notif->created_at->diffForHumans() }}</small>
                                </div>

                                <small class="fw-bold text-dark">{{ $notif->title }}</small>
                                <small class="text-muted text-wrap" style="font-size: 0.8rem;">
                                    {{ Str::limit($notif->message, 60) }}
                                </small>
                            </div>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider m-0" />
                    </li>
                @empty
                    <li class="py-4 text-center">
                        <i class="fa-regular fa-bell-slash text-muted d-block mb-2 fa-lg"></i>
                        <small class="text-muted">Belum ada notifikasi baru</small>
                    </li>
                @endforelse

                <li class="text-center">
                    <a class="dropdown-item small text-primary text-center fw-bold py-2 border-top"
                        href="{{ route('user.article.history') }}">
                        Lihat Semua Notifikasi
                    </a>
                </li>
            </ul>
        </div>

        <div class="dropdown">
            <img src="https://placehold.co/40x40" class="rounded-circle dropdown-toggle border" id="profileDropdown"
                data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer; width: 40px; height: 40px" />

            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="profileDropdown">
                <li>
                    {{-- Cari bagian ini di topbar --}}
                    <a class="dropdown-item" href="{{ route('user.profile') }}">
                        <i class="text-muted fa-solid fa-user me-2"></i> Profil
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="#" onclick="confirmLogout(event)">
                        <i class="text-muted fa-solid fa-right-from-bracket me-2"></i>
                        Logout
                    </a>

                    {{-- Form Logout Tersembunyi (Cukup satu saja di halaman ini) --}}
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>

<script>
    function confirmLogout(event) {
        event.preventDefault(); // Menghentikan form agar tidak submit otomatis

        Swal.fire({
            title: 'Yakin ingin keluar?',
            text: "Sesi Anda akan berakhir dan Anda harus login kembali.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#8B0000', // Warna Maroon khas UNJA
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Logout!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Beri efek loading biar lebih mantap
                Swal.fire({
                    title: 'Sedang memproses...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });
                // Eksekusi form logout
                document.getElementById('logout-form').submit();
            }
        });
    }
</script>
