<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Pendaftaran;
use App\Sertifikat;
use Faker\Generator as Faker;

$factory->define(Sertifikat::class, function (Faker $faker) {
    
    static $kode_sertifikat = 0001;
    $kode_sertifikat++;
    
    return [
        'id_pendaftaran' => Pendaftaran::inRandomOrder()->pluck('id')->first(),
        'kode_sertifikat' => "ALFA/2020/XCDM".$kode_sertifikat
    ];
});
