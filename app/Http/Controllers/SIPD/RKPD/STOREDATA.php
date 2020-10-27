<?php

namespace App\Http\Controllers\SIPD\RKPD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
class STOREDATA extends Controller
{
    //

    static $id_bidang;
    static $id_program;
    static $id_kegiatan;
    static $id_sub_kegiatan;
    static $data_ll=[];

    static $learning_data=[
    	'kegiatan'=>[],
    	'indikator_kegiatan'=>[],
    	'indikator_program'=>[]
    ];

    static function non_array($data_ex){
    	$data_ex=(array)$data_ex;
    	$data=[];
    	if(!is_array($data_ex)){
    		dd(static::$data_ll);
    	}

    	static::$data_ll=$data_ex;

      foreach ($data_ex as $key => $value) {
    		if(!is_array($value)){
    			$data[$key]=$value;
    		}
    	}
    	return $data;
    }

    static function learning_kegiatan($kodedata,$data){
    	if(isset(static::$learning_data['kegiatan'][$kodedata])){
    		$data=array_merge($data,static::$learning_data['kegiatan'][$kodedata]);
    	}

    	return $data;
    }

    static function learning_indikator_kegiatan($kodedata,$data){
    	if(isset(static::$learning_data['indikator_kegiatan'][$kodedata])){
    		$data=array_merge($data,static::$learning_data['indikator_kegiatan'][$kodedata]);
    	}

    	return $data;
    }

     static function learning_indikator_program($kodedata,$data){
    	if(isset(static::$learning_data['indikator_program'][$kodedata])){
    		$data=array_merge($data,static::$learning_data['indikator_program'][$kodedata]);
    	}

    	return $data;
    }

    static function store($data,$kodepemda,$tahun,$transactioncode=null){

    	if(file_exists(storage_path('app/BOT/SIPD/RKPD/'.$tahun.'/JSON-PEMETAAN/'.$kodepemda.'.json'))){
    		static::$learning_data=json_decode(file_get_contents(storage_path('app/BOT/SIPD/RKPD/'.$tahun.'/JSON-PEMETAAN/'.$kodepemda.'.json')),true);
    	}

    	DB::table('rkpd.'.'master_'.$tahun.'_status')
    	->where([
    		'kodepemda'=>$kodepemda,
    		'tahun'=>$tahun
    	])
    	->update([
    		'attemp'=>DB::raw('(attemp+1)')
    	]);

    	foreach($data as $key => $bd) {
			     static::$id_bidang=DB::table('rkpd.'.'master_'.$tahun.'_bidang')->insertGetId(static::non_array($bd)
			);

			foreach ($bd['program'] as $keyp => $p) {
				# code...
				$dbi=static::non_array($p);
				$dbi['id_bidang']=static::$id_bidang;
				static::$id_program=DB::table('rkpd.'.'master_'.$tahun.'_program')->insertGetId($dbi);
				foreach ($p['capaian'] as $keyc => $c) {
					$dbi=static::non_array($c);
					$dbi['id_bidang']=static::$id_bidang;
					$dbi['id_program']=static::$id_program;
					$dbi=static::learning_indikator_program($dbi['kodedata'],$dbi);

					DB::table('rkpd.'.'master_'.$tahun.'_program_capaian')->insertGetId($dbi);
				}

				foreach ($p['kegiatan'] as $keyk => $k) {
					# code...
					$dbi=static::non_array($k);
					$dbi['id_bidang']=static::$id_bidang;
					$dbi['id_program']=static::$id_program;

					$dbi=static::learning_kegiatan($dbi['kodedata'],$dbi);
					static::$id_kegiatan=DB::table('rkpd.'.'master_'.$tahun.'_kegiatan')->insertGetId($dbi);


					foreach ($k['indikator'] as $keyi => $i) {
						$dbi=static::non_array($i);
						$dbi['id_bidang']=static::$id_bidang;
						$dbi['id_program']=static::$id_program;
						$dbi['id_kegiatan']=static::$id_kegiatan;
						$dbi=static::learning_indikator_kegiatan($dbi['kodedata'],$dbi);
						DB::table('rkpd.'.'master_'.$tahun.'_kegiatan_indikator')->insertGetId($dbi);
					}

					foreach ($k['sumberdana'] as $keyksum => $sum) {

						$dbi=static::non_array($sum);
						$dbi['id_bidang']=static::$id_bidang;
						$dbi['id_program']=static::$id_program;
						$dbi['id_kegiatan']=static::$id_kegiatan;

						DB::table('rkpd.'.'master_'.$tahun.'_kegiatan_sumberdana')->insertGetId($dbi);

					}


					foreach ($k['subkegiatan'] as $keyks => $ks) {
						# code...
						$dbi=static::non_array($ks);
						$dbi['id_bidang']=static::$id_bidang;
						$dbi['id_program']=static::$id_program;
						$dbi['id_kegiatan']=static::$id_kegiatan;

						static::$id_sub_kegiatan=DB::table('rkpd.'.'master_'.$tahun.'_subkegiatan')->insertGetId($dbi);

						foreach ($ks['indikator'] as $keyis => $issub) {
						# code...
							$dbi=static::non_array($issub);
							$dbi['id_bidang']=static::$id_bidang;
							$dbi['id_program']=static::$id_program;
							$dbi['id_kegiatan']=static::$id_kegiatan;
							$dbi['id_sub_kegiatan']=static::$id_sub_kegiatan;
							$dbi=static::learning_indikator_kegiatan($dbi['kodedata'],$dbi);



							DB::table('rkpd.'.'master_'.$tahun.'_subkegiatan_indikator')->insertGetId($dbi);
						}


						foreach ($ks['sumberdana'] as $keyssum => $ssum) {
							$dbi=static::non_array($ssum);
							$dbi['id_bidang']=static::$id_bidang;
							$dbi['id_program']=static::$id_program;
							$dbi['id_kegiatan']=static::$id_kegiatan;
							$dbi['id_sub_kegiatan']=static::$id_sub_kegiatan;


							DB::table('rkpd.'.'master_'.$tahun.'_subkegiatan_sumberdana')->insertGetId($dbi);
						}
					}

				}
			}
		}

		$approve=false;



		$pagu=(array)DB::table('rkpd.'.'master_'.$tahun.'_bidang as b')->leftJoin( 'rkpd.'.'master_'.$tahun.'_kegiatan as k','b.id','=','k.id_bidang')->selectRaw('sum(k.pagu) as total_pagu,min(b.transactioncode) as transactioncode')->where([
			'b.kodepemda'=>$kodepemda,
			'b.tahun'=>$tahun,

		])->first();

		if(!$pagu){
			$pagu=[
				'transactioncode'=>$transactioncode,
				'kodepemda'=>$kodepemda,
				'tahun'=>$tahun,
				'total_pagu'=>0
			];
		}

		$pagu['total_pagu']=(int)($pagu['total_pagu']);
		$pagu['transactioncode']=isset($pagu['transactioncode'])?$pagu['transactioncode']:$transactioncode;





		if($pagu){

			$pagu_rkpd=(array)DB::table('rkpd.'.'master_'.$tahun.'_status')->where([
				'kodepemda'=>$kodepemda,
				'tahun'=>$tahun,
			])->first();

			if($pagu_rkpd){
				$pagu_rkpd['y']=[];
				$pagu_rkpd['y']=$pagu_rkpd['y']+$pagu;
				if(((number_format((int)$pagu_rkpd['pagu']))==(number_format((int)$pagu['total_pagu']))) and ($pagu_rkpd['transactioncode']==$pagu['transactioncode'])){
					$approve=true;

					$in=DB::table('rkpd.'.'master_'.$tahun.'_status_data')->updateOrInsert([
						'kodepemda'=>$kodepemda,
						'tahun'=>$tahun,
					],
					[
						'kodepemda'=>$kodepemda,
						'tahun'=>$tahun,
            			'tipe_pengambilan'=>$pagu_rkpd['tipe_pengambilan'],
            			'method'=>$pagu_rkpd['method'],
            			'perkada'=>$pagu_rkpd['perkada'],
            			'nomenklatur'=>$pagu_rkpd['nomenklatur'],
            			'sumber_data'=>$pagu_rkpd['sumber_data'],
						'transactioncode'=>$pagu_rkpd['transactioncode'],
						'last_date'=>$pagu_rkpd['last_date'],
						'updated_at'=>Carbon::now(),
						'pagu'=>(float)($pagu['total_pagu']?$pagu['total_pagu']:0),
						'matches'=>true,
						'status'=>$pagu_rkpd['status']
					]);

					$in=DB::table('rkpd.'.'master_'.$tahun.'_status_data')->where(['kodepemda'=>$kodepemda,
						'tahun'=>$tahun,
					])->update(
					[
						'matches'=>true,
					]);
				}
			}


		}

		$in=DB::table('rkpd.'.'master_'.$tahun.'_status_data')->insertOrIgnore(
		[
				'kodepemda'=>$kodepemda,
				'tahun'=>$tahun,
        		'tipe_pengambilan'=>$pagu_rkpd['tipe_pengambilan'],
				'method'=>$pagu_rkpd['method'],
				'perkada'=>$pagu_rkpd['perkada'],
				'nomenklatur'=>$pagu_rkpd['nomenklatur'],
				'sumber_data'=>$pagu_rkpd['sumber_data'],
				'transactioncode'=>$pagu_rkpd['transactioncode'],
				'last_date'=>$pagu_rkpd['last_date'],
				'updated_at'=>Carbon::now(),
				'pagu'=>(float)($pagu['total_pagu']?$pagu['total_pagu']:0),
				'matches'=>false,
				'status'=>$pagu_rkpd['status']
		]);

		if(!$approve){
			$in=DB::table('rkpd.'.'master_'.$tahun.'_status_data')->where([
				'kodepemda'=>$kodepemda,
				'tahun'=>$tahun,
			])->update(['matches'=>false]);
		}

		return 'ok';

    }
}
