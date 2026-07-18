<div class="bg-white p-6 md:p-8 rounded-[2.5rem] shadow-sm border border-gray-100 mb-8">
    <h3 class="text-lg font-black mb-6 text-gray-800 tracking-tighter uppercase italic">Tambah Promo Bundling</h3>
    <form action="{{ route('admin.bundlings.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @csrf
        <input type="hidden" name="current_tab" :value="tab">
        <div>
            <label class="block text-xs font-black text-gray-700 uppercase mb-2 ml-1">Nama Paket (Subject)</label>
            <input type="text" name="subject" placeholder="Engagement + Ortu" class="w-full border-gray-300 bg-white rounded-2xl p-4 text-sm font-bold text-gray-800 focus:ring-2 focus:ring-pink-500 outline-none" required>
        </div>
        <div>
            <label class="block text-xs font-black text-gray-700 uppercase mb-2 ml-1">Harga (IDR)</label>
            <input type="number" name="price" placeholder="350000" class="w-full border-gray-300 bg-white rounded-2xl p-4 text-sm font-bold text-gray-800 outline-none" required>
        </div>

        <!-- 🚨 PERBAIKAN 3: MEMAKSA INPUT FOTO MENYAMPING SECARA HORIZONTAL (GRID 2 KOLOM) -->
        <div class="grid grid-cols-2 gap-4 md:col-span-2">
            <div>
                <label class="block text-xs font-black text-gray-700 uppercase mb-2 ml-1">Foto Utama (Landscape)</label>
                <input type="file" name="main_image" class="w-full text-xs font-bold text-gray-700 border border-gray-300 rounded-2xl p-3 bg-white file:bg-pink-50 file:text-pink-600 file:border-0 file:rounded-xl file:px-4 file:py-2 file:font-black file:uppercase file:text-[10px] file:mr-2 file:shadow-sm" required>
            </div>
            <div>
                <label class="block text-xs font-black text-gray-700 uppercase mb-2 ml-1">Foto Kecil (Lingkaran)</label>
                <input type="file" name="secondary_image" class="w-full text-xs font-bold text-gray-700 border border-gray-300 rounded-2xl p-3 bg-white file:bg-pink-50 file:text-pink-600 file:border-0 file:rounded-xl file:px-4 file:py-2 file:font-black file:uppercase file:text-[10px] file:mr-2 file:shadow-sm" required>
            </div>
        </div>

        <div class="md:col-span-2">
            <label class="block text-xs font-black text-gray-700 uppercase mb-2 ml-1">Short Description</label>
            <input type="text" name="short_description" placeholder="Free transport Tegal Kota + Softlens" class="w-full border-gray-300 bg-white rounded-2xl p-4 text-sm font-bold text-gray-800 outline-none" required>
        </div>
        <div class="md:col-span-2">
            <label class="block text-xs font-black text-gray-700 uppercase mb-2 ml-1">Deskripsi Lengkap</label>
            <textarea name="description" placeholder="deskripsi lengkap paket" rows="3" class="w-full border-gray-300 bg-white rounded-2xl p-4 text-sm font-bold text-gray-800 outline-none" required></textarea>
        </div>
        <div class="grid grid-cols-2 gap-4 md:col-span-2">
            <div>
                <label class="block text-xs font-black text-gray-700 uppercase mb-2 ml-1">Durasi (Menit)</label>
                <input type="number" name="duration_minutes" placeholder="90" class="w-full border-gray-300 bg-white rounded-2xl p-4 text-sm font-bold text-gray-800 outline-none" required>
            </div>
            <div>
                <label class="block text-xs font-black text-gray-700 uppercase mb-2 ml-1">Kapasitas Orang</label>
                <select name="target_person_count" class="w-full border-gray-300 bg-white rounded-2xl p-4 text-sm font-bold text-gray-800 outline-none">
                    <option value="1">1 Orang</option>
                    <option value="2">2 Orang (Pasangan)</option>
                </select>
            </div>
        </div>
        <div class="md:col-span-2">
            <button type="submit" class="w-full bg-pink-600 text-white font-black py-4 rounded-2xl hover:bg-pink-700 transition-all shadow-lg text-xs uppercase tracking-widest">
                ✨ Terbitkan Promo Bundling
            </button>
        </div>
    </form>
</div>

<!-- GRID CARD DUA KOLOM -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
    @forelse($bundlings as $b)
    <!-- Ditambahkan gap-8 di bawah agar Kolom 1 dan Kolom 2 memiliki celah pemisah yang tegas -->
    <div class="bg-white rounded-[2.5rem] shadow-sm border-2 border-pink-100 overflow-hidden flex flex-row p-6 gap-8">
        
        <!-- KOLOM 1: AREA FOTO UTAMA DAN FOTO KECIL -->
        <div class="flex flex-col items-center" style="width: 140px !important; flex-shrink: 0 !important;">
            <div class="text-center w-full mb-3">
                <p class="text-[10px] font-black uppercase text-gray-800 mb-1 tracking-tight">Foto Utama</p>
                <div class="w-full h-32 bg-slate-100 rounded-xl overflow-hidden border-2 border-gray-200 shadow-sm">
                    <img src="{{ asset('storage/'.$b->main_image) }}" class="w-full h-full object-cover">
                </div>
            </div>

            <div class="text-center w-full">
                <p class="text-[10px] font-black uppercase text-gray-800 mb-1 tracking-tight">Foto Kecil</p>
                <div class="bg-slate-100 rounded-full overflow-hidden border-4 border-white shadow-md mx-auto" style="width: 140px !important; height: 140px !important;">
                    <img src="{{ asset('storage/'.$b->secondary_image) }}" class="w-full h-full object-cover">
                </div>
            </div>
        </div>

        <!-- KOLOM 2: DETAIL INFORMASI SAMPING -->
        <!-- 🚨 PERBAIKAN 1: pl-6 memberi jarak internal tambahan agar teks bergeser ke kanan dan tidak menempel gambar -->
        <div class="flex-1 flex flex-col justify-between text-left pl-6">
            <div class="space-y-3">
                <div>
                    <span class="block text-[11px] font-black text-gray-900 uppercase tracking-wider">Nama Paket:</span>
                    <span class="block text-base font-black text-pink-600 uppercase italic leading-tight">{{ $b->subject }}</span>
                </div>

                <div class="flex items-center gap-2 border-y border-dashed border-gray-200 py-2">
                    <span class="text-[11px] font-black text-gray-900 uppercase tracking-wider">Harga:</span>
                    <span class="text-base font-black text-pink-600 italic">Rp{{ number_format($b->price/1000, 0) }}k</span>
                </div>

                <div>
                    <span class="block text-[11px] font-black text-gray-900 uppercase tracking-wider mb-0.5">Deskripsi:</span>
                    <p class="text-sm text-gray-800 font-bold leading-relaxed">
                        {{ $b->description }}
                    </p>
                </div>
            </div>

            <!-- 🚨 PERBAIKAN 2: TOMBOL EDIT & HAPUS BERJEDA (gap-3) DAN PADDING LEBIH RAMPING (py-2) -->
            <div class="flex gap-3 mt-6">
                <button onclick="openEditBundlingModal({{ json_encode($b) }})" class="flex-1 bg-blue-50 text-blue-600 py-2 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-blue-500 hover:text-white transition-all border border-blue-200 flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-pen-to-square text-sm"></i> Edit
                </button>
                <button type="button" onclick="confirmDelete('{{ $b->id }}', '{{ $b->subject }}', 'bundling')" class="flex-1 bg-rose-50 text-rose-600 py-2 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-rose-500 hover:text-white transition-all border border-rose-200 flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-trash text-sm"></i> Hapus
                </button>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full py-20 text-center bg-white rounded-[2.5rem] border border-dashed border-gray-200">
        <p class="text-gray-400 font-black uppercase italic text-sm tracking-[0.2em]">Belum ada promo bundling aktif</p>
    </div>
    @endforelse
</div>