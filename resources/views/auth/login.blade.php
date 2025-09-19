@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-orange-600">CourierXpress</h2>
            <p class="mt-2 text-sm text-gray-600">Đăng nhập vào tài khoản của bạn</p>
        </div>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <!-- Test Account Info -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
            <h3 class="text-sm font-medium text-blue-800 mb-2">📝 Tài khoản test:</h3>
            <div class="text-xs text-blue-700 space-y-1">
                <p><strong>Admin:</strong> admin@courierxpress.com | 123456</p>
                <p><strong>Customer:</strong> customer@courierxpress.com | 123456</p>
                <p><strong>Agent:</strong> agent@courierxpress.com | 123456</p>
                <p><strong>Shipper:</strong> shipper@courierxpress.com | 123456</p>
            </div>
            <p class="text-xs text-blue-600 mt-2">
                <a href="/test-accounts" class="underline hover:text-blue-800">Xem thêm thông tin</a> | 
                <a href="/fix-admin-password" class="underline hover:text-blue-800">Reset mật khẩu Admin</a>
            </p>
        </div>
        <div class="bg-white py-8 px-4 shadow-lg rounded-lg sm:px-10">
            <form class="space-y-6" action="{{ route('login.store') }}" method="POST">
                @csrf
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" required 
                               class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                               placeholder="Nhập địa chỉ email"
                               value="{{ old('email') }}">
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Mật khẩu</label>
                    <div class="mt-1">
                        <input id="password" name="password" type="text" required 
                               class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                               placeholder="Nhập mật khẩu">
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- General Errors -->
                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 rounded-md p-4">
                        <p class="text-sm text-red-600">{{ session('error') }}</p>
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-md p-4">
                        <ul class="text-sm text-red-600 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Submit Button -->
                <div>
                    <button type="submit" 
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-150 ease-in-out">
                        Đăng nhập
                    </button>
                </div>

                <!-- Register Link -->
                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        Chưa có tài khoản?
                        <a href="{{ route('register') }}" class="font-medium text-orange-600 hover:text-orange-500">
                            Đăng ký ngay
                        </a>
                    </p>
                </div>
            </form>   
        </div>
    </div>
</div>
@endsection

