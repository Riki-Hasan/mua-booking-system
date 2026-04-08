<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Category;
use App\Models\Location;
use App\Models\User;
use App\Mail\NewOrderMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function create($category_id)
    {
        $category = Category::findOrFail($category_id);
        $locations = Location::all();
        return view('booking.create', compact('category', 'locations'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input (Hapus payment_proof sesuai rencana Midtrans)
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'whatsapp_number' => 'required',
            'booking_date' => 'required',
            'start_time' => 'required',
            'address' => 'required',
        ]);

        // --- BAGIAN PERBAIKAN TANGGAL ---
        // Konversi "28-Maret-2026" menjadi "2026-03-28"
        $bulanIndo = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $bulanAngka = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
        
        // Ganti nama bulan menjadi angka
        $dateStep1 = str_replace($bulanIndo, $bulanAngka, $request->booking_date);
        
        // Ubah ke format Y-m-d agar diterima MySQL
        $dbDate = \Carbon\Carbon::createFromFormat('d-m-Y', $dateStep1)->format('Y-m-d');
        // --------------------------------

        $category = Category::findOrFail($request->category_id);
        
        $additionalPrice = 0;
        $locationId = null;
        if ($request->location_id != 0) {
            $location = Location::findOrFail($request->location_id);
            $additionalPrice = $location->additional_price;
            $locationId = $location->id;
        }

        $totalAmount = $category->base_price + $additionalPrice;
        
        $endTime = \Carbon\Carbon::parse($request->start_time)
                    ->addMinutes($category->duration_minutes)
                    ->format('H:i');

        // 2. Simpan ke Database menggunakan $dbDate
        $booking = Booking::create([
            'order_id' => 'MUA-' . strtoupper(bin2hex(random_bytes(3))),
            'customer_name' => $request->customer_name,
            'whatsapp_number' => $request->whatsapp_number,
            'address' => $request->address,
            'category_id' => $category->id,
            'location_id' => $locationId,
            'booking_date' => $dbDate, // Gunakan hasil konversi
            'start_time' => $request->start_time,
            'end_time' => $endTime,
            'total_amount' => $totalAmount,
            'dp_amount' => $totalAmount * 0.5,
            'status' => 'pending',
        ]);

        return redirect()->route('payment.summary', $booking->id);
    }

    public function checkAvailability(Request $request)
    {
        $month = $request->month;
        $year = $request->year;

        $bookings = Booking::whereMonth('booking_date', $month)
                    ->whereYear('booking_date', $year)
                    ->whereIn('status', ['confirmed', 'paid_dp', 'paid_full']) // Cek semua yang sudah bayar
                    ->get();

        $availability = [];
        foreach ($bookings as $b) {
            $day = Carbon::parse($b->booking_date)->format('j');
            if (!isset($availability[$day])) {
                $availability[$day] = ['status' => 'partial', 'details' => []];
            }
            $availability[$day]['details'][] = [
                'start' => Carbon::parse($b->start_time)->format('H:i'),
                'end' => Carbon::parse($b->end_time)->format('H:i')
            ];
            
            if (count($availability[$day]['details']) >= 3) {
                $availability[$day]['status'] = 'full';
            }
        }

        return response()->json($availability);
    }

    public function calendar($category_id)
    {
        $category = Category::findOrFail($category_id);
        return view('booking.calendar', compact('category'));
    }
}