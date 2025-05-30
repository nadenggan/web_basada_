<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pembayaran;
use App\Models\JenisPembayaran;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PrediksiController;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PembayaranImport;
use App\Traits\LogAktivitas;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    use logAktivitas;
    public function home(Request $request)
    {
        //set_time_limit(60);

        // Only get data Siswa
        $users = User::with("kelas")->whereNotNull("nis")->join('kelas', 'users.id_kelas', '=', 'kelas.id_kelas')
            ->orderBy('kelas.tingkat_kelas', 'asc') // From X to XII
            ->select('users.*');
        ;

        // Filter by Name, NIS, Jurusan, status
        if ($request->get('search')) {
            $search = $request->get('search');

            $users->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('nis', 'LIKE', '%' . $search . '%')
                    ->orWhere('status_siswa', 'LIKE', '%' . $search . '%')
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

        // Paginate (all query string before, stay at URL )
        $users = $users->paginate(10)->appends($request->all());

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

        //  Diagram Pie
        $jenisPembayaranOptions = JenisPembayaran::all();
        $lunasCount = 0;
        $belumLunasCount = 0;
        $totalPembayaranDiagram = 0;
        $hasDiagramData = false;

        $pembayaranQuery = DB::table('pembayaran');

        if ($request->has('filter_jenis_pembayaran') && $request->filter_jenis_pembayaran != '') {
            $pembayaranQuery->where('id_jenis_pembayaran', $request->filter_jenis_pembayaran);
        }

        if ($request->has('filter_kelas') && $request->filter_kelas != '') {
            $pembayaranQuery->join('users', 'pembayaran.user_id', '=', 'users.id')
                ->join('kelas', 'users.id_kelas', '=', 'kelas.id_kelas')
                ->where('kelas.tingkat_kelas', $request->filter_kelas);
        }

        $pembayaransDiagram = $pembayaranQuery->get();
        $totalPembayaranDiagram = $pembayaransDiagram->count();
        $lunasCount = $pembayaransDiagram->where('status_pembayaran', 'Lunas')->count();
        $belumLunasCount = $totalPembayaranDiagram - $lunasCount;

        $persentaseLunas = $totalPembayaranDiagram > 0 ? ($lunasCount / $totalPembayaranDiagram) * 100 : 0;
        $persentaseBelumLunas = $totalPembayaranDiagram > 0 ? ($belumLunasCount / $totalPembayaranDiagram) * 100 : 0;

        if ($totalPembayaranDiagram > 0) {
            $hasDiagramData = true;
        }

       $notifications = Pembayaran::where('status_pembayaran', 'Belum Lunas')
        ->where(function ($query) {
            // periode non-bulanan
            $query->whereHas('jenisPembayaran', function ($jpQuery) {
                $jpQuery->where('tenggat_waktu', '<', Carbon::now()->toDateString())
                    ->where('periode', '!=', 'bulanan')
                    ->orWhereNull('periode');
            })
            // periode bulanan
            ->orWhere(function ($queryBulanan) {
                $queryBulanan->whereHas('jenisPembayaran', function ($jpQueryBulanan) {
                    $jpQueryBulanan->where('periode', 'bulanan')
                        ->whereNotNull('tanggal_bulanan')
                        ->where('tanggal_bulanan', '<', Carbon::now()->day);
                });
            });
        })
        ->with('users', 'jenisPembayaran')
        ->get();

        return view('/home', compact("users", "total", "totalX", "totalXI", "totalXII", "totalJenisPembayaran", "request", "prediksiMap", "jenisPembayaranOptions", "persentaseLunas", "persentaseBelumLunas", "hasDiagramData", 'notifications'));
    }


    public function homeSiswa(Request $request)
    {
        $user = Auth::user();
        $siswa = User::find($user->id);

        $tahunAjaranList = Pembayaran::where('user_id', $siswa->id)
            ->select('tahun_ajaran')
            ->distinct()
            ->pluck('tahun_ajaran');

        // Default: tahun ajaran aktif
        $tahunAjaranAktif = $request->get('tahun_ajaran', config('app.tahun_ajaran_aktif'));

        $query = Pembayaran::where('user_id', $siswa->id)
            ->where('tahun_ajaran', $tahunAjaranAktif);

        if ($request->filled('jenis_pembayaran')) {
            $query->where('id_jenis_pembayaran', $request->jenis_pembayaran);
        }

        $pembayarans = $query->with('jenisPembayaran')
            ->paginate(10)
            ->appends($request->query());

        $jenisPembayaran = JenisPembayaran::all();

        return view('siswa/home', compact('siswa', 'pembayarans', 'jenisPembayaran', 'tahunAjaranList', 'tahunAjaranAktif'));
    }

    public function importExcelPembayaran(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        // dd($request->file('file'));

        //dd('Validasi file berhasil');
        $file = $request->file('file');


        try {
            Excel::import(new PembayaranImport, $file);
            $this->logAktivitas('Import Pembayaran', 'Berhasil mengimpor data pembayaran dari file: ' . $file->getClientOriginalName());
            return redirect('/home')->with('success', 'Data pembayaran  berhasil diimpor.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorString = "Terjadi kesalahan validasi saat impor:\n";
            foreach ($failures as $failure) {
                $errorString .= sprintf(
                    "- Baris %s, Kolom %s: %s\n",
                    $failure->row(),
                    $failure->attribute(),
                    implode(', ', $failure->errors())
                );
            }
            return redirect()->back()->with('error', $errorString);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengimpor data pembayaran: ' . $e->getMessage());
        }
    }
}