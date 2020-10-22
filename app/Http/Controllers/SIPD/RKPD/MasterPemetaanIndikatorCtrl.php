<?php

namespace App\Http\Controllers\SIPD\RKPD;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Validator;
use Carbon\Carbon;
class MasterPemetaanIndikatorCtrl extends Controller
{
    //

	public function satuan(Request $request){
		$data=DB::table('rkpd.master_peta_indikator as i')
		->where('i.satuan','ilike','%'.$request->q.'%')
		->groupBy('i.satuan')
		->selectRaw("i.satuan as text, i.satuan as id")
		->limit(10)->get();
		return ['results'=>$data];
	}

    public function index(Request $request){
    	$data=DB::table('rkpd.master_peta_indikator as i')
    	->leftJoin('public.master_urusan as u','u.id','=','i.id_urusan')
    	->leftJoin('public.master_sub_urusan as su','su.id','=','i.id_sub_urusan')
    	->selectRaw("i.*,u.nama as nama_urusan,su.nama as nama_sub_urusan");

    	if($request->urusan){
    		$data=$data->where('i.id_urusan',$request->urusan);
    	}
    	if($request->sub_urusan){
    		$data=$data->where('i.id_sub_urusan',$request->sub_urusan);
    	}
    	if($request->q){
    		$data=$data->where('i.nama','ilike','%'.$request->q.'%');
    	}

    	$data=$data->get();
    	$urusan=DB::table('public.master_urusan')->whereIn('id',json_decode(env('URUSAN'),true))->get();
    	$sub_urusan=DB::table('public.master_sub_urusan')->whereIn('id_urusan',json_decode(env('URUSAN'),true))->get();

    	return view('sipd.rkpd.master.indikator')->with(['data'=>$data,'urusan'=>$urusan,'sub_urusan'=>$sub_urusan,'request'=>$request]);
    }


    public function create(){
    	$urusan=DB::table('public.master_urusan')->whereIn('id',json_decode(env('URUSAN'),true))->get();
    	$sub_urusan=DB::table('public.master_sub_urusan')->whereIn('id_urusan',json_decode(env('URUSAN'),true))->get();

    	return view('sipd.rkpd.master.indikator_create')->with(['urusan'=>$urusan,'sub_urusan'=>$sub_urusan]);
    }

    public function store(Request $request){
    	$valid=Validator::make($request->all(),[
    		'urusan'=>'required|exists:master_urusan,id',
    		'sub_urusan'=>'required|exists:master_sub_urusan,id',
    		'uraian'=>'required|string',
    		'tipe'=>'required|string',
    		'satuan'=>'required|string',
    		'target'=>'required|numeric',
    		'deskripsi'=>'nullable|string',
    		'follow'=>'nullable|numeric'



    	]);

    	if($valid->fails()){
    		return $valid->errors();
    	}


    	$data=DB::table('rkpd.master_peta_indikator')->insert([
    		'id_urusan'=>$request->urusan,
    		'id_sub_urusan'=>$request->sub_urusan,
    		'nama'=>strtoupper($request->uraian),
    		'tipe'=>strtoupper($request->tipe),
    		'target'=>(float)($request->target),
    		'satuan'=>strtoupper($request->satuan),
    		'deskripsi'=>$request->deskripsi,
    		'follow'=>!isset($request->follow)?null:((int)$request->follow),
    		'created_at'=>Carbon::now(),
    		'updated_at'=>Carbon::now(),
    	]);

    	if($data){
    		return redirect()->route('sipd.rkpd.ind.master');
    	}else{

    	}
    }


    public function edit($id){
    	$data=DB::table('rkpd.master_peta_indikator')->find($id);
    	if($data){
    		$urusan=DB::table('public.master_urusan')->whereIn('id',json_decode(env('URUSAN'),true))->get();
    		$sub_urusan=DB::table('public.master_sub_urusan')->whereIn('id_urusan',json_decode(env('URUSAN'),true))->get();
    		return view('sipd.rkpd.master.indikator_edit')->with(['urusan'=>$urusan,'sub_urusan'=>$sub_urusan,'data'=>$data]);
    	}

    	return abort(404);
    }


    public function update($id,Request $request){
    	$data=DB::table('rkpd.master_peta_indikator')->find($id);
    	if($data){

    		$valid=Validator::make($request->all(),[
	    		'urusan'=>'required|exists:master_urusan,id',
	    		'sub_urusan'=>'required|exists:master_sub_urusan,id',
	    		'uraian'=>'required|string',
	    		'tipe'=>'required|string',
	    		'satuan'=>'required|string',
	    		'target'=>'required|numeric',
    			'deskripsi'=>'nullable|string',
    			'follow'=>'nullable|numeric'
	    	]);

	    	if($valid->fails()){
	    		return $valid->errors();
	    	}


	    	$data=DB::table('rkpd.master_peta_indikator')->where('id',$id)->update([
	    		'id_urusan'=>$request->urusan,
	    		'id_sub_urusan'=>$request->sub_urusan,
	    		'nama'=>strtoupper($request->uraian),
	    		'tipe'=>strtoupper($request->tipe),
	    		'target'=>(float)($request->target),
	    		'satuan'=>strtoupper($request->satuan),
	    		'deskripsi'=>$request->deskripsi,
    			'follow'=>!isset($request->follow)?null:((int)$request->follow),


	    		'updated_at'=>Carbon::now(),
	    	]);

	    	if($data){
	    		return back();
	    	}else{
	    		return abort(500);

	    	}

    	}else{
    		return abort(404);

    	}

    }


}
