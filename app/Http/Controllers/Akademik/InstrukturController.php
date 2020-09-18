<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use App\Instruktur;
use App\ProgramKursusInstruktur;
use Illuminate\Http\Request;
use Image;
use File;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\DB;

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

            $instruktur = Instruktur::with('programkursusinstrukturs','programkursusinstrukturs.programkursuses')->get();

            foreach ($instruktur as $key => $value) {
                $instruktur[$key]['foto'] = request()->getSchemeAndHttpHost().'/storage/instruktur/'.$value->foto;
            }

            return apiReturn($instruktur, "Berhasil menampilkan Instruktur");
        
        } catch (\Throwable $th) {
            
            return apiCatch();
        }
        // dd($instruktur);
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
            "nama" =>  'required|string|max:255',
            "email" => 'required|string|unique:instrukturs,email|max:255',
            "no_telp" => 'required|string|max:25',
            "alamat_lengkap" => 'required|string|max:255',
            "kota" => 'required|string|max:255',
            "provinsi" => 'required|string|max:255',
            "foto" => 'image|mimes:jpg,png,jpeg,svg,gif|max:7048'
        ]);

        // dd($request->all());
        DB::beginTransaction();

        try {
            // mendeklarasikan attribute foto menjadi file
            $photo = $request->file("foto");

            // mendeklarasikan nama dari attribute foto yang akan disimpan
            $nama_photo = time().'.'.$photo->getClientOriginalExtension();

            // mendeklarasikan tempat penyimpanan attribut foto
            $path = storage_path("app/public/instruktur");

            // resizing ukuran photo
            $resizeImage = Image::make($photo->getRealPath());
            $resizeImage->resize(1000,700,function($constraint){
                $constraint->aspectRatio();
            })->save($path."/".$nama_photo);

            // instansiasi table/ model yang akan diproses
            $instrukturData = new Instruktur;

            // proses data yang akan diposting
            $instrukturData->nama = $request->nama;
            $instrukturData->email = $request->email;
            $instrukturData->no_telp = $request->no_telp;
            $instrukturData->alamat_lengkap = $request->alamat_lengkap;
            $instrukturData->kota = $request->kota;
            $instrukturData->provinsi = $request->provinsi;
            $instrukturData->foto = $nama_photo;
            
            // menyimpan data yang telah diproses
            $instrukturData->save();

            // input data untuk attribut dengan banyak value/ array
            $program_kursus_instr = $request->programkursus;

            // dd($program_kursus_instr);

            foreach ($program_kursus_instr as $key => $projectValue) {
                $programInstr = new ProgramKursusInstruktur();
                $programInstr->id_instruktur = $instrukturData->id;
                $programInstr->id_program_kursus = $program_kursus_instr[$key]['id_program_kursus'];

                $programInstr->save();
            }

            DB::commit();
            $response = $instrukturData::with('programkursusinstrukturs.programkursuses')->where(['id'=>$instrukturData->id])->get();

            return apiReturn($response,'Berhasil Menambahkan Instruktur');
        } catch (\Throwable $th) {
            DB::rollBack();
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
    // TODO:membuat update instruk (perhatian untuk foto)
    public function update(Request $request, $id)
    {
        $instruktur = Instruktur::find($id);
        
        $request->validate([
            'nama' => 'string|max:255',
            'email' => 'string|max:255|unique:instrukturs,email,'.$instruktur->id,
            'no_telp' => 'string|max:25',
            'alamat_lengkap' => 'string|max:255',
            'kota' => 'string|max:255',
            'provinsi' => 'string|max:255',
            'foto' => 'image|mimes:jpg,png,jpeg,svg,gif|max:7048',
            'programkursusinstruktur' => ['array']
        ]);

        DB::beginTransaction();
        try {
        
            if ($request->hasFile('foto')) {
                $hapusfoto = storage_path("app/public/instruktur/{$instruktur->foto}");
                if (File::exists($hapusfoto)) {
                    unlink($hapusfoto);
                }
            }

            if ($request->file('foto')) {
                $photo = $request->file("foto");
            
                $nama_photo = time().'.'.$photo->getClientOriginalExtension();
                
                $path = storage_path("app/public/instruktur");
                
                $resizeImage = Image::make($photo->getRealPath());
                $resizeImage->resize(1000,700,function($constraint){
                    $constraint->aspectRatio();
                })->save($path."/".$nama_photo);
            }

            // dd($nama_photo);

            $instruktur->nama = $request->nama ? $request->nama : $instruktur->nama;
            $instruktur->email = $request->email ? $request->email : $instruktur->email;
            $instruktur->no_telp = $request->no_telp ? $request->no_telp : $instruktur->no_telp;
            $instruktur->alamat_lengkap = $request->alamat_lengkap ? $request->alamat_lengkap : $instruktur->alamat_lengkap;
            $instruktur->kota= $request->kota ? $request->kota: $instruktur->kota;
            $instruktur->provinsi= $request->provinsi ? $request->provinsi: $instruktur->provinsi;
            $instruktur->foto= $request->hasFile('foto') ? $nama_photo : $instruktur->foto;
            $instruktur->update();
            
            $program_instruktur = $request->programkursus;
            
            $content = [];

            foreach ($program_instruktur as $key => $programkursus) {
                $content[] = new ProgramKursusInstruktur($programkursus);
            }

            $instruktur->programkursusinstrukturs()->delete();
            $instruktur->programkursusinstrukturs()->saveMany($content);

            DB::commit();
            
            $response = $instruktur::with('programkursusinstrukturs','programkursusinstrukturs.programkursuses:id,nama')->where(['id'=>$instruktur->id])->get();

            // dd($response);
            return apiReturn($response,"updated success");

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
        
            $instrukturDestroy = Instruktur::find($id);
            $instrukturDestroy->delete();
            
            return apiReturn($instrukturDestroy,'Success menghapus');

        } catch (\Throwable $th) {
            return apiCatch();
        }
    }
}
