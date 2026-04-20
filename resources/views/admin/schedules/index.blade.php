<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-pink-600 leading-tight tracking-tighter">
                {{ __('Manajemen Jadwal & Booking Offline') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="bg-gray-100 text-gray-700 px-6 py-2 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-pink-100 transition-all active:scale-95">
                <i class="fa-solid fa-arrow-left mr-1"></i> Back
            </a>
        </div>
    </x-slot>

    <style>
        /* CSS Hapus Spinner Input Angka */
        input::-webkit-outer-spin-button, input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number] { -moz-appearance: textfield; }
        input[type=number]:focus {
            scroll-behavior: contain;
        }

        /* Grid Form 6x6 Sesuai Desain */
        .grid-form-parent { display: grid; grid-template-columns: repeat(6, 1fr); grid-template-rows: repeat(6, auto); gap: 1.5rem; }
        .div1 { grid-area: 1 / 1 / 2 / 4; } .div2 { grid-area: 1 / 4 / 2 / 7; } .div3 { grid-area: 2 / 1 / 3 / 5; }
        .div4 { grid-area: 2 / 5 / 3 / 7; } .div5 { grid-area: 3 / 1 / 4 / 4; } .div6 { grid-area: 3 / 4 / 4 / 7; }
        .div7 { grid-area: 4 / 1 / 5 / 7; } .div8 { grid-area: 5 / 1 / 6 / 7; } .div9 { grid-area: 6 / 1 / 7 / 7; }
        
        .select-wrapper { position: relative; width: 100%; display: flex; align-items: center; }
        .select-wrapper::after { content: '▼'; font-size: 8px; position: absolute; right: 15px; pointer-events: none; color: #9ca3af; }
        select { -webkit-appearance: none; appearance: none; background: #f9fafb; border: 1px solid #f3f4f6; width: 100%; cursor: pointer; border-radius: 1rem; }
        
        .day-btn { aspect-ratio: 1 / 1; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; border-radius: 1rem; font-weight: 800; transition: all 0.3s; border: none; }
        .holiday-active { background-color: #e11d48 !important; color: white !important; }
        .before-today { opacity: 0.2; cursor: not-allowed; background-color: #f3f4f6; color: #9ca3af; }
        .booking-partial { background-color: #fbbf24 !important; color: white !important; }
        .active-date { background-color: #ec4899 !important; color: white !important; ring: 4px; ring-color: rgba(236, 72, 153, 0.3); }
        
        /* Icon Jam Putih */
        input[type="time"]::-webkit-calendar-picker-indicator { filter: invert(1); cursor: pointer; transform: scale(1.5); }
        
        .summary-parent { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; }
        .s-div1 { grid-area: 1 / 1 / 2 / 3; } .s-div2 { grid-area: 2 / 1 / 3 / 2; } .s-div3 { grid-area: 2 / 2 / 3 / 3; }
        .s-div4 { grid-area: 3 / 1 / 4 / 2; } .s-div5 { grid-area: 3 / 2 / 4 / 3; }
    </style>

    <div class="py-12 bg-[#FDF8F8] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('admin.schedules.manual') }}" method="POST" id="bookingForm" class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-12">
                @csrf
                <input type="hidden" name="booking_date" id="final_date">
                <input type="hidden" name="start_time" id="final_time">

                <div class="lg:col-span-7 bg-white p-10 rounded-[3rem] shadow-xl border border-pink-100/50">
                    <h1 class="text-2xl font-black text-gray-900 tracking-tighter mb-8 italic uppercase border-b border-gray-50 pb-5">Input Data Pelanggan</h1>
                    
                    <div class="grid-form-parent">
                        <div class="div1">
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Nama Lengkap</label>
                            <input type="text" name="customer_name" placeholder="Nama Client" class="w-full bg-gray-50 border-gray-100 rounded-2xl p-4 outline-none focus:ring-2 focus:ring-pink-500 font-bold text-sm transition-all" required>
                        </div>
                        <div class="div2">
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">WhatsApp</label>
                            <input type="number" name="whatsapp_number" placeholder="08xxxxxxxx" class="w-full bg-gray-50 border-gray-100 rounded-2xl p-4 outline-none focus:ring-2 focus:ring-pink-500 font-bold text-sm transition-all" required>
                        </div>
                        <div class="div3">
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Alamat Lengkap</label>
                            <input type="text" name="address" placeholder="Contoh: Jl. Pancasakti No. 1, Tegal" class="w-full bg-gray-50 border-gray-100 rounded-2xl p-4 outline-none focus:ring-2 focus:ring-pink-500 font-bold text-sm transition-all" required>
                        </div>
                        <div class="div4">
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1 text-center">Jml Orang (Max 2)</label>
                            <input type="number" name="person_count" id="person_count" value="1" min="1" max="2" oninput="updateTotal()" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 outline-none focus:ring-2 focus:ring-pink-500 font-bold text-center text-sm">
                        </div>
                        <div class="div5">
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Layanan</label>
                            <div class="select-wrapper">
                                <select name="category_id" id="category_select" onchange="updateTotal()" class="p-4 rounded-2xl text-xs font-black uppercase outline-none">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" data-price="{{ $category->base_price }}" data-duration="{{ $category->duration_minutes }}">{{ $category->name }} ({{ number_format($category->duration_minutes, 0, ',', '.') }} menit)</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="div6">
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Lokasi Rias</label>
                            <div class="select-wrapper">
                                <select name="location_id" id="location_select" onchange="updateTotal()" class="p-4 rounded-2xl text-xs font-black uppercase outline-none">
                                    <option value="0" data-price="0">Datang ke Toko (+0)</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}" data-price="{{ $location->additional_price }}">{{ $location->region_name }} (+Rp{{ number_format($location->additional_price, 0, ',', '.') }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="div7 pt-2">
                            <div class="bg-slate-950 rounded-[2.5rem] p-8 text-white shadow-2xl relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-32 h-32 bg-pink-600/10 rounded-full -mr-16 -mt-16 blur-3xl"></div>
                                <div class="summary-parent">
                                    <div class="s-div1 border-b border-white/5 pb-4 mb-2"><span class="text-[10px] font-black text-pink-500 italic uppercase">Ringkasan Biaya</span></div>
                                    <div class="s-div2 flex flex-col gap-2 text-[10px] font-bold text-gray-500 uppercase"><p>Subtotal</p><p>Jumlah Orang</p><p class="text-white font-black mt-2">Total</p></div>
                                    <div class="s-div3 flex flex-col gap-2 text-[10px] font-black text-right text-white"><p id="display_subtotal">Rp0</p><p id="display_multiplier" class="text-pink-500">X1</p><p class="text-xl text-pink-500 italic mt-1.5">Rp<span id="display_total">0</span></p></div>
                                    <div class="s-div4 pt-6"><label class="block mb-2 font-black text-[9px] uppercase text-gray-500">Metode</label>
                                        <select name="payment_method" id="payMethod" onchange="togglePaymentInput()" class="p-3 bg-white/5 border-white/10 text-white text-xs uppercase outline-none rounded-xl w-full"><option value="cash" class="bg-slate-900">Tunai (Cash)</option><option value="qris" class="bg-slate-900">QRIS / Transfer</option></select>
                                    </div>
                                    <div class="s-div5 pt-6" id="payment_input_area"></div>
                                </div>
                            </div>
                        </div>

                        <div class="div8">
                            <button type="submit" id="btnConfirm" class="w-full bg-emerald-500 text-white font-black py-5 rounded-[2rem] hover:bg-emerald-600 transition-all shadow-xl active:scale-95 uppercase text-[11px]">Konfirmasi & Simpan Jadwal →</button>
                        </div>
                        <div class="div9">
                            <button type="button" id="btnHoliday" onclick="toggleHoliday()" class="w-full hidden bg-rose-50 text-rose-500 font-black py-4 rounded-[2rem] hover:bg-rose-500 hover:text-white transition-all text-[9px] uppercase"><span id="holidayText">Liburkan Hari Ini</span></button>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-5 flex flex-col gap-6">
                    <div class="bg-white p-8 rounded-[3.5rem] shadow-xl border border-pink-50">
                        <div class="flex justify-between items-center mb-8">
                            <button type="button" id="prevMonth" class="bg-pink-50 text-pink-500 px-4 py-2 rounded-xl font-black text-[10px] hidden" onclick="changeMonth(-1)">&larr; Back</button>
                            <h3 id="calTitle" class="text-xl font-black italic uppercase text-gray-900 tracking-tighter">Bulan 2026</h3>
                            <button type="button" id="nextMonth" class="bg-pink-50 text-pink-500 px-5 py-2.5 rounded-2xl font-black text-[10px] uppercase active:scale-90" onclick="changeMonth(1)">Next →</button>
                        </div>
                        <div class="grid grid-cols-7 gap-2 text-center mb-4 border-b border-gray-50 pb-2">
                            @foreach(['S','S','R','K','J','S','M'] as $h) <div class="font-black text-gray-300 text-[11px] uppercase">{{$h}}</div> @endforeach
                        </div>
                        <div id="calGridContent" class="grid grid-cols-7 gap-2 text-center"></div>
                    </div>

                    <div id="timeSection" class="bg-slate-950 p-10 rounded-[3.5rem] text-white hidden shadow-2xl relative overflow-hidden text-center transition-all">
                         <p id="dateDisplay" class="text-pink-500 font-black text-[10px] uppercase mb-2 italic">-</p>
                         <h4 class="text-2xl font-black italic mb-8 uppercase text-white">Atur Jam Rias</h4>
                         <input type="time" id="timeValue" class="w-full bg-white/10 border-white/20 rounded-3xl p-6 text-4xl font-black text-center outline-none focus:ring-4 focus:ring-pink-500 transition-all text-white">
                         <div id="bookedTable" class="hidden mt-8 bg-white/5 rounded-2xl p-6 border border-white/10 text-left text-[11px]">
                            <p class="text-pink-400 font-black uppercase mb-4 tracking-widest underline underline-offset-4">Slot Terisi:</p>
                            <table class="w-full text-gray-200">
                                <thead><tr class="text-gray-500 uppercase"><th>No</th><th>Mulai</th><th class="text-right">Selesai</th></tr></thead>
                                <tbody id="bookedList"></tbody>
                            </table>
                         </div>
                    </div>
                </div>
            </form>

            <div class="bg-white rounded-[3rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                    <h2 class="font-black text-gray-800 uppercase tracking-widest text-xs italic">Agenda Offline Terbaru</h2></div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-white text-[10px] uppercase text-gray-400 font-black tracking-widest">
                            <tr>
                                <th class="px-8 py-6">Waktu</th>
                                <th class="px-8 py-6">Pelanggan</th>
                                <th class="px-8 py-6">Pembayaran</th>
                                <th class="text-center px-8 py-6">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($offlineBookings as $s)
                            <tr class="hover:bg-pink-50/10 transition-colors text-sm font-bold">
                                <td class="px-8 py-6"><div class="text-gray-900">{{ \Carbon\Carbon::parse($s->booking_date)->format('d/m/Y') }}</div><div class="text-[10px] text-pink-600 font-black mt-1 uppercase">{{ $s->start_time }}</div></td>
                                <td class="px-8 py-6"><div class="font-black text-gray-800 italic uppercase">{{ $s->customer_name }} ({{ $s->person_count }} Org)</div><div class="text-[9px] text-gray-400 uppercase tracking-widest">{{ $s->category->name }}</div></td>
                                <td class="px-8 py-6">
                                    @php
                                        $pType = (stripos($s->payment_proof, 'qris') !== false) ? 'Transfer' : 'Cash';
                                        $pStat = ($s->dp_amount >= $s->total_amount) ? 'Lunas' : 'DP';
                                    @endphp
                                    <span class="text-[10px] font-black uppercase text-gray-500 tracking-tighter">{{ $pType }} / {{ $pStat }}</span>
                                </td>
                                <td class="px-8 py-6 text-center flex justify-center gap-2">
                                    @php
                                        $waNumber = preg_replace('/[^0-9]/', '', $s->whatsapp_number);
                                        if (str_starts_with($waNumber, '0')) {
                                            $waNumber = '62' . substr($waNumber, 1);
                                        }
                                        
                                        $tglIndo = \Carbon\Carbon::parse($s->booking_date)->translatedFormat('d F Y');
                                        
                                        $pesanWa = "Halo Kak *{$s->customer_name}*! ✨\n\nKami mengonfirmasi jadwal rias Kakak untuk *{$s->person_count} orang*.\n\n📍 *Alamat:* {$s->address}\n📅 *Tanggal:* {$tglIndo}\n⏰ *Jam:* {$s->start_time} WIB\n\nSampai ketemu di hari H ya Kak! Jika ada perubahan segera hubungi kami. Terima kasih! ❤️";
                                    @endphp
                                    
                                    <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode($pesanWa) }}" target="_blank" class="bg-emerald-500 text-white p-2 px-4 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-emerald-600 transition-all flex items-center">
                                        <i class="fa-brands fa-whatsapp mr-1 text-sm"></i> Chat
                                    </a>

                                    <a href="{{ route('booking.receipt', $s->id) }}" class="bg-gray-900 text-white p-2 px-4 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-pink-600 transition-all">Struk</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="statusModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm transition-all duration-300">
        <div id="statusModalContent" class="bg-white rounded-[2.5rem] max-w-sm w-full p-8 shadow-2xl transform scale-90 opacity-0 transition-all duration-300 border-4 border-gray-100 text-center">
            <div id="statusIconBox" class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                <i id="statusIcon" class="text-3xl fa-solid"></i>
            </div>
            <h3 id="statusTitle" class="text-2xl font-black text-gray-900 uppercase italic mb-2 tracking-tighter">Status</h3>
            <p id="statusDescription" class="text-sm text-gray-500 font-medium mb-8 leading-relaxed"></p>
            
            <button id="statusBtn" type="button" onclick="closeStatusModal()" class="w-full bg-slate-900 text-white font-black py-4 rounded-2xl hover:opacity-90 transition-all uppercase tracking-widest text-xs">Tutup</button>
        </div>
    </div>
</div>

<div id="confirmModal" class="fixed inset-0 z-[110] hidden items-center justify-center p-4 bg-slate-900/90 backdrop-blur-md transition-all duration-300">
    <div id="confirmModalContent" class="bg-white rounded-[2.5rem] max-w-sm w-full p-8 shadow-2xl transform scale-90 opacity-0 transition-all duration-300 border-4 border-blue-50 text-center">
        <div class="w-20 h-20 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fa-solid fa-circle-question text-3xl"></i>
        </div>
        <h3 class="text-2xl font-black text-gray-900 uppercase italic mb-2 tracking-tighter">Konfirmasi</h3>
        <p id="confirmDescription" class="text-sm text-gray-500 font-medium mb-8 leading-relaxed"></p>
        
        <div class="flex gap-3">
            <button type="button" onclick="closeConfirmModal()" class="flex-1 bg-gray-100 text-gray-400 font-black py-4 rounded-2xl hover:bg-gray-200 transition-all uppercase tracking-widest text-xs">Batal</button>
            <button id="confirmOkBtn" type="button" class="flex-1 bg-slate-900 text-white font-black py-4 rounded-2xl hover:bg-blue-600 transition-all uppercase tracking-widest text-xs">Konfirmasi</button>
        </div>
    </div>
</div>

<script type="text/javascript" 
            src="https://app.sandbox.midtrans.com/snap/snap.js" 
            data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

    <script>
        const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        const initMonth = new Date().getMonth(), initYear = 2026;
        let curMonth = initMonth, curYear = initYear, dayBookings = [], totalVal = 0, isHoliday = false;

        // 1. FUNGSI MODAL PREMIUM (DUAL MODE: ERROR & SUCCESS)
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
                content.classList.remove('border-emerald-100');
                content.classList.add('border-rose-100');
                iconBox.className = "w-20 h-20 bg-rose-100 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-6 animate-bounce";
                icon.className = "fa-solid fa-calendar-xmark text-3xl";
                title.innerText = "Peringatan!";
                title.className = "text-2xl font-black text-gray-900 uppercase italic mb-2 tracking-tighter";
                btn.classList.remove('hidden');
            } else {
                content.classList.remove('border-rose-100');
                content.classList.add('border-emerald-100');
                iconBox.className = "w-20 h-20 bg-emerald-100 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-6 animate-pulse";
                icon.className = "fa-solid fa-calendar-check text-3xl";
                title.innerText = "Berhasil!";
                title.className = "text-2xl font-black text-emerald-600 uppercase italic mb-2 tracking-tighter";
                btn.classList.add('hidden');
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => { 
                content.classList.replace('scale-90', 'scale-100'); 
                content.classList.replace('opacity-0', 'opacity-100'); 
            }, 10);
        }

        function closeStatusModal() {
            const modal = document.getElementById('statusModal');
            const content = document.getElementById('statusModalContent');
            content.classList.replace('scale-100', 'scale-90');
            content.classList.replace('opacity-100', 'opacity-0');
            setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 300);
        }

        let holidayCallback = null;

        // FUNGSI MODAL KONFIRMASI (OKE / BATAL)
        function showConfirmModal(msg, callback) {
            document.getElementById('confirmDescription').innerHTML = msg;
            holidayCallback = callback; // Simpan aksi yang akan dijalankan
            
            const modal = document.getElementById('confirmModal');
            const content = document.getElementById('confirmModalContent');
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => { 
                content.classList.replace('scale-90', 'scale-100'); 
                content.classList.replace('opacity-0', 'opacity-100'); 
            }, 10);
        }

        function closeConfirmModal() {
            const modal = document.getElementById('confirmModal');
            const content = document.getElementById('confirmModalContent');
            content.classList.replace('scale-100', 'scale-90');
            content.classList.replace('opacity-100', 'opacity-0');
            setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 300);
        }

        // Listener Tombol OKE di Modal Konfirmasi
        document.getElementById('confirmOkBtn').onclick = function() {
            if (holidayCallback) holidayCallback();
            closeConfirmModal();
        };

        // FUNGSI LIBUR
        async function toggleHoliday() {
            const date = document.getElementById('final_date').value;
            if(!date) { showStatusModal("Pilih tanggal di kalender terlebih dahulu!"); return; }

            const actionText = isHoliday ? "Membatalkan Libur" : "Meliburkan Jadwal";
            const msg = `Apakah Anda yakin ingin <strong>${actionText}</strong> untuk tanggal <strong>${date}</strong>?`;

            showConfirmModal(msg, async () => {
                try {
                    const res = await fetch("{{ route('admin.schedules.toggle_holiday') }}", {
                        method: 'POST', 
                        headers: { 
                            'Content-Type': 'application/json', 
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ date: date })
                    });
                    
                    const data = await res.json();
                    
                    if(data.error) {
                        showStatusModal(data.error);
                    } else {
                        const successMsg = isHoliday ? "Hari libur berhasil dihapus!" : "Berhasil meliburkan jadwal!";
                        showStatusModal(successMsg, false);
                        setTimeout(() => location.reload(), 1500);
                    }
                } catch (err) {
                    showStatusModal("Terjadi kesalahan sistem saat memproses libur.");
                }
            });
        }

        // 3. UPDATE HARGA & LIMIT ORANG
        function updateTotal() {
            const sOpt = document.getElementById('category_select').selectedOptions[0], lOpt = document.getElementById('location_select').selectedOptions[0];
            let persons = parseInt(document.getElementById('person_count').value) || 1;
            if (persons > 2) { 
                showStatusModal("Maksimal pemesanan offline adalah 2 orang."); 
                document.getElementById('person_count').value = 2; persons = 2; 
            }
            const sPrice = parseInt(sOpt.dataset.price) || 0, lPrice = parseInt(lOpt.dataset.price) || 0;
            totalVal = (sPrice * persons) + lPrice;
            document.getElementById('display_subtotal').innerText = 'Rp' + new Intl.NumberFormat('id-ID').format(sPrice);
            document.getElementById('display_multiplier').innerText = 'X' + persons;
            document.getElementById('display_total').innerText = new Intl.NumberFormat('id-ID').format(totalVal);
            togglePaymentInput();
        }

        function togglePaymentInput() {
            const method = document.getElementById('payMethod').value, area = document.getElementById('payment_input_area');
            if (method === 'cash') { 
                area.innerHTML = `<label class="block text-[9px] font-black text-gray-500 uppercase mb-2 ml-1 tracking-widest">Nominal Tunai</label>
                                  <input type="number" name="dp_amount" id="dpInput" placeholder="0" class="w-full bg-white/5 border-white/10 rounded-xl p-3 text-sm text-white outline-none">`; 
            } else { 
                area.innerHTML = `<label class="block text-[9px] font-black text-gray-500 uppercase mb-2 ml-1 tracking-widest">Bayar</label>
                                  <div class="select-wrapper"><select name="dp_amount" id="dpInput" class="p-3 bg-white/5 border-white/10 text-white rounded-xl text-xs uppercase outline-none w-full font-bold">
                                      <option value="${totalVal/2}" class="bg-slate-900">DP 50% (Rp${new Intl.NumberFormat('id-ID').format(totalVal/2)})</option>
                                      <option value="${totalVal}" class="bg-slate-900">Lunas (Rp${new Intl.NumberFormat('id-ID').format(totalVal)})</option>
                                  </select></div>`; 
            }
        }

        // 4. LOGIKA KALENDER
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
                    btn.className = "day-btn p-3 bg-gray-50 text-gray-800 transition-all";
                    const dateToCheck = new Date(curYear, curMonth, i);
                    if (dateToCheck < today) { btn.disabled = true; btn.classList.add('before-today'); } 
                    else {
                        if (availability[i]) {
                            if (availability[i].status === 'holiday') btn.classList.add('holiday-active');
                            else btn.classList.add('booking-partial');
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
                btnConfirm.disabled = true; btnConfirm.classList.replace('bg-emerald-500', 'bg-gray-100'); timeArea.classList.add('hidden');
            } else {
                btnConfirm.disabled = false; btnConfirm.classList.replace('bg-gray-100', 'bg-emerald-500'); timeArea.classList.remove('hidden');
            }
            const list = document.getElementById('bookedList'), table = document.getElementById('bookedTable');
            list.innerHTML = '';
            if (details.length > 0) { table.classList.remove('hidden'); details.forEach((d, idx) => { list.innerHTML += `<tr class="border-b border-white/5"><td class="py-2">${idx+1}</td><td class="py-2">${d.start}</td><td class="py-2 text-right text-pink-400">${d.end}</td></tr>`; }); } 
            else { table.classList.add('hidden'); }
            document.querySelectorAll('.day-btn').forEach(b => b.classList.remove('active-date')); if(!isHoliday) el.classList.add('active-date');
        }

        // 5. SUBMIT FORM AJAX
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
            formData.set('booking_date', dateVal);
            formData.set('start_time', timeVal);

            if (method === 'qris') {
                try {
                    const res = await fetch("{{ route('admin.schedules.prepare') }}", { 
                        method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } 
                    });
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
            } else {
                await saveData(formData);
            }
        };

        async function saveData(formData) {
            const btn = document.getElementById('btnConfirm');
            try {
                const response = await fetch("{{ route('admin.schedules.manual') }}", { 
                    method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } 
                });
                const result = await response.json();
                if (result.status === 'success' || response.ok) {
                    showStatusModal("Jadwal rias offline telah berhasil disimpan ke agenda.", false);
                    setTimeout(() => location.reload(), 2000);
                } else { showStatusModal(result.message || "Gagal menyimpan."); resetBtn(btn, "Konfirmasi & Simpan Jadwal →"); }
            } catch (err) { showStatusModal("Koneksi bermasalah."); resetBtn(btn, "Konfirmasi & Simpan Jadwal →"); }
        }

        // 6. EVENT WHEEL & NAVIGASI
        document.addEventListener('wheel', function(event) {
            if (document.activeElement.type === 'number') document.activeElement.blur();
        });

        function resetBtn(btn, text) { btn.disabled = false; btn.innerText = text; }
        function changeMonth(step) { curMonth += step; if(curMonth > 11) { curMonth = 0; curYear++; } else if(curMonth < 0) { curMonth = 11; curYear--; } renderCalendar(); }
        
        updateTotal(); renderCalendar();
    </script>
</x-app-layout>