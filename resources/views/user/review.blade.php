<x-app-layout>
    <div class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="text-center mb-8 bg-white p-6 rounded-xl shadow-sm border border-[#e2e8f0]">
                <h1 class="text-2xl md:text-3xl font-bold text-[#1e293b] flex items-center justify-center">
                    <i class='bx bx-edit text-[#6366f1] mr-3'></i> Customer Reviews
                </h1>
                <p class="mt-2 text-[#64748b]">Share your experience with our community</p>
            </div>

            <!-- Review Form -->
            <div class="mb-10 p-6 bg-white rounded-xl shadow-sm border border-[#e2e8f0]">
                <h2 class="text-lg font-semibold text-[#1e293b] mb-4 flex items-center" id="formTitle">
                    <i class='bx bx-edit-alt text-[#6366f1] mr-2'></i> Write a Review
                </h2>
                <form id="reviewForm" class="space-y-4">
                    <input type="hidden" id="reviewId">
                    <div>
                        <label for="reviewText" class="block text-sm font-medium text-[#1e293b] mb-2">Your Thoughts</label>
                        <textarea id="reviewText" name="text" rows="4" 
                            class="w-full px-4 py-3 border border-[#e2e8f0] rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#6366f1] focus:border-[#6366f1] transition-all"
                            placeholder="What did you like about our product or service?"></textarea>
                    </div>
                    <div class="flex space-x-3">
                        <button type="submit" 
                            class="inline-flex items-center px-5 py-2.5 bg-[#6366f1] hover:bg-[#4f46e5] border border-transparent rounded-md font-medium text-white text-xs uppercase tracking-widest transition duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#6366f1]">
                            <i class='bx bx-send mr-2'></i> Submit Review
                        </button>
                        <button type="button" id="cancelEdit" class="hidden items-center px-5 py-2.5 bg-[#f1f5f9] hover:bg-[#e2e8f0] border border-transparent rounded-md font-medium text-[#64748b] text-xs uppercase tracking-widest transition duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#c7d2fe]">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>

            <!-- Reviews List -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- My Reviews Section -->
                <div id="myReviewsSection" class="bg-white rounded-xl shadow-sm p-6 border border-[#e2e8f0]">
                    <div class="flex items-center mb-6 pb-2 border-b border-[#e2e8f0]">
                        <i class='bx bx-user-check text-2xl text-[#6366f1] mr-3'></i>
                        <h2 class="text-xl font-semibold text-[#1e293b]">My Reviews</h2>
                    </div>
                    <div id="myReviewsContainer" class="space-y-5">
                        <div class="text-center py-10 text-[#64748b]" id="loadingMyReviews">
                            <i class='bx bx-loader-alt bx-spin text-3xl mb-3'></i>
                            <p>Loading your reviews...</p>
                        </div>
                    </div>
                </div>

                <!-- Community Reviews Section -->
                <div id="otherReviewsSection" class="bg-white rounded-xl shadow-sm p-6 border border-[#e2e8f0]">
                    <div class="flex items-center mb-6 pb-2 border-b border-[#e2e8f0]">
                        <i class='bx bx-group text-2xl text-[#6366f1] mr-3'></i>
                        <h2 class="text-xl font-semibold text-[#1e293b]">Community Reviews</h2>
                    </div>
                    <div id="otherReviewsContainer" class="space-y-5">
                        <div class="text-center py-10 text-[#64748b]" id="loadingOtherReviews">
                            <i class='bx bx-loader-alt bx-spin text-3xl mb-3'></i>
                            <p>Loading community reviews...</p>
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
            loadReviews();
            
            // Form submission handler
            document.getElementById('reviewForm').addEventListener('submit', function(e) {
                e.preventDefault();
                submitReview();
            });

            // Cancel edit handler
            document.getElementById('cancelEdit').addEventListener('click', resetForm);
        });

        let editingReviewId = null;

        function loadReviews() {
            axios.get('/api/reviews')
                .then(response => {
                    const reviewsData = response.data.data || response.data;
                    if (reviewsData?.length >= 0) {
                        displayReviews(response.data);
                    } else {
                        throw new Error('Invalid response format');
                    }
                })
                .catch(error => {
                    console.error('Error loading reviews:', error);
                    const errorMsg = error.response?.data?.message || error.message || 'Error loading reviews';
                    document.getElementById('loadingMyReviews').innerHTML = `
                        <div class="bg-[#fee2e2] border-l-4 border-[#ef4444] p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class='bx bx-error text-[#ef4444]'></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-[#991b1b]">${errorMsg}</p>
                                </div>
                            </div>
                        </div>
                    `;
                });
        }

        function submitReview() {
            const reviewText = document.getElementById('reviewText').value;
            const userId = {{ Auth::id() }};
            const url = editingReviewId ? `/api/reviews/${editingReviewId}` : '/api/reviews';
            const method = editingReviewId ? 'put' : 'post';

            axios[method](url, {
                text: reviewText,
                customer_id: userId
            }, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(() => {
                resetForm();
                loadReviews();
                showAlert(editingReviewId ? 'Review updated!' : 'Review submitted!');
            })
            .catch(error => {
                console.error('Error submitting review:', error);
                showAlert('Error: ' + (error.response?.data?.message || 'Please try again.'), 'error');
            });
        }

        function displayReviews(reviews) {
            const userId = {{ Auth::id() ?? 'null' }};
            const reviewsArray = reviews.data || reviews;
            
            // Separate reviews
            const [myReviews, otherReviews] = reviewsArray.reduce(([mine, others], review) => {
                const isOwner = userId && (review.customer_id == userId || (review.customer && review.customer.id == userId));
                return isOwner ? [[...mine, review], others] : [mine, [...others, review]];
            }, [[], []]);
            
            // Display reviews
            displayReviewSection('myReviewsContainer', myReviews, true);
            displayReviewSection('otherReviewsContainer', otherReviews, false);
        }

        function displayReviewSection(containerId, reviews, isOwner) {
            const container = document.getElementById(containerId);
            
            if (reviews.length > 0) {
                container.innerHTML = reviews.map(review => createReviewCard(review, isOwner)).join('');
            } else {
                container.innerHTML = `
                    <div class="text-center py-8 rounded-lg bg-[#f8fafc] border border-dashed border-[#e2e8f0]">
                        <i class='bx bx-message-rounded-minus text-4xl text-[#cbd5e1] mb-3'></i>
                        <p class="text-[#64748b]">${isOwner ? "You haven't written any reviews yet." : "No reviews from the community yet."}</p>
                    </div>
                `;
            }
        }

        function createReviewCard(review, isOwner) {
            const createdAt = new Date(review.created_at).toLocaleDateString('en-US', { 
                year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'
            });
            
            const customerName = review.customer?.name || 'Anonymous';
            const initials = customerName.split(' ').slice(0, 2).map(n => n[0]).join('').toUpperCase();
            
            return `
                <div class="p-5 rounded-xl ${isOwner ? 'bg-[#eef2ff] border-l-4 border-[#6366f1]' : 'bg-white border-l-4 border-[#e2e8f0]'} shadow-sm hover:shadow-md transition-all">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full ${isOwner ? 'bg-[#6366f1] text-white' : 'bg-[#f1f5f9] text-[#1e293b]'} font-medium">
                                ${initials}
                            </div>
                            <div>
                                <h3 class="font-medium text-[#1e293b]">${customerName}</h3>
                                <p class="text-xs text-[#64748b]">${createdAt}</p>
                            </div>
                        </div>
                        ${isOwner ? `
                        <div class="flex space-x-2">
                            <button onclick="editReview('${review.id}')" class="p-1.5 text-[#6366f1] hover:text-[#4f46e5] hover:bg-[#e0e7ff] rounded-full transition-colors" title="Edit">
                                <i class='bx bx-edit-alt'></i>
                            </button>
                            <button onclick="deleteReview('${review.id}')" class="p-1.5 text-[#ef4444] hover:text-[#dc2626] hover:bg-[#fee2e2] rounded-full transition-colors" title="Delete">
                                <i class='bx bx-trash'></i>
                            </button>
                        </div>
                        ` : ''}
                    </div>
                    <p class="text-[#1e293b] mt-2 pl-13">${review.text}</p>
                </div>
            `;
        }

        function editReview(reviewId) {
            axios.get(`/api/reviews/${reviewId}`)
                .then(response => {
                    const reviewText = response.data.data?.text || response.data.text;
                    if (!reviewText) throw new Error('Review text not found');
                    
                    document.getElementById('reviewText').value = reviewText;
                    document.getElementById('reviewId').value = reviewId;
                    editingReviewId = reviewId;
                    
                    // Update UI for editing
                    document.getElementById('formTitle').innerHTML = `<i class='bx bx-edit-alt text-[#6366f1] mr-2'></i> Edit Your Review`;
                    document.querySelector('#reviewForm button[type="submit"]').innerHTML = `<i class='bx bx-save mr-2'></i> Update Review`;
                    document.getElementById('cancelEdit').classList.remove('hidden');
                    
                    // Scroll to form
                    document.getElementById('reviewText').focus();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                })
                .catch(error => {
                    console.error('Error fetching review:', error);
                    showAlert('Error loading review: ' + error.message, 'error');
                });
        }

        function deleteReview(reviewId) {
            if (!confirm('Delete this review permanently?')) return;
            
            axios.delete(`/api/reviews/${reviewId}`, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(() => {
                showAlert('Review deleted');
                loadReviews();
            })
            .catch(error => {
                console.error('Error deleting review:', error);
                showAlert('Error deleting review', 'error');
            });
        }

        function resetForm() {
            document.getElementById('reviewForm').reset();
            document.getElementById('reviewId').value = '';
            editingReviewId = null;
            document.getElementById('formTitle').innerHTML = `<i class='bx bx-edit-alt text-[#6366f1] mr-2'></i> Write a Review`;
            document.querySelector('#reviewForm button[type="submit"]').innerHTML = `<i class='bx bx-send mr-2'></i> Submit Review`;
            document.getElementById('cancelEdit').classList.add('hidden');
        }

        function showAlert(message, type = 'success') {
            const alertDiv = document.createElement('div');
            alertDiv.className = `fixed bottom-4 right-4 max-w-sm p-4 rounded-lg shadow-lg ${
                type === 'success' ? 'bg-[#ecfdf5] text-[#065f46] border border-[#a7f3d0]' : 'bg-[#fee2e2] text-[#991b1b] border border-[#fecaca]'
            }`;
            alertDiv.innerHTML = `
                <div class="flex items-start">
                    <i class='bx ${type === 'success' ? 'bx-check-circle text-[#10b981]' : 'bx-error text-[#ef4444]'} mr-2 mt-0.5 text-xl'></i>
                    <div>
                        <p class="font-medium">${message}</p>
                    </div>
                </div>
            `;
            
            document.body.appendChild(alertDiv);
            setTimeout(() => {
                alertDiv.classList.add('opacity-0', 'transition-opacity', 'duration-300');
                setTimeout(() => alertDiv.remove(), 300);
            }, 3000);
        }
    </script>
    @endpush
</x-app-layout>