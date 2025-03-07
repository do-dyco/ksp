<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Jeffgreco13\FilamentBreezy\Traits\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class User extends Authenticatable implements FilamentUser, HasAvatar, MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, TwoFactorAuthenticatable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'nip',
        'tgl_masuk',
        'dept',
        'alamat',
        'no_telp',
        'email',
        'password',
        'avatar_url',
    ];

    protected $casts = [
        'tgl_masuk' => 'date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
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

    // public function getFilamentAvatarUrl(): ?string
    // {
    //     return asset($this->avatar_url);
    // }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url ? Storage::url($this->avatar_url) : null;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function age(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->tgl_masuk) {
                    return 'Belum tersedia';
                }

                $tglMasuk = Carbon::parse($this->tgl_masuk);
                $today = Carbon::today();

                $totalYears = $tglMasuk->diffInDays($today) / 365;
                $years = floor($totalYears);
                $months = round(($totalYears - $years) * 12);

                return $years . " tahun {$months} bulan";
            }
        );
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'dept');
    }
}
