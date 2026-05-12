<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        .card { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 500px; margin: auto; border: 1px solid #fce7f3; border-radius: 24px; padding: 32px; background: #ffffff; }
        .header { color: #db2777; font-weight: 900; font-size: 22px; text-align: center; margin-bottom: 8px; font-style: italic; }
        .sub-header { text-align: center; font-size: 13px; color: #6b7280; margin-bottom: 28px; }
        
        .info-box { background: #fff5f7; border-radius: 20px; padding: 24px; margin-bottom: 28px; border: 1px solid #ffe4e6; }
        .info-item { margin-bottom: 14px; }
        .info-label { font-weight: 900; color: #9ca3af; text-transform: uppercase; font-size: 9px; letter-spacing: 1.5px; display: block; margin-bottom: 4px; }
        .info-value { color: #111827; font-size: 14px; font-weight: 500; display: block; }
        
        /* Gaya Button dengan Teks Putih */
        .btn { display: block; text-align: center; padding: 16px; border-radius: 14px; font-weight: 800; text-transform: uppercase; font-size: 11px; text-decoration: none; margin-bottom: 12px; letter-spacing: 1px; transition: all 0.3s; }
        .btn-wa { background: #10b981 !important; color: #ffffff !important; }
        .btn-receipt { background: #111827 !important; color: #ffffff !important; }
        
        .bold-pink { color: #db2777; font-weight: 800; }
        .footer { font-size: 10px; color: #9ca3af; text-align: center; border-top: 1px dashed #e5e7eb; margin-top: 24px; padding-top: 16px; font-weight: 500; }
    </style>
</head>
<body>
    @php
        // Pembersihan Nomor WA
        $phone = preg_replace('/[^0-9]/', '', $booking->whatsapp_number);
        if (str_starts_with($phone, '0')) { $phone = '62' . substr($phone, 1); }

        $tglIndo = \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d F Y');
        $sisaBayar = $booking->total_amount - $booking->dp_amount;
        $serviceName = $booking->category->name ?? $booking->bundling->subject ?? 'Paket Rias';
        
        // TEMPLATE PESAN WA (List Angka & Bold Selektif)
        $message = "Reminder H-{$admin->reminder_days} Makeup ({$serviceName}):\n\n";
        $message .= "1. Diusahakan client tidak begadang dan istirahat yang cukup agar fit saat dimakeup ✨\n";
        $message .= "2. Kita start makeup jam {$booking->start_time} WIB, mohon sudah ready ya kak supaya tidak terlambat.\n";
        $message .= "3. Kalo bisa malam ini memakai sheet mask (tidak wajib) supaya kulit plumpy dan makeup nempel.\n";
        $message .= "4. Client sudah memakai atasan kebaya/kostum dan hijab sudah disetrika rapi di bagian tengahnya.\n";
        $message .= "5. Jika ada yang sedang sholat, diusahakan client sudah mengambil wudhu terlebih dahulu ya kak.\n";
        $message .= "6. Lokasi di {$booking->address}. Biaya makeup {$booking->person_count} orang Rp" . number_format($booking->total_amount, 0, ',', '.') . ", sudah bayar DP Rp" . number_format($booking->dp_amount, 0, ',', '.') . ", jadi *Total Cash nya Rp" . number_format($sisaBayar, 0, ',', '.') . "* ya.\n\n";
        $message .= "See u kaka cantik *{$booking->customer_name}* besok jam {$booking->start_time} jangan sampai kelewatan yaaa ❤️";

        $waUrl = "https://wa.me/" . $phone . "?text=" . urlencode($message);
    @endphp

    <div class="card">
        <div class="header">Halo Admin Dya's Makeup! ✨</div>
        <div class="sub-header">Berikut ringkasan jadwal pelanggan untuk besok:</div>

        <div class="info-box">
            <div class="info-item">
                <span class="info-label">Nama Pelanggan</span>
                <span class="info-value"><strong class="bold-pink">{{ $booking->customer_name }}</strong></span>
            </div>
            <div class="info-item">
                <span class="info-label">Layanan & Jumlah</span>
                <span class="info-value">{{ $serviceName }} ({{ $booking->person_count }} Orang)</span>
            </div>
            <div class="info-item">
                <span class="info-label">Waktu Rias</span>
                <span class="info-value">{{ $tglIndo }} | {{ $booking->start_time }} WIB</span>
            </div>
            <div class="info-item">
                <span class="info-label">Alamat Lokasi</span>
                <span class="info-value">{{ $booking->address }}</span>
            </div>
            <div class="info-item" style="margin-bottom: 0;">
                <span class="info-label">Sisa Pembayaran (CASH)</span>
                <span class="info-value"><strong class="bold-pink">Rp{{ number_format($sisaBayar, 0, ',', '.') }}</strong></span>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <a href="{{ $waUrl }}" class="btn btn-wa">Kirim Reminder WA ke Customer</a>
        <a href="{{ route('booking.receipt', $booking->id) }}" class="btn btn-receipt">Download Struk Pesanan</a>

        <div class="footer">
            Email otomatis sistem Dya's Makeup (H-{{ $admin->reminder_days }} Reminder).[cite: 10, 11]
        </div>
    </div>
</body>
</html>