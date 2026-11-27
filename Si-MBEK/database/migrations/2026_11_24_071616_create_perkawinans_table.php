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
        Schema::create('perkawinan', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_kawin');
            $table->foreignId('pejantan_id')->constrained('kambing', 'id_kambing')->restrictOnDelete();
            $table->foreignId('indukan_id')->constrained('kambing', 'id_kambing')->restrictOnDelete();
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
        Schema::dropIfExists('perkawinan');
    }
};
