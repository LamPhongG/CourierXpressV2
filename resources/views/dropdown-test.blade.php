@extends('layouts.app')

@section('content')
<div class="pt-20 min-h-screen bg-gray-50">
    <!-- Test Page -->
    <div class="py-20">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h1 class="text-5xl font-bold mb-8 text-gray-800">Test Dropdown Menu</h1>
                <p class="text-xl text-gray-600 mb-8">Header 1 hàng thẳng với đầy đủ dropdown chức năng</p>
                
                <!-- Language Test -->
                <div class="bg-orange-100 p-6 rounded-lg mb-8">
                    <h2 class="text-2xl font-bold mb-4 text-orange-800">Language Test</h2>
                    <p class="text-orange-700 mb-4">Current language: 
                        @if(Session::get('locale', 'vi') == 'vi')
                            <strong>Vietnam - Tiếng Việt</strong>
                        @elseif(Session::get('locale', 'vi') == 'en')
                            <strong>Vietnam - English</strong>
                        @else
                            <strong>Vietnam - हिंदी</strong>
                        @endif
                    </p>
                    <p class="text-orange-600">Click language selector ở góc phải để test!</p>
                </div>
            </div>

            <!-- Dropdown Test Grid -->
            <div class="grid md:grid-cols-2 lg:grid-cols-5 gap-8">
                
                <!-- Delivery Services -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-lg font-bold mb-4 text-gray-800 text-center">{{ __('lalamove.delivery') }}</h3>
                    <div class="space-y-3">
                        <a href="{{ route('dich-vu.giao-hang') }}" class="block p-3 bg-orange-50 rounded hover:bg-orange-100 transition duration-300">
                            <div class="font-semibold text-orange-800">{{ __('messages.delivery_service') }}</div>
                            <div class="text-xs text-gray-600">{{ __('messages.delivery_service_desc') }}</div>
                        </a>
                        <a href="{{ route('dich-vu.xe-tai') }}" class="block p-3 bg-orange-50 rounded hover:bg-orange-100 transition duration-300">
                            <div class="font-semibold text-orange-800">{{ __('messages.truck_service') }}</div>
                            <div class="text-xs text-gray-600">{{ __('messages.truck_service_desc') }}</div>
                        </a>
                        <a href="{{ route('dich-vu.chuyen-nha') }}" class="block p-3 bg-orange-50 rounded hover:bg-orange-100 transition duration-300">
                            <div class="font-semibold text-orange-800">{{ __('messages.moving_service') }}</div>
                            <div class="text-xs text-gray-600">{{ __('messages.moving_service_desc') }}</div>
                        </a>
                        <a href="{{ route('dich-vu.doanh-nghiep') }}" class="block p-3 bg-orange-50 rounded hover:bg-orange-100 transition duration-300">
                            <div class="font-semibold text-orange-800">{{ __('messages.enterprise_service') }}</div>
                            <div class="text-xs text-gray-600">{{ __('messages.enterprise_service_desc') }}</div>
                        </a>
                    </div>
                </div>

                <!-- Customers -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-lg font-bold mb-4 text-gray-800 text-center">{{ __('messages.customers') }}</h3>
                    <div class="space-y-3">
                        <a href="{{ route('khach-hang.ca-nhan') }}" class="block p-3 bg-blue-50 rounded hover:bg-blue-100 transition duration-300">
                            <div class="font-semibold text-blue-800">{{ __('messages.individual_customer') }}</div>
                            <div class="text-xs text-gray-600">{{ __('messages.individual_customer_desc') }}</div>
                        </a>
                        <a href="{{ route('khach-hang.doanh-nghiep') }}" class="block p-3 bg-blue-50 rounded hover:bg-blue-100 transition duration-300">
                            <div class="font-semibold text-blue-800">{{ __('messages.business_customer') }}</div>
                            <div class="text-xs text-gray-600">{{ __('messages.business_customer_desc') }}</div>
                        </a>
                        <a href="{{ route('cong-dong.ho-tro') }}" class="block p-3 bg-blue-50 rounded hover:bg-blue-100 transition duration-300">
                            <div class="font-semibold text-blue-800">{{ __('messages.community_support') }}</div>
                            <div class="text-xs text-gray-600">{{ __('messages.community_support_desc') }}</div>
                        </a>
                    </div>
                </div>

                <!-- Drivers -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-lg font-bold mb-4 text-gray-800 text-center">{{ __('messages.drivers') }}</h3>
                    <div class="space-y-3">
                        <a href="{{ route('tai-xe.dang-ky') }}" class="block p-3 bg-green-50 rounded hover:bg-green-100 transition duration-300">
                            <div class="font-semibold text-green-800">{{ __('messages.driver_registration') }}</div>
                            <div class="text-xs text-gray-600">{{ __('messages.driver_registration_desc') }}</div>
                        </a>
                        <a href="{{ route('tai-xe.cong-dong') }}" class="block p-3 bg-green-50 rounded hover:bg-green-100 transition duration-300">
                            <div class="font-semibold text-green-800">{{ __('messages.driver_community') }}</div>
                            <div class="text-xs text-gray-600">{{ __('messages.driver_community_desc') }}</div>
                        </a>
                        <a href="{{ route('tai-xe.cam-nang') }}" class="block p-3 bg-green-50 rounded hover:bg-green-100 transition duration-300">
                            <div class="font-semibold text-green-800">{{ __('messages.driver_handbook') }}</div>
                            <div class="text-xs text-gray-600">{{ __('messages.driver_handbook_desc') }}</div>
                        </a>
                    </div>
                </div>

                <!-- Support -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-lg font-bold mb-4 text-gray-800 text-center">{{ __('messages.support') }}</h3>
                    <div class="space-y-3">
                        <a href="{{ route('ho-tro.khach-hang') }}" class="block p-3 bg-purple-50 rounded hover:bg-purple-100 transition duration-300">
                            <div class="font-semibold text-purple-800">{{ __('messages.customer_support') }}</div>
                            <div class="text-xs text-gray-600">{{ __('messages.customer_support_desc') }}</div>
                        </a>
                        <a href="{{ route('ho-tro.tai-xe') }}" class="block p-3 bg-purple-50 rounded hover:bg-purple-100 transition duration-300">
                            <div class="font-semibold text-purple-800">{{ __('messages.driver_support') }}</div>
                            <div class="text-xs text-gray-600">{{ __('messages.driver_support_desc') }}</div>
                        </a>
                    </div>
                </div>

                <!-- Company/Recruitment -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-lg font-bold mb-4 text-gray-800 text-center">{{ __('messages.recruitment') }}</h3>
                    <div class="space-y-3">
                        <a href="{{ route('tuyen-dung.ve-chung-toi') }}" class="block p-3 bg-red-50 rounded hover:bg-red-100 transition duration-300">
                            <div class="font-semibold text-red-800">{{ __('messages.about_us') }}</div>
                            <div class="text-xs text-gray-600">{{ __('messages.about_us_desc') }}</div>
                        </a>
                        <a href="{{ route('tuyen-dung.cau-chuyen') }}" class="block p-3 bg-red-50 rounded hover:bg-red-100 transition duration-300">
                            <div class="font-semibold text-red-800">{{ __('messages.our_story') }}</div>
                            <div class="text-xs text-gray-600">{{ __('messages.our_story_desc') }}</div>
                        </a>
                        <a href="{{ route('tuyen-dung.gia-nhap') }}" class="block p-3 bg-red-50 rounded hover:bg-red-100 transition duration-300">
                            <div class="font-semibold text-red-800">{{ __('messages.join_us') }}</div>
                            <div class="text-xs text-gray-600">{{ __('messages.join_us_desc') }}</div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Instructions -->
            <div class="mt-16 text-center">
                <div class="bg-white p-8 rounded-lg shadow-lg max-w-2xl mx-auto">
                    <h2 class="text-2xl font-bold mb-4 text-gray-800">Hướng dẫn test</h2>
                    <div class="text-left space-y-3 text-gray-600">
                        <p><strong>1.</strong> Hover vào các menu ở header để xem dropdown</p>
                        <p><strong>2.</strong> Click language selector "Vietnam - English" ở góc phải</p>
                        <p><strong>3.</strong> Chọn ngôn ngữ khác để thấy toàn bộ nội dung thay đổi</p>
                        <p><strong>4.</strong> Tất cả dropdown vẫn hoạt động bình thường</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
