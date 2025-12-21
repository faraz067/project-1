@extends('layouts.staff.app')

@section('title', 'Riwayat Booking')

@section('content')
<div class="container-fluid py-4">
    
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white p-4 border-bottom">
            <div class="row align-items-center">
                <div class="col-md-6 mb-3 mb-md-0">
                    <h5 class="mb-1 text-dark fw-bold">📂 Riwayat Booking</h5>
                    <p class="mb-0 text-muted small">Data penyewaan yang sudah selesai atau dibatalkan.</p>
                </div>
                <div class="col-md-6">
                    <form action="{{ route('staff.riwayat') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="q" class="form-control bg-light border-0" placeholder="Cari Riwayat..." value="{{ request('q') }}">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-uppercase small text-muted">
                        <tr>
                            <th class="ps-4 py-3">Tanggal</th>
                            <th>Jam Main</th>
                            <th>Pelanggan</th>
                            <th>Status</th>
                            <th>Total & Denda</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold">{{ \Carbon\Carbon::parse($booking->tanggal)->translatedFormat('d M Y') }}</div>
                                <div class="small text-muted">{{ \Carbon\Carbon::parse($booking->tanggal)->translatedFormat('l') }}</div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ \Carbon\Carbon::parse($booking->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->jam_selesai)->format('H:i') }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $booking->nama_pelanggan }}</div>
                                <div class="small text-muted">{{ $booking->no_hp }}</div>
                            </td>
                            <td>
                                @if($booking->status_booking == 'selesai')
                                    <span class="badge bg-success bg-opacity-10 text-success">Selesai</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger">Batal</span>
                                @endif
                            </td>
                            <td>
                                @if($booking->denda > 0)
                                    <div class="text-danger small fw-bold">Denda: Rp {{ number_format($booking->denda) }}</div>
                                @endif
                                @if($booking->jam_tambahan > 0)
                                    <div class="text-primary small">Extra: {{ $booking->jam_tambahan }} Jam</div>
                                @endif
                                @if($booking->denda == 0 && $booking->jam_tambahan == 0)
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('staff.struk', $booking->id) }}" class="btn btn-sm btn-outline-secondary" target="_blank">
                                    <i class="bi bi-printer"></i> Struk
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                Belum ada riwayat booking.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection