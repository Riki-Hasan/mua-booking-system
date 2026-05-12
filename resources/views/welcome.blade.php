<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dya's Makeup - Your Beauty, My Art</title>
    
    {{-- PENAMBAHAN: Library Alpine.js agar Tab bisa diklik --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&family=Playfair+Display:ital,wght@0,700;1,700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #FDF7F5; scroll-behavior: smooth; }
        .font-serif { font-family: 'Playfair Display', serif; }
        .swiper-slide { height: auto; }
        
        {{-- PENAMBAHAN: CSS agar tab tidak berkedip saat loading --}}
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="text-slate-800">

    <nav class="fixed w-full z-50 bg-white/70 backdrop-blur-lg border-b border-rose-50">
        <div class="max-w-6xl mx-auto px-6 py-3 flex justify-between items-center">
            <div class="flex gap-3 items-center ">
                <h1 class="text-xl font-black text-rose-500 italic tracking-tighter">Dya's Makeup</h1>
                <a href="{{ route('login') }}" class="hover:text-rose-500 text-xs block md:hidden"><i class="fa-solid fa-lock"></i></a>
            </div>
            
            <div class=" hidden md:flex gap-8 text-[10px] font-black uppercase tracking-widest text-gray-400">
                <a href="#home" class="hover:text-rose-500 transition-colors">Home</a>
                <a href="#portfolio" class="hover:text-rose-500 transition-colors">Portfolio</a>
                <a href="#pricelist" class="hover:text-rose-500 transition-colors">Pricelist</a>
                <a href="{{ route('login') }}" class="hover:text-rose-500"><i class="fa-solid fa-lock"></i></a>
            </div>
            <a href="#pricelist" class="bg-rose-500 text-white px-5 py-2 rounded-full text-[9px] font-black uppercase tracking-widest shadow-md hover:bg-rose-600">Book Now</a>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-6 pt-24 pb-12">
        <section id="home" class="flex flex-col md:flex-row items-center justify-between gap-8 mb-12">
            <div class="md:w-3/5 space-y-4 text-center md:text-left">
                <h2 class="text-3xl md:text-5xl font-serif text-slate-900 leading-tight">
                    Hi, I'm Dya! <br>
                    <span class="text-rose-400 italic">Your Beauty, My Art.</span> <br>
                    <span class="text-2xl md:text-3xl">Welcome to My Studio.</span>
                </h2>
                <p class="text-gray-500 text-xs md:text-sm max-w-sm">Sentuhan riasan profesional untuk memancarkan kecantikan alami Anda di setiap momen berharga.</p>
            </div>
            
            <div class="relative md:w-2/5 flex justify-end">
                <div class="w-48 h-60 md:w-56 md:h-72 bg-rose-100 rounded-[2.5rem] overflow-hidden border-4 border-white shadow-xl flex items-center justify-center">
                    @php
                        $admin = \App\Models\User::first(); 
                    @endphp

                    @if($admin && $admin->profile_photo_path)
                        <img src="{{ asset('storage/' . $admin->profile_photo_path) }}" 
                            alt="Dya Profile" 
                            class="w-full h-full object-cover">
                    @else
                        <i class="fa-solid fa-user-tie text-7xl text-rose-200"></i>
                    @endif
                </div>
            </div>
        </section>

        <section class="mb-20">
            <div class="swiper promoSwiper overflow-visible">
                <div class="swiper-wrapper">
                    @foreach($bundlings as $index => $promo)
                    <div class="swiper-slide w-full md:w-[580px] h-full">
                        <div class="{{ $index % 2 == 0 ? 'bg-slate-900' : 'bg-rose-400' }} rounded-[2.5rem] p-8 text-white flex flex-row items-center gap-6 relative overflow-hidden h-[280px]">
                            <div class="flex-1 z-10">
                                <span class="text-[8px] font-black {{ $index % 2 == 0 ? 'bg-rose-500' : 'bg-white/20' }} px-3 py-1 rounded-md uppercase tracking-widest">
                                    {{ $promo->title }}
                                </span>
                                <h4 class="text-2xl font-black italic mt-3 uppercase leading-tight">
                                    {!! nl2br(e($promo->subject)) !!}
                                </h4>
                                <p class="text-[10px] {{ $index % 2 == 0 ? 'text-gray-400' : 'text-rose-100' }} mt-2 font-bold italic">
                                    {{ $promo->short_description }}
                                </p>
                                <div class="mt-6 flex items-center gap-4">
                                    <div class="text-2xl font-black {{ $index % 2 == 0 ? 'text-rose-400' : 'text-slate-900' }} italic tracking-tighter">
                                        Rp{{ number_format($promo->price/1000, 0) }}k
                                    </div>
                                    <button onclick="openPromoDetail('{{ $promo->id }}', '{{ $promo->subject }}', '{{ $promo->short_description }}', '{{ addslashes($promo->description) }}')" 
                                            class="{{ $index % 2 == 0 ? 'bg-white/10 hover:bg-white/20' : 'bg-slate-900 hover:bg-slate-800' }} text-white px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">
                                        Detail
                                    </button>
                                </div>
                            </div>
                            <div class="w-1/3 relative h-full flex items-center justify-center">
                                <div class="w-full h-48 bg-gray-800 rounded-3xl border-2 {{ $index % 2 == 0 ? 'border-white/10' : 'border-white/30' }} overflow-hidden shadow-2xl {{ $index % 2 == 0 ? 'rotate-2' : '-rotate-2' }}">
                                    <img src="{{ asset('storage/' . $promo->main_image) }}" class="w-full h-full object-cover">
                                </div>
                                <div class="absolute -bottom-2 -left-4 w-20 h-20 bg-gray-700 rounded-full border-4 {{ $index % 2 == 0 ? 'border-slate-900' : 'border-rose-400' }} overflow-hidden shadow-xl">
                                    <img src="{{ asset('storage/' . $promo->secondary_image) }}" class="w-full h-full object-cover">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section id="portfolio" class="max-w-6xl mx-auto py-12" x-data="{ activeTab: 'makeup' }">
            {{-- Navigasi Tab --}}
            <div class="flex justify-center gap-8 mb-12">
                <button @click="activeTab = 'makeup'"
                    :class="activeTab === 'makeup' ? 'text-rose-500 border-b-2 border-rose-500' : 'text-gray-400 hover:text-rose-300'"
                    class="pb-2 text-[10px] font-black uppercase tracking-[0.3em] transition-all">
                    Makeup Portfolio
                </button>
                <button @click="activeTab = 'kebaya'"
                    :class="activeTab === 'kebaya' ? 'text-rose-500 border-b-2 border-rose-500' : 'text-gray-400 hover:text-rose-300'"
                    class="pb-2 text-[10px] font-black uppercase tracking-[0.3em] transition-all">
                    Koleksi Kebaya
                </button>
            </div>

            {{-- Isi Tab: Makeup --}}
            <div x-show="activeTab === 'makeup'" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($portfolios as $index => $portfolio)
                    <div class="relative group aspect-[3/4] bg-white rounded-[2.5rem] overflow-hidden shadow-sm {{ $index % 2 != 0 ? 'md:mt-12' : '' }}">
                        <img src="{{ asset('storage/' . $portfolio->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-all duration-500 flex flex-col items-center justify-center p-6 text-center">
                            <h4 class="text-white font-black italic text-lg leading-tight">{{ $portfolio->category->name }}</h4>
                            <p class="text-rose-200 font-bold text-xs mb-4">Rp{{ number_format($portfolio->category->base_price, 0, ',', '.') }}</p>
                            <a href="{{ route('booking.create', $portfolio->category_id) }}" class="bg-white text-slate-900 px-6 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-rose-500 hover:text-white transition-all">Book Now</a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Isi Tab: Kebaya --}}
            <div x-show="activeTab === 'kebaya'" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-cloak>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @forelse($kebayas as $index => $kebaya)
                    <div class="relative group aspect-[3/4] bg-white rounded-[2.5rem] overflow-hidden shadow-sm {{ $index % 2 != 0 ? 'md:mt-12' : '' }}">
                        <img src="{{ asset('storage/' . $kebaya->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute bottom-0 inset-x-0 p-6 bg-gradient-to-t from-slate-900/90 to-transparent flex flex-col items-center justify-end h-1/2">
                            <h4 class="text-white font-black italic text-sm uppercase tracking-tighter">{{ $kebaya->name }}</h4>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-20 text-center text-gray-300 font-black uppercase italic text-xs tracking-[0.3em]">
                        Belum ada koleksi kebaya yang tersedia
                    </div>
                    @endforelse
                </div>
            </div>
        </section>

        <section id="pricelist" class="py-24">
            <div class="bg-white rounded-[3rem] p-8 md:p-16 border border-rose-50 shadow-sm">
                <h3 class="text-[9px] font-black text-rose-400 uppercase tracking-[0.3em] mb-12 text-center">Pricelist 2026</h3>
                <div class="grid md:grid-cols-2 gap-x-12 gap-y-2">
                    @foreach($categories as $category)
                    <a href="{{ route('booking.create', $category->id) }}" class="group flex justify-between items-center border-b border-rose-50 py-5 hover:px-4 hover:bg-rose-50 rounded-2xl transition-all">
                        <div>
                            <span class="text-xs font-bold text-gray-700 uppercase group-hover:text-rose-500 transition-colors">{{ $category->name }}</span>
                            <p class="text-[8px] text-gray-400 mt-1 uppercase italic opacity-0 group-hover:opacity-100">Klik untuk detail booking</p>
                        </div>
                        <span class="text-xs font-black text-rose-400 tracking-widest">Rp{{ number_format($category->base_price, 0, ',', '.') }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
        </section>
    </main>

    <footer id="contact" class="pt-0 pb-10 border-t border-rose-50 text-center">
        <h2 class="text-lg font-black italic text-rose-500 uppercase mb-6">Follow Me</h2>
        <div class="flex justify-center gap-6">
            <a href="https://www.instagram.com/dyamakeup04_?igsh=MWJxNm5iNGgyazBrYw==" class="text-rose-400 hover:text-rose-600 text-xl transition-transform hover:scale-125"><i class="fa-brands fa-instagram"></i></a>
            <a href="https://wa.me/6285742423379?text=Halo%20Kak%20Admin%20Dya%27s%20Makeup!%20%E2%9C%A8%20Saya%20baru%20saja%20melihat%20portfolio%20di%20website%20dan%20tertarik%20banget%20dengan%20riasannya.%20Boleh%20tanya-tanya%20soal%20paket%20rias%20dan%20ketersediaan%20jadwalnya?%20Terima%20kasih!%20%E2%9D%A4%EF%B8%8F" 
                target="_blank" 
                class="text-rose-400 hover:text-rose-600 text-xl transition-transform hover:scale-125">
                <i class="fa-brands fa-whatsapp"></i>
            </a>
            <a href="https://www.tiktok.com/@dyamakeup04_?_r=1&_t=ZS-9633M479a7e" class="text-rose-400 hover:text-rose-600 text-xl transition-transform hover:scale-125"><i class="fa-brands fa-tiktok"></i></a>
        </div>
        <p class="text-[8px] font-black text-gray-300 uppercase tracking-[0.5em] mt-10">© 2026 Dya's Makeup Studio</p>
    </footer>

    <div id="promoModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-slate-900/90 backdrop-blur-md">
        <div class="bg-white rounded-[2.5rem] max-w-lg w-full p-8 shadow-2xl relative border-4 border-rose-50">
            <button onclick="closePromoModal()" class="absolute top-6 right-6 text-gray-400 hover:text-rose-500 text-xl">
                <i class="fa-solid fa-circle-xmark"></i>
            </button>
            <span class="text-[9px] font-black text-rose-500 uppercase tracking-widest bg-rose-50 px-3 py-1 rounded-full">Detail Bundling</span>
            <h3 id="modalTitle" class="text-2xl font-black italic uppercase tracking-tighter text-slate-900 mt-4 mb-2">Title</h3>
            <div class="space-y-6">
                <div class="p-4 bg-rose-50 rounded-2xl border border-rose-100">
                    <p class="text-[9px] font-black text-rose-400 uppercase tracking-widest mb-1">Highlight</p>
                    <p id="modalShortDesc" class="text-xs font-bold text-slate-700 italic"></p>
                </div>
                <div>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Deskripsi Lengkap</p>
                    <p id="modalFullDesc" class="text-xs text-gray-500 leading-relaxed"></p>
                </div>
            </div>
            <button id="btnBookingPromo" class="w-full bg-slate-900 text-white font-black py-4 rounded-2xl mt-8 hover:bg-rose-500 transition-all uppercase tracking-widest text-[10px]">
                Booking Sekarang
            </button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper('.promoSwiper', {
            slidesPerView: 'auto',
            spaceBetween: 20,
            loop: true,
            autoplay: { delay: 3000, disableOnInteraction: false },
        });

        function openPromoDetail(id, title, shortDesc, fullDesc) {
            document.getElementById('modalTitle').innerText = title;
            document.getElementById('modalShortDesc').innerText = shortDesc;
            document.getElementById('modalFullDesc').innerText = fullDesc;
            const bookingBtn = document.getElementById('btnBookingPromo');
            bookingBtn.onclick = () => { window.location.href = `/booking/promo/${id}`; };
            document.getElementById('promoModal').classList.replace('hidden', 'flex');
        }

        function closePromoModal() {
            const modal = document.getElementById('promoModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
</body>
</html>