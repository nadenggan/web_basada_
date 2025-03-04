<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;

class StatusPembayaranSiswa extends Controller
{
    public function statusPembayaranSiswa(Request $request){
        $users = User::with("kelas")->whereNotNull("nis");

        if($request->get("search")){
            $users->where("name","LIKE","%".$request->get("search")."%");
        }

        $users = $users->paginate(10);

        return view('statusPembayaranSiswa',compact("users","request"));
    }
}
