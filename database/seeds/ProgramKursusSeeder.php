<?php

use Illuminate\Database\Seeder;

class ProgramKursusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Factory(App\ProgramKursus::class, 15)->create();
    }
}
