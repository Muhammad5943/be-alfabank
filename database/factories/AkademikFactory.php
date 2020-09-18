<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Akademik;
use Faker\Generator as Faker;

$factory->define(Akademik::class, function (Faker $faker) {
    
    static $number = 0;
    $number++;

    return [

        'nama'=>'AgusAlfabank',
        'email'=>'agusalfabank@gmail.com',
        'no_telp'=>'08458795857'. $number,
        'foto'=>'user.png',
        'password'=>bcrypt('123456789')
        
    ];
});
