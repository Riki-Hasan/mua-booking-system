<div id="statusModal" class="hidden no-print" style="display: none;">
    <div id="statusModalContent" class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border-2 border-gray-200 text-center mx-4" style="display: block !important;">
        <div id="statusIconBox" class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
            <i id="statusIcon" class="text-2xl fa-solid"></i>
        </div>
        <h3 id="statusTitle" class="text-xl font-bold text-gray-900 uppercase mb-2">Status</h3>
        <p id="statusDescription" class="text-sm text-gray-600 font-semibold mb-6 leading-relaxed"></p>
        <button id="statusBtn" type="button" onclick="closeStatusModal()" class="w-full bg-slate-900 text-white font-bold py-3.5 rounded-xl hover:opacity-90 transition-all uppercase tracking-wider text-xs">Tutup</button>
    </div>
</div>

<div id="confirmModal" class="hidden no-print" style="display: none;">
    <div id="confirmModalContent" class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border-2 border-gray-100 text-center mx-4" style="display: block !important;">
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