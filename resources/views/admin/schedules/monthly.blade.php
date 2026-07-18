<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-pink-700 leading-tight tracking-tighter uppercase">
                Agenda {{ \Carbon\Carbon::create($year, $month)->format('F Y') }}
            </h2>
            <div class="no-print">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 bg-white text-gray-700 px-4 py-3 rounded-xl shadow-sm border border-gray-200 hover:text-pink-600 transition-all active:scale-95">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    <span class="text-xs font-bold uppercase tracking-wider">Back</span>
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        .text-kontras-tablet {
            color: #0f172a !important;
            font-weight: 700 !important;
        }
        .card-minimalis-tab {
            background-color: #ffffff !important;
            border: 2px solid #e2e8f0 !important;
        }
    </style>

    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            
            <!-- 🚨 REVISI 1: MEMINDAHKAN TOMBOL UBAH BULAN KE ATAS CARD (SEBELUM GRID/CARD DAFTAR) -->
            <div class="flex justify-between items-center mb-6 px-1 no-print">
                <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Navigasi Bulan:</span>
                <div class="flex gap-2">
                    <a href="?month={{ $month-1 }}&year={{ $year }}" class="px-5 py-3 bg-white rounded-xl border-2 border-gray-250 text-gray-800 hover:text-pink-600 font-black transition-all active:scale-90 text-sm shadow-sm">&larr; Bulan Lalu</a>
                    <a href="?month={{ $month+1 }}&year={{ $year }}" class="px-5 py-3 bg-white rounded-xl border-2 border-gray-250 text-gray-800 hover:text-pink-600 font-black transition-all active:scale-90 text-sm shadow-sm">Bulan Depan &rarr;</a>
                </div>
            </div>

            <div class="md:bg-white md:shadow-md md:rounded-[2rem] md:border md:border-gray-200 overflow-hidden">
                
                <!-- TAMPILAN 1: MOBILE VIEW (CARD BIODATA JADUL RAMAH TABLET) -->
                <div class="block md:hidden bg-transparent">
                @forelse($bookings as $b)
                <!-- Ditambahkan gap-4 dan p-5 agar bagian dalam card lebih renggang dan bernapas -->
                <div class="p-5 rounded-2xl shadow-sm mb-5 transition-all card-minimalis-tab flex flex-col gap-4">
                    
                    <!-- Baris 1: Nama Pelanggan & Status Bayar (Header Card) -->
                    <div class="flex justify-between items-center border-b border-gray-100 pb-2.5 mb-2">
                        <div class="flex items-center gap-2">
                            <span class="text-md font-black text-gray-900 uppercase tracking-tight">{{ $b->customer_name }}</span>
                            <span class="inline-block ml-4 text-sm font-bold text-pink-700 uppercase bg-pink-50 px-2 py-0.5 rounded border border-pink-100">
                                {{ $b->category->name ?? $b->bundling-> subject ?? 'Layanan'}}
                            </span>
                        </div>
                        <div>
                            @if($b->status == 'paid_dp')
                                <span class="px-2 py-0.5 bg-orange-50 text-orange-700 border border-orange-200 rounded text-[10px] font-black uppercase">DP 50%</span>
                            @else
                                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded text-[10px] font-black uppercase">Lunas</span>
                            @endif
                        </div>
                    </div>

                    <!-- Baris 2: Informasi Waktu (Diberi margin mb-1 agar tidak mepet teks bawah) -->
                    <div class="text-sm text-gray-900 font-bold flex items-center gap-1.5 mb-2">
                        <i class="fa-solid fa-calendar-day text-pink-600 text-sm mr-2"></i>
                        <span class="">{{ \Carbon\Carbon::parse($b->booking_date)->format('d M Y') }}</span>
                        <span class="text-pink-700 font-black inline-block ml-2">({{ $b->start_time }} WIB)</span>
                    </div>

                    <!-- Baris 3: Lokasi & Alamat (Logika Bersyarat Penggabungan Kalimat) -->
                    <div class="text-sm text-gray-900 font-semibold leading-relaxed uppercase mb-2">
                        @if(!$b->location || $b->location->region_name == 'Datang ke Toko')
                            <span class="text-pink-700 font-black flex items-center gap-1.5 mr-2">
                                <i class="fa-solid fa-store text-xs "></i> [🏠 DATANG KE TOKO]
                            </span>
                        @else
                            <div class="flex items-start gap-1.5">
                                <i class="fa-solid fa-map-location-dot text-pink-600 text-xs mt-0.5 mr-2"></i>
                                <span>
                                    <strong class="text-pink-700 font-black">[{{ $b->location->region_name }}]  </strong> 
                                    {{ $b->address }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Baris 4: Area WhatsApp (🚨 REVISI: TOMBOL DIKECILKAN TIDAK FULL WIDTH & TATA DI TENGAH) -->
                    <div class="pt-3 border-t border-gray-100 flex flex-col items-center justify-center gap-2.5">
                        @php
                            $wa = preg_replace('/^0/', '62', $b->whatsapp_number);
                            $msg = urlencode("Halo Kak " . $b->customer_name . ", saya dari Dya's Makeup ingin konfirmasi jadwal rias tanggal " . \Carbon\Carbon::parse($b->booking_date)->format('d/m/Y') . " jam " . $b->start_time . ".");
                        @endphp
                        
                        <!-- Mengubah w-full menjadi inline-flex px-6 agar tombol mengecil pas sesuai teks -->
                        <a href="https://wa.me/{{ $wa }}?text={{ $msg }}" target="_blank" 
                           class="inline-flex items-center justify-center gap-2 py-2.5 px-6 bg-[#25D366] text-white rounded-xl hover:bg-[#128C7E] text-xs font-black uppercase tracking-wider transition-all shadow-sm max-w-xs mx-auto">
                            <i class="fa-brands fa-whatsapp text-sm"></i> Chat WhatsApp
                        </a>
                        
                        <div>
                            <span class="text-xs font-black text-gray-900 tracking-wider bg-slate-100 px-3 py-0.5 rounded border border-slate-200">
                                <i class="fa-solid fa-phone text-[10px] mr-1 text-gray-500"></i> {{ $b->whatsapp_number }}
                            </span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-8 bg-white rounded-2xl border border-gray-200 text-center text-sm text-gray-700 font-bold shadow-sm">
                    Belum ada jadwal untuk bulan ini.
                </div>
                @endforelse
            </div>

                <!-- TAMPILAN 2: LAPTOP VIEW (TABEL) -->
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

                                        <span class="text-xs font-bold text-gray-750 tracking-tight">
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