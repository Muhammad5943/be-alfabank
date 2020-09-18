<?php

use Illuminate\Database\Seeder;

class SertifikatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Factory(App\Sertifikat::class, 100)->create();
    }
}
