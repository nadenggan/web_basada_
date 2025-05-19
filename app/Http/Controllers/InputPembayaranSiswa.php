<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\JenisPembayaran;
use App\Models\Pembayaran;
use App\Models\Cicilan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


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
         'tahun_ajaran' => $request->tahun_ajaran,
         'bulan' => $jenisPembayaran->periode === 'bulanan' ? $request->bulan : null,
      ];

      //Lunas
      if ($request->status_pembayaran === "Lunas") {
         $pembayaranData['tanggal_lunas'] = $request->tanggal_lunas;
      }

      // If it has proof of payment
      if ($request->hasFile('bukti_pembayaran')) {
         $file = $request->file('bukti_pembayaran');
         $filename = time() . '_' . $file->getClientOriginalName();
         $file->move(public_path('uploads/bukti_pembayaran'), $filename);
         $pembayaranData['bukti_pembayaran'] = 'uploads/bukti_pembayaran/' . $filename;
      }

      $pembayaran = Pembayaran::create($pembayaranData);

      // Belum Lunas
      if ($request->status_pembayaran === "Belum Lunas") {
         Cicilan::create([
            'id_pembayaran' => $pembayaran->id,
            'nominal' => $request->nominal_cicilan,
            'tanggal_bayar' => $request->tanggal_bayar,
         ]);
      }
      return redirect()->route('home');
   }

   public function uploadBukti(Request $request, $id)
   {
      //dd($request->all()); 
      $request->validate([
         'bukti_pembayaran' => 'required|file|mimes:jpg,jpeg,png|max:2048'
      ]);


      $pembayaran = Pembayaran::findOrFail($id);

      if ($request->hasFile('bukti_pembayaran')) {
         $file = $request->file('bukti_pembayaran');
         $filename = time() . '_' . $file->getClientOriginalName();

         $file->move(public_path('uploads/bukti_pembayaran'), $filename);
         $path = 'uploads/bukti_pembayaran/' . $filename;

         $pembayaran->bukti_pembayaran = $path;

         $pembayaran->save();
      }

      return redirect()->back()->with('success', 'Bukti pembayaran berhasil diupload.');
   }

}