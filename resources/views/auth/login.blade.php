<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tourism Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1e3a5f 0%, #2c5282 50%, #63b3ed 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            max-width: 420px;
            width: 100%;
        }
        .login-header {
            background: linear-gradient(135deg, #1e3a5f, #2c5282);
            color: #fff;
            padding: 30px;
            text-align: center;
        }
        .login-header i { font-size: 2.5rem; margin-bottom: 10px; display: block; }
        .login-body { padding: 30px; }
        .form-control:focus { border-color: #2c5282; box-shadow: 0 0 0 0.2rem rgba(44,82,130,0.25); }
        .btn-login { background: #2c5282; border: none; padding: 12px; font-size: 1rem; }
        .btn-login:hover { background: #1e3a5f; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <i class="bi bi-globe-americas"></i>
            <h4 class="mb-1">Tourism Management System</h4>
            <p class="mb-0 opacity-75">Sign in to your account</p>
        </div>
        <div class="login-body">
            @if($errors->any())
                <div class="alert alert-danger py-2">
                    @foreach($errors->all() as $error)
                        <small><i class="bi bi-exclamation-circle"></i> {{ $error }}</small><br>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Enter your email">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" required placeholder="Enter your password">
                    </div>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <button type="submit" class="btn btn-primary btn-login w-100">
                    <i class="bi bi-box-arrow-in-right"></i> Sign In
                </button>
            </form>
        </div>
    </div>
</body>
</html>
