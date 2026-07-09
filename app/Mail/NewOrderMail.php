<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\Booking;

class NewOrderMail extends Mailable
{
    use Queueable;

    public function __construct(public Booking $booking) {}

    public function build()
    {
        return $this->subject('🔔 PESANAN BARU MASUK! - ' . $this->booking->customer_name)
                ->html("
                <div style='background-color: #fff9f9; padding: 40px; font-family: sans-serif; color: #333;'>
                    <div style='max-width: 600px; margin: 0 auto; background: white; border-radius: 30px; overflow: hidden; box-shadow: 0 10px 30px rgba(255,182,193,0.2); border: 1px solid #ffe4e6;'>
                        
                        <div style='background: #111; padding: 30px; text-align: center;'>
                            <h1 style='color: white; margin: 0; font-size: 24px; letter-spacing: -1px;'>MUA<span style='color: #db2777;'>.</span> PROFESSIONAL</h1>
                            <p style='color: #999; font-size: 10px; text-transform: uppercase; letter-spacing: 2px; margin-top: 5px;'>Admin Notification Center</p>
                        </div>

                        <div style='padding: 40px;'>
                            <h2 style='font-size: 20px; font-weight: 900; color: #111; margin-bottom: 20px;'>Hai Admin, Ada Pesanan Baru! ✨</h2>
                            <p style='color: #666; font-size: 14px; line-height: 1.6;'>Seseorang baru saja melakukan booking melalui website. Berikut adalah rinciannya:</p>

                            <div style='background: #fdf2f8; border-radius: 20px; padding: 25px; margin: 30px 0;'>
                                <table style='width: 100%; font-size: 14px; border-collapse: collapse;'>
                                    <tr>
                                        <td style='padding: 8px 0; color: #9d174d; font-weight: bold; width: 100px;'>Pelanggan</td>
                                        <td style='padding: 8px 0; font-weight: 900;'>: {$this->booking->customer_name}</td>
                                    </tr>
                                    <tr>
                                        <td style='padding: 8px 0; color: #9d174d; font-weight: bold;'>Layanan</td>
                                        <td style='padding: 8px 0; font-weight: 900;'>: " . ($this->booking->category->name ?? 'Layanan/Promo Tidak Tersedia') . "</td>
                                    </tr>
                                    <tr>
                                        <td style='padding: 8px 0; color: #9d174d; font-weight: bold;'>Total Bayar</td>
                                        <td style='padding: 8px 0; font-size: 18px; font-weight: 900; color: #111;'>: Rp" . number_format($this->booking->total_amount, 0, ',', '.') . "</td>
                                    </tr>
                                </table>
                            </div>

                            <div style='text-align: center; margin-top: 40px;'>
                                <a href='http://127.0.0.1:8000/admin/orders' style='background: #db2777; color: white; padding: 18px 35px; border-radius: 15px; text-decoration: none; font-weight: bold; font-size: 14px; display: inline-block; box-shadow: 0 10px 20px rgba(219,39,119,0.2);'>
                                    KONFIRMASI PESANAN SEKARANG
                                </a>
                            </div>
                        </div>

                        <div style='padding: 20px; text-align: center; border-top: 1px solid #fce7f3; background: #fffafb;'>
                            <p style='color: #999; font-size: 11px;'>© 2026 MUA Professional Booking System</p>
                        </div>
                    </div>
                </div>
                ");
    }
}