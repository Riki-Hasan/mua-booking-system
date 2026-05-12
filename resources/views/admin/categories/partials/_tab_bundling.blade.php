<div class="bg-white p-6 md:p-8 rounded-[2.5rem] shadow-sm border border-gray-100 mb-8">
    <h3 class="text-lg font-black mb-6 text-gray-800 tracking-tighter uppercase italic">Tambah Promo Bundling</h3>
    <form action="{{ route('admin.bundlings.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @csrf
        <input type="hidden" name="current_tab" :value="tab">
        <div>
            <label class="block text-[9px] font-black text-gray-400 uppercase mb-2 ml-1">Nama Paket (Subject)</label>
            <input type="text" name="subject" placeholder="Engagement + Ortu" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-pink-500 outline-none" required>
        </div>
        <div>
            <label class="block text-[9px] font-black text-gray-400 uppercase mb-2 ml-1">Harga (IDR)</label>
            <input type="number" name="price" placeholder="350000" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 text-sm font-bold outline-none" required>
        </div>
        <div>
            <label class="block text-[9px] font-black text-gray-400 uppercase mb-2 ml-1">Foto Utama (Landscape)</label>
            <input type="file" name="main_image" class="w-full text-[10px] font-bold text-gray-400 file:bg-pink-50 file:text-pink-600 file:border-0 file:rounded-xl file:px-4 file:py-2" required>
        </div>
        <div>
            <label class="block text-[9px] font-black text-gray-400 uppercase mb-2 ml-1">Foto Kecil (Lingkaran)</label>
            <input type="file" name="secondary_image" class="w-full text-[10px] font-bold text-gray-400 file:bg-pink-50 file:text-pink-600 file:border-0 file:rounded-xl file:px-4 file:py-2" required>
        </div>
        <div class="md:col-span-2">
            <label class="block text-[9px] font-black text-gray-400 uppercase mb-2 ml-1">Short Description</label>
            <input type="text" name="short_description" placeholder="Free transport Tegal Kota + Softlens" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 text-sm font-bold outline-none" required>
        </div>
        <div class="md:col-span-2">
            <label class="block text-[9px] font-black text-gray-400 uppercase mb-2 ml-1">Deskripsi Lengkap</label>
            <textarea name="description" placeholder="deskripsi lengkap paket" rows="3" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 text-sm font-bold outline-none" required></textarea>
        </div>
        <div class="grid grid-cols-2 gap-4 md:col-span-2">
            <div>
                <label class="block text-[9px] font-black text-gray-400 uppercase mb-2 ml-1">Durasi (Menit)</label>
                <input type="number" name="duration_minutes" placeholder="90" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 text-sm font-bold outline-none" required>
            </div>
            <div>
                <label class="block text-[9px] font-black text-gray-400 uppercase mb-2 ml-1">Kapasitas Orang</label>
                <select name="target_person_count" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 text-sm font-bold outline-none">
                    <option value="1">1 Orang</option>
                    <option value="2">2 Orang (Pasangan)</option>
                </select>
            </div>
        </div>
        <div class="md:col-span-2">
            <button type="submit" class="w-full bg-gray-900 text-white font-black py-4 rounded-2xl hover:bg-pink-600 transition-all shadow-lg text-[10px] uppercase tracking-widest">
                Terbitkan Promo Bundling
            </button>
        </div>
    </form>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($bundlings as $b)
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-rose-50 overflow-hidden flex flex-col">
        <div class="p-6 bg-gray-50/50 flex justify-center gap-4 border-b border-gray-100">
            <div class="text-center">
                <p class="text-[8px] font-black uppercase text-gray-400 mb-2">Foto Utama</p>
                <div class="w-20 h-24 bg-white rounded-xl overflow-hidden border border-gray-100 shadow-sm">
                    <img src="{{ asset('storage/'.$b->main_image) }}" class="w-full h-full object-cover">
                </div>
            </div>
            <div class="text-center">
                <p class="text-[8px] font-black uppercase text-gray-400 mb-2">Foto Kecil</p>
                <div class="w-20 h-20 bg-white rounded-full overflow-hidden border-4 border-white shadow-md">
                    <img src="{{ asset('storage/'.$b->secondary_image) }}" class="w-full h-full object-cover">
                </div>
            </div>
        </div>

        <div class="p-6 flex-1 flex flex-col justify-between">
            <table class="w-full text-[10px] font-bold text-gray-600 mb-6">
                <tr class="border-b border-gray-50">
                    <td class="py-2 text-gray-400 uppercase tracking-tighter w-1/3">Nama Paket</td>
                    <td class="py-2 text-gray-900 uppercase italic">{{ $b->subject }}</td>
                </tr>
                <tr class="border-b border-gray-50">
                    <td class="py-2 text-gray-400 uppercase tracking-tighter">Harga</td>
                    <td class="py-2 text-pink-600 font-black italic">Rp{{ number_format($b->price/1000, 0) }}k</td>
                </tr>
                <tr>
                    <td class="py-1 text-gray-400 uppercase tracking-tighter w-24 align-top">Deskripsi</td>
                    <td class="py-1 text-gray-500 leading-tight font-medium text-[9px] italic">
                        {{ Str::limit($b->description, 80) }}
                    </td>
                </tr>
            </table>

            <div class="flex gap-2">
                <button onclick="openEditBundlingModal({{ json_encode($b) }})" class="flex-1 bg-blue-50 text-blue-500 py-3 rounded-xl font-black text-[9px] uppercase tracking-widest hover:bg-blue-500 hover:text-white transition-all">
                    <i class="fa-solid fa-pen-to-square mr-1"></i> Edit
                </button>
                <button type="button" onclick="confirmDelete('{{ $b->id }}', '{{ $b->subject }}', 'bundling')" class="flex-1 bg-rose-50 text-rose-500 py-3 rounded-xl font-black text-[9px] uppercase tracking-widest hover:bg-rose-500 hover:text-white transition-all">
                    <i class="fa-solid fa-trash mr-1"></i> Hapus
                </button>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full py-20 text-center bg-white rounded-[2.5rem] border border-dashed border-gray-200">
        <p class="text-gray-300 font-black uppercase italic text-xs tracking-[0.3em]">Belum ada promo bundling aktif</p>
    </div>
    @endforelse
</div>