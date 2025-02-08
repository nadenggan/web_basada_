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
    Route::get('/dataSiswaAdmin', [DataSiswa::class, 'dataSiswaAdmin'])->name("dataSiswaAdmin");
    Route::get('/logAktivitas', [LogAktivitas::class, 'logAktivitas'])->name("logAktivitas");
    Route::get('/tambahJenisPembayaran', [TambahJenisPembayaran::class, 'tambahJenisPembayaran'])->name("tambahJenisPembayaran");
    Route::get('/jenisPembayaranAdmin', [JenisPembayaran::class, 'jenisPembayaranAdmin'])->name("jenisPembayaranAdmin");
    Route::get('/rekap-pembayaran/{nis}', function ($nis) {
        return view('admin.rekapPembayaran', ['nis' => $nis]);
    });
    
    
});

Route::group(["middleware" => ["auth", "checkrole:admin,guru"]], function () {
    Route::get('/home', [HomeController::class, 'home'])->name("home");
    Route::get('/statusPembayaranSiswa', [StatusPembayaranSiswa::class, 'statusPembayaranSiswa'])->name("statusPembayaranSiswa");
});

Route::group(["middleware" => ["auth", "checkrole:siswa"]], function () {
    Route::get('/homeSiswa', [HomeController::class, 'homeSiswa'])->name("homeSiswa");
});

Route::group(["middleware" => ["auth", "checkrole:guru"]], function () {
    Route::get('/dataSiswa', [DataSiswa::class, 'dataSiswaGuru'])->name("dataSiswaGuru");
    Route::get('/jenisPembayaran', [JenisPembayaran::class, 'jenisPembayaranGuru'])->name("jenisPembayaranGuru");
});

