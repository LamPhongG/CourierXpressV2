@extends('layouts.unified')

@section('title', 'Create Order - CourierXpress')

@section('head')
    <link href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" rel="stylesheet">
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection

@section('navigation')
    <a href="/user/dashboard" class="text-gray-700 hover:text-red-600">Dashboard</a>
    <a href="/user/orders" class="text-gray-700 hover:text-red-600">Đơn hàng</a>
    <a href="/user/create-order" class="text-red-600 font-medium">Tạo đơn</a>
    <a href="/user/tracking" class="text-gray-700 hover:text-red-600">Tra cứu</a>
    <a href="/user/profile" class="text-gray-700 hover:text-red-600">Hồ sơ</a>
@endsection

@section('content')
    @include('user.orders.create')
@endsection

@section('scripts')
<!-- Scripts already included in the create partial -->
@endsection