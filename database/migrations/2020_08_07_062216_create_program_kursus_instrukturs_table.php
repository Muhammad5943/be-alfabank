<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramKursusInstruktursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('program_kursus_instrukturs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_program_kursus')->unsigned();
            $table->unsignedBigInteger('id_instruktur')->unsigned();
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
        Schema::dropIfExists('program_kursus_instrukturs');
    }
}
