<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

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
        'tanggal_bulanan',
    ];

      // Formatting for tanggal_bulanan
      public function dynamicTenggatWaktu(): Attribute
      {
          return Attribute::make(
              get: fn () => $this->periode === 'bulanan' && $this->tanggal_bulanan
                  ? now()->format('Y-m-') . str_pad($this->tanggal_bulanan, 2, '0', STR_PAD_LEFT)
                  : $this->tenggat_waktu
          );
      }

}
