<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    protected $table = 'layanan';
    protected $primaryKey = 'id_layanan'; // PENTING: Sesuai di database
    public $timestamps = false;
    protected $fillable = [
        'id_vendor', 'id_kategori', 'nama_layanan',
        'harga', 'deskripsi', 'gambar', 'minimal_dp_persen'
    ];
}
