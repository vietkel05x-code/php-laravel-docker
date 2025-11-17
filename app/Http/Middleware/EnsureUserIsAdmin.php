<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra đã đăng nhập admin chưa (dùng session key riêng)
        if (!$request->session()->has('admin_logged_in') || !$request->session()->get('admin_user_id')) {
            return redirect()->route('admin.login')->with('error', 'Vui lòng đăng nhập để truy cập trang quản trị.');
        }

        // Lấy user từ session
        $adminUser = \App\Models\User::find($request->session()->get('admin_user_id'));

        // Kiểm tra user tồn tại và là admin
        if (!$adminUser || !$adminUser->isAdmin()) {
            $request->session()->forget('admin_logged_in');
            $request->session()->forget('admin_user_id');
            return redirect()->route('admin.login')->with('error', 'Phiên đăng nhập không hợp lệ. Vui lòng đăng nhập lại.');
        }

        // Set user vào request để sử dụng trong controller
        $request->setUserResolver(function () use ($adminUser) {
            return $adminUser;
        });

        return $next($request);
    }
}
