<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran #{{ $booking->id }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace; /* Font ala mesin kasir */
            font-size: 14px;
            background-color: #f0f0f0;
            padding: 20px;
        }
        .struk-container {
            max-width: 350px; /* Lebar standar struk */
            background: white;
            padding: 20px;
            margin: 0 auto;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .fw-bold { font-weight: bold; }
        .divider { border-top: 1px dashed #000; margin: 10px 0; }
        .item-row { display: flex; justify-content: space-between; margin-bottom: 5px; }
        .total-row { display: flex; justify-content: space-between; font-weight: bold; margin-top: 10px; font-size: 16px;}
        
        @media print {
            body { background: white; padding: 0; }
            .struk-container { box-shadow: none; max-width: 100%; padding: 0; margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()"> <div class="struk-container">
        
        <div class="text-center">
            <h3 style="margin-bottom: 5px;">Lapangin</h3>
            <span style="font-size: 12px;">Jl. melur ujung(panam) pekanbaru</span><br>
            <span style="font-size: 12px;">Telp: 0812-3456-7890</span>
        </div>

        <div class="divider"></div>

        <div style="font-size: 12px;">
            <div>No. Nota : #{{ $booking->id }}</div>
            <div>Tgl      : {{ \Carbon\Carbon::parse($booking->tanggal)->format('d/m/Y') }}</div>
            <div>Pelanggan: {{ $booking->nama_pelanggan }}</div>
            <div>Kasir    : {{ Auth::user()->name ?? 'Staff' }}</div>
        </div>

        <div class="divider"></div>

        <div class="item-row">
            <span>Sewa Lapangan ({{ $durasi }} Jam)</span>
            <span>{{ number_format($biayaSewa, 0, ',', '.') }}</span>
        </div>

        @if($booking->jam_tambahan > 0)
        <div class="item-row">
            <span>Tambahan Waktu ({{ $booking->jam_tambahan }} Jam)</span>
            <span>{{ number_format($biayaExtra, 0, ',', '.') }}</span>
        </div>
        @endif

       @if($booking->denda > 0)
<div class="item-row">
    <span>Denda / Kerusakan</span>
    <span>{{ number_format($biayaDenda, 0, ',', '.') }}</span>
</div>
@endif

        <div class="divider"></div>

        <div class="total-row">
            <span>TOTAL</span>
            <span>Rp {{ number_format($totalBayar, 0, ',', '.') }}</span>
        </div>

        <div class="divider"></div>

        <div class="text-center" style="margin-top: 20px;">
            <p>Terima Kasih<br>Selamat Berolahraga!</p>
            <small style="font-size: 10px;">{{ date('d/m/Y H:i:s') }}</small>
        </div>

        <div class="text-center no-print" style="margin-top: 30px;">
            <a href="{{ route('staff.riwayat') }}" style="text-decoration: none; background: #333; color: white; padding: 10px 20px; border-radius: 5px;">Kembali</a>
        </div>

    </div>

</body>
</html>