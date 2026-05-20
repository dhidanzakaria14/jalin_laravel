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
        Schema::create('pesan', function (Blueprint $table) {
            $table->id('id_pesan');
            $table->unsignedBigInteger('id_obrolan'); // Menghubungkan ke primary key tabel obrolan
            $table->unsignedBigInteger('id_pengirim'); // Menyimpan ID user yang mengetik pesan (bisa vendor / customer)
            $table->text('isi_pesan');
            $table->boolean('is_read')->default(false); // Status apakah pesan sudah dibaca atau belum
            $table->timestamps(); // Membuat kolom created_at & updated_at secara otomatis

            // Opsional: Aktifkan foreign key jika ingin relasi database terkunci aman
            // $table->foreign('id_obrolan')->references('id_obrolan')->on('obrolan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesan');
    }
};
