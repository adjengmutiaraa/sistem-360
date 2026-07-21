<?php

namespace Tests\Feature;

use App\Models\Jabatan;
use App\Models\KategoriNilai;
use App\Models\Pertanyaan;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminMasterDataTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->admin = User::where('role', 'admin')->first();
    }

    public function test_admin_can_crud_jabatan(): void
    {
        // 1. Create
        $response = $this->actingAs($this->admin)->post(route('admin.jabatans.store'), [
            'nama_jabatan' => 'Kepala Seksi Operasional',
            'level' => 'kabid',
        ]);
        $response->assertRedirect(route('admin.jabatans.index'));
        $this->assertDatabaseHas('jabatans', ['nama_jabatan' => 'Kepala Seksi Operasional']);

        $jabatan = Jabatan::where('nama_jabatan', 'Kepala Seksi Operasional')->first();

        // 2. Update
        $response = $this->actingAs($this->admin)->put(route('admin.jabatans.update', $jabatan), [
            'nama_jabatan' => 'Kepala Seksi Operasional V2',
            'level' => 'kabid',
        ]);
        $response->assertRedirect(route('admin.jabatans.index'));
        $this->assertDatabaseHas('jabatans', ['nama_jabatan' => 'Kepala Seksi Operasional V2']);

        // 3. Delete
        $response = $this->actingAs($this->admin)->delete(route('admin.jabatans.destroy', $jabatan));
        $response->assertRedirect(route('admin.jabatans.index'));
        $this->assertDatabaseMissing('jabatans', ['id' => $jabatan->id]);
    }

    public function test_admin_can_crud_unit(): void
    {
        // 1. Create
        $response = $this->actingAs($this->admin)->post(route('admin.units.store'), [
            'nama_unit' => 'Bidang Keuangan & Akuntansi',
            'kode_unit' => 'KEUANGAN',
        ]);
        $response->assertRedirect(route('admin.units.index'));
        $this->assertDatabaseHas('units', ['kode_unit' => 'KEUANGAN']);

        $unit = Unit::where('kode_unit', 'KEUANGAN')->first();

        // 2. Update
        $response = $this->actingAs($this->admin)->put(route('admin.units.update', $unit), [
            'nama_unit' => 'Bidang Keuangan & Pajak',
            'kode_unit' => 'KEUANGAN_V2',
        ]);
        $response->assertRedirect(route('admin.units.index'));
        $this->assertDatabaseHas('units', ['kode_unit' => 'KEUANGAN_V2']);

        // 3. Delete
        $response = $this->actingAs($this->admin)->delete(route('admin.units.destroy', $unit));
        $response->assertRedirect(route('admin.units.index'));
        $this->assertDatabaseMissing('units', ['id' => $unit->id]);
    }

    public function test_admin_can_crud_pegawai(): void
    {
        $jabatan = Jabatan::first();
        $unit = Unit::first();

        // 1. Create
        $response = $this->actingAs($this->admin)->post(route('admin.pegawai.store'), [
            'nip' => '199912122022011009',
            'name' => 'Pegawai Baru Test',
            'email' => 'pegawaibaru@sistem360.go.id',
            'password' => 'password123',
            'role' => 'pegawai',
            'jabatan_id' => $jabatan->id,
            'unit_id' => $unit->id,
        ]);
        $response->assertRedirect(route('admin.pegawai.index'));
        $this->assertDatabaseHas('users', ['nip' => '199912122022011009']);

        $pegawai = User::where('nip', '199912122022011009')->first();

        // 2. Update
        $response = $this->actingAs($this->admin)->put(route('admin.pegawai.update', $pegawai), [
            'nip' => '199912122022011009',
            'name' => 'Pegawai Baru Updated',
            'email' => 'pegawaibaru@sistem360.go.id',
            'role' => 'pegawai',
            'jabatan_id' => $jabatan->id,
            'unit_id' => $unit->id,
        ]);
        $response->assertRedirect(route('admin.pegawai.index'));
        $this->assertDatabaseHas('users', ['name' => 'Pegawai Baru Updated']);

        // 3. Delete
        $response = $this->actingAs($this->admin)->delete(route('admin.pegawai.destroy', $pegawai));
        $response->assertRedirect(route('admin.pegawai.index'));
        $this->assertDatabaseMissing('users', ['id' => $pegawai->id]);
    }

    public function test_admin_can_crud_pertanyaan(): void
    {
        $kategori = KategoriNilai::first();

        // 1. Create
        $response = $this->actingAs($this->admin)->post(route('admin.pertanyaans.store'), [
            'pertanyaan' => 'Apakah pegawai selalu hadir tepat waktu?',
            'kategori_nilai_id' => $kategori->id,
            'urutan' => 99,
        ]);
        $response->assertRedirect(route('admin.pertanyaans.index'));
        $this->assertDatabaseHas('pertanyaans', ['pertanyaan' => 'Apakah pegawai selalu hadir tepat waktu?']);

        $pertanyaan = Pertanyaan::where('pertanyaan', 'Apakah pegawai selalu hadir tepat waktu?')->first();

        // 2. Update
        $response = $this->actingAs($this->admin)->put(route('admin.pertanyaans.update', $pertanyaan), [
            'pertanyaan' => 'Apakah pegawai selalu hadir tepat waktu di kantor?',
            'kategori_nilai_id' => $kategori->id,
            'urutan' => 99,
        ]);
        $response->assertRedirect(route('admin.pertanyaans.index'));
        $this->assertDatabaseHas('pertanyaans', ['pertanyaan' => 'Apakah pegawai selalu hadir tepat waktu di kantor?']);

        // 3. Delete
        $response = $this->actingAs($this->admin)->delete(route('admin.pertanyaans.destroy', $pertanyaan));
        $response->assertRedirect(route('admin.pertanyaans.index'));
        $this->assertDatabaseMissing('pertanyaans', ['id' => $pertanyaan->id]);
    }
}
