<x-app-layout>
    <div class="py-12">
        <div class="mx-auto px-6 sm:px-20">
            <!-- Welcome Banner Section -->
            <div class="relative bg-cover bg-center bg-opacity-75 rounded-lg overflow-hidden mb-10 sm:mb-24" style="background-image: url('{{ asset('images/Banner.png') }}');">
                <div class="absolute inset-0 bg-gray-800 bg-opacity-60"></div>
                <div class="relative text-center box-border p-6 sm:p-10">
                    <h2 class="text-4xl sm:text-6xl font-bold text-white mb-4 font-rowdies">Welcome</h2>
                    <p class="text-lg sm:text-2xl text-gray-100 mx-auto sm:w-3/4 leading-relaxed mb-4">
                        Discover your trusted destination for buying authentic music albums. From the latest hits to rare classics, we offer a carefully curated collection for every music lover.
                    </p> 
                    <p class="text-lg sm:text-2xl text-gray-200 mx-auto sm:w-3/4 leading-relaxed">
                        With secure payments, quality assurance, and excellent customer support, we ensure a seamless shopping experience. Trust us to bring you closer to the music that moves you.
                    </p>
                </div>
            </div>

            <!-- New Releases Section -->
            <div class="bg-[#e6e7e7] overflow-hidden shadow-xl sm:rounded-lg p-6 mb-10 sm:mb-24">
                <div>
                    <h3 class="text-3xl font-bold text-[#1F2937] font-rowdies mb-2 text-center">Fresh Releases</h3>
                    <div class="text-right">
                        <a href="{{ route('user.album-search') }}" class="text-[#4B5563] hover:text-[#6366F1] font-medium transition-colors duration-200">
                            View All <i class='bx bx-chevron-right ml-1 text-xl'></i>
                        </a>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-20 p-10"> 
                    @foreach($newReleases as $album) 
                    <div class="group relative bg-white rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 h-full flex flex-col">
                        <div class="relative aspect-square overflow-hidden"> 
                            <img src="{{ $album->cover_image_url }}" alt="{{ $album->title }}" 
                                class="absolute w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        
                        <div class="p-4 flex-grow flex flex-col"> 
                            <h4 class="text-lg font-bold text-gray-800 truncate">{{ $album->title }}</h4>
                            <p class="text-gray-600 text-sm">{{ $album->artist }}</p> 
                            <div class="mt-auto pt-3 flex justify-between items-center"> 
                                <span class="text-md font-bold text-[#6366F1]">${{ number_format($album->price, 2) }}</span>
                                <span class="text-xs text-gray-500">{{ $album->release_year }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Review Section -->
            <div class="bg-[#e6e7e7] overflow-hidden shadow-xl sm:rounded-lg p-6 mb-10 sm:mb-24">
                <div class="text-center">
                    <h3 class="text-3xl font-bold text-[#1F2937] font-rowdies mb-2">Customer Reviews</h3>
                    <p class="text-[#4B5563] max-w-2xl mx-auto">What our customers say about their experience</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-16 p-10">
                    @foreach($recentReviews as $review)
                        <div class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 p-6">
                            <div class="mb-4 border-b border-[#E5E7EB] pb-3">
                                @php $customer = \App\Models\User::find($review->customer_id); @endphp
                                <h4 class="text-lg font-semibold text-[#1F2937]">{{ $customer?->name ?? 'Unknown' }}</h4>

                                <p class="text-sm text-[#6B7280] mb-2">{{ $review->created_at->format('M d, Y') }}</p>
                                <div class="flex mb-2">
                                    @for($i = 0; $i < 5; $i++)
                                        <i class='bx bxs-star text-[#F59E0B]'></i>
                                    @endfor
                                </div>
                            </div>
                            <div class="text-[#4B5563] mb-4">
                                <p class="italic">"{{ $review->text }}"</p>
                            </div>
                            <div class="flex justify-end">
                                <span class="text-xs text-[#6366F1] bg-[#EEF2FF] px-2 py-1 rounded-full">Verified Purchase</span>
                            </div>
                        </div>
                    @endforeach

                </div>
                
                <div class="mt-2 text-center">
                    <a href="{{ route('user.review') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-[#212124] hover:bg-[#484849] transition-colors duration-300 shadow-sm">
                        Write Your Review
                        <i class='bx bx-edit ml-2'></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>