<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Jadwal;
use App\Pendaftaran;
use App\Presensi;
use Faker\Generator as Faker;

$factory->define(Presensi::class, function (Faker $faker) {
    return [
        'id_pendaftaran' => Pendaftaran::inRandomOrder()->pluck('id')->first(),
        'id_jadwal' => Jadwal::inRandomOrder()->pluck('id')->first(),
        'materi_tersampaikan' => $faker->sentence($nbWords = 2, $variableNbWords = true),
        'waktu_mulai' => $faker->time(),
        'waktu_berakhir' => $faker->time()
    ];
});
