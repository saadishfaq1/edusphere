<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Platform Login | EDUSphere</title>
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('assets-elite/css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets-elite/css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('assets-elite/css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('assets-elite/css/pages.css') }}">
</head>
<body>
    <div class="d-flex align-items-center justify-content-center min-vh-100 bg-light">
        <div class="card shadow-sm" style="width: 400px;">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <i class="bi bi-hexagon-half fs-1 text-primary"></i>
                    <h4 class="mt-2 mb-0">EDUSphere Platform</h4>
                    <p class="text-muted">Sign in to manage schools and plans.</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('central.login.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Sign In</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
