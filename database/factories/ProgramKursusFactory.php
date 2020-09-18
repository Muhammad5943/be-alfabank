<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ProgramKursus;
use Faker\Generator as Faker;

$factory->define(ProgramKursus::class, function (Faker $faker) {
    static $total_pertemuan = 0;
    static $kuota = 0;
    static $harga = 1000000;
    /* $total_pertemuan++;
    $kuota++;
    $harga++; */

    return [
        'nama' => $faker->randomElement(
            [
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
            ]),

        'total_pertemuan' => rand(15,25),
        'kuota' => rand(5,10),
        'harga' => rand(1000000,7000000)  
    ];
});
