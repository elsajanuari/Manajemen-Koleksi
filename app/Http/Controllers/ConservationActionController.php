<?php

namespace App\Http\Controllers;

use App\Models\ConservationAction;
use App\Models\ConservationImplementation;
use App\Models\Koleksi;
use App\Models\KondisiKoleksi;
use App\Models\PerawatanKoleksi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ConservationActionController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $statusFilter = $request->query('status');

        $query = ConservationAction::with(['koleksi', 'perawatanKoleksi'])
            ->when($search, fn ($q) => $q->whereHas('koleksi', fn ($k) =>
                $k->where('nama', 'like', "%{$search}%")
                    ->orWhere('nomor_inventaris', 'like', "%{$search}%")
            ))
            ->when($statusFilter, fn ($q) => $q->where('status', $statusFilter))
            ->orderByDesc('created_at');

        $perPageOptions = [10, 25, 50, 100];
        $perPage = (int) $request->query('per_page', 10);
        if (! in_array($perPage, $perPageOptions, true)) {
            $perPage = 10;
        }

        $actions = $query->paginate($perPage)->withQueryString();

        return view('konservasi.tindakan.index', compact(
            'actions',
            'search',
            'statusFilter',
            'perPageOptions',
            'perPage'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'koleksi_id' => ['required', 'exists:koleksis,id'],
            'kondisi_koleksi_id' => ['required', 'exists:kondisi_koleksis,id'],
            'perawatan_koleksi_id' => ['required', 'exists:perawatan_koleksis,id'],
        ]);

        $perawatan = PerawatanKoleksi::findOrFail($validated['perawatan_koleksi_id']);
        
        abort_if($perawatan->jenis_perawatan !== 'penanganan_kerusakan', 403);

        $existing = ConservationAction::where('perawatan_koleksi_id', $perawatan->id)->first();
        if ($existing) {
            return redirect()->route('konservasi.tindakan.show', $existing);
        }

        $action = ConservationAction::create([
            'koleksi_id' => $validated['koleksi_id'],
            'kondisi_koleksi_id' => $validated['kondisi_koleksi_id'],
            'perawatan_koleksi_id' => $validated['perawatan_koleksi_id'],
            'jenis_konservasi' => ConservationAction::TYPE_KURATIF,
            'status' => ConservationAction::STATUS_DIRENCANAKAN,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('konservasi.tindakan.show', $action);
    }

    public function show(ConservationAction $action): View
    {
        $action->load(['koleksi', 'kondisiKoleksi', 'perawatanKoleksi', 'plan', 'implementations', 'result']);

        return view('konservasi.tindakan.show', compact('action'));
    }

    public function plan(ConservationAction $action): View
    {
        $action->load(['koleksi', 'kondisiKoleksi', 'perawatanKoleksi', 'plan']);

        return view('konservasi.tindakan.plan', compact('action'));
    }

    public function storePlan(Request $request, ConservationAction $action): RedirectResponse
    {
        $validated = $request->validate([
            'jenis_tindakan' => ['required', 'string', 'max:255'],
            'deskripsi_tindakan' => ['required', 'string', 'max:4000'],
            'bahan_material' => ['nullable', 'string', 'max:2000'],
            'target_penyelesaian' => ['required', 'date'],
            'catatan' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($action->plan) {
            $action->plan->update($validated);
        } else {
            $action->plan()->create($validated);
        }

        if ($action->status === ConservationAction::STATUS_DIRENCANAKAN) {
            $action->update(['status' => ConservationAction::STATUS_DIRENCANAKAN]);
        }

        return redirect()->route('konservasi.tindakan.index')
            ->with('success', 'Rencana konservasi berhasil disimpan.');
    }

    public function pelaksanaan(ConservationAction $action): View
    {
        $action->load(['koleksi', 'kondisiKoleksi', 'perawatanKoleksi', 'implementations']);

        return view('konservasi.tindakan.pelaksanaan', compact('action'));
    }

    public function storeImplementation(Request $request, ConservationAction $action): RedirectResponse
    {
        if ($action->implementations()->exists()) {
            return redirect()->route('konservasi.tindakan.pelaksanaan', $action)
                ->with('error', 'Tindakan konservasi ini sudah memiliki satu catatan pelaksanaan. Untuk konservasi ulang, buat jadwal dan tindakan konservasi baru.');
        }

        $validated = $request->validate([
            'tanggal_pelaksanaan' => ['required', 'date'],
            'petugas' => ['required', 'string', 'max:255'],
            'durasi' => ['nullable', 'string', 'max:255'],
            'catatan_pelaksanaan' => ['required', 'string', 'max:6000'],
            'foto_proses' => ['required', 'image', 'max:4096'],
            'catatan_perubahan' => ['nullable', 'string', 'max:2000'],
        ], [
            'foto_proses.required' => 'Foto proses wajib diunggah.',
        ]);

        $validated['foto_proses'] = $request->file('foto_proses')->store('conservation', 'public');

        $implementation = $action->implementations()->create($validated);

        if ($action->status === ConservationAction::STATUS_DIRENCANAKAN) {
            $action->update(['status' => ConservationAction::STATUS_SEDANG_BERJALAN]);
        }

        $this->syncPerawatanStatusFromImplementation($action, $implementation);

        return redirect()->route('konservasi.tindakan.index')
            ->with('success', 'Catatan pelaksanaan berhasil ditambahkan.');
    }

    public function updateImplementation(Request $request, ConservationAction $action): RedirectResponse
    {
        $implementation = $action->implementations()->first();

        if (! $implementation) {
            return redirect()->route('konservasi.tindakan.pelaksanaan', $action)
                ->with('error', 'Tidak ada catatan pelaksanaan untuk diperbarui.');
        }

        $validated = $request->validate([
            'tanggal_pelaksanaan' => ['required', 'date'],
            'petugas' => ['required', 'string', 'max:255'],
            'durasi' => ['nullable', 'string', 'max:255'],
            'catatan_pelaksanaan' => ['required', 'string', 'max:6000'],
            'foto_proses' => [($implementation->foto_proses ? 'nullable' : 'required'), 'image', 'max:4096'],
            'catatan_perubahan' => ['nullable', 'string', 'max:2000'],
        ], [
            'foto_proses.required' => 'Foto proses wajib diunggah.',
            'foto_proses.image' => 'Foto proses harus berupa file gambar.',
        ]);

        if ($request->hasFile('foto_proses')) {
            $validated['foto_proses'] = $request->file('foto_proses')->store('conservation', 'public');
        }

        $implementation->update($validated);

        $this->syncPerawatanStatusFromImplementation($action, $implementation);

        return redirect()->route('konservasi.tindakan.pelaksanaan', $action)
            ->with('success', 'Catatan pelaksanaan berhasil diperbarui.');
    }

    private function syncPerawatanStatusFromImplementation(ConservationAction $action, ConservationImplementation $implementation): void
    {
        $perawatan = $action->perawatanKoleksi;

        if (! $perawatan) {
            return;
        }

        $perawatan->syncStatusFromImplementation($implementation->tanggal_pelaksanaan);
    }

    public function hasil(ConservationAction $action): View
    {
        $action->load(['koleksi', 'kondisiKoleksi', 'perawatanKoleksi', 'result']);

        return view('konservasi.tindakan.hasil', compact('action'));
    }

    public function storeResult(Request $request, ConservationAction $action): RedirectResponse
    {
        $validated = $request->validate([
            'kondisi_setelah' => ['required', 'in:baik,rusak_ringan,rusak_berat'],
            'foto_setelah' => [($action->result?->foto_setelah ? 'nullable' : 'required'), 'image', 'max:4096'],
            'rekomendasi_penyimpanan' => ['nullable', 'string', 'max:2000'],
            'rekomendasi_penanganan_khusus' => ['nullable', 'string', 'max:2000'],
            'catatan_akhir' => ['nullable', 'string', 'max:3000'],
        ]);

        $evaluasi = match ($validated['kondisi_setelah']) {
            'baik' => 'berhasil',
            'rusak_ringan' => 'sebagian_berhasil',
            'rusak_berat' => 'perlu_tindak_lanjut',
        };

        $validated['evaluasi'] = $evaluasi;

        if ($request->hasFile('foto_setelah')) {
            $validated['foto_setelah'] = $request->file('foto_setelah')->store('conservation', 'public');
        }

        if ($action->result) {
            $action->result->update($validated);
        } else {
            $action->result()->create($validated);
        }

        $action->update(['status' => ConservationAction::STATUS_SELESAI]);

        $this->createKondisiKoleksiFromConservationResult($action, $validated, $validated['kondisi_setelah'], $evaluasi);

        if ($evaluasi === 'berhasil') {
            $this->applyKondisiSetelahKonservasi($action, 'baik', restoreSewa: true);
        } else {
            $this->applyKondisiSetelahKonservasi($action, $validated['kondisi_setelah'], restoreSewa: false);
        }

        $perawatan = $action->perawatanKoleksi;
        $routineSuggestion = null;
        $followUpSuggestion = null;

        if ($perawatan && $perawatan->isScheduled()) {
            $perawatan->update([
                'status'               => PerawatanKoleksi::STATUS_SELESAI,
                'tanggal_selesai'      => today(),
                'catatan_penyelesaian' => match ($evaluasi) {
                    'berhasil' => 'Konservasi berhasil. Kondisi koleksi diperbarui dan status sewa/beli dipulihkan.',
                    'sebagian_berhasil' => 'Konservasi sebagian berhasil. Kondisi koleksi diperbarui, status sewa/beli tetap terkunci.',
                    'perlu_tindak_lanjut' => 'Siklus konservasi selesai. Jadwal tindak lanjut disarankan.',
                },
            ]);

            if ($evaluasi === 'berhasil') {
                $routineSuggestion = $perawatan->fresh(['koleksi'])->buildNextScheduleSuggestion();
            }

            if ($evaluasi === 'perlu_tindak_lanjut') {
                $followUpSuggestion = PerawatanKoleksi::buildConservationFollowUpSuggestion($action, $validated);
            }
        }

        if ($followUpSuggestion) {
            return redirect()->route('jadwal-konservasi.index')
                ->with('success', 'Hasil konservasi disimpan. Buat jadwal penanganan kerusakan lanjutan untuk siklus konservasi berikutnya.')
                ->with('conservation_follow_up_suggestion', $followUpSuggestion);
        }

        if ($routineSuggestion) {
            return redirect()->route('jadwal-konservasi.index')
                ->with('success', 'Hasil konservasi berhasil disimpan. Status jadwal konservasi dicatat selesai.')
                ->with('next_schedule_suggestion', $routineSuggestion);
        }

        $successMessage = match ($evaluasi) {
            'sebagian_berhasil' => 'Hasil konservasi disimpan. Kondisi koleksi diperbarui, status sewa/beli tetap terkunci.',
            default => 'Hasil konservasi berhasil disimpan.',
        };

        return redirect()->route('konservasi.tindakan.index')
            ->with('success', $successMessage);
    }

    private function createKondisiKoleksiFromConservationResult(ConservationAction $action, array $validated, string $kondisiSetelah, string $evaluasi): void
    {
        $koleksi = $action->koleksi;

        if (! $koleksi) {
            return;
        }

        $inspectionData = [
            'koleksi_id' => $koleksi->id,
            'perawatan_id' => $action->perawatan_koleksi_id,
            'tanggal_periksa' => today(),
            'kondisi' => $kondisiSetelah,
            'pemeriksa' => auth()->user()?->name ?: 'System',
            'catatan' => $validated['catatan_akhir'] ?? null,
            'rekomendasi_tindak_lanjut' => $evaluasi === 'perlu_tindak_lanjut' ? 'penanganan_kerusakan' : 'tidak_perlu_tindakan',
        ];

        if (isset($validated['foto_setelah'])) {
            $inspectionData['foto_kondisi_saat_ini'] = $validated['foto_setelah'];
        }

        KondisiKoleksi::create($inspectionData);
    }

    private function applyKondisiSetelahKonservasi(ConservationAction $action, string $kondisiSetelah, bool $restoreSewa): void
    {
        $koleksi = $action->koleksi;

        if (! $koleksi) {
            return;
        }

        $koleksi->kondisi = $kondisiSetelah;

        if ($restoreSewa) {
            $sumber = $action->kondisiKoleksi;

            if ($sumber
                && $sumber->previous_status_sewa
                && $koleksi->status_sewa === 'tidak'
                && in_array($sumber->previous_status_sewa, ['tidak', 'sewa', 'beli', 'sewa_beli'], true)) {
                $koleksi->status_sewa = $sumber->previous_status_sewa;
            }
        }

        $koleksi->save();
    }
}
