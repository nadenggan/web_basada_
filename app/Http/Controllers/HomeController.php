<?php

namespace App\Http\Controllers;

class HomeController extends Controller{
    public function home(){
        return view('home');
    }

    public function homeSiswa(){
        return view('siswa/home');
    }

}