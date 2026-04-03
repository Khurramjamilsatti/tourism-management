<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Tourism Management System')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .sidebar {
            min-height: 100vh;
            max-height: 100vh;
            background: linear-gradient(135deg, #1e3a5f 0%, #2c5282 100%);
            padding-top: 0;
            position: fixed;
            width: 250px;
            z-index: 1000;
            overflow-y: auto;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 2px 10px;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: #fff;
        }
        .sidebar .nav-link i { margin-right: 10px; width: 20px; text-align: center; }
        .sidebar-brand {
            padding: 20px;
            color: #fff;
            font-size: 1.2rem;
            font-weight: 700;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 10px;
        }
        .sidebar-brand i { color: #63b3ed; }
        .main-content { margin-left: 250px; padding: 20px; }
        .top-bar {
            background: #fff;
            padding: 15px 25px;
            margin: -20px -20px 20px -20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .stat-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: transform 0.2s;
        }
        .stat-card:hover { transform: translateY(-2px); }
        .table th { background: #f8f9fa; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; }
        .btn-primary { background: #2c5282; border-color: #2c5282; }
        .btn-primary:hover { background: #1e3a5f; border-color: #1e3a5f; }
        .card { border: none; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .sidebar-section { color: rgba(255,255,255,0.5); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; padding: 15px 20px 5px; }
        @media (max-width: 768px) {
            .sidebar { width: 100%; min-height: auto; position: relative; }
            .main-content { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="d-flex">
        {{-- Sidebar --}}
        <nav class="sidebar d-flex flex-column">
            <div class="sidebar-brand">
                <i class="bi bi-globe-americas"></i> Tourism MS
            </div>
            <ul class="nav flex-column flex-grow-1">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>

                <div class="sidebar-section">Data Management</div>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('tourism-data.*') ? 'active' : '' }}" href="{{ route('tourism-data.index') }}">
                        <i class="bi bi-collection"></i> Tourism Data
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('analytics') ? 'active' : '' }}" href="{{ route('analytics') }}">
                        <i class="bi bi-bar-chart-line"></i> Analytics
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                        <i class="bi bi-file-earmark-text"></i> Reports
                    </a>
                </li>

                <div class="sidebar-section">Master Data</div>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('countries.*') ? 'active' : '' }}" href="{{ route('countries.index') }}">
                        <i class="bi bi-flag"></i> Countries
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('visit-purposes.*') ? 'active' : '' }}" href="{{ route('visit-purposes.index') }}">
                        <i class="bi bi-bookmark"></i> Visit Purposes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('age-groups.*') ? 'active' : '' }}" href="{{ route('age-groups.index') }}">
                        <i class="bi bi-person-lines-fill"></i> Age Groups
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('travel-types.*') ? 'active' : '' }}" href="{{ route('travel-types.index') }}">
                        <i class="bi bi-airplane"></i> Travel Types
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('budget-categories.*') ? 'active' : '' }}" href="{{ route('budget-categories.index') }}">
                        <i class="bi bi-wallet2"></i> Budget Categories
                    </a>
                </li>

                @if(auth()->user()->isAdmin())
                <div class="sidebar-section">Administration</div>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                        <i class="bi bi-people"></i> Users
                    </a>
                </li>
                @endif
            </ul>
        </nav>

        {{-- Main Content --}}
        <div class="main-content flex-grow-1">
            <div class="top-bar">
                <h5 class="mb-0">@yield('page-title', 'Dashboard')</h5>
                <div class="d-flex align-items-center gap-3">
                    <span class="text-muted">
                        <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                        <span class="badge bg-{{ auth()->user()->isAdmin() ? 'danger' : 'primary' }}">{{ ucfirst(auth()->user()->role) }}</span>
                    </span>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </div>
            </div>

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    @stack('scripts')
</body>
</html>
