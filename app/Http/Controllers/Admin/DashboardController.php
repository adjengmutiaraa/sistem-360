<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HasilAkhir;
use App\Models\PeriodePenilaian;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPegawai = User::where('role', 'pegawai')->count();
        $totalStaff = User::whereHas('jabatan', fn ($q) => $q->where('level', 'staff'))->count();
        $totalKabid = User::whereHas('jabatan', fn ($q) => $q->where('level', 'kabid'))->count();
        $totalKetua = User::whereHas('jabatan', fn ($q) => $q->where('level', 'ketua_umum'))->count();

        $periodeAktif = PeriodePenilaian::getPeriodeAktif();

        $top5Hasils = collect();
        if ($periodeAktif) {
            $top5Hasils = HasilAkhir::with(['user.jabatan', 'user.unit'])
                ->where('periode_penilaian_id', $periodeAktif->id)
                ->orderBy('nilai_akhir', 'desc')
                ->take(5)
                ->get();
        }

        return view('admin.dashboard', compact(
            'totalPegawai',
            'totalStaff',
            'totalKabid',
            'totalKetua',
            'periodeAktif',
            'top5Hasils'
        ));
    }
}
