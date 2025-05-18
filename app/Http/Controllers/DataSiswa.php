<?php

namespace App\Http\Controllers;

use App\Imports\SiswaImport;
use App\Models\User;
use App\Models\Kelas;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\JenisPembayaran;
use App\Models\Pembayaran;
use App\Models\Cicilan;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Exports\RekapExport;
use App\Http\Controllers\PrediksiController;


use App\Traits\LogAktivitas;

use Illuminate\Http\Request;

class DataSiswa extends Controller
{
    use LogAktivitas;
    public function dataSiswaAdmin(Request $request)
    {
        // Only get data Siswa
        $users = User::with("kelas")->whereNotNull("nis")->leftJoin('kelas', 'users.id_kelas', '=', 'kelas.id_kelas')
            ->orderBy('kelas.tingkat_kelas', 'asc') // From X to XII
            ->select('users.*');
        ;
        ;

        // Filter by Name, NIS, Jurusan
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

        // Paginate
        $users = $users->paginate(10)->appends($request->all());
        ;

        return view('admin/dataSiswa', compact("users", "request"));
    }

    public function exportData(Request $request, $nis)
    {
        $siswa = User::where('nis', $nis)->firstOrFail();
        $pembayarans = Pembayaran::where('user_id', $siswa->id)
            ->with(['jenisPembayaran', 'cicilans'])
            ->get();
        $namaSiswa = $siswa->name;

        $totalKekurangan = 0;
        foreach ($pembayarans as $pembayaran) {
            if ($pembayaran->status_pembayaran === 'Belum Lunas') {
                $totalCicilan = $pembayaran->cicilans->sum('nominal');
                $selisih = $pembayaran->jenisPembayaran->nominal - $totalCicilan;
                $totalKekurangan += max($selisih, 0);
            }
        }

        //dd($totalKekurangan);

        // Log Act
        $this->logAktivitas('Export Data Pembayaran Siswa', 'Export data pembayaran siswa dengan NIS ' . $nis . ' atas nama ' . $namaSiswa . '.');

        $export = new RekapExport($pembayarans, $namaSiswa, $totalKekurangan);

        return Excel::download($export, $export->filename());
    }

    public function tambahDataSiswa()
    {
        $kelas = Kelas::all();
        return view('admin/tambahDataSiswa', compact('kelas'));
    }

    public function storeDataSiswa(Request $request)
    {
        //dd($request->all());
        $data['nis'] = $request->nis;
        $data['name'] = $request->name;
        $data['alamat'] = $request->alamat;
        $data['id_kelas'] = $request->id_kelas;
        $data['role'] = "siswa";
        $data['email'] = $request->nis . '@gmail.com';
        $data['password'] = $request->nis;
        $data['status_siswa'] = $request->status_siswa;

        User::create($data);

        $nis = $request->nis;
        $namaSiswa = $request->name;

        // Log Act
        $this->logAktivitas('Tambah Data Siswa', 'Tambah data siswa dengan NIS ' . $nis . ' atas nama ' . $namaSiswa . '.');

        return redirect()->route('dataSiswaAdmin')->with('success', 'Data siswa berhasil ditambah.');

    }
    public function rekapDataSiswa(Request $request, $nis)
    {
        $data = User::where('nis', $nis)->firstOrFail();
        $kelas = Kelas::all();
        $jenisPembayaran = JenisPembayaran::all();

        $tahunAjaranList = Pembayaran::where('user_id', $data->id)
            ->select('tahun_ajaran')
            ->distinct()
            ->pluck('tahun_ajaran'); //array

        $tahunAjaranAktif = config('app.tahun_ajaran_aktif');

        // For total kekurangan (collection format)
        $pembayaranSemua = Pembayaran::where('user_id', $data->id)
            ->with('jenisPembayaran', 'cicilans')
            ->get();

        $totalKekurangan = 0;
        foreach ($pembayaranSemua as $pembayaran) {
            if ($pembayaran->status_pembayaran === 'Belum Lunas') {
                $totalCicilan = $pembayaran->cicilans->sum('nominal');
                $selisih = $pembayaran->jenisPembayaran->nominal - $totalCicilan;
                $totalKekurangan += max($selisih, 0); // Hindari hasil negatif
            }
        }

        // For page rekap 
        $pembayaranQuery = Pembayaran::where('user_id', $data->id)
            ->with('jenisPembayaran', 'cicilans');

        // Filter Jenis Pembayaran
        if ($request->has('jenisPembayaran') && $request->jenisPembayaran != '') {
            $pembayaranQuery->where('id_jenis_pembayaran', $request->jenisPembayaran);
        }


        // Filter Tahun_Ajaran
        if ($request->has('tahunAjaran') && $request->tahunAjaran != '') {
            $pembayaranQuery->where('tahun_ajaran', $request->tahunAjaran);
        } else {
            // Default:tahun ajaran aktif
            $pembayaranQuery->where('tahun_ajaran', $tahunAjaranAktif);
            $request->merge(['tahunAjaran' => $tahunAjaranAktif]); // selected at Blade
        }

        $pembayarans = $pembayaranQuery->paginate(10)->appends($request->query());

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

        return view('rekapPembayaran', compact('data', 'kelas', 'jenisPembayaran', 'pembayarans', 'prediksiMap', 'tahunAjaranList', 'totalKekurangan'));
    }
    public function editDataSiswaAdmin($nis)
    {
        $data = User::where('nis', $nis)->firstOrFail();
        $kelas = Kelas::all();
        return view('admin/editDataSiswa', compact('data', 'kelas'));
    }

    public function updateDataSiswa(Request $request, $nis)
    {
        $data = User::where('nis', $nis)->firstOrFail();

        $data->nis = $request->nis;
        $data->name = $request->name;
        $data->alamat = $request->alamat;
        $data->status_siswa = $request->status_siswa;

        $data->save();

        $nis = $request->nis;
        $namaSiswa = $request->name;

        // Log Act
        $this->logAktivitas('Edit Data Siswa', 'Edit data siswa dengan NIS ' . $nis . ' atas nama ' . $namaSiswa . '.');

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

        // Log Act
        $this->logAktivitas('Delete Data Siswa', 'Delete data siswa dengan NIS ' . $nis . '.');

        return redirect()->route('dataSiswaAdmin')->with('success', 'Data siswa berhasil dihapus.');
    }

    public function deleteDataRekapSiswa(Request $request)
    {
        $idPembayaran = $request->input('delete_pembayaran');
        $pembayaran = Pembayaran::where('id', $idPembayaran)->first();

        if (!$pembayaran) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        if ($pembayaran->users) {
            $nis = $pembayaran->users->nis;

            // Delete Data
            $pembayaran->delete();

            // Log Act
            $this->logAktivitas('Delete Rekap Pembayaran', 'Delete rekap pembayaran siswa dengan NIS ' . $nis . ' id pembayaran ' . $idPembayaran . '.');

            return response()->json(['success' => 'Data rekap pembayaran berhasil dihapus.', 'nis' => $nis]);
        } else {
            return response()->json(['error' => 'Data pembayaran tidak terkait dengan data siswa yang valid.'], 400);
        }
    }

    public function destroy(Request $request)
    {
        $cicilan = Cicilan::findOrFail($request->id_cicilan);
        $cicilan->delete();

        return back()->with('success', 'Cicilan berhasil dihapus.');
    }

    public function detailCicilan($id_pembayaran): JsonResponse
    {
        try {
            $cicilan = Cicilan::where('id_pembayaran', $id_pembayaran)
                ->orderBy('tanggal_bayar')
                ->get();

            return response()->json($cicilan);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan server saat mengambil data cicilan.'], 500);
        }
    }

    public function tambahCicilan(Request $request)
    {
        $request->validate([
            'id_pembayaran' => 'required|exists:pembayaran,id',
            'nominal' => 'required|numeric|min:1',
            'tanggal_bayar' => 'required|date',
        ]);

        Cicilan::create([
            'id_pembayaran' => $request->id_pembayaran,
            'nominal' => $request->nominal,
            'tanggal_bayar' => $request->tanggal_bayar,
        ]);

        $pembayaran = Pembayaran::findOrFail($request->id_pembayaran);

        if ($pembayaran->users) {
            $nis = $pembayaran->users->nis;
            return response()->json(['success' => true, 'nis' => $nis]);
        } else {
            return response()->json(['success' => false, 'message' => 'Gagal mendapatkan informasi siswa terkait pembayaran.']);
        }
    }

    public function detailCicilanSiswa($id_pembayaran): JsonResponse
    {
        try {
            $cicilan = Cicilan::where('id_pembayaran', $id_pembayaran)
                ->orderBy('tanggal_bayar')
                ->get();

            return response()->json($cicilan);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan server saat mengambil data cicilan.'], 500);
        }
    }

    public function updateCicilan(Request $request)
    {
        $cicilan = Cicilan::findOrFail($request->input('id_cicilan'));
        $cicilan->nominal = $request->nominal;
        $cicilan->tanggal_bayar = $request->tanggal_bayar;
        $cicilan->save();

        // Log Act
        $this->logAktivitas('Edit Data Cicilan', 'Edit data cicilan dengan id cicilan ' . $cicilan->id . '.');

        return response()->json(['message' => 'Data cicilan berhasil diperbarui.']);
    }
    public function detailPembayaran($id): JsonResponse
    {
        $pembayaran = Pembayaran::findOrFail($id);

        return response()->json($pembayaran);
    }

    public function updateDataRekapSiswa(Request $request)
    {
        $pembayaran = Pembayaran::findOrFail($request->id_pembayaran);
        $pembayaran->status_pembayaran = $request->status_pembayaran;
        $pembayaran->tanggal_lunas = $request->tanggal_lunas;
        $nis = $pembayaran->users->nis;
        $pembayaran->save();

        // Log Act
        $this->logAktivitas('Edit Data Rekap Siswa', 'Edit data rekap siswa dengan NIS ' . $nis . ' id pembayaran ' . $pembayaran->id . '.');

        return response()->json(['success' => 'Data rekap pembayaran berhasil diperbarui.', 'nis' => $nis]);
    }

    public function dataSiswaGuru(Request $request)
    {
        // Only get data Siswa
        $users = User::with("kelas")->whereNotNull("nis")->leftjoin('kelas', 'users.id_kelas', '=', 'kelas.id_kelas')
            ->orderBy('kelas.tingkat_kelas', 'asc') // From X to XII
            ->select('users.*');

        // Filter by Name, NIS, Jurusan
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

        // Paginate
        $users = $users->paginate(10)->appends($request->all());

        return view('guru/dataSiswa', compact("users", "request"));
    }

    public function importExcel(request $request)
    {
        ini_set('max_execution_time', 300); // 5 minute max

        $file = $request->file("file");
        $namaFile = rand() . $file->getClientOriginalName();

        // Put file in path  public/DataSiswa
        $file->move("DataSiswaExcel", $namaFile);

        Excel::import(new SiswaImport, public_path("/DataSiswaExcel/" . $namaFile));

        // Log Act
        $this->logAktivitas('Import Data Siswa', 'Import data siswa bama file ' . $namaFile);

        return redirect("/dataSiswaAdmin")->with('success', 'Data siswa berhasil diimpor.');
        ;
    }
}
