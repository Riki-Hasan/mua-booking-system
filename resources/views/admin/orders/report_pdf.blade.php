<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pendapatan MUA</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .total { margin-top: 20px; text-align: right; font-size: 16px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PENDAPATAN MUA PROFESSIONAL</h1>
        <p>Bulan: {{ now()->format('F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Layanan</th>
                <th>Total Bayar</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $index => $order)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($order->booking_date)->format('d/m/Y') }}</td>
                <td>{{ $order->customer_name }}</td>
                <td>{{ $order->category->name }}</td>
                <td>Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        TOTAL PENDAPATAN BULAN INI: Rp{{ number_format($totalIncome, 0, ',', '.') }}
    </div>

    <div style="margin-top: 50px; text-align: right;">
        Tegal, {{ now()->format('d F Y') }} <br><br><br><br>
        (...........................) <br>
        Admin MUA
    </div>
</body>
</html>