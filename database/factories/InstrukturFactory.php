<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Instruktur;
use Faker\Generator as Faker;

$factory->define(Instruktur::class, function (Faker $faker) {
    
    static $number = 0;
    $number++;
    
    return [
        'nama' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'no_telp' => '08165487985'. $number,
        'alamat_lengkap' => $faker->address,
        'kota' => $faker->city,
        'provinsi' => 'Jawa Tengah',
        'foto' => 'user.png',
    ];
});
