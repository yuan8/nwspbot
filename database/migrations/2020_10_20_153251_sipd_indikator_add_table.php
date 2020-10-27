<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SipdIndikatorAddTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
         Schema::table('rkpd.master_2020_status', function($table) {
                $table->string('sumber_data')->nullable();
                $table->string('perkada')->nullable();
                $table->string('nomenklatur')->nullable();

        });
         Schema::table('rkpd.master_2020_status_data', function($table) {
                 $table->string('sumber_data')->nullable();
                $table->string('perkada')->nullable();
                $table->string('nomenklatur')->nullable();
                    $table->string('dokumen_path')->nullable();


        });

            Schema::table('rkpd.master_2021_status', function($table) {
                $table->string('sumber_data')->nullable();
                $table->string('perkada')->nullable();
                $table->string('nomenklatur')->nullable();

        });
         Schema::table('rkpd.master_2021_status_data', function($table) {
                 $table->string('sumber_data')->nullable();
                $table->string('perkada')->nullable();
                $table->string('nomenklatur')->nullable();
                    $table->string('dokumen_path')->nullable();
                

        });
        // Schema::table('rkpd.master_2020_program_capaian', function($table) {
        //          $table->bigInteger('rpjmn')->nullable();
        //         $table->bigInteger('spm')->nullable();
        //         $table->bigInteger('sdgs')->nullable();
        //         $table->bigInteger('lainya')->nullable();
        //          $table->double('target_peneyesuaian',25,3)->nullable();

        // });



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
