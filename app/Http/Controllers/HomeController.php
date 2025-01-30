<?php

namespace App\Http\Controllers;

class HomeController extends Controller{
    public function home(){
        return view('home');
    }

    public function halaman1(){
        return view('halaman1');
    }

    public function halaman2(){
        return view('halaman2');
    }


}