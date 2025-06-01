@extends('layouts.admin')

@section('title', 'Album List')

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">🎵 Album Management</h1>
            <p class="text-gray-600 mt-2">Manage your music collection with ease</p>
            @if(session('token'))
                <script>
                    localStorage.setItem('auth_token', '{{ session('token') }}');
                </script>
            @endif
        </div>
        <button id="create-album-btn" 
           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-blue-600 border border-transparent rounded-lg font-medium text-white hover:from-purple-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
            <i class='bx bx-plus mr-2 text-xl'></i> Add New Album
        </button>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    @endpush

    <!-- Modal Backdrop (Hidden by default) -->
    <div id="modal-backdrop" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-40"></div>

    <!-- Album Form Modal (Hidden by default) -->
    <div id="album-form-modal" class="hidden fixed inset-0 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 id="form-title" class="text-xl font-bold text-gray-900">Create New Album</h3>
                    <button id="close-modal-btn" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="album-form" enctype="multipart/form-data" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                            <input type="text" name="title" id="title" required 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 border">
                        </div>
                        <div>
                            <label for="artist" class="block text-sm font-medium text-gray-700 mb-1">Artist</label>
                            <input type="text" name="artist" id="artist" required 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 border">
                        </div>
                        <div>
                            <label for="release_year" class="block text-sm font-medium text-gray-700 mb-1">Release Year</label>
                            <input type="number" name="release_year" id="release_year" required 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 border">
                        </div>
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                            <input type="number" step="0.01" name="price" id="price" required 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 border">
                        </div>
                        <div>
                            <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                            <input type="number" name="stock" id="stock" required 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 border">
                        </div>
                        <div>
                            <label for="cover_image" class="block text-sm font-medium text-gray-700 mb-1">Cover Image</label>
                            <input type="file" name="cover_image" id="cover_image" 
                                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <div id="current-cover" class="mt-2 hidden">
                                <span class="text-sm text-gray-500">Current Cover:</span>
                                <img id="current-cover-image" src="" class="h-20 w-20 object-cover mt-1 rounded">
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label for="genres-select" class="block text-sm font-medium text-gray-700 mb-1">Genres</label>
                            <select name="genres[]" id="genres-select" multiple 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 border">
                                <!-- Genres will be loaded here -->
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" id="cancel-album-btn" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                            Cancel
                        </button>
                        <button type="submit" id="submit-btn" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                            Save Album
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Albums Table -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cover</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Artist</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Genres</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="albums-container" class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">Loading albums...</td>
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
                const createAlbumBtn = document.getElementById('create-album-btn');
                const modalBackdrop = document.getElementById('modal-backdrop');
                const albumFormModal = document.getElementById('album-form-modal');
                const closeModalBtn = document.getElementById('close-modal-btn');
                const cancelAlbumBtn = document.getElementById('cancel-album-btn');
                const albumForm = document.getElementById('album-form');
                const genresSelect = document.getElementById('genres-select');
                const albumsContainer = document.getElementById('albums-container');
                const formTitle = document.getElementById('form-title');
                const submitBtn = document.getElementById('submit-btn');
                const currentCoverDiv = document.getElementById('current-cover');
                const currentCoverImage = document.getElementById('current-cover-image');

                // Function to show modal
                function showModal() {
                    modalBackdrop.classList.remove('hidden');
                    albumFormModal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                }

                // Function to hide modal
                function hideModal() {
                    modalBackdrop.classList.add('hidden');
                    albumFormModal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                    albumForm.reset();
                    albumForm.dataset.albumId = '';
                    currentCoverDiv.classList.add('hidden');
                }

                // Event listeners for modal
                createAlbumBtn.addEventListener('click', () => {
                    albumForm.reset();
                    albumForm.dataset.albumId = '';
                    formTitle.textContent = 'Create New Album';
                    submitBtn.textContent = 'Save Album';
                    currentCoverDiv.classList.add('hidden');
                    showModal();
                    loadGenresForForm();
                });

                closeModalBtn.addEventListener('click', hideModal);
                cancelAlbumBtn.addEventListener('click', hideModal);
                modalBackdrop.addEventListener('click', hideModal);

                // Load Genres for Form
                function loadGenresForForm() {
                    return new Promise((resolve) => {
                        genresSelect.innerHTML = '<option value="" disabled>Loading genres...</option>';

                        axios.get('/api/genres', apiHeaders)
                            .then(response => {
                                const genresData = Array.isArray(response.data) ? response.data :
                                    (response.data?.data || response.data?.genres || []);

                                if (!Array.isArray(genresData)) {
                                    throw new Error('Invalid genres data format');
                                }

                                genresSelect.innerHTML = genresData.map(genre => 
                                    `<option value="${genre.id}">${genre.name}</option>`
                                ).join('');
                                resolve();
                            })
                            .catch(error => {
                                console.error('Error loading genres:', error);
                                genresSelect.innerHTML = '<option value="" disabled>Error loading genres</option>';
                                resolve();
                            });
                    });
                }

                // Edit Album Function
                function editAlbum(albumId) {
                    axios.get(`/api/albums/${albumId}`, apiHeaders)
                        .then(response => {
                            const album = response.data.data || response.data;
                            
                            // Show the form
                            formTitle.textContent = 'Edit Album';
                            submitBtn.textContent = 'Update Album';
                            albumForm.dataset.albumId = albumId;
                            
                            // Fill the form with album data
                            document.getElementById('title').value = album.title;
                            document.getElementById('artist').value = album.artist;
                            document.getElementById('release_year').value = album.release_year;
                            document.getElementById('price').value = album.price;
                            document.getElementById('stock').value = album.stock;
                            
                            // Show current cover image if exists
                            if (album.cover_image) {
                                currentCoverImage.src = album.cover_image;
                                currentCoverDiv.classList.remove('hidden');
                            } else {
                                currentCoverDiv.classList.add('hidden');
                            }
                            
                            // Load genres and set selected ones
                            loadGenresForForm().then(() => {
                                if (album.genres && album.genres.length) {
                                    // Select the album's genres
                                    album.genres.forEach(genre => {
                                        const option = genresSelect.querySelector(`option[value="${genre.id}"]`);
                                        if (option) option.selected = true;
                                    });
                                }
                            });
                            
                            showModal();
                        })
                        .catch(error => {
                            console.error('Error loading album:', error);
                            alert('Failed to load album for editing');
                        });
                }

                // Delete Album Function
                function deleteAlbum(albumId) {
                    if (confirm('Are you sure you want to delete this album? This action cannot be undone.')) {
                        axios.delete(`/api/albums/${albumId}`, apiHeaders)
                            .then(() => {
                                alert('Album deleted successfully');
                                loadAlbums();
                            })
                            .catch(error => {
                                console.error('Error deleting album:', error);
                                alert('Failed to delete album');
                            });
                    }
                }

                // Submit Album Form (Create/Update)
                albumForm.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const formData = new FormData();
                    const albumId = albumForm.dataset.albumId;
                    const isUpdate = !!albumId;

                    // Add all form fields to FormData
                    formData.append('title', document.getElementById('title').value);
                    formData.append('artist', document.getElementById('artist').value);
                    formData.append('release_year', document.getElementById('release_year').value);
                    formData.append('price', document.getElementById('price').value);
                    formData.append('stock', document.getElementById('stock').value);
                    
                    // Add cover image if selected
                    const coverImageInput = document.getElementById('cover_image');
                    if (coverImageInput.files[0]) {
                        formData.append('cover_image', coverImageInput.files[0]);
                    }

                    // Add selected genres
                    const selectedGenres = Array.from(genresSelect.selectedOptions).map(option => option.value);
                    formData.append('genres', JSON.stringify(selectedGenres));

                    const headers = {
                        ...apiHeaders.headers,
                        'Content-Type': 'multipart/form-data'
                    };

                    const request = isUpdate 
                        ? axios.post(`/api/albums/${albumId}`, formData, {
                            headers: headers,
                            params: { _method: 'PUT' }
                        })
                        : axios.post('/api/albums', formData, { headers: headers });

                    request.then(() => {
                        alert(`Album ${isUpdate ? 'updated' : 'created'} successfully!`);
                        hideModal();
                        loadAlbums();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        if (error.response) {
                            alert(`Error: ${error.response.data?.message || error.response.statusText}`);
                        } else {
                            alert('Network error: ' + error.message);
                        }
                    });
                });

                // Load Albums
                function loadAlbums() {
                    albumsContainer.innerHTML = '<tr><td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">Loading albums...</td></tr>';

                    axios.get('/api/albums', apiHeaders)
                        .then(response => {
                            const albums = response.data?.data || response.data || [];

                            if (!Array.isArray(albums)) {
                                throw new Error('Invalid albums data format');
                            }

                            if (albums.length === 0) {
                                albumsContainer.innerHTML = '<tr><td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">No albums found.</td></tr>';
                                return;
                            }

                            albumsContainer.innerHTML = albums.map((album, index) => `
                                <tr class="hover:bg-gray-50" data-album-id="${album.id}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${index + 1}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <img src="${album.cover_image || '/images/default-cover.png'}" alt="Cover" class="h-12 w-12 object-cover rounded-md">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${album.title || 'N/A'}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${album.artist || 'N/A'}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${album.release_year || 'N/A'}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$${(album.price || 0).toFixed(2)}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${album.stock || 0}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        ${(album.genres && album.genres.length) ? 
                                            album.genres.map(g => `<span class="inline-block bg-gray-100 rounded-full px-3 py-1 text-xs font-medium text-gray-700 mr-1 mb-1">${g.name || 'Unnamed'}</span>`).join('') : 
                                            '<span class="text-gray-400">No genres</span>'}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="window.editAlbum(${album.id})" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                        <button onclick="window.deleteAlbum(${album.id})" class="text-red-600 hover:text-red-900">Delete</button>
                                    </td>
                                </tr>
                            `).join('');
                        })
                        .catch(error => {
                            console.error('Error loading albums:', error);
                            albumsContainer.innerHTML = `<tr><td colspan="8" class="px-6 py-4 text-center text-sm text-red-500">
                                Error loading albums: ${error.message}
                            </td></tr>`;
                        });
                }

                // Initial load
                loadAlbums();

                // Make functions available globally
                window.editAlbum = editAlbum;
                window.deleteAlbum = deleteAlbum;
            }
        });
    </script>
@endsection