<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalVendor = DB::table('users')->where('role', 'Vendor')->count();
        $totalCustomer = DB::table('users')->where('role', 'Pengantin')->count();
        $totalLayanan = DB::table('layanan')->count();

        $vendorMenunggu = DB::table('users')
            ->where('role', 'Vendor')
            ->where('status_verifikasi', 'menunggu')
            ->count();

        $layananMenunggu = DB::table('layanan')
            ->where('status_verifikasi', 'menunggu')
            ->count();

        $vendors = DB::table('users')
            ->where('role', 'Vendor')
            ->orderByRaw("FIELD(status_verifikasi, 'menunggu', 'ditolak', 'terverifikasi')")
            ->get();

        $layananList = DB::table('layanan')
            ->join('users', 'layanan.id_vendor', '=', 'users.id')
            ->select('layanan.*', 'users.nama_toko', 'users.nama_lengkap')
            ->orderByRaw("FIELD(layanan.status_verifikasi, 'menunggu', 'ditolak', 'terverifikasi')")
            ->get();

        return view('dashboard.admin', compact(
            'user',
            'totalVendor',
            'totalCustomer',
            'totalLayanan',
            'vendorMenunggu',
            'layananMenunggu',
            'vendors',
            'layananList'
        ));
    }

    public function verifikasiVendor(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:terverifikasi,ditolak',
        ]);

        DB::table('users')
            ->where('id', $id)
            ->where('role', 'Vendor')
            ->update(['status_verifikasi' => $request->status]);

        $label = $request->status === 'terverifikasi' ? 'diverifikasi' : 'ditolak';

        return redirect('/admin/dashboard?tab=vendor')->with('sukses', "Vendor berhasil {$label}!");
    }

    public function verifikasiLayanan(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:terverifikasi,ditolak',
        ]);

        DB::table('layanan')
            ->where('id_layanan', $id)
            ->update(['status_verifikasi' => $request->status]);

        $label = $request->status === 'terverifikasi' ? 'diverifikasi' : 'ditolak';

        return redirect('/admin/dashboard?tab=layanan')->with('sukses', "Layanan berhasil {$label}!");
    }
}
