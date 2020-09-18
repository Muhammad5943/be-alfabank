<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Instruktur extends Model
{

    use SoftDeletes;
    
    protected $guarded = [
        'id'
    ];
    
    public function programkursusinstrukturs()
    {
        return $this->hasMany('App\ProgramKursusInstruktur', 'id_instruktur', 'id');  
    }
}