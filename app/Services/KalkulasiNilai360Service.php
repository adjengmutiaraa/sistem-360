<?php

namespace App\Services;

use App\Models\HasilAkhir;
use App\Models\Penilaian;
use App\Models\PeriodePenilaian;
use App\Models\User;

class KalkulasiNilai360Service
{
    /**
     * Calculate 360-degree weighted final score for all evaluatees in a given evaluation period.
     */
    public function hitungNilaiAkhir(PeriodePenilaian $periode): int
    {
        $penilaians = Penilaian::with(['detailPenilaian'])
            ->where('periode_penilaian_id', $periode->id)
            ->get();

        if ($penilaians->isEmpty()) {
            return 0;
        }

        // Group penilaians by evaluatee (dinilai_id)
        $groupedByDinilai = $penilaians->groupBy('dinilai_id');
        $countCalculated = 0;

        foreach ($groupedByDinilai as $dinilaiId => $evaluations) {
            $user = User::with('jabatan')->find($dinilaiId);

            if (! $user) {
                continue;
            }

            // Exclude Ketua Umum from receiving final scores (Ketua Umum only evaluates)
            if ($user->jabatan?->level === 'ketua_umum') {
                continue;
            }

            $atasanScores = [];
            $rekanScores = [];
            $bawahanScores = [];

            foreach ($evaluations as $p) {
                $countDetails = $p->detailPenilaian->count();
                if ($countDetails === 0) {
                    continue;
                }

                $avgScore1to5 = $p->detailPenilaian->avg('skor');
                $score100 = round($avgScore1to5 * 20, 2);

                if ($p->jenis_penilai === 'atasan') {
                    $atasanScores[] = $score100;
                } elseif ($p->jenis_penilai === 'rekan') {
                    $rekanScores[] = $score100;
                } elseif ($p->jenis_penilai === 'bawahan') {
                    $bawahanScores[] = $score100;
                }
            }

            $nilaiAtasan = count($atasanScores) > 0 ? round(array_sum($atasanScores) / count($atasanScores), 2) : null;
            $nilaiRekan = count($rekanScores) > 0 ? round(array_sum($rekanScores) / count($rekanScores), 2) : null;
            $nilaiBawahan = count($bawahanScores) > 0 ? round(array_sum($bawahanScores) / count($bawahanScores), 2) : null;

            $level = $user->jabatan?->level;
            $nilaiAkhir = 0;

            if ($level === 'staff') {
                // Formula Staff: (Atasan * 50%) + (Rekan * 50%)
                if ($nilaiAtasan !== null && $nilaiRekan !== null) {
                    $nilaiAkhir = ($nilaiAtasan * 0.50) + ($nilaiRekan * 0.50);
                } elseif ($nilaiAtasan !== null) {
                    $nilaiAkhir = $nilaiAtasan;
                } elseif ($nilaiRekan !== null) {
                    $nilaiAkhir = $nilaiRekan;
                }
            } else {
                // Formula Kabid: (Atasan * 50%) + (Rekan * 30%) + (Bawahan * 20%)
                if ($nilaiAtasan !== null && $nilaiRekan !== null && $nilaiBawahan !== null) {
                    $nilaiAkhir = ($nilaiAtasan * 0.50) + ($nilaiRekan * 0.30) + ($nilaiBawahan * 0.20);
                } else {
                    // Proportional weighting if partial
                    $sum = 0;
                    $weight = 0;
                    if ($nilaiAtasan !== null) {
                        $sum += $nilaiAtasan * 0.50;
                        $weight += 0.50;
                    }
                    if ($nilaiRekan !== null) {
                        $sum += $nilaiRekan * 0.30;
                        $weight += 0.30;
                    }
                    if ($nilaiBawahan !== null) {
                        $sum += $nilaiBawahan * 0.20;
                        $weight += 0.20;
                    }
                    $nilaiAkhir = $weight > 0 ? ($sum / $weight) : 0;
                }
            }

            $nilaiAkhir = round($nilaiAkhir, 2);
            $kategori = $this->tentukanKategoriPredikat($nilaiAkhir);

            HasilAkhir::updateOrCreate([
                'periode_penilaian_id' => $periode->id,
                'user_id' => $user->id,
            ], [
                'nilai_atasan' => $nilaiAtasan,
                'nilai_rekan' => $nilaiRekan,
                'nilai_bawahan' => $nilaiBawahan,
                'nilai_akhir' => $nilaiAkhir,
                'kategori' => $kategori,
            ]);

            $countCalculated++;
        }

        return $countCalculated;
    }

    public function tentukanKategoriPredikat(float $nilai): string
    {
        if ($nilai >= 90.00) {
            return 'Sangat Baik';
        }
        if ($nilai >= 80.00) {
            return 'Baik';
        }
        if ($nilai >= 70.00) {
            return 'Cukup';
        }

        return 'Kurang';
    }
}
