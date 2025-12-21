<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }} - LAPANGIN</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --bg-color: #f3f4f6;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-color);
            color: #1f2937;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Container Invoice (Kertas) */
        .invoice-box {
            max-width: 800px;
            margin: 30px auto;
            padding: 40px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            position: relative;
            overflow: hidden;
        }

        /* Header Styles */
        .brand-name {
            font-size: 24px;
            font-weight: 800;
            color: var(--primary-color);
            letter-spacing: -0.5px;
            text-transform: uppercase; /* Agar tulisan LAPANGIN kapital semua */
        }

        .invoice-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--secondary-color);
            font-weight: 700;
            margin-bottom: 4px;
        }

        /* Table Styles */
        .custom-table thead th {
            background-color: #f9fafb !important;
            color: #4b5563;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 16px;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .custom-table tbody td {
            padding: 16px;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
        }

        /* Total Section */
        .total-container {
            background-color: #f8f9fa;
            border-radius: 12px;
            padding: 24px;
        }

        /* Stamp Lunas Effect */
        .stamp-paid {
            position: absolute;
            top: 40px;
            right: 40px;
            padding: 10px 20px;
            border: 3px solid #198754;
            color: #198754;
            font-size: 2rem;
            font-weight: 900;
            text-transform: uppercase;
            border-radius: 8px;
            opacity: 0.2;
            transform: rotate(-15deg);
            pointer-events: none;
            z-index: 0;
        }

        /* Print Settings */
        @media print {
            body {
                background: none;
                margin: 0;
                padding: 0;
            }
            .invoice-box {
                box-shadow: none;
                border: none;
                margin: 0;
                padding: 20px 0;
                width: 100%;
                max-width: 100%;
                border-radius: 0;
            }
            .no-print {
                display: none !important;
            }
            .total-container {
                background-color: #f8f9fa !important; /* Force bg color */
                -webkit-print-color-adjust: exact;
            }
            /* Pastikan teks href link tidak muncul */
            a[href]:after { content: none !important; }
        }
    </style>
</head>
<body>

    <div class="container d-flex justify-content-center mt-4 no-print">
        <div class="bg-white p-2 rounded-pill shadow-sm border d-flex gap-2">
            <a href="{{ route('staff.riwayat') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
            <button onclick="window.print()" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                <i class="bi bi-printer-fill me-1"></i> Cetak Sekarang
            </button>
        </div>
    </div>

    <div class="invoice-box">
        
        <div class="stamp-paid">LUNAS</div>

        <div class="row align-items-center mb-5 position-relative" style="z-index: 1;">
            <div class="col-7">
                <div class="brand-name mb-2">
                    <i class="bi bi-hexagon-fill me-2"></i>LAPANGIN
                </div>
                <div class="text-secondary small lh-sm">
                    Jalan Melur (Panam), Pekanbaru<br>
                    WhatsApp: 0812-3456-7890<br>
                    Email: admin@lapangin.com
                </div>
            </div>
            <div class="col-5 text-end">
                <div class="invoice-label">Invoice ID</div>
                <div class="fs-4 fw-bold text-dark mb-2">#INV-{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</div>
                <div class="text-secondary small">
                    Tanggal Cetak: <span class="fw-bold text-dark">{{ now()->translatedFormat('d F Y') }}</span>
                </div>
            </div>
        </div>

        <hr class="border-secondary opacity-10 my-4">

        <div class="row mb-5">
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="invoice-label mb-2">DITAGIHKAN KEPADA</div>
                <h5 class="fw-bold text-dark mb-1">{{ $booking->nama_pelanggan }}</h5>
                <div class="d-flex align-items-center text-muted small">
                    <i class="bi bi-telephone-fill me-2 opacity-50"></i> {{ $booking->no_hp }}
                </div>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="invoice-label mb-2">DETAIL JADWAL</div>
                <div class="fs-6 fw-bold text-dark">
                    {{ \Carbon\Carbon::parse($booking->tanggal)->translatedFormat('l, d F Y') }}
                </div>
                <div class="badge bg-light text-dark border mt-1">
                    <i class="bi bi-clock me-1"></i> 
                    {{ \Carbon\Carbon::parse($booking->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->jam_selesai)->format('H:i') }} WIB
                </div>
            </div>
        </div>

        <div class="table-responsive mb-4">
            <table class="table custom-table mb-0">
                <thead>
                    <tr>
                        <th style="width: 50%;">Deskripsi Item</th>
                        <th class="text-center">Durasi / Qty</th>
                        <th class="text-end">Harga Satuan</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="fw-bold text-dark">Sewa Lapangan Futsal</div>
                            <div class="small text-muted">Biaya sewa reguler (Jam Normal)</div>
                        </td>
                        <td class="text-center fw-bold">{{ $durasi }} Jam</td>
                        <td class="text-end text-muted">Rp {{ number_format($hargaPerJam, 0, ',', '.') }}</td>
                        <td class="text-end fw-bold">Rp {{ number_format($biayaSewa, 0, ',', '.') }}</td>
                    </tr>

                    @if($booking->jam_tambahan > 0)
                    <tr>
                        <td>
                            <div class="fw-bold text-primary">Tambahan Waktu (Overtime)</div>
                            <div class="small text-muted">Biaya perpanjangan durasi main</div>
                        </td>
                        <td class="text-center text-primary fw-bold">{{ $booking->jam_tambahan }} Jam</td>
                        <td class="text-end text-muted">Rp 50.000</td>
                        <td class="text-end fw-bold text-primary">Rp {{ number_format($biayaExtra, 0, ',', '.') }}</td>
                    </tr>
                    @endif

                    @if($booking->denda > 0)
                    <tr>
                        <td>
                            <div class="fw-bold text-danger">Biaya Denda / Kerusakan</div>
                            <div class="small text-muted">Cas tambahan akibat pelanggaran</div>
                        </td>
                        <td class="text-center text-danger fw-bold">1 Unit</td>
                        <td class="text-end text-muted">-</td>
                        <td class="text-end fw-bold text-danger">Rp {{ number_format($biayaDenda, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="row justify-content-end">
            <div class="col-md-6">
                <div class="total-container">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-secondary fw-medium">Subtotal</span>
                        <span class="fw-bold">Rp {{ number_format($biayaSewa, 0, ',', '.') }}</span>
                    </div>

                    @if($biayaExtra + $biayaDenda > 0)
                    <div class="d-flex justify-content-between mb-3 text-secondary small">
                        <span>Biaya Lainnya (Extra/Denda)</span>
                        <span>+ Rp {{ number_format($biayaExtra + $biayaDenda, 0, ',', '.') }}</span>
                    </div>
                    @endif

                    <div class="border-top border-secondary opacity-25 my-3"></div>

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-dark fw-bolder fs-5">TOTAL BAYAR</span>
                        <span class="text-primary fw-bolder fs-3">Rp {{ number_format($totalBayar, 0, ',', '.') }}</span>
                    </div>
                    <div class="text-end mt-2">
                        <span class="badge bg-success bg-opacity-10 text-success border border-success px-3">
                            <i class="bi bi-check-circle-fill me-1"></i> STATUS LUNAS
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-5 text-center">
            <p class="text-muted small mb-1">Terima kasih telah bermain di <strong>LAPANGIN</strong>.</p>
            <p class="text-muted small fst-italic opacity-75">Struk ini adalah bukti pembayaran yang sah.</p>
        </div>
    </div>

</body>
</html>