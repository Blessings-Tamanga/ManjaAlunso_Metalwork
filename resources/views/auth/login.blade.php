<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | ManjaAlunso Metalworks</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.1.0/fonts/remixicon.css" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: var(--bg);
        }
        .auth-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
        }
        .auth-card h1 {
            margin-bottom: 0.5rem;
            text-align: center;
        }
        .auth-card .subtitle {
            text-align: center;
            color: var(--text-secondary);
            margin-bottom: 2rem;
        }
        .auth-card .btn {
            width: 100%;
            justify-content: center;
        }
        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }
        .auth-footer a {
            color: var(--accent);
            text-decoration: none;
        }
        .alert {
            padding: 0.75rem 1rem;
            border-radius: var(--radius-sm);
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }
        .alert-danger {
            background: #dc2626;
            color: white;
        }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <h1>Welcome Back</h1>
            <p class="subtitle">Sign in to your admin account</p>

            @if($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        Remember Me
                    </label>
                </div>
                <button type="submit" class="btn btn-primary">Sign In</button>
            </form>

            <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
                <a href="{{ route('socialite.redirect', 'google') }}" class="btn btn-outline" style="flex: 1; justify-content: center; color: #ea4335; border-color: #ea4335;">
                    <i class="ri-google-fill"></i> Google
                </a>
                <a href="{{ route('socialite.redirect', 'facebook') }}" class="btn btn-outline" style="flex: 1; justify-content: center; color: #1877f2; border-color: #1877f2;">
                    <i class="ri-facebook-fill"></i> Facebook
                </a>
            </div>

            <div class="auth-footer">
                <p>Don't have an account? <a href="#">Contact admin</a></p>
            </div>
        </div>
    </div>
</body>
</html>
