<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AllRelations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kompetensis', function( Blueprint $table) {
            $table->foreign('id_program_kursus')->references('id')->on('program_kursuses')->onDelete('cascade'); 
        });

        Schema::table('pendaftarans', function( Blueprint $table ) {
            $table->foreign('id_siswa')->references('id')->on('siswas')->onDelete('cascade');
            $table->foreign('id_program_kursus')->references('id')->on('program_kursuses')->onDelete('cascade');
        });

        Schema::table('program_kursus_instrukturs', function( Blueprint $table) {
            $table->foreign('id_program_kursus')->references('id')->on('program_kursuses')->onDelete('cascade');
            $table->foreign('id_instruktur')->references('id')->on('instrukturs')->onDelete('cascade');
        });

        Schema::table('sertifikats', function( Blueprint $table) {
            $table->foreign('id_pendaftaran')->references('id')->on('pendaftarans')->onDelete('cascade');
        });

        Schema::table('jadwals', function( Blueprint $table ) {
            $table->foreign('id_program_kursus_instruktur')->references('id')->on('program_kursus_instrukturs')->onDelete('cascade');
        });

        Schema::table('presensis', function( Blueprint $table ) {
            $table->foreign('id_pendaftaran')->references('id')->on('pendaftarans')->onDelete('cascade');
            $table->foreign('id_jadwal')->references('id')->on('jadwals')->onDelete('cascade');
        });

        Schema::table('penilaians', function( Blueprint $table ) {
            $table->foreign('id_sertifikat')->references('id')->on('sertifikats')->onDelete('cascade');
            $table->foreign('id_kompetensi')->references('id')->on('kompetensis')->onDelete('cascade');
        });

        Schema::table('detail_siswas', function( Blueprint $table ){
            $table->foreign('id_siswa')->references('id')->on('siswas')->onDelete('cascade');
        });

        Schema::table('jadwals', function( Blueprint $table ){
            $table->foreign('id_ruang_kelas')->references('id')->on('ruang_kelas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
