<?php

namespace App\Http\Controllers\FrontOffice;

use App\Http\Controllers\Controller;
use App\Siswa;
use Illuminate\Http\Request;

class NilaiSiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $lihatSiswa = Siswa::with(['pendaftarans'=>function($query){
                $query->where('status','lulus');
            },'pendaftarans.programkursuses','pendaftarans.sertifikats.penilaians.kompetensis'])->get();

            foreach ($lihatSiswa as $key => $value) {
                $lihatSiswa[$key]['foto'] = request()->getSchemeAndHttpHost().'/storage/siswa/'.$value->foto;
            }
    
            return apiReturn($lihatSiswa,'Berhasil meload siswa');
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
