<?php

namespace App\Http\Controllers\FrontOffice;

use App\Http\Controllers\Controller;
use App\Instruktur;
use App\Jadwal;
use App\Pendaftaran;
use App\ProgramKursus;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            $programkursus = ProgramKursus::count();
        
            $instruktur = Instruktur::count();

            $pendaftaranPending = Pendaftaran::where('status','pending')->count();
            
            $jadwal = Jadwal::with('programkursusinstrukturs','programkursusinstrukturs.programkursuses','programkursusinstrukturs.instrukturs')->where('tanggal', Carbon::Now()->format('Y-m-d'))->get();

            $responseDasboard = [
                
                'program_kursus' => $programkursus, 
                'instruktur' => $instruktur,
                'pendaftaran_pending' => $pendaftaranPending,
                'jadwal_hari_ini' => $jadwal
            
            ];

            return apiReturn($responseDasboard,'Menampilkan dashboard front office');

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
