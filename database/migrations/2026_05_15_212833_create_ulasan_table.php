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
        Schema::create('ulasan', function (Blueprint $table) {
            $table->id('id_ulasan');

            // Relasi: Siapa yang mengulas dan layanan apa yang diulas
            $table->foreignId('id_pengantin')->constrained('users', 'id')->onDelete('cascade');
            $table->foreignId('id_layanan')->constrained('layanan', 'id_layanan')->onDelete('cascade');
            // Opsional: Hubungkan ke id_pesanan agar 1 pesanan cuma bisa kasih 1 ulasan
            $table->foreignId('id_pesanan')->constrained('pesanan', 'id_pesanan')->onDelete('cascade');

            $table->integer('rating'); // Biasanya 1 sampai 5
            $table->text('komentar');
            $table->string('foto_ulasan', 255)->nullable(); // Biar pengantin bisa pamer foto hasil layanan
            $table->dateTime('tgl_ulasan');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ulasan');
    }
};
