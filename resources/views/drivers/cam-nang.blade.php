@extends('layouts.app')

@section('content')
<div class="pt-20 min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-orange-600 to-orange-700 text-white py-20">
        <div class="container mx-auto px-6">
            <div class="text-center">
                <h1 class="text-5xl font-bold mb-6">{{ __('messages.driver_handbook_title') }}</h1>
                <p class="text-xl mb-8 max-w-3xl mx-auto">{{ __('messages.driver_handbook_subtitle') }}</p>
                <button class="bg-white text-orange-600 font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition duration-300">
                    {{ __('messages.read_more') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Guide Categories -->
    <div class="py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">Danh mục hướng dẫn</h2>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-4 text-gray-800">Sử dụng ứng dụng</h3>
                    <p class="text-gray-600 text-center mb-6">Hướng dẫn chi tiết cách sử dụng app tài xế</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>• Đăng nhập và cập nhật thông tin</li>
                        <li>• Nhận và xử lý đơn hàng</li>
                        <li>• Sử dụng GPS và navigation</li>
                        <li>• Báo cáo thu nhập</li>
                    </ul>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-4 text-gray-800">Quy trình giao hàng</h3>
                    <p class="text-gray-600 text-center mb-6">Các bước thực hiện giao hàng chuẩn</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>• Nhận đơn và xác nhận</li>
                        <li>• Liên hệ khách hàng</li>
                        <li>• Giao hàng và thu tiền</li>
                        <li>• Hoàn thành đơn hàng</li>
                    </ul>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-4 text-gray-800">Tips & Tricks</h3>
                    <p class="text-gray-600 text-center mb-6">Mẹo hay từ các tài xế kinh nghiệm</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>• Tối ưu tuyến đường</li>
                        <li>• Tiết kiệm nhiên liệu</li>
                        <li>• Tăng thu nhập</li>
                        <li>• Xử lý tình huống khó</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ -->
    <div class="bg-white py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">Câu hỏi thường gặp</h2>
            
            <div class="max-w-4xl mx-auto space-y-6">
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-bold mb-3 text-gray-800">Làm thế nào để tăng thu nhập?</h3>
                    <p class="text-gray-600">Làm việc trong khung giờ cao điểm, nhận nhiều đơn hàng, duy trì đánh giá cao từ khách hàng.</p>
                </div>

                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-bold mb-3 text-gray-800">Xử lý khi khách hàng không có mặt?</h3>
                    <p class="text-gray-600">Liên hệ qua điện thoại, chờ 15 phút, sau đó báo cáo với tổng đài để được hướng dẫn.</p>
                </div>

                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-bold mb-3 text-gray-800">Làm gì khi gặp sự cố trên đường?</h3>
                    <p class="text-gray-600">Liên hệ ngay với hotline hỗ trợ tài xế 24/7, báo cáo tình hình và được hướng dẫn xử lý.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA -->
    <div class="bg-orange-600 text-white py-20">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold mb-6">Cần hỗ trợ thêm?</h2>
            <p class="text-xl mb-8">Liên hệ đội ngũ hỗ trợ tài xế 24/7 để được giải đáp mọi thắc mắc</p>
            <button class="bg-white text-orange-600 font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition duration-300">
                Liên hệ hỗ trợ
            </button>
        </div>
    </div>
</div>
@endsection
