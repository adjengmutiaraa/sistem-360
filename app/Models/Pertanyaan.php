<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pertanyaan extends Model
{
    use HasFactory;

    protected $table = 'pertanyaans';

    protected $fillable = [
        'pertanyaan',
        'kategori_nilai_id',
        'urutan',
    ];

    public function kategoriNilai(): BelongsTo
    {
        return $this->belongsTo(KategoriNilai::class, 'kategori_nilai_id');
    }

    public function detailPenilaian(): HasMany
    {
        return $this->hasMany(DetailPenilaian::class, 'pertanyaan_id');
    }
}
