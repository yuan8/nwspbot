@extends('adminlte::page',['side_active'=>Hp::menus('sipd')])
@section('content')

@foreach(Hp::tipe_indikator() as $keytipe=>$tipe)

<div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>Indikator {{$tipe}}</h3>

              <p>TAHUN {!!($tahun)!!}</p>
            </div>
            <div class="icon">
              <i class="ion ion-file"></i>
            </div>
            <a href="{{route('sipd.rkpd.d.indikator.detail',['tahun'=>$tahun,'tipe'=>$keytipe])}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
@endforeach

@stop