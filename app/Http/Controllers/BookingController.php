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
use App\Models\Holiday;

class BookingController extends Controller
{
    public function create($category_id)
    {
        $category = Category::findOrFail($category_id);
        $locations = Location::all();
        return view('booking.create', compact('category', 'locations'));
    }

    // ... bagian atas tetap sama ...

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'whatsapp_number' => 'required',
            'booking_date' => 'required',
            'start_time' => 'required',
            'address' => 'required',
            'person_count' => 'required|numeric|min:1', // Tambahkan validasi
        ]);

        $bulanIndo = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $bulanAngka = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
        $dateStep1 = str_replace($bulanIndo, $bulanAngka, $request->booking_date);
        $dbDate = \Carbon\Carbon::createFromFormat('d-m-Y', $dateStep1)->format('Y-m-d');

        $category = Category::findOrFail($request->category_id);
        
        $additionalPrice = 0;
        $locationId = null;
        if ($request->location_id != 0) {
            $location = Location::findOrFail($request->location_id);
            $additionalPrice = $location->additional_price;
            $locationId = $location->id;
        }

        // REVISI: Harga Paket x Jumlah Orang + Ongkir
        $totalAmount = ($category->base_price * $request->person_count) + $additionalPrice;
        
        $endTime = \Carbon\Carbon::parse($request->start_time)
                    ->addMinutes($category->duration_minutes)
                    ->format('H:i');

        $booking = Booking::create([
            'order_id' => 'MUA-' . strtoupper(bin2hex(random_bytes(3))),
            'customer_name' => $request->customer_name,
            'whatsapp_number' => $request->whatsapp_number,
            'address' => $request->address,
            'category_id' => $category->id,
            'location_id' => $locationId,
            'booking_date' => $dbDate,
            'start_time' => $request->start_time,
            'end_time' => $endTime,
            'person_count' => $request->person_count, // Simpan jumlah orang
            'total_amount' => $totalAmount,
            'dp_amount' => $totalAmount * 0.5,
            'status' => 'pending',
        ]);

        return redirect()->route('payment.summary', $booking->id);
    }

    public function checkAvailability(Request $request)
    {
        // Ambil input dari query string
        $month = $request->query('month');
        $year = $request->query('year');

        // Pastikan variabel tidak kosong
        if (!$month || !$year) {
            return response()->json([]);
        }

        // 1. Ambil Data Booking
        $bookings = Booking::whereMonth('booking_date', $month)
                    ->whereYear('booking_date', $year)
                    ->whereIn('status', ['confirmed', 'paid_dp', 'paid_full', 'success'])
                    ->get();

        // 2. Ambil Data Libur (Gunakan try-catch agar tidak crash jika tabel belum ada)
        try {
            $holidays = Holiday::whereMonth('holiday_date', $month)
                        ->whereYear('holiday_date', $year)
                        ->get();
        } catch (\Exception $e) {
            $holidays = collect(); // Jika error/tabel belum ada, buat koleksi kosong saja
        }

        $availability = [];

        // Tandai Hari Libur
        foreach ($holidays as $h) {
            $day = (int)\Carbon\Carbon::parse($h->holiday_date)->format('j');
            $availability[$day] = ['status' => 'holiday', 'details' => []];
        }

        // Tandai Data Booking
        foreach ($bookings as $b) {
            $day = (int)\Carbon\Carbon::parse($b->booking_date)->format('j');
            
            if (isset($availability[$day]) && $availability[$day]['status'] === 'holiday') continue;

            if (!isset($availability[$day])) {
                $availability[$day] = ['status' => 'partial', 'details' => []];
            }
            
            $availability[$day]['details'][] = [
                'start' => \Carbon\Carbon::parse($b->start_time)->format('H:i'),
                'end' => \Carbon\Carbon::parse($b->end_time)->format('H:i')
            ];
            
            if (count($availability[$day]['details']) >= 3) {
                $availability[$day]['status'] = 'full';
            }
        }

        return response()->json((object)$availability);
    }


    public function calendar($category_id)
    {
        $category = Category::findOrFail($category_id);
        return view('booking.calendar', compact('category'));
    }
}