<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departemen extends Model
{
    protected $table = 'departemen';

    //Mengizinkan mass-assigment
    protected $fillable = [
        'kode',
        'nama',
    ];

    /**
     *Realasi One-to-many: 1 Departemen bisa punya banyak jabatan*
     */

     public function jabatan()
     {
        return $this->hasMany(Jabatan::class);
     }
    
}
