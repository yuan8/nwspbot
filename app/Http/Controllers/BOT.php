<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class BOT extends Controller
{
    //

    public function get_sipd_rkpd($tahun=null){
      if($tahun==null){
        $tahun=date('Y');
      }
      $data=DB::table('rkpd.master_'.$tahun.'_status_data')->selectRaw($tahun." as tahun, max(updated_at) as last_date,count(*) as count")->first();
      return view('box.box-sipd')->with(['data'=>(array)$data])->render();
    }

    public function get_sirup($tahun=null){
      if($tahun==null){
        $tahun=date('Y');
      }
      $data=DB::table('sirup.'.$tahun.'_paket')->selectRaw($tahun." as tahun, max(created_at) as last_date,count(*) as count")->first();
      return view('box.sirup')->with(['data'=>(array)$data])->render();
    }

    public function get_nuwsp_api($tahun=null){
      if($tahun==null){
        $tahun=date('Y');
      }
      $data=DB::table('sat.'.'laporan')->selectRaw($tahun." as tahun, max(created_at) as last_date,count(*) as count")->first();
      return view('box.nuwsp_api')->with(['data'=>(array)$data])->render();
    }

    public function file_manager($tahun=null){
      
      return view('box.filemanager')->render();
    }
}
