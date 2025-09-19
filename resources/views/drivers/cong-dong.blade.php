@extends('layouts.app')

@section('content')
<div class="pt-20 min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-orange-600 to-orange-700 text-white py-20">
        <div class="container mx-auto px-6">
            <div class="text-center">
                <h1 class="text-5xl font-bold mb-6">{{ __('messages.driver_community_title') }}</h1>
                <p class="text-xl mb-8 max-w-3xl mx-auto">{{ __('messages.driver_community_subtitle') }}</p>
                <button class="bg-white text-orange-600 font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition duration-300">
                    {{ __('messages.join_community') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Community Features -->
    <div class="py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">Tính năng cộng đồng</h2>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a2 2 0 01-2-2v-6a2 2 0 012-2h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-4 text-gray-800">Diễn đàn tài xế</h3>
                    <p class="text-gray-600 text-center">Chia sẻ kinh nghiệm, thắc mắc về công việc và đời sống</p>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-4 text-gray-800">Bản đồ tài xế</h3>
                    <p class="text-gray-600 text-center">Tìm kiếm và kết nối với tài xế cùng khu vực</p>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-4 text-gray-800">Bảng xếp hạng</h3>
                    <p class="text-gray-600 text-center">Xếp hạng tài xế xuất sắc và nhận thưởng</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="bg-white py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">Cộng đồng tài xế CourierXpress</h2>
            
            <div class="grid md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="text-5xl font-bold text-orange-600 mb-4">15K+</div>
                    <h3 class="text-xl font-bold mb-2 text-gray-800">Tài xế</h3>
                    <p class="text-gray-600">Đang hoạt động</p>
                </div>

                <div class="text-center">
                    <div class="text-5xl font-bold text-orange-600 mb-4">500+</div>
                    <h3 class="text-xl font-bold mb-2 text-gray-800">Bài viết</h3>
                    <p class="text-gray-600">Mỗi tuần</p>
                </div>

                <div class="text-center">
                    <div class="text-5xl font-bold text-orange-600 mb-4">4.8/5</div>
                    <h3 class="text-xl font-bold mb-2 text-gray-800">Đánh giá</h3>
                    <p class="text-gray-600">Từ khách hàng</p>
                </div>

                <div class="text-center">
                    <div class="text-5xl font-bold text-orange-600 mb-4">95%</div>
                    <h3 class="text-xl font-bold mb-2 text-gray-800">Hài lòng</h3>
                    <p class="text-gray-600">Tài xế gắn bó</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA -->
    <div class="bg-orange-600 text-white py-20">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold mb-6">Tham gia cộng đồng ngay!</h2>
            <p class="text-xl mb-8">Kết nối với hàng nghìn tài xế, chia sẻ kinh nghiệm và phát triển cùng nhau</p>
            <button class="bg-white text-orange-600 font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition duration-300">
                Tham gia ngay
            </button>
        </div>
    </div>
</div>
@endsection
