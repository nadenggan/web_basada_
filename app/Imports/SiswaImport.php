<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Kelas;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class SiswaImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        //dd($row);

        // Remove space from each value
        $row = array_change_key_case(array_map('trim', $row), CASE_LOWER);  

        $nis = $row["nis"] ?? null;
        $name = $row["nama"] ?? null;
        $tingkat_kelas = $row["tingkat_kelas"] ?? null;
        $jurusan = $row["jurusan"] ?? null;
        $alamat = $row["alamat"] ?? null;
        $status_siswa = $row["status_siswa"] ?? null;


        //dd($nis, $name, $alamat);

        // If there is an empty row
        if (empty($nis) && empty($name) && empty($alamat) && empty($tingkat_kelas) && empty($jurusan)) {
            return null; 
        }

        $id_kelas = null; // Default value for id_kelas

        // Only process kelas if status siswa is not 'lulus'
        if (strtolower($status_siswa) !== 'lulus') {
            $kelas = Kelas::where("tingkat_kelas", $tingkat_kelas)->where("jurusan", $jurusan)->first();

            // If there is new class
            if (!$kelas) {
                $id_kelas_baru = strtoupper($tingkat_kelas . "-" . str_replace(" ", "", $jurusan));

                $kelas = Kelas::create([
                    "id_kelas" => $id_kelas_baru,
                    "tingkat_kelas" => $tingkat_kelas,
                    "jurusan" => $jurusan
                ]);
            }
            $id_kelas = $kelas->id_kelas;
        }

        return new User([
            'nis' => $nis,
            'name' => $name,
            'role' => 'siswa',
            'email' => strtolower(str_replace(' ', '', $nis)) . '@example.com',
            'alamat' => $alamat,
            'password' => $nis,
            'id_kelas' => $id_kelas,
            'status_siswa' => $status_siswa,
        ]);
    }
}