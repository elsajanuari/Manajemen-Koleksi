<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketSchedule extends Model
{
    protected $fillable = [
        'ticket_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'hari_tersedia',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'hari_tersedia' => 'array',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
