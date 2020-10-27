<?php

namespace App\Http\Controllers\SIPD\RKPD;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Storage;
use Carbon\Carbon;
use DB;
use Validator;
use Illuminate\Support\Facades\Schema;
class DOKUMEN extends Controller
{
    //

    public function index($tahun,Request $request){
    	if(!Schema::connection('pgsql')->hasTable('rkpd.'.'master_'.$tahun.'_status')){
			return view('sipd.rkpd.index')->with(['data'=>[],'page_block'=>true,'tahun'=>$tahun]);
		}

        $last_list_date=DB::table('rkpd.'.'master_'.$tahun.'_status as rk')->orderBy('updated_at','DESC')->pluck('updated_at')->first();

        $where=[];
        if($request->match){
            $where[]='rkpd_match = '.(string)$request->match;
        }

        if($request->status!=null){
            $where[]='status = '.(string)$request->status;
        }

         if($request->q){
            $where[]="nama_pemda ilike '%".(string)$request->q."%'";
        }



		$data=DB::table( DB::raw("(".DB::table('public.master_daerah as ld')
        ->join('rkpd.'.'master_'.$tahun.'_status as rk','rk.kodepemda','=','ld.id')
        ->leftJoin('rkpd.'.'master_'.$tahun.'_status_data as d',[['d.kodepemda','=','rk.kodepemda'],['d.tahun','=','rk.tahun'],['d.status','=','rk.status'],['d.transactioncode','=','rk.transactioncode']])
        ->selectRaw("rk.*,d.matches as rkpd_match,d.pagu as pagu_store,
                (case when length(ld.id::text)<3 then ld.nama else concat(ld.nama,' - ',(select p.nama from public.master_daerah as p where p.id::text = left(ld.id::text,2))) end) as nama_pemda,
                ld.id as kodepemda_m,
                 rk.attemp as attemp,
                d.id as stored
        ")
        ->where('rk.method',DB::RAW("'DOKUMEN'"))
        ->orderBy(DB::raw('(ld.id)'),'ASC')->toSql().") as dd"));
       

        if(count($where)>0){
            $data->whereRaw(implode(' and ', $where));
        }

        $data=$data->get()->toArray();

        $pemda=DB::table('public.master_daerah as d')
        ->selectRaw("id,(case when (length(d.id::text)>3) then concat(d.nama,' - ',(select p.nama from public.master_daerah as p where p.id=d.kode_daerah_parent::text limit 1)) else d.nama end) as nama_pemda")->orderBy('id','asc')
        ->get()->toArray();

        $bidang=DB::table('rkpd.master_'.$tahun.'_bidang')->where('uraibidang','!=',null)->groupBy(DB::raw("uraibidang"))->selectRaw("upper(uraibidang) as uraibidang")->get()->pluck('uraibidang');

        $urusan=DB::table('public.master_urusan')->whereIn('id',json_decode(env('URUSAN'),true))->selectRaw("upper(nama) as nama,id")->get()->toArray();


		return view('sipd.rkpd.dokumen.index')->with(['data'=>$data,'tahun'=>$tahun,'last_list_date'=>$last_list_date,'request'=>$request,'pemda'=>$pemda,'bidang'=>$bidang,'urusan'=>$urusan]);
    }


    public function upload($tahun){
    	$daerah=DB::table('public.master_daerah as ld')->selectRaw("*,(case when length(ld.id::text)<3 then ld.nama else concat(ld.nama,' - ',(select p.nama from public.master_daerah as p where p.id::text = left(ld.id::text,2))) end) as nama_pemda")->get();
    	return view('sipd.rkpd.dokumen.upload')->with(['tahun'=>$tahun,'daerah'=>$daerah]);
    }

    public function store($tahun,Request $request){
    	$valid=Validator::make($request->all(),[
    		'kodepemda'=>'exists:master_'.$tahun.'_status,kodepemda',
    		'file'=>'file|mimes:xlsx,xls'
    	]);

    	if($valid->fails()){
    		return back();
    	}

    	return 's';
    }
}
