<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogAktivitas extends Controller
{
    public function logAktivitas(){
        return view('admin/logAktivitas');
    }
}
