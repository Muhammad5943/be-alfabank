<?php

use Illuminate\Database\Seeder;

class RuangKelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Factory(App\RuangKelas::class,13)->create();
    }
}
