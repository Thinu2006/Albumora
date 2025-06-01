<x-app-layout>
    <div class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Add token handling similar to albums page -->
            @if(session('token'))
                <script>
                    localStorage.setItem('auth_token', '{{ session('token') }}');
                </script>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-[#e2e8f0]">
                <div class="p-6 bg-white border-b border-[#e2e8f0]">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold text-[#1e293b] flex items-center">
                            <i class='bx bx-package text-[#6366f1] mr-2'></i>
                            My Orders
                        </h1>
                    </div>
                    
                    <div id="orders-container">
                        <!-- Orders will be loaded here via JavaScript -->
                        <div class="text-center py-12">
                            <i class='bx bx-loader-alt bx-spin text-3xl text-[#6366f1] mb-3'></i>
                            <p class="mt-4 text-[#64748b]">Loading your orders...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <!-- Boxicons CSS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .album-tooltip {
            display: none;
            position: absolute;
            background-color: white;
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 0.75rem;
            min-width: 200px;
            z-index: 10;
        }
        .album-tooltip-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.25rem;
        }
        .album-tooltip-item:last-child {
            margin-bottom: 0;
        }
        .album-tooltip-title {
            font-weight: 500;
            color: #1e293b;
        }
        .album-tooltip-quantity {
            color: #64748b;
        }
        .items-container:hover .album-tooltip {
            display: block;
        }
        tr:hover {
            background-color: #f8fafc;
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get token from localStorage or cookie
            let rawToken = localStorage.getItem('auth_token');
            
            if (!rawToken) {
                // Fallback to cookie if not in localStorage
                rawToken = document.cookie
                    .split('; ')
                    .find(row => row.startsWith('auth_token='))
                    ?.split('=')[1];
                
                if (rawToken) {
                    localStorage.setItem('auth_token', rawToken);
                }
            }

            if (!rawToken) {
                console.error('No auth token found');
                return;
            }
            
            const token = rawToken.startsWith('Bearer ') ? rawToken : `Bearer ${rawToken}`;
            
            // Define apiHeaders with the token
            const apiHeaders = {
                headers: {
                    'Authorization': token,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            };

            // Verify token is valid
            axios.get('/api/user', apiHeaders)
                .then(response => {
                    console.log('Token is valid', response);
                    // Initialize the orders page after token validation
                    loadOrders(apiHeaders);
                })
                .catch(error => {
                    console.error('Token validation failed', error);
                });

            function loadOrders(apiHeaders) {
                axios.get('/api/orders', {
                    params: {
                        user_id: {{ $userId }}
                    },
                    headers: apiHeaders.headers
                })
                .then(response => {
                    const orders = response.data.data;
                    const container = document.getElementById('orders-container');
                    
                    if (orders.length === 0) {
                        container.innerHTML = `
                            <div class="text-center py-12 bg-white rounded-lg border border-[#e2e8f0]">
                                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-[#eef2ff] mb-4">
                                    <i class='bx bx-package text-[#6366f1] text-xl'></i>
                                </div>
                                <h3 class="text-lg font-medium text-[#1e293b] mb-2">No orders yet</h3>
                                <p class="text-[#64748b] mb-6">You haven't placed any orders yet.</p>
                                <a href="{{ route('user.album-search') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-[#6366f1] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#4f46e5] focus:outline-none focus:ring-2 focus:ring-[#c7d2fe] transition duration-200">
                                    <i class='bx bx-music mr-1'></i> Browse Albums
                                </a>
                            </div>
                        `;
                        return;
                    }
                    
                    let html = `
                        <div class="overflow-hidden shadow ring-1 ring-[#e2e8f0] rounded-lg">
                            <table class="min-w-full divide-y divide-[#e2e8f0]">
                                <thead class="bg-[#f8fafc]">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#64748b] uppercase tracking-wider">Order #</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#64748b] uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#64748b] uppercase tracking-wider">Items</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#64748b] uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-[#64748b] uppercase tracking-wider">Total</th>
                                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-[#e2e8f0]">
                    `;
                    
                    orders.forEach(order => {
                        const orderDate = new Date(order.created_at).toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'short',
                            day: 'numeric'
                        });
                        const statusColor = getStatusColor(order.status);
                        const statusIcon = getStatusIcon(order.status);
                        
                        // Safely calculate item count and prepare album list
                        let itemCount = 0;
                        let albumListHtml = '';
                        
                        if (order.items && Array.isArray(order.items)) {
                            itemCount = order.items.reduce((sum, item) => sum + (item.quantity || 0), 0);
                            
                            // Build album list for tooltip
                            albumListHtml = '<div class="album-tooltip">';
                            order.items.forEach(item => {
                                albumListHtml += `
                                    <div class="album-tooltip-item">
                                        <span class="album-tooltip-title">${item.album?.title || 'Unknown Album'}</span>
                                        <span class="album-tooltip-quantity">×${item.quantity || 1}</span>
                                    </div>
                                `;
                            });
                            albumListHtml += '</div>';
                        }
                        
                        html += `
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-[#1e293b]">#${order.id}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-[#64748b]">${orderDate}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-[#64748b]">
                                    <div class="items-container relative inline-block">
                                        <div class="flex items-center cursor-pointer">
                                            <i class='bx bx-music mr-1 text-[#6366f1]'></i>
                                            ${itemCount} ${itemCount === 1 ? 'item' : 'items'}
                                        </div>
                                        ${albumListHtml}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="px-2 inline-flex items-center text-xs leading-5 font-semibold rounded-full ${statusColor}">
                                            <i class='${statusIcon} mr-1'></i>
                                            ${order.status.charAt(0).toUpperCase() + order.status.slice(1)}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-[#1e293b]">$${(order.total_amount || 0)}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="/user/orders/${order.id}" 
                                       class="text-[#6366f1] hover:text-[#4f46e5] inline-flex items-center transition duration-200"
                                       title="View order details">
                                        <i class='bx bx-show mr-1'></i> Details
                                    </a>
                                </td>
                            </tr>
                        `;
                    });
                    
                    html += `
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 text-xs text-[#64748b] flex items-center">
                            <i class='bx bx-info-circle mr-1 text-[#6366f1]'></i> Need help with an order? Contact our support team.
                        </div>
                    `;
                    
                    container.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error loading orders:', error);
                    
                    let errorMessage = 'There was an error loading your orders.';
                    if (error.response && error.response.status === 401) {
                        errorMessage = 'Your session has expired. Please login again.';
                        return;
                    }
                    
                    document.getElementById('orders-container').innerHTML = `
                        <div class="rounded-md bg-[#fee2e2] p-4 border border-[#fecaca]">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class='bx bx-error-circle text-[#ef4444] text-xl'></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-[#991b1b]">${errorMessage}</h3>
                                    <div class="mt-2 text-sm text-[#b91c1c]">
                                        <p>Please try again later.</p>
                                    </div>
                                    <div class="mt-4">
                                        <button onclick="window.location.reload()" 
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-[#991b1b] bg-[#fee2e2] hover:bg-[#fecaca] focus:outline-none focus:ring-2 focus:ring-[#fecaca] transition duration-200">
                                            <i class='bx bx-refresh mr-1'></i> Retry
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
            }
            
            function getStatusColor(status) {
                const statusColors = {
                    'completed': 'bg-[#bbf7d0] text-[#166534]',
                    'delivered': 'bg-[#bbf7d0] text-[#166534]',
                    'cancelled': 'bg-[#fecaca] text-[#991b1b]',
                    'declined': 'bg-[#fecaca] text-[#991b1b]',
                    'pending': 'bg-[#fef08a] text-[#854d0e]',
                    'shipped': 'bg-[#bfdbfe] text-[#1e40af]',
                    'accepted': 'bg-[#e9d5ff] text-[#6b21a8]'
                };
                return statusColors[status.toLowerCase()] || 'bg-[#f1f5f9] text-[#334155]';
            }
            
            function getStatusIcon(status) {
                const statusIcons = {
                    'completed': 'bx bx-check-circle',
                    'delivered': 'bx bx-package-check',
                    'cancelled': 'bx bx-x-circle',
                    'declined': 'bx bx-x-circle',
                    'pending': 'bx bx-time-five',
                    'shipped': 'bx bx-truck',
                    'accepted': 'bx bx-check'
                };
                return statusIcons[status.toLowerCase()] || 'bx bx-info-circle';
            }

            // Make functions available globally
            window.loadOrders = loadOrders;
        });
    </script>
    @endpush
</x-app-layout>