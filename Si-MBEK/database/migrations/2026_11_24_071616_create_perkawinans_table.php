<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('perkawinans', function (Blueprint $table) {
        $table->id();
        $table->date('tanggal_kawin');
        $table->foreignId('pejantan_id')->constrained('kambings')->restrictOnDelete();
        $table->foreignId('indukan_id')->constrained('kambings')->restrictOnDelete();
        $table->date('estimasi_melahirkan')->nullable();
        $table->unsignedInteger('jumlah_anak_lahir')->default(0);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perkawinans');
    }
};
