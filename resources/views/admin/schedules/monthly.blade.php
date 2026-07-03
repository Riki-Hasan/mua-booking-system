<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-pink-700 leading-tight tracking-tighter uppercase">
                Agenda {{ \Carbon\Carbon::create($year, $month)->format('F Y') }}
            </h2>
            <div class="flex gap-2 no-print">
                <div>
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 bg-white text-gray-700 px-4 py-3 rounded-xl shadow-sm border border-gray-200 hover:text-pink-600 transition-all active:scale-95">
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                        <span class="text-xs font-bold uppercase tracking-wider">Back</span>
                    </a>
                </div>
                <a href="?month={{ $month-1 }}&year={{ $year }}" class="p-3 bg-white rounded-xl border border-gray-200 text-gray-700 hover:text-pink-600 font-bold transition-all">&larr;</a>
                <a href="?month={{ $month+1 }}&year={{ $year }}" class="p-3 bg-white rounded-xl border border-gray-200 text-gray-700 hover:text-pink-600 font-bold transition-all">&rarr;</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="md:bg-white md:shadow-md md:rounded-[2rem] md:border md:border-gray-200 overflow-hidden">
                
                <!-- TAMPILAN 1: MOBILE VIEW (CARD BIODATA MINIMALIS) -->
                <!-- Mengubah divide-y menjadi space-y-5 dan bg-transparent agar jarak antar-card terlihat jelas -->
                <div class="block md:hidden bg-transparent">
                @forelse($bookings as $b)
                <!-- Menggunakan bg-white, border penuh, shadow, dan mb-5 untuk memberikan margin bottom yang nyata di setiap card -->
                <div class="p-5 bg-white rounded-2xl border border-gray-200 shadow-sm mb-5 last:mb-0 transition-all">
                    
                    <!-- Header Card: Nama Pelanggan & Status Bayar -->
                    <div class="flex justify-between items-start border-b border-gray-100 pb-3">
                        <div class="flex flex-col">
                            <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-0.5">Nama Pelanggan</span>
                            <span class="text-base font-extrabold text-gray-900 uppercase tracking-tight">{{ $b->customer_name }}</span>
                        </div>
                        <div class="pt-1">
                            @if($b->status == 'paid_dp')
                                <span class="px-2.5 py-1 bg-orange-50 text-orange-700 border border-orange-200 rounded-lg text-xs font-bold uppercase">DP 50%</span>
                            @else
                                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-xs font-bold uppercase">Lunas</span>
                            @endif
                        </div>
                    </div>

                    <!-- Info Biodata Pelanggan -->
                    <div class="space-y-3 my-4">
                        <div>
                            <span class="block text-[11px] font-bold text-gray-500 uppercase tracking-wide">Tanggal & Jam Rias</span>
                            <span class="text-sm font-bold text-gray-900 uppercase">
                                {{ \Carbon\Carbon::parse($b->booking_date)->format('d M Y') }}
                            </span>
                            <span class="inline-block text-xs font-bold text-pink-700 uppercase ml-1.5">({{ $b->start_time }} WIB)</span>
                        </div>
                        <div>
                            <span class="block text-[11px] font-bold text-gray-500 uppercase tracking-wide">Layanan</span>
                            <span class="text-xs font-bold text-pink-700 uppercase">{{ $b->category->name ?? $b->bundling->subject ?? 'Layanan/Promo'}}</span>
                        </div>
                        <div>
                            <span class="block text-[11px] font-bold text-gray-500 uppercase tracking-wide">Wilayah</span>
                            <span class="text-xs font-bold text-gray-900 uppercase">{{ $b->location->region_name ?? 'Datang ke Toko' }}</span>
                        </div>
                        <div>
                            <span class="block text-[11px] font-bold text-gray-500 uppercase tracking-wide">Alamat Lengkap</span>
                            <p class="text-xs font-semibold text-gray-800 leading-relaxed uppercase">{{ $b->address }}</p>
                        </div>
                    </div>

                    <!-- Aksi Kontak WhatsApp -->
                    <div class="pt-3 border-t border-gray-100 flex items-center justify-between gap-4">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-bold text-gray-500 uppercase">Nomor WhatsApp</span>
                            <span class="text-xs font-bold text-gray-900 tracking-wide">{{ $b->whatsapp_number }}</span>
                        </div>
                        @php
                            $wa = preg_replace('/^0/', '62', $b->whatsapp_number);
                            $msg = urlencode("Halo Kak " . $b->customer_name . ", saya dari Dya's Makeup ingin konfirmasi jadwal rias tanggal " . \Carbon\Carbon::parse($b->booking_date)->format('d/m/Y') . " jam " . $b->start_time . ". Apakah lokasi benar di " . $b->address . "?");
                        @endphp
                        <a href="https://wa.me/{{ $wa }}?text={{ $msg }}" target="_blank" 
                        class="inline-flex items-center gap-2 px-4 py-2 bg-[#25D366] text-white rounded-xl hover:bg-[#128C7E] text-xs font-bold uppercase tracking-wider transition-all shadow-md">
                            <i class="fa-brands fa-whatsapp text-base"></i> Chat WA
                        </a>
                    </div>
                </div>
                @empty
                <div class="p-8 bg-white rounded-2xl border border-gray-200 text-center text-sm text-gray-600 font-bold shadow-sm">
                    Belum ada jadwal untuk bulan ini.
                </div>
                @endforelse
            </div>

                <!-- TAMPILAN 2: LAPTOP/TABLET VIEW (TABEL) -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="p-5 text-xs font-bold uppercase text-gray-700 tracking-wider">Tanggal & Jam</th>
                                <th class="p-5 text-xs font-bold uppercase text-gray-700 tracking-wider">Pelanggan</th>
                                <th class="p-5 text-xs font-bold uppercase text-gray-700 tracking-wider">Lokasi & Alamat</th>
                                <th class="p-5 text-xs font-bold uppercase text-gray-700 tracking-wider">Status Bayar</th>
                                <th class="p-5 text-xs font-bold uppercase text-gray-700 tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($bookings as $b)
                            <tr class="hover:bg-pink-50/20 transition-colors text-sm font-semibold text-gray-900">
                                <td class="p-5">
                                    <span class="block font-bold text-gray-900 uppercase">
                                        {{ \Carbon\Carbon::parse($b->booking_date)->format('d M Y') }}
                                    </span>
                                    <span class="text-xs font-extrabold text-pink-700 uppercase mt-0.5 block">{{ $b->start_time }} WIB</span>
                                </td>

                                <td class="p-5">
                                    <span class="block font-bold text-gray-900 uppercase tracking-tight">{{ $b->customer_name }}</span>
                                    <span class="text-xs text-gray-600 font-medium uppercase block mt-0.5">{{ $b->category->name ?? $b->bundling->subject ?? 'Layanan/Promo'}}</span>
                                </td>

                                <td class="p-5">
                                    <div class="flex items-start gap-2 max-w-[280px]">
                                        <i class="fa-solid fa-map-location-dot text-pink-700 mt-1 text-xs"></i>
                                        <div>
                                            <span class="block text-[11px] font-bold uppercase text-pink-700 tracking-wide mb-0.5">
                                                {{ $b->location->region_name ?? 'Datang ke Toko' }}
                                            </span>
                                            <p class="text-xs font-medium text-gray-800 leading-relaxed uppercase">
                                                {{ $b->address }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="p-5">
                                    @if($b->status == 'paid_dp')
                                        <span class="px-2.5 py-1 bg-orange-50 text-orange-700 border border-orange-200 rounded-lg text-xs font-bold uppercase">DP 50%</span>
                                    @else
                                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-lg text-xs font-bold uppercase">Lunas</span>
                                    @endif
                                </td>

                                <td class="p-5 text-center">
                                    <div class="flex flex-col items-center gap-1.5">
                                        @php
                                            $wa = preg_replace('/^0/', '62', $b->whatsapp_number);
                                            $msg = urlencode("Halo Kak " . $b->customer_name . ", saya dari Dya's Makeup ingin konfirmasi jadwal rias tanggal " . \Carbon\Carbon::parse($b->booking_date)->format('d/m/Y') . " jam " . $b->start_time . ". Apakah lokasi benar di " . $b->address . "?");
                                        @endphp

                                        <a href="https://wa.me/{{ $wa }}?text={{ $msg }}" target="_blank" 
                                        class="inline-flex items-center justify-center w-10 h-10 bg-[#25D366] text-white rounded-xl hover:bg-[#128C7E] transition-all shadow-md active:scale-95" title="Hubungi Via WhatsApp">
                                            <i class="fa-brands fa-whatsapp text-xl"></i>
                                        </a>

                                        <span class="text-xs font-bold text-gray-700 tracking-tight">
                                            {{ $b->whatsapp_number }}
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-10 text-center text-gray-600 font-bold">Belum ada jadwal untuk bulan ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if(!$showPast && $bookings->isNotEmpty())
            <div class="mt-8 text-center">
                <a href="{{ request()->fullUrlWithQuery(['show_past' => 1]) }}" class="text-xs font-bold uppercase text-gray-700 hover:text-pink-700 tracking-wider border-b-2 border-gray-200 hover:border-pink-300 pb-1 transition-all">
                    Tampilkan Booking Sebelumnya
                </a>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>