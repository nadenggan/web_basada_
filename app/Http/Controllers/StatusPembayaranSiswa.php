<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;

class StatusPembayaranSiswa extends Controller
{
    public function statusPembayaranSiswa(){
        $users = User::with("kelas")->whereNotNull("nis")->paginate(10);
        return view('statusPembayaranSiswa',compact("users"));
    }
}
