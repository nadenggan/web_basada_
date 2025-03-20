<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';
    protected $fillable = [
        'user_id',
        'id_jenis_pembayaran',
        'status_pembayaran',
        'tanggal_lunas'
    ];

    public function users(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function jenisPembayaran(){
        return $this->belongsTo(JenisPembayaran::class,'id_jenis_pembayaran','id');
    }
}
