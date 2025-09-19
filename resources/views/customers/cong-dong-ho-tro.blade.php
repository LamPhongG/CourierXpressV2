@extends('layouts.app')

@section('content')
<div class="pt-20 min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-orange-600 to-orange-700 text-white py-20">
        <div class="container mx-auto px-6">
            <div class="text-center">
                <h1 class="text-5xl font-bold mb-6">{{ __('messages.community_support_title') }}</h1>
                <p class="text-xl mb-8 max-w-3xl mx-auto">{{ __('messages.community_support_subtitle') }}</p>
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
                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a2 2 0 01-2-2v-6a2 2 0 012-2h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-4 text-gray-800">Diễn đàn thảo luận</h3>
                    <p class="text-gray-600 text-center mb-6">Chia sẻ kinh nghiệm, thắc mắc và giải pháp với cộng đồng</p>
                    <ul class="space-y-2 text-sm text-gray-600 mb-6">
                        <li>• Q&A về dịch vụ</li>
                        <li>• Tips & tricks</li>
                        <li>• Feedback sản phẩm</li>
                        <li>• Kết nối người dùng</li>
                    </ul>
                    <button class="w-full bg-orange-600 text-white font-bold py-2 rounded-lg hover:bg-orange-700 transition duration-300">
                        Tham gia thảo luận
                    </button>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-4 text-gray-800">Thư viện hướng dẫn</h3>
                    <p class="text-gray-600 text-center mb-6">Tài liệu hướng dẫn chi tiết và video tutorial</p>
                    <ul class="space-y-2 text-sm text-gray-600 mb-6">
                        <li>• Hướng dẫn sử dụng app</li>
                        <li>• Video tutorials</li>
                        <li>• FAQ chi tiết</li>
                        <li>• Best practices</li>
                    </ul>
                    <button class="w-full bg-orange-600 text-white font-bold py-2 rounded-lg hover:bg-orange-700 transition duration-300">
                        Xem hướng dẫn
                    </button>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-4 text-gray-800">Hỗ trợ trực tuyến</h3>
                    <p class="text-gray-600 text-center mb-6">Hỗ trợ 24/7 từ đội ngũ chuyên nghiệp</p>
                    <ul class="space-y-2 text-sm text-gray-600 mb-6">
                        <li>• Live chat</li>
                        <li>• Hotline 24/7</li>
                        <li>• Email support</li>
                        <li>• Remote assistance</li>
                    </ul>
                    <button class="w-full bg-orange-600 text-white font-bold py-2 rounded-lg hover:bg-orange-700 transition duration-300">
                        Liên hệ hỗ trợ
                    </button>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-4 text-gray-800">Chương trình thưởng</h3>
                    <p class="text-gray-600 text-center mb-6">Tích điểm và nhận thưởng khi tham gia cộng đồng</p>
                    <ul class="space-y-2 text-sm text-gray-600 mb-6">
                        <li>• Điểm thưởng hoạt động</li>
                        <li>• Voucher giảm giá</li>
                        <li>• Quà tặng đặc biệt</li>
                        <li>• Ưu đãi VIP</li>
                    </ul>
                    <button class="w-full bg-orange-600 text-white font-bold py-2 rounded-lg hover:bg-orange-700 transition duration-300">
                        Xem thưởng
                    </button>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-4 text-gray-800">Sự kiện cộng đồng</h3>
                    <p class="text-gray-600 text-center mb-6">Tham gia các sự kiện offline và online</p>
                    <ul class="space-y-2 text-sm text-gray-600 mb-6">
                        <li>• Workshop training</li>
                        <li>• Meetup định kỳ</li>
                        <li>• Webinar chuyên đề</li>
                        <li>• Contest & giveaway</li>
                    </ul>
                    <button class="w-full bg-orange-600 text-white font-bold py-2 rounded-lg hover:bg-orange-700 transition duration-300">
                        Xem sự kiện
                    </button>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-4 text-gray-800">Ý tưởng & Feedback</h3>
                    <p class="text-gray-600 text-center mb-6">Đóng góp ý tưởng cải thiện dịch vụ</p>
                    <ul class="space-y-2 text-sm text-gray-600 mb-6">
                        <li>• Góp ý tính năng mới</li>
                        <li>• Báo lỗi hệ thống</li>
                        <li>• Đánh giá dịch vụ</li>
                        <li>• Vote ý tưởng</li>
                    </ul>
                    <button class="w-full bg-orange-600 text-white font-bold py-2 rounded-lg hover:bg-orange-700 transition duration-300">
                        Gửi ý kiến
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Community Stats -->
    <div class="bg-white py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">Cộng đồng CourierXpress</h2>
            
            <div class="grid md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="text-5xl font-bold text-orange-600 mb-4">50K+</div>
                    <h3 class="text-xl font-bold mb-2 text-gray-800">Thành viên</h3>
                    <p class="text-gray-600">Người dùng tích cực</p>
                </div>

                <div class="text-center">
                    <div class="text-5xl font-bold text-orange-600 mb-4">1.2K</div>
                    <h3 class="text-xl font-bold mb-2 text-gray-800">Bài viết</h3>
                    <p class="text-gray-600">Mỗi tuần</p>
                </div>

                <div class="text-center">
                    <div class="text-5xl font-bold text-orange-600 mb-4">98%</div>
                    <h3 class="text-xl font-bold mb-2 text-gray-800">Hài lòng</h3>
                    <p class="text-gray-600">Đánh giá tích cực</p>
                </div>

                <div class="text-center">
                    <div class="text-5xl font-bold text-orange-600 mb-4">24/7</div>
                    <h3 class="text-xl font-bold mb-2 text-gray-800">Hỗ trợ</h3>
                    <p class="text-gray-600">Luôn sẵn sàng</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">Hoạt động gần đây</h2>
            
            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-lg font-bold mb-4 text-gray-800">Thảo luận mới nhất</h3>
                    <div class="space-y-4">
                        <div class="border-l-4 border-teal-500 pl-4">
                            <h4 class="font-semibold text-gray-800">Làm sao để tracking đơn hàng hiệu quả?</h4>
                            <p class="text-sm text-gray-600">bởi Nguyễn Văn A • 2 giờ trước</p>
                            <p class="text-sm text-gray-700 mt-2">Mình muốn tìm hiểu cách sử dụng tính năng tracking để theo dõi đơn hàng...</p>
                        </div>

                        <div class="border-l-4 border-teal-500 pl-4">
                            <h4 class="font-semibold text-gray-800">Tips tiết kiệm phí giao hàng</h4>
                            <p class="text-sm text-gray-600">bởi Trần Thị B • 5 giờ trước</p>
                            <p class="text-sm text-gray-700 mt-2">Chia sẻ một số mẹo giúp các bạn tiết kiệm chi phí giao hàng hiệu quả...</p>
                        </div>

                        <div class="border-l-4 border-teal-500 pl-4">
                            <h4 class="font-semibold text-gray-800">Feedback về tính năng mới</h4>
                            <p class="text-sm text-gray-600">bởi Lê Văn C • 1 ngày trước</p>
                            <p class="text-sm text-gray-700 mt-2">Tính năng đặt lịch giao hàng mới rất tiện lợi, tuy nhiên mình có một số góp ý...</p>
                        </div>
                    </div>
                    <button class="mt-6 text-orange-600 font-semibold hover:text-teal-700">Xem tất cả →</button>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-lg font-bold mb-4 text-gray-800">Sự kiện sắp tới</h3>
                    <div class="space-y-4">
                        <div class="border-l-4 border-orange-500 pl-4">
                            <h4 class="font-semibold text-gray-800">Workshop: Tối ưu logistics cho SME</h4>
                            <p class="text-sm text-gray-600">15/12/2024 • 14:00 - 16:00</p>
                            <p class="text-sm text-gray-700 mt-2">Hướng dẫn các doanh nghiệp nhỏ tối ưu hóa quy trình logistics</p>
                        </div>

                        <div class="border-l-4 border-orange-500 pl-4">
                            <h4 class="font-semibold text-gray-800">Meetup cộng đồng Q4</h4>
                            <p class="text-sm text-gray-600">20/12/2024 • 18:00 - 21:00</p>
                            <p class="text-sm text-gray-700 mt-2">Gặp gỡ, kết nối và chia sẻ kinh nghiệm với cộng đồng</p>
                        </div>

                        <div class="border-l-4 border-orange-500 pl-4">
                            <h4 class="font-semibold text-gray-800">Contest: Ý tưởng tính năng mới</h4>
                            <p class="text-sm text-gray-600">01/01/2025 - 31/01/2025</p>
                            <p class="text-sm text-gray-700 mt-2">Cuộc thi ý tưởng tính năng mới với giải thưởng hấp dẫn</p>
                        </div>
                    </div>
                    <button class="mt-6 text-orange-600 font-semibold hover:text-teal-700">Xem lịch sự kiện →</button>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-orange-600 text-white py-20">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold mb-6">Tham gia cộng đồng ngay hôm nay!</h2>
            <p class="text-xl mb-8">Kết nối với hàng nghìn người dùng, chia sẻ kinh nghiệm và nhận hỗ trợ</p>
            <div class="space-x-4">
                <button class="bg-white text-orange-600 font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition duration-300">
                    Đăng ký ngay
                </button>
                <button class="border-2 border-white text-white font-bold py-3 px-8 rounded-lg hover:bg-white hover:text-orange-600 transition duration-300">
                    Tìm hiểu thêm
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
