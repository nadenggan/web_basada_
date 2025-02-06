<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JenisPembayaran extends Controller
{
    public function jenisPembayaran(){
        return view('jenisPembayaran');
    }
}
