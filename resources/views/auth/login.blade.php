<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | {{ app('tenant')->name }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('assets-elite/css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets-elite/css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('assets-elite/css/components.css') }}">

    <style>
        :root { --bs-primary: {{ app('tenant')->primary_color ?? '#556ee6' }}; }
        .btn-primary { background-color: {{ app('tenant')->primary_color ?? '#556ee6' }}; border-color: {{ app('tenant')->primary_color ?? '#556ee6' }}; }
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body>
    <div class="d-flex align-items-center justify-content-center min-vh-100 bg-light">
        <div class="card shadow-sm" style="width: 400px;">
            <div class="card-body p-4">
                <div class="text-center mb-3">
                    @if (app('tenant')->logo_path)
                        <img src="{{ asset('storage/'.app('tenant')->logo_path) }}" alt="{{ app('tenant')->name }}" height="60">
                    @else
                        <h4 class="text-primary mb-0">{{ app('tenant')->name }}</h4>
                    @endif
                </div>

                <div class="text-center mb-4">
                    <h5 class="mb-1">Welcome Back</h5>
                    <p class="text-muted">Sign in to continue to {{ app('tenant')->name }}.</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <label class="form-label">Password</label>
                            <a href="{{ route('password.request') }}" class="text-muted small">Forgot password?</a>
                        </div>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Sign In</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>