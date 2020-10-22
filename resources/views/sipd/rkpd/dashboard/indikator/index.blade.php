@extends('adminlte::page',['side_active'=>Hp::menus('sipd')])
@section('content')

@foreach($data as $key=>$d)

<div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>Indikator {{$d['nama']}}</h3>

              <p>TAHUN {!!($tahun)!!} ({{number_format($d['count'])}} Indikator)</p>
            </div>
            <div class="icon">
              <i class="ion ion-file"></i>
            </div>
            <a href="{{route('sipd.rkpd.d.indikator.detail',['tahun'=>$tahun,'tipe'=>$d['nama']])}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
@endforeach

@stop