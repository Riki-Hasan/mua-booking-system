<div class="bg-white p-6 md:p-8 rounded-[2.5rem] shadow-sm border border-gray-100 mb-8">
    <h3 class="text-lg font-black mb-6 text-gray-800 tracking-tighter uppercase italic">Tambah Paket Baru</h3>
    <form action="{{ route('admin.categories.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        @csrf
        <input type="hidden" name="current_tab" :value="tab">
        <div class="md:col-span-1">
            <label class="block text-[9px] font-black text-gray-400 uppercase mb-2 ml-1">Nama Paket</label>
            <input type="text" name="name" placeholder="Misal: Wedding" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-pink-500 outline-none" required>
        </div>
        <div>
            <label class="block text-[9px] font-black text-gray-400 uppercase mb-2 ml-1">Harga (IDR)</label>
            <input type="number" name="base_price" placeholder="500000" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 text-sm font-bold outline-none" required>
        </div>
        <div>
            <label class="block text-[9px] font-black text-gray-400 uppercase mb-2 ml-1">Durasi (Menit)</label>
            <input type="number" name="duration_minutes" placeholder="90" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 text-sm font-bold outline-none" required>
        </div>
        <button type="submit" class="bg-pink-600 text-white font-black py-4 rounded-2xl hover:bg-pink-600 transition-all shadow-lg  uppercase active:scale-95  text-xs  tracking-wider shadow-lg shadow-pink-100">
            Simpan Paket
        </button>
    </form>
</div>

<div class="bg-white rounded-[2rem] md:rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50/50 text-[9px] uppercase text-gray-400 font-black tracking-widest">
                <tr>
                    <th class="px-6 py-6 w-16"></th>
                    <th class="px-6 py-6">Detail Paket</th>
                    <th class="px-6 py-6 text-center">Harga</th>
                    <th class="px-6 py-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="sortable-table" class="divide-y divide-gray-50">
                @foreach($categories->sortBy(fn($cat) => $cat->sort_order ?? 999) as $category)
                <tr data-id="{{ $category->id }}" class="hover:bg-pink-50/20 transition-colors font-bold text-sm">
                    <td class="px-4 py-6 text-center">
                        <div class="handle cursor-grab text-gray-300 hover:text-pink-500 transition-colors">
                            <i class="fa-solid fa-grip-vertical text-lg"></i>
                        </div>
                    </td>
                    <td class="px-6 py-6">
                        <div class="text-gray-900 uppercase italic leading-none">{{ $category->name }}</div>
                        <div class="text-[9px] text-gray-400 uppercase mt-1.5">{{ $category->duration_minutes }} Menit Rias</div>
                    </td>
                    <td class="px-6 py-6 text-pink-600 text-center font-black">
                        Rp{{ number_format($category->base_price, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-6 text-center">
                        <div class="flex justify-center gap-4">
                            <button onclick="openEditModal({{ json_encode($category) }})" class="text-blue-400 hover:scale-125 transition-all">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button onclick="confirmDelete('{{ $category->id }}', '{{ $category->name }}', 'category')" class="text-rose-400 hover:scale-125 transition-all">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-6 bg-gray-50 border-t border-gray-100 flex justify-end">
            <button onclick="saveNewOrder()" class="bg-gray-900 text-white px-8 py-3 rounded-xl font-black uppercase text-[10px] tracking-widest hover:bg-pink-600 transition-all shadow-lg active:scale-95">
                Konfirmasi Urutan Paket
            </button>
        </div>
    </div>
</div>