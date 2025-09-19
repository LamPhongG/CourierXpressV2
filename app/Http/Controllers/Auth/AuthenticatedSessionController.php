<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Hiển thị form đăng nhập.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Xử lý đăng nhập.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        // Check if user exists
        $user = \App\Models\User::where('email', $request->email)->first();
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'Không tìm thấy tài khoản với email này.',
            ]);
        }

        // Check if user is active
        if ($user->status !== 'active') {
            throw ValidationException::withMessages([
                'email' => 'Tài khoản đã bị khóa hoặc chưa kích hoạt.',
            ]);
        }

        // Attempt login
        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'password' => 'Mật khẩu không đúng.',
            ]);
        }

        $request->session()->regenerate();

        // Chuyển hướng dựa trên vai trò
        $user = Auth::user();
        
        // Store user data in session for JavaScript access
        $request->session()->put('user_data', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'status' => $user->status
        ]);
        
        switch ($user->role ?? 'user') {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'shipper':
                return redirect()->route('shipper.dashboard');
            case 'agent':
                return redirect()->route('agent.dashboard');
            default:
                return redirect()->route('user.dashboard');
        }
    }

    /**
     * Đăng xuất.
     */
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Clear any stored user data
        $request->session()->forget('user_data');

        // Redirect to home page
        return redirect('/')->with('success', 'Đăng xuất thành công!');
    }
}