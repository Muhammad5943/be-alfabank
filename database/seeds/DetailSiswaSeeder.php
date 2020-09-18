<?php

use Illuminate\Database\Seeder;

class DetailSiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Factory(App\DetailSiswa::class,200)->create();
    }
}
