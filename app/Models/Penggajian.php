<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penggajian extends Model
{
    protected $table = 'penggajian';

    protected $guarded = ['id'];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
}
