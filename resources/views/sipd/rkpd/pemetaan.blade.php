@extends('adminlte::page',['layoutBuild'=>['menuBuild'=>'RKPD','tahun'=>$tahun]])

@section('content')
<link rel="stylesheet" type="text/css" href="{{asset('bower_components/select2/dist/css/select2.min.css')}}">
<script type="text/javascript" src="{{asset('bower_components/select2/dist/js/select2.min.js')}}"></script>
<script type="text/javascript">

   function change_pemetaan(context,ids,data){
      var data_json={};
      data_json['id']=ids.split(',');
      data_json['data']=data;

      if(context=='U'){
        console.log('[name="kegiatan['+ids+'][id_sub_urusan]"] option');
        console.log($('[name="kegiatan['+ids+'][id_sub_urusan]"] option').html());

        $('[name="kegiatan['+ids+'][id_sub_urusan]"] option').attr('disabled',true);
        $('[name="kegiatan['+ids+'][id_sub_urusan]"] option').each(function(i,d){
          if(parseInt($(d).attr('data-parent'))==parseInt(data.id_urusan)){
            $(d).attr('disabled',false);
          }
        });

        $('[name="kegiatan['+ids+'][id_sub_urusan]"] option:nth-child(0)').val(data.id_urusan+"||");

      }

      if(data.id_urusan){
        $.post('{{route('api.sipd.rkpd.pemetaan.update.kegiatan',['tahun'=>$tahun,'kodepemda'=>$daerah->id])}}',data_json,function(res){
          console.log(res);
        });
      }else if(context=='S'){
        $('[name="['+ids+'][id_urusan]"]').trigger('change');
      }



   }
</script>
<a href="{{route('sipd.rkpd',['tahun'=>$tahun])}}" class="btn btn-sm btn-success btn-circle">
  <i class="fa fa-arrow-left"></i> Kembali
</a>
<h3 class="text-center">PEMETAAN {{Hp::status_rkpd($status->status)}} {{$tahun}} - {{$daerah->nama}}</h3>
<div class="box">
  <div class="box-body">

    <table class="table table-bordered">
      <thead>
        <tr class="bg-primary">
        <form action="{{url()->full()}}" method="get" id="form-search">
            <th colspan="2">
              <p><b>PAGU</b></p>
              <p>Rp. {{number_format($pagu)}}</p>
            </th>
              <th style="width: 250px;">
                <select class="form-control" name="bidang" onchange="$('#form-search').submit()">
                  <option value="">-</option>
                  @foreach($bidang as $b)
                    <option value="{{$b}}" {{$b==$request->bidang?'selected':''}}>{{$b}}</option>
                  @endforeach
                </select>
              </th>
              <th></th>
              <th style="width: 250px;">
                <input type="hidden" name="page" value="1" >
                <select class="form-control" name="skpd" onchange="$('#form-search').submit()">
                  <option value="">-</option>
                  @foreach($skpd as $b)
                    <option value="{{$b}}" {{$b==$request->skpd?'selected':''}}>{{$b}}</option>
                  @endforeach
                </select>
              </th>
              <th colspan="7">
                <input type="text" name="q" class="form-control" placeholder="Search Kegiatan" value="{{$request->q}}" onchange="$('#form-search').submit()">
              </th>


        </form>
        </tr>
        <tr class="bg-info">
          <th>NO</th>
          <th>KODEBIDANG</th>
          <th style="width: 250px;">URAIBIDANG</th>
          <th>KODESKPD</th>
          <th style="width: 250px;">URAISKPD</th>
          <th>KODEPROGRAM</th>
          <th>URAIPROGRAM</th>
          <th>KODEKEGIATAN</th>
          <th>URAIKEGIATAN</th>
          <th>PAGU</th>
          <th style="width: 250px;">URUSAN</th>
          <th style="width: 250px;">SUB URUSAN</th>
        </tr>
      </thead>
      <tbody>
        @foreach($data as $key=>$d)
        <tr>
          <td><B>{{$key+1}}.</B></td>
          <td>{{$d->p_kodebidang}}</td>
          <td style="width: 250px;">{{$d->p_uraibidang}}</td>
          <td>{{$d->p_kodeskpd}}</td>
          <td style="width: 250px;">{{$d->p_uraiskpd}}</td>
          <td>{{$d->p_kodeprogram}}</td>
          <td>{{$d->p_uraiprogram}}
            <br>
            <br>

            <button class="btn btn-xs btn-primary" onclick="pemetaan_indikator(1)">Pemetaan Indikator</button>
          </td>

          <td>{{$d->kodekegiatan}}</td>
          <td>{{$d->uraikegiatan}}
          <br>
          <br>

            <button class="btn btn-xs btn-primary " onclick="pemetaan_indikator(2)">Pemetaan Indikator</button></td>
          <td>Rp. {{number_format($d->pagu)}}</td>

          <td style="width:250px;">
            <select class="form-control" name="kegiatan[{{$d->ids}}][id_urusan]" onchange="change_pemetaan('U','{{$d->ids}}',({id_urusan:this.value,id_sub_urusan:null}))">
              <option value="">-</option>
              @foreach($urusan as $u)
                <option value="{{$u->id}}" {{$d->id_urusan==$u->id?'selected':''}}>{{$u->nama}}</option>
              @endforeach
            </select>
          </td>
          <td style="width: 250px;">
            <select class="form-control" name="kegiatan[{{$d->ids}}][id_sub_urusan]" def-parent="{{$d->id_urusan}}"  onchange="change_pemetaan('S','{{$d->ids}}',({id_urusan:this.value.split('||')[0],id_sub_urusan:this.value.split('||')[1]}))">
               <option value="||">-</option>
                @foreach($sub_urusan as $u)
                <option value="{{$u->id_urusan}}||{{$u->id}}" data-parent="{{$u->id_urusan}}" {{$d->id_sub_urusan==$u->id?'selected':''}}>{{$u->nama}}</option>
              @endforeach
            </select>
          </td>

        </tr>
        @endforeach
      </tbody>

    </table>

  </div>

</div>
@stop

@section('js')

<script type="text/javascript">
  $('select').select2();
 function  pemetaan_indikator(index=1){
    if(index==1){
      $('#pemetaan-indikator .modal-header h4').html('PEMETAAN INDIKATOR PROGRAM');
    }else{
      $('#pemetaan-indikator .modal-header h4').html('PEMETAAN INDIKATOR KEGIATAN');

    }
    $('#pemetaan-indikator').modal();
  }
</script>
  <div id="pemetaan-indikator" class="modal fade bd-example-modal-xl" role="dialog">
  <div class="modal-dialog modal-xl">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">PEMETAAN INDIKATOR PROGRAM</h4>
      </div>
      <div class="modal-body table-responsive">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th colspan="4">PENILAIAN</th>
              <th style="max-width:200px">INDIKATOR</th>
              <th style="width: 100px;">TARGET</th>
              <th style="width: 150px;">PAGU</th>

            </tr>
          </thead>
          <tr>
            <td style="max-width: 100px">
              <p>Indikator Air minum?</p>
            </td>
            <td style="max-width: 100px">
              <p>Memiliki target SR?</p>
            </td>
              <td style="max-width: 100px">
              <p>Memiliki target Lokasi ?</p>
            </td>
              <td style="max-width: 100px">
              <p>Memiliki target Pembuatan Dokumen Kebijakan Air Minum?</p>
            </td>
            <td>
              <p>simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book  </p>
            </td>
            <td>
              20 SR
            </td>
            <td></td>

          </tr>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


@stop


