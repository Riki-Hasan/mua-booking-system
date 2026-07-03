<x-app-layout>
    <style>
        [x-cloak] { display: none !important; }
        .animate-pop { animation: pop 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        @keyframes pop { 0% { transform: scale(0.9); opacity: 0; } 100% { transform: scale(1); opacity: 1; } }
    </style>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-pink-700 leading-tight tracking-tighter">
                {{ __('Manajemen Wilayah & Ongkir') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 bg-white text-gray-700 px-4 py-3 rounded-xl shadow-sm border border-gray-200 hover:text-pink-600 transition-all active:scale-95 font-bold text-sm">
                <i class="fa-solid fa-arrow-left mr-1"></i> Back
            </a>
        </div>
    </x-slot>

    {{-- State Management dengan Alpine.js --}}
    <div class="py-12 px-4 sm:px-6 lg:px-8" x-data="{ 
        openDeleteModal: false, 
        deleteUrl: '', 
        locationName: '',
        openStatusModal: {{ session('success') ? 'true' : 'false' }} 
    }">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Form Tambah Wilayah --}}
            <div class="bg-white p-6 sm:p-8 rounded-[2rem] shadow-md border border-pink-100 h-fit">
                <h3 class="font-bold text-gray-900 mb-6 uppercase tracking-wider text-sm border-b border-gray-100 pb-3">Tambah Wilayah</h3>
                <form action="{{ route('admin.locations.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2 ml-1">Nama Wilayah</label>
                            <input type="text" name="region_name" placeholder="Contoh: Adiwerna" class="w-full border border-gray-300 bg-gray-50 rounded-2xl p-3.5 outline-none focus:ring-2 focus:ring-pink-500 text-sm font-semibold text-gray-900" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2 ml-1">Biaya Tambahan (Rp)</label>
                            <input type="number" name="additional_price" placeholder="Contoh: 15000" class="w-full border border-gray-300 bg-gray-50 rounded-2xl p-3.5 outline-none focus:ring-2 focus:ring-pink-500 text-sm font-semibold text-gray-900" required>
                        </div>
                        <button type="submit" class="w-full bg-gray-900 text-white font-bold py-4 rounded-2xl hover:bg-pink-700 transition-all shadow-md active:scale-95 text-xs uppercase tracking-wider">
                            Simpan Wilayah
                        </button>
                    </div>
                </form>
            </div>

            {{-- Tabel Wilayah (Dengan Jarak Aman di Layar HP / Mobile View) --}}
            <div class="lg:col-span-2 bg-white rounded-[2rem] shadow-md border border-gray-200 overflow-hidden">
                <div class="p-5 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-bold text-gray-900 uppercase tracking-wider text-sm">Daftar Wilayah & Ongkir</h3>
                </div>
                
                <!-- Wrapper responsive dengan padding tambahan (px-4) agar tabel tidak nempel tepi layar HP -->
                <div class="overflow-x-auto px-4 py-2 sm:px-0 sm:py-0">
                    <table class="w-full text-left min-w-[500px] sm:min-w-full">
                        <thead class="bg-white border-b border-gray-200 text-xs font-bold uppercase text-gray-700 tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Nama Wilayah</th>
                                <th class="px-6 py-4">Ongkir Tambahan</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($locations as $location)
                            <tr class="hover:bg-pink-50/20 transition-colors text-sm font-semibold text-gray-900">
                                <td class="px-6 py-4 font-bold text-gray-900">{{ $location->region_name }}</td>
                                <td class="px-6 py-4 text-pink-700 font-extrabold">Rp{{ number_format($location->additional_price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center items-center gap-4">
                                        <button onclick="openEditLocationModal({{ json_encode($location) }})" class="text-blue-600 hover:scale-110 transition-all p-1" title="Edit">
                                            <i class="fa-solid fa-pen-to-square text-lg"></i>
                                        </button>

                                        <button @click="openDeleteModal = true; deleteUrl = '{{ route('admin.locations.destroy', $location->id) }}'; locationName = '{{ $location->region_name }}'" 
                                                class="text-rose-600 hover:scale-110 transition-all p-1" title="Hapus">
                                            <i class="fa-solid fa-trash-can text-lg"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-sm text-gray-500 font-medium">Belum ada data wilayah.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- MODAL KONFIRMASI HAPUS --}}
        <div x-show="openDeleteModal" 
             x-cloak
             class="fixed inset-0 z-[200] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            <div class="bg-white p-6 rounded-3xl shadow-2xl max-w-sm w-full text-center border-2 border-rose-100 animate-pop">
                <div class="w-16 h-16 bg-rose-50 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 uppercase mb-2">Hapus Wilayah?</h3>
                <p class="text-gray-600 text-sm font-semibold mb-6 leading-relaxed">
                    Yakin ingin menghapus biaya ongkir untuk <span class="text-rose-600 font-bold" x-text="locationName"></span>?
                </p>
                
                <div class="flex gap-3">
                    <button @click="openDeleteModal = false" class="flex-1 py-3 bg-gray-100 text-gray-500 rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-gray-200 transition-all">Batal</button>
                    <form :action="deleteUrl" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-3 bg-rose-600 text-white rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-rose-700 shadow-md transition-all">Ya, Hapus!</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL STATUS SUKSES --}}
        <div x-show="openStatusModal" 
             x-cloak
             class="fixed inset-0 z-[210] flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl text-center border-2 border-emerald-100 animate-pop">
                <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-check-double text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 uppercase mb-2">Berhasil!</h3>
                <p class="text-sm text-gray-600 font-semibold mb-6 leading-relaxed">
                    {{ session('success') }}
                </p>
                <button @click="openStatusModal = false" 
                        class="w-full bg-slate-900 text-white font-bold py-3.5 rounded-xl uppercase text-xs tracking-wider hover:bg-pink-700 transition-all shadow-md">
                    Oke, Paham
                </button>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT WILAYAH --}}
    <div id="modalEditLocation" class="fixed inset-0 z-[110] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border-2 border-blue-100 animate-pop relative">
            <h3 class="text-xl font-bold text-gray-900 uppercase mb-5 border-b border-gray-100 pb-2">Edit Wilayah</h3>
            
            <form id="editLocationForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2 ml-1">Nama Wilayah</label>
                        <input type="text" name="region_name" id="edit_region_name" class="w-full border border-gray-300 bg-gray-50 rounded-2xl p-3.5 outline-none focus:ring-2 focus:ring-blue-400 text-sm font-semibold text-gray-900" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2 ml-1">Biaya Tambahan (Rp)</label>
                        <input type="number" name="additional_price" id="edit_additional_price" class="w-full border border-gray-300 bg-gray-50 rounded-2xl p-3.5 outline-none focus:ring-2 focus:ring-blue-400 text-sm font-semibold text-gray-900" required>
                    </div>
                    
                    <div class="flex gap-3 pt-4">
                        <button type="button" onclick="closeEditLocationModal()" class="flex-1 bg-gray-100 text-gray-500 font-bold py-3.5 rounded-xl uppercase text-xs tracking-wider hover:bg-gray-200">Batal</button>
                        <button type="submit" class="flex-1 bg-blue-600 text-white font-bold py-3.5 rounded-xl shadow-md uppercase text-xs tracking-wider hover:bg-blue-700">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditLocationModal(location) {
            document.getElementById('edit_region_name').value = location.region_name;
            document.getElementById('edit_additional_price').value = location.additional_price;
            document.getElementById('editLocationForm').action = `/admin/locations/${location.id}`;
            document.getElementById('modalEditLocation').classList.replace('hidden', 'flex');
        }

        function closeEditLocationModal() {
            document.getElementById('modalEditLocation').classList.replace('flex', 'hidden');
        }
    </script>
</x-app-layout>