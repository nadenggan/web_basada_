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

        $data = $request->except('tingkat_kelas', 'tenggat_waktu');
        // Get tingkat_kelas value
        $tingkatKelas = $request->input('tingkat_kelas');

        // Check tingkatKelas is array or no
        $data['tingkat_kelas'] = json_encode($tingkatKelas); // Simpan sebagai JSON array


        // Check periode
        if ($request->periode === 'bulanan') {
            $data['tanggal_bulanan'] = $request->input('tenggat_waktu');
            $data['tenggat_waktu'] = null;
        } else {
            $data['tenggat_waktu'] = $request->input('tenggat_waktu');
            $data['tanggal_bulanan'] = null;
        }

        JenisPembayaran::create($data);

        return redirect()->back()->with('success', 'Jenis pembayaran berhasil ditambahkan.');
    }

}
