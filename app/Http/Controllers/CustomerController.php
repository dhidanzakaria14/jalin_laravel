<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Menampilkan Dashboard Customer + Katalog Belanja Layanan
     */
    public function index()
    {
        $user = Auth::user();
        $id_user = $user->id;

        // 1. Hitung total booking dari data pesanan customer
        $countBooking = DB::table('pesanan')
            ->where('id_pengantin', $id_user)
            ->count();

        // 2. 🛒 AMBIL DATA LAYANAN VENDOR UNTUK KATALOG ALA SHOPEE
        $katalogLayanan = DB::table('layanan as l')
            ->join('users as u', 'l.id_vendor', '=', 'u.id')
            ->select('l.*', 'u.nama_toko', 'u.foto_profil as foto_vendor')
            ->get();

        // 3. Ambil maksimal 5 pesanan terbaru untuk tabel history
        $queryOrders = DB::table('pesanan as p')
            ->join('detail_pesanan as dp', 'p.id_pesanan', '=', 'dp.id_pesanan')
            ->join('layanan as l', 'dp.id_layanan', '=', 'l.id_layanan')
            ->join('users as u', 'l.id_vendor', '=', 'u.id')
            ->where('p.id_pengantin', $id_user)
            ->select('p.id_pesanan', 'p.tgl_pesan', 'p.status', 'l.nama_layanan', 'u.nama_lengkap as nama_vendor')
            ->orderBy('p.tgl_pesan', 'desc')
            ->limit(5)
            ->get();

        // 4. Ambil data riwayat pembayaran customer
        $queryPayment = DB::table('pembayaran as py')
            ->join('pesanan as p', 'py.id_pesanan', '=', 'p.id_pesanan')
            ->join('detail_pesanan as dp', 'p.id_pesanan', '=', 'dp.id_pesanan')
            ->join('layanan as l', 'dp.id_layanan', '=', 'l.id_layanan')
            ->where('p.id_pengantin', $id_user)
            ->select('py.*', 'l.nama_layanan')
            ->orderBy('py.tgl_bayar', 'desc')
            ->get();

        // 🎯 MENGARAH KE OPSI B: resources/views/dashboard/customer.blade.php
        return view('dashboard.customer', compact('user', 'countBooking', 'queryOrders', 'queryPayment', 'katalogLayanan'));
    }

    /**
     * 🛒 MENAMPILKAN HALAMAN KERANJANG BELANJA CUSTOMER
     */
    public function showKeranjang()
    {
        $user = Auth::user();

        // Contoh penarikan data item keranjang milik user aktif (sesuaikan nama tabelmu jika berbeda)
        $itemsKeranjang = DB::table('keranjang as k')
            ->join('layanan as l', 'k.id_layanan', '=', 'l.id_layanan')
            ->join('users as u', 'l.id_vendor', '=', 'u.id')
            ->where('k.id_user', $user->id)
            ->select('k.id_keranjang', 'l.nama_layanan', 'l.harga', 'l.foto_layanan', 'u.nama_toko')
            ->get();

        return view('dashboard.customer_keranjang', compact('user', 'itemsKeranjang'));
    }

    /**
     * ➕ PROSES MENAMBAHKAN ITEM KATALOG KE DALAM KERANJANG
     */
    public function tambahKeranjang(Request $request)
    {
        $request->validate([
            'id_layanan' => 'required'
        ]);

        // Masukkan data ke dalam tabel pembantu keranjang
        DB::table('keranjang')->insert([
            'id_user' => Auth::id(),
            'id_layanan' => $request->id_layanan,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Hore! Berhasil masuk keranjang belanjaanmu 🛒');
    }

    /**
     * 📦 MENAMPILKAN HALAMAN UTAH LIST "PESANAN SAYA" CUSTOMER
     */
    public function showPesanan()
    {
        $user = Auth::user();

        // Tarik semua daftar status pesanan lengkap milik customer terkait
        $semuaPesanan = DB::table('pesanan as p')
            ->join('detail_pesanan as dp', 'p.id_pesanan', '=', 'dp.id_pesanan')
            ->join('layanan as l', 'dp.id_layanan', '=', 'l.id_layanan')
            ->join('users as u', 'l.id_vendor', '=', 'u.id')
            ->where('p.id_pengantin', $user->id)
            ->select('p.*', 'l.nama_layanan', 'l.harga', 'u.nama_toko')
            ->orderBy('p.tgl_pesan', 'desc')
            ->get();

        return view('dashboard.customer_pesanan', compact('user', 'semuaPesanan'));
    }
}
