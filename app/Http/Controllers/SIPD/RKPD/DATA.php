<?php

namespace App\Http\Controllers\SIPD\RKPD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\SIPD\RKPD\ModelBidang;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use DB;
use Storage;
class DATA extends Controller
{
    //
      static $abj=['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ','CA','CB','CC','CD','CE','CF','CG','CH','CI','CJ','CK','CL','CM','CN','CO','CP','CQ','CR','CS','CT','CU','CV','CW','CX','CY','CZ'];

      static $bidang=[];
      static $skpd=[];
      static $program=[];
      static $kegiatan=[];
      static $subkegiatan=[];


      public function update_pemetaan_kegiatan($tahun,$kodepemda,Request $request){
        $up= DB::table('rkpd.master_'.$tahun.'_kegiatan')->whereIn('id',$request->id)->update($request->data);
        if($up){

            $data=DB::table('rkpd.master_'.$tahun.'_kegiatan')->whereIn('id',(array)$request->id)->get();
            $data_save=[
                'kegiatan'=>[],
                'indikator_kegiatan'=>[],
                'indikator_program'=>[],

             ];
            if(file_exists(storage_path('app/BOT/SIPD/RKPD/'.$tahun.'/JSON-PEMETAAN/'.$kodepemda.'.json'))){
                $data_save=json_decode(file_get_contents(storage_path('app/BOT/SIPD/RKPD/'.$tahun.'/JSON-PEMETAAN/'.$kodepemda.'.json')),true);
            }

            foreach ($data as $key => $value) {
                # code...
                $data_save['kegiatan'][$value->kodedata]['kodedata']=$value->kodedata;
                $data_save['kegiatan'][$value->kodedata]['id_sub_urusan']=$value->id_sub_urusan;
                $data_save['kegiatan'][$value->kodedata]['id_urusan']=$value->id_urusan;


            }

            Storage::put('BOT/SIPD/RKPD/'.$tahun.'/JSON-PEMETAAN/'.$kodepemda.'.json',json_encode($data_save));

            return $data;


        }

      }


      public function pemetaan($tahun,$kodepemda,Request $request){
        DB::enableQueryLog(); // Enable query log

        $status=DB::table('rkpd.master_'.$tahun.'_status_data')->where([
            'kodepemda'=>$kodepemda,
            'tahun'=>$tahun

        ])->first();

        $daerah=DB::table('public.master_daerah')->where([
            'id'=>$kodepemda,

        ])->first();

        $bidang=DB::table('rkpd.master_'.$tahun.'_bidang')->where([
            'kodepemda'=>$kodepemda,
            'tahun'=>$tahun
        ])
        ->groupBy('uraibidang')
        ->selectRaw('uraibidang')->get()->pluck('uraibidang');

        $skpd=DB::table('rkpd.master_'.$tahun.'_bidang')->where([
            'kodepemda'=>$kodepemda,
            'tahun'=>$tahun
        ]);


        if($request->bidang){
            $skpd=$skpd->where('uraibidang',$request->bidang);
        }

        $skpd=$skpd->groupBy('uraiskpd')->selectRaw('uraiskpd,max(uraibidang)')->get()->pluck('uraiskpd');



        $data=DB::table('rkpd.'.'master_'.$tahun.'_kegiatan as k')->where([
          'k.kodepemda'=>$kodepemda,
          'k.tahun'=>$tahun
        ])
        ->groupBy('uraikegiatan')
        ->join('rkpd.master_'.$tahun.'_program as p','p.id','=','k.id_program')

        ->selectRaw("
             string_agg(k.id::text,',') as ids,string_agg(p.id::text,',') as p_ids,max(k.kodekegiatan) as kodekegiatan,max(k.uraikegiatan) as uraikegiatan,max(k.kodepemda) as kodepemda,sum(k.pagu) as pagu ,max(p.kodeskpd) as p_kodeskpd, max(p.uraiskpd) as p_uraiskpd,max(p.kodebidang) as p_kodebidang,max(p.uraibidang) as p_uraibidang,max(p.kodeprogram) as p_kodeprogram,max(p.uraiprogram) as p_uraiprogram,max(k.id_urusan) as id_urusan,max(k.id_sub_urusan) as id_sub_urusan,(select u.nama from public.master_urusan as u where u.id=max(k.id_urusan)) as nama_urusan,(select s.nama from public.master_sub_urusan as s where s.id=max(k.id_sub_urusan)) as nama_sub_urusan");

        if(($request->bidang)){
            $data=$data->where('p.uraibidang','ilike',$request->bidang);

        }
        if(($request->q)){
            $data=$data->where('k.uraikegiatan','ilike','%'.$request->q.'%');

        }
        if(($request->skpd) and (in_array($request->skpd,(array)$skpd->toArray()))){

            $data=$data->where('p.uraiskpd','ilike',$request->skpd);
        }else if($request->skpd){
            return redirect()->route('sipd.rkpd.pemetaan',['tahun'=>$tahun,'kodepemda'=>$kodepemda,'bidang'=>$request->bidang]);

        }


        $urusan=Db::table('public.master_urusan')->whereIn('id',json_decode(env('URUSAN'),true))->get();
        $sub_urusan=Db::table('public.master_sub_urusan')->whereIn('id_urusan',json_decode(env('URUSAN'),true))->get();

        $data=$data->get();

        $data_rekap=$data->pluck('pagu');




        return view('sipd.rkpd.pemetaan')->with([

          'data'=>$data,
          'tahun'=>$tahun,
          'skpd'=>$skpd,
          'bidang'=>$bidang,
          'request'=>$request,
          'status'=>$status,
          'daerah'=>$daerah,
          'urusan'=>$urusan,
          'sub_urusan'=>$sub_urusan,
          'pagu'=>array_sum($data_rekap->toArray())

        ]);


        // (select sum(pagu) as pagu from rkpd.master_".$tahun."_kegiatan as k where  k.id_program=string_to_array(string_agg(id::text,',')) ) as pagu

      }

      public function api_pemetaan($tahun,$kodepemda,Request $request){

      }



    public function download($tahun,$kodepemda=null,Request $request){
      set_time_limit(-1);
      ini_set('memory_limit', '8095M');
      $name='';
      foreach ($request->all() as $key => $value) {
          # code...'
        $name.=$key.":";
        if(is_array($value)){
            $name.=implode(',', $value);
        }else{
            $name.=$value;
        }


      }

      $name.='-data-rekap.xlsx';




      $where=[];
      if($kodepemda){
        $where[]="n.kodepemda ='".$kodepemda."'";
      }

      if($request->match){
        $where[]="n.atch =".(boolean)$request->match;
      }

      if($request->status){
        $where[]="n.status ='".$request->status."'";
      }

      if($request->pemda){
        $where[]="n.kodepemda in ('".implode("','", $request->pemda)."')";
      }

      if($request->bidang){
        $where[]="n.uraibidang in ('".implode("','", $request->bidang)."')";
      }

      if($request->urusan){

         $where[]="n.id_urusan in ('".implode("','", $request->urusan)."')";
       }


        $data=DB::table("rkpd.view_master_".$tahun."_rkpd as  n"))
        ->orderBy('n.index_p','asc')
        ->orderBy('n.index_k','asc')
        ->orderBy('n.index','asc')
        ->whereRaw(count($where)>0?implod(' and ',$where):"true")
        ->get()->toArray();


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        if(isset($data[0])){
            $head=array_values((array)$data[0]);
            $keys=array_keys((array)$data[0]);
            foreach ($head as $key => $value) {
              $sheet->setCellValue(static::$abj[$key].'1', str_replace('_', ' ', strtoupper($keys[$key])));

            }
        }
        $start=2;
        foreach($data as $key=>$d){
            $jenis=$d->jenis;
             if($jenis=='PROGRAM'){
                 $sheet->getStyle(static::$abj[0].($start+$key).':'.static::$abj[count((array)$d)-1].($start+$key))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('A7E1FF');
            }else if($jenis=='KEGIATAN'){
                $sheet->getStyle(static::$abj[0].($start+$key).':'.static::$abj[count((array)$d)-1].($start+$key))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('BFFFC9');
            }

          $d=array_values((array)$d);
          foreach ($d as $keyv => $dd) {
            $sheet->setCellValue(static::$abj[$keyv].($start+$key), $dd);

          }
        }



        $writer = new Xlsx($spreadsheet);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');


        Storage::put('public/SIPD/RKPD/'.$tahun.'/init.txt','');
        if($kodepemda!=null){
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="RKPD-'.$tahun.'.xlsx"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
        }else{
            $save=$writer->save(storage_path('app/public/SIPD/RKPD/'.$tahun.'/'.$name));
            return redirect('storage/SIPD/RKPD/'.$tahun.'/'.$name);

        }


    }
}
