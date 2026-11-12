<?php

namespace Tests\Feature;

use App\Models\Kelas;
use App\Models\Peserta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PendaftaranTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }
    public function test_login_page_renders_properly(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Sistem Pendaftaran Pelatihan');
        $response->assertSee('admin123');
    }

    public function test_registration_page_renders_properly(): void
    {
        $response = $this->get('/daftar');
        $response->assertStatus(200);
        $response->assertSee('Pendaftaran');
        $response->assertSee('NIDN / NUPTK');
    }

    public function test_dosen_lookup_api(): void
    {
        // 0001018001 is seeded Dr. Ahmad Fauzi
        $response = $this->get('/api/dosen/lookup?nidn=0001018001');
        $response->assertStatus(200);
        $response->assertJson([
            'found' => true,
            'nama' => 'Dr. Ahmad Fauzi, M.Kom.',
        ]);

        $notFound = $this->get('/api/dosen/lookup?nidn=9999999999');
        $notFound->assertStatus(200);
        $notFound->assertJson(['found' => false]);
    }

    public function test_admin_can_login_and_access_dashboard(): void
    {
        $response = $this->post('/login', [
            'username' => 'admin',
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/admin/dashboard');

        $admin = User::where('username', 'admin')->first();
        $this->actingAs($admin)
            ->get('/admin/dashboard')
            ->assertStatus(200)
            ->assertSee('Ringkasan pendaftaran pelatihan');
    }

    public function test_verified_peserta_can_login(): void
    {
        $response = $this->post('/login', [
            'username' => 'siti@unsri.ac.id',
            'password' => 'peserta123',
        ]);

        $response->assertRedirect('/peserta/dashboard');
    }

    public function test_pending_peserta_cannot_login(): void
    {
        $response = $this->from('/login')->post('/login', [
            'username' => 'budi@unsri.ac.id',
            'password' => 'peserta123',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('error');
    }

    public function test_rejected_peserta_cannot_login(): void
    {
        $response = $this->from('/login')->post('/login', [
            'username' => 'rina@univx.ac.id',
            'password' => 'peserta123',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('error');
    }

    public function test_pengajar_can_login_and_view_jadwal(): void
    {
        $response = $this->post('/login', [
            'username' => 'ahmad@unsri.ac.id',
            'password' => 'pengajar123',
        ]);

        $response->assertRedirect('/pengajar/jadwal');

        $pengajar = User::where('username', 'ahmad@unsri.ac.id')->first();
        $this->actingAs($pengajar)
            ->get('/pengajar/jadwal')
            ->assertStatus(200)
            ->assertSee('Jadwal Mengajar');
    }
}
