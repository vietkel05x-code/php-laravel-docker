<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Admin - E-Learning</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/auth.css') }}">
</head>
<body class="admin-login-page">
    <div class="admin-login-container">
        <div class="admin-login-header">
            <div class="admin-login-logo">E</div>
            <h1 class="admin-login-title">Admin Panel</h1>
            <p class="admin-login-subtitle">Đăng nhập để quản trị hệ thống</p>
        </div>

        <div class="admin-login-body">
            @if(session('success'))
                <div class="admin-login-alert admin-login-alert--success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="admin-login-alert admin-login-alert--error">
                    <i class="fas fa-exclamation-circle"></i>
                    @foreach($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}">
                @csrf

                <div class="admin-login-form-group">
                    <label class="admin-login-form-label" for="email">Email</label>
                    <div class="admin-login-form-input-wrapper">
                        <i class="fas fa-envelope admin-login-form-icon"></i>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="admin-login-form-input" 
                            value="{{ old('email') }}" 
                            placeholder="admin@example.com"
                            required
                            autofocus
                        >
                    </div>
                    @error('email')
                        <div class="admin-login-error-message">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="admin-login-form-group">
                    <label class="admin-login-form-label" for="password">Mật khẩu</label>
                    <div class="admin-login-form-input-wrapper">
                        <i class="fas fa-lock admin-login-form-icon"></i>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="admin-login-form-input" 
                            placeholder="••••••••"
                            required
                        >
                    </div>
                    @error('password')
                        <div class="admin-login-error-message">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="admin-login-form-checkbox">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Ghi nhớ đăng nhập</label>
                </div>

                <button type="submit" class="admin-login-btn">
                    <i class="fas fa-sign-in-alt admin-login-btn__icon"></i>
                    Đăng nhập
                </button>
            </form>

            <div class="admin-login-back-link">
                <a href="{{ route('home') }}">
                    <i class="fas fa-arrow-left admin-login-back-link__icon"></i>
                    Về trang chủ
                </a>
            </div>
        </div>
    </div>
</body>
</html>
