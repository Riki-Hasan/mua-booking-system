<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-2xl md:text-3xl text-pink-600 leading-tight tracking-tight uppercase italic">
                {{ __('Admin Control Center') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-12 bg-slate-100 min-h-screen"> 
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"> 
            
            {{-- Perbaikan 2: Mengurangi ketebalan border dari border-4 ke border-2 pada mobile/tab, serta memangkas padding agar tidak gemuk --}}
            <div class="bg-white overflow-hidden shadow-md rounded-[2rem] md:rounded-[2.5rem] mb-6 md:mb-8 border-2 md:border-4 border-pink-500 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-40 h-40 bg-pink-500/10 rounded-full -mr-16 -mt-16 blur-2xl"></div>
                <div class="p-4 tab:p-5 md:p-10 text-gray-900 relative z-10"> 
                    <h1 class="text-2xl tab:text-3xl md:text-4xl font-black italic tracking-tighter uppercase text-slate-900">Halo, {{ explode(' ', Auth::user()->name)[0] }}! ✨</h1>
                    <p class="text-slate-600 text-[10px] md:text-sm font-extrabold uppercase tracking-widest mt-1.5 bg-pink-50 inline-block px-2.5 py-0.5 md:px-3 md:py-1 rounded-lg">Kelola bisnismu dengan satu genggaman modern.</p>
                </div>
            </div>

            {{-- Perbaikan 3: Urutan METRICS CARDS disusun ulang (2 Atas: Pesanan & Pendapatan | 2 Bawah: Agenda & Status Bayar) --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8 md:mb-10">
                
                {{-- BARIS ATAS - CARD 1: TOTAL PESANAN --}}
                <div class="bg-white p-4 tab:p-5 md:p-6 rounded-2xl md:rounded-3xl shadow-md border-2 border-slate-200 border-l-[6px] border-l-slate-800 flex flex-col justify-center">
                    <p class="text-[10px] md:text-xs font-black text-slate-500 uppercase tracking-wider mb-1">Total Pesanan</p>
                    <p class="text-2xl tab:text-3xl md:text-4xl font-black text-slate-900 italic mt-1">{{ $totalOrders }}</p>
                </div>

                {{-- BARIS ATAS - CARD 2: TOTAL PENDAPATAN (Sekarang disandingkan ke baris atas berdampingan dengan Total Pesanan) --}}
                <div class="bg-white p-4 tab:p-5 md:p-6 rounded-2xl md:rounded-3xl shadow-md border-2 border-slate-200 border-l-[6px] border-l-blue-600 flex flex-col justify-center">
                    <p class="text-[10px] md:text-xs font-black text-slate-500 tracking-wider uppercase mb-1">Total Pendapatan</p>
                    <div class="mt-1">
                        <p class="text-sm tab:text-base md:text-2xl font-black text-blue-700 italic bg-blue-50/50 p-1.5 tab:p-2 rounded-xl border border-blue-100 inline-block whitespace-nowrap">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</p>
                    </div>
                </div>

                {{-- BARIS BAWAH - CARD 3: AGENDA KERJA (Dipindahkan ke baris bawah) --}}
                <a href="{{ route('admin.schedules.monthly') }}" class="bg-white p-4 tab:p-5 md:p-6 rounded-2xl md:rounded-3xl shadow-md border-2 border-slate-200 border-l-[6px] border-l-emerald-500 hover:border-emerald-500 transition-all group shadow-sm hover:shadow-lg flex flex-col justify-center">
                    <p class="text-[10px] md:text-xs font-black uppercase text-emerald-600 tracking-wider mb-1">Agenda Kerja</p>
                    <h3 class="text-2xl tab:text-3xl md:text-4xl font-black text-slate-900 italic mt-1 group-hover:text-emerald-600 transition-colors leading-none">
                        {{ $scheduleMonthCount }}
                    </h3>
                    <p class="text-[9px] text-emerald-600 font-black mt-1 uppercase italic group-hover:underline">Jadwal Bulan Ini &rarr;</p>
                </a>

                {{-- BARIS BAWAH - CARD 4: STATUS BAYAR (Dipindahkan ke baris bawah berdampingan dengan Agenda Kerja) --}}
                <div class="bg-white p-4 tab:p-5 md:p-6 rounded-2xl md:rounded-3xl shadow-md border-2 border-slate-200 border-l-[6px] border-l-pink-600 flex flex-col justify-center">
                    <p class="text-[10px] md:text-xs font-black uppercase text-pink-600 tracking-wider mb-1">Status Bayar (Lunas/DP)</p>
                    <h3 class="text-2xl tab:text-3xl md:text-4xl font-black text-slate-900 italic mt-1 leading-none">
                        {{ $lunasCount }}<span class="text-slate-400 text-lg md:text-2xl mx-1">/</span><span class="text-pink-600">{{ $dpCount }}</span>
                    </h3>
                    <div class="mt-1">
                        <p class="text-[8px] md:text-[10px] text-slate-500 font-extrabold uppercase italic bg-pink-50 inline-block px-1.5 py-0.5 rounded leading-none">Sistem Pembayaran</p>
                    </div>
                </div>

            </div>

            {{-- NAVIGATION MENUS (BOLD TITLES & HIGH CONTRAST) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                
                {{-- BUTTON 1 --}}
                <a href="{{ route('admin.orders.index') }}" class="group gap-4 bg-white p-6 md:p-8 rounded-2xl md:rounded-3xl shadow-md border-2 border-slate-200 hover:border-pink-500 transition-all hover:-translate-y-1 flex items-center md:block hover:shadow-xl">
                    <div class="w-14 h-14 bg-pink-100 rounded-xl md:rounded-2xl flex-shrink-0 flex items-center justify-center text-pink-600 md:mb-6 group-hover:bg-pink-600 group-hover:text-white transition-colors border border-pink-200 shadow-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <div class="ml-6 md:ml-0"> 
                        <h3 class="font-black text-lg text-slate-900 mb-1 md:mb-2 leading-tight uppercase tracking-tight group-hover:text-pink-600">Verifikasi Pesanan</h3>
                        <p class="text-slate-600 text-xs italic font-bold">Cek transfer & konfirmasi.</p>
                    </div>
                </a>

                {{-- BUTTON 2 --}}
                <a href="{{ route('admin.schedules.index') }}" class="group gap-4 bg-white p-6 md:p-8 rounded-2xl md:rounded-3xl shadow-md border-2 border-slate-200 hover:border-emerald-500 transition-all hover:-translate-y-1 flex items-center md:block hover:shadow-xl">
                    <div class="w-14 h-14 bg-emerald-100 rounded-xl md:rounded-2xl flex-shrink-0 flex items-center justify-center text-emerald-600 md:mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-colors border border-emerald-200 shadow-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div class="ml-6 md:ml-0"> 
                        <h3 class="font-black text-lg text-slate-900 mb-1 md:mb-2 leading-tight uppercase tracking-tight group-hover:text-emerald-600">Manajemen Jadwal</h3>
                        <p class="text-slate-600 text-xs italic font-bold">Input jadwal offline.</p>
                    </div>
                </a>

                {{-- BUTTON 3 --}}
                <a href="{{ route('admin.categories.index') }}" class="group gap-4 bg-white p-6 md:p-8 rounded-2xl md:rounded-3xl shadow-md border-2 border-slate-200 hover:border-blue-500 transition-all hover:-translate-y-1 flex items-center md:block hover:shadow-xl">
                    <div class="w-14 h-14 bg-blue-100 rounded-xl md:rounded-2xl flex-shrink-0 flex items-center justify-center text-blue-600 md:mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors border border-blue-200 shadow-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div class="ml-6 md:ml-0"> 
                        <h3 class="font-black text-lg text-slate-900 mb-1 md:mb-2 leading-tight uppercase tracking-tight group-hover:text-blue-600">Portfolio</h3>
                        <p class="text-slate-600 text-xs italic font-bold">Ubah harga & galeri.</p>
                    </div>
                </a>

                {{-- BUTTON 4 --}}
                <a href="{{ route('admin.locations.index') }}" class="group gap-4 bg-white p-6 md:p-8 rounded-2xl md:rounded-3xl shadow-md border-2 border-slate-200 hover:border-orange-500 transition-all hover:-translate-y-1 flex items-center md:block hover:shadow-xl">
                    <div class="w-14 h-14 bg-orange-100 rounded-xl md:rounded-2xl flex-shrink-0 flex items-center justify-center text-orange-600 md:mb-6 group-hover:bg-orange-600 group-hover:text-white transition-colors border border-orange-200 shadow-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <div class="ml-6 md:ml-0"> 
                        <h3 class="font-black text-lg text-slate-900 mb-1 md:mb-2 leading-tight uppercase tracking-tight group-hover:text-orange-600">Wilayah</h3>
                        <p class="text-slate-600 text-xs italic font-bold">Atur biaya transportasi.</p>
                    </div>
                </a>
                
            </div>
        </div>
    </div>
</x-app-layout>