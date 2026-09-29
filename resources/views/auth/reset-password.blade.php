<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Reset Password | {{ app('tenant')->name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
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
                        <img src="{{ asset('storage/'.app('tenant')->logo_path) }}" alt="{{ app('tenant')->name }}" height="50">
                    @else
                        <h5 class="text-primary mb-0">{{ app('tenant')->name }}</h5>
                    @endif
                </div>

                <div class="text-center mb-4">
                    <h5 class="mb-1">Reset Password</h5>
                    <p class="text-muted">Choose a new password for your account.</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $email }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <button class="btn btn-primary w-100" type="submit">Reset Password</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>