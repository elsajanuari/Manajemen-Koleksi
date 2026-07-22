<?php

namespace App\Http\Controllers;

use App\Models\ConservationAction;
use App\Models\Koleksi;
use App\Models\KondisiKoleksi;
use App\Models\PerawatanKoleksi;
use Carbon\Carbon;
use Illuminate\View\View;

class ManajemenKoleksiController extends Controller
{
    public function index(): View
    {
        $totalKoleksi = Koleksi::count();

        $koleksiPerKategori = Koleksi::selectRaw('kategori, COUNT(*) as total')
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->get();

        $koleksiPerStatusSewa = Koleksi::selectRaw('status_sewa, COUNT(*) as total')
            ->groupBy('status_sewa')
            ->get();

        $koleksiDisimpan = Koleksi::where('lokasi', 'disimpan')->count();
        $koleksiDipamerkan = Koleksi::where('lokasi', 'dipamerkan')->count();

        $latestConditionIds = KondisiKoleksi::selectRaw('MAX(id) as id')
            ->groupBy('koleksi_id')
            ->pluck('id');

        $koleksiTerperiksa = $latestConditionIds->count();
        $koleksiBelumDiperiksa = max(0, $totalKoleksi - $koleksiTerperiksa);

        $latestKondisi = KondisiKoleksi::selectRaw('kondisi, COUNT(*) as total')
            ->whereIn('id', $latestConditionIds)
            ->groupBy('kondisi')
            ->get()
            ->keyBy('kondisi');

        $kondisiBaik = $latestKondisi->get('baik')?->total ?? 0;
        $kondisiRusakRingan = $latestKondisi->get('rusak_ringan')?->total ?? 0;
        $kondisiRusakBerat = $latestKondisi->get('rusak_berat')?->total ?? 0;

        $rekomendasi = KondisiKoleksi::selectRaw('rekomendasi_tindak_lanjut, COUNT(*) as total')
            ->whereIn('id', $latestConditionIds)
            ->whereNotNull('rekomendasi_tindak_lanjut')
            ->groupBy('rekomendasi_tindak_lanjut')
            ->orderByDesc('total')
            ->get();

        $menungguJadwal = KondisiKoleksi::whereIn('id', $latestConditionIds)
            ->whereIn('rekomendasi_tindak_lanjut', array_keys(KondisiKoleksi::REKOMENDASI_TO_JENIS_PERAWATAN))
            ->whereDoesntHave('jadwalTerjadwal')
            ->count();

        $perawatanTerjadwal = PerawatanKoleksi::where('status', PerawatanKoleksi::STATUS_TERJADWAL)->count();
        $perawatanSelesai = PerawatanKoleksi::where('status', PerawatanKoleksi::STATUS_SELESAI)->count();
        $perawatanDibatalkan = PerawatanKoleksi::where('status', PerawatanKoleksi::STATUS_DIBATALKAN)->count();
        $perawatanTerlambat = PerawatanKoleksi::overdue()->count();

        $perawatanHariIni = PerawatanKoleksi::dueToday()->count();
        $perawatanMingguDepan = PerawatanKoleksi::scheduled()
            ->whereBetween('jadwal_tanggal', [Carbon::tomorrow(), Carbon::now()->addDays(7)])
            ->count();
        $perawatanTerjadwalMendatang = PerawatanKoleksi::scheduled()
            ->whereDate('jadwal_tanggal', '>=', Carbon::today())
            ->count();

        $perawatanMendatang = PerawatanKoleksi::with(['koleksi', 'kondisiSumber'])
            ->scheduled()
            ->whereBetween('jadwal_tanggal', [Carbon::today(), Carbon::now()->addDays(30)])
            ->orderBy('jadwal_tanggal')
            ->limit(8)
            ->get();

        $today = Carbon::today();
        $scheduledDates = PerawatanKoleksi::scheduled()
            ->whereBetween('jadwal_tanggal', [$today, $today->copy()->addMonths(12)])
            ->orderBy('jadwal_tanggal')
            ->pluck('jadwal_tanggal');

        $countByDate = $scheduledDates->groupBy(fn ($d) => $d->toDateString())->map->count();
        $countByWeek = $scheduledDates->groupBy(fn ($d) => $d->copy()->startOfWeek()->toDateString())->map->count();
        $countByMonth = $scheduledDates->groupBy(fn ($d) => $d->format('Y-m'))->map->count();

        $timelineHarian = collect();
        for ($i = 0; $i < 30; $i++) {
            $cursor = $today->copy()->addDays($i);
            $timelineHarian->push([
                'label' => $cursor->translatedFormat('d M'),
                'total' => (int) ($countByDate[$cursor->toDateString()] ?? 0),
            ]);
        }

        $timelineMingguan = collect();
        $weekStart = $today->copy()->startOfWeek();
        for ($i = 0; $i < 12; $i++) {
            $cursor = $weekStart->copy()->addWeeks($i);
            $timelineMingguan->push([
                'label' => $cursor->translatedFormat('d M'),
                'total' => (int) ($countByWeek[$cursor->toDateString()] ?? 0),
            ]);
        }

        $timelineBulanan = collect();
        $monthStart = $today->copy()->startOfMonth();
        for ($i = 0; $i < 12; $i++) {
            $cursor = $monthStart->copy()->addMonths($i);
            $timelineBulanan->push([
                'label' => $cursor->translatedFormat('M Y'),
                'total' => (int) ($countByMonth[$cursor->format('Y-m')] ?? 0),
            ]);
        }

        $conservationByStatus = ConservationAction::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $conservationDirencanakan = $conservationByStatus->get(ConservationAction::STATUS_DIRENCANAKAN)?->total ?? 0;
        $conservationSedangBerjalan = $conservationByStatus->get(ConservationAction::STATUS_SEDANG_BERJALAN)?->total ?? 0;
        $conservationSelesai = $conservationByStatus->get(ConservationAction::STATUS_SELESAI)?->total ?? 0;

        $conservationActive = ConservationAction::with('koleksi')
            ->where('status', ConservationAction::STATUS_SEDANG_BERJALAN)
            ->orderByDesc('updated_at')
            ->limit(5)
            ->get();

        $koleksiKritis = KondisiKoleksi::with('koleksi')
            ->whereIn('id', $latestConditionIds)
            ->where('kondisi', 'rusak_berat')
            ->orderByDesc('tanggal_periksa')
            ->limit(5)
            ->get();

        $chartData = [
            'kategori' => [
                'labels' => $koleksiPerKategori->map(fn ($row) => ucfirst($row->kategori))->values()->all(),
                'data' => $koleksiPerKategori->pluck('total')->values()->all(),
            ],
            'kondisi' => [
                'labels' => ['Baik', 'Rusak Ringan', 'Rusak Berat', 'Belum Diperiksa'],
                'data' => [$kondisiBaik, $kondisiRusakRingan, $kondisiRusakBerat, $koleksiBelumDiperiksa],
            ],
            'lokasi' => [
                'labels' => ['Ruang Pameran', 'Ruang Penyimpanan'],
                'data' => [$koleksiDipamerkan, $koleksiDisimpan],
            ],
            'statusSewa' => [
                'labels' => $koleksiPerStatusSewa
                    ->map(fn ($row) => Koleksi::labelStatusSewa($row->status_sewa))
                    ->values()
                    ->all(),
                'data' => $koleksiPerStatusSewa->pluck('total')->values()->all(),
            ],
            'rekomendasi' => [
                'labels' => $rekomendasi
                    ->map(fn ($row) => KondisiKoleksi::REKOMENDASI_OPTIONS[$row->rekomendasi_tindak_lanjut] ?? ucfirst(str_replace('_', ' ', $row->rekomendasi_tindak_lanjut)))
                    ->values()
                    ->all(),
                'data' => $rekomendasi->pluck('total')->values()->all(),
            ],
            'perawatan' => [
                'labels' => ['Terjadwal', 'Selesai', 'Dibatalkan'],
                'data' => [$perawatanTerjadwal, $perawatanSelesai, $perawatanDibatalkan],
            ],
            'konservasi' => [
                'labels' => array_values(ConservationAction::STATUS_OPTIONS),
                'data' => [$conservationDirencanakan, $conservationSedangBerjalan, $conservationSelesai],
            ],
            'timeline' => [
                'harian' => [
                    'labels' => $timelineHarian->pluck('label')->all(),
                    'data' => $timelineHarian->pluck('total')->all(),
                ],
                'mingguan' => [
                    'labels' => $timelineMingguan->pluck('label')->all(),
                    'data' => $timelineMingguan->pluck('total')->all(),
                ],
                'bulanan' => [
                    'labels' => $timelineBulanan->pluck('label')->all(),
                    'data' => $timelineBulanan->pluck('total')->all(),
                ],
            ],
        ];

        return view('manajemen-koleksi.dashboard', [
            'totalKoleksi' => $totalKoleksi,
            'koleksiBelumDiperiksa' => $koleksiBelumDiperiksa,
            'kondisiBaik' => $kondisiBaik,
            'kondisiRusakRingan' => $kondisiRusakRingan,
            'kondisiRusakBerat' => $kondisiRusakBerat,
            'menungguJadwal' => $menungguJadwal,
            'perawatanTerlambat' => $perawatanTerlambat,
            'perawatanHariIni' => $perawatanHariIni,
            'perawatanMingguDepan' => $perawatanMingguDepan,
            'perawatanTerjadwal' => $perawatanTerjadwal,
            'perawatanTerjadwalMendatang' => $perawatanTerjadwalMendatang,
            'perawatanMendatang' => $perawatanMendatang,
            'koleksiKritis' => $koleksiKritis,
            'conservationActive' => $conservationActive,
            'chartData' => $chartData,
        ]);
    }
}
