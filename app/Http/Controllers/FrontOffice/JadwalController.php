<?php

namespace App\Http\Controllers\FrontOffice;

use App\Http\Controllers\Controller;
use App\Jadwal;
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
        try {
            $jadwal = Jadwal::with('programkursusinstrukturs.programkursuses','programkursusinstrukturs.instrukturs','ruangkelas')
            ->where('tanggal','>=', Carbon::now()->format('Y-m-d'))
            ->orderBy('tanggal','asc')
            ->get();

            $responseGroub = $jadwal->groupBy(function($query,$key){
                return Carbon::createFromFormat('Y-m-d',$query['tanggal'])->format('Y-m-d');
            });

            return apiReturn($responseGroub,'Menampilkan Jadwal');
        
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
