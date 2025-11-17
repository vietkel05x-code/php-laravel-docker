@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')
<div class="auth-container">
    <h2>Đăng nhập</h2>
    <form method="POST" action="/login">
        @csrf
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required>

        <label>Mật khẩu</label>
        <input type="password" name="password" required>

        <div class="remember">
            <input type="checkbox" name="remember"> Ghi nhớ đăng nhập
        </div>

        @error('email')
            <p class="error">{{ $message }}</p>
        @enderror

        <button type="submit">Đăng nhập</button>

        <p>Chưa có tài khoản? <a href="/register">Đăng ký</a></p>
    </form>
</div>
@endsection
