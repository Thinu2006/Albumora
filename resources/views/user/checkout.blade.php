<x-app-layout>
    <div class="py-8 sm:py-16 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-[#e2e8f0]">
                <div class="px-6 py-5 border-b border-[#e2e8f0] bg-[#efeff0]">
                    <h2 class="text-2xl font-bold text-[#1e293b]">Checkout</h2>
                </div>
                
                <div class="grid md:grid-cols-3 gap-8 p-6">
                    <!-- Order Summary -->
                    <div class="md:col-span-1 bg-[#f8fafc] p-6 rounded-lg border border-[#e2e8f0] h-fit">
                        <h3 class="text-lg font-bold text-[#1e293b] mb-4">Order Summary</h3>
                        <div id="checkout-items-container" class="space-y-4">
                            <!-- Cart items will be loaded here via JavaScript -->
                        </div>
                        <div class="border-t border-[#e2e8f0] mt-4 pt-4">
                            <div class="flex justify-between text-lg font-bold text-[#1e293b]">
                                <span>Total:</span>
                                <span class="text-[#6366f1]">$<span id="checkout-total">0.00</span></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Shipping and Payment Forms -->
                    <div class="md:col-span-2 space-y-8">
                        <!-- Shipping Information -->
                        <div class="bg-white p-6 rounded-lg shadow-sm border border-[#e2e8f0]">
                            <h3 class="text-lg font-bold text-[#1e293b] mb-4">Shipping Information</h3>
                            <form id="shipping-form">
                                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                    <div>
                                        <label for="address_line1" class="block text-sm font-medium text-[#64748b]">Address Line 1</label>
                                        <input type="text" id="address_line1" name="address_line1" required
                                            class="mt-1 block w-full border border-[#e2e8f0] rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#6366f1] focus:border-[#6366f1] text-[#1e293b]">
                                    </div>
                                    <div>
                                        <label for="address_line2" class="block text-sm font-medium text-[#64748b]">Address Line 2 (Optional)</label>
                                        <input type="text" id="address_line2" name="address_line2"
                                            class="mt-1 block w-full border border-[#e2e8f0] rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#6366f1] focus:border-[#6366f1] text-[#1e293b]">
                                    </div>
                                    <div>
                                        <label for="city" class="block text-sm font-medium text-[#64748b]">City</label>
                                        <input type="text" id="city" name="city" required
                                            class="mt-1 block w-full border border-[#e2e8f0] rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#6366f1] focus:border-[#6366f1] text-[#1e293b]">
                                    </div>
                                    <div>
                                        <label for="state" class="block text-sm font-medium text-[#64748b]">State/Province</label>
                                        <input type="text" id="state" name="state" required
                                            class="mt-1 block w-full border border-[#e2e8f0] rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#6366f1] focus:border-[#6366f1] text-[#1e293b]">
                                    </div>
                                    <div>
                                        <label for="country" class="block text-sm font-medium text-[#64748b]">Country</label>
                                        <input type="text" id="country" name="country" required
                                            class="mt-1 block w-full border border-[#e2e8f0] rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#6366f1] focus:border-[#6366f1] text-[#1e293b]">
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Payment Information -->
                        <div class="bg-white p-6 rounded-lg shadow-sm border border-[#e2e8f0]">
                            <h3 class="text-lg font-bold text-[#1e293b] mb-4">Payment Information</h3>
                            <form id="payment-form">
                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <label for="cardholder_name" class="block text-sm font-medium text-[#64748b]">Cardholder Name</label>
                                        <input type="text" id="cardholder_name" name="cardholder_name" required
                                            class="mt-1 block w-full border border-[#e2e8f0] rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#6366f1] focus:border-[#6366f1] text-[#1e293b]">
                                    </div>
                                    <div>
                                        <label for="card_number" class="block text-sm font-medium text-[#64748b]">Card Number</label>
                                        <input type="text" id="card_number" name="card_number" required
                                            class="mt-1 block w-full border border-[#e2e8f0] rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#6366f1] focus:border-[#6366f1] text-[#1e293b]"
                                            placeholder="4242 4242 4242 4242">
                                    </div>
                                    <div class="grid grid-cols-2 gap-6">
                                        <div>
                                            <label for="expiration_month" class="block text-sm font-medium text-[#64748b]">Expiration Month</label>
                                            <input type="text" id="expiration_month" name="expiration_month" required
                                                class="mt-1 block w-full border border-[#e2e8f0] rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#6366f1] focus:border-[#6366f1] text-[#1e293b]"
                                                placeholder="MM">
                                        </div>
                                        <div>
                                            <label for="expiration_year" class="block text-sm font-medium text-[#64748b]">Expiration Year</label>
                                            <input type="text" id="expiration_year" name="expiration_year" required
                                                class="mt-1 block w-full border border-[#e2e8f0] rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#6366f1] focus:border-[#6366f1] text-[#1e293b]"
                                                placeholder="YYYY">
                                        </div>
                                    </div>
                                    <div>
                                        <label for="card_type" class="block text-sm font-medium text-[#64748b]">Card Type</label>
                                        <select id="card_type" name="card_type" required
                                            class="mt-1 block w-full border border-[#e2e8f0] rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-[#6366f1] focus:border-[#6366f1] text-[#1e293b]">
                                            <option value="">Select Card Type</option>
                                            <option value="visa">Visa</option>
                                            <option value="mastercard">Mastercard</option>
                                            <option value="amex">American Express</option>
                                            <option value="discover">Discover</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Place Order Button -->
                        <div class="flex justify-end">
                            <button id="place-order-btn"
                                class="px-6 py-3 bg-[#6366f1] hover:bg-[#4f46e5] text-white font-medium rounded-lg transition duration-300 shadow-sm hover:shadow-md flex items-center">
                                <i class='bx bx-credit-card mr-2'></i>
                                Place Order
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadCheckoutItems();
            
            // Set up place order button
            document.getElementById('place-order-btn').addEventListener('click', placeOrder);
        });

        function loadCheckoutItems() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const container = document.getElementById('checkout-items-container');
            const totalElement = document.getElementById('checkout-total');
            
            if (cart.length === 0) {
                // If cart is empty, redirect back to cart page
                window.location.href = '{{ route("user.cart") }}';
                return;
            }
            
            // Get all album data from server (passed from controller)
            const allAlbums = @json($allAlbums);
            
            let total = 0;
            let html = '';
            
            cart.forEach(item => {
                const album = allAlbums.find(a => a.id === item.id);
                if (album) {
                    // Convert price to number if it's a string
                    const price = typeof album.price === 'string' ? parseFloat(album.price) : album.price;
                    const itemTotal = price * item.quantity;
                    total += itemTotal;
                    
                    html += `
                        <div class="flex justify-between py-4 border-b border-[#e2e8f0]">
                            <div class="flex items-center">
                                <img src="${album.cover_image ? '/storage/' + album.cover_image : 'https://via.placeholder.com/300'}" 
                                     alt="${album.title}" 
                                     class="w-16 h-16 object-cover rounded-lg mr-4 shadow-sm">
                                <div>
                                    <h4 class="font-medium text-[#1e293b]">${album.title}</h4>
                                    <p class="text-sm text-[#64748b]">${album.artist}</p>
                                    <p class="text-sm text-[#64748b]">Qty: ${item.quantity}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-[#1e293b]">$${itemTotal.toFixed(2)}</p>
                            </div>
                        </div>
                    `;
                }
            });
            
            container.innerHTML = html;
            totalElement.textContent = total.toFixed(2);
        }
        
        function placeOrder() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    if (cart.length === 0) {
        alert('Your cart is empty!');
        return;
    }
    
    // Get form data
    const shippingForm = document.getElementById('shipping-form');
    const paymentForm = document.getElementById('payment-form');
    
    const shippingData = {
        address_line1: shippingForm.address_line1.value,
        address_line2: shippingForm.address_line2.value,
        city: shippingForm.city.value,
        state: shippingForm.state.value,
        country: shippingForm.country.value
    };
    
    const paymentData = {
        cardholder_name: paymentForm.cardholder_name.value,
        card_number: paymentForm.card_number.value,
        expiration_month: paymentForm.expiration_month.value,
        expiration_year: paymentForm.expiration_year.value,
        card_type: paymentForm.card_type.value
    };
    
    // Validate forms
    if (!shippingForm.address_line1.value || !shippingForm.city.value || 
        !shippingForm.state.value || !shippingForm.country.value) {
        alert('Please fill in all required shipping information');
        return;
    }
    
    if (!paymentForm.cardholder_name.value || !paymentForm.card_number.value || 
        !paymentForm.expiration_month.value || !paymentForm.expiration_year.value || 
        !paymentForm.card_type.value) {
        alert('Please fill in all payment information');
        return;
    }
    
    // Prepare order data
    const allAlbums = @json($allAlbums);
    const albums = cart.map(item => {
        const album = allAlbums.find(a => a.id === item.id);
        const price = typeof album.price === 'string' ? parseFloat(album.price) : album.price;
        return {
            id: item.id,
            quantity: item.quantity,
            unit_price: price,
            current_stock: album.stock_quantity // Include current stock for validation
        };
    });
    
    // Check stock availability before proceeding
    const outOfStockItems = albums.filter(item => {
        const album = allAlbums.find(a => a.id === item.id);
        return album.stock_quantity < item.quantity;
    });
    
    if (outOfStockItems.length > 0) {
        const itemNames = outOfStockItems.map(item => {
            const album = allAlbums.find(a => a.id === item.id);
            return `${album.title} (Requested: ${item.quantity}, Available: ${album.stock_quantity})`;
        }).join('\n');
        
        alert(`The following items don't have enough stock:\n${itemNames}\n\nPlease adjust your quantities.`);
        return;
    }
    
    const totalAmount = albums.reduce((sum, item) => sum + (item.unit_price * item.quantity), 0);
    
    const orderData = {
        customer_id: {{ Auth::id() }},
        total_amount: totalAmount,
        status: 'pending',
        albums: albums,
        shipment: shippingData,
        payment: paymentData,
        update_stock: true // Flag to indicate we want to update stock
    };
    
    // Disable button to prevent multiple submissions
    const btn = document.getElementById('place-order-btn');
    btn.disabled = true;
    btn.innerHTML = '<i class="bx bx-loader-alt animate-spin mr-2"></i> Processing...';
    
    // Send data to server
    axios.post('/api/orders', orderData, {
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => {
        // Clear cart on success
        localStorage.removeItem('cart');
        
        // Make sure we're getting the ID correctly
        const orderId = response.data.data.id;
        
        // Redirect to order confirmation page
        window.location.href = `/user/orders/${orderId}`;
    })
    .catch(error => {
        console.error('Error:', error);
        let errorMessage = 'There was an error processing your order. Please try again.';
        
        if (error.response) {
            if (error.response.data && error.response.data.message) {
                errorMessage = error.response.data.message;
            } else if (error.response.status === 422) {
                // Validation errors
                const errors = Object.values(error.response.data.errors).flat();
                errorMessage = errors.join('\n');
            } else if (error.response.status === 409) {
                // Stock conflict error
                errorMessage = 'Some items in your order are no longer available in the requested quantities. Please review your cart.';
            }
        }
        
        alert(errorMessage);
        btn.disabled = false;
        btn.innerHTML = '<i class="bx bx-credit-card mr-2"></i> Place Order';
    });
}
    </script>
    @endpush
</x-app-layout>