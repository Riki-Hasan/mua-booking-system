<x-app-layout>
    <style>
        [x-cloak] { display: none !important; }
        .animate-pop { animation: pop 0.2s ease-out; }
        @keyframes pop { 0% { transform: scale(0.95); } 100% { transform: scale(1); } }

        /* ======================================================== */
        /* 🚨 REVISI 1: PENINGKATAN KETERBACAAN FORM PADA TABLET LAMA */
        /* ======================================================== */
        input[type="text"], input[type="number"] {
            background-color: #ffffff !important;
            color: #1f2937 !important; /* text-gray-800 tebal */
            font-size: 14px !important;
            font-weight: 700 !important;
            border: 2px solid #d1d5db !important; /* Border abu-abu tegas */
        }
        label {
            font-size: 12px !important;
            font-weight: 900 !important;
            color: #374151 !important; /* text-gray-700 hitam tebal */
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            margin-bottom: 6px !important;
        }

        /* ======================================================== */
        /* 🚨 REVISI 2: PEMBERSIHAN BACKGROUND TOMBOL AKSI & JARAK    */
        /* ======================================================== */
        /* Memaksa warna latar belakang tombol menjadi putih bersih (menghilangkan kotak abu sistem tablet) */
        .btn-aksi-tabel {
            background-color: #ffffff !important;
            border: 2px solid #f3f4f6 !important; /* Memberi border putih halus agar rapi */
            padding: 10px 14px !important; /* Ukuran touch-target yang ideal */
            border-radius: 12px !important;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05) !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: all 0.2s !important;
        }
        
        /* ======================================================== */
        /* 🚨 REVISI 3: OVERRIDE BASE BG MODAL OVERLAY KHUSUS TABLET  */
        /* ======================================================== */
        #modalEditLocation, #modalDeleteLocationLuar, #modalStatusLocationLuar {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            width: 100% !important;
            height: 100% !important;
            background-color: rgba(15, 23, 42, 0.8) !important; /* RGBA Klasik */
            align-items: center !important;
            justify-content: center !important;
            z-index: 2147483647 !important; /* Kekuatan Z-Index Maksimal */
        }
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

    {{-- Pembungkus Utama (Alpine.js dilepas dari kontrol pembuka modal agar anti-freeze) --}}
    <div class="py-12 px-4 sm:px-6 lg:px-8">
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
                        <button type="submit" class="w-full bg-pink-600 text-white font-bold py-4 rounded-2xl hover:bg-pink-700 transition-all shadow-md active:scale-95 text-xs uppercase tracking-wider">
                            Simpan Wilayah
                        </button>
                    </div>
                </form>
            </div>

            {{-- Tabel Wilayah --}}
            <div class="lg:col-span-2 bg-white rounded-[2rem] shadow-md border border-gray-200 overflow-hidden">
                <div class="p-5 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-bold text-gray-900 uppercase tracking-wider text-sm">Daftar Wilayah & Ongkir</h3>
                </div>
                
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
                            @php
                                $safeLocationName = addslashes($location->region_name);
                            @endphp
                            <tr class="hover:bg-pink-50/20 transition-colors text-sm font-semibold text-gray-900">
                                <td class="px-6 py-4 font-bold text-gray-900">{{ $location->region_name }}</td>
                                <td class="px-6 py-4 text-pink-700 font-extrabold">Rp{{ number_format($location->additional_price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <!-- Menerapkan gap-6 untuk menjauhkan jarak antar tombol aksi sesuai permintaanmu -->
                                    <div class="flex justify-center items-center gap-6">
                                        <!-- Tombol Edit: Latar di-putih-kan murni via class khusus dan dipicu secara native -->
                                        <button type="button" onclick="openEditLocationModal({{ json_encode($location) }})" class="btn-aksi-tabel text-blue-600 hover:scale-110" title="Edit">
                                            <i class="fa-solid fa-pen-to-square text-lg"></i>
                                        </button>

                                        <!-- Tombol Hapus: Latar di-putih-kan murni via class khusus dan dipicu secara native -->
                                        <button type="button" onclick="bukaModalHapusNative('{{ $safeLocationName }}', '{{ route('admin.locations.destroy', $location->id) }}')" 
                                                class="btn-aksi-tabel text-rose-600 hover:scale-110" title="Hapus">
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
    </div>

    <!-- ======================================================== -->
    <!-- 💥 AREA MODAL MUTLAK (OUTSIDE LAYER - ANTI BYPASS CRASH)  -->
    <!-- ======================================================== -->

    {{-- MODAL KONFIRMASI HAPUS NATIVE --}}
    <div id="modalDeleteLocationLuar" 
         class="hidden"
         style="display: none;">
        <div class="bg-white p-6 rounded-3xl shadow-2xl max-w-sm w-full mx-4 text-center border-2 border-rose-100 animate-pop"
             style="display: block !important;">
            <div class="w-16 h-16 bg-rose-50 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 uppercase mb-2">Hapus Wilayah?</h3>
            <p class="text-gray-600 text-sm font-semibold mb-6 leading-relaxed">
                Yakin ingin menghapus biaya ongkir untuk <span id="namaWilayahHapusLuar" class="text-rose-600 font-bold"></span>?
            </p>
            
            <div class="flex gap-3">
                <button type="button" onclick="tutupModalHapusNative()" class="flex-1 py-3 bg-gray-100 text-gray-500 rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-gray-200 transition-all">Batal</button>
                <form id="formHapusLocationLuar" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full py-3 bg-rose-600 text-white rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-rose-700 shadow-md transition-all">Ya, Hapus!</button>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL STATUS SUKSES (MURNI PHP LOGIC LOGGED AFTER SAVE) --}}
    @if(session('success'))
    <div id="modalStatusLocationLuar" 
         style="display: flex;">
        <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl text-center border-2 border-emerald-100 animate-pop"
             style="display: block !important;">
            <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-check-double text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 uppercase mb-2">Berhasil!</h3>
            <p class="text-sm text-gray-600 font-semibold mb-6 leading-relaxed">
                {{ session('success') }}
            </p>
            <button type="button" onclick="tutupModalStatusNative()" 
                    class="w-full bg-slate-900 text-white font-bold py-3.5 rounded-xl uppercase text-xs tracking-wider hover:bg-pink-700 transition-all shadow-md">
                Oke, Paham
            </button>
        </div>
    </div>
    @endif

    {{-- MODAL EDIT WILAYAH NATIVE --}}
    <div id="modalEditLocation" class="hidden" style="display: none;">
        <div class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border-2 border-blue-100 animate-pop relative mx-4"
             style="display: block !important;">
            <h3 class="text-xl font-bold text-gray-900 uppercase mb-5 border-b border-gray-100 pb-2">Edit Wilayah</h3>
            
            <form id="editLocationForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2 ml-1">Nama Wilayah</label>
                        <input type="text" name="region_name" id="edit_region_name" class="w-full border border-gray-300 bg-white rounded-2xl p-3.5 outline-none focus:ring-2 focus:ring-blue-400 text-sm font-semibold text-gray-900" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-2 ml-1">Biaya Tambahan (Rp)</label>
                        <input type="number" name="additional_price" id="edit_additional_price" class="w-full border border-gray-300 bg-white rounded-2xl p-3.5 outline-none focus:ring-2 focus:ring-blue-400 text-sm font-semibold text-gray-900" required>
                    </div>
                    
                    <div class="flex gap-3 pt-4">
                        <button type="button" onclick="closeEditLocationModal()" class="flex-1 bg-gray-100 text-gray-500 font-bold py-3.5 rounded-xl uppercase text-xs tracking-wider hover:bg-gray-200">Batal</button>
                        <button type="submit" class="flex-1 bg-blue-600 text-white font-bold py-3.5 rounded-xl shadow-md uppercase text-xs tracking-wider hover:bg-blue-700">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- JAVASCRIPT NATIVE TOTAL BYPASS SYSTEM                    -->
    <!-- ======================================================== -->
    <script>
        // Fungsi Kontrol Modal Edit Wilayah
        function openEditLocationModal(location) {
            document.getElementById('edit_region_name').value = location.region_name;
            document.getElementById('edit_additional_price').value = location.additional_price;
            document.getElementById('editLocationForm').action = `/admin/locations/${location.id}`;
            
            var modal = document.getElementById('modalEditLocation');
            if(modal) {
                modal.classList.remove('hidden');
                modal.style.setProperty('display', 'flex', 'important');
            }
        }

        function closeEditLocationModal() {
            var modal = document.getElementById('modalEditLocation');
            if(modal) {
                modal.classList.add('hidden');
                modal.style.setProperty('display', 'none', 'important');
            }
        }

        // Fungsi Kontrol Modal Hapus Wilayah
        function bukaModalHapusNative(locationName, deleteUrl) {
            var form = document.getElementById('formHapusLocationLuar');
            var labelNama = document.getElementById('namaWilayahHapusLuar');
            var modal = document.getElementById('modalDeleteLocationLuar');

            if (form) { form.action = deleteUrl; }
            if (labelNama) { labelNama.innerText = locationName; }
            
            if (modal) {
                modal.classList.remove('hidden');
                modal.style.setProperty('display', 'flex', 'important');
            }
        }

        function tutupModalHapusNative() {
            var modal = document.getElementById('modalDeleteLocationLuar');
            if (modal) {
                modal.classList.add('hidden');
                modal.style.setProperty('display', 'none', 'important');
            }
        }

        // Fungsi Kontrol Modal Status Sukses
        function tutupModalStatusNative() {
            var modal = document.getElementById('modalStatusLocationLuar');
            if (modal) {
                modal.style.setProperty('display', 'none', 'important');
            }
        }
    </script>
</x-app-layout>