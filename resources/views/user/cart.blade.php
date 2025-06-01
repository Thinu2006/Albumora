<x-app-layout>
    <div class="py-8 sm:py-16 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Left Column - Cart Items -->
                <div class="lg:w-2/3">
                    <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-[#e2e8f0]">
                        <div class="px-6 py-5 border-b border-[#e2e8f0] bg-[#efeff0]">
                            <h2 class="text-2xl font-bold text-[#1e293b]">Your Cart Items</h2>
                        </div>
                        
                        <div id="cart-items-container" class="divide-y divide-[#e2e8f0]">
                            <!-- Cart items will be loaded here via JavaScript -->
                        </div>
                        
                        <div id="empty-cart-message" class="p-8 text-center text-[#64748b]" style="display: none;">
                            <i class='bx bx-cart text-4xl text-[#cbd5e1] mb-3'></i>
                            <p class="text-lg font-medium">Your cart is empty</p>
                            <a href="{{ route('user.album-search') }}" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-[#6366f1] hover:text-[#4f46e5]">
                                Browse Albums
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column - Checkout Summary -->
                <div class="lg:w-1/3">
                    <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-[#e2e8f0] sticky top-4">
                        <div class="px-6 py-5 border-b border-[#e2e8f0] bg-[#efeff0]">
                            <h2 class="text-2xl font-bold text-[#1e293b]">Order Summary</h2>
                        </div>
                        
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex justify-between">
                                    <span class="text-[#64748b]">Subtotal</span>
                                    <span class="font-medium" id="subtotal">$0.00</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-[#64748b]">Shipping</span>
                                    <span class="font-medium" id="shipping">$5.00</span>
                                </div>
                                <div class="flex justify-between border-t border-[#e2e8f0] pt-4">
                                    <span class="text-lg font-bold text-[#1e293b]">Total</span>
                                    <span class="text-lg font-bold text-[#6366f1]" id="cart-total">$5.00</span>
                                </div>
                            </div>
                            
                            <button 
                                onclick="checkout()"
                                class="w-full mt-6 px-6 py-3 bg-[#6366f1] hover:bg-[#4f46e5] text-white font-medium rounded-lg transition duration-200 shadow-sm hover:shadow-md flex items-center justify-center">
                                <i class='bx bx-credit-card mr-2'></i>
                                Proceed to Checkout
                            </button>
                            
                            <div class="mt-4 text-center text-sm text-[#64748b]">
                                <i class='bx bx-lock-alt mr-1'></i> Secure checkout
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadCartItems();
        });

        function loadCartItems() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const container = document.getElementById('cart-items-container');
            const emptyMessage = document.getElementById('empty-cart-message');
            const totalElement = document.getElementById('cart-total');
            const subtotalElement = document.getElementById('subtotal');
            const shippingElement = document.getElementById('shipping');
            
            if (cart.length === 0) {
                emptyMessage.style.display = 'block';
                container.innerHTML = '';
                subtotalElement.textContent = '$0.00';
                shippingElement.textContent = '$0.00';
                totalElement.textContent = '$0.00';
                return;
            }
            
            emptyMessage.style.display = 'none';
            
            const allAlbums = @json($allAlbums);
            
            let subtotal = 0;
            let html = '';
            
            cart.forEach(item => {
                const album = allAlbums.find(a => a.id === item.id);
                if (album) {
                    const price = typeof album.price === 'string' ? parseFloat(album.price) : album.price;
                    const itemTotal = price * item.quantity;
                    subtotal += itemTotal;
                    
                    html += `
                        <div class="p-6 flex flex-col sm:flex-row gap-6 group hover:bg-[#f8fafc]" data-id="${album.id}">
                            <div class="flex-shrink-0">
                                <img src="${album.cover_image ? '/storage/' + album.cover_image : 'https://via.placeholder.com/300'}" 
                                    alt="${album.title}" 
                                    class="w-24 h-24 sm:w-32 sm:h-32 object-cover rounded-lg shadow-sm group-hover:shadow-md transition duration-200">
                            </div>
                            <div class="flex-grow">
                                <h3 class="text-lg font-bold text-[#1e293b]">${album.title}</h3>
                                <p class="text-[#64748b]">${album.artist}</p>
                                <p class="text-[#64748b] mt-1">$${price.toFixed(2)} each</p>
                                <div class="mt-3 flex items-center gap-4">
                                    <div class="flex items-center border border-[#e2e8f0] rounded-lg overflow-hidden">
                                        <button onclick="updateQuantity(${album.id}, -1)" 
                                                class="px-3 py-1 bg-[#f1f5f9] hover:bg-[#e2e8f0] text-[#475569] transition duration-200">
                                            <i class='bx bx-minus'></i>
                                        </button>
                                        <span class="px-3 py-1 bg-white text-[#1e293b]">${item.quantity}</span>
                                        <button onclick="updateQuantity(${album.id}, 1)" 
                                                class="px-3 py-1 bg-[#f1f5f9] hover:bg-[#e2e8f0] text-[#475569] transition duration-200">
                                            <i class='bx bx-plus'></i>
                                        </button>
                                    </div>
                                    <button onclick="removeFromCart(${album.id})" 
                                            class="text-[#ef4444] hover:text-[#dc2626] flex items-center text-sm">
                                        <i class='bx bx-trash mr-1'></i> Remove
                                    </button>
                                </div>
                            </div>
                            <div class="flex-shrink-0 text-right">
                                <p class="text-lg font-bold text-[#1e293b]">$${itemTotal.toFixed(2)}</p>
                            </div>
                        </div>
                    `;
                }
            });
            
            container.innerHTML = html;
            subtotalElement.textContent = '$' + subtotal.toFixed(2);
            
            // Calculate shipping (fixed $5.00 for now)
            const shipping = cart.length > 0 ? 5.00 : 0.00;
            shippingElement.textContent = '$' + shipping.toFixed(2);
            
            // Calculate total (subtotal + shipping)
            const total = subtotal + shipping;
            totalElement.textContent = '$' + total.toFixed(2);
        }
        
        function updateQuantity(albumId, change) {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            const allAlbums = @json($allAlbums);
            const album = allAlbums.find(a => a.id === albumId);
            
            if (!album) {
                removeFromCart(albumId);
                return;
            }
            
            const item = cart.find(item => item.id === albumId);
            
            if (item) {
                const newQuantity = item.quantity + change;
                
                if (newQuantity <= 0) {
                    cart = cart.filter(item => item.id !== albumId);
                } else {
                    item.quantity = newQuantity;
                }
                
                localStorage.setItem('cart', JSON.stringify(cart));
                loadCartItems();
                window.dispatchEvent(new Event('storage'));
            }
        }
        
        function removeFromCart(albumId) {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            cart = cart.filter(item => item.id !== albumId);
            localStorage.setItem('cart', JSON.stringify(cart));
            loadCartItems();
            window.dispatchEvent(new Event('storage'));
        }
        
        function checkout() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            if (cart.length === 0) {
                Toastify({
                    text: "Your cart is empty!",
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#7f8086",
                    stopOnFocus: true,
                }).showToast();
                return;
            }
            
            window.location.href = '{{ route("user.checkout") }}';
        }
    </script>
    @endpush
</x-app-layout>