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

        // 2. Lunas vs DP
        $lunasCount = Booking::whereIn('status', ['paid_full', 'confirmed'])->count();
        $dpCount = Booking::where('status', 'paid_dp')->count();

        // 3. Jadwal Bulan Ini
        $scheduleMonthCount = Booking::whereMonth('booking_date', $now->month)
                                    ->whereYear('booking_date', $now->year)
                                    ->whereIn('status', ['paid_dp', 'paid_full', 'confirmed'])
                                    ->count();

        // 4. REVISI: Total Pendapatan (Menghitung Harga Full Paket)
        // Kita langsung jumlahkan 'total_amount' untuk semua status yang valid
        $totalRevenue = Booking::whereIn('status', ['paid_dp', 'paid_full', 'confirmed'])
                                ->sum('total_amount');

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


    
}