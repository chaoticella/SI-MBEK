<?php

namespace Tests\Feature;

use App\Models\Kandang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class Modul1Test extends TestCase
{
    use RefreshDatabase;

    protected User $pengelola;
    protected User $pegawai;
    protected User $inactiveUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pengelola = User::create([
            'name' => 'Pengelola Utama',
            'email' => 'pengelola.test@simbek.com',
            'password' => Hash::make('password123'),
            'role' => 'pengelola',
            'status_aktif' => true,
        ]);

        $this->pegawai = User::create([
            'name' => 'Pegawai Lapangan',
            'email' => 'pegawai.test@simbek.com',
            'password' => Hash::make('password123'),
            'role' => 'pegawai',
            'status_aktif' => true,
        ]);

        $this->inactiveUser = User::create([
            'name' => 'User Nonaktif',
            'email' => 'nonaktif.test@simbek.com',
            'password' => Hash::make('password123'),
            'role' => 'pegawai',
            'status_aktif' => false,
        ]);
    }

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('SI-MBEK');
        $response->assertSee('Masuk ke Sistem');
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $response = $this->post('/login', [
            'email' => 'pengelola.test@simbek.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($this->pengelola);
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $response = $this->post('/login', [
            'email' => 'pengelola.test@simbek.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_inactive_user_cannot_login(): void
    {
        $response = $this->post('/login', [
            'email' => 'nonaktif.test@simbek.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_user_can_logout(): void
    {
        $this->actingAs($this->pengelola);
        $response = $this->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_pengelola_can_access_users_management(): void
    {
        $this->actingAs($this->pengelola);
        $response = $this->get('/users');

        $response->assertStatus(200);
        $response->assertSee('Manajemen Pengguna');
    }

    public function test_pegawai_cannot_access_users_management(): void
    {
        $this->actingAs($this->pegawai);
        $response = $this->get('/users');

        $response->assertStatus(403);
    }

    public function test_pengelola_can_create_user(): void
    {
        $this->actingAs($this->pengelola);

        $response = $this->post('/users', [
            'name' => 'Pegawai Baru',
            'email' => 'baru@simbek.com',
            'role' => 'pegawai',
            'password' => 'password123',
            'status_aktif' => '1',
        ]);

        $response->assertRedirect('/users');
        $this->assertDatabaseHas('users', [
            'email' => 'baru@simbek.com',
            'role' => 'pegawai',
        ]);
    }

    public function test_pengelola_can_toggle_user_status(): void
    {
        $this->actingAs($this->pengelola);

        $this->patch("/users/{$this->pegawai->id}/toggle-status");
        $this->assertDatabaseHas('users', [
            'id' => $this->pegawai->id,
            'status_aktif' => false,
        ]);
    }

    public function test_pengelola_cannot_deactivate_or_delete_own_account(): void
    {
        $this->actingAs($this->pengelola);

        $responseToggle = $this->patch("/users/{$this->pengelola->id}/toggle-status");
        $responseToggle->assertSessionHas('error');

        $responseDelete = $this->delete("/users/{$this->pengelola->id}");
        $responseDelete->assertSessionHas('error');
    }

    public function test_both_can_view_kandang_list(): void
    {
        Kandang::create([
            'blok_kandang' => 'Blok Uji 1',
            'kapasitas_maksimal' => 20,
            'kategori' => 'jantan',
            'status_aktif' => true,
        ]);

        // Pegawai can view
        $this->actingAs($this->pegawai);
        $responsePegawai = $this->get('/kandang');
        $responsePegawai->assertStatus(200);
        $responsePegawai->assertSee('Blok Uji 1');

        // Pengelola can view
        $this->actingAs($this->pengelola);
        $responsePengelola = $this->get('/kandang');
        $responsePengelola->assertStatus(200);
        $responsePengelola->assertSee('Blok Uji 1');
    }

    public function test_pengelola_can_create_kandang(): void
    {
        $this->actingAs($this->pengelola);

        $response = $this->post('/kandang', [
            'blok_kandang' => 'Blok 99',
            'kapasitas_maksimal' => 35,
            'kategori' => 'betina',
            'status_aktif' => '1',
            'keterangan' => 'Kandang pengujian otomatis',
        ]);

        $response->assertRedirect('/kandang');
        $this->assertDatabaseHas('kandangs', [
            'blok_kandang' => 'Blok 99',
            'kapasitas_maksimal' => 35,
            'kategori' => 'betina',
        ]);
    }

    public function test_kandang_form_validation_rejects_invalid_input(): void
    {
        $this->actingAs($this->pengelola);

        $response = $this->post('/kandang', [
            'blok_kandang' => '',
            'kapasitas_maksimal' => -5,
            'kategori' => 'invalid_category',
        ]);

        $response->assertSessionHasErrors(['blok_kandang', 'kapasitas_maksimal', 'kategori']);
    }

    public function test_pegawai_cannot_create_kandang(): void
    {
        $this->actingAs($this->pegawai);

        $response = $this->post('/kandang', [
            'blok_kandang' => 'Blok Ilegal',
            'kapasitas_maksimal' => 10,
            'kategori' => 'jantan',
        ]);

        $response->assertStatus(403);
    }

    public function test_pengelola_can_toggle_kandang_status(): void
    {
        $kandang = Kandang::create([
            'blok_kandang' => 'Blok Toggle',
            'kapasitas_maksimal' => 15,
            'kategori' => 'isolasi',
            'status_aktif' => true,
        ]);

        $this->actingAs($this->pengelola);
        $this->patch("/kandang/{$kandang->id}/toggle-status");

        $this->assertDatabaseHas('kandangs', [
            'id' => $kandang->id,
            'status_aktif' => false,
        ]);
    }

    public function test_pengelola_can_update_and_delete_kandang(): void
    {
        $kandang = Kandang::create([
            'blok_kandang' => 'Blok Update',
            'kapasitas_maksimal' => 10,
            'kategori' => 'jantan',
            'status_aktif' => true,
        ]);

        $this->actingAs($this->pengelola);

        $this->put("/kandang/{$kandang->id}", [
            'blok_kandang' => 'Blok Diperbarui',
            'kapasitas_maksimal' => 25,
            'kategori' => 'betina',
            'status_aktif' => '1',
            'keterangan' => 'Sudah diperbarui',
        ])->assertRedirect('/kandang');

        $this->assertDatabaseHas('kandangs', [
            'id' => $kandang->id,
            'blok_kandang' => 'Blok Diperbarui',
            'kapasitas_maksimal' => 25,
            'kategori' => 'betina',
        ]);

        $this->delete("/kandang/{$kandang->id}")->assertRedirect('/kandang');
        $this->assertDatabaseMissing('kandangs', ['id' => $kandang->id]);
    }
}
