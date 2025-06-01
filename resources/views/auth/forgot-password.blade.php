<x-guest-layout>
    <div class="flex items-center justify-center min-h-screen bg-gray-200">
        <div class="bg-white shadow-lg rounded-3xl w-[90%] sm:w-[160%] max-w-lg p-8">
            <h2 class="text-3xl font-bold text-center mb-6 font-rowdies">RESET PASSWORD</h2>
            
            <div class="text-center text-gray-600">
                {{ __('Forgot your password?') }}
                
            </div>
            <div class="mb-6 text-center text-gray-600">
                {{ __('Enter your email and we\'ll send you a reset link.') }}
            </div>

            @session('status')
                <div class="mb-6 font-medium text-sm text-green-600 text-center">
                    {{ $value }}
                </div>
            @endsession

            <x-validation-errors class="mb-6" />

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="sr-only">Email</label>
                    <input id="email" type="email" name="email" 
                           placeholder="Your Email Address"
                           class="w-full px-4 py-3 rounded-lg border-gray-300 focus:border-gray-500 focus:ring focus:ring-gray-200"
                           :value="old('email')" required autofocus autocomplete="email">
                </div>

                <div class="text-center pt-4">
                    <button type="submit" class="w-full py-3 bg-gray-800 hover:bg-gray-700 text-white font-bold rounded-lg transition duration-200">
                        {{ __('Send Reset Link') }}
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-500">
                    Remembered your password?
                    <a href="{{ route('login') }}" class="text-gray-800 hover:underline">
                        Sign In
                    </a>
                </p>
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