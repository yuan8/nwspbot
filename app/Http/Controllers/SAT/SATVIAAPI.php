<?php

namespace App\Http\Controllers\SAT;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\SAT\LAPORAN;
use App\SAT\MASTERPDAM;

class SATVIAAPI extends Controller
{
    //

    public function index($tahun){

		$data=Db::table('sat.laporan as l')->groupBy('l.pemda_id')->where([
			['l.pemda_id','!=',null],
			['l.entry_period_year','<=',$tahun],
			['l.entry_period_year','>=',$tahun-2]
		])->leftJoin('sat.master_pdam as pdam','pdam.pemda_id','=','l.pemda_id')
		->selectRaw("max(l.id) as id,max(l.entry_period_year) as entry_period_year,max(l.entry_period_month) as entry_period_month,max(l.pemda_id) as pemda_id,max(pdam.name) as nama_pdam, (select (case when length(d.id)<3 then d.nama else concat(d.nama,' - ',(select p.nama from public.master_daerah as p where p.id=left(d.id,2)) ) end)  from public.master_daerah as d where d.id = l.pemda_id) as nama_pemda,
			(select ld.data from sat.laporan_kategori as ld where ld.id_laporan = max(l.id) and ld.id_question = 1 limit 1) as laporan_kinerja ,
			(select ld.data from sat.laporan_kategori as ld where ld.id_laporan = max(l.id) and ld.id_question = 2 limit 1) as laporan_keuangan,
			(select ld.data from sat.laporan_kategori as ld where ld.id_laporan = max(l.id) and ld.id_question = 3 limit 1) as laporan_oprasional,
			(select ld.data from sat.laporan_kategori as ld where ld.id_laporan = max(l.id) and ld.id_question = 69 limit 1) as laporan_sdm    
			")

		->get();
		// dd($data);



		return view('sat.api.index')->with(['data'=>$data,'tahun'=>$tahun]);

    }


    public function pemetaan_data($tahun){
    	$data=LAPORAN::orderBy('pemda_id','desc')->selectRaw("*,(select (case when length(d.id)<3 then d.nama else concat(d.nama,' - ',(select p.nama from public.master_daerah as p where p.id=left(d.id,2)) ) end)  from public.master_daerah as d where d.id = pemda_id) as nama_pemda")->with(['_pdam'])->get()->toArray();
    	$pemda=DB::table('public.master_daerah as d')
    	->selectRaw("d.*,(case when length(d.id)<3 then d.nama else concat(d.nama,' - ',(select p.nama from public.master_daerah as p where p.id=left(d.id,2)) ) end) as nama_pemda")
    	->get();

    	return view('sat.api.pemetaan')->with(['data'=>$data,'tahun'=>$tahun,'pemda'=>$pemda]);
    }

    public function detail($tahun,$kodelaporan){
    	$data=LAPORAN::where([['entry_period_year','<=',$tahun],['entry_period_year','>=',$tahun-2],['id','=',$kodelaporan]])
    	->selectRaw("sat.laporan.*,(select (case when length(d.id)<3 then d.nama else concat(d.nama,' - ',(select p.nama from public.master_daerah as p where p.id=left(d.id,2)) ) end)  from public.master_daerah as d where d.id = sat.laporan.pemda_id) as nama_pemda")
    	->with(['_ketegori','_pdam','_pelayanan','_operasional','_keuangan','_pemerintah_daerah'])->first()->toArray();

    		$data_else=LAPORAN::where([['entry_period_year','<=',$tahun],['entry_period_year','>=',$tahun-2],['pemda_id',$data['pemda_id']],['id','!=',$data['id']]])->get()->toArray();



		return view('sat.api.detail')->with(['data'=>$data,'tahun'=>$tahun,'data_else'=>$data_else]);
    	

    }

    public function api_index($tahun,Request $request){

        

        

  

    }

    public function pemetaan_data_store(Request $request){
    	$F=LAPORAN::where('id',$request->id)->first();
    	if($F){
    		$pdam=MASTERPDAM::where('pemda_id',$request->pemda_id)->first();

    		if(!$pdam){
    			MASTERPDAM::create(['pemda_id'=>$request->pemda_id,'name'=>$F->site_name,'address'=>$F->address,'provincies_id'=>substr((string)$request->pemda_id,0,1),'regencies_id'=>$request->pemda_id]);
    		}

    		$data=LAPORAN::where('id',$request->id)->update((array)$request->all());
    		return $data;

    	}
    
    }
}
