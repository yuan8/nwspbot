<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelperProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

     static function status_rkpd($status){
      switch ($status) {
        case 0:
        $status='BELUM TERDAPAT STATUS';
          break;
        case 1:
        $status='PERSIAPAN';
          break;
        case 2:
        $status='RKPD RANWAL';
          break;
        case 3:
          $status='RKPD RANCANGAN';
          break;
        case 4:
        $status='RKPD AHIR';
          break;
        case 5:
        $status='RKPD FINAL';
          break;
        default:
          // code...
          break;
      }
      return $status;
    }


    static function tipe_indikator(){
      return [
        'rpjmn'=>'RPJMN',
        'spm'=>'SPM',
        'sdgs'=>'SDGS',
        'lainya'=>'LAINYA'
      ];
    }

    static function menus($for){
      switch (strtolower($for)) {
        case 'sipd':
          # code...
         return  static::sipd_menu();
          break;
        
        default:
          # code...
          break;
      }
    }

    static function sipd_menu(){
      $menus=[
        'MENU RKPD',
        [
          'text'=>'RKPD',
          'href'=>route('sipd.rkpd',['tahun'=>isset($tahun)?$tahun:date('Y')]),
        ],
        [
          'text'=>'MASTER PEMETAAN INDIKATOR',
          'href'=>route('sipd.rkpd.ind.master'),
        ],
        [
          'text'=>'DASHBOARD',
          'href'=>route('sipd.rkpd.d.indikator',[isset($tahun)?$tahun:date('Y')]),
        ]

      ];

      return $menus;
    }
}
