<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-pink-600 leading-tight italic">
                Verifikasi Pesanan Pelanggan
            </h2>
            <a href="{{ route('admin.orders.report') }}" class="bg-gray-900 text-white text-[10px] font-black px-6 py-3 rounded-xl hover:bg-pink-600 transition-all uppercase tracking-widest shadow-lg">
                ⬇️ Download Laporan PDF
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen" x-data="{ openDeleteModal: false, deleteUrl: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-100 border border-emerald-200 text-emerald-700 rounded-2xl font-bold text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-[2.5rem] shadow-sm border border-pink-50 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400">
                            <th class="px-8 py-6">Pelanggan</th>
                            <th class="px-8 py-6">Layanan & Jadwal</th>
                            <th class="px-8 py-6">Total & DP</th>
                            <th class="px-8 py-6">Struk Digital</th> 
                            <th class="px-8 py-6">Status</th>
                            <th class="px-8 py-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($orders as $order)
                        @php
                            $phone = preg_replace('/[^0-9]/', '', $order->whatsapp_number);
                            if (str_starts_with($phone, '0')) {
                                $phone = '62' . substr($phone, 1);
                            }

                            // Logika Notif WA
                            $statusMsg = ($order->status == 'paid_full') ? "LUNAS" : (($order->status == 'paid_dp') ? "DP TERBAYAR" : "MENUNGGU PEMBAYARAN");
                            $message = "Halo *" . $order->customer_name . "*, pesanan *" . $order->category->name . "* Anda berstatus *" . $statusMsg . "*. ✅ Mohon segera lakukan pembayaran jika belum. Sampai jumpa di tanggal *" . \Carbon\Carbon::parse($order->booking_date)->format('d M Y') . "*!";
                            $waUrl = "https://api.whatsapp.com/send?phone=" . $phone . "&text=" . urlencode($message);
                        @endphp
                        
                        <tr class="hover:bg-pink-50/20 transition-colors">
                            <td class="px-8 py-6">
                                <div class="font-black text-gray-900 text-lg">{{ $order->customer_name }}</div>
                                <div class="text-pink-500 font-bold text-xs uppercase tracking-widest">{{ $order->whatsapp_number }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-sm font-black text-gray-700">{{ $order->category->name }}</div>
                                <div class="text-[10px] text-gray-400 font-bold uppercase mt-1">
                                    {{ \Carbon\Carbon::parse($order->booking_date)->format('d M Y') }} | {{ $order->start_time }} WIB
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="font-black text-gray-900">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</div>
                                <div class="text-[10px] text-emerald-600 font-black uppercase tracking-tighter italic">Min. DP: Rp{{ number_format($order->dp_amount, 0, ',', '.') }}</div>
                            </td>
                            
                            <td class="px-8 py-6">
                                @if($order->status == 'paid_dp' || $order->status == 'paid_full')
                                    <a href="{{ route('booking.receipt', $order->id) }}" target="_blank" class="inline-block bg-emerald-100 text-emerald-700 text-[10px] font-black px-4 py-2 rounded-xl hover:bg-emerald-200 transition-all uppercase italic">
                                        Lihat Struk
                                    </a>
                                @else
                                    <span class="text-[10px] text-rose-400 font-bold italic uppercase">Belum Bayar</span>
                                @endif
                            </td>

                            <td class="px-8 py-6">
                                @if($order->status == 'paid_dp')
                                    <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-[10px] font-black uppercase tracking-widest">DP (50%)</span>
                                @elseif($order->status == 'paid_full')
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-600 rounded-full text-[10px] font-black uppercase tracking-widest">LUNAS</span>
                                @else
                                    <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-full text-[10px] font-black uppercase tracking-widest">BELUM BAYAR</span>
                                @endif
                            </td>

                            <td class="px-8 py-6">
                                <div class="flex flex-col gap-2 items-center">
                                    <a href="{{ $waUrl }}" target="_blank" class="w-full text-center border border-emerald-500 text-emerald-600 text-[10px] font-black py-2 rounded-xl hover:bg-emerald-50 transition-all uppercase tracking-widest">
                                         Kirim Notif WA
                                    </a>
                                    
                                    <button type="button" 
                                            @click="openDeleteModal = true; deleteUrl = '{{ route('admin.orders.destroy', $order->id) }}'"
                                            class="w-full text-rose-400 text-[9px] font-bold hover:text-rose-600 transition-all uppercase tracking-tighter">
                                        Hapus Data
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center">
                                <div class="text-gray-300 font-black text-xl italic uppercase tracking-tighter">Belum ada pesanan masuk</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="openDeleteModal" 
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm"
             x-cloak>
            <div class="bg-white p-8 rounded-[2.5rem] shadow-2xl max-w-sm w-full mx-4 text-center border border-pink-50">
                <div class="w-16 h-16 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="text-xl font-black text-gray-900 uppercase italic mb-2">Hapus Pesanan?</h3>
                <p class="text-gray-500 text-sm mb-8">Data yang sudah dihapus tidak bisa dikembalikan lagi, lho.</p>
                
                <div class="flex gap-3">
                    <button @click="openDeleteModal = false" class="flex-1 py-3 bg-gray-100 text-gray-500 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-gray-200 transition-all">Batal</button>
                    <form :action="deleteUrl" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-3 bg-rose-500 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-rose-600 shadow-lg shadow-rose-200 transition-all">Ya, Hapus!</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>