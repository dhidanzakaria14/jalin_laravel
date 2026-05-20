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
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // Ini otomatis menjadi id_kategori (primary key)
            $table->string('nama_kategori', 50); // Sesuai tipe varchar(50) di ERD kamu
            $table->timestamps(); // Opsional: untuk mencatat kapan kategori dibuat
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
