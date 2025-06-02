<x-app-layout>
    <!-- Add Boxicons CSS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- Add Toastify CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    
    <div class="py-8 sm:py-12 bg-[#f0f1f3] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Hero Section -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-extrabold text-[#2d3748] sm:text-5xl mb-4">
                    Discover Your <span class="text-[#6f6f70]">Favorite Music</span>
                </h1>
                <p class="mt-3 max-w-2xl mx-auto text-xl text-[#4a5568]">
                    Explore albums from all genres and eras
                </p>
            </div>

            <!-- Livewire Component -->
            @livewire('album-search')
        </div>
    </div>
</x-app-layout>