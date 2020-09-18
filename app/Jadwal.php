<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jadwal extends Model
{
    use SoftDeletes;

    protected $guarded = [
        'id'
    ];
    
    public function programkursusinstrukturs()
    {
        return $this->belongsTo('App\ProgramKursusInstruktur', 'id_program_kursus_instruktur', 'id');
    }

    public function presensis()
    {
        return $this->hasMany('App\Presensi', 'id_jadwal', 'id');
    }

    public function ruangkelas()
    {
        return $this->belongsTo('App\RuangKelas','id_ruang_kelas','id');
    }
}
