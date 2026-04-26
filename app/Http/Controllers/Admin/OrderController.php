<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    // Melihat semua pesanan (HANYA yang sudah bayar/konfirmasi)
    public function index()
    {
        // Kita filter: status 'pending' tidak akan ditarik ke view
        $orders = Booking::with(['category', 'bundling', 'location'])
            ->whereIn('status', ['paid_dp', 'paid_full', 'confirmed']) 
            ->latest()
            ->get();

        return view('admin.orders.index', compact('orders'));
    }

    // Mengonfirmasi pembayaran pelanggan (dari paid_dp/paid_full ke confirmed)
    public function confirm($id)
    {
        $order = Booking::findOrFail($id);
        $order->update(['status' => 'confirmed']);
        
        return back()->with('success', 'Pesanan berhasil dikonfirmasi dan masuk jadwal tetap!');
    }

    public function downloadReport()
    {
        // Mengambil data booking yang valid di bulan ini [cite: 2]
        $orders = \App\Models\Booking::with('category')
                    ->whereIn('status', ['paid_dp', 'paid_full', 'confirmed'])
                    ->whereMonth('booking_date', now()->month)
                    ->get();

        // TOTAL PENDAPATAN BULAN INI: Dari total harga full semua pesanan 
        $totalIncome = $orders->sum('total_amount');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.orders.report_pdf', compact('orders', 'totalIncome'));

        return $pdf->download('Laporan_MUA_' . now()->format('M_Y') . '.pdf');
    }

    public function destroy($id)
        {
            try {
                $booking = Booking::findOrFail($id);
                $customerName = $booking->customer_name; // Simpan nama dulu
                $booking->delete();

                // REVISI 2: Modal sukses dengan nama pelanggan
                return redirect()->route('admin.orders.index')
                    ->with('success', 'Pesanan milik ' . $customerName . ' berhasil dihapus dari sistem.');
                    
            } catch (\Exception $e) {
                return redirect()->route('admin.orders.index')
                    ->with('error', 'Gagal menghapus: ' . $e->getMessage());
            }
        }
}