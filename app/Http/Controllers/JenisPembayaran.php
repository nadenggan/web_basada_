<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JenisPembayaran extends Controller
{
    public function jenisPembayaranAdmin(){
        return view('admin/jenisPembayaran');
    }

    public function jenisPembayaranGuru(){
        return view('guru/jenisPembayaran');
    }
}
