<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisPembayaran as Pembayaran;

class JenisPembayaran extends Controller
{
    public function jenisPembayaranAdmin(Request $request)
    {
        $data = Pembayaran::query();

        // Filter by Nama Jenis Pembayaran
        if ($request->get('search')) {
            $data->where('nama_jenis_pembayaran', 'LIKE', '%' . $request->get('search') . '%');
        }

        // Filter by Kelas
        if ($request->get('kelas')) {
            $data->whereJsonContains('tingkat_kelas', $request->get('kelas'));
        }

        // Paginate
        $data = $data->paginate(10);

        return view('admin/jenisPembayaran', compact('data', 'request'));
    }

    public function deleteJenisPembayaran(Request $request){
        $id = $request->input('delete_id');
        $jenisPembayaran = Pembayaran::where('id', $id)->first();

        if (!$jenisPembayaran) {
            return redirect()->route('jenisPembayaranAdmin')->with('error', 'Id tidak ditemukan.');
        }

        // Delete Data
        $jenisPembayaran->delete();

        return redirect()->route('jenisPembayaranAdmin')->with('success', 'Jenis Pembayaran  berhasil dihapus.');
    }

    public function jenisPembayaranGuru(Request $request)
    {
        $data = Pembayaran::query();

        if ($request->get('search')) {
            $data->where('nama_jenis_pembayaran', 'LIKE', '%' . $request->get('search') . '%');
        }

        // Filter by Kelas
        if ($request->get('kelas')) {
            $data->whereJsonContains('tingkat_kelas', $request->get('kelas'));
        }


        // Paginate
        $data = $data->paginate(10);

        return view('guru/jenisPembayaran', compact('data', 'request'));
    }
}
