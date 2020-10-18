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
}
