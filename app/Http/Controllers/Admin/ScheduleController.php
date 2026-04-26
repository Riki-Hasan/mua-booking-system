<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Location;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Bundling;
use Illuminate\Support\Facades\Storage;

class ScheduleController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function index()
    {
        $categories = Category::all();
        $locations = Location::all();
        $schedules = Booking::whereIn('status', ['confirmed', 'paid_dp', 'paid_full'])->get();
        $offlineBookings = Booking::where('order_id', 'like', 'OFFLINE-%')->latest()->get();
        return view('admin.schedules.index', compact('schedules', 'categories', 'locations', 'offlineBookings'));
    }

    public function preparePayment(Request $request)
    {
        // Paksa validasi di sini
        if (!$request->start_time || !$request->booking_date) {
            return response()->json(['status' => 'error', 'message' => 'Tanggal atau Jam belum dipilih!'], 422);
        }

        $category = Category::findOrFail($request->category_id);
        $multiplier = ($request->person_count >= 2) ? 1.5 : 1.0;
        $actualDuration = (int)($category->duration_minutes * $multiplier);
        
        $startTime = $request->start_time;
        $endTime = Carbon::parse($startTime)->addMinutes($actualDuration)->format('H:i');
        $formattedDate = Carbon::parse($request->booking_date)->format('Y-m-d');

        // Query Bentrok yang lebih aman
        $isClash = Booking::where('booking_date', $formattedDate)
            ->whereIn('status', ['confirmed', 'paid_dp', 'paid_full'])
            ->where(function($q) use ($startTime, $endTime) {
                $q->where('start_time', '<', $endTime)
                  ->where('end_time', '>', $startTime);
            })->exists();

        if ($isClash) {
            return response()->json(['status' => 'error', 'message' => 'Maaf, jam tersebut sudah terisi jadwal lain!'], 422);
        }

        try {
            $orderId = 'OFFLINE-' . time();
            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int)$request->dp_amount,
                ],
                'customer_details' => [
                    'first_name' => $request->customer_name,
                    'phone' => $request->whatsapp_number,
                ],
            ];
            $snapToken = Snap::getSnapToken($params);
            return response()->json(['status' => 'midtrans', 'snap_token' => $snapToken, 'order_id' => $orderId]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Midtrans: ' . $e->getMessage()], 500);
        }
    }

    public function storeManual(Request $request)
    {
        // Pastikan start_time ada
        if (!$request->start_time) {
            return response()->json(['status' => 'error', 'message' => 'Data jam (start_time) hilang!'], 422);
        }

        $category = Category::findOrFail($request->category_id);
        $location = Location::find($request->location_id);
        
        $multiplier = ($request->person_count >= 2) ? 1.5 : 1.0;
        $actualDuration = (int)($category->duration_minutes * $multiplier);

        $startTime = $request->start_time;
        $endTime = Carbon::parse($startTime)->addMinutes($actualDuration)->format('H:i');
        $formattedDate = Carbon::parse($request->booking_date)->format('Y-m-d');

        $totalAmount = ($category->base_price * $request->person_count) + ($location->additional_price ?? 0);
        $dpPaid = $request->dp_amount ?? 0;
        $status = ($dpPaid >= $totalAmount) ? 'paid_full' : 'paid_dp';

        Booking::create([
            'order_id' => $request->order_id ?? 'OFFLINE-' . time(),
            'customer_name' => $request->customer_name,
            'whatsapp_number' => $request->whatsapp_number,
            'address' => $request->address,
            'category_id' => $request->category_id,
            'location_id' => ($request->location_id == 0) ? null : $request->location_id,
            'booking_date' => $formattedDate,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'person_count' => $request->person_count,
            'total_amount' => $totalAmount,
            'dp_amount' => $dpPaid,
            'payment_proof' => $request->payment_method, 
            'status' => $status, 
        ]);

        return response()->json(['status' => 'success']);
    }

    public function toggleHoliday(Request $request)
    {
        $date = $request->date;

        // Cek booking aktif
        $hasBooking = Booking::whereDate('booking_date', $date)
            ->whereIn('status', ['confirmed', 'paid_dp', 'paid_full', 'success'])
            ->exists();

        if ($hasBooking) {
            return response()->json(['error' => 'Tidak bisa libur! Sudah ada jadwal booking pada tanggal tersebut.'], 422);
        }

        $exists = Holiday::whereDate('holiday_date', $date)->first();
        if ($exists) {
            $exists->delete();
            return response()->json(['status' => 'removed']);
        } else {
            Holiday::create(['holiday_date' => $date]);
            return response()->json(['status' => 'added']);
        }
    }


    // 1. Update Foto Profil Branding
    public function updateProfilePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = auth()->user();

        // Hapus foto lama jika ada
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        // Simpan foto baru
        $path = $request->file('profile_photo')->store('profile-photos', 'public');
        $user->update(['profile_photo_path' => $path]);

        return back()->with('success', 'Foto branding berhasil diperbarui!');
    }

    // 2. Simpan Promo Bundling Baru
    public function storeBundling(Request $request)
    {
        $request->validate([
            'subject' => 'required|string',
            'price' => 'required|numeric',
            'main_image' => 'required|image|max:2048',
            'secondary_image' => 'required|image|max:2048',
            'short_description' => 'required|string|max:150',
            'description' => 'required|string',
            'duration_minutes' => 'required|numeric',
            'target_person_count' => 'required|numeric',
        ]);

        // Simpan Gambar
        $mainPath = $request->file('main_image')->store('bundlings', 'public');
        $secondaryPath = $request->file('secondary_image')->store('bundlings', 'public');

        Bundling::create([
            'subject' => $request->subject,
            'price' => $request->price,
            'main_image' => $mainPath,
            'secondary_image' => $secondaryPath,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'include_text' => $request->short_description,
            'duration_minutes' => $request->duration_minutes,
            'target_person_count' => $request->target_person_count,
        ]);

        return back()->with('success', 'Promo Bundling berhasil ditambahkan!');
    }

    // 3. Hapus Bundling
    public function destroyBundling($id)
    {
        $bundling = Bundling::findOrFail($id);
        Storage::disk('public')->delete([$bundling->main_image, $bundling->secondary_image]);
        $bundling->delete();

        return back()->with('success_delete', 'Promo berhasil dihapus.');
    }

    public function updateBundling(Request $request, $id)
    {
        $bundling = \App\Models\Bundling::findOrFail($id);
        
        $request->validate([
            'subject' => 'required|string',
            'price' => 'required|numeric',
            'short_description' => 'required|string',
            'description' => 'nullable|string',
            'main_image' => 'nullable|image|max:2048',
            'secondary_image' => 'nullable|image|max:2048',
            'duration_minutes' => 'required|numeric',
        'target_person_count' => 'required|numeric',
        ]);

        $data = [
            'subject' => $request->subject,
            'price' => $request->price,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'duration_minutes' => $request->duration_minutes,
        'target_person_count' => $request->target_person_count,
            'include_text' => $request->short_description,
        ];

        // Cek jika ada upload Foto Utama baru
        if ($request->hasFile('main_image')) {
            Storage::disk('public')->delete($bundling->main_image);
            $data['main_image'] = $request->file('main_image')->store('bundlings', 'public');
        }

        // Cek jika ada upload Foto Kecil baru
        if ($request->hasFile('secondary_image')) {
            Storage::disk('public')->delete($bundling->secondary_image);
            $data['secondary_image'] = $request->file('secondary_image')->store('bundlings', 'public');
        }

        $bundling->update($data);

        return back()->with('success_edit', 'Promo Bundling berhasil diperbarui!');
    }
}