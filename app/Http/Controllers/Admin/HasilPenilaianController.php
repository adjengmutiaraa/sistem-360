<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HasilAkhir;
use App\Models\Jabatan;
use App\Models\KategoriNilai;
use App\Models\Penilaian;
use App\Models\PeriodePenilaian;
use App\Models\Unit;
use App\Models\User;
use App\Services\KalkulasiNilai360Service;
use Illuminate\Http\Request;

class HasilPenilaianController extends Controller
{
    public function index(Request $request, KalkulasiNilai360Service $service)
    {
        $periodes = PeriodePenilaian::orderBy('id', 'desc')->get();
        $selectedPeriodeId = $request->input('periode_id');

        if ($selectedPeriodeId) {
            $selectedPeriode = PeriodePenilaian::find($selectedPeriodeId);
        } else {
            $selectedPeriode = PeriodePenilaian::getPeriodeAktif() ?? PeriodePenilaian::orderBy('id', 'desc')->first();
        }

        $hasils = collect();
        $units = Unit::orderBy('nama_unit')->get();
        $jabatans = Jabatan::orderBy('id')->get();

        if ($selectedPeriode) {
            // Auto calculate scores in real-time
            $service->hitungNilaiAkhir($selectedPeriode);

            $query = HasilAkhir::with(['user.jabatan', 'user.unit'])
                ->where('periode_penilaian_id', $selectedPeriode->id);

            if (($search = $request->input('search')) !== null && trim($search) !== '') {
                $term = mb_strtolower(trim($search));
                $query->whereHas('user', function ($q) use ($term) {
                    $q->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"])
                        ->orWhereRaw('LOWER(nip) LIKE ?', ["%{$term}%"]);
                });
            }

            if ($unitId = $request->input('unit_id')) {
                $query->whereHas('user', fn ($q) => $q->where('unit_id', $unitId));
            }

            if ($jabatanId = $request->input('jabatan_id')) {
                $query->whereHas('user', fn ($q) => $q->where('jabatan_id', $jabatanId));
            }

            $hasils = $query->orderBy('nilai_akhir', 'desc')->paginate(15)->withQueryString();
        }

        return view('admin.hasil.index', compact('periodes', 'selectedPeriode', 'hasils', 'units', 'jabatans'));
    }

    public function show(PeriodePenilaian $periode, User $user)
    {
        $hasil = HasilAkhir::where('periode_penilaian_id', $periode->id)
            ->where('user_id', $user->id)
            ->first();

        $penilaians = Penilaian::with(['penilai.jabatan', 'detailPenilaian.pertanyaan.kategoriNilai'])
            ->where('periode_penilaian_id', $periode->id)
            ->where('dinilai_id', $user->id)
            ->get();

        $kategoris = KategoriNilai::with('pertanyaans')->get();

        return view('admin.hasil.show', compact('periode', 'user', 'hasil', 'penilaians', 'kategoris'));
    }

    public function kalkulasi(PeriodePenilaian $periode, KalkulasiNilai360Service $service)
    {
        $count = $service->hitungNilaiAkhir($periode);

        return redirect()->route('admin.hasil.index', ['periode_id' => $periode->id])
            ->with('success', "Kalkulasi nilai akhir 360° berhasil diproses untuk {$count} pegawai.");
    }

    public function exportPdf(PeriodePenilaian $periode, User $user)
    {
        $hasil = HasilAkhir::where('periode_penilaian_id', $periode->id)
            ->where('user_id', $user->id)
            ->first();

        $penilaians = Penilaian::with(['penilai.jabatan', 'detailPenilaian.pertanyaan.kategoriNilai'])
            ->where('periode_penilaian_id', $periode->id)
            ->where('dinilai_id', $user->id)
            ->get();

        $kategoris = KategoriNilai::with('pertanyaans')->get();

        return view('admin.hasil.pdf_single', compact('periode', 'user', 'hasil', 'penilaians', 'kategoris'));
    }

    public function exportExcel(PeriodePenilaian $periode)
    {
        $hasils = HasilAkhir::with(['user.jabatan', 'user.unit'])
            ->where('periode_penilaian_id', $periode->id)
            ->orderBy('nilai_akhir', 'desc')
            ->get();

        $filename = 'Rekapitulasi_Nilai_360_' . str_replace(' ', '_', $periode->nama_periode) . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($hasils, $periode) {
            $file = fopen('php://output', 'w');

            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, ["REKAPITULASI HASIL PENILAIAN KINERJA 360 DEGERAJAT ASN"]);
            fputcsv($file, ["Periode: {$periode->nama_periode}"]);
            fputcsv($file, []);
            fputcsv($file, ['Ranking', 'NIP', 'Nama Pegawai', 'Jabatan', 'Unit Kerja', 'Nilai Atasan', 'Nilai Rekan', 'Nilai Bawahan', 'Nilai Akhir', 'Kategori Predikat']);

            foreach ($hasils as $rank => $h) {
                fputcsv($file, [
                    $rank + 1,
                    "'".$h->user->nip,
                    $h->user->name,
                    $h->user->jabatan?->nama_jabatan ?? '-',
                    $h->user->unit?->nama_unit ?? '-',
                    $h->nilai_atasan ?? '-',
                    $h->nilai_rekan ?? '-',
                    $h->nilai_bawahan ?? '-',
                    $h->nilai_akhir,
                    $h->kategori,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
