<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kompetensi extends Model
{
    use SoftDeletes;

    protected $guarded = [
        'id'
    ];

    public function penilaians()
    {
        return $this->hasMany('App\Penilaian', 'id_kompetensi', 'id');
    }

    public function programkursuses()
    {
        return $this->belongsTo('App\ProgramKursus', 'id_program_kursus', 'id');
    }
}
