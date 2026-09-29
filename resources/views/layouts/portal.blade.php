<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Parent Portal') | {{ app('tenant')->name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root { --bs-primary: {{ app('tenant')->primary_color ?? '#556ee6' }}; }
        .btn-primary { background-color: {{ app('tenant')->primary_color ?? '#556ee6' }}; border-color: {{ app('tenant')->primary_color ?? '#556ee6' }}; }
        body { font-family: 'Inter', sans-serif; background: #f4f6f9; }
    </style>
</head>
<body>
    <nav class="navbar navbar-light bg-white shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('portal.dashboard') }}">
                @if (app('tenant')->logo_path)
                    <img src="{{ asset('storage/'.app('tenant')->logo_path) }}" height="32" class="me-2">
                @endif
                {{ app('tenant')->name }} — Parent Portal
            </a>
            <div>
                <span class="text-muted me-3">{{ auth('guardian')->user()->name }}</span>
                <form method="POST" action="{{ route('portal.logout') }}" class="d-inline">
                    @csrf
                    <button class="btn btn-sm btn-outline-secondary">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        @yield('content')
    </div>
</body>
</html>