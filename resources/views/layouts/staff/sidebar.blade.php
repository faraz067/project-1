<nav id="sidebar">
    <div class="p-4 pt-5">
        <a href="#" class="d-flex align-items-center mb-3 text-white text-decoration-none">
            <i class="bi bi-building fs-4 me-2"></i>
            <span class="fs-4 fw-bold">Lapangin</span>
        </a>
        <hr>
        <ul class="nav flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('staff.jadwal') }}" class="nav-link {{ request()->routeIs('staff.jadwal') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check me-2"></i> Jadwal Hari Ini
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('staff.riwayat') }}" class="nav-link {{ request()->routeIs('staff.riwayat') ? 'active' : '' }}">
                    <i class="bi bi-clock-history me-2"></i>
                    Riwayat
                </a>
            </li>
        </ul>
    </div>
</nav