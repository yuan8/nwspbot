<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PetaIndikator extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $schema='rkpd.';
         if(!Schema::connection('pgsql')->hasTable($schema.'master_peta_indikator')){
               Schema::connection('pgsql')->create($schema.'master_peta_indikator',function(Blueprint $table) use ($schema){
                    $table->bigIncrements('id');
                    $table->text('nama');
                    $table->longText('deskripsi')->nullable();
                    $table->bigInteger('id_urusan')->unsigned();
                    $table->bigInteger('id_sub_urusan')->unsigned();
                    $table->timestamps();


                    $table->foreign('id_urusan')
                    ->references('id')->on('public.master_urusan')
                    ->onDelete('cascade')->onUpdate('cascade');

                    $table->foreign('id_sub_urusan')
                        ->references('id')->on('public.master_sub_urusan')
                        ->onDelete('cascade')->onUpdate('cascade');

                });
                    
         }
    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        $schema='rkpd.';

        Schema::connection('pgsql')->dropIfExists($schema.'master_peta_indikator');

    }
}
