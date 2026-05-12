<x-app-layout>
    <style>
        [x-cloak] { display: none !important; }
        .animate-pop { animation: pop 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        @keyframes pop { 0% { transform: scale(0.9); opacity: 0; } 100% { transform: scale(1); opacity: 1; } }
    </style>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-pink-600 leading-tight tracking-tighter italic">
                {{ __('Manajemen Wilayah & Ongkir') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 bg-white text-gray-500 px-4 py-3 rounded-xl shadow-sm border border-gray-100 hover:text-pink-600 transition-all active:scale-95">
                <i class="fa-solid fa-arrow-left mr-1"></i> Back
            </a>
        </div>
    </x-slot>

    {{-- State Management dengan Alpine.js --}}
    <div class="py-12 px-6" x-data="{ 
        openDeleteModal: false, 
        deleteUrl: '', 
        locationName: '',
        openStatusModal: {{ session('success') ? 'true' : 'false' }} 
    }">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Form Tambah --}}
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-pink-50">
                <h3 class="font-black text-gray-800 mb-6 uppercase tracking-widest text-sm italic">Tambah Wilayah</h3>
                <form action="{{ route('admin.locations.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 ml-1">Nama Wilayah</label>
                            <input type="text" name="region_name" placeholder="Contoh: Adiwerna" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 outline-none focus:ring-2 focus:ring-pink-500 text-sm font-bold" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 ml-1">Biaya Tambahan (Rp)</label>
                            <input type="number" name="additional_price" placeholder="Contoh: 15000" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 outline-none focus:ring-2 focus:ring-pink-500 text-sm font-bold" required>
                        </div>
                        <button type="submit" class="w-full bg-gray-900 text-white font-black py-4 rounded-2xl hover:bg-pink-600 transition-all shadow-lg active:scale-95 text-[10px] uppercase tracking-widest">
                            SIMPAN WILAYAH
                        </button>
                    </div>
                </form>
            </div>

            {{-- Tabel Wilayah --}}
            <div class="lg:col-span-2 bg-white rounded-[2.5rem] shadow-sm border border-gray-50 overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-[10px] font-black uppercase text-gray-400 tracking-widest">
                        <tr>
                            <th class="px-8 py-6">Nama Wilayah</th>
                            <th class="px-8 py-6">Ongkir Tambahan</th>
                            <th class="px-8 py-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($locations as $location)
                        <tr class="hover:bg-pink-50/30 transition-colors">
                            <td class="px-8 py-6 font-bold text-gray-800">{{ $location->region_name }}</td>
                            <td class="px-8 py-6 font-medium text-pink-600 italic font-black">Rp{{ number_format($location->additional_price, 0, ',', '.') }}</td>
                            <td class="px-8 py-6 text-center">
                                <div class="flex justify-center items-center gap-4">
                                    <button onclick="openEditLocationModal({{ json_encode($location) }})" class="text-blue-400 hover:scale-125 transition-all">
                                        <i class="fa-solid fa-pen-to-square text-lg"></i>
                                    </button>

                                    <button @click="openDeleteModal = true; deleteUrl = '{{ route('admin.locations.destroy', $location->id) }}'; locationName = '{{ $location->region_name }}'" 
                                            class="text-rose-400 hover:scale-125 transition-all">
                                        <i class="fa-solid fa-trash-can text-lg"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- MODAL KONFIRMASI HAPUS --}}
        <div x-show="openDeleteModal" 
             x-cloak
             class="fixed inset-0 z-[200] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            <div class="bg-white p-8 rounded-[2.5rem] shadow-2xl max-w-sm w-full mx-4 text-center border-4 border-rose-50 animate-pop">
                <div class="w-16 h-16 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-6 animate-bounce">
                    <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
                </div>
                <h3 class="text-xl font-black text-gray-900 uppercase italic mb-2">Hapus Wilayah?</h3>
                <p class="text-gray-500 text-sm mb-8 leading-relaxed">
                    Yakin ingin menghapus biaya ongkir untuk <span class="text-rose-500 font-bold" x-text="locationName"></span>?
                </p>
                
                <div class="flex gap-3">
                    <button @click="openDeleteModal = false" class="flex-1 py-4 bg-gray-100 text-gray-500 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-gray-200 transition-all">Batal</button>
                    <form :action="deleteUrl" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-4 bg-rose-500 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-rose-600 shadow-lg shadow-rose-200 transition-all">Ya, Hapus!</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL STATUS SUKSES (Tambah, Edit, Hapus) --}}
        <div x-show="openStatusModal" 
             x-cloak
             class="fixed inset-0 z-[210] flex items-center justify-center bg-slate-900/40 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            <div class="bg-white rounded-[2.5rem] max-w-sm w-full p-10 shadow-2xl text-center border-4 border-emerald-50 animate-pop">
                <div class="w-20 h-20 bg-emerald-100 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fa-solid fa-check-double text-3xl"></i>
                </div>
                <h3 class="text-2xl font-black text-gray-900 uppercase italic tracking-tighter mb-2">Berhasil!</h3>
                <p class="text-sm text-gray-500 mb-8 leading-relaxed">
                    {{ session('success') }}
                </p>
                <button @click="openStatusModal = false" 
                        class="w-full bg-slate-900 text-white font-black py-4 rounded-2xl uppercase text-[10px] tracking-[0.2em] hover:bg-pink-600 transition-all shadow-lg">
                    Oke, Siap
                </button>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT WILAYAH --}}
    <div id="modalEditLocation" class="fixed inset-0 z-[110] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-[2.5rem] max-w-sm w-full p-8 shadow-2xl border-4 border-blue-50 animate-pop relative">
            <h3 class="text-xl font-black text-gray-900 uppercase italic tracking-tighter mb-6">Edit Wilayah</h3>
            
            <form id="editLocationForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 ml-1">Nama Wilayah</label>
                        <input type="text" name="region_name" id="edit_region_name" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 outline-none focus:ring-2 focus:ring-blue-400 text-sm font-bold" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 ml-1">Biaya Tambahan (Rp)</label>
                        <input type="number" name="additional_price" id="edit_additional_price" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 outline-none focus:ring-2 focus:ring-blue-400 text-sm font-bold" required>
                    </div>
                    
                    <div class="flex gap-3 pt-4">
                        <button type="button" onclick="closeEditLocationModal()" class="flex-1 bg-gray-100 text-gray-400 font-black py-4 rounded-2xl uppercase text-[10px]">Batal</button>
                        <button type="submit" class="flex-1 bg-blue-500 text-white font-black py-4 rounded-2xl shadow-lg uppercase text-[10px] tracking-widest">Simpan</button>
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