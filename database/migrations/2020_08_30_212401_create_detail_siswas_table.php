<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailSiswasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_siswas', function (Blueprint $table) {
            $table->id();
            // unsigned = tidak boleh dibawah 0 
            $table->unsignedBigInteger('id_siswa')->unsigned();
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('agama');
            $table->enum('jenis_kelamin',['laki-laki','perempuan']);
            $table->string('nama_ortu');
            $table->string('no_telp_ortu',15);
            $table->string('instagram')->nullable();
            $table->string('status_sekolah');
            $table->string('asal_sekolah')->nullable();
            $table->string('pekerjaan');
            $table->enum('model_pembelajaran',['online','offline']);
            $table->string('jenis_program');
            $table->time('jam',0);
            $table->date('mulai_pendidikan');
            $table->string('informasi_dari');
            $table->text('catatan');
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
        Schema::dropIfExists('detail_siswas');
    }
}
