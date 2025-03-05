<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;

class StatusPembayaranSiswa extends Controller
{
    public function statusPembayaranSiswa(Request $request)
    {
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

        $users = $users->paginate(10);

        return view('statusPembayaranSiswa', compact("users", "request"));
    }
}
