<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-black text-2xl text-pink-600 italic uppercase tracking-tighter">
                Agenda {{ \Carbon\Carbon::create($year, $month)->format('F Y') }}
            </h2>
            <div class="flex gap-2 no-print">
                <div class="">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 bg-white text-gray-500 px-4 py-3 rounded-xl shadow-sm border border-gray-100 hover:text-pink-600 transition-all active:scale-95">
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                        <span class="text-[10px] font-black uppercase tracking-widest">Back</span>
                    </a>
                </div>
                <a href="?month={{ $month-1 }}&year={{ $year }}" class="p-2 bg-white rounded-xl border border-gray-100 text-gray-400 hover:text-pink-500 transition-all">&larr;</a>
                <a href="?month={{ $month+1 }}&year={{ $year }}" class="p-2 bg-white rounded-xl border border-gray-100 text-gray-400 hover:text-pink-500 transition-all">&rarr;</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white overflow-hidden shadow-sm rounded-[2.5rem] border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100">
                                <th class="p-6 text-[10px] font-black uppercase text-gray-400 tracking-widest">Tanggal & Jam</th>
                                <th class="p-6 text-[10px] font-black uppercase text-gray-400 tracking-widest">Pelanggan</th>
                                <th class="p-6 text-[10px] font-black uppercase text-gray-400 tracking-widest">Lokasi & Alamat</th>
                                <th class="p-6 text-[10px] font-black uppercase text-gray-400 tracking-widest">Bayar</th>
                                <th class="p-6 text-[10px] font-black uppercase text-gray-400 tracking-widest text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($bookings as $b)
                            <tr class="hover:bg-pink-50/20 transition-colors">
                                <td class="p-6">
                                    <span class="block font-black text-gray-900 italic uppercase text-sm">
                                        {{ \Carbon\Carbon::parse($b->booking_date)->format('d M Y') }}
                                    </span>
                                    <span class="text-[10px] font-bold text-pink-500 uppercase">{{ $b->start_time }} WIB</span>
                                </td>

                                <td class="p-6">
                                    <span class="block font-bold text-gray-800 text-sm">{{ $b->customer_name }}</span>
                                    <span class="text-[10px] text-gray-400 font-medium uppercase tracking-tighter">{{ $b->category->name ?? $b->bundling->subject ?? 'Layanan/Promo'}}</span>
                                </td>

                                <td class="p-6">
                                    <div class="flex items-start gap-2 max-w-[250px]">
                                        <i class="fa-solid fa-map-location-dot text-pink-500 mt-1 text-xs"></i>
                                        <div>
                                            <span class="block text-[9px] font-black uppercase text-gray-400 tracking-widest mb-1">
                                                {{ $b->location->region_name ?? 'Datang ke Toko' }}
                                            </span>
                                            
                                            <p class="text-[11px] font-bold text-gray-700 leading-relaxed uppercase italic">
                                                {{ $b->address }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="p-6">
                                    @if($b->status == 'paid_dp')
                                        <span class="px-3 py-1 bg-orange-100 text-orange-600 rounded-lg text-[9px] font-black uppercase italic">DP 50%</span>
                                    @else
                                        <span class="px-3 py-1 bg-emerald-100 text-emerald-600 rounded-lg text-[9px] font-black uppercase italic">Lunas</span>
                                    @endif
                                </td>

                                <td class="p-6 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        @php
                                            $wa = preg_replace('/^0/', '62', $b->whatsapp_number);
                                            $msg = urlencode("Halo Kak " . $b->customer_name . ", saya dari Dya's Makeup ingin konfirmasi jadwal rias tanggal " . \Carbon\Carbon::parse($b->booking_date)->format('d/m/Y') . " jam " . $b->start_time . ". Apakah lokasi benar di " . $b->address . "?");
                                        @endphp

                                        <a href="https://wa.me/{{ $wa }}?text={{ $msg }}" target="_blank" 
                                          class="inline-flex items-center justify-center w-11 h-11 bg-[#25D366] text-white rounded-2xl hover:bg-[#128C7E] transition-all shadow-lg shadow-emerald-100 active:scale-95">
                                            <i class="fa-brands fa-whatsapp text-2xl"></i>
                                        </a>

                                        <span class="text-[10px] font-black text-gray-400 tracking-tighter cursor-text hover:text-pink-500 transition-colors">
                                            {{ $b->whatsapp_number }}
                                        </span>
                                    </div>
                                </td>

                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-10 text-center text-gray-400 italic font-medium">Belum ada jadwal untuk bulan ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if(!$showPast && $bookings->isNotEmpty())
            <div class="mt-8 text-center">
                <a href="{{ request()->fullUrlWithQuery(['show_past' => 1]) }}" class="text-[10px] font-black uppercase text-gray-400 hover:text-pink-500 tracking-widest border-b-2 border-gray-100 hover:border-pink-200 pb-1 transition-all">
                    Tampilkan Booking Sebelumnya
                </a>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>