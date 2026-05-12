<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendBookingReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 1. Ambil data Admin (karena sistem background tidak ada auth()->user)
        $admin = \App\Models\User::first(); 

        if (!$admin) {
            $this->error('Data admin tidak ditemukan.');
            return;
        }

        $days = $admin->reminder_days ?? 1;
        $targetDate = \Carbon\Carbon::today()->addDays($days)->toDateString();

        $bookings = \App\Models\Booking::where('booking_date', $targetDate)
                    ->whereIn('status', ['success', 'paid_dp', 'confirmed', 'paid_full'])
                    ->get();

        if ($bookings->isEmpty()) {
            $this->info('Tidak ada jadwal untuk tanggal: ' . $targetDate);
            return;
        }

        foreach ($bookings as $booking) {
            try {
                // PERBAIKAN: Kirimkan $admin sebagai parameter kedua
                \Illuminate\Support\Facades\Mail::to($admin->email)
                    ->send(new \App\Mail\ScheduleReminder($booking, $admin));
                
                $this->info('Berhasil mengirim email ke: ' . $admin->email);
            } catch (\Exception $e) {
                $this->error('Gagal mengirim email: ' . $e->getMessage());
            }
        }
    }

    
}
