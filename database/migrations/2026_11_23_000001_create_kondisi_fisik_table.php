<?php

/*
 * MODUL 3 — Kesehatan & Pencatatan Penyakit Kambing (Nat)
 * Tabel kondisi_fisik: pencatatan kondisi fisik kambing secara berkala.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kondisi_fisik', function (Blueprint $table) {
            $table->id('id_kondisi');

            $table->foreignId('id_kambing')
                ->constrained('kambing', 'id_kambing')
                ->cascadeOnDelete();

            // Petugas yang mencatat. nullOnDelete supaya catatan tetap ada
            // walaupun akun petugasnya dihapus.
            $table->foreignId('id_user')->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->date('tanggal_cek');
            $table->decimal('berat_badan', 6, 2);            // kg
            $table->decimal('tinggi_badan', 5, 1)->nullable(); // cm (tambahan di luar ERD)
            $table->enum('nafsu_makan', ['baik', 'sedang', 'buruk']);
            $table->decimal('suhu_tubuh', 4, 1);              // °C
            $table->decimal('bcs', 2, 1)->nullable();          // Body Condition Score 1.0–5.0
            $table->unsignedTinyInteger('famacha')->nullable(); // skor FAMACHA 1–5
            $table->text('catatan')->nullable();

            $table->timestamps();

            // 1 kambing hanya dicek 1x per tanggal
            $table->unique(['id_kambing', 'tanggal_cek']);
            $table->index('tanggal_cek');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kondisi_fisik');
    }
};
