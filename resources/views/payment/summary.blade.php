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
</head>
<body class="bg-pink-50 min-h-screen py-12 px-6 font-sans">
    <div class="max-w-xl mx-auto bg-white rounded-[3rem] shadow-2xl overflow-hidden border border-pink-100">
        <div class="p-10 lg:p-14 text-center">
            <h1 class="text-3xl font-black text-gray-900 tracking-tighter mb-2 italic uppercase">Ringkasan Pesanan</h1>
            <p class="text-pink-600 font-bold mb-10 uppercase text-xs tracking-[0.2em]">Layanan: {{ $booking->category->name }}</p>

            <div class="bg-gray-50 rounded-3xl p-8 mb-8 space-y-4 text-left border border-gray-100">
                <div class="flex justify-between border-b border-gray-200 pb-3">
                    <span class="text-xs font-black uppercase text-gray-400">Nama Pelanggan</span>
                    <span class="font-bold text-gray-800">{{ $booking->customer_name }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-200 pb-3">
                    <span class="text-xs font-black uppercase text-gray-400">Tanggal Rias</span>
                    <span class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-xs font-black uppercase text-gray-400 tracking-widest">Total Tagihan</span>
                    <span class="text-2xl font-black text-gray-900 italic">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4">
                <button onclick="pay('dp')" class="group relative bg-gray-900 text-white p-6 rounded-3xl font-black transition-all hover:bg-gray-800 shadow-xl shadow-gray-200 overflow-hidden">
                    <div class="relative z-10 flex justify-between items-center">
                        <span class="uppercase tracking-tighter text-left">Bayar DP (50%)<br><span class="text-pink-500 text-xs">Mulai Persiapan</span></span>
                        <span class="text-xl italic">Rp {{ number_format($dp_amount, 0, ',', '.') }}</span>
                    </div>
                </button>

                <button onclick="pay('full')" class="group relative bg-pink-600 text-white p-6 rounded-3xl font-black transition-all hover:bg-pink-700 shadow-xl shadow-pink-200 overflow-hidden">
                    <div class="relative z-10 flex justify-between items-center text-left">
                        <span class="uppercase tracking-tighter">Bayar Lunas<br><span class="text-pink-200 text-xs">Tanpa Tagihan Lagi</span></span>
                        <span class="text-xl italic">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </button>
            </div>

            <p class="mt-8 text-[10px] font-bold text-gray-400 uppercase tracking-widest italic">Aman & Terverifikasi Otomatis oleh Midtrans</p>
        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    
    <script>
        function pay(type) {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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
                if (!response.ok) {
                    throw new Error(resData.message || 'Gagal menghubungi server.');
                }
                return resData;
            })
            .then(data => {
                Swal.close();

                window.snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        // POP-UP SUKSES (DIKUNCI)
                        Swal.fire({
                            title: 'Pembayaran Berhasil!',
                            text: 'Terima kasih! Silakan unduh struk pembayaran Anda sebagai bukti reservasi yang sah.',
                            icon: 'success',
                            confirmButtonColor: '#db2777', // Pink-600
                            confirmButtonText: '<i class="fa fa-download mr-2"></i> Lihat Struk Digital',
                            showCancelButton: false, // Hapus tombol Tutup
                            allowOutsideClick: false, // Tidak bisa klik luar
                            allowEscapeKey: false,   // Tidak bisa tekan tombol Esc
                            showCloseButton: false    // Tidak ada tombol (x) di pojok
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Satu-satunya jalan keluar: Ke halaman struk
                                window.location.href = "{{ route('booking.receipt', $booking->id) }}";
                            }
                        });
                    },
                    onPending: function(result) {
                        Swal.fire('Menunggu Pembayaran', 'Segera selesaikan pembayaran Anda sebelum kedaluwarsa.', 'info');
                    },
                    onError: function(result) {
                        Swal.fire('Pembayaran Gagal', 'Mohon maaf, terjadi kesalahan saat memproses pembayaran.', 'error');
                    },
                    onClose: function() {
                        console.log('User menunda pembayaran');
                    }
                });
            })
            .catch(error => {
                Swal.fire({
                    title: 'Waduh!',
                    text: error.message,
                    icon: 'warning',
                    confirmButtonColor: '#db2777'
                });
            });
        }
    </script>
</body>
</html>