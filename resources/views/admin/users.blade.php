@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">👥 User Management</h1>
            <p class="text-gray-600 mt-2">Manage all registered users in the system</p>
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

    <!-- Users Table -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200">
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="users-container" class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">Loading users...</td>
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
                    // Initialize the dashboard after token validation
                    initializeUserManagement();
                })
                .catch(error => {
                    console.error('Token validation failed', error);
                });

            function initializeUserManagement() {
                const usersContainer = document.getElementById('users-container');

                // Delete User Function
                function deleteUser(userId) {
                    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                        axios.delete(`/api/users/${userId}`, apiHeaders)
                            .then(() => {
                                showAlert('User deleted successfully');
                                loadUsers();
                            })
                            .catch(error => {
                                console.error('Error deleting user:', error);
                                showAlert('Failed to delete user: ' + (error.response?.data?.message || error.message), 'error');
                            });
                    }
                }

                // Load Users
                function loadUsers() {
                    usersContainer.innerHTML = '<tr><td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">Loading users...</td></tr>';

                    axios.get('/api/users', apiHeaders)
                        .then(response => {
                            const users = response.data?.data || response.data || [];

                            if (!Array.isArray(users)) {
                                throw new Error('Invalid users data format');
                            }

                            if (users.length === 0) {
                                usersContainer.innerHTML = '<tr><td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">No users found.</td></tr>';
                                return;
                            }

                            usersContainer.innerHTML = users.map((user, index) => `
                                <tr class="hover:bg-gray-50" data-user-id="${user.id}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${index + 1}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${user.name || 'N/A'}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${user.email || 'N/A'}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            ${user.user_type === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800'}">
                                            ${user.user_type || 'user'}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        ${new Date(user.created_at).toLocaleDateString()}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="window.deleteUser(${user.id})" 
                                            class="text-red-600 hover:text-red-800 transition-colors duration-200">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            `).join('');
                        })
                        .catch(error => {
                            console.error('Error loading users:', error);
                            usersContainer.innerHTML = `<tr><td colspan="7" class="px-6 py-4 text-center text-sm text-red-600">
                                Error loading users: ${error.message}
                            </td></tr>`;
                        });
                }

                // Show Alert Function
                function showAlert(message, type = 'success') {
                    const alertDiv = document.createElement('div');
                    alertDiv.className = `fixed bottom-4 right-4 p-4 rounded-lg shadow-lg ${
                        type === 'success' ? 'bg-green-50 text-green-800 border border-green-200' : 'bg-red-50 text-red-800 border border-red-200'
                    }`;
                    alertDiv.innerHTML = `
                        <div class="flex items-center">
                            <i class='bx ${type === 'success' ? 'bx-check-circle text-green-500' : 'bx-error text-red-500'} mr-2'></i>
                            <span>${message}</span>
                        </div>
                    `;
                    
                    document.body.appendChild(alertDiv);
                    
                    setTimeout(() => {
                        alertDiv.classList.add('opacity-0', 'transition-opacity', 'duration-300');
                        setTimeout(() => alertDiv.remove(), 300);
                    }, 3000);
                }

                // Initial load
                loadUsers();

                // Make functions available globally
                window.deleteUser = deleteUser;
            }
        });
    </script>
@endsection