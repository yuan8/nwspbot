<?php

namespace App\Http\Controllers\SAT;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use DB;
class InitCtrl extends Controller
{
    //

    public static function init($tahun){
        $schema='sat.';

    	 if(!Schema::connection('pgsql')->hasTable($schema.'master_pdam')){
              Schema::create($schema.'master_pdam', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->string('name')->unique();
                    $table->string('address')->nullable();
                    $table->string('regencies_id',4)->nullable();
                    $table->string('provincies_id',2)->nullable();
                    $table->string('districts_id',11)->nullable();
                    $table->string('pemda_id',4)->unique()->nullable();
                    $table->timestamps();
                });
          }
           if(!Schema::connection('pgsql')->hasTable($schema.'master_pertanyaan')){
              Schema::connection('pgsql')->create($schema.'master_pertanyaan',function(Blueprint $table) use ($schema){
                    $table->bigIncrements('id');
                    $table->bigInteger('parent_category')->nullable();
                    $table->text('question')->nullable();
                    $table->string('default_value')->nullable();
                    $table->text('uom')->nullable();
                    $table->text('remark')->nullable();
                    $table->float('sort')->nullable();
                    $table->integer('year')->nullable();
                    $table->text('js')->nullable();
                    $table->integer('commas')->nullable();
                    $table->float('average')->nullable();
                    $table->float('max_value')->nullable();
                    $table->boolean('readonly')->nullable()->default(0);
                    $table->timestamps();

              });
          }
          if(!Schema::connection('pgsql')->hasTable($schema.'laporan')){
              Schema::connection('pgsql')->create($schema.'laporan',function(Blueprint $table) use ($schema){
                    $table->string('id')->primary();
                    $table->string('site_name')->nullable();
                    $table->string('address')->nullable();
                    $table->string('pemda_id',4)->nullable();
                    $table->dateTime('entry_date')->nullable();
                    $table->integer('entry_period_year')->nullable();
                    $table->integer('entry_period_month')->nullable();
                    $table->dateTime('entry_period')->nullable();
                    $table->dateTime('insert_date')->nullable();
                    $table->timestamps();
                    $table->foreign('pemda_id')
                      ->references('pemda_id')->on($schema.'master_pdam')
                      ->onDelete('cascade')->onUpdate('cascade');

              });
          }

         if(!Schema::connection('pgsql')->hasTable($schema.'laporan_kategori')){
              Schema::connection('pgsql')->create($schema.'laporan_kategori',function(Blueprint $table) use ($schema){
                    $table->bigIncrements('id');

                    $table->string('id_laporan');
                    $table->bigInteger('id_question')->unsigned();

                    $table->string('data')->nullable();
                    $table->unique(['id_laporan','id_question']);

                    $table->foreign('id_laporan')
                      ->references('id')->on($schema.'laporan')
                      ->onDelete('cascade')->onUpdate('cascade');

                    $table->foreign('id_question')
                      ->references('id')->on($schema.'master_pertanyaan')
                      ->onDelete('cascade')->onUpdate('cascade');

              });
          }
          if(!Schema::connection('pgsql')->hasTable($schema.'laporan_pelayanan')){
              Schema::connection('pgsql')->create($schema.'laporan_pelayanan',function(Blueprint $table) use ($schema){
                    $table->bigIncrements('id');

                    $table->string('id_laporan');
                    $table->bigInteger('id_question')->unsigned();

                    $table->double('data',25,3)->nullable();
                    $table->unique(['id_laporan','id_question']);


                    $table->foreign('id_laporan')
                      ->references('id')->on($schema.'laporan')
                      ->onDelete('cascade')->onUpdate('cascade');

                    $table->foreign('id_question')
                      ->references('id')->on($schema.'master_pertanyaan')
                      ->onDelete('cascade')->onUpdate('cascade');

              });
          }
           if(!Schema::connection('pgsql')->hasTable($schema.'laporan_operasional')){
              Schema::connection('pgsql')->create($schema.'laporan_operasional',function(Blueprint $table) use ($schema){
                    $table->bigIncrements('id');

                    $table->string('id_laporan');
                    $table->bigInteger('id_question')->unsigned();
                    $table->double('data',25,3)->nullable();
                    $table->unique(['id_laporan','id_question']);


                    $table->foreign('id_laporan')
                      ->references('id')->on($schema.'laporan')
                      ->onDelete('cascade')->onUpdate('cascade');

                    $table->foreign('id_question')
                      ->references('id')->on($schema.'master_pertanyaan')
                      ->onDelete('cascade')->onUpdate('cascade');

              });
          }
          if(!Schema::connection('pgsql')->hasTable($schema.'laporan_keuangan')){
              Schema::connection('pgsql')->create($schema.'laporan_keuangan',function(Blueprint $table) use ($schema){
                    $table->bigIncrements('id');

                    $table->string('id_laporan');
                    $table->bigInteger('id_question')->unsigned();
                    $table->double('data',25,3)->nullable();
                    $table->unique(['id_laporan','id_question']);


                    $table->foreign('id_laporan')
                      ->references('id')->on($schema.'laporan')
                      ->onDelete('cascade')->onUpdate('cascade');

                    $table->foreign('id_question')
                      ->references('id')->on($schema.'master_pertanyaan')
                      ->onDelete('cascade')->onUpdate('cascade');

              });
          }
            if(!Schema::connection('pgsql')->hasTable($schema.'laporan_pemda')){
              Schema::connection('pgsql')->create($schema.'laporan_pemda',function(Blueprint $table) use ($schema){
                    $table->bigIncrements('id');

                    $table->string('id_laporan');
                    $table->bigInteger('id_question')->unsigned();

                    $table->double('data',25,3)->nullable();
                    $table->unique(['id_laporan','id_question']);


                    $table->foreign('id_laporan')
                      ->references('id')->on($schema.'laporan')
                      ->onDelete('cascade')->onUpdate('cascade');

                    $table->foreign('id_question')
                      ->references('id')->on($schema.'master_pertanyaan')
                      ->onDelete('cascade')->onUpdate('cascade');

              });
          }

          return 'SAT INI DONE';
    }
}
