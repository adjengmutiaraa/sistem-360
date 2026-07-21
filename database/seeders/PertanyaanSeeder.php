<?php

namespace Database\Seeders;

use App\Models\KategoriNilai;
use App\Models\Pertanyaan;
use Illuminate\Database\Seeder;

class PertanyaanSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            [
                'id' => 1,
                'nama_kategori' => 'Orientasi Pelayanan',
                'bobot' => 25.00,
                'deskripsi' => 'Komitmen memberikan pelayanan prima demi kepuasan masyarakat dan organisasi.',
                'pertanyaans' => [
                    'Memberikan pelayanan terbaik, ramah, cepat, dan solutif.',
                    'Melakukan perbaikan tiada henti dalam menyelesaikan setiap tugas.',
                ],
            ],
            [
                'id' => 2,
                'nama_kategori' => 'Akuntabel & Integritas',
                'bobot' => 25.00,
                'deskripsi' => 'Bertanggung jawab atas kepercayaan dan tugas yang diberikan.',
                'pertanyaans' => [
                    'Melaksanakan tugas dengan jujur, disiplin, dan berintegritas tinggi.',
                    'Menggunakan fasilitas dan barang milik negara secara bertanggung jawab dan efisien.',
                ],
            ],
            [
                'id' => 3,
                'nama_kategori' => 'Kompeten',
                'bobot' => 25.00,
                'deskripsi' => 'Terus belajar dan mengembangkan kapasitas diri.',
                'pertanyaans' => [
                    'Meningkatkan kompetensi diri untuk menghadapi tantangan pekerjaan.',
                    'Membantu rekan kerja atau bawahan dalam mempelajari tugas-tugas baru.',
                ],
            ],
            [
                'id' => 4,
                'nama_kategori' => 'Harmonis & Kolaboratif',
                'bobot' => 25.00,
                'deskripsi' => 'Membangun lingkungan kerja yang kondusif dan saling bersinergi.',
                'pertanyaans' => [
                    'Saling menghargai, membantu, dan menciptakan suasana kerja yang harmonis.',
                    'Terbuka dalam bekerja sama untuk menghasilkan nilai tambah organisasi.',
                ],
            ],
        ];

        $urutan = 1;
        foreach ($kategoris as $kategoriData) {
            $pertanyaans = $kategoriData['pertanyaans'];
            unset($kategoriData['pertanyaans']);

            $kategori = KategoriNilai::updateOrCreate(
                ['id' => $kategoriData['id']],
                $kategoriData
            );

            foreach ($pertanyaans as $pText) {
                Pertanyaan::updateOrCreate(
                    [
                        'kategori_nilai_id' => $kategori->id,
                        'pertanyaan' => $pText,
                    ],
                    [
                        'urutan' => $urutan++,
                    ]
                );
            }
        }
    }
}
