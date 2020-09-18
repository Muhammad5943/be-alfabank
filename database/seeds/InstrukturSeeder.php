<?php

use Illuminate\Database\Seeder;

class InstrukturSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Factory(App\Instruktur::class, 15)->create();
    }
}
