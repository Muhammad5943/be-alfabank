<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJadwalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_program_kursus_instruktur')->unsigned();
            $table->unsignedBigInteger('id_ruang_kelas')->unsigned();
            $table->string('materi');
            $table->date('tanggal');
            $table->time('waktu_mulai', 0);
            $table->time('waktu_berakhir', 0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jadwals');
    }
}
