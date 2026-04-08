<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function index()
    {
        // Ambil kategori & lokasi untuk dropdown
        $categories = Category::all();
        $locations = Location::all();
        
        // Ambil semua jadwal untuk kalender (Online & Offline)
        $schedules = Booking::whereIn('status', ['confirmed', 'success', 'paid_dp', 'paid_full'])->get();
        
        // Ambil hanya booking offline untuk tabel di bawah
        $offlineBookings = Booking::where('order_id', 'like', 'OFFLINE-%')
                            ->latest()
                            ->get();

        return view('admin.schedules.index', compact('schedules', 'categories', 'locations', 'offlineBookings'));
    }

    public function storeManual(Request $request)
    {
        $category = Category::findOrFail($request->category_id);
        $location = Location::find($request->location_id);
        
        // Konversi tanggal ke format database (Y-m-d) agar MySQL tidak protes
        $formattedDate = Carbon::parse($request->booking_date)->format('Y-m-d');

        $startTime = $request->start_time;
        $endTime = Carbon::parse($startTime)
                ->addMinutes($category->duration_minutes)
                ->format('H:i');

        $additionalPrice = $location ? $location->additional_price : 0;
        $totalAmount = $category->base_price + $additionalPrice;
        $dpPaid = $request->dp_amount;
        $status = ($dpPaid >= $totalAmount) ? 'paid_full' : 'paid_dp';

        Booking::create([
            'order_id' => 'OFFLINE-' . time(),
            'customer_name' => $request->customer_name,
            'whatsapp_number' => $request->whatsapp_number,
            'address' => $request->address,
            'category_id' => $request->category_id,
            'location_id' => $request->location_id == 0 ? null : $request->location_id,
            'booking_date' => $formattedDate, // <--- Pakai yang sudah di-format
            'start_time' => $startTime,
            'end_time' => $endTime,
            'total_amount' => $totalAmount,
            'dp_amount' => $dpPaid,
            'payment_method' => $request->payment_method,
            'status' => $status, 
        ]);

        return back()->with('success', 'Jadwal offline berhasil ditambahkan!');
    }

    public function monthlySchedule(Request $request)
    {
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);
        $showPast = $request->has('show_past');

        $query = Booking::with(['category', 'location'])
            ->whereMonth('booking_date', $month)
            ->whereYear('booking_date', $year)
            ->whereIn('status', ['paid_dp', 'paid_full', 'confirmed', 'success'])
            ->orderBy('booking_date', 'asc')
            ->orderBy('start_time', 'asc');

        if (!$showPast && $month == now()->month && $year == now()->year) {
            $query->where('booking_date', '>=', now()->format('Y-m-d'));
        }

        $bookings = $query->get();
        return view('admin.schedules.monthly', compact('bookings', 'month', 'year', 'showPast'));
    }
}