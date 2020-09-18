<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\FrontOffice;
use Faker\Generator as Faker;

$factory->define(FrontOffice::class, function (Faker $faker) {
    static $number = 0;
    $number++;

    return [

        'nama'=>'wijiAlfabank',
        'email'=>'wijialfabank@gmail.com',
        'no_telp'=>'08458795845'. $number,
        'foto'=>'user.png',
        'password'=>bcrypt('123456789')
        
    ];
});
