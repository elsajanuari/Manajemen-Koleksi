<?php

namespace App\Http\Controllers;

use App\Models\Koleksi;
use App\Models\KondisiKoleksi;
use App\Models\PerawatanKoleksi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class KondisiKoleksiController extends Controller
{
    public function index(Request $request): View
    {
        $perPageOptions = [10, 20, 50, 100];
        $perPage = (int) $request->query('per_page', 20);

        if (! in_array($perPage, $perPageOptions, true)) {
            $perPage = 20;
        }

        $search = $request->query('search');
        $kondisiFilter = $request->query('kondisi');
        $rekomendasiFilter = $request->query('rekomendasi');

        $kondisiKoleksis = KondisiKoleksi::with('koleksi')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('pemeriksa', 'like', "%{$search}%")
                        ->orWhereHas('koleksi', fn ($koleksi) => $koleksi
                            ->where('nama', 'like', "%{$search}%")
                            ->orWhere('nomor_inventaris', 'like', "%{$search}%"));
                });
            })
            ->when($kondisiFilter, fn ($query) => $query->where('kondisi', $kondisiFilter))
            ->when($rekomendasiFilter, fn ($query) => $query->where('rekomendasi_tindak_lanjut', $rekomendasiFilter))
            ->orderByDesc('tanggal_periksa')
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        return view('kondisi.index', compact(
            'kondisiKoleksis',
            'perPageOptions',
            'perPage',
            'search',
            'kondisiFilter',
            'rekomendasiFilter',
        ));
    }

    public function create(Request $request, Koleksi $koleksi): View
    {
        $previousInspection = $koleksi->kondisis()->first();
        $perawatan = $this->resolveScheduledPemeriksaanUlang($request, $koleksi);

        return view('kondisi.create', compact('koleksi', 'previousInspection', 'perawatan'));
    }

    public function store(Request $request, Koleksi $koleksi): RedirectResponse
    {
        $perawatan = $this->resolveScheduledPemeriksaanUlang($request, $koleksi);

        $validated = $request->validate(
            $this->kondisiValidationRules($request),
            $this->kondisiValidationMessages()
        );

        $validated['koleksi_id'] = $koleksi->id;

        if ($perawatan) {
            $validated['perawatan_id'] = $perawatan->id;
        }

        if ($request->hasFile('foto_kondisi_saat_ini')) {
            $validated['foto_kondisi_saat_ini'] = $request->file('foto_kondisi_saat_ini')->store('kondisi', 'public');
        }
        if ($request->hasFile('foto_kerusakan')) {
            $validated['foto_kerusakan'] = $request->file('foto_kerusakan')->store('kondisi', 'public');
        }

        $kondisi = KondisiKoleksi::create($validated);
        $koleksi->update(['kondisi' => $kondisi->kondisi]);

        if ($perawatan) {
            $perawatan->completeFromPemeriksaan($kondisi);

            $redirect = redirect()->route('jadwal-konservasi.index')
                ->with('success', 'Pemeriksaan kondisi berhasil dicatat dan jadwal pemeriksaan ulang ditandai selesai.');

            $suggestion = $perawatan->fresh(['koleksi'])->buildNextScheduleSuggestion();

            if ($suggestion) {
                $redirect->with('next_schedule_suggestion', $suggestion);
            }

            return $redirect;
        }

        return redirect()
            ->route('koleksi.show', $koleksi)
            ->with('success', 'Pemeriksaan kondisi berhasil dicatat.');
    }

    public function edit(Koleksi $koleksi, KondisiKoleksi $kondisi): View
    {
        abort_if($kondisi->koleksi_id !== $koleksi->id, 404);

        $previousInspection = $kondisi->getPreviousInspection();

        return view('kondisi.edit', compact('koleksi', 'kondisi', 'previousInspection'));
    }

    public function show(Koleksi $koleksi, KondisiKoleksi $kondisi): View
    {
        abort_if($kondisi->koleksi_id !== $koleksi->id, 404);

        $kondisi->load([
            'jadwalRekomendasi.conservationAction.plan',
            'jadwalRekomendasi.conservationAction.implementations',
            'jadwalRekomendasi.conservationAction.result',
        ]);

        return view('kondisi.show', compact('koleksi', 'kondisi'));
    }

    public function update(Request $request, Koleksi $koleksi, KondisiKoleksi $kondisi): RedirectResponse
    {
        abort_if($kondisi->koleksi_id !== $koleksi->id, 404);

        $validated = $request->validate(
            $this->kondisiValidationRules($request, $kondisi),
            $this->kondisiValidationMessages()
        );

        if ($request->hasFile('foto_kondisi_saat_ini')) {
            if ($kondisi->foto_kondisi_saat_ini) {
                Storage::disk('public')->delete($kondisi->foto_kondisi_saat_ini);
            }
            $validated['foto_kondisi_saat_ini'] = $request->file('foto_kondisi_saat_ini')->store('kondisi', 'public');
        }
        if ($request->hasFile('foto_kerusakan')) {
            if ($kondisi->foto_kerusakan) {
                Storage::disk('public')->delete($kondisi->foto_kerusakan);
            }
            $validated['foto_kerusakan'] = $request->file('foto_kerusakan')->store('kondisi', 'public');
        }

        $kondisi->update($validated);
        $koleksi->update(['kondisi' => $kondisi->kondisi]);

        return redirect()
            ->route('koleksi.show', $koleksi)
            ->with('success', 'Catatan pemeriksaan berhasil diperbarui.');
    }

    public function destroy(Koleksi $koleksi, KondisiKoleksi $kondisi): RedirectResponse
    {
        abort_if($kondisi->koleksi_id !== $koleksi->id, 404);

        if ($kondisi->foto) {
            Storage::disk('public')->delete($kondisi->foto);
        }
        if ($kondisi->foto_sebelum) {
            Storage::disk('public')->delete($kondisi->foto_sebelum);
        }
        if ($kondisi->foto_kondisi_saat_ini) {
            Storage::disk('public')->delete($kondisi->foto_kondisi_saat_ini);
        }
        if ($kondisi->foto_kerusakan) {
            Storage::disk('public')->delete($kondisi->foto_kerusakan);
        }

        $kondisi->delete();

        $freshKondisi = $koleksi->kondisiTerakhir()->first();
        $koleksi->update(['kondisi' => $freshKondisi?->kondisi]);

        return redirect()
            ->route('koleksi.show', $koleksi)
            ->with('success', 'Catatan pemeriksaan berhasil dihapus.');
    }

    /** @return array<string, mixed> */
    private function kondisiValidationRules(Request $request, ?KondisiKoleksi $existing = null): array
    {
        $kondisi = $request->input('kondisi');
        $isRusak = in_array($kondisi, ['rusak_ringan', 'rusak_berat'], true);

        $fotoKondisiRules = $existing?->foto_kondisi_saat_ini
            ? ['nullable', 'image', 'max:4096']
            : ['required', 'image', 'max:4096'];

        $fotoKerusakanRules = ['nullable', 'image', 'max:4096'];
        if ($isRusak && ! $existing?->foto_kerusakan) {
            $fotoKerusakanRules = ['required', 'image', 'max:4096'];
        }

        return [
            'tanggal_periksa'     => ['required', 'date'],
            'kondisi'             => ['required', 'in:baik,rusak_ringan,rusak_berat'],
            'pemeriksa'           => ['required', 'string', 'max:255'],
            'catatan'             => ['nullable', 'string', 'max:2000'],
            'suhu'                => ['nullable', 'numeric', 'between:-10,60'],
            'kelembapan'          => ['nullable', 'integer', 'between:0,100'],
            'pencahayaan'         => ['nullable', 'in:rendah,sedang,tinggi'],
            'jenis_kerusakan'     => ['nullable', 'string', 'max:255'],
            'kebersihan_lingkungan'=> ['nullable', 'in:baik,cukup,buruk'],
            'foto_kondisi_saat_ini' => $fotoKondisiRules,
            'foto_kerusakan'      => $fotoKerusakanRules,
            'rekomendasi_tindak_lanjut' => [
                Rule::requiredIf(fn () => in_array($kondisi, ['rusak_ringan', 'rusak_berat'], true)),
                'nullable',
                Rule::in($this->allowedRekomendasiForKondisi($kondisi)),
            ],
            'perawatan_id' => ['nullable', 'integer', 'exists:perawatan_koleksis,id'],
        ];
    }

    private function resolveScheduledPemeriksaanUlang(Request $request, Koleksi $koleksi): ?PerawatanKoleksi
    {
        $perawatanId = $request->input('perawatan_id') ?? $request->query('perawatan_id');

        if (! $perawatanId) {
            return null;
        }

        $perawatan = PerawatanKoleksi::query()
            ->whereKey($perawatanId)
            ->where('koleksi_id', $koleksi->id)
            ->firstOrFail();

        abort_if(
            $perawatan->status !== PerawatanKoleksi::STATUS_TERJADWAL,
            403,
            'Jadwal pemeriksaan ulang ini sudah tidak aktif.'
        );
        abort_if(
            ! $perawatan->isPemeriksaan(),
            403,
            'Jadwal yang dipilih bukan pemeriksaan ulang.'
        );

        return $perawatan;
    }

    /**
     *
     * @return array<int, string>
     */
    private function allowedRekomendasiForKondisi(?string $kondisi): array
    {
        return match ($kondisi) {
            'baik'         => ['tidak_perlu_tindakan', 'pemeliharaan', 'pemeriksaan_ulang'],
            'rusak_ringan' => ['penanganan_kerusakan', 'pemeriksaan_ulang'],
            'rusak_berat'  => ['penanganan_kerusakan'],
            default        => array_keys(KondisiKoleksi::REKOMENDASI_OPTIONS),
        };
    }

    /** @return array<string, string> */
    private function kondisiValidationMessages(): array
    {
        return [
            'rekomendasi_tindak_lanjut.in' => 'Rekomendasi tindak lanjut tidak sesuai dengan kondisi koleksi yang dipilih.',
            'rekomendasi_tindak_lanjut.required' => 'Rekomendasi tindak lanjut wajib dipilih untuk kondisi rusak.',
            'foto_kondisi_saat_ini.required' => 'Foto kondisi saat ini wajib diunggah.',
            'foto_kerusakan.required' => 'Foto detail kerusakan wajib diunggah untuk kondisi rusak.',
        ];
    }
}