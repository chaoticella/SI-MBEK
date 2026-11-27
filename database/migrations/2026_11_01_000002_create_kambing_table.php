<?php

/*
 * ⚠️ PLACEHOLDER — tabel milik modul Kambing (teman satu kelompok).
 * Dibuat di sini hanya supaya Modul 3 (Kesehatan) bisa di-migrate & dites.
 * Kalau migration kambing versi temanmu sudah masuk, HAPUS file ini.
 * Struktur mengikuti ERD di proposal.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('kambing')) {
            return;
        }

        Schema::create('kambing', function (Blueprint $table) {
            $table->id('id_kambing');
            $table->foreignId('id_kandang')->nullable()
                ->constrained('kandang', 'id_kandang')->nullOnDelete();
            $table->string('jenis_ras', 50);
            $table->enum('jenis_kelamin', ['jantan', 'betina']);
            $table->date('tanggal_lahir')->nullable();
            $table->enum('status_ternak', ['indukan', 'anakan']);
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kambing');
    }
};
