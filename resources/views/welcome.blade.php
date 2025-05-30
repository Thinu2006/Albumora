<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Albumora - Your Music Collection</title>
    <meta name="description" content="Discover, organize and enjoy your music collection with Albumora">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700|montserrat:700" rel="stylesheet" />

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/Logo.png') }}" type="image/png">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .bg-albumora {
            background-image: linear-gradient(rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.5)), url("{{ asset('images/MainPageBG.PNG') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        
        .text-shadow {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        
        .btn-primary {
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body class="h-screen w-screen m-0 p-0 overflow-hidden font-sans">
    <!-- Background with overlay -->
    <div class="relative w-full h-full bg-albumora flex flex-col">
        <!-- Navigation -->
        <nav class="flex justify-between items-center px-6 py-4 sm:px-12 sm:py-6">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/Logo.png') }}" alt="Albumora Logo" class="h-12 w-auto sm:h-24">
            </div>
            
            @if (Route::has('login'))
                <div>
                    @auth
                        <a href="{{ url('/dashboard') }}" 
                           class="bg-white text-gray-800 px-4 py-2 rounded-full font-medium hover:bg-gray-100 transition duration-300">
                            Dashboard
                        </a>
                    @else

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" 
                               class="bg-white text-gray-800 px-4 py-2 rounded-full font-medium hover:bg-gray-100 transition duration-300">
                                Get Started
                            </a>
                        @endif
                    @endauth
                </div>
            @endif
        </nav>

        <!-- Hero Content -->
        <div class="flex-grow flex flex-col items-center justify-center px-4 text-center">
            <h1 class="text-5xl sm:text-7xl md:text-8xl font-bold text-white text-shadow mb-6 font-montserrat">
                YOUR MUSIC<br>COLLECTION
            </h1>
            
            <p class="text-xl text-white text-shadow max-w-2xl mb-12 px-4">
                Discover, organize and enjoy your music like never before with Albumora's powerful collection management
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" 
                       class="btn-primary bg-white text-gray-900 px-8 py-4 rounded-full font-bold text-lg hover:bg-gray-100">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" 
                       class="btn-primary bg-white text-gray-900 px-8 py-4 rounded-full font-bold text-lg hover:bg-gray-100">
                        Sign In
                    </a>
                @endauth
            </div>
        </div>

        <!-- Footer -->
        <footer class="text-center py-6 text-white text-opacity-70 text-sm">
            &copy; {{ date('Y') }} Albumora. All rights reserved.
        </footer>
    </div>
</body>
</html>