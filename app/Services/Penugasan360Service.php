<?php

namespace App\Services;

use App\Models\PenugasanPenilaian;
use App\Models\PeriodePenilaian;
use App\Models\User;

class Penugasan360Service
{
    /**
     * Generate 360-degree assessment assignment matrix for a given evaluation period.
     */
    public function generatePenugasan(PeriodePenilaian $periode): int
    {
        $countGenerated = 0;

        // Fetch Users grouped by level
        $ketuaUmum = User::whereHas('position', fn ($q) => $q->where('level', '1'))->first();
        $kabids = User::whereHas('position', fn ($q) => $q->where('level', '3'))->get();
        // treat levels >= 4 as staff
        $staffs = User::whereHas('position', fn ($q) => $q->where('level', '>=', '4'))->get();

        // 1. KETUA UMUM -> Menilai Semua Kepala Bidang (jenis: 'bawahan')
        if ($ketuaUmum) {
            foreach ($kabids as $kabid) {
                $created = PenugasanPenilaian::firstOrCreate([
                    'periode_penilaian_id' => $periode->id,
                    'penilai_id' => $ketuaUmum->id,
                    'dinilai_id' => $kabid->id,
                ], [
                    'jenis_penilai' => 'atasan', // Ketua Umum is Atasan to Kabid
                    'status' => 'belum',
                ]);

                if ($created->wasRecentlyCreated) {
                    $countGenerated++;
                }
            }
        }

        // 2. KEPALA BIDANG
        foreach ($kabids as $kabid) {
            // A. Kabid -> Menilai Atasan (Ketua Umum)
            if ($ketuaUmum) {
                $created = PenugasanPenilaian::firstOrCreate([
                    'periode_penilaian_id' => $periode->id,
                    'penilai_id' => $kabid->id,
                    'dinilai_id' => $ketuaUmum->id,
                ], [
                    'jenis_penilai' => 'bawahan', // Kabid is Bawahan to Ketua Umum
                    'status' => 'belum',
                ]);
                if ($created->wasRecentlyCreated) {
                    $countGenerated++;
                }
            }

            // B. Kabid -> Menilai Semua Staff di Divisi / Unit Kerjanya (jenis: 'atasan' for staff)
            $staffsInUnit = User::where('atasan_id', $kabid->id)->get();
            if ($staffsInUnit->isEmpty() && $kabid->department_id) {
                $staffsInUnit = User::where('department_id', $kabid->department_id)
                    ->whereHas('position', fn ($q) => $q->where('level', '>=', '4'))
                    ->get();
            }

            foreach ($staffsInUnit as $staff) {
                $created = PenugasanPenilaian::firstOrCreate([
                    'periode_penilaian_id' => $periode->id,
                    'penilai_id' => $kabid->id,
                    'dinilai_id' => $staff->id,
                ], [
                    'jenis_penilai' => 'atasan', // Kabid is Atasan to Staff
                    'status' => 'belum',
                ]);
                if ($created->wasRecentlyCreated) {
                    $countGenerated++;
                }
            }

            // C. Kabid -> Menilai 3 Kabid Lainnya (jenis: 'rekan')
            $otherKabids = $kabids->where('id', '!=', $kabid->id)->take(3);
            foreach ($otherKabids as $otherKabid) {
                $created = PenugasanPenilaian::firstOrCreate([
                    'periode_penilaian_id' => $periode->id,
                    'penilai_id' => $kabid->id,
                    'dinilai_id' => $otherKabid->id,
                ], [
                    'jenis_penilai' => 'rekan',
                    'status' => 'belum',
                ]);
                if ($created->wasRecentlyCreated) {
                    $countGenerated++;
                }
            }
        }

        // 3. STAFF
        foreach ($staffs as $staff) {
            // A. Staff -> Menilai Atasan Langsung (Kabid)
            $atasan = $staff->atasan;
            if (! $atasan && $staff->department_id) {
                $atasan = User::where('department_id', $staff->department_id)
                    ->whereHas('position', fn ($q) => $q->where('level', '3'))
                    ->first();
            }

            if ($atasan) {
                $created = PenugasanPenilaian::firstOrCreate([
                    'periode_penilaian_id' => $periode->id,
                    'penilai_id' => $staff->id,
                    'dinilai_id' => $atasan->id,
                ], [
                    'jenis_penilai' => 'bawahan', // Staff is Bawahan to Kabid
                    'status' => 'belum',
                ]);
                if ($created->wasRecentlyCreated) {
                    $countGenerated++;
                }
            }

            // B. Staff -> Menilai 3 Rekan Staff (se-unit atau lintas unit)
            $peerStaffs = $staffs->where('id', '!=', $staff->id);
            // Prefer peers in same unit first
            if ($staff->department_id) {
                $sameUnitPeers = $peerStaffs->where('department_id', $staff->department_id)->take(3);
                if ($sameUnitPeers->count() < 3) {
                    $otherPeers = $peerStaffs->where('department_id', '!=', $staff->department_id)->take(3 - $sameUnitPeers->count());
                    $assignedPeers = $sameUnitPeers->concat($otherPeers);
                } else {
                    $assignedPeers = $sameUnitPeers;
                }
            } else {
                $assignedPeers = $peerStaffs->take(3);
            }

            foreach ($assignedPeers as $peer) {
                $created = PenugasanPenilaian::firstOrCreate([
                    'periode_penilaian_id' => $periode->id,
                    'penilai_id' => $staff->id,
                    'dinilai_id' => $peer->id,
                ], [
                    'jenis_penilai' => 'rekan',
                    'status' => 'belum',
                ]);
                if ($created->wasRecentlyCreated) {
                    $countGenerated++;
                }
            }
        }

        return $countGenerated;
    }
}
