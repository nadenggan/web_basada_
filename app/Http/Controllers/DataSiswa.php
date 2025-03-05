<?php

namespace App\Http\Controllers;

use App\Imports\SiswaImport;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;

class DataSiswa extends Controller
{
    public function dataSiswaAdmin(Request $request)
    {
        // Only get data Siswa
        $users = User::with("kelas")->whereNotNull("nis");

        // Filter by Name, NIS, Jurusan
        if ($request->get('search')) {
            $search = $request->get('search');

            $users->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('nis', 'LIKE', '%' . $search . '%')
                    ->orWhereHas('kelas', function ($kelasQuery) use ($search) {
                        $kelasQuery->where('jurusan', 'LIKE', '%' . $search . '%');
                    });
            });
        }

        // Filter by Kelas
        if ($request->get("kelas")) {
            $users->whereHas('kelas', function ($query) use ($request) {
                $query->where('tingkat_kelas', $request->get("kelas"));
            });
        }

        // Paginate
        $users = $users->paginate(10);

        return view('admin/dataSiswa', compact("users", "request"));
    }

    public function editDataSiswaAdmin($nis)
    {
        $data = User::where('nis', $nis)->firstOrFail();
        return view('admin/editDataSiswa', compact('data'));
    }

    public function updateDataSiswa(Request $request, $nis)
    {
        $data = User::where('nis', $nis)->firstOrFail();

        $data->nis = $request->nis;
        $data->name = $request->name;
        $data->alamat = $request->alamat;

        $data->save();

        return redirect()->route('dataSiswaAdmin')->with('success', 'Data siswa berhasil diupdate.');
    }

    public function deleteDataSiswaAdmin(Request $request)
    {
        $nis = $request->input('delete_nis');
        $user = User::where('nis', $nis)->first();

        if (!$user) {
            return redirect()->route('dataSiswaAdmin')->with('error', 'Siswa tidak ditemukan.');
        }

        // Delete Data
        $user->delete();

        return redirect()->route('dataSiswaAdmin')->with('success', 'Data siswa berhasil dihapus.');
    }

    public function dataSiswaGuru(Request $request)
    {
        // Only get data Siswa
        $users = User::with("kelas")->whereNotNull("nis");

        // Filter by Name, NIS, Jurusan
        if ($request->get('search')) {
            $search = $request->get('search');

            $users->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('nis', 'LIKE', '%' . $search . '%')
                    ->orWhereHas('kelas', function ($kelasQuery) use ($search) {
                        $kelasQuery->where('jurusan', 'LIKE', '%' . $search . '%');
                    });
            });
        }

        // Filter by Kelas
        if ($request->get("kelas")) {
            $users->whereHas('kelas', function ($query) use ($request) {
                $query->where('tingkat_kelas', $request->get("kelas"));
            });
        }

        // Paginate
        $users = $users->paginate(10);

        return view('guru/dataSiswa', compact("users", "request"));
    }

    public function importExcel(request $request)
    {

        $file = $request->file("file");
        $namaFile = rand() . $file->getClientOriginalName();

        // Put file in path  public/DataSiswa
        $file->move("DataSiswaExcel", $namaFile);

        Excel::import(new SiswaImport, public_path("/DataSiswaExcel/" . $namaFile));

        return redirect("/dataSiswaAdmin");
    }
}
