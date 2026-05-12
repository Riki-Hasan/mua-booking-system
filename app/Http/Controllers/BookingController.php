<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Category;
use App\Models\Location;
use App\Models\Bundling;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    /**
     * Helper: Mendapatkan data Kategori atau Promo secara dinamis
     */
    private function getSourceData($id)
    {
        if (str_starts_with($id, 'promo-')) {
            $realId = str_replace('promo-', '', $id);
            $bundling = Bundling::findOrFail($realId);
            
            // Samarkan bundling menjadi object yang serupa dengan Category
            return (object)[
                'id' => 'promo-' . $bundling->id,
                'name' => $bundling->subject,
                'base_price' => $bundling->price,
                'duration_minutes' => $bundling->duration_minutes,
                'is_bundling' => true,
                'target_person' => $bundling->target_person_count
            ];
        }
        
        $category = Category::findOrFail($id);
        // Tambahkan property target_person default untuk kategori biasa
        $category->target_person = 1;
        $category->is_bundling = false;
        return $category;
    }

    public function create($category_id)
    {
        $category = $this->getSourceData($category_id);
        $locations = Location::all();
        return view('booking.create', compact('category', 'locations'));
    }

    public function createFromPromo($bundling_id)
    {
        // Langsung arahkan ke fungsi create dengan prefix promo-
        return $this->create('promo-' . $bundling_id);
    }

    public function calendar($category_id)
    {
        $category = $this->getSourceData($category_id);
        return view('booking.calendar', compact('category'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'whatsapp_number' => 'required',
            'booking_date' => 'required',
            'start_time' => 'required',
            'address' => 'required',
            'person_count' => 'required|numeric|min:1|max:2',
        ]);

        $source = $this->getSourceData($request->category_id);
        $persons = (int)$request->person_count;
        
        // DEKLARASI START TIME
        $startTime = $request->start_time;

        // 1. LOGIKA HARGA 
        $calculatedPrice = $source->base_price;
        if ($source->is_bundling) {
            // Promo 1 orang diubah jadi 2 orang -> Harga x 2
            if ($source->target_person == 1 && $persons == 2) {
                $calculatedPrice = $source->base_price * 2;
            }
        } else {
            // Layanan biasa -> Harga x Jumlah Orang
            $calculatedPrice = $source->base_price * $persons;
        }

        // 2. LOGIKA DURASI 
        // Jika 2 orang (baik promo maupun biasa), durasi dikali 1.5
        $multiplier = ($persons >= 2) ? 1.5 : 1.0;
        $actualDuration = (int)($source->duration_minutes * $multiplier);

        $endTime = Carbon::parse($startTime)
                    ->addMinutes($actualDuration)
                    ->format('H:i');

        // 3. KONVERSI TANGGAL INDONESIA
        $bulanIndo = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $bulanAngka = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
        $dateStep1 = str_replace($bulanIndo, $bulanAngka, $request->booking_date);
        $dbDate = Carbon::createFromFormat('d-m-Y', $dateStep1)->format('Y-m-d');


        // CEK BENTROK SEBELUM SIMPAN
        $isClash = Booking::where('booking_date', $dbDate)
            ->whereIn('status', ['confirmed', 'paid_dp', 'paid_full', 'pending', 'success'])
            ->where(function($q) use ($startTime, $endTime) {
                $q->where('start_time', '<', $endTime)
                ->where('end_time', '>', $startTime);
            })->exists();

        if ($isClash) {
            return back()->withInput()->withErrors(['error' => 'Maaf, jam tersebut baru saja dipesan orang lain. Silakan pilih jam atau tanggal lain!']);
        }

        // 4. ONGKIR (Tetap berbayar, transport gratis dihapus)
        $additionalPrice = 0;
        $locationId = null;
        if ($request->location_id != 0) {
            $location = Location::findOrFail($request->location_id);
            $additionalPrice = $location->additional_price;
            $locationId = $location->id;
        }

        $totalAmount = $calculatedPrice + $additionalPrice;

        $booking = Booking::create([
            'order_id' => 'MUA-' . strtoupper(bin2hex(random_bytes(3))),
            'customer_name' => $request->customer_name,
            'whatsapp_number' => $request->whatsapp_number,
            'address' => $request->address,
            'location_id' => $locationId,
            'booking_date' => $dbDate,
            'start_time' => $startTime, // Menggunakan variabel yang sudah dibuat
            'end_time' => $endTime,
            'person_count' => $persons,
            'total_amount' => $totalAmount,
            'dp_amount' => $totalAmount * 0.5,
            'status' => 'pending',
            'category_id' => $source->is_bundling ? null : $source->id,
            'bundling_id' => $source->is_bundling ? str_replace('promo-', '', $source->id) : null,
        ]);

        return redirect()->route('payment.summary', $booking->id);
    }

    public function checkAvailability(Request $request)
    {
        $month = $request->query('month');
        $year = $request->query('year');

        if (!$month || !$year) return response()->json([]);

        $bookings = Booking::whereMonth('booking_date', $month)
                    ->whereYear('booking_date', $year)
                    ->whereIn('status', ['confirmed', 'paid_dp', 'paid_full', 'success'])
                    ->get();

        try {
            $holidays = Holiday::whereMonth('holiday_date', $month)
                        ->whereYear('holiday_date', $year)
                        ->get();
        } catch (\Exception $e) { $holidays = collect(); }

        $availability = [];
        foreach ($holidays as $h) {
            $day = (int)Carbon::parse($h->holiday_date)->format('j');
            $availability[$day] = ['status' => 'holiday', 'details' => []];
        }

        foreach ($bookings as $b) {
            $day = (int)Carbon::parse($b->booking_date)->format('j');
            if (isset($availability[$day]) && $availability[$day]['status'] === 'holiday') continue;

            if (!isset($availability[$day])) {
                $availability[$day] = ['status' => 'partial', 'details' => [], 'total_minutes' => 0];
            }
            
            // Hitung durasi pesanan
            $start = Carbon::parse($b->start_time);
            $end = Carbon::parse($b->end_time);
            $duration = $start->diffInMinutes($end);

            $availability[$day]['details'][] = [
                'start' => $start->format('H:i'),
                'end' => $end->format('H:i')
            ];
            
            $availability[$day]['total_minutes'] += $duration;
            
            // REVISI: Hanya "Full" (Merah) jika total jadwal mencapai 24 jam (1440 menit)
            if ($availability[$day]['total_minutes'] >= 1440) {
                $availability[$day]['status'] = 'full';
            }
        }
        return response()->json((object)$availability);
    }
}