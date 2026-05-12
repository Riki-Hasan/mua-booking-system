<div class="flex justify-center gap-2 mb-8">
    <button @click="tab = 'paket'" 
            :class="tab === 'paket' ? 'bg-pink-600 text-white shadow-lg border-pink-600' : 'bg-white text-gray-400 border-gray-100'"
            class="flex-1 max-w-[180px] px-4 py-4 rounded-2xl font-black uppercase text-[10px] transition-all border-2">
        <i class="fa-solid fa-box-archive mr-1"></i> Paket
    </button>
    <button @click="tab = 'konten'" 
            :class="tab === 'konten' ? 'bg-pink-600 text-white shadow-lg border-pink-600' : 'bg-white text-gray-400 border-gray-100'"
            class="flex-1 max-w-[180px] px-4 py-4 rounded-2xl font-black uppercase text-[10px] transition-all border-2">
        <i class="fa-solid fa-images mr-1"></i> Konten
    </button>
    <button @click="tab = 'kebaya'" 
            :class="tab === 'kebaya' ? 'bg-pink-600 text-white shadow-lg border-pink-600' : 'bg-white text-gray-400 border-gray-100'"
            class="flex-1 max-w-[180px] px-4 py-4 rounded-2xl font-black uppercase text-[10px] transition-all border-2">
        <i class="fa-solid fa-shirt mr-1"></i> Kebaya
    </button>
    <button @click="tab = 'bundling'" 
            :class="tab === 'bundling' ? 'bg-pink-600 text-white shadow-lg border-pink-600' : 'bg-white text-gray-400 border-gray-100'"
            class="flex-1 max-w-[180px] px-4 py-4 rounded-2xl font-black uppercase text-[10px] transition-all border-2">
        <i class="fa-solid fa-tags mr-1"></i> Bundling
    </button>
</div>