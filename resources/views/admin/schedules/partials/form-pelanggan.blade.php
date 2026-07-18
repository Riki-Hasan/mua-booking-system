<div class="lg:col-span-7 bg-white p-6 sm:p-8 rounded-[2rem] shadow-md border border-pink-100">
    <h1 class="text-xl font-bold text-gray-900 tracking-tight mb-6 uppercase border-b border-gray-100 pb-4">Input Data Pelanggan</h1>
    
    <div class="grid-form-parent">
        <div class="div1">
            <label class="block text-xs font-bold text-gray-700 uppercase mb-2 ml-1">Nama Lengkap</label>
            <input type="text" name="customer_name" placeholder="Nama Client" class="w-full p-3.5 outline-none focus:ring-2 focus:ring-pink-500 font-semibold text-sm text-gray-900" required>
        </div>
        <div class="div2">
            <label class="block text-xs font-bold text-gray-700 uppercase mb-2 ml-1">Nomor WhatsApp</label>
            <input type="number" name="whatsapp_number" placeholder="08xxxxxxxx" class="w-full p-3.5 outline-none focus:ring-2 focus:ring-pink-500 font-semibold text-sm text-gray-900" required>
        </div>
        <div class="div3">
            <label class="block text-xs font-bold text-gray-700 uppercase mb-2 ml-1">Alamat Lengkap</label>
            <input type="text" name="address" placeholder="Contoh: Jl. Pancasakti No. 1, Tegal" class="w-full p-3.5 outline-none focus:ring-2 focus:ring-pink-500 font-semibold text-sm text-gray-900" required>
        </div>
        <div class="div4">
            <label class="block text-xs font-bold text-gray-700 uppercase mb-2 ml-1 text-center">Jml Orang (Max 2)</label>
            <input type="number" name="person_count" id="person_count" value="1" min="1" max="2" oninput="updateTotal()" class="w-full border border-gray-300 rounded-2xl p-3.5 outline-none focus:ring-2 focus:ring-pink-500 font-bold text-center text-sm text-gray-900">
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
        
        <div class="div7 pt-2">
            <div class="bg-gray-50 rounded-2xl p-5 sm:p-6 text-gray-900 border-2 border-gray-200 shadow-sm relative overflow-hidden">
                <div class="summary-parent">
                    <div class="s-div1 border-b border-gray-200 pb-3 mb-1">
                        <span class="text-sm font-black text-pink-700 uppercase tracking-wider">Ringkasan Biaya</span>
                    </div>
                    
                    <div class="s-div2 flex flex-col gap-2.5 text-sm font-black text-gray-900 uppercase">
                        <p>Subtotal</p>
                        <p>Jumlah Orang</p>
                        <p class="border-t border-gray-200 pt-2">Biaya Transport (Ongkir)</p>
                        <p class="text-base font-black text-gray-900 pt-2">Total Bayar</p>
                    </div>
                    
                    <div class="s-div3 flex flex-col gap-2.5 text-sm font-bold text-right text-gray-900">
                        <p id="display_subtotal">Rp0</p>
                        <p id="display_multiplier" class="text-pink-700">X1</p>
                        <p id="display_ongkir" class="text-pink-700 border-t border-gray-200 pt-2">Rp0</p>
                        <p class="text-xl text-pink-700 font-extrabold pt-1">Rp<span id="display_total">0</span></p>
                    </div>
                    
                    <div class="s-div4 pt-4">
                        <label class="block mb-2 font-black text-xs uppercase text-gray-900">Metode Pembayaran</label>
                        <div class="select-wrapper">
                            <select name="payment_method" id="payMethod" onchange="togglePaymentInput()" class="p-3 bg-white border border-gray-300 text-sm font-bold text-gray-900 outline-none rounded-xl w-full">
                                <option value="cash">Tunai (Cash)</option>
                                <option value="qris">QRIS / Transfer</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="s-div5 pt-4" id="payment_input_area_fixed">
                        <div id="area_cash_input">
                            <label class="block text-xs font-black text-gray-900 uppercase mb-2 ml-1">Nominal Tunai</label>
                            <input type="number" name="dp_amount_cash" id="dpInputCash" placeholder="Masukkan Nominal" class="w-full bg-white border border-slate-300 rounded-xl p-3 text-sm text-gray-900 outline-none focus:ring-2 focus:ring-pink-500 font-bold" style="border: 2px solid #cbd5e1 !important;">
                        </div>
                        <div id="area_qris_input" class="hidden" style="display: none;">
                            <label class="block text-xs font-black text-gray-900 uppercase mb-2 ml-1">Pilihan Nominal Bayar</label>
                            <div class="select-wrapper">
                                <select name="dp_amount_qris" id="dpInputQris" class="p-3 bg-white border border-gray-300 text-gray-900 rounded-xl text-xs font-bold outline-none w-full">
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="div8">
            <button type="submit" id="btnConfirm" class="w-full bg-emerald-600 text-white font-bold py-4 rounded-2xl hover:bg-emerald-700 transition-all shadow-md active:scale-95 uppercase text-sm tracking-wider">Konfirmasi & Simpan Jadwal →</button>
        </div>
        <div class="div9">
            <button type="button" id="btnHoliday" onclick="toggleHoliday()" class="w-full hidden bg-white text-rose-600 border-2 border-rose-200 font-bold py-3.5 rounded-2xl hover:bg-rose-600 hover:text-white transition-all text-xs uppercase tracking-wide" style="display: none;"><span id="holidayText">Liburkan Hari Ini</span></button>
        </div>
    </div>
</div>