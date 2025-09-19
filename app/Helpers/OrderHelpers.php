<?php

if (!function_exists('getStatusIcon')) {
    function getStatusIcon($status) {
        $icons = [
            'pending' => '<i class="fas fa-clock text-yellow-500"></i>',
            'confirmed' => '<i class="fas fa-check text-blue-500"></i>',
            'assigned' => '<i class="fas fa-user-plus text-purple-500"></i>',
            'pickup' => '<i class="fas fa-truck text-blue-500"></i>', 
            'picked_up' => '<i class="fas fa-box text-green-500"></i>',
            'in_transit' => '<i class="fas fa-shipping-fast text-purple-500"></i>',
            'delivering' => '<i class="fas fa-truck-loading text-orange-500"></i>',
            'delivered' => '<i class="fas fa-check-circle text-green-500"></i>',
            'failed' => '<i class="fas fa-times-circle text-red-500"></i>',
            'returned' => '<i class="fas fa-undo text-gray-500"></i>',
            'cancelled' => '<i class="fas fa-ban text-red-500"></i>'
        ];

        return $icons[$status] ?? '<i class="fas fa-question-circle text-gray-500"></i>';
    }
}

if (!function_exists('getStatusBadge')) {
    function getStatusBadge($status) {
        $badges = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'confirmed' => 'bg-blue-100 text-blue-800',
            'assigned' => 'bg-purple-100 text-purple-800',
            'pickup' => 'bg-blue-100 text-blue-800',
            'picked_up' => 'bg-green-100 text-green-800',
            'in_transit' => 'bg-purple-100 text-purple-800',
            'delivering' => 'bg-orange-100 text-orange-800', 
            'delivered' => 'bg-green-100 text-green-800',
            'failed' => 'bg-red-100 text-red-800',
            'returned' => 'bg-gray-100 text-gray-800',
            'cancelled' => 'bg-red-100 text-red-800'
        ];

        $statusText = [
            'pending' => 'Chờ xử lý',
            'confirmed' => 'Đã xác nhận',
            'assigned' => 'Đã phân công',
            'pickup' => 'Đang lấy hàng',
            'picked_up' => 'Đã lấy hàng',
            'in_transit' => 'Đang vận chuyển',
            'delivering' => 'Đang giao',
            'delivered' => 'Đã giao', 
            'failed' => 'Thất bại',
            'returned' => 'Đã trả lại',
            'cancelled' => 'Đã hủy'
        ];

        $class = $badges[$status] ?? 'bg-gray-100 text-gray-800';
        $text = $statusText[$status] ?? 'Không xác định';

        return sprintf(
            '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full %s">%s</span>',
            $class,
            $text
        );
    }
}
