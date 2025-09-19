@extends('layouts.app')

@section('content')
<div class="pt-20 min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-orange-600 to-orange-700 text-white py-20">
        <div class="container mx-auto px-6">
            <div class="text-center">
                <h1 class="text-5xl font-bold mb-6">{{ __('messages.delivery_service_title') }}</h1>
                <p class="text-xl mb-8 max-w-3xl mx-auto">{{ __('messages.delivery_service_subtitle') }}</p>
                <button class="bg-white text-orange-600 font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition duration-300">
                    {{ __('messages.order_now') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">{{ __('messages.why_choose_us') }}</h2>
            
            <div class="grid md:grid-cols-3 gap-12">
                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4 text-gray-800">{{ __('messages.delivery_fast_title') }}</h3>
                    <p class="text-gray-600">{{ __('messages.delivery_fast_desc') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4 text-gray-800">{{ __('messages.delivery_safe_title') }}</h3>
                    <p class="text-gray-600">{{ __('messages.delivery_safe_desc') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4 text-gray-800">{{ __('messages.delivery_tracking_title') }}</h3>
                    <p class="text-gray-600">{{ __('messages.delivery_tracking_desc') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Types -->
    <div class="bg-white py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">{{ __('messages.delivery_types_title') }}</h2>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-gray-50 p-8 rounded-lg text-center">
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.delivery_standard') }}</h3>
                    <p class="text-gray-600 mb-4">{{ __('messages.delivery_standard_time') }}</p>
                    <p class="text-orange-600 font-bold">{{ __('messages.from_price') }} 15.000đ</p>
                </div>

                <div class="bg-gray-50 p-8 rounded-lg text-center">
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.delivery_fast_service') }}</h3>
                    <p class="text-gray-600 mb-4">{{ __('messages.same_day') }}</p>
                    <p class="text-orange-600 font-bold">{{ __('messages.from_price') }} 25.000đ</p>
                </div>

                <div class="bg-gray-50 p-8 rounded-lg text-center">
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.delivery_express_service') }}</h3>
                    <p class="text-gray-600 mb-4">{{ __('messages.delivery_express_time') }}</p>
                    <p class="text-orange-600 font-bold">{{ __('messages.from_price') }} 45.000đ</p>
                </div>

                <div class="bg-gray-50 p-8 rounded-lg text-center">
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.delivery_cod') }}</h3>
                    <p class="text-gray-600 mb-4">{{ __('messages.delivery_cod_desc') }}</p>
                    <p class="text-orange-600 font-bold">{{ __('messages.delivery_cod_fee') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Truck Service Demo -->
    <div class="py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">{{ __('messages.truck_available_title') }}</h2>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <div class="text-center mb-6">
                        <h3 class="text-xl font-bold text-gray-800">{{ __('messages.truck_500kg') }}</h3>
                    </div>
                    <ul class="space-y-2 text-gray-600 mb-6">
                        <li>• {{ __('messages.truck_500kg_size') }}</li>
                        <li>• {{ __('messages.truck_500kg_suitable') }}</li>
                        <li>• {{ __('messages.truck_500kg_area') }}</li>
                    </ul>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-orange-600">{{ __('messages.from_price') }} 150.000đ</p>
                        <p class="text-sm text-gray-500">{{ __('messages.base_price') }}</p>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <div class="text-center mb-6">
                        <h3 class="text-xl font-bold text-gray-800">{{ __('messages.truck_1ton') }}</h3>
                    </div>
                    <ul class="space-y-2 text-gray-600 mb-6">
                        <li>• {{ __('messages.truck_1ton_size') }}</li>
                        <li>• {{ __('messages.truck_1ton_suitable') }}</li>
                        <li>• {{ __('messages.truck_1ton_area') }}</li>
                    </ul>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-orange-600">{{ __('messages.from_price') }} 250.000đ</p>
                        <p class="text-sm text-gray-500">{{ __('messages.base_price') }}</p>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <div class="text-center mb-6">
                        <h3 class="text-xl font-bold text-gray-800">{{ __('messages.truck_25ton') }}</h3>
                    </div>
                    <ul class="space-y-2 text-gray-600 mb-6">
                        <li>• {{ __('messages.truck_25ton_size') }}</li>
                        <li>• {{ __('messages.truck_25ton_suitable') }}</li>
                        <li>• {{ __('messages.truck_25ton_area') }}</li>
                    </ul>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-orange-600">{{ __('messages.from_price') }} 400.000đ</p>
                        <p class="text-sm text-gray-500">{{ __('messages.base_price') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Moving Packages Demo -->
    <div class="bg-white py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">{{ __('messages.moving_packages_title') }}</h2>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-lg shadow-lg border-t-4 border-orange-500">
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">{{ __('messages.basic_package') }}</h3>
                        <p class="text-4xl font-bold text-orange-600 mb-2">{{ __('messages.from_price') }} 500K</p>
                        <p class="text-gray-500">{{ __('messages.basic_suitable') }}</p>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('messages.truck_500kg') }} - {{ __('messages.truck_1ton') }}
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            2 {{ __('messages.staff_support') }}
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('messages.inner_city_transport') }}
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('messages.basic_insurance') }}
                        </li>
                    </ul>
                    <button class="w-full bg-orange-600 text-white font-bold py-3 rounded-lg hover:bg-orange-700 transition duration-300">
                        {{ __('messages.book_service') }}
                    </button>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg border-t-4 border-orange-600 relative">
                    <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                        <span class="bg-orange-600 text-white px-4 py-1 rounded-full text-sm font-bold">{{ __('messages.popular') }}</span>
                    </div>
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">{{ __('messages.standard_package') }}</h3>
                        <p class="text-4xl font-bold text-orange-600 mb-2">{{ __('messages.from_price') }} 1.2M</p>
                        <p class="text-gray-500">{{ __('messages.standard_suitable') }}</p>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('messages.truck_1ton') }}-{{ __('messages.truck_25ton') }}
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            4 {{ __('messages.staff_support') }}
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('messages.free_packing') }}
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('messages.comprehensive_insurance_full') }}
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('messages.basic_old_house_cleaning') }}
                        </li>
                    </ul>
                    <button class="w-full bg-orange-600 text-white font-bold py-3 rounded-lg hover:bg-orange-700 transition duration-300">
                        {{ __('messages.book_service') }}
                    </button>
                </div>

                <div class="bg-white p-8 rounded-lg shadow-lg border-t-4 border-orange-700">
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">{{ __('messages.vip_package') }}</h3>
                        <p class="text-4xl font-bold text-orange-600 mb-2">{{ __('messages.from_price') }} 2.5M</p>
                        <p class="text-gray-500">{{ __('messages.vip_suitable') }}</p>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('messages.large_truck_on_demand') }}
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            6+ {{ __('messages.professional_staff') }}
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('messages.packing_arranging_new_house') }}
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('messages.premium_insurance') }}
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('messages.complete_cleaning_both_houses') }}
                        </li>
                    </ul>
                    <button class="w-full bg-orange-600 text-white font-bold py-3 rounded-lg hover:bg-orange-700 transition duration-300">
                        {{ __('messages.book_service') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Process -->
    <div class="bg-white py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">{{ __('messages.moving_process_title') }}</h2>
            
            <div class="grid md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-orange-600">1</span>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.survey') }}</h3>
                    <p class="text-gray-600">{{ __('messages.survey_desc') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-orange-600">2</span>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.packing') }}</h3>
                    <p class="text-gray-600">{{ __('messages.packing_desc') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-orange-600">3</span>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.transport') }}</h3>
                    <p class="text-gray-600">{{ __('messages.transport_desc') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-orange-600">4</span>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.completion') }}</h3>
                    <p class="text-gray-600">{{ __('messages.completion_desc') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Advantages -->
    <div class="py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">{{ __('messages.outstanding_advantages') }}</h2>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.on_time') }}</h3>
                    <p class="text-gray-600">{{ __('messages.on_time_desc') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.professional_drivers') }}</h3>
                    <p class="text-gray-600">{{ __('messages.professional_drivers_desc') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.comprehensive_insurance') }}</h3>
                    <p class="text-gray-600">{{ __('messages.comprehensive_insurance_desc') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('messages.24_7_support') }}</h3>
                    <p class="text-gray-600">{{ __('messages.customer_support_desc') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-orange-600 text-white py-20">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold mb-6">{{ __('messages.ready_to_deliver') }}</h2>
            <p class="text-xl mb-8">{{ __('messages.contact_for_quote') }}</p>
            <div class="space-x-4">
                <button class="bg-white text-orange-600 font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition duration-300">
                    {{ __('messages.contact_now') }}
                </button>
                <button class="border-2 border-white text-white font-bold py-3 px-8 rounded-lg hover:bg-white hover:text-orange-600 transition duration-300">
                    {{ __('messages.download_app') }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
