@extends('layouts.app')

@section('content')
<div class="pt-20 min-h-screen bg-gray-50">
    <!-- Demo Content -->
    <div class="py-20">
        <div class="container mx-auto px-6">
            <div class="text-center">
                <h1 class="text-5xl font-bold mb-8 text-gray-800">Header Demo - 1 Hàng Thẳng</h1>
                <p class="text-xl text-gray-600 mb-12">Header đã được tối ưu để tất cả nằm trên 1 hàng thẳng như Lalamove</p>
                
                <div class="grid md:grid-cols-3 gap-8 max-w-4xl mx-auto">
                    <div class="bg-white p-6 rounded-lg shadow-lg">
                        <h3 class="text-lg font-bold mb-4 text-gray-800">📍 Vị trí</h3>
                        <p class="text-gray-600">Language selector được đặt ở bên phải header, sau menu navigation</p>
                    </div>
                    
                    <div class="bg-white p-6 rounded-lg shadow-lg">
                        <h3 class="text-lg font-bold mb-4 text-gray-800">🎨 Design</h3>
                        <p class="text-gray-600">Header cao 64px (h-16), tất cả elements nằm trên 1 hàng với flexbox</p>
                    </div>
                    
                    <div class="bg-white p-6 rounded-lg shadow-lg">
                        <h3 class="text-lg font-bold mb-4 text-gray-800">🌍 Language</h3>
                        <p class="text-gray-600">Format "Vietnam - English" giống Lalamove, dropdown từ phải sang trái</p>
                    </div>
                </div>

                <div class="mt-12">
                    <h2 class="text-3xl font-bold mb-6 text-gray-800">Test chức năng</h2>
                    <p class="text-lg text-gray-600 mb-8">Click vào language selector ở góc phải để test chuyển đổi ngôn ngữ</p>
                    
                    <div class="bg-orange-100 p-6 rounded-lg">
                        <h3 class="text-xl font-bold mb-4 text-orange-800">{{ __('lalamove.delivery') }} {{ __('lalamove.company_name') }}</h3>
                        <p class="text-orange-700">{{ __('lalamove.professional_drivers') }} • {{ __('lalamove.secure_payment') }}</p>
                        <div class="mt-4 space-x-4">
                            <button class="bg-orange-600 text-white px-6 py-2 rounded-lg hover:bg-orange-700 transition duration-300">
                                {{ __('lalamove.book_now') }}
                            </button>
                            <button class="border border-orange-600 text-orange-600 px-6 py-2 rounded-lg hover:bg-orange-50 transition duration-300">
                                {{ __('lalamove.track_order') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
