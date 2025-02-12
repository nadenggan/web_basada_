<?php

namespace App\Http\Controllers;

use App\Imports\SiswaImport;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;

class DataSiswa extends Controller
{
    public function dataSiswaAdmin(){
        // Only get data Siswa
        $users=User::with("kelas")->whereNotNull("nis")->paginate(10);
        return view('admin/dataSiswa', compact("users"));
    }
    public function dataSiswaGuru(){
         // Only get data Siswa
         $users=User::with("kelas")->whereNotNull("nis")->paginate(10);
         return view('guru/dataSiswa', compact("users"));
    }

    public function importExcel(request $request){

        $file = $request->file("file");
        $namaFile=rand().$file->getClientOriginalName();

        // Put file in path  public/DataSiswa
        $file->move("DataSiswa", $namaFile); 

        Excel::import(new SiswaImport, public_path("/DataSiswaExcel/".$namaFile));

        return redirect("/dataSiswaAdmin");
    }
}
