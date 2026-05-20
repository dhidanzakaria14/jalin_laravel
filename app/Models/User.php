<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // 1. Tentukan tabel yang digunakan di database
    protected $table = 'users';

    // 2. Tentukan primary key yang digunakan
   protected $primaryKey = 'id';

    // 3. Matikan auto-increment jika memang tidak otomatis
    public $incrementing = true;

    // 4. Matikan timestamps jika tabelmu tidak punya kolom created_at/updated_at
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     * REVISI: Semua kolom database wajib didaftarkan di sini agar tidak NULL!
     */
    protected $fillable = [
        'nama_lengkap',
        'nama_toko',      // <-- Tambahkan ini
        'email',
        'password',
        'no_whatsapp',    // <-- Tambahkan ini (Wajib sama dengan nama kolom di phpMyAdmin)
        'alamat',         // <-- Tambahkan ini
        'role',
        'foto_profil',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        // Untuk Laravel versi terbaru, biarkan default atau sesuaikan jika diperlukan
        return [
            'email_verified_at' => 'datetime',
        ];
    }
}
