<?php

namespace App\Helpers;

use App\Models\User;

class AdminHelper
{
    /**
     * Lấy admin user hiện tại từ session
     */
    public static function user()
    {
        if (session()->has('admin_logged_in') && session()->get('admin_user_id')) {
            return User::find(session()->get('admin_user_id'));
        }
        return null;
    }

    /**
     * Kiểm tra đã đăng nhập admin chưa
     */
    public static function check()
    {
        $user = self::user();
        return $user && $user->isAdmin();
    }
}

