<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - JALIN (Jembatan Layanan Pernikahan)
|--------------------------------------------------------------------------
*/

// Halaman Utama JALIN (Bisa diakses oleh publik tanpa login)
Route::get('/', function () {
    return view('login');
});


// =========================================================================
// JALUR AUTENTIKASI (LOGIN, REGISTER, & LUPA PASSWORD)
// =========================================================================

// Tampilan Form Login & Proses Autentikasinya
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Tampilan Form Register & Proses Pendaftaran User Baru
Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Tampilan Form Lupa Password & Proses Update Reset Password Mandiri
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'processForgotPassword'])->name('password.update');

// Proses Logout Akun JALIN
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// =========================================================================
// JALUR DASHBOARD MULTI-ROLE (WAJIB LOGIN / PROTEKSI MIDDLWARE AUTH)
// =========================================================================
Route::middleware(['auth'])->group(function () {

    // 1. Jalur Dashboard untuk Admin JALIN
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/vendor/verifikasi/{id}', [AdminController::class, 'verifikasiVendor'])->name('admin.vendor.verifikasi');
    Route::post('/admin/layanan/verifikasi/{id}', [AdminController::class, 'verifikasiLayanan'])->name('admin.layanan.verifikasi');

    // 2. Dashboard Customer (Calon Pengantin) Utama & Katalog Belanja
    Route::get('/customer/dashboard', [CustomerController::class, 'index'])->name('customer.dashboard');

    // ⚙️ Proses Simpan Perubahan Profil Customer di URL Dashboard Utama (Single Page)
    Route::post('/customer/dashboard', [CustomerController::class, 'updateProfile'])->name('customer.profile.update');

    // 🤖 🎯 TAMBAHAN BARU: Rute AJAX Endpoint untuk JALIN Meta AI Consultant
    Route::post('/customer/tanya-ai', [CustomerController::class, 'tanyaAi'])->name('customer.tanya.ai');

    // 🛒 Rute Fitur Keranjang & Pesanan Saya Ala Shopee Customer
    Route::get('/customer/keranjang', [CustomerController::class, 'showKeranjang'])->name('customer.keranjang');
    Route::post('/customer/keranjang/add', [CustomerController::class, 'tambahKeranjang'])->name('customer.customer.keranjang.add');
    Route::get('/customer/pesanan', [CustomerController::class, 'showPesanan'])->name('customer.pesanan');

    // 3. Jalur Utama Vendor UMKM (Konsep Single Page Dashboard)
    Route::get('/vendor/dashboard', [VendorController::class, 'index'])->name('vendor.dashboard');

    // Fitur Menangani Proses Submit Form Perubahan Profil Vendor (Method POST)
    Route::post('/vendor/edit-profile', [VendorController::class, 'updateProfile'])->name('vendor.profile.update');

    // Fitur Menangani Proses Hapus Akun Vendor (Method DELETE)
    Route::delete('/vendor/delete-account', [VendorController::class, 'deleteAccount'])->name('vendor.profile.delete');

    // Fitur Manajemen Katalog Jasa/Layanan (Store, Update, & Delete)
    Route::post('/vendor/layanan/store', [VendorController::class, 'storeLayanan'])->name('vendor.layanan.store');
    Route::post('/vendor/layanan/update/{id}', [VendorController::class, 'updateLayanan'])->name('vendor.layanan.update');
    Route::delete('/vendor/layanan/delete/{id}', [VendorController::class, 'deleteLayanan'])->name('vendor.layanan.delete');

    // Jalur Manajemen Kategori Jasa Vendor (Store, Update, & Delete)
    Route::post('/vendor/kategori/store', [VendorController::class, 'storeKategori'])->name('vendor.kategori.store');
    Route::put('/vendor/kategori/update/{id}', [VendorController::class, 'updateKategori'])->name('vendor.kategori.update');
    Route::delete('/vendor/kategori/delete/{id}', [VendorController::class, 'deleteKategori'])->name('vendor.kategori.delete');

    // =========================================================================
    // JALUR FITUR CHAT JALIN (POLLING AJAX METHOD)
    // =========================================================================
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/room/{id_obrolan}', [ChatController::class, 'bukaObrolan'])->name('chat.room');
    Route::get('/chat/ambil-pesan/{id_obrolan}', [ChatController::class, 'ambilPesan']);
    Route::post('/chat/kirim', [ChatController::class, 'kirimPesan'])->name('chat.kirim');
    Route::post('/chat/mulai/{id_vendor}', [ChatController::class, 'mulaiObrolanBaru'])->name('chat.mulai');

});
