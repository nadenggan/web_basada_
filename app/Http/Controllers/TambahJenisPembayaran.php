<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TambahJenisPembayaran extends Controller
{
    public function tambahJenisPembayaran(){
        return view('tambahJenisPembayaran');
    }
}
