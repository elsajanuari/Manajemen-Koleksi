<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConservationResult extends Model
{
    use HasFactory;

    public const EVALUATION_OPTIONS = [
        'berhasil' => 'Berhasil',
        'sebagian_berhasil' => 'Sebagian Berhasil',
        'perlu_tindak_lanjut' => 'Perlu Tindak Lanjut',
    ];

    protected $fillable = [
        'conservation_action_id',
        'kondisi_setelah',
        'foto_setelah',
        'evaluasi',
        'rekomendasi_penyimpanan',
        'rekomendasi_penanganan_khusus',
        'catatan_akhir',
    ];

    public function conservationAction(): BelongsTo
    {
        return $this->belongsTo(ConservationAction::class);
    }

    public function getFotoSetelahUrlAttribute(): ?string
    {
        return $this->foto_setelah ? asset('storage/' . $this->foto_setelah) : null;
    }
}
