<?php

namespace App\SAT;

use Illuminate\Database\Eloquent\Model;

class MASTERPDAM extends Model
{
    //
    protected $table='sat.master_pdam';

    protected $fillable=['id','pemda_id','name','address','regencies_id','prvincies_id'];

}
