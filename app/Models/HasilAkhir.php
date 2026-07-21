<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HasilAkhir extends Model
{
    use HasFactory;

    protected $table = 'hasil_akhirs';

    protected $fillable = [
        'periode_penilaian_id',
        'user_id',
        'nilai_atasan',
        'nilai_rekan',
        'nilai_bawahan',
        'nilai_akhir',
        'kategori',
    ];

    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodePenilaian::class, 'periode_penilaian_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
