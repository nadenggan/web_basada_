<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataSiswa extends Controller
{
    public function dataSiswaAdmin(){
        return view('admin/dataSiswa');
    }
    public function dataSiswaGuru(){
        return view('guru/dataSiswa');
    }
}
