<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConservationImplementation extends Model
{
    use HasFactory;

    protected $fillable = [
        'conservation_action_id',
        'tanggal_pelaksanaan',
        'petugas',
        'durasi',
        'catatan_pelaksanaan',
        'foto_proses',
        'catatan_perubahan',
    ];

    protected $casts = [
        'tanggal_pelaksanaan' => 'date',
    ];

    public function conservationAction(): BelongsTo
    {
        return $this->belongsTo(ConservationAction::class);
    }

    public function getFotoProsesUrlAttribute(): ?string
    {
        return $this->foto_proses ? asset('storage/' . $this->foto_proses) : null;
    }
}
