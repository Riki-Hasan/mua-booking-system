<x-app-layout>
    <style>
        [x-cloak] { display: none !important; }
        .back-btn-content { display: flex; align-items: center; gap: 0.5rem; }
        
        /* Animasi pop disederhanakan agar tidak tertahan di opacity 0 saat diakses browser tablet jadul */
        .animate-pop { animation: pop 0.2s ease-out; }
        @keyframes pop { 0% { transform: scale(0.95); } 100% { transform: scale(1); } }
        
        @media (max-width: 768px) {
            .mobile-card-grid { display: block; }
            .desktop-table { display: none; }
            .back-btn-content { flex-direction: column; gap: 0.1rem; }
        }
    </style>

    <x-slot name="header">
        <!-- Menggunakan justify-between agar PDF di kiri dan Back di kanan, rapi dan tidak saling mepet -->
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex justify-between items-center w-full md:w-auto gap-4">
                <h2 class="font-bold text-xl md:text-2xl text-pink-600 leading-tight italic">
                    Verifikasi Pesanan
                </h2>
            </div>

            <div class="flex items-center justify-between gap-3 w-full md:w-auto">
                <!-- Tombol PDF diperlebar menyamping (px-8) agar bentuknya proporsional dan gagah -->
                <a href="{{ route('admin.orders.report') }}" class="bg-gray-900 text-white text-[10px] font-black px-8 py-3 rounded-xl hover:bg-pink-600 transition-all uppercase tracking-widest shadow-md text-center">
                    ⬇️ Download PDF
                </a>

                <div class="">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 bg-white text-gray-500 px-5 py-3 rounded-xl shadow-sm border border-gray-100 hover:text-pink-600 transition-all active:scale-95">
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                        <span class="text-[10px] font-black uppercase tracking-widest">Back</span>
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <!-- AREA HALAMAN UTAMA -->
    <div class="py-6 md:py-12 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

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
                            
                            $message = "Halo Kak *" . $order->customer_name . "*! ✨ Kabar gembira nih, pesanan rias Kakak untuk *" . $order->person_count . " orang* sudah dikonfirmasi ya. 😍\n\nRencananya kita ketemu di:\n📍 *Alamat:* " . $order->address . "\n📅 *Tanggal:* " . $tglIndo . "\n⏰ *Jam:* " . $order->start_time . " WIB\n\nStatus: *" . $statusIndo . "* ✅\n\nSampai ketemu di hari H ya Kak! Jika ada pertanyaan langsung balas chat ini saja. Terima kasih! ❤️";
                            $waUrl = "https://wa.me/" . $phone . "?text=" . urlencode($message);
                            
                            // Mengamankan penulisan nama pelanggan dari resiko error tanda petik di javascript
                            $safeName = addslashes($order->customer_name);
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
                                    <!-- Ukuran teks badge DP 50% diperbesar agar sangat terbaca -->
                                    <span class="px-4 py-1.5 bg-blue-100 text-blue-600 rounded-full text-xs font-black tracking-wide">DP 50%</span>
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
                                    <!-- Membuka modal konfirmasi dengan parameter Javascript murni bebas konflik -->
                                    <button type="button" onclick="bukaModalHapus('{{ $safeName }}', '{{ route('admin.orders.destroy', $order->id) }}')" class="p-3 bg-rose-50 text-rose-400 rounded-xl hover:bg-rose-500 hover:text-white transition-all shadow-sm shadow-rose-100">
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
                @php
                    $safeNameMobile = addslashes($order->customer_name);
                @endphp
                <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-pink-50">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <div class="font-black text-gray-900 text-xl">{{ $order->customer_name }}</div>
                            <div class="text-pink-600 font-extrabold text-xs uppercase tracking-widest mt-1">{{ $order->whatsapp_number }}</div>
                        </div>
                        @if($order->status == 'paid_full')
                            <span class="text-[9px] font-black bg-emerald-100 text-emerald-600 px-2 py-1 rounded-lg">LUNAS</span>
                        @else
                            <!-- Badge DP 50% diperbesar juga pada visual Mobile Card -->
                            <span class="text-[10px] font-black bg-blue-100 text-blue-600 px-3 py-1.5 rounded-lg tracking-wide">DP 50%</span>
                        @endif
                    </div>
                    <div class="space-y-2 mb-6">
                        <div class="flex items-center text-sm font-black text-gray-800">
                            <i class="fa-solid fa-wand-sparkles mr-2 text-pink-400"></i> 
                            {{ $order->category->name ?? $order->bundling->subject ?? 'Paket/Promo' }} ({{ $order->person_count }} Org)
                        </div>
                        <div class="flex items-center text-sm font-bold text-gray-600">
                            <i class="fa-solid fa-calendar-day mr-2 text-pink-400"></i> 
                            {{ \Carbon\Carbon::parse($order->booking_date)->translatedFormat('d F Y') }}
                        </div>
                        <div class="flex items-center text-sm font-bold text-gray-600">
                            <i class="fa-solid fa-clock mr-2 text-pink-400"></i> 
                            {{ $order->start_time }} WIB
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <a href="{{ $waUrl }}" class="bg-emerald-500 text-white p-3 rounded-xl text-center"><i class="fa-brands fa-whatsapp"></i></a>
                        <a href="{{ route('booking.receipt', $order->id) }}" class="bg-gray-900 text-white p-3 rounded-xl text-center"><i class="fa-solid fa-file-invoice"></i></a>
                        <button type="button" onclick="bukaModalHapus('{{ $safeNameMobile }}', '{{ route('admin.orders.destroy', $order->id) }}')" class="bg-rose-100 text-rose-500 p-3 rounded-xl text-center">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>


    <!-- ======================================================== -->
    <!-- 💥 AREA LUAR (DIJAMIN BEBAS DARI KONFLIK RENDERING TABLET) -->
    <!-- ======================================================== -->

    <!-- MODAL KONFIRMASI HAPUS (LAYOUT DAN STYLE DIJAMIN SAMA PERSIS SEPERTI SEBELUMNYA) -->
    <!-- Modifikasi: Mengganti bg-slate-900/60 & backdrop-blur ke standar RGBA murni agar didukung tablet -->
    <div id="modalKonfirmasiHapusLuar" 
         class="hidden"
         style="display: none; position: fixed !important; top: 0 !important; left: 0 !important; right: 0 !important; bottom: 0 !important; width: 100% !important; height: 100% !important; z-index: 2147483647 !important; background-color: rgba(15, 23, 42, 0.8) !important; align-items: center; justify-content: center; padding: 16px;">
        
        <!-- Seluruh struktur tata letak kotak modal, border pink tipis, dan tombol dipertahankan utuh -->
        <div class="bg-white p-8 rounded-[2.5rem] shadow-2xl max-w-sm w-full mx-4 text-center border-4 border-rose-50 animate-pop">
            <div class="w-16 h-16 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
            </div>
            <h3 class="text-xl font-black text-gray-900 uppercase italic mb-2">Hapus Pesanan?</h3>
            <p class="text-gray-500 text-sm mb-8 leading-relaxed">
                Yakin ingin menghapus data milik <span id="namaCustomerHapusLuar" class="text-rose-500 font-bold"></span>?
            </p>
            
            <div class="flex gap-3">
                <button type="button" onclick="tutupModalHapus()" class="flex-1 py-4 bg-gray-100 text-gray-500 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-gray-200 transition-all">Batal</button>
                <button type="button" onclick="kirimFormHapus()" class="flex-1 py-4 bg-rose-500 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-rose-600 shadow-lg shadow-rose-200 transition-all">Ya, Hapus!</button>
            </div>
        </div>
    </div>

    <!-- FORM PENGIRIMAN DATA KE SERVER LAPTOP -->
    <form id="formHapusLuar" action="" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <!-- MODAL STATUS TERHAPUS (BERHASIL) -->
    <!-- Modifikasi: Mengganti bg-slate-900/40 ke standar RGBA murni agar menyembul lancar setelah reload -->
    @if(session('success'))
    <div id="modalStatusSuksesLuar" 
         style="position: fixed !important; top: 0 !important; left: 0 !important; right: 0 !important; bottom: 0 !important; width: 100% !important; height: 100% !important; z-index: 2147483647 !important; background-color: rgba(15, 23, 42, 0.6) !important; display: flex !important; align-items: center; justify-content: center; padding: 16px;">
        
        <div class="bg-white rounded-[2.5rem] max-w-sm w-full p-10 shadow-2xl text-center border-4 border-emerald-50 animate-pop">
            <div class="w-20 h-20 bg-emerald-100 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fa-solid fa-check-double text-3xl"></i>
            </div>
            <h3 class="text-2xl font-black text-gray-900 uppercase italic tracking-tighter mb-2">Terhapus!</h3>
            <p class="text-sm text-gray-500 mb-8 leading-relaxed">
                {{ session('success') }}
            </p>
            <button type="button" onclick="tutupModalSukses()" 
                    class="w-full bg-slate-900 text-white font-black py-4 rounded-2xl uppercase text-[10px] tracking-[0.2em] hover:bg-pink-600 transition-all shadow-lg active:scale-95">
                Oke, Mantap
            </button>
        </div>
    </div>
    @endif


    <!-- ======================================================== -->
    <!-- JAVASCRIPT KONTROL UTAMA (NATIVE VANILLA JS)              -->
    <!-- ======================================================== -->
    <script>
        // Membuka modal konfirmasi hapus
        function bukaModalHapus(customerName, deleteUrl) {
            var form = document.getElementById('formHapusLuar');
            var labelNama = document.getElementById('namaCustomerHapusLuar');
            var modal = document.getElementById('modalKonfirmasiHapusLuar');

            if (form) {
                form.action = deleteUrl;
            }

            if (labelNama && modal) {
                labelNama.innerText = customerName;
                
                // Memicu modal tampil tanpa bantuan event listener Alpine yang rawan crash di tablet
                modal.classList.remove('hidden');
                modal.style.setProperty('display', 'flex', 'important');
            }
        }

        // Menutup modal konfirmasi (Batal)
        function tutupModalHapus() {
            var modal = document.getElementById('modalKonfirmasiHapusLuar');
            if (modal) {
                modal.classList.add('hidden');
                modal.style.display = 'none';
            }
        }

        // Mengirimkan form delete sesungguhnya ke database laptop
        function kirimFormHapus() {
            var form = document.getElementById('formHapusLuar');
            if (form) {
                form.submit();
            }
        }

        // Menutup modal sukses terhapus setelah reload data
        function tutupModalSukses() {
            var modal = document.getElementById('modalStatusSuksesLuar');
            if (modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</x-app-layout>