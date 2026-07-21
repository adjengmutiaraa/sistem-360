<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pegawai\PilihRekanRequest;
use App\Http\Requests\Pegawai\StorePenilaianRequest;
use App\Models\DetailPenilaian;
use App\Models\KategoriNilai;
use App\Models\Penilaian;
use App\Models\PenugasanPenilaian;
use App\Models\PeriodePenilaian;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PenilaianController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $periodeAktif = PeriodePenilaian::getPeriodeAktif();

        if (! $periodeAktif) {
            return view('pegawai.penilaian.index', [
                'periodeAktif' => null,
                'penugasantable' => collect(),
                'needsPilihRekan' => false,
                'isComplete' => false,
            ]);
        }

        $penugasan = PenugasanPenilaian::with(['dinilai.position', 'dinilai.department'])
            ->where('periode_penilaian_id', $periodeAktif->id)
            ->where('penilai_id', $user->id)
            ->get();

        // Check if staff user needs to select 3 peer staff members
        $isStaff = $user->position?->level >= 4;
        $peerAssignmentsCount = $penugasan->where('jenis_penilai', 'rekan')->count();
        $needsPilihRekan = $isStaff && $peerAssignmentsCount < 3;

        // Check overall completion rule
        $totalWajib = $penugasan->count();
        $totalSelesai = $penugasan->where('status', 'selesai')->count();
        $isComplete = ($totalWajib > 0) && ($totalWajib === $totalSelesai) && ! $needsPilihRekan;

        return view('pegawai.penilaian.index', compact(
            'periodeAktif',
            'penugasan',
            'isStaff',
            'needsPilihRekan',
            'peerAssignmentsCount',
            'totalWajib',
            'totalSelesai',
            'isComplete'
        ));
    }

    public function pilihRekan()
    {
        $user = auth()->user();
        $periodeAktif = PeriodePenilaian::getPeriodeAktif();

        if (! $periodeAktif) {
            return redirect()->route('pegawai.penilaian.index')
                ->with('error', 'Tidak ada periode penilaian yang sedang aktif.');
        }

        if ($user->position?->level !== 'staff') {
            return redirect()->route('pegawai.penilaian.index')
                ->with('error', 'Fitur pemilihan rekan staff hanya khusus untuk pegawai berlevel Staff.');
        }

        // Selected peers currently
        $existingPeerIds = PenugasanPenilaian::where('periode_penilaian_id', $periodeAktif->id)
            ->where('penilai_id', $user->id)
            ->where('jenis_penilai', 'rekan')
            ->pluck('dinilai_id')
            ->toArray();

        // Potential peer staff list (exclude self)
        $availableStaffs = User::where('id', '!=', $user->id)
            ->whereHas('position', fn ($q) => $q->where('level', 'staff'))
            ->with(['department', 'position'])
            ->get();

        // Annotate each staff whether they already have 3 peer evaluators
        $staffs = $availableStaffs->map(function ($staff) use ($periodeAktif, $user) {
            $peerCount = PenugasanPenilaian::where('periode_penilaian_id', $periodeAktif->id)
                ->where('dinilai_id', $staff->id)
                ->where('jenis_penilai', 'rekan')
                ->where('penilai_id', '!=', $user->id)
                ->count();

            $staff->is_full = $peerCount >= 3;
            $staff->current_peer_evaluators = $peerCount;

            return $staff;
        });

        return view('pegawai.penilaian.pilih_rekan', compact('periodeAktif', 'staffs', 'existingPeerIds'));
    }

    public function storePilihRekan(PilihRekanRequest $request)
    {
        $user = auth()->user();
        $periodeAktif = PeriodePenilaian::getPeriodeAktif();

        if (! $periodeAktif) {
            return redirect()->route('pegawai.penilaian.index')
                ->with('error', 'Periode penilaian sudah tidak aktif.');
        }

        $rekanIds = $request->input('rekan_ids');

        foreach ($rekanIds as $rekanId) {
            PenugasanPenilaian::firstOrCreate([
                'periode_penilaian_id' => $periodeAktif->id,
                'penilai_id' => $user->id,
                'dinilai_id' => $rekanId,
            ], [
                'jenis_penilai' => 'rekan',
                'status' => 'belum',
            ]);
        }

        return redirect()->route('pegawai.penilaian.index')
            ->with('success', '3 Rekan Staff berhasil dipilih. Silakan lanjutkan pengisian penilaian.');
    }

    public function create(User $dinilai)
    {
        $user = auth()->user();
        $periodeAktif = PeriodePenilaian::getPeriodeAktif();

        if (! $periodeAktif) {
            return redirect()->route('pegawai.penilaian.index')
                ->with('error', 'Form penilaian dikunci karena tidak ada periode aktif.');
        }

        $penugasan = PenugasanPenilaian::where('periode_penilaian_id', $periodeAktif->id)
            ->where('penilai_id', $user->id)
            ->where('dinilai_id', $dinilai->id)
            ->first();

        if (! $penugasan) {
            return redirect()->route('pegawai.penilaian.index')
                ->with('error', 'Anda tidak memiliki tugas untuk menilai pegawai ini.');
        }

        if ($penugasan->status === 'selesai') {
            return redirect()->route('pegawai.penilaian.index')
                ->with('info', "Anda sudah menyelesaikan penilaian untuk {$dinilai->name}.");
        }

        $kategoris = KategoriNilai::with(['pertanyaans' => function ($q) {
            $q->orderBy('urutan');
        }])->get();

        return view('pegawai.penilaian.create', compact('periodeAktif', 'dinilai', 'penugasan', 'kategoris'));
    }

    public function store(StorePenilaianRequest $request, User $dinilai)
    {
        $user = auth()->user();
        $periodeAktif = PeriodePenilaian::getPeriodeAktif();

        if (! $periodeAktif) {
            return redirect()->route('pegawai.penilaian.index')
                ->with('error', 'Gagal menyimpan. Periode penilaian telah berakhir/ditutup.');
        }

        $penugasan = PenugasanPenilaian::where('periode_penilaian_id', $periodeAktif->id)
            ->where('penilai_id', $user->id)
            ->where('dinilai_id', $dinilai->id)
            ->firstOrFail();

        DB::transaction(function () use ($request, $periodeAktif, $user, $dinilai, $penugasan) {
            // 1. Create or update Penilaian record
            $penilaian = Penilaian::updateOrCreate([
                'periode_penilaian_id' => $periodeAktif->id,
                'penilai_id' => $user->id,
                'dinilai_id' => $dinilai->id,
            ], [
                'jenis_penilai' => $penugasan->jenis_penilai,
                'catatan' => $request->input('catatan'),
            ]);

            // 2. Insert Detail Penilaian
            foreach ($request->input('skor', []) as $pertanyaanId => $skor) {
                DetailPenilaian::updateOrCreate([
                    'penilaian_id' => $penilaian->id,
                    'pertanyaan_id' => $pertanyaanId,
                ], [
                    'skor' => $skor,
                ]);
            }

            // 3. Mark Penugasan as Selesai
            $penugasan->update(['status' => 'selesai']);
        });

        // Auto calculate 360 final scores in real-time
        app(\App\Services\KalkulasiNilai360Service::class)->hitungNilaiAkhir($periodeAktif);

        return redirect()->route('pegawai.penilaian.index')
            ->with('success', "Penilaian 360° untuk {$dinilai->name} berhasil disimpan.");
    }

    public function profil()
    {
        $user = User::with(['position', 'department', 'atasan', 'bawahan'])->find(auth()->id());

        return view('pegawai.profil', compact('user'));
    }
}

