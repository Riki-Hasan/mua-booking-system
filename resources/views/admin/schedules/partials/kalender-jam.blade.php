<div class="lg:col-span-5 flex flex-col gap-6">
    <div class="bg-white p-6 rounded-[2rem] shadow-md border border-pink-50">
        <div class="flex justify-between items-center mb-6">
            <button type="button" id="prevMonth" class="bg-pink-50 text-pink-700 px-3 py-2 rounded-xl font-bold text-xs hidden" onclick="changeMonth(-1)" style="display: none;">&larr; Back</button>
            <h3 id="calTitle" class="text-lg font-bold uppercase text-gray-900 tracking-tight">Bulan 2026</h3>
            <button type="button" id="nextMonth" class="bg-pink-50 text-pink-700 px-4 py-2 rounded-xl font-bold text-xs uppercase active:scale-90" onclick="changeMonth(1)">Next →</button>
        </div>
        <div class="grid grid-cols-7 gap-2 text-center mb-3 border-b border-gray-100 pb-2">
            @foreach(['S','S','R','K','J','S','M'] as $h) <div class="font-bold text-gray-400 text-xs">{{$h}}</div> @endforeach
        </div>
        <div id="calGridContent" class="grid grid-cols-7 gap-2 text-center"></div>
    </div>

    <div id="timeSection" class="bg-slate-900 p-6 sm:p-8 rounded-[2rem] text-white hidden shadow-md text-center transition-all" style="display: none;">
         <p id="dateDisplay" class="text-pink-400 font-bold text-xs uppercase mb-2 tracking-wider">-</p>
         <h4 class="text-xl font-bold mb-6 uppercase text-white">Atur Jam Rias</h4>
         <input type="time" id="timeValue" class="w-full bg-white/10 border border-white/20 rounded-2xl p-4 text-3xl font-bold text-center outline-none focus:ring-4 focus:ring-pink-500 transition-all text-white">
         
         <div id="bookedTable" class="hidden mt-6 bg-white/5 rounded-xl p-4 border border-white/10 text-left text-xs" style="display: none;">
            <p class="text-pink-400 font-bold uppercase mb-3 tracking-wider underline underline-offset-4">Slot Terisi:</p>
            <table class="w-full text-gray-200">
                <thead><tr class="text-gray-400 uppercase font-bold border-b border-white/10"> <th class="pb-1 text-left">No</th> <th class="pb-1 text-left">Mulai</th> <th class="pb-1 text-right">Selesai</th> </tr></thead>
                <tbody id="bookedList" class="divide-y divide-white/5"></tbody>
            </table>
         </div>
    </div>
</div>