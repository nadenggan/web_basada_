<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\JenisPembayaran;

class TambahJenisPembayaran extends Controller
{
    public function tambahJenisPembayaran()
    {
        return view('admin/tambahJenisPembayaran');
    }

    public function inputJenisPembayaran(Request $request)
    {
        JenisPembayaran::create($request->all());

        return redirect()->back()->with('success', 'Jenis pembayaran berhasil ditambahkan.');
    }

}
