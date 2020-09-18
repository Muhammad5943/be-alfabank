<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Siswa;
use App\ProgramKursus;
use App\Instruktur;
use App\FrontOffice;
use App\Pendaftaran;

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
            $pendaftaranAktif = Pendaftaran::where('status','aktif')->count();
        
            $pendaftaranTidak_aktif = Pendaftaran::where('status','tidak_aktif')->count();

            $pendaftaranLulus = Pendaftaran::where('status','lulus')->count();

            $instruktur = Instruktur::count();

            $frontOffice = FrontOffice::count();

            $programKursus = ProgramKursus::count();
            
            $response = [
                
                'pendaftar_aktif' => $pendaftaranAktif,
                'pendaftar_lulus' => $pendaftaranLulus,
                'pendaftar_tidak_aktif' => $pendaftaranTidak_aktif,
                'program_kursus' => $programKursus,
                'instruktur' => $instruktur,
                'front_office' => $frontOffice];
            
            return [

                    'success' => true,
                    'status_code' => 200,
                    'status' => 'OK',
                    'messeges' => 'Berhasil menampilkan halaman dashboard akademik',
                    'data' => $response

                    ];

        } catch (\Throwable $th) {

            $error = [

                'success' => false,
                'status_code' => 500,
                'status' => 'Server Error',
                'messeges' => 'Terjadi kesalahan sistem'

                ];

                return $error;
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
