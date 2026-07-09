<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran #{{ $booking->order_id }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html-to-image/1.11.11/html-to-image.min.js"></script>

    <style>
        @media print { 
            .no-print { display: none; } 
            body { background-color: white; padding: 0; }
            .receipt-card { shadow: none; border: 1px solid #eee; }
        }
        .dot-line { border-top: 2px dashed #e5e7eb; }
        #receipt-card { background-color: white; }
    </style>
</head>
<body class="bg-gray-100 py-12 px-6 font-sans">
    <div id="receipt-card" class="max-w-md mx-auto bg-white shadow-2xl rounded-3xl overflow-hidden receipt-card border border-gray-100 relative">
        
        <div id="status-ribbon" class="absolute -right-12 top-6 {{ $isFull ? 'bg-emerald-500' : 'bg-orange-500' }} text-white px-14 py-1 rotate-45 text-[10px] font-black uppercase tracking-widest shadow-md z-10">
            {{ $isFull ? 'Verified Full' : 'DP Verified' }}
        </div>

        <div class="p-8">
            <div class="text-center mb-6"> <div class="w-20 h-20 bg-pink-50 rounded-full flex items-center justify-center mx-auto mb-3 border-2 border-pink-100 overflow-hidden">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="w-full h-full object-cover">
                </div>
                <h2 class="text-2xl font-black italic uppercase tracking-tighter text-gray-900">Dya's Makeup</h2>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em]">Official Digital Receipt</p>
            </div>

            <div class="space-y-3 mb-5 text-sm"> <div class="flex justify-between items-center">
                    <span class="text-gray-400 font-bold uppercase text-[10px]">Order ID</span>
                    <span class="font-black text-gray-800">#{{ $booking->order_id }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 font-bold uppercase text-[10px]">Tanggal Cetak</span>
                    <span class="font-bold text-gray-700 text-xs">{{ now()->format('d/m/Y H:i') }}</span>
                </div>
                <div class="dot-line my-3"> </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 font-bold uppercase text-[10px]">Pelanggan</span>
                    <span class="font-black text-gray-900">{{ $booking->customer_name }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 font-bold uppercase text-[10px]">Layanan</span>
                    <span class="font-black text-gray-900 uppercase italic">{{ $booking->category->name ?? ($booking->bundling->subject ?? 'Layanan/Promo') }}</span>
                </div>
                <div class="flex justify-between items-center text-[11px]">
                    <span class="text-gray-400 font-bold uppercase">Jadwal Rias</span>
                    <span class="font-bold text-gray-700">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }} | {{ $booking->start_time }}</span>
                </div>
            </div>

            <div class="bg-gray-50 rounded-2xl p-5 mb-4 border border-gray-100"> <div class="space-y-2 mb-4">
                    <div class="flex justify-between text-xs font-bold text-gray-500">
                        <span>Total Layanan</span>
                        <span>Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-xs font-bold text-emerald-600">
                        <span>Dibayar ({{ $isFull ? 'Lunas' : 'DP 50%' }})</span>
                        <span>- Rp {{ number_format($paidAmount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="dot-line mb-4"></div>

                @if(!$isFull)
                    <div class="flex justify-between items-center text-red-600">
                        <span class="text-xs font-black uppercase tracking-tighter">Sisa Tagihan</span>
                        <span class="text-xl font-black italic">Rp {{ number_format($remainingBalance, 0, ',', '.') }}</span>
                    </div>
                    <div class="mt-3 bg-red-100 text-red-700 text-[9px] font-bold text-center py-2 rounded-lg uppercase tracking-widest">
                        Wajib Dilunasi di Lokasi Rias
                    </div>
                @else
                    <div class="flex justify-between items-center text-emerald-600">
                        <span class="text-xs font-black uppercase tracking-tighter">Sisa Tagihan</span>
                        <span class="text-xl font-black italic">Rp 0</span>
                    </div>
                    <div class="mt-3 bg-emerald-500 text-white text-[9px] font-bold text-center py-2 rounded-lg uppercase tracking-widest shadow-lg shadow-emerald-100">
                        Lunas & Terverifikasi
                    </div>
                @endif
            </div>

            <div class="text-center mb-4"> <div class="flex justify-center mb-2">
                    <div class="w-28 h-28 bg-white border-4 border-gray-100 rounded-2xl flex items-center justify-center  overflow-hidden shadow-sm">
                        <img src="{{ asset('assets/img/qrWhatsApp.png') }}" 
                             alt="QR WhatsApp" 
                             class="w-full h-full object-contain">
                    </div>
                </div>
                
                <p class="text-[8px] font-black text-pink-500 uppercase tracking-widest mb-3 italic">
                    Scan di sini buat tanya-tanya admin ya! ✨
                </p>

                <p class="text-[9px] text-gray-400 font-bold uppercase leading-relaxed tracking-tight">
                    Simpan struk ini sebagai bukti reservasi jadwal.<br>
                    Pembatalan < 24 jam, DP dianggap hangus.<br>
                    Terima kasih telah memilih @dyasmakeup
                </p>
            </div>

            <div class="flex gap-3 no-print">
                <button id="downloadBtn" onclick="downloadReceipt()" class="flex-1 bg-gray-900 text-white font-black py-4 rounded-2xl text-[10px] uppercase tracking-widest transition-all hover:bg-black active:scale-95 flex items-center justify-center gap-2">
                    <i class="fa fa-download"></i> Download PNG
                </button>

                @php
                    // Logika menentukan URL kembali
                    $referer = request()->header('referer');
                    $backUrl = url('/'); // Default buat user umum

                    if (auth()->check()) {
                        if (str_contains($referer, 'admin/schedules')) {
                            $backUrl = route('admin.schedules.index');
                        } elseif (str_contains($referer, 'admin/orders')) {
                            $backUrl = route('admin.orders.index');
                        } else {
                            $backUrl = route('admin.orders.index'); // Fallback default admin
                        }
                    }
                @endphp

                <a href="{{ $backUrl }}" 
                class="flex-1 bg-pink-100 text-pink-700 font-black py-4 rounded-2xl text-[10px] uppercase tracking-widest text-center transition-all hover:bg-pink-200 active:scale-95 flex items-center justify-center gap-2">
                    <i class="fa fa-house"></i> 
                    @if(auth()->check())
                        {{ str_contains($referer, 'admin/schedules') ? 'Ke Jadwal' : 'Ke Daftar Order' }}
                    @else
                        Selesai
                    @endif
                </a>
            </div>
        </div>
    </div>

    <p class="text-center mt-8 text-[10px] font-bold text-gray-400 uppercase tracking-[0.4em] no-print italic">
        Generated by MUA Management System
    </p>

    <script>
        function downloadReceipt() {
            if (typeof htmlToImage === 'undefined') {
                alert("Sistem sedang menyiapkan library gambar, silakan tunggu sebentar.");
                return;
            }

            const node = document.getElementById('receipt-card');
            const btn = document.getElementById('downloadBtn');
            
            btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Processing...';
            btn.disabled = true;

            htmlToImage.toPng(node, {
                quality: 1.0,
                pixelRatio: 3, 
                backgroundColor: '#ffffff',
                style: {
                    boxShadow: 'none',
                    margin: '0'
                }
            })
            .then(function (dataUrl) {
                const link = document.createElement('a');
                link.download = 'Struk-{{ $booking->order_id }}.png';
                link.href = dataUrl;
                link.click();
                
                btn.innerHTML = '<i class="fa fa-download"></i> Download PNG';
                btn.disabled = false;
            })
            .catch(function (error) {
                console.error('Error:', error);
                btn.disabled = false;
                btn.innerHTML = '<i class="fa fa-download"></i> Download PNG';
            });
        }
    </script>
</body>
</html>