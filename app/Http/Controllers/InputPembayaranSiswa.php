<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\JenisPembayaran;
use App\Models\Pembayaran;
use App\Models\Cicilan;

class InputPembayaranSiswa extends Controller
{
   public function showInputPembayaran($nis)
   {
      $data = User::where('nis', $nis)->first();
      $jenis_pembayaran = JenisPembayaran::all();
      return view('inputPembayaranSiswa', compact('data', 'jenis_pembayaran'));
   }

   public function storePembayaranSiswa(Request $request)
   {
      $user = User::where('nis', $request->nis)->first();
      $jenisPembayaran = JenisPembayaran::findOrFail($request->id_jenis_pembayaran);

      $pembayaranData = [
         'user_id' => $user->id,
         'id_jenis_pembayaran' => $request->id_jenis_pembayaran,
         'status_pembayaran' => $request->status_pembayaran,
      ];

      // Store bulan if jenis pembayaran is bulanan
      $pembayaranData['bulan'] = $jenisPembayaran->periode === 'bulanan' ? $request->bulan : null;

      // Cicilan option
      if ($request->status_pembayaran === "belum lunas") {
         // Create Pembayaran record
         $pembayaran = Pembayaran::create($pembayaranData);

         // Create Cicilan record
         Cicilan::create([
            'id_pembayaran' => $pembayaran->id,
            'nominal' => $request->nominal_cicilan,
            'tanggal_bayar' => $request->tanggal_bayar,
         ]);

      } else {
         // Create Pembayaran record
         $pembayaranData['tanggal_lunas'] = $request->tanggal_lunas;
         Pembayaran::create($pembayaranData);
      }


      return redirect()->route('home');
   }
}