<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

// tambahan
use App\Mail\ScheduleReminder;
use Illuminate\Support\Facades\Mail;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        // 1. Total Pesanan (Hanya yang sudah bayar valid)
        $totalOrders = Booking::whereIn('status', ['paid_dp', 'paid_full', 'confirmed'])->count();

        // 2. REVISI: Lunas vs DP (Format: Lunas / DP)
        $lunasCount = Booking::whereIn('status', ['paid_full', 'confirmed'])->count();
        $dpCount = Booking::where('status', 'paid_dp')->count();

        // 3. REVISI: Jadwal Bulan Ini
        $scheduleMonthCount = Booking::whereMonth('booking_date', $now->month)
                                    ->whereYear('booking_date', $now->year)
                                    ->whereIn('status', ['paid_dp', 'paid_full', 'confirmed'])
                                    ->count();

        // 4. Total Pendapatan Real (Uang yang sudah benar-benar masuk)
        $revenueFull = Booking::whereIn('status', ['paid_full', 'confirmed'])->sum('total_amount');
        $revenueDP = Booking::where('status', 'paid_dp')->sum('dp_amount');
        $totalRevenue = $revenueFull + $revenueDP;

        return view('dashboard', compact(
            'totalOrders', 
            'lunasCount', 
            'dpCount', 
            'scheduleMonthCount', 
            'totalRevenue'
        ));
    }

    /**
     * Method baru untuk halaman detail Jadwal Bulan Ini
     */
    public function monthlySchedule(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        $showPast = $request->has('show_past');

        $query = Booking::with(['category', 'bundling', 'location'])
            ->whereMonth('booking_date', $month)
            ->whereYear('booking_date', $year)
            ->whereIn('status', ['paid_dp', 'paid_full', 'confirmed'])
            ->orderBy('booking_date', 'asc')
            ->orderBy('start_time', 'asc');

        // Logic sembunyikan tanggal terlewat (jika tidak klik "tampilkan sebelumnya")
        if (!$showPast && $month == now()->month && $year == now()->year) {
            $query->where('booking_date', '>=', now()->format('Y-m-d'));
        }

        $bookings = $query->get();

        return view('admin.schedules.monthly', compact('bookings', 'month', 'year', 'showPast'));
    }


    // test reminder
    public function testReminder()
    {
        $admin = auth()->user();
        $targetDate = Carbon::today()->addDays($admin->reminder_days)->toDateString();

        // Masukkan status yang sah di sistem kamu
        $bookings = \App\Models\Booking::where('booking_date', $targetDate)
                        ->whereIn('status', ['success', 'paid_dp', 'confirmed', 'paid_full'])
                        ->get();

        if ($bookings->isEmpty()) {
            return back()->with('error_delete', 'Tidak ada jadwal untuk H-' . $admin->reminder_days . ' (Tgl: ' . Carbon::parse($targetDate)->format('d/m/Y') . ')');
        }

        foreach ($bookings as $booking) {
            \Illuminate\Support\Facades\Mail::to($admin->email)->send(new \App\Mail\ScheduleReminder($booking));
        }

        return back()->with('success_edit', 'Email pengingat berhasil dikirim ke ' . $admin->email);
    }
}