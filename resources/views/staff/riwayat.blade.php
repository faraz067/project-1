@extends('layouts.staff.app')

@section('title', 'Riwayat Booking')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<div class="container-fluid py-4" style="font-family: 'Plus Jakarta Sans', sans-serif;">
    
    <div class="row g-4 align-items-center mb-4">
        <div class="col-md-6">
            <h3 class="fw-bolder text-dark mb-1">
                <i class="bi bi-clock-history text-primary me-2"></i>Riwayat Booking
            </h3>
            <p class="text-secondary mb-0">Arsip data penyewaan yang telah selesai atau dibatalkan.</p>
        </div>
        <div class="col-md-6">
            <form action="{{ route('staff.riwayat') }}" method="GET">
                <div class="input-group input-group-lg shadow-sm rounded-pill overflow-hidden border bg-white">
                    <span class="input-group-text bg-white border-0 ps-4 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="q" class="form-control border-0 bg-white fs-6 ps-2" 
                           placeholder="Cari nama pelanggan / no. hp..." value="{{ request('q') }}">
                    <button type="submit" class="btn btn-primary px-4 fw-bold rounded-pill m-1 shadow-sm">Cari Data</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 custom-table">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-4 text-uppercase text-secondary small fw-bold" style="width: 20%;">Tanggal & Waktu</th>
                            <th class="text-uppercase text-secondary small fw-bold" style="width: 30%;">Pelanggan</th>
                            <th class="text-uppercase text-secondary small fw-bold" style="width: 15%;">Status</th>
                            <th class="text-uppercase text-secondary small fw-bold" style="width: 35%;">Catatan Biaya</th>
                            {{-- KOLOM AKSI DIHAPUS --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                        <tr>
                            {{-- TANGGAL --}}
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="date-box bg-white shadow-sm border rounded-3 text-center p-2 me-3" style="min-width: 60px;">
                                        <small class="d-block text-uppercase fw-bold text-muted" style="font-size: 0.65rem">
                                            {{ \Carbon\Carbon::parse($booking->tanggal)->format('M') }}
                                        </small>
                                        <span class="d-block fs-3 fw-bolder text-dark lh-1">
                                            {{ \Carbon\Carbon::parse($booking->tanggal)->format('d') }}
                                        </span>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark text-uppercase small mb-1">
                                            {{ \Carbon\Carbon::parse($booking->tanggal)->translatedFormat('l, Y') }}
                                        </div>
                                        <div class="badge bg-light text-dark border fw-normal">
                                            <i class="bi bi-clock me-1 text-muted"></i>
                                            {{ \Carbon\Carbon::parse($booking->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->jam_selesai)->format('H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- PELANGGAN --}}
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-gradient rounded-circle text-white fw-bold me-3 d-flex align-items-center justify-content-center shadow-sm" 
                                         style="width: 40px; height: 40px;">
                                    {{ substr($booking->nama_pelanggan, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $booking->nama_pelanggan }}</div>
                                        <div class="small text-muted">
                                            <i class="bi bi-whatsapp me-1 text-success"></i> {{ $booking->no_hp }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- STATUS --}}
                            <td>
                                @if($booking->status_booking == 'selesai')
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill border border-success">
                                        <i class="bi bi-check-circle-fill me-1"></i> Selesai
                                    </span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill border border-danger">
                                        <i class="bi bi-x-circle-fill me-1"></i> Batal
                                    </span>
                                @endif
                            </td>

                            {{-- INFO BIAYA --}}
                            <td>
                                @if($booking->status_booking == 'selesai')
                                    @if($booking->denda > 0 || $booking->jam_tambahan > 0)
                                        <div class="d-flex flex-column gap-1">
                                            @if($booking->denda > 0)
                                                <div class="d-flex align-items-center text-danger fw-bold small">
                                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                                    Denda Rp {{ number_format($booking->denda) }}
                                                </div>
                                            @endif
                                            @if($booking->jam_tambahan > 0)
                                                <div class="d-flex align-items-center text-primary fw-bold small">
                                                    <i class="bi bi-plus-circle-fill me-2"></i>
                                                    Extra {{ $booking->jam_tambahan }} Jam
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted small fst-italic opacity-75">
                                            <i class="bi bi-check2-all me-1"></i> Tidak ada denda
                                        </span>
                                    @endif
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            
                            {{-- KOLOM AKSI DIHAPUS --}}
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5"> {{-- COLSPAN DIUBAH JADI 4 --}}
                                <div class="py-5 opacity-50">
                                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                        <i class="bi bi-folder-x text-muted fs-1"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark">Data tidak ditemukan</h5>
                                    <p class="text-muted small">Belum ada riwayat booking yang sesuai pencarian Anda.</p>
                                    <a href="{{ route('staff.riwayat') }}" class="btn btn-sm btn-outline-primary rounded-pill px-4 mt-2">
                                        Reset Pencarian
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- PAGINATION --}}
            @if(isset($bookings) && method_exists($bookings, 'links'))
            <div class="px-4 py-3 border-top bg-light d-flex justify-content-end">
                {{ $bookings->withQueryString()->links('pagination::bootstrap-5') }}
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    /* Styling Senada dengan Halaman Jadwal */
    body { background-color: #f3f4f6; }

    .custom-table tbody tr {
        transition: background-color 0.2s;
    }

    .avatar-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        font-size: 1.1rem;
    }

    .date-box {
        background-color: #fff;
        border-color: #e9ecef !important;
    }

    /* Form Control Focus */
    .form-control:focus {
        box-shadow: none;
        border-color: #0d6efd;
    }
</style>
@endsection