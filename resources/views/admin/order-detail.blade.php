@extends('layouts.admin')

@section('title', 'Order Details')

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2">
                <i class='bx bx-package text-indigo-600'></i>
                Order #{{ $orderId ?? '' }}
            </h1>
            <p class="text-gray-600 mt-2 flex items-center gap-1">
                <i class='bx bx-info-circle'></i>
                View and manage order details
            </p>
            @if(session('token'))
                <script>
                    localStorage.setItem('auth_token', '{{ session('token') }}');
                </script>
            @endif
        </div>
        <a href="/admin/orders" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
            <i class='bx bx-arrow-back mr-2'></i>
            Back to Orders
        </a>
    </div>

    @push('styles')
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <style>
            .status-badge {
                @apply px-2 py-1 inline-flex items-center gap-1 text-xs leading-4 font-semibold rounded-full;
            }
            .order-item {
                @apply transition-all duration-200 hover:bg-gray-50;
            }
            .action-btn {
                @apply flex items-center justify-center px-4 py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors duration-200;
            }
            .print-only {
                display: none;
            }
            @media print {
                .no-print {
                    display: none;
                }
                .print-only {
                    display: block;
                }
                body {
                    background: white !important;
                    color: black !important;
                }
                .bg-white {
                    background: white !important;
                }
                .shadow-sm {
                    box-shadow: none !important;
                }
                .border {
                    border-color: #e5e7eb !important;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    @endpush

    <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-8 border border-gray-100">
        <div class="no-print flex justify-end p-4 border-b border-gray-100">
            <button onclick="window.print()" class="flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                <i class='bx bx-printer'></i>
                Print Order
            </button>
        </div>
        
        <div id="order-detail-container" class="p-6">
            <div class="text-center py-8">
                <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500 mx-auto"></div>
                <p class="mt-4 text-gray-600 flex items-center justify-center gap-2">
                    <i class='bx bx-loader-alt bx-spin'></i>
                    Loading order details...
                </p>
            </div>
        </div>
    </div>

    <div class="print-only bg-white p-6 hidden">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Order Invoice</h1>
        <p class="text-gray-600 mb-6">Order #<span id="print-order-id"></span></p>
        <div id="print-order-content"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Authentication and token handling
            const auth = {
                getToken: function() {
                    let rawToken = localStorage.getItem('auth_token');
                    
                    if (!rawToken) {
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
                        return null;
                    }
                    
                    return rawToken.startsWith('Bearer ') ? rawToken : `Bearer ${rawToken}`;
                },
                
                validateToken: async function(token) {
                    try {
                        const response = await axios.get('/api/user', {
                            headers: {
                                'Authorization': token,
                                'Accept': 'application/json'
                            }
                        });
                        return true;
                    } catch (error) {
                        console.error('Token validation failed', error);
                        window.location.href = '/admin/login';
                        return false;
                    }
                },
                
                getApiHeaders: function(token) {
                    return {
                        headers: {
                            'Authorization': token,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    };
                }
            };

            // Order status management
            const orderStatus = {
                getStatusInfo: function(status) {
                    const statuses = {
                        'pending': { 
                            class: 'bg-yellow-100 text-yellow-800', 
                            icon: 'bx-time',
                            label: 'Pending'
                        },
                        'accepted': { 
                            class: 'bg-blue-100 text-blue-800', 
                            icon: 'bx-check-circle',
                            label: 'Accepted'
                        },
                        'shipped': { 
                            class: 'bg-purple-100 text-purple-800', 
                            icon: 'bx-truck',
                            label: 'Shipped'
                        },
                        'delivered': { 
                            class: 'bg-green-100 text-green-800', 
                            icon: 'bx-package-check',
                            label: 'Delivered'
                        },
                        'declined': { 
                            class: 'bg-red-100 text-red-800', 
                            icon: 'bx-x-circle',
                            label: 'Declined'
                        },
                        'cancelled': { 
                            class: 'bg-red-100 text-red-800', 
                            icon: 'bx-x-circle',
                            label: 'Cancelled'
                        },
                        'completed': { 
                            class: 'bg-green-100 text-green-800', 
                            icon: 'bx-check-double',
                            label: 'Completed'
                        },
                        'default': { 
                            class: 'bg-gray-100 text-gray-800', 
                            icon: 'bx-question-mark',
                            label: 'Unknown'
                        }
                    };
                    
                    return statuses[status] || statuses['default'];
                },
                
                getPaymentStatusInfo: function(paymentStatus) {
                    const status = (paymentStatus || 'unknown').toLowerCase();
                    const statuses = {
                        'paid': { 
                            class: 'bg-green-100 text-green-800', 
                            icon: 'bx-check-circle',
                            label: 'Paid'
                        },
                        'pending': { 
                            class: 'bg-yellow-100 text-yellow-800', 
                            icon: 'bx-time',
                            label: 'Pending'
                        },
                        'failed': { 
                            class: 'bg-red-100 text-red-800', 
                            icon: 'bx-x-circle',
                            label: 'Failed'
                        },
                        'declined': { 
                            class: 'bg-red-100 text-red-800', 
                            icon: 'bx-x-circle',
                            label: 'Declined'
                        },
                        'default': { 
                            class: 'bg-gray-100 text-gray-800', 
                            icon: 'bx-question-mark',
                            label: 'Unknown'
                        }
                    };
                    
                    return statuses[status] || statuses['default'];
                },
                
                getActionButtons: function(status) {
                    const actions = {
                        'pending': [
                            { 
                                status: 'accepted', 
                                label: 'Accept Order',
                                icon: 'bx-check-circle',
                                color: 'bg-green-600 hover:bg-green-700 focus:ring-green-500'
                            },
                            { 
                                status: 'declined', 
                                label: 'Decline Order',
                                icon: 'bx-x-circle',
                                color: 'bg-red-600 hover:bg-red-700 focus:ring-red-500'
                            }
                        ],
                        'accepted': [
                            { 
                                status: 'shipped', 
                                label: 'Mark as Shipped',
                                icon: 'bx-truck',
                                color: 'bg-purple-600 hover:bg-purple-700 focus:ring-purple-500'
                            }
                        ],
                        'shipped': [
                            { 
                                status: 'delivered', 
                                label: 'Mark as Delivered',
                                icon: 'bx-package-check',
                                color: 'bg-green-600 hover:bg-green-700 focus:ring-green-500'
                            }
                        ]
                    };
                    
                    return actions[status] || [];
                }
            };

            // UI utilities
            const ui = {
                formatDate: function(dateString) {
                    const date = new Date(dateString);
                    return date.toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                },
                
                showNotification: function(message, type) {
                    const notification = document.createElement('div');
                    notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg text-white flex items-center gap-2 ${
                        type === 'success' ? 'bg-green-500' : 'bg-red-500'
                    }`;
                    notification.innerHTML = `
                        <i class='bx ${
                            type === 'success' ? 'bx-check-circle' : 'bx-error-circle'
                        } text-xl'></i>
                        <span>${message}</span>
                    `;
                    document.body.appendChild(notification);
                    
                    setTimeout(() => {
                        notification.classList.add('opacity-0', 'transition-opacity', 'duration-300');
                        setTimeout(() => notification.remove(), 300);
                    }, 3000);
                },
                
                preparePrintContent: function(order) {
                    const statusInfo = orderStatus.getStatusInfo(order.status);
                    const paymentInfo = orderStatus.getPaymentStatusInfo(order.payment?.payment_status);
                    
                    // Format order items for print
                    const itemsHtml = order.items?.map(item => `
                        <tr class="border-b border-gray-200">
                            <td class="py-3">
                                <div class="flex items-center gap-3">
                                    <img src="${item.album?.cover_image || '/images/default-cover.png'}" 
                                         alt="Cover" 
                                         class="h-12 w-12 object-cover rounded-md">
                                    <div>
                                        <p class="font-medium">${item.album?.title || 'Unknown Album'}</p>
                                        <p class="text-sm text-gray-600">SKU: ${item.album?.sku || 'N/A'}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="text-right">$${item.unit_price}</td>
                            <td class="text-right">${item.quantity}</td>
                            <td class="text-right">$${(item.unit_price * item.quantity)}</td>
                        </tr>
                    `).join('') || '<tr><td colspan="4" class="py-4 text-center text-gray-500">No items found</td></tr>';
                    
                    // Prepare print content
                    document.getElementById('print-order-id').textContent = order.id || '';
                    document.getElementById('print-order-content').innerHTML = `
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div class="md:col-span-2">
                                <h2 class="text-lg font-medium mb-2">Order Details</h2>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Order Date:</p>
                                        <p class="font-medium">${this.formatDate(order.created_at)}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Order Status:</p>
                                        <p class="font-medium">
                                            <span class="status-badge ${statusInfo.class}">
                                                <i class='bx ${statusInfo.icon}'></i>
                                                ${statusInfo.label}
                                            </span>
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Payment Status:</p>
                                        <p class="font-medium">
                                            <span class="status-badge ${paymentInfo.class}">
                                                <i class='bx ${paymentInfo.icon}'></i>
                                                ${paymentInfo.label}
                                            </span>
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Total Amount:</p>
                                        <p class="font-medium">$${order.total_amount || '0.00'}</p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h2 class="text-lg font-medium mb-2">Customer Information</h2>
                                <div class="space-y-1">
                                    <p class="font-medium">${order.customer?.name || 'Guest User'}</p>
                                    <p class="text-sm text-gray-600">${order.customer?.email || 'No email provided'}</p>
                                    <p class="text-sm text-gray-600 mt-2">
                                        ${order.shipment?.address_line1 || 'N/A'}<br>
                                        ${order.shipment?.address_line2 || ''}<br>
                                        ${order.shipment?.city || ''}, ${order.shipment?.state || ''}<br>
                                        ${order.shipment?.country || ''}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <h2 class="text-lg font-medium mb-2">Order Items</h2>
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="text-left py-2">Item</th>
                                    <th class="text-right py-2">Price</th>
                                    <th class="text-right py-2">Qty</th>
                                    <th class="text-right py-2">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${itemsHtml}
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-right py-2 pr-4">Subtotal:</td>
                                    <td class="text-right py-2">$${order.total_amount || '0.00'}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-right py-2 pr-4">Shipping:</td>
                                    <td class="text-right py-2">$0.00</td>
                                </tr>
                                <tr class="border-t border-gray-200">
                                    <td colspan="3" class="text-right py-2 pr-4 font-medium">Total:</td>
                                    <td class="text-right py-2 font-medium">$${order.total_amount || '0.00'}</td>
                                </tr>
                            </tfoot>
                        </table>
                        
                        <div class="mt-8 pt-4 border-t border-gray-200 text-center text-sm text-gray-500">
                            <p>Thank you for your business!</p>
                            <p class="mt-1">Order ID: ${order.id || ''} • ${this.formatDate(order.created_at)}</p>
                        </div>
                    `;
                }
            };

            // Main order functions
            const order = {
                load: async function() {
                    const token = auth.getToken();
                    if (!token) return;
                    
                    const isValid = await auth.validateToken(token);
                    if (!isValid) return;
                    
                    const orderId = window.location.pathname.split('/').pop();
                    const orderContainer = document.getElementById('order-detail-container');
                    const apiHeaders = auth.getApiHeaders(token);

                    try {
                        const response = await axios.get(`/api/orders/${orderId}`, apiHeaders);
                        const orderData = response.data?.data || response.data;
                        this.render(orderData);
                        ui.preparePrintContent(orderData);
                    } catch (error) {
                        console.error('Error loading order details:', error);
                        orderContainer.innerHTML = `
                            <div class="text-center py-8">
                                <div class="mx-auto text-red-500 text-5xl mb-4">
                                    <i class='bx bx-error-circle'></i>
                                </div>
                                <p class="text-red-500">Error loading order details: ${error.message}</p>
                                <a href="/admin/orders" class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-medium text-white hover:bg-indigo-700 transition-colors duration-200">
                                    <i class='bx bx-arrow-back mr-2'></i>
                                    Back to Orders
                                </a>
                            </div>
                        `;
                    }
                },
                
                render: function(order) {
                    const orderContainer = document.getElementById('order-detail-container');
                    const statusInfo = orderStatus.getStatusInfo(order.status);
                    const paymentInfo = orderStatus.getPaymentStatusInfo(order.payment?.payment_status);
                    const actionButtons = orderStatus.getActionButtons(order.status);
                    
                    // Render order items
                    const itemsHtml = order.items?.map(item => `
                        <div class="flex items-start py-4 border-b border-gray-200 last:border-0 order-item">
                            <img src="${item.album?.cover_image || '/images/default-cover.png'}" 
                                 alt="Cover" 
                                 class="h-16 w-16 object-cover rounded-md">
                            <div class="ml-4 flex-1">
                                <div class="flex justify-between">
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-900">${item.album?.title || 'Unknown Album'}</h3>
                                        <p class="text-xs text-gray-500 mt-1">SKU: ${item.album?.sku || 'N/A'}</p>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900">$${item.unit_price}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-2 mt-2">
                                    <p class="text-sm text-gray-500 flex items-center gap-1">
                                        <i class='bx bx-purchase-tag-alt'></i>
                                        <span>Quantity: ${item.quantity}</span>
                                    </p>
                                    <p class="text-sm text-gray-500 flex items-center gap-1">
                                        <i class='bx bx-calculator'></i>
                                        <span>Subtotal: $${(item.unit_price * item.quantity)}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    `).join('') || '<p class="text-gray-500 py-4 flex items-center gap-2"><i class="bx bx-info-circle"></i> No items found in this order.</p>';

                    // Render action buttons
                    let actionsHtml = '';
                    if (actionButtons.length > 0) {
                        actionsHtml = `
                            <div class="flex flex-col sm:flex-row gap-3 mt-6">
                                ${actionButtons.map(btn => `
                                    <button onclick="updateOrderStatus('${btn.status}')" 
                                            class="action-btn ${btn.color} text-white">
                                        <i class='bx ${btn.icon} mr-2'></i>
                                        ${btn.label}
                                    </button>
                                `).join('')}
                            </div>
                        `;
                    }

                    // Render the complete order details
                    orderContainer.innerHTML = `
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-2">
                                <h2 class="text-lg font-medium text-gray-900 mb-4 flex items-center gap-2">
                                    <i class='bx bx-list-ul'></i>
                                    Order Items
                                </h2>
                                <div class="divide-y divide-gray-200">
                                    ${itemsHtml}
                                </div>
                                <div class="flex justify-end mt-4">
                                    <div class="text-right space-y-1">
                                        <p class="text-sm text-gray-500 flex items-center justify-end gap-1">
                                            <i class='bx bx-receipt'></i>
                                            <span>Subtotal: $${order.total_amount|| '0.00'}</span>
                                        </p>
                                        <p class="text-sm text-gray-500 flex items-center justify-end gap-1">
                                            <i class='bx bx-ship'></i>
                                            <span>Shipping: $0.00</span>
                                        </p>
                                        <p class="text-lg font-medium mt-2 flex items-center justify-end gap-1">
                                            <i class='bx bx-dollar-circle'></i>
                                            <span>Total: $${order.total_amount|| '0.00'}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h2 class="text-lg font-medium text-gray-900 mb-4 flex items-center gap-2">
                                    <i class='bx bx-detail'></i>
                                    Order Summary
                                </h2>
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                                    <div class="mb-4">
                                        <h3 class="text-sm font-medium text-gray-900 flex items-center gap-2">
                                            <i class='bx bx-package'></i>
                                            <span>Order Status</span>
                                        </h3>
                                        <p class="mt-1 flex items-center gap-2">
                                            <span class="status-badge ${statusInfo.class}">
                                                <i class='bx ${statusInfo.icon}'></i>
                                                ${statusInfo.label}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="mb-4">
                                        <h3 class="text-sm font-medium text-gray-900 flex items-center gap-2">
                                            <i class='bx bx-calendar'></i>
                                            <span>Order Date</span>
                                        </h3>
                                        <p class="mt-1 text-sm text-gray-600 flex items-center gap-2">
                                            <i class='bx bx-time'></i>
                                            <span>${ui.formatDate(order.created_at)}</span>
                                        </p>
                                    </div>
                                    <div class="mb-4">
                                        <h3 class="text-sm font-medium text-gray-900 flex items-center gap-2">
                                            <i class='bx bx-user'></i>
                                            <span>Customer</span>
                                        </h3>
                                        <p class="mt-1 text-sm text-gray-600 flex items-center gap-2">
                                            <i class='bx bx-id-card'></i>
                                            <span>${order.customer?.name || 'Guest User'}</span>
                                        </p>
                                        <p class="mt-1 text-sm text-gray-600 flex items-center gap-2">
                                            <i class='bx bx-envelope'></i>
                                            <span>${order.customer?.email || 'No email provided'}</span>
                                        </p>
                                    </div>
                                    <div class="mb-4">
                                        <h3 class="text-sm font-medium text-gray-900 flex items-center gap-2">
                                            <i class='bx bx-map'></i>
                                            <span>Shipping Address</span>
                                        </h3>
                                        <p class="mt-1 text-sm text-gray-600 flex items-start gap-2">
                                            <i class='bx bx-home mt-0.5'></i>
                                            <span>
                                                ${order.shipment?.address_line1 || 'N/A'}<br>
                                                ${order.shipment?.address_line2 ? order.shipment.address_line2 + '<br>' : ''}
                                                ${order.shipment?.city || ''}, ${order.shipment?.state || ''}<br>
                                                ${order.shipment?.country || ''}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="mb-4">
                                        <h3 class="text-sm font-medium text-gray-900 flex items-center gap-2">
                                            <i class='bx bx-credit-card'></i>
                                            <span>Payment Method</span>
                                        </h3>
                                        <p class="mt-1 text-sm text-gray-600 flex items-center gap-2">
                                            <i class='bx bx-card'></i>
                                            <span>
                                                ${order.payment?.card_type ? order.payment.card_type.charAt(0).toUpperCase() + order.payment.card_type.slice(1) : 'N/A'}
                                            </span>
                                        </p>
                                        <p class="mt-1 text-sm text-gray-600 flex items-center gap-2">
                                            <i class='bx bx-dollar-circle'></i>
                                            <span>Paid: $${order.payment?.amount|| '0.00'}</span>
                                        </p>
                                        <p class="mt-1 flex items-center gap-2">
                                            <span class="status-badge ${paymentInfo.class}">
                                                <i class='bx ${paymentInfo.icon}'></i>
                                                ${paymentInfo.label}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                                ${actionsHtml}
                            </div>
                        </div>
                    `;
                },
                
                updateStatus: async function(newStatus) {
                    const token = auth.getToken();
                    if (!token) return;
                    
                    const orderId = window.location.pathname.split('/').pop();
                    const actionName = {
                        'accepted': 'accept',
                        'declined': 'decline',
                        'shipped': 'ship',
                        'delivered': 'deliver'
                    }[newStatus] || newStatus;
                    
                    if (confirm(`Are you sure you want to ${actionName} this order?`)) {
                        const button = event.target;
                        const originalText = button.innerHTML;
                        button.innerHTML = `<i class='bx bx-loader-alt bx-spin mr-2'></i> Processing...`;
                        button.disabled = true;
                        
                        try {
                            const apiHeaders = auth.getApiHeaders(token);
                            await axios.put(`/api/orders/${orderId}`, { status: newStatus }, apiHeaders);
                            ui.showNotification(`Order ${actionName}ed successfully`, 'success');
                            this.load(); // Refresh the order details
                        } catch (error) {
                            console.error('Error updating order status:', error);
                            ui.showNotification(`Failed to ${actionName} order`, 'error');
                            button.innerHTML = originalText;
                            button.disabled = false;
                        }
                    }
                }
            };

            // Initialize
            (async function() {
                await order.load();
                
                // Make the update function available globally
                window.updateOrderStatus = function(newStatus) {
                    order.updateStatus(newStatus);
                };
            })();
        });
    </script>
@endsection