<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aktivitas;

class LogAktivitasController extends Controller
{
    public function logAktivitas(){
        $aktivitas = Aktivitas::with('users')->orderByDesc('waktu_kegiatan')->paginate(15);
        return view('admin/logAktivitas', compact('aktivitas'));
    
    }

    public function deleteAktivitas(Request $request)
    {
        $id = $request->input('delete_id');
        $aktivitas = Aktivitas::findOrFail($id);

        if (!$aktivitas) {
            return redirect()->route('logAktivitas')->with('error', 'Aktivitas tidak ditemukan.');
        }

        // Delete Data
        $aktivitas->delete();

        return redirect()->route('logAktivitas')->with('success', 'Aktivitas berhasil dihapus.');
    }
}
