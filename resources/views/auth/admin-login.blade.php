<x-guest-layout>
    <div class="min-h-screen bg-gray-200 flex items-center justify-center p-4">
        <div class="bg-white shadow-lg rounded-3xl flex flex-col md:flex-row w-full max-w-4xl h-auto md:h-[500px]"> 
            <!-- Left Panel with Background Image -->
            <div class="hidden md:block md:w-1/2 relative rounded-l-3xl overflow-hidden">
                <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/AdminSignInPageBG.PNG') }}')"></div>
                <div class="absolute inset-0 bg-black bg-opacity-30 rounded-l-3xl"></div>
            </div>

            <!-- Right Panel with Login Form -->
            <div class="w-full md:w-1/2 px-8 md:px-12 py-8 flex flex-col justify-center"> 
                <h2 class="text-3xl font-bold text-center font-rowdies mb-6">ADMIN SIGN IN</h2> 

                @if (session('status'))
                    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            {{ session('status') }}
                        </div>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('admin.login') }}" class="space-y-5"> 
                    @csrf
                    <div class="space-y-2"> 
                        <x-label for="email" :value="__('Email')" class="sr-only" />
                        <x-input id="email" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 bg-gray-50 focus:border-gray-400 focus:ring-1 focus:ring-gray-200" 
                                type="email" 
                                name="email" 
                                :value="old('email')" 
                                required 
                                autofocus 
                                placeholder="Email" 
                                autocomplete="email" />
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2"> 
                        <x-label for="password" :value="__('Password')" class="sr-only" />
                        <x-input id="password" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 bg-gray-50 focus:border-gray-400 focus:ring-1 focus:ring-gray-200" 
                                type="password" 
                                name="password" 
                                required 
                                placeholder="Password" 
                                autocomplete="current-password" />
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between pt-2"> 
                        <!-- Remember Me -->
                        <div class="block">
                            <label for="remember_me" class="inline-flex items-center">
                                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
                                <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                            </label>
                        </div>

                        <!-- Forgot Password -->
                        @if (Route::has('admin.password.request'))
                            <a class="text-sm text-gray-600 hover:text-gray-900 underline" href="{{ route('admin.password.request') }}">
                                {{ __('Forgot password?') }}
                            </a>
                        @endif
                    </div>

                    <div class="pt-4"> 
                        <x-button class="w-full py-3 bg-gray-800 hover:bg-gray-700 text-white font-bold rounded-lg transition duration-200 flex justify-center items-center">
                            {{ __('Login') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>