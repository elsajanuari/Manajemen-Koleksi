<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SerahTerima extends Model
{
    use HasFactory;

    protected $table = 'serah_terimas';

    protected $fillable = [
        'penyewaan_id',
        'document_number',
        'handover_status',

        // Delivery (tahap 13-14)
        'delivery_method',
        'delivery_location',
        'delivery_officer',
        'delivery_tracking_number',
        'delivery_notes',
        'delivery_scheduled_at',
        'recipient_name',
        'shipped_at',
        'delivered_at',

        'dispatch_front_photo',
        'dispatch_back_photo',
        'dispatch_packing_photos',
        'dispatch_video_path',

        // Sub-status & timeline pengiriman oleh pengelola
        'manager_delivery_status',
        'manager_delivery_timeline',

        // Dokumen serah terima awal (tahap 16)
        'handover_document_path',
        'generated_file',
        'signed_handover_path',

        // Kondisi awal (tahap 17)
        'initial_condition_note',
        'initial_condition_photo_path',
        'checklist_frame_safe',
        'checklist_no_tears',
        'checklist_color_normal',
        'checklist_glass_safe',
        'checklist_no_mold',
        'checklist_matches_documentation',
        'condition_notes',
        'tenant_notes',
        'received_condition_photo_path',

        // Upload dokumen penyewa (tahap 17)
        'uploaded_file',
        'uploaded_at',
        'tenant_signed_document_path',
        'tenant_uploaded_at',

        // Status & validasi (tahap 18)
        'status',
        'serah_terima_status',
        'validation_notes',
        'validated_at',
        'validated_by',

        // Konfirmasi terima (tahap 15)
        'confirmed_received_at',

        // ── BARU: Pengecekan kondisi saat penerimaan ──────────────────────
        'condition_check_status',       // 'good' | 'damaged'
        'condition_checked_at',
        'condition_front_photo',        // foto depan (kondisi baik)
        'condition_back_photo',         // foto belakang (kondisi baik)
        'condition_video',              // video opsional (kondisi baik)

        // Kerusakan saat penerimaan
        'arrival_damage_checklist',     // JSON [{key, label, checked}] (legacy)
        'arrival_damage_items',         // JSON [{key, label, checked, description}]
        'arrival_damage_photos',        // JSON [path, ...]
        'arrival_condition_front_photo',// foto depan (ada kerusakan)
        'arrival_condition_back_photo', // foto belakang (ada kerusakan)
        'damage_video_path',            // video bukti kerusakan (wajib)
        'arrival_damage_severity',      // 'ringan' | 'parah' (legacy)
        'arrival_damage_description',
        'arrival_damage_tenant_decision', // legacy: 'lanjutkan' | 'batalkan'
        'arrival_damage_buyer_decision',  // 'lanjut' | 'batalkan'
        'arrival_damage_reported_at',
        'packing_condition_photos',     // JSON foto packing
        'courier_receipt_photos',       // JSON bukti kurir

        // Keputusan pengelola atas kerusakan saat penerimaan
        'arrival_damage_manager_decision',
        'arrival_damage_manager_notes',
        'arrival_damage_decided_at',
        'arrival_damage_decided_by',
        'arrival_damage_final_severity',
        'arrival_damage_severity_corrected',
        'arrival_damage_compensation_amount',
        'damage_handling_timeline',

        // Refund kerusakan saat penerimaan
        'refund_bank_name',
        'refund_account_number',
        'refund_account_holder',
        'refund_bank_submitted_at',
        'refund_amount',
        'refund_transfer_proof_path',
        'refund_date',
        'refund_notes',
        'refund_processed_at',
        'refund_processed_by',
        'return_shipping_cost',
        'return_shipping_proof_path',
        // ─────────────────────────────────────────────────────────────────

        // Pengembalian (tahap 21-23)
        'return_date',
        'return_condition_notes',
        'return_condition_photo_path',
        'return_checklist_frame_safe',
        'return_checklist_no_tears',
        'return_checklist_color_normal',
        'return_checklist_glass_safe',
        'return_checklist_no_mold',
        'return_checklist_matches_documentation',
        'damage_notes',
        'damage_cost',
        'return_document_path',
        'final_inspection_at',
        'final_inspection_by',
        'final_checklist_frame_safe',
        'final_checklist_no_tears',
        'final_checklist_color_normal',
        'final_checklist_glass_safe',
        'final_checklist_no_mold',
        'final_checklist_packaging_safe',
        'final_checklist_matches_documentation',
        'final_inspection_notes',
        'final_inspection_photo_path',
        'collection_arrived_at',
        'has_damage',
        'final_damage_type',
        'final_damage_level',
        'final_damage_cost',
        'final_damage_notes',
        'damage_items_detail',

        // Pengiriman balik dari penyewa
        'return_shipment_method',
        'return_shipment_officer',
        'return_shipment_tracking',
        'return_shipment_status',
        'return_shipment_timeline',
        'return_shipment_scheduled_at',
        'return_shipment_notes',
        'return_shipment_submitted_at',
        'return_shipped_at',

        // Dokumen pengembalian ditandatangani penyewa
        'initial_return_document_path',
        'tenant_signed_return_document_path',
        'tenant_signed_return_at',

        // Konfirmasi pengelola
        'collection_returned_at',

        'refund_confirmed_at',
        'refund_confirmed_by',
    ];

    protected $casts = [
        // ── Datetime ──────────────────────────────────────────────────────
        'shipped_at'                   => 'datetime',
        'delivered_at'                 => 'datetime',
        'dispatch_packing_photos' => 'array',
        'delivery_scheduled_at'        => 'datetime',
        'confirmed_received_at'        => 'datetime',
        'uploaded_at'                  => 'datetime',
        'tenant_uploaded_at'           => 'datetime',
        'validated_at'                 => 'datetime',
        'return_date'                  => 'datetime',
        'final_inspection_at'          => 'datetime',
        'collection_arrived_at'        => 'datetime',
        'return_shipment_scheduled_at' => 'datetime',
        'return_shipment_submitted_at' => 'datetime',
        'tenant_signed_return_at'      => 'datetime',
        'collection_returned_at'       => 'datetime',
        'return_shipped_at'            => 'datetime',
        'refund_confirmed_at'          => 'datetime',

        // ── BARU: Datetime kondisi penerimaan ─────────────────────────────
        'condition_checked_at'          => 'datetime',
        'arrival_damage_reported_at'    => 'datetime',
        'arrival_damage_decided_at'     => 'datetime',
        'refund_bank_submitted_at'      => 'datetime',
        'refund_date'                   => 'date',
        'refund_processed_at'           => 'datetime',
        'arrival_damage_severity_corrected' => 'boolean',
        'arrival_damage_compensation_amount' => 'integer',
        'refund_amount'                 => 'integer',
        'return_shipping_cost'          => 'integer',
        'arrival_damage_items'          => 'array',
        'packing_condition_photos'      => 'array',
        'courier_receipt_photos'        => 'array',
        'damage_handling_timeline'      => 'array',
        // ─────────────────────────────────────────────────────────────────

        // ── Boolean checklist awal ────────────────────────────────────────
        'checklist_frame_safe'            => 'boolean',
        'checklist_no_tears'              => 'boolean',
        'checklist_color_normal'          => 'boolean',
        'checklist_glass_safe'            => 'boolean',
        'checklist_no_mold'               => 'boolean',
        'checklist_matches_documentation' => 'boolean',

        // ── Boolean checklist pengembalian ────────────────────────────────
        'return_checklist_frame_safe'            => 'boolean',
        'return_checklist_no_tears'              => 'boolean',
        'return_checklist_color_normal'          => 'boolean',
        'return_checklist_glass_safe'            => 'boolean',
        'return_checklist_no_mold'               => 'boolean',
        'return_checklist_matches_documentation' => 'boolean',

        // ── Boolean checklist final ───────────────────────────────────────
        'final_checklist_frame_safe'            => 'boolean',
        'final_checklist_no_tears'              => 'boolean',
        'final_checklist_color_normal'          => 'boolean',
        'final_checklist_glass_safe'            => 'boolean',
        'final_checklist_no_mold'               => 'boolean',
        'final_checklist_packaging_safe'        => 'boolean',
        'final_checklist_matches_documentation' => 'boolean',

        // ── Boolean lain ──────────────────────────────────────────────────
        'has_damage' => 'boolean',

        // ── Integer ───────────────────────────────────────────────────────
        'damage_cost'       => 'integer',
        'final_damage_cost' => 'integer',

        // ── JSON / Array ──────────────────────────────────────────────────
        'damage_items_detail'        => 'array',
        'manager_delivery_timeline'  => 'array',
        'return_shipment_timeline'   => 'array',

        // ── BARU: JSON kondisi penerimaan ─────────────────────────────────
        'arrival_damage_checklist' => 'array',  // [{key, label, checked}]
        'arrival_damage_photos'    => 'array',  // [path, path, ...]
        // ─────────────────────────────────────────────────────────────────

        // ── Misc ──────────────────────────────────────────────────────────
        'refund_confirmed_by' => 'string',
    ];

    // ─── Relasi ───────────────────────────────────────────────────────

    public function penyewaan()
    {
        return $this->belongsTo(Penyewaan::class);
    }

    public function logs()
    {
        return $this->hasMany(SerahTerimaLog::class)->latest();
    }

    // ─── Accessors ────────────────────────────────────────────────────

    public function getHandoverDocumentUrlAttribute(): ?string
    {
        return $this->handover_document_path
            ? asset('storage/' . $this->handover_document_path)
            : null;
    }

    public function getReturnDocumentUrlAttribute(): ?string
    {
        return $this->return_document_path
            ? asset('storage/' . $this->return_document_path)
            : null;
    }

    public function getTenantSignedDocumentUrlAttribute(): ?string
    {
        return $this->tenant_signed_document_path
            ? asset('storage/' . $this->tenant_signed_document_path)
            : null;
    }

    public function getFinalInspectionPhotoUrlAttribute(): ?string
    {
        return $this->final_inspection_photo_path
            ? asset('storage/' . $this->final_inspection_photo_path)
            : null;
    }

    // ─── Helper: cek status pengecekan kondisi ────────────────────────

    /**
     * Apakah penyewa sudah konfirmasi terima dan sedang/sudah cek kondisi.
     */
    public function isConditionCheckPending(): bool
    {
        return $this->handover_status === 'condition_checking'
            && $this->condition_check_status === null;
    }

    /**
     * Apakah ada laporan kerusakan yang menunggu review pengelola.
     */
    public function hasPendingDamageReport(): bool
    {
        return $this->handover_status === 'damage_reported'
            && $this->arrival_damage_manager_decision === null;
    }

    /**
     * Apakah pengelola sudah memutuskan kerusakan saat penerimaan.
     */
    public function isDamageReviewed(): bool
    {
        return $this->arrival_damage_manager_decision !== null;
    }

    /** Pembatalan sewa karena kerusakan saat pengiriman (perlu pengembalian koleksi + refund penuh). */
    public function isArrivalDamageCancellation(): bool
    {
        return $this->handover_status === 'cancelled_due_to_damage'
            || $this->arrival_damage_manager_decision === 'setuju_batal'
            || $this->arrival_damage_manager_decision === 'setujui_pembatalan';
    }

    public function isDamageCancellation(): bool
    {
        return $this->arrival_damage_manager_decision === 'setujui_pembatalan';
    }

    public function isDamageCompensation(): bool
    {
        return $this->arrival_damage_manager_decision === 'setujui_kompensasi';
    }

    public function isDamageReviewPending(): bool
    {
        return $this->penyewaan?->status === 'menunggu_review_kerusakan';
    }

    public function hasDamageReport(): bool
    {
        return $this->condition_check_status === 'damaged'
            && $this->arrival_damage_reported_at !== null;
    }

    public function isFinalSeverityParah(): bool
    {
        return $this->arrival_damage_final_severity === 'parah';
    }

    public function appendDamageTimeline(string $status, string $message, ?string $performedBy = null): void
    {
        $timeline = $this->damage_handling_timeline ?? [];
        $timeline[] = [
            'status'    => $status,
            'message'   => $message,
            'timestamp' => now()->toDateTimeString(),
            'by'        => $performedBy ?? (auth()->user()?->name ?? 'Sistem'),
        ];
        $this->update(['damage_handling_timeline' => $timeline]);
    }

    public function calculateBaseDamageRefundAmount(): int
    {
        $penyewaan = $this->penyewaan;

        return (int) max(0, ($penyewaan->total_bayar ?? 0) - ($penyewaan->shipping_cost ?? 0));
    }

    public function calculateFullDamageRefundAmount(): int
    {
        return $this->calculateBaseDamageRefundAmount()
            + (int) ($this->return_shipping_cost ?? 0);
    }

    /** Kerusakan ringan — penyewa memilih lanjut sewa tanpa verifikasi pengelola (legacy). */
    public function isAutoContinueAfterLightDamage(): bool
    {
        return $this->arrival_damage_manager_decision === 'auto_lanjut';
    }

    /**
     * Ringkasan checklist kerusakan — hanya item yang dicentang.
     * Mengembalikan array label kerusakan.
     */
    public function getCheckedDamageItems(): array
    {
        if (empty($this->arrival_damage_checklist)) {
            return [];
        }

        return collect($this->arrival_damage_checklist)
            ->filter(fn($item) => ! empty($item['checked']))
            ->pluck('label')
            ->values()
            ->toArray();
    }

    // ─── Helper: cek apakah semua checklist terpenuhi ────────────────

    public function isInitialChecklistComplete(): bool
    {
        return $this->checklist_frame_safe
            && $this->checklist_no_tears
            && $this->checklist_color_normal
            && $this->checklist_glass_safe
            && $this->checklist_no_mold
            && $this->checklist_matches_documentation;
    }

    public function isReturnChecklistComplete(): bool
    {
        return $this->return_checklist_frame_safe
            && $this->return_checklist_no_tears
            && $this->return_checklist_color_normal
            && $this->return_checklist_glass_safe
            && $this->return_checklist_no_mold
            && $this->return_checklist_matches_documentation;
    }

    public function isFinalChecklistComplete(): bool
    {
        return $this->final_checklist_frame_safe
            && $this->final_checklist_no_tears
            && $this->final_checklist_color_normal
            && $this->final_checklist_glass_safe
            && $this->final_checklist_no_mold
            && $this->final_checklist_packaging_safe
            && $this->final_checklist_matches_documentation;
    }

    public function hasFinalInspection(): bool
    {
        return $this->final_inspection_at !== null;
    }

    // ─── Helper: manager delivery ─────────────────────────────────────

    public static function managerDeliveryStatuses(): array
    {
        return [
            'dikemas'          => '📦 Dikemas',
            'siap_dikirim'     => '✅ Siap Kirim',
            'dalam_perjalanan' => '🚗 Dalam Perjalanan',
            'tiba_di_tujuan'   => '🏁 Tiba di Tujuan',
        ];
    }

    public static function returnShipmentStatuses(): array
    {
        return [
            'dikemas'          => '📦 Dikemas',
            'siap_dikirim'     => '✅ Siap Kirim',
            'dalam_perjalanan' => '🚗 Dalam Perjalanan',
            'tiba_di_tujuan'   => '🏁 Tiba di Museum',
        ];
    }

    public function managerDeliveryStatusIndex(): int
    {
        $keys = array_keys(self::managerDeliveryStatuses());
        $idx  = array_search($this->manager_delivery_status, $keys);
        return $idx === false ? -1 : (int) $idx;
    }

    public function returnShipmentStatusIndex(): int
    {
        $keys = array_keys(self::returnShipmentStatuses());
        $idx  = array_search($this->return_shipment_status, $keys);
        return $idx === false ? -1 : (int) $idx;
    }

    public function pushDeliveryTimeline(string $status, string $label, ?string $catatan, string $by): void
    {
        $timeline   = $this->manager_delivery_timeline ?? [];
        $timeline[] = [
            'status'    => $status,
            'label'     => $label,
            'catatan'   => $catatan,
            'timestamp' => now()->toISOString(),
            'by'        => $by,
        ];

        $this->manager_delivery_status   = $status;
        $this->manager_delivery_timeline = $timeline;
    }

    public function pushReturnShipmentTimeline(string $status, string $label, ?string $catatan, string $by): void
    {
        $timeline   = $this->return_shipment_timeline ?? [];
        $timeline[] = [
            'status'    => $status,
            'label'     => $label,
            'catatan'   => $catatan,
            'timestamp' => now()->toISOString(),
            'by'        => $by,
        ];

        $this->return_shipment_status   = $status;
        $this->return_shipment_timeline = $timeline;
    }

    // ─── Konstanta: daftar item checklist kerusakan ───────────────────

    /**
     * Daftar item kerusakan yang bisa dicentang penyewa saat terima koleksi.
     * Key = identifier, value = label tampilan.
     */
    public static function arrivalDamageChecklistItems(): array
    {
        return [
            'frame'   => 'Frame / Bingkai Rusak',
            'tears'   => 'Sobekan pada Kanvas / Lukisan',
            'color'   => 'Kerusakan Warna / Cat Mengelupas',
            'glass'   => 'Kaca Pelindung Retak / Pecah',
            'mold'    => 'Jamur / Noda Biologis',
            'scratch' => 'Goresan pada Permukaan',
            'dent'    => 'Penyok / Deformasi Fisik',
            'other'   => 'Kerusakan Lainnya',
        ];
    }
}