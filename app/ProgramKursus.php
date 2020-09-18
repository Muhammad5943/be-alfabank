<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgramKursus extends Model
{
    use SoftDeletes;

    protected $guarded = [
        'id'
    ];
    
    public function pendaftarans()
    {
        return $this->hasMany('App\Pendaftaran','id_program_kursus','id');
    }

    public function kompetensis()
    {
        return $this->hasMany('App\Kompetensi','id_program_kursus','id');
    }

    public function programkursusinstrukturs()
    {
        return $this->hasMany('App\ProgramKursusInstruktur','id_program_kursus','id');
    }
}
