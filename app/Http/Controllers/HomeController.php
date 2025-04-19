<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pembayaran;
use App\Models\JenisPembayaran;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PrediksiController;

class HomeController extends Controller
{
    public function home(Request $request)
    {
        //set_time_limit(60);

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

         // Initiate PrediksiController
         $prediksiController = new PrediksiController();

         // Call prediksiSemuaSiswa function from  PrediksiController
         $prediksiSiswa = $prediksiController->prediksiSemuaSiswa()->getData()->data;
         //dd($prediksiSiswa);

         if (is_object($prediksiSiswa)) {
            $prediksiSiswa = json_decode(json_encode($prediksiSiswa), true);
        }

        // Change format for blade
        $prediksiMap = collect($prediksiSiswa)->keyBy('user_id');
        //dd($prediksiMap);
        //dd($prediksiMap->toArray());
        
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

        return view('/home', compact("users", "total", "totalX", "totalXI", "totalXII", "totalJenisPembayaran", "request", "prediksiMap"));
    }

    public function homeSiswa()
    {
        $user = Auth::user();
        $siswa = User::find($user->id);
        $pembayarans = Pembayaran::where('user_id', $siswa->id)
            ->with('jenisPembayaran')
            ->get();
        $jenisPembayaran = JenisPembayaran::all();
        return view('siswa/home', compact('siswa', 'pembayarans', 'jenisPembayaran'));
    }

}