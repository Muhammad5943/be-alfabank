<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Jadwal;
use App\ProgramKursusInstruktur;
use App\RuangKelas;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Jadwal::class, function (Faker $faker) {
    return [
        'id_program_kursus_instruktur' => ProgramKursusInstruktur::inRandomOrder()->limit(1)->pluck('id')->first(),
        'id_ruang_kelas' => RuangKelas::inRandomOrder()->limit(1)->pluck('id')->first(),
        'materi' => $faker->sentence($nbWords = 2, $variableNbWords = true),
        // 'tanggal' => $faker->dateTimeBetween($startDate = '-1 years', $endDate = '+1 years', $timezone = null),
        'tanggal' => $faker->randomElement([$faker->dateTimeBetween($startDate = '-1 years', $endDate = '+1 years', $timezone = null),
                        $faker->dateTimeBetween($startDate = '-1 years', $endDate = '+1 years', $timezone = null),
                        $faker->dateTimeBetween($startDate = '-1 years', $endDate = '+1 years', $timezone = null),
                        $faker->dateTimeBetween($startDate = '-1 years', $endDate = '+1 years', $timezone = null),
                        $faker->dateTimeBetween($startDate = '-1 years', $endDate = '+1 years', $timezone = null),
                        $faker->dateTimeBetween($startDate = '-1 years', $endDate = '+1 years', $timezone = null),
                        Carbon::now()]),
        
        'waktu_mulai' => $faker->time(),
        'waktu_berakhir' => $faker->time()
    ];
});
