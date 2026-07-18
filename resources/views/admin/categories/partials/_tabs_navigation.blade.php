<!-- Mengubah gap-2 menjadi gap-4 agar tombol navigasi tidak saling berdempetan -->
<div class="flex justify-center gap-4 mb-8">
    <!-- Mengubah px-4 py-4 menjadi px-3 py-3 sesuai permintaanmu -->
    <button @click="tab = 'paket'" 
            id="btn-tab-nav-paket"
            class="flex-1 max-w-[180px] px-3 py-3 rounded-2xl font-black uppercase text-xs md:text-sm transition-all border-2 bg-pink-600 text-white shadow-lg border-pink-600">
        <i class="fa-solid fa-box-archive mr-2 text-sm md:text-base"></i> Paket
    </button>

    <button @click="tab = 'konten'" 
            id="btn-tab-nav-konten"
            class="flex-1 max-w-[180px] px-3 py-3 rounded-2xl font-black uppercase text-xs md:text-sm transition-all border-2 bg-white text-gray-500 border-gray-100">
        <i class="fa-solid fa-images mr-2 text-sm md:text-base"></i> Konten
    </button>

    <button @click="tab = 'kebaya'" 
            id="btn-tab-nav-kebaya"
            class="flex-1 max-w-[180px] px-3 py-3 rounded-2xl font-black uppercase text-xs md:text-sm transition-all border-2 bg-white text-gray-500 border-gray-100">
        <i class="fa-solid fa-shirt mr-2 text-sm md:text-base"></i> Kebaya
    </button>

    <button @click="tab = 'bundling'" 
            id="btn-tab-nav-bundling"
            class="flex-1 max-w-[180px] px-3 py-3 rounded-2xl font-black uppercase text-xs md:text-sm transition-all border-2 bg-white text-gray-500 border-gray-100">
        <i class="fa-solid fa-tags mr-2 text-sm md:text-base"></i> Bundling
    </button>
</div>