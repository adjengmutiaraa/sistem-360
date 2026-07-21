<?php

namespace Tests\Feature;

use App\Models\HasilAkhir;
use App\Models\Penilaian;
use App\Models\PeriodePenilaian;
use App\Models\Pertanyaan;
use App\Models\User;
use App\Services\KalkulasiNilai360Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KalkulasiNilaiTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $staff;
    protected User $kabid;
    protected PeriodePenilaian $periode;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->admin = User::where('role', 'admin')->first();
        $this->staff = User::where('email', 'staff1@sistem360.go.id')->first();
        $this->kabid = User::where('email', 'kabid1@sistem360.go.id')->first();
        $this->periode = PeriodePenilaian::where('status', 'aktif')->first();
    }

    public function test_kalkulasi_service_calculates_staff_weighted_score_correctly(): void
    {
        $evaluatorAtasan = User::where('email', 'kabid1@sistem360.go.id')->first();
        $evaluatorRekan = User::where('email', 'staff2@sistem360.go.id')->first();
        $pertanyaans = Pertanyaan::all();

        // 1. Atasan score = 5/5 -> 100
        $p1 = Penilaian::create([
            'periode_penilaian_id' => $this->periode->id,
            'penilai_id' => $evaluatorAtasan->id,
            'dinilai_id' => $this->staff->id,
            'jenis_penilai' => 'atasan',
        ]);
        foreach ($pertanyaans as $p) {
            $p1->detailPenilaian()->create(['pertanyaan_id' => $p->id, 'skor' => 5]);
        }

        // 2. Rekan score = 4/5 -> 80
        $p2 = Penilaian::create([
            'periode_penilaian_id' => $this->periode->id,
            'penilai_id' => $evaluatorRekan->id,
            'dinilai_id' => $this->staff->id,
            'jenis_penilai' => 'rekan',
        ]);
        foreach ($pertanyaans as $p) {
            $p2->detailPenilaian()->create(['pertanyaan_id' => $p->id, 'skor' => 4]);
        }

        // Staff Formula: (100 * 50%) + (80 * 50%) = 50 + 40 = 90.00 (Sangat Baik)
        $service = new KalkulasiNilai360Service();
        $service->hitungNilaiAkhir($this->periode);

        $hasil = HasilAkhir::where('periode_penilaian_id', $this->periode->id)
            ->where('user_id', $this->staff->id)
            ->first();

        $this->assertNotNull($hasil);
        $this->assertEquals(90.00, $hasil->nilai_akhir);
        $this->assertEquals('Sangat Baik', $hasil->kategori);
    }

    public function test_admin_can_trigger_kalkulasi_and_view_reports(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.hasil.kalkulasi', $this->periode));
        $response->assertRedirect();

        $indexResponse = $this->actingAs($this->admin)->get(route('admin.hasil.index'));
        $indexResponse->assertStatus(200);
    }

    public function test_admin_can_export_excel(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.hasil.excel', $this->periode));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function test_pegawai_can_view_own_hasil_report(): void
    {
        $service = new KalkulasiNilai360Service();
        $service->hitungNilaiAkhir($this->periode);

        $response = $this->actingAs($this->staff)->get(route('pegawai.hasil-saya'));

        $response->assertStatus(200);
        $response->assertViewHas('hasil');
    }
}
