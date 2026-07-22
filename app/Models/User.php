<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\PemesananTiket;
use App\Models\Penyewaan;
use App\Models\Pembelian;
use App\Models\PerawatanKoleksi;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function penyewaan()
    {
        return $this->hasMany(Penyewaan::class);
    }

    public function perawatansPenanggungJawab()
    {
        return $this->hasMany(PerawatanKoleksi::class, 'penanggung_jawab_user_id');
    }

    public function pemesanans()
    {
        return $this->hasMany(PemesananTiket::class);
    }

    public function pembelians()
    {
        return $this->hasMany(\App\Models\Pembelian::class);
    }
}