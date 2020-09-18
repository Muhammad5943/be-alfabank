<?php

use Illuminate\Database\Seeder;

class KompetensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Factory(App\Kompetensi::class, 50)->create();
    }
}
