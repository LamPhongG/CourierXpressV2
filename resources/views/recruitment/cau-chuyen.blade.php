@extends('layouts.app')

@section('content')
<div class="pt-20 min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-orange-600 to-orange-700 text-white py-20">
        <div class="container mx-auto px-6">
            <div class="text-center">
                <h1 class="text-5xl font-bold mb-6">{{ __('messages.our_story_title') }}</h1>
                <p class="text-xl mb-8 max-w-3xl mx-auto">{{ __('messages.our_story_subtitle') }}</p>
            </div>
        </div>
    </div>

    <!-- Story Timeline -->
    <div class="py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">Hành trình phát triển</h2>
            
            <div class="max-w-4xl mx-auto">
                <div class="space-y-12">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-24 h-24 bg-orange-100 rounded-full flex items-center justify-center mr-8">
                            <span class="text-2xl font-bold text-orange-600">2018</span>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold mb-4 text-gray-800">Khởi đầu với ước mơ</h3>
                            <p class="text-gray-600 text-lg">
                                CourierXpress được thành lập bởi nhóm bạn trẻ với ước mơ cách mạng hóa ngành logistics Việt Nam. 
                                Chúng tôi bắt đầu với 5 nhân viên và 10 tài xế đầu tiên tại TP.HCM.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-24 h-24 bg-orange-100 rounded-full flex items-center justify-center mr-8">
                            <span class="text-2xl font-bold text-orange-600">2019</span>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold mb-4 text-gray-800">Mở rộng ra Hà Nội</h3>
                            <p class="text-gray-600 text-lg">
                                Sau 1 năm hoạt động, chúng tôi mở rộng dịch vụ ra Hà Nội và các tỉnh lân cận. 
                                Đội ngũ tăng lên 100 nhân viên và 1,000 tài xế đối tác.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-24 h-24 bg-orange-100 rounded-full flex items-center justify-center mr-8">
                            <span class="text-2xl font-bold text-orange-600">2020</span>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold mb-4 text-gray-800">Vượt qua thử thách Covid-19</h3>
                            <p class="text-gray-600 text-lg">
                                Trong đại dịch, chúng tôi nhanh chóng thích ứng và trở thành cầu nối quan trọng, 
                                giúp duy trì hoạt động thương mại. Doanh thu tăng 300% so với năm trước.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-24 h-24 bg-orange-100 rounded-full flex items-center justify-center mr-8">
                            <span class="text-2xl font-bold text-orange-600">2021</span>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold mb-4 text-gray-800">Công nghệ AI và Big Data</h3>
                            <p class="text-gray-600 text-lg">
                                Ra mắt hệ thống AI tối ưu tuyến đường và dự đoán nhu cầu. 
                                Áp dụng Big Data để nâng cao trải nghiệm khách hàng.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-24 h-24 bg-orange-100 rounded-full flex items-center justify-center mr-8">
                            <span class="text-2xl font-bold text-orange-600">2022</span>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold mb-4 text-gray-800">Mở rộng toàn quốc</h3>
                            <p class="text-gray-600 text-lg">
                                Có mặt tại 63 tỉnh thành với mạng lưới 15,000 tài xế. 
                                Trở thành nền tảng logistics số 1 về số lượng đơn hàng tại Việt Nam.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-24 h-24 bg-orange-100 rounded-full flex items-center justify-center mr-8">
                            <span class="text-2xl font-bold text-orange-600">2024</span>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold mb-4 text-gray-800">Hướng tới tương lai</h3>
                            <p class="text-gray-600 text-lg">
                                Tiếp tục đầu tư vào công nghệ blockchain, IoT và xe tự lái. 
                                Mục tiêu mở rộng ra khu vực Đông Nam Á trong 2 năm tới.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Team -->
    <div class="bg-white py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">Đội ngũ lãnh đạo</h2>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-32 h-32 bg-gray-300 rounded-full mx-auto mb-6"></div>
                    <h3 class="text-xl font-bold mb-2 text-gray-800">Nguyễn Văn Hợp</h3>
                    <p class="text-orange-600 font-semibold mb-4">CEO & Founder</p>
                    <p class="text-gray-600">15 năm kinh nghiệm trong ngành logistics và công nghệ</p>
                </div>

                <div class="text-center">
                    <div class="w-32 h-32 bg-gray-300 rounded-full mx-auto mb-6"></div>
                    <h3 class="text-xl font-bold mb-2 text-gray-800">Châu Quốc Lâm Phong</h3>
                    <p class="text-orange-600 font-semibold mb-4">CTO</p>
                    <p class="text-gray-600">Chuyên gia công nghệ với 12 năm kinh nghiệm tại Silicon Valley</p>
                </div>

                <div class="text-center">
                    <div class="w-32 h-32 bg-gray-300 rounded-full mx-auto mb-6"></div>
                    <h3 class="text-xl font-bold mb-2 text-gray-800">Hoàng Phúc</h3>
                    <p class="text-orange-600 font-semibold mb-4">COO</p>
                    <p class="text-gray-600">Chuyên gia vận hành với kinh nghiệm quản lý quy mô lớn</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA -->
    <div class="bg-orange-600 text-white py-20">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold mb-6">Viết tiếp câu chuyện cùng chúng tôi</h2>
            <p class="text-xl mb-8">Gia nhập CourierXpress và cùng tạo nên những thành tựu mới</p>
            <button class="bg-white text-orange-600 font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition duration-300">
                Khám phá cơ hội
            </button>
        </div>
    </div>
</div>
@endsection
