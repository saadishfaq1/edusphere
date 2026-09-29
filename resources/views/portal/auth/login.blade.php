<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Parent Login | {{ app('tenant')->name }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="d-flex align-items-center justify-content-center min-vh-100 bg-light">
        <div class="card shadow-sm" style="width: 380px;">
            <div class="card-body p-4">
                <div class="text-center mb-3">
                    @if (app('tenant')->logo_path)
                        <img src="{{ asset('storage/'.app('tenant')->logo_path) }}" height="50">
                    @else
                        <h5>{{ app('tenant')->name }}</h5>
                    @endif
                </div>
                <div class="text-center mb-4">
                    <h5>Parent Portal</h5>
                    <p class="text-muted">Sign in to view your child's progress.</p>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach
                    </div>
                @endif
                <form method="POST" action="{{ route('portal.login.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button class="btn btn-primary w-100">Sign In</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>