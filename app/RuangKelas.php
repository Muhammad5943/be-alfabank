<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RuangKelas extends Model
{
    use SoftDeletes;

    protected $guarded = [
        'id'
    ];

    public function jadwals()
    {
        return $this->hasMany('App\Jadwal','id_ruang_kelas','id');
    }
}
