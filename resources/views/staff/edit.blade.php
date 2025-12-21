@extends('layouts.staff.app')

@section('title', 'Edit Booking')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">✏️ Edit Data Booking</h5>
                </div>
                <div class="card-body p-4">
                    
                    {{-- Form Update --}}
                    <form action="{{ route('staff.update', $booking->id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- PENTING: Untuk update data harus pakai PUT --}}

                        {{-- Nama Pelanggan --}}
                        <div class="mb-3">
                            <label class="form-label">Nama Pelanggan</label>
                            <input type="text" name="nama_pelanggan" 
                                   class="form-control @error('nama_pelanggan') is-invalid @enderror" 
                                   {{-- PERHATIKAN BAGIAN VALUE INI --}}
                                   value="{{ old('nama_pelanggan', $booking->nama_pelanggan) }}" 
                                   required>
                            @error('nama_pelanggan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- No HP --}}
                        <div class="mb-3">
                            <label class="form-label">No. HP / WhatsApp</label>
                            <input type="text" name="no_hp" 
                                   class="form-control @error('no_hp') is-invalid @enderror"
                                   value="{{ old('no_hp', $booking->no_hp) }}" 
                                   required>
                        </div>

                        <div class="row">
                            {{-- Tanggal --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Tanggal Main</label>
                                <input type="date" name="tanggal" 
                                       class="form-control @error('tanggal') is-invalid @enderror"
                                       value="{{ old('tanggal', $booking->tanggal) }}" 
                                       required>
                            </div>

                            {{-- Jam Mulai --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Jam Mulai</label>
                                <input type="time" name="jam_mulai" 
                                       class="form-control @error('jam_mulai') is-invalid @enderror"
                                       value="{{ old('jam_mulai', $booking->jam_mulai) }}" 
                                       required>
                            </div>

                            {{-- Jam Selesai --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Jam Selesai</label>
                                <input type="time" name="jam_selesai" 
                                       class="form-control @error('jam_selesai') is-invalid @enderror"
                                       value="{{ old('jam_selesai', $booking->jam_selesai) }}" 
                                       required>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('staff.jadwal') }}" class="btn btn-light">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection