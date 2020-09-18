<?php

namespace App\Http\Controllers\FrontOffice;

use App\FrontOffice;
use App\Http\Controllers\Controller;
use Lcobucci\JWT\Parser;
use File;
use Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

            $user = Auth::user();
    
            $user['foto'] = request()->getSchemeAndHttpHost().'/storage/front_office/'.$user->foto; 
    
            return apiReturn($user,"Berhasil menampilkan user yang sedang login");
        
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
        $userFinder = Auth::user();
        
        $request->validate([
            'nama' => ['string'],
            'email' => ['string','unique:front_offices,email,'.$userFinder->id],
            'no_telp' => 'string|max:25',
            'foto' => 'image|mimes:jpg,png,jpeg,svg,gif|max:7048'
        ]);

        if ($request->hasFile('foto')) {
            // dd($request->hasFile('foto'));
            $hapusFoto = storage_path("app/public/front_office/{$userFinder->foto}");
                    if (File::exists($hapusFoto)) {
                        unlink($hapusFoto);
                    }
        }

        if ($request->file('foto')) {
            $photo = $request->file("foto");
        
            $nama_photo = time().'.'.$photo->getClientOriginalExtension();
            
            $path = storage_path("app/public/front_office");
            
            $resizeImage = Image::make($photo->getRealPath());
            $resizeImage->resize(1000,700,function($constraint){
                $constraint->aspectRatio();
            })->save($path."/".$nama_photo);
        }

        $userFinder->nama = $request->nama ? $request->nama : $userFinder->nama;
        $userFinder->email = $request->email ? $request->email : $userFinder->email;
        $userFinder->no_telp = $request->no_telp ? $request->no_telp : $userFinder->no_telp;
        $userFinder->foto = $request->hasFile('foto') ? $nama_photo : $userFinder->foto;
        $updateFrontOffice = $userFinder->update();

        if ($updateFrontOffice) {
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
                'data' => $userFinder
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
