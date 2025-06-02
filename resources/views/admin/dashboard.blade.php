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

<!-- Stats Cards Section Livewire -->
<livewire:stats-overview />

@endsection