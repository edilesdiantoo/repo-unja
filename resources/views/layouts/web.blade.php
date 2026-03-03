<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Repository FH UNJA')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <style>
        :root {
            --bs-unja-primary: #8B0000;
            --bs-unja-secondary: #FF8C00;
            --bs-unja-light-shade: #B22222;
            --bs-unja-darker: #630000;
        }

        .bg-unja-hero {
            background: linear-gradient(135deg, var(--bs-unja-darker) 0%, var(--bs-unja-primary) 50%, var(--bs-unja-light-shade) 100%) !important;
        }

        .text-unja {
            color: var(--bs-unja-primary) !important;
        }

        .border-unja {
            border-left: 5px solid var(--bs-unja-secondary);
        }

        .ranking-box {
            background-color: #660000;
            border-radius: .5rem;
            backdrop-filter: blur(5px);
            padding: 20px;
            border: 1px solid white;
            color: #fff;
        }

        .btn-unja-primary {
            background-color: var(--bs-unja-primary) !important;
            color: white !important;
            border: none;
        }

        .btn-unja-primary:hover {
            background-color: var(--bs-unja-darker) !important;
            color: white !important;
        }
    </style>
    @yield('extra-css')
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('assets/img/logounjahukum.png') }}" style="width: 125px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item px-3">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active fw-bold text-unja' : '' }}"
                            href="{{ route('home') }}">Beranda</a>
                    </li>
                    <li class="nav-item px-3">
                        <a class="nav-link {{ request()->routeIs('web.about') ? 'active fw-bold text-unja' : '' }}"
                            href="{{ route('web.about') }}">Tentang Kami</a>
                    </li>
                    <li class="nav-item px-3">
                        <a class="nav-link {{ request()->routeIs('web.browse') ? 'active fw-bold text-unja' : '' }}"
                            href="{{ route('web.browse') }}">Pencarian</a>
                    </li>

                    {{-- Logika Autentikasi Navbar --}}
                    @guest
                        <li class="nav-item px-3">
                            <a href="{{ route('login') }}" class="btn btn-unja-primary px-4 rounded-3 text-white">Masuk</a>
                        </li>
                    @endguest

                    @auth
                        <li class="nav-item dropdown px-3">
                            <a class="nav-link dropdown-toggle fw-bold text-unja" href="#" id="navbarDropdown"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="navbarDropdown">
                                <li>
                                    <h6 class="dropdown-header small text-muted">Akses Sistem</h6>
                                </li>
                                @if (Auth::user()->role === 'admin' || Auth::user()->role === 'superadmin')
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i
                                                class="fas fa-tachometer-alt me-2"></i>Dashboard Admin</a></li>
                                @else
                                    <li><a class="dropdown-item" href="{{ route('user.dashboard') }}"><i
                                                class="fas fa-th-large me-2"></i>Dashboard Saya</a></li>
                                @endif
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i>Keluar
                                    </a>
                                </li>
                            </ul>
                        </li>
                        {{-- Form Logout Tersembunyi --}}
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <footer class="bg-unja-hero py-4 mt-5">
        <div class="container text-center text-white">
            <p class="small mb-0">© 2025 Fakultas Hukum Universitas Jambi</p>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.waves.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('extra-js')
</body>

</html>
