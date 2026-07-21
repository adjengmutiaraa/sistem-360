<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\PenugasanPenilaian;
use App\Models\PeriodePenilaian;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user()->load(['position', 'department', 'atasan']);
        $periodeAktif = PeriodePenilaian::getPeriodeAktif();

        $penugasan = collect();
        $totalWajib = 0;
        $totalSelesai = 0;
        $progress = 0;
        $needsPilihRekan = false;

        if ($periodeAktif) {
            $penugasan = PenugasanPenilaian::with(['dinilai.position', 'dinilai.department'])
                ->where('periode_penilaian_id', $periodeAktif->id)
                ->where('penilai_id', $user->id)
                ->get();

            $totalWajib = $penugasan->count();
            $totalSelesai = $penugasan->where('status', 'selesai')->count();
            $progress = $totalWajib > 0 ? round(($totalSelesai / $totalWajib) * 100, 1) : 0;

            if ($user->position?->level >= 4 && $penugasan->where('jenis_penilai', 'rekan')->count() < 3) {
                $needsPilihRekan = true;
            }
        }

        return view('pegawai.dashboard', compact(
            'user',
            'periodeAktif',
            'penugasan',
            'totalWajib',
            'totalSelesai',
            'progress',
            'needsPilihRekan'
        ));
    }
}

