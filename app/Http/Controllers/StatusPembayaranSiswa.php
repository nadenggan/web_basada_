<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\JenisPembayaran;

use Illuminate\Http\Request;

class StatusPembayaranSiswa extends Controller
{
    public function statusPembayaranSiswa(Request $request)
    {
        $users = User::with("kelas")->whereNotNull("nis");
        $jenisPembayaran = JenisPembayaran::all();
        $selectedJenisPembayaranId = $request->get('jenisPembayaran');
        $selectedBulan = $request->get('bulan');
        $showBulanFilter = false;
        $bulanList = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];


         // Selected the top jenisPembayaran
         if (!$selectedJenisPembayaranId && $jenisPembayaran->isNotEmpty()) {
            $selectedJenisPembayaranId = $jenisPembayaran->first()->id;
            $request->merge(['jenisPembayaran' => $selectedJenisPembayaranId]);
        }

        // Filter bulan 
        if ($selectedJenisPembayaranId) {
            $filteredJenisPembayaran = JenisPembayaran::find($selectedJenisPembayaranId);
            if ($filteredJenisPembayaran && $filteredJenisPembayaran->periode === 'bulanan') {
                $showBulanFilter = true;
            }
        }

        // Filter by Name, NIS, Jurusan, Status
        if ($request->get('search')) {
            $search = $request->get('search');
        
            $users->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('nis', 'LIKE', '%' . $search . '%')
                    ->orWhereHas('kelas', function ($kelasQuery) use ($search) {
                        $kelasQuery->where('jurusan', 'LIKE', '%' . $search . '%');
                    })
                    ->orWhereHas('pembayarans', function ($pembayaranQuery) use ($search) {
                        $pembayaranQuery->where('status_pembayaran', 'LIKE', '%' . $search . '%');
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

        // Load pembayaran for each user based on the selected jenis pembayaran
        $users->each(function ($user) use ($selectedJenisPembayaranId, $selectedBulan) {
            $user->load(['pembayarans' => function ($query) use ($selectedJenisPembayaranId, $selectedBulan) {
                if ($selectedJenisPembayaranId) {
                    $query->where('id_jenis_pembayaran', $selectedJenisPembayaranId);
                    if ($selectedBulan) {
                        $query->where('bulan', $selectedBulan);
                    }
                }
            }]);
        });

        return view('statusPembayaranSiswa', compact("users", "request","jenisPembayaran", "showBulanFilter", "bulanList" ));
    }
}
