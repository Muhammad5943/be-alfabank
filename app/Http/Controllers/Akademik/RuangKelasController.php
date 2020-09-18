<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use App\RuangKelas;
use Illuminate\Http\Request;

class RuangKelasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $ruang_kelas = RuangKelas::get();
    
            return apiReturn($ruang_kelas, "Berhasil menampilkan data ruang kelas");
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
        try {
            $request->validate([
                'ruang_kelas' => ['required','string']
            ]);
    
            $kelas = new RuangKelas();
    
            $kelas->ruang_kelas = $request->ruang_kelas;
            $kelas->save();
    
            return apiReturn($kelas, "Berhasil menambahkan ruang kelas");
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
        try {
            $findKelas = RuangKelas::find($id);

            $request->validate([
                "ruang_kelas" => ['string']
            ]);

            $findKelas->ruang_kelas = $request->ruang_kelas ? $request->ruang_kelas : $findKelas->ruang_kelas;
            $findKelas->update();

            return apiReturn($findKelas,"Berhasil update ruang kelas");
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
            $cariKelas = RuangKelas::find($id);

            $cariKelas->delete();

            return apiReturn($cariKelas,"Berhasil delete kelas");
        } catch (\Throwable $th) {
            return apiCatch();
        }
        
    }
}
