<div style="font-family: sans-serif; max-width: 600px; margin: auto; border: 1px solid #eee; padding: 20px; border-radius: 20px;">
    <h2 style="color: #db2777;">Halo Admin Dya's Makeup! ✨</h2>
    <p>Ini adalah pengingat otomatis bahwa ada jadwal makeup untuk:</p>
    
    <div style="background: #fdf2f8; padding: 15px; border-radius: 15px; margin-bottom: 20px;">
        <strong>Pelanggan:</strong> {{ $booking->customer_name }}<br>
        <strong>Layanan:</strong> {{ $booking->category->name }} ({{ $booking->person_count }} Orang)<br>
        <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}<br>
        <strong>Jam Start:</strong> {{ $booking->start_time }} WIB
    </div>

    <p>Silakan hubungi customer untuk konfirmasi ulang melalui WhatsApp di bawah ini:</p>
    
    <a href="{{ $waUrl }}" style="display: inline-block; background: #25D366; color: white; padding: 12px 25px; text-decoration: none; border-radius: 10px; font-weight: bold;">
        Kirim Reminder WA ke Customer
    </a>

    <div style="margin-top: 10px;">
        <a href="{{ route('booking.receipt', $booking->id) }}" style="display: inline-block; background: #111827; color: white; padding: 12px 25px; text-decoration: none; border-radius: 10px; font-weight: bold;">
            Download Struk Pesanan
        </a>
    </div>

    <p style="font-size: 12px; color: #999; margin-top: 30px; border-top: 1px dashed #ddd; padding-top: 10px;">
        *Email ini dikirim otomatis berdasarkan pengaturan H-{{ auth()->user()->reminder_days }} di sistem.
    </p>
</div>