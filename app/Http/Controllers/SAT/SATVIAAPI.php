<?php

namespace App\Http\Controllers\SAT;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\SAT\LAPORAN;

class SATVIAAPI extends Controller
{
    //

    public function index($tahun){

return datatables()->eloquent(
          LAPORAN::query()
        )->toJson();

    }

    public function api_index($tahun,Request $request){

        

        

     


    }
}
