<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ur' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'Dashboard') | {{ app('tenant')->name }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('assets-elite/css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets-elite/css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('assets-elite/css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('assets-elite/css/pages.css') }}">

    <style>
        :root { --bs-primary: {{ app('tenant')->primary_color ?? '#556ee6' }}; }
        .btn-primary { background-color: {{ app('tenant')->primary_color ?? '#556ee6' }}; border-color: {{ app('tenant')->primary_color ?? '#556ee6' }}; }
    </style>
</head>
<body>
<div class="app-wrapper">

    @include('partials.elite-sidebar')

    <div class="app-content">
        @include('partials.elite-topbar')

        <main class="app-main">
            <div class="page-header">
                <div>
                    <h1>@yield('title', 'Dashboard')</h1>
                </div>
            </div>

            @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
            @if (session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

            @if (auth()->check() && app('tenant')->status === 'trial' && app('tenant')->trial_ends_at)
                @php($daysLeft = now()->diffInDays(app('tenant')->trial_ends_at, false))
                @if ($daysLeft <= 5 && $daysLeft >= 0)
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        Your free trial ends in {{ $daysLeft }} day{{ $daysLeft === 1 ? '' : 's' }}. Contact support to upgrade and avoid losing access.
                    </div>
                @endif
            @endif

            @yield('content')
        </main>

        <footer class="app-footer">
            <div>© {{ date('Y') }} {{ app('tenant')->name }} — EDUSphere.</div>
            <div>Powered by EDUSphere</div>
        </footer>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets-elite/js/app.js') }}"></script>
@include('partials.confirm-delete-modal')
@stack('scripts')
</body>
</html>

