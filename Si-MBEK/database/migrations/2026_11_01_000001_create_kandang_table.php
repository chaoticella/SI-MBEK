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
        Schema::create('kandang', function (Blueprint $table) {
            $table->id('id_kandang');
            $table->string('blok_kandang', 50);
            $table->integer('kapasitas_maksimal');
            $table->enum('kategori', ['jantan', 'betina', 'isolasi']);
            $table->boolean('status_aktif')->default(true);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kandang');
    }
};
