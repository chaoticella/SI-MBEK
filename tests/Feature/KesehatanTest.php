<?php

namespace Tests\Feature;

use App\Models\Kambing;
use App\Models\Kandang;
use App\Models\KondisiFisik;
use App\Models\Penyakit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KesehatanTest extends TestCase
{
    use RefreshDatabase;

    private Kambing $kambing;
    private Kandang $isolasi;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->actingAs(User::factory()->create());

        $biasa = Kandang::create(['blok_kandang' => 1, 'kapasitas_maksimal' => 10, 'kategori' => 'betina']);
        $this->isolasi = Kandang::create(['blok_kandang' => 9, 'kapasitas_maksimal' => 3, 'kategori' => 'isolasi']);
        $this->kambing = Kambing::create([
            'id_kandang' => $biasa->id_kandang, 'jenis_ras' => 'Etawa', 'jenis_kelamin' => 'betina',
            'tanggal_lahir' => '2024-01-01', 'status_ternak' => 'indukan', 'status_aktif' => true,
        ]);
    }

    /* ---------------- Kondisi fisik ---------------- */

    public function test_halaman_kondisi_fisik_tampil(): void
    {
        $this->get(route('kondisi-fisik.index'))->assertOk()->assertSee('Kondisi Fisik');
        $this->get(route('kondisi-fisik.create'))->assertOk()->assertSee('Berat Badan');
    }

    public function test_simpan_kondisi_fisik_dengan_koma_desimal(): void
    {
        $this->post(route('kondisi-fisik.store'), [
            'id_kambing' => $this->kambing->id_kambing, 'tanggal_cek' => today()->toDateString(),
            'berat_badan' => '32,5', 'nafsu_makan' => 'baik', 'suhu_tubuh' => '40,5', 'famacha' => 4,
        ])->assertRedirect(route('kondisi-fisik.index'))->assertSessionHas('success');

        $k = KondisiFisik::first();
        $this->assertEquals(32.5, (float) $k->berat_badan);
        $this->assertContains('Demam', $k->peringatan());
        $this->assertContains('Anemia (FAMACHA)', $k->peringatan());
    }

    public function test_kondisi_fisik_tidak_boleh_dobel_di_tanggal_sama(): void
    {
        $payload = ['id_kambing' => $this->kambing->id_kambing, 'tanggal_cek' => today()->toDateString(),
                    'berat_badan' => 30, 'nafsu_makan' => 'baik', 'suhu_tubuh' => 39];
        $this->post(route('kondisi-fisik.store'), $payload);
        $this->post(route('kondisi-fisik.store'), $payload)->assertSessionHasErrors('tanggal_cek');
    }

    public function test_update_show_dan_hapus_kondisi_fisik(): void
    {
        $k = KondisiFisik::create(['id_kambing' => $this->kambing->id_kambing, 'tanggal_cek' => today(),
            'berat_badan' => 30, 'nafsu_makan' => 'baik', 'suhu_tubuh' => 39]);

        $this->get(route('kondisi-fisik.show', $k))->assertOk();
        $this->get(route('kondisi-fisik.edit', $k))->assertOk();
        $this->put(route('kondisi-fisik.update', $k), [
            'id_kambing' => $this->kambing->id_kambing, 'tanggal_cek' => today()->toDateString(),
            'berat_badan' => 31, 'nafsu_makan' => 'sedang', 'suhu_tubuh' => 39.1,
        ])->assertRedirect();
        $this->assertEquals(31, (float) $k->fresh()->berat_badan);

        $this->delete(route('kondisi-fisik.destroy', $k))->assertRedirect();
        $this->assertDatabaseCount('kondisi_fisik', 0);
    }

    public function test_ajax_kondisi_terakhir(): void
    {
        KondisiFisik::create(['id_kambing' => $this->kambing->id_kambing, 'tanggal_cek' => today()->subWeek(),
            'berat_badan' => 28, 'nafsu_makan' => 'baik', 'suhu_tubuh' => 39]);

        $this->getJson(route('kondisi-fisik.terakhir', $this->kambing))
            ->assertOk()->assertJsonPath('terakhir.berat_badan', 28);
    }

    /* ---------------- Penyakit ---------------- */

    public function test_simpan_penyakit_dengan_banyak_obat(): void
    {
        $this->post(route('penyakit.store'), [
            'id_kambing' => $this->kambing->id_kambing, 'tanggal_sakit' => today()->subDays(2)->toDateString(),
            'diagnosa' => 'Scabies', 'gejala' => 'gatal, bulu rontok', 'status_akhir' => 'sakit',
            'obat' => [
                ['jenis' => 'obat', 'nama' => 'Ivermectin', 'dosis' => '1 ml', 'tanggal' => today()->toDateString()],
                ['jenis' => 'vitamin', 'nama' => 'Vit B', 'dosis' => '', 'tanggal' => ''],
                ['jenis' => 'obat', 'nama' => '', 'dosis' => '', 'tanggal' => ''], // baris kosong dibuang
            ],
        ])->assertRedirect(route('penyakit.index'))->assertSessionHas('warning'); // belum diisolasi

        $p = Penyakit::first();
        $this->assertCount(2, $p->jenis_obat_vaksin);
        $this->assertSame('sakit', $p->status);
    }

    public function test_status_sembuh_wajib_tanggal_dan_tidak_sebelum_tanggal_sakit(): void
    {
        $this->post(route('penyakit.store'), [
            'id_kambing' => $this->kambing->id_kambing, 'tanggal_sakit' => today()->toDateString(),
            'gejala' => 'lemas', 'status_akhir' => 'sembuh',
        ])->assertSessionHasErrors('tanggal_sembuh');

        $this->post(route('penyakit.store'), [
            'id_kambing' => $this->kambing->id_kambing, 'tanggal_sakit' => today()->toDateString(),
            'gejala' => 'lemas', 'status_akhir' => 'sembuh', 'tanggal_sembuh' => today()->subDay()->toDateString(),
        ])->assertSessionHasErrors('tanggal_sembuh');
    }

    public function test_kambing_mati_otomatis_nonaktif(): void
    {
        $this->post(route('penyakit.store'), [
            'id_kambing' => $this->kambing->id_kambing, 'tanggal_sakit' => today()->subDays(3)->toDateString(),
            'gejala' => 'lemas', 'status_akhir' => 'mati', 'tanggal_kematian' => today()->toDateString(),
        ]);
        $this->assertFalse($this->kambing->fresh()->status_aktif);
    }

    public function test_notifikasi_isolasi_dan_tandai_sembuh(): void
    {
        $this->kambing->update(['id_kandang' => $this->isolasi->id_kandang]);
        $p = Penyakit::create(['id_kambing' => $this->kambing->id_kambing, 'tanggal_sakit' => today()->subDay(),
            'gejala' => 'demam', 'diagnosa' => 'Pneumonia']);

        $this->get(route('penyakit.index'))->assertOk()
            ->assertSee('kambing sakit di kandang isolasi perlu dipantau')
            ->assertSee($this->kambing->kode);

        $this->get(route('penyakit.show', $p))->assertOk();
        $this->get(route('penyakit.edit', $p))->assertOk();

        $this->patch(route('penyakit.sembuh', $p))->assertRedirect();
        $this->assertSame('sembuh', $p->fresh()->status);

        $this->get(route('penyakit.index'))->assertDontSee('kambing sakit di kandang isolasi perlu dipantau');
    }

    public function test_filter_penyakit_berdasarkan_status(): void
    {
        Penyakit::create(['id_kambing' => $this->kambing->id_kambing, 'tanggal_sakit' => today(), 'gejala' => 'AKTIF-X', 'diagnosa' => 'Diare']);
        Penyakit::create(['id_kambing' => $this->kambing->id_kambing, 'tanggal_sakit' => today()->subDays(9),
            'tanggal_sembuh' => today(), 'gejala' => 'SEMBUH-Y']);

        $this->get(route('penyakit.index', ['status' => 'sembuh']))->assertSee('SEMBUH-Y')->assertDontSee('AKTIF-X');
    }
}
