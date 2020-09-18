<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgramKursusInstruktur extends Model
{
    use SoftDeletes;
    
    protected $guarded = [
        'id'
    ];
    
    public function programkursuses()
    {
        return $this->belongsTo('App\ProgramKursus', 'id_program_kursus', 'id');
    }

    public function jadwals()
    {
        return $this->hasOne('App\Jadwal','id_program_kursus_instruktur','id');
    }

    public function instrukturs()
    {
        return $this->belongsTo('App\Instruktur','id_instruktur','id');
    }
}
