<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking {{ $category->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number] { -moz-appearance: textfield; }

        .summary-parent { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; }
        .s-div1 { grid-area: 1 / 1 / 2 / 3; } 
        .s-div2 { grid-area: 2 / 1 / 3 / 2; } 
        .s-div3 { grid-area: 2 / 2 / 3 / 3; }
        .s-div4 { grid-area: 3 / 1 / 4 / 3; } 
        .s-div5 { grid-area: 4 / 1 / 5 / 3; }

        .animate-pop { animation: pop 0.2s ease-out; }
        @keyframes pop { 0% { transform: scale(0.95); } 100% { transform: scale(1); } }
        
        /* 🚨 REVISI KETERBACAAN INPUT & BOX UNTUK TABLET */
        input[type="text"], input[type="number"], select, textarea {
            background-color: #ffffff !important;
            color: #0f172a !important;
            font-weight: 700 !important;
            border: 2px solid #cbd5e1 !important;
        }

        /* 🚨 PAKSA BG OVERLAY MODAL JADUL ANTIFREEZE */
        #warningModal {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            width: 100% !important;
            height: 100% !important;
            background-color: rgba(15, 23, 42, 0.8) !important;
            align-items: center !important;
            justify-content: center !important;
            z-index: 2147483647 !important;
        }
    </style>
</head>
<body class="bg-pink-50 min-h-screen py-12 px-6 font-sans text-gray-900">
    <div class="max-w-2xl mx-auto bg-white rounded-[3rem] shadow-2xl overflow-hidden border border-pink-100">
        <div class="p-10 lg:p-14">
            <h1 class="text-4xl font-black tracking-tighter mb-2 italic uppercase">Data Pemesanan</h1>
            <p class="text-pink-600 font-bold mb-10 uppercase text-xs tracking-[0.2em]">Layanan: {{ $category->name }}</p>

            <form action="{{ route('booking.store') }}" method="POST" id="bookingForm" class="space-y-8">
                @csrf
                <input type="hidden" name="category_id" id="base_price_input" data-price="{{ $category->base_price }}" value="{{ $category->id }}">
                
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-black text-gray-700 uppercase mb-2 ml-1">Nama Lengkap</label>
                            <input type="text" id="input_name" name="customer_name" class="w-full rounded-2xl p-4 outline-none focus:ring-2 focus:ring-pink-500 font-bold" required>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-700 uppercase mb-2 ml-1">Jumlah Orang (Maks 2)</label>
                            <input type="number" name="person_count" id="input_persons" 
                                   value="{{ $category->target_person ?? 1 }}" 
                                   {{ isset($category->is_bundling) && $category->target_person == 2 ? 'disabled' : '' }}
                                   min="1" max="2" onwheel="this.blur()" oninput="updateTotal()" 
                                   class="w-full rounded-2xl p-4 outline-none focus:ring-2 focus:ring-pink-500 font-bold text-center">
                            
                            @if(isset($category->is_bundling) && $category->target_person == 2)
                                <p class="text-[9px] text-rose-500 mt-2 italic font-bold">* Paket ini khusus untuk 2 orang.</p>
                                <input type="hidden" name="person_count" value="2"> 
                            @endif
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-black text-gray-700 uppercase mb-2 ml-1">Nomor WhatsApp</label>
                            <input type="number" id="input_wa" name="whatsapp_number" onwheel="this.blur()" class="w-full rounded-2xl p-4 outline-none focus:ring-2 focus:ring-pink-500 font-bold" placeholder="08xxx" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-black text-gray-700 uppercase mb-2 ml-1">Alamat Lengkap (Lokasi Rias)</label>
                        <textarea id="input_address" name="address" rows="3" class="w-full rounded-2xl p-4 outline-none focus:ring-2 focus:ring-pink-500 resize-none font-bold" placeholder="Tulis alamat detail..." required></textarea>
                    </div>
                </div>

                <div class="space-y-4">
                    <label class="block text-xs font-black text-gray-700 uppercase mb-1 ml-1">Jadwal Rias</label>
                    <a href="{{ route('booking.calendar', $category->id) }}" id="btnGoToCalendar" class="block w-full bg-pink-500 text-white font-black p-5 rounded-2xl text-center hover:bg-pink-600 transition-all shadow-lg shadow-pink-100 uppercase tracking-widest text-xs">Buka Kalender Jadwal</a>
                    <div class="p-6 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200 text-center">
                        @if(request('date') && request('time'))
                            <p class="text-[10px] font-black text-gray-500 mb-1 tracking-widest uppercase">Jadwal Terpilih:</p>
                            <p class="text-xl font-black italic uppercase text-gray-900">{{ request('date') }} | {{ request('time') }}</p>
                            <input type="hidden" name="booking_date" value="{{ request('date') }}">
                            <input type="hidden" name="start_time" value="{{ request('time') }}">
                        @else
                            <p class="text-xs font-bold text-rose-500 italic">Pilih tanggal & jam di kalender</p>
                        @endif
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-black text-gray-700 uppercase mb-2 ml-1">Lokasi Rias</label>
                    <select name="location_id" id="location_select" onchange="updateTotal()" class="w-full rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-pink-500">
                        <option value="0" data-extra="0">Datang ke Toko (Tanpa Tambahan Biaya)</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}" data-extra="{{ $location->additional_price }}">
                                {{ $location->region_name }} (+Rp{{ number_format($location->additional_price, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- 🚨 REVISI TOTAL BOX RINGKASAN BIAYA KONTRAST TINGGI KHUSUS TABLET -->
                <div class="pt-2">
                    <div class="bg-white rounded-[2.5rem] p-8 text-gray-900 shadow-sm relative overflow-hidden border-2 border-pink-100">
                        <div class="summary-parent relative z-[10]">
                            <div class="s-div1 border-b border-gray-200 pb-4 mb-2"><span class="text-xs font-black text-pink-700 italic uppercase tracking-wider">Ringkasan Biaya</span></div>
                            <div class="s-div2 flex flex-col gap-2.5 text-xs font-black text-gray-900 uppercase"><p>Harga Paket</p><p>Jumlah Orang</p><p>Ongkir Lokasi</p><p class="text-gray-900 font-black mt-2">Total Estimasi</p></div>
                            <div class="s-div3 flex flex-col gap-2.5 text-xs font-black text-right text-gray-900"><p id="display_subtotal">Rp0</p><p id="display_multiplier" class="text-pink-700 italic">X1</p><p id="display_extra" class="text-pink-700">Rp0</p><p class="text-2xl text-pink-700 italic mt-1 font-black">Rp<span id="display_total">0</span></p></div>
                            
                            <div class="s-div4 pt-6 mt-4 border-t border-gray-200">
                                <label class="block mb-3 font-black text-[10px] uppercase text-gray-900 tracking-widest">Bocoran Pembayaran</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="bg-pink-50 p-3 rounded-2xl border-2 border-pink-100">
                                        <p class="text-[8px] text-gray-900 uppercase font-black">Bayar Lunas</p>
                                        <p class="text-sm font-black text-pink-700" id="display_full">Rp0</p>
                                    </div>
                                    <div class="bg-pink-50 p-3 rounded-2xl border-2 border-pink-100">
                                        <p class="text-[8px] text-gray-900 uppercase font-black">Bayar DP (50%)</p>
                                        <p class="text-sm font-black text-pink-700" id="display_dp">Rp0</p>
                                    </div>
                                </div>
                            </div>
                            <div class="s-div5 pt-4 text-[9px] text-gray-700 font-bold italic text-center leading-relaxed">Rincian asli akan muncul pada halaman pembayaran berikutnya.</div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-gray-900 text-white font-black py-6 rounded-3xl hover:bg-emerald-600 transition-all shadow-2xl group uppercase tracking-widest text-sm">
                    Lanjut Bayar <span class="group-hover:ml-2 transition-all">→</span>
                </button>
            </form>
        </div>
    </div>

    <div id="warningModal" class="hidden no-print" style="display: none;">
        <div class="bg-white rounded-[2.5rem] max-w-sm w-full p-10 shadow-2xl text-center animate-pop border-4 border-rose-50 mx-4" style="display: block !important;">
            <div class="w-20 h-20 bg-rose-100 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fa-solid fa-triangle-exclamation text-3xl"></i>
            </div>
            <h3 class="text-2xl font-black text-gray-900 uppercase italic tracking-tighter mb-2">Perhatian!</h3>
            <p id="warningText" class="text-sm text-gray-500 mb-8 font-bold"></p>
            <button type="button" onclick="closeWarning()" class="w-full bg-slate-900 text-white font-black py-4 rounded-2xl uppercase text-[10px] tracking-widest shadow-lg">Paham</button>
        </div>
    </div>

    <script>
        const inputs = ['input_name', 'input_wa', 'input_address', 'location_select', 'input_persons'];
        const btnCalendar = document.getElementById('btnGoToCalendar');
        const inputPersons = document.getElementById('input_persons');

        // 🚨 REVISI PENANGANAN EVENT: Menggunakan event yang tepat untuk menghindari loop refresh bawaan browser
        inputs.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                const saved = localStorage.getItem(id);
                if (saved) el.value = saved;
                
                // Elemen teks menggunakan 'input', seleksi/angka menggunakan 'change' agar tidak loop
                const eventType = (el.tagName === 'SELECT' || el.type === 'number') ? 'change' : 'input';
                
                el.addEventListener(eventType, () => {
                    if (el.value !== "") {
                        localStorage.setItem(id, el.value);
                    }
                    updateTotal();
                });
            }
        });

        function showWarning(msg) {
            document.getElementById('warningText').innerText = msg;
            var modal = document.getElementById('warningModal');
            if(modal) { modal.style.setProperty('display', 'flex', 'important'); }
        }

        function closeWarning() {
            var modal = document.getElementById('warningModal');
            if(modal) { modal.style.setProperty('display', 'none', 'important'); }
        }

        function updateTotal() {
            const loc = document.getElementById('location_select');
            const p = document.getElementById('input_persons');
            if (!loc || !p) return; // Proteksi jika elemen belum siap dimuat

            const base = parseInt(document.getElementById('base_price_input').dataset.price);
            const isBundling = {{ isset($category->is_bundling) ? 'true' : 'false' }};
            const targetPerson = {{ $category->target_person ?? 1 }};
            
            let persons = parseInt(p.value) || 1;
            
            // Validasi tanpa memicu loop input
            if (persons > 2) { 
                persons = 2; 
                p.value = 2; 
                showWarning("Maaf, maksimal pemesanan adalah 2 orang per jadwal rias.");
            }
            if (persons < 1 && p.value !== "") { persons = 1; p.value = 1; }

            let calculatedPrice = 0;
            if (isBundling) {
                if (targetPerson === 1 && persons === 2) {
                    calculatedPrice = base * 2;
                } else {
                    calculatedPrice = base;
                }
            } else {
                calculatedPrice = base * persons;
            }

            const extra = parseInt(loc.options[loc.selectedIndex].getAttribute('data-extra')) || 0;
            const total = calculatedPrice + extra;
            const dp = Math.ceil(total * 0.5);

            document.getElementById('display_multiplier').innerText = 'X' + persons;
            document.getElementById('display_extra').innerText = 'Rp' + extra.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            document.getElementById('display_subtotal').innerText = 'Rp' + base.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            document.getElementById('display_total').innerText = total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            document.getElementById('display_full').innerText = 'Rp' + total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            document.getElementById('display_dp').innerText = 'Rp' + dp.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");

            updateCalendarLink();
        }

        function updateCalendarLink() {
            const baseUrl = "{{ route('booking.calendar', $category->id) }}";
            if(btnCalendar && inputPersons) {
                btnCalendar.href = `${baseUrl}?person_count=${inputPersons.value}`;
            }
        }

        // Pemicu awal dijalankan dengan aman saat seluruh window selesai ter-render
        window.addEventListener('load', () => {
            updateTotal();
        });

        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            if (!document.querySelector('input[name="booking_date"]')) {
                e.preventDefault();
                showWarning("Silahkan pilih tanggal dan jam rias di kalender terlebih dahulu!");
                return;
            }
            inputs.forEach(id => localStorage.removeItem(id));
        });
    </script>
</body>
</html>