<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\HasilPenilaianController;
use App\Http\Controllers\Admin\JabatanController;
use App\Http\Controllers\Admin\PegawaiController;
use App\Http\Controllers\Admin\PeriodePenilaianController;
use App\Http\Controllers\Admin\PertanyaanController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Pegawai\DashboardController as PegawaiDashboardController;
use App\Http\Controllers\Pegawai\HasilSayaController;
use App\Http\Controllers\Pegawai\PenilaianController as PegawaiPenilaianController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Master Data Routes
    Route::resource('jabatans', JabatanController::class)->except(['create', 'edit', 'show']);
    Route::resource('units', UnitController::class)->except(['create', 'edit', 'show']);
    Route::resource('pegawai', PegawaiController::class);
    Route::resource('pertanyaans', PertanyaanController::class)->except(['create', 'edit', 'show']);

    // Periode Penilaian & Penugasan Routes
    Route::resource('periodes', PeriodePenilaianController::class);
    Route::post('periodes/{periode}/toggle-status', [PeriodePenilaianController::class, 'toggleStatus'])->name('periodes.toggle-status');
    Route::post('periodes/{periode}/generate-penugasan', [PeriodePenilaianController::class, 'generatePenugasan'])->name('periodes.generate-penugasan');

    // Hasil Penilaian 360° & Report Routes (Specific routes defined before model binding wildcards)
    Route::get('hasil', [HasilPenilaianController::class, 'index'])->name('hasil.index');
    Route::post('hasil/{periode}/kalkulasi', [HasilPenilaianController::class, 'kalkulasi'])->name('hasil.kalkulasi');
    Route::get('hasil/{periode}/excel', [HasilPenilaianController::class, 'exportExcel'])->name('hasil.excel');
    Route::get('hasil/{periode}/{user}', [HasilPenilaianController::class, 'show'])->name('hasil.show');
    Route::get('hasil/{periode}/{user}/pdf', [HasilPenilaianController::class, 'exportPdf'])->name('hasil.pdf');
});

// Pegawai Routes
Route::middleware(['auth', 'pegawai'])->prefix('pegawai')->name('pegawai.')->group(function () {
    Route::get('/dashboard', [PegawaiDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profil', [PegawaiPenilaianController::class, 'profil'])->name('profil');

    // Penilaian 360° Routes
    Route::get('/penilaian', [PegawaiPenilaianController::class, 'index'])->name('penilaian.index');
    Route::get('/penilaian/pilih-rekan', [PegawaiPenilaianController::class, 'pilihRekan'])->name('penilaian.pilih-rekan');
    Route::post('/penilaian/pilih-rekan', [PegawaiPenilaianController::class, 'storePilihRekan'])->name('penilaian.store-pilih-rekan');
    Route::get('/penilaian/isi/{dinilai}', [PegawaiPenilaianController::class, 'create'])->name('penilaian.create');
    Route::post('/penilaian/isi/{dinilai}', [PegawaiPenilaianController::class, 'store'])->name('penilaian.store');

    // Hasil Penilaian Saya Route
    Route::get('/hasil-saya', [HasilSayaController::class, 'index'])->name('hasil-saya');
});

require __DIR__.'/auth.php';
