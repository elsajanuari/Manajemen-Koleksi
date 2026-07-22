<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Penyewaan;
use Illuminate\Http\Request;

class PengelolaTransactionController extends Controller
{
    private const PEMBELIAN_FINAL_STATUSES = [
        'diterima_pembeli', 'selesai', 'dibatalkan', 'ditolak',
    ];

    private const PENYEWAAN_FINAL_STATUSES = [
        'selesai', 'dikembalikan', 'dibatalkan', 'ditolak',
    ];

    public function index(Request $request)
    {
        $filters = [
            'search'    => $request->input('search', ''),
            'type'      => $request->input('type', 'all'),
            'date_from' => $request->input('date_from', ''),
            'date_to'   => $request->input('date_to', ''),
        ];

        $perPage = (int) $request->input('per_page', 20);

        // Summary tidak terpengaruh filter
        $summaryPembelian = Pembelian::whereIn('status', self::PEMBELIAN_FINAL_STATUSES)->count();
        $summaryPenyewaan = Penyewaan::whereIn('status', self::PENYEWAAN_FINAL_STATUSES)->count();

        $summary = [
            'total'     => $summaryPembelian + $summaryPenyewaan,
            'pembelian' => $summaryPembelian,
            'penyewaan' => $summaryPenyewaan,
        ];

        // Query
        $pembelianQuery = Pembelian::with(['user', 'painting'])
            ->whereIn('status', self::PEMBELIAN_FINAL_STATUSES);

        $penyewaanQuery = Penyewaan::with(['user', 'painting'])
            ->whereIn('status', self::PENYEWAAN_FINAL_STATUSES);

        // Filter search
        if ($filters['search']) {
            $s = $filters['search'];
            $pembelianQuery->where(function ($q) use ($s) {
                $q->where('nama_lengkap', 'like', "%$s%")
                  ->orWhere('email', 'like', "%$s%")
                  ->orWhereHas('painting', fn($k) => $k->where('title', 'like', "%$s%"))
                  ->orWhere('id', is_numeric($s) ? $s : -1);
            });
            $penyewaanQuery->where(function ($q) use ($s) {
                $q->where('contact_name', 'like', "%$s%")
                  ->orWhere('contact_email', 'like', "%$s%")
                  ->orWhere('nama_instansi', 'like', "%$s%")
                  ->orWhereHas('painting', fn($k) => $k->where('title', 'like', "%$s%"))
                  ->orWhere('id', is_numeric($s) ? $s : -1);
            });
        }

        // Filter tanggal
        if ($filters['date_from']) {
            $pembelianQuery->whereDate('created_at', '>=', $filters['date_from']);
            $penyewaanQuery->whereDate('created_at', '>=', $filters['date_from']);
        }
        if ($filters['date_to']) {
            $pembelianQuery->whereDate('created_at', '<=', $filters['date_to']);
            $penyewaanQuery->whereDate('created_at', '<=', $filters['date_to']);
        }

        // Gabung & sort
        $type = $filters['type'];

        if ($type === 'pembelian') {
            $rows = $pembelianQuery->latest()->get()->map(fn($p) => $this->mapPembelian($p));
        } elseif ($type === 'penyewaan') {
            $rows = $penyewaanQuery->latest()->get()->map(fn($p) => $this->mapPenyewaan($p));
        } else {
            $rows = $pembelianQuery->latest()->get()->map(fn($p) => $this->mapPembelian($p))
                ->merge($penyewaanQuery->latest()->get()->map(fn($p) => $this->mapPenyewaan($p)))
                ->sortByDesc('date')
                ->values();
        }

        // Paginasi manual
        $page         = (int) $request->input('page', 1);
        $transactions = new \Illuminate\Pagination\LengthAwarePaginator(
            $rows->forPage($page, $perPage)->values(),
            $rows->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('pengelola.transactions.index', compact('transactions', 'summary', 'filters'));
    }

    public function show(Request $request, string $type, int $id)
    {
        if ($type === 'pembelian') {
            $model = Pembelian::with(['painting', 'user'])->findOrFail($id);
            return view('pengelola.transactions.show', compact('type', 'model'));
        }

        if ($type === 'penyewaan') {
            $model = Penyewaan::with(['painting', 'user'])->findOrFail($id);
            return view('pengelola.transactions.show', compact('type', 'model'));
        }

        abort(404);
    }

    private function mapPembelian(Pembelian $p): array
    {
        $painting = $p->painting;

        return [
            'id'              => $p->id,
            'type'            => 'pembelian',
            'status'          => $p->status,
            'date'            => $p->created_at,
            'title'           => $painting?->title ?? '-',
            'painting_artist' => $painting?->artist ?? null,
            'painting_image'  => $painting?->image_url ?? null,
            'user_name'       => $p->nama_lengkap ?? $p->user?->name ?? '-',
            'user_email'      => $p->email ?? $p->user?->email ?? '',
            'amount'          => (int) ($p->total_bayar ?? $p->harga_beli ?? 0),
            'refund_amount'   => (int) ($p->refund_amount ?? 0),
            'rental_start'    => null,
            'rental_end'      => null,
        ];
    }

    private function mapPenyewaan(Penyewaan $p): array
    {
        $painting = $p->painting;

        return [
            'id'              => $p->id,
            'type'            => 'penyewaan',
            'status'          => $p->status,
            'date'            => $p->created_at,
            'title'           => $painting?->title ?? '-',
            'painting_artist' => $painting?->artist ?? null,
            'painting_image'  => $painting?->image_url ?? null,
            'user_name'       => $p->contact_name ?? $p->nama_instansi ?? $p->user?->name ?? '-',
            'user_email'      => $p->contact_email ?? $p->user?->email ?? '',
            'amount'          => (int) ($p->total_bayar ?? $p->subtotal_amount ?? 0),
            'refund_amount'   => 0,
            'rental_start'    => $p->start_date,
            'rental_end'      => $p->end_date,
        ];
    }
}