<?php

namespace App\Http\Controllers\Akademik;

use App\Akademik;
use App\Http\Controllers\Controller;
use Lcobucci\JWT\Parser;
use File;
use Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Constraint;

class ProfilesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
        
            $showProfile = Auth::user();
        
            $showProfile['foto'] = request()->getSchemeAndHttpHost().'/storage/akademik/'.$showProfile->foto;
        
            return $showProfile;
        
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
    public function update(Request $request)
    {
        $akademik = Auth::user();

        $request->validate([
            'nama' => ['string'],
            'email' => ['string','unique:akademiks,email,'.$akademik->id],
            'no_telp' => ['string'],
            'foto' => ['image','mimes:png,jpg,jpeg,gif,svg','max:7048']
        ]);

        if ($request->hasFile('foto')) {
            // dd($request->hasFile('foto'));
            $hapusFoto = storage_path("app/public/akademik/{$akademik->foto}");
                if (File::exists($hapusFoto)) {
                    unlink($hapusFoto);
                }
        }

        if ($request->has('foto')) {
            $photo = $request->file("foto");
            
            $nama_photo = time().'.'.$photo->getClientOriginalExtension();

            $path = storage_path("app/public/akademik");

            $resizeImage = Image::make($photo->getRealPath());
            $resizeImage->resize(1000,700,function($constraint){
                $constraint->aspectRatio();
            })->save($path."/".$nama_photo);
        }

        $akademik->nama = $request->nama ? $request->nama : $akademik->nama;
        $akademik->email = $request->email ? $request->email : $akademik->email;
        $akademik->no_telp = $request->no_telp ? $request->no_telp : $akademik->no_telp;
        $akademik->foto = $request->hasFile('foto') ? $nama_photo : $akademik->foto;
        $updateAkademik = $akademik->update();

        if ($updateAkademik) {
            $myToken = $request->bearerToken();
            if ($myToken) {
            $id = (new Parser())->parse($myToken)->getClaim('jti');
                $revoke = DB::table('oauth_access_tokens')->where('id',$id)->update(['revoked' => 1]);
            }
        }    
            return [
                'success' => true,
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Berhasil update front office',
                'data' => $akademik
            ];        
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
