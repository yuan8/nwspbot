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
        //
         Schema::table('rkpd.master_2021_kegiatan_indikator', function($table) {
                //   $table->bigInteger('rpjmn')->nullable();

                // $table->bigInteger('spm')->nullable();
                // $table->bigInteger('sdgs')->nullable();
                // $table->bigInteger('lainya')->nullable();

                 // $table->double('target_peneyesuaian',25,3)->nullable();

        });
         Schema::table('rkpd.master_2021_subkegiatan_indikator', function($table) {
                //  $table->bigInteger('rpjmn')->nullable();
                // $table->bigInteger('spm')->nullable();
                // $table->bigInteger('sdgs')->nullable();
                // $table->bigInteger('lainya')->nullable();     
                 // $table->double('target_peneyesuaian',25,3)->nullable();

        });
        Schema::table('rkpd.master_2021_program_capaian', function($table) {
                //  $table->bigInteger('rpjmn')->nullable();
                // $table->bigInteger('spm')->nullable();
                // $table->bigInteger('sdgs')->nullable();
                // $table->bigInteger('lainya')->nullable();
                 // $table->double('target_peneyesuaian',25,3)->nullable();
            
        });



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
