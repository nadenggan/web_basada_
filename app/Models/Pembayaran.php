<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';
    protected $fillable = [
        'user_id',
        'id_jenis_pembayaran',
        'status_pembayaran',
        'tanggal_lunas',
        'bulan',
        'tahun_ajaran',
    ];

    public function users(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function jenisPembayaran(){
        return $this->belongsTo(JenisPembayaran::class,'id_jenis_pembayaran','id');
    }

    public function cicilans(): HasMany
    {
        return $this->hasMany(Cicilan::class, 'id_pembayaran');
    }
}
