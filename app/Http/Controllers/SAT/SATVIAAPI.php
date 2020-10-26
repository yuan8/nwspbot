<?php

namespace App\Http\Controllers\SAT;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\SAT\LAPORAN;

class SATVIAAPI extends Controller
{
    //

    public function index($tahun){

		$data=Db::table('sat.laporan as l')->groupBy('l.pemda_id')->where([
			['l.pemda_id','!=',null],
			['l.entry_period_year','<=',$tahun],
			['l.entry_period_year','>=',$tahun-2]
		])->leftJoin('sat.master_pdam as pdam','pdam.pemda_id','=','l.pemda_id')
		->selectRaw("max(l.id) as id,max(l.entry_period_year) as entry_period_year,max(l.entry_period_month) as entry_period_month,max(l.pemda_id) as pemda_id,max(pdam.name) as nama_pdam, (select (case when length(d.id)<3 then d.nama else concat(d.nama,' - ',(select p.nama from public.master_daerah as p where p.id=left(d.id,2)) ) end)  from public.master_daerah as d where d.id = l.pemda_id) as nama_pemda")

		->get();
		// dd($data);



		return view('sat.api.index')->with(['data'=>$data,'tahun'=>$tahun]);

    }


    public function getData(){


    }

    public function api_index($tahun,Request $request){

        

        

  

    }
}
