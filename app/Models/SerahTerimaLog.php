<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SerahTerimaLog extends Model
{
    use HasFactory;

    protected $table = 'serah_terima_logs';

    protected $fillable = [
        'serah_terima_id',
        'status',
        'performed_by',
        'message',
    ];

    public function serahTerima()
    {
        return $this->belongsTo(SerahTerima::class);
    }
}
