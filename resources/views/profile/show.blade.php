<x-app-layout>
    <div class="py-6">
        <div class="mx-auto px-6 sm:px-20">
            <!-- Profile Header Section -->
            <div class="relative bg-cover bg-center bg-opacity-75 rounded-lg overflow-hidden mb-6 " >
                <div class="absolute inset-0 bg-opacity-60"></div>
                <div class="relative text-center box-border p-6 sm:p-10">
                    <h2 class="text-5xl font-bold text-black mb-4 font-rowdies">Your Profile</h2>
                    <p class="text-xl text-gray-600 mx-auto sm:w-3/4 leading-relaxed">
                        Manage your account settings and personal information
                    </p>
                </div>
            </div>
            

            <div class="max-w-7xl mx-auto space-y-10">
                @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                    <div class="bg-[#e6e7e7] overflow-hidden shadow-xl sm:rounded-lg p-6">
                        @livewire('profile.update-profile-information-form')
                    </div>
                @endif

                @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                    <div class="bg-[#e6e7e7] overflow-hidden shadow-xl sm:rounded-lg p-6">
                        @livewire('profile.update-password-form')
                    </div>
                @endif

                @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                    <div class="bg-[#e6e7e7] overflow-hidden shadow-xl sm:rounded-lg p-6">
                        @livewire('profile.two-factor-authentication-form')
                    </div>
                @endif

                <div class="bg-[#e6e7e7] overflow-hidden shadow-xl sm:rounded-lg p-6">
                    @livewire('profile.logout-other-browser-sessions-form')
                </div>

                @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                    <div class="bg-[#e6e7e7] overflow-hidden shadow-xl sm:rounded-lg p-6">
                        @livewire('profile.delete-user-form')
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>