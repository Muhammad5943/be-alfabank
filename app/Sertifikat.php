<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sertifikat extends Model
{
    use SoftDeletes;
    
    protected $guarded = [
        'id'
    ];
    
    public function pendaftarans()
    {
        return $this->belongsTo('App\Pendaftaran','id_pendaftaran','id');
    }

    public function penilaians()
    {
        return $this->hasMany('App\Penilaian','id_sertifikat','id');
    }
}
