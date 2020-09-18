<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pendaftaran extends Model
{
    use SoftDeletes;
    
    protected $guarded = [
        'id'
    ];
    
    public function siswas()
    {
        return $this->belongsTo('App\Siswa','id_siswa','id');
    }

    public function presensis()
    {
        return $this->hasMany('App\Presensi','id_pendaftaran','id');
    }

    public function sertifikats()
    {
    return $this->hasOne('App\Sertifikat','id_pendaftaran','id');
    }

    public function programkursuses()
    {
        return $this->belongsTo('App\ProgramKursus','id_program_kursus','id');
    }
}
