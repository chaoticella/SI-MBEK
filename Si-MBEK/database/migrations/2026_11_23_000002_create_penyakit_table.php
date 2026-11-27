<?php

/*
 * MODUL 3 — Kesehatan & Pencatatan Penyakit Kambing (Nat)
 * Tabel penyakit (pencatatan penyakit): riwayat sakit, gejala, obat/vaksin,
 * dan hasil akhir (sembuh / mati).
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penyakit', function (Blueprint $table) {
            $table->id('id_penyakit');

            $table->foreignId('id_kambing')
                ->constrained('kambing', 'id_kambing')
                ->cascadeOnDelete();

            $table->foreignId('id_user')->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->date('tanggal_sakit');
            $table->string('diagnosa', 100)->nullable(); // nama penyakit (tambahan di luar ERD)
            $table->text('gejala');

            // Disimpan sebagai JSON supaya 1 kasus bisa punya banyak obat/vaksin
            // (diinput dinamis pakai jQuery). Contoh isi:
            // [{"jenis":"obat","nama":"Ivermectin","dosis":"1 ml/10 kg","tanggal":"2026-11-20"}]
            $table->json('jenis_obat_vaksin')->nullable();

            $table->date('tanggal_sembuh')->nullable();
            $table->date('tanggal_kematian')->nullable();
            $table->text('catatan')->nullable();

            $table->timestamps();

            $table->index('tanggal_sakit');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penyakit');
    }
};
