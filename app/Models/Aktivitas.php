<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aktivitas extends Model
{
    protected $fillable = [
        'user_id',
        'kegiatan',
        'waktu_kegiatan',
        'deskripsi'
        ,
    ];


    protected $casts = [
        'waktu_kegiatan' => 'datetime', 
    ];
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

