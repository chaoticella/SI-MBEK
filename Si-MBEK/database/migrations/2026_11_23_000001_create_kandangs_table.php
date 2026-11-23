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
        Schema::create('kandangs', function (Blueprint $table) {
            $table->id();
            $table->string('blok_kandang');
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
        Schema::dropIfExists('kandangs');
    }
};
