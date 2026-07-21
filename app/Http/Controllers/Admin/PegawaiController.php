<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePegawaiRequest;
use App\Http\Requests\Admin\UpdatePegawaiRequest;
use App\Models\Jabatan;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['jabatan', 'unit', 'atasan']);

        if (($search = $request->input('search')) !== null && trim($search) !== '') {
            $term = mb_strtolower(trim($search));
            $query->where(function ($q) use ($term) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(nip) LIKE ?', ["%{$term}%"])
                    ->orWhereRaw('LOWER(email) LIKE ?', ["%{$term}%"]);
            });
        }

        if ($unitId = $request->input('unit_id')) {
            $query->where('unit_id', $unitId);
        }

        if ($jabatanId = $request->input('jabatan_id')) {
            $query->where('jabatan_id', $jabatanId);
        }

        $pegawais = $query->orderBy('name')->paginate(10)->withQueryString();
        $units = Unit::orderBy('nama_unit')->get();
        $jabatans = Jabatan::orderBy('id')->get();

        return view('admin.pegawai.index', compact('pegawais', 'units', 'jabatans'));
    }

    public function create()
    {
        $jabatans = Jabatan::orderBy('id')->get();
        $units = Unit::orderBy('nama_unit')->get();
        // Atasan potential is Ketua Umum or Kabid
        $atasans = User::whereHas('jabatan', function ($q) {
            $q->whereIn('level', ['ketua_umum', 'kabid']);
        })->orderBy('name')->get();

        return view('admin.pegawai.create', compact('jabatans', 'units', 'atasans'));
    }

    public function store(StorePegawaiRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('admin.pegawai.index')
            ->with('success', 'Data Pegawai berhasil ditambahkan.');
    }

    public function show(User $pegawai)
    {
        $pegawai->load(['jabatan', 'unit', 'atasan', 'bawahan']);

        return view('admin.pegawai.show', compact('pegawai'));
    }

    public function edit(User $pegawai)
    {
        $jabatans = Jabatan::orderBy('id')->get();
        $units = Unit::orderBy('nama_unit')->get();
        $atasans = User::where('id', '!=', $pegawai->id)
            ->whereHas('jabatan', function ($q) {
                $q->whereIn('level', ['ketua_umum', 'kabid']);
            })->orderBy('name')->get();

        return view('admin.pegawai.edit', compact('pegawai', 'jabatans', 'units', 'atasans'));
    }

    public function update(UpdatePegawaiRequest $request, User $pegawai)
    {
        $data = $request->validated();

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $pegawai->update($data);

        return redirect()->route('admin.pegawai.index')
            ->with('success', 'Data Pegawai berhasil diperbarui.');
    }

    public function destroy(User $pegawai)
    {
        if ($pegawai->bawahan()->count() > 0) {
            return redirect()->route('admin.pegawai.index')
                ->with('error', 'Gagal menghapus! Pegawai ini memiliki bawahan terdaftar. Ubah atasan bawahannya terlebih dahulu.');
        }

        $pegawai->delete();

        return redirect()->route('admin.pegawai.index')
            ->with('success', 'Data Pegawai berhasil dihapus.');
    }
}
