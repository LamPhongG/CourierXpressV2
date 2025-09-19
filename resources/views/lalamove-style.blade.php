@extends('layouts.app')

@section('content')
<div class="pt-20 min-h-screen bg-gray-50">
    <!-- Hero Section - Lalamove Style -->
    <div class="bg-gradient-to-r from-orange-600 to-orange-700 text-white py-20">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <!-- Left: Content -->
                <div>
                    <h1 class="text-5xl font-bold mb-6">CourierXpress {{ __('lalamove.delivery') }}</h1>
                    <p class="text-xl mb-8">{{ __('lalamove.instant_booking') }} • {{ __('lalamove.live_tracking') }} • {{ __('lalamove.professional_drivers') }}</p>
                    <div class="flex space-x-4">
                        <button class="bg-white text-orange-600 font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition duration-300">
                            {{ __('lalamove.book_now') }}
                        </button>
                        <button class="border-2 border-white text-white font-bold py-3 px-8 rounded-lg hover:bg-white hover:text-orange-600 transition duration-300">
                            {{ __('lalamove.track_order') }}
                        </button>
                    </div>
                </div>
                
                <!-- Right: Image placeholder -->
                <div class="text-center">
                    <div class="bg-white bg-opacity-20 p-8 rounded-lg">
                        <svg class="w-32 h-32 mx-auto text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                        </svg>
                        <p class="text-white mt-4">{{ __('lalamove.delivery') }} App</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Types - Lalamove Grid Style -->
    <div class="py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">{{ __('lalamove.delivery') }} Options</h2>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Motorbike -->
                <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition duration-300 text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-2 text-gray-800">{{ __('lalamove.motorbike') }}</h3>
                    <p class="text-sm text-gray-600 mb-4">Small items, documents</p>
                    <p class="text-orange-600 font-bold">15,000đ+</p>
                    <button class="mt-4 w-full bg-orange-600 text-white py-2 rounded-lg hover:bg-orange-700 transition duration-300">
                        {{ __('lalamove.book_now') }}
                    </button>
                </div>

                <!-- Car -->
                <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition duration-300 text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-2 text-gray-800">{{ __('lalamove.car') }}</h3>
                    <p class="text-sm text-gray-600 mb-4">Medium packages</p>
                    <p class="text-orange-600 font-bold">35,000đ+</p>
                    <button class="mt-4 w-full bg-orange-600 text-white py-2 rounded-lg hover:bg-orange-700 transition duration-300">
                        {{ __('lalamove.book_now') }}
                    </button>
                </div>

                <!-- Van -->
                <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition duration-300 text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-2 text-gray-800">{{ __('lalamove.van') }}</h3>
                    <p class="text-sm text-gray-600 mb-4">Large items, furniture</p>
                    <p class="text-orange-600 font-bold">80,000đ+</p>
                    <button class="mt-4 w-full bg-orange-600 text-white py-2 rounded-lg hover:bg-orange-700 transition duration-300">
                        {{ __('lalamove.book_now') }}
                    </button>
                </div>

                <!-- Truck -->
                <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition duration-300 text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-2 text-gray-800">{{ __('lalamove.truck') }}</h3>
                    <p class="text-sm text-gray-600 mb-4">Heavy cargo, moving</p>
                    <p class="text-orange-600 font-bold">150,000đ+</p>
                    <button class="mt-4 w-full bg-orange-600 text-white py-2 rounded-lg hover:bg-orange-700 transition duration-300">
                        {{ __('lalamove.book_now') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Section - Exactly like Lalamove -->
    <div class="bg-white py-20">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">Frequently Asked Questions</h2>
                
                <div class="space-y-6">
                    <!-- FAQ 1 -->
                    <div class="border border-gray-200 rounded-lg">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50">
                            <span class="text-lg font-semibold text-gray-800">{{ __('lalamove.when_can_deliver') }}</span>
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="px-6 pb-4">
                            <p class="text-gray-600">{{ __('lalamove.deliver_24_7') }}</p>
                        </div>
                    </div>

                    <!-- FAQ 2 -->
                    <div class="border border-gray-200 rounded-lg">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50">
                            <span class="text-lg font-semibold text-gray-800">{{ __('lalamove.can_track_order') }}</span>
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="px-6 pb-4">
                            <p class="text-gray-600">{{ __('lalamove.yes_realtime_tracking') }}</p>
                        </div>
                    </div>

                    <!-- FAQ 3 -->
                    <div class="border border-gray-200 rounded-lg">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50">
                            <span class="text-lg font-semibold text-gray-800">{{ __('lalamove.delivery_areas') }}</span>
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="px-6 pb-4">
                            <p class="text-gray-600">{{ __('lalamove.nationwide_coverage') }}</p>
                        </div>
                    </div>

                    <!-- FAQ 4 -->
                    <div class="border border-gray-200 rounded-lg">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50">
                            <span class="text-lg font-semibold text-gray-800">{{ __('lalamove.payment_methods') }}</span>
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="px-6 pb-4">
                            <p class="text-gray-600">{{ __('lalamove.multiple_payment') }}</p>
                        </div>
                    </div>

                    <!-- FAQ 5 -->
                    <div class="border border-gray-200 rounded-lg">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50">
                            <span class="text-lg font-semibold text-gray-800">{{ __('lalamove.delivery_time') }}</span>
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="px-6 pb-4">
                            <p class="text-gray-600">{{ __('lalamove.delivery_15_mins_2_days') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Read More Button - Like Lalamove -->
                <div class="text-center mt-12">
                    <button class="bg-orange-600 text-white font-bold py-3 px-12 rounded-lg hover:bg-orange-700 transition duration-300">
                        {{ __('lalamove.read_more') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Features - Lalamove Style -->
    <div class="py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">Why choose CourierXpress?</h2>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('lalamove.instant_booking') }}</h3>
                    <p class="text-gray-600">Book delivery in seconds</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('lalamove.live_tracking') }}</h3>
                    <p class="text-gray-600">Track your order in real-time</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('lalamove.secure_payment') }}</h3>
                    <p class="text-gray-600">Multiple secure payment options</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">{{ __('lalamove.professional_drivers') }}</h3>
                    <p class="text-gray-600">Verified and experienced drivers</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Section - Keep key info unchanged -->
    <div class="bg-white py-20">
        <div class="container mx-auto px-6">
            <div class="max-w-2xl mx-auto text-center">
                <h2 class="text-3xl font-bold mb-8 text-gray-800">{{ __('lalamove.customer_care') }}</h2>
                
                <div class="grid md:grid-cols-2 gap-8">
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-xl font-bold mb-4 text-gray-800">{{ __('lalamove.hotline') }}</h3>
                        <!-- Phone number never changes -->
                        <p class="text-2xl font-bold text-orange-600 mb-2">{{ __('lalamove.phone_number') }}</p>
                        <p class="text-gray-600">24/7 {{ __('lalamove.customer_care') }}</p>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-xl font-bold mb-4 text-gray-800">{{ __('lalamove.email_support') }}</h3>
                        <!-- Email address never changes -->
                        <p class="text-lg font-semibold text-orange-600 mb-2">{{ __('lalamove.email_address') }}</p>
                        <p class="text-gray-600">{{ __('lalamove.professional_drivers') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA -->
    <div class="bg-orange-600 text-white py-20">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold mb-6">{{ __('lalamove.book_now') }} with CourierXpress</h2>
            <p class="text-xl mb-8">{{ __('lalamove.instant_booking') }} • {{ __('lalamove.secure_payment') }}</p>
            <div class="space-x-4">
                <button class="bg-white text-orange-600 font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition duration-300">
                    {{ __('lalamove.book_now') }}
                </button>
                <button class="border-2 border-white text-white font-bold py-3 px-8 rounded-lg hover:bg-white hover:text-orange-600 transition duration-300">
                    {{ __('lalamove.contact_support') }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
