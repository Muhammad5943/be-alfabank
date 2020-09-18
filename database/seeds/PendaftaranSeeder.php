<?php

use Illuminate\Database\Seeder;

class PendaftaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Factory(App\Pendaftaran::class, 200)->create();
    }
}
