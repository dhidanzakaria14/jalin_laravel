<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status_verifikasi', ['menunggu', 'terverifikasi', 'ditolak'])
                ->default('menunggu')
                ->after('foto_profil');
        });

        Schema::table('layanan', function (Blueprint $table) {
            $table->enum('status_verifikasi', ['menunggu', 'terverifikasi', 'ditolak'])
                ->default('menunggu')
                ->after('minimal_dp_persen');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status_verifikasi');
        });

        Schema::table('layanan', function (Blueprint $table) {
            $table->dropColumn('status_verifikasi');
        });
    }
};
