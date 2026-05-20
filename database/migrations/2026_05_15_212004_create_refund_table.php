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
        Schema::create('refund', function (Blueprint $table) {
            // 1. ID Refund sebagai Primary Key
            $table->id('id_refund');

            // 2. Foreign Key ke tabel pesanan
            $table->foreignId('id_pesanan')->constrained('pesanan', 'id_pesanan')->onDelete('cascade');

            // 3. Data utama refund (Urutan disesuaikan agar rapi)
            $table->decimal('jumlah_refund', 15, 2);
            $table->text('keterangan');
            $table->dateTime('tgl_refund');

            // 4. Data Rekening (Nama Bank, No Rekening, Nama Penerima)
            $table->string('nama_bank', 50);
            $table->string('no_rekening', 30);
            $table->string('nama_penerima', 100);

            // 5. Status dan Bukti Transfer
            $table->enum('status_refund', ['Proses', 'Selesai', 'Ditolak'])->default('Proses');
            $table->string('bukti_refund', 255)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refund');
    }
};
