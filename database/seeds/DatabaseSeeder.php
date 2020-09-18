<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SiswaSeeder::class);
        $this->call(AkademikSeeder::class);
        $this->call(FrontOfficeSeeder::class);
        $this->call(ProgramKursusSeeder::class);
        $this->call(InstrukturSeeder::class);
        $this->call(PendaftaranSeeder::class);
        $this->call(ProgramKursusInstrukturSeeder::class);
        $this->call(RuangKelasSeeder::class);
        $this->call(JadwalSeeder::class);
        $this->call(KompetensiSeeder::class);
        $this->call(SertifikatSeeder::class);
        $this->call(PenilaianSeeder::class);
        $this->call(PresensiSeeder::class);
        $this->call(DetailSiswaSeeder::class);
    }
}
