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
        $data=[];
        foreach(Hp::tipe_indikator() as $keytipe=>$tipe){
            $data[$keytipe]['count']=DB::table('rkpd.master_peta_indikator as mi')
            ->whereRaw("mi.tipe='".$tipe."'")
            ->count();
            $data[$keytipe]['nama']=$tipe;
        }
    	return view('sipd.rkpd.dashboard.indikator.index')->with(['tahun'=>$tahun,'data'=>$data]);
    }


    public function detail($tahun,$keytipe){

    		$d=DB::table('rkpd.master_'.$tahun.'_kegiatan_indikator as ki')
            ->join('rkpd.master_'.$tahun.'_peta_indikator_kegiatan as pki','pki.kodedata','=','ki.kodedata')
            ->join('rkpd.master_'.$tahun.'_status_data as st','st.kodepemda','=','ki.kodepemda')
            ->whereRaw('st.status = 5')
    		->selectRaw("pki.id_master as id_master,ki.kodepemda,ki.tahun,'OUTPUT' as jenis,ki.id as k_i,null k_p,ki.tolokukur,ki.target,ki.satuan");


    		$data=DB::table('rkpd.master_peta_indikator as i')
    		->leftJoin('public.master_urusan as u','u.id','=','i.id_urusan')
    		->leftJoin('public.master_sub_urusan as su','su.id','=','i.id_sub_urusan')
    		->selectRaw("i.id,min(i.nama) as nama,min(i.follow) as follow,min(i.satuan) as satuan,min(i.target) as target,min(i.deskripsi) as deskripsi, min(i.id_urusan),min(i.id_sub_urusan) as id_sub_urusan,min(su.nama) as nama_sub_urusan,min(u.nama) as nama_urusan,count(distinct(ind.kodepemda)) as jumlah_pemda")
    		->groupBy(['i.id'])
    		->leftJoin(
    			DB::raw("(".DB::table('rkpd.master_'.$tahun.'_program_capaian as c')
                ->join('rkpd.master_'.$tahun.'_peta_indikator_program as ppi','ppi.kodedata','=','c.kodedata')
                ->join('rkpd.master_'.$tahun.'_status_data as stp','stp.kodepemda','=','c.kodepemda')
                ->whereRaw('stp.status = 5')
    			->selectRaw("ppi.id_master as id_master,c.kodepemda,c.tahun,'OUTCOME' as jenis,null as k_i,c.id k_p,c.tolokukur,c.target,c.satuan")->union($d)->toSql().") as ind")
    		,DB::raw('ind.id_master'),'=','i.id')
    		->where('tipe',$keytipe)->get();

	    	return view('sipd.rkpd.dashboard.indikator.detail')->with(['data'=>$data,'tahun'=>$tahun,'tipe'=>$keytipe]);
    }


    public function detail_indikator_kalkulasi($tahun,$id){
    		$data_ind=DB::table('rkpd.master_peta_indikator as i')->find($id);
    		if($data_ind){

                $d=DB::table('rkpd.master_'.$tahun.'_kegiatan_indikator as ki')
                ->join('rkpd.master_'.$tahun.'_peta_indikator_kegiatan as pki','pki.kodedata','=','ki.kodedata')
                 ->join('rkpd.master_'.$tahun.'_status_data as st','st.kodepemda','=','ki.kodepemda')
                    ->whereRaw('st.status = 5')
                ->selectRaw("pki.id_master as id_master,ki.kodepemda,ki.tahun,'OUTPUT' as jenis,ki.id as k_i,null p_i,ki.tolokukur,ki.target,ki.satuan");

                $data=DB::table('rkpd.master_peta_indikator as i')
                ->leftJoin('public.master_urusan as u','u.id','=','i.id_urusan')
                ->leftJoin('public.master_sub_urusan as su','su.id','=','i.id_sub_urusan')
                ->selectRaw("
                    min(i.id) as id_master,
                    (select (case when length(d.id)<3 then d.nama else concat(d.nama,' - ',(select p.nama from public.master_daerah as p where p.id=left(d.id,2)) ) end)  from public.master_daerah as d where d.id = ind.kodepemda) as nama_pemda,
                    ind.kodepemda,
                    sum(case when ind.k_i is not null then 1 else 0 end) jumlah_indikator_output,
                    sum(case when ind.p_i is not null then 1 else 0 end) jumlah_indikator_outcome
                ")
                ->groupBy(['ind.kodepemda'])
                ->leftJoin(
                    DB::raw("(".DB::table('rkpd.master_'.$tahun.'_program_capaian as c')
                    ->join('rkpd.master_'.$tahun.'_peta_indikator_program as ppi','ppi.kodedata','=','c.kodedata')
                     ->join('rkpd.master_'.$tahun.'_status_data as st','st.kodepemda','=','c.kodepemda')
                     ->whereRaw('st.status = 5')
                    ->selectRaw("ppi.id_master as id_master,c.kodepemda,c.tahun,'OUTCOME' as jenis,null as k_i,c.id p_i,c.tolokukur,c.target,c.satuan")->union($d)->toSql().") as ind")
                ,DB::raw('ind.id_master'),'=','i.id')
                ->where([
                    ['tipe','=',$data_ind->tipe],['ind.kodepemda','!=',null]])->get();


	    	return view('sipd.rkpd.dashboard.indikator.kalkulasi')->with(['tahun'=>$tahun,'data_ind'=>$data_ind,'data'=>$data]);

                // $data=DB::table('rkpd.master_'.$tahun.'_kegiatan_indikator as ki')
            // ->rightJoin('rkpd.master_peta_indikator as mi',[['mi.id','=','ki.'.$keytipe],['mi.tipe','=',DB::raw("'".$tipe."'")]])
            // ->whereRaw("ki.target ~ '^[0-9\.]+$'")
            // ->leftJoin('rkpd.master_'.$tahun.'_status_data as st','st.kodepemda','=','ki.kodepemda')
            // ->selectRaw("string_agg(distinct(ki.kodepemda),',') as kodepemda_list,count(distinct(ki.kodepemda)) as jumlah_pemda,min(mi.nama) as indikator_pusat,min(mi.target) as target_pusat,min(mi.satuan) as satuan_pusat,mi.tipe,
            //  sum(
            //      (case when (ki.follow is not null) then  
            //          (case when (ki.follow=1) then 
            //              (case when (ki.target::numeric>=min(mi.target))then 1 else 0 end ) 
            //           else 
                    //      (case when mi.follow=0 then 
                    //          (case when ki.target::numeric=min(mi.target) then 1 else 0 end)  
                    //      else
                    //          (case when mi.follow=-1 then (case when ki.target::numeric<=min(mi.target) then 1 else 0 end) else 0 end)
                    //      end)
                    //  end) 
                    // else                     
            //      ki.target::numeric end) as target_total, 
            //      (case when (min(mi.follow is not null)) then 'Jumlah Mencapai Nilai Persentase' else ki.satuan end) as satuan ")
            // ->groupBy(["mi.tipe","ki.satuan"])
            // ->where('mi.id',$id)
            // ->where('st.status',5)
            // ->get();


    		}
        }


    		public function detail_sebaran($tahun,$id,$kodepemda){
                $data_ind=DB::table('rkpd.master_peta_indikator as i')->find($id);
                $daerah=DB::table('public.master_daerah as d')->selectRaw("(case when length(d.id)<3 then d.nama else concat(d.nama,' - ',(select p.nama from public.master_daerah as p where p.id=left(d.id,2)) ) end) as nama_pemda")
                ->where('d.id',$kodepemda)->first();

                $d=DB::table('rkpd.master_'.$tahun.'_kegiatan_indikator as ki')
                ->join('rkpd.master_'.$tahun.'_peta_indikator_kegiatan as pki','pki.kodedata','=','ki.kodedata')
                ->join('rkpd.master_'.$tahun.'_status_data as st','st.kodepemda','=','ki.kodepemda')
                ->whereRaw('st.status = 5')
                ->selectRaw("ki.target ~ '^[0-9\.]+$' as can_calculate,pki.id_master as id_master,ki.kodepemda,ki.tahun,'OUTPUT' as jenis,ki.id as k_i,null p_i,ki.tolokukur,ki.target,ki.satuan,(select uraibidang from master_".$tahun."_program as p where p.id=ki.id_program) as nama_bidang,(select uraiskpd from master_".$tahun."_program as p where p.id=ki.id_program) as nama_skpd,(select uraiprogram from master_".$tahun."_program as p where p.id=ki.id_program) as nama_program,(select uraikegiatan from master_".$tahun."_kegiatan as k where k.id=ki.id_kegiatan) as nama_kegiatan");

                $data=DB::table('rkpd.master_peta_indikator as i')
                ->selectRaw("ind.*")
                ->leftJoin(
                    DB::raw("(".DB::table('rkpd.master_'.$tahun.'_program_capaian as c')
                    ->join('rkpd.master_'.$tahun.'_peta_indikator_program as ppi','ppi.kodedata','=','c.kodedata')
                     ->join('rkpd.master_'.$tahun.'_status_data as st','st.kodepemda','=','c.kodepemda')
                     ->whereRaw('st.status = 5')
                    ->selectRaw("c.target ~ '^[0-9\.]+$' as can_calculate,ppi.id_master as id_master,c.kodepemda,c.tahun,'OUTCOME' as jenis,null as k_i,c.id p_i,c.tolokukur,c.target,c.satuan,(select uraibidang from master_".$tahun."_program as p where p.id=c.id_program) as nama_bidang,(select uraiskpd from master_".$tahun."_program as p where p.id=c.id_program) as nama_skpd,(select uraiprogram from master_".$tahun."_program as p where p.id=c.id_program) as nama_program,null as nama_kegiatan")->union($d)->toSql().") as ind")
                ,DB::raw('ind.id_master'),'=','i.id')
                ->where([['i.id','=',$id],['ind.kodepemda','=',$kodepemda]])
                ->orderBy('ind.nama_bidang','asc')
                ->orderBy('ind.nama_skpd','asc')
                ->orderBy('ind.nama_program','asc')
                ->orderBy('ind.nama_kegiatan','desc')
                ->get();


                $kalkulasi=[];
                $program=[];

                foreach ($data as $key => $value) {
                    if(empty($value->satuan)){
                        $value->satuan='-';
                    }
                    if($value->can_calculate){
                        if(!isset($kalkulasi[trim(strtoupper($value->satuan),true)])){
                            $kalkulasi[trim(strtoupper($value->satuan),true)]=0;
                        }
                        $kalkulasi[strtoupper($value->satuan)]+=(float)$value->target;
                    }else{

                    }
                    $program[trim($value->nama_program,true)]=trim($value->nama_program,true);
                }

                return view('sipd.rkpd.dashboard.indikator.sebaran')->with(['data'=>$data,'kalkulasi'=>$kalkulasi,'data_ind'=>$data_ind,'daerah'=>$daerah,'tahun'=>$tahun,'program'=>$program]);

    		}


    
}
