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
        // Get tingkat_kelas value
        $tingkatKelas = $request->input('tingkat_kelas');

        // Check tingkatKelas is array or no
        if (is_array($tingkatKelas)) {
            $tingkatKelasString = implode(',', $tingkatKelas);
            $data = $request->except('tingkat_kelas');
            $data['tingkat_kelas'] = $tingkatKelasString; // Add data tingkat_kelas with custom value
            JenisPembayaran::create($data);
        } else {
            JenisPembayaran::create($request->all());
        }

        return redirect()->back()->with('success', 'Jenis pembayaran berhasil ditambahkan.');
    }

}
