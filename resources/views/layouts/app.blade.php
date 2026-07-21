<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - SI-PK360 ASN</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 260px;
            --topbar-height: 64px;
            --primary-color: #2563eb;
            --sidebar-bg: #0f172a;
            --body-bg: #f8fafc;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--body-bg);
            color: #334155;
            overflow-x: hidden;
        }
        /* Sidebar Styling */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: var(--sidebar-bg);
            color: #94a3b8;
            z-index: 1030;
            transition: all 0.3s ease;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.05);
        }
        .sidebar-brand {
            height: var(--topbar-height);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            color: #ffffff;
            font-weight: 700;
            font-size: 1.15rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            text-decoration: none;
        }
        .sidebar-menu {
            padding: 1rem 0.75rem;
        }
        .nav-header {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            padding: 0.75rem 1rem 0.25rem;
            font-weight: 700;
        }
        .sidebar .nav-link {
            color: #94a3b8;
            padding: 0.7rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s ease;
            margin-bottom: 0.25rem;
        }
        .sidebar .nav-link:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.06);
        }
        .sidebar .nav-link.active {
            color: #ffffff;
            background-color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }
        .sidebar .nav-link i {
            font-size: 1.1rem;
        }
        /* Top Navbar */
        .top-navbar {
            height: var(--topbar-height);
            margin-left: var(--sidebar-width);
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 1020;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }
        /* Main Content Wrapper */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 1.5rem;
            min-height: calc(100vh - var(--topbar-height));
        }
        /* Card Styling */
        .card-custom {
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            background: #ffffff;
            transition: all 0.2s ease;
        }
        .card-stat {
            border: none;
            border-radius: 1rem;
            color: #ffffff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .card-stat .stat-icon {
            position: absolute;
            right: 1.25rem;
            bottom: 1rem;
            font-size: 3.5rem;
            opacity: 0.25;
        }
        @media (max-width: 991.98px) {
            .sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            .sidebar.show {
                margin-left: 0;
            }
            .top-navbar, .main-content {
                margin-left: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <a href="{{ auth()->user()->hasAnyRole(['Super Admin', 'Admin BKPSDM']) ? route('admin.dashboard') : route('pegawai.dashboard') }}" class="sidebar-brand">
            <i class="bi bi-award-fill text-primary me-2 fs-4"></i>
            <span>SI-PK360 ASN</span>
        </a>

        <div class="sidebar-menu">
            @if(auth()->user()->hasAnyRole(['Super Admin', 'Admin BKPSDM']))
                <div class="nav-header">NAVIGASI UTAMA</div>
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Dashboard Admin</span>
                </a>

                <div class="nav-header">MASTER DATA</div>
                <a href="{{ route('admin.pegawai.index') }}" class="nav-link {{ request()->routeIs('admin.pegawai.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i>
                    <span>Kelola Pegawai</span>
                </a>
                <a href="{{ route('admin.jabatans.index') }}" class="nav-link {{ request()->routeIs('admin.jabatans.*') ? 'active' : '' }}">
                    <i class="bi bi-briefcase-fill"></i>
                    <span>Kelola Jabatan</span>
                </a>
                <a href="{{ route('admin.units.index') }}" class="nav-link {{ request()->routeIs('admin.units.*') ? 'active' : '' }}">
                    <i class="bi bi-building-fill"></i>
                    <span>Kelola Unit Kerja</span>
                </a>
                <a href="{{ route('admin.pertanyaans.index') }}" class="nav-link {{ request()->routeIs('admin.pertanyaans.*') ? 'active' : '' }}">
                    <i class="bi bi-patch-question-fill"></i>
                    <span>Pertanyaan Penilaian</span>
                </a>

                <div class="nav-header">PENILAIAN 360°</div>
                <a href="{{ route('admin.periodes.index') }}" class="nav-link {{ request()->routeIs('admin.periodes.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-event-fill"></i>
                    <span>Periode & Progress 360°</span>
                </a>
                <a href="{{ route('admin.hasil.index') }}" class="nav-link {{ request()->routeIs('admin.hasil.*') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart-line-fill"></i>
                    <span>Hasil & Rekapitulasi 360°</span>
                </a>
            @else
                <div class="nav-header">NAVIGASI PEGAWAI</div>
                <a href="{{ route('pegawai.dashboard') }}" class="nav-link {{ request()->routeIs('pegawai.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Dashboard Saya</span>
                </a>
                <a href="{{ route('pegawai.penilaian.index') }}" class="nav-link {{ request()->routeIs('pegawai.penilaian.*') ? 'active' : '' }}">
                    <i class="bi bi-clipboard-check-fill"></i>
                    <span>Isi Penilaian 360°</span>
                </a>
                <a href="{{ route('pegawai.hasil-saya') }}" class="nav-link {{ request()->routeIs('pegawai.hasil-saya') ? 'active' : '' }}">
                    <i class="bi bi-award-fill"></i>
                    <span>Hasil Penilaian Saya</span>
                </a>
                <a href="{{ route('pegawai.profil') }}" class="nav-link {{ request()->routeIs('pegawai.profil') ? 'active' : '' }}">
                    <i class="bi bi-person-badge-fill"></i>
                    <span>Profil Saya</span>
                </a>
            @endif
        </div>
    </aside>

    <!-- Top Navbar -->
    <header class="top-navbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-light d-lg-none" id="sidebarToggle">
                <i class="bi bi-list fs-5"></i>
            </button>
            <h6 class="mb-0 fw-bold d-none d-sm-block text-secondary">
                Sistem Penilaian Kinerja 360 Derajat
            </h6>
        </div>

        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle d-flex align-items-center gap-2 border-0 bg-transparent" type="button" data-bs-toggle="dropdown">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="text-start d-none d-md-block">
                    <div class="fw-semibold text-dark small leading-tight">{{ auth()->user()->name }}</div>
                    <div class="text-secondary" style="font-size: 0.75rem;">
                        {{ auth()->user()->hasAnyRole(['Super Admin', 'Admin BKPSDM']) ? 'Administrator' : (auth()->user()->position?->name ?? 'Pegawai') }}
                    </div>
                </div>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 mt-2">
                <li class="px-3 py-2 border-bottom">
                    <div class="fw-bold small">{{ auth()->user()->name }}</div>
                    <div class="text-secondary small">{{ auth()->user()->email }}</div>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2 py-2 mt-1">
                            <i class="bi bi-box-arrow-right"></i> Keluar / Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                <div class="fw-bold mb-1"><i class="bi bi-exclamation-octagon-fill me-2"></i> Terjadi kesalahan validasi:</div>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
    </script>
    @stack('scripts')
</body>
</html>

