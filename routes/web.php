<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Aplikasi Perpustakaan Digital
|--------------------------------------------------------------------------
*/

// Halaman utama: Redirect ke dashboard jika sudah login, atau ke form login
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Autentikasi Pengguna
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Menu yang membutuhkan autentikasi (Login Terlebih Dahulu)
Route::middleware('auth')->group(function () {
    // 1. Dashboard Statistik
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Manajemen Data Buku (CRUD Lengkap)
    Route::resource('books', BookController::class);

    // 3. Manajemen Data Anggota Siswa (CRUD Lengkap)
    Route::resource('members', MemberController::class);

    // 4. Manajemen Transaksi Peminjaman (CRUD Lengkap)
    Route::resource('borrowings', BorrowingController::class);
    Route::post('/borrowings/{borrowing}/return', [BorrowingController::class, 'returnBook'])->name('borrowings.return');

    // 5. Profil Pengguna
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
