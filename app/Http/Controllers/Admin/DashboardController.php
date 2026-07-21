<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HasilAkhir;
use App\Models\PeriodePenilaian;
use App\Models\User;
use App\Models\Department;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPegawai = User::whereDoesntHave('roles', function($q) {
            $q->whereIn('name', ['Super Admin', 'Admin BKPSDM']);
        })->count();
        
        $totalStaff = User::whereHas('position', fn ($q) => $q->where('level', '>=', 4))->count();
        $totalKabid = User::whereHas('position', fn ($q) => $q->where('level', 3))->count();
        $totalKetua = User::whereHas('position', fn ($q) => $q->where('level', 1))->count();

        $periodeAktif = PeriodePenilaian::getPeriodeAktif();

        $top5Hasils = collect();
        if ($periodeAktif) {
            $top5Hasils = HasilAkhir::with(['user.position', 'user.department'])
                ->where('periode_penilaian_id', $periodeAktif->id)
                ->orderBy('nilai_akhir', 'desc')
                ->take(5)
                ->get();
        }

        // Get organizational structure (Root department which is level 1)
        $orgStructure = Department::whereNull('parent_id')->with('children.children')->get();

        return view('admin.dashboard', compact(
            'totalPegawai',
            'totalStaff',
            'totalKabid',
            'totalKetua',
            'periodeAktif',
            'top5Hasils',
            'orgStructure'
        ));
    }
}
