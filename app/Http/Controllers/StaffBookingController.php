<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StaffBookingController extends Controller
{
    // ==========================================================
    // BAGIAN 1: JADWAL & OPERASIONAL (CHECK-IN / START / FINISH)
    // ==========================================================

    public function index(Request $request)
    {
        $query = Booking::query();

        // Filter 1: Hanya tampilkan jadwal HARI INI
        $query->whereDate('tanggal', Carbon::today());

        // Filter 2: Logika Pencarian (Nama / No HP)
        if ($request->has('q') && $request->q != '') {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('nama_pelanggan', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }

        // Urutkan berdasarkan jam mulai
        $bookings = $query->orderBy('jam_mulai', 'asc')->get();

        return view('staff.jadwal', compact('bookings'));
    }

    public function action(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        if ($request->action == 'checkin') {
            $booking->konfirmasi_datang = true;
            $booking->save();
            return redirect()->back()->with('success', "Pelanggan {$booking->nama_pelanggan} Check-in.");
        
        } elseif ($request->action == 'start') {
            $booking->status_booking = 'aktif';
            $booking->save();
            return redirect()->back()->with('success', "Sesi dimulai.");
        }

        return redirect()->back();
    }

   public function finish(Request $request, $id)
{
    $booking = Booking::findOrFail($id);

    // 1. Bersihkan format input uang (misal "50.000" jadi "50000")
    // Jika tidak ada denda, set ke 0
    $denda_kerusakan = $request->denda ? (int) str_replace('.', '', $request->denda) : 0;

    // 2. Ambil jam tambahan
    $jam_tambahan = (int) $request->jam_tambahan;

    // 3. Update Data Booking
    // PENTING: Jangan gabung biaya waktu ke kolom 'denda'. 
    // Biarkan 'denda' khusus untuk kerusakan, agar di struk rinciannya rapi.
    
    $booking->update([
        'status_booking'    => 'selesai',
        'status_pembayaran' => 'lunas', // Langsung set lunas
        'jam_tambahan'      => $jam_tambahan,
        'denda'             => $denda_kerusakan, // Hanya nominal denda/kerusakan       
        'metode_pembayaran' => $request->metode_pembayaran // 'cash' atau 'qris'
    ]);

    // Opsi: Jika di database ada kolom 'total_harga', Anda bisa update di sini:
    // $harga_normal   = $booking->durasi * 50000;
    // $harga_tambahan = $jam_tambahan * 55000;
    // $booking->total_harga = $harga_normal + $harga_tambahan + $denda_kerusakan;
    // $booking->save();

    return redirect()->back()->with('success', 'Sesi selesai. Data pembayaran berhasil disimpan.');
}
    // ==========================================================
    // BAGIAN 2: CRUD BOOKING
    // ==========================================================

    public function create()
    {
        return view('staff.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'no_hp'          => 'required|string|max:20',
            'tanggal'        => 'required|date',
            'jam_mulai'      => 'required',
            'jam_selesai'    => 'required|after:jam_mulai',
        ]);

        Booking::create([
            'nama_pelanggan'    => $request->nama_pelanggan,
            'no_hp'             => $request->no_hp,
            'tanggal'           => $request->tanggal,
            'jam_mulai'         => $request->jam_mulai,
            'jam_selesai'       => $request->jam_selesai,
            'status_booking'    => 'booking', 
            'konfirmasi_datang' => true,      
            'dp'                => 0,
            'diskon'            => 0,
            'status_pembayaran' => 'Belum Bayar',
            'denda'             => 0,
            'jam_tambahan'      => 0,
        ]);

        return redirect()->route('staff.jadwal')->with('success', 'Booking berhasil!');
    }

    public function edit($id)
    {
        $booking = Booking::findOrFail($id);
        return view('staff.edit', compact('booking'));
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        $booking->update([
            'nama_pelanggan' => $request->nama_pelanggan,
            'no_hp'          => $request->no_hp,
            'tanggal'        => $request->tanggal,
            'jam_mulai'      => $request->jam_mulai,
            'jam_selesai'    => $request->jam_selesai,
        ]);

        return redirect()->route('staff.jadwal')->with('success', 'Update berhasil.');
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();
        return redirect()->route('staff.jadwal')->with('success', 'Dihapus.');
    }

    // ==========================================================
    // BAGIAN 3: PELAPORAN & STRUK (SUDAH DIPERBAIKI)
    // ==========================================================

    public function riwayat(Request $request)
    {
        $query = Booking::query();
        $query->whereIn('status_booking', ['selesai', 'batal']);

        if ($request->has('q') && $request->q != '') {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('nama_pelanggan', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }

        $bookings = $query->orderBy('tanggal', 'desc')->get();
        return view('staff.riwayat', compact('bookings'));
    }

    /**
     * PERBAIKAN PENTING:
     * Nama variabel disesuaikan menjadi '$totalBayar' agar View tidak Error.
     */
    public function cetakStruk($id)
    {
        $booking = Booking::findOrFail($id);
        
        // 1. Hitung Durasi
        $start = Carbon::parse($booking->jam_mulai);
        $end   = Carbon::parse($booking->jam_selesai);
        $durasi = $end->diffInHours($start); 
        if ($durasi == 0) $durasi = 1;

        // 2. Hitung Biaya
        $hargaPerJam  = 100000; 
        $biayaSewa    = $durasi * $hargaPerJam;
        $biayaExtra   = ($booking->jam_tambahan ?? 0) * 50000; 
        $biayaDenda   = $booking->denda ?? 0;
        
        // 3. TOTAL AKHIR (Nama variabel ini Wajib 'totalBayar')
        $totalBayar   = $biayaSewa + $biayaExtra + $biayaDenda;
        
        // Variabel tambahan agar view aman (mencegah error 'undefined variable')
        $uangDibayar = $totalBayar; 
        $kembalian   = 0;
        $dp          = 0;

        return view('staff.struk', compact(
            'booking', 'durasi', 'hargaPerJam', 
            'biayaSewa', 'biayaExtra', 'biayaDenda', 
            'totalBayar', 'uangDibayar', 'kembalian', 'dp'
        ));
    }
}