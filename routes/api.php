<?php

use App\Http\Controllers\Akademik\LaporanAbsensiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    
    Route::prefix('akademik')->group(function () {
        Route::get('/','Akademik\DashboardController@index');
        Route::get('/allfrontoffice','Auth\AuthController@showAllFO');
        Route::post('/login','Auth\AuthController@loginAkademik');
        Route::post('/register','Auth\AuthController@registerFrontOffice');
        Route::delete('/frontoffice/destroy/{id}','Auth\AuthController@hapusFrontOffice');
        Route::get('/profile','Akademik\ProfilesController@index')->middleware('multiauth:akademik');
        Route::put('/profile/update','Akademik\ProfilesController@update')->middleware('multiauth:akademik');;
        
        Route::prefix('programkursus')->group(function () {
            Route::get('/','Akademik\ProgramKursusController@index');
            Route::post('/store','Akademik\ProgramKursusController@store');
            Route::put('/update/{id}','Akademik\ProgramKursusController@update');
            Route::delete('/destroy/{id}','Akademik\ProgramKursusController@destroy');
        });

        Route::prefix('siswa')->group(function () {
            Route::get('/','Akademik\SiswaController@index');
            Route::put('/ubahstatus/{id}','Akademik\SiswaController@ubahStatus');
            Route::post('/store','Akademik\SiswaController@store');
            Route::put('/update/{id}','Akademik\SiswaController@update');
            Route::delete('/destroy/{id}','Akademik\SiswaController@destroy');
        });

        Route::prefix('instruktur')->group(function () {
            Route::get('/','Akademik\InstrukturController@index');
            Route::post('/store','Akademik\InstrukturController@store');
            Route::put('/update/{id}','Akademik\InstrukturController@update');
            Route::delete('/destroy/{id}','Akademik\InstrukturController@destroy');
        });

        Route::prefix('jadwal')->group(function () {
            Route::get('/','Akademik\JadwalController@index');
            Route::get('/programinstruktur','Akademik\JadwalController@getInstrukturProgramkursus');
            Route::post('/store','Akademik\JadwalController@store');
            Route::put('/update/{id}','Akademik\JadwalController@update');
            Route::delete('/destroy/{id}','Akademik\JadwalController@destroy');
        });

        Route::prefix('sertifikat')->group(function () {
            Route::get('/sertifikasi','Akademik\SertifikatController@index');
            Route::get('/tersertifikasi','Akademik\SertifikatController@indexTersertifikasi');
            Route::post('/store','Akademik\SertifikatController@store');
            Route::put('/update/{id}','Akademik\SertifikatController@update');
            Route::get('/getkompetensi/{id}','Akademik\SertifikatController@getKompetensi');
            Route::get('/getsertifikat/{id}','Akademik\SertifikatController@cetakSertifikat');
        });

        Route::prefix('ruangkelas')->group(function () {
            Route::get('/',);
        });

        Route::prefix('laporanabsebsi')->group(function () {
            Route::get('/{id_program}','Akademik\LaporanAbsensiController@index');
            Route::get('/programkursus/tampil','Akademik\LaporanAbsensiController@getProgramKursus');
        });

        Route::prefix('ruangkelas')->group(function () {
            Route::get('/','Akademik\RuangKelasController@index');
            Route::post('/store','Akademik\RuangKelasController@store');
            Route::put('/update/{id}','Akademik\RuangKelasController@update');
            Route::delete('/destroy/{id}','Akademik\RuangKelasController@destroy');
        });

    });

    Route::prefix('frontoffice')->group(function () {
        Route::get('/','FrontOffice\DashboardController@index');
        Route::post('/login','Auth\AuthController@loginFrontOffice');
        Route::get('/profile','FrontOffice\ProfilesController@index')->middleware('multiauth:front_office');
        Route::put('/profile/update','FrontOffice\ProfilesController@update')->middleware('multiauth:front_office');

        Route::prefix('siswa')->group(function () {
            Route::get('/','FrontOffice\SiswaController@index');
            Route::get('/pending','FrontOffice\SiswaController@siswaPending');
            Route::post('/store','FrontOffice\SiswaController@store');
            Route::put('/update/{id}','FrontOffice\SiswaController@update');
            Route::get('/{id}','FrontOffice\SiswaController@pesenanMasBudi');
            Route::put('/toaktif/{id}','FrontOffice\SiswaController@changeStatusToActivate');
        });

        Route::prefix('programkursus')->group(function () {
            Route::get('/','FrontOffice\ProgramKursusController@index');
            Route::post('/store','FrontOffice\ProgramKursusController@store');
            Route::put('/update/{id}','FrontOffice\ProgramKursusController@update');
            Route::delete('/destroy/{id}','FrontOffice\ProgramKursusController@destroy');
        });

        Route::prefix('instruktur')->group(function () {
            Route::get('/','FrontOffice\InstrukturController@index');
        });

        Route::prefix('jadwal')->group(function () {
            Route::get('/','FrontOffice\JadwalController@index');
        });

        Route::prefix('absensi')->group(function () {
            Route::get('/','FrontOffice\PresensiController@index');
            Route::get('/{id}','FrontOffice\PresensiController@getSiswaProg');
            Route::post('/store','FrontOffice\PresensiController@store');
        });

        Route::prefix('nilaisiswa')->group(function () {
            Route::get('/','FrontOffice\NilaiSiswaController@index');
        });


    });
});
