<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\DetailSiswa;
use App\Siswa;
use Faker\Generator as Faker;

$factory->define(DetailSiswa::class, function (Faker $faker) {
    $no_ortu = 0;
    return [
        'id_siswa' => Siswa::inRandomOrder()->limit(1)->pluck('id')->first(),
        'tempat_lahir' => $faker->address,
        'tanggal_lahir' => $faker->date,
        'agama' => $faker->randomElement(['Islam','Kristen','Katolik','Hindu','Budha','Konghuchu']),
        'jenis_kelamin' => $faker->randomElement(['laki-laki','perempuan']),
        'nama_ortu' => $faker->name,
        'no_telp_ortu' => '0815465877'.$no_ortu++,
        'instagram' => $faker->name,
        'status_sekolah' => $faker->randomElement(['aktif','lulus']),
        'asal_sekolah' => $faker->randomElement(['SMK','SMA','Universitas']),
        'pekerjaan' => $faker->randomElement(['PNS','Swasta','Wirausaha']),
        'model_pembelajaran' => $faker->randomElement(['online','offline']),
        'jenis_program' => $faker->randomElement(['WDP','APK','Arsitek','Designer','English','Photograph']),
        'jam' => $faker->time,
        'mulai_pendidikan' => $faker->date,
        'informasi_dari' => $faker->name,
        'catatan' => $faker->paragraph,
    ];
});
