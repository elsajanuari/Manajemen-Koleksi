<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Services\DocumentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\TrackingService;

class SerahTerimaPembelianController extends Controller
{
    // ──────────────────────────────────────────────────────────────────
    //  PENGELOLA: Tahap 1 — Isi info pengiriman (pembayaran_berhasil → siap_diserahkan)
    // ──────────────────────────────────────────────────────────────────

    public function updateDeliveryInfo(Request $request, Pembelian $pembelian): RedirectResponse
    {
        $this->authorizePengelola();

        if ($pembelian->status !== 'pembayaran_berhasil') {
            return redirect()->back()->with('error', 'Pengiriman hanya bisa diisi setelah pembayaran berhasil.');
        }

        $isKurir = $pembelian->shipping_method_type === 'courier';

        // Validasi berbeda tergantung metode pengiriman
        $rules = [
            'delivery_method'          => ['required', 'string', 'max:255'],
            'delivery_officer'         => ['required', 'string', 'max:255'],
            'delivery_location'        => ['required', 'string', 'max:1000'],
            'recipient_name'           => ['required', 'string', 'max:255'],
            'delivery_notes'           => ['nullable', 'string', 'max:2000'],
            'dispatch_front_photo'     => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:5120'],
            'dispatch_back_photo'      => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:5120'],
            'dispatch_packing_photos'  => ['required', 'array', 'min:1', 'max:5'],
            'dispatch_packing_photos.*'=> ['required', 'file', 'mimes:jpg,jpeg,png', 'max:5120'],
            'dispatch_video'           => ['nullable', 'file', 'mimes:mp4,mov,avi', 'max:51200'],
        ];

        if ($isKurir) {
            // Kurir: nomor resi wajib, tidak ada rencana tanggal
            $rules['delivery_tracking_number'] = ['required', 'string', 'max:100'];
        } else {
            // Pengelola: nomor resi tidak ada, rencana tanggal opsional
            $rules['delivery_scheduled_at'] = ['nullable', 'date'];
        }

        $data = $request->validate($rules, [
            'dispatch_front_photo.required'    => 'Foto depan koleksi saat dikirim wajib diunggah.',
            'dispatch_back_photo.required'     => 'Foto belakang koleksi saat dikirim wajib diunggah.',
            'dispatch_packing_photos.required' => 'Foto packing wajib diunggah minimal 1.',
        ]);

        unset($data['dispatch_video']); // bersihkan, bukan kolom DB — sudah diwakili dispatch_video_path
        // Upload dokumentasi kondisi koleksi saat dikirim — dipindah ke sini,
        // berlaku untuk kurir maupun pengelola
        $dispatchFrontPath = $request->file('dispatch_front_photo')
            ->store('pembelian/dispatch/kondisi', 'public');
        $dispatchBackPath = $request->file('dispatch_back_photo')
            ->store('pembelian/dispatch/kondisi', 'public');

        $dispatchPackingPaths = [];
        foreach ($request->file('dispatch_packing_photos') as $file) {
            $dispatchPackingPaths[] = $file->store('pembelian/dispatch/packing', 'public');
        }

        $dispatchVideoPath = null;
        if ($request->hasFile('dispatch_video')) {
            $dispatchVideoPath = $request->file('dispatch_video')
                ->store('pembelian/dispatch/video', 'public');
        }

        if ($isKurir) {
            // Hitung estimasi tiba dari courier_etd yang disimpan saat verifikasi
            $scheduledAt = null;
            if ($pembelian->courier_etd) {
                preg_match('/(\d+)/', $pembelian->courier_etd, $matches);
                $etdDays = isset($matches[1]) ? (int) $matches[1] : null;
                if ($etdDays) {
                    $scheduledAt = now()->addDays($etdDays);
                }
            }
            // Fallback: estimasi default per kurir jika courier_etd tidak tersimpan
            if (! $scheduledAt) {
                $courierName = strtolower($pembelian->courier_name ?? '');
                $defaultEtd = match(true) {
                    str_contains($courierName, 'tiki') => 3,
                    str_contains($courierName, 'jne')  => 3,
                    str_contains($courierName, 'jnt')  => 3,
                    str_contains($courierName, 'sicepat') => 2,
                    str_contains($courierName, 'anteraja') => 2,
                    str_contains($courierName, 'pos')  => 5,
                    default => 4,
                };
                $scheduledAt = now()->addDays($defaultEtd);
            }

            // Kurir: satu langkah — langsung dalam_pengiriman + catat shipped_at
            $pembelian->update(array_merge($data, [
                'status'                => 'dalam_pengiriman',
                'shipped_at'            => now(),
                'delivery_scheduled_at' => $scheduledAt,
                'dispatch_front_photo'     => $dispatchFrontPath,
                'dispatch_back_photo'      => $dispatchBackPath,
                'dispatch_packing_photos'  => $dispatchPackingPaths,
                'dispatch_video_path'      => $dispatchVideoPath,
            ]));

            return redirect()->route('pengelola.pembelian.serah-terima', $pembelian)
                ->with('success', 'Koleksi berhasil dicatat sebagai sudah dikirim via kurir. Menunggu konfirmasi pembeli.');
        } else {
            // Pengelola: dua langkah — simpan dulu, status siap_diserahkan
            $pembelian->update(array_merge($data, [
                'status' => 'siap_diserahkan',
                'dispatch_front_photo'     => $dispatchFrontPath,
                'dispatch_back_photo'      => $dispatchBackPath,
                'dispatch_packing_photos'  => $dispatchPackingPaths,
                'dispatch_video_path'      => $dispatchVideoPath,
            ]));

            return redirect()->route('pengelola.pembelian.serah-terima', $pembelian)
                ->with('success', 'Informasi pengiriman berhasil disimpan. Tandai koleksi sudah dikirim saat sudah berangkat.');
        }
    }

    // ──────────────────────────────────────────────────────────────────
    //  PENGELOLA: Tahap 2 — Tandai sudah dikirim (siap_diserahkan → dalam_pengiriman)
    // ──────────────────────────────────────────────────────────────────

    public function markAsShipped(Pembelian $pembelian): RedirectResponse
    {
        $this->authorizePengelola();

        if ($pembelian->status !== 'siap_diserahkan') {
            return redirect()->back()->with('error', 'Koleksi harus dalam status siap diserahkan terlebih dahulu.');
        }

        $pembelian->update([
            'status'     => 'dalam_pengiriman',
            'shipped_at' => now(),
        ]);

        return redirect()->route('pengelola.pembelian.serah-terima', $pembelian)
            ->with('success', 'Koleksi ditandai sudah dikirim. Menunggu konfirmasi pembeli.');
    }

    // ──────────────────────────────────────────────────────────────────
    //  PENGELOLA: Update status pengiriman manual (khusus metode pengelola)
    // ──────────────────────────────────────────────────────────────────

    // ──────────────────────────────────────────────────────────────────
    //  PENGELOLA: Update status pengiriman manual (khusus metode pengelola)
    //  DILENGKAPI: validasi urutan, update status pembelian, dan sinkronisasi
    // ──────────────────────────────────────────────────────────────────

    public function updateManagerDeliveryStatus(Request $request, Pembelian $pembelian): RedirectResponse
    {
        $this->authorizePengelola();

        $request->validate([
            'manager_delivery_status' => ['required', 'string', 'in:dikemas,siap_dikirim,dalam_perjalanan,tiba_di_tujuan'],
            'catatan_status'          => ['nullable', 'string', 'max:500'],
        ]);

        $statusLabel = match($request->manager_delivery_status) {
            'dikemas'          => 'Sedang Dikemas',
            'siap_dikirim'     => 'Siap Dikirim',
            'dalam_perjalanan' => 'Dalam Perjalanan',
            'tiba_di_tujuan'   => 'Tiba di Tujuan',
        };

        // Validasi urutan status (tidak boleh mundur)
        $statusOrder = ['dikemas', 'siap_dikirim', 'dalam_perjalanan', 'tiba_di_tujuan'];
        $currentStatus = $pembelian->manager_delivery_status ?? null;
        $currentIndex = $currentStatus ? array_search($currentStatus, $statusOrder) : -1;
        $newIndex = array_search($request->manager_delivery_status, $statusOrder);

        if ($currentStatus && $newIndex < $currentIndex) {
            return redirect()->back()->with('error', 'Status tidak bisa mundur ke tahap sebelumnya.');
        }

        if ($currentStatus && $newIndex > $currentIndex + 1) {
            return redirect()->back()->with('error', 'Harap update status secara berurutan. Langkah selanjutnya: ' . $statusLabel);
        }

        // Jika ini adalah update pertama (dari null ke dikemas)
        $isFirstUpdate = !$currentStatus && $request->manager_delivery_status === 'dikemas';

        // Ambil timeline lama, tambahkan entry baru
        $timeline = $pembelian->manager_delivery_timeline ?? [];
        $timeline[] = [
            'status'    => $request->manager_delivery_status,
            'label'     => $statusLabel,
            'catatan'   => $request->catatan_status,
            'timestamp' => now()->toDateTimeString(),
            'by'        => auth()->user()->name,
        ];

        $updateData = [
            'manager_delivery_status'   => $request->manager_delivery_status,
            'manager_delivery_timeline' => $timeline,
        ];

        // Jika pertama kali update (dikemas), catat shipped_at dan ubah status pembelian
        if ($isFirstUpdate) {
            $updateData['shipped_at'] = now();
            $updateData['status'] = 'dalam_pengiriman';
        }

        // Jika status sudah tiba di tujuan, update status pembelian ke menunggu dokumen serah terima

        $pembelian->update($updateData);

        $message = "Status pengiriman diperbarui: " . $statusLabel;
        if ($request->manager_delivery_status === 'tiba_di_tujuan') {
            $message .= ". Koleksi telah tiba di tujuan. Menunggu konfirmasi penerimaan dari pembeli.";
        } elseif ($isFirstUpdate) {
            $message .= " Pengiriman dimulai. Pembeli akan mendapat notifikasi.";
        }

        return redirect()->route('pengelola.pembelian.serah-terima', $pembelian)
            ->with('success', $message);
    }

    public function trackingData(Pembelian $pembelian)
    {
        $user = auth()->user();
        if ($user->role !== 'pengelola' && $user->id !== $pembelian->user_id) {
            abort(403);
        }

        $isReturn = request()->get('for') === 'return';
        $binderbyte = app(\App\Services\BinderbyteService::class);
        $refresh    = request()->boolean('refresh');

        if ($isReturn) {
            if (! $pembelian->return_shipment_tracking || ! $pembelian->return_shipment_method) {
                return response()->json(['success' => false, 'message' => 'Data resi atau kurir pengiriman balik belum tersedia.']);
            }

            $trackingNumber = $pembelian->return_shipment_tracking;
            $courier        = $pembelian->return_shipment_method;
        } else {
            if ($pembelian->shipping_method_type !== 'courier') {
                return response()->json(['success' => false, 'message' => 'Bukan pengiriman kurir.']);
            }

            if (! $pembelian->delivery_tracking_number || ! $pembelian->delivery_method) {
                return response()->json(['success' => false, 'message' => 'Data resi atau kurir belum tersedia.']);
            }

            $trackingNumber = $pembelian->delivery_tracking_number;
            $courier        = $pembelian->delivery_method;
        }

        try {
            $result = $refresh
                ? $binderbyte->refresh($trackingNumber, $courier)
                : $binderbyte->track($trackingNumber, $courier);

            return response()->json($result);
        } catch (\Throwable $e) {
            \Log::error('Tracking error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data tracking.',
            ]);
        }
    }
    // ──────────────────────────────────────────────────────────────────
    //  PENGGUNA: Tahap 3 — Konfirmasi terima koleksi (dalam_pengiriman → diterima_pembeli)
    // ──────────────────────────────────────────────────────────────────

    public function confirmReceived(Pembelian $pembelian): RedirectResponse
    {
        $this->authorizeOwner($pembelian);

        if ($pembelian->status !== 'dalam_pengiriman') {
            return redirect()->back()->with('error', 'Konfirmasi hanya bisa dilakukan saat koleksi sedang dalam pengiriman.');
        }

        $pembelian->update([
            'status'      => 'pengecekan_kondisi',
            'received_at' => now(),
        ]);

        $pembelian->appendDamageTimeline(
            'condition_checking',
            'Pembeli mengkonfirmasi koleksi telah diterima. Melanjutkan ke pengecekan kondisi.'
        );

        // ← Redirect ke serah-terima, BUKAN ke condition-check
        return redirect()->route('pembelian.serah-terima', $pembelian)
            ->with('success', 'Penerimaan dikonfirmasi. Silakan periksa kondisi koleksi terlebih dahulu.');
    }

    public function showConditionCheck(Pembelian $pembelian)
    {
        $this->authorizeOwner($pembelian);

        // Redirect ke serah-terima — form cek kondisi sudah ada di sana
        return redirect()->route('pembelian.serah-terima', $pembelian);
    }

    public function submitConditionGood(Request $request, Pembelian $pembelian): RedirectResponse
    {
        $this->authorizeOwner($pembelian);

        if ($pembelian->status !== 'pengecekan_kondisi') {
            return redirect()->back()
                ->with('error', 'Pengecekan kondisi hanya bisa dilakukan saat status pengecekan kondisi.');
        }

        $request->validate([
            'condition_front_photo' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:5120'],
            'condition_back_photo'  => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:5120'],
            'condition_video'       => ['nullable', 'file', 'mimes:mp4,mov,avi', 'max:51200'],
        ], [
            'condition_front_photo.required' => 'Foto depan koleksi wajib diunggah.',
            'condition_front_photo.mimes'    => 'Foto depan harus berformat JPG atau PNG.',
            'condition_back_photo.required'  => 'Foto belakang koleksi wajib diunggah.',
            'condition_back_photo.mimes'     => 'Foto belakang harus berformat JPG atau PNG.',
            'condition_video.mimes'          => 'Video harus berformat MP4, MOV, atau AVI.',
            'condition_video.max'            => 'Video maksimal 50MB.',
        ]);

        $frontPath = $request->file('condition_front_photo')
            ->store('pembelian/kondisi', 'public');
        $backPath = $request->file('condition_back_photo')
            ->store('pembelian/kondisi', 'public');
        $videoPath = $request->hasFile('condition_video')
            ? $request->file('condition_video')->store('pembelian/kondisi-video', 'public')
            : null;

        $pembelian->update([
            'condition_check_status' => 'good',
            'condition_checked_at'   => now(),
            'condition_front_photo'  => $frontPath,
            'condition_back_photo'   => $backPath,
            'condition_video'        => $videoPath,
            'status'                 => 'menunggu_dokumen_serah_terima',
        ]);

        $pembelian->appendDamageTimeline(
            'condition_good',
            'Pembeli mengkonfirmasi koleksi diterima dalam kondisi baik beserta foto dokumentasi. Melanjutkan ke proses dokumen serah terima.'
        );

        return redirect()->route('pembelian.serah-terima', $pembelian)
            ->with('success', 'Kondisi koleksi dikonfirmasi baik. Silakan unduh dan upload dokumen serah terima.');
    }

    public function submitConditionDamage(Request $request, Pembelian $pembelian): RedirectResponse
    {
        $this->authorizeOwner($pembelian);

        if ($pembelian->status !== 'pengecekan_kondisi') {
            return redirect()->back()
                ->with('error', 'Laporan kerusakan hanya bisa dikirim saat pengecekan kondisi.');
        }

        $isKurir = $pembelian->shipping_method_type === 'courier';

        $rules = [
            // ── 3 file terpisah ──────────────────────────────────
            'condition_front_photo'      => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:5120'],
            'condition_back_photo'       => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:5120'],
            'damage_video'               => ['required', 'file', 'mimes:mp4,mov,avi', 'max:51200'],

            // ── Checklist & detail ────────────────────────────────
            'arrival_damage_checklist'   => ['required', 'array', 'min:1'],
            'arrival_damage_description' => ['nullable', 'string', 'max:2000'],
            'buyer_decision'             => ['required', 'in:lanjut,batalkan'],

            // ── Foto packing ──────────────────────────────────────
            'packing_condition_photos'   => ['required', 'array', 'min:1', 'max:5'],
            'packing_condition_photos.*' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:5120'],

            'item_descriptions'   => ['nullable', 'array'],
            'item_descriptions.*' => ['nullable', 'string', 'max:500'],
        ];

        if ($isKurir) {
            $rules['courier_receipt_photos']   = ['required', 'array', 'min:1', 'max:3'];
            $rules['courier_receipt_photos.*'] = ['required', 'file', 'mimes:jpg,jpeg,png', 'max:5120'];
        }

        $request->validate($rules, [
            'condition_front_photo.required' => 'Foto depan koleksi wajib diunggah.',
            'condition_back_photo.required'  => 'Foto belakang koleksi wajib diunggah.',
            'damage_video.required'          => 'Video bukti kerusakan wajib diunggah.',
            'damage_video.mimes'             => 'Video harus berformat MP4, MOV, atau AVI.',
            'damage_video.max'               => 'Video maksimal 50MB.',
            'arrival_damage_checklist.required' => 'Pilih minimal satu jenis kerusakan.',
            'arrival_damage_severity.required'  => 'Tingkat keparahan kerusakan wajib dipilih.',
            'buyer_decision.required'           => 'Keputusan lanjut/batalkan wajib dipilih.',
            'packing_condition_photos.required' => 'Foto kondisi packing wajib diunggah.',
            'courier_receipt_photos.required'   => 'Bukti penerimaan dari kurir wajib diunggah.',
        ]);

        $decision = $request->input('buyer_decision');

        // Build checklist
        $allItems   = Pembelian::arrivalDamageChecklistItems();
        $submitted  = $request->input('arrival_damage_checklist', []);
        $descriptions = $request->input('item_descriptions', []);

        $damageItems = [];
        foreach ($allItems as $key => $label) {
            if (array_key_exists($key, $submitted)) {
                $damageItems[] = [
                    'key'         => $key,
                    'label'       => $label,
                    'checked'     => true,
                    'description' => $descriptions[$key] ?? null,
                ];
            }
        }

        if (empty($damageItems)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['arrival_damage_checklist' => 'Pilih minimal satu jenis kerusakan yang valid.']);
        }

        // Upload foto depan, belakang, video
        $frontPath = $request->file('condition_front_photo')
            ->store('pembelian/damage/condition', 'public');
        $backPath = $request->file('condition_back_photo')
            ->store('pembelian/damage/condition', 'public');
        $videoPath = $request->file('damage_video')
            ->store('pembelian/damage/video', 'public');

        // Upload foto packing
        $packingPaths = [];
        foreach ($request->file('packing_condition_photos') as $file) {
            $packingPaths[] = $file->store('pembelian/damage/packing', 'public');
        }

        // Upload bukti kurir
        $courierPaths = [];
        if ($isKurir && $request->hasFile('courier_receipt_photos')) {
            foreach ($request->file('courier_receipt_photos') as $file) {
                $courierPaths[] = $file->store('pembelian/damage/courier', 'public');
            }
        }

        $checkedLabels = collect($damageItems)->pluck('label')->implode(', ');

        // Semua laporan kerusakan menunggu review pengelola terlebih dahulu
        $nextStatus = 'menunggu_review_kerusakan';

        $pembelian->update([
            'condition_check_status'        => 'damaged',
            'condition_checked_at'          => now(),
            'arrival_damage_items'          => $damageItems,
            'arrival_damage_photos'         => [$frontPath, $backPath], // simpan front & back
            'condition_front_photo'         => $frontPath,
            'condition_back_photo'          => $backPath,
            'damage_video_path'             => $videoPath,
            'arrival_damage_description'    => $request->input('arrival_damage_description'),            'packing_condition_photos'      => $packingPaths,
            'courier_receipt_photos'        => $courierPaths ?: null,
            'arrival_damage_reported_at'    => now(),
            'arrival_damage_buyer_decision' => $decision,
            'status'                        => $nextStatus,
        ]);

        $pembelian->appendDamageTimeline(
            'damage_reported',
            'Pembeli melaporkan kerusakan. Jenis: ' . $checkedLabels
                . '. Keputusan pembeli: ' . ($decision === 'lanjut' ? 'Terima dengan kompensasi' : 'Ajukan pembatalan')
                . '. Menunggu review pengelola.'
        );

        return redirect()->route('pembelian.serah-terima', $pembelian)
            ->with('success', 'Laporan kerusakan berhasil dikirim. Menunggu review pengelola.');
    }

    public function decideDamage(Request $request, Pembelian $pembelian): RedirectResponse
    {
        $this->authorizePengelola();

        if ($pembelian->status !== 'menunggu_review_kerusakan') {
            return redirect()->back()->with('error', 'Tidak ada laporan kerusakan yang perlu direview.');
        }

        if ($pembelian->arrival_damage_decided_at !== null) {
            return redirect()->back()->with('error', 'Laporan kerusakan sudah pernah diputuskan.');
        }

        $buyerDecision = $pembelian->arrival_damage_buyer_decision; // 'lanjut' atau 'batalkan'

        if ($buyerDecision === 'batalkan') {
            // ── Pembeli ajukan pembatalan ──────────────────────────
            $data = $request->validate([
                'manager_decision' => ['required', 'in:setujui,tolak'],
                'notes'            => ['required', 'string', 'max:2000'],
            ], [
                'manager_decision.required' => 'Pilih keputusan terlebih dahulu.',
                'notes.required'            => 'Alasan / catatan untuk pembeli wajib diisi.',
            ]);

            $managerDecision = $data['manager_decision'];

            if ($managerDecision === 'setujui') {
                // Setujui pembatalan → refund penuh - ongkir → menunggu_data_rekening
                $refundAmount = $pembelian->calculateFullDamageRefundAmount();

                $pembelian->update([
                    'arrival_damage_final_severity'      => 'parah',
                    'arrival_damage_severity_corrected'  => false,
                    'arrival_damage_compensation_amount' => null,
                    'arrival_damage_manager_notes'       => $data['notes'],
                    'arrival_damage_decided_at'          => now(),
                    'arrival_damage_decided_by'          => auth()->user()->name,
                    'arrival_damage_manager_decision'    => 'setujui_pembatalan',
                    'status'                             => 'menunggu_data_rekening',
                ]);

                $message = 'Pembatalan disetujui. Refund penuh (dikurangi ongkir awal, ditambah ongkir pengembalian): estimasi Rp '
                    . number_format($refundAmount, 0, ',', '.') . '. Pembeli diminta mengembalikan koleksi ke museum dan mengisi data rekening.';

            } else {
                // Tolak pembatalan → transaksi lanjut serah terima
                $pembelian->update([
                    'arrival_damage_final_severity'      => 'ringan',
                    'arrival_damage_severity_corrected'  => true,
                    'arrival_damage_compensation_amount' => null,
                    'arrival_damage_manager_notes'       => $data['notes'],
                    'arrival_damage_decided_at'          => now(),
                    'arrival_damage_decided_by'          => auth()->user()->name,
                    'arrival_damage_manager_decision'    => 'tolak_pembatalan',
                    'status'                             => 'menunggu_dokumen_serah_terima',
                ]);

                $message = 'Klaim pembatalan ditolak. Pembeli akan dinotifikasi dan diminta melanjutkan proses serah terima.';
            }

        } else {
            // ── Pembeli minta kompensasi (lanjut) ─────────────────
            $maxCompensation = $pembelian->calculateBaseDamageRefundAmount();

            $data = $request->validate([
                'manager_decision'    => ['required', 'in:setujui,tolak'],
                'notes'               => ['required', 'string', 'max:2000'],
                'compensation_amount' => ['required_if:manager_decision,setujui', 'nullable', 'integer', 'min:1', 'max:' . max(1, $maxCompensation)],
            ], [
                'manager_decision.required'       => 'Pilih keputusan terlebih dahulu.',
                'notes.required'                  => 'Alasan / catatan untuk pembeli wajib diisi.',
                'compensation_amount.required_if' => 'Jumlah kompensasi wajib diisi jika klaim disetujui.',
                'compensation_amount.max'         => 'Kompensasi tidak boleh melebihi Rp ' . number_format($maxCompensation, 0, ',', '.') . '.',
            ]);

            if ($data['manager_decision'] === 'setujui') {
                $pembelian->update([
                    'arrival_damage_final_severity'      => 'ringan',
                    'arrival_damage_severity_corrected'  => false,
                    'arrival_damage_compensation_amount' => (int) $data['compensation_amount'],
                    'arrival_damage_manager_notes'       => $data['notes'],
                    'arrival_damage_decided_at'          => now(),
                    'arrival_damage_decided_by'          => auth()->user()->name,
                    'arrival_damage_manager_decision'    => 'setujui_kompensasi',
                    'status'                             => 'menunggu_data_rekening',
                ]);

                $message = 'Kompensasi Rp ' . number_format((int) $data['compensation_amount'], 0, ',', '.')
                    . ' disetujui. Pembeli diminta mengisi data rekening untuk proses transfer.';
            } else {
                $pembelian->update([
                    'arrival_damage_final_severity'      => 'ringan',
                    'arrival_damage_severity_corrected'  => true,
                    'arrival_damage_compensation_amount' => null,
                    'arrival_damage_manager_notes'       => $data['notes'],
                    'arrival_damage_decided_at'          => now(),
                    'arrival_damage_decided_by'          => auth()->user()->name,
                    'arrival_damage_manager_decision'    => 'tolak_kompensasi',
                    'status'                             => 'menunggu_dokumen_serah_terima',
                ]);

                $message = 'Klaim kompensasi ditolak. Pembeli akan dinotifikasi dan diminta melanjutkan proses serah terima tanpa kompensasi.';
            }
        }

        $pembelian->appendDamageTimeline('damage_reviewed', $message);

        return redirect()->route('pengelola.pembelian.serah-terima', $pembelian)
            ->with('success', $message);
    }

    public function submitBankAccount(Request $request, Pembelian $pembelian): RedirectResponse
    {
        $this->authorizeOwner($pembelian);

        if ($pembelian->status !== 'menunggu_data_rekening') {
            return redirect()->back()->with('error', 'Pengisian rekening hanya diperlukan setelah review kerusakan selesai.');
        }

        if ($pembelian->isDamageCancellation()) {
            return $this->submitReturnAndBankAccount($request, $pembelian);
        }

        $data = $request->validate([
            'refund_bank_name'      => ['required', 'string', 'max:100'],
            'refund_account_number' => ['required', 'string', 'max:50'],
            'refund_account_holder' => ['required', 'string', 'max:255'],
        ]);

        $pembelian->update(array_merge($data, [
            'refund_bank_submitted_at' => now(),
            'status'                   => 'menunggu_refund_kerusakan',
        ]));

        $pembelian->appendDamageTimeline(
            'bank_submitted',
            'Pembeli mengisi data rekening: ' . $data['refund_bank_name']
                . ' - ' . $data['refund_account_number'] . ' a.n. ' . $data['refund_account_holder']
                . '. Menunggu transfer refund dari pengelola.'
        );

        return redirect()->route('pembelian.serah-terima', $pembelian)
            ->with('success', 'Data rekening berhasil dikirim. Pengelola akan memproses refund secara manual.');
    }

    protected function submitReturnAndBankAccount(Request $request, Pembelian $pembelian): RedirectResponse
    {
        $data = $request->validate([
            'return_shipment_method'       => ['required', 'string', 'max:255'],
            'return_shipment_officer'      => ['required', 'string', 'max:255'],
            'return_shipment_tracking'     => ['nullable', 'string', 'max:100'],
            'return_shipment_scheduled_at' => ['required', 'date'],
            'return_shipment_notes'        => ['nullable', 'string', 'max:2000'],
            'return_shipping_cost'         => ['required', 'integer', 'min:0'],
            'return_shipping_proof'        => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'refund_bank_name'             => ['required', 'string', 'max:100'],
            'refund_account_number'        => ['required', 'string', 'max:50'],
            'refund_account_holder'        => ['required', 'string', 'max:255'],
        ], [
            'return_shipping_proof.required' => 'Bukti ongkir pengembalian wajib diunggah.',
            'return_shipping_cost.required'  => 'Nominal ongkir pengembalian wajib diisi.',
        ]);

        $proofPath = $request->file('return_shipping_proof')
            ->store('pembelian/return-shipping/proofs', 'public');

        $pembelian->update([
            'return_shipment_method'       => $data['return_shipment_method'],
            'return_shipment_officer'      => $data['return_shipment_officer'],
            'return_shipment_tracking'     => $data['return_shipment_tracking'] ?? null,
            'return_shipment_scheduled_at' => $data['return_shipment_scheduled_at'],
            'return_shipment_notes'        => $data['return_shipment_notes'] ?? null,
            'return_shipment_submitted_at' => now(),
            'return_shipping_cost'         => (int) $data['return_shipping_cost'],
            'return_shipping_proof_path'   => $proofPath,
            'refund_bank_name'             => $data['refund_bank_name'],
            'refund_account_number'        => $data['refund_account_number'],
            'refund_account_holder'        => $data['refund_account_holder'],
            'refund_bank_submitted_at'     => now(),
            'status'                       => 'menunggu_penerimaan_koleksi',
        ]);

        $timelineMessage = 'Pembeli mengirimkan info pengembalian koleksi ke museum dan data rekening refund.'
            . ' Metode: ' . $data['return_shipment_method']
            . '. Ongkir pengembalian: Rp ' . number_format((int) $data['return_shipping_cost'], 0, ',', '.')
            . ($data['return_shipment_tracking'] ? '. Resi: ' . $data['return_shipment_tracking'] : '')
            . '. Menunggu pengelola konfirmasi penerimaan koleksi di museum.';

        $pembelian->appendDamageTimeline('return_shipment_submitted', $timelineMessage);

        return redirect()->route('pembelian.serah-terima', $pembelian)
            ->with('success', 'Data pengembalian koleksi dan rekening berhasil dikirim. Menunggu pengelola mengkonfirmasi penerimaan koleksi di museum sebelum proses refund dilanjutkan.');
    }

    public function returnShipmentStatus(Request $request, Pembelian $pembelian): RedirectResponse
    {
        $this->authorizeOwner($pembelian);

        if ($pembelian->status !== 'menunggu_penerimaan_koleksi' || ! $pembelian->return_shipment_submitted_at) {
            return redirect()->back()->with('error', 'Status tidak sesuai untuk memperbarui tracking pengembalian.');
        }

        if ($pembelian->return_shipment_tracking) {
            return redirect()->back()->with('error', 'Pengiriman balik sudah berupa nomor resi. Gunakan tracker kurir.');
        }

        if ($pembelian->collection_arrived_at) {
            return redirect()->back()->with('info', 'Koleksi sudah dikonfirmasi tiba di museum.');
        }

        $data = $request->validate([
            'return_shipment_status' => ['required', 'in:dikemas,siap_dikirim,dalam_perjalanan,tiba_di_tujuan'],
            'catatan_status'         => ['nullable', 'string', 'max:500'],
        ]);

        $statusLabels = Pembelian::returnShipmentStatuses();
        $statusLabel  = $statusLabels[$data['return_shipment_status']] ?? $data['return_shipment_status'];
        $statusOrder  = array_keys($statusLabels);
        $current      = $pembelian->return_shipment_status;
        $currentIndex = $current ? array_search($current, $statusOrder) : -1;
        $newIndex     = array_search($data['return_shipment_status'], $statusOrder);

        if ($current && $newIndex <= $currentIndex) {
            return redirect()->back()->with('error', 'Status tidak bisa mundur ke tahap sebelumnya.');
        }

        if ($current && $newIndex > $currentIndex + 1) {
            return redirect()->back()->with('error', 'Update status harus berurutan.');
        }

        $timeline = $pembelian->return_shipment_timeline ?? [];
        $timeline[] = [
            'status'    => $data['return_shipment_status'],
            'label'     => $statusLabel,
            'catatan'   => $data['catatan_status'] ?? null,
            'timestamp' => now()->toDateTimeString(),
            'by'        => auth()->user()->name,
        ];

        $pembelian->update([
            'return_shipment_status'   => $data['return_shipment_status'],
            'return_shipment_timeline' => $timeline,
        ]);

        $pembelian->appendDamageTimeline(
            $data['return_shipment_status'],
            'Status pengembalian koleksi diperbarui: ' . $statusLabel
                . ($data['catatan_status'] ? '. Catatan: ' . $data['catatan_status'] : '')
        );

        return redirect()->back()->with('success', 'Status pengembalian koleksi diperbarui: ' . $statusLabel);
    }

    public function confirmCollectionArrived(Pembelian $pembelian): RedirectResponse
    {
        $this->authorizePengelola();

        if ($pembelian->status !== 'menunggu_penerimaan_koleksi') {
            return redirect()->back()->with('error', 'Status tidak sesuai untuk konfirmasi penerimaan koleksi.');
        }

        if (! $pembelian->return_shipment_submitted_at) {
            return redirect()->back()
                ->with('error', 'Pembeli belum mengirimkan informasi pengembalian koleksi.');
        }

        if ($pembelian->collection_arrived_at) {
            return redirect()->route('pengelola.pembelian.serah-terima', $pembelian)
                ->with('info', 'Koleksi sudah dikonfirmasi tiba. Lanjutkan proses refund.');
        }

        $refundAmount = $pembelian->calculateFullDamageRefundAmount();

        $pembelian->update([
            'collection_arrived_at' => now(),
            'status'                => 'menunggu_refund_kerusakan',
        ]);

        $pembelian->appendDamageTimeline(
            'collection_arrived',
            'Pengelola mengkonfirmasi koleksi sudah tiba kembali di museum. '
                . 'Melanjutkan proses refund Rp ' . number_format($refundAmount, 0, ',', '.')
                . ' (termasuk ongkir pengembalian Rp ' . number_format((int) ($pembelian->return_shipping_cost ?? 0), 0, ',', '.') . ').'
        );

        return redirect()->route('pengelola.pembelian.serah-terima', $pembelian)
            ->with('success', 'Koleksi dikonfirmasi tiba di museum. Silakan proses refund ke rekening pembeli.');
    }

    public function storeRefundProof(Request $request, Pembelian $pembelian): RedirectResponse
    {
        $this->authorizePengelola();

        if ($pembelian->status !== 'menunggu_refund_kerusakan') {
            return redirect()->back()->with('error', 'Status tidak sesuai untuk proses refund kerusakan.');
        }

        if ($pembelian->refund_processed_at !== null) {
            return redirect()->back()->with('error', 'Bukti refund sudah pernah diinput.');
        }

        $maxRefund = $pembelian->isFinalSeverityParah()
            ? $pembelian->calculateFullDamageRefundAmount()
            : (int) ($pembelian->arrival_damage_compensation_amount ?? 0);

        $data = $request->validate([
            'refund_amount'   => ['required', 'integer', 'min:1', 'max:' . max(1, $maxRefund)],
            'refund_date'     => ['required', 'date'],
            'transfer_proof'  => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'refund_notes'    => ['nullable', 'string', 'max:1000'],
        ]);

        $proofPath = $request->file('transfer_proof')
            ->store('pembelian/refund/proofs', 'public');

        $nextStatus = 'menunggu_konfirmasi_refund';

        $pembelian->update([
            'refund_amount'              => $data['refund_amount'],
            'refund_transfer_proof_path' => $proofPath,
            'refund_date'                => $data['refund_date'],
            'refund_notes'               => $data['refund_notes'] ?? null,
            'refund_processed_at'        => now(),
            'refund_processed_by'        => auth()->user()->name,
            'status'                     => $nextStatus,
        ]);


        $timelineMessage = 'Pengelola mengunggah bukti transfer '
            . ($pembelian->isDamageCompensation() ? 'kompensasi' : 'refund')
            . '. Nominal: Rp ' . number_format($data['refund_amount'], 0, ',', '.') . '.';

        if ($pembelian->isDamageCompensation()) {
            $timelineMessage .= ' Menunggu pembeli mengkonfirmasi penerimaan kompensasi sebelum melanjutkan ke dokumen serah terima.';
        } elseif ($pembelian->isDamageCancellation()) {
            $timelineMessage .= ' Menunggu pembeli mengkonfirmasi penerimaan refund.';
        }

        $pembelian->appendDamageTimeline('refund_processed', $timelineMessage);

        $successMessage = $pembelian->isDamageCompensation()
            ? 'Bukti transfer kompensasi berhasil disimpan. Menunggu konfirmasi penerimaan dari pembeli.'
            : ($pembelian->isDamageCancellation()
                ? 'Bukti transfer refund penuh berhasil disimpan. Menunggu konfirmasi pembeli.'
                : 'Bukti transfer berhasil disimpan.');

        return redirect()->route('pengelola.pembelian.show', $pembelian)
            ->with('success', $successMessage);
    }

    public function confirmRefundReceived(Pembelian $pembelian): RedirectResponse
    {
        $this->authorizeOwner($pembelian);

        if ($pembelian->status !== 'menunggu_konfirmasi_refund') {
            return redirect()->back()->with('error', 'Konfirmasi tidak diperlukan saat ini.');
        }

        if ($pembelian->isDamageCompensation()) {
            $pembelian->update([
                'status'              => 'menunggu_dokumen_serah_terima',
                'refund_confirmed_at' => now(),
            ]);

            $pembelian->appendDamageTimeline(
                'compensation_confirmed',
                'Pembeli mengkonfirmasi penerimaan kompensasi. Melanjutkan ke proses dokumen serah terima.'
            );

            return redirect()->route('pembelian.show', $pembelian)
                ->with('success', 'Penerimaan kompensasi dikonfirmasi. Silakan lanjutkan upload dokumen serah terima.');
        }

        $pembelian->update([
            'status'              => 'dibatalkan',
            'refund_confirmed_at' => now(),
        ]);

        $pembelian->koleksi?->update([
            'available' => true,
        ]);

        $pembelian->appendDamageTimeline(
            'refund_confirmed',
            'Pembeli mengkonfirmasi penerimaan refund. Pembatalan transaksi selesai.'
        );

        return redirect()->route('pembelian.show', $pembelian)
            ->with('success', 'Penerimaan refund dikonfirmasi. Pembatalan transaksi selesai.');
    }


    public function downloadDocument(Pembelian $pembelian)
    {
        if (auth()->user()->role !== 'pengelola' && auth()->id() !== $pembelian->user_id) {
            abort(403);
        }
    
        // Selalu generate ulang PDF terbaru
        $documentService = new DocumentService();
        $path = $documentService->generatePurchaseHandoverPdf($pembelian);
        $pembelian->update(['handover_document_path' => $path]);
    
        $fileName = 'Serah-Terima-Pembelian-BLI-' . str_pad($pembelian->id, 5, '0', STR_PAD_LEFT) . '.pdf';
    
        return response(
            Storage::disk('public')->get($path),
            200,
            [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                'Cache-Control'       => 'no-cache, no-store, must-revalidate',
            ]
        );
    }
 
    /**
     * Download sertifikat keaslian.
     * Generate ulang jika belum ada.
     */
    public function downloadCertificate(Pembelian $pembelian)
    {
        // Pastikan yang akses adalah pemilik atau pengelola
        if (auth()->user()->role === 'pengguna' && $pembelian->user_id !== auth()->id()) {
            abort(403);
        }
    
        // Pastikan pembelian sudah selesai
        if ($pembelian->status !== 'selesai' && $pembelian->status !== 'selesai_dengan_kompensasi') {
            return redirect()->back()
                ->with('error', 'Sertifikat hanya tersedia setelah proses pembelian selesai.');
        }
    
        /** @var \App\Services\DocumentService $docService */
        $docService = app(\App\Services\DocumentService::class);
    
        // Generate jika belum ada atau file sudah terhapus
        if (
            ! $pembelian->certificate_document_path ||
            ! \Illuminate\Support\Facades\Storage::disk('public')->exists($pembelian->certificate_document_path)
        ) {
            $pembelian->certificate_document_path = $docService->generateAuthenticityCertificatePdf($pembelian);
            $pembelian->save();
        }
    
        $path = \Illuminate\Support\Facades\Storage::disk('public')->path($pembelian->certificate_document_path);
    
        // Ambil cert ID dari nama file untuk nama download
        preg_match('/(COA-\d{4}-[A-Z0-9]+)/', basename($pembelian->certificate_document_path), $m);
        $certId   = $m[1] ?? ('COA-' . $pembelian->id);
        $fileName = 'Sertifikat-Keaslian-' . $certId . '.pdf';
    
        return response()->file($path, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
        ]);
    }

    public function previewCertificate(Pembelian $pembelian)
    {
        $user = auth()->user();
        if ($user->role !== 'pengelola' && $pembelian->user_id !== $user->id) {
            abort(403);
        }

        // Selalu generate ulang (sama seperti downloadCertificate)
        $docService = app(\App\Services\DocumentService::class);
        $pembelian->certificate_document_path = $docService->generateAuthenticityCertificatePdf($pembelian);
        $pembelian->save();

        return response()->file(
            Storage::disk('public')->path($pembelian->certificate_document_path),
            [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'inline; filename="sertifikat-' . $pembelian->id . '.pdf"',
            ]
        );
    }
    protected function generateCertificate(Pembelian $pembelian): void
    {
        $documentService = new DocumentService();
        $path = $documentService->generateAuthenticityCertificatePdf($pembelian);
        $pembelian->update(['certificate_document_path' => $path]);
    }

    // Preview uploaded (signed) handover document inline for pengelola
    public function previewUploadedDocument(Pembelian $pembelian)
    {
        $this->authorizePengelola();

        if (! $pembelian->handover_signed_document_path) {
            abort(404, 'Dokumen serah terima yang ditandatangani tidak ditemukan.');
        }

        $path = $pembelian->handover_signed_document_path;

        if (! \Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
            abort(404, 'File tidak ditemukan di storage.');
        }

        return response()->file(
            \Illuminate\Support\Facades\Storage::disk('public')->path($path),
            ['Content-Disposition' => 'inline; filename="dokumen-serah-terima-' . $pembelian->id . '.pdf"']
        );
    }

    // Download the uploaded (signed) handover document
    public function downloadUploadedDocument(Pembelian $pembelian)
    {
        $this->authorizePengelola();

        if (! $pembelian->handover_signed_document_path) {
            abort(404, 'Dokumen serah terima yang ditandatangani tidak ditemukan.');
        }

        $path = $pembelian->handover_signed_document_path;

        if (! \Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
            abort(404, 'File tidak ditemukan di storage.');
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->download(
            $path,
            'Dokumen-Serah-Terima-Uploaded-' . $pembelian->id . '.pdf'
        );
    }

    public function uploadDocument(Request $request, Pembelian $pembelian): RedirectResponse
    {
        $this->authorizeOwner($pembelian);

        if ($pembelian->status !== 'menunggu_dokumen_serah_terima') {
            return redirect()->route('pembelian.serah-terima', $pembelian)
                ->with('error', 'Upload dokumen hanya bisa dilakukan setelah koleksi diterima.');
        }

        $data = $request->validate([
            'signed_handover_document'          => ['required', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
            'received_condition_photo'          => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'checklist_frame_safe'              => ['sometimes', 'boolean'],
            'checklist_no_tears'                => ['sometimes', 'boolean'],
            'checklist_color_normal'            => ['sometimes', 'boolean'],
            'checklist_glass_safe'              => ['sometimes', 'boolean'],
            'checklist_no_mold'                 => ['sometimes', 'boolean'],
            'checklist_matches_documentation'   => ['sometimes', 'boolean'],
            'handover_condition_notes'          => ['nullable', 'string', 'max:2000'],
        ]);

        $nomorBli  = 'BLI-' . str_pad($pembelian->id, 5, '0', STR_PAD_LEFT);
        $ext       = $request->file('signed_handover_document')->getClientOriginalExtension();
        $fileName  = 'Serah-Terima-Ditandatangani-' . $nomorBli . '.' . $ext;

        $signedPath = $request->file('signed_handover_document')
            ->storeAs('pembelian/serah-terima/signed', $fileName, 'public');

        // Convert to PDF for reliable browser preview if not already PDF
        $documentService = new DocumentService();
        try {
            $pdfPath = $documentService->convertToPdf($signedPath);
            // Rename converted PDF juga agar konsisten
            $pdfFileName = 'Serah-Terima-Ditandatangani-' . $nomorBli . '.pdf';
            $newPdfPath  = 'pembelian/serah-terima/signed/' . $pdfFileName;
            \Illuminate\Support\Facades\Storage::disk('public')->move($pdfPath, $newPdfPath);
            $pdfPath = $newPdfPath;
        } catch (\Throwable $e) {
            $pdfPath = $signedPath;
        }

        $photoPath = null;
        if ($request->hasFile('received_condition_photo')) {
            $photoPath = $request->file('received_condition_photo')
                ->store('pembelian/serah-terima/photos', 'public');
        }

        $pembelian->update([
            'handover_signed_document_path'      => $pdfPath,
            'handover_signed_at'                 => now(),
            'handover_document_uploaded_at'      => now(),
            'handover_checklist_frame_safe'      => $request->boolean('checklist_frame_safe'),
            'handover_checklist_no_tears'        => $request->boolean('checklist_no_tears'),
            'handover_checklist_color_normal'    => $request->boolean('checklist_color_normal'),
            'handover_checklist_glass_safe'      => $request->boolean('checklist_glass_safe'),
            'handover_checklist_no_mold'         => $request->boolean('checklist_no_mold'),
            'handover_checklist_matches_documentation' => $request->boolean('checklist_matches_documentation'),
            'handover_condition_notes'           => $request->input('handover_condition_notes'),
            'handover_received_condition_photo_path' => $photoPath,
            'status'                             => 'menunggu_validasi_serah_terima',
        ]);

        return redirect()->route('pembelian.serah-terima', $pembelian)
            ->with('success', 'Dokumen serah terima berhasil diunggah. Menunggu validasi pengelola.');
    }

    public function validateDocument(Request $request, Pembelian $pembelian): RedirectResponse
    {
        $this->authorizePengelola();

        if ($pembelian->status !== 'menunggu_validasi_serah_terima') {
            return redirect()->back()
                ->with('error', 'Belum ada dokumen serah terima yang perlu divalidasi.');
        }

        $data = $request->validate([
            'action'           => ['required', 'in:validate,reject'],
            'validation_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($data['action'] === 'validate') {
            $finalStatus = $pembelian->arrival_damage_final_severity === 'ringan' && $pembelian->refund_processed_at
                ? 'selesai_dengan_kompensasi'
                : 'selesai';

            $pembelian->update([
                'status'                   => $finalStatus,
                'completed_at'             => now(),
                'handover_validated_at'    => now(),
                'handover_validated_by'    => auth()->user()->name,
                'handover_validation_notes' => $data['validation_notes'],
            ]);

            $pembelian->koleksi?->update([
                'available'   => false,
                'status_sewa' => 'tidak',
            ]);

            $this->generateCertificate($pembelian);

            return redirect()->route('pengelola.pembelian.serah-terima', $pembelian)
                ->with('success', 'Dokumen serah terima divalidasi. Transaksi selesai dan sertifikat keaslian telah dibuat.');
        }

        $pembelian->update([
            'status'                  => 'menunggu_dokumen_serah_terima',
            'handover_validation_notes' => $data['validation_notes'],
            'handover_validated_at'     => now(),
        ]);

        return redirect()->route('pengelola.pembelian.serah-terima', $pembelian)
            ->with('success', 'Dokumen ditolak. Pembeli diminta upload ulang.');
    }

    public function tracking(Pembelian $pembelian, TrackingService $trackingService)
    {
        $user = auth()->user();
        if ($user->role !== 'pengelola' && $user->id !== $pembelian->user_id) abort(403);

        $trackingData = null;
        $error = null;
        $refresh = request()->boolean('refresh');

        if ($pembelian->delivery_tracking_number && $pembelian->delivery_method) {
            $result = $refresh
                ? $trackingService->refresh($pembelian->delivery_tracking_number, $pembelian->delivery_method)
                : $trackingService->track($pembelian->delivery_tracking_number, $pembelian->delivery_method);

            if ($result['success']) $trackingData = $result['data'];
            else $error = $result['message'];
        }

        $isPengelola = $user->role === 'pengelola';
        return view('pembelian.tracking', compact('pembelian', 'trackingData', 'error', 'isPengelola'));
    }

    // ──────────────────────────────────────────────────────────────────
    //  PENGELOLA: Tahap 4 — Selesaikan transaksi (diterima_pembeli → selesai)
    // ──────────────────────────────────────────────────────────────────

    public function markAsCompleted(Pembelian $pembelian): RedirectResponse
    {
        $this->authorizePengelola();

        if ($pembelian->status !== 'diterima_pembeli') {
            return redirect()->back()->with('error', 'Transaksi hanya bisa diselesaikan setelah pembeli mengkonfirmasi penerimaan.');
        }

        $pembelian->update([
            'status'       => 'selesai',
            'completed_at' => now(),
        ]);

        // Tandai lukisan sebagai terjual
        $pembelian->koleksi?->update([
            'available'   => false,
            'status_sewa' => 'tidak',
        ]);

        $this->generateCertificate($pembelian);

        return redirect()->route('pengelola.pembelian.serah-terima', $pembelian)
            ->with('success', 'Transaksi selesai. Koleksi resmi menjadi milik pembeli dan sertifikat keaslian telah dibuat.');
    }

    // ──────────────────────────────────────────────────────────────────
    //  SHARED: Halaman serah terima
    // ──────────────────────────────────────────────────────────────────

    public function show(Pembelian $pembelian)
    {
        $user = auth()->user();

        if ($user->role !== 'pengelola' && $user->id !== $pembelian->user_id) {
            abort(403);
        }

        $pembelian->load(['painting', 'user', 'payments']);

        // Data untuk form cek kondisi (dipakai saat status pengecekan_kondisi)
        $isKurir              = $pembelian->shipping_method_type === 'courier';
        $damageChecklistItems = Pembelian::arrivalDamageChecklistItems();

        return view('pengelola.pembelian.serah-terima', compact(
            'pembelian',
            'isKurir',
            'damageChecklistItems',
        ));
    }

    // ──────────────────────────────────────────────────────────────────
    //  Helpers
    // ──────────────────────────────────────────────────────────────────

    protected function authorizePengelola(): void
    {
        if (auth()->user()->role !== 'pengelola') {
            abort(403);
        }
    }

    protected function authorizeOwner(Pembelian $pembelian): void
    {
        if (auth()->id() !== $pembelian->user_id) {
            abort(403);
        }
    }
}