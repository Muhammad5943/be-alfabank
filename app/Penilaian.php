<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penilaian extends Model
{
    use SoftDeletes;
    
    protected $guarded = [
        'id'
    ];
    
    public function sertifikats()
    {
        return $this->belongsTo('App\Sertifikat','id_sertifikat','id');
    }

    public function kompetensis()
    {
        return $this->belongsTo('App\Kompetensi','id_kompetensi','id');
    }
}
