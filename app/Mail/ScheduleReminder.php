<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ScheduleReminder extends Mailable
{
    use Queueable, SerializesModels;

        public $booking;
        public $waUrl;
        public $admin;

        public function __construct($booking, $admin)
        {
            $this->booking = $booking;
            $this->admin = $admin;
            
            // Logic Template WhatsApp
            $waNum = preg_replace('/^0/', '62', $booking->whatsapp_number);
            $totalCash = $booking->total_amount - $booking->dp_amount;
            $tglFormat = \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('l, d F Y');
            
            $msg = "*Reminder H-1 Makeup* (*{$booking->category->name}*):
    - Diusahakan client *tidak begadang* dan istirahat yg cukup *agar fit dan freshh saat dimakeup*😊
    - ⁠kita *start makeup jam {$booking->start_time} pagi* sudah ready ya kak supaya nanti tidak terlambat untuk acaranya
    - ⁠kalo bisa malam ini *memakai sheet mask*(ga wajib gpp) supaya kulit plumpy dan makeup bisa nempel + awet
    - ⁠*client sudah memakai atasan kebaya/kostum supaya tidak terburu2 after makeup & juga hijab sudah di setrika rapih untuk bagian tengahnya yaa*🫶🏼
    - ⁠Jika ada yg sedang sholat diusahakan untuk client *sebelum makeup sudah mengambil wudhu terlebih dahulu ya kak*✨
    - ⁠*lokasi ada di {$booking->address} ({$booking->person_count} orang)*. Makeup nya 1 orang Rp".number_format($booking->category->base_price,0,',','.')." dan sudah membayar dp Rp".number_format($booking->dp_amount,0,',','.').", jadi *total cash nya Rp".number_format($totalCash,0,',','.')."* yaa.

    See u kaka cantik besok pagi jam {$booking->start_time} jangan sampai kelewatan yaaa😍🫶🏼";

            $this->waUrl = "https://wa.me/{$waNum}?text=" . urlencode($msg);
        }

        public function build()
        {
            return $this->subject('📢 Pengingat Jadwal Makeup - ' . $this->booking->customer_name)
                        ->view('emails.reminder');
        }
}
