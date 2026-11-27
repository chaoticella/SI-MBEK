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
       Schema::create('kambings', function (Blueprint $table) {
        $table->id();
        $table->string('kode_kambing')->unique();
        $table->foreignId('kandang_id')->constrained('kandangs')->restrictOnDelete();
        $table->string('ras');
        $table->enum('jenis_kelamin', ['jantan', 'betina']);
        $table->date('tanggal_lahir')->nullable();
        $table->enum('status', ['indukan', 'anakan', 'siap_jual']);
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kambings');
    }
};
