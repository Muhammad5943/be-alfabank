<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\RuangKelas;
use Faker\Generator as Faker;

$factory->define(RuangKelas::class, function (Faker $faker) {
    return [
        'ruang_kelas' => $faker->randomElement([
            'Web Development And Design',
            'Desain Grafis',
            'Perbankan',
            'Komputer Akutansi',
            'English Conversation',
            'Professional Office',
            'Teknisi Komputer Jaringan',
            'Rancangan Bangunan',
            'Komputer Arsitek',
            'Admin Perbankan Dan Keuangan',
            'Admin Perkantoran',
            'Professional Web Development Programmer',
            'Digital Marketing'
        ])  
    ];
});
