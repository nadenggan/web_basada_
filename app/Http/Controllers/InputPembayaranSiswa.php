<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\JenisPembayaran;
use App\Models\Pembayaran;

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

      $data['user_id'] = $user->id;
      $data['id_jenis_pembayaran'] = $request->id_jenis_pembayaran;
      $data['tanggal_lunas'] = $request->tanggal_lunas;
      $data['status_pembayaran'] = $request->status_pembayaran;

      Pembayaran::create($data);

      return redirect()->route('home');
   }
}