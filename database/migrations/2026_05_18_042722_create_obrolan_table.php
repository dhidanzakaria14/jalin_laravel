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
        Schema::create('obrolan', function (Blueprint $table) {
            $table->id('id_obrolan');
            $table->unsignedBigInteger('id_customer'); // Menghubungkan ke ID user ber-role Customer
            $table->unsignedBigInteger('id_vendor');   // Menghubungkan ke ID user ber-role Vendor
            $table->timestamps(); // Otomatis membuat kolom created_at dan updated_at

            // Opsional: Aktifkan foreign key jika relasi ke tabel users ingin terkunci ketat di database
            // $table->foreign('id_customer')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('id_vendor')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obrolan');
    }
};
