<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cicilan extends Model
{
    protected $table = "cicilan";

    protected $fillable =[
        'id',
        'id_pembayaran',
        'nominal',
        'tanggal_bayar',
    ];

    public function pembayaran(){
        return $this->belongsTo(Pembayaran::class,'id_pembayaran','id');
    }
}
