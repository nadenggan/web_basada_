<?php

namespace App\Http\Controllers;
use App\Models\User;

class HomeController extends Controller{
    public function home(){
        $users = User::with("kelas")->whereNotNull("nis")->paginate(10);
        return view('home',compact("users"));
    }

    public function homeSiswa(){
        return view('siswa/home');
    }

}