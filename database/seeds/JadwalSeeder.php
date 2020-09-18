<?php

use Illuminate\Database\Seeder;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Factory(App\Jadwal::class, 50)->create();
    }
}
