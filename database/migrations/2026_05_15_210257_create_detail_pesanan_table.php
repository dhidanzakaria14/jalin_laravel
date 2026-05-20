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
    Schema::create('detail_pesanan', function (Blueprint $table) {
        $table->id('id_detail'); // Primary Key

        // Relasi ke tabel pesanan (Satu pesanan bisa punya banyak detail/item)
        $table->foreignId('id_pesanan')->constrained('pesanan', 'id_pesanan')->onDelete('cascade');

        // Relasi ke tabel layanan (Layanan apa yang dipesan)
        $table->foreignId('id_layanan')->constrained('layanan', 'id_layanan')->onDelete('cascade');

        // Data Keuangan & Harga
        $table->decimal('harga_asli', 15, 2); // Harga normal layanan saat dipesan
        $table->decimal('diskon_nominal', 15, 2)->default(0); // Jika ada potongan harga
        $table->decimal('harga_final', 15, 2); // Harga setelah diskon (yang harus dibayar)

        // Catatan & Status Item
        $table->text('catatan_khusus')->nullable(); // Misal: "Warna dekorasi mau yang pastel"
        $table->decimal('nominal_dp_tagihan', 15, 2); // Jumlah DP yang harus dibayar untuk item ini
        $table->enum('status_item', ['Belum DP', 'Sudah DP', 'Lunas'])->default('Belum DP');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pesanan');
    }
};
