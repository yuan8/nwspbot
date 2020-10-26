<?php

use Illuminate\Http\Request;

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

Route::prefix('connection-sinkronisasi')->group(function(){
	Route::prefix('sat')->group(function(){
		Route::get('last-record/{tahun}/{pemda?}','SAT@sat');
		Route::get('laporan-per-daerah/{tahun}/{pemda}','SAT@series_laporan');
		Route::get('list-data/{tahun}/{pemda?}','SAT@list_data');
	});
});


Route::prefix('sipd/rkpd/{tahun}')->group(function(){
	Route::post('/{kodepemda}/pemetaan','SIPD\RKPD\DATA@update_pemetaan_kegiatan')->name('api.sipd.rkpd.pemetaan.update.kegiatan');
	Route::post('/{kodepemda}/pemetaan/get-indikator/{context}','SIPD\RKPD\DATA@api_indikator')->name('api.sipd.rkpd.pemetaan.api.get.indikator');
	Route::get('/{kodepemda}/pemetaan/get-master-indikator/{context}','SIPD\RKPD\DATA@api_master_indikator')->name('api.sipd.rkpd.pemetaan.api.get.master.indikator');
	Route::post('/{kodepemda}/pemetaan/indikator','SIPD\RKPD\DATA@update_pemetaan_indikator')->name('api.sipd.rkpd.pemetaan.api.update.indikator');
});

Route::prefix('sat/api/{tahun}')->group(function(){
	Route::post('pemetaan','SAT\SATVIAAPI@pemetaan_data_store')->name('api.sat.api.pemetaan.update');
	
});




