<?php

namespace App\Http\Controllers\SIPD\RKPD;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

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
}



