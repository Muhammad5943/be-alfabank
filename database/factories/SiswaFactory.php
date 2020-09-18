<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Siswa;
use Faker\Generator as Faker;

$factory->define(Siswa::class, function (Faker $faker) {
    
    static $number = 0;
    $number++;

    return [
        'nama' => $faker->name,
        'email' => $faker->unique()->email,
        'no_telp' => '08125469887'. $number,
        'alamat_lengkap' => $faker->address,
        'kota' => $faker->city,
        'provinsi' => 'Jawa Tengah',
        'foto' => 'user.png'
    ];
});
