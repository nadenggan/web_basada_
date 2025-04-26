<?php
namespace App\Imports;

use App\Models\Pembayaran;
use App\Models\User;
use App\Models\JenisPembayaran;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use App\Models\Cicilan;
use Maatwebsite\Excel\Facades\Excel;

class PembayaranImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */


    public function model(array $row)
    {
        // Row is empty
        if (empty($row['nis']) && empty($row['jenis_pembayaran']) && empty($row['status_lunas']) && empty($row['nominal_cicilan']) && empty($row['tanggal_lunas']) && empty($row['tanggal_bayar'])) {
            return null;
        }

        $user = User::where('nis', $row['nis'])->first();
        $jenisPembayaran = JenisPembayaran::where('nama_jenis_pembayaran', $row['jenis_pembayaran'])->first();

        if ($user && $jenisPembayaran) {
            $pembayaranData = [
                'user_id' => $user->id,
                'id_jenis_pembayaran' => $jenisPembayaran->id,
                'status_pembayaran' => $row['status_lunas'] == 'L' ? 'lunas' : 'belum lunas', // Sesuaikan
                'tanggal_lunas' => $row['tanggal_lunas'] ?? null,
                'tahun_ajaran' => $row['tahun_ajaran'],
            ];

            // If column 'bulan' available
            if (isset($row['bulan'])) {
                $pembayaranData['bulan'] = $row['bulan'];
            }

            $pembayaran = new Pembayaran($pembayaranData);
            $pembayaran->save();


            // Cicilan just 1
            if (isset($row['nominal_cicilan']) && !empty($row['nominal_cicilan']) && isset($row['tanggal_bayar']) && !empty($row['tanggal_bayar'])) {
                $cicilanSudahDibuat = Cicilan::where('id_pembayaran', $pembayaran->id)
                    ->where('nominal', $row['nominal_cicilan'])
                    ->where('tanggal_bayar', $row['tanggal_bayar'])
                    ->exists();

                if (!$cicilanSudahDibuat) {
                    Cicilan::create([
                        'id_pembayaran' => $pembayaran->id,
                        'nominal' => $row['nominal_cicilan'],
                        'tanggal_bayar' => $row['tanggal_bayar'],
                    ]);
                }
            }

            // Cicilan more than 1 
            foreach ($row as $key => $value) {
                if (strpos(strtolower($key), 'nominal_cicilan_') === 0 && !empty($value)) {
                    $cicilanIndex = str_replace('nominal_cicilan_', '', strtolower($key));
                    $tanggalBayarKey = 'tanggal_bayar_' . $cicilanIndex;

                    if (isset($row[$tanggalBayarKey]) && !empty($row[$tanggalBayarKey])) {
                        Cicilan::create([
                            'id_pembayaran' => $pembayaran->id,
                            'nominal' => $value,
                            'tanggal_bayar' => $row[$tanggalBayarKey],
                        ]);
                    }
                }
            }

            return $pembayaran;
        }
        return null;
    }

    public function headingRow(): int
    {
        return 1;
    }

    public function rules(): array
    {
        $rules = [
            'nis' => 'required_with:jenis_pembayaran,status_lunas',
            'jenis_pembayaran' => 'required_with:nis,status_lunas',
            'status_lunas' => 'required_with:nis,jenis_pembayaran',
            'tanggal_lunas' => 'nullable|date_format:Y-m-d',
        ];

        // Validation nominal_cicilan & tanggal_bayar
        foreach (request()->all() as $key => $value) {
            if (strpos(strtolower($key), 'nominal_cicilan') === 0) {
                $rules[$key] = 'nullable|numeric';
            }
            if (strpos(strtolower($key), 'tanggal_bayar') === 0) {
                $rules[$key] = 'nullable|date_format:Y-m-d';
            }
        }

        // column 'bulan' validation
        if (request()->hasFile('file')) {
            $import = new static();
            $headings = array_map('strtolower', $import->headingRow() ? Excel::toArray($import, request()->file('file'))[0][0] : []);
            if (in_array('bulan', $headings)) {
                $rules['bulan'] = 'nullable|string|in:Januari,Februari,Maret,April,Mei,Juni,Juli,Agustus,SeptEmber,Oktober,November,Desember';
            }
        }

        return $rules;
    }

    public function customValidationMessages()
    {
        $messages = [
            'nis.required_with' => 'Kolom NIS wajib diisi jika kolom Jenis Pembayaran atau Status Lunas diisi.',
            'jenis_pembayaran.required_with' => 'Kolom Jenis Pembayaran wajib diisi jika kolom NIS atau Status Lunas diisi.',
            'status_lunas.required_with' => 'Kolom Status Lunas wajib diisi jika kolom NIS atau Jenis Pembayaran diisi.',
            'tanggal_lunas.date_format' => 'Format Tanggal Lunas tidak valid (YYYY-MM-DD).',
        ];

        // Message validation nominal_cicilan dan tanggal_bayar
        foreach (request()->all() as $key => $value) {
            if (strpos(strtolower($key), 'nominal_cicilan') === 0) {
                $messages[$key . '.numeric'] = 'Kolom ' . $key . ' harus berupa angka.';
            }
            if (strpos(strtolower($key), 'tanggal_bayar') === 0) {
                $messages[$key . '.date_format'] = 'Format Kolom ' . $key . ' tidak valid (YYYY-MM-DD).';
            }
        }

        if (request()->hasFile('file')) {
            $import = new static();
            $headings = array_map('strtolower', $import->headingRow() ? Excel::toArray($import, request()->file('file'))[0][0] : []);
            if (in_array('bulan', $headings)) {
                $messages['bulan.string'] = 'Kolom Bulan harus berupa teks.';
            }
        }

        return $messages;
    }
}