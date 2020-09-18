<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Siswa extends Model
{
    use SoftDeletes;
    
    protected $guarded = [
        'id'
    ];
    
    public function pendaftarans()
    {
        return $this->hasMany('App\Pendaftaran','id_siswa','id');
    }

    public function detail_siswas()
    {
        return $this->hasOne('App\DetailSiswa','id_siswa','id');
    }
}
