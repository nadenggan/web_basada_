<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function home(Request $request)
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

        // Total Siswa (All class)
        $total = DB::table("users")
            ->where("role", "siswa")
            ->count("nis");

        // Total Siswa (X)
        $totalX = User::join("kelas", "users.id_kelas", "=", "kelas.id_kelas")
            ->where("users.role", "siswa")
            ->where("kelas.tingkat_kelas", "X")
            ->count();

        // Total Siswa (XI)
        $totalXI = User::join("kelas", "users.id_kelas", "=", "kelas.id_kelas")
            ->where("users.role", "siswa")
            ->where("kelas.tingkat_kelas", "XI")
            ->count();

        // Total Siswa (XII)
        $totalXII = User::join("kelas", "users.id_kelas", "=", "kelas.id_kelas")
            ->where("users.role", "siswa")
            ->where("kelas.tingkat_kelas", "XII")
            ->count();

        // Total Jenis Pembayaran
        $totalJenisPembayaran = DB::table("jenis_pembayaran")->count();

        return view('/home', compact("users", "total", "totalX", "totalXI", "totalXII", "totalJenisPembayaran", "request"));
    }

    public function homeSiswa()
    {
        return view('siswa/home');
    }

}