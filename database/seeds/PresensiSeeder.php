<?php

use Illuminate\Database\Seeder;

class PresensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Factory(App\Presensi::class, 100)->create();
    }
}
