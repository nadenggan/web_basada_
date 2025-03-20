<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DataSiswa;
use App\Http\Controllers\LogAktivitas;
use App\Http\Controllers\StatusPembayaranSiswa;
use App\Http\Controllers\TambahJenisPembayaran;
use App\Http\Controllers\JenisPembayaran;
use App\Http\Controllers\Register;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\InputPembayaranSiswa;

Route::get('/login', function () {
    return view("login");
})->name("login");

Route::post('/postLogin', [LoginController::class, 'login'])->name("postLogin");

Route::get('/register', function () {
    return view("register");
})->name("register");

Route::post('/postRegister', [Register::class, 'register'])->name("postRegister");

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', [ForgotPasswordController::class, 'forgotPassword'])->middleware('guest')->name('password.email');


Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->middleware('guest')->name('password.update');

Route::get('/logout', [LoginController::class, 'logout'])->name("logout");


Route::group(["middleware" => ["auth", "checkrole:admin"]], function () {
    Route::get('/dataSiswaAdmin', [DataSiswa::class, 'dataSiswaAdmin'])->name("dataSiswaAdmin");
    Route::post('/deleteDataSiswaAdmin', [DataSiswa::class, 'deleteDataSiswaAdmin'])->name("deleteDataSiswaAdmin");
    Route::get('/dataSiswaAdmin/edit/{nis}', [DataSiswa::class, 'editDataSiswaAdmin'])->name("editDataSiswaAdmin");
    Route::post('/dataSiswaAdmin/update/{nis}', [DataSiswa::class, 'updateDataSiswa'])->name("updateDataSiswa");
    Route::get('/logAktivitas', [LogAktivitas::class, 'logAktivitas'])->name("logAktivitas");
    Route::get('/tambahJenisPembayaran', [TambahJenisPembayaran::class, 'tambahJenisPembayaran'])->name("tambahJenisPembayaran");
    Route::post('/inputJenisPembayaran', [TambahJenisPembayaran::class, 'inputJenisPembayaran'])->name("inputJenisPembayaran");
    Route::get('/jenisPembayaranAdmin', [JenisPembayaran::class, 'jenisPembayaranAdmin'])->name("jenisPembayaranAdmin");
    Route::post('/deleteJenisPembayaran', [JenisPembayaran::class, 'deleteJenisPembayaran'])->name("deleteJenisPembayaran");
    Route::get('/jenisPembayaranAdmin/edit/{jenisPembayaran}', [JenisPembayaran::class, 'editJenisPembayaran'])->name("editJenisPembayaran");
    Route::post('/jenisPembayaranAdmin/update/{id}', [JenisPembayaran::class, 'updateJenisPembayaran'])->name("updateJenisPembayaran");
    Route::get('/tambah-data/', [DataSiswa::class, 'tambahDataSiswa'])->name("tambahDataSiswa");
    Route::post('/store-data/', [DataSiswa::class, 'storeDataSiswa'])->name("storeDataSiswa");
    Route::post('/importExcel', [DataSiswa::class, 'importExcel'])->name("importExcel");

});

Route::group(["middleware" => ["auth", "checkrole:admin,guru"]], function () {
    Route::get('/home', [HomeController::class, 'home'])->name("home");
    Route::get('/statusPembayaranSiswa', [StatusPembayaranSiswa::class, 'statusPembayaranSiswa'])->name("statusPembayaranSiswa");
    Route::get('/rekap-pembayaran/{nis}', [DataSiswa::class, 'rekapDataSiswa'])->name("rekapDataSiswa");
    Route::get('/inputPembayaran/{nis}', [inputPembayaranSiswa::class, 'showInputPembayaran']);
    Route::post('/storePembayaranSiswa/', [InputPembayaranSiswa::class, 'storePembayaranSiswa'])->name("storePembayaranSiswa");


});

Route::group(["middleware" => ["auth", "checkrole:siswa"]], function () {
    Route::get('/homeSiswa', [HomeController::class, 'homeSiswa'])->name("homeSiswa");
});

Route::group(["middleware" => ["auth", "checkrole:guru"]], function () {
    Route::get('/dataSiswa', [DataSiswa::class, 'dataSiswaGuru'])->name("dataSiswaGuru");
    Route::get('/jenisPembayaran', [JenisPembayaran::class, 'jenisPembayaranGuru'])->name("jenisPembayaranGuru");
});

