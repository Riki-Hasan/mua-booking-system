<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center font-sans">
            <h2 class="font-bold text-2xl text-pink-700 leading-tight tracking-tighter">
                {{ __('Manajemen Jadwal & Booking Offline') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 bg-white text-gray-700 px-4 py-3 rounded-xl shadow-sm border border-gray-200 hover:text-pink-600 transition-all active:scale-95 font-bold text-sm">
                <i class="fa-solid fa-arrow-left mr-1"></i> Back
            </a>
        </div>
    </x-slot>

    {{-- Hubungkan Berkas Styles --}}
    @include('admin.schedules.partials.styles')

    <div class="py-12 bg-[#FDF8F8] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('admin.schedules.manual') }}" method="POST" id="bookingForm" class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">
                @csrf
                <input type="hidden" name="booking_date" id="final_date">
                <input type="hidden" name="start_time" id="final_time">

                {{-- Hubungkan Berkas Form Pelanggan --}}
                @include('admin.schedules.partials.form-pelanggan')

                {{-- Hubungkan Berkas Kalender & Jam --}}
                @include('admin.schedules.partials.kalender-jam')
            </form>

            {{-- Hubungkan Berkas Agenda Terbaru --}}
            @include('admin.schedules.partials.agenda-terbaru')
        </div>
    </div>

    {{-- Hubungkan Berkas Modals Status & Konfirmasi --}}
    @include('admin.schedules.partials.modals')

    {{-- Hubungkan Berkas Script Driver Utama --}}
    @include('admin.schedules.partials.script-schedules')
</x-app-layout>