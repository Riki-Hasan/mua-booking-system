<div class="bg-white rounded-[2rem] shadow-md border border-gray-200 overflow-hidden">
    <div class="p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
        <h2 class="font-bold text-gray-900 uppercase tracking-wider text-sm">Agenda Offline Terbaru</h2>
    </div>

    <!-- TAMPILAN MOBILE CARD -->
    <div class="block md:hidden divide-y divide-gray-200">
        @forelse($offlineBookings as $s)
        <div class="p-5 bg-white space-y-3">
            <div class="flex justify-between items-start">
                <div>
                    <span class="text-xs bg-pink-50 text-pink-700 font-bold px-2.5 py-1 rounded-md border border-pink-100">
                        {{ \Carbon\Carbon::parse($s->booking_date)->format('d/m/Y') }} - {{ $s->start_time }} WIB
                    </span>
                </div>
                <div>
                    @php
                        $pType = (stripos($s->payment_proof, 'qris') !== false) ? 'Transfer' : 'Cash';
                        $pStat = ($s->dp_amount >= $s->total_amount) ? 'Lunas' : 'DP';
                    @endphp
                    <span class="text-xs font-bold px-2 py-0.5 rounded bg-gray-100 text-gray-800 border border-gray-200 uppercase">
                        {{ $pType }} / {{ $pStat }}
                    </span>
                </div>
            </div>
            
            <div class="text-sm">
                <h4 class="font-bold text-gray-900 uppercase tracking-tight">{{ $s->customer_name }} <span class="text-xs text-gray-600 font-medium">({{ $s->person_count }} Orang)</span></h4>
                <p class="text-xs text-gray-700 font-semibold mt-0.5">Layanan: <span class="text-pink-700 uppercase">{{ $s->category->name }}</span></p>
                <p class="text-xs text-gray-600 mt-1 line-clamp-2 italic">📍 Alamat: {{ $s->address }}</p>
            </div>

            <div class="flex gap-2 pt-2 border-t border-gray-100">
                @php
                    $waNumber = preg_replace('/[^0-9]/', '', $s->whatsapp_number);
                    if (str_starts_with($waNumber, '0')) { $waNumber = '62' . substr($waNumber, 1); }
                    $tglIndo = \Carbon\Carbon::parse($s->booking_date)->translatedFormat('d F Y');
                    $pesanWa = "Halo Kak *{$s->customer_name}*! ✨\n\nKami mengonfirmasi jadwal rias Kakak untuk *{$s->person_count} orang*.\n\n📍 *Alamat:* {$s->address}\n📅 *Tanggal:* {$tglIndo}\n⏰ *Jam:* {$s->start_time} WIB\n\nSampai ketemu di hari H ya Kak! Jika ada perubahan segera hubungi kami. Terima kasih! ❤️";
                @endphp
                <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode($pesanWa) }}" target="_blank" class="flex-1 text-center justify-center bg-emerald-600 text-white py-2 px-3 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-emerald-700 transition-all flex items-center gap-1.5">
                    <i class="fa-brands fa-whatsapp text-sm"></i> WhatsApp
                </a>
                <a href="{{ route('booking.receipt', $s->id) }}" class="flex-1 text-center justify-center bg-gray-900 text-white py-2 px-3 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-pink-700 transition-all flex items-center">
                    Lihat Struk
                </a>
            </div>
        </div>
        @empty
        <div class="p-6 text-center text-sm text-gray-500 font-medium">Belum ada agenda offline terbaru.</div>
        @endforelse
    </div>

    <!-- TAMPILAN DESKTOP TABLE -->
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-200 text-xs uppercase text-gray-700 font-bold tracking-wider">
                <tr>
                    <th class="px-6 py-4">Waktu</th>
                    <th class="px-6 py-4">Pelanggan & Layanan</th>
                    <th class="px-6 py-4">Status Bayar</th>
                    <th class="text-center px-6 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @foreach($offlineBookings as $s)
                <tr class="hover:bg-pink-50/20 transition-colors text-sm font-semibold text-gray-900">
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($s->booking_date)->format('d/m/Y') }}</div>
                        <div class="text-xs text-pink-700 font-extrabold mt-0.5">{{ $s->start_time }} WIB</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-900 uppercase tracking-tight">{{ $s->customer_name }} ({{ $s->person_count }} Org)</div>
                        <div class="text-xs text-gray-600 font-medium mt-0.5 uppercase">Layanan: {{ $s->category->name }}</div>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $pType = (stripos($s->payment_proof, 'qris') !== false) ? 'Transfer' : 'Cash';
                            $pStat = ($s->dp_amount >= $s->total_amount) ? 'Lunas' : 'DP';
                        @endphp
                        <span class="text-xs font-bold uppercase text-gray-800 bg-gray-100 border border-gray-200 px-2 py-0.5 rounded">{{ $pType }} / {{ $pStat }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            @php
                                $waNumber = preg_replace('/[^0-9]/', '', $s->whatsapp_number);
                                if (str_starts_with($waNumber, '0')) { $waNumber = '62' . substr($waNumber, 1); }
                                $tglIndo = \Carbon\Carbon::parse($s->booking_date)->translatedFormat('d F Y');
                                $pesanWa = "Halo Kak *{$s->customer_name}*! ✨\n\nKami mengonfirmasi jadwal rias Kakak untuk *{$s->person_count} orang*.\n\n📍 *Alamat:* {$s->address}\n📅 *Tanggal:* {$tglIndo}\n⏰ *Jam:* {$s->start_time} WIB\n\nSampai ketemu di hari H ya Kak! Jika ada perubahan segera hubungi kami. Terima kasih! ❤️";
                            @endphp
                            
                            <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode($pesanWa) }}" target="_blank" class="bg-emerald-600 text-white py-2 px-4 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-emerald-700 transition-all flex items-center">
                                <i class="fa-brands fa-whatsapp mr-1 text-sm"></i> Chat
                            </a>
                            <a href="{{ route('booking.receipt', $s->id) }}" class="bg-gray-900 text-white py-2 px-4 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-pink-700 transition-all flex items-center">Struk</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>