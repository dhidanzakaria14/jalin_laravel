<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class VendorController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $id_vendor = $user->id;

        // 1. Data total layanan milik vendor
        $totalLayanan = DB::table('layanan')->where('id_vendor', $id_vendor)->count();

        // 2. Data total pesanan masuk (Menggunakan id_layanan sesuai database kamu)
        $totalPesanan = DB::table('pesanan')
            ->join('detail_pesanan', 'pesanan.id_pesanan', '=', 'detail_pesanan.id_pesanan')
            ->join('layanan', 'detail_pesanan.id_layanan', '=', 'layanan.id_layanan')
            ->where('layanan.id_vendor', $id_vendor)
            ->count();

        // 3. Ambil daftar kategori secara aman untuk Dropdown Form Modal
        $tabelKategori = Schema::hasTable('kategori') ? 'kategori' : 'categories';
        $primaryKeyKategori = Schema::hasColumn($tabelKategori, 'id_kategori') ? 'id_kategori' : 'id';

        $rawKategori = DB::table($tabelKategori)->get()->toArray();
        $listKategori = json_decode(json_encode($rawKategori), true);

        // 4. Join tabel layanan dengan tabel kategori secara dinamis agar nama_kategori muncul asli
        $layananQuery = DB::table('layanan')
            ->leftJoin($tabelKategori, 'layanan.id_kategori', '=', $tabelKategori . '.' . $primaryKeyKategori)
            ->where('layanan.id_vendor', $id_vendor)
            ->select('layanan.*', $tabelKategori . '.nama_kategori')
            ->orderBy('layanan.id_layanan', 'desc')
            ->get();

        return view('dashboard.vendor', compact('user', 'totalLayanan', 'totalPesanan', 'listKategori', 'layananQuery'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nama_toko'    => 'nullable|string|max:255',
            'no_wa'        => 'required|string|max:20',
            'alamat'       => 'nullable|string',
            'foto_profil'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $dbUser = \App\Models\User::find($user->id);

        $dbUser->nama_lengkap = $request->nama_lengkap;
        $dbUser->nama_toko    = $request->nama_toko;
        $dbUser->no_whatsapp  = $request->no_wa;
        $dbUser->alamat       = $request->alamat;

        if ($request->hasFile('foto_profil')) {
            $file = $request->file('foto_profil');
            $nama_foto = 'profil_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $nama_foto);
            $dbUser->foto_profil = $nama_foto;
        }

        $dbUser->save();

        return redirect('/vendor/dashboard?tab=profile')->with('sukses', 'Profil JALIN Partner Anda berhasil diperbarui!');
    }

    /**
     * REVISI: Menyimpan Katalog Layanan Baru dengan Kolom minimal_dp_persen
     */
    public function storeLayanan(Request $request)
    {
        $user = Auth::user();

        // Menangkap name="minimal_dp_percent" dari form blade secara fleksibel
        $request->validate([
            'id_kategori'        => 'required',
            'nama_layanan'       => 'required|string|max:255',
            'harga'              => 'required|numeric',
            'minimal_dp_percent' => 'nullable|numeric|min:0|max:100',
            'deskripsi'          => 'nullable|string',
            'foto'               => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $nama_gambar = 'default-layanan.png';

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $nama_gambar = 'layanan_' . time() . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $nama_gambar);
        }

        // Simpan langsung ke database sesuai struktur kolom tabel layanan asli kamu
        DB::table('layanan')->insert([
            'id_vendor'         => $user->id,
            'id_kategori'       => $request->id_kategori,
            'nama_layanan'      => $request->nama_layanan,
            'harga'             => $request->harga,
            'minimal_dp_persen' => $request->minimal_dp_percent ?? 0,
            'deskripsi'         => $request->deskripsi,
            'gambar'            => $nama_gambar,
        ]);

        return redirect('/vendor/dashboard?tab=layanan')->with('sukses', 'Katalog layanan baru berhasil ditambahkan!');
    }

    /**
     * FITUR TAMBAHAN: Menyimpan Kategori Baru ke Database
     */
    public function storeKategori(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        $tabelKategori = Schema::hasTable('kategori') ? 'kategori' : 'categories';

        DB::table($tabelKategori)->insert([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return redirect('/vendor/dashboard?tab=kategori')->with('sukses', 'Kategori baru berhasil ditambahkan!');
    }

    /**
     * FITUR TAMBAHAN: Mengupdate Nama Kategori Jasa (REVISI AMAN & DINAMIS)
     */
    public function updateKategori(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ]);

        $tabelKategori = Schema::hasTable('kategori') ? 'kategori' : 'categories';

        $primaryKey = 'id';
        if ($tabelKategori === 'kategori' && Schema::hasColumn('kategori', 'id_kategori')) {
            $primaryKey = 'id_kategori';
        }

        DB::table($tabelKategori)
            ->where($primaryKey, $id)
            ->update([
                'nama_kategori' => $request->nama_kategori,
            ]);

        return redirect('/vendor/dashboard?tab=kategori')->with('sukses', 'Kategori berhasil diperbarui!');
    }

    /**
     * FITUR TAMBAHAN: Menghapus Kategori Berdasarkan ID Kategori
     */
    public function deleteKategori($id)
    {
        $tabelKategori = Schema::hasTable('kategori') ? 'kategori' : 'categories';

        $primaryKey = 'id';
        if ($tabelKategori === 'kategori' && Schema::hasColumn('kategori', 'id_kategori')) {
            $primaryKey = 'id_kategori';
        }

        DB::table($tabelKategori)
            ->where($primaryKey, $id)
            ->delete();

        return redirect('/vendor/dashboard?tab=kategori')->with('sukses', 'Kategori sukses dihapus!');
    }

    public function deleteAccount(Request $request)
    {
        $user = Auth::user();
        $dbUser = \App\Models\User::find($user->id);

        if ($dbUser) {
            Auth::logout();
            $dbUser->delete();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/login')->with('sukses', 'Akun JALIN Partner Anda telah dihapus secara permanen.');
        }

        return redirect()->back()->withErrors(['error' => 'Gagal menghapus akun, data tidak ditemukan.']);
    }

    /**
     * REVISI: Mengupdate Data, Foto Katalog Layanan, & Kolom minimal_dp_persen
     */
    public function updateLayanan(Request $request, $id)
    {
        $request->validate([
            'nama_layanan'       => 'required|string|max:255',
            'harga'              => 'required|numeric',
            'minimal_dp_percent' => 'nullable|numeric|min:0|max:100',
            'deskripsi'          => 'nullable|string',
            'foto'               => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // 1. Ambil data layanan lama untuk cek gambar
        $layanan = DB::table('layanan')->where('id_layanan', $id)->first();
        if (!$layanan) {
            return redirect()->back()->withErrors(['error' => 'Layanan tidak ditemukan.']);
        }

        $nama_gambar = $layanan->gambar;

        // 2. Jika user mengunggah foto baru, proses filenya
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $nama_gambar = 'layanan_' . time() . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $nama_gambar);

            // Hapus foto lama dari folder jika bukan gambar default
            if ($layanan->gambar && $layanan->gambar !== 'default-layanan.png' && file_exists(public_path('uploads/' . $layanan->gambar))) {
                @unlink(public_path('uploads/' . $layanan->gambar));
            }
        }

        // 3. Update data lengkap ke tabel database layanan
        DB::table('layanan')
            ->where('id_layanan', $id)
            ->update([
                'nama_layanan'      => $request->nama_layanan,
                'harga'             => $request->harga,
                'minimal_dp_persen' => $request->minimal_dp_percent ?? 0,
                'deskripsi'         => $request->deskripsi,
                'gambar'            => $nama_gambar,
            ]);

        return redirect('/vendor/dashboard?tab=layanan')->with('sukses', 'Katalog layanan berhasil diperbarui!');
    }

    /**
     * FITUR TAMBAHAN: Menghapus Katalog Layanan secara Permanen
     */
    public function deleteLayanan($id)
    {
        $layanan = DB::table('layanan')->where('id_layanan', $id)->first();

        if ($layanan) {
            // Hapus file gambar fisik dari folder uploads jika ada
            if ($layanan->gambar && $layanan->gambar !== 'default-layanan.png' && file_exists(public_path('uploads/' . $layanan->gambar))) {
                @unlink(public_path('uploads/' . $layanan->gambar));
            }

            // Hapus data dari tabel database
            DB::table('layanan')->where('id_layanan', $id)->delete();

            return redirect('/vendor/dashboard?tab=layanan')->with('sukses', 'Layanan berhasil dihapus dari katalog!');
        }

        return redirect()->back()->withErrors(['error' => 'Gagal menghapus layanan.']);
    }
}
