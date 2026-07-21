<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePertanyaanRequest;
use App\Http\Requests\Admin\UpdatePertanyaanRequest;
use App\Models\KategoriNilai;
use App\Models\Pertanyaan;

class PertanyaanController extends Controller
{
    public function index()
    {
        $kategoris = KategoriNilai::with(['pertanyaans' => function ($q) {
            $q->orderBy('urutan');
        }])->get();

        $allKategori = KategoriNilai::orderBy('nama_kategori')->get();

        return view('admin.pertanyaans.index', compact('kategoris', 'allKategori'));
    }

    public function store(StorePertanyaanRequest $request)
    {
        Pertanyaan::create($request->validated());

        return redirect()->route('admin.pertanyaans.index')
            ->with('success', 'Pertanyaan penilaian berhasil ditambahkan.');
    }

    public function update(UpdatePertanyaanRequest $request, Pertanyaan $pertanyaan)
    {
        $pertanyaan->update($request->validated());

        return redirect()->route('admin.pertanyaans.index')
            ->with('success', 'Pertanyaan penilaian berhasil diperbarui.');
    }

    public function destroy(Pertanyaan $pertanyaan)
    {
        if ($pertanyaan->detailPenilaian()->count() > 0) {
            return redirect()->route('admin.pertanyaans.index')
                ->with('error', 'Gagal menghapus! Pertanyaan ini sudah pernah digunakan dalam lembar penilaian.');
        }

        $pertanyaan->delete();

        return redirect()->route('admin.pertanyaans.index')
            ->with('success', 'Pertanyaan penilaian berhasil dihapus.');
    }
}
