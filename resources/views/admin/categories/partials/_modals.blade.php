<div id="modalEditBundling" class="fixed inset-0 z-[110] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="bg-white rounded-[2.5rem] max-w-2xl w-full p-8 shadow-2xl border-4 border-blue-50 animate-pop overflow-y-auto max-h-[90vh]">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-black text-gray-900 uppercase italic tracking-tighter">Edit Promo Bundling</h3>
            <button onclick="closeEditBundlingModal()" class="text-gray-400 hover:text-rose-500"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>
        <form id="editBundlingForm" onsubmit="event.preventDefault(); submitEditBundlingAjax();" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf @method('PUT')
            <input type="hidden" name="current_tab" :value="tab">
            <div class="md:col-span-1">
                <label class="block text-[9px] font-black text-gray-400 uppercase mb-2 ml-1">Nama Paket</label>
                <input type="text" name="subject" id="edit_bundling_subject" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-blue-400 outline-none" required>
            </div>
            <div>
                <label class="block text-[9px] font-black text-gray-400 uppercase mb-2 ml-1">Harga (IDR)</label>
                <input type="number" name="price" id="edit_bundling_price" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 text-sm font-bold outline-none" required>
            </div>
            <div>
                <label class="block text-[9px] font-black text-gray-400 uppercase mb-2 ml-1">Foto Utama</label>
                <input type="file" name="main_image" class="w-full text-[9px] font-bold text-gray-400 file:bg-blue-50 file:text-blue-600 file:border-0 file:rounded-xl file:px-4 file:py-2">
            </div>
            <div>
                <label class="block text-[9px] font-black text-gray-400 uppercase mb-2 ml-1">Foto Kecil</label>
                <input type="file" name="secondary_image" class="w-full text-[9px] font-bold text-gray-400 file:bg-blue-50 file:text-blue-600 file:border-0 file:rounded-xl file:px-4 file:py-2">
            </div>
            <div class="md:col-span-2">
                <label class="block text-[9px] font-black text-gray-400 uppercase mb-2 ml-1">Short Description</label>
                <input type="text" name="short_description" id="edit_bundling_short" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 text-sm font-bold outline-none" required>
            </div>
            <div class="md:col-span-2">
                <label class="block text-[9px] font-black text-gray-400 uppercase mb-2 ml-1">Deskripsi Lengkap</label>
                <textarea name="description" id="edit_bundling_desc" rows="3" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 text-sm font-bold outline-none"></textarea>
            </div>
            <div>
                <label class="block text-[9px] font-black text-gray-400 uppercase mb-2 ml-1">Durasi (Menit)</label>
                <input type="number" id="edit_bundling_duration" name="duration_minutes" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 text-sm font-bold outline-none" required>
            </div>
            <div>
                <label class="block text-[9px] font-black text-gray-400 uppercase mb-2 ml-1">Kapasitas</label>
                <select name="target_person_count" id="edit_bundling_target" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 text-sm font-bold outline-none">
                    <option value="1">1 Orang</option>
                    <option value="2">2 Orang</option>
                </select>
            </div>
            <div class="md:col-span-2 flex gap-3 mt-4">
                <button type="button" onclick="closeEditBundlingModal()" class="flex-1 bg-gray-100 text-gray-400 font-black py-4 rounded-2xl uppercase text-[10px]">Batal</button>
                <button type="submit" class="flex-1 bg-blue-500 text-white font-black py-4 rounded-2xl shadow-lg uppercase text-[10px] tracking-widest">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div id="modalDelete" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="bg-white rounded-[2.5rem] max-w-sm w-full p-8 shadow-2xl text-center border-4 border-rose-50 animate-pop">
        <div class="w-20 h-20 bg-rose-100 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fa-solid fa-trash-can text-3xl"></i>
        </div>
        <h3 class="text-2xl font-black text-gray-900 uppercase italic tracking-tighter mb-2">Hapus Data?</h3>
        <p class="text-sm text-gray-500 mb-8">Yakin ingin menghapus <strong id="deletePackageName"></strong>?</p>
        <div class="flex gap-3">
            <button onclick="closeModal('modalDelete')" class="flex-1 bg-gray-100 text-gray-400 font-black py-4 rounded-2xl uppercase text-[10px]">Batal</button>
            <button type="button" onclick="submitDeleteAjax()" class="flex-1 bg-rose-500 text-white font-black py-4 rounded-2xl shadow-lg uppercase text-[10px]">Ya, Hapus!</button>
        </div>
    </div>
</div>

<div id="modalEdit" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="bg-white rounded-[2.5rem] max-w-md w-full p-8 shadow-2xl border-4 border-blue-50 animate-pop">
        <h3 class="text-2xl font-black text-gray-900 uppercase italic tracking-tighter mb-6 text-center">Edit Paket</h3>
        <form id="editForm" onsubmit="event.preventDefault(); submitEditAjax();" class="space-y-4">
            @csrf @method('PUT')
            <input type="hidden" name="current_tab" :value="tab">
            <div>
                <label class="text-[9px] font-black text-gray-400 uppercase mb-2 ml-1 block">Nama Paket</label>
                <input type="text" name="name" id="edit_name" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 text-sm font-bold focus:ring-2 focus:ring-blue-400 outline-none" required>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[9px] font-black text-gray-400 uppercase mb-2 ml-1 block">Harga</label>
                    <input type="number" name="base_price" id="edit_price" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 text-sm font-bold outline-none" required>
                </div>
                <div>
                    <label class="text-[9px] font-black text-gray-400 uppercase mb-2 ml-1 block">Durasi</label>
                    <input type="number" name="duration_minutes" id="edit_duration" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 text-sm font-bold outline-none" required>
                </div>
            </div>
            <div class="flex gap-3 mt-8">
                <button type="button" onclick="closeModal('modalEdit')" class="flex-1 bg-gray-100 text-gray-400 font-black py-4 rounded-2xl uppercase text-[10px]">Batal</button>
                <button type="submit" class="flex-1 bg-blue-500 text-white font-black py-4 rounded-2xl shadow-lg uppercase text-[10px]">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div id="modalStatus" class="fixed inset-0 z-[110] hidden items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm">
    <div class="bg-white rounded-[2.5rem] max-w-sm w-full p-10 shadow-2xl text-center animate-pop">
        <div id="statusIcon" class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6"></div>
        <h3 id="statusTitle" class="text-2xl font-black text-gray-900 uppercase italic tracking-tighter mb-2"></h3>
        <p id="statusMsg" class="text-sm text-gray-500 mb-8"></p>
        <button onclick="closeModal('modalStatus')" class="w-full bg-slate-900 text-white font-black py-4 rounded-2xl uppercase text-[10px]">Oke</button>
    </div>
</div>

<div id="modalLimit" class="fixed inset-0 z-[120] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="bg-white rounded-[2.5rem] max-w-sm w-full p-8 shadow-2xl text-center border-4 border-rose-50 animate-pop">
        <div class="w-20 h-20 bg-rose-100 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fa-solid fa-file-circle-exclamation text-3xl"></i>
        </div>
        <h3 class="text-2xl font-black text-gray-900 uppercase italic tracking-tighter mb-2">File Kegedean!</h3>
        <p class="text-sm text-gray-500 mb-8">Batas maksimal hanya **2MB**. Foto ini ukurannya <span id="fileSizeLabel" class="font-bold text-rose-500"></span>. Tolong kompres dulu ya.</p>
        <button onclick="closeModal('modalLimit')" class="w-full bg-slate-900 text-white font-black py-4 rounded-2xl uppercase text-[10px]">Siap, Saya Ganti</button>
    </div>
</div>