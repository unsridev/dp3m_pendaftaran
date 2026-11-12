<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PengajarController;
use App\Http\Controllers\PesertaController;
use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication & Public Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/daftar', [AuthController::class, 'showRegister'])->name('register');
Route::post('/daftar', [AuthController::class, 'register'])->name('register.post');
Route::get('/api/dosen/lookup', [AuthController::class, 'lookupDosen'])->name('dosen.lookup');
Route::post('/demo/reset', [AuthController::class, 'resetDemo'])->name('demo.reset');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Verifikasi
    Route::get('/verifikasi', [AdminController::class, 'verifikasi'])->name('verifikasi');
    Route::post('/verifikasi/{id}', [AdminController::class, 'verifikasiAksi'])->name('verifikasi.aksi');

    // Data Peserta
    Route::get('/peserta', [AdminController::class, 'pesertaIndex'])->name('peserta.index');
    Route::post('/peserta', [AdminController::class, 'pesertaStore'])->name('peserta.store');
    Route::put('/peserta/{id}', [AdminController::class, 'pesertaUpdate'])->name('peserta.update');
    Route::delete('/peserta/{id}', [AdminController::class, 'pesertaDestroy'])->name('peserta.destroy');

    // Data Kelas & Plotting
    Route::get('/kelas', [AdminController::class, 'kelasIndex'])->name('kelas.index');
    Route::post('/kelas', [AdminController::class, 'kelasStore'])->name('kelas.store');
    Route::put('/kelas/{id}', [AdminController::class, 'kelasUpdate'])->name('kelas.update');
    Route::delete('/kelas/{id}', [AdminController::class, 'kelasDestroy'])->name('kelas.destroy');
    Route::get('/kelas/{id}/plotting', [AdminController::class, 'kelasPlotting'])->name('kelas.plotting');
    Route::post('/kelas/{id}/plotting/add', [AdminController::class, 'kelasPlottingAdd'])->name('kelas.plotting.add');
    Route::post('/kelas/{id}/plotting/remove', [AdminController::class, 'kelasPlottingRemove'])->name('kelas.plotting.remove');

    // Jadwal
    Route::get('/jadwal', [AdminController::class, 'jadwalIndex'])->name('jadwal.index');
    Route::post('/jadwal', [AdminController::class, 'jadwalStore'])->name('jadwal.store');
    Route::put('/jadwal/{id}', [AdminController::class, 'jadwalUpdate'])->name('jadwal.update');
    Route::delete('/jadwal/{id}', [AdminController::class, 'jadwalDestroy'])->name('jadwal.destroy');

    // Data Pengajar
    Route::get('/pengajar', [AdminController::class, 'pengajarIndex'])->name('pengajar.index');
    Route::post('/pengajar', [AdminController::class, 'pengajarStore'])->name('pengajar.store');
    Route::put('/pengajar/{id}', [AdminController::class, 'pengajarUpdate'])->name('pengajar.update');
    Route::delete('/pengajar/{id}', [AdminController::class, 'pengajarDestroy'])->name('pengajar.destroy');

    // Database Dosen Master
    Route::get('/dosen', [AdminController::class, 'dosenIndex'])->name('dosen.index');
    Route::post('/dosen', [AdminController::class, 'dosenStore'])->name('dosen.store');
    Route::put('/dosen/{id}', [AdminController::class, 'dosenUpdate'])->name('dosen.update');
    Route::delete('/dosen/{id}', [AdminController::class, 'dosenDestroy'])->name('dosen.destroy');
});

// Peserta Routes
Route::middleware(['auth', 'role:peserta'])->prefix('peserta')->name('peserta.')->group(function () {
    Route::get('/dashboard', [PesertaController::class, 'dashboard'])->name('dashboard');
    Route::get('/jadwal', [PesertaController::class, 'jadwal'])->name('jadwal');
    Route::get('/elearning', [PesertaController::class, 'elearning'])->name('elearning');
    Route::get('/wa', [PesertaController::class, 'wa'])->name('wa');
    Route::get('/profil', [PesertaController::class, 'profil'])->name('profil');
});

// Pengajar Routes
Route::middleware(['auth', 'role:pengajar'])->prefix('pengajar')->name('pengajar.')->group(function () {
    Route::get('/jadwal', [PengajarController::class, 'jadwal'])->name('jadwal');
    Route::get('/peserta', [PengajarController::class, 'peserta'])->name('peserta');
});
