<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Pendaftaran;
use App\ProgramKursus;
use App\Siswa;
use Faker\Generator as Faker;

$factory->define(Pendaftaran::class, function (Faker $faker) {
    return [
        'id_siswa' => Siswa::inRandomOrder()->limit(1)->pluck('id')->first(),
        'id_program_kursus' => ProgramKursus::inRandomOrder()->limit(1)->pluck('id')->first(),
        'status' => $faker->randomElement(['aktif','tidak_aktif','lulus','pending'])
    ];
});
