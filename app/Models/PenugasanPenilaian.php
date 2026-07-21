<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenugasanPenilaian extends Model
{
    use HasFactory;

    protected $table = 'penugasan_penilaians';

    protected $fillable = [
        'periode_penilaian_id',
        'penilai_id',
        'dinilai_id',
        'jenis_penilai',
        'status',
    ];

    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodePenilaian::class, 'periode_penilaian_id');
    }

    public function penilai(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penilai_id');
    }

    public function dinilai(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dinilai_id');
    }
}
