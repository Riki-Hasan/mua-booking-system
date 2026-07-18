<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ringkasan Pembayaran - Dya's Makeup</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .animate-pop { animation: pop 0.2s ease-out; }
        @keyframes pop { 0% { transform: scale(0.95); } 100% { transform: scale(1); } }
    </style>
</head>
<body class="bg-pink-50 min-h-screen py-12 px-6 font-sans">
    <div class="max-w-xl mx-auto bg-white rounded-[3rem] shadow-2xl overflow-hidden border border-pink-100">
        <div class="p-10 lg:p-14 text-center">
            <h1 class="text-3xl font-black text-gray-900 tracking-tighter mb-2 italic uppercase">Ringkasan Pesanan</h1>
            <p class="text-pink-700 font-black mb-10 uppercase text-xs tracking-[0.2em]">Layanan: {{ $booking->category->name ?? $booking->bundling->subject }}</p>

            <!-- 🚨 REVISI KONTRAST TEKS INFO MENJADI HITAM PEKAT (GRAY DIHAPUS) -->
            <div class="bg-slate-50 rounded-3xl p-8 mb-6 space-y-4 text-left border-2 border-slate-100">
                <div class="flex justify-between border-b border-gray-200 pb-3">
                    <span class="text-xs font-black uppercase text-gray-900 tracking-wider">Nama Pelanggan</span>
                    <span class="font-bold text-gray-900 text-sm">{{ $booking->customer_name }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-200 pb-3">
                    <span class="text-xs font-black uppercase text-gray-900 tracking-wider">Tanggal Rias</span>
                    <span class="font-bold text-gray-900 text-sm">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs font-black uppercase text-gray-900 tracking-widest">Total Tagihan</span>
                    <span class="text-2xl font-black text-pink-700 italic">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- 🚨 PILIHAN METODE PEMBAYARAN JADUL KONTRAST TINGGI ANTI-FREEZE -->
            <div class="mb-6 p-4 bg-gray-50 rounded-3xl border-2 border-slate-200 text-left">
                <label class="block text-xs font-black uppercase text-gray-900 mb-3 tracking-wider ml-1">Pilih Mode Pembayaran:</label>
                
                <div class="grid grid-cols-1 gap-3">
                    <!-- Opsi 1: Snap Pop-up -->
                    <label class="flex items-center gap-3 p-4 bg-white rounded-2xl border-2 border-slate-300 cursor-pointer hover:border-pink-500 transition-all">
                        <input type="radio" name="payment_mode" value="snap" checked class="w-5 h-5 accent-pink-500">
                        <div>
                            <p class="text-xs font-black text-gray-900 uppercase">Pembayaran Umum</p>
                            <p class="text-[10px] text-gray-500 font-bold">Transfer Bank, VA, KlikBCA (Gunakan di HP/Laptop Modern)</p>
                        </div>
                    </label>

                    <!-- Opsi 2: QRIS Direct -->
                    <label class="flex items-center gap-3 p-4 bg-white rounded-2xl border-2 border-slate-300 cursor-pointer hover:border-pink-500 transition-all">
                        <input type="radio" name="payment_mode" value="qris" class="w-5 h-5 accent-pink-500">
                        <div>
                            <p class="text-xs font-black text-emerald-700 uppercase">QRIS Langsung (Rekomendasi Tablet)</p>
                            <p class="text-[10px] text-gray-500 font-bold">Langsung memunculkan Kode QR di layar tanpa pop-up berat</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- 🚨 BOX PENAMPIL QRIS JADUL (Hidden secara bawaan) -->
            <div id="qrisContainer" class="hidden mb-6 p-6 bg-white border-4 border-emerald-500 rounded-[2rem] text-center shadow-2xl animate-pop">
                <h3 class="text-lg font-black text-gray-900 uppercase italic tracking-tighter mb-1">Pindai QRIS di Bawah Ini</h3>
                <p class="text-[9px] text-rose-500 font-black uppercase tracking-widest mb-4">* Silakan scan menggunakan Gopay, OVO, Dana, atau Mobile Banking</p>
                
                <div class="w-56 h-56 mx-auto bg-gray-50 border-4 border-gray-100 rounded-2xl flex items-center justify-center p-2 overflow-hidden shadow-inner mb-4">
                    <img id="qrisImage" src="" alt="QRIS Midtrans" class="w-full h-full object-contain">
                </div>

                <p class="text-[10px] text-slate-700 font-bold mb-4">Setelah melakukan pembayaran sukses, halaman ini akan otomatis berpindah ke halaman sukses dalam beberapa detik.</p>
                
                <a href="{{ route('booking.receipt', $booking->id) }}" class="inline-block bg-slate-900 text-white font-black px-6 py-3 rounded-xl uppercase text-[9px] tracking-widest">
                    Sudah Bayar? Cek Struk
                </a>
            </div>

            <div class="grid grid-cols-1 gap-4">
                <button onclick="pay('dp')" class="group relative bg-gray-900 text-white p-6 rounded-3xl font-black transition-all hover:bg-gray-800 shadow-xl overflow-hidden">
                    <div class="relative z-10 flex justify-between items-center">
                        <span class="uppercase tracking-tighter text-left">Bayar DP (50%)<br><span class="text-pink-400 text-xs font-black uppercase">Mulai Persiapan</span></span>
                        <span class="text-xl italic text-pink-500">Rp {{ number_format($dp_amount, 0, ',', '.') }}</span>
                    </div>
                </button>

                <button onclick="pay('full')" class="group relative bg-pink-600 text-white p-6 rounded-3xl font-black transition-all hover:bg-pink-700 shadow-xl overflow-hidden">
                    <div class="relative z-10 flex justify-between items-center text-left">
                        <span class="uppercase tracking-tighter">Bayar Lunas<br><span class="text-white text-xs font-black uppercase opacity-90">Tanpa Tagihan Lagi</span></span>
                        <span class="text-xl italic">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </button>
            </div>

            <p class="mt-8 text-[10px] font-black text-gray-900 uppercase tracking-widest italic">Aman & Terverifikasi Otomatis oleh Midtrans</p>
        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    
    <script>
        function pay(type) {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const paymentMode = document.querySelector('input[name="payment_mode"]:checked').value;

            if (paymentMode === 'qris') {
                // --- KONDISI 1: JALUR DIRECT QRIS (ANTI FREEZE / TABLET JADUL) ---
                const qrisContainer = document.getElementById('qrisContainer');
                const qrisImage = document.getElementById('qrisImage');
                
                // Reset dan munculkan loading placeholder
                qrisContainer.classList.remove('hidden');
                qrisImage.src = "https://i.gifer.com/ZZ5H.gif"; 
                qrisContainer.scrollIntoView({ behavior: 'smooth' });

                fetch("{{ route('payment.qris_direct') }}", {
                    method: "POST",
                    headers: { 
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": token
                    },
                    body: JSON.stringify({ 
                        booking_id: "{{ $booking->id }}",
                        type: type 
                    })
                })
                .then(async response => {
                    const resData = await response.json();
                    if (!response.ok) { throw new Error(resData.message || 'Gagal mengambil data QRIS.'); }
                    return resData;
                })
                .then(data => {
                    if (data.status === 'success') {
                        // Tampilkan gambar QRIS asli hasil render Midtrans Core API
                        qrisImage.src = data.qr_url;

                        // Jalankan interval pemantau status otomatis setiap 4 detik
                        setInterval(() => {
                            fetch("{{ route('booking.success', $booking->id) }}")
                            .then(response => {
                                // Jika status database berubah berkat callback, arahkan otomatis ke receipt
                                if (response.redirected) {
                                    window.location.href = "{{ route('booking.receipt', $booking->id) }}";
                                }
                            });
                        }, 4000);
                    } else {
                        Swal.fire('Waduh!', data.message, 'warning');
                        qrisContainer.classList.add('hidden');
                    }
                })
                .catch(error => {
                    Swal.fire({ title: 'Waduh!', text: error.message, icon: 'warning', confirmButtonColor: '#db2777' });
                    qrisContainer.classList.add('hidden');
                });

            } else {
                // --- KONDISI 2: JALUR SNAP UTAMA (POP-UP MEMORY SEPERTI BIASA) ---
                // Sembunyikan container QRIS jika sebelumnya sempat dibuka
                document.getElementById('qrisContainer').classList.add('hidden');

                Swal.fire({
                    title: 'Menyiapkan Pembayaran...',
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading() }
                });

                fetch("{{ route('payment.token') }}", {
                    method: "POST",
                    headers: { 
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": token
                    },
                    body: JSON.stringify({ 
                        booking_id: "{{ $booking->id }}",
                        type: type 
                    })
                })
                .then(async response => {
                    const resData = await response.json();
                    if (!response.ok) { throw new Error(resData.message || 'Gagal menghubungi server.'); }
                    return resData;
                })
                .then(data => {
                    Swal.close();
                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            Swal.fire({
                                title: 'Pembayaran Berhasil!',
                                text: 'Terima kasih! Silakan unduh struk pembayaran Anda sebagai bukti reservasi yang sah.',
                                icon: 'success',
                                confirmButtonColor: '#db2777',
                                confirmButtonText: 'Lihat Struk Digital',
                                allowOutsideClick: false,
                                allowEscapeKey: false
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = "{{ route('booking.receipt', $booking->id) }}";
                                }
                            });
                        },
                        onPending: function(result) { Swal.fire('Menunggu Pembayaran', 'Segera selesaikan pembayaran Anda.', 'info'); },
                        onError: function(result) { Swal.fire('Pembayaran Gagal', 'Terjadi kesalahan.', 'error'); }
                    });
                })
                .catch(error => {
                    Swal.fire({ title: 'Waduh!', text: error.message, icon: 'warning', confirmButtonColor: '#db2777' });
                });
            }
        }
    </script>
</body>
</html>