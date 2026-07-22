<?php
// app/Http/Controllers/DataPengunjungController.php

namespace App\Http\Controllers;

use App\Models\DetailPengunjung;
use App\Services\TiketStatistikService;
use Illuminate\Http\Request;

class DataPengunjungController extends Controller
{
    protected $statistikService;

    public function __construct(TiketStatistikService $statistikService)
    {
        $this->statistikService = $statistikService;
    }

    public function index(Request $request)
    {
        // FIX: Tambahkan withTrashed() untuk mengatasi ticket yang sudah dihapus
        $query = DetailPengunjung::with([
            'pemesananTiket' => function($query) {
                $query->with(['ticket' => function($query) {
                    $query->withTrashed(); // Include soft-deleted tickets
                }]);
            },
            'pemesananTiket.user'
        ]);

        // Filter berdasarkan nama
        if ($request->filled('nama')) {
            $query->where('nama_lengkap', 'like', '%' . $request->nama . '%')
                  ->orWhere('nama_kelompok', 'like', '%' . $request->nama . '%');
        }

        // Filter berdasarkan email
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%')
                  ->orWhere('email_penanggung_jawab', 'like', '%' . $request->email . '%');
        }

        // Filter berdasarkan nomor telepon
        if ($request->filled('telepon')) {
            $query->where('nomor_ponsel', 'like', '%' . $request->telepon . '%')
                  ->orWhere('nomor_ponsel_penanggung_jawab', 'like', '%' . $request->telepon . '%');
        }

        // Filter berdasarkan jenis tiket
        if ($request->filled('jenis_tiket')) {
            $query->whereHas('pemesananTiket.ticket', function($q) use ($request) {
                $q->withTrashed()->where('jenis_tiket', $request->jenis_tiket);
            });
        }

        // Filter berdasarkan status pembayaran
        if ($request->filled('status')) {
            $query->whereHas('pemesananTiket', function($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        // Filter berdasarkan rentang tanggal
        if ($request->filled('tanggal_mulai')) {
            $query->whereHas('pemesananTiket', function($q) use ($request) {
                $q->whereDate('tanggal_pemesanan', '>=', $request->tanggal_mulai);
            });
        }
        if ($request->filled('tanggal_selesai')) {
            $query->whereHas('pemesananTiket', function($q) use ($request) {
                $q->whereDate('tanggal_pemesanan', '<=', $request->tanggal_selesai);
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $pengunjung = $query->paginate(20)->withQueryString();

        // Data untuk statistik
        $statistikBaruVsLama = $this->statistikService->getPengunjungBaruVsLama();
        $statistikByKota = $this->statistikService->getPengunjungByKota();
        $statistikByJenisTiket = $this->statistikService->getPengunjungByJenisTiket();
        $topPengunjung = $this->statistikService->getTopPengunjung(10);

        return view('tickets.data-pengunjung.index', compact(
            'pengunjung', 
            'statistikBaruVsLama', 
            'statistikByKota', 
            'statistikByJenisTiket', 
            'topPengunjung'
        ));
    }

    public function show($id)
    {
        // FIX: Tambahkan withTrashed() untuk detail view
        $detail = DetailPengunjung::with([
            'pemesananTiket' => function($query) {
                $query->with(['ticket' => function($query) {
                    $query->withTrashed();
                }]);
            },
            'pemesananTiket.user'
        ])->findOrFail($id);

        // Riwayat kunjungan berdasarkan email yang sama
        $riwayat = DetailPengunjung::where('email', $detail->email)
            ->where('id', '!=', $id)
            ->whereHas('pemesananTiket')
            ->with([
                'pemesananTiket' => function($query) {
                    $query->with(['ticket' => function($query) {
                        $query->withTrashed();
                    }]);
                },
                'pemesananTiket.ticket'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tickets.data-pengunjung.show', compact('detail', 'riwayat'));
    }
}