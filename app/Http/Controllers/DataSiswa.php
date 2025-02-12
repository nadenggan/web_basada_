<?php

namespace App\Http\Controllers;

use App\Imports\SiswaImport;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;

class DataSiswa extends Controller
{
    public function dataSiswaAdmin(){
        return view('admin/dataSiswa');
    }
    public function dataSiswaGuru(){
        return view('guru/dataSiswa');
    }

    public function importExcel(request $request){

        $file = $request->file("file");
        $namaFile=rand().$file->getClientOriginalName();

        // Put file in path  public/DataSiswa
        $file->move("DataSiswa", $namaFile); 

        Excel::import(new SiswaImport, public_path("/DataSiswa/".$namaFile));

        return redirect("/dataSiswaAdmin");
    }
}
