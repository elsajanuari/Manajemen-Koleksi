<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketScheduleException extends Model
{
    protected $fillable = [
        'ticket_id',
        'tanggal',
        'is_tersedia',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'is_tersedia' => 'boolean',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
