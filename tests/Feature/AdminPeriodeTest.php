<?php

namespace Tests\Feature;

use App\Models\PeriodePenilaian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPeriodeTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        PeriodePenilaian::query()->delete();
        $this->admin = User::where('role', 'admin')->first();
    }

    public function test_admin_can_create_active_periode_and_auto_generates_360_matrix(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.periodes.store'), [
            'nama_periode' => 'Periode Masa Depan Test',
            'tanggal_mulai' => now()->format('Y-m-d'),
            'tanggal_selesai' => now()->addDays(14)->format('Y-m-d'),
            'status' => 'aktif',
            'deskripsi' => 'Periode Uji Coba',
        ]);

        $response->assertRedirect(route('admin.periodes.index'));
        $this->assertDatabaseHas('periode_penilaians', ['nama_periode' => 'Periode Masa Depan Test', 'status' => 'aktif']);

        $periode = PeriodePenilaian::where('nama_periode', 'Periode Masa Depan Test')->first();
        $this->assertGreaterThan(0, $periode->penugasan()->count());
    }

    public function test_cannot_create_second_active_periode(): void
    {
        // Active 1
        PeriodePenilaian::create([
            'nama_periode' => 'Periode 1',
            'tanggal_mulai' => now()->format('Y-m-d'),
            'tanggal_selesai' => now()->addDays(14)->format('Y-m-d'),
            'status' => 'aktif',
        ]);

        // Try creating Active 2
        $response = $this->actingAs($this->admin)->post(route('admin.periodes.store'), [
            'nama_periode' => 'Periode 2',
            'tanggal_mulai' => now()->addDays(15)->format('Y-m-d'),
            'tanggal_selesai' => now()->addDays(30)->format('Y-m-d'),
            'status' => 'aktif',
        ]);

        $response->assertSessionHasErrors('status');
        $this->assertDatabaseMissing('periode_penilaians', ['nama_periode' => 'Periode 2']);
    }

    public function test_admin_can_toggle_periode_status(): void
    {
        $periode = PeriodePenilaian::create([
            'nama_periode' => 'Periode Status Test',
            'tanggal_mulai' => now()->format('Y-m-d'),
            'tanggal_selesai' => now()->addDays(14)->format('Y-m-d'),
            'status' => 'aktif',
        ]);

        // Toggle to selesai
        $response = $this->actingAs($this->admin)->post(route('admin.periodes.toggle-status', $periode));
        $response->assertRedirect(route('admin.periodes.index'));
        $this->assertEquals('selesai', $periode->fresh()->status);

        // Toggle back to aktif
        $response = $this->actingAs($this->admin)->post(route('admin.periodes.toggle-status', $periode));
        $response->assertRedirect(route('admin.periodes.index'));
        $this->assertEquals('aktif', $periode->fresh()->status);
    }

    public function test_periode_auto_closes_when_expired(): void
    {
        // Active period in the past
        $periode = PeriodePenilaian::create([
            'nama_periode' => 'Periode Lampau',
            'tanggal_mulai' => '2020-01-01',
            'tanggal_selesai' => '2020-01-10',
            'status' => 'aktif',
        ]);

        $active = PeriodePenilaian::getPeriodeAktif();

        $this->assertNull($active);
        $this->assertEquals('selesai', $periode->fresh()->status);
    }
}
