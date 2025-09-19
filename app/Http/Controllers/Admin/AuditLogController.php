<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AuditLogController extends Controller
{
    public function index()
    {
        return view('admin.audit.index');
    }

    public function getLogs(Request $request)
    {
        // For now, return mock audit logs since we don't have the AuditLog model yet
        // In a real implementation, you would query the audit_logs table
        $logs = collect([
            [
                'id' => 1,
                'user_id' => Auth::id(),
                'user_name' => Auth::user() ? Auth::user()->name : 'Unknown',
                'action' => 'user.created',
                'description' => 'Tạo người dùng mới: john@example.com',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => Carbon::now()->subMinutes(5),
                'details' => [
                    'user_email' => 'john@example.com',
                    'user_role' => 'user'
                ]
            ],
            [
                'id' => 2,
                'user_id' => Auth::id(),
                'user_name' => Auth::user() ? Auth::user()->name : 'Unknown',
                'action' => 'order.status_updated',
                'description' => 'Cập nhật trạng thái đơn hàng #1001 từ "pending" thành "confirmed"',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => Carbon::now()->subMinutes(10),
                'details' => [
                    'order_id' => 1001,
                    'old_status' => 'pending',
                    'new_status' => 'confirmed'
                ]
            ],
            [
                'id' => 3,
                'user_id' => Auth::id(),
                'user_name' => Auth::user() ? Auth::user()->name : 'Unknown',
                'action' => 'settings.updated',
                'description' => 'Cập nhật cài đặt hệ thống - phần shipping',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => Carbon::now()->subMinutes(15),
                'details' => [
                    'category' => 'shipping',
                    'fields_updated' => ['default_shipping_fee', 'insurance_rate']
                ]
            ],
            [
                'id' => 4,
                'user_id' => Auth::id(),
                'user_name' => Auth::user() ? Auth::user()->name : 'Unknown',
                'action' => 'login.success',
                'description' => 'Đăng nhập thành công vào hệ thống',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => Carbon::now()->subMinutes(30),
                'details' => [
                    'login_method' => 'email'
                ]
            ],
            [
                'id' => 5,
                'user_id' => null,
                'user_name' => 'System',
                'action' => 'system.backup',
                'description' => 'Thực hiện sao lưu dữ liệu tự động',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'System',
                'created_at' => Carbon::now()->subHours(1),
                'details' => [
                    'backup_type' => 'daily',
                    'backup_size' => '125.6 MB'
                ]
            ]
        ]);

        // Apply filters
        if ($request->has('action') && $request->action) {
            $logs = $logs->where('action', 'like', '%' . $request->action . '%');
        }

        if ($request->has('user_id') && $request->user_id) {
            $logs = $logs->where('user_id', $request->user_id);
        }

        if ($request->has('date_from') && $request->date_from) {
            $dateFrom = Carbon::parse($request->date_from)->startOfDay();
            $logs = $logs->where('created_at', '>=', $dateFrom);
        }

        if ($request->has('date_to') && $request->date_to) {
            $dateTo = Carbon::parse($request->date_to)->endOfDay();
            $logs = $logs->where('created_at', '<=', $dateTo);
        }

        // Pagination
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 15);
        $offset = ($page - 1) * $perPage;

        $paginatedLogs = $logs->slice($offset, $perPage)->values();

        return response()->json([
            'data' => $paginatedLogs,
            'total' => $logs->count(),
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($logs->count() / $perPage)
        ]);
    }

    public function getStatistics()
    {
        // Mock statistics for audit logs
        $stats = [
            'total_actions' => 150,
            'today_actions' => 25,
            'unique_users' => 12,
            'top_actions' => [
                ['action' => 'order.status_updated', 'count' => 45],
                ['action' => 'user.login', 'count' => 38],
                ['action' => 'user.created', 'count' => 22],
                ['action' => 'settings.updated', 'count' => 15],
                ['action' => 'order.created', 'count' => 30]
            ],
            'activity_timeline' => [
                ['hour' => '08:00', 'count' => 5],
                ['hour' => '09:00', 'count' => 12],
                ['hour' => '10:00', 'count' => 8],
                ['hour' => '11:00', 'count' => 15],
                ['hour' => '12:00', 'count' => 6],
                ['hour' => '13:00', 'count' => 9],
                ['hour' => '14:00', 'count' => 18],
                ['hour' => '15:00', 'count' => 14],
            ]
        ];

        return response()->json($stats);
    }

    public function exportLogs(Request $request)
    {
        // Get logs with filters
        $logs = $this->getLogs($request)->getData()->data;
        
        $csvData = [];
        $csvData[] = ['ID', 'User', 'Action', 'Description', 'IP Address', 'Date/Time'];
        
        foreach ($logs as $log) {
            $csvData[] = [
                $log->id,
                $log->user_name,
                $log->action,
                $log->description,
                $log->ip_address,
                Carbon::parse($log->created_at)->format('Y-m-d H:i:s')
            ];
        }
        
        $filename = 'audit_logs_' . date('Y-m-d_H-i-s') . '.csv';
        
        $handle = fopen('php://temp', 'w+');
        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename={$filename}");
    }

    public function show($id)
    {
        // Return detailed log information
        $log = [
            'id' => $id,
            'user_id' => Auth::id(),
            'user_name' => Auth::user() ? Auth::user()->name : 'Unknown',
            'action' => 'user.created',
            'description' => 'Tạo người dùng mới: john@example.com',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => Carbon::now()->subMinutes(5),
            'details' => [
                'user_email' => 'john@example.com',
                'user_role' => 'user',
                'additional_info' => 'User was created via admin panel'
            ]
        ];
        
        return response()->json($log);
    }

    // Helper method to log actions (to be used throughout the application)
    public static function logAction($action, $description, $details = [], $userId = null)
    {
        // In a real implementation, this would save to the audit_logs table
        // For now, we'll just log to Laravel's log files
        Log::info('Audit Log', [
            'user_id' => $userId ?: Auth::id(),
            'action' => $action,
            'description' => $description,
            'details' => $details,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()
        ]);
    }
}