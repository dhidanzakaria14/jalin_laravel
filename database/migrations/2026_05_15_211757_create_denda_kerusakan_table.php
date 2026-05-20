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
        Schema::create('denda_kerusakan', function (Blueprint $table) {
            // Sesuai ERD: id_denda sebagai Primary Key
            $table->id('id_denda');

            // Relasi ke tabel pesanan
            $table->foreignId('id_pesanan')->constrained('pesanan', 'id_pesanan')->onDelete('cascade');

            // Kolom rincian sesuai gambar
            $table->string('nama_barang', 100);
            $table->text('kerusakan'); // Menampung deskripsi kerusakan
            $table->decimal('biaya_denda', 15, 2);
            $table->string('foto_bukti', 255)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('denda_kerusakan');
    }
};
