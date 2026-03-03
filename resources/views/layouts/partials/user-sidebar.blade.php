<div class="sidebar" id="sidebar">
    <div class="p-4 fw-bold fs-5 border-bottom">
        <a href="{{ route('home') }}" class="text-white text-decoration-none">
            Repositori FH UNJA
        </a>
    </div>
    <div class="menu p-3">
        <a href="{{ route('user.dashboard') }}" class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
            <i class="fa fa-home me-2"></i> Dashboard
        </a>

        <a href="{{ route('user.article.create') }}"
            class="{{ request()->routeIs('user.article.create') ? 'active' : '' }}">
            <i class="fa fa-cloud-arrow-up me-2"></i> Upload Karya Ilmiah
        </a>
        <a href="{{ route('user.article.history') }}"
            class="{{ request()->routeIs('user.article.history') ? 'active' : '' }}">
            <i class="fa fa-clock-rotate-left me-2"></i> Riwayat Data
            <a href="{{ route('web.policy') }}" class="nav-item nav-link">
                <i class="fa fa-book me-2"></i> Panduan & Kebijakan
            </a>
    </div>
</div>
