<?php

namespace App\Models;

use App\Models\TicketQuota;
use App\Models\TicketSchedule;
use App\Models\TicketScheduleException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];
    
    protected $fillable = [
        'nama_tiket',
        'jenis_tiket',
        'sub_jenis',
        'sub_kategori',
        'kategori_pengunjung',
        'harga',
        'kuota',
        'minimal_anggota',
        'tanggal_mulai',
        'tanggal_selesai',
        'deskripsi',
        'gambar',
        'status',
        'boleh_reschedule',
        'boleh_cancel',
        'jam_mulai',
    ];

    protected $casts = [
        'status' => 'boolean',
        'boleh_reschedule' => 'boolean',
        'boleh_cancel' => 'boolean',
        'minimal_anggota' => 'integer',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'jam_mulai' => 'string',
        'deleted_at' => 'datetime',
    ];

    public function schedules()
    {
        return $this->hasMany(TicketSchedule::class);
    }

    public function exceptions()
    {
        return $this->hasMany(TicketScheduleException::class);
    }

    public function quotas()
    {
        return $this->hasMany(TicketQuota::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', true);
    }

    public function scopeByJenis(Builder $query, string $jenis): Builder
    {
        return $query->where('jenis_tiket', $jenis);
    }

    public function scopeBySubJenis(Builder $query, string $subJenis): Builder
    {
        return $query->where('sub_jenis', $subJenis);
    }

    public function scopeValid(Builder $query): Builder
    {
        return $query->where('status', true)
            ->where(function ($q) {
                $q->whereNull('tanggal_selesai')
                  ->orWhere('tanggal_selesai', '>=', now()->toDateString());
            })
            ->where('tanggal_mulai', '<=', now()->toDateString());
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('status', true)
            ->where('tanggal_mulai', '>', now()->toDateString());
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('status', true)
            ->whereNotNull('tanggal_selesai')
            ->where('tanggal_selesai', '<', now()->toDateString());
    }

    public function scopeFilterByDisplayStatus($query, $status)
    {
        switch ($status) {
            case 'aktif':
                return $query->where('status', true)
                    ->where('tanggal_mulai', '<=', now()->toDateString())
                    ->where(function($q) {
                        $q->whereNull('tanggal_selesai')
                        ->orWhere('tanggal_selesai', '>=', now()->toDateString());
                    });
            
            case 'akan_datang':
                return $query->where('status', true)
                    ->where('tanggal_mulai', '>', now()->toDateString());
            
            case 'berakhir':
                return $query->where('status', true)
                    ->whereNotNull('tanggal_selesai')
                    ->where('tanggal_selesai', '<', now()->toDateString());
            
            
            default:
                return $query;
        }
    }

    public function isExpired(): bool
    {
        if (!$this->status) {
            return true;
        }
        
        if (!$this->tanggal_selesai) {
            return false;
        }
        
        return $this->tanggal_selesai < now()->toDateString();
    }

    public function isValid(): bool
    {
        if (!$this->status) {
            return false;
        }
        
        $today = now()->toDateString();
        
        if ($this->tanggal_mulai > $today) {
            return false;
        }
        
        if ($this->tanggal_selesai && $this->tanggal_selesai < $today) {
            return false;
        }
        
        return true;
    }

    public function isUpcoming(): bool
    {
        if (!$this->status) {
            return false;
        }
        
        return $this->tanggal_mulai > now()->toDateString();
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->isExpired();
    }

    public function getIsValidAttribute(): bool
    {
        return $this->isValid();
    }

    public function getIsUpcomingAttribute(): bool
    {
        return $this->isUpcoming();
    }

    public function generateQuotas($startDate, $endDate, int $quotaPerDay, array $availableDays): void
    {
        $exceptions = $this->exceptions()->get()->keyBy(fn ($exception) => $exception->tanggal->toDateString());
        $current = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        while ($current <= $end) {
            $dateString = $current->toDateString();
            $dayOfWeek = $current->dayOfWeek;
            $exception = $exceptions[$dateString] ?? null;

            if ($exception !== null) {
                $isAvailable = (bool) $exception->is_tersedia;
            } else {
                $isAvailable = in_array($dayOfWeek, $availableDays, true);
            }

            if ($isAvailable) {
                $quota = TicketQuota::firstOrNew([
                    'ticket_id' => $this->id,
                    'tanggal' => $dateString,
                ]);

                if (!$quota->exists) {
                    $quota->kuota_terjual = 0;
                }

                $quota->kuota_max = $quotaPerDay;
                $quota->save();
            } else {
                TicketQuota::where('ticket_id', $this->id)
                    ->where('tanggal', $dateString)
                    ->delete();
            }

            $current->addDay();
        }
    }

    public function regenerateQuotas(): void
    {
        $this->refresh();
        $this->load('schedules', 'exceptions');

        $this->quotas()->delete();

        $exceptions = $this->exceptions->keyBy(fn ($exception) => $exception->tanggal->toDateString());

        foreach ($this->schedules as $schedule) {
            $current = \Carbon\Carbon::parse($schedule->tanggal_mulai);
            $end     = \Carbon\Carbon::parse($schedule->tanggal_selesai);

            $hariTersedia = $schedule->hari_tersedia ?? [];
            if (is_string($hariTersedia)) {
                $hariTersedia = json_decode($hariTersedia, true) ?? [];
            }
            $hariTersedia = array_map('intval', (array) $hariTersedia);

            $isExplicitSingleDay = $schedule->tanggal_mulai->toDateString() === $schedule->tanggal_selesai->toDateString();

            while ($current <= $end) {
                $dateString   = $current->toDateString();
                $exception    = $exceptions[$dateString] ?? null;
                $dayOfWeek    = (int) $current->dayOfWeek; // 0=Sun..6=Sat
                $isDayAvailable = in_array($dayOfWeek, $hariTersedia, true);

                if ($exception !== null) {
                    $available = (bool) $exception->is_tersedia;
                } elseif ($isExplicitSingleDay) {
                    $available = $isDayAvailable;
                } else {
                    $isHoliday = TicketQuota::isHoliday($dateString, $current->year);
                    $available = !$isHoliday && $isDayAvailable;
                }

                if ($available) {
                    TicketQuota::create([
                        'ticket_id'     => $this->id,
                        'tanggal'       => $dateString,
                        'kuota_max'     => $this->kuota,
                        'kuota_terjual' => 0,
                    ]);
                }

                $current->addDay();
            }
        }
    }

    public function getAvailableQuotas()
    {
        return $this->quotas()
                    ->where('tanggal', '>=', now()->toDateString())
                    ->where('kuota_terjual', '<', DB::raw('kuota_max'))
                    ->get();
    }

    public function canBeDeleted(): bool
    {
        $hasTransaction = PemesananTiket::where('ticket_id', $this->id)->exists();
        
        if ($hasTransaction) {
            return false;
        }
        
        if ($this->isExpired()) {
            return false;
        }
        
        return true;
    }

    public function getCannotDeleteReason(): string
    {
        $hasTransaction = PemesananTiket::where('ticket_id', $this->id)->exists();
        
        if ($hasTransaction) {
            return 'Tiket sudah digunakan dalam transaksi.';
        }
        
        if ($this->isExpired()) {
            return 'Periode tiket sudah berakhir.';
        }
        
        return '';
    }

    public function getDisplayStatus(): string
    {
        if (!$this->status) {
            return 'Tidak Aktif';
        }
        
        if ($this->isExpired()) {
            return 'Berakhir';
        }
        
        if ($this->isUpcoming()) {
            return 'Akan Datang';
        }
        
        return 'Aktif';
    }

    public function getStatusBadgeClass(): string
    {
        if (!$this->status) {
            return 'bg-gray-100 text-gray-600 border border-gray-200';
        }
        
        if ($this->isExpired()) {
            return 'bg-gray-100 text-gray-500 border border-gray-200';
        }
        
        if ($this->isUpcoming()) {
            return 'bg-blue-50 text-blue-700 border border-blue-200';
        }
        
        return 'bg-green-50 text-green-700 border border-green-200';
    }

    public function getStatusDotClass(): string
    {
        if (!$this->status) {
            return 'bg-gray-400';
        }
        
        if ($this->isExpired()) {
            return 'bg-gray-400';
        }
        
        if ($this->isUpcoming()) {
            return 'bg-blue-500';
        }
        
        return 'bg-green-500';
    }
}