<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script>
    var monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    var initMonth = new Date().getMonth();
    var initYear = 2026;
    var curMonth = initMonth;
    var curYear = initYear;
    var dayBookings = [];
    var totalVal = 0;
    var isHoliday = false;

    function showStatusModal(msg, isError) {
        if (typeof isError === 'undefined') { isError = true; }
        var modal = document.getElementById('statusModal');
        var content = document.getElementById('statusModalContent');
        var iconBox = document.getElementById('statusIconBox');
        var icon = document.getElementById('statusIcon');
        var title = document.getElementById('statusTitle');
        var desc = document.getElementById('statusDescription');
        var btn = document.getElementById('statusBtn');

        desc.innerHTML = msg;
        if (isError) {
            content.className = "bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border-2 border-rose-200 text-center mx-4 animate-pop";
            iconBox.className = "w-16 h-16 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-4";
            icon.className = "fa-solid fa-calendar-xmark text-2xl";
            title.innerText = "Peringatan!";
            title.className = "text-xl font-bold text-gray-900 uppercase mb-2";
            if (btn) { btn.style.display = 'block'; }
        } else {
            content.className = "bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl border-2 border-emerald-200 text-center mx-4 animate-pop";
            iconBox.className = "w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4";
            icon.className = "fa-solid fa-calendar-check text-2xl";
            title.innerText = "Berhasil!";
            title.className = "text-xl font-bold text-emerald-700 uppercase mb-2";
            if (btn) { btn.style.display = 'none'; }
        }
        if(modal) {
            modal.style.setProperty('display', 'flex', 'important');
        }
    }

    function closeStatusModal() {
        var modal = document.getElementById('statusModal');
        if(modal) { modal.style.setProperty('display', 'none', 'important'); }
    }

    var holidayCallback = null;
    function showConfirmModal(msg, callback) {
        document.getElementById('confirmDescription').innerHTML = msg;
        holidayCallback = callback;
        var modal = document.getElementById('confirmModal');
        if(modal) { modal.style.setProperty('display', 'flex', 'important'); }
    }

    function closeConfirmModal() {
        var modal = document.getElementById('confirmModal');
        if(modal) { modal.style.setProperty('display', 'none', 'important'); }
    }

    document.getElementById('confirmOkBtn').onclick = function() { 
        if (holidayCallback) { holidayCallback(); } 
        closeConfirmModal(); 
    };

    // 🚨 FIX TOMBOL LIBUR: Menggunakan XMLHttpRequest Klasik Jadul agar Kebal Mampet di Tablet
    function toggleHoliday() {
        var date = document.getElementById('final_date').value;
        if(!date) { showStatusModal("Pilih tanggal di kalender terlebih dahulu!"); return; }
        
        var actionText = isHoliday ? "Membatalkan Libur" : "Meliburkan Jadwal";
        var msg = "Apakah Anda yakin ingin <strong>" + actionText + "</strong> untuk tanggal <strong>" + date + "</strong>?";
        
        showConfirmModal(msg, function() {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "{{ route('admin.schedules.toggle_holiday') }}", true);
            xhr.setRequestHeader("X-CSRF-TOKEN", "{{ csrf_token() }}");
            xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
            
            var formData = new FormData();
            formData.append('date', date);

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        showStatusModal(isHoliday ? "Hari libur berhasil dihapus!" : "Berhasil meliburkan jadwal!", false);
                        setTimeout(function() { location.reload(); }, 1500);
                    } else {
                        showStatusModal("Gagal memproses perubahan jadwal libur.");
                    }
                }
            };
            xhr.send(formData);
        });
    }

    function updateTotal() {
        var sOpt = document.getElementById('category_select').selectedOptions[0];
        var lOpt = document.getElementById('location_select').selectedOptions[0];
        var inputEl = document.getElementById('person_count');
        var persons = parseInt(inputEl.value) || 1;
        
        if (persons > 2) { 
            showStatusModal("Maksimal pemesanan offline adalah 2 orang."); 
            inputEl.value = 2; 
            persons = 2; 
        }
        if (persons < 1) {
            inputEl.value = 1;
            persons = 1;
        }
        
        var sPrice = parseInt(sOpt.dataset.price) || 0;
        var lPrice = parseInt(lOpt.dataset.price) || 0;
        totalVal = (sPrice * persons) + lPrice;
        
        document.getElementById('display_subtotal').innerText = 'Rp' + sPrice.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        document.getElementById('display_multiplier').innerText = 'X' + persons;
        document.getElementById('display_ongkir').innerText = 'Rp' + lPrice.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        document.getElementById('display_total').innerText = totalVal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        togglePaymentInput();
    }

    function togglePaymentInput() {
        var method = document.getElementById('payMethod').value;
        var cashArea = document.getElementById('area_cash_input');
        var qrisArea = document.getElementById('area_qris_input');
        var qrisSelect = document.getElementById('dpInputQris');
        
        if (method === 'cash') { 
            qrisArea.style.setProperty('display', 'none', 'important');
            cashArea.style.setProperty('display', 'block', 'important');
        } else { 
            cashArea.style.setProperty('display', 'none', 'important');
            qrisArea.style.setProperty('display', 'block', 'important');
            
            var dpLabel = (totalVal / 2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            var lunasLabel = totalVal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            
            qrisSelect.innerHTML = '<option value="' + (totalVal/2) + '">DP 50% (Rp' + dpLabel + ')</option><option value="' + totalVal + '">Lunas (Rp' + lunasLabel + ')</option>';
        }
    }

    // 🚨 FIX KALENDER JADUL: Menggunakan Rumus Angka/Timestamp Murni agar Deteksi Expired Akurat di Tablet
    function renderCalendar() {
        var grid = document.getElementById('calGridContent');
        var label = document.getElementById('calTitle');
        var prevBtn = document.getElementById('prevMonth');
        
        label.innerText = monthNames[curMonth] + " " + curYear;
        prevBtn.style.setProperty('display', (curYear > initYear || (curYear === initYear && curMonth > initMonth)) ? 'block' : 'none', 'important');
        
        grid.innerHTML = '';
        
        // Buat patokan waktu "hari ini" jam 00:00 dalam hitungan angka milidetik murni
        var t = new Date();
        var todayTimestamp = new Date(t.getFullYear(), t.getMonth(), t.getDate()).getTime();
        
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "/api/check-availability?month=" + (curMonth + 1) + "&year=" + curYear, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var availability = JSON.parse(xhr.responseText);
                var daysInMonth = new Date(curYear, curMonth + 1, 0).getDate();
                
                for (var i = 1; i <= daysInMonth; i++) {
                    (function(day) {
                        var btn = document.createElement('button');
                        btn.type = "button";
                        btn.innerText = day;
                        btn.className = "day-btn p-3 bg-gray-50 text-gray-800 transition-all font-bold";
                        btn.style.setProperty('display', 'flex', 'important');
                        
                        // Cek kedaluwarsa berbasis angka murni (Anti Gagal Grafis Tablet)
                        var checkTimestamp = new Date(curYear, curMonth, day).getTime();
                        
                        if (checkTimestamp < todayTimestamp) {
                            btn.disabled = true;
                            btn.className += ' before-today';
                        } else {
                            var statusLiburAtauBooking = false;
                            if (availability[day]) {
                                statusLiburAtauBooking = (availability[day].status === 'holiday');
                                if (availability[day].status === 'holiday' || availability[day].status === 'full') { 
                                    btn.className += ' holiday-active'; 
                                } else { 
                                    btn.className += ' booking-partial'; 
                                }
                            }
                            
                            var detailsData = (availability[day] && availability[day].details) ? availability[day].details : [];
                            
                            btn.onclick = function() {
                                selectDate(day, detailsData, btn, statusLiburAtauBooking);
                            };
                        }
                        grid.appendChild(btn);
                    })(i);
                }
            }
        };
        xhr.send();
    }

    function selectDate(day, details, el, holidayStatus) {
        dayBookings = details; 
        isHoliday = holidayStatus;
        
        var formattedMonth = (curMonth + 1).toString();
        if (formattedMonth.length < 2) { formattedMonth = '0' + formattedMonth; }
        var formattedDay = day.toString();
        if (formattedDay.length < 2) { formattedDay = '0' + formattedDay; }
        
        document.getElementById('final_date').value = curYear + '-' + formattedMonth + '-' + formattedDay;
        document.getElementById('dateDisplay').innerText = day + " " + monthNames[curMonth] + " " + curYear;
        
        var btnHoliday = document.getElementById('btnHoliday');
        if (btnHoliday) {
            btnHoliday.style.setProperty('display', 'block', 'important');
            document.getElementById('holidayText').innerText = isHoliday ? "Batalkan Hari Libur" : "Liburkan Hari Ini";
        }
        
        var btnConfirm = document.getElementById('btnConfirm');
        var timeArea = document.getElementById('timeSection');
        
        if(isHoliday) {
            btnConfirm.disabled = true;
            btnConfirm.className = "w-full bg-gray-200 text-gray-400 font-bold py-4 rounded-2xl cursor-not-allowed uppercase text-sm tracking-wider";
            if (timeArea) { timeArea.style.setProperty('display', 'none', 'important'); }
        } else {
            btnConfirm.disabled = false;
            btnConfirm.className = "w-full bg-emerald-600 text-white font-bold py-4 rounded-2xl hover:bg-emerald-700 transition-all shadow-md active:scale-95 uppercase text-sm tracking-wider";
            if (timeArea) { timeArea.style.setProperty('display', 'block', 'important'); }
        }
        
        var list = document.getElementById('bookedList');
        var table = document.getElementById('bookedTable');
        list.innerHTML = '';
        
        if (details.length > 0) { 
            if (table) { table.style.setProperty('display', 'block', 'important'); }
            for (var idx = 0; idx < details.length; idx++) {
                var d = details[idx];
                list.innerHTML += '<tr class="border-b border-white/5"><td class="py-2 font-medium">' + (idx+1) + '</td><td class="py-2 font-bold">' + d.start + '</td><td class="py-2 text-right text-pink-400 font-bold">' + d.end + '</td></tr>';
            }
        } else { 
            if (table) { table.style.setProperty('display', 'none', 'important'); } 
        }
        
        var allButtons = document.querySelectorAll('.day-btn');
        for (var bIdx = 0; bIdx < allButtons.length; bIdx++) {
            allButtons[bIdx].classList.remove('active-date');
        }
        if(!isHoliday) { el.classList.add('active-date'); }
    }

    document.getElementById('bookingForm').onsubmit = function(e) {
        e.preventDefault();
        var timeVal = document.getElementById('timeValue').value;
        var dateVal = document.getElementById('final_date').value;
        var method = document.getElementById('payMethod').value;
        var nominal = (method === 'cash') ? document.getElementById('dpInputCash').value : document.getElementById('dpInputQris').value;

        if(!dateVal || !timeVal) { showStatusModal("Harap pilih TANGGAL dan JAM rias!"); return; }
        if(method === 'cash' && (!nominal || nominal <= 0)) { showStatusModal("Nominal Tunai wajib diisi!"); return; }

        var btn = document.getElementById('btnConfirm');
        var originalText = "Konfirmasi & Simpan Jadwal →";
        btn.disabled = true; 
        btn.innerText = "Memproses...";

        var formData = new FormData(this);
        formData.set('booking_date', dateVal); 
        formData.set('start_time', timeVal);
        formData.set('dp_amount', nominal);

        if (method === 'qris') {
            // Skrip Midtrans tetap aman terjaga utuh 100% untuk laptop
            var xhrPrep = new XMLHttpRequest();
            xhrPrep.open("POST", "{{ route('admin.schedules.prepare') }}", true);
            xhrPrep.setRequestHeader("X-Requested-With", "XMLHttpRequest");
            xhrPrep.onreadystatechange = function() {
                if (xhrPrep.readyState === 4 && xhrPrep.status === 200) {
                    var result = JSON.parse(xhrPrep.responseText);
                    if (result.status === 'midtrans') {
                        window.snap.pay(result.snap_token, {
                            onSuccess: function(r){ formData.append('order_id', result.order_id); saveDataNative(formData); },
                            onPending: function(r){ formData.append('order_id', result.order_id); saveDataNative(formData); },
                            onError: function(r){ showStatusModal("Pembayaran Gagal."); resetBtn(btn, originalText); },
                            onClose: function(){ resetBtn(btn, originalText); }
                        });
                    } else { showStatusModal(result.message || "Gagal mengambil token."); resetBtn(btn, originalText); }
                }
            };
            xhrPrep.send(formData);
        } else { 
            saveDataNative(formData); 
        }
    };

    function saveDataNative(formData) {
        var btn = document.getElementById('btnConfirm');
        var xhrSave = new XMLHttpRequest();
        xhrSave.open("POST", "{{ route('admin.schedules.manual') }}", true);
        xhrSave.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        xhrSave.onreadystatechange = function() {
            if (xhrSave.readyState === 4) {
                if (xhrSave.status === 200) {
                    showStatusModal("Jadwal rias offline telah berhasil disimpan ke agenda.", false);
                    setTimeout(function() { location.reload(); }, 2000);
                } else {
                    showStatusModal("Gagal menyimpan data jadwal rias.");
                    resetBtn(btn, "Konfirmasi & Simpan Jadwal →");
                }
            }
        };
        xhrSave.send(formData);
    }

    document.addEventListener('wheel', function(event) { if (document.activeElement.type === 'number') document.activeElement.blur(); });
    function resetBtn(btn, text) { btn.disabled = false; btn.innerText = text; }
    
    function changeMonth(step) { 
        curMonth += step; 
        if(curMonth > 11) { curMonth = 0; curYear++; } 
        else if(curMonth < 0) { curMonth = 11; curYear--; } 
        renderCalendar(); 
    }
    
    updateTotal(); 
    renderCalendar();
</script>