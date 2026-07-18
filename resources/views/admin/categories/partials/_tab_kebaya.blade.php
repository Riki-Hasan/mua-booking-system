<!-- REVISI 1: Menghapus x-show, x-transition, dan x-cloak Alpine agar tidak nge-blank di tablet -->
<div>
    <div class="bg-white p-6 md:p-8 rounded-[2.5rem] shadow-sm border border-gray-100 mb-8">
        <h3 class="text-lg font-black mb-6 text-gray-800 tracking-tighter uppercase italic">Tambah Koleksi Kebaya</h3>
        <form action="{{ route('admin.kebayas.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
            @csrf
            <input type="hidden" name="current_tab" :value="tab">
            
            <!-- Font Label & Input Diperbesar Kontras Tinggi -->
            <div>
                <label class="block text-xs font-black text-gray-700 uppercase mb-2 ml-1">Nama Kebaya</label>
                <input type="text" name="name" placeholder="Misal: Kebaya Klasik Putih" class="w-full border-gray-300 bg-white rounded-2xl p-4 text-sm font-bold text-gray-800 outline-none focus:ring-2 focus:ring-pink-500" required>
            </div>
            
            <!-- Tombol Input File Standar/Biasa Bawaan Tailwind -->
            <div>
                <label class="block text-xs font-black text-gray-700 uppercase mb-2 ml-1">Foto Kebaya (Maks 2MB)</label>
                <input type="file" name="image" class="w-full text-xs font-bold text-gray-700 border border-gray-300 rounded-2xl p-3 bg-white file:bg-pink-50 file:text-pink-600 file:border-0 file:rounded-xl file:px-4 file:py-2 file:font-black file:uppercase file:text-[10px] file:mr-2 file:shadow-sm" required>
            </div>
            
            <!-- Tombol Upload Diganti Jadi Pink Glamour, Teks Diperbesar & Bold -->
            <button type="submit" class="bg-pink-600 text-white font-black py-4 rounded-2xl hover:bg-pink-700 active:scale-95 transition-all text-xs uppercase tracking-wider shadow-lg shadow-pink-100">
                ✨ Upload Kebaya
            </button>
        </form>
    </div>

    <!-- REVISI 2: Grid Diubah Menjadi 2 Kolom Sebaris Saja Agar Serasi -->
    <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-2 gap-4 md:gap-6">
        @foreach($kebayas as $item)
        @php
            // Mengamankan penulisan nama kebaya dari error karakter petik di javascript native
            $safeKebayaName = e($item->name);
        @endphp
        <div class="bg-white rounded-[1.5rem] md:rounded-[2.5rem] overflow-hidden shadow-sm border border-gray-100 group relative">
            
            <!-- REVISI 3: Menggunakan Padding-Bottom Hack untuk mengunci rasio persegi 1:1 (Anti-Hilang di Tablet) -->
            <div class="w-full relative bg-slate-100 overflow-hidden" style="padding-bottom: 100% !important; height: 0 !important; position: relative !important;">
                
                <!-- Gambar object-cover menyesuaikan kontainer card secara otomatis -->
                <img src="{{ asset('storage/' . $item->image_path) }}" 
                     class="transition-transform duration-500"
                     style="position: absolute !important; top: 0 !important; left: 0 !important; width: 100% !important; height: 100% !important; object-fit: cover !important;">
                
                <!-- Mengunci Posisi Tombol Aksi, Perbesar Lingkaran Icon Edit & Hapus -->
                <div class="absolute top-3 right-3 opacity-100 transition-opacity" style="z-index: 10;">
                    <button type="button" onclick="openEditKebayaModal('{{ $item->id }}', '{{ $safeKebayaName }}')" class="w-10 h-10 bg-white text-blue-600 rounded-full shadow-md flex items-center justify-center mb-2 active:scale-95 transition-transform">
                        <i class="fa-solid fa-pen text-sm"></i>
                    </button>    
                    <button type="button" onclick="confirmDelete('{{ $item->id }}', '{{ $safeKebayaName }}', 'kebaya')" class="w-10 h-10 bg-white text-rose-500 rounded-full shadow-md flex items-center justify-center active:scale-95 transition-transform">
                        <i class="fa-solid fa-trash-can text-sm"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-4 text-center md:text-left">
                <h4 class="font-black text-gray-800 uppercase italic text-xs truncate leading-none">{{ $item->name }}</h4>
            </div>
        </div>
        @endforeach
    </div>
</div>