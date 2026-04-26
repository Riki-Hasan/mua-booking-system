<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Exception;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        Config::$isSanitized = env('MIDTRANS_IS_SANITIZED');
        Config::$is3ds = env('MIDTRANS_IS_3DS');

        // Fix untuk lingkungan local/Windows (Laragon/XAMPP)
        putenv('CURL_CA_BUNDLE=');
    }

    public function getToken(Request $request)
    {
        try {
            // Re-set curl options untuk menangani issue SSL di lokal
            Config::$curlOptions = [
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_HTTPHEADER     => [] 
            ];

            $booking = Booking::findOrFail($request->booking_id);

            // 1. Kunci Transaksi: Hanya boleh bayar jika status 'pending'
            if ($booking->status !== 'pending') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pesanan ini sudah diproses atau sudah dibayar.'
                ], 403);
            }

            $amount = ($request->type == 'dp') ? $booking->dp_amount : $booking->total_amount;
            $finishUrl = (auth()->check() && auth()->user()->is_admin) 
                            ? route('admin.orders.index') 
                            : route('booking.success', $booking->id);
            $params = [
                'transaction_details' => [
                    'order_id' => 'MUA-' . $request->type . '-' . $booking->id . '-' . time(),
                    'gross_amount' => (int) $amount,
                ],
                'customer_details' => [
                    'first_name' => $booking->customer_name,
                    'phone' => $booking->whatsapp_number,
                    'email' => 'customer@mail.com', 
                ],
                'callbacks' => [
                    'finish' => $finishUrl,
                    'unfinish' => route('payment.summary', $booking->id),
                    'error' => route('payment.summary', $booking->id),
                ]
            ];

            $snapToken = Snap::getSnapToken($params);
            
            return response()->json(['snap_token' => $snapToken]);

        } catch (Exception $e) {
            Log::error("Gagal Request Midtrans: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }

    public function summary($id)
    {

        $booking = Booking::with(['category', 'bundling', 'location'])->findOrFail($id);

        return view('payment.summary', [
            'booking' => $booking,
            'total' => $booking->total_amount,
            'dp_amount' => $booking->dp_amount
        ]);
    }

    public function showReceipt($id)
    {
        // Eager load category, bundling, dan location sekaligus untuk mencegah crash di View
        $booking = \App\Models\Booking::with(['category', 'bundling', 'location'])->findOrFail($id);
        
        // Logika penentuan status pembayaran
        $isFull = ($booking->status == 'paid_full' || $booking->status == 'confirmed');
        $paidAmount = $isFull ? $booking->total_amount : $booking->dp_amount;
        $remainingBalance = $booking->total_amount - $paidAmount;

        $typeLabel = $isFull ? 'LUNAS (FULL PAYMENT)' : 'DOWN PAYMENT (50%)';

        return view('payment.receipt', compact('booking', 'typeLabel', 'remainingBalance', 'paidAmount', 'isFull'));
    }

    public function showSuccess($id)
    {
        $booking = Booking::findOrFail($id);
        return view('booking.success', compact('booking'));
    }

    public function callback(Request $request)
    {
        Log::info("Callback Midtrans Masuk!", $request->all());

        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        // 1. Validasi Signature (Keamanan)
        if ($hashed !== $request->signature_key) {
            Log::warning("Signature Key Tidak Cocok! Percobaan manipulasi data.");
            return response()->json(['message' => 'Invalid Signature'], 403);
        }

        $transactionStatus = $request->transaction_status;
        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            
            $orderData = explode('-', $request->order_id);
            // Format Order ID: MUA-{type}-{bookingId}-{timestamp}
            $type = $orderData[1]; 
            $bookingId = $orderData[2];

            $booking = Booking::find($bookingId);
            
            if ($booking) {
                // 2. Cegah Downgrade Status: 
                // Jika sudah Lunas (paid_full) atau Dikonfirmasi (confirmed), jangan ubah lagi.
                if (in_array($booking->status, ['paid_full', 'confirmed'])) {
                    Log::info("Booking #$bookingId sudah berstatus " . $booking->status . ". Update dilewati.");
                } else {
                    $booking->status = ($type == 'dp') ? 'paid_dp' : 'paid_full';
                    $booking->save();
                    Log::info("Booking #$bookingId berhasil diupdate ke: " . $booking->status);
                }
            } else {
                Log::error("Booking #$bookingId tidak ditemukan di database saat callback.");
            }
        }

        return response()->json(['status' => 'success']);
    }
}