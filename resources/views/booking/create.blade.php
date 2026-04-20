<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking {{ $category->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-pink-50 min-h-screen py-12 px-6 font-sans">
    <div class="max-w-2xl mx-auto bg-white rounded-[3rem] shadow-2xl overflow-hidden border border-pink-100">
        <div class="p-10 lg:p-14">
            <h1 class="text-4xl font-black text-gray-900 tracking-tighter mb-2 italic uppercase">Data Pemesanan</h1>
            <p class="text-pink-600 font-bold mb-10 uppercase text-xs tracking-[0.2em]">Layanan: {{ $category->name }}</p>

            <form action="{{ route('booking.store') }}" method="POST" id="bookingForm" class="space-y-8">
                @csrf
                <input type="hidden" name="category_id" id="base_price_input" data-price="{{ $category->base_price }}" value="{{ $category->id }}">
                
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Nama Lengkap</label>
                            <input type="text" id="input_name" name="customer_name" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 outline-none focus:ring-2 focus:ring-pink-500 transition-all" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Jumlah Orang</label>
                            <input type="number" name="person_count" id="input_persons" value="1" min="1" oninput="updateTotal()" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 outline-none focus:ring-2 focus:ring-pink-500 font-bold" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Nomor WhatsApp</label>
                            <input type="number" id="input_wa" name="whatsapp_number" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 outline-none focus:ring-2 focus:ring-pink-500 transition-all" placeholder="08xxx" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Alamat Lengkap (Lokasi Rias)</label>
                        <textarea id="input_address" name="address" rows="3" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 outline-none focus:ring-2 focus:ring-pink-500 transition-all resize-none" placeholder="Contoh: Jl. Pancasakti No. 1, Tegal (Samping Indomaret)" required></textarea>
                    </div>
                </div>

                <div class="space-y-4">
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 ml-1">Jadwal Rias</label>
                    <a href="{{ route('booking.calendar', $category->id) }}" 
                    class="block w-full bg-pink-500 text-white font-black p-5 rounded-2xl text-center hover:bg-pink-600 transition-all shadow-lg shadow-pink-200 uppercase tracking-widest text-xs">
                        Buka Kalender Jadwal
                    </a>
                    
                    <div class="p-6 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200 text-center">
                        @if(request('date') && request('time'))
                            <p class="text-[10px] font-black text-gray-400 uppercase mb-1 tracking-widest">Jadwal Terpilih:</p>
                            <p class="text-xl font-black text-gray-900 italic uppercase">
                                {{ request('date') }} | {{ request('time') }}
                            </p>
                            <input type="hidden" name="booking_date" value="{{ request('date') }}">
                            <input type="hidden" name="start_time" value="{{ request('time') }}">
                        @else
                            <p class="text-xs font-bold text-rose-400 italic">Silahkan pilih tanggal dan jam makeup pada tombol diatas</p>
                        @endif
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Lokasi Rias</label>
                    <select name="location_id" id="location_select" class="w-full border-gray-100 bg-gray-50 rounded-2xl p-4 text-sm outline-none focus:ring-2 focus:ring-pink-500 transition-all">
                        <option value="0" data-extra="0">Datang ke Toko (Tanpa Tambahan Biaya)</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}" data-extra="{{ $location->additional_price }}">
                                {{ $location->region_name }} (+Rp{{ number_format($location->additional_price, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="bg-gray-900 rounded-[2rem] p-8 text-white shadow-xl relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-pink-600/20 rounded-full -mr-16 -mt-16 blur-3xl"></div>
                    <div class="space-y-3 text-sm border-b border-white/10 pb-6 mb-6">
                        <div class="flex justify-between text-gray-400"><span>Harga Paket</span><span>Rp{{ number_format($category->base_price, 0, ',', '.') }}</span></div>
                        <div class="flex justify-between text-pink-400"><span>Ongkir Lokasi</span><span>+ Rp<span id="display_extra">0</span></span></div>
                    </div>
                    <div class="flex justify-between items-end mb-4">
                        <span class="text-xs font-black uppercase text-gray-400">Total Est.</span>
                        <span class="text-3xl font-black">Rp<span id="display_total">{{ number_format($category->base_price, 0, ',', '.') }}</span></span>
                    </div>
                    <div class="p-4 bg-white/5 rounded-xl border border-white/10 text-center">
                        <p class="text-[9px] font-black uppercase text-pink-300 tracking-widest">Pilihan DP atau Lunas akan muncul di halaman berikutnya</p>
                    </div>
                </div>

                <button type="submit" class="w-full bg-gray-900 text-white font-black py-6 rounded-3xl hover:bg-emerald-500 transition-all shadow-2xl shadow-emerald-200 group">
                    LANJUT KE PEMBAYARAN <span class="group-hover:ml-2 transition-all">→</span>
                </button>
            </form>
        </div>
    </div>

    <script>
        // 1. PERSISTENSI DATA (Local Storage) - Agar data tidak hilang saat pindah page
        const inputs = ['input_name', 'input_wa', 'input_address', 'location_select', 'input_persons'];
        
        // Simpan data setiap kali ada perubahan
        inputs.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                // Ambil data yang tersimpan saat page load
                const savedValue = localStorage.getItem(id);
                if (savedValue) el.value = savedValue;

                // Event listener untuk simpan otomatis
                el.addEventListener('input', () => {
                    localStorage.setItem(id, el.value);
                    if(id === 'input_persons') updateTotal();
                });
            }
        });

        // Hapus storage jika form berhasil dikirim
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            // Cek apakah tanggal sudah dipilih
            const dateInput = document.querySelector('input[name="booking_date"]');
            if (!dateInput) {
                e.preventDefault();
                alert("Harap pilih tanggal dan jam di kalender terlebih dahulu!");
                return;
            }
            // Jika OK, hapus data lama agar tidak muncul di booking selanjutnya
            inputs.forEach(id => localStorage.removeItem(id));
        });

        // 2. LOGIKA HITUNG BIAYA (Frontend)
        const locationSelect = document.getElementById('location_select');
        const basePrice = parseInt(document.getElementById('base_price_input').dataset.price);
        
        function updateTotal() {
            const extra = parseInt(locationSelect.options[locationSelect.selectedIndex].getAttribute('data-extra')) || 0;
            const persons = parseInt(document.getElementById('input_persons').value) || 1;
            
            // RUMUS: (Harga Paket x Jumlah Orang) + Ongkir
            const total = (basePrice * persons) + extra;
            
            document.getElementById('display_extra').innerText = extra.toLocaleString('id-ID');
            document.getElementById('display_total').innerText = total.toLocaleString('id-ID');
        }

        locationSelect.addEventListener('change', updateTotal);
        window.addEventListener('load', updateTotal); // Jalankan saat load untuk menyesuaikan localstorage
    </script>
</body>
</html>