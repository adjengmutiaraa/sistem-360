<?php

namespace Tests\Feature;

use App\Models\PenugasanPenilaian;
use App\Models\PeriodePenilaian;
use App\Models\Pertanyaan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PegawaiPenilaianTest extends TestCase
{
    use RefreshDatabase;

    protected User $staff;
    protected User $kabid;
    protected PeriodePenilaian $periode;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        // Clear pre-seeded penugasan for clean test isolation
        PenugasanPenilaian::query()->delete();

        $this->staff = User::where('email', 'staff1@sistem360.go.id')->first();
        $this->kabid = User::where('email', 'kabid1@sistem360.go.id')->first();
        $this->periode = PeriodePenilaian::where('status', 'aktif')->first();
    }

    public function test_pegawai_can_view_penilaian_index(): void
    {
        $response = $this->actingAs($this->staff)->get(route('pegawai.penilaian.index'));

        $response->assertStatus(200);
        $response->assertViewHas('periodeAktif');
    }

    public function test_staff_can_select_3_peers(): void
    {
        $peers = User::where('id', '!=', $this->staff->id)
            ->whereHas('jabatan', fn ($q) => $q->where('level', 'staff'))
            ->take(3)
            ->pluck('id')
            ->toArray();

        $response = $this->actingAs($this->staff)->post(route('pegawai.penilaian.store-pilih-rekan'), [
            'rekan_ids' => $peers,
        ]);

        $response->assertRedirect(route('pegawai.penilaian.index'));
        $this->assertEquals(3, PenugasanPenilaian::where('penilai_id', $this->staff->id)->where('jenis_penilai', 'rekan')->count());
    }

    public function test_cannot_select_peer_who_already_has_3_evaluators(): void
    {
        $targetPeer = User::where('email', 'staff2@sistem360.go.id')->first();

        // Create 3 existing peer evaluators for targetPeer
        $otherStaffs = User::whereNotIn('id', [$this->staff->id, $targetPeer->id])
            ->whereHas('jabatan', fn ($q) => $q->where('level', 'staff'))
            ->take(3)
            ->get();

        foreach ($otherStaffs as $evaluator) {
            PenugasanPenilaian::firstOrCreate([
                'periode_penilaian_id' => $this->periode->id,
                'penilai_id' => $evaluator->id,
                'dinilai_id' => $targetPeer->id,
            ], [
                'jenis_penilai' => 'rekan',
                'status' => 'belum',
            ]);
        }

        // Try selecting targetPeer when he already has 3 evaluators
        $otherPeers = User::whereNotIn('id', [$this->staff->id, $targetPeer->id])
            ->whereHas('jabatan', fn ($q) => $q->where('level', 'staff'))
            ->take(2)
            ->pluck('id')
            ->toArray();

        $rekanIds = array_merge([$targetPeer->id], $otherPeers);

        $response = $this->actingAs($this->staff)->post(route('pegawai.penilaian.store-pilih-rekan'), [
            'rekan_ids' => $rekanIds,
        ]);

        $response->assertSessionHasErrors('rekan_ids');
    }

    public function test_pegawai_can_fill_evaluation_questionnaire(): void
    {
        $dinilai = $this->kabid;

        $tugas = PenugasanPenilaian::firstOrCreate([
            'periode_penilaian_id' => $this->periode->id,
            'penilai_id' => $this->staff->id,
            'dinilai_id' => $dinilai->id,
        ], [
            'jenis_penilai' => 'bawahan',
            'status' => 'belum',
        ]);

        $pertanyaans = Pertanyaan::all();
        $skorData = [];
        foreach ($pertanyaans as $p) {
            $skorData[$p->id] = 4;
        }

        $response = $this->actingAs($this->staff)->post(route('pegawai.penilaian.store', $dinilai), [
            'skor' => $skorData,
            'catatan' => 'Sangat komunikatif dan mengayomi bawahan.',
        ]);

        $response->assertRedirect(route('pegawai.penilaian.index'));
        $this->assertEquals('selesai', $tugas->fresh()->status);
        $this->assertDatabaseHas('penilaians', [
            'penilai_id' => $this->staff->id,
            'dinilai_id' => $dinilai->id,
        ]);
    }
}
