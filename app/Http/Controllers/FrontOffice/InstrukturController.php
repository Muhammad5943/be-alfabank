<?php

namespace App\Http\Controllers\FrontOffice;

use App\Http\Controllers\Controller;
use App\Instruktur;
use Illuminate\Http\Request;

class InstrukturController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $instruktur = Instruktur::with('programkursusinstrukturs.programkursuses')->whereHas('programkursusinstrukturs', function($query){
                $query->where('id_instruktur','!=',null);
            })->get();
    
            foreach ($instruktur as $key => $value) {
                $instruktur[$key]['foto'] = request()->getSchemeAndHttpHost().'/storage/insteuktur/'.$value->foto;
            }
    
            return apiReturn($instruktur,'Berhasil Membuat Instruktur');
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
