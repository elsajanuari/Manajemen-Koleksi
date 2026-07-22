<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDetailPengunjungRequest;
use App\Models\DetailPengunjung;
use App\Models\PemesananTiket;
use App\Models\Ticket;
use App\Services\MidtransPaymentService;
use App\Services\PemesananTiketModifikasiService;
use App\Services\PemesananTiketRefundNotifier;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemesananTiketController extends Controller
{
    /**
     * Daftar pemesanan tiket pengguna
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->pemesanans()
            ->where('status', 'menunggu_pembayaran')
            ->where('tanggal_pemesanan', '<', now('Asia/Jakarta')->startOfDay())
            ->get()
            ->each->expireJikaKedaluwarsa();

        $statusFilter = $request->query('status', 'all');
        $search = trim((string) $request->query('search', ''));

        $query = $user->pemesanans()
            ->with('ticket', 'detailPengunjungs')
            ->with('ticket')
            ->where('status', '!=', 'pending');

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        if ($search !== '') {
        $query->where(function ($q) use ($search) {
            $q->where('id', 'like', "%{$search}%")
                ->orWhereHas('user', function ($user) use ($search) {
                    $user->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('detailPengunjungs', function ($detail) use ($search) {
                    $detail->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('nama_kelompok', 'like', "%{$search}%");
                })
                ->orWhereHas('ticket', function ($ticket) use ($search) {
                    $ticket->where('nama_tiket', 'like', "%{$search}%");
                });
        });
    }

        $pemesanans = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pemesanan-tiket.index', [
            'pemesanans' => $pemesanans,
            'isAdmin' => false,
            'statusFilter' => $statusFilter,
            'search' => $search,
        ]);
    }

    /**
     * Detail pemesanan tiket
     */
    public function show(PemesananTiket $pemesananTiket)
    {
        $this->authorize('view', $pemesananTiket);
        $pemesananTiket->expireJikaKedaluwarsa();
        $pemesananTiket->load(['ticket', 'detailPengunjungs']);

        return view('pemesanan-tiket.show', compact('pemesananTiket'));
    }

    /**
     * Form permintaan pembatalan tiket
     */
    public function batalkanForm(PemesananTiket $pemesananTiket)
    {
        $this->authorize('view', $pemesananTiket);
        $pemesananTiket->expireJikaKedaluwarsa();

        if ($pemesananTiket->isTiketTerpakai()) {
            return redirect()->route('pemesanan-tiket.show', $pemesananTiket)
                ->with('error', 'Tiket sudah digunakan, tidak dapat dibatalkan.');
        }

        if ($pemesananTiket->isPending()) {
            $this->authorize('delete', $pemesananTiket);
            return view('pemesanan-tiket.batalkan', compact('pemesananTiket'));
        }

        if ($pemesananTiket->isWaitingPayment()) {
            return view('pemesanan-tiket.batalkan', compact('pemesananTiket'));
        }

        if (! $pemesananTiket->dapatCancel()) {
            $pesan = 'Pemesanan ini tidak dapat dibatalkan.';

            if ($pemesananTiket->ticket && ! $pemesananTiket->ticket->boleh_cancel) {
                $pesan = 'Tiket ini tidak mengizinkan pembatalan.';
            } elseif (! \App\Services\PemesananTiketModifikasiService::masihDalamBatasWaktu($pemesananTiket)) {
                $pesan = \App\Services\PemesananTiketModifikasiService::pesanBatasWaktu($pemesananTiket);
            }

            return redirect()->route('pemesanan-tiket.show', $pemesananTiket)
                ->with('error', $pesan);
        }

        if ($pemesananTiket->isPaid()) {
            $this->authorize('cancel', $pemesananTiket);
        }

        return view('pemesanan-tiket.batalkan', compact('pemesananTiket'));
    }

    /**
     * Proses checkout
     */
    public function checkout(Request $request, int $id)
    {
        $ticket = Ticket::where('status', true)->findOrFail($id);

        $validated = $request->validate([
            'tanggal_pemesanan' => ['required', 'date'],
            'jumlah_tiket' => ['required', 'integer', 'min:1'],
        ]);

        $tanggalPemesanan = $validated['tanggal_pemesanan'];
        $jumlahTiket = (int) $validated['jumlah_tiket'];

        if (
            strtolower((string) $ticket->jenis_tiket) === 'event' &&
            strtolower((string) $ticket->sub_jenis) === 'sunday painting' &&
            (string) $ticket->kategori_pengunjung === 'Kelompok' &&
            $jumlahTiket < 5
        ) {
            return back()->withErrors([
                'jumlah_tiket' => 'Tiket Sunday Painting kategori Kelompok minimal pembelian 5 tiket.',
            ])->withInput();
        }
        
        $quota = $ticket->quotas()
            ->whereDate('tanggal', $tanggalPemesanan)
            ->first();

        if (!$quota || $quota->tanggal->lt(now()->startOfDay()) || $quota->kuota_sisa <= 0) {
            return back()->withErrors([
                'tanggal_pemesanan' => 'Tanggal yang dipilih tidak tersedia.',
            ])->withInput();
        }

        if ($jumlahTiket > $quota->kuota_sisa) {
            return back()->withErrors([
                'jumlah_tiket' => 'Kuota tidak mencukupi. Sisa kuota: ' . $quota->kuota_sisa,
            ])->withInput();
        }

        if (strtolower((string) $ticket->jenis_tiket) === 'event' &&
            strtolower((string) $ticket->sub_jenis) === 'sunday painting' &&
            (string) $ticket->kategori_pengunjung === 'Individu' &&
            $jumlahTiket > 4) {
            return back()->withErrors([
                'jumlah_tiket' => 'Pembelian tiket Sunday Painting untuk kategori Individu maksimal 4 tiket. Jika lebih dari 4, silakan pilih kategori Kelompok.',
            ])->withInput();
        }

        $totalHarga = $jumlahTiket * (int) $ticket->harga;

        // Buat pemesanan awal dengan status pending.
        $pemesanan = PemesananTiket::create([
            'user_id' => Auth::id(),
            'ticket_id' => $ticket->id,
            'tanggal_pemesanan' => $tanggalPemesanan,
            'jumlah_tiket' => $jumlahTiket,
            'total_harga' => $totalHarga,
            'status' => 'pending',
        ]);

        return redirect()->route('pemesanan-tiket.detail-pengunjung', $pemesanan->id)
            ->with('success', 'Pemesanan berhasil dibuat. Silakan lengkapi data pengunjung terlebih dahulu.');
    }

    /**
     * Form pengisian detail pengunjung
     */
    public function detailPengunjung(PemesananTiket $pemesananTiket)
    {
        $this->authorize('view', $pemesananTiket);
        $pemesananTiket->expireJikaKedaluwarsa();

        if (!$pemesananTiket->isPending() && !$pemesananTiket->isWaitingPayment()) {
            return redirect()->route('pemesanan-tiket.show', $pemesananTiket)
                ->with('error', 'Pemesanan ini tidak valid.');
        }

        $ticket = $pemesananTiket->ticket;
        $detailPengunjungs = $pemesananTiket->detailPengunjungs;

        return view('pemesanan-tiket.detail-pengunjung', compact('pemesananTiket', 'ticket', 'detailPengunjungs'));
    }

    /**
     * Simpan detail pengunjung
     */
    public function storeDetailPengunjung(StoreDetailPengunjungRequest $request, PemesananTiket $pemesananTiket)
    {
        if (!$pemesananTiket->isPending() &&
            !$pemesananTiket->isWaitingPayment()) {
            return redirect()->route('pemesanan-tiket.show', $pemesananTiket)
                ->with('error', 'Pemesanan ini tidak valid.');
        }

        $ticket = $pemesananTiket->ticket;
        $jumlahTiket = $pemesananTiket->jumlah_tiket;
        
        $isKelompok = strtolower((string) $ticket->jenis_tiket) === 'event' &&
                    strtolower((string) $ticket->sub_jenis) === 'sunday painting' &&
                    (string) $ticket->kategori_pengunjung === 'Kelompok';

        $validated = $request->validated();

        $buktiPelajarPath = null;
        if (strtolower((string) $ticket->kategori_pengunjung) === 'pelajar' && $request->hasFile('bukti_pelajar')) {
            $buktiPelajarPath = $request->file('bukti_pelajar')->store('bukti-pelajar', 'public');
        }

        // Hapus detail pengunjung lama jika ada
        $pemesananTiket->detailPengunjungs()->delete();

        if ($isKelompok) {
            $detail = DetailPengunjung::create([
                'pemesanan_tiket_id' => $pemesananTiket->id,
                'urutan_pengunjung' => 1,
                'nama_kelompok' => $validated['nama_kelompok'],
                'daftar_anggota' => collect(
                    array_map('trim', explode(',', $validated['daftar_anggota']))
                )->filter()->values(),
                'nama_penanggung_jawab' => $validated['nama_penanggung_jawab'],
                'alamat_penanggung_jawab' => $validated['alamat_penanggung_jawab'],
                'nomor_ponsel_penanggung_jawab' => $validated['nomor_ponsel_penanggung_jawab'],
                'email_penanggung_jawab' => $validated['email_penanggung_jawab'],
                'email' => $validated['email_penanggung_jawab'], 
                'nomor_ponsel' => $validated['nomor_ponsel_penanggung_jawab'],
                'alamat' => $validated['alamat_penanggung_jawab'],
                'bukti_pelajar_path' => $buktiPelajarPath,
                'tipe_pengunjung' => 'kelompok',
                'tiket_verifikasi_token' => $this->generateUniqueToken(),
            ]);
        } else {
            for ($i = 1; $i <= $jumlahTiket; $i++) {
                DetailPengunjung::create([
                    'pemesanan_tiket_id' => $pemesananTiket->id,
                    'urutan_pengunjung' => $i,
                    'nama_lengkap' => $validated['pengunjung'][$i]['nama_lengkap'],
                    'pendidikan' => $validated['pengunjung'][$i]['pendidikan'] ?? null,
                    'email' => $validated['pengunjung'][$i]['email'],
                    'nomor_ponsel' => $validated['pengunjung'][$i]['nomor_ponsel'],
                    'alamat' => $validated['pengunjung'][$i]['alamat'],
                    'bukti_pelajar_path' => $buktiPelajarPath,
                    'tipe_pengunjung' => 'individu',
                    'tiket_verifikasi_token' => $this->generateUniqueToken(),
                ]);
            }
        }

        $pemesananTiket->update(['status' => 'menunggu_pembayaran']);

        return redirect()->route('pemesanan-tiket.show', $pemesananTiket)
            ->with('success', 'Data pengunjung berhasil disimpan. Silakan tinjau detail pemesanan lalu lanjutkan pembayaran.');
    }

    private function generateUniqueToken(): string
    {
        do {
            $token = bin2hex(random_bytes(32));
            $exists = DetailPengunjung::where('tiket_verifikasi_token', $token)->exists();
        } while ($exists);
        
        return $token;
    }

    /**
     * Form pembayaran
     */
    public function bayar(PemesananTiket $pemesananTiket)
    {
        $this->authorize('view', $pemesananTiket);
        $pemesananTiket->expireJikaKedaluwarsa();

        if (!$pemesananTiket->isWaitingPayment()) {
            return redirect()->route('pemesanan-tiket.show', $pemesananTiket)
                ->with('error', 'Pemesanan ini tidak menunggu pembayaran.');
        }

        if (!$pemesananTiket->isDetailPengunjungComplete()) {
            return redirect()->route('pemesanan-tiket.detail-pengunjung', $pemesananTiket->id)
                ->with('error', 'Silakan lengkapi data pengunjung terlebih dahulu.');
        }

        if (!$pemesananTiket->ticket || $pemesananTiket->ticket->trashed()) {
        $pemesananTiket->update([
            'status' => 'dibatalkan',
            'dibatalkan_pada' => now(),
            'catatan' => 'Pemesanan dibatalkan otomatis karena tiket sudah tidak tersedia.',
        ]);

        return redirect()->route('pemesanan-tiket.index')
            ->with('error', 'Pemesanan dibatalkan karena tiket sudah tidak tersedia.');
    }

        return view('pemesanan-tiket.bayar', compact('pemesananTiket'));
    }

    /**
     * Token Snap Midtrans
     */
    public function midtransSnapToken(PemesananTiket $pemesananTiket)
    {
        $this->authorize('view', $pemesananTiket);
        $pemesananTiket->expireJikaKedaluwarsa();

        if (! $pemesananTiket->isWaitingPayment()) {
            return response()->json(['message' => 'Pemesanan tidak menunggu pembayaran.'], 422);
        }

        if (! config('midtrans.server_key') || ! config('midtrans.client_key')) {
            return response()->json(['message' => 'Pembayaran Midtrans belum dikonfigurasi (MIDTRANS_* di .env).'], 503);
        }

        try {
            $pemesananTiket->load(['ticket', 'user', 'detailPengunjungs']);
            $snapToken = MidtransPaymentService::createSnapToken($pemesananTiket);

            return response()->json(['snap_token' => $snapToken]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['message' => 'Gagal membuat sesi pembayaran. Silakan coba lagi.'], 500);
        }
    }

    /**
     * E-tiket dengan QR
     */
    public function etiket(PemesananTiket $pemesananTiket, ?DetailPengunjung $detailPengunjung = null)
    {
        $this->authorize('view', $pemesananTiket);

        if (!$pemesananTiket->isPaid()) {
            return redirect()->route('pemesanan-tiket.show', $pemesananTiket)
                ->with('error', 'E-tiket tersedia setelah pembayaran berhasil dikonfirmasi.');
        }

        $pemesananTiket->load(['ticket', 'detailPengunjungs', 'user']);

        if (!$detailPengunjung) {
            $detailPengunjung = $pemesananTiket->detailPengunjungs->first();
        } else {
            if ($detailPengunjung->pemesanan_tiket_id !== $pemesananTiket->id) {
                abort(404);
            }
        }

        if (!$detailPengunjung->tiket_verifikasi_token) {
            $detailPengunjung->tiket_verifikasi_token = $this->generateUniqueToken();
            $detailPengunjung->save();
        }

        $verificationUrl = route('pengelola.scan-tiket', [
            'token' => $detailPengunjung->tiket_verifikasi_token
        ], absolute: true);

        $builder = new Builder(writer: new PngWriter(), validateResult: false);
        $qrImageDataUri = $builder->build(data: $verificationUrl, size: 260)->getDataUri();

        return view('pemesanan-tiket.e-tiket', compact(
            'pemesananTiket', 
            'detailPengunjung', 
            'qrImageDataUri', 
            'verificationUrl'
        ));
    }

    /**
     * Show all e-tickets for a booking
     */
    public function semuaEtiket(PemesananTiket $pemesananTiket)
    {
        $this->authorize('view', $pemesananTiket);

        if (!$pemesananTiket->isPaid()) {
            return redirect()->route('pemesanan-tiket.show', $pemesananTiket)
                ->with('error', 'E-tiket tersedia setelah pembayaran berhasil dikonfirmasi.');
        }

        $pemesananTiket->load(['ticket', 'detailPengunjungs', 'user']);
        
        foreach ($pemesananTiket->detailPengunjungs as $detail) {
            if (!$detail->tiket_verifikasi_token) {
                $detail->tiket_verifikasi_token = $this->generateUniqueToken();
                $detail->save();
            }
        }

        return view('pemesanan-tiket.semua-e-tiket', compact('pemesananTiket'));
    }

    /**
     * Sinkronkan status pembayaran dari API Midtrans
     */
    public function midtransSyncStatus(PemesananTiket $pemesananTiket)
    {
        $this->authorize('view', $pemesananTiket);

        if (! config('midtrans.server_key')) {
            return response()->json(['message' => 'Midtrans belum dikonfigurasi.'], 503);
        }

        if (! $pemesananTiket->midtrans_order_id) {
            return response()->json(['message' => 'Belum ada Order ID Midtrans.'], 422);
        }

        MidtransPaymentService::configure();

        try {
            $status = \Midtrans\Transaction::status($pemesananTiket->midtrans_order_id);
            MidtransPaymentService::sinkronkanDariResponsMidtrans($pemesananTiket, $status);

            return response()->json([
                'paid' => $pemesananTiket->fresh()->isPaid(),
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['message' => 'Gagal memeriksa status pembayaran.'], 500);
        }
    }

    /**
     * Form reschedule tanggal kunjungan
     */
    public function formReschedule(PemesananTiket $pemesananTiket)
    {
        $this->authorize('view', $pemesananTiket);

        // TAMBAHAN KEAMANAN: Blokir akses form jika tiket sudah terpakai
        if ($pemesananTiket->isTiketTerpakai()) {
            return redirect()->route('pemesanan-tiket.show', $pemesananTiket)
                ->with('error', 'Tiket sudah digunakan dan tidak dapat di-reschedule.');
        }

        $pemesananTiket->load(['ticket.quotas']);

        if (! $pemesananTiket->dapatReschedule()) {
            return redirect()->route('pemesanan-tiket.show', $pemesananTiket)
                ->with('error', 'Pemesanan ini tidak dapat di-reschedule. ' . PemesananTiketModifikasiService::pesanBatasWaktu($pemesananTiket));
        }

        $tanggalTersedia = $pemesananTiket->ticket->quotas
            ->filter(function ($quota) use ($pemesananTiket) {
                if ($quota->tanggal->toDateString() === $pemesananTiket->tanggal_pemesanan->toDateString()) {
                    return false;
                }

                if ($quota->kuota_sisa < $pemesananTiket->jumlah_tiket) {
                    return false;
                }

                $batas = $quota->tanggal->copy()->startOfDay()
                    ->subHours((int) config('museum.batas_modifikasi_jam', 48));

                return $quota->tanggal->gte(now()->startOfDay()) && now()->lte($batas);
            })
            ->sortBy('tanggal')
            ->values();

        return view('pemesanan-tiket.reschedule', compact('pemesananTiket', 'tanggalTersedia'));
    }

    /**
     * Proses reschedule
     */
    public function reschedule(Request $request, PemesananTiket $pemesananTiket)
    {
        $this->authorize('reschedule', $pemesananTiket);

        // TAMBAHAN KEAMANAN: Blokir aksi kirim data jika tiket sudah terpakai
        if ($pemesananTiket->isTiketTerpakai()) {
            return redirect()->route('pemesanan-tiket.show', $pemesananTiket)
                ->with('error', 'Tiket sudah digunakan dan tidak dapat di-reschedule.');
        }

        $validated = $request->validate([
            'tanggal_pemesanan_baru' => ['required', 'date'],
        ]);

        try {
            PemesananTiketModifikasiService::reschedule(
                $pemesananTiket,
                $validated['tanggal_pemesanan_baru']
            );
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }

        return redirect()->route('pemesanan-tiket.show', $pemesananTiket)
            ->with('success', 'Tanggal kunjungan berhasil diubah.');
    }

    /**
     * Proses pembatalan pemesanan
     */
    public function batalkan(Request $request, PemesananTiket $pemesananTiket)
    {
        $this->authorize('view', $pemesananTiket);

        // CEK DI AWAL: Tiket sudah digunakan, langsung alihkan ke halaman detail dengan error
        if ($pemesananTiket->isTiketTerpakai()) {
            return redirect()->route('pemesanan-tiket.show', $pemesananTiket)
                ->with('error', 'Tiket sudah digunakan dan tidak dapat dibatalkan.');
        }

        if ($pemesananTiket->isPending()) {
            $this->authorize('delete', $pemesananTiket);
            $pemesananTiket->delete();

            return redirect()->route('tiket.index')->with('success', 'Pemesanan dibatalkan dan tidak disimpan ke riwayat.');
        }

        $this->authorize('cancel', $pemesananTiket);

        if (! $pemesananTiket->dapatCancel()) {
            $pesan = 'Pemesanan ini tidak dapat dibatalkan.';
            if ($pemesananTiket->ticket && ! $pemesananTiket->ticket->boleh_cancel) {
                $pesan = 'Tiket ini tidak mengizinkan pembatalan.';
            } elseif (! PemesananTiketModifikasiService::masihDalamBatasWaktu($pemesananTiket)) {
                $pesan = PemesananTiketModifikasiService::pesanBatasWaktu($pemesananTiket);
            }

            return redirect()->route('pemesanan-tiket.show', $pemesananTiket)->with('error', $pesan);
        }

        if ($pemesananTiket->isPaid()) {
            $validated = $request->validate([
                'nama_bank'  => ['required', 'string', 'max:255'],
                'atas_nama'  => ['required', 'string', 'max:255'],
                'no_rekening'=> ['required', 'string', 'max:255'],
                'catatan'    => ['nullable', 'string', 'max:1000'],
            ]);

            $pemesananTiket->update([
                'status'             => 'proses_pembatalan',
                'nama_bank_refund'   => $validated['nama_bank'],
                'atas_nama_refund'   => $validated['atas_nama'],
                'no_rekening_refund' => $validated['no_rekening'],
                'catatan'            => $validated['catatan'] ?? $pemesananTiket->catatan,
                'refund_requested_at'=> now(),
                'dibatalkan_pada'    => now(),
            ]);

            PemesananTiketRefundNotifier::notifyRefundRequested($pemesananTiket->fresh());

            return redirect()->route('pemesanan-tiket.show', $pemesananTiket)
                ->with('success', 'Permintaan pembatalan terkirim. Tunggu pengelola memproses pengembalian dana.');
        }

        $pemesananTiket->update([
            'status'         => 'dibatalkan',
            'dibatalkan_pada'=> now(),
            'catatan'        => $request->input('catatan') ?? $pemesananTiket->catatan,
        ]);

        return redirect()->route('pemesanan-tiket.index')->with('success', 'Pemesanan berhasil dibatalkan.');
    }
}