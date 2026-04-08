<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Berhasil!</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-emerald-50 min-h-screen flex items-center justify-center px-6">
    <div class="max-w-md w-full bg-white rounded-[3rem] shadow-2xl p-10 text-center border border-emerald-100">
        <div class="w-20 h-20 bg-emerald-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg shadow-emerald-200">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <h1 class="text-3xl font-black text-gray-900 italic uppercase tracking-tighter mb-2">Pembayaran Berhasil!</h1>
        <p class="text-gray-500 text-sm mb-8 font-medium">Pesanan kamu telah terverifikasi otomatis oleh sistem. Sampai jumpa di jadwal rias!</p>

        <div class="space-y-3">
            <a href="{{ route('booking.receipt', $booking->id) }}" class="block w-full bg-gray-900 text-white font-black py-4 rounded-2xl hover:bg-pink-600 transition-all uppercase text-xs tracking-widest">
                Lihat Struk Digital
            </a>
            <a href="{{ url('/') }}" class="block w-full text-gray-400 font-bold py-2 text-xs uppercase hover:text-gray-600 transition-all">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>