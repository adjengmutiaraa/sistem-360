<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriNilai extends Model
{
    use HasFactory;

    protected $table = 'kategori_nilais';

    protected $fillable = [
        'nama_kategori',
        'bobot',
        'deskripsi',
    ];

    public function pertanyaans(): HasMany
    {
        return $this->hasMany(Pertanyaan::class, 'kategori_nilai_id');
    }
}
