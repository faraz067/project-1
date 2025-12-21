<nav id="sidebar" class="sidebar-wrapper d-flex flex-column flex-shrink-0 p-4">
    
    <a href="/" class="brand-link d-flex align-items-center mb-4 text-decoration-none">
        <div class="brand-icon">
            <i class="bi bi-building-fill"></i>
        </div>
        <div class="d-flex flex-column ms-3">
            <span class="fs-4 fw-bolder text-white tracking-tight">LAPANGIN</span>
            <span class="text-white-50" style="font-size: 0.7rem; letter-spacing: 1px;">STAFF PANEL</span>
        </div>
    </a>

    <div class="sidebar-divider mb-4"></div>

    <div class="mb-2 text-uppercase text-white-50 fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">Main Menu</div>
    
    <ul class="nav nav-pills flex-column mb-auto gap-2">
        <li class="nav-item">
            <a href="{{ route('staff.jadwal') }}" 
               class="nav-link d-flex align-items-center {{ request()->routeIs('staff.jadwal') ? 'active-menu' : 'inactive-menu' }}">
                <div class="icon-wrapper">
                    <i class="bi bi-calendar-week-fill"></i>
                </div>
                <span class="ms-3 fw-medium">Jadwal Hari Ini</span>
                
                @if(request()->routeIs('staff.jadwal'))
                    <i class="bi bi-chevron-right ms-auto fs-6 fade-in"></i>
                @endif
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('staff.riwayat') }}" 
               class="nav-link d-flex align-items-center {{ request()->routeIs('staff.riwayat') ? 'active-menu' : 'inactive-menu' }}">
                <div class="icon-wrapper">
                    <i class="bi bi-clock-history"></i>
                </div>
                <span class="ms-3 fw-medium">Riwayat Booking</span>

                @if(request()->routeIs('staff.riwayat'))
                    <i class="bi bi-chevron-right ms-auto fs-6 fade-in"></i>
                @endif
            </a>
        </li>
    </ul>

    <div class="mt-auto mb-3">
        <div class="clock-card p-3 rounded-4 text-center">
            <div id="live-clock" class="fw-bold fs-3 text-white tracking-tight" style="font-family: 'Courier New', monospace;">
                00:00:00
            </div>
            <div id="live-date" class="text-white-50 small text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">
                Memuat Tanggal...
            </div>
        </div>
    </div>

    <div>
        <div class="user-card p-3 rounded-4 d-flex align-items-center">
            <div class="avatar-circle">
                SO
            </div>
            <div class="ms-3 overflow-hidden">
                <h6 class="m-0 text-white fw-bold text-truncate">Staff Operator</h6>
                <div class="d-flex align-items-center mt-1">
                    <span class="online-indicator me-1"></span>
                    <small class="text-white-50" style="font-size: 11px;">Online & Active</small>
                </div>
            </div>
            <a href="#" class="btn btn-icon-only ms-auto text-white-50 hover-danger">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </div>
</nav>

<script>
    function updateClock() {
        const now = new Date();
        
        // Format Waktu (HH:MM:SS)
        const timeString = now.toLocaleTimeString('id-ID', { 
            hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false 
        }).replace(/\./g, ':');

        // Format Tanggal (Senin, 25 Des 2023)
        const dateString = now.toLocaleDateString('id-ID', { 
            weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' 
        });

        document.getElementById('live-clock').innerText = timeString;
        document.getElementById('live-date').innerText = dateString;
    }

    setInterval(updateClock, 1000); // Update setiap 1 detik
    updateClock(); // Jalankan langsung saat load
</script>

<style>
    /* 1. CONTAINER BACKGROUND */
    .sidebar-wrapper {
        width: 280px;
        height: 100vh;
        position: sticky;
        top: 0;
        background: linear-gradient(160deg, #1a1f3c 0%, #0f1225 100%);
        box-shadow: 4px 0 24px rgba(0,0,0,0.2);
        color: #fff;
    }

    /* 2. BRAND LOGO */
    .brand-icon {
        width: 42px;
        height: 42px;
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: white;
        box-shadow: 0 4px 15px rgba(67, 97, 238, 0.4);
    }
    .tracking-tight { letter-spacing: -0.5px; }
    
    .sidebar-divider {
        height: 1px;
        background: linear-gradient(90deg, rgba(255,255,255,0.1), transparent);
    }

    /* 3. MENU STYLING */
    .nav-link {
        padding: 14px 16px;
        border-radius: 14px;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        position: relative;
        overflow: hidden;
    }

    /* State: INACTIVE */
    .inactive-menu { color: #94a3b8; background: transparent; }
    .inactive-menu:hover {
        background: rgba(255, 255, 255, 0.05);
        color: #fff;
        transform: translateX(4px);
    }
    .inactive-menu .icon-wrapper { color: #64748b; }

    /* State: ACTIVE */
    .active-menu {
        background: #4361ee;
        color: white;
        box-shadow: 0 8px 20px -4px rgba(67, 97, 238, 0.5);
    }
    .active-menu .icon-wrapper { color: white; }

    .icon-wrapper {
        width: 24px;
        display: flex;
        justify-content: center;
        font-size: 1.1rem;
        transition: color 0.3s;
    }

    /* 4. CLOCK & USER CARD STYLE (GLASSMORPHISM) */
    /* Kita gunakan style yang sama untuk Jam dan User Card agar seragam */
    .user-card, .clock-card {
        background: rgba(255, 255, 255, 0.03); /* Lebih transparan */
        border: 1px solid rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
    }
    
    /* Khusus widget jam, beri sedikit gradasi halus saat di hover */
    .clock-card:hover {
        background: rgba(255, 255, 255, 0.08);
        transition: background 0.3s ease;
    }

    .avatar-circle {
        width: 40px;
        height: 40px;
        background: #e2e8f0;
        border-radius: 50%;
        color: #0f172a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.9rem;
    }
    .online-indicator {
        width: 8px;
        height: 8px;
        background-color: #10b981;
        border-radius: 50%;
        box-shadow: 0 0 8px rgba(16, 185, 129, 0.6);
    }
    
    .hover-danger:hover { color: #ef4444 !important; }

    .fade-in { animation: fadeIn 0.4s ease-in-out; }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateX(-5px); }
        to { opacity: 1; transform: translateX(0); }
    }
</style>