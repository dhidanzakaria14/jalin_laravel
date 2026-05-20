<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id('id_pesanan'); // Primary Key sesuai ERD

            // Relasi ke User (Pengantin yang memesan)
            $table->foreignId('id_pengantin')->constrained('users', 'id')->onDelete('cascade');

            $table->dateTime('tgl_pesan');
            $table->enum('status', ['Pending', 'Proses', 'Selesai', 'Dibatalkan'])->default('Pending');
            $table->decimal('total_bayar', 15, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
