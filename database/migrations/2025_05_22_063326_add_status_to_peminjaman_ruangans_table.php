<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('peminjaman_ruangans', function (Blueprint $table) {
            // Hapus constraint lama jika ada
            $table->dropForeign(['pegawai_id']);
            
            // Tambahkan kolom status dengan opsi lengkap
            if (!Schema::hasColumn('peminjaman_ruangans', 'status')) {
                $table->string('status')
                      ->default('pending')
                      ->comment('pending: menunggu, approved: disetujui, rejected: ditolak');
            }
            
            // Tambahkan kolom alasan_penolakan dengan properti lengkap
            if (!Schema::hasColumn('peminjaman_ruangans', 'alasan_penolakan')) {
                $table->text('alasan_penolakan')
                      ->nullable()
                      ->after('status')
                      ->comment('Alasan penolakan jika status rejected');
            }
            
            // Tambahkan foreign key constraint baru
            $table->foreign('pegawai_id')
                  ->references('id')
                  ->on('employees')
                  ->onDelete('cascade');
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('peminjaman_ruangans', function (Blueprint $table) {
            $table->dropForeign(['pegawai_id']);
            
            // Hanya drop column jika ada
            if (Schema::hasColumn('peminjaman_ruangans', 'status')) {
                $table->dropColumn('status');
            }
            
            if (Schema::hasColumn('peminjaman_ruangans', 'alasan_penolakan')) {
                $table->dropColumn('alasan_penolakan');
            }
        });
    }
};