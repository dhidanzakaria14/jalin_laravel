<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    /**
     * Menampilkan daftar kontak obrolan aktif (Inbox Utama)
     */
    public function index()
    {
        $user = Auth::user();

        // Ambil daftar obrolan yang melibatkan user aktif
        $daftarObrolan = DB::table('obrolan')
            ->where('id_customer', $user->id)
            ->orWhere('id_vendor', $user->id)
            ->orderBy('updated_at', 'desc')
            ->get();

        // Cari info identitas lawan bicara secara dinamis untuk setiap room chat
        foreach ($daftarObrolan as $o) {
            $idLawan = ($user->id === $o->id_customer) ? $o->id_vendor : $o->id_customer;
            $lawan = DB::table('users')->where('id', $idLawan)->first();

            $o->nama_lawan = $lawan->nama_toko ?? $lawan->nama_lengkap ?? 'User JALIN';
            $o->foto_lawan = $lawan->foto_profil ?? null;

            $pesanTerakhir = DB::table('pesan')
                ->where('id_obrolan', $o->id_obrolan)
                ->orderBy('id_pesan', 'desc')
                ->first();

            $o->pesan_terakhir = $pesanTerakhir->isi_pesan ?? 'Belum ada pesan.';
            $o->waktu_terakhir = $pesanTerakhir ? date('H:i', strtotime($pesanTerakhir->created_at)) : '';

            $o->unread_count = DB::table('pesan')
                ->where('id_obrolan', $o->id_obrolan)
                ->where('id_pengirim', '!=', $user->id)
                ->where('is_read', false)
                ->count();
        }

        // 🎯 REVISI DUA ARAH: Menentukan siapa yang muncul di modal popup (+) berdasarkan role login
        if (strtolower($user->role) === 'vendor') {
            // Jika Vendor yang login, tampilkan daftar Customer untuk di-chat
            $listAllKontak = DB::table('users')
                ->where('role', 'customer')
                ->orWhere('role', 'Customer')
                ->get();
        } else {
            // Jika Customer/Admin yang login, tampilkan daftar Vendor
            $listAllKontak = DB::table('users')
                ->where('role', 'vendor')
                ->orWhere('role', 'Vendor')
                ->get();
        }

        return view('chat.index', compact('user', 'daftarObrolan', 'listAllKontak'));
    }

    /**
     * API Endpoint AJAX: Polling ambil pesan terupdate
     */
    public function ambilPesan($id_obrolan)
    {
        DB::table('pesan')
            ->where('id_obrolan', $id_obrolan)
            ->where('id_pengirim', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $pesan = DB::table('pesan')
            ->where('id_obrolan', $id_obrolan)
            ->orderBy('id_pesan', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'user_id' => Auth::id(),
            'data' => $pesan
        ]);
    }

    /**
     * API Endpoint AJAX: Kirim pesan baru
     */
    public function kirimPesan(Request $request)
    {
        $request->validate([
            'id_obrolan' => 'required',
            'isi_pesan'  => 'required|string',
        ]);

        DB::table('pesan')->insert([
            'id_obrolan'  => $request->id_obrolan,
            'id_pengirim' => Auth::id(),
            'isi_pesan'   => $request->isi_pesan,
            'is_read'     => false,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        DB::table('obrolan')
            ->where('id_obrolan', $request->id_obrolan)
            ->update(['updated_at' => now()]);

        return response()->json(['status' => 'success']);
    }

    /**
     * Membuat ruang obrolan baru dari modal (+) bebas role
     */
    public function mulaiObrolanBaru($id_lawan)
    {
        $userAktif = Auth::user();

        // Cari tahu siapa yang jadi customer dan siapa yang jadi vendor secara fleksibel
        if (strtolower($userAktif->role) === 'vendor') {
            $id_customer = $id_lawan;
            $id_vendor = $userAktif->id;
        } else {
            $id_customer = $userAktif->id;
            $id_vendor = $id_lawan;
        }

        // Cek apakah room kombinasi ini sudah pernah dibuat sebelumnya
        $cekRoom = DB::table('obrolan')
            ->where('id_customer', $id_customer)
            ->where('id_vendor', $id_vendor)
            ->first();

        if ($cekRoom) {
            return redirect()->route('chat.index');
        }

        // Buat room baru jika belum ada
        DB::table('obrolan')->insert([
            'id_customer' => $id_customer,
            'id_vendor'   => $id_vendor,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        return redirect()->route('chat.index');
    }
}
