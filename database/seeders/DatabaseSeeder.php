<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Nonaktifkan foreign key checks sementara untuk menghindari error saat truncate/insert
        Schema::disableForeignKeyConstraints();

        // Kosongkan tabel sebelum diisi
        DB::table('users')->truncate();
        DB::table('categories')->truncate();
        DB::table('layanan')->truncate();
        DB::table('obrolan')->truncate();
        DB::table('pesan')->truncate();

        // 1. Seeder Table: users
        DB::table('users')->insert([
            [
                'id' => 3,
                'nama_lengkap' => 'Dhidan Fachriza',
                'nama_toko' => 'maju',
                'email' => 'dhidanzakaria14@gmail.com',
                'password' => '$2y$12$uUFr7H6J3M6cvD2H0.8tgOuRCeomYuTXOA/7CELN7RTqZ5AmwwSou',
                'no_whatsapp' => '085808330777',
                'alamat' => 'Perum Kedunturi Permai 2 blok V-10',
                'deskripsi_toko' => null,
                'role' => 'Vendor',
                'foto_profil' => 'profil_3_1779073404.jfif',
                'remember_token' => null,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 4,
                'nama_lengkap' => 'Dhidan Fachriza zakaria',
                'nama_toko' => null,
                'email' => 'dhidanzakaria@gmail.com',
                'password' => '$2y$12$0QFw9WN9bMICiNRUb51Mge64BH1VomJQ1ZMVxQZntXSsL0fQzBA5y',
                'no_whatsapp' => '081',
                'alamat' => null,
                'deskripsi_toko' => null,
                'role' => 'Pengantin',
                'foto_profil' => '1779263657_logo_barca.jfif',
                'remember_token' => null,
                'created_at' => null,
                'updated_at' => '2026-05-20 00:54:17',
            ],
            [
                'id' => 6,
                'nama_lengkap' => 'marsela marsela',
                'nama_toko' => 'joss',
                'email' => 'marsela@gmail.com',
                'password' => '$2y$12$xgCSCyz1ndZ/jkNDD1tIXOJToNKeUCUcPynQAX7C8ASS6oYCeRsRK',
                'no_whatsapp' => '081',
                'alamat' => 'ppppp',
                'deskripsi_toko' => null,
                'role' => 'Vendor',
                'foto_profil' => null,
                'remember_token' => null,
                'created_at' => null,
                'updated_at' => null,
            ],
        ]);

        // 2. Seeder Table: categories
        DB::table('categories')->insert([
            [
                'id' => 1,
                'nama_kategori' => 'catering',
                'created_at' => null,
                'updated_at' => null
            ],
            [
                'id' => 2,
                'nama_kategori' => 'venuee',
                'created_at' => null,
                'updated_at' => null
            ],
        ]);

        // 3. Seeder Table: layanan
        DB::table('layanan')->insert([
            [
                'id_layanan' => 1,
                'id_vendor' => 3,
                'id_kategori' => 1,
                'nama_layanan' => 'uenak polllll',
                'harga' => 5000.00,
                'deskripsi' => 'pkomm',
                'gambar' => 'layanan_1779070535_775.jfif',
                'minimal_dp_persen' => 20,
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id_layanan' => 3,
                'id_vendor' => 3,
                'id_kategori' => 2,
                'nama_layanan' => 'Catering joss',
                'harga' => 6000000.00,
                'deskripsi' => 'poll',
                'gambar' => 'layanan_1779072562_745.jpg',
                'minimal_dp_persen' => 50,
                'created_at' => null,
                'updated_at' => null,
            ],
        ]);

        // 4. Seeder Table: obrolan
        DB::table('obrolan')->insert([
            [
                'id_obrolan' => 1,
                'id_customer' => 4,
                'id_vendor' => 3,
                'created_at' => '2026-05-18 19:57:18',
                'updated_at' => '2026-05-20 00:44:50',
            ],
        ]);

        // 5. Seeder Table: pesan
        DB::table('pesan')->insert([
            [
                'id_pesan' => 1,
                'id_obrolan' => 1,
                'id_pengirim' => 4,
                'isi_pesan' => 'test',
                'is_read' => 1,
                'created_at' => '2026-05-18 19:57:25',
                'updated_at' => '2026-05-18 19:57:25',
            ],
            [
                'id_pesan' => 2,
                'id_obrolan' => 1,
                'id_pengirim' => 4,
                'isi_pesan' => 'halloo kak',
                'is_read' => 0,
                'created_at' => '2026-05-20 00:44:50',
                'updated_at' => '2026-05-20 00:44:50',
            ],
        ]);

        // Aktifkan kembali foreign key checks
        Schema::enableForeignKeyConstraints();
    }
}
