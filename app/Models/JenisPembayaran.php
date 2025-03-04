<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisPembayaran extends Model
{
    protected $table = 'jenis_pembayaran';

    protected $fillable = [
        'id',
        'tingkat_kelas',
        'nama_jenis_pembayaran',
        'deskripsi',
        'nominal',
        'tenggat_waktu',
        'periode',
    ];

}
