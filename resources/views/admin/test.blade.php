<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
                <div class="flex items-center mb-4 md:mb-0">
                    <i class='bx bx-check-circle text-4xl text-indigo-600 mr-4'></i>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Order #{{ $order->id ?? '' }}</h1>
                        <p class="text-gray-500">Placed on {{ $order->created_at->format('F j, Y') ?? '' }}</p>
                    </div>
                </div>
                
                <!-- Status and Actions -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                    @isset($order)
                    <div class="flex items-center">
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'processing' => 'bg-blue-100 text-blue-800',
                                'completed' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                                'shipped' => 'bg-purple-100 text-purple-800'
                            ];
                            $statusIcons = [
                                'pending' => 'bx-time',
                                'processing' => 'bx-cog',
                                'completed' => 'bx-check',
                                'cancelled' => 'bx-x',
                                'shipped' => 'bx-truck'
                            ];
                        @endphp
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                            <i class='bx {{ $statusIcons[$order->status] ?? 'bx-info-circle' }} mr-2'></i>
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    
                    @if($order->status == 'pending')
                        <button id="cancelOrderBtn" 
                                class="flex-shrink-0 flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 transition ease-in-out duration-150">
                            <i class='bx bx-x mr-2'></i> Cancel Order
                        </button>
                    @endif

                    @if($order->status == 'shipped')
                        <button id="confirmReceiptBtn" 
                                class="flex-shrink-0 flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 transition ease-in-out duration-150">
                            <i class='bx bx-check mr-2'></i> Confirm Receipt
                        </button>
                    @endif
                    @endisset
                </div>
            </div>

            @isset($order)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Order Tracker -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                            <i class='bx bx-map mr-2 text-indigo-600'></i> Order Status
                        </h2>
                        
                        <div class="relative">
                            <!-- Progress Line -->
                            <div class="absolute left-0 right-0 top-1/2 h-1.5 bg-gray-200 -translate-y-1/2 rounded-full"></div>
                            @if($order->status != 'cancelled')
                                <div class="absolute left-0 top-1/2 h-1.5 bg-indigo-500 -translate-y-1/2 rounded-full transition-all duration-500" 
                                    style="width: {{ $order->status == 'pending' ? '25%' : ($order->status == 'processing' ? '50%' : ($order->status == 'shipped' ? '75%' : '100%')) }}"></div>
                            @else
                                <div class="absolute left-0 top-1/2 h-1.5 bg-red-500 -translate-y-1/2 rounded-full transition-all duration-500" 
                                    style="width: 50%"></div>
                            @endif
                            
                            <div class="relative flex justify-between">
                                <!-- Order Placed -->
                                <div class="flex flex-col items-center w-1/4">
                                    <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center text-white mb-2 shadow-md">
                                        <i class='bx bx-cart-alt text-xl'></i>
                                    </div>
                                    <span class="text-sm font-medium text-center">Order Placed</span>
                                    <span class="text-xs text-gray-500 text-center">{{ $order->created_at->format('M d') }}</span>
                                </div>
                                
                                <!-- Cancelled or Processing -->
                                <div class="flex flex-col items-center w-1/4">
                                    @if($order->status == 'cancelled')
                                        <div class="w-10 h-10 rounded-full bg-red-500 flex items-center justify-center text-white mb-2 shadow-md">
                                            <i class='bx bx-x text-xl'></i>
                                        </div>
                                        <span class="text-sm font-medium text-center">Cancelled</span>
                                        <span class="text-xs text-gray-500 text-center">{{ $order->updated_at->format('M d') }}</span>
                                    @else
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center mb-2 shadow-md
                                            {{ $order->status == 'pending' ? 'bg-gray-200 text-gray-500' : 'bg-indigo-500 text-white' }}">
                                            <i class='bx bx-cog text-xl'></i>
                                        </div>
                                        <span class="text-sm font-medium text-center">
                                            Processing
                                        </span>
                                        @if($order->status != 'pending')
                                            <span class="text-xs text-gray-500 text-center">{{ $order->updated_at->format('M d') }}</span>
                                        @endif
                                    @endif
                                </div>
                                
                                @if($order->status != 'cancelled')
                                    <!-- Shipped -->
                                    <div class="flex flex-col items-center w-1/4">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center mb-2 shadow-md
                                            {{ in_array($order->status, ['shipped', 'completed']) ? 'bg-indigo-500 text-white' : 'bg-gray-200 text-gray-500' }}">
                                            <i class='bx bx-package text-xl'></i>
                                        </div>
                                        <span class="text-sm font-medium text-center">Shipped</span>
                                        @if(in_array($order->status, ['shipped', 'completed']))
                                            <span class="text-xs text-gray-500 text-center">{{ $order->updated_at->format('M d') }}</span>
                                        @endif
                                    </div>
                                    
                                    <!-- Received -->
                                    <div class="flex flex-col items-center w-1/4">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center mb-2 shadow-md
                                            {{ $order->status == 'completed' ? 'bg-indigo-500 text-white' : 'bg-gray-200 text-gray-500' }}">
                                            <i class='bx bx-home text-xl'></i>
                                        </div>
                                        <span class="text-sm font-medium text-center">Received</span>
                                        @if($order->status == 'completed')
                                            <span class="text-xs text-gray-500 text-center">{{ $order->updated_at->format('M d') }}</span>
                                        @endif
                                    </div>
                                @else
                                    <!-- Empty placeholders for cancelled orders -->
                                    <div class="flex flex-col items-center w-1/4 opacity-0">
                                        <div class="w-10 h-10 rounded-full bg-gray-200 mb-2"></div>
                                    </div>
                                    <div class="flex flex-col items-center w-1/4 opacity-0">
                                        <div class="w-10 h-10 rounded-full bg-gray-200 mb-2"></div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class='bx bx-package mr-2 text-indigo-600'></i> Order Items
                            </h2>
                        </div>
                        
                        <div class="divide-y divide-gray-200">
                            @foreach($order->albums as $album)
                                <div class="flex items-center p-6 hover:bg-gray-50 transition duration-150">
                                    <img src="{{ $album->cover_image ? '/storage/'.$album->cover_image : 'https://via.placeholder.com/300' }}" 
                                         alt="{{ $album->title }}" 
                                         class="w-16 h-16 md:w-20 md:h-20 object-cover rounded-lg shadow-sm mr-4">
                                    
                                    <div class="flex-grow">
                                        <h3 class="font-semibold text-gray-900">{{ $album->title }}</h3>
                                        <p class="text-gray-600 text-sm">{{ $album->artist }}</p>
                                        <div class="flex items-center mt-2 text-sm text-gray-500">
                                            <span class="flex items-center mr-4">
                                                <i class='bx bx-dollar mr-1'></i> 
                                                ${{ number_format($album->pivot->unit_price, 2) }} each
                                            </span>
                                            <span class="flex items-center">
                                                <i class='bx bx-cart mr-1'></i> 
                                                Qty: {{ $album->pivot->quantity }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="text-right">
                                        <p class="font-bold text-gray-900">${{ number_format($album->pivot->unit_price * $album->pivot->quantity, 2) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Order Total -->
                        <div class="p-6 bg-gray-50 border-t border-gray-200">
                            <div class="space-y-3 max-w-md ml-auto">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span class="font-medium">${{ number_format($order->total_amount, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Shipping</span>
                                    <span class="font-medium">$5.00</span>
                                </div>
                                <div class="flex justify-between pt-3 border-t border-gray-200">
                                    <span class="font-bold text-gray-900">Total</span>
                                    <span class="font-bold text-indigo-600">${{ number_format($order->total_amount + 5, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-8">
                    <!-- Order Summary -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                            <i class='bx bx-receipt mr-2 text-indigo-600'></i> Order Summary
                        </h2>
                        
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Order Number</h3>
                                <p class="font-semibold">#{{ $order->id }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Order Date</h3>
                                <p class="">{{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Payment Method</h3>
                                <p class="flex items-center">
                                    @if($order->payment)
                                        <i class='bx bx-credit-card mr-2'></i> 
                                        {{ ucfirst($order->payment->card_type) }}
                                    @else
                                        <span class="text-gray-500">Not specified</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">Total Amount</h3>
                                <p class="text-2xl font-bold text-indigo-600">${{ number_format($order->total_amount + 5, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Information -->
                    @if($order->shipment)
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                            <i class='bx bx-truck mr-2 text-indigo-600'></i> Shipping Information
                        </h2>
                        
                        <div class="space-y-2">
                            <p class="font-medium">{{ $order->shipment->address_line1 }}</p>
                            @if($order->shipment->address_line2)
                                <p>{{ $order->shipment->address_line2 }}</p>
                            @endif
                            <p>{{ $order->shipment->city }}, {{ $order->shipment->state }} {{ $order->shipment->postal_code ?? '' }}</p>
                            <p>{{ $order->shipment->country }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @else
                <!-- Order not found message -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden text-center py-12">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                        <i class='bx bx-error text-red-500 text-2xl'></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Order Not Found</h3>
                    <p class="text-gray-600 mb-6">The order you're looking for doesn't exist or you don't have permission to view it.</p>
                    <a href="{{ route('user.orders') }}" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                        <i class='bx bx-arrow-back mr-1'></i> Back to Orders
                    </a>
                </div>
            @endisset
        </div>
    </div>

    @push('styles')
    <!-- Boxicons CSS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .order-item:hover {
            transform: translateY(-2px);
            transition: transform 0.2s ease;
        }
        .tracker-step {
            transition: all 0.3s ease;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        // Cancel Order Button
        document.getElementById('cancelOrderBtn')?.addEventListener('click', function() {
            if(confirm('Are you sure you want to cancel this order?')) {
                axios.patch('/api/orders/{{ $order->id ?? '' }}', {
                    status: 'cancelled'
                })
                .then(response => {
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error cancelling order:', error);
                    alert('Failed to cancel order. Please try again.');
                });
            }
        });

        // Confirm Receipt Button
        document.getElementById('confirmReceiptBtn')?.addEventListener('click', function() {
            if(confirm('Have you received this order?')) {
                axios.patch('/api/orders/{{ $order->id ?? '' }}', {
                    status: 'completed'
                })
                .then(response => {
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error confirming receipt:', error);
                    alert('Failed to confirm receipt. Please try again.');
                });
            }
        });
    </script>
    @endpush
</x-app-layout>