<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nip',
        'name',
        'email',
        'password',
        'role',
        'jabatan_id',
        'unit_id',
        'atasan_id',
        'foto',
        'telepon',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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

    // --- RELATIONSHIPS ---

    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function atasan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'atasan_id');
    }

    public function bawahan(): HasMany
    {
        return $this->hasMany(User::class, 'atasan_id');
    }

    public function penilaianSebagaiPenilai(): HasMany
    {
        return $this->hasMany(Penilaian::class, 'penilai_id');
    }

    public function penilaianSebagaiDinilai(): HasMany
    {
        return $this->hasMany(Penilaian::class, 'dinilai_id');
    }

    public function penugasanSebagaiPenilai(): HasMany
    {
        return $this->hasMany(PenugasanPenilaian::class, 'penilai_id');
    }

    public function penugasanSebagaiDinilai(): HasMany
    {
        return $this->hasMany(PenugasanPenilaian::class, 'dinilai_id');
    }

    // --- HELPER METHODS ---

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPegawai(): bool
    {
        return $this->role === 'pegawai';
    }

    public function getJabatanLevelAttribute(): ?string
    {
        return $this->jabatan?->level;
    }
}
