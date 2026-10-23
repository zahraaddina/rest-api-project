<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PoliController;
use App\Http\Controllers\Api\DokterController;
use App\Http\Controllers\Api\JadwalPraktikController;

// Endpoint publik: login
Route::post('/login', [AuthController::class, 'login']);

// Endpoint yang wajib menggunakan JWT
Route::middleware('auth:api')->group(function () {

    // Autentikasi admin
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Menampilkan daftar poliklinik
    Route::get('/polis', [PoliController::class, 'index']);

    // CRUD dokter
    Route::apiResource('dokters', DokterController::class);

    // CRUD jadwal praktik
    Route::apiResource(
        'jadwal-praktiks',
        JadwalPraktikController::class
    );
});
