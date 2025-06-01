<x-guest-layout>
    <div class="flex items-center justify-center min-h-screen bg-gray-200">
        <div class="bg-white shadow-lg rounded-3xl w-[90%] sm:w-[65%] max-w-md p-8">
            <h2 class="text-3xl font-bold text-center mb-8 font-rowdies">RESET PASSWORD</h2>

            <x-validation-errors class="mb-6" />

            <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                @csrf

                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div>
                    <label for="email" class="block text-gray-700 mb-2">Email</label>
                    <input id="email" type="email" name="email" 
                           class="w-full px-4 py-3 rounded-lg border-gray-300 focus:border-gray-500 focus:ring focus:ring-gray-200"
                           value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
                </div>

                <div>
                    <label for="password" class="block text-gray-700 mb-2">Password</label>
                    <input id="password" type="password" name="password" 
                           class="w-full px-4 py-3 rounded-lg border-gray-300 focus:border-gray-500 focus:ring focus:ring-gray-200"
                           required autocomplete="new-password">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-gray-700 mb-2">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" 
                           class="w-full px-4 py-3 rounded-lg border-gray-300 focus:border-gray-500 focus:ring focus:ring-gray-200"
                           required autocomplete="new-password">
                </div>

                <div class="text-center pt-4">
                    <button type="submit" class="w-full py-3 bg-gray-800 hover:bg-gray-700 text-white font-bold rounded-lg transition duration-200">
                        {{ __('Reset Password') }}
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-500">
                    Remember your password?
                    <a href="{{ route('login') }}" class="text-gray-800 hover:underline">
                        Login here
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