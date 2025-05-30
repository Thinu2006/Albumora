<x-guest-layout>
    <div class="flex items-center justify-center min-h-screen bg-gray-200">
        <div class="bg-white shadow-lg rounded-3xl flex w-[90%] sm:w-[65%] h-[600px]">
            <!-- Left Panel with Background Image -->
            <div class="hidden md:flex w-full md:w-1/2 relative bg-cover bg-center rounded-l-3xl" 
                 style="background-image: url('{{ asset('images/SignUpBG.PNG') }}')">
                <div class="absolute inset-0 bg-black opacity-30 rounded-l-3xl"></div>
            </div>
            
            <!-- Right Panel with Form -->
            <div class="w-full md:w-1/2 p-8 flex flex-col justify-center">
                <h2 class="text-3xl font-bold text-center mb-8 font-rowdies">SIGN UP</h2>
                
                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="name" class="sr-only">Name</label>
                        <input type="text" id="name" name="name" placeholder="Name" 
                               class="w-full px-4 py-3 rounded-lg border-gray-300 focus:border-gray-500 focus:ring focus:ring-gray-200"
                               value="{{ old('name') }}" required autofocus autocomplete="name">
                    </div>

                    <div>
                        <label for="email" class="sr-only">Email</label>
                        <input type="email" id="email" name="email" placeholder="Email" 
                               class="w-full px-4 py-3 rounded-lg border-gray-300 focus:border-gray-500 focus:ring focus:ring-gray-200"
                               value="{{ old('email') }}" required autocomplete="username">
                    </div>

                    <div>
                        <label for="password" class="sr-only">Password</label>
                        <input type="password" id="password" name="password" placeholder="Password" 
                               class="w-full px-4 py-3 rounded-lg border-gray-300 focus:border-gray-500 focus:ring focus:ring-gray-200"
                               required autocomplete="new-password">
                    </div>

                    <div>
                        <label for="password_confirmation" class="sr-only">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" 
                               placeholder="Confirm Password" 
                               class="w-full px-4 py-3 rounded-lg border-gray-300 focus:border-gray-500 focus:ring focus:ring-gray-200"
                               required autocomplete="new-password">
                    </div>

                    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                        <div class="mt-4">
                            <label for="terms" class="flex items-center">
                                <input type="checkbox" name="terms" id="terms" class="rounded border-gray-300 text-gray-600 shadow-sm focus:ring-gray-500" required>
                                <span class="ms-2 text-sm text-gray-600">
                                    {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                            'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-gray-600 hover:text-gray-900">'.__('Terms of Service').'</a>',
                                            'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-gray-600 hover:text-gray-900">'.__('Privacy Policy').'</a>',
                                    ]) !!}
                                </span>
                            </label>
                        </div>
                    @endif

                    <div class="text-center pt-6 px-10">
                        <button type="submit" class="w-full py-3 bg-gray-800 hover:bg-gray-700 text-white font-bold rounded-lg transition duration-200">
                            Sign Up
                        </button>
                    </div>
                </form>

                <div class="mt-4 text-center">
                    <p class="text-gray-500">
                        Already have an account?
                        <a href="{{ route('login') }}" class="text-gray-800 hover:underline">
                            Sign In
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link href="https://fonts.googleapis.com/css2?family=Rowdies:wght@300;400;700&display=swap" rel="stylesheet">
        <style>
            .font-rowdies {
                font-family: 'Rowdies', cursive;
            }
        </style>
    @endpush
</x-guest-layout>