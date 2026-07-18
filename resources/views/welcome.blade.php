<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Dya's Makeup - Your Beauty, My Art</title>
    
    {{-- Memanggil Tailwind CSS via Vite Lokal (Anti-Blokir Ngrok) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&family=Playfair+Display:ital,wght@0,700;1,700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #FDF7F5; scroll-behavior: smooth; }
        .font-serif { font-family: 'Playfair Display', serif; }
        .swiper-slide { height: auto; }

        /* 🚨 REVISI GLOBAL OVERLAY MODAL JADUL RAMAH TABLET JADUL */
        #promoModal {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            width: 100% !important;
            height: 100% !important;
            background-color: rgba(15, 23, 42, 0.8) !important;
            align-items: center !important;
            justify-content: center !important;
            z-index: 2147483647 !important;
        }

        /* 🚨 PERBAIKAN TOMBOL SILANG MODAL: Memaksa transparan murni untuk menghapus kotak abu bawaan tablet */
        .btn-close-modal-clear {
            background-color: transparent !important;
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            outline: none !important;
            padding: 0 !important;
        }

        /* Kelas Pengunci Keterbukaan Overlay Portofolio via Click JavaScript */
        .show-overlay-tablet {
            opacity: 1 !important;
        }
    </style>
</head>
<body class="text-slate-800">

    <nav class="fixed w-full z-50 bg-white/70 backdrop-blur-lg border-b border-rose-50 box-border no-print">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-3 flex justify-between items-center gap-2 w-full box-border">
            <div class="flex gap-4 items-center flex-shrink-0">
                <h1 class="text-base sm:text-xl font-black text-rose-500 italic tracking-tighter whitespace-nowrap">Dya's Makeup</h1>
                <a href="{{ route('login') }}" class="hover:text-rose-500 text-xs block md:hidden ml-2"><i class="fa-solid fa-lock"></i></a>
            </div>
            
            <div class="hidden md:flex gap-6 lg:gap-8 text-[10px] font-black uppercase tracking-widest text-gray-400 flex-shrink-0">
                <a href="#home" class="hover:text-rose-500 transition-colors">Home</a>
                <a href="#portfolio" class="hover:text-rose-500 transition-colors">Portfolio</a>
                <a href="#pricelist" class="hover:text-rose-500 transition-colors">Pricelist</a>
                <a href="{{ route('login') }}" class="hover:text-rose-500"><i class="fa-solid fa-lock"></i></a>
            </div>
            <div class="flex-shrink-0">
                <a href="#pricelist" class="bg-rose-500 text-white px-3 sm:px-5 py-2 rounded-full text-[9px] font-black uppercase tracking-widest shadow-md hover:bg-rose-600 transition-all whitespace-nowrap">Book Now</a>
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-6 pt-24 pb-12">
        <!-- HERO SECTION -->
        <section id="home" class="flex flex-col items-center justify-between gap-8 mb-12 text-center md:flex-row md:text-left">
            <div class="md:w-3/5 space-y-4 flex flex-col items-center md:items-start">
                <h2 class="text-3xl md:text-5xl font-serif text-slate-900 leading-tight">
                    Hi, I'm Dya! <br>
                    <span class="text-rose-400 italic">Your Beauty, My Art.</span> <br>
                    <span class="text-2xl md:text-3xl">Welcome to My Studio.</span>
                </h2>
                <p class="text-gray-500 text-xs md:text-sm max-w-sm">Sentuhan riasan profesional untuk memancarkan kecantikan alami Anda di setiap momen berharga.</p>
            </div>
            
            <div class="relative md:w-2/5 flex justify-center md:justify-end">
                <div class="w-48 h-60 md:w-56 md:h-72 bg-rose-100 rounded-[2.5rem] overflow-hidden border-4 border-white shadow-xl flex items-center justify-center">
                    @php
                        $admin = \App\Models\User::first(); 
                    @endphp

                    @if($admin && $admin->profile_photo_path)
                        <img src="{{ asset('storage/' . $admin->profile_photo_path) }}" alt="Dya Profile" class="w-full h-full object-cover">
                    @else
                        <i class="fa-solid fa-user-tie text-7xl text-rose-200"></i>
                    @endif
                </div>
            </div>
        </section>

        <!-- PROMO BUNDLING -->
        <section class="mb-20">
            <div class="swiper promoSwiper overflow-visible">
                <div class="swiper-wrapper">
                    @foreach($bundlings as $index => $promo)
                    <div class="swiper-slide w-[90%] tab:w-[460px] md:w-[580px] h-full">
                        <div class="{{ $index % 2 == 0 ? 'bg-slate-900' : 'bg-rose-400' }} rounded-[2.5rem] p-6 tab:p-8 text-white flex flex-row items-center gap-4 tab:gap-6 relative overflow-hidden h-[250px] tab:h-[280px]">
                            <div class="flex-1 z-10">
                                <span class="text-[8px] font-black {{ $index % 2 == 0 ? 'bg-rose-500' : 'bg-white/20' }} px-3 py-1 rounded-md uppercase tracking-widest">
                                    {{ $promo->title }}
                                </span>
                                <h4 class="text-lg tab:text-2xl font-black italic mt-3 uppercase leading-tight">
                                    {!! nl2br(e($promo->subject)) !!}
                                </h4>
                                
                                <!-- 🚨 PERBAIKAN 1: Ukuran font short_description diperbesar menjadi text-xs agar terbaca jelas di tablet -->
                                <p class="text-xs {{ $index % 2 == 0 ? 'text-gray-300' : 'text-white' }} mt-2 font-bold italic line-clamp-2">
                                    {{ $promo->short_description }}
                                </p>
                                
                                <div class="mt-4 tab:mt-6 flex items-center">
                                    <div class="text-xl tab:text-2xl font-black {{ $index % 2 == 0 ? 'text-rose-400' : 'text-slate-900' }} italic tracking-tighter">
                                        Rp{{ number_format($promo->price/1000, 0) }}k
                                    </div>
                                    
                                    <!-- 🚨 PERBAIKAN 2: Memberi jarak margin kiri (ml-6) agar tombol detail tidak mepet dengan harga -->
                                    <button onclick="openPromoDetail('{{ $promo->id }}', '{{ e($promo->subject) }}', '{{ e($promo->short_description) }}', '{{ addslashes($promo->description) }}')" 
                                            class="ml-6 {{ $index % 2 == 0 ? 'bg-white/10 hover:bg-white/20' : 'bg-slate-900 hover:bg-slate-800' }} text-white px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">
                                        Detail
                                    </button>
                                </div>
                            </div>
                            <div class="w-1/3 relative h-full flex items-center justify-center">
                                <div class="w-full h-36 tab:h-48 bg-gray-800 rounded-3xl border-2 {{ $index % 2 == 0 ? 'border-white/10' : 'border-white/30' }} overflow-hidden shadow-2xl {{ $index % 2 == 0 ? 'rotate-2' : '-rotate-2' }}">
                                    <img src="{{ asset('storage/' . $promo->main_image) }}" class="w-full h-full object-cover">
                                </div>
                                <div class="absolute -bottom-2 -left-4 w-14 h-14 tab:w-20 tab:h-20 bg-gray-700 rounded-full border-4 {{ $index % 2 == 0 ? 'border-slate-900' : 'border-rose-400' }} overflow-hidden shadow-xl">
                                    <img src="{{ asset('storage/' . $promo->secondary_image) }}" class="w-full h-full object-cover">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- PORTOFOLIO SECTION -->
        <section id="portfolio" class="max-w-6xl mx-auto py-12">
            
            <div class="flex justify-center gap-4 mb-12 max-w-sm mx-auto px-4 no-print">
                <button id="btn-tab-makeup" onclick="switchMainTab('makeup')"
                    class="bg-rose-500 text-white shadow-md font-black py-3 px-6 text-[10px] uppercase tracking-[0.2em] transition-all flex-1 text-center rounded-full cursor-pointer touch-manipulation select-none">
                    Makeup
                </button>
                <button id="btn-tab-kebaya" onclick="switchMainTab('kebaya')"
                    class="bg-gray-100 text-gray-500 hover:bg-rose-50 hover:text-rose-400 font-bold py-3 px-6 text-[10px] uppercase tracking-[0.2em] transition-all flex-1 text-center rounded-full cursor-pointer touch-manipulation select-none">
                    Kebaya
                </button>
            </div>

            {{-- 🚨 GRID MAKEUP: Menggunakan Padding-Bottom Hack agar ukuran card 100% seiras, dan penataan mt-6 / mt-4 membuat susunan selang-seling estetik laptop berjalan di tablet --}}
            <div id="panel-makeup" class="grid grid-cols-2 tab:grid-cols-3 md:grid-cols-4 gap-4">
                @foreach($portfolios as $index => $portfolio)
                <div onclick="toggleOverlayTablet(this, event)" 
                     class="card-portfolio-item relative group bg-white rounded-[2.5rem] overflow-hidden shadow-sm cursor-pointer border border-gray-100 {{ ($index % 2 != 0) ? 'mt-6' : '' }}"
                     style="padding-bottom: 133.33% !important; height: 0 !important; position: relative !important;">
                    
                    <img src="{{ asset('storage/' . $portfolio->image_path) }}" 
                         class="transition-transform duration-700"
                         style="position: absolute !important; top: 0 !important; left: 0 !important; width: 100% !important; height: 100% !important; object-fit: cover !important;">
                    
                    <!-- Area Keterangan Overlay (Menerapkan Opsi 2: Muncul ketika diklik di tablet) -->
                    <div class="portfolio-overlay absolute inset-0 bg-slate-900/80 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col items-center justify-center p-4 text-center"
                         style="position: absolute !important; width: 100% !important; height: 100% !important; top: 0 !important; left: 0 !important;">
                        <h4 class="text-white font-black italic text-sm tab:text-lg leading-tight uppercase">{{ $portfolio->category->name }}</h4>
                        <p class="text-rose-300 font-black text-xs mb-4">Rp{{ number_format($portfolio->category->base_price, 0, ',', '.') }}</p>
                        <a href="{{ route('booking.create', $portfolio->category_id) }}" class="bg-white text-slate-900 px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-500 hover:text-white transition-all shadow-md">Book Now</a>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Grid Kebaya (Selang-seling seiras) --}}
            <div id="panel-kebaya" class="grid grid-cols-2 tab:grid-cols-3 md:grid-cols-4 gap-4 hidden" style="display: none;">
                @forelse($kebayas as $index => $kebaya)
                <div class="relative bg-white rounded-[2.5rem] overflow-hidden shadow-sm border border-gray-100 {{ ($index % 2 != 0) ? 'mt-6' : '' }}"
                     style="padding-bottom: 133.33% !important; height: 0 !important; position: relative !important;">
                    <img src="{{ asset('storage/' . $kebaya->image_path) }}" 
                         style="position: absolute !important; top: 0 !important; left: 0 !important; width: 100% !important; height: 100% !important; object-fit: cover !important;">
                    <div class="absolute bottom-0 inset-x-0 p-4 bg-gradient-to-t from-slate-900/90 to-transparent flex flex-col items-center justify-end h-1/2" style="position: absolute !important; width: 100% !important; bottom: 0 !important;">
                        <h4 class="text-white font-black italic text-xs uppercase tracking-tighter text-center leading-none">{{ $kebaya->name }}</h4>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-20 text-center text-gray-400 font-black uppercase italic text-xs tracking-[0.3em]">
                    Belum ada koleksi kebaya yang tersedia
                </div>
                @endforelse
            </div>
        </section>

        <!-- PRICELIST SECTION -->
        <section id="pricelist" class="py-16">
            <div class="bg-white rounded-[3rem] p-6 md:p-16 border border-rose-50 shadow-sm">
                <h3 class="text-[9px] font-black text-rose-400 uppercase tracking-[0.3em] mb-8 text-center">Pricelist 2026</h3>
                <div class="grid md:grid-cols-2 gap-x-12 gap-y-1">
                    @foreach($categories as $category)
                    <a href="{{ route('booking.create', $category->id) }}" class="group flex justify-between items-center border-b border-rose-50 py-3 hover:px-4 hover:bg-rose-50 rounded-2xl transition-all">
                        <div>
                            <span class="text-xs font-bold text-gray-700 uppercase group-hover:text-rose-500 transition-colors">{{ $category->name }}</span>
                            <p class="text-[8px] text-gray-400 mt-0.5 uppercase italic opacity-0 group-hover:opacity-100">Klik untuk detail booking</p>
                        </div>
                        <span class="text-xs font-black text-rose-400 tracking-widest">Rp{{ number_format($category->base_price, 0, ',', '.') }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
        </section>
    </main>

    <!-- FOOTER -->
    <footer id="contact" class="pt-0 pb-10 border-t border-rose-50 text-center">
        <h2 class="text-sm font-black italic text-rose-500 uppercase mb-6 tracking-wider">Follow Me</h2>
        <div class="flex justify-center gap-8 items-center">
            <a href="https://www.instagram.com/dyamakeup04_?igsh=MWJxNm5iNGgyazBrYw==" class="mr-2 text-rose-400 hover:text-rose-600 text-2xl transition-transform hover:scale-125"><i class="fa-brands fa-instagram"></i></a>
            <a href="https://wa.me/6285742423379?text=Halo%20Kak%20Admin%20Dya%27s%20Makeup!%20%E2%9C%A8%20Saya%20baru%20saja%20melihat%20portfolio%20di%20website%20dan%20tertarik%20banget%20dengan%20riasannya.%20Boleh%20tanya-tanya%20soal%20paket%20rias%20dan%20ketersediaan%20jadwalnya?%20Terima%20kasih!%20%E2%9D%A4%EF%B8%8F" 
                target="_blank" 
                class="text-rose-400 hover:text-rose-600 text-2xl transition-transform hover:scale-125 mr-2">
                <i class="fa-brands fa-whatsapp"></i>
            </a>
            <a href="https://www.tiktok.com/@dyamakeup04_?_r=1&_t=ZS-9633M479a7e" class="text-rose-400 hover:text-rose-600 text-2xl transition-transform hover:scale-125"><i class="fa-brands fa-tiktok"></i></a>
        </div>
        <p class="text-[8px] font-black text-gray-700 uppercase tracking-[0.5em] mt-10">© 2026 Dya's Makeup Studio</p>
    </footer>

    <!-- 🚨 REVISI TOTAL KETERBACAAN MODAL DETAIL PROMO BUNDLING -->
    <div id="promoModal" class="hidden no-print" style="display: none;">
        <div class="bg-white rounded-[2.5rem] max-w-lg w-full p-8 shadow-2xl relative border-4 border-rose-100 mx-4" style="display: block !important;">
            
            <!-- Tombol Silang Terkunci Transparan (Bebas Kotak Abu-Abu Sistem) -->
            <button onclick="closePromoModal()" class="btn-close-modal-clear absolute top-6 right-6 text-gray-900 hover:text-rose-500 text-2xl" style="position: absolute;">
                <i class="fa-solid fa-circle-xmark"></i>
            </button>
            
            <span class="text-[10px] font-black text-pink-700 uppercase tracking-widest bg-pink-50 px-3 py-1 rounded-full border border-pink-100">Detail Bundling</span>
            <h3 id="modalTitle" class="text-2xl font-black italic uppercase tracking-tighter text-gray-900 mt-5 mb-3">Title</h3>
            
            <div class="space-y-4">
                <!-- Box Highlight (Teks Hitam Tebal) -->
                <div class="p-4 bg-rose-50/60 rounded-2xl border-2 border-rose-100">
                    <p class="text-[10px] font-black text-gray-900 uppercase tracking-widest mb-1">Highlight Promo:</p>
                    <p id="modalShortDesc" class="text-sm font-black text-gray-900 italic leading-snug"></p>
                </div>
                
                <!-- Box Deskripsi (Diubah Total Menjadi Hitam Pekat & Tebal Kontras Tinggi) -->
                <div class="p-1">
                    <p class="text-[10px] font-black text-gray-900 uppercase tracking-widest mb-1.5">Deskripsi Lengkap Paket:</p>
                    <p id="modalFullDesc" class="text-sm text-gray-900 font-bold leading-relaxed bg-slate-50 p-4 rounded-xl border border-gray-200"></p>
                </div>
            </div>
            
            <button id="btnBookingPromo" class="w-full bg-slate-900 text-white font-black py-4 rounded-2xl mt-6 hover:bg-rose-600 transition-all uppercase tracking-widest text-xs">
                Booking Sekarang →
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

        // 🚨 OPSI 2 NATIVE CONTROLLER: Sentuhan pertama pada tablet mengunci tampilan overlay portofolio
        function toggleOverlayTablet(container, event) {
            // Cek apakah perangkat mendukung touch (layar sentuh / mobile / tablet)
            if (window.innerWidth <= 1024) {
                var overlay = container.querySelector('.portfolio-overlay');
                var isAlreadyOpen = overlay.classList.contains('show-overlay-tablet');
                
                // Tutup dulu semua overlay aktif lainnya agar tidak tumpang tindih
                var allOverlays = document.querySelectorAll('.portfolio-overlay');
                allOverlays.forEach(function(ov) {
                    ov.classList.remove('show-overlay-tablet');
                });
                
                // Jika sebelumnya belum terbuka, buka overlay ini (Sentuhan Pertama)
                if (!isAlreadyOpen) {
                    event.preventDefault(); // Menahan link redirect instan agar tulisan terbaca dulu
                    overlay.classList.add('show-overlay-tablet');
                }
            }
        }

        function switchMainTab(tabName) {
            const btnMakeup = document.getElementById('btn-tab-makeup');
            const btnKebaya = document.getElementById('btn-tab-kebaya');
            const panelMakeup = document.getElementById('panel-makeup');
            const panelKebaya = document.getElementById('panel-kebaya');

            const activeClass = "bg-rose-500 text-white shadow-md font-black py-3 px-6 text-[10px] uppercase tracking-[0.2em] transition-all flex-1 text-center rounded-full cursor-pointer touch-manipulation select-none";
            const inactiveClass = "bg-gray-100 text-gray-500 hover:bg-rose-50 hover:text-rose-400 font-bold py-3 px-6 text-[10px] uppercase tracking-[0.2em] transition-all flex-1 text-center rounded-full cursor-pointer touch-manipulation select-none";

            if (tabName === 'makeup') {
                btnMakeup.className = activeClass;
                btnKebaya.className = inactiveClass;
                panelMakeup.style.setProperty('display', 'grid', 'important');
                panelKebaya.style.setProperty('display', 'none', 'important');
            } else {
                btnMakeup.className = inactiveClass;
                btnKebaya.className = activeClass;
                panelMakeup.style.setProperty('display', 'none', 'important');
                panelKebaya.style.setProperty('display', 'grid', 'important');
            }
        }

        function openPromoDetail(id, title, shortDesc, fullDesc) {
            document.getElementById('modalTitle').innerText = title;
            document.getElementById('modalShortDesc').innerText = shortDesc;
            document.getElementById('modalFullDesc').innerText = fullDesc;
            
            const bookingBtn = document.getElementById('btnBookingPromo');
            bookingBtn.onclick = function() { 
                window.location.href = '/booking/promo/' + id; 
            };
            
            const modal = document.getElementById('promoModal');
            if(modal) {
                modal.style.setProperty('display', 'flex', 'important');
            }
        }

        function closePromoModal() {
            const modal = document.getElementById('promoModal');
            if(modal) {
                modal.style.setProperty('display', 'none', 'important');
            }
        }

        // Klik area luar untuk menyembunyikan overlay portofolio di tablet
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.card-portfolio-item')) {
                var allOverlays = document.querySelectorAll('.portfolio-overlay');
                allOverlays.forEach(function(ov) {
                    ov.classList.remove('show-overlay-tablet');
                });
            }
        });
    </script>
</body>
</html>