<header class="fixed top-0 left-0 w-full bg-white text-gray-800 shadow-md z-50">
    <div class="container mx-auto px-6">
        <div class="flex items-center justify-between h-16">
            
        <!-- Logo -->
            <a href="{{ url('/') }}" class="flex items-center flex-shrink-0 hover:opacity-80 transition duration-300">
                <img src="{{ asset('images/baohop.png') }}" alt="Logo" class="h-14 w-18  ">
                <span class="text-xl font-bold cyberpunk-title text-gray-800"></span>
            </a>

            <!-- Center Navigation -->
            <nav class="hidden lg:flex items-center space-x-6 flex-1 justify-center">
                
                <!-- Delivery Dropdown -->
                <div class="relative group">
                    <button class="hover:text-gray-600 flex items-center space-x-1 text-sm font-medium px-2 py-1 rounded transition duration-300">
                        <span>{{ __('lalamove.delivery') }}</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="absolute top-full left-0 mt-1 w-72 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                        <div class="py-3">
                            <a href="{{ route('dich-vu.giao-hang') }}" class="block px-4 py-3 text-gray-800 hover:bg-orange-50 hover:text-orange-600 border-b border-gray-100 last:border-b-0">
                                <div class="font-semibold text-base mb-1">{{ __('messages.delivery_service') }}</div>
                                <div class="text-sm text-gray-600">{{ __('messages.delivery_service_desc') }}</div>
                            </a>
                            <a href="{{ route('dich-vu.xe-tai') }}" class="block px-4 py-3 text-gray-800 hover:bg-orange-50 hover:text-orange-600 border-b border-gray-100 last:border-b-0">
                                <div class="font-semibold text-base mb-1">{{ __('messages.truck_service') }}</div>
                                <div class="text-sm text-gray-600">{{ __('messages.truck_service_desc') }}</div>
                            </a>
                            <a href="{{ route('dich-vu.chuyen-nha') }}" class="block px-4 py-3 text-gray-800 hover:bg-orange-50 hover:text-orange-600 border-b border-gray-100 last:border-b-0">
                                <div class="font-semibold text-base mb-1">{{ __('messages.moving_service') }}</div>
                                <div class="text-sm text-gray-600">{{ __('messages.moving_service_desc') }}</div>
                            </a>
                            <a href="{{ route('dich-vu.doanh-nghiep') }}" class="block px-4 py-3 text-gray-800 hover:bg-orange-50 hover:text-orange-600">
                                <div class="font-semibold text-base mb-1">{{ __('messages.enterprise_service') }}</div>
                                <div class="text-sm text-gray-600">{{ __('messages.enterprise_service_desc') }}</div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Customers Dropdown -->
                <div class="relative group">
                    <button class="hover:text-gray-600 flex items-center space-x-1 text-sm font-medium px-2 py-1 rounded transition duration-300">
                        <span>{{ __('messages.customers') }}</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="absolute top-full left-0 mt-1 w-72 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                        <div class="py-3">
                            <a href="{{ route('khach-hang.ca-nhan') }}" class="block px-4 py-3 text-gray-800 hover:bg-orange-50 hover:text-orange-600 border-b border-gray-100 last:border-b-0">
                                <div class="font-semibold text-base mb-1">{{ __('messages.individual_customer') }}</div>
                                <div class="text-sm text-gray-600">{{ __('messages.individual_customer_desc') }}</div>
                            </a>
                            <a href="{{ route('khach-hang.doanh-nghiep') }}" class="block px-4 py-3 text-gray-800 hover:bg-orange-50 hover:text-orange-600 border-b border-gray-100 last:border-b-0">
                                <div class="font-semibold text-base mb-1">{{ __('messages.business_customer') }}</div>
                                <div class="text-sm text-gray-600">{{ __('messages.business_customer_desc') }}</div>
                            </a>
                            <a href="{{ route('cong-dong.ho-tro') }}" class="block px-4 py-3 text-gray-800 hover:bg-orange-50 hover:text-orange-600">
                                <div class="font-semibold text-base mb-1">{{ __('messages.community_support') }}</div>
                                <div class="text-sm text-gray-600">{{ __('messages.community_support_desc') }}</div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Driver Dropdown -->
                <div class="relative group">
                    <button class="hover:text-gray-600 flex items-center space-x-1 text-sm font-medium px-2 py-1 rounded transition duration-300">
                        <span>{{ __('messages.drivers') }}</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="absolute top-full left-0 mt-1 w-72 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                        <div class="py-3">
                            <a href="{{ route('tai-xe.dang-ky') }}" class="block px-4 py-3 text-gray-800 hover:bg-orange-50 hover:text-orange-600 border-b border-gray-100 last:border-b-0">
                                <div class="font-semibold text-base mb-1">{{ __('messages.driver_registration') }}</div>
                                <div class="text-sm text-gray-600">{{ __('messages.driver_registration_desc') }}</div>
                            </a>
                            <a href="{{ route('tai-xe.cong-dong') }}" class="block px-4 py-3 text-gray-800 hover:bg-orange-50 hover:text-orange-600 border-b border-gray-100 last:border-b-0">
                                <div class="font-semibold text-base mb-1">{{ __('messages.driver_community') }}</div>
                                <div class="text-sm text-gray-600">{{ __('messages.driver_community_desc') }}</div>
                            </a>
                            <a href="{{ route('tai-xe.cam-nang') }}" class="block px-4 py-3 text-gray-800 hover:bg-orange-50 hover:text-orange-600">
                                <div class="font-semibold text-base mb-1">{{ __('messages.driver_handbook') }}</div>
                                <div class="text-sm text-gray-600">{{ __('messages.driver_handbook_desc') }}</div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Support Dropdown -->
                <div class="relative group">
                    <button class="hover:text-gray-600 flex items-center space-x-1 text-sm font-medium px-2 py-1 rounded transition duration-300">
                        <span>{{ __('messages.support') }}</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="absolute top-full left-0 mt-1 w-72 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                        <div class="py-3">
                            <a href="{{ route('ho-tro.khach-hang') }}" class="block px-4 py-3 text-gray-800 hover:bg-orange-50 hover:text-orange-600 border-b border-gray-100 last:border-b-0">
                                <div class="font-semibold text-base mb-1">{{ __('messages.customer_support') }}</div>
                                <div class="text-sm text-gray-600">{{ __('messages.customer_support_desc') }}</div>
                            </a>
                            <a href="{{ route('ho-tro.tai-xe') }}" class="block px-4 py-3 text-gray-800 hover:bg-orange-50 hover:text-orange-600">
                                <div class="font-semibold text-base mb-1">{{ __('messages.driver_support') }}</div>
                                <div class="text-sm text-gray-600">{{ __('messages.driver_support_desc') }}</div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Company Dropdown -->
                <div class="relative group">
                    <button class="hover:text-gray-600 flex items-center space-x-1 text-sm font-medium px-2 py-1 rounded transition duration-300">
                        <span>{{ __('messages.recruitment') }}</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="absolute top-full left-0 mt-1 w-80 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                        <div class="py-3">
                            <a href="{{ route('tuyen-dung.ve-chung-toi') }}" class="block px-4 py-3 text-gray-800 hover:bg-orange-50 hover:text-orange-600 border-b border-gray-100 last:border-b-0">
                                <div class="font-semibold text-base mb-1">{{ __('messages.about_us') }}</div>
                                <div class="text-sm text-gray-600">{{ __('messages.about_us_desc') }}</div>
                            </a>
                            <a href="{{ route('tuyen-dung.cau-chuyen') }}" class="block px-4 py-3 text-gray-800 hover:bg-orange-50 hover:text-orange-600 border-b border-gray-100 last:border-b-0">
                                <div class="font-semibold text-base mb-1">{{ __('messages.our_story') }}</div>
                                <div class="text-sm text-gray-600">{{ __('messages.our_story_desc') }}</div>
                            </a>
                            <a href="{{ route('tuyen-dung.gia-nhap') }}" class="block px-4 py-3 text-gray-800 hover:bg-orange-50 hover:text-orange-600">
                                <div class="font-semibold text-base mb-1">{{ __('messages.join_us') }}</div>
                                <div class="text-sm text-gray-600">{{ __('messages.join_us_desc') }}</div>
                            </a>
                        </div>
                    </div>
        </div>

        </nav>

            <!-- Right Side: Language + Auth -->
            <div class="flex items-center space-x-3 flex-shrink-0">
                
                <!-- Language Selector -->
                <div class="relative group">
                    <button class="flex items-center space-x-1 text-gray-800 hover:text-gray-600 transition duration-300 px-2 py-1 rounded text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Vietnam - Tiếng Việt</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="absolute top-full right-0 mt-1 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                        <div class="py-1">
                            <a href="{{ route('language.change', 'vi') }}" class="flex items-center px-3 py-2 text-gray-800 hover:bg-orange-50 hover:text-orange-600 text-sm bg-orange-50 text-orange-600">
                                <img src="https://flagcdn.com/w20/vn.png" alt="Vietnam" class="w-4 h-3 mr-2">
                                <span>Vietnam - Tiếng Việt</span>
                            </a>
                        </div>
                    </div>
                </div>

                @guest
                <!-- Login -->
                <button onclick="openAuthModal('login')" 
                   class="flex items-center hover:text-gray-600 px-2 py-1 rounded transition duration-300 text-sm"
                   style="pointer-events: auto; cursor: pointer; z-index: 9999; position: relative;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>{{ __('lalamove.login') }}</span>
                </button>

                <!-- Sign Up -->
                <button onclick="openAuthModal('register')" 
                   class="bg-orange-600 text-white font-semibold px-3 py-2 rounded-lg hover:bg-orange-700 transition duration-300 text-sm"
                   style="pointer-events: auto; cursor: pointer; z-index: 9999; position: relative;">
                    {{ __('lalamove.sign_up') }}
                </button>
                @else
                <!-- User Menu for authenticated users -->
                <div class="relative group">
                    <button class="flex items-center space-x-1 text-gray-800 hover:text-gray-600 transition duration-300 px-2 py-1 rounded text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>{{ Auth::user()->name }}</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="absolute top-full right-0 mt-1 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                        <div class="py-1">
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-gray-800 hover:bg-orange-50 hover:text-orange-600 text-sm">
                                Dashboard
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-gray-800 hover:bg-orange-50 hover:text-orange-600 text-sm">
                                    Đăng xuất
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endguest

            </div>
        </div>
    </div>
</header>

<script>
// Global function to open auth modal from header
function openAuthModal(tab = 'login') {
    const authModal = document.getElementById('authModal');
    if (authModal) {
        authModal.classList.remove('hidden');
        // Switch to the requested tab
        if (typeof switchTab === 'function') {
            switchTab(tab);
        }
    }
}
</script>