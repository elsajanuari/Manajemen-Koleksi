<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pembelian extends Model
{
    // ────────────────────────────────────────────────────────────
    // CATATAN PAJAK:
    // Museum MK Lesmana belum PKP → tidak ada PPN
    // Koleksi seni bukan barang sangat mewah → tidak ada PPh 22
    // total_bayar = harga_beli + shipping_cost
    // ────────────────────────────────────────────────────────────

    protected $fillable = [
        'user_id',
        'koleksi_id',
        'status',
        'buyer_type',

        // Data Pribadi (B2C)
        'nama_lengkap',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'pekerjaan',
        'npwp',

        // Alamat domisili pembeli perorangan (B2C)
        'alamat_domisili',
        'dom_provinsi',
        'dom_kota_kabupaten',
        'dom_kecamatan',
        'dom_kelurahan_desa',
        'dom_rt',
        'dom_rw',
        'dom_kode_pos',

        // Data Instansi / Perusahaan (B2B)
        'company_name',
        'company_type',
        'business_field',
        'company_npwp',
        'company_address',
        'company_rt',
        'company_rw',
        'company_kelurahan_desa',
        'company_kecamatan',
        'company_city',
        'company_province',
        'company_postal_code',
        'company_email',
        'company_phone',
        'company_website',

        // Data PIC
        'pic_name',
        'pic_position',
        'pic_nik',
        'pic_phone',
        'pic_email',
        'pic_alamat_domisili',
        'pic_provinsi',
        'pic_kota_kabupaten',
        'pic_kecamatan',
        'pic_kelurahan_desa',
        'pic_rt',
        'pic_rw',
        'pic_kode_pos',

        // Kontak
        'nomor_hp',
        'email',

        // Alamat Pengiriman
        'alamat_pengiriman',
        'rt',
        'rw',
        'kelurahan_desa',
        'kota_kabupaten',
        'provinsi',
        'kode_pos',
        'kecamatan',

        // Dokumen
        'upload_ktp',
        'upload_npwp',
        'upload_npwp_company',
        'upload_purchase_request_letter',
        'upload_pic_ktp',
        'upload_legal_document',

        // ── Harga ──────────────────────────────────────────────
        // total_bayar = harga_beli + shipping_cost
        'harga_beli',
        'shipping_cost',       // ongkir (0 jika gratis), snapshot saat invoice dibuat
        'total_bayar',

        // ── Pengiriman ─────────────────────────────────────────
        'shipping_zone_id',    // FK ke shipping_zones (snapshot zona)
        'shipping_method_type',// 'courier' | 'manager' — ditentukan pengelola saat approve
        'courier_name',        // "JNE", "JNT", dll. null jika pengelola
        'courier_service',        // ← tambahkan
        'courier_etd',            // ← tambahkan
        'destination_city_id',
        'city_name',
        'province_id',

        // Pembayaran
        'payment_status',
        'payment_reference',
        'paid_at',

        // Invoice
        'invoice_number',
        'invoice_path',
        'invoice_generated_at',

        // Pengiriman fisik (setelah pembayaran)
        'delivery_method',
        'delivery_officer',
        'delivery_tracking_number',
        'delivery_location',
        'recipient_name',
        'delivery_scheduled_at',
        'delivery_notes',
        'manager_delivery_status',
        'manager_delivery_timeline',
        'shipped_at',
        'received_at',
        'completed_at',
        'dispatch_front_photo',
        'dispatch_back_photo',
        'dispatch_packing_photos',
        'dispatch_video_path',

        // Pengecekan kondisi & kerusakan saat penerimaan
        'condition_check_status',
        'condition_checked_at',
        'arrival_damage_items',
        'arrival_damage_photos',
        'arrival_damage_description',
        'arrival_damage_severity',
        'packing_condition_photos',
        'courier_receipt_photos',
        'arrival_damage_reported_at',
        'arrival_damage_final_severity',
        'arrival_damage_severity_corrected',
        'arrival_damage_compensation_amount',
        'arrival_damage_manager_notes',
        'arrival_damage_decided_at',
        'arrival_damage_decided_by',
        'arrival_damage_manager_decision',
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
        'damage_handling_timeline',
        'condition_front_photo',
        'condition_back_photo',
        'damage_video_path',
        'arrival_damage_buyer_decision',
        'condition_video',
        'refund_confirmed_at',
        'return_shipment_method',
        'return_shipment_officer',
        'return_shipment_tracking',
        'return_shipment_scheduled_at',
        'return_shipment_notes',
        'return_shipment_submitted_at',
        'return_shipment_status',
        'return_shipment_timeline',
        'return_shipping_cost',
        'return_shipping_proof_path',
        'collection_arrived_at',

        // Dokumen Serah Terima
        'handover_document_path',
        'handover_signed_document_path',
        'handover_signed_at',
        'handover_document_uploaded_at',
        'handover_condition_notes',
        'handover_received_condition_photo_path',
        'handover_checklist_frame_safe',
        'handover_checklist_no_tears',
        'handover_checklist_color_normal',
        'handover_checklist_glass_safe',
        'handover_checklist_no_mold',
        'handover_checklist_matches_documentation',
        'handover_validation_notes',
        'handover_validated_at',
        'handover_validated_by',
        'certificate_document_path',

        // Pengelola
        'catatan_pengelola',
        'submitted_at',
    ];

    protected $casts = [
        'tanggal_lahir'                            => 'date',
        'submitted_at'                             => 'datetime',
        'paid_at'                                  => 'datetime',
        'invoice_generated_at'                     => 'datetime',
        'delivery_scheduled_at'                    => 'datetime',
        'shipped_at'                               => 'datetime',
        'received_at'                              => 'datetime',
        'completed_at'                             => 'datetime',
        'condition_checked_at'                     => 'datetime',
        'arrival_damage_items'                     => 'array',
        'arrival_damage_photos'                    => 'array',
        'packing_condition_photos'                 => 'array',
        'courier_receipt_photos'                   => 'array',
        'arrival_damage_reported_at'               => 'datetime',
        'arrival_damage_severity_corrected'        => 'boolean',
        'arrival_damage_compensation_amount'       => 'decimal:0',
        'arrival_damage_decided_at'                => 'datetime',
        'refund_bank_submitted_at'                 => 'datetime',
        'refund_amount'                            => 'decimal:0',
        'refund_date'                              => 'date',
        'refund_processed_at'                      => 'datetime',
        'damage_handling_timeline'                 => 'array',
        'handover_signed_at'                       => 'datetime',
        'handover_document_uploaded_at'            => 'datetime',
        'handover_validated_at'                    => 'datetime',
        'handover_checklist_frame_safe'            => 'boolean',
        'handover_checklist_no_tears'              => 'boolean',
        'handover_checklist_color_normal'          => 'boolean',
        'handover_checklist_glass_safe'            => 'boolean',
        'handover_checklist_no_mold'               => 'boolean',
        'handover_checklist_matches_documentation' => 'boolean',
        'harga_beli'                               => 'decimal:0',
        'shipping_cost'                            => 'decimal:0',
        'total_bayar'                              => 'decimal:0',
        'manager_delivery_timeline' => 'array',
        'refund_confirmed_at'              => 'datetime',
        'return_shipment_scheduled_at'     => 'datetime',
        'return_shipment_submitted_at'     => 'datetime',
        'return_shipment_timeline'         => 'array',
        'return_shipping_cost'             => 'decimal:0',
        'collection_arrived_at'            => 'datetime',
        'dispatch_packing_photos' => 'array',
    ];

    // ── Relasi ───────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function koleksi(): BelongsTo
    {
        return $this->belongsTo(Koleksi::class, 'koleksi_id');
    }

    /** Alias backward compat — view/$pembelian->painting tetap jalan */
    public function painting(): BelongsTo
    {
        return $this->belongsTo(Koleksi::class, 'koleksi_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(PembelianPayment::class);
    }

    public function serahTerima(): HasOne
    {
        return $this->hasOne(\App\Models\SerahTerima::class, 'pembelian_id');
    }

    public function shippingZone(): BelongsTo
    {
        return $this->belongsTo(ShippingZone::class, 'shipping_zone_id');
    }

    // ── Accessor ─────────────────────────────────────────────────

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'disetujui'           => 'Disetujui',
            'ditolak'             => 'Ditolak',
            'menunggu_pembayaran' => 'Menunggu Pembayaran',
            'pembayaran_berhasil' => 'Pembayaran Berhasil',
            'dibatalkan'          => 'Dibatalkan',
            default               => ucfirst($this->status),
        };
    }

    public function getShippingMethodLabelAttribute(): string
    {
        return match ($this->shipping_method_type) {
            'courier' => 'Kurir (' . ($this->courier_name ?? '-') . ')',
            'manager' => 'Dikirim Pengelola',
            default   => '-',
        };
    }

    public function getNpwpInfoAttribute(): string
    {
        if ($this->buyer_type === 'b2b') {
            return $this->company_npwp
                ? 'NPWP Perusahaan: ' . $this->company_npwp
                : 'Tidak tersedia';
        }
        return $this->npwp
            ? 'NPWP: ' . $this->npwp
            : 'Tidak disediakan';
    }

    // ── Helper: apakah ongkir sudah ditentukan ───────────────────
    public function hasShippingDecided(): bool
    {
        return $this->shipping_method_type !== null;
    }

    public static function arrivalDamageChecklistItems(): array
    {
        return SerahTerima::arrivalDamageChecklistItems();
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

    public function getCheckedDamageItems(): array
    {
        if (empty($this->arrival_damage_items)) {
            return [];
        }

        return collect($this->arrival_damage_items)
            ->filter(fn ($item) => ! empty($item['checked']))
            ->values()
            ->toArray();
    }

    public function calculateBaseDamageRefundAmount(): int
    {
        return (int) max(0, ($this->total_bayar ?? 0) - ($this->shipping_cost ?? 0));
    }

    /** Refund pembatalan kerusakan parah: total bayar − ongkir awal + ongkir pengembalian ke museum. */
    public function calculateFullDamageRefundAmount(): int
    {
        return $this->calculateBaseDamageRefundAmount()
            + (int) ($this->return_shipping_cost ?? 0);
    }

    public function isDamageCancellation(): bool
    {
        return $this->arrival_damage_manager_decision === 'setujui_pembatalan';
    }

    public function isDamageCompensation(): bool
    {
        return $this->arrival_damage_manager_decision === 'setujui_kompensasi';
    }

    public static function returnShipmentStatuses(): array
    {
        return SerahTerima::returnShipmentStatuses();
    }

    public function hasDamageReport(): bool
    {
        return $this->condition_check_status === 'damaged'
            && $this->arrival_damage_reported_at !== null;
    }

    public function isDamageReviewPending(): bool
    {
        return $this->status === 'menunggu_review_kerusakan';
    }

    public function isFinalSeverityRingan(): bool
    {
        return $this->arrival_damage_final_severity === 'ringan';
    }

    public function isFinalSeverityParah(): bool
    {
        return $this->arrival_damage_final_severity === 'parah';
    }
}