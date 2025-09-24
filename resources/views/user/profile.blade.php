@extends('layouts.unified')

@section('title', 'My Profile - CourierXpress')

@section('navigation')
    <a href="/user/dashboard" class="text-gray-700 hover:text-red-600">Dashboard</a>
    <a href="/user/orders" class="text-gray-700 hover:text-red-600">Orders</a>
    <a href="/user/create-order" class="text-gray-700 hover:text-red-600">Create order</a>
    <a href="/user/tracking" class="text-gray-700 hover:text-red-600">Track</a>
    <a href="/user/profile" class="text-red-600 font-medium">Profile</a>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Profile Header -->
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-2xl font-bold mb-2">Personal profile</h1>
        <p class="text-blue-100">Manage account information and personal settings</p>
    </div>

    <!-- Profile Form -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-5 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Personal information</h3>
        </div>
        <div class="p-6">
            <form class="space-y-6" id="profileForm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Full name</label>
                        <input type="text" id="name" name="name" 
                               value="{{ auth()->check() ? auth()->user()->name : '' }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                        <input type="email" id="email" name="email" 
                               value="{{ auth()->check() ? auth()->user()->email : '' }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone number</label>
                        <input type="tel" id="phone" name="phone" 
                               value="{{ auth()->check() ? auth()->user()->phone : '' }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                        <input type="text" id="city" name="city" 
                               value="{{ auth()->check() ? auth()->user()->city : '' }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                    <textarea id="address" name="address" rows="3" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ auth()->check() ? auth()->user()->address : '' }}</textarea>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Update profile
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Change Password -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-5 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Change password</h3>
        </div>
        <div class="p-6">
            <form class="space-y-6" id="passwordForm">
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700">Current password</label>
                    <input type="password" id="current_password" name="current_password" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-700">New password</label>
                    <input type="password" id="new_password" name="new_password" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm new password</label>
                    <input type="password" id="confirm_password" name="confirm_password" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition-colors">
                        <i class="fas fa-key mr-2"></i>
                        Change password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Profile form submission
    document.getElementById('profileForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = {
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            phone: document.getElementById('phone').value,
            city: document.getElementById('city').value,
            address: document.getElementById('address').value
        };
        
        fetch('/api/user/profile', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Profile updated successfully!');
                // Update userName in header
                document.getElementById('userName').textContent = formData.name;
            } else {
                alert('An error occurred: ' + (data.message || 'Please try again'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating profile!');
        });
    });
    
    // Password form submission
    document.getElementById('passwordForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        
        if (newPassword !== confirmPassword) {
            alert('Password confirmation does not match!');
            return;
        }
        
        const formData = {
            current_password: document.getElementById('current_password').value,
            new_password: newPassword,
            confirm_password: confirmPassword
        };
        
        fetch('/api/user/change-password', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Password changed successfully!');
                document.getElementById('passwordForm').reset();
            } else {
                alert('An error occurred: ' + (data.message || 'Please try again'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while changing password!');
        });
    });
</script>
@endsection