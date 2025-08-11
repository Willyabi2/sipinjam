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
        Schema::create('kendaraans', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_kendaraan'); // Contoh: "Mobil", "Motor"
            $table->string('merk_kendaraan');  // Contoh: "Toyota", "Honda"
            $table->string('plat_nomor')->unique(); // Plat nomor unik
            $table->string('warna_kendaraan');
            $table->string('created_by')->default('admin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kendaraans');
    }
};
