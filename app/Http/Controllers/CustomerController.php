<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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

        return view('dashboard.customer', compact('user', 'countBooking', 'queryOrders', 'queryPayment', 'katalogLayanan'));
    }

    /**
     * 🛒 MENAMPILKAN DATA ITEM KERANJANG BELANJA
     */
    public function showKeranjang()
    {
        $user = Auth::user();

        $itemsKeranjang = DB::table('keranjang as k')
            ->join('layanan as l', 'k.id_layanan', '=', 'l.id_layanan')
            ->join('users as u', 'l.id_vendor', '=', 'u.id')
            ->where('k.id_user', $user->id)
            ->select('k.id_keranjang', 'l.nama_layanan', 'l.harga', 'l.gambar', 'u.nama_toko')
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

        DB::table('keranjang')->insert([
            'id_user' => Auth::id(),
            'id_layanan' => $request->id_layanan,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Hore! Berhasil masuk keranjang belanjaanmu 🛒✨');
    }

    /**
     * 📦 MENAMPILKAN HALAMAN LIST "PESANAN SAYA" CUSTOMER
     */
    public function showPesanan()
    {
        $user = Auth::user();

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

    /**
     * 💾 PROSES UPDATE PROFILE VERSI AMAN ANTI-EROR DATABASE
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'no_telp'      => 'nullable|string|max:15',
            'alamat'       => 'nullable|string',
            'password'     => 'nullable|string|min:6|confirmed',
            'foto_profil'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $dataUpdate = [
            'nama_lengkap' => $request->nama_lengkap,
            'updated_at'   => now()
        ];

        // Jalankan baris di bawah ini jika kamu sudah menambahkan kolom no_telp & alamat di tabel users phpMyAdmin
        // $dataUpdate['no_telp'] = $request->no_telp;
        // $dataUpdate['alamat'] = $request->alamat;

        if ($request->filled('password')) {
            $dataUpdate['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('foto_profil')) {
            $file = $request->file('foto_profil');
            $namaFoto = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $namaFoto);
            $dataUpdate['foto_profil'] = $namaFoto;
        }

        DB::table('users')->where('id', $user->id)->update($dataUpdate);

        return redirect()->route('customer.dashboard')->with('success', 'Data profil pernikahanmu berhasil diperbarui! 🌸✨');
    }

    /**
     * 🤖 META AI SIMULATION ENGINE: CONTEXTUAL MATCHING & RANDOM DATABASE RESPONSE
     */
    public function tanyaAi(Request $request)
    {
        $pesanUser = strtolower($request->input('pesan', ''));

        $scores = [
            'budget'   => 0,
            'katering' => 0,
            'dekor'    => 0,
            'baju'     => 0,
            'sapaan'   => 0,
        ];

        // Kamus padanan kata penilai konteks obrolan
        $dictionary = [
            'budget'   => ['api', 'hemat', 'murah', 'budget', 'biaya', 'dana', 'uang', 'dompet', 'modal', 'celengan', 'anggaran', 'pangkas', 'tips hemat'],
            'katering' => ['katering', 'catering', 'makan', 'konsumsi', 'prasmanan', 'menu', 'hidangan', 'gubuk', 'food', 'porsi', 'rasa', 'testing'],
            'dekor'    => ['dekor', 'dekorasi', 'bunga', 'panggung', 'tenda', 'pelaminan', 'gedung', 'estetik', 'rustic', 'backdrop', 'lampu', 'venue'],
            'baju'     => ['baju', 'gaun', 'rias', 'makeup', 'mua', 'kebaya', 'pakaian', 'pengantin', 'wajah', 'dandan', 'adatan'],
            'sapaan'   => ['halo', 'hai', 'tes', 'p', 'pagi', 'siang', 'malam', 'assalamualaikum', 'permisi', 'min', 'jalin']
        ];

        foreach ($dictionary as $category => $keywords) {
            foreach ($keywords as $word) {
                if (str_contains($pesanUser, $word)) {
                    $scores[$category] += 2;
                }
            }
        }

        arsort($scores);
        $keywordTerpilih = key($scores);
        $skorTertinggi = current($scores);

        if ($skorTertinggi == 0) {
            $keywordTerpilih = 'default';
        }

        // Ambil baris data secara acak sesuai bobot kata kunci terdekat
        $dataAi = DB::table('ai_knowledge')
            ->where('keyword', $keywordTerpilih)
            ->inRandomOrder()
            ->first();

        if ($dataAi) {
            $jawaban = $dataAi->jawaban;
        } else {
            $jawaban = "Wah, pertanyaan yang menarik, Kak! 💡 Sebagai **JALIN AI Wedding Assistant**, aku paling jago kalau diajak ngobrol seputar persiapan pernikahan. \n\nYuk, coba tanyakan hal seputar **tips hemat budget**, **katering prasmanan**, **dekorasi pelaminan**, atau **gaun pengantin** biar aku bisa kupas tuntas secara acak dari database-ku! 🌸✨";
        }

        return response()->json(['jawaban' => nl2br($jawaban)]);
    }
}
