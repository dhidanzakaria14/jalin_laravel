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
    Schema::create('pembayaran', function (Blueprint $table) {
        $table->id('id_pembayaran');

        // Relasi ke tabel pesanan
        $table->foreignId('id_pesanan')->constrained('pesanan', 'id_pesanan')->onDelete('cascade');

        $table->dateTime('tgl_bayar');
        $table->decimal('jumlah_bayar', 15, 2);
        $table->string('bukti_bayar', 255); // Menyimpan nama file foto bukti transfer
        $table->enum('metode_bayar', ['Transfer Bank', 'E-Wallet', 'Tunai'])->default('Transfer Bank');
        $table->enum('jenis_pembayaran', ['DP', 'Pelunasan', 'Cicilan']);
        $table->enum('status_konfirmasi', ['Menunggu', 'Valid', 'Ditolak'])->default('Menunggu');

        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
