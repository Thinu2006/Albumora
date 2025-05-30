<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/Logo.png') }}" type="image/png">
    
    <!-- Fonts and Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&family=Rowdies:wght@300;400;700&display=swap" rel="stylesheet">

    <!-- Include Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Styles -->
    @livewireStyles
    <style>
        select[multiple] {
            height: auto;
            min-height: 100px;
            background-image: none;
        }
        select[multiple] option {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        select[multiple] option:checked {
            background-color: #4f46e5;
            color: white;
        }
        
        body {
            font-family: 'Figtree', sans-serif;
            @apply bg-gray-50 text-gray-800;
        }
        
        .font-rowdies {
            font-family: 'Rowdies', sans-serif;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            @apply bg-gray-100;
        }
        
        ::-webkit-scrollbar-thumb {
            @apply bg-gray-300 rounded-full;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            @apply bg-gray-400;
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen antialiased bg-gray-50">
    <!-- Sidebar -->
    <x-admin-sidebar />

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden md:ml-64">
        <!-- Mobile Header -->
        <header class="md:hidden bg-white shadow-sm p-4 flex justify-between items-center sticky top-0 z-30 border-b border-gray-200">
            <div class="flex items-center">
                <img src="{{ asset('images/Logo.png') }}" alt="Logo" class="h-10 w-auto">
            </div>
            <button class="text-gray-600 hover:text-gray-900 focus:outline-none" onclick="toggleMobileMenu()">
                <i class='bx bx-menu text-2xl'></i>
            </button>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:px-16 mt-[50px] bg-gray-50">
            @yield('content')
        </main>
    </div>

    @stack('modals')
    @livewireScripts
    
    <script>
        function toggleMobileMenu() {
            const sidebar = document.querySelector('x-admin-sidebar');
            sidebar.classList.toggle('hidden');
        }
    </script>
</body>
</html>