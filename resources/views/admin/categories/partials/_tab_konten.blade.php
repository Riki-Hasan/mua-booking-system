<div class="bg-white p-6 md:p-8 rounded-[2.5rem] shadow-sm border border-gray-100 mb-8">
    <h3 class="text-lg font-black mb-6 text-gray-800 tracking-tighter uppercase italic">Upload Portfolio Makeup</h3>
    <form action="{{ route('admin.categories.update_image') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
        @csrf
        <input type="hidden" name="current_tab" :value="tab">
        <div>
            <label class="block text-[9px] font-black text-gray-400 uppercase mb-2">Pilih Paket</label>
            <select name="category_id" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 text-sm font-bold outline-none focus:ring-2 focus:ring-pink-500">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-[9px] font-black text-gray-400 uppercase mb-2">File Gambar (Maks 2MB)</label>
            <input type="file" name="image" class="w-full text-[10px] font-bold text-gray-400 file:bg-pink-50 file:text-pink-600 file:border-0 file:rounded-xl file:px-4 file:py-2" required>
        </div>
        <button type="submit" class="bg-gray-900 text-white font-black py-4 rounded-2xl hover:bg-emerald-500 transition-all text-[10px] uppercase">Upload Portfolio</button>
    </form>
</div>

<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
    @foreach($portfolios as $item)
    <div class="bg-white rounded-[1.5rem] md:rounded-[2.5rem] overflow-hidden shadow-sm border border-gray-100 group relative">
        <div class="aspect-square overflow-hidden relative">
            <img src="{{ asset('storage/' . $item->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
            <div class="absolute top-2 right-2 opacity-100 md:opacity-0 group-hover:opacity-100 transition-opacity">
                <button type="button" onclick="openEditPortfolioModal({{ json_encode($item) }})" class="w-8 h-8 bg-white/90 rounded-full text-blue-500 shadow-lg flex items-center justify-center mb-1">
                    <i class="fa-solid fa-pen text-[10px]"></i>
                </button>
                <button type="button" onclick="confirmDelete('{{ $item->id }}', 'Foto Galeri {{ $item->category->name }}', 'portfolio')" class="w-8 h-8 bg-white/90 rounded-full text-rose-500 shadow-lg flex items-center justify-center">
                    <i class="fa-solid fa-trash-can text-[10px]"></i>
                </button>
            </div>
        </div>
        <div class="p-4 text-center md:text-left">
            <h4 class="font-black text-gray-900 uppercase italic text-[10px] md:text-xs truncate leading-none">{{ $item->category->name }}</h4>
        </div>
    </div>
    @endforeach
</div>