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
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">{{ __('messages.development_journey') }}</h2>
            
            <div class="max-w-4xl mx-auto">
                <div class="space-y-12">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-24 h-24 bg-orange-100 rounded-full flex items-center justify-center mr-8">
                            <span class="text-2xl font-bold text-orange-600">2018</span>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold mb-4 text-gray-800">{{ __('messages.dream_beginning') }}</h3>
                            <p class="text-gray-600 text-lg">
                                {{ __('messages.courierxpress_founded') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-24 h-24 bg-orange-100 rounded-full flex items-center justify-center mr-8">
                            <span class="text-2xl font-bold text-orange-600">2019</span>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold mb-4 text-gray-800">{{ __('messages.expansion_hanoi') }}</h3>
                            <p class="text-gray-600 text-lg">
                                {{ __('messages.after_1_year') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-24 h-24 bg-orange-100 rounded-full flex items-center justify-center mr-8">
                            <span class="text-2xl font-bold text-orange-600">2020</span>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold mb-4 text-gray-800">{{ __('messages.overcoming_covid') }}</h3>
                            <p class="text-gray-600 text-lg">
                                {{ __('messages.during_pandemic') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-24 h-24 bg-orange-100 rounded-full flex items-center justify-center mr-8">
                            <span class="text-2xl font-bold text-orange-600">2021</span>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold mb-4 text-gray-800">{{ __('messages.ai_big_data') }}</h3>
                            <p class="text-gray-600 text-lg">
                                {{ __('messages.launched_ai_system') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-24 h-24 bg-orange-100 rounded-full flex items-center justify-center mr-8">
                            <span class="text-2xl font-bold text-orange-600">2022</span>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold mb-4 text-gray-800">{{ __('messages.nationwide_expansion') }}</h3>
                            <p class="text-gray-600 text-lg">
                                {{ __('messages.present_63_provinces') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-24 h-24 bg-orange-100 rounded-full flex items-center justify-center mr-8">
                            <span class="text-2xl font-bold text-orange-600">2024</span>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold mb-4 text-gray-800">{{ __('messages.towards_future') }}</h3>
                            <p class="text-gray-600 text-lg">
                                {{ __('messages.continue_investing') }}
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
            <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">{{ __('messages.leadership_team') }}</h2>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-32 h-32 bg-gray-300 rounded-full mx-auto mb-6"></div>
                    <h3 class="text-xl font-bold mb-2 text-gray-800">Nguyễn Văn Hợp</h3>
                    <p class="text-orange-600 font-semibold mb-4">{{ __('messages.ceo_founder') }}</p>
                    <p class="text-gray-600">{{ __('messages.logistics_experience') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-32 h-32 bg-gray-300 rounded-full mx-auto mb-6"></div>
                    <h3 class="text-xl font-bold mb-2 text-gray-800">Châu Quốc Lâm Phong</h3>
                    <p class="text-orange-600 font-semibold mb-4">{{ __('messages.cto') }}</p>
                    <p class="text-gray-600">{{ __('messages.technology_expert') }}</p>
                </div>

                <div class="text-center">
                    <div class="w-32 h-32 bg-gray-300 rounded-full mx-auto mb-6"></div>
                    <h3 class="text-xl font-bold mb-2 text-gray-800">Hoàng Phúc</h3>
                    <p class="text-orange-600 font-semibold mb-4">{{ __('messages.coo') }}</p>
                    <p class="text-gray-600">{{ __('messages.operations_expert') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA -->
    <div class="bg-orange-600 text-white py-20">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold mb-6">{{ __('messages.write_story_together') }}</h2>
            <p class="text-xl mb-8">{{ __('messages.join_courierxpress') }}</p>
            <button class="bg-white text-orange-600 font-bold py-3 px-8 rounded-lg hover:bg-gray-100 transition duration-300">
                {{ __('messages.explore_opportunities') }}
            </button>
        </div>
    </div>
</div>
@endsection