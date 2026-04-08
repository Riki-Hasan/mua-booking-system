<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-pink-600 leading-tight tracking-tighter">
                {{ __('Manajemen Jadwal & Booking Offline') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="bg-gray-100 text-gray-700 px-6 py-2 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-pink-100 transition-all">
                <i class="fa-solid fa-arrow-left mr-1"></i> Dashboard
            </a>
        </div>
    </x-slot>

    <style>
        .select-wrapper { position: relative; display: flex; align-items: center; }
        .select-wrapper::after { content: '▼'; font-size: 8px; position: absolute; right: 15px; pointer-events: none; color: #9ca3af; }
        select { -webkit-appearance: none; appearance: none; background: transparent; border: none; outline: none; width: 100%; cursor: pointer; }
        .custom-scroll::-webkit-scrollbar { width: 3px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: rgba(236, 72, 153, 0.4); border-radius: 10px; }
        input[type="time"]::-webkit-calendar-picker-indicator { filter: invert(100%); cursor: pointer; transform: scale(1.5); }
        .day-btn { aspect-ratio: 1 / 1; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('admin.schedules.manual') }}" method="POST" id="bookingForm" class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-12">
                @csrf
                <input type="hidden" name="booking_date" id="final_date">
                <input type="hidden" name="start_time" id="final_time">

                <div class="lg:col-span-7 bg-white p-8 md:p-12 rounded-[3rem] shadow-2xl border border-pink-100">
                    <h1 class="text-3xl font-black text-gray-900 tracking-tighter mb-8 italic uppercase">Input Data Pelanggan</h1>
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Nama Lengkap</label>
                                <input type="text" name="customer_name" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 outline-none focus:ring-2 focus:ring-pink-500 font-bold" required>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">WhatsApp</label>
                                <input type="number" name="whatsapp_number" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 outline-none focus:ring-2 focus:ring-pink-500 font-bold" placeholder="08xxx" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Alamat Lengkap</label>
                            <textarea name="address" rows="2" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 outline-none focus:ring-2 focus:ring-pink-500 font-bold resize-none" required></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Pilih Layanan</label>
                                <div class="select-wrapper bg-gray-50 rounded-2xl border border-gray-100">
                                    <select name="category_id" id="category_select" onchange="updateTotal()" class="p-4 text-xs font-black uppercase">
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" data-price="{{ $category->base_price }}" data-duration="{{ $category->duration_minutes }}">
                                                {{ $category->name }} ({{ $category->duration_minutes }} menit)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Tempat Rias</label>
                                <div class="select-wrapper bg-gray-50 rounded-2xl border border-gray-100">
                                    <select name="location_id" id="location_select" onchange="updateTotal()" class="p-4 text-xs font-black uppercase">
                                        <option value="0" data-price="0">Datang ke Toko (+Rp0)</option>
                                        @foreach($locations as $location)
                                            <option value="{{ $location->id }}" data-price="{{ $location->additional_price }}">
                                                {{ $location->region_name }} (+Rp{{ number_format($location->additional_price, 0, ',', '.') }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-950 rounded-[2rem] p-8 text-white shadow-xl relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-pink-600/10 rounded-full -mr-16 -mt-16 blur-3xl"></div>
                            
                            <div class="flex justify-between items-end mb-6">
                                <span class="text-xs font-black uppercase text-pink-500 italic tracking-widest">Ringkasan Biaya</span>
                                <span class="text-3xl font-black italic">Rp<span id="display_total">0</span></span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[9px] font-black text-gray-500 uppercase mb-2 ml-1 tracking-widest">Metode Bayar</label>
                                    <div class="select-wrapper bg-white/5 rounded-xl border border-white/10">
                                        <select name="payment_method" id="payMethod" onchange="togglePaymentInput()" class="p-3 text-xs font-bold text-white uppercase">
                                            <option value="cash" class="bg-slate-900">Tunai (Cash)</option>
                                            <option value="qris" class="bg-slate-900">QRIS / Transfer</option>
                                        </select>
                                    </div>
                                </div>
                                <div id="payment_input_area"></div>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-gray-900 text-white font-black py-6 rounded-3xl hover:bg-emerald-500 transition-all shadow-2xl active:scale-95 uppercase tracking-widest text-xs">
                            Konfirmasi & Simpan Jadwal →
                        </button>
                    </div>
                </div>

                <div class="lg:col-span-5 flex flex-col gap-6">
                    <div class="bg-white p-8 rounded-[3rem] shadow-xl border border-pink-50">
                        <div class="flex justify-between items-center mb-6">
                            <h3 id="calTitle" class="text-xl font-black italic uppercase text-gray-900">Bulan 2026</h3>
                            <button type="button" id="nextMonth" class="bg-pink-50 text-pink-500 p-3 rounded-xl font-black text-[10px] uppercase">Next →</button>
                        </div>
                        <div id="calGrid" class="grid grid-cols-7 gap-2 text-center text-[10px]">
                            @foreach(['S','S','R','K','J','S','M'] as $h) <div class="font-black text-gray-300 pb-2">{{$h}}</div> @endforeach
                        </div>
                    </div>

                    <div id="timeSection" class="bg-slate-950 p-8 rounded-[3rem] text-white hidden relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-40 h-40 bg-pink-500/5 rounded-full -mr-20 -mt-20 blur-3xl"></div>
                        <div class="relative z-10">
                            <p id="dateDisplay" class="text-pink-500 font-black text-[10px] uppercase tracking-[0.3em] mb-2 italic">-</p>
                            <h4 class="text-2xl font-black italic mb-6 tracking-tighter uppercase">Slot Waktu</h4>
                            
                            <div id="bookedTable" class="hidden mb-6 bg-white/5 rounded-2xl p-4 border border-white/10 max-h-32 overflow-y-auto custom-scroll">
                                <p class="text-[9px] font-black uppercase text-pink-400 mb-2 italic">Jadwal Terisi:</p>
                                <div id="bookedList" class="text-[10px] text-gray-300 space-y-1 font-bold"></div>
                            </div>

                            <input type="time" id="timeValue" onchange="syncTime()" class="w-full bg-white/10 border-white/20 rounded-2xl p-6 text-4xl font-black text-center outline-none focus:ring-4 focus:ring-pink-500 transition-all">
                        </div>
                    </div>
                </div>
            </form>

            <div class="bg-white rounded-[3rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 bg-gray-50/50">
                    <h2 class="font-black text-gray-800 uppercase tracking-widest text-xs italic">Daftar Agenda Offline (Admin Input)</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-white text-[10px] uppercase text-gray-400 font-black tracking-widest">
                            <tr>
                                <th class="px-8 py-6">Tanggal & Waktu</th>
                                <th class="px-8 py-6">Pelanggan</th>
                                <th class="px-8 py-6">Status Bayar</th>
                                <th class="px-8 py-6">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($offlineBookings as $s)
                            <tr class="hover:bg-pink-50/20 transition-colors text-sm font-bold">
                                <td class="px-8 py-6">
                                    <div class="text-gray-900">{{ \Carbon\Carbon::parse($s->booking_date)->format('d/m/Y') }}</div>
                                    <div class="text-[10px] text-pink-600 font-black">{{ $s->start_time }} - {{ $s->end_time }}</div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="font-black text-gray-800">{{ $s->customer_name }}</div>
                                    <div class="text-[9px] text-gray-400 uppercase tracking-widest">{{ $s->category->name }}</div>
                                </td>
                                <td class="px-8 py-6">
                                    @if($s->dp_amount >= $s->total_amount)
                                        <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-lg text-[9px] uppercase font-black">Lunas</span>
                                    @else
                                        <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-lg text-[9px] uppercase font-black">DP</span>
                                    @endif
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex gap-2">
                                        <a href="{{ route('booking.receipt', $s->id) }}" class="bg-gray-900 text-white p-2 px-4 rounded-xl text-[9px] font-black uppercase">Struk</a>
                                        <a href="https://wa.me/{{ $s->whatsapp_number }}?text=Halo%20{{ $s->customer_name }},%20konfirmasi%20jadwal%20MUA%20kamu%20pada%20tanggal%20{{ $s->booking_date }}.%20Terima%20kasih!" 
                                           target="_blank" 
                                           class="bg-emerald-500 text-white p-2 px-4 rounded-xl text-[9px] font-black uppercase flex items-center gap-1">
                                           <i class="fa-brands fa-whatsapp text-xs"></i> Chat
                                        </a>
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




    <!-- modal  -->
     <div id="clashModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-all duration-300">
    <div id="clashModalContent" class="bg-white rounded-[2.5rem] max-w-sm w-full p-8 shadow-2xl transform scale-90 opacity-0 transition-all duration-300 border-4 border-rose-100">
        <div class="text-center">
            <div class="w-20 h-20 bg-rose-100 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-6 animate-bounce">
                <i class="fa-solid fa-calendar-xmark text-3xl"></i>
            </div>
            
            <h3 class="text-2xl font-black text-gray-900 uppercase italic tracking-tighter mb-2">Jadwal Bentrok!</h3>
            <p id="clashMessage" class="text-sm text-gray-500 font-medium leading-relaxed mb-8">
                Wah, jam segitu Mas/Mbak lagi ada jadwal rias nih. Coba geser dikit jamnya ya!
            </p>

            <button type="button" onclick="closeClashModal()" class="w-full bg-slate-900 text-white font-black py-4 rounded-2xl hover:bg-rose-500 transition-all uppercase tracking-widest text-xs shadow-lg shadow-rose-100">
                Oke, Saya Ganti Jam
            </button>
        </div>
    </div>
</div>

<style>
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-10px); }
        75% { transform: translateX(10px); }
    }
    .animate-shake { animation: shake 0.4s ease-in-out; }
</style>

    <script>
        const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        let curMonth = new Date().getMonth();
        let curYear = 2026;
        let selectedDay = null;
        let totalVal = 0;
        let dayBookings = []; 
        let serviceDuration = 0; 

        // 1. HITUNG BIAYA & DURASI (Dipanggil setiap ganti layanan)
        function updateTotal() {
            const serviceOpt = document.getElementById('category_select').selectedOptions[0];
            const locationOpt = document.getElementById('location_select').selectedOptions[0];
            
            const sPrice = parseInt(serviceOpt.dataset.price) || 0;
            const lPrice = parseInt(locationOpt.dataset.price) || 0;
            
            // Update durasi untuk validasi tabrakan
            serviceDuration = parseInt(serviceOpt.dataset.duration) || 90; 
            
            totalVal = sPrice + lPrice;
            document.getElementById('display_total').innerText = new Intl.NumberFormat('id-ID').format(totalVal);
            togglePaymentInput();
        }

        function togglePaymentInput() {
            const method = document.getElementById('payMethod').value;
            const area = document.getElementById('payment_input_area');
            
            if (method === 'cash') {
                area.innerHTML = `
                    <label class="block text-[9px] font-black text-gray-500 uppercase mb-2 ml-1 tracking-widest">Nominal Tunai</label>
                    <input type="number" name="dp_amount" id="dpInput" placeholder="0" class="w-full bg-white/5 border-white/10 rounded-xl p-3 text-sm font-bold text-white outline-none focus:ring-2 focus:ring-pink-500 transition-all">
                `;
            } else {
                const dp = Math.floor(totalVal / 2);
                area.innerHTML = `
                    <label class="block text-[9px] font-black text-gray-500 uppercase mb-2 ml-1 tracking-widest">Pilih Transaksi</label>
                    <div class="select-wrapper bg-white/5 rounded-xl border border-white/10">
                        <select name="dp_amount" id="dpInput" class="p-3 text-xs font-bold text-white uppercase">
                            <option value="${dp}" class="bg-slate-900">DP 50% (Rp${new Intl.NumberFormat('id-ID').format(dp)})</option>
                            <option value="${totalVal}" class="bg-slate-900">Lunas (Rp${new Intl.NumberFormat('id-ID').format(totalVal)})</option>
                        </select>
                    </div>
                `;
            }
        }

        // 2. LOGIKA KALENDER (FIX: Disable Tanggal Lewat)
        async function renderCalendar() {
            const grid = document.getElementById('calGrid');
            const label = document.getElementById('calTitle');
            label.innerText = `${monthNames[curMonth]} ${curYear}`;
            
            const response = await fetch(`/api/check-availability?month=${curMonth + 1}&year=${curYear}`);
            const availability = await response.json();
            
            const today = new Date();
            today.setHours(0,0,0,0);

            const daysInMonth = new Date(curYear, curMonth + 1, 0).getDate();
            grid.querySelectorAll('.day-btn').forEach(b => b.remove());

            for (let i = 1; i <= daysInMonth; i++) {
                const btn = document.createElement('button');
                btn.type = "button";
                btn.innerText = i;
                btn.className = "day-btn p-3 rounded-xl font-black transition-all ";
                
                const dateToCheck = new Date(curYear, curMonth, i);

                if (dateToCheck < today) {
                    // BENAR-BENAR DISABLE
                    btn.className += "opacity-20 cursor-not-allowed bg-gray-100 text-gray-400";
                    btn.disabled = true;
                } else {
                    if (availability[i]) {
                        if (availability[i].status === 'full') {
                            btn.className += "bg-rose-500 text-white shadow-lg shadow-rose-200";
                        } else if (availability[i].status === 'partial') {
                            btn.className += "bg-amber-400 text-white shadow-lg shadow-amber-200";
                        }
                        btn.onclick = (e) => selectDate(i, availability[i].details, e.target);
                    } else {
                        btn.className += "bg-gray-50 text-gray-800 hover:bg-pink-500 hover:text-white";
                        btn.onclick = (e) => selectDate(i, [], e.target);
                    }
                }
                grid.appendChild(btn);
            }
        }

        function selectDate(day, details, el) {
            selectedDay = day;
            dayBookings = details; 
            
            document.getElementById('dateDisplay').innerText = `${day} ${monthNames[curMonth]} ${curYear}`;
            
            const month = (curMonth + 1).toString().padStart(2, '0');
            const formattedDay = day.toString().padStart(2, '0');
            document.getElementById('final_date').value = `${curYear}-${month}-${formattedDay}`;
            
            document.getElementById('timeSection').classList.remove('hidden');
            
            const list = document.getElementById('bookedList');
            const table = document.getElementById('bookedTable');
            list.innerHTML = '';
            
            if (details.length > 0) {
                table.classList.remove('hidden');
                details.forEach(d => { list.innerHTML += `<div>${d.start} - ${d.end}</div>`; });
            } else {
                table.classList.add('hidden');
            }

            document.querySelectorAll('.day-btn').forEach(b => b.classList.remove('bg-pink-500', 'text-white', 'ring-4', 'ring-pink-500/20'));
            el.classList.add('bg-pink-500', 'text-white', 'ring-4', 'ring-pink-500/20');
        }

        function syncTime() {
            document.getElementById('final_time').value = document.getElementById('timeValue').value;
        }

        // 3. MODAL INTERAKTIF & VALIDASI
        function openClashModal(clashingWith) {
            const modal = document.getElementById('clashModal');
            const content = document.getElementById('clashModalContent');
            const msg = document.getElementById('clashMessage');
            const timeInput = document.getElementById('timeValue');

            msg.innerHTML = `Jam tersebut bentrok dengan jadwal:<br>
                            <strong class="text-rose-500 text-lg uppercase underline decoration-rose-200">
                                ${clashingWith.start} - ${clashingWith.end}
                            </strong><br>
                            Coba cari celah jam lain ya! ✨`;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            setTimeout(() => {
                content.classList.remove('scale-90', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);

            // Efek Shake
            timeInput.classList.add('animate-shake', 'ring-4', 'ring-rose-500/30');
            setTimeout(() => timeInput.classList.remove('animate-shake'), 400);
        }

        function closeClashModal() {
            const modal = document.getElementById('clashModal');
            const content = document.getElementById('clashModalContent');
            content.classList.add('scale-90', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        }

        // 4. VALIDASI OVERLAP SAAT SUBMIT
        document.getElementById('bookingForm').onsubmit = function(e) {
            const date = document.getElementById('final_date').value;
            const time = document.getElementById('final_time').value;

            if (!date || !time) {
                alert("Harap pilih tanggal dan jam di kalender!");
                e.preventDefault();
                return;
            }

            const [h, m] = time.split(':').map(Number);
            const userStart = h * 60 + m; 
            const userEnd = userStart + serviceDuration;

            let clashingRecord = null;
            dayBookings.forEach(booking => {
                const [bsh, bsm] = booking.start.split(':').map(Number);
                const [beh, bem] = booking.end.split(':').map(Number);
                const existingStart = bsh * 60 + bsm;
                const existingEnd = beh * 60 + bem;

                if (userStart < existingEnd && existingStart < userEnd) {
                    clashingRecord = booking;
                }
            });

            if (clashingRecord) {
                openClashModal(clashingRecord);
                e.preventDefault();
                return;
            }
        };

        document.getElementById('nextMonth').onclick = () => {
            if(curMonth < 11) curMonth++; else { curMonth = 0; curYear++; }
            renderCalendar();
        };

        updateTotal();
        renderCalendar();
    </script>
</x-app-layout>