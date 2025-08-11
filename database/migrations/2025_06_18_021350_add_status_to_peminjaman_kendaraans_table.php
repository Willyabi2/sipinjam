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
    Schema::table('peminjaman_kendaraans', function (Blueprint $table) {
        // Hapus constraint lama jika ada
        $table->dropForeign(['pegawai_id']);

        if (!Schema::hasColumn('peminjaman_kendaraans', 'status')) {
            $table->string('status')
                    ->default('pending')
                    ->comment('pending: menunggu, approved: disetujui, rejected: ditolak');
        }

        if (!Schema::hasColumn('peminjaman_kendaraans', 'alasan_penolakan')) {
            $table->text('alasan_penolakan')
                  ->nullable()
                  ->after('status')
                  ->comment('Alasan penolakan jika status rejected');
        }

        
        // Tambahkan constraint baru
        $table->foreign('pegawai_id')
              ->references('id')
              ->on('employees')
              ->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('peminjaman_kendaraans', function (Blueprint $table) {
        $table->dropForeign(['pegawai_id']);

         // Hanya drop column jika ada
         if (Schema::hasColumn('peminjaman_kendaraans', 'status')) {
            $table->dropColumn('status');
        }
        
        if (Schema::hasColumn('peminjaman_kendaraans', 'alasan_penolakan')) {
            $table->dropColumn('alasan_penolakan');
        }
    });
}
};
