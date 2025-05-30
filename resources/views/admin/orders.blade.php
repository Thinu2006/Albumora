@extends('layouts.admin')

@section('title', 'Order List')

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">📦 Order Management</h1>
            <p class="text-gray-600 mt-2">View and manage all customer orders</p>
            @if(session('token'))
                <script>
                    localStorage.setItem('auth_token', '{{ session('token') }}');
                </script>
            @endif
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    @endpush

    <!-- Orders Table -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="orders-container" class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">Loading orders...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
                window.location.href = '/admin/login';
                return;
            }
            
            const token = rawToken.startsWith('Bearer ') ? rawToken : `Bearer ${rawToken}`;
            
            // Define apiHeaders with the token
            const apiHeaders = {
                headers: {
                    'Authorization': token,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            };

            // Verify token is valid
            axios.get('/api/user', apiHeaders)
                .then(response => {
                    console.log('Token is valid', response);
                    // Initialize the dashboard after token validation
                    initializeDashboard();
                })
                .catch(error => {
                    console.error('Token validation failed', error);
                    window.location.href = '/admin/login';
                });

            function initializeDashboard() {
                const ordersContainer = document.getElementById('orders-container');

                // Load Orders
                function loadOrders() {
                    ordersContainer.innerHTML = '<tr><td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">Loading orders...</td></tr>';

                    axios.get('/api/orders', apiHeaders)
                        .then(response => {
                            const orders = response.data?.data || response.data || [];

                            if (!Array.isArray(orders)) {
                                throw new Error('Invalid orders data format');
                            }

                            if (orders.length === 0) {
                                ordersContainer.innerHTML = '<tr><td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">No orders found.</td></tr>';
                                return;
                            }

                            ordersContainer.innerHTML = orders.map(order => {
                                // Count total items
                                const itemCount = order.items?.reduce((total, item) => total + item.quantity, 0) || 0;
                                
                                // Format date
                                const orderDate = new Date(order.created_at);
                                const formattedDate = orderDate.toLocaleDateString('en-US', {
                                    year: 'numeric',
                                    month: 'short',
                                    day: 'numeric'
                                });

                                // Status badge color
                                let statusClass = '';
                                switch(order.status) {
                                    case 'pending':
                                        statusClass = 'bg-yellow-100 text-yellow-800';
                                        break;
                                    case 'accepted':
                                        statusClass = 'bg-blue-100 text-blue-800';
                                        break;
                                    case 'shipped':
                                        statusClass = 'bg-purple-100 text-purple-800';
                                        break;
                                    case 'delivered':
                                        statusClass = 'bg-green-100 text-green-800';
                                        break;
                                    case 'declined':
                                    case 'cancelled':
                                        statusClass = 'bg-red-100 text-red-800';
                                        break;
                                    default:
                                        statusClass = 'bg-gray-100 text-gray-800';
                                }

                                return `
                                    <tr class="hover:bg-gray-50" data-order-id="${order.id}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#${order.id}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            ${order.customer?.name || 'Guest User'}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${formattedDate}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${itemCount} items</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$${order.total_amount || '0.00'}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                                                ${order.status.charAt(0).toUpperCase() + order.status.slice(1)}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="/admin/orders/${order.id}" class="text-indigo-600 hover:text-indigo-900">Details</a>
                                        </td>
                                    </tr>
                                `;
                            }).join('');
                        })
                        .catch(error => {
                            console.error('Error loading orders:', error);
                            ordersContainer.innerHTML = `<tr><td colspan="7" class="px-6 py-4 text-center text-sm text-red-500">
                                Error loading orders: ${error.message}
                            </td></tr>`;
                        });
                }

                // Initial load
                loadOrders();
            }
        });
    </script>
@endsection