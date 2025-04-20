<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisPembayaran as Pembayaran;
use App\Traits\LogAktivitas;

class JenisPembayaran extends Controller
{
    use LogAktivitas;
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

    public function deleteJenisPembayaran(Request $request)
    {
        $id = $request->input('delete_id');
        $jenisPembayaran = Pembayaran::where('id', $id)->first();

        if (!$jenisPembayaran) {
            return redirect()->route('jenisPembayaranAdmin')->with('error', 'Id tidak ditemukan.');
        }

        $nama = $jenisPembayaran->nama_jenis_pembayaran;
        
        // Delete Data
        $jenisPembayaran->delete();

        // Log Act
        $this->logAktivitas('Delete Jenis Pembayaran', 'Delete jenis pembayaran ' . $nama . '.');

        return redirect()->route('jenisPembayaranAdmin')->with('success', 'Jenis Pembayaran  berhasil dihapus.');
    }

    public function editJenisPembayaran($jenisPembayaran)
    {
        $data = Pembayaran::where('id', $jenisPembayaran)->firstOrFail();
        return view('admin/editJenisPembayaran', compact('data'));
    }

    public function updateJenisPembayaran(Request $request, $id)
    {
        $data = Pembayaran::where('id', $id)->firstOrFail();

        $data->nama_jenis_pembayaran = $request->name;
        $data->deskripsi = $request->deskripsi;
        $data->nominal = $request->nominal;

          // Get tingkat_kelas value
          $tingkatKelas = $request->tingkat_kelas;

          // Check tingkatKelas is array or no
          $data->tingkat_kelas = json_encode($tingkatKelas); // Simpan sebagai JSON array
  

        // Check periode
        if ($request->periode === 'bulanan') {
            $data->periode = $request->periode;
            $data->tanggal_bulanan = $request->tenggat_waktu;
            $data->tenggat_waktu = null;
        } else {
            $data->periode = $request->periode;
            $data->tenggat_waktu = $request->tenggat_waktu;
            $data->tanggal_bulanan = null;
        }


        $data->save();

        return redirect()->route('jenisPembayaranAdmin')->with('success', 'Jenis Pembayaran berhasil diupdate.');
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
