<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeriodePenilaian extends Model
{
    use HasFactory;

    protected $table = 'periode_penilaians';

    protected $fillable = [
        'nama_periode',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'deskripsi',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    /**
     * Get currently active evaluation period, automatically closing any expired active period.
     */
    public static function getPeriodeAktif(): ?self
    {
        $periode = self::where('status', 'aktif')->first();

        if (! $periode) {
            return null;
        }

        // Automatic closing if current date has passed tanggal_selesai
        if ($periode->tanggal_selesai && now()->startOfDay()->greaterThan($periode->tanggal_selesai->endOfDay())) {
            $periode->update(['status' => 'selesai']);

            return null;
        }

        return $periode;
    }

    public function isExpired(): bool
    {
        return $this->tanggal_selesai && now()->startOfDay()->greaterThan($this->tanggal_selesai->endOfDay());
    }

    public function penugasan(): HasMany
    {
        return $this->hasMany(PenugasanPenilaian::class, 'periode_penilaian_id');
    }

    public function penilaian(): HasMany
    {
        return $this->hasMany(Penilaian::class, 'periode_penilaian_id');
    }

    public function hasilAkhir(): HasMany
    {
        return $this->hasMany(HasilAkhir::class, 'periode_penilaian_id');
    }
}
