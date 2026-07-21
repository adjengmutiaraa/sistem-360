<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUnitRequest;
use App\Http\Requests\Admin\UpdateUnitRequest;
use App\Models\Unit;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::withCount('users')->orderBy('id')->get();

        return view('admin.units.index', compact('units'));
    }

    public function store(StoreUnitRequest $request)
    {
        Unit::create($request->validated());

        return redirect()->route('admin.units.index')
            ->with('success', 'Data Unit Kerja berhasil ditambahkan.');
    }

    public function update(UpdateUnitRequest $request, Unit $unit)
    {
        $unit->update($request->validated());

        return redirect()->route('admin.units.index')
            ->with('success', 'Data Unit Kerja berhasil diperbarui.');
    }

    public function destroy(Unit $unit)
    {
        if ($unit->users()->count() > 0) {
            return redirect()->route('admin.units.index')
                ->with('error', 'Gagal menghapus! Masih ada pegawai yang terhubung dengan unit kerja ini.');
        }

        $unit->delete();

        return redirect()->route('admin.units.index')
            ->with('success', 'Data Unit Kerja berhasil dihapus.');
    }
}
