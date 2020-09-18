<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use App\Instruktur;
use App\Kompetensi;
use App\Pendaftaran;
use App\ProgramKursus;
use App\ProgramKursusInstruktur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgramKursusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            // fungsi withCount digunakan untuk menghitung jumlah data
            /* kalo pake callback isi function $query adalah table yang dicallback (callback untuk mengakses attribute dari tabel pendaftaran) */

            $pendaftaran = ProgramKursus::withCount(['pendaftarans' => function($query){
                $query->where('status','aktif'); 
            }])->with('programkursusinstrukturs.instrukturs','kompetensis')->get();

            return [

            'success' => true,
            'status_code' => 200,
            'status' => 'OK',
            'messeges' => 'Berhasil menampilkan halaman dashboard akademik',
            'data' => $pendaftaran

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

        // store adalah method bawaan api untuk menambahkan data 
        public function store(Request $request)
        {
            // sebelum menambahkan data kita perlu validate data
            $request->validate([
                'nama' => ['required','string','max:255'], 
                'kuota' => ['required','integer','max:100'], 
                'total_pertemuan' => ['required','integer','max:30'], 
                'harga' => ['required','integer','max:99999999'], 
                'final_project' => ['array'], 
                'instruktur' => ['array'] 
                ]);
                
        DB::beginTransaction();
        try {
            // perlu instnsiasi untuk mengambil attribute data dari tabel yang akan diproses
            $programkursus = new ProgramKursus;
            
            $programkursus->nama = $request->nama;
            $programkursus->kuota = $request->kuota;
            $programkursus->total_pertemuan = $request->total_pertemuan;
            $programkursus->harga = $request->harga;
            
            // setelah kita ambil dan kita isi dengan $request lalu harus di save
            $programkursus->save();
            
            // mengambil $request final project yang berbentuk data array (berlaku untuk single tabel)
            $finalproject = $request->final_project;

            // mengambil $request instruktur program yang berbentuk data array
            $instruktur_program = $request->instruktur;
            
            // data request final project karena berbentuk array maka harus pake forEach untuk proccesing data
            foreach ($finalproject as $i => $project) {
                // instansiasi tabel yang berisi attribute yang akan di proses
                $kompetensi = new Kompetensi;
                // $programkursus->id berasal dari data program kursus yang baru dibuat
                $kompetensi->id_program_kursus = $programkursus->id;
                // mengambil attribut('nama') yang akan dilooping sebanyak $i
                $kompetensi->nama = $finalproject[$i]['nama'];
                // dd($finalproject);

                // lalu disimpan
                $kompetensi->save();
            }

            foreach ($instruktur_program as $key => $instr) {
                $instr = new ProgramKursusInstruktur;

                $instr->id_program_kursus = $programkursus->id;
                $instr->id_instruktur = $instruktur_program[$key]['id_instruktur'];
                
                $instr->save();
            }

            DB::commit();
            
            // dd($instruktur_program);

            // response adalah menampilkan data yang baru dibuat bersama dengan relasinya 
            // hasil dari response digunakan untuk menampilkan data yang akan ditampilkan pada wireframe (proses untuk frontend)
            $response = $programkursus::with('kompetensis',
                                                'programkursusinstrukturs.instrukturs',
                                                'programkursusinstrukturs.instrukturs:id,nama,email,foto')
                                                ->where(['id'=>$programkursus->id])
                                                ->get();

            return [
    
                'success' => true,
                'status_code' => 201,
                'status' => 'Created',
                'messeges' => 'Berhasil membuat data',
                'data' => $response
    
                ];

        } catch (\Throwable $th) {
            DB::rollBack();
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
            'nama' => ['string','max:255'], 
            'kuota' => ['integer','max:100'], 
            'total_pertemuan' => ['integer','max:30'], 
            'harga' => ['integer','max:99999999'], 
            'final_project' => ['array'], 
            'instruktur' => ['array'] 
        ]);

        /* hampir sama dengan menambahkan data hanya berbeda dengan menggunakan kondisi if jika data tidak diedit 
        dan $programkursus->kompetensi()->delete(); 
        dan $programkursus->kompetensi()->saveMany($content); */

        DB::beginTransaction();
        try {
            // dd($request->all());
            $programkursus = ProgramKursus::find($id);

            $programkursus->nama = $request->nama ? $request->nama : $programkursus->nama;
            $programkursus->kuota = $request->kuota ? $request->kuota : $programkursus->kuota;
            $programkursus->harga = $request->harga ? $request->harga : $programkursus->harga;
            $programkursus->total_pertemuan = $request->total_pertemuan ? $request->total_pertemuan : $programkursus->total_pertemuan;
            $programkursus->update();

            if ($request->final_project == !null) {
                $final_project = $request->final_project;
                
                // $content adalah array kosong yang akan di isi attribute final project 
                $content = [];

                // TODO :  $variable[] digunakan ketika memanipulasi multiple tabel
                foreach ($final_project as $key => $project) {
                    $content[] = new Kompetensi($project);
                }

                // dd($content);
                // mengubah dengan menghapus relasi yang lama dan membuat yang baru
                // kompetensi adalah relasi kompetensi dengan program kursus di model program kursus
                // pengisian $content dengan $programkursus
                $programkursus->kompetensis()->delete();
                $programkursus->kompetensis()->saveMany($content);
            }
            
            $instruktur_program = $request->instruktur;

            $contentInstruktur = [];

            foreach ($instruktur_program as $instr => $instruktur) {
                $contentInstruktur[] = new ProgramKursusInstruktur($instruktur);
            }

            $programkursus->programkursusinstrukturs()->delete();
            $programkursus->programkursusinstrukturs()->saveMany($contentInstruktur);

            DB::commit();
            // kompetensi dan programkursusinstruktur adalah nama relasi pada model programkursus
            $response = $programkursus::with('kompetensis','programkursusinstrukturs.instrukturs','programkursusinstrukturs.instrukturs:id,nama,email,foto')->where(['id'=>$programkursus->id])->get();

            // menggunakan app/helper.php
            return apiReturn($response,"Success to updated");

            } catch (\Throwable $th) {
                DB::rollBack();
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
            $programkursus = ProgramKursus::find($id);
            $programkursus->delete();

            return [
            
                'success' => true,
                'status_code' => 200,
                'status' => 'OK',
                'messeges' => 'Success to deleted',
                'data' => $programkursus

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
}
