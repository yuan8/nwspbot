<?php

namespace App\SAT;

use Illuminate\Database\Eloquent\Model;
use App\SAT\MASTERPERTANYAAN;

class LAPORANKEUANGAN extends Model
{
    //
    protected $table='sat.laporan_keuangan';
    protected $with=['_question'];
    

    public function _question(){
    	return $this->belongsTo(MASTERPERTANYAAN::class,'id_question');
    }
}
