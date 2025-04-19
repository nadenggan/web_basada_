<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\JenisPembayaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PrediksiController extends Controller
{
    private function hitungFitur(Pembayaran $pembayaran)
    {
        $userId = $pembayaran->user_id;

        // Gett all pembayaran
        $riwayatPembayaran = Pembayaran::where('user_id', $userId)
            ->with('jenisPembayaran')
            ->get();

        // a. Jumlah Status Pembayaran Lunas
        $jumlahLunas = $riwayatPembayaran->where('status_pembayaran', 'lunas')->count();

        // b. Jumlah Pembayaran Tepat Waktu
        $jumlahTepatWaktu = $riwayatPembayaran->filter(function ($pembayaran) {
            return $pembayaran->status_pembayaran == 'lunas' &&
                $pembayaran->tanggal_lunas <= $pembayaran->jenisPembayaran->tenggat_waktu;
        })->count();

        // c. Frekuensi Terlambat bayar
        $jumlahTelat = $riwayatPembayaran->where('status_pembayaran', 'lunas')
            ->filter(function ($pembayaran) {
                return $pembayaran->tanggal_lunas > $pembayaran->jenisPembayaran->tenggat_waktu;
            })->count();
        $frekuensiTelat = $jumlahTelat;

        // d. Frekuensi Cicilan
        $frekuensiCicilan = \App\Models\Cicilan::whereHas('pembayaran', function ($query) use ($pembayaran) {
            $query->where('id', $pembayaran->id);
        })->count();

        // e. Nominal Tunggakan
        $nominalTunggakan = 0;
        if ($pembayaran->status_pembayaran == 'belum lunas') {
            $totalDibayar = \App\Models\Cicilan::where('id_pembayaran', $pembayaran->id)->sum('nominal');
            $nominalTunggakan = $pembayaran->jenisPembayaran->nominal - $totalDibayar;
            $nominalTunggakan = max(0, $nominalTunggakan); // Not negative
        }

        // f. Rata-rata hari dari pembayaran yang terlambat
        $totalHariTelat = 0;
        $jumlahPembayaranTelat = 0;
        foreach ($riwayatPembayaran->where('status_pembayaran', 'lunas') as $pembayaran) {
            if ($pembayaran->tanggal_lunas > $pembayaran->jenisPembayaran->tenggat_waktu) {
                $tenggatWaktu = \Carbon\Carbon::parse($pembayaran->jenisPembayaran->tenggat_waktu);
                $tanggalLunas = \Carbon\Carbon::parse($pembayaran->tanggal_lunas);
                $selisihHari = $tanggalLunas->diffInDays($tenggatWaktu);
                $totalHariTelat += $selisihHari;
                $jumlahPembayaranTelat++;
            }
        }
        $rataRataHariTelat = ($jumlahPembayaranTelat > 0) ? $totalHariTelat / $jumlahPembayaranTelat : 0;

        // g. Proporsi Pembayaran Tepat Waktu
        $totalPembayaranLunas = $riwayatPembayaran->where('status_pembayaran', 'lunas')->count();
        $proporsiTepatWaktu = ($totalPembayaranLunas > 0) ? $jumlahTepatWaktu / $totalPembayaranLunas : 0;

        // h. Rasio Pembayaran Terlambat
        $rasioTelat = ($totalPembayaranLunas > 0) ? $jumlahTelat / $totalPembayaranLunas : 0;

        return [
            'status_lunas' => $jumlahLunas,
            'tepat_waktu' => $jumlahTepatWaktu,
            'frekuensi_telat' => $frekuensiTelat,
            'frekuensi_cicilan' => $frekuensiCicilan,
            'nominal_tunggakan' => $nominalTunggakan,
            'rata_hari_telat' => $rataRataHariTelat,
            'prop_tepat_waktu' => $proporsiTepatWaktu,
            'rasio_telat' => $rasioTelat,
        ];
    }

    public function prediksiBatchSiswa(array $fiturBatch)
    {
        try {
            $response = Http::post('http://127.0.0.1:8001/predict_batch', ['data' => $fiturBatch]);
            Log::info('Respons dari API: ' . $response->body());
            return $response->json();

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error("Gagal memprediksi batch siswa: " . $response->body());
                return null; 
            }
        } catch (\Exception $e) {
            Log::error("Error saat menghubungi API untuk prediksi batch: " . $e->getMessage());
            return null; 
        }
    }

    public function prediksiSemuaSiswa()
    {
        // Get all pembayaran
        $pembayarans = Pembayaran::with(['jenisPembayaran', 'users'])->get();

        $fiturBatch = [];
        $prediksiMap = [];

        foreach ($pembayarans as $pembayaran) {
            $fiturBatch[$pembayaran->id] = $this->hitungFitur($pembayaran);
        }
        //dd($fiturBatch);

        $hasilBatch = $this->prediksiBatchSiswa(array_values($fiturBatch));
        //dd($hasilBatch);

        if ($hasilBatch && isset($hasilBatch['predictions'])) {
            $i = 0;
            foreach ($pembayarans as $pembayaran) {
                  $prediksiMap[$pembayaran->user_id] = [
                    'id_pembayaran' => $pembayaran->id,
                    'prediksi' => $hasilBatch['predictions'][$i]['prediksi'],
                    'probabilitas' => $hasilBatch['predictions'][$i]['probabilitas'],
                    'user_id' => $pembayaran->user_id,
                ];
                $i++;
            }
        }

        return response()->json(['status' => 'success', 'data' => array_values($prediksiMap)]);
    }

}