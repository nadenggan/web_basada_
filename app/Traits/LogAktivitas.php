<?php

namespace App\Traits;

use App\Models\Aktivitas;
use Illuminate\Support\Facades\Auth;

trait LogAktivitas
{
    protected function logAktivitas(string $kegiatan, ?string $deskripsi = null)
    {
        if (Auth::check()) {
            Aktivitas::create([
                'user_id' => Auth::id(),
                'kegiatan' => $kegiatan,
                'deskripsi' => $deskripsi,
            ]);
        } else {
            Aktivitas::create([
                'user_id' => null,
                'kegiatan' => $kegiatan,
                'deskripsi' => $deskripsi,
            ]);
        }
    }
}