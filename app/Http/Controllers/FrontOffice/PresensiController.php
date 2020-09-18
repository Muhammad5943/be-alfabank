<?php

namespace App\Http\Controllers\FrontOffice;

use App\Http\Controllers\Controller;
use App\Jadwal;
use App\Presensi;
use App\ProgramKursus;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PresensiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $presensi = Jadwal::with(['programkursusinstrukturs','presensis','programkursusinstrukturs.programkursuses.pendaftarans'=>function($query){
                $query->where('status','aktif');
            }, 'programkursusinstrukturs.instrukturs:id,nama', 'programkursusinstrukturs.programkursuses.pendaftarans:id,id_siswa,id_program_kursus','programkursusinstrukturs.programkursuses.pendaftarans.siswas:id,nama','ruangkelas'])
            ->where('tanggal', Carbon::now()->format('Y-m-d'))->get();
            
            return apiReturn($presensi,'Berhasil menampilkan presensi');
        } catch (\Throwable $th) {
            
            return apiCatch();
        
        }
    }

    public function getSiswaProg($id)
    {
        try {
            $siswaProg = ProgramKursus::with(['pendaftarans'=>function($query){
                $query->where('status','aktif');
            },'pendaftarans.siswas:id,nama'])->where('id', $id)->get();
            
            return apiReturn($siswaProg,'Menampilkan siswa berdasarkan program yang dipilih');
        } catch (\Throwable $th) {
            return apiCatch();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'materi_tersampaikan' => ['required','string','max:255'],
            'waktu_mulai' => ['required','date_format:H:i:s'],
            'waktu_berakhir' => ['required','date_format:H:i:s'],
            'id_jadwal' => ['required','integer'],
            'pendaftaran' => ['array']
        ]);

        try {
            $ambilPendaftaran = $request->pendaftaran;

        foreach ($ambilPendaftaran as $key => $daftar) {
            $pendaftaranContent = new Presensi;

            $pendaftaranContent->id_jadwal = $request->id_jadwal;
            $pendaftaranContent->materi_tersampaikan = $request->materi_tersampaikan;
            $pendaftaranContent->waktu_mulai = $request->waktu_mulai;
            $pendaftaranContent->waktu_berakhir = $request->waktu_berakhir;
            $pendaftaranContent->id_pendaftaran = $ambilPendaftaran[$key]['id_pendaftaran'];
            
            $pendaftaranContent->save();
        }

        // $presensi->materi_tersampaikan = $request->materi_tersampaikan;

        $response = Jadwal::with('presensis.pendaftarans.siswas:id,nama','presensis.pendaftarans.programkursuses','ruangkelas')->where('id',$request->id_jadwal)->get();

        return apiReturn($response,'berhasil menambahkan');
        } catch (\Throwable $th) {
            
            return apiCatch();
        
        }
        
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
