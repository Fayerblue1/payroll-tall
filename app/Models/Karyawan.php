<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $table = 'karyawan';

    protected $guarded = ['id'];

    public function departemen()
    {
        return $this->belongsTo(Departemen::class);
    }
    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }
    public function penggajian()
    {
        return $this->hasMany(Penggajian::class);
    }

}
