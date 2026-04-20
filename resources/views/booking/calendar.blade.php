<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Jadwal - MUA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        select {
            -webkit-appearance: none; -moz-appearance: none; appearance: none;
            background: transparent; border: none; outline: none;
            text-align: center; text-align-last: center; 
            cursor: pointer; width: 100%;
        }
        .select-wrapper { position: relative; display: flex; align-items: center; }
        .select-wrapper::after {
            content: '▼'; font-size: 8px; position: absolute;
            right: 15px; pointer-events: none; color: #9ca3af;
        }
        .custom-scroll::-webkit-scrollbar { width: 3px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: rgba(236, 72, 153, 0.4); border-radius: 10px; }
        select option { background-color: #0f172a; color: white; padding: 10px; }
        input[type="time"]::-webkit-calendar-picker-indicator {
            filter: invert(100%) sepia(0%) saturate(0%) hue-rotate(0deg) brightness(100%) contrast(100%);
            cursor: pointer; transform: scale(1.2);
        }
        .day-btn {
            aspect-ratio: 1 / 1; display: flex; align-items: center;
            justify-content: center; font-size: 0.75rem;
        }

        /* ANIMASI MODAL & SHAKE */
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
        .animate-shake { animation: shake 0.4s ease-in-out; }

        .holiday-active { 
            background-color: #e11d48 !important; 
            color: white !important; 
            border: 4px solid #fff1f2 !important; 
            box-shadow: 0 10px 15px -3px rgba(225, 29, 72, 0.4); 
            cursor: not-allowed;
        }
    </style>
</head>
<body class="bg-pink-50 min-h-screen p-3 md:p-6 font-sans">
    <div class="max-w-5xl mx-auto flex flex-col lg:flex-row bg-white rounded-3xl lg:rounded-[4rem] shadow-2xl overflow-hidden border border-pink-100">
        
        <div class="flex-1 p-6 md:p-12 lg:p-16 border-b lg:border-b-0 lg:border-r border-pink-50">
            <div class="flex gap-2 md:gap-4 mb-6 md:mb-10">
                <div class="flex-1 select-wrapper bg-gray-50 rounded-2xl border border-gray-100">
                    <select id="monthSelect" class="p-3 md:p-4 text-[10px] md:text-xs font-black uppercase outline-none"></select>
                </div>
                <div class="w-24 md:w-32 select-wrapper bg-gray-50 rounded-2xl border border-gray-100">
                    <select id="yearSelect" class="p-3 md:p-4 text-[10px] md:text-xs font-black uppercase outline-none"></select>
                </div>
            </div>

            <div class="mb-6 md:mb-10">
                <h3 id="calendarTitle" class="text-2xl md:text-3xl font-black italic uppercase text-gray-900 tracking-tighter">Bulan Tahun</h3>
                <p class="text-[9px] md:text-[10px] font-black text-pink-500 uppercase tracking-widest mt-1 italic">Ketuk tanggal untuk memilih</p>
            </div>

            <div id="calendarGrid" class="grid grid-cols-7 gap-1 md:gap-3 text-center mb-8">
                @foreach(['S','S','R','K','J','S','M'] as $hari)
                    <div class="text-[10px] font-black text-gray-400 mb-2">{{ $hari }}</div>
                @endforeach
            </div>

            <div class="flex justify-center">
                <button type="button" id="nextMonth" class="w-full md:w-auto bg-pink-50 text-pink-500 px-8 py-4 rounded-2xl flex items-center justify-center gap-3 font-black hover:bg-pink-500 hover:text-white transition-all border border-pink-100 shadow-sm text-[10px] uppercase tracking-widest active:scale-95">
                    Bulan Berikutnya →
                </button>
            </div>
        </div>

        <div class="w-full lg:w-[420px] bg-slate-950 p-8 md:p-12 text-white flex flex-col justify-between relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-pink-500/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
            
            <div class="relative z-10">
                <p class="text-pink-500 font-black text-[10px] uppercase tracking-[0.3em] mb-3 italic">Ketersediaan Slot</p>
                <h2 id="selectedDateText" class="text-4xl md:text-5xl font-black italic tracking-tighter mb-4 text-white">-</h2>
                <div id="statusInfo" class="text-gray-400 leading-relaxed text-xs md:text-sm mb-6">Silakan pilih tanggal pada kalender.</div>

                <div id="bookingTableArea" class="hidden mb-8">
                    <p class="text-[10px] font-black uppercase text-pink-400 mb-3 tracking-widest italic underline underline-offset-4">Jadwal Terisi:</p>
                    <div class="bg-white/5 rounded-2xl p-4 border border-white/10">
                        <table class="w-full text-left text-[11px]">
                            <thead>
                                <tr class="text-gray-500 uppercase font-black">
                                    <th class="pb-2">No</th>
                                    <th class="pb-2">Mulai</th>
                                    <th class="pb-2">Selesai</th>
                                </tr>
                            </thead>
                            <tbody id="bookingTableBody" class="text-gray-200 font-bold"></tbody>
                        </table>
                    </div>
                </div>
                
                <div id="timeInputArea" class="hidden space-y-4">
                    <label class="block text-[10px] font-black text-pink-400 uppercase tracking-widest ml-1">
                        Atur Jam Mulai Rias
                    </label>
                    <div class="relative">
                        <input type="time" id="timeValue" 
                               class="w-full bg-white/10 border border-white/20 rounded-2xl p-5 md:p-6 text-white text-3xl md:text-4xl font-black text-center focus:ring-4 focus:ring-pink-500/30 transition-all outline-none">
                    </div>
                    <p class="text-[9px] text-gray-400 text-center italic leading-relaxed">
                        Estimasi durasi: <strong>{{ $category->duration_minutes }} menit</strong>.
                    </p>
                </div>
            </div>

            <div class="relative z-10 flex flex-col gap-3 mt-10 lg:mt-0">
                <button type="button" onclick="confirmSelection()" class="w-full bg-emerald-500 text-white font-black py-5 rounded-2xl hover:bg-emerald-600 transition-all shadow-xl shadow-emerald-900/20 uppercase tracking-widest text-[10px] active:scale-95">
                    Konfirmasi Jadwal
                </button>
                <a href="{{ route('booking.create', $category->id) }}" class="w-full bg-white/5 text-gray-400 text-center font-black py-4 rounded-2xl hover:bg-white/10 transition-all uppercase tracking-widest text-[9px]">
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <div id="clashModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm transition-all duration-300">
        <div id="clashModalContent" class="bg-white rounded-[2.5rem] max-w-sm w-full p-8 shadow-2xl transform scale-90 opacity-0 transition-all duration-300 border-4 border-rose-100 text-center">
            <div class="w-20 h-20 bg-rose-100 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-6 animate-bounce">
                <i class="fa-solid fa-calendar-xmark text-3xl"></i>
            </div>
            <h3 class="text-2xl font-black text-gray-900 uppercase italic tracking-tighter mb-2">Jadwal Bentrok!</h3>
            <p id="clashMessage" class="text-sm text-gray-500 font-medium leading-relaxed mb-8"></p>
            <button type="button" onclick="closeClashModal()" class="w-full bg-slate-900 text-white font-black py-4 rounded-2xl hover:bg-rose-500 transition-all uppercase tracking-widest text-xs">
                Oke, Cari Jam Lain
            </button>
        </div>
    </div>

    <script>
        let selectedDay = null;
        let dayBookings = []; 
        const serviceDuration = {{ $category->duration_minutes }};
        const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        let currentMonth = new Date().getMonth();
        let currentYear = 2026;

        function initSelectors() {
            const mSelect = document.getElementById('monthSelect');
            const ySelect = document.getElementById('yearSelect');
            const now = new Date();

            monthNames.forEach((m, i) => { mSelect.add(new Option(m, i)); });
            [2026, 2027].forEach(y => ySelect.add(new Option(y, y)));
            
            mSelect.value = currentMonth;
            ySelect.value = currentYear;
            
            const updateMonthStatus = () => {
                const year = parseInt(ySelect.value);
                Array.from(mSelect.options).forEach((opt, i) => {
                    opt.disabled = (year === now.getFullYear() && i < now.getMonth());
                });
            };

            updateMonthStatus();
            mSelect.onchange = () => { currentMonth = parseInt(mSelect.value); renderCalendar(); };
            ySelect.onchange = () => { currentYear = parseInt(ySelect.value); updateMonthStatus(); renderCalendar(); };
            
            document.getElementById('nextMonth').onclick = () => {
                if(currentMonth < 11) { 
                    currentMonth++; mSelect.value = currentMonth; renderCalendar(); 
                } else if(currentYear === 2026) {
                    currentYear = 2027; currentMonth = 0;
                    ySelect.value = currentYear; mSelect.value = currentMonth;
                    updateMonthStatus(); renderCalendar();
                }
            };
        }

        async function renderCalendar() {
            const grid = document.getElementById('calendarGrid');
            const now = new Date();
            now.setHours(0,0,0,0);
            document.getElementById('calendarTitle').innerText = `${monthNames[currentMonth]} ${currentYear}`;
            
            const response = await fetch(`/api/check-availability?month=${currentMonth + 1}&year=${currentYear}`);
            const availability = await response.json();
            
            const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
            grid.querySelectorAll('.day-btn').forEach(b => b.remove());

            for (let i = 1; i <= daysInMonth; i++) {
                const btn = document.createElement('button');
                const dateToCheck = new Date(currentYear, currentMonth, i);

                btn.type = "button";
                btn.innerText = i;
                btn.className = "day-btn p-2 md:p-4 text-[11px] md:text-xs font-black rounded-xl md:rounded-2xl transition-all ";
                
                if (dateToCheck < now) {
                    btn.className += "opacity-20 cursor-not-allowed bg-gray-100 text-gray-400";
                    btn.disabled = true;
                } else if (availability[i]) {
                    if (availability[i].status === 'full') {
                        btn.className += "bg-rose-500 text-white";
                        btn.onclick = () => showInfo(i, 'full', availability[i].details);
                    } else if (availability[i].status === 'holiday') {
                        btn.classList.add('holiday-active');
                        btn.disabled = true; // MATIKAN KLIK UNTUK CUSTOMER
                        btn.title = "Maaf, hari ini kami libur";
                        btn.onclick = null;
                    }else {
                        btn.className += "bg-amber-400 text-white";
                        btn.onclick = () => showInfo(i, 'partial', availability[i].details);
                    }
                } else {
                    btn.className += "bg-slate-100 text-slate-800 hover:bg-pink-500 hover:text-white";
                    btn.onclick = () => showInfo(i, 'free', []);
                }
                grid.appendChild(btn);
            }
        }

        function showInfo(day, status, details) {
            selectedDay = day;
            dayBookings = details;
            const dateText = document.getElementById('selectedDateText');
            const info = document.getElementById('statusInfo');
            const tableArea = document.getElementById('bookingTableArea');
            const tableBody = document.getElementById('bookingTableBody');
            const timeArea = document.getElementById('timeInputArea');
            
            dateText.innerText = `${day} ${monthNames[currentMonth]}`;
            tableBody.innerHTML = '';
            tableArea.classList.add('hidden');
            timeArea.classList.add('hidden');

            if (status === 'free') {
                info.innerText = "Tersedia! Silakan atur jam mulai rias Anda.";
                timeArea.classList.remove('hidden');
            } else {
                info.innerText = status === 'partial' ? "Beberapa jam sudah terisi." : "Maaf, hari ini sudah penuh.";
                tableArea.classList.remove('hidden');
                if(status === 'partial') timeArea.classList.remove('hidden');
                details.forEach((d, idx) => {
                    tableBody.innerHTML += `<tr class="border-b border-white/5"><td class="py-2">${idx+1}</td><td class="py-2">${d.start}</td><td class="py-2 font-black text-pink-400">${d.end}</td></tr>`;
                });
            }
        }

        function openClashModal(clashingWith) {
            const modal = document.getElementById('clashModal');
            const content = document.getElementById('clashModalContent');
            const msg = document.getElementById('clashMessage');
            const timeInput = document.getElementById('timeValue');

            msg.innerHTML = `Wah, jam tersebut bentrok dengan jadwal:<br>
                             <strong class="text-rose-500 text-lg uppercase underline decoration-rose-200">
                                ${clashingWith.start} - ${clashingWith.end}
                             </strong><br>
                             Coba cari celah jam lain ya ✨`;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => { content.classList.remove('scale-90', 'opacity-0'); content.classList.add('scale-100', 'opacity-100'); }, 10);

            timeInput.classList.add('animate-shake', 'ring-4', 'ring-rose-500/30');
            setTimeout(() => timeInput.classList.remove('animate-shake'), 400);
        }

        function closeClashModal() {
            const modal = document.getElementById('clashModal');
            const content = document.getElementById('clashModalContent');
            content.classList.add('scale-90', 'opacity-0');
            setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 300);
        }

        function confirmSelection() {
            const timeInput = document.getElementById('timeValue').value;
            if (!selectedDay || !timeInput) {
                alert("Pilih tanggal dan jam rias terlebih dahulu!");
                return;
            }

            const [h, m] = timeInput.split(':').map(Number);
            const userStart = h * 60 + m;
            const userEnd = userStart + serviceDuration;

            let clashingRecord = null;
            dayBookings.forEach(booking => {
                const [bhS, bmS] = booking.start.split(':').map(Number);
                const [bhE, bmE] = booking.end.split(':').map(Number);
                const existingStart = bhS * 60 + bmS;
                const existingEnd = bhE * 60 + bmE;

                if (userStart < existingEnd && existingStart < userEnd) {
                    clashingRecord = booking;
                }
            });

            if (clashingRecord) {
                openClashModal(clashingRecord);
                return;
            }

            const dateStr = `${selectedDay}-${monthNames[currentMonth]}-${currentYear}`;
            window.location.href = `{{ route('booking.create', $category->id) }}?date=${dateStr}&time=${timeInput}`;
        }

        initSelectors();
        renderCalendar();
    </script>
</body>
</html>