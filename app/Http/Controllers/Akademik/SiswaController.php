<?php

namespace App\Http\Controllers\Akademik;

use App\DetailSiswa;
use App\Http\Controllers\Controller;
use App\Pendaftaran;
use App\ProgramKursusInstruktur;
use App\Siswa;
use File;
use Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ubahStatus($id)
    {
        try {
            $ubahstatus = Pendaftaran::find($id);
        
            if ( $ubahstatus->status == 'aktif' ) {
            
                $statusberubah = 'tidak_aktif';
                
                $ubahstatus->status = $statusberubah;

                $ubahstatus->update();

                $response = $ubahstatus::with('siswas','programkursuses')->where('id',$ubahstatus->id)->get();
            
                return apiReturn($response,'Status siswa tidak aktif ');
            } elseif ($ubahstatus->status == 'tidak_aktif' ) {

                $statusberubah = 'aktif';

                $ubahstatus->status = $statusberubah;

                $ubahstatus->update();

                $response = $ubahstatus::with('siswas','programkursuses')->where(['id'=>$ubahstatus->id])->get();

                return apiReturn($response,'Status siswa aktif');
            } else {

                $response = $ubahstatus::with('siswas','programkursuses')->where(['id'=>$ubahstatus->id])->get();
                
                return apiFailed($response,'Siswa sudah lulus');        

            }
        } catch (\Throwable $th) {
            return apiCatch();
        }
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    // TODO:callback_function;
    public function index()
    {
        try {
            $siswa = Siswa::with('pendaftarans.programkursuses','detail_siswas')->whereHas('pendaftarans',function($query){
                $query->where('id_siswa','!=',null);
            })->whereHas('pendaftarans',function($query){
                $query->where('status','!=','pending');
            })->get();

            foreach ($siswa as $key => $value) {
                $siswa[$key]['foto'] = request()->getSchemeAndHttpHost()."/storage/siswa/".$value->foto;
            }

            return apiReturn($siswa,'berhasil menampilkan siswa');
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
            'nama' => ['required','string','max:255'],
            'email' => ['required','string','unique:siswas,email','max:255'],
            'alamat_lengkap' => ['required','string','max:255'],
            'no_telp' => ['required','string','max:255'],
            'kota' => ['required','string','max:255'],
            'provinsi' => ['required','string','max:255'],
            'foto' => ['image','mimes:jpg,png,jpeg,svg,gif','max:7048'],
            // Detail siswa
            'tempat_lahir' => ['required','string','max:255'],
            'tanggal_lahir' => ['date_format:Y-m-d'],
            'agama' => ['required','string','max:255'],
            'jenis_kelamin' => ['required','string','max:255'],
            'nama_ortu' => ['required','string'],
            'no_telp_ortu' => ['required','string'],
            'instagram' => ['required','string','max:255'],
            'status_sekolah' => ['required','string','max:255'],
            'asal_sekolah' => ['required','string','max:255'],
            'pekerjaan' => ['required','string','max:255'],
            'model_pembelajaran' => ['required','string','max:255'],
            'jenis_program' => ['required','string','max:255'],
            'jam' => ['required','date_format:H:i'],
            'mulai_pendidikan' => ['date_format:Y-m-d'],
            'informasi_dari' => ['required','string','max:255'],
            'catatan' => ['required','string','max:255'],
            // Pendaftaran
            'programkursus' => ['array']
        ]);

        // dd($request->all());

        DB::beginTransaction();
        try {
            $photo = $request->file('foto');

            $nama_foto = time().'.'.$photo->getClientOriginalExtension();

            $path = storage_path("app/public/siswa");

            $resizeImage = Image::make($photo->getRealPath());
            $resizeImage->resize(1000,700,function($constraint){
                $constraint->aspectRatio();
            })->save($path."/".$nama_foto);

            // dd($nama_foto);
            $siswa = new Siswa;

            $siswa->nama = $request->nama;
            $siswa->email = $request->email;
            $siswa->no_telp = $request->no_telp;
            $siswa->alamat_lengkap = $request->alamat_lengkap;
            $siswa->kota = $request->kota;
            $siswa->provinsi = $request->provinsi;
            $siswa->foto = $nama_foto;

            $siswa->save();
            
            $detailSiswa = new DetailSiswa();
        
            $detailSiswa->id_siswa = $siswa->id;
            $detailSiswa->tempat_lahir = $request->tempat_lahir;
            $detailSiswa->tanggal_lahir = $request->tanggal_lahir;
            $detailSiswa->agama = $request->agama;
            $detailSiswa->jenis_kelamin = $request->jenis_kelamin;
            $detailSiswa->nama_ortu = $request->nama_ortu;
            $detailSiswa->no_telp_ortu = $request->no_telp_ortu;
            $detailSiswa->instagram = $request->instagram;
            $detailSiswa->status_sekolah = $request->status_sekolah;
            $detailSiswa->asal_sekolah = $request->asal_sekolah;
            $detailSiswa->pekerjaan = $request->pekerjaan;
            $detailSiswa->model_pembelajaran = $request->model_pembelajaran;
            $detailSiswa->jenis_program = $request->jenis_program;
            $detailSiswa->jam = $request->jam;
            $detailSiswa->mulai_pendidikan = $request->mulai_pendidikan;
            $detailSiswa->informasi_dari = $request->informasi_dari;
            $detailSiswa->catatan = $request->catatan;
            $detailSiswa->save();
            // $siswa = Siswa  

            // ambil variable dari isi form-data pada postman untuk array
            $pendaftaran = $request->programkursus;

            foreach ($pendaftaran as $key => $pendaftar) {
                $pendaftarSiswa = new Pendaftaran();
                $pendaftarSiswa->id_siswa = $siswa->id;
                // TODO: id_program_kursus diambil dari postman (sama dengan key di postman)
                $pendaftarSiswa->id_program_kursus = $pendaftaran[$key]['id_program_kursus'];

                $pendaftarSiswa->save();
            }

            DB::commit();

            $response = $siswa::with('pendaftarans','detail_siswas','pendaftarans.programkursuses')->where(['id'=>$siswa->id])->get();

            return apiReturn($response,"Berhasil menambahkan siswa baru");

            // ambil variable dari isi form-data pada postman untuk array
            $pendaftaran = $request->programkursus;

            foreach ($pendaftaran as $key => $pendaftar) {
                $pendaftarSiswa = new Pendaftaran();
                $pendaftarSiswa->id_siswa = $siswa->id;
                // TODO: id_program_kursus diambil dari postman (sama dengan key di postman ato value yang di->$request)
                $pendaftarSiswa->id_program_kursus = $pendaftaran[$key]['id_program_kursus'];

                $pendaftarSiswa->save();
            }

            DB::commit();

            $response = $siswa::with('pendaftarans','pendaftarans.programkursuses')->where(['id'=>$siswa->id])->get();

            return apiReturn($response,"Berhasil menambahkan siswa baru");

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
    public function update(Request $request, $id)
    {
        $siswa = Siswa::find($id);
        
        $request->validate([
            'nama' => ['string','max:255'],
            'email' => ['string','unique:siswas,email,'.$siswa->id,'max:255'],
            'alamat_lengkap' => ['string','max:255'],
            'no_telp' => ['string','max:255'],
            'kota' => ['string','max:255'],
            'provinsi' => ['string','max:255'],
            'foto' => ['image','mimes:jpg,png,jpeg,svg,gif','max:7048'],
            // Detail siswa
            'tempat_lahir' => ['string','max:255'],
            'tanggal_lahir' => ['date_format:Y-m-d'],
            'agama' => ['string','max:255'],
            'jenis_kelamin' => ['string','max:255'],
            'nama_ortu' => ['string'],
            'no_telp_ortu' => ['string'],
            'instagram' => ['string','max:255'],
            'status_sekolah' => ['string','max:255'],
            'asal_sekolah' => ['string','max:255'],
            'pekerjaan' => ['string','max:255'],
            'model_pembelajaran' => ['string','max:255'],
            'jenis_program' => ['string','max:255'],
            'jam' => ['date_format:H:i'],
            'mulai_pendidikan' => ['date_format:Y-m-d'],
            'informasi_dari' => ['string','max:255'],
            'catatan' => ['string','max:255'],
        ]);

        try {
            if ($request->hasFile('foto')) {
                    $hapusfoto = storage_path("app/public/siswa/{$siswa->foto}");
                if (File::exists($hapusfoto)) {
                    unlink($hapusfoto);
                }
            }

            if ($request->file('foto')) {
                $photo = $request->file("foto");
            
                $nama_photo = time().'.'.$photo->getClientOriginalExtension();
                
                $path = storage_path("app/public/siswa");
                
                $resizeImage = Image::make($photo->getRealPath());
                $resizeImage->resize(1000,700,function($constraint){
                    $constraint->aspectRatio();
                })->save($path."/".$nama_photo);
            }
            
            $siswa->nama = $request->nama ? $request->nama : $siswa->nama;
            $siswa->email = $request->email ? $request->email : $siswa->email;
            $siswa->no_telp = $request->no_telp ? $request->no_telp : $siswa->no_telp;
            $siswa->alamat_lengkap = $request->alamat_lengkap ? $request->alamat_lengkap : $siswa->alamat_lengkap;
            $siswa->kota = $request->kota ? $request->kota : $siswa->kota;
            $siswa->provinsi = $request->provinsi ? $request->provinsi : $siswa->provinsi;
            $siswa->foto = $request->hasFile('foto') ? $nama_photo : $siswa->foto;
            $siswa->update();

            $detailSiswa = DetailSiswa::where('id_siswa',$id)->first();

            $detailSiswa->tempat_lahir = $request->tempat_lahir ? $request->tempat_lahir : $detailSiswa->tempat_lahir;
            $detailSiswa->tanggal_lahir = $request->tanggal_lahir ? $request->tanggal_lahir : $detailSiswa->tanggal_lahir;
            $detailSiswa->agama = $request->agama ? $request->agama : $detailSiswa->agama;
            $detailSiswa->jenis_kelamin = $request->jenis_kelamin ? $request->jenis_kelamin : $detailSiswa->jenis_kelamin;
            $detailSiswa->nama_ortu = $request->nama_ortu ? $request->nama_ortu : $detailSiswa->nama_ortu;
            $detailSiswa->no_telp_ortu = $request->no_telp_ortu ? $request->no_telp_ortu : $detailSiswa->no_telp_ortu;
            $detailSiswa->instagram = $request->instagram ? $request->instagram : $detailSiswa->instagram;
            $detailSiswa->status_sekolah = $request->status_sekolah ? $request->status_sekolah : $detailSiswa->status_sekolah;
            $detailSiswa->asal_sekolah = $request->asal_sekolah ? $request->asal_sekolah : $detailSiswa->asal_sekolah;
            $detailSiswa->pekerjaan = $request->pekerjaan ? $request->pekerjaan : $detailSiswa->pekerjaan;
            $detailSiswa->model_pembelajaran = $request->model_pembelajaran ? $request->model_pembelajaran : $detailSiswa->model_pembelajaran;
            $detailSiswa->jenis_program = $request->jenis_program ? $request->jenis_program : $detailSiswa->jenis_program;
            $detailSiswa->jam = $request->jam ? $request->jam : $detailSiswa->jam;
            $detailSiswa->mulai_pendidikan = $request->mulai_pendidikan ? $request->mulai_pendidikan : $detailSiswa->mulai_pendidikan;
            $detailSiswa->informasi_dari = $request->informasi_dari ? $request->informasi_dari : $detailSiswa->informasi_dari;
            $detailSiswa->catatan = $request->catatan ? $request->catatan : $detailSiswa->catatan;
            $detailSiswa->update();

            $response = $siswa::with('pendaftarans','pendaftarans.programkursuses','detail_siswas')->where(['id' => $siswa->id])->get();

            return apiReturn($response,'Berhasil update siswa');

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
            
            $siswaDestroy = Siswa::find($id);
            $siswaDestroy->delete();

            return apiReturn($siswaDestroy,'Berhasil menghapus data siswa');
        
        } catch (\Throwable $th) {

            return apiCatch();

        }
        
    }
}
