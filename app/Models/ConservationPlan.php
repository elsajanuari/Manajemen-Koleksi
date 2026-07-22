<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConservationPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'conservation_action_id',
        'jenis_tindakan',
        'deskripsi_tindakan',
        'bahan_material',
        'target_penyelesaian',
        'catatan',
    ];

    protected $casts = [
        'target_penyelesaian' => 'date',
    ];

    public function conservationAction(): BelongsTo
    {
        return $this->belongsTo(ConservationAction::class);
    }
}
