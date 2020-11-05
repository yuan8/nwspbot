<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class Macth extends Controller
{
    //

    public function init($tahun,Request $request){
    	$kodepemda=0;
    	if($request->kodepemda){
    		$kodepemda=$request->kodepemda;	
    	}
    	$cont_update=0;

    	$d=DB::connection('old')->table('public.master_daerah as d')
    	->selectRaw("d.id,min(d.nama) as nama")
    	->join('rkpd.master_'.$tahun.'_kegiatan as k',[['k.kodepemda','=','d.id']])
    	->where([['k.id_urusan','!=',0],['kodepemda','>',$kodepemda],['d.kode_daerah_parent','=',null]])
		->orwhere([['k.id_urusan','!=',null],['kodepemda','>',$kodepemda],['d.kode_daerah_parent','=',null]])
    	->orderBy('d.id','asc')->groupBy('d.id')->first();
		
		if($d){
			$data=DB::connection('old')->table('rkpd.master_'.$tahun.'_kegiatan')->where([['id_urusan','!=',0],['kodepemda','=',$d->id]])
			->orwhere([['id_urusan','!=',null],['kodepemda','=',$d->id]])->get();
			foreach ($data as $key => $e) {
				# code...
				$i=DB::connection('pgsql')->table('rkpd.master_'.$tahun.'_kegiatan')->where([
					['kodepemda','=',$e->kodepemda],
					['kodeprogram','=',$e->kodeprogram],
					['kodekegiatan','=',$e->kodekegiatan],
				])->update([
					'id_urusan'=>$e->id_urusan,
					'id_sub_urusan'=>$e->id_sub_urusan
				]);


				if($i){
					$cont_update+=1;
				}
				
			}

			return array('link'=>route('init-match',['tahun'=>$tahun,'kodepemda'=>$d->id]),
				'data'=>'<tr>
					<td>'.$d->id.'</td>
					<td>'.$d->nama.'</td>
					<td>'.number_format(count($data)).' KEGIATAN</td>
					<td>'.number_format($cont_update).' KEGIATAN</td>
				</tr>'
				);
		}else{
			return array('link'=>null,
				'data'=>'<tr>
					<td colspan="4">done</td>
			
				</tr>'
				);
		}

		

    	
    }

    public function index($tahun){
    	return view('macth_index')->with('tahun',$tahun);
    }


    public function air_minum($tahun){
    	$d=DB::table('public.master_daerah as d')
    	->selectRaw("d.id,min(d.nama) as nama, count(k.*) jumlah_kegiatan")
    	->join('rkpd.master_'.$tahun.'_kegiatan as k',[['k.kodepemda','=','d.id']])
    	->where([['k.id_urusan','=',3],['k.id_sub_urusan','=',12],['d.kode_daerah_parent','=',null]])
    	->orderBy('d.id','asc')->groupBy('d.id')->first();

    	return view('match_air_minum')->with('data',$d);

    }
}
