<div>
    <!-- Users Table -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200">
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <!-- Table header remains the same -->
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
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if($loading)
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Loading users...</td>
                            </tr>
                        @elseif(count($users['data'] ?? $users) === 0)
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No users found.</td>
                            </tr>
                        @else
                            @foreach(($users['data'] ?? $users) as $index => $user)
                                <tr class="hover:bg-gray-50" wire:key="user-{{ $user['id'] }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ ($users['current_page'] - 1) * $users['per_page'] + $loop->iteration }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user['name'] ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user['email'] ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $user['user_type'] === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $user['user_type'] ?? 'user' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($user['created_at'])->toFormattedDateString() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button wire:click="deleteUser({{ $user['id'] }})" 
                                            class="text-red-600 hover:text-red-800 transition-colors duration-200">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            @if(isset($users['links']))
                <div class="px-6 py-4">
                    {{ $users['links'] }}
                </div>
            @endif
        </div>
    </div>

    <!-- Keep the alert script the same -->
    @push('scripts')
        <script>
            document.addEventListener('livewire:initialized', () => {
                Livewire.on('alert', ({type, message}) => {
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
                });
            });
        </script>
    @endpush
</div>