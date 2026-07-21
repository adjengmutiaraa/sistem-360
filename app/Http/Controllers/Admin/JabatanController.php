<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreJabatanRequest;
use App\Http\Requests\Admin\UpdateJabatanRequest;
use App\Models\Jabatan;

class JabatanController extends Controller
{
    public function index()
    {
        $jabatans = Jabatan::withCount('users')->orderBy('id')->get();

        return view('admin.jabatans.index', compact('jabatans'));
    }

    public function store(StoreJabatanRequest $request)
    {
        Jabatan::create($request->validated());

        return redirect()->route('admin.jabatans.index')
            ->with('success', 'Data Jabatan berhasil ditambahkan.');
    }

    public function update(UpdateJabatanRequest $request, Jabatan $jabatan)
    {
        $jabatan->update($request->validated());

        return redirect()->route('admin.jabatans.index')
            ->with('success', 'Data Jabatan berhasil diperbarui.');
    }

    public function destroy(Jabatan $jabatan)
    {
        if ($jabatan->users()->count() > 0) {
            return redirect()->route('admin.jabatans.index')
                ->with('error', 'Gagal menghapus! Masih ada pegawai yang terhubung dengan jabatan ini.');
        }

        $jabatan->delete();

        return redirect()->route('admin.jabatans.index')
            ->with('success', 'Data Jabatan berhasil dihapus.');
    }
}
