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
    private function hitungFitur($user)
    {
        // Get all pembayaran each user
        $riwayatPembayaran = $user->pembayarans()->with('jenisPembayaran')->get();

        // a. Jumlah Status Pembayaran Lunas
        $jumlahLunas = $riwayatPembayaran->where('status_pembayaran', 'Lunas')->count();

        // b. Jumlah Pembayaran Lunas & Tepat Waktu
        $jumlahTepatWaktu = $riwayatPembayaran->filter(function ($pembayaran) {
            return $pembayaran->status_pembayaran == 'Lunas' &&
                $pembayaran->tanggal_lunas <= $pembayaran->jenisPembayaran->tenggat_waktu;
        })->count();

        // c. Frekuensi Terlambat bayar
        $frekuensiTelat = 0;
        foreach ($riwayatPembayaran->where('status_pembayaran', 'Lunas') as $pembayaran) {
            $tanggalLunas = \Carbon\Carbon::parse($pembayaran->tanggal_lunas);

            if ($pembayaran->jenisPembayaran->periode === 'bulanan') {
                $tanggalDeadline = $tanggalLunas->copy()->day($pembayaran->jenisPembayaran->tanggal_bulanan);
                if ($tanggalLunas->gt($tanggalDeadline)) {
                    $frekuensiTelat++;
                }
            } else {
                if ($tanggalLunas->gt(\Carbon\Carbon::parse($pembayaran->jenisPembayaran->tenggat_waktu))) {
                    $frekuensiTelat++;
                }
            }
        }



        // d. Frekuensi Cicilan 
        $frekuensiCicilan = \App\Models\Cicilan::whereIn('id_pembayaran', $riwayatPembayaran->pluck('id'))->count();

        // e. Nominal Tunggakan 
        $nominalTunggakan = 0;
        $pembayaranBelumLunasSemua = $riwayatPembayaran->where('status_pembayaran', 'Belum Lunas');

        foreach ($pembayaranBelumLunasSemua as $pembayaran) {
            $totalDibayar = \App\Models\Cicilan::where('id_pembayaran', $pembayaran->id)->sum('nominal');
            $sisa = $pembayaran->jenisPembayaran->nominal - $totalDibayar;
            $nominalTunggakan += max(0, $sisa);
        }

        // f. Rata-rata hari dari pembayaran yang terlambat
        $totalHariTelat = 0;
        $jumlahPembayaranTelat = 0;
        foreach ($riwayatPembayaran->where('status_pembayaran', 'Lunas') as $pembayaran) {
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
        $totalPembayaranLunas = $riwayatPembayaran->where('status_pembayaran', 'Lunas')->count();
        $proporsiTepatWaktu = ($totalPembayaranLunas > 0) ? $jumlahTepatWaktu / $totalPembayaranLunas : 0;

        // h. Rasio Pembayaran Terlambat
        $rasioTelat = ($totalPembayaranLunas > 0) ? $frekuensiTelat / $totalPembayaranLunas : 0;

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
            //return $response->json();

            if ($response->successful()) {
                return $response->json();
            } else {
                Log::error("Gagal memprediksi batch siswa: " . $response->body());
                return null;
            }
        } catch (\Exception $e) {
            Log::error("Error saat menghubungi API untuk prediksi batch: " . $e->getMessage()); // trouble connection
            return null;
        }
    }

    public function prediksiSemuaSiswa()
    {
        // Get user has relation with pembayaran
        $users = User::has('pembayarans')->get();

        $fiturBatch = [];
        $prediksiMap = [];

        foreach ($users as $user) {
            $fiturBatch[$user->id] = $this->hitungFitur($user);
        }
        //dd($fiturBatch);

        $hasilBatch = $this->prediksiBatchSiswa(array_values($fiturBatch));
        //dd($hasilBatch);

        if ($hasilBatch && isset($hasilBatch['predictions'])) {
            //$i = 0;
            foreach ($users as $index => $user) {
                $prediksiMap[$user->id] = [
                    'user_id' => $user->id,
                    'prediksi' => $hasilBatch['predictions'][$index]['prediksi'],
                    'probabilitas' => $hasilBatch['predictions'][$index]['probabilitas'],
                ];
            }

        }

        return response()->json(['status' => 'success', 'data' => array_values($prediksiMap)]);
    }

}