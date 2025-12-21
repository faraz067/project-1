@extends('layouts.staff.app')

@section('title', 'Jadwal Operasional')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<div class="container-fluid py-4" style="font-family: 'Plus Jakarta Sans', sans-serif;">

    {{-- Alert Notification --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center p-3 animate-fade-in">
            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                <i class="bi bi-check-lg"></i>
            </div>
            <div>
                <span class="fw-bold d-block">Berhasil!</span>
                <span class="small text-muted">{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4 mb-4 align-items-center">
        <div class="col-md-5">
            <h3 class="fw-bolder text-dark mb-1">Operasional Lapangan</h3>
            <p class="text-secondary mb-0">
                Jadwal hari ini: <span class="badge bg-dark rounded-pill px-3 ms-1">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
            </p>
        </div>
        
        <div class="col-md-7">
            <div class="row g-3 justify-content-end">
                <div class="col-4 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 py-2 card-hover">
                        <div class="card-body text-center p-2">
                            <i class="bi bi-hourglass-split text-warning fs-4 mb-1 d-block"></i>
                            <div class="h4 fw-bold mb-0">{{ $bookings->where('status_booking', 'booking')->count() }}</div>
                            <small class="text-muted fw-bold small text-uppercase" style="font-size: 0.65rem">Pending</small>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 py-2 card-hover">
                        <div class="card-body text-center p-2">
                            <div class="spinner-grow text-primary spinner-grow-sm mb-2" role="status"></div>
                            <div class="h4 fw-bold mb-0 text-primary">{{ $bookings->where('status_booking', 'aktif')->count() }}</div>
                            <small class="text-muted fw-bold small text-uppercase" style="font-size: 0.65rem">Sedang Main</small>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-lg-3">
                    <div class="card border-0 shadow-sm h-100 py-2 card-hover">
                        <div class="card-body text-center p-2">
                            <i class="bi bi-check-circle-fill text-success fs-4 mb-1 d-block"></i>
                            <div class="h4 fw-bold mb-0">{{ $bookings->where('status_booking', 'selesai')->count() }}</div>
                            <small class="text-muted fw-bold small text-uppercase" style="font-size: 0.65rem">Selesai</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        
        {{-- Toolbar --}}
        <div class="card-header bg-white p-4 border-bottom border-light">
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <form action="{{ route('staff.jadwal') }}" method="GET">
                        <div class="input-group input-group-lg shadow-sm rounded-pill overflow-hidden border">
                            <span class="input-group-text bg-white border-0 ps-4"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="q" class="form-control border-0 bg-white fs-6" placeholder="Cari Pelanggan..." value="{{ request('q') }}">
                            <button type="submit" class="btn btn-primary px-4 fw-bold">Cari</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="{{ route('staff.create') }}" class="btn btn-dark btn-lg rounded-pill shadow-sm px-4 fs-6 fw-bold">
                        <i class="bi bi-plus-lg me-2"></i>Booking Baru
                    </a>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 custom-table">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 text-uppercase text-secondary small fw-bold" style="width: 18%">Waktu</th>
                            <th class="text-uppercase text-secondary small fw-bold" style="width: 25%">Pelanggan</th>
                            <th class="text-uppercase text-secondary small fw-bold" style="width: 15%">Status</th>
                            <th class="text-uppercase text-secondary small fw-bold" style="width: 20%">Info</th>
                            <th class="text-end pe-4 text-uppercase text-secondary small fw-bold" style="width: 22%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                        <tr class="{{ $booking->status_booking == 'aktif' ? 'bg-primary-soft' : '' }}">
                            
                            {{-- WAKTU --}}
                            <td class="ps-4 py-4">
                                <div class="d-flex align-items-center">
                                    <div class="date-box rounded-3 text-center me-3 border shadow-sm p-1 bg-white" style="min-width: 50px;">
                                        <small class="d-block text-muted fw-bold small text-uppercase" style="font-size: 0.6rem">MULAI</small>
                                        <span class="fw-bolder fs-5 text-dark">{{ \Carbon\Carbon::parse($booking->jam_mulai)->format('H:i') }}</span>
                                    </div>
                                    <div class="text-muted small">
                                        <i class="bi bi-arrow-right text-primary mx-1"></i> 
                                        {{ \Carbon\Carbon::parse($booking->jam_selesai)->format('H:i') }}
                                    </div>
                                </div>
                            </td>

                            {{-- PELANGGAN --}}
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-gradient rounded-circle text-white fw-bold me-3 d-flex align-items-center justify-content-center shadow-sm" 
                                         style="width: 45px; height: 45px;">
                                        {{ substr($booking->nama_pelanggan, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark fs-6">{{ $booking->nama_pelanggan }}</div>
                                        <div class="small text-muted d-flex align-items-center">
                                            <i class="bi bi-whatsapp text-success me-1"></i> {{ $booking->no_hp }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- STATUS --}}
                            <td>
                                @if($booking->status_booking == 'booking')
                                    @if($booking->konfirmasi_datang)
                                        <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill border border-info">
                                            <i class="bi bi-person-check-fill me-1"></i> Check-in
                                        </span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill border border-warning">
                                            <i class="bi bi-clock me-1"></i> Menunggu
                                        </span>
                                    @endif
                                @elseif($booking->status_booking == 'aktif')
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill border border-primary animate-pulse">
                                        <i class="bi bi-play-circle-fill me-1"></i> Playing
                                    </span>
                                @elseif($booking->status_booking == 'selesai')
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill border border-success">
                                        <i class="bi bi-check-circle-fill me-1"></i> Selesai
                                    </span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">Batal</span>
                                @endif
                            </td>

                            {{-- INFO TAGIHAN --}}
                            <td>
                                @if($booking->status_booking == 'selesai')
                                    @if($booking->denda > 0 || $booking->jam_tambahan > 0)
                                        <div class="d-flex flex-column gap-1">
                                            @if($booking->jam_tambahan > 0)
                                                <span class="badge bg-primary text-white rounded-1 fw-normal" style="width: fit-content">
                                                    +{{ $booking->jam_tambahan }} Jam
                                                </span>
                                            @endif
                                            @if($booking->denda > 0)
                                                <span class="badge bg-danger text-white rounded-1 fw-normal" style="width: fit-content">
                                                    Denda: {{ number_format($booking->denda/1000) }}k
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted small fst-italic"><i class="bi bi-check-all me-1"></i>Normal</span>
                                    @endif
                                @else
                                    <span class="text-muted text-opacity-25">-</span>
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td class="text-end pe-4">
                                @if($booking->status_booking == 'booking')
                                    @if(!$booking->konfirmasi_datang)
                                        <form action="{{ route('staff.action', $booking->id) }}" method="POST" class="d-inline">
                                            @csrf <input type="hidden" name="action" value="checkin">
                                            <button class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-bold btn-hover-scale">
                                                Check-in
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('staff.action', $booking->id) }}" method="POST" class="d-inline">
                                            @csrf <input type="hidden" name="action" value="start">
                                            <button class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm fw-bold btn-hover-scale">
                                                <i class="bi bi-play-fill"></i> START
                                            </button>
                                        </form>
                                    @endif
                                    
                                    {{-- Menu Dropdown --}}
                                    <div class="btn-group ms-2">
                                        <button type="button" class="btn btn-light btn-sm rounded-circle shadow-sm" data-bs-toggle="dropdown" style="width: 32px; height: 32px;">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-3 mt-2">
                                            <li><a class="dropdown-item py-2" href="{{ route('staff.edit', $booking->id) }}"><i class="bi bi-pencil me-2 text-warning"></i> Edit</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('staff.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Hapus?');">
                                                    @csrf @method('DELETE')
                                                    <button class="dropdown-item text-danger py-2"><i class="bi bi-trash me-2"></i> Hapus</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>

                                @elseif($booking->status_booking == 'aktif')
                                    <button class="btn btn-danger btn-sm rounded-pill px-4 shadow-sm fw-bold btn-hover-scale" data-bs-toggle="modal" data-bs-target="#finishModal{{ $booking->id }}">
                                        <i class="bi bi-stop-fill me-1"></i> FINISH
                                    </button>
                                @elseif($booking->status_booking == 'selesai')
                                    <button class="btn btn-light text-muted btn-sm rounded-pill px-3" disabled>
                                        <i class="bi bi-lock-fill"></i> Closed
                                    </button>
                                @endif
                            </td>
                        </tr>

                        {{-- MODAL FINISH (REDESIGNED) --}}
                        <div class="modal fade" id="finishModal{{ $booking->id }}" tabindex="-1" data-bs-backdrop="static">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                                    <form action="{{ route('staff.finish', $booking->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-header bg-dark text-white p-4 border-0">
                                            <div>
                                                <h5 class="modal-title fw-bold">Selesaikan Sesi</h5>
                                                <p class="mb-0 text-white-50 small">Pelanggan: {{ $booking->nama_pelanggan }}</p>
                                            </div>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-4 bg-light">
                                            
                                            <div class="card border-0 shadow-sm mb-3">
                                                <div class="card-body p-3">
                                                    <label class="small fw-bold text-muted text-uppercase mb-2">Tambahan Waktu</label>
                                                    <div class="d-flex align-items-center justify-content-between bg-white p-2 rounded-3 border">
                                                        <button type="button" class="btn btn-light rounded-circle text-primary fw-bold" onclick="document.getElementById('jam_tambah_{{$booking->id}}').stepDown()" style="width: 40px; height: 40px;">-</button>
                                                        <div class="d-flex align-items-center">
                                                            <input type="number" id="jam_tambah_{{$booking->id}}" name="jam_tambahan" class="form-control border-0 text-center fw-bolder fs-4" value="0" min="0" style="width: 80px;">
                                                            <span class="text-muted fw-bold">Jam</span>
                                                        </div>
                                                        <button type="button" class="btn btn-light rounded-circle text-primary fw-bold" onclick="document.getElementById('jam_tambah_{{$booking->id}}').stepUp()" style="width: 40px; height: 40px;">+</button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card border-0 shadow-sm">
                                                <div class="card-body p-3">
                                                    <label class="small fw-bold text-muted text-uppercase mb-2">Biaya Kerusakan (Opsional)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text border-0 bg-danger text-white fw-bold">Rp</span>
                                                        <input type="number" name="denda" class="form-control border-0 bg-white shadow-none fs-5 fw-bold" placeholder="0">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer p-3 bg-white border-top-0 justify-content-between">
                                            <button type="button" class="btn btn-light rounded-pill px-4 fw-bold text-muted" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-dark rounded-pill px-5 fw-bold shadow-sm">
                                                Simpan & Cetak <i class="bi bi-printer-fill ms-2"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        {{-- END MODAL --}}

                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="py-5">
                                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                        <i class="bi bi-calendar-x text-muted fs-1"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark">Belum ada jadwal hari ini</h5>
                                    <p class="text-muted">Semua lapangan masih kosong.</p>
                                    <a href="{{ route('staff.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm mt-2">
                                        Buat Booking Sekarang
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* STYLING TAMBAHAN UNTUK KEINDAHAN */
    body { background-color: #f3f4f6; }
    
    .card-hover:hover {
        transform: translateY(-5px);
        transition: all 0.3s ease;
    }
    
    .custom-table tbody tr {
        transition: background-color 0.2s;
    }
    
    .bg-primary-soft {
        background-color: rgba(13, 110, 253, 0.05) !important;
    }

    .avatar-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    /* Animasi Status Playing */
    @keyframes pulse-primary {
        0% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.4); }
        70% { box-shadow: 0 0 0 6px rgba(13, 110, 253, 0); }
        100% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0); }
    }
    .animate-pulse {
        animation: pulse-primary 2s infinite;
    }

    .btn-hover-scale:hover {
        transform: scale(1.05);
        transition: transform 0.2s;
    }

    /* Input Styling Clean */
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; margin: 0; 
    }
    .form-control:focus {
        box-shadow: none;
        border-color: #0d6efd;
    }
</style>

@endsection