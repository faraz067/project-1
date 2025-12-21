<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran #{{ $booking->id }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 13px; /* Sedikit diperkecil agar muat lebih banyak */
            background-color: #f0f0f0;
            padding: 20px;
        }
        .struk-container {
            max-width: 300px; /* Lebar standar printer thermal 80mm/58mm */
            background: white;
            padding: 15px;
            margin: 0 auto;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .fw-bold { font-weight: bold; }
        .divider { border-top: 1px dashed #000; margin: 8px 0; }
        
        .item-row { 
            display: flex; 
            justify-content: space-between; 
            margin-bottom: 4px; 
        }
        
        /* Utilitas untuk baris total agar rapi */
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }
        
        .grand-total {
            font-size: 16px;
            font-weight: bold;
            margin-top: 5px;
            margin-bottom: 5px;
        }

        @media print {
            body { background: white; padding: 0; }
            .struk-container { box-shadow: none; max-width: 100%; width: 100%; padding: 0; margin: 0; }
            .no-print { display: none; }
            @page { margin: 0; }
        }
    </style>
</head>
<body onload="window.print()"> 

    <div class="struk-container">
        
        <div class="text-center">
            <h3 style="margin: 0;">LAPANGIN</h3>
            <span style="font-size: 11px;">Jl. Melur Ujung (Panam), Pekanbaru</span><br>
            <span style="font-size: 11px;">WA: 0812-3456-7890</span>
        </div>

        <div class="divider"></div>

        <div style="font-size: 11px;">
            <div class="item-row"><span>No. Nota</span> <span>#{{ $booking->id }}</span></div>
            <div class="item-row"><span>Tanggal</span> <span>{{ \Carbon\Carbon::parse($booking->tanggal)->format('d/m/y H:i') }}</span></div>
            <div class="item-row"><span>Pelanggan</span> <span>{{ substr($booking->nama_pelanggan, 0, 15) }}</span></div>
            <div class="item-row"><span>Kasir</span> <span>{{ Auth::user()->name ?? 'Staff' }}</span></div>
        </div>

        <div class="divider"></div>

        <div class="item-row">
            <span>Sewa ({{ $durasi }} Jam)</span>
            <span>{{ number_format($biayaSewa, 0, ',', '.') }}</span>
        </div>

        @if(isset($booking->jam_tambahan) && $booking->jam_tambahan > 0)
        <div class="item-row">
            <span>Extra Time ({{ $booking->jam_tambahan }} Jam)</span>
            <span>{{ number_format($biayaExtra, 0, ',', '.') }}</span>
        </div>
        @endif

        @if(isset($booking->sewa_sepatu) && $booking->sewa_sepatu > 0)
        <div class="item-row">
            <span>Sewa Sepatu ({{ $booking->qty_sepatu }} psg)</span>
            <span>{{ number_format($booking->sewa_sepatu, 0, ',', '.') }}</span>
        </div>
        @endif

        @if(isset($booking->minuman) && $booking->minuman > 0)
        <div class="item-row">
            <span>Minuman/Snack</span>
            <span>{{ number_format($booking->minuman, 0, ',', '.') }}</span>
        </div>
        @endif

        @if(isset($booking->denda) && $booking->denda > 0)
        <div class="item-row">
            <span>Denda/Lainnya</span>
            <span>{{ number_format($booking->denda, 0, ',', '.') }}</span>
        </div>
        @endif

        <div class="divider"></div>

        <div class="summary-row">
            <span>Subtotal</span>
            <span>{{ number_format($subTotal ?? ($biayaSewa + ($biayaExtra ?? 0) + ($booking->denda ?? 0)), 0, ',', '.') }}</span>
        </div>

        @if(isset($booking->diskon) && $booking->diskon > 0)
        <div class="summary-row">
            <span>Diskon</span>
            <span>-{{ number_format($booking->diskon, 0, ',', '.') }}</span>
        </div>
        @endif
        
        @if(isset($booking->dp) && $booking->dp > 0)
        <div class="summary-row">
            <span>Uang Muka (DP)</span>
            <span>-{{ number_format($booking->dp, 0, ',', '.') }}</span>
        </div>
        @endif

        <div class="divider"></div>

        <div class="summary-row grand-total">
            <span>TOTAL TAGIHAN</span>
            <span>{{ number_format($totalBayar, 0, ',', '.') }}</span>
        </div>

        <div class="divider"></div>

        <div class="summary-row">
            <span>Bayar ({{ $metodePembayaran ?? 'Tunai' }})</span>
            <span>{{ number_format($uangDibayar ?? $totalBayar, 0, ',', '.') }}</span>
        </div>
        
        <div class="summary-row">
            <span>Kembali</span>
            <span>{{ number_format(($uangDibayar ?? $totalBayar) - $totalBayar, 0, ',', '.') }}</span>
        </div>

        <div class="divider"></div>

        <div class="text-center" style="margin-top: 15px;">
            <p style="margin: 0;">Terima Kasih!</p>
            <p style="margin: 0;">Selamat Berolahraga</p>
            <p style="margin-top: 5px; font-size: 10px;"><i>Barang yang tertinggal bukan<br>tanggung jawab pengelola</i></p>
            <small style="font-size: 9px;">Dicetak: {{ date('d/m/Y H:i:s') }}</small>
        </div>

        <div class="text-center no-print" style="margin-top: 20px;">
            <a href="{{ route('staff.riwayat') }}" style="text-decoration: none; font-weight: bold; color: #333;">[ Kembali ke Menu ]</a>
        </div>

    </div>
</body>
</html>