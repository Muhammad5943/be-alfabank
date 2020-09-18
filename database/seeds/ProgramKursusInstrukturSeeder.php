<?php

use Illuminate\Database\Seeder;

class ProgramKursusInstrukturSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Factory(App\ProgramKursusInstruktur::class, 30)->create();
    }
}
