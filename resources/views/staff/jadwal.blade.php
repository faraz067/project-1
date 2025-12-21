@extends('layouts.staff.app')

@section('title', 'Jadwal Hari Ini')

@section('content')
<div class="container-fluid py-4">
    
    {{-- Alert Notification --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4 border-0 border-start border-success border-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        {{-- Card Header: Judul, Pencarian & Tombol Tambah --}}
        <div class="card-header bg-white p-4 border-bottom">
            <div class="row align-items-center">
                {{-- Kiri: Judul --}}
                <div class="col-md-5 mb-3 mb-md-0">
                    <h5 class="mb-1 text-dark fw-bold">📅 Operasional Lapangan</h5>
                    <p class="mb-0 text-muted small">
                        Hari ini: <span class="fw-semibold text-primary">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
                    </p>
                </div>

                {{-- Kanan: Search & Add Button --}}
                <div class="col-md-7">
                    <div class="d-flex justify-content-md-end gap-2">
                        <form action="{{ route('staff.jadwal') }}" method="GET" class="flex-grow-1 flex-md-grow-0" style="min-width: 250px;">
                            <div class="input-group">
                                <input type="text" name="q" class="form-control bg-light border-0" placeholder="Cari Nama / No HP..." value="{{ request('q') }}">
                                <button type="submit" class="btn btn-light border"><i class="bi bi-search"></i></button>
                                @if(request('q'))
                                    <a href="{{ route('staff.jadwal') }}" class="btn btn-secondary" title="Reset"><i class="bi bi-x-lg"></i></a>
                                @endif
                            </div>
                        </form>

                        <a href="{{ route('staff.create') }}" class="btn btn-primary fw-bold text-nowrap">
                            <i class="bi bi-plus-lg me-1"></i> Booking Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Body: Tabel --}}
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="min-width: 800px;">
                    <thead class="bg-light text-uppercase small text-muted">
                        <tr>
                            <th class="ps-4 py-3" width="20%">Jam Main</th>
                            <th width="25%">Pelanggan</th>
                            <th width="20%">Status</th>
                            <th width="15%">Info Extra</th>
                            <th class="text-end pe-4" width="20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                        <tr class="{{ $booking->status_booking == 'aktif' ? 'bg-blue-soft' : '' }}">
                            
                            {{-- Kolom Jam --}}
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded p-2 text-center border me-2" style="min-width: 50px;">
                                        <div class="fw-bold text-dark">{{ \Carbon\Carbon::parse($booking->jam_mulai)->format('H:i') }}</div>
                                    </div>
                                    <div class="text-muted small"><i class="bi bi-arrow-right"></i></div>
                                    <div class="bg-light rounded p-2 text-center border ms-2" style="min-width: 50px;">
                                        <div class="fw-bold text-dark">{{ \Carbon\Carbon::parse($booking->jam_selesai)->format('H:i') }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Kolom Pelanggan --}}
                            <td>
                                <div class="fw-bold text-dark">{{ $booking->nama_pelanggan }}</div>
                                <div class="small text-muted">
                                    <i class="bi bi-whatsapp text-success me-1"></i> {{ $booking->no_hp }}
                                </div>
                            </td>

                            {{-- Kolom Status --}}
                            <td>
                                @if($booking->status_booking == 'booking')
                                    @if($booking->konfirmasi_datang)
                                        <span class="badge rounded-pill bg-info bg-opacity-10 text-info border border-info">
                                            <i class="bi bi-person-check me-1"></i> Sudah Check-in
                                        </span>
                                    @else
                                        <span class="badge rounded-pill bg-warning bg-opacity-10 text-warning border border-warning">
                                            <i class="bi bi-hourglass-split me-1"></i> Menunggu
                                        </span>
                                    @endif
                                @elseif($booking->status_booking == 'aktif')
                                    <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary border border-primary animate-pulse">
                                        <span class="spinner-grow spinner-grow-sm me-1" style="width: 0.5rem; height: 0.5rem;"></span> Playing
                                    </span>
                                @elseif($booking->status_booking == 'selesai')
                                    <span class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success">
                                        <i class="bi bi-check-all me-1"></i> Selesai
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger border border-danger">Batal</span>
                                @endif
                            </td>

                            {{-- Kolom Info Tambahan (Denda/Add) --}}
                            <td>
                                @if($booking->status_booking == 'selesai')
                                    @if($booking->jam_tambahan || $booking->denda)
                                        <div class="d-flex flex-col gap-1">
                                            @if($booking->denda)
                                            <span class="badge bg-danger text-white rounded-1 fw-normal">
                                                Denda: Rp{{ number_format($booking->denda/1000) }}k
                                            </span>
                                            @endif
                                            @if($booking->jam_tambahan)
                                            <span class="badge bg-primary text-white rounded-1 fw-normal">
                                                +{{ $booking->jam_tambahan }} Jam
                                            </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>

                            {{-- Kolom Aksi --}}
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2 align-items-center">
                                    
                                    {{-- TOMBOL OPERASIONAL (Checkin/Start/Finish) --}}
                                    @if($booking->status_booking == 'booking')
                                        @if(!$booking->konfirmasi_datang)
                                            <form action="{{ route('staff.action', $booking->id) }}" method="POST">
                                                @csrf <input type="hidden" name="action" value="checkin">
                                                <button class="btn btn-sm btn-outline-success rounded-pill px-3" title="Check-in">
                                                    <i class="bi bi-person-check-fill"></i> Check-in
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('staff.action', $booking->id) }}" method="POST">
                                                @csrf <input type="hidden" name="action" value="start">
                                                <button class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm">
                                                    <i class="bi bi-play-fill me-1"></i> Start
                                                </button>
                                            </form>
                                        @endif
                                    @elseif($booking->status_booking == 'aktif')
                                        <button class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#finishModal{{ $booking->id }}">
                                            <i class="bi bi-stop-circle-fill me-1"></i> Finish
                                        </button>
                                    @elseif($booking->status_booking == 'selesai')
                                        <button class="btn btn-sm btn-light text-muted border rounded-pill px-3" disabled>
                                            <i class="bi bi-lock-fill me-1"></i> Closed
                                        </button>
                                    @endif

                                    {{-- TOMBOL CRUD (Edit/Delete) - Hanya muncul jika masih booking --}}
                                    @if($booking->status_booking == 'booking')
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light border" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('staff.edit', $booking->id) }}">
                                                        <i class="bi bi-pencil me-2 text-warning"></i> Edit Data
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('staff.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus booking atas nama {{ $booking->nama_pelanggan }}?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="bi bi-trash me-2"></i> Hapus
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif

                                </div>
                            </td>
                        </tr>

                        {{-- MODAL FINISH (Tidak Berubah) --}}
                        <div class="modal fade" id="finishModal{{ $booking->id }}" tabindex="-1" data-bs-backdrop="static">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow">
                                    <form action="{{ route('staff.finish', $booking->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header bg-danger text-white">
                                            <h6 class="modal-title fw-bold"><i class="bi bi-stop-circle me-2"></i>Selesaikan Sesi</h6>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body text-start p-4">
                                            <div class="alert alert-warning d-flex align-items-center border-0 shadow-sm mb-4">
                                                <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                                                <div class="small lh-sm">
                                                    <div><strong>Konfirmasi Pembayaran</strong></div>
                                                    <span>Pastikan pelanggan sudah melunasi tagihan sebelum klik selesai.</span>
                                                </div>
                                            </div>

                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <label class="form-label fw-bold small text-muted">TAMBAHAN WAKTU (JAM)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light"><i class="bi bi-clock"></i></span>
                                                        <input type="number" name="jam_tambahan" class="form-control" placeholder="0" min="0">
                                                        <span class="input-group-text bg-white text-muted">Jam</span>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <label class="form-label fw-bold small text-muted">DENDA / KERUSAKAN (RP)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light fw-bold">Rp</span>
                                                        <input type="number" name="denda" class="form-control" placeholder="0" min="0">
                                                    </div>
                                                    <div class="form-text small text-muted">Kosongkan jika tidak ada denda.</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light">
                                            <button type="button" class="btn btn-link text-muted text-decoration-none" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger px-4">
                                                Simpan & Selesai <i class="bi bi-check-lg ms-1"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="py-4">
                                    <div class="mb-3 text-muted opacity-25">
                                        <i class="bi bi-calendar-x display-1"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark">Tidak ada jadwal ditemukan</h6>
                                    <p class="text-muted small">Silakan tambah booking baru jika ada pelanggan walk-in.</p>
                                    <a href="{{ route('staff.create') }}" class="btn btn-primary mt-2">
                                        <i class="bi bi-plus-lg"></i> Tambah Booking Baru
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
    /* Custom simple CSS for specific tweaks */
    .bg-blue-soft { background-color: #f0f7ff; }
    .table-hover tbody tr:hover { background-color: #f8f9fa; }
    .animate-pulse { animation: pulse 2s infinite; }
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.7; }
        100% { opacity: 1; }
    }
</style>
@endsection