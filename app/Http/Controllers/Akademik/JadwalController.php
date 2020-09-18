<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use App\Jadwal;
use App\ProgramKursus;
use App\ProgramKursusInstruktur;
use Carbon\Carbon;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function getInstrukturProgramkursus()
    {
        $instruktur = ProgramKursus::with('programkursusinstrukturs.instrukturs')->get();

        return apiReturn($instruktur,'Berhasil menampilkan semua program kursus dan instruktur');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $jadwalAkad = Jadwal::with('programkursusinstrukturs.programkursuses',
                                        'programkursusinstrukturs.instrukturs',
                                        'ruangkelas:id,ruang_kelas')
                                        ->where('tanggal','>=',Carbon::now()
                                        ->format('Y-m-d'))
                                        ->orderBy('tanggal','desc')
                                        ->get();
    
            $jadwalGroup = $jadwalAkad->groupBy(function($query,$key){
                return Carbon::createFromFormat('Y-m-d',$query['tanggal'])->format('Y-m-d');
            });
        
            return apiReturn($jadwalGroup,"Berhasil lihat jadwal akademik");
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
            'materi' => ['required','string','max:255'],
            'tanggal' => ['date_format:Y-m-d'],
            'waktu_mulai' => ['required','date_format:H:i:s'],
            'waktu_berakhir' => ['required','date_format:H:i:s'],
            'id_program_kursus_instruktur' => ['required','integer'],
            'id_ruang_kelas' => ['required','integer']
        ]);
        
        try {
                $programinstruktur = new Jadwal;
                
                // $programinstruktur->id = 
                $programinstruktur->materi = $request->materi;
                $programinstruktur->tanggal = $request->tanggal;
                $programinstruktur->waktu_mulai = $request->waktu_mulai;
                $programinstruktur->waktu_berakhir = $request->waktu_berakhir;
                $programinstruktur->id_program_kursus_instruktur = $request->id_program_kursus_instruktur;
                $programinstruktur->id_ruang_kelas = $request->id_ruang_kelas;
                
                $programinstruktur->save();
            
            $response = Jadwal::with('programkursusinstrukturs.programkursuses','programkursusinstrukturs.instrukturs','ruangkelas:id,ruang_kelas')
                                    ->where('id','=',$programinstruktur->id)
                                    ->get();
    
            return apiReturn($response,'Berhasil input jadwal');
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
        $request->validate([
            'materi' => ['string','max:255'],
            'tanggal' => ['date_format:Y-m-d'],
            'waktu_mulai' => ['date_format:H:i:s'],
            'waktu_berakhir' => ['date_format:H:i:s'],
            'id_program_kursus_instruktur' => ['integer'],
            'id_ruang_kelas' => ['integer']
        ]);

        try {
            $ubahJadwal = Jadwal::find($id);
    
            $ubahJadwal->materi = $request->materi ? $request->materi : $ubahJadwal->materi;
            $ubahJadwal->tanggal = $request->tanggal ? $request->tanggal : $ubahJadwal->tanggal;
            $ubahJadwal->waktu_mulai = $request->waktu_mulai ? $request->waktu_mulai : $ubahJadwal->waktu_mulai;
            $ubahJadwal->waktu_berakhir = $request->waktu_berakhir ? $request->waktu_berakhir : $ubahJadwal->waktu_berakhir;
            $ubahJadwal->id_program_kursus_instruktur = $request->id_program_kursus_instruktur ? $request->id_program_kursus_instruktur : $ubahJadwal->id_program_kursus_instruktur;
            $ubahJadwal->id_ruang_kelas = $request->id_ruang_kelas ? $request->id_ruang_kelas : $ubahJadwal->id_ruang_kelas;
    
            $ubahJadwal->update();
    
            $response = $ubahJadwal::with('programkursusinstrukturs.programkursuses','programkursusinstrukturs.instrukturs','ruangkelas:id,ruang_kelas')->where('id',$ubahJadwal->id)->get();
    
            return apiReturn($response,'Berhasil merubah jadwal');
        } catch (\Throwable $th) {
            return apiCatch();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $deleteJadwal = Jadwal::find($id);
            $deleteJadwal->delete();
    
            return apiReturn($deleteJadwal,"berhasil delete jadwal");
        } catch (\Throwable $th) {
            return apiCatch();
        }
    }
}
