<?php

namespace App\Http\Controllers\Akademik;

use App\Http\Controllers\Controller;
use App\Kompetensi;
use App\Pendaftaran;
use App\Penilaian;
use App\ProgramKursus;
use App\Sertifikat;
use App\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SertifikatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $sertifikat = Siswa::with(['pendaftarans'=>function($query){
                $query->where('status','aktif');
            },'pendaftarans.programkursuses:id,nama','pendaftarans.programkursuses.kompetensis'])->whereHas('pendaftarans',function($query){
                $query->where('status','=','aktif');
            })->get();
    
            foreach ($sertifikat as $key => $value) {
                // TODO:membuat foto dengan local domain, agar memudahkan untuk memunculkan foto
                $sertifikat[$key]['foto'] = request()->getSchemeAndHttpHost().'/storage/siswa/'.$value->foto;
            }

            $response = $sertifikat->groupBy(function($item,$key){
                return $item['nama'];
            });

    
            return apiReturn($response,'Berhasil menampilkan siswa aktif');
        } catch (\Throwable $th) {
            return apiCatch();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexTersertifikasi()
    {
        try {
            $sertifikat = Siswa::with(['pendaftarans'=>function($query){
                $query->where('status','lulus');
            },'pendaftarans.programkursuses:id,nama',
                'pendaftarans.sertifikats:id,kode_sertifikat,id_pendaftaran',
                'pendaftarans.sertifikats.penilaians:id,id_sertifikat,id_kompetensi,nilai'
            ])->whereHas('pendaftarans',function($query){
                $query->where('status','=','lulus');
                $query->where('status','!=',null);
            })->whereHas('pendaftarans.sertifikats',function($query){
                $query->where('id_pendaftaran','!=',null);
            })->get();
            
            foreach ($sertifikat as $key => $value) {
                $sertifikat[$key]['foto'] = request()->getSchemeAndHttpHost().'/storage/siswa/'.$value->foto;
            }

            $response = $sertifikat->groupBy(function($item,$key){
                return $item['nama'];
            });
    
            return apiReturn($response,'Berhasil menampilkan siswa tersertifikasi');
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
            'id_pendaftaran' => ['required','integer'],
            'id_kompetensi' => ['array'],
            'nilai' => ['array']
        ]);
        
        DB::beginTransaction();
        try {

            $statusSiswa = Pendaftaran::find($request->id_pendaftaran);

            // kondisi dibuat diakhir setelah seluruh script selesai
            if ( $statusSiswa->status == 'aktif') {
                
                $status = 'lulus';
                $statusSiswa->status = $status;
                $statusSiswa->update();

            } elseif ( $statusSiswa->status == 'lulus') {

                $response = Pendaftaran::with('siswas','programkursuses')->where('id',$request->id_pendaftaran)->get();

                return apiFailed($response,'Maaf siswa sudah lulus');

            } else {

                $response = Pendaftaran::with('siswas','programkursuses')->where('id',$request->id_pendaftaran)->get();
                
                return apiFailed($response,'Untuk mendapatkan sertifikat anda harus mengubah siswa menjadi aktif');

            }

            $numb = 1;
            $numb++;
            $sertifikat = new Sertifikat;
            $sertifikat->kode_sertifikat = $statusSiswa->id."/".$statusSiswa->id_program_kursus."/".$statusSiswa->id_siswa."/".Carbon::now()->format('Y');
            $sertifikat->id_pendaftaran = $request->id_pendaftaran;
            $sertifikat->save();
            
            // kiriman dari user menggunakan postman, user mengirimkan id_kompetensi
            $kompetensiPenilaian = $request->id_kompetensi;
            $Penilaian = $request->nilai;

            foreach ($kompetensiPenilaian as $key => $kompetensi) {
                $penilaian = new Penilaian;

                $penilaian->id_sertifikat = $sertifikat->id;
                $penilaian->id_kompetensi = $kompetensiPenilaian[$key]['id_kompetensi'];
                // mengambil nilai $penilaian dari $request user dan disimpan dalam variable $penilaian diatas 
                $penilaian->nilai = $Penilaian[$key]['nilai'];
                $penilaian->save();
            }

            // tidak jadi dipakai
            /* $response = Sertifikat::with('pendaftaran.programkursus.kompetensi',
                                            'pendaftaran.programkursus.kompetensi.penilaian',
                                            'pendaftaran.siswa:id,nama,email,no_telp,alamat_lengkap,kota,provinsi,foto')
                                            ->where('id', $sertifikat->id)->get(); */
            DB::commit();


            $response = Sertifikat::with('penilaians.kompetensis',
                                            'pendaftarans.siswas',
                                            'pendaftarans.programkursuses')
                                            ->where('id', $sertifikat->id)->get();

            return apiReturn($response,'berhasil menambahkan sertifikat');

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
        $request->validate([
            'id_kompetensi' => ['array'],
            'nilai' => ['array']
        ]);

        try {
            $updateSertifikat = Sertifikat::find($id);
            // dd($updateSertifikat);
            $updateSertifikat->penilaians()->delete();
    
            $kompetensiid = $request->id_kompetensi;
            $nilai = $request->nilai;
    
            // TODO: digunakan untuk memanipulasi single tabel
            foreach ($kompetensiid as $key => $kompetensi) {
                // (data yang akan diubah/ diisi dengan tabel) = (attribute yang dikirimkan)
                $penilaian = new Penilaian;
                $penilaian->id_sertifikat = $id;
                $penilaian->id_kompetensi = $kompetensiid[$key]['id_kompetensi'];
                $penilaian->nilai = $nilai[$key]['nilai'];
                $penilaian->save();
            }
            
            $response = Sertifikat::with('penilaians.kompetensis:id,id_program_kursus,nama',
                                            'pendaftarans.programkursuses:id,nama,total_pertemuan,kuota,harga',
                                            'pendaftarans.siswas')
                                            ->where('id',$id)
                                            ->get();
            
            return apiReturn($response, "Berhasil update Sertifikat");
        } catch (\Throwable $th) {
            return apiCatch();
        }      
    }

    /**
     * getKompetensi the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getKompetensi($id)
    {
        try {
            // menggunakan "first" karena kita butuh 1 data saja dalam pencarian data
            $programkursus = ProgramKursus::with('kompetensis')->where('id',$id)->first();
            
            return apiReturn($programkursus,'Berhasil menampilkan koompetensi program kursus');
        } catch (\Throwable $th) {
            return apiCatch();
        }
    }

    /**
     * cetekSertifikat the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cetakSertifikat($id)
    {
        try {
            // menggunakan $first karena kita cuman butuh id pertama yang di cari dari postman
            $getSerti = Sertifikat::with('penilaians:id,id_sertifikat,id_kompetensi,nilai',
                                            'pendaftarans.siswas',
                                            'pendaftarans.programkursuses:id,nama',
                                            'penilaians.kompetensis:id,id_program_kursus,nama')
                                            ->where('id',$id)
                                            ->first();

            $getSerti->pendaftarans->siswas['foto'] = request()->getSchemeAndHttpHost().'/storage/siswa/'.$getSerti->pendaftarans->siswas->foto;
            
    
            return apiReturn($getSerti,"Berhasil cetak sertifikat");
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
        //
    }
}
