<?php

namespace App\Http\Controllers\SIPD\RKPD;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Hp;
class DashboardIndikatorCtrl extends Controller
{
    //

    public function index($tahun){
    	// $data=DB::table('rkpd.master_'.$tahun.'_kegiatan_indikator as ki')->where('satuan','%')->count();
    	// $data=[];
    	// foreach(Hp::tipe_indikator() as $keytipe=>$tipe){
    	// 	$data[$keytipe]['nama']=$tipe;
    	// 	$data[$keytipe]['data']=DB::table('rkpd.master_'.$tahun.'_kegiatan_indikator as ki')
	    // 	->rightJoin('rkpd.master_peta_indikator as mi',[['mi.id','=','ki.'.$keytipe],['mi.tipe','=',DB::raw("'".$tipe."'")]])
	    // 	->whereRaw("ki.target ~ '^[0-9\.]+$'")
	    // 	->selectRaw("count(distinct(ki.kodepemda)) as jumlah_pemda,min(mi.nama) as indikator_pusat,min(mi.target) as target_pusat,min(mi.satuan) as satuan_pusat,mi.tipe,sum(case when (ki.satuan='%') then (case when ((mi.satuan)='%') then 1 else 0 end) else ki.target::numeric end) as target_total, (case when (ki.satuan='%' and min(mi.satuan)='%') then 'Mencapai nilai Persentase' else ki.satuan end) as satuan ")
	    // 	->groupBy(["mi.tipe","ki.satuan"])
	    // 	->get();
    	// }

    	// dd($data);
    	
    	return view('sipd.rkpd.dashboard.indikator.index')->with(['tahun'=>$tahun]);
    }


    public function detail($tahun,$keytipe){
    		$d=DB::table('rkpd.master_'.$tahun.'_kegiatan_indikator as ki')->whereRaw('ki.'.$keytipe.' IS NOT NULL')
    		->selectRaw("ki.kodepemda,ki.tahun,'OUTPUT' as jenis,ki.id as k_i,null k_p,ki.tolokukur,ki.target,ki.satuan,ki.rpjmn,ki.spm,ki.sdgs,ki.lainya");
    		$tipe=Hp::tipe_indikator()[$keytipe];
    		$data=DB::table('rkpd.master_peta_indikator as i')
    		->leftJoin('public.master_urusan as u','u.id','=','i.id_urusan')
    		->leftJoin('public.master_sub_urusan as su','su.id','=','i.id_sub_urusan')
    		->selectRaw("i.id,min(i.nama) as nama,min(i.satuan) as satuan,min(i.target) as target,min(i.deskripsi) as deskripsi, min(i.id_urusan),min(i.id_sub_urusan) as id_sub_urusan,min(su.nama) as nama_sub_urusan,min(u.nama) as nama_urusan,count(distinct(ind.kodepemda)) as jumlah_pemda")
    		->groupBy(['i.id'])
    		->leftJoin(
    			DB::raw("(".DB::table('rkpd.master_'.$tahun.'_program_capaian as c')
    				->whereRaw('c.'.$keytipe.' IS NOT NULL')
    			->selectRaw("c.kodepemda,c.tahun,'OUTCOME' as jenis,null as k_i,c.id k_p,c.tolokukur,c.target,c.satuan,c.rpjmn,c.spm,c.sdgs,c.lainya")->union($d)->toSql().") as ind")
    		,DB::raw('ind.'.$keytipe),'=','i.id')
    		->where('tipe',$tipe)->get();





    		// $data=[];
    	

	    	return view('sipd.rkpd.dashboard.indikator.detail')->with(['data'=>$data,'tahun'=>$tahun,'tipe'=>$tipe]);
    }


    public function detail_indikator_kalkulasi($tahun,$id){
    		$data_ind=DB::table('rkpd.master_peta_indikator as i')->find($id);
    		if($data_ind){

    			$tipe=$data_ind->tipe;
    			$keytipe=array_search($tipe, Hp::tipe_indikator());

    			$d=DB::table('rkpd.master_'.$tahun.'_kegiatan_indikator as ki')->whereRaw('ki.'.$keytipe.' IS NOT NULL')
    				->selectRaw("ki.kodepemda,ki.tahun,'OUTPUT' as jenis,ki.id as k_i,null p_i,ki.tolokukur,ki.target,ki.satuan,ki.rpjmn,ki.spm,ki.sdgs,ki.lainya");


	    		$data=DB::table(DB::raw(DB::raw("(".DB::table('rkpd.master_'.$tahun.'_program_capaian as c')
    				->whereRaw('c.'.$keytipe.' IS NOT NULL')
    			->selectRaw("c.kodepemda,c.tahun,'OUTCOME' as jenis,null as k_i,c.id p_i,c.tolokukur,c.target,c.satuan,c.rpjmn,c.spm,c.sdgs,c.lainya")->union($d)->toSql().") as ind")))
	    		->rightJoin('rkpd.master_peta_indikator as mi',DB::raw('mi.id'),'=',DB::raw('ind.'.$keytipe))
	    		->where('mi.id',$id)
	    		->selectRaw("
	    			(select (case when length(d.id)<3 then d.nama else concat(d.nama,' - ',(select p.nama from public.master_daerah as p where p.id=left(d.id,2)) ) end)  from public.master_daerah as d where d.id = ind.kodepemda) as nama_pemda,
	    			ind.kodepemda,
	    			sum(case when ind.k_i is not null then 1 else 0 end) jumlah_indikator_output,
	    			sum(case when ind.p_i is not null then 1 else 0 end) jumlah_indikator_outcome

	    		")
	    		->groupBy(['ind.kodepemda'])
	    		->get();


	    		

    		// $data=DB::table('rkpd.master_'.$tahun.'_kegiatan_indikator as ki')
	    	// ->rightJoin('rkpd.master_peta_indikator as mi',[['mi.id','=','ki.'.$keytipe],['mi.tipe','=',DB::raw("'".$tipe."'")]])
	    	// ->whereRaw("ki.target ~ '^[0-9\.]+$'")
	    	// ->leftJoin('rkpd.master_'.$tahun.'_status_data as st','st.kodepemda','=','ki.kodepemda')
	    	// ->selectRaw("string_agg(distinct(ki.kodepemda),',') as kodepemda_list,count(distinct(ki.kodepemda)) as jumlah_pemda,min(mi.nama) as indikator_pusat,min(mi.target) as target_pusat,min(mi.satuan) as satuan_pusat,mi.tipe,
	    	// 	sum(
	    	// 		(case when (ki.follow is not null) then  
    		// 			(case when (ki.follow=1) then 
    		// 			 	(case when (ki.target::numeric>=min(mi.target))then 1 else 0 end ) 
    		// 			 else 
			 		// 		(case when mi.follow=0 then 
			 		// 			(case when ki.target::numeric=min(mi.target) then 1 else 0 end)  
			 		// 		else
			 		// 			(case when mi.follow=-1 then (case when ki.target::numeric<=min(mi.target) then 1 else 0 end) else 0 end)
			 		// 		end)
			 		// 	end) 
			 		// else			 			
	    	// 		ki.target::numeric end) as target_total, 
	    	// 		(case when (min(mi.follow is not null)) then 'Jumlah Mencapai Nilai Persentase' else ki.satuan end) as satuan ")
	    	// ->groupBy(["mi.tipe","ki.satuan"])
	    	// ->where('mi.id',$id)
	    	// ->where('st.status',5)
	    	// ->get();

	    	return view('sipd.rkpd.dashboard.indikator.kalkulasi')->with(['tahun'=>$tahun,'data_ind'=>$data_ind,'data'=>$data]);


    		}


    }
}
