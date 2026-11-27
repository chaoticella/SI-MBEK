<?php

namespace Database\Seeders;

use App\Models\Kambing;
use App\Models\Kandang;
use App\Models\KondisiFisik;
use App\Models\Penyakit;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Data contoh Modul 3 (Kesehatan).
 * Jalankan: php artisan db:seed --class=KesehatanSeeder
 */
class KesehatanSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create(['name' => 'Pegawai Kandang', 'email' => 'pegawai@simbek.test']);

        // Kandang & kambing hanya dibuat kalau modul teman belum mengisi datanya
        if (Kandang::count() === 0) {
            Kandang::insert([
                ['blok_kandang' => 1, 'kapasitas_maksimal' => 20, 'kategori' => 'jantan',  'created_at' => now(), 'updated_at' => now()],
                ['blok_kandang' => 2, 'kapasitas_maksimal' => 25, 'kategori' => 'betina',  'created_at' => now(), 'updated_at' => now()],
                ['blok_kandang' => 3, 'kapasitas_maksimal' => 5,  'kategori' => 'isolasi', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
        $kandang = Kandang::all()->keyBy('kategori');

        if (Kambing::count() === 0) {
            $ras = ['Etawa', 'Boer', 'Kacang', 'Saanen', 'Jawarandu'];
            foreach (range(1, 12) as $i) {
                $jk = $i % 3 === 0 ? 'jantan' : 'betina';
                Kambing::create([
                    'id_kandang'    => $kandang[$jk]->id_kandang,
                    'jenis_ras'     => $ras[$i % count($ras)],
                    'jenis_kelamin' => $jk,
                    'tanggal_lahir' => now()->subMonths(6 + $i * 2),
                    'status_ternak' => $i % 4 === 0 ? 'anakan' : 'indukan',
                    'status_aktif'  => true,
                ]);
            }
            // 2 kambing dipindah ke kandang isolasi
            Kambing::whereIn('id_kambing', [2, 5])->update(['id_kandang' => $kandang['isolasi']->id_kandang]);
        }

        $kambing = Kambing::orderBy('id_kambing')->get();

        // Kondisi fisik mingguan, 6 minggu terakhir
        foreach ($kambing as $k) {
            $berat = 20 + ($k->id_kambing * 1.7) % 18;
            foreach (range(5, 0) as $minggu) {
                $sakit = in_array($k->id_kambing, [2, 5]) && $minggu <= 1;
                KondisiFisik::firstOrCreate(
                    ['id_kambing' => $k->id_kambing, 'tanggal_cek' => now()->subWeeks($minggu)->toDateString()],
                    [
                        'id_user'      => $user->id,
                        'berat_badan'  => round($berat += ($sakit ? -1.2 : 0.6), 1),
                        'tinggi_badan' => 55 + $k->id_kambing % 15,
                        'nafsu_makan'  => $sakit ? 'buruk' : 'baik',
                        'suhu_tubuh'   => $sakit ? 40.4 : 39.0 + ($k->id_kambing % 5) / 10,
                        'bcs'          => $sakit ? 2.0 : 3.0,
                        'famacha'      => $sakit && $k->id_kambing === 5 ? 4 : 2,
                    ]
                );
            }
        }

        if (Penyakit::count() === 0) {
            $data = [
                [2, 6, 'Pneumonia', 'Batuk, pilek, demam, nafas cepat', [['obat', 'Oxytetracycline LA', '1 ml/10 kg', 5], ['vitamin', 'Vitamin B Kompleks', '2 ml', 5]], null, null],
                [5, 4, 'Cacingan', 'Lemas, kurus, selaput mata pucat', [['obat', 'Albendazole', '10 mg/kg', 3]], null, null],
                [7, 3, 'Diare', 'Diare, tidak mau makan', [], null, null], // sakit tapi belum diisolasi
                [3, 30, 'Scabies (Kudis)', 'Gatal, kerontokan bulu di leher', [['obat', 'Ivermectin', '1 ml/50 kg', 29]], 12, null],
                [9, 40, 'Kembung (Bloat)', 'Perut kiri membesar, gelisah', [['obat', 'Minyak nabati', '100 ml', 40]], 37, null],
            ];
            foreach ($data as [$id, $hariLalu, $diag, $gejala, $obat, $sembuh, $mati]) {
                Penyakit::create([
                    'id_kambing'        => $id,
                    'id_user'           => $user->id,
                    'tanggal_sakit'     => now()->subDays($hariLalu),
                    'diagnosa'          => $diag,
                    'gejala'            => $gejala,
                    'jenis_obat_vaksin' => collect($obat)->map(fn ($o) => [
                        'jenis' => $o[0], 'nama' => $o[1], 'dosis' => $o[2],
                        'tanggal' => now()->subDays($o[3])->toDateString(),
                    ])->all() ?: null,
                    'tanggal_sembuh'    => $sembuh ? now()->subDays($sembuh) : null,
                    'tanggal_kematian'  => $mati ? now()->subDays($mati) : null,
                ]);
            }
        }
    }
}
