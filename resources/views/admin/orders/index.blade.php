<x-app-layout>
    <style>
        [x-cloak] { display: none !important; }
        .back-btn-content { display: flex; align-items: center; gap: 0.5rem; }
        @media (max-width: 768px) {
            .mobile-card-grid { display: block; }
            .desktop-table { display: none; }
            .back-btn-content { flex-direction: column; gap: 0.1rem; }
        }
    </style>

    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex justify-between items-center w-full md:w-auto gap-4">
                <h2 class="font-bold text-xl md:text-2xl text-pink-600 leading-tight italic">
                    Verifikasi Pesanan
                </h2>
                
                
            </div>

            <div class="flex items-center gap-3 w-full md:w-auto">
                <a href="{{ route('admin.orders.report') }}" class="flex-1 md:flex-none bg-gray-900 text-white text-[10px] font-black px-6 py-3 rounded-xl hover:bg-pink-600 transition-all uppercase tracking-widest shadow-lg text-center">
                    ⬇️ Download PDF
                </a>

                <div class="">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 bg-white text-gray-500 px-4 py-3 rounded-xl shadow-sm border border-gray-100 hover:text-pink-600 transition-all active:scale-95">
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                        <span class="text-[10px] font-black uppercase tracking-widest">Back</span>
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 md:py-12 bg-slate-50 min-h-screen" x-data="{ openDeleteModal: false, deleteUrl: '', customerName: '' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-500 text-white rounded-2xl font-bold text-sm shadow-lg shadow-emerald-200 animate-pulse">
                    <i class="fa-solid fa-circle-check mr-2"></i> {{ session('success') }}
                </div>
            @endif

            <div class="desktop-table bg-white rounded-[2.5rem] shadow-sm border border-pink-50 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">
                            <th class="px-8 py-6">Pelanggan</th>
                            <th class="px-8 py-6">Layanan & Jadwal</th>
                            <th class="px-8 py-6">Status</th>
                            <th class="px-8 py-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($orders as $order)
                        @php
                            $phone = preg_replace('/[^0-9]/', '', $order->whatsapp_number);
                            if (str_starts_with($phone, '0')) { $phone = '62' . substr($phone, 1); }

                            $tglIndo = \Carbon\Carbon::parse($order->booking_date)->translatedFormat('d F Y');
                            $statusIndo = ($order->status == 'paid_full') ? "Lunas" : (($order->status == 'paid_dp') ? "DP Terbayar" : "Dikonfirmasi");
                            
                            // REVISI 5: Chat Template Friendly
                            $message = "Halo Kak *" . $order->customer_name . "*! ✨ Kabar gembira nih, pesanan rias Kakak untuk *" . $order->person_count . " orang* sudah dikonfirmasi ya. 😍\n\nRencananya kita ketemu di:\n📍 *Alamat:* " . $order->address . "\n📅 *Tanggal:* " . $tglIndo . "\n⏰ *Jam:* " . $order->start_time . " WIB\n\nStatus: *" . $statusIndo . "* ✅\n\nSampai ketemu di hari H ya Kak! Jika ada pertanyaan langsung balas chat ini saja. Terima kasih! ❤️";
                            $waUrl = "https://wa.me/" . $phone . "?text=" . urlencode($message);
                        @endphp
                        
                        <tr class="hover:bg-pink-50/20 transition-colors">
                            <td class="px-8 py-6">
                                <div class="font-black text-gray-900 text-lg">{{ $order->customer_name }}</div>
                                <div class="text-pink-500 font-bold text-xs uppercase tracking-widest">{{ $order->whatsapp_number }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-sm font-black text-gray-700">{{ $order->category->name ?? $order->bundling->subject ?? 'Paket/Promo' }} 
                                ({{ $order->person_count }} Org)</div>
                                @if($order->bundling_id)
                                    <span class="text-[8px] bg-rose-50 text-rose-500 px-2 py-0.5 rounded-full font-black uppercase">Promo</span>
                                @endif
                                <div class="text-[10px] text-gray-400 font-bold uppercase mt-1">{{ $tglIndo }} | {{ $order->start_time }} WIB</div>
                            </td>
                            <td class="px-8 py-6">
                                @if($order->status == 'paid_dp')
                                    <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-[9px] font-black">DP 50%</span>
                                @elseif($order->status == 'paid_full')
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-600 rounded-full text-[9px] font-black">LUNAS</span>
                                @else
                                    <span class="px-3 py-1 bg-pink-100 text-pink-600 rounded-full text-[9px] font-black uppercase">Confirmed</span>
                                @endif
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center justify-center gap-3">
                                    <a href="{{ $waUrl }}" target="_blank" class="p-3 bg-emerald-50 text-emerald-600 rounded-xl hover:bg-emerald-500 hover:text-white transition-all shadow-sm shadow-emerald-100" title="Kirim WhatsApp">
                                        <i class="fa-brands fa-whatsapp text-lg"></i>
                                    </a>
                                    <a href="{{ route('booking.receipt', $order->id) }}" target="_blank" class="p-3 bg-gray-50 text-gray-600 rounded-xl hover:bg-gray-900 hover:text-white transition-all shadow-sm" title="Struk Digital">
                                        <i class="fa-solid fa-file-invoice"></i>
                                    </a>
                                    <button @click="openDeleteModal = true; deleteUrl = '{{ route('admin.orders.destroy', $order->id) }}'; customerName = '{{ $order->customer_name }}'" class="p-3 bg-rose-50 text-rose-400 rounded-xl hover:bg-rose-500 hover:text-white transition-all shadow-sm shadow-rose-100">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="py-20 text-center text-gray-300 font-black italic uppercase">Kosong</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="hidden mobile-card-grid space-y-4">
                @foreach($orders as $order)
                <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-pink-50">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <div class="font-black text-gray-900 text-lg">{{ $order->customer_name }}</div>
                            <div class="text-pink-500 font-bold text-[10px] uppercase tracking-widest">{{ $order->whatsapp_number }}</div>
                        </div>
                        @if($order->status == 'paid_full')
                            <span class="text-[8px] font-black bg-emerald-100 text-emerald-600 px-2 py-1 rounded-lg">LUNAS</span>
                        @else
                            <span class="text-[8px] font-black bg-blue-100 text-blue-600 px-2 py-1 rounded-lg">DP</span>
                        @endif
                    </div>
                    <div class="space-y-2 mb-6">
                        <div class="flex items-center text-xs font-bold text-gray-600">
                            <i class="fa-solid fa-wand-sparkles mr-2 text-pink-300"></i> 
                            {{ $order->category->name ?? $order->bundling->subject ?? 'Paket/Promo' }} ({{ $order->person_count }} Org)
                            @if($order->bundling_id)
                                <span class="ml-2 text-[7px] bg-rose-50 text-rose-500 px-1.5 py-0.5 rounded-md font-black uppercase">Promo</span>
                            @endif
                        </div>
                        <div class="flex items-center text-xs font-bold text-gray-400"><i class="fa-solid fa-calendar-day mr-2 text-pink-200"></i> {{ \Carbon\Carbon::parse($order->booking_date)->translatedFormat('d F Y') }}</div>
                        <div class="flex items-center text-xs font-bold text-gray-400"><i class="fa-solid fa-clock mr-2 text-pink-200"></i> {{ $order->start_time }} WIB</div>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <a href="{{ $waUrl }}" class="bg-emerald-500 text-white p-3 rounded-xl text-center"><i class="fa-brands fa-whatsapp"></i></a>
                        <a href="{{ route('booking.receipt', $order->id) }}" class="bg-gray-900 text-white p-3 rounded-xl text-center"><i class="fa-solid fa-file-invoice"></i></a>
                        <button @click="openDeleteModal = true; deleteUrl = '{{ route('admin.orders.destroy', $order->id) }}'; customerName = '{{ $order->customer_name }}'" class="bg-rose-100 text-rose-500 p-3 rounded-xl text-center"><i class="fa-solid fa-trash-can"></i></button>
                    </div>
                </div>
                @endforeach
            </div>

        </div>

        <div x-show="openDeleteModal" 
             x-cloak
             class="fixed inset-0 z-[200] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            <div class="bg-white p-8 rounded-[2.5rem] shadow-2xl max-w-sm w-full mx-4 text-center border-4 border-rose-50">
                <div class="w-16 h-16 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-6 animate-bounce">
                    <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
                </div>
                <h3 class="text-xl font-black text-gray-900 uppercase italic mb-2">Hapus Pesanan?</h3>
                <p class="text-gray-500 text-sm mb-8 leading-relaxed">
                    Yakin ingin menghapus data milik <span class="text-rose-500 font-bold" x-text="customerName"></span>?
                </p>
                
                <div class="flex gap-3">
                    <button @click="openDeleteModal = false" class="flex-1 py-4 bg-gray-100 text-gray-500 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-gray-200 transition-all active:scale-95">Batal</button>
                    <form :action="deleteUrl" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-4 bg-rose-500 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-rose-600 shadow-lg shadow-rose-200 transition-all active:scale-95">Ya, Hapus!</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>