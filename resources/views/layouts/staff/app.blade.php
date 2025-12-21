<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Booking')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body { background-color: #f4f6f9; overflow-x: hidden; }
        .wrapper { display: flex; width: 100%; align-items: stretch; }
        #sidebar {
            min-width: 250px; max-width: 250px; min-height: 100vh;
            background: #212529; color: #fff; transition: all 0.3s;
        }
        #sidebar.active { margin-left: -250px; }
        #content { width: 100%; min-height: 100vh; display: flex; flex-direction: column; }
        .main-content { flex: 1; padding: 20px; }
        #sidebar .nav-link { color: rgba(255,255,255,0.8); padding: 15px 20px; border-bottom: 1px solid rgba(255,255,255,0.05); }
        #sidebar .nav-link:hover, #sidebar .nav-link.active { color: #fff; background: #0d6efd; }
    </style>
</head>
<body>

    <div class="wrapper">
        @include('layouts.staff.sidebar')

        <div id="content">
            @include('layouts.staff.header')

            <div class="main-content">
                @yield('content')
            </div>

            @include('layouts.staff.footer')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebarCollapse')?.addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('active');
        });
    </script>
</body>
</html>