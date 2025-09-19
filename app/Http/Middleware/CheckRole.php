<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            // Handle unauthenticated API requests
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Chưa đăng nhập. Vui lòng đăng nhập để tiếp tục.',
                    'error' => 'unauthenticated'
                ], 401);
            }
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // For debugging: Log the role check
        \Log::info('Role Check', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_role' => $user->role,
            'required_role' => $role,
            'match' => $user->role === $role
        ]);
        
        // Check if user has role attribute and if it matches the required role
        if (!isset($user->role) || $user->role !== $role) {
            $errorMessage = sprintf(
                'Lỗi quyền truy cập: Bạn có quyền "%s" nhưng trang này yêu cầu quyền "%s".',
                $user->role ?? 'không xác định',
                $role
            );
            
            // Check if this is an API request
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'error' => 'insufficient_permissions',
                    'required_role' => $role,
                    'user_role' => $user->role ?? null
                ], 403);
            }
            
            // For web requests, redirect to appropriate dashboard
            if ($user->role === 'admin') {
                return redirect('/admin/dashboard')->with('error', $errorMessage);
            } elseif ($user->role === 'agent') {
                return redirect('/agent/dashboard')->with('error', $errorMessage);
            } elseif ($user->role === 'shipper') {
                return redirect('/shipper/dashboard')->with('error', $errorMessage);
            } else {
                return redirect('/user/dashboard')->with('error', $errorMessage);
            }
        }

        return $next($request);
    }
}