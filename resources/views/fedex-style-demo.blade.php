@extends('layouts.app')

@section('content')
<div class="pt-20 min-h-screen bg-gray-50">
    <!-- Hero Section - FedEx Style Translation -->
    <div class="bg-gradient-to-r from-orange-600 to-orange-700 text-white py-20">
        <div class="container mx-auto px-6">
            <div class="text-center">
                <!-- Brand name NEVER translated -->
                <h1 class="text-5xl font-bold mb-6">CourierXpress {{ __('fedex-style.delivery_service') }}</h1>
                <!-- Main description translated -->
                <p class="text-xl mb-8 max-w-3xl mx-auto">{{ __('fedex-style.fast_reliable_delivery') }}</p>
                <!-- Button translated -->
                <button class="bg-white text-orange-600 font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition duration-300">
                    {{ __('fedex-style.track_shipment') }}
                </button>
            </div>
        </div>
    </div>

    <!-- Services Grid - Smart Translation like FedEx -->
    <div class="py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">{{ __('fedex-style.services') }}</h2>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <!-- Service 1: Shopping Delivery -->
                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <!-- Service name translated -->
                    <h3 class="text-xl font-bold text-center mb-4 text-gray-800">Shopping delivery</h3>
                    <!-- Description translated -->
                    <p class="text-gray-600 text-center mb-6">Delivery from stores, supermarkets, markets to your home</p>
                    
                    <!-- Features: Mix of translated and technical terms -->
                    <ul class="space-y-2 text-sm text-gray-600 mb-6">
                        <li>• {{ __('fedex-style.same_day') }}</li>
                        <li>• Check goods before payment</li>
                        <li>• Delivery fee {{ __('fedex-style.from') }} 15,000 {{ __('fedex-style.vnd') }}</li>
                        <li>• {{ __('fedex-style.cod') }} available</li>
                    </ul>
                    <!-- Button translated -->
                    <button class="w-full bg-orange-600 text-white font-bold py-3 rounded-lg hover:bg-orange-700 transition duration-300">
                        {{ __('fedex-style.book_service') }}
                    </button>
                </div>

                <!-- Service 2: Document Delivery -->
                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-4 text-gray-800">Document delivery</h3>
                    <p class="text-gray-600 text-center mb-6">Fast and secure delivery of important documents</p>
                    
                    <ul class="space-y-2 text-sm text-gray-600 mb-6">
                        <li>• Within 2 hours</li>
                        <li>• {{ __('fedex-style.secure_delivery') }}</li>
                        <li>• Recipient verification</li>
                        <li>• {{ __('fedex-style.real_time_tracking') }}</li>
                    </ul>
                    <button class="w-full bg-orange-600 text-white font-bold py-3 rounded-lg hover:bg-orange-700 transition duration-300">
                        {{ __('fedex-style.book_service') }}
                    </button>
                </div>

                <!-- Service 3: Gift Delivery -->
                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-4 text-gray-800">Gift delivery</h3>
                    <p class="text-gray-600 text-center mb-6">Send meaningful gifts to relatives and friends</p>
                    
                    <ul class="space-y-2 text-sm text-gray-600 mb-6">
                        <li>• Premium packaging</li>
                        <li>• Greeting card included</li>
                        <li>• Scheduled delivery</li>
                        <li>• Photo confirmation</li>
                    </ul>
                    <button class="w-full bg-orange-600 text-white font-bold py-3 rounded-lg hover:bg-orange-700 transition duration-300">
                        {{ __('fedex-style.book_service') }}
                    </button>
                </div>

                <!-- Service 4: Personal Shopping -->
                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.293 2.293c-.63.63-.184 1.707.707 1.707H19M7 13v6a2 2 0 002 2h6a2 2 0 002-2v-6"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-4 text-gray-800">Personal shopping</h3>
                    <p class="text-gray-600 text-center mb-6">Personal shopping and delivery when you don't have time</p>
                    
                    <ul class="space-y-2 text-sm text-gray-600 mb-6">
                        <li>• Buy on request</li>
                        <li>• Product consultation</li>
                        <li>• Flexible payment</li>
                        <li>• Reasonable service fee</li>
                    </ul>
                    <button class="w-full bg-orange-600 text-white font-bold py-3 rounded-lg hover:bg-orange-700 transition duration-300">
                        {{ __('fedex-style.book_service') }}
                    </button>
                </div>

                <!-- Service 5: Personal Moving -->
                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-4 text-gray-800">Personal moving</h3>
                    <p class="text-gray-600 text-center mb-6">Move personal belongings and small furniture</p>
                    
                    <ul class="space-y-2 text-sm text-gray-600 mb-6">
                        <li>• Packing support</li>
                        <li>• Item protection</li>
                        <li>• Suitable vehicle</li>
                        <li>• Reasonable pricing</li>
                    </ul>
                    <button class="w-full bg-orange-600 text-white font-bold py-3 rounded-lg hover:bg-orange-700 transition duration-300">
                        {{ __('fedex-style.book_service') }}
                    </button>
                </div>

                <!-- Service 6: Scheduled Delivery -->
                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-4 text-gray-800">Scheduled delivery</h3>
                    <p class="text-gray-600 text-center mb-6">Schedule delivery at convenient times</p>
                    
                    <ul class="space-y-2 text-sm text-gray-600 mb-6">
                        <li>• Flexible scheduling</li>
                        <li>• Advance reminder</li>
                        <li>• {{ __('fedex-style.professional_service') }}</li>
                        <li>• Easy modifications</li>
                    </ul>
                    <button class="w-full bg-orange-600 text-white font-bold py-3 rounded-lg hover:bg-orange-700 transition duration-300">
                        {{ __('fedex-style.book_service') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Section - FedEx Style (Keep important info unchanged) -->
    <div class="bg-white py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">{{ __('fedex-style.contact_us') }}</h2>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4 text-gray-800">{{ __('fedex-style.hotline') }}</h3>
                    <!-- Phone number NEVER translated -->
                    <p class="text-3xl font-bold text-orange-600 mb-2">{{ __('fedex-style.phone_number') }}</p>
                    <p class="text-gray-600">{{ __('fedex-style.24_7') }} {{ __('fedex-style.customer_service') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4 text-gray-800">{{ __('fedex-style.email') }}</h3>
                    <!-- Email address NEVER translated -->
                    <p class="text-orange-600 font-semibold mb-2">{{ __('fedex-style.email_address') }}</p>
                    <p class="text-gray-600">{{ __('fedex-style.professional_service') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4 text-gray-800">{{ __('fedex-style.working_hours') }}</h3>
                    <!-- Time format kept standard -->
                    <p class="text-orange-600 font-semibold mb-2">{{ __('fedex-style.monday_friday') }}: 8:00-18:00</p>
                    <p class="text-gray-600">{{ __('fedex-style.saturday') }}: 8:00-12:00</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tracking Demo - Like FedEx -->
    <div class="py-20">
        <div class="container mx-auto px-6">
            <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-lg">
                <h2 class="text-3xl font-bold text-center mb-8 text-gray-800">{{ __('fedex-style.track_shipment') }}</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('fedex-style.tracking_number') }}
                        </label>
                        <!-- Input placeholder can be mixed -->
                        <input type="text" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500" 
                               placeholder="CX123456789VN">
                    </div>
                    
                    <button class="w-full bg-orange-600 text-white font-bold py-3 rounded-lg hover:bg-orange-700 transition duration-300">
                        {{ __('fedex-style.track_shipment') }}
                    </button>
                </div>

                <!-- Sample tracking result -->
                <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                    <h4 class="font-bold text-gray-800 mb-2">{{ __('fedex-style.order_id') }}: CX123456789VN</h4>
                    <p class="text-sm text-gray-600 mb-2">{{ __('fedex-style.delivery_status') }}: In Transit</p>
                    <!-- Address kept as-is like FedEx -->
                    <p class="text-sm text-gray-600">{{ __('fedex-style.from') }}: 123 Nguyễn Huệ, Q1, TP.HCM</p>
                    <p class="text-sm text-gray-600">To: 456 Lê Lợi, Q3, TP.HCM</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-orange-600 text-white py-20">
        <div class="container mx-auto px-6 text-center">
            <!-- Brand name never translated -->
            <h2 class="text-4xl font-bold mb-6">{{ __('fedex-style.contact_us') }} CourierXpress</h2>
            <p class="text-xl mb-8">{{ __('fedex-style.professional_service') }} {{ __('fedex-style.24_7') }}</p>
            <div class="space-x-4">
                <button class="bg-white text-orange-600 font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition duration-300">
                    {{ __('fedex-style.get_quote') }}
                </button>
                <button class="border-2 border-white text-white font-bold py-3 px-8 rounded-lg hover:bg-white hover:text-orange-600 transition duration-300">
                    {{ __('fedex-style.hotline') }}: {{ __('fedex-style.phone_number') }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
