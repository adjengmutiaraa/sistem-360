<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\HasilAkhir;
use App\Models\KategoriNilai;
use App\Models\Penilaian;
use App\Models\PeriodePenilaian;
use Illuminate\Http\Request;

class HasilSayaController extends Controller
{
    public function index(Request $request, \App\Services\KalkulasiNilai360Service $service)
    {
        $user = auth()->user()->load(['jabatan', 'unit']);
        $isKetuaUmum = $user->jabatan?->level === 'ketua_umum';

        $periodes = PeriodePenilaian::orderBy('id', 'desc')->get();

        $selectedPeriodeId = $request->input('periode_id');
        if ($selectedPeriodeId) {
            $selectedPeriode = PeriodePenilaian::find($selectedPeriodeId);
        } else {
            $selectedPeriode = PeriodePenilaian::getPeriodeAktif() ?? PeriodePenilaian::orderBy('id', 'desc')->first();
        }

        $hasil = null;
        $penilaians = collect();
        $countAtasan = 0;
        $countRekan = 0;
        $countBawahan = 0;
        $totalPenilai = 0;

        if ($selectedPeriode && ! $isKetuaUmum) {
            // Auto calculate 360 scores in real-time
            $service->hitungNilaiAkhir($selectedPeriode);

            $hasil = HasilAkhir::where('periode_penilaian_id', $selectedPeriode->id)
                ->where('user_id', $user->id)
                ->first();

            // Fetch evaluations for current user without revealing evaluator identities
            $penilaians = Penilaian::where('periode_penilaian_id', $selectedPeriode->id)
                ->where('dinilai_id', $user->id)
                ->get();

            $countAtasan = $penilaians->where('jenis_penilai', 'atasan')->count();
            $countRekan = $penilaians->where('jenis_penilai', 'rekan')->count();
            $countBawahan = $penilaians->where('jenis_penilai', 'bawahan')->count();
            $totalPenilai = $penilaians->count();
        }

        return view('pegawai.hasil', compact(
            'user',
            'isKetuaUmum',
            'periodes',
            'selectedPeriode',
            'hasil',
            'penilaians',
            'countAtasan',
            'countRekan',
            'countBawahan',
            'totalPenilai'
        ));
    }
}
