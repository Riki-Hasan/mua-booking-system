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
        $orders = Booking::with(['category', 'location'])
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
        // Laporan juga hanya mengambil data yang valid (sudah bayar/confirmed)
        $orders = \App\Models\Booking::with('category')
                    ->whereIn('status', ['paid_dp', 'paid_full', 'confirmed'])
                    ->whereMonth('booking_date', now()->month)
                    ->get();

        $totalIncome = $orders->sum('total_amount');

        $pdf = Pdf::loadView('admin.orders.report_pdf', compact('orders', 'totalIncome'));

        return $pdf->download('Laporan_MUA_' . now()->format('M_Y') . '.pdf');
    }

    public function destroy($id)
    {
        try {
            $booking = \App\Models\Booking::findOrFail($id);
            $booking->delete();

            return redirect()->route('admin.orders.index')
                ->with('success', 'Pesanan #' . $id . ' berhasil dihapus.');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.orders.index')
                ->with('error', 'Gagal menghapus pesanan: ' . $e->getMessage());
        }
    }
}