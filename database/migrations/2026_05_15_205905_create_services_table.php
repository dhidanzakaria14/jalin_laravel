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
        // Nama tabel diganti jadi 'layanan'
        Schema::create('layanan', function (Blueprint $table) {
            // Nama primary key disesuaikan jadi id_layanan
            $table->id('id_layanan');

            // Relasi ke tabel users (id_vendor)
            $table->foreignId('id_vendor')->constrained('users', 'id')->onDelete('cascade');

            // Relasi ke tabel categories (id_kategori)
            $table->foreignId('id_kategori')->constrained('categories', 'id')->onDelete('cascade');

            $table->string('nama_layanan', 100);
            $table->decimal('harga', 15, 2);
            $table->text('deskripsi');
            $table->string('gambar', 255)->nullable();
            $table->integer('minimal_dp_persen');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Ganti juga di sini agar saat rollback tabel 'layanan' yang dihapus
        Schema::dropIfExists('layanan');
    }
};
