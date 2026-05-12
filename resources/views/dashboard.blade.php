<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl md:text-2xl text-pink-600 leading-tight tracking-tighter">
                {{ __('Admin Control Center') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-12 bg-slate-50 min-h-screen"> <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"> <div class="bg-white overflow-hidden shadow-sm rounded-[2rem] md:rounded-[2.5rem] mb-6 md:mb-8 border border-pink-50 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-pink-500/5 rounded-full -mr-16 -mt-16 blur-3xl"></div>
                <div class="p-6 md:p-10 text-gray-900 relative z-10"> <h1 class="text-2xl md:text-3xl font-black italic tracking-tighter uppercase">Halo, {{ explode(' ', Auth::user()->name)[0] }}! ✨</h1>
                    <p class="text-gray-400 text-[10px] md:text-xs font-bold uppercase tracking-widest mt-1">Kelola bisnismu dengan satu genggaman modern.</p>
                </div>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8 md:mb-10">
                <div class="bg-white p-5 md:p-6 rounded-2xl md:rounded-3xl shadow-sm border border-gray-100">
                    <p class="text-[9px] md:text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Pesanan</p>
                    <p class="text-2xl md:text-3xl font-black text-gray-900 italic mt-1">{{ $totalOrders }}</p>
                </div>

                <div class="bg-white p-5 md:p-6 rounded-2xl md:rounded-3xl shadow-sm border border-gray-100">
                    <p class="text-[9px] md:text-[10px] font-black uppercase text-pink-500 tracking-widest mb-1">Status Bayar</p>
                    <h3 class="text-2xl md:text-3xl font-black text-gray-900 italic">
                        {{ $lunasCount }}<span class="text-gray-300 text-lg mx-1">/</span><span class="text-pink-600">{{ $dpCount }}</span>
                    </h3>
                    <p class="text-[9px] text-gray-400 font-bold mt-1 uppercase italic">Lunas/DP</p>
                </div>

                <a href="{{ route('admin.schedules.monthly') }}" class="col-span-2 md:col-span-1 bg-white p-5 md:p-6 rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 hover:border-pink-300 transition-all group">
                    <p class="text-[9px] md:text-[10px] font-black uppercase text-emerald-500 tracking-widest mb-1">Agenda Kerja</p>
                    <h3 class="text-2xl md:text-3xl font-black text-gray-900 italic group-hover:text-pink-600 transition-colors">
                        {{ $scheduleMonthCount }}
                    </h3>
                    <p class="text-[9px] text-gray-400 font-bold mt-1 uppercase italic group-hover:text-pink-500 transition-colors">Jadwal Bulan Ini &rarr;</p>
                </a>

                <div class="col-span-2 md:col-span-1 bg-white p-5 md:p-6 rounded-2xl md:rounded-3xl shadow-sm border border-gray-100">
                    <p class="text-[9px] md:text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Pendapatan</p>
                    <p class="text-xl md:text-2xl font-black text-gray-900 italic mt-1">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                <a href="{{ route('admin.orders.index') }}" class="group gap-4 bg-white p-6 md:p-8 rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 hover:border-pink-300 transition-all hover:-translate-y-1 flex items-center md:block">
                    <div class="w-12 h-12 md:w-12 md:h-12 bg-pink-50 rounded-xl md:rounded-2xl flex-shrink-0 flex items-center justify-center text-pink-600 md:mb-6 group-hover:bg-pink-600 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <div class="ml-6 md:ml-0"> <h3 class="font-bold text-base md:text-lg text-gray-800 mb-1 md:mb-2 leading-tight">Verifikasi Pesanan</h3>
                        <p class="text-gray-500 text-[10px] md:text-xs italic font-medium">Cek transfer & konfirmasi.</p>
                    </div>
                </a>

                <a href="{{ route('admin.schedules.index') }}" class="group gap-4 bg-white p-6 md:p-8 rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 hover:border-emerald-300 transition-all hover:-translate-y-1 flex items-center md:block">
                    <div class="w-12 h-12 md:w-12 md:h-12 bg-emerald-50 rounded-xl md:rounded-2xl flex-shrink-0 flex items-center justify-center text-emerald-600 md:mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div class="ml-6 md:ml-0"> <h3 class="font-bold text-base md:text-lg text-gray-800 mb-1 md:mb-2 leading-tight">Manajemen Jadwal</h3>
                        <p class="text-gray-500 text-[10px] md:text-xs italic font-medium">Input jadwal offline.</p>
                    </div>
                </a>

                <a href="{{ route('admin.categories.index') }}" class="group gap-4 bg-white p-6 md:p-8 rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 hover:border-blue-300 transition-all hover:-translate-y-1 flex items-center md:block">
                    <div class="w-12 h-12 md:w-12 md:h-12 bg-blue-50 rounded-xl md:rounded-2xl flex-shrink-0 flex items-center justify-center text-blue-600 md:mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div class="ml-6 md:ml-0"> <h3 class="font-bold text-base md:text-lg text-gray-800 mb-1 md:mb-2 leading-tight">Portfolio</h3>
                        <p class="text-gray-500 text-[10px] md:text-xs italic font-medium">Ubah harga & galeri.</p>
                    </div>
                </a>

                <a href="{{ route('admin.locations.index') }}" class="group gap-4 bg-white p-6 md:p-8 rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 hover:border-orange-300 transition-all hover:-translate-y-1 flex items-center md:block">
                    <div class="w-12 h-12 md:w-12 md:h-12 bg-orange-50 rounded-xl md:rounded-2xl flex-shrink-0 flex items-center justify-center text-orange-600 md:mb-6 group-hover:bg-orange-600 group-hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <div class="ml-6 md:ml-0"> <h3 class="font-bold text-base md:text-lg text-gray-800 mb-1 md:mb-2 leading-tight">Wilayah</h3>
                        <p class="text-gray-500 text-[10px] md:text-xs italic font-medium">Atur biaya transportasi.</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>