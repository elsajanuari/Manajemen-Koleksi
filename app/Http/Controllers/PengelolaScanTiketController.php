<?php

namespace App\Http\Controllers;

use App\Models\PemesananTiket;
use App\Models\DetailPengunjung;
use App\Services\PemesananTiketRefundNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengelolaScanTiketController extends Controller
{
    public function form()
    {
        return view('pengelola.verifikasi-tiket');
    }

    public function lookup(Request $request)
    {
        $validated = $request->validate([
            'kode' => ['required', 'string', 'max:2048'],
        ]);

        $input = trim($validated['kode']);
        $token = $this->extractTokenFromInput($input);

        if (!$this->isValidSystemTicketToken($token)) {
            return redirect()->route('pengelola.verifikasi-tiket.form')
                ->with('error', 'QR yang Anda pindai bukan hasil generate sistem e-tiket ini. Pastikan Anda memindai QR Code tiket dari sistem ini.');
        }

        return $this->prosesVerifikasiOtomatis($request, $token);
    }

    private function extractTokenFromInput(string $input): string
    {
        if (preg_match('#/pengelola/scan-tiket/([^/?\s]+)#', $input, $m)) {
            return $m[1];
        }

        return $input;
    }

    private function isValidSystemTicketToken(string $token): bool
    {
        $normalizedToken = trim($token);

        if ($normalizedToken === '') {
            return false;
        }

        return preg_match('/^[a-f0-9]{64}$/i', $normalizedToken) === 1;
    }

    private function prosesVerifikasiOtomatis(Request $request, string $token)
    {
        $detailPengunjung = DetailPengunjung::where('tiket_verifikasi_token', $token)
            ->with(['pemesananTiket', 'pemesananTiket.ticket', 'pemesananTiket.user'])
            ->first();

        if (!$detailPengunjung) {
            return redirect()->route('pengelola.verifikasi-tiket.form')
                ->with('error', 'Token tiket tidak valid.');
        }

        $pemesanan = $detailPengunjung->pemesananTiket;

        if (!$pemesanan->isPaid()) {
            return redirect()->route('pengelola.verifikasi-tiket.form')
                ->with('error', 'Tiket belum lunas, tidak dapat diverifikasi.');
        }

        if ($detailPengunjung->tiket_terpakai_at) {
            return redirect()->route('pengelola.scan-tiket', ['token' => $token])
                ->with('warning', 'Tiket ini sudah ditandai digunakan sebelumnya.');
        }

        DB::transaction(function () use ($detailPengunjung, $request) {
            $row = DetailPengunjung::query()->lockForUpdate()->findOrFail($detailPengunjung->id);
            if (!$row->tiket_terpakai_at) {
                $row->forceFill([
                    'tiket_terpakai_at' => now(),
                ])->save();
            }
        });

        return redirect()->route('pengelola.scan-tiket', ['token' => $token])
            ->with('success', 'Tiket berhasil ditandai sebagai sudah digunakan.');
    }

    public function show(string $token)
    {
        $detailPengunjung = DetailPengunjung::where('tiket_verifikasi_token', $token)
            ->with(['pemesananTiket', 'pemesananTiket.ticket', 'pemesananTiket.user'])
            ->first();

        if (!$detailPengunjung) {
            abort(404, 'Tiket tidak ditemukan.');
        }

        $pemesanan = $detailPengunjung->pemesananTiket;

        if (!$pemesanan->isPaid()) {
            abort(404, 'Tiket belum lunas.');
        }

        return view('pengelola.scan-tiket', compact('detailPengunjung', 'pemesanan', 'token'));
    }

    public function tandaiTerpakai(Request $request, string $token)
    {
        $detailPengunjung = DetailPengunjung::where('tiket_verifikasi_token', $token)
            ->with('pemesananTiket')
            ->first();

        if (!$detailPengunjung) {
            return back()->with('error', 'Tiket tidak valid.');
        }

        $pemesanan = $detailPengunjung->pemesananTiket;

        if (!$pemesanan->isPaid()) {
            return back()->with('error', 'Tiket belum lunas.');
        }

        if ($detailPengunjung->tiket_terpakai_at) {
            return back()->with('warning', 'Tiket ini sudah ditandai digunakan sebelumnya.');
        }

        $updated = DB::transaction(function () use ($detailPengunjung, $request): bool {
            $row = DetailPengunjung::query()->lockForUpdate()->findOrFail($detailPengunjung->id);

            if ($row->tiket_terpakai_at) {
                return false;
            }

            $row->forceFill([
                'tiket_terpakai_at' => now(),
            ])->save();

            return true;
        });

        if (!$updated) {
            return back()->with('warning', 'Tiket ini sudah ditandai digunakan sebelumnya.');
        }

        return back()->with('success', 'Tiket berhasil ditandai sebagai sudah digunakan.');
    }

    public function riwayat(Request $request)
    {
        $statusFilter = $request->query('status', 'all');
        $search = $request->query('search', '');
        $sort = $request->query('sort', 'newest');

        $query = DetailPengunjung::with(['pemesananTiket', 'pemesananTiket.ticket', 'pemesananTiket.user'])
            ->whereHas('pemesananTiket', function($q) {
                $q->where('status', 'lunas');
            });

        if ($statusFilter === 'used') {
            $query->whereNotNull('tiket_terpakai_at');
        } elseif ($statusFilter === 'unused') {
            $query->whereNull('tiket_terpakai_at');
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nama_kelompok', 'like', "%{$search}%")
                  ->orWhereHas('pemesananTiket', function($q2) use ($search) {
                      $q2->where('id', 'like', "%{$search}%")
                         ->orWhereHas('user', function($q3) use ($search) {
                             $q3->where('name', 'like', "%{$search}%");
                         })
                         ->orWhereHas('ticket', function($q3) use ($search) {
                             $q3->where('nama_tiket', 'like', "%{$search}%");
                         });
                  });
            });
        }

        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $riwayat = $query->paginate(15)->withQueryString();

        return view('pengelola.riwayat-tiket', compact('riwayat', 'statusFilter'));
    }

    public function riwayatPemesanan(Request $request)
    {
        $statusFilter = $request->query('status', 'all');
        $search = trim((string) $request->query('search', ''));

        $query = PemesananTiket::query()->with(['ticket', 'user', 'detailPengunjungs'])
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
            ->orderByDesc('tanggal_pemesanan')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('pemesanan-tiket.index', [
            'pemesanans' => $pemesanans,
            'isAdmin' => true,
            'statusFilter' => $statusFilter,
            'search' => $search,
        ]);
    }

    public function kirimRefund(Request $request, PemesananTiket $pemesananTiket)
    {
        if ($pemesananTiket->status !== 'proses_pembatalan') {
            return back()->with('error', 'Status pemesanan tidak mendukung pengiriman bukti refund.');
        }

        $validated = $request->validate([
            'bukti_pengembalian' => ['required', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $path = $request->file('bukti_pengembalian')->store('bukti-pengembalian', 'public');

        \App\Services\PemesananTiketModifikasiService::releaseKuota($pemesananTiket);

        $pemesananTiket->update([
            'status' => 'pengembalian_berhasil',
            'bukti_pengembalian' => $path,
            'refund_completed_at' => now(),
        ]);

        PemesananTiketRefundNotifier::notifyRefundCompleted($pemesananTiket->fresh());

        return back()->with('success', 'Bukti transfer berhasil disimpan, status pengembalian telah diperbarui. Pendapatan otomatis berkurang.');
    }

    public function detailRefund(PemesananTiket $pemesananTiket)
    {
        if ($pemesananTiket->status !== 'proses_pembatalan' && $pemesananTiket->status !== 'pengembalian_berhasil') {
            abort(404);
        }
        
        return view('pengelola.detail-refund', compact('pemesananTiket'));
    }
}