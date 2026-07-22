<?php

namespace App\Http\Controllers;

use App\Models\Koleksi;
use App\Models\KondisiKoleksi;
use App\Models\PerawatanKoleksi;
use App\Notifications\PerawatanCreatedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PerawatanKoleksiController extends Controller
{

    public function index(Request $request): View
    {
        $search       = $request->query('search');
        $statusFilter = $request->query('status');
        $jenisFilter     = $request->query('jenis');
        $prioritasFilter = $request->query('prioritas');
        $tindakanFilter  = $request->query('tindakan');

        $query = PerawatanKoleksi::with([
            'koleksi',
            'kondisiSumber',
            'conservationAction.plan',
            'conservationAction.implementations',
            'conservationAction.result',
        ])
            ->when($search, fn ($q) => $q->where(fn ($inner) => $inner
                ->whereHas('koleksi', fn ($k) => $k->where('nama', 'like', "%{$search}%"))
                ->orWhere('penanggung_jawab', 'like', "%{$search}%")
            ))
            ->when($statusFilter, fn ($q) => $q->where('status', $statusFilter))
            ->when($jenisFilter, fn ($q) => $q->where('jenis_perawatan', $jenisFilter))
            ->when($prioritasFilter, fn ($q) => $q->wherePrioritas($prioritasFilter))
            ->when($tindakanFilter === 'belum', fn ($q) => $q->awaitingConservation());

        $query
            ->orderByRaw("CASE
                WHEN status = 'terjadwal' THEN 0
                WHEN status = 'dibatalkan' THEN 1
                WHEN status = 'selesai' THEN 2
                ELSE 3 END")
            ->orderByDesc('created_at')
            ->orderByDesc('id');

        $perPageOptions = [10, 25, 50, 100];
        $perPage = (int) $request->query('per_page', 10);
        if (! in_array($perPage, $perPageOptions, true)) {
            $perPage = 10;
        }

        $perawatans   = $query->paginate($perPage)->withQueryString();

        $stats = [
            'terjadwal'       => PerawatanKoleksi::scheduled()->count(),
            'terlambat'       => PerawatanKoleksi::overdue()->count(),
            'belum_ditangani' => PerawatanKoleksi::awaitingConservation()->count(),
            'selesai'         => PerawatanKoleksi::where('status', PerawatanKoleksi::STATUS_SELESAI)->count(),
        ];

        return view('perawatan.index', compact(
            'perawatans', 'stats',
            'search', 'statusFilter', 'jenisFilter', 'prioritasFilter', 'tindakanFilter',
            'perPageOptions', 'perPage'
        ));
    }

    public function create(Request $request): View|RedirectResponse
    {
        $selectedKoleksi = $request->query('koleksi_id')
            ? Koleksi::find($request->query('koleksi_id'))
            : null;

        $kondisiKoleksi  = null;
        $prefillJenis    = $request->query('jenis_perawatan');
        $koleksiList = $prefillJenis === PerawatanKoleksi::JENIS_PEMERIKSAAN_ULANG
            ? Koleksi::eligibleForPemeriksaanUlang()->orderBy('nama')->get(['id', 'nama', 'kategori'])
            : Koleksi::eligibleForPemeliharaan()->orderBy('nama')->get(['id', 'nama', 'kategori']);
        $prefillCatatan  = null;
        $prefillFrekuensi = $request->query('frekuensi');
        $prefillJadwalTanggal = $request->query('jadwal_tanggal');
        $prefillPenanggungJawab = $request->query('penanggung_jawab');
        $prefillEstimasiDurasi = $request->query('estimasi_durasi_menit');
        $minJadwalTanggal = today()->toDateString();

        if ($request->query('kondisi_koleksi_id')) {
            $kondisiKoleksi = KondisiKoleksi::with('koleksi')->find($request->query('kondisi_koleksi_id'));

            if ($kondisiKoleksi) {
                if ($kondisiKoleksi->hasJadwalTerjadwal()) {
                    return redirect()
                        ->route('koleksi.kondisi.show', [$kondisiKoleksi->koleksi, $kondisiKoleksi])
                        ->with('error', 'Sudah ada jadwal terjadwal untuk pemeriksaan kondisi ini.');
                }

                $selectedKoleksi = $kondisiKoleksi->koleksi;
                $prefillJenis = $prefillJenis ?: $kondisiKoleksi->getJenisPerawatanDariRekomendasi();
                $prefillCatatan = sprintf(
                    'Tindak lanjut dari pemeriksaan kondisi %s (%s). Rekomendasi: %s.',
                    $kondisiKoleksi->tanggal_periksa->format('d M Y'),
                    $kondisiKoleksi->label_kondisi,
                    $kondisiKoleksi->label_rekomendasi
                );
                $minJadwalTanggal = max(today(), $kondisiKoleksi->tanggal_periksa)->toDateString();
            }
        }

        if ($prefillJadwalTanggal && $prefillJadwalTanggal < $minJadwalTanggal) {
            $prefillJadwalTanggal = $minJadwalTanggal;
        }

        if ($request->boolean('lanjutan_jadwal')) {
            $prefillCatatan = $prefillCatatan ?: 'Jadwal lanjutan dari jadwal sebelumnya.';
        }

        if ($request->query('catatan')) {
            $prefillCatatan = $request->query('catatan');
        }

        return view('perawatan.create', compact(
            'koleksiList',
            'selectedKoleksi',
            'kondisiKoleksi',
            'prefillJenis',
            'prefillCatatan',
            'prefillFrekuensi',
            'prefillJadwalTanggal',
            'prefillPenanggungJawab',
            'prefillEstimasiDurasi',
            'minJadwalTanggal'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePerawatan($request);
        $validated['status'] = PerawatanKoleksi::STATUS_TERJADWAL;
        $validated['created_by'] = $request->user()->id;

        $perawatan = PerawatanKoleksi::create($validated);
        $perawatan->load('koleksi');

        $request->user()->notify(new PerawatanCreatedNotification($perawatan));

        if (! empty($validated['kondisi_koleksi_id'])) {
            $kondisi = KondisiKoleksi::with('koleksi')->find($validated['kondisi_koleksi_id']);

            if ($kondisi) {
                return redirect()
                    ->route('koleksi.kondisi.show', [$kondisi->koleksi, $kondisi])
                    ->with('success', 'Jadwal konservasi berhasil dibuat dari rekomendasi tindak lanjut.');
            }
        }

        return redirect()->route('jadwal-konservasi.index')
            ->with('success', 'Jadwal konservasi berhasil ditambahkan.');
    }

    public function edit(PerawatanKoleksi $perawatan): View
    {
        abort_if($perawatan->status !== 'terjadwal', 403, 'Jadwal yang sudah selesai atau dibatalkan tidak dapat diedit.');
        $koleksiList = Koleksi::orderBy('nama')->get(['id', 'nama', 'kategori']);
        $kondisiSumber = $perawatan->kondisiSumber;
        $minJadwalTanggal = $kondisiSumber
            ? max(today(), $kondisiSumber->tanggal_periksa)->toDateString()
            : today()->toDateString();

        return view('perawatan.edit', compact(
            'perawatan',
            'koleksiList',
            'minJadwalTanggal'
        ));
    }

    public function show(PerawatanKoleksi $perawatan): View
    {
        $perawatan->load([
            'koleksi',
            'kondisiSumber',
            'kondisiHasil',
            'creator',
            'conservationAction.plan',
            'conservationAction.implementations',
            'conservationAction.result',
        ]);

        return view('perawatan.show', compact('perawatan'));
    }

    public function update(Request $request, PerawatanKoleksi $perawatan): RedirectResponse
    {
        abort_if($perawatan->status !== 'terjadwal', 403);

        $validated = $this->validatePerawatan($request, $perawatan);
        $perawatan->update($validated);

        return redirect()->route('jadwal-konservasi.index')
            ->with('success', 'Jadwal konservasi berhasil diperbarui.');
    }

    public function selesai(Request $request, PerawatanKoleksi $perawatan): RedirectResponse
    {
        abort_if($perawatan->status !== 'terjadwal', 403);

        if ($perawatan->isPemeriksaan()) {
            return redirect()->route('koleksi.kondisi.create', [
                'koleksi' => $perawatan->koleksi_id,
                'perawatan_id' => $perawatan->id,
            ]);
        }

        if ($perawatan->requiresConservation()) {
            return redirect()->route('jadwal-konservasi.index')
                ->with('error', 'Jadwal penanganan kerusakan harus diselesaikan melalui alur tindakan konservasi (rencana → pelaksanaan → hasil).');
        }

        $rules = [
            'tanggal_selesai'      => ['required', 'date', 'before_or_equal:today'],
            'catatan_penyelesaian' => ['nullable', 'string', 'max:2000'],
            'kegiatan'             => ['nullable', 'array'],
            'kegiatan.*'           => ['string', 'in:' . implode(',', array_keys(PerawatanKoleksi::KEGIATAN_PEMELIHARAAN))],
            'kondisi'              => ['nullable', 'in:baik,rusak_ringan,rusak_berat'],
            'pemeriksa'            => ['required', 'string', 'max:255'],
            'suhu'                 => ['nullable', 'numeric', 'between:-10,60'],
            'kelembapan'           => ['nullable', 'integer', 'between:0,100'],
            'pencahayaan'          => ['nullable', 'in:rendah,sedang,tinggi'],
            'jenis_kerusakan'      => ['nullable', 'string', 'max:255'],
            'kebersihan_lingkungan'=> ['nullable', 'in:baik,cukup,buruk'],
        ];

        if ($perawatan->isPemeriksaan()) {
            $rules['kondisi'] = ['required', 'in:baik,rusak_ringan,rusak_berat'];
        }

        $validated = $request->validate($rules);

        $kegiatanLabels = collect($validated['kegiatan'] ?? [])
            ->map(fn ($k) => PerawatanKoleksi::KEGIATAN_PEMELIHARAAN[$k] ?? null)
            ->filter()
            ->values();

        $catatanBebas = trim((string) ($validated['catatan_penyelesaian'] ?? ''));
        $catatanPenyelesaian = collect([
            $kegiatanLabels->isNotEmpty() ? 'Kegiatan: ' . $kegiatanLabels->implode(', ') . '.' : null,
            $catatanBebas !== '' ? $catatanBebas : null,
        ])->filter()->implode(' ') ?: null;

        $perawatan->update([
            'status'               => PerawatanKoleksi::STATUS_SELESAI,
            'tanggal_selesai'      => $validated['tanggal_selesai'],
            'catatan_penyelesaian' => $catatanPenyelesaian,
        ]);

        $adaDataLingkungan = filled($validated['suhu'] ?? null)
            || filled($validated['kelembapan'] ?? null)
            || filled($validated['pencahayaan'] ?? null)
            || filled($validated['kebersihan_lingkungan'] ?? null);

        if (!empty($validated['kondisi'])) {
            $kondisi = KondisiKoleksi::create([
                'koleksi_id'         => $perawatan->koleksi_id,
                'perawatan_id'       => $perawatan->id,
                'tanggal_periksa'    => $validated['tanggal_selesai'],
                'kondisi'            => $validated['kondisi'],
                'pemeriksa'          => $validated['pemeriksa'],
                'catatan'            => null,
                'suhu'               => $validated['suhu'] ?? null,
                'kelembapan'         => $validated['kelembapan'] ?? null,
                'pencahayaan'        => $validated['pencahayaan'] ?? null,
                'jenis_kerusakan'    => $validated['jenis_kerusakan'] ?? null,
                'kebersihan_lingkungan'=> $validated['kebersihan_lingkungan'] ?? null,
            ]);

            $perawatan->koleksi->update(['kondisi' => $kondisi->kondisi]);
        } elseif ($perawatan->isPemeliharaan() && ($adaDataLingkungan || $kegiatanLabels->isNotEmpty())) {
            $catatanLog = collect([
                $kegiatanLabels->isNotEmpty()
                    ? 'Pemeliharaan preventif: ' . $kegiatanLabels->implode(', ') . '.'
                    : 'Pemeliharaan preventif.',
                $catatanBebas !== '' ? $catatanBebas : null,
            ])->filter()->implode(' ');

            KondisiKoleksi::create([
                'koleksi_id'           => $perawatan->koleksi_id,
                'perawatan_id'         => $perawatan->id,
                'tanggal_periksa'      => $validated['tanggal_selesai'],
                'kondisi'              => 'baik',
                'pemeriksa'            => $validated['pemeriksa'],
                'catatan'              => $catatanLog,
                'suhu'                 => $validated['suhu'] ?? null,
                'kelembapan'           => $validated['kelembapan'] ?? null,
                'pencahayaan'          => $validated['pencahayaan'] ?? null,
                'kebersihan_lingkungan'=> $validated['kebersihan_lingkungan'] ?? null,
                'rekomendasi_tindak_lanjut' => 'tidak_perlu_tindakan',
            ]);
        }

        $redirect = redirect()->route('jadwal-konservasi.index')
            ->with('success', 'Status jadwal berhasil dicatat selesai.');

        $suggestion = $perawatan->fresh(['koleksi'])->buildNextScheduleSuggestion();

        if ($suggestion) {
            $redirect->with('next_schedule_suggestion', $suggestion);
        }

        return $redirect;
    }

    public function batalkan(Request $request, PerawatanKoleksi $perawatan): RedirectResponse
    {
        abort_if($perawatan->status !== 'terjadwal', 403);

        $validated = $request->validate([
            'alasan_pembatalan' => ['required', 'string', 'max:1000'],
        ]);

        $perawatan->update([
            'status'              => PerawatanKoleksi::STATUS_DIBATALKAN,
            'alasan_pembatalan'   => $validated['alasan_pembatalan'],
        ]);

        return redirect()->route('jadwal-konservasi.index')
            ->with('success', 'Jadwal konservasi berhasil dibatalkan.');
    }

    public function destroy(PerawatanKoleksi $perawatan): RedirectResponse
    {
        $perawatan->delete();

        return redirect()->route('jadwal-konservasi.index')
            ->with('success', 'Jadwal konservasi berhasil dihapus.');
    }

    /** @return array<string, mixed> */
    private function validatePerawatan(Request $request, ?PerawatanKoleksi $existing = null): array
    {
        $validated = $request->validate([
            'koleksi_id'               => ['required', 'exists:koleksis,id'],
            'kondisi_koleksi_id'       => ['nullable', 'exists:kondisi_koleksis,id'],
            'jenis_perawatan'          => ['required', 'in:' . implode(',', array_keys(PerawatanKoleksi::JENIS_OPTIONS))],
            'jadwal_tanggal'           => ['required', 'date'],
            'frekuensi'                => ['required', 'in:sekali,bulanan,triwulan,tahunan'],
            'estimasi_durasi_menit'    => ['nullable', 'integer', 'min:15', 'max:1440'],
            'penanggung_jawab'         => ['required', 'string', 'max:255'],
            'catatan'                  => ['nullable', 'string', 'max:2000'],
        ]);

        $kondisiKoleksiId = $validated['kondisi_koleksi_id']
            ?? $existing?->kondisi_koleksi_id;

        $requiresKondisiSumber = $validated['jenis_perawatan'] === 'penanganan_kerusakan';

        if ($requiresKondisiSumber && ! $kondisiKoleksiId) {
            throw ValidationException::withMessages([
                'kondisi_koleksi_id' => 'Jadwal penanganan kerusakan harus dibuat dari rekomendasi pemeriksaan kondisi (tombol "Buat Jadwal"), agar penyelesaiannya dapat melalui alur tindakan konservasi: rencana → pelaksanaan → hasil.',
            ]);
        }

        if ($validated['jenis_perawatan'] === PerawatanKoleksi::JENIS_PEMELIHARAAN && ! $kondisiKoleksiId) {
            $eligible = Koleksi::where('id', $validated['koleksi_id'])
                ->eligibleForPemeliharaan()
                ->exists();

            if (! $eligible) {
                throw ValidationException::withMessages([
                    'koleksi_id' => 'Koleksi yang dipilih harus dalam kondisi baik dan tidak boleh memiliki jadwal konservasi terjadwal.',
                ]);
            }
        }

        if ($validated['jenis_perawatan'] === PerawatanKoleksi::JENIS_PEMERIKSAAN_ULANG && ! $kondisiKoleksiId) {
            $eligible = Koleksi::where('id', $validated['koleksi_id'])
                ->eligibleForPemeriksaanUlang()
                ->exists();

            if (! $eligible) {
                throw ValidationException::withMessages([
                    'koleksi_id' => 'Koleksi yang dipilih harus dalam kondisi rusak ringan dan tidak boleh memiliki jadwal konservasi terjadwal.',
                ]);
            }
        }

        $minDate = today();

        if ($kondisiKoleksiId) {
            $kondisi = KondisiKoleksi::find($kondisiKoleksiId);

            if (! $kondisi || (int) $kondisi->koleksi_id !== (int) $validated['koleksi_id']) {
                throw ValidationException::withMessages([
                    'kondisi_koleksi_id' => 'Pemeriksaan kondisi tidak sesuai dengan koleksi yang dipilih.',
                ]);
            }

            $duplicateQuery = PerawatanKoleksi::where('kondisi_koleksi_id', $kondisi->id)
                ->where('status', PerawatanKoleksi::STATUS_TERJADWAL);

            if ($existing) {
                $duplicateQuery->where('id', '!=', $existing->id);
            }

            if ($duplicateQuery->exists()) {
                throw ValidationException::withMessages([
                    'kondisi_koleksi_id' => 'Sudah ada jadwal terjadwal untuk pemeriksaan kondisi ini.',
                ]);
            }

            $minDate = max($minDate, $kondisi->tanggal_periksa);
            $validated['kondisi_koleksi_id'] = $kondisi->id;
        } else {
            unset($validated['kondisi_koleksi_id']);
        }

        if ($validated['jadwal_tanggal'] < $minDate->toDateString()) {
            throw ValidationException::withMessages([
                'jadwal_tanggal' => sprintf(
                    'Tanggal jadwal minimal %s.',
                    $minDate->format('d M Y')
                ),
            ]);
        }

        return $validated;
    }
}
