<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DataSiswa;
use App\Http\Controllers\LogAktivitas;
use App\Http\Controllers\StatusPembayaranSiswa;
use App\Http\Controllers\TambahJenisPembayaran;
use App\Http\Controllers\JenisPembayaran;

Route::get('/login', function () {
    return view("login");
})->name("login");

Route::post('/postLogin', [LoginController::class, 'login'])->name("postLogin");

Route::get('/logout', [LoginController::class, 'logout'])->name("logout");


Route::group(["middleware" => ["auth", "checkrole:admin"]], function () {

    Route::get('/halaman1', [HomeController::class, 'halaman1'])->name("halaman1");
});

Route::group(["middleware" => ["auth", "checkrole:admin,guru"]], function () {
    Route::get('/home', [HomeController::class, 'home'])->name("home");
    Route::get('/dataSiswa', [DataSiswa::class, 'dataSiswa'])->name("dataSiswa");
    Route::get('/logAktivitas', [LogAktivitas::class, 'logAktivitas'])->name("logAktivitas");
    Route::get('/tambahJenisPembayaran', [TambahJenisPembayaran::class, 'tambahJenisPembayaran'])->name("tambahJenisPembayaran");
    Route::get('/jenisPembayaran', [JenisPembayaran::class, 'jenisPembayaran'])->name("jenisPembayaran");
    Route::get('/statusPembayaranSiswa', [StatusPembayaranSiswa::class, 'statusPembayaranSiswa'])->name("statusPembayaranSiswa");
    Route::get('/halaman2', [HomeController::class, 'halaman2'])->name("halaman2");
});


