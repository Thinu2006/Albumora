<x-guest-layout>
    <div class="flex items-center justify-center min-h-screen bg-gray-200">
        <div class="bg-white shadow-lg rounded-3xl flex w-[90%] sm:w-[65%] h-[550px]">
            <!-- Left Panel with Background Image -->
            <div class="hidden md:flex w-full md:w-1/2 relative bg-cover bg-center rounded-l-3xl" 
                 style="background-image: url('{{ asset('images/SignInBG.PNG') }}')">
                <div class="absolute inset-0 bg-black opacity-30 rounded-l-3xl"></div>
            </div>
            
            <!-- Right Panel with Form -->
            <div class="w-full md:w-1/2 p-8 flex flex-col justify-center">
                <h2 class="text-3xl font-bold text-center mb-8 font-rowdies">SIGN IN</h2>
                
                <x-validation-errors class="mb-4" />

                @session('status')
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ $value }}
                    </div>
                @endsession

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="sr-only">Email</label>
                        <input type="email" id="email" name="email" placeholder="Email" 
                               class="w-full px-4 py-3 rounded-lg border-gray-300 focus:border-gray-500 focus:ring focus:ring-gray-200"
                               value="{{ old('email') }}" required autofocus>
                    </div>

                    <div>
                        <label for="password" class="sr-only">Password</label>
                        <input type="password" id="password" name="password" placeholder="Password" 
                               class="w-full px-4 py-3 rounded-lg border-gray-300 focus:border-gray-500 focus:ring focus:ring-gray-200" required>
                    </div>

                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-gray-600 shadow-sm focus:ring-gray-500" name="remember">
                            <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                                {{ __('Forgot password?') }}
                            </a>
                        @endif
                    </div>

                    <div class="text-center pt-4">
                        <button type="submit" class="w-full py-3 bg-gray-800 hover:bg-gray-700 text-white font-bold rounded-lg transition duration-200">
                            {{ __('Sign In') }}
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-gray-500">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="text-gray-800 hover:underline">
                            Sign Up
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