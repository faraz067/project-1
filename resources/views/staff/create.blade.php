@extends('layouts.staff.app')

@section('title', 'Tambah Booking Baru')

@section('content')
<div class="container py-4">
    <div class="card border-0 shadow-sm rounded-4" style="max-width: 600px; margin: 0 auto;">
        <div class="card-header bg-primary text-white p-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-plus-circle me-2"></i>Booking Baru (Walk-in)</h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('staff.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama Pelanggan</label>
                    <input type="text" name="nama_pelanggan" class="form-control" required placeholder="Contoh: Budi Santoso">
                </div>
                <div class="mb-3">
                    <label class="form-label">Nomor HP / WhatsApp</label>
                    <input type="text" name="no_hp" class="form-control" required placeholder="0812...">
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal Main</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jam Mulai</label>
                        <input type="time" name="jam_mulai" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jam Selesai</label>
                        <input type="time" name="jam_selesai" class="form-control" required>
                    </div>
                </div>
                
                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-primary fw-bold">Simpan Booking</button>
                    <a href="{{ route('staff.jadwal') }}" class="btn btn-light text-muted">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection