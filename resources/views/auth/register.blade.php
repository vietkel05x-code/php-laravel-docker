@extends('layouts.app')

@section('title', 'Đăng ký')

@section('content')
<div class="auth-container">
    <h2>Đăng ký</h2>
    <form method="POST" action="/register">
        @csrf
        <label>Tên</label>
        <input type="text" name="name" value="{{ old('name') }}" required>

        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required>

        <label>Mật khẩu</label>
        <input type="password" name="password" required>

        <label>Nhập lại mật khẩu</label>
        <input type="password" name="password_confirmation" required>

        @error('email')
            <p class="error">{{ $message }}</p>
        @enderror

        <button type="submit">Đăng ký</button>
        <p>Đã có tài khoản? <a href="/login">Đăng nhập</a></p>
    </form>
</div>
@endsection
