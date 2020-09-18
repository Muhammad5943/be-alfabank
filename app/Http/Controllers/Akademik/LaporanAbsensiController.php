<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use App\Pendaftaran;
use App\ProgramKursus;
use Illuminate\Http\Request;

class LaporanAbsensiController extends Controller
{
    /**
     * Display a Program Kursus of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getProgramKursus()
    {
        try {
            $programKursus = ProgramKursus::get();
    
            return apiReturn($programKursus,"Berhasil menampilkan program kursus");

        } catch (\Throwable $th) {
            return apiCatch();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id_program)
    {
        $totalSiswa = Pendaftaran::where('status','aktif')->where('id_program_kursus',$id_program)->count();

        $laporan = ProgramKursus::with(['pendaftarans' => function($query){
            $query->where('status','aktif');
        },'pendaftarans.siswas:id,nama,no_telp','pendaftarans.presensis'])->get();

        $laporanAbsensi = [
            'total_siswa' => $totalSiswa,
            'data_laporan' => $laporan
        ];

        return apiReturn($laporanAbsensi, "Berhasil menampilkan absensi");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
