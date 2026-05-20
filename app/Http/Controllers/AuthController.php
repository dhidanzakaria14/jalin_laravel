<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Fungsi Proses Register
    public function register(Request $request)
    {
        // 1. Validasi Input Dasar
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name'  => 'required|string|max:50',
            'email'      => 'required|string|email|max:100|unique:users,email',
            'password'   => 'required|string|min:6',
            'no_wa'      => 'required|string|max:20',
            'role'       => 'required|in:Vendor,Admin,Pengantin',
        ]);

        // 2. Logika Pass Key Admin
        if ($request->role === 'Admin') {
            if ($request->admin_pass_key !== 'JALIN2026') {
                return back()->withErrors(['admin_pass_key' => 'Gagal! Pass Key Administrator tidak valid.'])->withInput();
            }
        }

        // 3. Gabungkan Nama & Set Status
        $nama_lengkap = $request->first_name . ' ' . $request->last_name;
        $status = ($request->role === 'Vendor') ? 'Pending' : 'Approved';

        // 4. Simpan ke Database
        User::create([
            'nama_lengkap' => $nama_lengkap,
            'nama_toko'    => $request->nama_toko ?? null,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'no_whatsapp'  => $request->no_wa,
            'alamat'       => $request->alamat ?? null,
            'role'         => $request->role,
            'status'       => $status,
        ]);

        return redirect()->route('login')->with('sukses', 'Pendaftaran Berhasil! Silakan Login.');
    }

    // Fungsi Proses Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Proteksi status akun Vendor jika masih Pending
            /*if ($user->role === 'Vendor' && $user->status === 'Pending') {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun Vendor Anda masih ditinjau (Pending) oleh Admin.']);
            }*/

            // --- BAGIAN REDIRECT YANG DIREVISI ---
            if ($user->role === 'Admin') {
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->role === 'Vendor') {
                return redirect()->intended('/vendor/dashboard');
            } else {
                // Melempar role Pengantin langsung ke rute customer dashboard
                return redirect()->intended('/customer/dashboard');
            }
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    // Fungsi Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('sukses', 'Kamu telah berhasil logout.');
    }

    // Fungsi untuk menampilkan halaman form lupa password
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    // Fungsi untuk memproses pencocokan data email & WhatsApp
    public function processForgotPassword(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'no_wa'    => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Cari user yang email dan nomor whatsapp-nya cocok
        $user = User::where('email', $request->email)
                    ->where('no_whatsapp', $request->no_wa)
                    ->first();

        // Jika data tidak cocok
        if (!$user) {
            return back()->withErrors(['error' => 'Kombinasi Email dan No. WhatsApp tidak ditemukan di sistem JALIN.'])->withInput();
        }

        // Jika cocok, ganti password dengan yang baru
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('login')->with('sukses', 'Password berhasil diperbarui! Silakan login dengan password baru.');
    }
}
