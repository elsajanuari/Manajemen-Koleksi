<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\ShippingZone;
use App\Services\InvoiceService;
use App\Services\ShippingService;
use Illuminate\Http\Request;

class PengelolaPembelianController extends Controller
{
    // ── Daftar semua pengajuan pembelian ─────────────────────────
    public function index(Request $request)
    {
        $status  = $request->query('status', 'semua');
        $search  = $request->query('search');
        $perPage = (int) $request->query('per_page', 20);

        $query = Pembelian::with(['user', 'painting', 'shippingZone'])->latest();

        // Status aktif saja (kecuali filter spesifik dipilih)
        $statusAktif = ['menunggu_verifikasi', 'menunggu_pembayaran', 'pembayaran_berhasil',
        'siap_diserahkan', 'dalam_pengiriman',
        'pengecekan_kondisi',                    // ← tambah ini
        'menunggu_review_kerusakan',
        'menunggu_data_rekening',                // ← tambah ini
        'menunggu_penerimaan_koleksi',           // ← tambah ini
        'menunggu_refund_kerusakan',             // ← tambah ini
        'menunggu_konfirmasi_refund',            // ← tambah ini
        'menunggu_dokumen_serah_terima',
        'menunggu_validasi_serah_terima',
        'diterima_pembeli',];

        if ($status !== 'semua') {
            $query->where('status', $status);
        } else {
            // Default: hanya tampilkan yang masih berjalan
            $query->whereIn('status', $statusAktif);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('company_name', 'like', "%{$search}%")
                ->orWhere('pic_name', 'like', "%{$search}%");
            });
        }

        $pembelians = $query->paginate($perPage);

        $counts = [
            'semua'               => Pembelian::whereIn('status', $statusAktif)->count(),
            'menunggu_verifikasi' => Pembelian::where('status', 'menunggu_verifikasi')->count(),
            'menunggu_pembayaran' => Pembelian::where('status', 'menunggu_pembayaran')->count(),
            'pembayaran_berhasil' => Pembelian::where('status', 'pembayaran_berhasil')->count(),
            'menunggu_review_kerusakan' => Pembelian::where('status', 'menunggu_review_kerusakan')->count(), // ← opsional
            'ditolak'             => Pembelian::where('status', 'ditolak')->count(),
        ];

        return view('pengelola.pembelian.index', compact('pembelians', 'status', 'counts'));
    }

    // ── Detail pengajuan ─────────────────────────────────────────
    public function show(Pembelian $pembelian, ShippingService $shippingService)
    {
        $pembelian->load(['user', 'painting', 'payments', 'shippingZone']);

        // Info zona untuk ditampilkan ke pengelola
        $zonaSummary  = $shippingService->getZoneSummary($pembelian);
        $shippingZones = ShippingZone::orderBy('id')->get(); // untuk dropdown jika pengelola mau ganti

        return view('pengelola.pembelian.show', compact('pembelian', 'zonaSummary', 'shippingZones'));
    }

    public function riwayat(Request $request)
    {
        $query = Pembelian::whereIn('status', ['selesai', 'ditolak', 'dibatalkan'])
            ->with('painting');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('dari')) {
            $query->whereDate('created_at', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $request->sampai);
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%'.$request->search.'%')
                ->orWhereHas('koleksi', fn ($k) => $k->where('nama', 'like', '%'.$request->search.'%'));
            });
        }

        $riwayat = $query->latest()->paginate(20)->withQueryString();

        $counts = [
            'selesai'    => Pembelian::where('status', 'selesai')->count(),
            'ditolak'    => Pembelian::where('status', 'ditolak')->count(),
            'dibatalkan' => Pembelian::where('status', 'dibatalkan')->count(),
        ];

        return view('pengelola.pembelian.riwayat', compact('riwayat', 'counts'));
    }

    // ── Setujui pengajuan + tentukan metode & ongkir ─────────────
    //
    // Flow:
    // 1. Pengelola pilih metode: 'courier' atau 'manager'
    // 2. Pengelola input ongkir (atau pakai default zona)
    // 3. Sistem hitung total = harga_beli + ongkir
    // 4. Generate invoice dengan total final
    //
    public function approve(
        Request $request,
        Pembelian $pembelian,
        InvoiceService $invoiceService,
        ShippingService $shippingService
    ) {
        if ($pembelian->status !== 'menunggu_verifikasi') {
            return redirect()->back()
                ->with('error', 'Pengajuan ini tidak dapat disetujui pada status saat ini.');
        }

        $request->validate([
            'shipping_method_type' => ['required', 'in:courier,manager'],
            'courier_name'         => ['nullable', 'string', 'max:100'],
            'shipping_cost'        => ['required', 'numeric', 'min:0'],
            'catatan_pengelola'    => ['nullable', 'string', 'max:1000'],
        ]);

        $methodType   = $request->input('shipping_method_type');
        $courierName  = $request->input('courier_name');
        $shippingCost = (float) $request->input('shipping_cost');

        // ── Warning: harga ≥ 10 juta tapi pilih kurir ───────────
        // Sistem tidak blok, tapi tandai di catatan
        $warningMsg = null;
        if ($methodType === 'courier' && $shippingService->isManagerOnly($pembelian->harga_beli)) {
            $warningMsg = '[PERHATIAN] Koleksi akan dikirim via kurir atas keputusan pengelola. ';
        }

        // ── Zona: ambil dari pembelian (sudah di-resolve saat store) ─
        $zone = $pembelian->shippingZone;

        // Jika zona belum ada (data lama), resolve ulang
        if (! $zone) {
            $zone = $shippingService->resolveZone(
                $pembelian->provinsi,
                $pembelian->kota_kabupaten
            );
        }

        // ── Override ongkir: zona gratis tetap 0 ────────────────
        if ($zone->is_free) {
            $shippingCost = 0;
        }

        // ── Hitung total final ───────────────────────────────────
        $totalBayar = $pembelian->harga_beli + $shippingCost;

        // ── Update pembelian ─────────────────────────────────────
        $pembelian->update([
            'status'               => 'menunggu_pembayaran',
            'shipping_method_type' => $methodType,
            'courier_name'         => $methodType === 'courier' ? $courierName : null,
            'shipping_cost'        => $shippingCost,
            'shipping_zone_id'     => $zone->id,
            'total_bayar'          => $totalBayar,
            'catatan_pengelola'    => ($warningMsg ?? '') . ($request->input('catatan_pengelola') ?? ''),
        ]);

        // ── Generate invoice dengan total final ──────────────────
        $invoiceService->generate($pembelian->fresh());

        return redirect()->back()
            ->with('success', 'Pengajuan disetujui. Invoice dengan ongkir telah digenerate.');
    }

    // ── Tolak pengajuan ──────────────────────────────────────────
    public function reject(Request $request, Pembelian $pembelian)
    {
        if ($pembelian->status !== 'menunggu_verifikasi') {
            return redirect()->route('pengelola.pembelian.show', $pembelian)
                ->with('error', 'Pengajuan ini tidak dapat ditolak pada status saat ini.');
        }

        $request->validate([
            'catatan_pengelola' => ['required', 'string', 'max:1000'],
        ]);

        $pembelian->update([
            'status'            => 'ditolak',
            'catatan_pengelola' => $request->catatan_pengelola,
        ]);

        return redirect()->route('pengelola.pembelian.show', $pembelian)
            ->with('success', 'Pengajuan berhasil ditolak.');
    }
}