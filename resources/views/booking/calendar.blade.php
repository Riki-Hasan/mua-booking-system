<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Jadwal - MUA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .custom-scroll::-webkit-scrollbar { width: 3px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: rgba(236, 72, 153, 0.4); border-radius: 10px; }
        input[type="time"]::-webkit-calendar-picker-indicator {
            filter: invert(100%) sepia(0%) saturate(0%) hue-rotate(0deg) brightness(100%) contrast(100%);
            cursor: pointer; transform: scale(1.2);
        }
        
        /* 🚨 FIX KOTAK PERSEGI PANJANG TABLET: Memaksa tinggi tombol tanggal proporsional 1:1 di seluruh WebView */
        .day-btn {
            display: block !important; 
            width: 100% !important; 
            height: 52px !important; 
            line-height: 20px !important;
            font-size: 13px !important; 
            font-weight: 900 !important;
            border-radius: 14px !important;
            text-align: center !important;
            border: none !important;
            box-sizing: border-box !important;
        }

        .holiday-active { 
            background-color: #e11d48 !important; 
            color: white !important; 
            border: 3px solid #fff1f2 !important; 
            box-shadow: 0 4px 6px -1px rgba(225, 29, 72, 0.2) !important; 
            cursor: not-allowed;
        }

        #clashModal {
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
<body class="bg-pink-50 min-h-screen p-3 md:p-6 font-sans">
    <div class="max-w-5xl mx-auto flex flex-col lg:flex-row bg-white rounded-3xl lg:rounded-[4rem] shadow-2xl overflow-hidden border border-pink-100">
        
        <div class="flex-1 p-6 md:p-12 lg:p-16 border-b lg:border-b-0 lg:border-r border-pink-50">
            <div class="mb-8 md:mb-10 text-center relative">
                <div class="flex justify-between items-center mb-2">
                    <button type="button" id="prevMonth" class="bg-pink-50 hover:bg-pink-500 hover:text-white text-pink-500 px-4 py-3 rounded-2xl font-black text-[10px] uppercase transition-all hidden active:scale-95 border border-pink-100" onclick="changeMonth(-1)" style="display: none;">
                        &larr; Back
                    </button>
                    <h3 id="calendarTitle" class="text-2xl md:text-3xl font-black italic uppercase text-gray-900 tracking-tighter flex-1 text-center">Bulan Tahun</h3>
                    <button type="button" id="nextMonthBtn" class="bg-pink-50 hover:bg-pink-500 hover:text-white text-pink-500 px-4 py-3 rounded-2xl font-black text-[10px] uppercase transition-all active:scale-95 border border-pink-100" onclick="changeMonth(1)">
                        Next &rarr;
                    </button>
                </div>
                <p class="text-[10px] font-black text-pink-700 uppercase tracking-widest mt-1 italic">Ketuk tanggal untuk memilih</p>
            </div>

            <!-- Keterbacaan teks header hari dihitamkan pekat -->
            <div id="calendarGrid" class="grid grid-cols-7 gap-2 text-center mb-8">
                @foreach(['S','S','R','K','J','S','M'] as $hari)
                    <div class="text-xs font-black text-gray-800 uppercase mb-2">{{ $hari }}</div>
                @endforeach
            </div>
        </div>

        <div class="w-full lg:w-[420px] bg-slate-950 p-8 md:p-12 text-white flex flex-col justify-between relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-pink-500/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
            
            <div class="relative z-10">
                <p class="text-pink-500 font-black text-[10px] uppercase tracking-[0.3em] mb-3 italic">Ketersediaan Slot</p>
                <h2 id="selectedDateText" class="text-4xl md:text-5xl font-black italic tracking-tighter mb-4 text-white">-</h2>
                <div id="statusInfo" class="text-gray-300 font-bold leading-relaxed text-xs md:text-sm mb-6">Silakan pilih tanggal pada kalender.</div>

                <div id="bookingTableArea" class="hidden mb-8" style="display: none;">
                    <p class="text-[10px] font-black uppercase text-pink-400 mb-3 tracking-widest italic underline underline-offset-4">Jadwal Terisi:</p>
                    <div class="bg-white/5 rounded-2xl p-4 border border-white/10">
                        <table class="w-full text-left text-[11px]">
                            <thead>
                                <tr class="text-gray-400 uppercase font-black">
                                    <th class="pb-2">No</th>
                                    <th class="pb-2">Mulai</th>
                                    <th class="pb-2">Selesai</th>
                                </tr>
                            </thead>
                            <tbody id="bookingTableBody" class="text-gray-200 font-bold"></tbody>
                        </table>
                    </div>
                </div>
                
                <div id="timeInputArea" class="hidden space-y-4" style="display: none;">
                    <label class="block text-[10px] font-black text-pink-400 uppercase tracking-widest ml-1">Atur Jam Mulai Rias</label>
                    <div class="relative">
                        <input type="time" id="timeValue" class="w-full bg-white/10 border border-white/20 rounded-2xl p-5 md:p-6 text-white text-3xl md:text-4xl font-black text-center focus:ring-4 focus:ring-pink-500/30 transition-all outline-none" style="background-color: rgba(255,255,255,0.1) !important; color:#ffffff !important;">
                    </div>
                    @php
                        $persons = request('person_count', 1);
                        $multiplier = ($persons >= 2) ? 1.5 : 1.0;
                        $calculatedDuration = (int)($category->duration_minutes * $multiplier);
                    @endphp
                    <p class="text-[10px] text-gray-400 text-center italic leading-relaxed">
                        Estimasi durasi ({{ $persons }} Orang): <strong>{{ $calculatedDuration }} menit</strong>.
                    </p>
                </div>
            </div>

            <div class="relative z-10 flex flex-col gap-3 mt-10 lg:mt-0">
                <button type="button" onclick="confirmSelection()" class="w-full bg-emerald-500 text-white font-black py-5 rounded-2xl hover:bg-emerald-600 transition-all shadow-xl uppercase tracking-widest text-[10px] active:scale-95">Konfirmasi Jadwal</button>
                <a href="{{ route('booking.create', $category->id) }}" class="w-full bg-white/5 text-gray-400 text-center font-black py-4 rounded-2xl hover:bg-white/10 transition-all uppercase tracking-widest text-[9px]">Kembali</a>
            </div>
        </div>
    </div>

    <!-- MODAL NATIVE BYPASS BANNER JAM TABRAK -->
    <div id="clashModal" class="hidden no-print" style="display: none;">
        <div id="clashModalContent" class="bg-white rounded-[2.5rem] max-w-sm w-full p-8 shadow-2xl border-4 border-rose-100 text-center mx-4" style="display: block !important;">
            <div class="w-20 h-20 bg-rose-100 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fa-solid fa-calendar-xmark text-3xl"></i>
            </div>
            <h3 class="text-2xl font-black text-gray-900 uppercase italic tracking-tighter mb-2">Jadwal Bentrok!</h3>
            <p id="clashMessage" class="text-sm text-gray-900 font-bold leading-relaxed mb-8"></p>
            <button type="button" onclick="closeClashModal()" class="w-full bg-slate-900 text-white font-black py-4 rounded-2xl hover:bg-rose-500 transition-all uppercase tracking-widest text-xs">Oke, Cari Jam Lain</button>
        </div>
    </div>

    <script>
        let selectedDay = null;
        let dayBookings = []; 

        const serviceDuration = {{ $calculatedDuration }};
        const personCount = {{ $persons }};
        const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        
        let currentDate = new Date();
        let currentMonth = currentDate.getMonth();
        let currentYear = currentDate.getFullYear();

        function changeMonth(step) {
            currentMonth += step;
            if (currentMonth > 11) { currentMonth = 0; currentYear++; } 
            else if (currentMonth < 0) { currentMonth = 11; currentYear--; }
            renderCalendar();
        }

        async function renderCalendar() {
            const grid = document.getElementById('calendarGrid');
            var t = new Date();
            var nowTimestamp = new Date(t.getFullYear(), t.getMonth(), t.getDate()).getTime();
            
            document.getElementById('calendarTitle').innerText = `${monthNames[currentMonth]} ${currentYear}`;
            
            const prevBtn = document.getElementById('prevMonth');
            if (currentYear < t.getFullYear() || (currentYear === t.getFullYear() && currentMonth <= t.getMonth())) {
                prevBtn.style.setProperty('display', 'none', 'important');
            } else {
                prevBtn.style.setProperty('display', 'block', 'important');
            }
            
            const response = await fetch(`/api/check-availability?month=${currentMonth + 1}&year=${currentYear}`);
            const availability = await response.json();
            const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
            
            grid.querySelectorAll('.day-btn').forEach(b => b.remove());

            for (let i = 1; i <= daysInMonth; i++) {
                const btn = document.createElement('button');
                var checkTimestamp = new Date(currentYear, currentMonth, i).getTime();

                btn.type = "button";
                btn.innerText = i;
                
                if (checkTimestamp < nowTimestamp) {
                    btn.className = "day-btn p-2 opacity-20 cursor-not-allowed bg-gray-100 text-gray-400";
                    btn.disabled = true;
                } else if (availability[i]) {
                    if (availability[i].status === 'full') {
                        btn.className = "day-btn p-2 bg-rose-500 text-white";
                        btn.onclick = () => showInfo(i, 'full', availability[i].details);
                    } else if (availability[i].status === 'holiday') {
                        btn.className = "day-btn holiday-active";
                        btn.disabled = true;
                        btn.onclick = null;
                    } else {
                        btn.className = "day-btn p-2 bg-amber-400 text-white";
                        btn.onclick = () => showInfo(i, 'partial', availability[i].details);
                    }
                } else {
                    btn.className = "day-btn p-2 bg-slate-100 text-slate-800 border border-transparent";
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

            tableArea.style.setProperty('display', 'none', 'important');
            timeArea.style.setProperty('display', 'none', 'important');

            if (status === 'free') {
                info.innerText = "Tersedia! Silakan atur jam mulai rias Anda.";
                timeArea.style.setProperty('display', 'block', 'important');
            } else {
                info.innerText = status === 'partial' ? "Beberapa jam sudah terisi." : "Maaf, hari ini sudah penuh.";
                tableArea.style.setProperty('display', 'block', 'important');
                if(status === 'partial') timeArea.style.setProperty('display', 'block', 'important');
                details.forEach((d, idx) => {
                    tableBody.innerHTML += `<tr class="border-b border-white/5"><td class="py-2">${idx+1}</td><td class="py-2">${d.start}</td><td class="py-2 font-black text-pink-400">${d.end}</td></tr>`;
                });
            }
        }

        function openClashModal(clashingWith) {
            const modal = document.getElementById('clashModal');
            const msg = document.getElementById('clashMessage');
            msg.innerHTML = `Wah, jam tersebut bentrok dengan jadwal:<br><strong class="text-rose-600 text-lg uppercase">${clashingWith.start} - ${clashingWith.end}</strong><br>Coba cari celah jam lain ya ✨`;
            if(modal) { modal.style.setProperty('display', 'flex', 'important'); }
        }

        function closeClashModal() {
            const modal = document.getElementById('clashModal');
            if(modal) { modal.style.setProperty('display', 'none', 'important'); }
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
                if (userStart < existingEnd && existingStart < userEnd) { clashingRecord = booking; }
            });

            if (clashingRecord) { openClashModal(clashingRecord); return; }
            const dateStr = `${selectedDay}-${monthNames[currentMonth]}-${currentYear}`;
            window.location.href = `{{ route('booking.create', $category->id) }}?date=${dateStr}&time=${timeInput}&person_count=${personCount}`;
        }

        document.addEventListener('DOMContentLoaded', () => { renderCalendar(); });
    </script>
</body>
</html>