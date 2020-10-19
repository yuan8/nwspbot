<?php

namespace App\SIPD\RKPD;

use Illuminate\Database\Eloquent\Model;

class ModelBidang extends Model
{
    //

    public function __construct(integer $tahun=2020)
    {
        $this->setTable('rkpd'.$tahun.'_bidang'));
        parent::__construct([]);
    }


}
