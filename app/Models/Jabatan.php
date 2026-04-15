<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $table = 'jabatan';

    //Mengizinkan mass-assigment
    protected $fillable = [
        'departemen_id',
        'nama',
        'gaji_pokok',
    ];

    /**
     *Realasi Many-to-One: Banyak jabatan boleh dimiliki 1 Departemen*
     */

     public function departemen()
     {
        return $this->belongsTo(Departemen::class);
     }
}
