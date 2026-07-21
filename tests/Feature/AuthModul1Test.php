<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthModul1Test extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_admin_can_login_using_email_and_redirects_to_admin_dashboard(): void
    {
        $response = $this->post('/login', [
            'login' => 'admin@sistem360.go.id',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard', absolute: false));
        $this->assertAuthenticatedAs(User::where('email', 'admin@sistem360.go.id')->first());
    }

    public function test_admin_can_login_using_nip_and_redirects_to_admin_dashboard(): void
    {
        $response = $this->post('/login', [
            'login' => '199001012020011001',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard', absolute: false));
        $this->assertAuthenticatedAs(User::where('email', 'admin@sistem360.go.id')->first());
    }

    public function test_pegawai_can_login_using_nip_and_redirects_to_pegawai_dashboard(): void
    {
        $response = $this->post('/login', [
            'login' => '197001011995031001', // Ketua Umum NIP
            'password' => 'password',
        ]);

        $response->assertRedirect(route('pegawai.dashboard', absolute: false));
        $this->assertAuthenticatedAs(User::where('nip', '197001011995031001')->first());
    }

    public function test_pegawai_cannot_access_admin_dashboard(): void
    {
        $pegawai = User::where('role', 'pegawai')->first();

        $response = $this->actingAs($pegawai)->get('/admin/dashboard');
        $response->assertRedirect(route('pegawai.dashboard'));
    }

    public function test_admin_cannot_access_pegawai_dashboard(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/pegawai/dashboard');
        $response->assertRedirect(route('admin.dashboard'));
    }
}
