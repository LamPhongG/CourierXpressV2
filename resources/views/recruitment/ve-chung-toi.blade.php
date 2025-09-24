@extends('layouts.app')

@section('content')
<div class="pt-20 min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-orange-600 to-orange-700 text-white py-20">
        <div class="container mx-auto px-6">
            <div class="text-center">
                <h1 class="text-5xl font-bold mb-6">{{ __('messages.about_us_title') }}</h1>
                <p class="text-xl mb-8 max-w-3xl mx-auto">{{ __('messages.about_us_subtitle') }}</p>
            </div>
        </div>
    </div>

    <!-- Company Info -->
    <div class="py-20">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-4xl font-bold mb-6 text-gray-800">Vision & Mission</h2>
                    <p class="text-lg text-gray-600 mb-6">
                        CourierXpress was founded with the mission of connecting people through high-quality logistics services, 
                        creating sustainable value for customers, partners, and the community.
                    </p>
                    <p class="text-lg text-gray-600">
                        We are committed to becoming Vietnam's leading logistics platform, delivering great experiences 
                        and comprehensive solutions for every delivery need.
                    </p>
                </div>
                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <h3 class="text-2xl font-bold mb-6 text-gray-800">Key achievements</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="text-3xl font-bold text-orange-600 mr-4">5M+</div>
                            <div>
                                <p class="font-semibold">Orders</p>
                                <p class="text-sm text-gray-600">Delivered successfully</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="text-3xl font-bold text-orange-600 mr-4">50K+</div>
                            <div>
                                <p class="font-semibold">Customers</p>
                                <p class="text-sm text-gray-600">Trust and use our service</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="text-3xl font-bold text-orange-600 mr-4">15K+</div>
                            <div>
                                <p class="font-semibold">Drivers</p>
                                <p class="text-sm text-gray-600">Long-term partners</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Values -->
    <div class="bg-white py-20">
        <div class="container mx-auto px-6">
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">Core values</h2>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">Dedication</h3>
                    <p class="text-gray-600">Customer-centric, always listening and understanding</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">Quality</h3>
                    <p class="text-gray-600">Committed to the highest service quality in every step</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">Innovation</h3>
                    <p class="text-gray-600">Continuously innovating and adopting advanced technology</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">Teamwork</h3>
                    <p class="text-gray-600">Building strong teamwork and a culture of sharing</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA -->
    <div class="bg-orange-600 text-white py-20">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold mb-6">Join the CourierXpress team</h2>
            <p class="text-xl mb-8">Let's build the future of Vietnam logistics together</p>
            <button class="bg-white text-orange-600 font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition duration-300">
                View career opportunities
            </button>
        </div>
    </div>
</div>
@endsection
