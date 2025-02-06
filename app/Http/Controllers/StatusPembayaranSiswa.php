<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StatusPembayaranSiswa extends Controller
{
    public function statusPembayaranSiswa(){
        return view('statusPembayaranSiswa');
    }
}
