<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pendapatan MUA Professional</title> <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; } /* [cite: 1] */
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f9f9f9; font-weight: bold; text-transform: uppercase; font-size: 10px; }
        .total-box { margin-top: 20px; text-align: right; font-size: 14px; font-weight: bold; border-top: 2px solid #eee; padding-top: 10px; }
        .footer { margin-top: 50px; text-align: right; }
        .status-dp { color: #2563eb; font-weight: bold; }
        .status-full { color: #059669; font-weight: bold; }
        .row-total { background-color: #fcfcfc; font-weight: bold; }
        .text-total { text-align: right; text-transform: uppercase; letter-spacing: 1px; color: #666; }
        
        .footer { margin-top: 50px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PENDAPATAN MUA PROFESSIONAL</h1> <p>Bulan: {{ now()->translatedFormat('F Y') }}</p> </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th>Tanggal</th> <th>Pelanggan</th> <th>Layanan</th> <th>Pembayaran</th> <th>Harga Layanan</th></tr>
        </thead>
        <tbody>
            @foreach($orders as $index => $order)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($order->booking_date)->format('d/m/Y') }}</td>
                <td>{{ $order->customer_name }}</td> 
                <td>
                    @if($order->category)
                        {{ $order->category->name }}
                    @elseif(isset($order->bundling) && $order->bundling)
                        {{ $order->bundling->subject }} <small style="color: #666;">(Bundling)</small>
                    @else
                        {{ $order->bundling_id ? 'Paket Bundling' : 'Layanan Tidak Tersedia' }}
                    @endif
                </td>
                <td>
                    @if($order->dp_amount >= $order->total_amount)
                        <span class="status-full">[LUNAS]</span>
                    @else
                        <span class="status-dp">[DP]</span>
                    @endif
                    Rp{{ number_format($order->dp_amount, 0, ',', '.') }} </td>
                    <td>Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td> 
            </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr class="row-total">
                <td colspan="5" class="text-total">Total</td>
                <td>Rp{{ number_format($totalIncome, 0, ',', '.') }}</td>
                </tr>
        </tfoot>

    </table>

    <div class="total-box">
        TOTAL PENDAPATAN BULAN INI: Rp{{ number_format($totalIncome, 0, ',', '.') }} </div>

    <div class="footer">
        Tegal, {{ now()->translatedFormat('d F Y') }} <br><br><br><br> (...........................) <br> Admin MUA </div>
</body>
</html>