<x-guest-layout>
    <div class="flex items-center justify-center min-h-screen bg-gray-200">
        <div class="bg-white shadow-lg rounded-3xl w-[90%] sm:w-[65%] max-w-md p-8">
            <h2 class="text-3xl font-bold text-center mb-8 font-rowdies">TWO-FACTOR AUTHENTICATION</h2>
            
            <div x-data="{ recovery: false }">
                <div class="mb-6 text-center text-gray-600" x-show="! recovery">
                    {{ __('Please enter the authentication code from your authenticator app.') }}
                </div>

                <div class="mb-6 text-center text-gray-600" x-cloak x-show="recovery">
                    {{ __('Please enter one of your emergency recovery codes.') }}
                </div>

                <x-validation-errors class="mb-6" />

                <form method="POST" action="{{ route('two-factor.login') }}" class="space-y-6">
                    @csrf

                    <div x-show="! recovery">
                        <label for="code" class="sr-only">Authentication Code</label>
                        <input id="code" type="text" inputmode="numeric" name="code" 
                               placeholder="Authentication Code"
                               class="w-full px-4 py-3 rounded-lg border-gray-300 focus:border-gray-500 focus:ring focus:ring-gray-200"
                               autofocus x-ref="code" autocomplete="one-time-code">
                    </div>

                    <div x-cloak x-show="recovery">
                        <label for="recovery_code" class="sr-only">Recovery Code</label>
                        <input id="recovery_code" type="text" name="recovery_code" 
                               placeholder="Recovery Code"
                               class="w-full px-4 py-3 rounded-lg border-gray-300 focus:border-gray-500 focus:ring focus:ring-gray-200"
                               x-ref="recovery_code" autocomplete="one-time-code">
                    </div>

                    <div class="flex items-center justify-between mt-4">
                        <button type="button" class="text-sm text-gray-600 hover:text-gray-900 underline cursor-pointer"
                                        x-show="! recovery"
                                        x-on:click="
                                            recovery = true;
                                            $nextTick(() => { $refs.recovery_code.focus() })
                                        ">
                            {{ __('Use recovery code') }}
                        </button>

                        <button type="button" class="text-sm text-gray-600 hover:text-gray-900 underline cursor-pointer"
                                        x-cloak
                                        x-show="recovery"
                                        x-on:click="
                                            recovery = false;
                                            $nextTick(() => { $refs.code.focus() })
                                        ">
                            {{ __('Use authenticator code') }}
                        </button>
                    </div>

                    <div class="text-center pt-4">
                        <button type="submit" class="w-full py-3 bg-gray-800 hover:bg-gray-700 text-white font-bold rounded-lg transition duration-200">
                            {{ __('Verify') }}
                        </button>
                    </div>
                </form>
            </div>

            <div class="mt-6 text-center">
                <p class="text-gray-500">
                    Having trouble?
                    <a href="{{ route('login') }}" class="text-gray-800 hover:underline">
                        Return to login
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