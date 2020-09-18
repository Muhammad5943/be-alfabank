<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Instruktur;
use App\ProgramKursus;
use App\ProgramKursusInstruktur;
use Faker\Generator as Faker;

$factory->define(ProgramKursusInstruktur::class, function (Faker $faker) {
    return [
        'id_program_kursus' => ProgramKursus::inRandomOrder()->pluck('id')->first(),
        'id_instruktur' => Instruktur::inRandomOrder()->pluck('id')->first()
    ];
});
