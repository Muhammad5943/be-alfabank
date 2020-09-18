<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use App\Jadwal;
use App\ProgramKursusInstruktur;
use Carbon\Carbon;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jadwalAkad = Jadwal::with('programkursusinstruktur.programkursus','programkursusinstruktur.programkursus.kompetensi',
                                    'programkursusinstruktur.instruktur')->get();
    
        return apiReturn($jadwalAkad,"Berhasil lihat jadwal akademik");
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
            'materi' => ['required','string','max:255'],
            'tanggal' => ['date_format:Y-m-d'],
            'waktu_mulai' => ['required','date_format:H:i:s'],
            'waktu_berakhir' => ['required','date_format:H:i:s'],
            'program_kursus_instruktur_id' => ['array']
        ]);

        $program_instruktur = $request->program_kursus_instruktur_id;
        
        foreach ($program_instruktur as $key => $proginstruktur) {
            $programinstruktur = new Jadwal;
            
            // $programinstruktur->id = 
            $programinstruktur->materi = $request->materi;
            $programinstruktur->tanggal = $request->tanggal;
            $programinstruktur->waktu_mulai = $request->waktu_mulai;
            $programinstruktur->waktu_berakhir = $request->waktu_berakhir;
            $programinstruktur->program_kursus_instruktur_id = $program_instruktur[$key]['program_kursus_instruktur_id'];
            
            $programinstruktur->save();
        }
        
        $response = Jadwal::with('programkursusinstruktur.programkursus','programkursusinstruktur.instruktur','presensi')
                                ->where('tanggal','>=',Carbon::now()->format('Y-m-d'))
                                ->orderBy('tanggal','asc')
                                ->get();

        return apiReturn($response,'Berhasil input jadwal');
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
        $ubahJadwal = Jadwal::find($id);

        $ubahJadwal->jadwal_id = $request->jadwal_id;
        $ubahJadwal->materi = $request->materi ? $request->materi : $ubahJadwal->materi;
        $ubahJadwal->tanggal = $request->tanggal ? $request->tanggal : $ubahJadwal->tanggal;
        $ubahJadwal->waktu_mulai = $request->waktu_mulai ? $request->waktu_mulai : $ubahJadwal->waktu_mulai;
        $ubahJadwal->waktu_berakhir = $request->waktu_berakhir ? $request->waktu_berakhir : $ubahJadwal->waktu_berakhir;
        $ubahJadwal->update();

        $instruktur_program = $request->program_kursus_instruktur_id;

        $contentProgram = [];

        foreach ($instruktur_program as $key => $program) {
            $contentProgram[] = new ProgramKursusInstruktur($program);
        }

        $ubahJadwal->programkursusinstruktur()->delete();
        $ubahJadwal->programkursusinstruktur()->saveMany($contentProgram);

        $response = $ubahJadwal::with('programkursusinstruktur.instruktur','programkursusinstruktur.programkursus')->where('id',$request->jadwal_id)->get();

        return apiReturn($response,'Berhasil merubah jadwal');
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
