<?php

namespace App\Http\Controllers\SIPD\RKPD;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use DB;
class InitCtrl extends Controller
{
    //


    public function init($tahun){
    	static::status($tahun);
    	static::bidang($tahun);
    	static::program($tahun);
    	static::program_capaian($tahun);

    	static::kegiatan($tahun);
    	static::kegiatan_indikator($tahun);
    	static::kegiatan_sumberdana($tahun);

    	static::sub_kegiatan($tahun);
        static::sub_kegiatan_indikator($tahun);
    	static::sub_kegiatan_sumberdana($tahun);
        static::view($tahun);
    	return back();
    }

    static function status($tahun){
    	 $schema='rkpd.';

         if(!Schema::connection('pgsql')->hasTable($schema.'master_'.$tahun.'_status')){
              Schema::connection('pgsql')->create($schema.'master_'.$tahun.'_status',function(Blueprint $table) use ($schema,$tahun){
                    $table->bigIncrements('id');
                    $table->string('kodepemda',4)->unique();
                    $table->integer('tahun');
                    $table->integer('status');
                    $table->integer('attemp')->default(0);
                    $table->double('pagu',25,3)->default(0);
                    $table->dateTime('last_date')->nullable();
                    $table->bigInteger('transactioncode')->nullable();
                    $table->boolean('matches')->nullable();
                    $table->timestamps();
              });
          }

        $schema='rkpd.';

        if(!Schema::connection('pgsql')->hasTable($schema.'master_'.$tahun.'_status_data')){
              Schema::connection('pgsql')->create($schema.'master_'.$tahun.'_status_data',function(Blueprint $table) use ($schema,$tahun){
                    $table->bigIncrements('id');
                    $table->string('kodepemda',4)->unique();
                    $table->integer('tahun');
                    $table->integer('status');
                    $table->double('pagu',25,3)->default(0);
                    $table->dateTime('last_date')->nullable();
                    $table->bigInteger('transactioncode')->nullable();
                    $table->boolean('matches')->nullable();
                    $table->timestamps();
                   
                   
              });
        }
    }

    static function bidang($tahun){
		
		  $schema='rkpd.';


         if(!Schema::connection('pgsql')->hasTable($schema.'master_'.$tahun.'_bidang')){
              Schema::connection('pgsql')->create($schema.'master_'.$tahun.'_bidang',function(Blueprint $table) use ($schema,$tahun){
                    $table->bigIncrements('id');
                    $table->integer('status')->default(0);
                    $table->text('kodedata')->unique();
                    $table->string('kodepemda',4);
                    $table->integer('tahun');
                    $table->string('kodebidang')->nullable();
                    $table->string('uraibidang')->nullable();
                    $table->bigInteger('id_urusan')->nullable();
                    $table->string('kodeskpd')->nullable();
                    $table->string('uraiskpd')->nullable();
                    $table->bigInteger('transactioncode')->nullable();
            		$table->timestamps();

              });
          }



	}

	static function program($tahun){
		
		  $schema='rkpd.';

         if(!Schema::connection('pgsql')->hasTable($schema.'master_'.$tahun.'_program')){
              Schema::connection('pgsql')->create($schema.'master_'.$tahun.'_program',function(Blueprint $table) use ($schema,$tahun){
                    $table->bigIncrements('id');
                    $table->integer('status')->default(0);
                    $table->text('kodedata')->unique();
                    $table->bigInteger('id_bidang')->unsigned();
                    $table->string('kodepemda',4);
                    $table->integer('tahun');
                    $table->string('kodebidang')->nullable();
                    $table->string('uraibidang')->nullable();
                    $table->string('kode_urusan_prioritas')->nullable();
                    $table->bigInteger('id_urusan')->nullable();
                    $table->bigInteger('id_sub_urusan')->nullable();
                    $table->string('kodeprogram')->nullable();
                    $table->text('uraiprogram')->nullable();
                    $table->string('kodeskpd')->nullable();
                    $table->text('uraiskpd')->nullable();
                    $table->bigInteger('transactioncode')->nullable();
            		$table->timestamps();
                    $table->foreign('id_bidang')
	                  ->references('id')->on($schema.'master_'.$tahun.'_bidang')
	                  ->onDelete('cascade')->onUpdate('cascade');
	                });
          }

	}


	static function program_capaian($tahun){
		 $schema='rkpd.';
		if(!Schema::connection('pgsql')->hasTable($schema.'master_'.$tahun.'_program_capaian')){
          Schema::connection('pgsql')->create($schema.'master_'.$tahun.'_program_capaian',function(Blueprint $table) use ($schema,$tahun){
                $table->bigIncrements('id');
            	$table->integer('status')->default(0);
                $table->text('kodedata')->unique();
                $table->bigInteger('id_bidang')->unsigned();
                $table->bigInteger('id_program')->unsigned();
                $table->string('kodepemda',4);
                $table->integer('tahun');
            	$table->string('kodebidang')->nullable();
            	$table->string('kodeskpd')->nullable();
                $table->string('kodeprogram')->nullable();
                $table->string('kodeindikator')->nullable();
                $table->longText('tolokukur')->nullable();
                $table->longText('satuan')->nullable();
                $table->longText('real_p3')->nullable();
                $table->double('pagu_p3',25,3)->nullable();
                $table->longText('real_p2')->nullable();
                $table->double('pagu_p2',25,3)->nullable();
                $table->longText('real_p1')->nullable();
                $table->double('pagu_p1',25,3)->nullable();
                $table->longText('target')->nullable();
                $table->double('pagu',25,3)->default(0);
                $table->double('pagu_p',25,3)->default(0);
                $table->longText('target_n1')->nullable();
                $table->double('pagu_n1',25,3)->default(0);
            	$table->integer('jenis')->nullable();
                $table->bigInteger('transactioncode')->nullable();
            		$table->timestamps();


		      	$table->foreign('id_program')
				      ->references('id')->on($schema.'master_'.$tahun.'_program')
				      ->onDelete('cascade')->onUpdate('cascade');
                 $table->foreign('id_bidang')
                      ->references('id')->on($schema.'master_'.$tahun.'_bidang')
                      ->onDelete('cascade')->onUpdate('cascade');
                  });
      }


	}

	static function program_prioritas($tahun){

		if(!Schema::connection('pgsql')->hasTable($schema.'master_'.$tahun.'_program_prioritas')){
          	Schema::connection('pgsql')->create($schema.'master_'.$tahun.'_program_prioritas',function(Blueprint $table) use ($schema,$tahun){
               $table->bigIncrements('id');
                    $table->integer('status')->default(0);
                    $table->text('kodedata')->unique();
                    $table->string('kodepemda',4);
                    $table->integer('tahun');
                    $table->string('kodebidang')->nullable();
                    $table->string('uraibidang')->nullable();
                    $table->string('kodeskpd')->nullable();
                    $table->text('uraiskpd')->nullable();
                    $table->string('kodeprioritas')->nullable();
                    $table->text('uraiprioritas')->nullable();
            		$table->integer('jenis')->nullable();
                    $table->bigInteger('id_program')->unsigned();
                    $table->bigInteger('transactioncode')->nullable();
            		$table->timestamps();

                    $table->foreign('id_program')
	                  ->references('id')->on($schema.'master_'.$tahun.'_program')
	                  ->onDelete('cascade')->onUpdate('cascade');
	                });
		  	}
      }


	

	static function kegiatan($tahun){


  		$schema='rkpd.';

        if(!Schema::connection('pgsql')->hasTable($schema.'master_'.$tahun.'_kegiatan')){
              Schema::connection('pgsql')->create($schema.'master_'.$tahun.'_kegiatan',function(Blueprint $table) use ($schema,$tahun){
                    $table->bigIncrements('id');
                    $table->integer('status')->default(0);

                    $table->text('kodedata')->unique();
                    $table->bigInteger('id_bidang')->unsigned();
                    $table->bigInteger('id_program')->unsigned();
                    $table->string('kodepemda',4);
                    $table->integer('tahun');
                    $table->bigInteger('id_urusan')->nullable();
                    $table->bigInteger('id_sub_urusan')->nullable();
                    $table->string('kodebidang')->nullable();
                    $table->string('kodeprogram')->nullable();
                    $table->string('kodekegiatan')->nullable();
                    $table->text('uraikegiatan')->nullable();
                    $table->double('pagu',25,3)->default(0);
                    $table->double('pagu_p',25,3)->default(0);
            		$table->integer('jenis')->nullable();
            		$table->integer('kode_lintas_urusan')->nullable();



                    $table->bigInteger('transactioncode')->nullable();
            		$table->timestamps();


		            $table->foreign('id_bidang')
				        ->references('id')->on($schema.'master_'.$tahun.'_bidang')
				        ->onDelete('cascade')->onUpdate('cascade');

				     $table->foreign('id_program')
				        ->references('id')->on($schema.'master_'.$tahun.'_program')
				        ->onDelete('cascade')->onUpdate('cascade');
		              });
         }

	}

	static function kegiatan_indikator($tahun){


  		$schema='rkpd.';

        if(!Schema::connection('pgsql')->hasTable($schema.'master_'.$tahun.'_kegiatan_indikator')){
              Schema::connection('pgsql')->create($schema.'master_'.$tahun.'_kegiatan_indikator',function(Blueprint $table) use ($schema,$tahun){
                 
         
		                $table->bigIncrements('id');
		            	$table->integer('status')->default(0);
		                $table->text('kodedata')->unique();
		                $table->bigInteger('id_bidang')->unsigned();
		                $table->bigInteger('id_program')->unsigned();
		                $table->bigInteger('id_kegiatan')->unsigned();
		                $table->string('kodepemda',4);
		                $table->integer('tahun');
		            	$table->string('kodebidang')->nullable();
		            	$table->string('kodeskpd')->nullable();
		                $table->string('kodeprogram')->nullable();
		                $table->string('kodekegiatan')->nullable();
		                $table->string('kodeindikator')->nullable();
		                $table->longText('tolokukur')->nullable();
		                $table->longText('satuan')->nullable();
		                $table->longText('real_p3')->nullable();
		                $table->double('pagu_p3',25,3)->nullable();
		                $table->longText('real_p2')->nullable();
		                $table->double('pagu_p2',25,3)->nullable();
		                $table->longText('real_p1')->nullable();
		                $table->double('pagu_p1',25,3)->nullable();
		                $table->longText('target')->nullable();
		                $table->double('pagu',25,3)->default(0);
		                $table->double('pagu_p',25,3)->default(0);
		                $table->longText('target_n1')->nullable();
		                $table->double('pagu_n1',25,3)->default(0);
		            	$table->integer('jenis')->nullable();
		                $table->bigInteger('transactioncode')->nullable();
		            	$table->timestamps();


				      	$table->foreign('id_kegiatan')
						      ->references('id')->on($schema.'master_'.$tahun.'_kegiatan')
						      ->onDelete('cascade')->onUpdate('cascade');
						  

						$table->foreign('id_bidang')
						      ->references('id')->on($schema.'master_'.$tahun.'_bidang')
						      ->onDelete('cascade')->onUpdate('cascade');
						  

				      	$table->foreign('id_program')
						      ->references('id')->on($schema.'master_'.$tahun.'_program')
						      ->onDelete('cascade')->onUpdate('cascade');
	
		      });
         }

	}

	static function kegiatan_sumberdana($tahun){


		$schema='rkpd.';

        if(!Schema::connection('pgsql')->hasTable($schema.'master_'.$tahun.'_kegiatan_sumberdana')){
          	Schema::connection('pgsql')->create($schema.'master_'.$tahun.'_kegiatan_sumberdana',function(Blueprint $table) use ($schema,$tahun){
               $table->bigIncrements('id');
                    $table->integer('status')->default(0);
                    $table->text('kodedata')->unique();
                    $table->string('kodepemda',4);
                    $table->integer('tahun');
                    $table->string('kodebidang')->nullable();
                    $table->string('kodeskpd')->nullable();
                    $table->string('kodeprogram')->nullable();
                    $table->string('kodekegiatan')->nullable();
                    $table->string('kodesumberdana')->nullable();
                    $table->longText('sumberdana')->nullable();
               		$table->double('pagu',25,3)->default(0);
                  	$table->bigInteger('id_bidang')->unsigned();
                    $table->bigInteger('id_program')->unsigned();
                    $table->bigInteger('id_kegiatan')->unsigned();
                    $table->bigInteger('transactioncode')->nullable();
            		$table->timestamps();

                    $table->foreign('id_bidang')
				        ->references('id')->on($schema.'master_'.$tahun.'_bidang')
				        ->onDelete('cascade')->onUpdate('cascade');

				     $table->foreign('id_program')
				        ->references('id')->on($schema.'master_'.$tahun.'_program')
				        ->onDelete('cascade')->onUpdate('cascade');
		       		  $table->foreign('id_kegiatan')
			        ->references('id')->on($schema.'master_'.$tahun.'_kegiatan')
			        ->onDelete('cascade')->onUpdate('cascade');

	                });
		 }
	}


	static function kegiatan_lokasi($tahun){
	
		$schema='rkpd.';

        if(!Schema::connection('pgsql')->hasTable($schema.'master_'.$tahun.'_kegiatan_lokasi')){
              Schema::connection('pgsql')->create($schema.'master_'.$tahun.'_kegiatan_lokasi',function(Blueprint $table) use ($schema,$tahun){
                    $table->bigIncrements('id');
                    $table->integer('status')->default(0);
                    $table->text('kodedata')->unique();
                    $table->bigInteger('id_bidang')->unsigned();
                    $table->bigInteger('id_program')->unsigned();
                    $table->bigInteger('id_kegiatan')->unsigned();
                    $table->string('kodepemda',4);
                    $table->integer('tahun');
                    $table->string('kodebidang')->nullable();
                    $table->string('kodeskpd')->nullable();
                    $table->string('kodeprogram')->nullable();
                    $table->string('kodekegiatan')->nullable();
                    $table->string('kodelokasi')->nullable();
                    $table->text('lokasi')->nullable();
                    $table->longText('detaillokasi')->nullable();
             
                    $table->bigInteger('transactioncode')->nullable();
            		$table->timestamps();


		            $table->foreign('id_bidang')
				        ->references('id')->on($schema.'master_'.$tahun.'_bidang')
				        ->onDelete('cascade')->onUpdate('cascade');

				     $table->foreign('id_program')
				        ->references('id')->on($schema.'master_'.$tahun.'_program')
				        ->onDelete('cascade')->onUpdate('cascade');
		         $table->foreign('id_kegiatan')
		        ->references('id')->on($schema.'master_'.$tahun.'_kegiatan')
		        ->onDelete('cascade')->onUpdate('cascade');

		           });
          }


	}



	static function kegiatan_prioritas($tahun){
	
		$schema='rkpd.';

        if(!Schema::connection('pgsql')->hasTable($schema.'master_'.$tahun.'_kegiatan_prioritas')){
          	Schema::connection('pgsql')->create($schema.'master_'.$tahun.'_kegiatan_prioritas',function(Blueprint $table) use ($schema,$tahun){
               $table->bigIncrements('id');
                    $table->integer('status')->default(0);
                    $table->text('kodedata')->unique();
                    $table->string('kodepemda',4);
                    $table->integer('tahun');
                    $table->string('kodebidang')->nullable();
                    $table->string('uraibidang')->nullable();
                    $table->string('kodeskpd')->nullable();
                    $table->text('uraiskpd')->nullable();
                    $table->string('kodeprioritas')->nullable();
                    $table->text('uraiprioritas')->nullable();
            		$table->integer('jenis')->nullable();
                    $table->bigInteger('id_kegiatan')->unsigned();
                    $table->bigInteger('transactioncode')->nullable();
            		$table->timestamps();

                    $table->foreign('id_kegiatan')
	                  ->references('id')->on($schema.'master_'.$tahun.'_kegiatan')
	                  ->onDelete('cascade')->onUpdate('cascade');
	                });
		 }


	}

	static function sub_kegiatan($tahun){


  		$schema='rkpd.';


        if(!Schema::connection('pgsql')->hasTable($schema.'master_'.$tahun.'_subkegiatan')){
              Schema::connection('pgsql')->create($schema.'master_'.$tahun.'_subkegiatan',function(Blueprint $table) use ($schema,$tahun){
                    $table->bigIncrements('id');
                    $table->integer('status')->default(0);
                    $table->text('kodedata')->unique();
                    $table->bigInteger('id_bidang')->unsigned();
                    $table->bigInteger('id_program')->unsigned();
                    $table->bigInteger('id_kegiatan')->unsigned();
                    $table->string('kodepemda',4);
                    $table->integer('tahun');
                    $table->bigInteger('id_urusan')->nullable();
                    $table->bigInteger('id_sub_urusan')->nullable();
                    $table->string('kodebidang')->nullable();
                    $table->string('kodeprogram')->nullable();
                    $table->string('kodekegiatan')->nullable();
                    $table->string('kodesubkegiatan')->nullable();
                    $table->text('uraisubkegiatan')->nullable();
                    $table->double('pagu',25,3)->default(0);
                    $table->double('pagu_p',25,3)->default(0);
            		$table->integer('jenis')->nullable();
            		$table->integer('kode_lintas_urusan')->nullable();
                    $table->bigInteger('transactioncode')->nullable();
            		$table->timestamps();

		            $table->foreign('id_bidang')
				        ->references('id')->on($schema.'master_'.$tahun.'_bidang')
				        ->onDelete('cascade')->onUpdate('cascade');

				     $table->foreign('id_program')
				        ->references('id')->on($schema.'master_'.$tahun.'_program')
				        ->onDelete('cascade')->onUpdate('cascade');
				         $table->foreign('id_kegiatan')
				        ->references('id')->on($schema.'master_'.$tahun.'_kegiatan')
				        ->onDelete('cascade')->onUpdate('cascade');
		              });

              		
         }

	}
	static function sub_kegiatan_sumberdana($tahun){


		$schema='rkpd.';

        if(!Schema::connection('pgsql')->hasTable($schema.'master_'.$tahun.'_subkegiatan_sumberdana')){
          	Schema::connection('pgsql')->create($schema.'master_'.$tahun.'_subkegiatan_sumberdana',function(Blueprint $table) use ($schema,$tahun){
               $table->bigIncrements('id');
                    $table->integer('status')->default(0);
                    $table->text('kodedata')->unique();
                    $table->string('kodepemda',4);
                    $table->integer('tahun');
                    $table->string('kodebidang')->nullable();
                    $table->string('kodeskpd')->nullable();
                    $table->string('kodeprogram')->nullable();
                    $table->string('kodekegiatan')->nullable();
                    $table->string('kodesubkegiatan')->nullable();
                    $table->string('kodesumberdana')->nullable();
                    $table->longText('sumberdana')->nullable();
               		$table->double('pagu',25,3)->default(0);
                  	$table->bigInteger('id_bidang')->unsigned();
                    $table->bigInteger('id_program')->unsigned();
                    $table->bigInteger('id_kegiatan')->unsigned();
                    $table->bigInteger('id_sub_kegiatan')->unsigned();
                    $table->bigInteger('transactioncode')->nullable();
            		$table->timestamps();

                    $table->foreign('id_bidang')
				        ->references('id')->on($schema.'master_'.$tahun.'_bidang')
				        ->onDelete('cascade')->onUpdate('cascade');

				     $table->foreign('id_program')
				        ->references('id')->on($schema.'master_'.$tahun.'_program')
				        ->onDelete('cascade')->onUpdate('cascade');
		       		  $table->foreign('id_kegiatan')
			        ->references('id')->on($schema.'master_'.$tahun.'_kegiatan')
			        ->onDelete('cascade')->onUpdate('cascade');

			          $table->foreign('id_sub_kegiatan')
			        ->references('id')->on($schema.'master_'.$tahun.'_subkegiatan')
			        ->onDelete('cascade')->onUpdate('cascade');

	                });
		 }
	}


    static function sub_kegiatan_indikator($tahun){


        $schema='rkpd.';

        if(!Schema::connection('pgsql')->hasTable($schema.'master_'.$tahun.'_subkegiatan_indikator')){
              Schema::connection('pgsql')->create($schema.'master_'.$tahun.'_subkegiatan_indikator',function(Blueprint $table) use ($schema,$tahun){
                 
         
                        $table->bigIncrements('id');
                        $table->integer('status')->default(0);
                        $table->text('kodedata')->unique();
                        $table->bigInteger('id_bidang')->unsigned();
                        $table->bigInteger('id_program')->unsigned();
                        $table->bigInteger('id_kegiatan')->unsigned();
                        $table->bigInteger('id_sub_kegiatan')->unsigned();

                        $table->string('kodepemda',4);
                        $table->integer('tahun');
                        $table->string('kodebidang')->nullable();
                        $table->string('kodeskpd')->nullable();
                        $table->string('kodeprogram')->nullable();
                        $table->string('kodekegiatan')->nullable();
                        $table->string('kodesubkegiatan')->nullable();
                        $table->string('kodeindikator')->nullable();
                        $table->longText('tolokukur')->nullable();
                        $table->longText('satuan')->nullable();
                        $table->longText('real_p3')->nullable();
                        $table->double('pagu_p3',25,3)->nullable();
                        $table->longText('real_p2')->nullable();
                        $table->double('pagu_p2',25,3)->nullable();
                        $table->longText('real_p1')->nullable();
                        $table->double('pagu_p1',25,3)->nullable();
                        $table->longText('target')->nullable();
                        $table->double('pagu',25,3)->default(0);
                        $table->double('pagu_p',25,3)->default(0);
                        $table->longText('target_n1')->nullable();
                        $table->double('pagu_n1',25,3)->default(0);
                        $table->integer('jenis')->nullable();
                        $table->bigInteger('transactioncode')->nullable();
                        $table->timestamps();


                        $table->foreign('id_kegiatan')
                              ->references('id')->on($schema.'master_'.$tahun.'_kegiatan')
                              ->onDelete('cascade')->onUpdate('cascade');

                         $table->foreign('id_sub_kegiatan')
                              ->references('id')->on($schema.'master_'.$tahun.'_subkegiatan')
                              ->onDelete('cascade')->onUpdate('cascade');
                          

                        $table->foreign('id_bidang')
                              ->references('id')->on($schema.'master_'.$tahun.'_bidang')
                              ->onDelete('cascade')->onUpdate('cascade');
                          

                        $table->foreign('id_program')
                              ->references('id')->on($schema.'master_'.$tahun.'_program')
                              ->onDelete('cascade')->onUpdate('cascade');
    
              });
         }

    }

    public static function view($tahun){
        $schema="rkpd.";

        if(!(DB::table(DB::raw("(select * from information_schema.tables where table_schema='rkpd' and table_name='view_master_".$tahun."_rkpd') as c"))->first())){

            DB::statement("create view rkpd.view_master_".$tahun."_rkpd as select * from ((
            select 
                min(k.status) as status, 
                1 as index, 
                k.id_program as index_p, 
                0 as index_pi, 
                min(k.id) as index_k, 
                0 as index_ki,
                min(k.kodepemda) as kodepemda,
                (case when (length(min(k.kodepemda))>3) then concat(min(d.nama),' - ',(select p.nama from public.master_daerah as p where p.id = left(min(k.kodepemda),2))) else min(d.nama) end) as nama_pemda,
                min(k.id_urusan) as id_urusan,
                'PROGRAM' as jenis,
                min(u.nama) as nama_urusan,
                min(k.id_sub_urusan) as id_sub_urusan,
                min(su.nama) as nama_sub_urusan,
                min(k.kodebidang) as kodebidang,
                min(p.uraibidang) as uraibidang,
                min(p.kodeskpd) as kodeskpd,
                min( p.uraiskpd) as uraiskpd,
                min(k.kodeprogram) as kodeprogram,
                min(p.uraiprogram) as uraiprogram,
                '' as kodekegiatan,
                '' as uraikegiatan,
                sum(pagu) as pagu_kegiatan,
                '' as kodeindikator,
                '' as  indikator,
                '' as target,
                '' as satuan,
                null as pagu_indikator
            from rkpd.master_".$tahun."_kegiatan as k 
            left join rkpd.master_".$tahun."_program as p on p.id=k.id_program
            left join public.master_urusan as u on u.id=k.id_urusan
            left join public.master_sub_urusan as su on su.id=k.id_sub_urusan
            left join public.master_daerah as d on d.id=k.kodepemda
            group by k.id_program   
            ) 
            union 
            (
            select 
                k.status,
                2 as index, 
                k.id_program as index_p, 
                pi.id as index_pi, 
                k.id as index_k, 
                0 as index_ki,
                k.kodepemda,
                (case when (length(k.kodepemda)>3) then concat(d.nama,' - ',(select p.nama from public.master_daerah as p where p.id = left(k.kodepemda,2))) else d.nama end) as nama_pemda,
                k.id_urusan,
                'CAPAIAN' as jenis,
                u.nama as nama_urusan,
                k.id_sub_urusan,
                su.nama as nama_sub_urusan,
                k.kodebidang,
                p.uraibidang,
                p.kodeskpd,
                p.uraiskpd,
                k.kodeprogram,
                p.uraiprogram,
                k.kodekegiatan,
                k.uraikegiatan,
                null as pagu_kegiatan,
                pi.kodeindikator as kodeindikator,
                pi.tolokukur as  indikator,
                pi.target as target,
                pi.satuan as satuan,
                pi.pagu as pagu_indikator
            from rkpd.master_".$tahun."_kegiatan as k 
            left join rkpd.master_".$tahun."_program as p on p.id=k.id_program
            left join public.master_urusan as u on u.id=k.id_urusan
            left join public.master_sub_urusan as su on su.id=k.id_sub_urusan
            left join public.master_daerah as d on d.id=k.kodepemda
            join rkpd.master_".$tahun."_program_capaian as pi on pi.id_program =k.id_program 
            ) 
            union
            (
            select 
                k.status,
                3 as index, 
                k.id_program as index_p, 
                0 as index_pi, 
                k.id as index_k, 
                0 as index_ki,
                k.kodepemda,
                (case when (length(k.kodepemda)>3) then concat(d.nama,' - ',(select p.nama from public.master_daerah as p where p.id = left(k.kodepemda,2))) else d.nama end) as nama_pemda,
                k.id_urusan,
                'KEGIATAN' as jenis,
                u.nama as nama_urusan,
                k.id_sub_urusan,
                su.nama as nama_sub_urusan,
                k.kodebidang,
                p.uraibidang,
                p.kodeskpd,
                p.uraiskpd,
                k.kodeprogram,
                p.uraiprogram,
                k.kodekegiatan,
                k.uraikegiatan,
                k.pagu as pagu_kegiatan,
                '' as kodeindikator,
                '' as  indikator,
                '' as target,
                '' as satuan,
                null as pagu_indikator
            from rkpd.master_".$tahun."_kegiatan as k 
            left join rkpd.master_".$tahun."_program as p on p.id=k.id_program
            left join public.master_urusan as u on u.id=k.id_urusan
            left join public.master_sub_urusan as su on su.id=k.id_sub_urusan
            left join public.master_daerah as d on d.id=k.kodepemda
            )
            union 
            (
            select 
                k.status,
                4 as index, 
                k.id_program as index_p, 
                0 as index_pi, 
                k.id as index_k, 
                ki.id as index_ki,
                k.kodepemda,
                (case when (length(k.kodepemda)>3) then concat(d.nama,' - ',(select p.nama from public.master_daerah as p where p.id = left(k.kodepemda,2))) else d.nama end) as nama_pemda,
                k.id_urusan,
                'INDIKATOR' as jenis,
                u.nama as nama_urusan,
                k.id_sub_urusan,
                su.nama as nama_sub_urusan,
                k.kodebidang,
                p.uraibidang,
                p.kodeskpd,
                p.uraiskpd,
                k.kodeprogram,
                p.uraiprogram,
                k.kodekegiatan,
                k.uraikegiatan,
                null as pagu_kegiatan,
                ki.kodeindikator as kodeindikator,
                ki.tolokukur as  indikator,
                ki.target as target,
                ki.satuan as satuan,
                ki.pagu as pagu_indikator
            from rkpd.master_".$tahun."_kegiatan as k 
            left join rkpd.master_".$tahun."_program as p on p.id=k.id_program
            left join public.master_urusan as u on u.id=k.id_urusan
            left join public.master_sub_urusan as su on su.id=k.id_sub_urusan
            left join public.master_daerah as d on d.id=k.kodepemda
            join rkpd.master_".$tahun."_kegiatan_indikator as ki on ki.id_kegiatan=k.id
            ) ) as nx
            order by nx.index_p,nx.index_k,nx.index ");

        }


        $data=DB::table('rkpd.master_'.$tahun.'_status_data')->get();

        foreach ($data as $key => $value) {
            DB::table('rkpd.master_'.$tahun."_kegiatan")->where('kodepemda',$value->kodepemda)
            ->update(['status'=>$value->status]);
        }


    }
}



