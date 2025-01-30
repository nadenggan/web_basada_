<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

Route::get('/login', function () {
    return view("login");
})->name("login");

Route::post('/postLogin', [LoginController::class, 'login'])->name("postLogin");

Route::get('/logout', [LoginController::class, 'logout'])->name("logout");


Route::group(["middleware" => ["auth", "checkrole:admin"]], function () {

    Route::get('/halaman1', [HomeController::class, 'halaman1'])->name("halaman1");
});

Route::group(["middleware" => ["auth", "checkrole:admin,guru"]], function () {
    Route::get('/home', [HomeController::class, 'home']);

    Route::get('/halaman2', [HomeController::class, 'halaman2'])->name("halaman2");
});


