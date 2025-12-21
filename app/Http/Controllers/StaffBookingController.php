<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StaffBookingController extends Controller
{
    // ==========================================================
    // BAGIAN 1: JADWAL & OPERASIONAL (CHECK-IN / START / FINISH)
    // ==========================================================

    /**
     * Menampilkan halaman jadwal hari ini.
     */
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

        // Urutkan berdasarkan jam mulai (pagi ke malam)
        $bookings = $query->orderBy('jam_mulai', 'asc')->get();

        return view('staff.jadwal', compact('bookings'));
    }

    /**
     * Menangani aksi cepat: Check-in Kedatangan & Mulai Main (Start).
     */
    public function action(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        if ($request->action == 'checkin') {
            // Konfirmasi orangnya sudah datang
            $booking->konfirmasi_datang = true;
            $booking->save();
            return redirect()->back()->with('success', "Pelanggan {$booking->nama_pelanggan} berhasil Check-in.");
        
        } elseif ($request->action == 'start') {
            // Mulai waktu bermain (Status berubah jadi Aktif/Playing)
            $booking->status_booking = 'aktif';
            $booking->save();
            return redirect()->back()->with('success', "Sesi lapangan untuk {$booking->nama_pelanggan} DIMULAI.");
        }

        return redirect()->back();
    }

    /**
     * Menangani penyelesaian sesi (Finish), termasuk input denda/jam tambahan.
     */
   public function finish(Request $request, $id)
{
    $booking = Booking::findOrFail($id);

    $denda_manual = $request->denda ? str_replace('.', '', $request->denda) : 0;
    $jam_tambahan = (int) $request->jam_tambahan;
    $biaya_waktu  = $jam_tambahan * 50000;

    $total_bayar  = $denda_manual + $biaya_waktu;

    $booking->update([
        'status_booking' => 'selesai',
        'jam_tambahan'   => $jam_tambahan,
        'denda'          => $total_bayar,
    ]);

    return redirect()->back()->with('success', 'Sesi selesai. Total: Rp ' . number_format($total_bayar));
}

        // ==========================================================
        // BAGIAN 2: CRUD BOOKING BARU (CREATE, STORE, EDIT, UPDATE, DELETE)
        // ==========================================================

        /**
         * Menampilkan form tambah booking baru.
     */
    public function create()
    {
        return view('staff.create');
    }

    /**
     * Menyimpan data booking baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'no_hp'          => 'required|string|max:20',
            'tanggal'        => 'required|date',
            'jam_mulai'      => 'required',
            'jam_selesai'    => 'required|after:jam_mulai',
        ]);

        // Simpan ke database
        Booking::create([
            'nama_pelanggan'    => $request->nama_pelanggan,
            'no_hp'             => $request->no_hp,
            'tanggal'           => $request->tanggal,
            'jam_mulai'         => $request->jam_mulai,
            'jam_selesai'       => $request->jam_selesai,
            'status_booking'    => 'booking', // Default status awal
            'konfirmasi_datang' => true,      // Asumsi staff input = orangnya di tempat/fix
        ]);

        return redirect()->route('staff.jadwal')->with('success', 'Booking berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit data booking.
     */
    public function edit($id)
    {
        $booking = Booking::findOrFail($id);
        return view('staff.edit', compact('booking'));
    }

    /**
     * Mengupdate data booking yang sudah ada.
     */
    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'no_hp'          => 'required|string|max:20',
            'tanggal'        => 'required|date',
            'jam_mulai'      => 'required',
            'jam_selesai'    => 'required|after:jam_mulai',
        ]);

        $booking->update([
            'nama_pelanggan' => $request->nama_pelanggan,
            'no_hp'          => $request->no_hp,
            'tanggal'        => $request->tanggal,
            'jam_mulai'      => $request->jam_mulai,
            'jam_selesai'    => $request->jam_selesai,
        ]);

        return redirect()->route('staff.jadwal')->with('success', 'Data booking berhasil diperbarui.');
    }

    /**
     * Menghapus data booking.
     */
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return redirect()->route('staff.jadwal')->with('success', 'Booking berhasil dihapus.');
    }

    // ==========================================================
    // BAGIAN 3: PELAPORAN (RIWAYAT & CETAK STRUK)
    // ==========================================================

    /**
     * Menampilkan halaman riwayat (History) booking yang sudah selesai/batal.
     */
    public function riwayat(Request $request)
    {
        $query = Booking::query();

        // Kita hanya ambil status 'selesai' atau 'batal' untuk riwayat
        $query->whereIn('status_booking', ['selesai', 'batal']);

        // Logika Pencarian
        if ($request->has('q') && $request->q != '') {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('nama_pelanggan', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }

        // Urutkan dari tanggal terbaru (descending)
        $bookings = $query->orderBy('tanggal', 'desc')
                          ->orderBy('jam_mulai', 'desc')
                          ->get();

        return view('staff.riwayat', compact('bookings'));
    }

    /**
     * Menampilkan halaman cetak struk untuk booking tertentu.
     */
    public function cetakStruk($id)
    {
        $booking = Booking::findOrFail($id);
        
        // 1. Hitung Durasi Main (Selisih Jam)
        $start = Carbon::parse($booking->jam_mulai);
        $end   = Carbon::parse($booking->jam_selesai);
        
        // Menggunakan diffInMinutes dibagi 60 agar lebih akurat (bisa desimal misal 1.5 jam)
        // Atau gunakan diffInHours jika ingin pembulatan jam penuh.
        $durasi = $end->diffInHours($start); 
        if ($durasi == 0) $durasi = 1; // Minimal hitung 1 jam

        // 2. Tentukan Harga Per Jam (Hardcode sementara, atau ambil dari config/DB)
        $hargaPerJam = 100000; 
        
        // 3. Hitung Kalkulasi Biaya
        $biayaSewa    = $durasi * $hargaPerJam;
        $biayaExtra   = ($booking->jam_tambahan ?? 0) * $hargaPerJam;
        $biayaDenda   = $booking->denda ?? 0;
        
        $totalBayar   = $biayaSewa + $biayaExtra + $biayaDenda;

        return view('staff.struk', compact('booking', 'durasi', 'biayaSewa', 'biayaExtra', 'biayaDenda', 'totalBayar'));
    }
}