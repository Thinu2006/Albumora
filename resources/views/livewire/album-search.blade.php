<div class="mt-6">
    <!-- Search and Filter -->
    <div class="mb-16 flex flex-col sm:flex-row gap-4 justify-between items-center">
        <div class="relative w-full sm:w-96">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class='bx bx-search text-[#94a3b8] text-xl'></i>
            </div>
            <input 
                type="text" 
                wire:model.live.debounce.500ms="search"
                class="block w-full pl-10 pr-3 py-3 border border-[#e2e8f0] rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-[#6366f1] focus:border-[#6366f1] text-[#334155]" 
                placeholder="Search albums...">
        </div>
        <select 
            wire:model.live="genreFilter"
            class="block w-full sm:w-48 px-4 py-3 border border-[#e2e8f0] rounded-lg bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-[#6366f1] focus:border-[#6366f1] text-[#334155]">
            <option value="">All Genres</option>
            @foreach($genres as $genre)
                <option value="{{ $genre->name }}">{{ $genre->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Loading indicator -->
    <div wire:loading class="mb-4 text-center py-4">
        <div class="inline-flex items-center px-4 py-2 rounded-md text-sm font-medium text-[#6366f1] bg-[#e0e7ff]">
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-[#6366f1]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Loading...
        </div>
    </div>

    <!-- Album Grid -->
    <div wire:loading.remove class="grid grid-cols-1 sm:grid-cols-4 gap-[46px]">
        @forelse($albums as $album)
            <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-all duration-300 transform hover:-translate-y-1 group border border-[#f1f5f9]">
                <div class="relative">
                    <div class="aspect-square w-full overflow-hidden">
                        <img src="{{ $album->cover_image ? Storage::url($album->cover_image) : 'https://via.placeholder.com/300' }}" 
                             alt="{{ $album->title }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-all duration-300">
                    </div>
                    <div class="absolute top-2 right-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $album->stock > 0 ? 'bg-[#dcfce7] text-[#166534]' : 'bg-[#fee2e2] text-[#991b1b]' }}">
                            {{ $album->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                        </span>
                    </div>
                </div>
                <div class="p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-semibold text-[#1e293b] truncate">{{ $album->title }}</h3>
                            <p class="text-sm text-[#64748b] mt-1">{{ $album->artist }}</p>
                        </div>
                    </div>
                    <div class="mt-3 flex justify-between items-center">
                        <span class="text-lg font-bold text-[#1e293b]">${{ number_format($album->price, 2) }}</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-[#f8fafc] text-[#475569]">
                            {{ $album->release_year }}
                        </span>
                    </div>
                    <div class="mt-3 flex flex-wrap gap-1">
                        @foreach($album->genres as $genre)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-[#e0e7ff] text-[#4338ca]">
                                {{ $genre->name }}
                            </span>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <button 
                            onclick="addToCart({{ $album->id }})"
                            class="w-full bg-[#6366f1] hover:bg-[#4f46e5] text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center shadow-sm hover:shadow-md">
                            <i class='bx bx-cart-add mr-2'></i>
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <i class='bx bx-album text-[#cbd5e1] text-5xl mb-4'></i>
                <h3 class="mt-2 text-lg font-medium text-[#1e293b]">No albums found</h3>
                <p class="mt-1 text-[#64748b]">Try adjusting your search or filter to find what you're looking for.</p>
            </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    @if($albums->hasPages())
        <div class="mt-8">
            {{ $albums->links() }}
        </div>
    @endif
    
    @push('scripts')
    <script>
        function addToCart(albumId) {
            // Get current cart from localStorage
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            
            // Check if album already exists in cart
            const existingItem = cart.find(item => item.id === albumId);
            
            if (existingItem) {
                // If exists, increase quantity
                existingItem.quantity += 1;
            } else {
                // If not exists, add new item with quantity 1
                cart.push({
                    id: albumId,
                    quantity: 1
                });
            }
            
            // Save back to localStorage
            localStorage.setItem('cart', JSON.stringify(cart));
            
            // Show success message
            Toastify({
                text: "Item added to cart!",
                duration: 3000,
                close: true,
                closeColour: "#000000",
                gravity: "top",
                position: "right",
                backgroundColor: "#7f8086",
                fontColour: "#000000",
                stopOnFocus: true,
            }).showToast();
        }
    </script>
    @endpush
</div>