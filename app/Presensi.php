<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Presensi extends Model
{
    use SoftDeletes;
    
    protected $guarded = [
        'id'
    ];
    
    public function pendaftarans()
    {
        return $this->belongsTo('App\Pendaftaran','id_pendaftaran','id');
    }

    public function jadwals()
    {
        return $this->belongsTo('App\Jadwal','id_jadwal','id');
    }
}
