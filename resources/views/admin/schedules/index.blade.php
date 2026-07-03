<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-pink-700 leading-tight tracking-tighter">
                {{ __('Manajemen Jadwal & Booking Offline') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="flex items-center bg-white text-gray-700 px-4 py-3 rounded-xl shadow-sm border border-gray-200 hover:text-pink-600 transition-all active:scale-95 font-bold text-sm">
                <i class="fa-solid fa-arrow-left mr-1"></i> Back
            </a>
        </div>
    </x-slot>

    <style>
        /* CSS Hapus Spinner Input Angka */
        input::-webkit-outer-spin-button, input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number] { -moz-appearance: textfield; }
        input[type=number]:focus { scroll-behavior: contain; }

        /* Grid Form - Responsive Layout */
        .grid-form-parent { display: grid; grid-template-columns: repeat(6, 1fr); gap: 1.25rem; }
        .div1 { grid-column: span 6; }
        .div2 { grid-column: span 6; }
        .div3 { grid-column: span 6; }
        .div4 { grid-column: span 6; }
        .div5 { grid-column: span 6; }
        .div6 { grid-column: span 6; }
        .div7 { grid-column: span 6; }
        .div8 { grid-column: span 6; }
        .div9 { grid-column: span 6; }

        /* Responsivitas Grid untuk Layar Tablet/Laptop (min-width: 768px) */
        @media (min-width: 768px) {
            .div1 { grid-column: span 3; }
            .div2 { grid-column: span 3; }
            .div3 { grid-column: span 4; }
            .div4 { grid-column: span 2; }
            .div5 { grid-column: span 3; }
            .div6 { grid-column: span 3; }
        }
        
        .select-wrapper { position: relative; width: 100%; display: flex; align-items: center; }
        .select-wrapper::after { content: '▼'; font-size: 10px; position: absolute; right: 15px; pointer-events: none; color: #4b5563; }
        select { -webkit-appearance: none; appearance: none; background: #f9fafb; border: 1px solid #d1d5db; width: 100%; cursor: pointer; border-radius: 1rem; }
        
        .day-btn { aspect-ratio: 1 / 1; display: flex; align-items: center; justify-content: center; font-size: 0.875rem; border-radius: 1rem; font-weight: 800; transition: all 0.3s; border: none; }
        .holiday-active { background-color: #e11d48 !important; color: white !important; }
        .before-today { opacity: 0.25; cursor: not-allowed; background-color: #f3f4f6; color: #9ca3af; }
        .booking-partial { background-color: #d97706 !important; color: white !important; }
        .active-date { background-color: #db2777 !important; color: white !important; ring: 4px; ring-color: rgba(219, 39, 119, 0.4); }
        
        /* Icon Jam */
        input[type="time"]::-webkit-calendar-picker-indicator { filter: invert(0); cursor: pointer; transform: scale(1.3); }
        
        /* Layout Ringkasan Biaya */
        .summary-parent { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; }
        .s-div1 { grid-column: span 2; } .s-div2 { grid-column: span 1; } .s-div3 { grid-column: span 1; }
        .s-div4 { grid-column: span 2; } .s-div5 { grid-column: span 2; }
        @media (min-width: 640px) {
            .s-div4 { grid-column: span 1; } .s-div5 { grid-column: span 1; }
        }
    </style>

    <div class="py-12 bg-[#FDF8F8] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('admin.schedules.manual') }}" method="POST" id="bookingForm" class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">
                @csrf
                <input type="hidden" name="booking_date" id="final_date">
                <input type="hidden" name="start_time" id="final_time">

                <!-- Panel Kiri: Input Data Pelanggan -->
                <div class="lg:col-span-7 bg-white p-6 sm:p-8 rounded-[2rem] shadow-md border border-pink-100">
                    <h1 class="text-xl font-bold text-gray-900 tracking-tight mb-6 uppercase border-b border-gray-100 pb-4">Input Data Pelanggan</h1>
                    
                    <div class="grid-form-parent">
                        <div class="div1">
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2 ml-1">Nama Lengkap</label>
                            <input type="text" name="customer_name" placeholder="Nama Client" class="w-full bg-gray-50 border border-gray-300 rounded-2xl p-3.5 outline-none focus:ring-2 focus:ring-pink-500 font-semibold text-sm text-gray-900 transition-all" required>
                        </div>
                        <div class="div2">
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2 ml-1">Nomor WhatsApp</label>
                            <input type="number" name="whatsapp_number" placeholder="08xxxxxxxx" class="w-full bg-gray-50 border border-gray-300 rounded-2xl p-3.5 outline-none focus:ring-2 focus:ring-pink-500 font-semibold text-sm text-gray-900 transition-all" required>
                        </div>
                        <div class="div3">
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2 ml-1">Alamat Lengkap</label>
                            <input type="text" name="address" placeholder="Contoh: Jl. Pancasakti No. 1, Tegal" class="w-full bg-gray-50 border border-gray-300 rounded-2xl p-3.5 outline-none focus:ring-2 focus:ring-pink-500 font-semibold text-sm text-gray-900 transition-all" required>
                        </div>
                        <div class="div4">
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2 ml-1 text-center">Jml Orang (Max 2)</label>
                            <input type="number" name="person_count" id="person_count" value="1" min="1" max="2" oninput="updateTotal()" class="w-full border border-gray-300 bg-gray-50 rounded-2xl p-3.5 outline-none focus:ring-2 focus:ring-pink-500 font-bold text-center text-sm text-gray-900">
                        </div>
                        <div class="div5">
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2 ml-1">Layanan Rias</label>
                            <div class="select-wrapper">
                                <select name="category_id" id="category_select" onchange="updateTotal()" class="p-3.5 border border-gray-300 rounded-2xl text-sm font-bold text-gray-900 outline-none">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" data-price="{{ $category->base_price }}" data-duration="{{ $category->duration_minutes }}">{{ $category->name }} ({{ number_format($category->duration_minutes, 0, ',', '.') }} menit)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="div6">
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-2 ml-1">Lokasi Rias</label>
                            <div class="select-wrapper">
                                <select name="location_id" id="location_select" onchange="updateTotal()" class="p-3.5 border border-gray-300 rounded-2xl text-sm font-bold text-gray-900 outline-none">
                                    <option value="0" data-price="0">Datang ke Toko (+0)</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}" data-price="{{ $location->additional_price }}">{{ $location->region_name }} (+Rp{{ number_format($location->additional_price, 0, ',', '.') }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <!-- REVISI KETERBACAAN & PADDING MOBILE VIEW PADA RINGKASAN BIAYA -->
                        <div class="div7 pt-2">
                            <div class="bg-gray-50 rounded-2xl p-5 sm:p-6 text-gray-900 border-2 border-gray-200 shadow-sm relative overflow-hidden">
                                <div class="summary-parent">
                                    <div class="s-div1 border-b border-gray-200 pb-3 mb-1">
                                        <span class="text-sm font-bold text-pink-700 uppercase tracking-wider">Ringkasan Biaya</span>
                                    </div>
                                    
                                    <div class="s-div2 flex flex-col gap-2.5 text-sm font-semibold text-gray-700 uppercase">
                                        <p>Subtotal</p>
                                        <p>Jumlah Orang</p>
                                        <p class="border-t border-gray-200 pt-2">Biaya Transport (Ongkir)</p>
                                        <p class="text-base font-bold text-gray-900 pt-2">Total Bayar</p>
                                    </div>
                                    
                                    <div class="s-div3 flex flex-col gap-2.5 text-sm font-bold text-right text-gray-900">
                                        <p id="display_subtotal">Rp0</p>
                                        <p id="display_multiplier" class="text-pink-700">X1</p>
                                        <p id="display_ongkir" class="text-pink-700 border-t border-gray-200 pt-2">Rp0</p>
                                        <p class="text-xl text-pink-700 font-extrabold pt-1">Rp<span id="display_total">0</span></p>
                                    </div>
                                    
                                    <div class="s-div4 pt-4">
                                        <label class="block mb-2 font-bold text-xs uppercase text-gray-700">Metode Pembayaran</label>
                                        <select name="payment_method" id="payMethod" onchange="togglePaymentInput()" class="p-3 bg-white border border-gray-300 text-sm font-bold text-gray-900 outline-none rounded-xl w-full">
                                            <option value="cash">Tunai (Cash)</option>
                                            <option value="qris">QRIS / Transfer</option>
                                        </select>
                                    </div>
                                    
                                    <div class="s-div5 pt-4" id="payment_input_area"></div>
                                </div>
                            </div>
                        </div>

                        <div class="div8">
                            <button type="submit" id="btnConfirm" class="w-full bg-emerald-600 text-white font-bold py-4 rounded-2xl hover:bg-emerald-700 transition-all shadow-md active:scale-95 uppercase text-sm tracking-wider">Konfirmasi & Simpan Jadwal →</button>
                        </div>
                        <div class="div9">
                            <button type="button" id="btnHoliday" onclick="toggleHoliday()" class="w-full hidden bg-rose-50 text-rose-600 border border-rose-200 font-bold py-3.5 rounded-2xl hover:bg-rose-600 hover:text-white transition-all text-xs uppercase tracking-wide"><span id="holidayText">Liburkan Hari Ini</span></button>
                        </div>
                    </div>
                </div>

                <!-- Panel Kanan: Kalender & Jam -->
                <div class="lg:col-span-5 flex flex-col gap-6">
                    <div class="bg-white p-6 rounded-[2rem] shadow-md border border-pink-50">
                        <div class="flex justify-between items-center mb-6">
                            <button type="button" id="prevMonth" class="bg-pink-50 text-pink-700 px-3 py-2 rounded-xl font-bold text-xs hidden" onclick="changeMonth(-1)">&larr; Back</button>
                            <h3 id="calTitle" class="text-lg font-bold uppercase text-gray-900 tracking-tight">Bulan 2026</h3>
                            <button type="button" id="nextMonth" class="bg-pink-50 text-pink-700 px-4 py-2 rounded-xl font-bold text-xs uppercase active:scale-90" onclick="changeMonth(1)">Next →</button>
                        </div>
                        <div class="grid grid-cols-7 gap-2 text-center mb-3 border-b border-gray-100 pb-2">
                            @foreach(['S','S','R','K','J','S','M'] as $h) <div class="font-bold text-gray-400 text-xs">{{$h}}</div> @endforeach
                        </div>
                        <div id="calGridContent" class="grid grid-cols-7 gap-2 text-center"></div>
                    </div>

                    <div id="timeSection" class="bg-slate-900 p-6 sm:p-8 rounded-[2rem] text-white hidden shadow-md text-center transition-all">
                         <p id="dateDisplay" class="text-pink-400 font-bold text-xs uppercase mb-2 tracking-wider">-</p>
                         <h4 class="text-xl font-bold mb-6 uppercase text-white">Atur Jam Rias</h4>
                         <input type="time" id="timeValue" class="w-full bg-white/10 border border-white/20 rounded-2xl p-4 text-3xl font-bold text-center outline-none focus:ring-4 focus:ring-pink-500 transition-all text-white">
                         
                         <div id="bookedTable" class="hidden mt-6 bg-white/5 rounded-xl p-4 border border-white/10 text-left text-xs">
                            <p class="text-pink-400 font-bold uppercase mb-3 tracking-wider underline underline-offset-4">Slot Terisi:</p>
                            <table class="w-full text-gray-200">
                                <thead><tr class="text-gray-400 uppercase font-bold border-b border-white/10"> <th class="pb-1 text-left">No</th> <th class="pb-1 text-left">Mulai</th> <th class="pb-1 text-right">Selesai</th> </tr></thead>
                                <tbody id="bookedList" class="divide-y divide-white/5"></tbody>
                            </table>
                         </div>
                    </div>
                </div>
            </form>

            <!-- REVISI TOTAL: AGENDA OFFLINE TERBARU (RESPONSIVE CARDS UNTUK MOBILE) -->
            <div class="bg-white rounded-[2rem] shadow-md border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <h2 class="font-bold text-gray-900 uppercase tracking-wider text-sm">Agenda Offline Terbaru</h2>
                </div>

                <!-- TAMPILAN 1: TAMPIL DI HP (CARD MINIMALIS KONTRAST TINGGI) -->
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

                <!-- TAMPILAN 2: HANYA TAMPIL DI LAPTOP/TABLET (TABEL HORIZONTAL SEBELUMNYA) -->
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
        </div>
    </div>

    <!-- Modals (Status & Confirmation) -->
    <div id="statusModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm transition-all duration-300">
        <div id="statusModalContent" class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl transform scale-90 opacity-0 transition-all duration-300 border-2 border-gray-200 text-center">
            <div id="statusIconBox" class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <i id="statusIcon" class="text-2xl fa-solid"></i>
            </div>
            <h3 id="statusTitle" class="text-xl font-bold text-gray-900 uppercase mb-2">Status</h3>
            <p id="statusDescription" class="text-sm text-gray-600 font-semibold mb-6 leading-relaxed"></p>
            <button id="statusBtn" type="button" onclick="closeStatusModal()" class="w-full bg-slate-900 text-white font-bold py-3.5 rounded-xl hover:opacity-90 transition-all uppercase tracking-wider text-xs">Tutup</button>
        </div>
    </div>

    <div id="confirmModal" class="fixed inset-0 z-[110] hidden items-center justify-center p-4 bg-slate-900/90 backdrop-blur-md transition-all duration-300">
        <div id="confirmModalContent" class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl transform scale-90 opacity-0 transition-all duration-300 border-2 border-gray-100 text-center">
            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-circle-question text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 uppercase mb-2">Konfirmasi</h3>
            <p id="confirmDescription" class="text-sm text-gray-600 font-semibold mb-6 leading-relaxed"></p>
            <div class="flex gap-3">
                <button type="button" onclick="closeConfirmModal()" class="flex-1 bg-gray-100 text-gray-500 font-bold py-3.5 rounded-xl hover:bg-gray-200 transition-all uppercase tracking-wider text-xs">Batal</button>
                <button id="confirmOkBtn" type="button" class="flex-1 bg-slate-900 text-white font-bold py-3.5 rounded-xl hover:bg-blue-600 transition-all uppercase tracking-wider text-xs">Konfirmasi</button>
            </div>
        </div>
    </div>

    <!-- Scripts JavaScript -->
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script>
        const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        const initMonth = new Date().getMonth(), initYear = 2026;
        let curMonth = initMonth, curYear = initYear, dayBookings = [], totalVal = 0, isHoliday = false;

        function showStatusModal(msg, isError = true) {
            const modal = document.getElementById('statusModal');
            const content = document.getElementById('statusModalContent');
            const iconBox = document.getElementById('statusIconBox');
            const icon = document.getElementById('statusIcon');
            const title = document.getElementById('statusTitle');
            const desc = document.getElementById('statusDescription');
            const btn = document.getElementById('statusBtn');

            desc.innerHTML = msg;
            if (isError) {
                content.classList.remove('border-emerald-200'); content.classList.add('border-rose-200');
                iconBox.className = "w-16 h-16 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-4";
                icon.className = "fa-solid fa-calendar-xmark text-2xl";
                title.innerText = "Peringatan!";
                title.className = "text-xl font-bold text-gray-900 uppercase mb-2";
                btn.classList.remove('hidden');
            } else {
                content.classList.remove('border-rose-200'); content.classList.add('border-emerald-200');
                iconBox.className = "w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4";
                icon.className = "fa-solid fa-calendar-check text-2xl";
                title.innerText = "Berhasil!";
                title.className = "text-xl font-bold text-emerald-700 uppercase mb-2";
                btn.classList.add('hidden');
            }
            modal.classList.remove('hidden'); modal.classList.add('flex');
            setTimeout(() => { content.classList.replace('scale-90', 'scale-100'); content.classList.replace('opacity-0', 'opacity-100'); }, 10);
        }

        function closeStatusModal() {
            const modal = document.getElementById('statusModal');
            const content = document.getElementById('statusModalContent');
            content.classList.replace('scale-100', 'scale-90'); content.classList.replace('opacity-100', 'opacity-0');
            setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 300);
        }

        let holidayCallback = null;
        function showConfirmModal(msg, callback) {
            document.getElementById('confirmDescription').innerHTML = msg;
            holidayCallback = callback;
            const modal = document.getElementById('confirmModal');
            const content = document.getElementById('confirmModalContent');
            modal.classList.remove('hidden'); modal.classList.add('flex');
            setTimeout(() => { content.classList.replace('scale-90', 'scale-100'); content.classList.replace('opacity-0', 'opacity-100'); }, 10);
        }

        function closeConfirmModal() {
            const modal = document.getElementById('confirmModal');
            const content = document.getElementById('confirmModalContent');
            content.classList.replace('scale-100', 'scale-90'); content.classList.replace('opacity-100', 'opacity-0');
            setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 300);
        }

        document.getElementById('confirmOkBtn').onclick = function() { if (holidayCallback) holidayCallback(); closeConfirmModal(); };

        async function toggleHoliday() {
            const date = document.getElementById('final_date').value;
            if(!date) { showStatusModal("Pilih tanggal di kalender terlebih dahulu!"); return; }
            const actionText = isHoliday ? "Membatalkan Libur" : "Meliburkan Jadwal";
            const msg = `Apakah Anda yakin ingin <strong>${actionText}</strong> untuk tanggal <strong>${date}</strong>?`;
            showConfirmModal(msg, async () => {
                try {
                    const res = await fetch("{{ route('admin.schedules.toggle_holiday') }}", {
                        method: 'POST', 
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' },
                        body: JSON.stringify({ date: date })
                    });
                    const data = await res.json();
                    if(data.error) { showStatusModal(data.error); } 
                    else {
                        showStatusModal(isHoliday ? "Hari libur berhasil dihapus!" : "Berhasil meliburkan jadwal!", false);
                        setTimeout(() => location.reload(), 1500);
                    }
                } catch (err) { showStatusModal("Terjadi kesalahan sistem saat memproses libur."); }
            });
        }

        function updateTotal() {
            const sOpt = document.getElementById('category_select').selectedOptions[0];
            const lOpt = document.getElementById('location_select').selectedOptions[0];
            let persons = parseInt(document.getElementById('person_count').value) || 1;
            
            if (persons > 2) { 
                showStatusModal("Maksimal pemesanan offline adalah 2 orang."); 
                document.getElementById('person_count').value = 2; 
                persons = 2; 
            }
            
            const sPrice = parseInt(sOpt.dataset.price) || 0;
            const lPrice = parseInt(lOpt.dataset.price) || 0;
            totalVal = (sPrice * persons) + lPrice;
            
            document.getElementById('display_subtotal').innerText = 'Rp' + new Intl.NumberFormat('id-ID').format(sPrice);
            document.getElementById('display_multiplier').innerText = 'X' + persons;
            document.getElementById('display_ongkir').innerText = 'Rp' + new Intl.NumberFormat('id-ID').format(lPrice);
            document.getElementById('display_total').innerText = new Intl.NumberFormat('id-ID').format(totalVal);
            togglePaymentInput();
        }

        function togglePaymentInput() {
            const method = document.getElementById('payMethod').value, area = document.getElementById('payment_input_area');
            if (method === 'cash') { 
                area.innerHTML = `<label class="block text-xs font-bold text-gray-700 uppercase mb-2 ml-1">Nominal Tunai</label>
                                  <input type="number" name="dp_amount" id="dpInput" placeholder="Masukkan Nominal" class="w-full bg-white border border-gray-300 rounded-xl p-3 text-sm text-gray-900 outline-none focus:ring-2 focus:ring-pink-500">`; 
            } else { 
                area.innerHTML = `<label class="block text-xs font-bold text-gray-700 uppercase mb-2 ml-1">Pilihan Nominal Bayar</label>
                                  <div class="select-wrapper"><select name="dp_amount" id="dpInput" class="p-3 bg-white border border-gray-300 text-gray-900 rounded-xl text-xs font-bold outline-none w-full">
                                      <option value="${totalVal/2}">DP 50% (Rp${new Intl.NumberFormat('id-ID').format(totalVal/2)})</option>
                                      <option value="${totalVal}">Lunas (Rp${new Intl.NumberFormat('id-ID').format(totalVal)})</option>
                                  </select></div>`; 
            }
        }

        async function renderCalendar() {
            const grid = document.getElementById('calGridContent'), label = document.getElementById('calTitle'), prevBtn = document.getElementById('prevMonth');
            label.innerText = `${monthNames[curMonth]} ${curYear}`;
            prevBtn.style.display = (curYear > initYear || (curYear === initYear && curMonth > initMonth)) ? 'block' : 'none';
            const today = new Date(); today.setHours(0,0,0,0);
            try {
                const res = await fetch(`/api/check-availability?month=${curMonth + 1}&year=${curYear}`);
                const availability = await res.json();
                const daysInMonth = new Date(curYear, curMonth + 1, 0).getDate();
                grid.innerHTML = '';
                for (let i = 1; i <= daysInMonth; i++) {
                    const btn = document.createElement('button'); btn.type = "button"; btn.innerText = i;
                    btn.className = "day-btn p-3 bg-gray-50 text-gray-800 transition-all font-bold";
                    const dateToCheck = new Date(curYear, curMonth, i);
                    if (dateToCheck < today) { btn.disabled = true; btn.classList.add('before-today'); } 
                    else {
                        if (availability[i]) {
                            if (availability[i].status === 'holiday' || availability[i].status === 'full') { btn.classList.add('holiday-active'); } 
                            else { btn.classList.add('booking-partial'); }
                        }
                        btn.onclick = (e) => selectDate(i, availability[i]?.details || [], e.target, availability[i]?.status === 'holiday');
                    }
                    grid.appendChild(btn);
                }
            } catch (err) { console.error(err); }
        }

        function selectDate(day, details, el, holidayStatus) {
            dayBookings = details; isHoliday = holidayStatus;
            document.getElementById('final_date').value = `${curYear}-${(curMonth + 1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
            document.getElementById('dateDisplay').innerText = `${day} ${monthNames[curMonth]} ${curYear}`;
            document.getElementById('btnHoliday').classList.remove('hidden');
            document.getElementById('holidayText').innerText = isHoliday ? "Batalkan Hari Libur" : "Liburkan Hari Ini";
            
            const btnConfirm = document.getElementById('btnConfirm'), timeArea = document.getElementById('timeSection');
            if(isHoliday) {
                btnConfirm.disabled = true; btnConfirm.classList.replace('bg-emerald-600', 'bg-gray-200'); timeArea.classList.add('hidden');
            } else {
                btnConfirm.disabled = false; btnConfirm.classList.replace('bg-gray-200', 'bg-emerald-600'); timeArea.classList.remove('hidden');
            }
            const list = document.getElementById('bookedList'), table = document.getElementById('bookedTable');
            list.innerHTML = '';
            if (details.length > 0) { 
                table.classList.remove('hidden'); 
                details.forEach((d, idx) => { list.innerHTML += `<tr class="border-b border-white/5"><td class="py-2 font-medium">${idx+1}</td><td class="py-2 font-bold">${d.start}</td><td class="py-2 text-right text-pink-400 font-bold">${d.end}</td></tr>`; }); 
            } else { table.classList.add('hidden'); }
            document.querySelectorAll('.day-btn').forEach(b => b.classList.remove('active-date')); if(!isHoliday) el.classList.add('active-date');
        }

        document.getElementById('bookingForm').onsubmit = async function(e) {
            e.preventDefault();
            const timeVal = document.getElementById('timeValue').value, dateVal = document.getElementById('final_date').value;
            const method = document.getElementById('payMethod').value, nominal = document.getElementById('dpInput')?.value;

            if(!dateVal || !timeVal) { showStatusModal("Harap pilih TANGGAL dan JAM rias!"); return; }
            if(method === 'cash' && (!nominal || nominal <= 0)) { showStatusModal("Nominal Tunai wajib diisi!"); return; }

            const btn = document.getElementById('btnConfirm');
            const originalText = "Konfirmasi & Simpan Jadwal →";
            btn.disabled = true; btn.innerText = "Memproses...";

            const formData = new FormData(this);
            formData.set('booking_date', dateVal); formData.set('start_time', timeVal);

            if (method === 'qris') {
                try {
                    const res = await fetch("{{ route('admin.schedules.prepare') }}", { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    const result = await res.json();
                    if (result.status === 'midtrans') {
                        window.snap.pay(result.snap_token, {
                            onSuccess: async function(r){ formData.append('order_id', result.order_id); await saveData(formData); },
                            onPending: async function(r){ formData.append('order_id', result.order_id); await saveData(formData); },
                            onError: function(r){ showStatusModal("Pembayaran Gagal."); resetBtn(btn, originalText); },
                            onClose: function(){ resetBtn(btn, originalText); }
                        });
                    } else { showStatusModal(result.message || "Gagal mengambil token."); resetBtn(btn, originalText); }
                } catch (err) { showStatusModal("Gagal terhubung ke Midtrans."); resetBtn(btn, originalText); }
            } else { await saveData(formData); }
        };

        async function saveData(formData) {
            const btn = document.getElementById('btnConfirm');
            try {
                const response = await fetch("{{ route('admin.schedules.manual') }}", { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const result = await response.json();
                if (result.status === 'success' || response.ok) {
                    showStatusModal("Jadwal rias offline telah berhasil disimpan ke agenda.", false);
                    setTimeout(() => location.reload(), 2000);
                } else { showStatusModal(result.message || "Gagal menyimpan."); resetBtn(btn, "Konfirmasi & Simpan Jadwal →"); }
            } catch (err) { showStatusModal("Koneksi bermasalah."); resetBtn(btn, "Konfirmasi & Simpan Jadwal →"); }
        }

        document.addEventListener('wheel', function(event) { if (document.activeElement.type === 'number') document.activeElement.blur(); });
        function resetBtn(btn, text) { btn.disabled = false; btn.innerText = text; }
        function changeMonth(step) { curMonth += step; if(curMonth > 11) { curMonth = 0; curYear++; } else if(curMonth < 0) { curMonth = 11; curYear--; } renderCalendar(); }
        
        updateTotal(); renderCalendar();
    </script>
</x-app-layout>