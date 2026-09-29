<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'Dashboard') | EDUSphere Platform</title>

    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('assets-elite/css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets-elite/css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('assets-elite/css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('assets-elite/css/pages.css') }}">

    @php($platformSettings = \App\Models\PlatformSetting::current())
    <style>
        :root { --bs-primary: {{ $platformSettings->primary_color }}; }
        .btn-primary { background-color: {{ $platformSettings->primary_color }}; border-color: {{ $platformSettings->primary_color }}; }
    </style>
</head>
<body>
<div class="app-wrapper">

    <aside class="app-sidebar" id="appSidebar">
        <a href="{{ route('central.dashboard') }}" class="sidebar-brand">
            @if ($platformSettings->logo_path)
                <img src="{{ asset('storage/'.$platformSettings->logo_path) }}" height="28">
            @else
                <span class="brand-icon"><i class="bi bi-hexagon-half"></i></span>
                <span class="brand-text">EDU<span>Sphere</span></span>
            @endif
        </a>
        <nav class="sidebar-nav">
            <ul class="sidebar-menu">
                <li class="sidebar-heading">Platform</li>
                <li class="menu-item">
                    <a class="menu-link" href="{{ route('central.dashboard') }}">
                        <i class="bi bi-house-door"></i><span class="menu-text">Dashboard</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a class="menu-link" href="{{ route('central.schools') }}">
                        <i class="bi bi-buildings"></i><span class="menu-text">Schools</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a class="menu-link" href="{{ route('central.organizations') }}">
                        <i class="bi bi-diagram-3"></i><span class="menu-text">Organizations</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a class="menu-link" href="{{ route('central.plans') }}">
                        <i class="bi bi-boxes"></i><span class="menu-text">Plans</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a class="menu-link" href="{{ route('central.settings') }}">
                        <i class="bi bi-gear"></i><span class="menu-text">Settings</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <div class="app-content">
        <header class="app-header">
            <div class="header-inner">
                <button class="menu-toggle" data-toggle="sidebar" type="button" aria-label="Toggle navigation">
                    <i class="bi bi-list"></i>
                </button>

                <div class="header-actions">
                    <button class="header-action-btn" data-toggle="theme" type="button" aria-label="Toggle theme">
                        <i class="bi bi-moon-stars"></i>
                    </button>
                    <div class="dropdown">
                        <button class="header-profile" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="avatar avatar-sm"><i class="bi bi-person-circle fs-4"></i></span>
                            <span class="profile-info">
                                <span class="name d-block">{{ auth()->user()->name }}</span>
                                <span class="role">Platform Admin</span>
                            </span>
                            <i class="bi bi-chevron-down ms-1 fs-12"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end profile-dropdown">
                            <div class="profile-head">
                                <div>
                                    <div class="fw-semibold">{{ auth()->user()->name }}</div>
                                    <div class="fs-12 text-muted">{{ auth()->user()->email }}</div>
                                </div>
                            </div>
                            <ul>
                                <li>
                                    <form method="POST" action="{{ route('central.logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item border-0 bg-transparent w-100 text-start text-danger">
                                            <i class="bi bi-box-arrow-right"></i> Sign Out
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="app-main">
            <div class="page-header">
                <div><h1>@yield('title', 'Dashboard')</h1></div>
            </div>

            @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
            @if (session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

            @yield('content')
        </main>

        <footer class="app-footer">
            <div>© {{ date('Y') }} EDUSphere Platform.</div>
        </footer>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets-elite/js/app.js') }}"></script>
@include('partials.confirm-delete-modal')
@stack('scripts')
</body>
</html>