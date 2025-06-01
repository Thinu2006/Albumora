@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">👥 User Management</h1>
            <p class="text-gray-600 mt-2">Manage all registered users in the system</p>
            @if(session('token'))
                <script>
                    localStorage.setItem('auth_token', '{{ session('token') }}');
                </script>
            @endif
        </div>
    </div>

    @livewire('user-management')
@endsection