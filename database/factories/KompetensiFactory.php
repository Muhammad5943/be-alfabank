<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Kompetensi;
use App\ProgramKursus;
use Faker\Generator as Faker;

$factory->define(Kompetensi::class, function (Faker $faker) {
    return [
        'id_program_kursus' => ProgramKursus::inRandomOrder()->pluck('id')->first(),
        'nama' => $faker->randomElement(['WDP','Arsitek','Administrasi','Desain_Grafis','English_Conversation','Akutansi'])  
    ];
});
