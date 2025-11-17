<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        return view('admin.profile.show');
    }

    public function edit()
    {
        return view('admin.profile.edit');
    }

    public function update(Request $request)
    {
        // Lấy admin user từ session
        $user = \App\Helpers\AdminHelper::user();
        
        if (!$user) {
            return redirect()->route('admin.login')->with('error', 'Phiên đăng nhập đã hết hạn.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // email locked (no edit allowed)
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update name only (email not editable)
        $user->name = $validated['name'];
        // Ignore any posted email field silently

        // Update password if provided
        if ($request->filled('new_password')) {
            if (!Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng']);
            }
            $user->password = Hash::make($validated['new_password']);
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return redirect()->route('admin.profile.show')->with('success', 'Cập nhật thông tin thành công!');
    }
}

