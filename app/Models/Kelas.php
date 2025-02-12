<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    
    protected $fillable = [
        'id_kelas',
        'tingkat_kelas',
        'jurusan',
    ];

    public function kelas()
{
    return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
}

}
