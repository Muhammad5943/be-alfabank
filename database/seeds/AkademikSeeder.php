<?php

use Illuminate\Database\Seeder;

class AkademikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Factory(App\Akademik::class, 1)->create();
    }
}
