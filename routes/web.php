<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('initial/{tahun}')->group(function(){
	Route::get('rkpd','SIPD\RKPD\InitCtrl@init');
	Route::get('sat','SAT\InitCtrl@init');

});


Route::get('/box-sipd/{tahun?}', 'BOT@get_sipd_rkpd')->name('box.sipd');
Route::get('/box-sirup/{tahun?}', 'BOT@get_sirup')->name('box.sirup');
Route::get('/box-nuwsp-api/{tahun?}', 'BOT@get_nuwsp_api')->name('box.nuwsp.api');



Route::prefix('bot-dss/')->middleware('auth:web')->group(function () {
		Route::prefix('sipd/rkpd/master')->group(function(){
			Route::get('/indikator','SIPD\RKPD\MasterPemetaanIndikatorCtrl@index')->name('sipd.rkpd.ind.master');
			Route::get('/indikator/satuan','SIPD\RKPD\MasterPemetaanIndikatorCtrl@satuan')->name('sipd.rkpd.ind.master.satuan');
			Route::get('/indikator','SIPD\RKPD\MasterPemetaanIndikatorCtrl@index')->name('sipd.rkpd.ind.master');

			Route::get('/indikator/create','SIPD\RKPD\MasterPemetaanIndikatorCtrl@create')->name('sipd.rkpd.ind.master.create');
			Route::get('/indikator/edit/{id}','SIPD\RKPD\MasterPemetaanIndikatorCtrl@edit')->name('sipd.rkpd.ind.master.edit');
			Route::post('/indikator/update/{id}','SIPD\RKPD\MasterPemetaanIndikatorCtrl@update')->name('sipd.rkpd.ind.master.update');


			Route::post('/indikator/create','SIPD\RKPD\MasterPemetaanIndikatorCtrl@store')->name('sipd.rkpd.ind.master.store');



		});
		Route::prefix('sipd/rkpd/{tahun}')->group(function () {

			Route::get('/','SIPD\RKPD\LISTDATA@index')->name('sipd.rkpd');
			Route::get('/dashboard/indikator','SIPD\RKPD\DashboardIndikatorCtrl@index')->name('sipd.rkpd.d.indikator');
			Route::get('/dashboard/indikator/detail/{tipe}','SIPD\RKPD\DashboardIndikatorCtrl@detail')->name('sipd.rkpd.d.indikator.detail');
			Route::get('/dashboard/indikator-pusat/detail/{id}','SIPD\RKPD\DashboardIndikatorCtrl@detail_indikator_kalkulasi')->name('sipd.rkpd.d.indikator.kelkulasi.detail');



			Route::get('/pemetaan/{kodepemda}','SIPD\RKPD\DATA@pemetaan')->name('sipd.rkpd.pemetaan');
			Route::get('/pemetaan/{kodepemda}/get-data','SIPD\RKPD\DATA@api_pemetaan')->name('sipd.rkpd.pemetaan.data');


			Route::get('/handle','SIPD\RKPD\LISTDATA@needHandle')->name('sipd.rkpd.handle');
			Route::get('/get/json/{json_id}','SIPD\RKPD\LISTDATA@getjson')->name('sipd.rkpd.json');
			Route::get('/get-list','SIPD\RKPD\LISTDATA@getData')->name('sipd.rkpd.list.update');
			Route::get('/get-data/{kodepemda}/{status}/{transactioncode}/{console?}','SIPD\RKPD\GETDATA@getData')->name('sipd.rkpd.data.update');
			Route::get('/get-data-masive','SIPD\RKPD\GETDATA@store_masive')->name('sipd.rkpd.data.masive');
			Route::get('/download/{kodepemda?}','SIPD\RKPD\DATA@download')->name('sipd.rkpd.data.download');
			Route::get('/data/{kodepemda?}','SIPD\RKPD\IO@index')->name('sipd.rkpd.data.show');
		});


	Route::prefix('sirup')->middleware('auth:web')->group(function () {
		Route::prefix('paket-pekerjaan/{tahun}')->group(function () {
			Route::get('/','SIRUP\PAKETPEKERJAAN\LISTDATA@index')->name('sirup.paket');
			Route::get('/get-data/','SIRUP\PAKETPEKERJAAN\GETDATA@getData')->name('sirup.paket.data.update');
		});

	});

	Route::prefix('nuwsp')->middleware('auth:web')->group(function () {
		Route::prefix('sat/via-api')->group(function () {
			Route::prefix('data/{tahun}')->group(function () {
				Route::get('/','SAT\SATVIAAPI@index')->name('nuwsp.sat');
				Route::get('/get-list','SIPD\RKPD\LISTDATA@getData')->name('nuwsp.sat.list.update');
				Route::get('/get-data/{kodepemda}/{status}/{transactioncode}','SIPD\RKPD\GETDATA@getData')->name('nuwsp.sat.data.update');
			});
		});

		Route::prefix('sat/via-scrap')->middleware('auth:web')->group(function () {
			Route::prefix('data/{tahun}')->group(function () {
				Route::get('/','SIPD\RKPD\LISTDATA@index')->name('nuwsp.sat.scr');
				Route::get('/get-list','SIPD\RKPD\LISTDATA@getData')->name('nuwsp.sat.list.update.scr');
				Route::get('/get-data/{kodepemda}/{status}/{transactioncode}','SIPD\RKPD\GETDATA@getData')->name('nuwsp.sat.data.update.scr');
			});
		});
	});
});

Auth::routes();

