@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="flex flex-col justify-between items-start mb-16">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">👤Dashboard Overview</h1>
        <p class="text-gray-600 mt-2">Here's what's happening with your store today.</p>
        @if(session('token'))
            <script>
                localStorage.setItem('auth_token', '{{ session('token') }}');
            </script>
        @endif
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

<!-- Stats Cards Section -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-16 mb-8">
    <!-- Albums Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-200 hover:shadow-md hover:border-gray-200">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Albums</p>
                    <h3 class="mt-1 text-3xl font-semibold text-gray-900">{{ $albumCount }}</h3>
                </div>
                <div class="p-3 rounded-full bg-blue-50 text-blue-600">
                    <i class='bx bx-album text-2xl'></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-200 hover:shadow-md hover:border-gray-200">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Users</p>
                    <h3 class="mt-1 text-3xl font-semibold text-gray-900">{{ $userCount }}</h3>
                </div>
                <div class="p-3 rounded-full bg-green-50 text-green-600">
                    <i class='bx bx-user text-2xl'></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-200 hover:shadow-md hover:border-gray-200">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Orders</p>
                    <h3 class="mt-1 text-3xl font-semibold text-gray-900">{{ $orderCount }}</h3>
                </div>
                <div class="p-3 rounded-full bg-purple-50 text-purple-600">
                    <i class='bx bx-cart text-2xl'></i>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection