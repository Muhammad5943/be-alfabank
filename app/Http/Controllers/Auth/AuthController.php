<?php

namespace App\Http\Controllers\Auth;

use App\Akademik;
use App\FrontOffice;
use App\Http\Controllers\Controller;
use Dotenv\Loader\Parser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Constraint;
use Image;

class AuthController extends Controller
{
    public function loginAkademik(Request $request)
    {
        $request->validate([
            'email' => ['required','string','email'],
            'password' => ['required','string']
        ]);

        $email = Akademik::whereEmail($request->email)->first();

        if ($email) {
            $authenticate = Hash::check($request->password,$email->password);
            // dd($authenticate);
            if ($authenticate == true) {
                $token = $email->createToken('My Token');
                $email->token = $token->accessToken;
                $email['foto'] = request()->getSchemeAndHttpHost().'/storage/akademik/'.$email->foto;
            
                return apiReturn($email,"Berhasil login akademik");
            } else {
                return apiUnauthorized("Maaf Password Tidak Sama");
            }

        } else {
            return apiUnauthorized("Maaf akun anda tidak terdaftar");
        }

        // dd($email);
    }

    public function registerFrontOffice(Request $request)
    {
        $request->validate([
            'nama' => ['required','string'],
            'email' => ['required','string','unique:front_offices,email'],
            'no_telp' => ['required','string'],
            'foto' => ['required','image','mimes:jpg,png,jpeg,svg,gif'],
            'password' => ['required','string'],
            'ulangi_password' => ['required','string'],
        ]);

        if ($request->password != $request->ulangi_password) {

            return apiUnauthorized("Maaf password tidak match");

        }

        $photo = $request->file("foto");

        $photo_name = time().'.'.$photo->getClientOriginalExtension();

        $path = storage_path("app/public/front_office");

        $resizingImage = Image::make($photo->getRealPath());
        $resizingImage->resize(1000,700,function($constraint){
            $constraint->aspectRatio();
        })->save($path."/".$photo_name);

        $frontoffice = new FrontOffice();

        $frontoffice->nama = $request->nama;
        $frontoffice->email = $request->email;
        $frontoffice->no_telp = $request->no_telp;
        $frontoffice->password = bcrypt($request->password);
        $frontoffice->foto = $photo_name;

        $frontoffice->save();

        $token = $frontoffice->createToken("My Token");
        $frontoffice->token = $token->accessToken;

        return apiReturn($frontoffice,"Berhasil membuat front office");
    }

    public function loginFrontOffice(Request $request)
    {
        $request->validate([
            'email' => ['required','string','email'],
            'password' => ['required','string']
        ]);

        $emailFO = FrontOffice::whereEmail($request->email)->first();
        
        if ($emailFO) {
            $authenticateFO = Hash::check($request->password,$emailFO->password);
            // dd($authenticateFO);
            if ($authenticateFO == true) {
                $token = $emailFO->createToken("My Token");
                $emailFO->token = $token->accessToken;
                $emailFO['foto'] = request()->getSchemeAndHttpHost().'/storage/front_office/'.$emailFO->foto;

                return apiReturn($emailFO,"Berhasil login FO");
            } else {
                return apiUnauthorized("Maaf password salah");
            }
        } else {
            return apiUnauthorized("Maaf email anda tidak terdaftar");
        }
    }

    public function showAllFO()
    {
        try {
            $frontOffice = FrontOffice::get();
    
            foreach ($frontOffice as $key => $value) {
                $frontOffice[$key]['foto'] = request()->getSchemeAndHttpHost().'/storage/front_office/'.$value->foto;
            }
    
            return apiReturn($frontOffice,"Berhasil menampilkan front office");
        } catch (\Throwable $th) {
            return apiCatch();
        }
    }

    public function hapusFrontOffice($id)
    {
        try {
            $deleteFrontOffice = FrontOffice::find($id);
            $deleteFrontOffice->delete();

            return [
                'success' => true,
                'status_code' => 200,
                'status' => 'OK',
                'message' => 'Berhasil delete Front Office',
                'data' => $deleteFrontOffice
            ];
        } catch (\Throwable $th) {
            return apiCatch();
        }
    }
}
