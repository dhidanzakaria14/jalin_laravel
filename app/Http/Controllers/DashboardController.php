<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect('/dashboard');
        }

        $user = Auth::user();

        // 2. Logika Redirect Berdasarkan Role
        if ($user->role == 'admin') {
            return view('dashboard.admin');
        } elseif ($user->role == 'vendor') {
            return view('dashboard.vendor');
        } else {
            // Jika Customer, ambil data dashboard
            return $this->customerDashboard($user);
        }
    }

    private function customerDashboard($user)
    {
        // Menggantikan logika SQL native dengan Laravel Query Builder
        $countBooking = DB::table('pesanan')->where('id_pengantin', $user->id_user)->count();

        $orders = DB::table('pesanan as p')
            ->select('p.id_pesanan', 'p.tgl_pesan', 'p.status', 'l.nama_layanan', 'u.nama_lengkap as nama_vendor')
            ->join('detail_pesanan as dp', 'p.id_pesanan', '=', 'dp.id_pesanan')
            ->join('layanan as l', 'dp.id_layanan', '=', 'l.id_layanan')
            ->join('users as u', 'l.id_vendor', '=', 'u.id_user')
            ->where('p.id_pengantin', $user->id_user)
            ->orderBy('p.tgl_pesan', 'DESC')
            ->limit(5)
            ->get();

        $payments = DB::table('pembayaran as py')
            ->select('py.*', 'l.nama_layanan')
            ->join('pesanan as p', 'py.id_pesanan', '=', 'p.id_pesanan')
            ->join('detail_pesanan as dp', 'p.id_pesanan', '=', 'dp.id_pesanan')
            ->join('layanan as l', 'dp.id_layanan', '=', 'l.id_layanan')
            ->where('p.id_pengantin', $user->id_user)
            ->orderBy('py.tgl_bayar', 'DESC')
            ->get();

        return view('dashboard.customer', compact('user', 'countBooking', 'orders', 'payments'));
    }
}
