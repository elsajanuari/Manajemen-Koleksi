<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class TicketQuota extends Model
{
    protected $fillable = [
        'ticket_id',
        'tanggal',
        'kuota_max',
        'kuota_terjual'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'kuota_max' => 'integer',
        'kuota_terjual' => 'integer',
    ];

    // RELATIONS
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    // ACCESSORS
    public function getKuotaSisaAttribute()
    {
        return $this->kuota_max - $this->kuota_terjual;
    }

    public function getPersentaseAttribute()
    {
        if ($this->kuota_max == 0) return 0;
        return round(($this->kuota_terjual / $this->kuota_max) * 100, 2);
    }

    public function getStatusAttribute()
    {
        if ($this->tanggal < now()->toDateString()) {
            return 'expired';
        }

        if ($this->kuota_sisa <= 0) {
            return 'penuh';
        }

        if ($this->kuota_sisa < ($this->kuota_max * 0.1)) {
            return 'low_stock';
        }

        return 'available';
    }

    public function getIsHolidayAttribute()
    {
        return self::isHoliday($this->tanggal);
    }

    public function getHolidayNameAttribute()
    {
        return self::getHolidayName($this->tanggal);
    }

    // SCOPES
    public function scopeByDate($query, $date)
    {
        return $query->where('tanggal', $date);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal', [$startDate, $endDate]);
    }

    public function scopeAvailable($query)
    {
        return $query->where('kuota_terjual', '<', DB::raw('kuota_max'))
                     ->where('tanggal', '>=', now()->toDateString());
    }

    public static function isHoliday($date, $year = null): bool
    {
        $year = $year ?: date('Y', strtotime($date));
        $holidays = self::getIndonesianHolidays($year);
        $dateKey = date('Y-m-d', strtotime($date));

        return isset($holidays[$dateKey]);
    }

    public static function getHolidayName($date, $year = null): ?string
    {
        $year = $year ?: date('Y', strtotime($date));
        $holidays = self::getIndonesianHolidays($year);
        $dateKey = date('Y-m-d', strtotime($date));
        
        return $holidays[$dateKey] ?? null;
    }

    public static function getIndonesianHolidays($year): array
    {
        $cacheKey = "indonesian_holidays_{$year}";
        
        return Cache::remember($cacheKey, 60 * 24 * 30, function () use ($year) {
            $apiKey = env('CALENDARIFIC_API_KEY');
            
            if (!$apiKey) {
                \Log::warning('CALENDARIFIC_API_KEY not set in .env file');
                return [];
            }

            try {
                $response = Http::timeout(10)->get('https://calendarific.com/api/v2/holidays', [
                    'api_key' => $apiKey,
                    'country' => 'ID',
                    'year' => $year,
                    'type' => 'national'
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $holidays = [];
                    
                    if (isset($data['response']['holidays'])) {
                        foreach ($data['response']['holidays'] as $holiday) {
                            $dateKey = $holiday['date']['iso'];
                            $holidays[$dateKey] = $holiday['name'];

                            \Log::info("Holiday found: {$dateKey} - {$holiday['name']}");
                        }
                    }
                    
                    return $holidays;
                }
                
                \Log::warning("Calendarific API returned error: " . $response->status());
                return [];
                
            } catch (\Exception $e) {
                \Log::error("Failed to fetch holidays from Calendarific: " . $e->getMessage());
                return [];
            }
        });
    }

    public static function clearHolidayCache($year = null): void
    {
        if ($year) {
            Cache::forget("indonesian_holidays_{$year}");
        } else {
            $currentYear = now()->year;
            Cache::forget("indonesian_holidays_{$currentYear}");
            Cache::forget("indonesian_holidays_" . ($currentYear + 1));
        }
    }
}