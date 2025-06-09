<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MoodController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\FocusSessionController;
use App\Http\Controllers\StatisticController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sinilah Anda dapat mendaftarkan semua rute untuk aplikasi Anda.
| Rute-rute ini dimuat oleh RouteServiceProvider dan semuanya akan
| ditetapkan ke grup middleware "web".
|
*/

// Halaman utama/landing page untuk pengguna yang belum login.
Route::get('/', function () {
    // Jika sudah login, arahkan ke dashboard. Jika belum, tampilkan halaman welcome.
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

// -----------------------------------------------------------------------------
// GRUP RUTE YANG MEMERLUKAN AUTENTIKASI (PENGGUNA HARUS LOGIN)
// -----------------------------------------------------------------------------
// Middleware 'auth' memastikan hanya user yang sudah login yang bisa akses.
// Middleware 'verified' memastikan email user sudah terverifikasi (jika Anda mengaktifkannya).
Route::middleware(['auth', 'verified'])->group(function () {

    // 🟩 2. [Dashboard / Home]
    // URL: /dashboard
    // Menampilkan halaman utama setelah login.
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 🟨 3. [Mood Input Logic]
    // URL: /moods (Method: POST)
    // Menerima data dari form input mood di dashboard.
    Route::post('/moods', [MoodController::class, 'store'])->name('moods.store');

    // 🟪 5. [Task List Page & Logic]
    // Ini adalah "Resource Route" yang secara otomatis membuat semua URL
    // yang dibutuhkan untuk CRUD (Create, Read, Update, Delete) tugas.
    Route::resource('tasks', TaskController::class);

    // 🟥 6. [Fokus Mode Page]
    // URL: /fokus (Method: GET)
    // Menampilkan halaman timer Pomodoro.
    Route::get('/fokus', [FocusSessionController::class, 'index'])->name('fokus.index');
    // URL: /fokus (Method: POST)
    // Menerima data dari JavaScript setelah sesi fokus selesai.
    Route::post('/fokus', [FocusSessionController::class, 'store'])->name('fokus.store');

    // ⬜ 7. [Statistik Page]
    // URL: /statistik
    // Menampilkan halaman grafik dan insight.
    Route::get('/statistik', [StatisticController::class, 'index'])->name('statistik.index');

    // Halaman Profil (Disediakan oleh Laravel Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// Rute Autentikasi (Disediakan oleh Laravel Breeze)
// File ini berisi semua rute untuk login, register, lupa password, dll.
require __DIR__.'/auth.php';
