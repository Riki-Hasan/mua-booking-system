<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-pink-600 leading-tight tracking-tighter italic">Manajemen Studio</h2>
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 bg-white text-gray-500 px-4 py-3 rounded-xl shadow-sm border border-gray-100 hover:text-pink-600 transition-all active:scale-95">
                <i class="fa-solid fa-arrow-left mr-1"></i> Back
            </a>
        </div>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    {{-- TAB PERSISTENCE: Membaca session current_tab --}}
    <div class="py-6 md:py-12" x-data="{ tab: '{{ session('current_tab', 'paket') }}' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Bagian Navigasi --}}
            @include('admin.categories.partials._tabs_navigation')

            {{-- Tab Paket --}}
            <div x-show="tab === 'paket'" x-transition>
                {{-- (Pindahkan isi tab paket lama ke file _tab_paket.blade.php jika ingin lebih rapi) --}}
                {{-- Untuk sekarang saya biarkan ringkas di sini agar Mas tidak bingung --}}
                @include('admin.categories.partials._tab_paket')
            </div>

            {{-- Tab Konten Makeup --}}
            <div x-show="tab === 'konten'" x-transition x-cloak>
                @include('admin.categories.partials._tab_konten')
            </div>

            {{-- Tab Kebaya --}}
            @include('admin.categories.partials._tab_kebaya')

            {{-- Tab Bundling --}}
            <div x-show="tab === 'bundling'" x-transition x-cloak>
                @include('admin.categories.partials._tab_bundling')
            </div>

        </div>
    </div>

    {{-- Bagian Modal --}}
    @include('admin.categories.partials._modals')

    {{-- Bagian Script --}}
    @include('admin.categories.partials._scripts')

</x-app-layout>