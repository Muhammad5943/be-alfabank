<?php

use Illuminate\Database\Seeder;

class FrontOfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Factory(App\FrontOffice::class, 1)->create();
    }
}
