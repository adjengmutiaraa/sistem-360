<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePeriodeRequest;
use App\Http\Requests\Admin\UpdatePeriodeRequest;
use App\Models\PenugasanPenilaian;
use App\Models\PeriodePenilaian;
use App\Services\Penugasan360Service;
use Illuminate\Http\Request;

class PeriodePenilaianController extends Controller
{
    public function index()
    {
        $periodes = PeriodePenilaian::withCount([
            'penugasan as total_penugasan',
            'penugasan as penugasan_selesai' => function ($q) {
                $q->where('status', 'selesai');
            },
        ])->orderBy('id', 'desc')->get();

        return view('admin.periodes.index', compact('periodes'));
    }

    public function store(StorePeriodeRequest $request, Penugasan360Service $service)
    {
        $periode = PeriodePenilaian::create($request->validated());

        if ($periode->status === 'aktif') {
            $count = $service->generatePenugasan($periode);

            return redirect()->route('admin.periodes.index')
                ->with('success', "Periode berhasil dibuat & diaktifkan. Matriks {$count} penugasan 360° berhasil di-generate.");
        }

        return redirect()->route('admin.periodes.index')
            ->with('success', 'Periode penilaian berhasil ditambahkan.');
    }

    public function show(Request $request, PeriodePenilaian $periode)
    {
        $query = PenugasanPenilaian::with(['penilai.jabatan', 'dinilai.jabatan'])
            ->where('periode_penilaian_id', $periode->id);

        if ($jenis = $request->input('jenis_penilai')) {
            $query->where('jenis_penilai', $jenis);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if (($search = $request->input('search')) !== null && trim($search) !== '') {
            $term = mb_strtolower(trim($search));
            $query->where(function ($q) use ($term) {
                $q->whereHas('penilai', fn ($u) => $u->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"]))
                    ->orWhereHas('dinilai', fn ($u) => $u->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"]));
            });
        }

        $penugasantable = $query->paginate(15)->withQueryString();

        $totalPenugasan = $periode->penugasan()->count();
        $totalSelesai = $periode->penugasan()->where('status', 'selesai')->count();
        $progress = $totalPenugasan > 0 ? round(($totalSelesai / $totalPenugasan) * 100, 1) : 0;

        return view('admin.periodes.show', compact('periode', 'penugasantable', 'totalPenugasan', 'totalSelesai', 'progress'));
    }

    public function update(UpdatePeriodeRequest $request, PeriodePenilaian $periode)
    {
        $periode->update($request->validated());

        return redirect()->route('admin.periodes.index')
            ->with('success', 'Data Periode Penilaian berhasil diperbarui.');
    }

    public function toggleStatus(PeriodePenilaian $periode, Penugasan360Service $service)
    {
        if ($periode->status === 'aktif') {
            $periode->update(['status' => 'selesai']);
            $msg = "Periode '{$periode->nama_periode}' telah ditutup/diselesaikan.";
        } else {
            // Check if there is another active period
            $hasActiveOther = PeriodePenilaian::where('status', 'aktif')
                ->where('id', '!=', $periode->id)
                ->exists();

            if ($hasActiveOther) {
                return redirect()->route('admin.periodes.index')
                    ->with('error', 'Gagal mengaktifkan! Hanya boleh ada 1 periode aktif dalam satu waktu.');
            }

            $periode->update(['status' => 'aktif']);
            $count = $service->generatePenugasan($periode);
            $msg = "Periode '{$periode->nama_periode}' berhasil diaktifkan. Matriks {$count} penugasan baru di-generate.";
        }

        return redirect()->route('admin.periodes.index')->with('success', $msg);
    }

    public function generatePenugasan(PeriodePenilaian $periode, Penugasan360Service $service)
    {
        $count = $service->generatePenugasan($periode);

        return redirect()->back()
            ->with('success', "Matriks penugasan 360° berhasil diperbarui/di-generate ({$count} penugasan baru dibuat).");
    }

    public function destroy(PeriodePenilaian $periode)
    {
        $periode->delete();

        return redirect()->route('admin.periodes.index')
            ->with('success', 'Periode Penilaian beserta data penugasan berhasil dihapus.');
    }
}
