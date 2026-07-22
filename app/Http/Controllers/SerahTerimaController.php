<?php

namespace App\Http\Controllers;

use App\Models\DamageInvoice;
use App\Models\DepositRefund;
use App\Models\Penyewaan;
use App\Models\SerahTerima;
use App\Models\SerahTerimaLog;
use App\Models\User;
use App\Notifications\SerahTerimaStatusNotification;
use App\Services\DocumentService;
use App\Services\MidtransService;
use App\Services\BinderbyteService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;


class SerahTerimaController extends Controller
{
    // ──────────────────────────────────────────────────────────────────
    //  SHARED — Show & Track
    // ──────────────────────────────────────────────────────────────────

    public function show(Penyewaan $penyewaan)
    {
        $this->authorizeOwnerOrPengelola($penyewaan);
        $penyewaan->loadMissing('serahTerima');
        $penyewaan->syncLegacyShippingStatus();
        $penyewaan->refresh();
        $serahTerima = $this->ensureSerahTerima($penyewaan);
        $this->maybeAdvanceAfterDamagePayment($penyewaan, $serahTerima);
        $penyewaan->refresh();
        $serahTerima->refresh();

        $isKurir              = $penyewaan->shipping_method_type === 'courier';
        $damageChecklistItems = SerahTerima::arrivalDamageChecklistItems();
        $isDamageCancellation = $serahTerima->isDamageCancellation();
        $isDamageCompensation = $serahTerima->isDamageCompensation();

        return view('serah_terima.show', compact(
            'penyewaan',
            'serahTerima',
            'isKurir',
            'damageChecklistItems',
            'isDamageCancellation',
            'isDamageCompensation',
        ));
    }

    public function track(Penyewaan $penyewaan)
    {
        $this->authorizeOwnerOrPengelola($penyewaan);
        $serahTerima = $this->ensureSerahTerima($penyewaan);

        return view('serah_terima.track', compact('penyewaan', 'serahTerima'));
    }


    // ──────────────────────────────────────────────────────────────────
    //  TAHAP 13 — Pengelola mengisi info pengiriman
    // ──────────────────────────────────────────────────────────────────

    public function updateDeliveryInfo(Request $request, Penyewaan $penyewaan): RedirectResponse
    {
        $this->authorizePengelola();

        if ($penyewaan->status !== 'pengiriman') {
            return redirect()->back()->with('error', 'Informasi pengiriman hanya bisa diisi saat status pengiriman.');
        }

        $isKurir = $penyewaan->shipping_method_type === 'courier';

        $rules = [
            'delivery_method'   => ['required', 'string', 'max:255'],
            'delivery_officer'  => ['required', 'string', 'max:255'],
            'delivery_location' => ['required', 'string', 'max:1000'],
            'delivery_notes'    => ['nullable', 'string', 'max:2000'],
            'recipient_name'    => ['required', 'string', 'max:255'],
        ];

        // Tambahkan setelah $rules yang sudah ada
        $rules['dispatch_front_photo']      = ['required', 'file', 'mimes:jpg,jpeg,png', 'max:5120'];
        $rules['dispatch_back_photo']       = ['required', 'file', 'mimes:jpg,jpeg,png', 'max:5120'];
        $rules['dispatch_packing_photos']   = ['required', 'array', 'min:1'];
        $rules['dispatch_packing_photos.*'] = ['file', 'mimes:jpg,jpeg,png', 'max:5120'];
        $rules['dispatch_video']            = ['nullable', 'file', 'mimes:mp4,mov,avi', 'max:51200'];

        if ($isKurir) {
            $rules['delivery_tracking_number'] = ['required', 'string', 'max:100'];
        } else {
            $rules['delivery_scheduled_at'] = ['nullable', 'date'];
        }

        $data = $request->validate($rules);

        // Upload foto dispatch
        $dispatchFrontPath  = $request->file('dispatch_front_photo')
            ->store('serah-terima/dispatch/' . $penyewaan->id, 'public');
        $dispatchBackPath   = $request->file('dispatch_back_photo')
            ->store('serah-terima/dispatch/' . $penyewaan->id, 'public');

        $dispatchPackingPaths = [];
        foreach ($request->file('dispatch_packing_photos') as $photo) {
            $dispatchPackingPaths[] = $photo->store('serah-terima/dispatch/' . $penyewaan->id, 'public');
        }

        $dispatchVideoPath = null;
        if ($request->hasFile('dispatch_video')) {
            $dispatchVideoPath = $request->file('dispatch_video')
                ->store('serah-terima/dispatch-video/' . $penyewaan->id, 'public');
        }

        $serahTerima = $this->ensureSerahTerima($penyewaan);

        if ($isKurir) {
            $scheduledAt = null;
            if ($penyewaan->courier_etd) {
                preg_match('/(\d+)/', $penyewaan->courier_etd, $matches);
                $etdDays = isset($matches[1]) ? (int) $matches[1] : null;
                if ($etdDays) {
                    $scheduledAt = now()->addDays($etdDays);
                }
            }

            // Kurir: satu langkah — langsung in_delivery + catat shipped_at
            $serahTerima->update(array_merge($data, [
                'handover_status'         => 'in_delivery',
                'serah_terima_status'     => 'in_delivery',
                'shipped_at'              => now(),
                'delivery_scheduled_at'   => $scheduledAt,
                'dispatch_front_photo'    => $dispatchFrontPath,
                'dispatch_back_photo'     => $dispatchBackPath,
                'dispatch_packing_photos' => $dispatchPackingPaths,
                'dispatch_video_path'     => $dispatchVideoPath,
            ]));

            $penyewaan->update(['status' => 'dalam_pengiriman']);

            SerahTerimaLog::create([
                'serah_terima_id' => $serahTerima->id,
                'status'          => 'in_delivery',
                'performed_by'    => auth()->user()->name,
                'message'         => 'Koleksi diserahkan ke kurir '
                    . ($data['delivery_method'] ?? '')
                    . '. No. Resi: ' . ($data['delivery_tracking_number'] ?? '-') . '.',
            ]);

            $this->notifyBoth($serahTerima, 'in_delivery');

            return redirect()->route('pengelola.penyewaan.handover.show', $penyewaan)
                ->with('success', 'Koleksi berhasil dicatat sudah dikirim via kurir. Menunggu konfirmasi penyewa.');

        } else {
            // Manager: dua langkah — simpan dulu, status preparing_delivery
            $serahTerima->update(array_merge($data, [
                'handover_status'     => 'preparing_delivery',
                'serah_terima_status' => 'preparing_delivery',
                'dispatch_front_photo'    => $dispatchFrontPath,
                'dispatch_back_photo'     => $dispatchBackPath,
                'dispatch_packing_photos' => $dispatchPackingPaths,
                'dispatch_video_path'     => $dispatchVideoPath,
            ]));

            SerahTerimaLog::create([
                'serah_terima_id' => $serahTerima->id,
                'status'          => 'preparing_delivery',
                'performed_by'    => auth()->user()->name,
                'message'         => 'Informasi pengiriman diisi. Petugas: ' . $data['delivery_officer']
                    . '. Metode: ' . $data['delivery_method'] . '.',
            ]);

            $penyewaan->update(['status' => 'siap_diserahkan']);

            $this->notifyBoth($serahTerima, 'preparing_delivery');

            return redirect()->route('pengelola.penyewaan.handover.show', $penyewaan)
                ->with('success', 'Informasi pengiriman berhasil disimpan. Tandai koleksi sudah dikirim saat sudah berangkat.');
        }
    }

    

    // ──────────────────────────────────────────────────────────────────
    //  TAHAP 14 — Pengelola tandai koleksi sudah dikirim
    // ──────────────────────────────────────────────────────────────────

    public function markAsShipped(Penyewaan $penyewaan): RedirectResponse
    {
        $this->authorizePengelola();

        $serahTerima = $this->ensureSerahTerima($penyewaan);

        if ($serahTerima->handover_status !== 'preparing_delivery') {
            return redirect()->back()->with('error', 'Koleksi harus dalam status persiapan pengiriman terlebih dahulu.');
        }

        if (! in_array($penyewaan->status, ['siap_diserahkan', 'pengiriman'], true)) {
            return redirect()->back()->with('error', 'Status penyewaan tidak sesuai untuk menandai koleksi sudah dikirim.');
        }

        $serahTerima->update([
            'handover_status'     => 'in_delivery',
            'serah_terima_status' => 'in_delivery',
            'shipped_at'          => now(),
        ]);

        $penyewaan->update(['status' => 'dalam_pengiriman']);

        SerahTerimaLog::create([
            'serah_terima_id' => $serahTerima->id,
            'status'          => 'in_delivery',
            'performed_by'    => auth()->user()->name,
            'message'         => 'Koleksi telah dikirim ke penyewa. Menunggu konfirmasi penerimaan.',
        ]);

        $this->notifyBoth($serahTerima, 'in_delivery');

        return redirect()->route('pengelola.penyewaan.handover.show', $penyewaan)
            ->with('success', 'Koleksi ditandai sudah dikirim. Penyewa akan menerima notifikasi.');
    }

    // ──────────────────────────────────────────────────────────────────
    //  TAHAP 15 — Pengguna konfirmasi terima koleksi
    // ──────────────────────────────────────────────────────────────────

    public function confirmReceived(Penyewaan $penyewaan): RedirectResponse
    {
        $this->authorizeOwner($penyewaan);

        $serahTerima = $this->ensureSerahTerima($penyewaan);

        if ($penyewaan->status !== 'dalam_pengiriman') {
            if ($penyewaan->status === 'pengecekan_kondisi') {
                return redirect()->route('penyewaan.requests.handover.show', $penyewaan)
                    ->with('info', 'Kamu sudah konfirmasi terima. Silakan lanjutkan pengecekan kondisi koleksi.');
            }

            return redirect()->back()
                ->with('error', 'Konfirmasi hanya bisa dilakukan saat koleksi sedang dalam pengiriman.');
        }

        if ($serahTerima->handover_status !== 'in_delivery') {
            return redirect()->back()
                ->with('error', 'Koleksi belum dalam status pengiriman. Silakan tunggu pengelola menandai koleksi sudah dikirim.');
        }

        $penyewaan->update([
            'status'      => 'pengecekan_kondisi',
            'received_at' => now(),
        ]);

        $serahTerima->update([
            'handover_status'       => 'condition_checking',
            'delivered_at'          => now(),
            'confirmed_received_at' => now(),
            'serah_terima_status'   => 'condition_checking',
        ]);

        $serahTerima->appendDamageTimeline(
            'condition_checking',
            'Penyewa mengkonfirmasi koleksi telah diterima. Melanjutkan ke pengecekan kondisi.'
        );

        $this->notifyBoth($serahTerima, 'condition_checking');

        return redirect()->route('penyewaan.requests.handover.show', $penyewaan)
            ->with('success', 'Penerimaan dikonfirmasi. Silakan periksa kondisi koleksi terlebih dahulu.');
    }

    // ──────────────────────────────────────────────────────────────────
    //  TAHAP 16 — Download dokumen serah terima awal
    // ──────────────────────────────────────────────────────────────────

    public function showConditionCheck(Penyewaan $penyewaan)
    {
        $this->authorizeOwner($penyewaan);

        return redirect()->route('penyewaan.requests.handover.show', $penyewaan);
    }
    
    // ───────────────────────────────────────────────────────────────────────
    //  3. BARU — submitConditionGood()
    //     Penyewa konfirmasi kondisi baik, upload foto depan & belakang,
    //     lanjut ke upload dokumen serah terima
    // ───────────────────────────────────────────────────────────────────────
    
    public function submitConditionGood(Request $request, Penyewaan $penyewaan): RedirectResponse
    {
        $this->authorizeOwner($penyewaan);

        $serahTerima = $this->ensureSerahTerima($penyewaan);

        if ($penyewaan->status !== 'pengecekan_kondisi') {
            return redirect()->back()
                ->with('error', 'Pengecekan kondisi hanya bisa dilakukan saat status pengecekan kondisi.');
        }

        $request->validate([
            'condition_front_photo' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:5120'],
            'condition_back_photo'  => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:5120'],
            'condition_video'       => ['nullable', 'file', 'mimes:mp4,mov,avi', 'max:51200'],
        ], [
            'condition_front_photo.required' => 'Foto depan koleksi wajib diunggah.',
            'condition_back_photo.required'  => 'Foto belakang koleksi wajib diunggah.',
            'condition_video.mimes'          => 'Video harus berformat MP4, MOV, atau AVI.',
            'condition_video.max'            => 'Video maksimal 50MB.',
        ]);

        $frontPath = $request->file('condition_front_photo')
            ->store('penyewaan/kondisi', 'public');
        $backPath = $request->file('condition_back_photo')
            ->store('penyewaan/kondisi', 'public');
        $videoPath = $request->hasFile('condition_video')
            ? $request->file('condition_video')->store('penyewaan/kondisi-video', 'public')
            : null;

        $serahTerima->update([
            'condition_check_status' => 'good',
            'condition_checked_at'   => now(),
            'condition_front_photo'  => $frontPath,
            'condition_back_photo'   => $backPath,
            'condition_video'        => $videoPath,
            'handover_status'        => 'delivered',
            'serah_terima_status'    => 'waiting_document',
        ]);

        $penyewaan->update(['status' => 'menunggu_dokumen_serah_terima']);

        $serahTerima->appendDamageTimeline(
            'condition_good',
            'Penyewa mengkonfirmasi koleksi diterima dalam kondisi baik beserta foto dokumentasi. Melanjutkan ke proses dokumen serah terima.'
        );

        $this->notifyBoth($serahTerima, 'condition_good');

        return redirect()->route('penyewaan.requests.handover.show', $penyewaan)
            ->with('success', 'Kondisi koleksi dikonfirmasi baik. Silakan unduh dan upload dokumen serah terima.');
    }
    
    // ───────────────────────────────────────────────────────────────────────
    //  4. BARU — submitConditionDamage()
    //     Penyewa melaporkan kerusakan saat menerima koleksi
    // ───────────────────────────────────────────────────────────────────────
    
    public function submitConditionDamage(Request $request, Penyewaan $penyewaan): RedirectResponse
    {
        $this->authorizeOwner($penyewaan);

        $serahTerima = $this->ensureSerahTerima($penyewaan);

        if ($penyewaan->status !== 'pengecekan_kondisi') {
            return redirect()->back()
                ->with('error', 'Laporan kerusakan hanya bisa dikirim saat pengecekan kondisi.');
        }

        $isKurir = $penyewaan->shipping_method_type === 'courier';

        $rules = [
            'condition_front_photo'      => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:5120'],
            'condition_back_photo'       => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:5120'],
            'damage_video'               => ['required', 'file', 'mimes:mp4,mov,avi', 'max:51200'],
            'arrival_damage_checklist'   => ['required', 'array', 'min:1'],
            'arrival_damage_description' => ['nullable', 'string', 'max:2000'],
            'buyer_decision'             => ['required', 'in:lanjut,batalkan'],
            'packing_condition_photos'   => ['required', 'array', 'min:1', 'max:5'],
            'packing_condition_photos.*' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:5120'],
            'item_descriptions'          => ['nullable', 'array'],
            'item_descriptions.*'        => ['nullable', 'string', 'max:500'],
        ];

        if ($isKurir) {
            $rules['courier_receipt_photos']   = ['required', 'array', 'min:1', 'max:3'];
            $rules['courier_receipt_photos.*'] = ['required', 'file', 'mimes:jpg,jpeg,png', 'max:5120'];
        }

        $request->validate($rules, [
            'condition_front_photo.required'    => 'Foto depan koleksi wajib diunggah.',
            'condition_back_photo.required'     => 'Foto belakang koleksi wajib diunggah.',
            'damage_video.required'             => 'Video bukti kerusakan wajib diunggah.',
            'arrival_damage_checklist.required' => 'Pilih minimal satu jenis kerusakan.',
            'buyer_decision.required'           => 'Keputusan lanjut/batalkan wajib dipilih.',
            'packing_condition_photos.required' => 'Foto kondisi packing wajib diunggah.',
            'courier_receipt_photos.required'   => 'Bukti penerimaan dari kurir wajib diunggah.',
        ]);

        $decision     = $request->input('buyer_decision');
        $allItems     = SerahTerima::arrivalDamageChecklistItems();
        $submitted    = $request->input('arrival_damage_checklist', []);
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

        $frontPath = $request->file('condition_front_photo')->store('penyewaan/damage/condition', 'public');
        $backPath  = $request->file('condition_back_photo')->store('penyewaan/damage/condition', 'public');
        $videoPath = $request->file('damage_video')->store('penyewaan/damage/video', 'public');

        $packingPaths = [];
        foreach ($request->file('packing_condition_photos') as $file) {
            $packingPaths[] = $file->store('penyewaan/damage/packing', 'public');
        }

        $courierPaths = [];
        if ($isKurir && $request->hasFile('courier_receipt_photos')) {
            foreach ($request->file('courier_receipt_photos') as $file) {
                $courierPaths[] = $file->store('penyewaan/damage/courier', 'public');
            }
        }

        $checkedLabels = collect($damageItems)->pluck('label')->implode(', ');

        $serahTerima->update([
            'condition_check_status'          => 'damaged',
            'condition_checked_at'            => now(),
            'arrival_damage_items'            => $damageItems,
            'condition_front_photo'           => $frontPath,
            'condition_back_photo'            => $backPath,
            'damage_video_path'               => $videoPath,
            'arrival_damage_description'      => $request->input('arrival_damage_description'),
            'packing_condition_photos'        => $packingPaths,
            'courier_receipt_photos'          => $courierPaths ?: null,
            'arrival_damage_reported_at'      => now(),
            'arrival_damage_buyer_decision'   => $decision,
            'handover_status'                 => 'damage_reported',
            'serah_terima_status'             => 'damage_reported',
        ]);

        $penyewaan->update(['status' => 'menunggu_review_kerusakan']);

        $serahTerima->appendDamageTimeline(
            'damage_reported',
            'Penyewa melaporkan kerusakan. Jenis: ' . $checkedLabels
                . '. Keputusan penyewa: ' . ($decision === 'lanjut' ? 'Terima dengan kompensasi' : 'Ajukan pembatalan')
                . '. Menunggu review pengelola.'
        );

        $this->notifyBoth($serahTerima, 'damage_reported');

        return redirect()->route('penyewaan.requests.handover.show', $penyewaan)
            ->with('success', 'Laporan kerusakan berhasil dikirim. Menunggu review pengelola.');
    }

    public function decideDamage(Request $request, Penyewaan $penyewaan): RedirectResponse
    {
        $this->authorizePengelola();

        $serahTerima = $this->ensureSerahTerima($penyewaan);

        if ($penyewaan->status !== 'menunggu_review_kerusakan') {
            return redirect()->back()->with('error', 'Tidak ada laporan kerusakan yang perlu direview.');
        }

        if ($serahTerima->arrival_damage_decided_at !== null) {
            return redirect()->back()->with('error', 'Laporan kerusakan sudah pernah diputuskan.');
        }

        $buyerDecision = $serahTerima->arrival_damage_buyer_decision;

        if ($buyerDecision === 'batalkan') {
            $data = $request->validate([
                'manager_decision' => ['required', 'in:setujui,tolak'],
                'notes'            => ['required', 'string', 'max:2000'],
            ]);

            if ($data['manager_decision'] === 'setujui') {
                $refundAmount = $serahTerima->calculateFullDamageRefundAmount();

                $serahTerima->update([
                    'arrival_damage_final_severity'      => 'parah',
                    'arrival_damage_severity_corrected'  => 0,
                    'arrival_damage_compensation_amount' => null,
                    'arrival_damage_manager_notes'       => $data['notes'],
                    'arrival_damage_decided_at'          => now(),
                    'arrival_damage_decided_by'          => auth()->user()->name,
                    'arrival_damage_manager_decision'    => 'setujui_pembatalan',
                    'handover_status'                    => 'damage_reviewed',
                ]);

                $penyewaan->update(['status' => 'menunggu_data_rekening']);

                $message = 'Pembatalan disetujui. Refund penuh (dikurangi ongkir awal, ditambah ongkir pengembalian): estimasi Rp '
                    . number_format($refundAmount, 0, ',', '.') . '. Penyewa diminta mengembalikan koleksi ke museum dan mengisi data rekening.';
            } else {
                $serahTerima->update([
                    'arrival_damage_final_severity'      => 'ringan',
                    'arrival_damage_severity_corrected'  => true,
                    'arrival_damage_compensation_amount' => null,
                    'arrival_damage_manager_notes'       => $data['notes'],
                    'arrival_damage_decided_at'          => now(),
                    'arrival_damage_decided_by'          => auth()->user()->name,
                    'arrival_damage_manager_decision'    => 'tolak_pembatalan',
                    'handover_status'                    => 'delivered',
                    'serah_terima_status'                => 'waiting_document',
                ]);

                $penyewaan->update(['status' => 'menunggu_dokumen_serah_terima']);

                $message = 'Klaim pembatalan ditolak. Penyewa akan dinotifikasi dan diminta melanjutkan proses serah terima.';
            }
        } else {
            $maxCompensation = $serahTerima->calculateBaseDamageRefundAmount();

            $data = $request->validate([
                'manager_decision'    => ['required', 'in:setujui,tolak'],
                'notes'               => ['required', 'string', 'max:2000'],
                'compensation_amount' => ['required_if:manager_decision,setujui', 'nullable', 'integer', 'min:1', 'max:' . max(1, $maxCompensation)],
            ]);

            if ($data['manager_decision'] === 'setujui') {
                $serahTerima->update([
                    'arrival_damage_final_severity'      => 'ringan',
                    'arrival_damage_severity_corrected'  => false,
                    'arrival_damage_compensation_amount' => (int) $data['compensation_amount'],
                    'arrival_damage_manager_notes'       => $data['notes'],
                    'arrival_damage_decided_at'          => now(),
                    'arrival_damage_decided_by'          => auth()->user()->name,
                    'arrival_damage_manager_decision'    => 'setujui_kompensasi',
                    'handover_status'                    => 'damage_reviewed',
                ]);

                $penyewaan->update(['status' => 'menunggu_data_rekening']);

                $message = 'Kompensasi Rp ' . number_format((int) $data['compensation_amount'], 0, ',', '.')
                    . ' disetujui. Penyewa diminta mengisi data rekening untuk proses transfer.';
            } else {
                $serahTerima->update([
                    'arrival_damage_final_severity'      => 'ringan',
                    'arrival_damage_severity_corrected'  => true,
                    'arrival_damage_compensation_amount' => null,
                    'arrival_damage_manager_notes'       => $data['notes'],
                    'arrival_damage_decided_at'          => now(),
                    'arrival_damage_decided_by'          => auth()->user()->name,
                    'arrival_damage_manager_decision'    => 'tolak_kompensasi',
                    'handover_status'                    => 'delivered',
                    'serah_terima_status'                => 'waiting_document',
                ]);

                $penyewaan->update(['status' => 'menunggu_dokumen_serah_terima']);

                $message = 'Klaim kompensasi ditolak. Penyewa akan dinotifikasi dan diminta melanjutkan proses serah terima tanpa kompensasi.';
            }
        }

        $serahTerima->appendDamageTimeline('damage_reviewed', $message);
        $this->notifyBoth($serahTerima, 'damage_reviewed');

        return redirect()->route('pengelola.penyewaan.handover.show', $penyewaan)
            ->with('success', $message);
    }

    public function submitBankAccount(Request $request, Penyewaan $penyewaan): RedirectResponse
    {
        $this->authorizeOwner($penyewaan);

        if ($penyewaan->status !== 'menunggu_data_rekening') {
            return redirect()->back()->with('error', 'Pengisian rekening hanya diperlukan setelah review kerusakan selesai.');
        }

        $serahTerima = $this->ensureSerahTerima($penyewaan);

        if ($serahTerima->isDamageCancellation()) {
            return $this->submitReturnAndBankAccount($request, $penyewaan);
        }

        $data = $request->validate([
            'refund_bank_name'      => ['required', 'string', 'max:100'],
            'refund_account_number' => ['required', 'string', 'max:50'],
            'refund_account_holder' => ['required', 'string', 'max:255'],
        ]);

        $serahTerima->update(array_merge($data, [
            'refund_bank_submitted_at' => now(),
        ]));

        $penyewaan->update(['status' => 'menunggu_refund_kerusakan']);

        $timelineMessage = $serahTerima->isDamageCompensation()
            ? 'Penyewa mengisi data rekening untuk kompensasi: '
                . $data['refund_bank_name'] . ' - ' . $data['refund_account_number']
                . ' a.n. ' . $data['refund_account_holder']
                . '. Menunggu transfer kompensasi dari pengelola.'
            : 'Penyewa mengisi data rekening: ' . $data['refund_bank_name']
                . ' - ' . $data['refund_account_number'] . ' a.n. ' . $data['refund_account_holder']
                . '. Menunggu transfer refund dari pengelola.';

        $serahTerima->appendDamageTimeline('bank_submitted', $timelineMessage);

        $successMessage = $serahTerima->isDamageCompensation()
            ? 'Data rekening berhasil dikirim. Pengelola akan memproses transfer kompensasi.'
            : 'Data rekening berhasil dikirim. Pengelola akan memproses refund secara manual.';

        return redirect()->route('penyewaan.requests.handover.show', $penyewaan)
            ->with('success', $successMessage);
    }

    protected function submitReturnAndBankAccount(Request $request, Penyewaan $penyewaan): RedirectResponse
    {
        $serahTerima = $this->ensureSerahTerima($penyewaan);

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
        ]);

        $proofPath = $request->file('return_shipping_proof')
            ->store('penyewaan/return-shipping/proofs', 'public');

        $serahTerima->update([
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
            'handover_status'              => 'return_shipment_submitted',
        ]);

        $penyewaan->update(['status' => 'menunggu_penerimaan_koleksi']);

        $serahTerima->appendDamageTimeline(
            'return_shipment_submitted',
            'Penyewa mengirimkan info pengembalian koleksi ke museum dan data rekening refund.'
                . ' Metode: ' . $data['return_shipment_method']
                . '. Ongkir pengembalian: Rp ' . number_format((int) $data['return_shipping_cost'], 0, ',', '.')
                . ($data['return_shipment_tracking'] ? '. Resi: ' . $data['return_shipment_tracking'] : '')
                . '. Menunggu pengelola konfirmasi penerimaan koleksi di museum.'
        );

        return redirect()->route('penyewaan.requests.handover.show', $penyewaan)
            ->with('success', 'Data pengembalian koleksi dan rekening berhasil dikirim. Menunggu pengelola mengkonfirmasi penerimaan koleksi di museum.');
    }

    public function downloadDocument(Penyewaan $penyewaan)
    {
        $this->authorizeOwnerOrPengelola($penyewaan);
        $serahTerima = $this->ensureSerahTerima($penyewaan);

        $documentService = new DocumentService();
        
        // Regenerate PDF dari blade terbaru (hapus file DOCX lama jika ada)
        if ($serahTerima->handover_document_path) {
            $oldDocx = str_replace('.pdf', '.docx', $serahTerima->handover_document_path);
            if (Storage::disk('public')->exists($oldDocx)) {
                Storage::disk('public')->delete($oldDocx);
            }
        }

        $pdfPath = $documentService->generateHandoverDocumentPdf($penyewaan, $serahTerima);
        $serahTerima->update(['handover_document_path' => $pdfPath]);

        return Storage::disk('public')->download(
            $pdfPath,
            'Serah-Terima-' . $serahTerima->document_number . '.pdf'
        );
    }

    // ──────────────────────────────────────────────────────────────────
    //  TAHAP 17 — Pengguna upload dokumen serah terima yang ditandatangani
    // ──────────────────────────────────────────────────────────────────

    public function showUploadForm(Penyewaan $penyewaan)
    {
        $this->authorizeOwnerOrPengelola($penyewaan);

        if ($penyewaan->status !== 'menunggu_dokumen_serah_terima') {
            return redirect()->route('penyewaan.requests.handover.show', $penyewaan)
                ->with('error', 'Upload dokumen hanya bisa dilakukan setelah koleksi diterima.');
        }

        $serahTerima = $this->ensureSerahTerima($penyewaan);

        return view('serah_terima.upload', compact('penyewaan', 'serahTerima'));
    }

    public function uploadDocument(Request $request, Penyewaan $penyewaan): RedirectResponse
    {
        $this->authorizeOwner($penyewaan);

        if ($penyewaan->status !== 'menunggu_dokumen_serah_terima') {
            return redirect()->route('penyewaan.requests.handover.show', $penyewaan)
                ->with('error', 'Upload dokumen hanya bisa dilakukan setelah koleksi diterima.');
        }

        $serahTerima = $this->ensureSerahTerima($penyewaan);

        $request->validate([
            'tenant_signed_document'          => ['required', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
            'checklist_frame_safe'            => ['sometimes', 'boolean'],
            'checklist_no_tears'              => ['sometimes', 'boolean'],
            'checklist_color_normal'          => ['sometimes', 'boolean'],
            'checklist_glass_safe'            => ['sometimes', 'boolean'],
            'checklist_no_mold'               => ['sometimes', 'boolean'],
            'checklist_matches_documentation' => ['sometimes', 'boolean'],
            'initial_condition_note'          => ['nullable', 'string', 'max:2000'],
            'tenant_notes'                    => ['nullable', 'string', 'max:2000'],
            'received_condition_photo'        => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $signedPath = $request->file('tenant_signed_document')
            ->store('handover_documents/signed', 'public');

        $photoPath = null;
        if ($request->hasFile('received_condition_photo')) {
            $photoPath = $request->file('received_condition_photo')
                ->store('handover_documents/photos', 'public');
        }

        $serahTerima->update([
            'tenant_signed_document_path'     => $signedPath,
            'tenant_uploaded_at'              => now(),
            'serah_terima_status'             => 'document_uploaded',
            'validation_notes'                => null,
            'checklist_frame_safe'            => $request->boolean('checklist_frame_safe'),
            'checklist_no_tears'              => $request->boolean('checklist_no_tears'),
            'checklist_color_normal'          => $request->boolean('checklist_color_normal'),
            'checklist_glass_safe'            => $request->boolean('checklist_glass_safe'),
            'checklist_no_mold'               => $request->boolean('checklist_no_mold'),
            'checklist_matches_documentation' => $request->boolean('checklist_matches_documentation'),
            'initial_condition_note'          => $request->input('initial_condition_note'),
            'tenant_notes'                    => $request->input('tenant_notes'),
            'received_condition_photo_path'   => $photoPath ?? $serahTerima->received_condition_photo_path,
        ]);

        $documentService = new DocumentService();
        $newDocPath = $documentService->generateHandoverDocumentPdf($penyewaan, $serahTerima->fresh());
        $serahTerima->update(['handover_document_path' => $newDocPath]);

        $penyewaan->update(['status' => 'verifikasi_serah_terima']);

        SerahTerimaLog::create([
            'serah_terima_id' => $serahTerima->id,
            'status'          => 'document_uploaded',
            'performed_by'    => auth()->user()->name,
            'message'         => 'Penyewa mengunggah dokumen serah terima yang telah ditandatangani beserta checklist kondisi koleksi.',
        ]);

        $this->notifyBoth($serahTerima, 'document_uploaded');

        return redirect()->route('penyewaan.requests.handover.show', $penyewaan)
            ->with('success', 'Dokumen serah terima berhasil diunggah. Menunggu validasi pengelola.');
    }

    // ──────────────────────────────────────────────────────────────────
    //  TAHAP 18 — Pengelola validasi serah terima → penyewaan aktif
    // ──────────────────────────────────────────────────────────────────

    public function validateHandover(Request $request, Penyewaan $penyewaan): RedirectResponse
    {
        $this->authorizePengelola();

        $serahTerima = $this->ensureSerahTerima($penyewaan);

        if ($serahTerima->serah_terima_status !== 'document_uploaded') {
            return redirect()->back()
                ->with('error', 'Belum ada dokumen serah terima yang perlu divalidasi.');
        }

        $data = $request->validate([
            'action'           => ['required', 'in:validate,reject'],
            'validation_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($data['action'] === 'validate') {
            $serahTerima->update([
                'serah_terima_status' => 'validated',
                'handover_status'     => 'handover_completed',
                'validation_notes'    => $data['validation_notes'],
                'validated_at'        => now(),
                'validated_by'        => auth()->user()->name,
            ]);

            $penyewaan->update([
                'status'            => 'aktif',
                'rental_started_at' => now(),
            ]);

            SerahTerimaLog::create([
                'serah_terima_id' => $serahTerima->id,
                'status'          => 'validated',
                'performed_by'    => auth()->user()->name,
                'message'         => 'Pengelola memvalidasi dokumen serah terima. Penyewaan resmi aktif.',
            ]);

            $this->notifyBoth($serahTerima, 'validated');

            return redirect()->route('pengelola.penyewaan.handover.show', $penyewaan)
                ->with('success', 'Serah terima divalidasi. Penyewaan sekarang aktif.');
        }

        $serahTerima->update([
            'serah_terima_status' => 'rejected',
            'validation_notes'    => $data['validation_notes'],
        ]);

        $penyewaan->update(['status' => 'menunggu_dokumen_serah_terima']);

        SerahTerimaLog::create([
            'serah_terima_id' => $serahTerima->id,
            'status'          => 'rejected',
            'performed_by'    => auth()->user()->name,
            'message'         => 'Pengelola menolak dokumen serah terima. Alasan: ' . ($data['validation_notes'] ?? '-') . '. Penyewa diminta upload ulang.',
        ]);

        $this->notifyBoth($serahTerima, 'rejected');

        return redirect()->route('pengelola.penyewaan.handover.show', $penyewaan)
            ->with('success', 'Dokumen ditolak. Penyewa diminta upload ulang.');
    }

    // ──────────────────────────────────────────────────────────────────
    //  TAHAP 20 — Pengelola tandai masa sewa berakhir → pengembalian
    // ──────────────────────────────────────────────────────────────────

    public function markAsReturning(Penyewaan $penyewaan): RedirectResponse
    {
        $this->authorizePengelola();

        if ($penyewaan->status !== 'aktif') {
            return redirect()->back()->with('error', 'Penyewaan harus dalam status aktif.');
        }

        $serahTerima = $this->ensureSerahTerima($penyewaan);

        $penyewaan->update(['status' => 'pengembalian']);

        SerahTerimaLog::create([
            'serah_terima_id' => $serahTerima->id,
            'status'          => 'pengembalian',
            'performed_by'    => auth()->user()->name,
            'message'         => 'Masa penyewaan berakhir. Pengelola memulai proses pengembalian koleksi.',
        ]);

        $this->notifyBoth($serahTerima, 'pengembalian');

        return redirect()->route('pengelola.penyewaan.handover.show', $penyewaan)
            ->with('success', 'Status diubah ke pengembalian. Menunggu penyewa mengirimkan info pengiriman balik.');
    }

    // ──────────────────────────────────────────────────────────────────
    //  TAHAP 20b — Pengguna submit info pengiriman balik koleksi
    // ──────────────────────────────────────────────────────────────────

    public function submitReturnShipment(Request $request, Penyewaan $penyewaan): RedirectResponse
    {
        $this->authorizeOwner($penyewaan);

        $serahTerima = $this->ensureSerahTerima($penyewaan);

        $isPengembalianNormal  = $penyewaan->status === 'pengembalian';
        $isPembatalanKerusakan = $penyewaan->status === 'dibatalkan'
            && $serahTerima->isArrivalDamageCancellation();

        if (! $isPengembalianNormal && ! $isPembatalanKerusakan) {
            return redirect()->back()->with('error', 'Status tidak sesuai untuk pengiriman balik.');
        }

        if ($serahTerima->return_shipment_submitted_at) {
            return redirect()->back()->with('error', 'Info pengiriman balik sudah pernah dikirim.');
        }

        $data = $request->validate([
            'return_shipment_method'       => ['required', 'string', 'max:255'],
            'return_shipment_officer'      => ['required', 'string', 'max:255'],
            'return_shipment_tracking'     => ['nullable', 'string', 'max:100'],
            'return_shipment_scheduled_at' => ['required', 'date'],
            'return_shipment_notes'        => ['nullable', 'string', 'max:2000'],
        ]);

        $serahTerima->update([
            'return_shipment_method'       => $data['return_shipment_method'],
            'return_shipment_officer'      => $data['return_shipment_officer'],
            'return_shipment_tracking'     => $data['return_shipment_tracking'] ?? null,
            'return_shipment_scheduled_at' => $data['return_shipment_scheduled_at'],
            'return_shipment_notes'        => $data['return_shipment_notes'] ?? null,
            'return_shipment_submitted_at' => now(),
            'handover_status'              => 'return_shipment_submitted',
        ]);

        $logMessage = 'Penyewa mengirimkan informasi pengiriman balik koleksi.'
            . ' Metode: ' . $data['return_shipment_method']
            . '. Petugas: ' . $data['return_shipment_officer']
            . ($data['return_shipment_tracking'] ? '. Resi: ' . $data['return_shipment_tracking'] : '') . '.';

        if ($isPembatalanKerusakan) {
            $logMessage .= ' (Pengiriman balik akibat pembatalan sewa karena kerusakan saat pengiriman.)';
        }

        SerahTerimaLog::create([
            'serah_terima_id' => $serahTerima->id,
            'status'          => 'return_shipment_submitted',
            'performed_by'    => auth()->user()->name,
            'message'         => $logMessage,
        ]);

        $this->notifyBoth($serahTerima, 'return_shipment_submitted');

        $successMessage = $isPembatalanKerusakan
            ? 'Info pengiriman balik berhasil dikirim. Menunggu pengelola mengkonfirmasi penerimaan koleksi di museum sebelum proses refund dilanjutkan.'
            : 'Info pengiriman balik berhasil dikirim. Menunggu pengelola mengkonfirmasi penerimaan koleksi di museum.';

        return redirect()->route('penyewaan.requests.handover.show', $penyewaan)
            ->with('success', $successMessage);
    }

    public function returnShipmentStatus(Request $request, Penyewaan $penyewaan): RedirectResponse
    {
        $this->authorizeOwner($penyewaan);

        $serahTerima = $this->ensureSerahTerima($penyewaan);

        $isPengembalianNormal  = $penyewaan->status === 'pengembalian';
        $isPembatalanKerusakan = $penyewaan->status === 'menunggu_penerimaan_koleksi'
            && $serahTerima->isDamageCancellation();

        if ((! $isPengembalianNormal && ! $isPembatalanKerusakan) || ! $serahTerima->return_shipment_submitted_at) {
            return redirect()->back()->with('error', 'Status tidak sesuai untuk memperbarui tracking balik.');
        }

        if ($serahTerima->return_shipment_tracking) {
            return redirect()->back()->with('error', 'Return tracking sudah berupa nomor resi. Gunakan tracker kurir.');
        }

        if ($serahTerima->collection_arrived_at) {
            return redirect()->back()->with('info', 'Koleksi sudah dikonfirmasi tiba di museum.');
        }

        $data = $request->validate([
            'return_shipment_status' => ['required', 'in:dikemas,siap_dikirim,dalam_perjalanan,tiba_di_tujuan'],
            'catatan_status'         => ['nullable', 'string', 'max:500'],
        ]);

        $statusLabels = SerahTerima::returnShipmentStatuses();
        $statusLabel  = $statusLabels[$data['return_shipment_status']] ?? $data['return_shipment_status'];
        $statusOrder  = array_keys($statusLabels);
        $current      = $serahTerima->return_shipment_status;
        $currentIndex = $current ? array_search($current, $statusOrder) : -1;
        $newIndex     = array_search($data['return_shipment_status'], $statusOrder);

        if ($current && $newIndex <= $currentIndex) {
            return redirect()->back()->with('error', 'Status tidak bisa mundur ke tahap sebelumnya.');
        }

        if ($current && $newIndex > $currentIndex + 1) {
            return redirect()->back()->with('error', 'Update status harus berurutan.');
        }

        $timeline = $serahTerima->return_shipment_timeline ?? [];
        $timeline[] = [
            'status'    => $data['return_shipment_status'],
            'label'     => $statusLabel,
            'catatan'   => $data['catatan_status'] ?? null,
            'timestamp' => now()->toDateTimeString(),
            'by'        => auth()->user()->name,
        ];

        $serahTerima->update([
            'return_shipment_status'   => $data['return_shipment_status'],
            'return_shipment_timeline' => $timeline,
        ]);

        if ($isPembatalanKerusakan) {
            $serahTerima->appendDamageTimeline(
                $data['return_shipment_status'],
                'Status pengembalian koleksi diperbarui: ' . $statusLabel
                    . ($data['catatan_status'] ? '. Catatan: ' . $data['catatan_status'] : '')
            );
        } else {
            SerahTerimaLog::create([
                'serah_terima_id' => $serahTerima->id,
                'status'          => $data['return_shipment_status'],
                'performed_by'    => auth()->user()->name,
                'message'         => 'Status pengiriman balik diperbarui: ' . $statusLabel
                    . ($data['catatan_status'] ? '. Catatan: ' . $data['catatan_status'] : ''),
            ]);
        }

        $this->notifyBoth($serahTerima, $data['return_shipment_status']);

        return redirect()->back()->with('success', 'Status pengiriman balik diperbarui: ' . $statusLabel);
    }

    // ──────────────────────────────────────────────────────────────────
    //  TAHAP 20c — Pengelola konfirmasi koleksi sudah tiba di museum
    // ──────────────────────────────────────────────────────────────────

    public function confirmCollectionArrived(Penyewaan $penyewaan): RedirectResponse
    {
        $this->authorizePengelola();

        $serahTerima = $this->ensureSerahTerima($penyewaan);

        $isPengembalianNormal  = $penyewaan->status === 'pengembalian';
        $isPembatalanKerusakan = $penyewaan->status === 'menunggu_penerimaan_koleksi'
            && $serahTerima->isDamageCancellation();

        if (! $isPengembalianNormal && ! $isPembatalanKerusakan) {
            return redirect()->back()->with('error', 'Status tidak sesuai.');
        }

        if (! $serahTerima->return_shipment_submitted_at) {
            return redirect()->back()
                ->with('error', 'Penyewa belum mengirimkan informasi pengiriman balik.');
        }

        if ($serahTerima->collection_arrived_at) {
            return redirect()->route('pengelola.penyewaan.handover.show', $penyewaan)
                ->with('info', 'Koleksi sudah dikonfirmasi tiba. Lanjutkan proses refund.');
        }

        if ($isPembatalanKerusakan) {
            $refundAmount = $serahTerima->calculateFullDamageRefundAmount();

            $serahTerima->update(['collection_arrived_at' => now()]);
            $penyewaan->update(['status' => 'menunggu_refund_kerusakan']);

            $serahTerima->appendDamageTimeline(
                'collection_arrived',
                'Pengelola mengkonfirmasi koleksi sudah tiba kembali di museum. '
                    . 'Melanjutkan proses refund Rp ' . number_format($refundAmount, 0, ',', '.')
                    . ' (termasuk ongkir pengembalian Rp ' . number_format((int) ($serahTerima->return_shipping_cost ?? 0), 0, ',', '.') . ').'
            );

            $this->notifyBoth($serahTerima, 'collection_arrived');

            return redirect()->route('pengelola.penyewaan.handover.show', $penyewaan)
                ->with('success', 'Koleksi dikonfirmasi tiba di museum. Silakan proses refund ke rekening penyewa.');
        }

        $serahTerima->update([
            'collection_arrived_at' => now(),
            'handover_status'       => 'collection_arrived',
        ]);

        SerahTerimaLog::create([
            'serah_terima_id' => $serahTerima->id,
            'status'          => 'collection_arrived',
            'performed_by'    => auth()->user()->name,
            'message'         => 'Pengelola mengkonfirmasi koleksi sudah tiba kembali di museum. Siap untuk pemeriksaan kondisi.',
        ]);

        $this->notifyBoth($serahTerima, 'collection_arrived');

        return redirect()->route('pengelola.penyewaan.handover.show', $penyewaan)
        ->with('success', 'Koleksi dikonfirmasi tiba. Silakan lakukan pemeriksaan kondisi koleksi.');
    }

    // ──────────────────────────────────────────────────────────────────
    //  Helper: proses refund 100% untuk pembatalan akibat kerusakan pengiriman
    // ──────────────────────────────────────────────────────────────────

    protected function processCancellationRefund(Penyewaan $penyewaan, SerahTerima $serahTerima): RedirectResponse
    {
        $refundAmount = $penyewaan->calculateCancellationRefundAmount();

        $serahTerima->update([
            'has_damage'             => true,
            'final_damage_cost'      => 0,
            'damage_cost'            => 0,
            'final_inspection_at'    => now(),
            'final_inspection_by'    => auth()->user()->name,
            'return_condition_notes' => 'Pengembalian akibat pembatalan sewa karena kerusakan saat pengiriman. '
                . 'Refund biaya sewa + deposit (ongkir tidak dikembalikan).',
        ]);

        $penyewaan->update([
            'deposit_status' => 'paid',
            'status'         => 'menunggu_konfirmasi_refund',
        ]);
        $serahTerima->update(['handover_status' => 'waiting_refund_proof']);

        SerahTerimaLog::create([
            'serah_terima_id' => $serahTerima->id,
            'status'          => 'waiting_refund_proof',
            'performed_by'    => auth()->user()->name,
            'message'         => 'Koleksi telah diterima kembali di museum. Sewa dibatalkan akibat kerusakan saat pengiriman. '
                . 'Pengelola perlu mengisi bukti transfer refund Rp '
                . number_format($refundAmount, 0, ',', '.')
                . ' (biaya sewa + deposit) ke rekening penyewa.',
        ]);

        $this->notifyBoth($serahTerima, 'waiting_refund_proof');

        return redirect()->route('pengelola.penyewaan.handover.show', $penyewaan)
            ->with('success', 'Koleksi diterima kembali. Silakan proses pengembalian biaya sewa + deposit ke rekening penyewa.');
    }

    // ──────────────────────────────────────────────────────────────────
    //  TAHAP 21 — Pengelola periksa kondisi, tentukan kerusakan,
    //             generate dokumen pengembalian + proses deposit sekaligus
    // ──────────────────────────────────────────────────────────────────

    public function showReturnForm(Penyewaan $penyewaan)
    {
        $this->authorizePengelola();

        if ($penyewaan->status !== 'pengembalian') {
            return redirect()->back()->with('error', 'Form pengembalian hanya tersedia saat status pengembalian.');
        }

        $serahTerima = $this->ensureSerahTerima($penyewaan);

        // Guard: koleksi harus sudah dikonfirmasi tiba dulu
        if (! $serahTerima->collection_arrived_at) {
            return redirect()->route('pengelola.penyewaan.handover.show', $penyewaan)
                ->with('error', 'Konfirmasi penerimaan koleksi di museum terlebih dahulu sebelum melakukan pemeriksaan.');
        }

        // Guard: jangan bisa isi ulang jika sudah diproses
        if ($serahTerima->return_document_path) {
            return redirect()->route('pengelola.penyewaan.handover.show', $penyewaan)
                ->with('info', 'Pemeriksaan kondisi sudah dilakukan.');
        }

        $depositAmount = $penyewaan->deposit_amount ?: $penyewaan->calculateDeposit();

        return view('serah_terima.return', compact('penyewaan', 'serahTerima', 'depositAmount'));
    }

    public function processReturn(Request $request, Penyewaan $penyewaan): RedirectResponse
    {
        $this->authorizePengelola();

        if ($penyewaan->status !== 'pengembalian') {
            return redirect()->back()->with('error', 'Pengembalian hanya bisa diproses saat status pengembalian.');
        }

        $serahTerima = $this->ensureSerahTerima($penyewaan);

        if (! $serahTerima->collection_arrived_at) {
            return redirect()->back()
                ->with('error', 'Konfirmasi penerimaan koleksi di museum terlebih dahulu.');
        }

        if ($serahTerima->return_document_path) {
            return redirect()->route('pengelola.penyewaan.handover.show', $penyewaan)
                ->with('error', 'Pemeriksaan kondisi sudah pernah dilakukan.');
        }

        // ── Validasi dasar ──
        $data = $request->validate([
            'has_damage'             => ['required', 'boolean'],
            'damage_items'           => ['nullable', 'array'],
            'damage_items.*.checked' => ['nullable'],
            'damage_items.*.level'   => ['nullable', 'in:ringan,sedang,berat'],
            'damage_items.*.cost'    => ['nullable', 'integer', 'min:0'],
            'damage_items.*.note'    => ['nullable', 'string', 'max:500'],
            'damage_cost'            => ['nullable', 'integer', 'min:0'],
            'return_condition_photo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:10240'],
        ]);

        $hasDamage     = (bool) $data['has_damage'];
        
        // ── PASTIKAN DEPOSIT AMOUNT ──
        $depositAmount = $penyewaan->deposit_amount ?: $penyewaan->calculateDeposit();
        
        // Jika deposit masih 0, set default 50% dari subtotal
        if ($depositAmount <= 0) {
            $depositAmount = (int) round(($penyewaan->subtotal_amount ?? 0) * 0.5);
            $penyewaan->update(['deposit_amount' => $depositAmount]);
            \Log::info('processReturn - deposit_amount set to default: ' . $depositAmount);
        }

        // ── Proses damage_items jika ada kerusakan ──
        $damageCost        = 0;
        $damageItemsDetail = [];
        $damageTypeLabel   = null;
        $dominantLevel     = null;

        // Label mapping untuk display
        $damageTypeLabels = [
            'frame' => 'Frame / Bingkai Rusak',
            'tears' => 'Sobekan Kanvas / Lukisan',
            'color' => 'Kerusakan Warna / Cat',
            'glass' => 'Kaca Pelindung Retak / Pecah',
            'mold'  => 'Jamur / Kerusakan Biologis',
            'other' => 'Kerusakan Lainnya',
        ];

        // Level priority untuk menentukan dominant level
        $levelPriority = ['berat' => 3, 'sedang' => 2, 'ringan' => 1];

        if ($hasDamage && ! empty($data['damage_items'])) {
            foreach ($data['damage_items'] as $key => $item) {
                // Hanya proses item yang dicentang
                if (empty($item['checked'])) continue;

                $itemCost  = (int) ($item['cost'] ?? 0);
                $itemLevel = $item['level'] ?? 'ringan';
                $itemNote  = $item['note'] ?? null;
                $itemLabel = $damageTypeLabels[$key] ?? ucfirst($key);

                $damageCost        += $itemCost;
                $damageItemsDetail[] = [
                    'key'   => $key,
                    'label' => $itemLabel,
                    'level' => $itemLevel,
                    'cost'  => $itemCost,
                    'note'  => $itemNote,
                ];

                // Track dominant level (ambil yang paling parah)
                if (
                    $dominantLevel === null ||
                    ($levelPriority[$itemLevel] ?? 0) > ($levelPriority[$dominantLevel] ?? 0)
                ) {
                    $dominantLevel = $itemLevel;
                }
            }

            // Buat ringkasan jenis kerusakan untuk final_damage_type
            $damageTypeLabel = implode(', ', array_column($damageItemsDetail, 'label'));

            // Validasi: jika has_damage=1 tapi tidak ada item yang dicentang
            if (empty($damageItemsDetail)) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['damage_items' => 'Pilih minimal satu jenis kerusakan.']);
            }
        } elseif ($hasDamage && empty($data['damage_items'])) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['damage_items' => 'Pilih minimal satu jenis kerusakan.']);
        }

        // ── Upload foto kondisi ──
        $photoPath = null;
        if ($request->hasFile('return_condition_photo')) {
            $photoPath = $request->file('return_condition_photo')
                ->store('return_documents/photos', 'public');
        }

        // ── Simpan hasil pemeriksaan ──
        $serahTerima->update([
            'has_damage'          => $hasDamage,
            'final_damage_type'   => $hasDamage ? $damageTypeLabel : null,
            'final_damage_level'  => $hasDamage ? $dominantLevel : null,
            'final_damage_cost'   => $damageCost,
            'damage_cost'         => $damageCost,
            'damage_items_detail' => $hasDamage ? $damageItemsDetail : null,
            'damage_notes'        => $hasDamage
                ? implode(' | ', array_filter(array_column($damageItemsDetail, 'note')))
                : null,
            'return_condition_photo_path' => $photoPath,
            'final_inspection_at'         => now(),
            'final_inspection_by'         => auth()->user()->name,
        ]);

        // ── Generate dokumen pengembalian ──
        $documentService = new DocumentService();
        $returnDocPath = $documentService->generateReturnDocumentPdf($penyewaan, $serahTerima->fresh());
        $serahTerima->update(['return_document_path' => $returnDocPath]);

        // ── Reset SEMUA sisa data dari alur kerusakan KEDATANGAN (arrival damage) ──
        // Supaya tidak "nyangkut" dan bocor ke alur refund/kompensasi PENGEMBALIAN ini.
        $serahTerima->update([
            // Data refund/kompensasi
            'refund_processed_at'        => null,
            'refund_processed_by'        => null,
            'refund_transfer_proof_path' => null,
            'refund_amount'              => null,
            'refund_confirmed_at'        => null,
            'refund_confirmed_by'        => null,
            'refund_bank_name'           => null,
            'refund_account_number'      => null,
            'refund_account_holder'      => null,
            'refund_bank_submitted_at'   => null,
            'refund_date'                => null,
            'refund_notes'               => null,

            // Keputusan & status kerusakan kedatangan
            'arrival_damage_manager_decision'    => null,
            'arrival_damage_manager_notes'       => null,
            'arrival_damage_decided_at'          => null,
            'arrival_damage_decided_by'          => null,
            'arrival_damage_final_severity'      => null,
            'arrival_damage_severity_corrected'  => false,
            'arrival_damage_compensation_amount' => null,
        ]);

        // ── Log pemeriksaan ──
        SerahTerimaLog::create([
            'serah_terima_id' => $serahTerima->id,
            'status'          => 'return_inspected',
            'performed_by'    => auth()->user()->name,
            'message'         => 'Pengelola menyelesaikan pemeriksaan kondisi koleksi. '
                . ($hasDamage
                    ? 'Ditemukan ' . count($damageItemsDetail) . ' kerusakan: '
                    . implode(', ', array_column($damageItemsDetail, 'label'))
                    . '. Total biaya: Rp ' . number_format($damageCost, 0, ',', '.')
                    : 'Koleksi dalam kondisi baik, tidak ada kerusakan.'),
        ]);

        // ══════════════════════════════════════════════════════════════
        //  PROSES DEPOSIT
        // ══════════════════════════════════════════════════════════════

        // ── TAMBAHKAN LOGGING ──
        \Log::info('processReturn - depositAmount: ' . $depositAmount);
        \Log::info('processReturn - damageCost: ' . $damageCost);
        \Log::info('processReturn - hasDamage: ' . ($hasDamage ? 'true' : 'false'));

        if (! $hasDamage) {
            // ── KASUS 1: Tidak ada kerusakan ──
            $penyewaan->update([
                'deposit_status' => 'paid',
                'status'         => 'menunggu_konfirmasi_refund',
            ]);
            $serahTerima->update(['handover_status' => 'waiting_refund_proof']);

            SerahTerimaLog::create([
                'serah_terima_id' => $serahTerima->id,
                'status'          => 'waiting_refund_proof',
                'performed_by'    => auth()->user()->name,
                'message'         => 'Tidak ada kerusakan. Dokumen pengembalian dibuat. '
                    . 'Pengelola perlu mengisi bukti transfer pengembalian deposit ke rekening penyewa.',
            ]);

            $this->notifyBoth($serahTerima, 'waiting_refund_proof');

            return redirect()->route('pengelola.penyewaan.handover.show', $penyewaan)
                ->with('success', 'Pemeriksaan selesai. Koleksi dalam kondisi baik. Silakan proses pengembalian deposit ke rekening penyewa.');

        } elseif ($damageCost <= $depositAmount) {
            // ── KASUS 2: Kerusakan ≤ deposit ──
            $sisaRefund = $depositAmount - $damageCost;

            $penyewaan->update([
                'deposit_status' => 'paid',
                'status'         => 'menunggu_konfirmasi_refund',
            ]);
            $serahTerima->update(['handover_status' => 'waiting_refund_proof']);

            SerahTerimaLog::create([
                'serah_terima_id' => $serahTerima->id,
                'status'          => 'waiting_refund_proof',
                'performed_by'    => auth()->user()->name,
                'message'         => 'Kerusakan ditemukan (Rp ' . number_format($damageCost, 0, ',', '.') . '). '
                    . 'Deposit dipotong. Sisa deposit yang dikembalikan: Rp '
                    . number_format($sisaRefund, 0, ',', '.') . '.',
            ]);

            $this->notifyBoth($serahTerima, 'waiting_refund_proof');

            $sisaLabel = $sisaRefund > 0
                ? ' Sisa Rp ' . number_format($sisaRefund, 0, ',', '.') . ' perlu ditransfer ke rekening penyewa.'
                : ' Deposit habis digunakan untuk biaya kerusakan, tidak ada yang perlu ditransfer.';

            return redirect()->route('pengelola.penyewaan.handover.show', $penyewaan)
                ->with('success', 'Pemeriksaan selesai. Deposit dipotong Rp '
                    . number_format($damageCost, 0, ',', '.') . ' untuk kerusakan.' . $sisaLabel);

        } else {
            // ── KASUS 3: Kerusakan > deposit ──
            $additionalCharge = $damageCost - $depositAmount;
            $invoiceNumber    = 'DMG-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4));
            $orderId          = 'dmg-' . $penyewaan->id . '-' . time();

            $invoice = DamageInvoice::create([
                'penyewaan_id'      => $penyewaan->id,
                'invoice_number'    => $invoiceNumber,
                'damage_type'       => $damageTypeLabel ?? '-',
                'damage_level'      => $dominantLevel ?? 'sedang',
                'restoration_cost'  => $damageCost,
                'deposit_amount'    => $depositAmount,
                'deposit_used'      => $depositAmount,
                'additional_charge' => $additionalCharge,
                'damage_notes'      => $serahTerima->damage_notes,
                'order_id'          => $orderId,
                'status'            => 'unpaid',
                'created_by'        => auth()->user()->name,
            ]);

            // ── Update status DULU sebelum Midtrans — supaya tidak gagal meski token error ──
            $penyewaan->update([
                'deposit_status' => 'additional_payment_required',
                'status'         => 'menunggu_pembayaran_kerusakan',
            ]);
            $serahTerima->update(['handover_status' => 'waiting_damage_payment']);

            SerahTerimaLog::create([
                'serah_terima_id' => $serahTerima->id,
                'status'          => 'damage_invoice_created',
                'performed_by'    => auth()->user()->name,
                'message'         => 'Invoice kerusakan dibuat: ' . $invoiceNumber
                    . '. Deposit hangus: Rp ' . number_format($depositAmount, 0, ',', '.')
                    . '. Tagihan tambahan: Rp ' . number_format($additionalCharge, 0, ',', '.') . '.',
            ]);

            // ── Midtrans snap token — error tidak menghentikan proses ──
            try {
                $midtrans  = new MidtransService();
                $snapToken = $midtrans->getSnapToken([
                    'transaction_details' => [
                        'order_id'     => $orderId,
                        'gross_amount' => $additionalCharge,
                    ],
                    'customer_details' => [
                        'first_name' => $penyewaan->contact_name ?? $penyewaan->nama_instansi,
                        'email'      => $penyewaan->contact_email,
                        'phone'      => $penyewaan->contact_phone,
                    ],
                    'item_details' => [[
                        'id'       => 'damage-' . $penyewaan->id,
                        'price'    => $additionalCharge,
                        'quantity' => 1,
                        'name'     => 'Biaya Kerusakan: ' . Str::limit($penyewaan->painting->title, 40),
                    ]],
                ]);
                $invoice->update(['snap_token' => $snapToken]);
            } catch (\Throwable $e) {
                Log::error('Midtrans damage invoice snap token gagal: ' . $e->getMessage(), [
                    'penyewaan_id' => $penyewaan->id,
                    'order_id'     => $orderId,
                ]);
                // Lanjut — invoice tetap ada, penyewa bisa bayar via halaman deposit
            }

            $this->notifyBoth($serahTerima, 'damage_invoice_created');

            return redirect()->route('pengelola.penyewaan.handover.show', $penyewaan)
                ->with('success', 'Pemeriksaan selesai. Deposit hangus. Invoice kerusakan Rp '
                    . number_format($additionalCharge, 0, ',', '.') . ' telah dibuat dan dikirim ke penyewa.');
        }
    }

    // ──────────────────────────────────────────────────────────────────
    //  TAHAP 22a — Pengelola input bukti transfer refund deposit (manual)
    //  Dipakai untuk kasus 1 (tidak ada kerusakan) dan kasus 2 (sisa deposit)
    // ──────────────────────────────────────────────────────────────────

    public function storeRefundProof(Request $request, Penyewaan $penyewaan): RedirectResponse
    {
        $this->authorizePengelola();

        $serahTerima = $this->ensureSerahTerima($penyewaan);

        // ── Refund kerusakan saat penerimaan (alur baru, sama dengan pembelian) ──
        if ($penyewaan->status === 'menunggu_refund_kerusakan') {
            if ($serahTerima->refund_processed_at !== null) {
                return redirect()->back()->with('error', 'Bukti refund sudah pernah diinput.');
            }

            $maxRefund = $serahTerima->isFinalSeverityParah()
                ? $serahTerima->calculateFullDamageRefundAmount()
                : (int) ($serahTerima->arrival_damage_compensation_amount ?? 0);

            $data = $request->validate([
                'refund_amount'  => ['required', 'integer', 'min:1', 'max:' . max(1, $maxRefund)],
                'refund_date'    => ['required', 'date'],
                'transfer_proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
                'refund_notes'   => ['nullable', 'string', 'max:1000'],
            ]);

            $proofPath = $request->file('transfer_proof')
                ->store('penyewaan/refund/proofs', 'public');

            $serahTerima->update([
                'refund_amount'              => $data['refund_amount'],
                'refund_transfer_proof_path' => $proofPath,
                'refund_date'                => $data['refund_date'],
                'refund_notes'               => $data['refund_notes'] ?? null,
                'refund_processed_at'        => now(),
                'refund_processed_by'        => auth()->user()->name,
            ]);

            $penyewaan->update(['status' => 'menunggu_konfirmasi_refund']);

            $timelineMessage = 'Pengelola mengunggah bukti transfer '
                . ($serahTerima->isDamageCompensation() ? 'kompensasi' : 'refund')
                . '. Nominal: Rp ' . number_format($data['refund_amount'], 0, ',', '.') . '.';

            $serahTerima->appendDamageTimeline('refund_processed', $timelineMessage);
            $this->notifyBoth($serahTerima, 'refund_processed');

            $successMessage = $serahTerima->isDamageCompensation()
                ? 'Bukti transfer kompensasi berhasil disimpan. Menunggu konfirmasi penerimaan dari penyewa.'
                : 'Bukti transfer refund penuh berhasil disimpan. Menunggu konfirmasi penyewa.';

            return redirect()->route('pengelola.penyewaan.handover.show', $penyewaan)
                ->with('success', $successMessage);
        }

        // ── Refund deposit akhir masa sewa (alur lama) ──
        if ($penyewaan->status !== 'menunggu_konfirmasi_refund') {
            return redirect()->back()->with('error', 'Status tidak sesuai untuk proses refund.');
        }

        if ($penyewaan->depositRefund) {
            return redirect()->back()->with('error', 'Bukti refund sudah pernah diinput.');
        }

        $isCancellation = $serahTerima->isArrivalDamageCancellation()
            && ! $serahTerima->isDamageCancellation();

        if ($isCancellation) {
            $depositAmount = $penyewaan->calculateCancellationRefundAmount();
            $damageCost    = 0;
            $sisaRefund    = $depositAmount;
            $maxRefund     = $depositAmount;
        } else {
            $depositAmount = $penyewaan->deposit_amount ?: $penyewaan->calculateDeposit();
            $damageCost    = (int) ($serahTerima->final_damage_cost ?? $serahTerima->damage_cost ?? 0);
            $sisaRefund    = max(0, $depositAmount - $damageCost);
            $maxRefund     = $depositAmount;
        }

        $data = $request->validate([
            'refund_amount'   => ['required', 'integer', 'min:0', 'max:' . $maxRefund],
            'bank_name'       => ['required', 'string', 'max:100'],
            'account_number'  => ['required', 'string', 'max:50'],
            'account_holder'  => ['required', 'string', 'max:255'],
            'refund_date'     => ['required', 'date'],
            'transfer_proof'  => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'notes'           => ['nullable', 'string', 'max:1000'],
        ]);

        $proofPath = $request->file('transfer_proof')
            ->store('deposit_refunds/proofs', 'public');

        $damageDeduction = $isCancellation ? 0 : ($depositAmount - $data['refund_amount']);

        DepositRefund::create([
            'penyewaan_id'        => $penyewaan->id,
            'deposit_amount'      => $depositAmount,
            'damage_deduction'    => $damageDeduction,
            'refund_amount'       => $data['refund_amount'],
            'bank_name'           => $data['bank_name'],
            'account_number'      => $data['account_number'],
            'account_holder'      => $data['account_holder'],
            'refund_date'         => $data['refund_date'],
            'transfer_proof_path' => $proofPath,
            'notes'               => $data['notes'] ?? ($isCancellation
                ? 'Refund pembatalan akibat kerusakan saat pengiriman (biaya sewa + deposit, ongkir tidak dikembalikan).'
                : null),
            'status'              => 'processed',
            'processed_by'        => auth()->user()->name,
        ]);

        // Tentukan deposit_status
        $depositStatus = 'returned';
        if (! $isCancellation) {
            if ($damageCost > 0 && $data['refund_amount'] <= 0) {
                $depositStatus = 'deducted';
            } elseif ($damageCost > 0) {
                $depositStatus = 'partially_returned';
            }
        }

        // Tetap menunggu_konfirmasi_refund — penyewa harus konfirmasi dana diterima dulu
        $penyewaan->update([
            'deposit_status' => $depositStatus,
        ]);

        $serahTerima->update(['handover_status' => 'refund_proof_submitted']);

        $logMessage = $isCancellation
            ? 'Bukti transfer refund pembatalan diinput. Nominal: Rp '
                . number_format($data['refund_amount'], 0, ',', '.')
                . ' (biaya sewa + deposit). Menunggu konfirmasi penyewa.'
            : 'Bukti transfer refund deposit diinput. Nominal: Rp '
                . number_format($data['refund_amount'], 0, ',', '.')
                . ($damageDeduction > 0
                    ? '. Potongan kerusakan: Rp ' . number_format($damageDeduction, 0, ',', '.')
                    : '')
                . '. Menunggu konfirmasi penyewa.';

        SerahTerimaLog::create([
            'serah_terima_id' => $serahTerima->id,
            'status'          => 'refund_processed',
            'performed_by'    => auth()->user()->name,
            'message'         => $logMessage,
        ]);

        $this->notifyBoth($serahTerima, 'refund_processed');

        $successMessage = $isCancellation
            ? 'Bukti transfer berhasil disimpan. Penyewa akan diminta mengkonfirmasi penerimaan dana.'
            : 'Bukti transfer berhasil disimpan. Penyewa akan diminta mengkonfirmasi penerimaan refund.';

        return redirect()->route('pengelola.penyewaan.handover.show', $penyewaan)
            ->with('success', $successMessage);
    }

    public function confirmRefund(Penyewaan $penyewaan): RedirectResponse
    {
        $this->authorizeOwner($penyewaan);

        if ($penyewaan->status !== 'menunggu_konfirmasi_refund') {
            return redirect()->back()->with('error', 'Status tidak sesuai untuk konfirmasi refund.');
        }

        $serahTerima = $this->ensureSerahTerima($penyewaan);

        // ── Konfirmasi refund/kompensasi kerusakan saat penerimaan (data di SerahTerima) ──
        if ($serahTerima->refund_processed_at && ! $penyewaan->depositRefund) {
            if (! $serahTerima->refund_transfer_proof_path) {
                return redirect()->back()->with('error', 'Pengelola belum mengunggah bukti transfer.');
            }

            if ($serahTerima->isDamageCompensation()) {
                $serahTerima->update([
                    'refund_confirmed_at' => now(),
                    'refund_confirmed_by' => auth()->id(),
                    'handover_status'     => 'delivered',
                    'serah_terima_status' => 'waiting_document',
                ]);

                $penyewaan->update(['status' => 'menunggu_dokumen_serah_terima']);

                $serahTerima->appendDamageTimeline(
                    'compensation_confirmed',
                    'Penyewa mengkonfirmasi penerimaan kompensasi. Melanjutkan ke proses dokumen serah terima.'
                );

                $this->notifyBoth($serahTerima, 'compensation_confirmed');

                return redirect()->route('penyewaan.requests.handover.show', $penyewaan)
                    ->with('success', 'Penerimaan kompensasi dikonfirmasi. Silakan lanjutkan upload dokumen serah terima.');
            }

            if ($serahTerima->isDamageCancellation()) {
                $serahTerima->update([
                    'handover_status'          => 'returned',
                    'collection_returned_at'   => now(),
                    'refund_confirmed_at'      => now(),
                    'refund_confirmed_by'      => auth()->id(),
                ]);

                $penyewaan->update([
                    'status'         => 'dibatalkan',
                    'deposit_status' => 'returned',
                ]);

                $penyewaan->painting->update(['available' => true]);

                $serahTerima->appendDamageTimeline(
                    'refund_confirmed',
                    'Penyewa mengkonfirmasi penerimaan refund. Proses pembatalan akibat kerusakan saat pengiriman selesai.'
                );

                $this->notifyBoth($serahTerima, 'completed');

                return redirect()->route('penyewaan.requests.show', $penyewaan)
                    ->with('success', 'Refund dikonfirmasi diterima. Proses pembatalan penyewaan selesai.');
            }

            return redirect()->back()->with('error', 'Jenis refund kerusakan tidak dikenali.');
        }

        if (! $penyewaan->depositRefund) {
            return redirect()->back()->with('error', 'Pengelola belum mengunggah bukti transfer refund.');
        }

        // Pengembalian normal — lanjut ke TTD dokumen pengembalian
        $penyewaan->update(['status' => 'menunggu_ttd_pengembalian']);

        $serahTerima->update([
            'handover_status'     => 'waiting_return_signature',
            'refund_confirmed_at' => now(),
            'refund_confirmed_by' => auth()->id(),
        ]);

        return redirect()->route('penyewaan.requests.handover.show', $penyewaan)
            ->with('success', 'Konfirmasi penerimaan refund berhasil. Silakan unduh dan tandatangani dokumen pengembalian.');
    }

    // ──────────────────────────────────────────────────────────────────
    //  TAHAP 22b — Download dokumen pengembalian
    // ──────────────────────────────────────────────────────────────────

    public function downloadReturnDocument(Penyewaan $penyewaan)
    {
        $this->authorizeOwnerOrPengelola($penyewaan);
        $serahTerima = $this->ensureSerahTerima($penyewaan);

        if (! $serahTerima->return_document_path
            || ! Storage::disk('public')->exists($serahTerima->return_document_path)) {
            
            // Generate PDF jika belum ada
            $documentService = new DocumentService();
            $pdfPath = $documentService->generateReturnDocumentPdf($penyewaan, $serahTerima);
            $serahTerima->update(['return_document_path' => $pdfPath]);
            $serahTerima->refresh();
        }

        // Cek apakah file yang ada adalah PDF, jika DOCX maka regenerate
        $currentPath = $serahTerima->return_document_path;
        if (!str_ends_with($currentPath, '.pdf')) {
            $documentService = new DocumentService();
            $pdfPath = $documentService->generateReturnDocumentPdf($penyewaan, $serahTerima);
            $serahTerima->update(['return_document_path' => $pdfPath]);
            $serahTerima->refresh();
        }

        return Storage::disk('public')->download(
            $serahTerima->return_document_path,
            'Pengembalian-' . $serahTerima->document_number . '.pdf'
        );
    }

    // ──────────────────────────────────────────────────────────────────
    //  TAHAP 23 — Pengguna upload dokumen pengembalian yang sudah di-TTD
    //  Hanya bisa dilakukan setelah:
    //  - Kasus 1 & 2: refund sudah diproses pengelola
    //  - Kasus 3: invoice kerusakan sudah lunas
    // ──────────────────────────────────────────────────────────────────

    public function uploadSignedReturnDocument(Request $request, Penyewaan $penyewaan): RedirectResponse
    {
        $this->authorizeOwner($penyewaan);

        $serahTerima = $this->ensureSerahTerima($penyewaan);

        // Kasus 3: kalau invoice sudah lunas tapi status belum bertransisi
        // (webhook Midtrans belum/terlewat), transisikan di sini sebagai fallback.
        $this->maybeAdvanceAfterDamagePayment($penyewaan, $serahTerima);
        $penyewaan->refresh();
        $serahTerima->refresh();

        // Guard: pastikan status sudah boleh upload TTD
        if ($penyewaan->status !== 'menunggu_ttd_pengembalian') {
            if ($penyewaan->status === 'menunggu_pembayaran_kerusakan') {
                return redirect()->back()
                    ->with('error', 'Silakan lunasi tagihan kerusakan terlebih dahulu sebelum menandatangani dokumen pengembalian.');
            }

            return redirect()->back()
                ->with('error', 'Belum saatnya mengunggah dokumen pengembalian. Tunggu proses deposit selesai.');
        }

        // Guard: pastikan dokumen sudah digenerate pengelola
        if (! $serahTerima->return_document_path
            || ! Storage::disk('public')->exists($serahTerima->return_document_path)) {
            return redirect()->back()
                ->with('error', 'Dokumen pengembalian belum tersedia. Tunggu pengelola menyiapkan dokumen terlebih dahulu.');
        }

        // Guard: pastikan handover_status sudah waiting_return_signature
        if (! in_array($serahTerima->handover_status, ['waiting_return_signature'])) {
            return redirect()->back()
                ->with('error', 'Belum saatnya mengunggah dokumen TTD.');
        }

        $request->validate([
            'tenant_signed_return_document' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
        ]);

        $path = $request->file('tenant_signed_return_document')
            ->store('return_documents/signed', 'public');

        $serahTerima->update([
            'tenant_signed_return_document_path' => $path,
            'tenant_signed_return_at'            => now(),
            'handover_status'                    => 'return_document_uploaded',
        ]);

        $penyewaan->update(['status' => 'menunggu_konfirmasi_selesai']);

        SerahTerimaLog::create([
            'serah_terima_id' => $serahTerima->id,
            'status'          => 'return_document_uploaded',
            'performed_by'    => auth()->user()->name,
            'message'         => 'Penyewa mengunggah dokumen pengembalian yang telah ditandatangani. Menunggu pengelola mengkonfirmasi selesai.',
        ]);

        $this->notifyBoth($serahTerima, 'return_document_uploaded');

        return redirect()->route('penyewaan.requests.handover.show', $penyewaan)
            ->with('success', 'Dokumen berhasil diunggah. Menunggu pengelola mengkonfirmasi penyewaan selesai.');
    }

    // ──────────────────────────────────────────────────────────────────
    //  TAHAP 24 — Pengelola konfirmasi penyewaan selesai
    // ──────────────────────────────────────────────────────────────────

    public function confirmRentalCompleted(Penyewaan $penyewaan): RedirectResponse
    {
        $this->authorizePengelola();

        if ($penyewaan->status !== 'menunggu_konfirmasi_selesai') {
            return redirect()->back()->with('error', 'Status tidak sesuai.');
        }

        $serahTerima = $this->ensureSerahTerima($penyewaan);

        if (! $serahTerima->tenant_signed_return_document_path) {
            return redirect()->back()
                ->with('error', 'Penyewa belum mengunggah dokumen pengembalian yang ditandatangani.');
        }

        $isCancelledDueToDamage = $serahTerima->arrival_damage_manager_decision === 'setuju_batal';

        $serahTerima->update([
            'collection_returned_at' => now(),
            'handover_status'        => 'returned',
        ]);

        $penyewaan->update([
            'status' => $isCancelledDueToDamage ? 'dibatalkan' : 'selesai',
        ]);

        // Koleksi kembali tersedia
        $penyewaan->painting->update(['available' => true]);

        SerahTerimaLog::create([
            'serah_terima_id' => $serahTerima->id,
            'status'          => 'completed',
            'performed_by'    => auth()->user()->name,
            'message'         => $isCancelledDueToDamage
                ? 'Pengelola mengkonfirmasi proses pembatalan & pengembalian deposit selesai. Sewa resmi dibatalkan. Koleksi kembali tersedia.'
                : 'Pengelola mengkonfirmasi penyewaan selesai. Koleksi kembali tersedia.',
        ]);

        $this->notifyBoth($serahTerima, 'completed');

        return redirect()->route('pengelola.penyewaan.handover.show', $penyewaan)
            ->with('success', $isCancelledDueToDamage
                ? 'Proses pembatalan & refund deposit selesai. Koleksi kembali tersedia.'
                : 'Penyewaan dinyatakan selesai. Koleksi kembali tersedia.');
    }
    
    // ──────────────────────────────────────────────────────────────────
    //  Update status pengiriman umum (backward compat)
    // ──────────────────────────────────────────────────────────────────

    public function updateStatus(Request $request, Penyewaan $penyewaan): RedirectResponse
    {
        $this->authorizePengelola();
        $serahTerima = $this->ensureSerahTerima($penyewaan);

        $status = $request->validate([
            'status' => ['required', 'in:preparing_delivery,in_delivery,delivered,handover_completed'],
        ])['status'];

        $updateData = ['handover_status' => $status];

        if ($status === 'in_delivery' && ! $serahTerima->shipped_at) {
            $updateData['shipped_at'] = now();
        }

        if ($status === 'delivered') {
            $updateData['delivered_at'] = now();
            $penyewaan->update(['status' => 'menunggu_dokumen_serah_terima']);
        }

        $serahTerima->update($updateData);

        SerahTerimaLog::create([
            'serah_terima_id' => $serahTerima->id,
            'status'          => $status,
            'performed_by'    => auth()->user()->name,
            'message'         => 'Pengelola memperbarui status pengiriman menjadi: ' . str_replace('_', ' ', $status) . '.',
        ]);

        $this->notifyBoth($serahTerima, $status);

        return redirect()->route('pengelola.penyewaan.handover.show', $penyewaan)
            ->with('success', 'Status pengiriman berhasil diperbarui.');
    }

    public function confirmCollectionReturned(Penyewaan $penyewaan): RedirectResponse
    {
        return $this->confirmRentalCompleted($penyewaan);
    }

    public function markReturnShipped(Penyewaan $penyewaan): RedirectResponse
    {
        $this->authorizeOwner($penyewaan);
    
        // Redirect ke show — flow lama tidak digunakan lagi
        return redirect()->route('penyewaan.requests.handover.show', $penyewaan)
            ->with('info', 'Gunakan form "Kirim Info Pengiriman Balik" untuk mengirimkan informasi pengembalian koleksi.');
    }

    public function downloadInitialReturnDocument(Penyewaan $penyewaan)
    {
        $this->authorizeOwnerOrPengelola($penyewaan);
        $serahTerima = $this->ensureSerahTerima($penyewaan);

        $documentService = new DocumentService();

        // Selalu regenerate dari blade terbaru
        $pdfPath = $documentService->generateReturnDocumentPdf($penyewaan, $serahTerima);
        $serahTerima->update(['return_document_path' => $pdfPath]);

        return Storage::disk('public')->download(
            $pdfPath,
            'Pengembalian-' . $serahTerima->document_number . '.pdf'
        );
    }

    public function uploadSignedInitialReturnDocument(Request $request, Penyewaan $penyewaan): RedirectResponse
    {
        $this->authorizeOwner($penyewaan);
    
        // Delegate ke method baru
        return $this->uploadSignedReturnDocument($request, $penyewaan);
    }

    // ──────────────────────────────────────────────────────────────────
    //  UPDATE SUB-STATUS MANAGER (dikemas → siap_dikirim → dalam_perjalanan → tiba_di_tujuan)
    // ──────────────────────────────────────────────────────────────────

    public function managerStatus(Request $request, Penyewaan $penyewaan): RedirectResponse
    {
        $this->authorizePengelola();

        $serahTerima = $this->ensureSerahTerima($penyewaan);

        if ($penyewaan->shipping_method_type !== 'manager') {
            return redirect()->back()->with('error', 'Fitur ini hanya untuk pengiriman via pengelola.');
        }

        if ($serahTerima->handover_status !== 'in_delivery') {
            return redirect()->back()->with('error', 'Koleksi harus dalam status in_delivery.');
        }

        $data = $request->validate([
            'manager_delivery_status' => ['required', 'in:dikemas,siap_dikirim,dalam_perjalanan,tiba_di_tujuan'],
            'catatan_status'          => ['nullable', 'string', 'max:500'],
        ]);

        $statusLabels = self::managerDeliveryStatuses();
        $statusLabel  = $statusLabels[$data['manager_delivery_status']] ?? $data['manager_delivery_status'];

        // Validasi urutan — tidak boleh mundur atau loncat
        $statusOrder  = array_keys($statusLabels);
        $currentMds   = $serahTerima->manager_delivery_status;
        $currentIndex = $currentMds ? array_search($currentMds, $statusOrder) : -1;
        $newIndex     = array_search($data['manager_delivery_status'], $statusOrder);

        if ($currentMds && $newIndex <= $currentIndex) {
            return redirect()->back()->with('error', 'Status tidak bisa mundur ke tahap sebelumnya.');
        }

        if ($currentMds && $newIndex > $currentIndex + 1) {
            return redirect()->back()->with('error', 'Update status harus berurutan.');
        }

        $timeline   = $serahTerima->manager_delivery_timeline ?? [];
        $timeline[] = [
            'status'    => $data['manager_delivery_status'],
            'label'     => $statusLabel,
            'catatan'   => $data['catatan_status'] ?? null,
            'timestamp' => now()->toDateTimeString(),
            'by'        => auth()->user()->name,
        ];

        $serahTerima->update([
            'manager_delivery_status'   => $data['manager_delivery_status'],
            'manager_delivery_timeline' => $timeline,
        ]);

        SerahTerimaLog::create([
            'serah_terima_id' => $serahTerima->id,
            'status'          => $data['manager_delivery_status'],
            'performed_by'    => auth()->user()->name,
            'message'         => 'Status pengiriman diperbarui: ' . $statusLabel
                . ($data['catatan_status'] ? '. Catatan: ' . $data['catatan_status'] : ''),
        ]);

        $this->notifyBoth($serahTerima, $data['manager_delivery_status']);

        return redirect()->route('pengelola.penyewaan.handover.show', $penyewaan)
            ->with('success', 'Status pengiriman diperbarui: ' . $statusLabel);
    }

    // ──────────────────────────────────────────────────────────────────
    //  TRACKING DATA (Binderbyte) — untuk penyewaan via kurir
    // ──────────────────────────────────────────────────────────────────

    public function trackingData(Penyewaan $penyewaan)
    {
        $this->authorizeOwnerOrPengelola($penyewaan);

        $serahTerima = $this->ensureSerahTerima($penyewaan);

        $isReturn = request()->get('for') === 'return';

        if ($isReturn) {
            if (! $serahTerima->return_shipment_tracking || ! $serahTerima->return_shipment_method) {
                return response()->json(['success' => false, 'message' => 'Data resi atau kurir pengiriman balik belum tersedia.']);
            }
        } else {
            if ($penyewaan->shipping_method_type !== 'courier') {
                return response()->json(['success' => false, 'message' => 'Bukan pengiriman kurir.']);
            }
            if (! $serahTerima->delivery_tracking_number || ! $serahTerima->delivery_method) {
                return response()->json(['success' => false, 'message' => 'Data resi atau kurir belum tersedia.']);
            }
        }

        $binderbyte = app(\App\Services\BinderbyteService::class);
        $refresh    = request()->boolean('refresh');

        try {
            $result = $refresh
                ? $binderbyte->refresh(
                    $isReturn ? $serahTerima->return_shipment_tracking : $serahTerima->delivery_tracking_number,
                    $isReturn ? $serahTerima->return_shipment_method : $serahTerima->delivery_method
                )
                : $binderbyte->track(
                    $isReturn ? $serahTerima->return_shipment_tracking : $serahTerima->delivery_tracking_number,
                    $isReturn ? $serahTerima->return_shipment_method : $serahTerima->delivery_method
                );

            return response()->json($result);
        } catch (\Throwable $e) {
            \Log::error('Tracking penyewaan error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data tracking.',
            ]);
        }
    }

    // ──────────────────────────────────────────────────────────────────
    //  STATIC HELPER — daftar sub-status pengiriman manager
    // ──────────────────────────────────────────────────────────────────

    public static function managerDeliveryStatuses(): array
    {
        return [
            'dikemas'          => '📦 Dikemas',
            'siap_dikirim'     => '✅ Siap Dikirim',
            'dalam_perjalanan' => '🚚 Dalam Perjalanan',
            'tiba_di_tujuan'   => '🏁 Tiba di Tujuan',
        ];
    }

    // ──────────────────────────────────────────────────────────────────
    //  Private Helpers
    // ──────────────────────────────────────────────────────────────────

    /**
     * Kasus 3 (kerusakan > deposit): setelah invoice kerusakan lunas (via Midtrans),
     * transisikan status penyewaan & handover_status ke tahap TTD pengembalian.
     * Dipanggil sebagai fallback di show() & uploadSignedReturnDocument(),
     * supaya transisi tetap terjadi meski webhook Midtrans belum/terlewat memproses.
     */
    protected function maybeAdvanceAfterDamagePayment(Penyewaan $penyewaan, SerahTerima $serahTerima): void
    {
        if ($penyewaan->status !== 'menunggu_pembayaran_kerusakan') {
            return;
        }

        $invoice = $penyewaan->damageInvoice;

        if (! $invoice || ! $invoice->isPaid()) {
            return;
        }

        $penyewaan->update(['status' => 'menunggu_ttd_pengembalian']);
        $serahTerima->update(['handover_status' => 'waiting_return_signature']);

        SerahTerimaLog::create([
            'serah_terima_id' => $serahTerima->id,
            'status'          => 'damage_invoice_paid',
            'performed_by'    => 'Sistem',
            'message'         => 'Tagihan kerusakan telah lunas. Melanjutkan ke tahap tanda tangan dokumen pengembalian.',
        ]);

        $this->notifyBoth($serahTerima, 'damage_invoice_paid');
    }

    protected function ensureSerahTerima(Penyewaan $penyewaan): SerahTerima
    {
        $serahTerima = $penyewaan->serahTerima;

        if (! $serahTerima) {
            if ($penyewaan->payment_status === \App\Enums\PaymentStatus::PAID->value
                && in_array($penyewaan->status, array_merge(Penyewaan::ACTIVE_STATUSES, ['dibatalkan']), true)) {

                $documentService = new \App\Services\DocumentService();

                $serahTerima = $penyewaan->serahTerima()->create([
                    'document_number'        => 'HT-' . now()->format('YmdHis') . '-' . \Illuminate\Support\Str::upper(\Illuminate\Support\Str::random(4)),
                    'handover_status'        => 'waiting_handover',
                    'handover_document_path' => '',
                ]);

                // Gunakan PDF, bukan DOCX
                $handoverPath = $documentService->generateHandoverDocumentPdf($penyewaan, $serahTerima);
                $serahTerima->update(['handover_document_path' => $handoverPath]);

                $serahTerima->logs()->create([
                    'status'       => 'waiting_handover',
                    'performed_by' => 'Sistem',
                    'message'      => 'Dokumen serah terima dibuat otomatis (recovery).',
                ]);

                $penyewaan->refresh();
                return $serahTerima;
            }

            abort(404, 'Data serah terima tidak ditemukan.');
        }

        return $serahTerima;
    }

    protected function notifyBoth(SerahTerima $serahTerima, string $event): void
    {
        try {
            $serahTerima->penyewaan->user->notify(
                new SerahTerimaStatusNotification($serahTerima, $event)
            );
            User::where('role', 'pengelola')->get()
                ->each->notify(new SerahTerimaStatusNotification($serahTerima, $event));
        } catch (\Throwable $e) {
            \Log::warning('Notifikasi serah terima gagal: ' . $e->getMessage());
        }
    }

    protected function authorizeOwnerOrPengelola(Penyewaan $penyewaan): void
    {
        $user = auth()->user();
        if ($user->role !== 'pengelola' && $user->id !== $penyewaan->user_id) {
            abort(403);
        }
    }

    protected function authorizeOwner(Penyewaan $penyewaan): void
    {
        if (auth()->id() !== $penyewaan->user_id) {
            abort(403);
        }
    }

    protected function authorizePengelola(): void
    {
        if (auth()->user()->role !== 'pengelola') {
            abort(403);
        }
    }
}