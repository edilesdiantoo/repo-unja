<div class="topbar d-flex align-items-center">
    <button class="btn btn-outline-secondary d-lg-none" onclick="toggleSidebar()">
        <i class="fa fa-bars"></i>
    </button>
    <div class="fw-semibold d-lg-block d-none text-muted">
        Selamat Datang, <span class="text-dark">Superadmin</span>
    </div>

    <div class="ms-auto d-flex align-items-center gap-3">
        {{-- Notifikasi --}}
        <div class="dropdown">
            <button class="btn btn-light rounded-circle position-relative border" data-bs-toggle="dropdown"
                style="width: 40px; height: 40px;">
                <i class="fa-regular fa-bell"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                    style="font-size: 0.6rem;">
                    {{ $unreadCount ?? 0 }}
                </span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" style="width: 320px;">
                <li
                    class="dropdown-header fw-bold border-bottom d-flex justify-content-between align-items-center py-2">
                    <span>Persetujuan Baru</span>
                    {{-- Gunakan ?? 0 agar jika variabel tidak ada, angka 0 yang muncul --}}
                    @if (($unreadCount ?? 0) > 0)
                        <span class="badge bg-danger rounded-pill">{{ $unreadCount }}</span>
                    @endif
                </li>

                <div style="max-height: 350px; overflow-y: auto;">
                    {{-- Pakai @foreach atau @forelse dari variabel $notifications --}}
                    @forelse($notifications as $notif)
                        <li>
                            <a class="dropdown-item py-3" href="{{ route('admin.notifications.read', $notif->id) }}">
                                <div class="d-flex flex-column">
                                    <span class="badge bg-info mb-1"
                                        style="width: fit-content;">{{ $notif->status }}</span>
                                    <small class="fw-bold">{{ $notif->title }}</small>
                                    <small class="text-muted text-wrap">{{ $notif->message }}</small>
                                </div>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider m-0">
                        </li>
                    @empty
                        <li class="py-4 text-center">
                            <small class="text-muted">Tidak ada antrean validasi baru</small>
                        </li>
                    @endforelse
                </div>

                <li class="text-center">
                    <a class="dropdown-item small text-primary fw-bold py-2"
                        href="{{ route('admin.repository.pending') }}">
                        Lihat Semua Antrean <i class="fas fa-chevron-right ms-1 small"></i>
                    </a>
                </li>
            </ul>
        </div>

        {{-- Profil --}}
        <div class="dropdown">
            <img src="https://ui-avatars.com/api/?name=Super+Admin&background=8B0000&color=fff"
                class="rounded-circle border dropdown-toggle" data-bs-toggle="dropdown"
                style="cursor: pointer; width: 40px; height: 40px;">
            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                <li>
                    <a class="dropdown-item" href="{{ route('admin.profile') }}">
                        <i class="fa fa-user-gear me-2 text-muted"></i> Profile
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <a class="dropdown-item text-danger" href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa fa-power-off me-2"></i> Keluar
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
