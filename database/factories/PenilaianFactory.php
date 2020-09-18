<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Kompetensi;
use App\Penilaian;
use App\Sertifikat;
use Faker\Generator as Faker;

$factory->define(Penilaian::class, function (Faker $faker) {
    return [
        'id_sertifikat' => Sertifikat::inRandomOrder()->pluck('id')->first(),
        'id_kompetensi' => Kompetensi::inRandomOrder()->pluck('id')->first(),
        'nilai' => $faker->randomElement(['100','90','80','70']),
    ];
});
