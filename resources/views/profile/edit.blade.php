<x-app-layout>

            <x-slot name="header">
                <div class="flex justify-between items-center">
                    <h2 class="font-bold text-2xl text-pink-600 leading-tight tracking-tighter">
                        {{ __('Profile') }}
                    </h2>
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 bg-white text-gray-500 px-4 py-3 rounded-xl shadow-sm border border-gray-100 hover:text-pink-600 transition-all active:scale-95">
                        <i class="fa-solid fa-arrow-left mr-1"></i> Back
                    </a>
                </div>
            </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
