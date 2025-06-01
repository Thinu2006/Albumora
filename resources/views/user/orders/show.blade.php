<x-app-layout>
    <div class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 bg-white p-6 rounded-xl shadow-sm border border-[#e2e8f0]">
                <div class="flex items-center mb-4 md:mb-0">
                    <i class='bx bx-check-circle text-4xl text-[#6366f1] mr-4'></i>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-[#1e293b]">Order #{{ $order->id ?? '' }}</h1>
                        <p class="text-[#64748b]">Placed on {{ $order->created_at->format('F j, Y') ?? '' }}</p>
                    </div>
                </div>
                
                <!-- Status and Actions -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                    @isset($order)
                    <div class="flex items-center">
                        @php
                            $statusColors = [
                                'pending' => 'bg-[#fef08a] text-[#854d0e]',
                                'accepted' => 'bg-[#bfdbfe] text-[#1e40af]',
                                'declined' => 'bg-[#fecaca] text-[#991b1b]',
                                'shipped' => 'bg-[#e9d5ff] text-[#6b21a8]',
                                'delivered' => 'bg-[#bbf7d0] text-[#166534]',
                                'completed' => 'bg-[#bbf7d0] text-[#166534]',
                                'cancelled' => 'bg-[#fecaca] text-[#991b1b]'
                            ];
                            $statusIcons = [
                                'pending' => 'bx-time',
                                'accepted' => 'bx-check-circle',
                                'declined' => 'bx-x-circle',
                                'shipped' => 'bx-truck',
                                'delivered' => 'bx-package',
                                'completed' => 'bx-check',
                                'cancelled' => 'bx-x'
                            ];
                        @endphp
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $statusColors[$order->status] ?? 'bg-[#f1f5f9] text-[#334155]' }}">
                            <i class='bx {{ $statusIcons[$order->status] ?? 'bx-info-circle' }} mr-2'></i>
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    
                    @if($order->status == 'pending')
                        <button id="cancelOrderBtn" 
                                class="flex-shrink-0 flex items-center px-4 py-2 bg-[#ef4444] hover:bg-[#dc2626] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition duration-200">
                            <i class='bx bx-x mr-2'></i> Cancel Order
                        </button>
                    @endif

                    @if(in_array($order->status, ['shipped', 'delivered']))
                        <button id="confirmReceiptBtn" 
                                class="flex-shrink-0 flex items-center px-4 py-2 bg-[#10b981] hover:bg-[#059669] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition duration-200">
                            <i class='bx bx-check mr-2'></i> Confirm Receipt
                        </button>
                    @endif
                    @endisset
                    @if($order->status == 'completed')
                        <a href="{{ route('user.review') }}" 
                        class="flex-shrink-0 flex items-center px-4 py-2 bg-[#6366f1] hover:bg-[#4f46e5] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition duration-200">
                            <i class='bx bx-edit mr-2'></i> Write a Review
                        </a>
                    @endif
                </div>
            </div>

            @isset($order)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Order Tracker -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-[#e2e8f0]">
                        <h2 class="text-lg font-semibold text-[#1e293b] mb-6 flex items-center">
                            <i class='bx bx-map mr-2 text-[#6366f1]'></i> Order Status
                        </h2>
                        
                        <div class="relative">
                            <!-- Progress Line -->
                            <div class="absolute left-0 right-0 top-1/2 h-1.5 bg-[#e2e8f0] -translate-y-1/2 rounded-full"></div>
                            @if($order->status != 'cancelled' && $order->status != 'declined')
                                @php
                                    $progressWidth = '0%';
                                    if($order->status == 'pending') $progressWidth = '20%';
                                    elseif($order->status == 'accepted') $progressWidth = '40%';
                                    elseif($order->status == 'shipped') $progressWidth = '70%';
                                    elseif(in_array($order->status, ['delivered', 'completed'])) $progressWidth = '100%';
                                @endphp
                                <div class="absolute left-0 top-1/2 h-1.5 bg-[#6366f1] -translate-y-1/2 rounded-full transition-all duration-500" 
                                    style="width: {{ $progressWidth }}"></div>
                            @else
                                <div class="absolute left-0 top-1/2 h-1.5 bg-[#ef4444] -translate-y-1/2 rounded-full transition-all duration-500" 
                                    style="width: 40%"></div>
                            @endif
                            
                            <div class="relative flex justify-between">
                                <!-- Order Placed -->
                                <div class="flex flex-col items-center w-1/5">
                                    <div class="w-10 h-10 rounded-full bg-[#6366f1] flex items-center justify-center text-white mb-2 shadow-md">
                                        <i class='bx bx-cart-alt text-xl'></i>
                                    </div>
                                    <span class="text-sm font-medium text-center text-[#1e293b]">Order Placed</span>
                                    <span class="text-xs text-[#64748b] text-center">{{ $order->created_at->format('M d') }}</span>
                                </div>
                                
                                <!-- Processing/Accepted -->
                                <div class="flex flex-col items-center w-1/5">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center mb-2 shadow-md
                                        {{ in_array($order->status, ['accepted', 'shipped', 'delivered', 'completed']) ? 'bg-[#6366f1] text-white' : 
                                           ($order->status == 'pending' ? 'bg-[#e2e8f0] text-[#64748b]' : 
                                           (in_array($order->status, ['cancelled', 'declined']) ? 'bg-[#ef4444] text-white' : 'bg-[#e2e8f0] text-[#64748b]')) }}">
                                        @if(in_array($order->status, ['cancelled', 'declined']))
                                            <i class='bx bx-x text-xl'></i>
                                        @else
                                            <i class='bx bx-check-circle text-xl'></i>
                                        @endif
                                    </div>
                                    <span class="text-sm font-medium text-center text-[#1e293b]">
                                        @if(in_array($order->status, ['cancelled', 'declined']))
                                            {{ ucfirst($order->status) }}
                                        @else
                                            {{ $order->status == 'pending' ? 'Pending' : 'Accepted' }}
                                        @endif
                                    </span>
                                    @if($order->status != 'pending')
                                        <span class="text-xs text-[#64748b] text-center">{{ $order->updated_at->format('M d') }}</span>
                                    @endif
                                </div>
                                
                                @if(!in_array($order->status, ['cancelled', 'declined']))
                                    <!-- Shipped -->
                                    <div class="flex flex-col items-center w-1/5">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center mb-2 shadow-md
                                            {{ in_array($order->status, ['shipped', 'delivered', 'completed']) ? 'bg-[#6366f1] text-white' : 'bg-[#e2e8f0] text-[#64748b]' }}">
                                            <i class='bx bx-package text-xl'></i>
                                        </div>
                                        <span class="text-sm font-medium text-center text-[#1e293b]">Shipped</span>
                                        @if(in_array($order->status, ['shipped', 'delivered', 'completed']))
                                            <span class="text-xs text-[#64748b] text-center">{{ $order->updated_at->format('M d') }}</span>
                                        @endif
                                    </div>
                                    
                                    <!-- Delivered -->
                                    <div class="flex flex-col items-center w-1/5">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center mb-2 shadow-md
                                            {{ in_array($order->status, ['delivered', 'completed']) ? 'bg-[#6366f1] text-white' : 'bg-[#e2e8f0] text-[#64748b]' }}">
                                            <i class='bx-check-shield text-xl'></i>
                                        </div>
                                        <span class="text-sm font-medium text-center text-[#1e293b]">Delivered</span>
                                        @if(in_array($order->status, ['delivered', 'completed']))
                                            <span class="text-xs text-[#64748b] text-center">{{ $order->updated_at->format('M d') }}</span>
                                        @endif
                                    </div>
                                    
                                    <!-- Completed -->
                                    <div class="flex flex-col items-center w-1/5">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center mb-2 shadow-md
                                            {{ $order->status == 'completed' ? 'bg-[#6366f1] text-white' : 'bg-[#e2e8f0] text-[#64748b]' }}">
                                            <i class='bx bx-check text-xl'></i>
                                        </div>
                                        <span class="text-sm font-medium text-center text-[#1e293b]">Completed</span>
                                        @if($order->status == 'completed')
                                            <span class="text-xs text-[#64748b] text-center">{{ $order->updated_at->format('M d') }}</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-[#e2e8f0]">
                        <div class="p-6 border-b border-[#e2e8f0] bg-[#f8fafc]">
                            <h2 class="text-lg font-semibold text-[#1e293b] flex items-center">
                                <i class='bx bx-package mr-2 text-[#6366f1]'></i> Order Items
                            </h2>
                        </div>
                        
                        <div class="divide-y divide-[#e2e8f0]">
                            @foreach($order->albums as $album)
                                <div class="flex items-center p-6 hover:bg-[#f8fafc] transition duration-150">
                                    <img src="{{ $album->cover_image ? '/storage/'.$album->cover_image : 'https://via.placeholder.com/300' }}" 
                                         alt="{{ $album->title }}" 
                                         class="w-16 h-16 md:w-20 md:h-20 object-cover rounded-lg shadow-sm mr-4">
                                    
                                    <div class="flex-grow">
                                        <h3 class="font-semibold text-[#1e293b]">{{ $album->title }}</h3>
                                        <p class="text-[#64748b] text-sm">{{ $album->artist }}</p>
                                        <div class="flex items-center mt-2 text-sm text-[#64748b]">
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
                                        <p class="font-bold text-[#1e293b]">${{ number_format($album->pivot->unit_price * $album->pivot->quantity, 2) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Order Total -->
                        <!-- <div class="p-6 bg-[#f8fafc] border-t border-[#e2e8f0]">
                            <div class="space-y-3 max-w-md ml-auto">
                                <div class="flex justify-between">
                                    <span class="text-[#64748b]">Subtotal</span>
                                    <span class="font-medium text-[#1e293b]">${{ number_format($order->subtotal ?? $order->total_amount, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-[#64748b]">Shipping</span>
                                    <span class="font-medium text-[#1e293b]">${{ number_format($order->shipping_cost ?? 5.00, 2) }}</span>
                                </div>
                                <div class="flex justify-between pt-3 border-t border-[#e2e8f0]">
                                    <span class="font-bold text-[#1e293b]">Total</span>
                                    <span class="font-bold text-[#6366f1]">${{ number_format($order->total_amount, 2) }}</span>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-8">
                    <!-- Order Summary -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-[#e2e8f0]">
                        <h2 class="text-lg font-semibold text-[#1e293b] mb-6 flex items-center">
                            <i class='bx bx-receipt mr-2 text-[#6366f1]'></i> Order Summary
                        </h2>
                        
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-medium text-[#64748b]">Order Number</h3>
                                <p class="font-semibold text-[#1e293b]">#{{ $order->id }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-[#64748b]">Order Date</h3>
                                <p class="text-[#1e293b]">{{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-[#64748b]">Payment Method</h3>
                                <p class="flex items-center text-[#1e293b]">
                                    @if($order->payment)
                                        <i class='bx bx-credit-card mr-2'></i> 
                                        {{ ucfirst($order->payment->card_type) }}
                                    @else
                                        <span class="text-[#64748b]">Not specified</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-[#64748b]">Total Amount</h3>
                                <p class="text-2xl font-bold text-[#6366f1]">${{ number_format($order->total_amount, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Information -->
                    @if($order->shipment)
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-[#e2e8f0]">
                        <h2 class="text-lg font-semibold text-[#1e293b] mb-6 flex items-center">
                            <i class='bx bx-truck mr-2 text-[#6366f1]'></i> Shipping Information
                        </h2>
                        
                        <div class="space-y-2 text-[#1e293b]">
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
                <div class="bg-white rounded-xl shadow-sm overflow-hidden text-center py-12 border border-[#e2e8f0]">
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-[#fee2e2] mb-4">
                        <i class='bx bx-error text-[#ef4444] text-2xl'></i>
                    </div>
                    <h3 class="text-lg font-medium text-[#1e293b] mb-2">Order Not Found</h3>
                    <p class="text-[#64748b] mb-6">The order you're looking for doesn't exist or you don't have permission to view it.</p>
                    <a href="{{ route('user.orders') }}" 
                       class="inline-flex items-center px-4 py-2 bg-[#6366f1] hover:bg-[#4f46e5] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition duration-200">
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