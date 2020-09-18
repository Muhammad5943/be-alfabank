<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailSiswa extends Model
{
    use SoftDeletes;

    protected $guarded = [
        'id'
    ];

    public function siswas()
    {
        return $this->belongsTo('App\Siswa','id_siswa','id');
    }
}
