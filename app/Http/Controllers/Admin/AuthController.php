<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Hiển thị form đăng nhập admin
     */
    public function showLoginForm(Request $request)
    {
        // Kiểm tra đã đăng nhập admin chưa (dùng session key riêng)
        if ($request->session()->has('admin_logged_in') && $request->session()->get('admin_user_id')) {
            $adminUser = \App\Models\User::find($request->session()->get('admin_user_id'));
            if ($adminUser && $adminUser->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
        }

        return view('admin.auth.login');
    }

    /**
     * Xử lý đăng nhập admin
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Tìm user
        $user = \App\Models\User::where('email', $credentials['email'])->first();

        // Kiểm tra user tồn tại và mật khẩu đúng
        if ($user && \Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
            // Kiểm tra user có phải admin không
            if ($user->isAdmin()) {
                // Lưu admin login vào session riêng (không dùng Auth::login để tránh conflict với public)
                $request->session()->put('admin_logged_in', true);
                $request->session()->put('admin_user_id', $user->id);
                $request->session()->regenerate();

                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', 'Đăng nhập thành công!');
            } else {
                return back()->withErrors([
                    'email' => 'Tài khoản này không có quyền truy cập trang quản trị.',
                ])->onlyInput('email');
            }
        }

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không đúng.',
        ])->onlyInput('email');
    }

    /**
     * Đăng xuất admin
     */
    public function logout(Request $request)
    {
        // Xóa session admin (không ảnh hưởng đến public login)
        $request->session()->forget('admin_logged_in');
        $request->session()->forget('admin_user_id');
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Đã đăng xuất thành công!');
    }
}

