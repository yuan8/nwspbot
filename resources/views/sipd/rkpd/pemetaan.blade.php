@extends('adminlte::page',['layoutBuild'=>['menuBuild'=>'RKPD','tahun'=>$tahun]])
@section('content_header')
  <h3 class=""><span><a href="{{route('sipd.rkpd',['tahun'=>$tahun])}}" class="btn btn-xs btn-success btn-circle">
    <i class="fa fa-arrow-left"></i> Kembali
  </a></span> PEMETAAN {{Hp::status_rkpd($status->status)}} {{$tahun}} - {{$daerah->nama}}</h3>
@stop

@section('content')
<link rel="stylesheet" type="text/css" href="{{asset('bower_components/select2/dist/css/select2.min.css')}}">
<script type="text/javascript" src="{{asset('bower_components/select2/dist/js/select2.min.js')}}"></script>
<script type="text/javascript">

   function change_pemetaan(context,ids,data){
      var data_json={};
      data_json['id']=(ds.split(','));
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

        $('[name="kegiatan['+ids+'][id_sub_urusan]"] option:nth-child(1)').val(data.id_urusan+"||");
        $('[name="kegiatan['+ids+'][id_sub_urusan]"] option:nth-child(1)').attr('selected',true);
            $('[name="kegiatan['+ids+'][id_sub_urusan]"] option:nth-child(1)').attr('disabled',false);
        $('[name="kegiatan['+ids+'][id_sub_urusan]"]').val(data.id_urusan+"||").trigger('change');

      }



      if(context=='S'){

      if(data.id_urusan){

         $.post('{{route('api.sipd.rkpd.pemetaan.update.kegiatan',['tahun'=>$tahun,'kodepemda'=>$daerah->id])}}',data_json,function(res){
            console.log(res);
          });
        }

      }





   }
</script>

<div class="box">
  <div class="box-header">
    <h5><p><b>Mengambil Data..</b></p></h5>
    <div class="progress">
      <div class="progress-bar bg-yellow" role="progressbar" aria-valuenow="70" id="loader-data-json"
      aria-valuemin="0" aria-valuemax="100" style="width:0%">
        <span class="">0% sss</span>
      </div>
</div>
  </div>
  <div class="box-body table-responsive">


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
      <tbody id="content-pemetaan">

      </tbody>

    </table>

  </div>

</div>
@stop

@section('js')

<script type="text/javascript">


  $('select').select2();
 function  pemetaan_indikator(index=1,ids,k_ids='',id_urusan,id_sub_urusan){
    if(id_urusan && id_sub_urusan){
       if(index==1){
      $('#pemetaan-indikator .modal-header h4').html('PEMETAAN INDIKATOR PROGRAM');
      var url='{{route('api.sipd.rkpd.pemetaan.api.get.indikator',['tahun'=>$tahun,'kodepemda'=>$daerah->id,'context'=>1])}}';
    }else{
      $('#pemetaan-indikator .modal-header h4').html('PEMETAAN INDIKATOR KEGIATAN');
      var url='{{route('api.sipd.rkpd.pemetaan.api.get.indikator',['tahun'=>$tahun,'kodepemda'=>$daerah->id,'context'=>2])}}';
    }
       $('#pemetaan-indikator #content-pemetaan-indikator').html('');

      $.post(url,{ids:ids.split(','),k_ids:k_ids.split(',')},function(res){
         $('#pemetaan-indikator #content-pemetaan-indikator').html(res);

      });
    }else{
      $('#pemetaan-indikator #content-pemetaan-indikator').html('<h5 class=text-center>MOHON MELAKUKAN PEMETAAN URUSAN DAN SUB URUSAN TERLEBIH DAHULU</h5>');
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
      <div class="modal-body table-responsive" id="content-pemetaan-indikator">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<script type="text/javascript">

  var total_data={{$data->count}};
  var attemp_data=1;
  var data_show=0;
  var data_perpage=60;

  function get_content(){
      var data_request=<?php echo json_encode($request->all(),true); ?>;
      data_request= JSON.stringify(data_request).replace('[','{').replace(']','}');
      data_request=JSON.parse(data_request);

      if(data_request==[]){
        var data_request={
          'data_request':attemp_data,
          'paginate':data_perpage
        };

      }else{
          data_request.page=attemp_data;
          data_request.paginate=data_perpage;
      }




    $.get('{{route('sipd.rkpd.pemetaan.data',['tahun'=>$tahun,'kodepemda'=>$kodepemda])}}',data_request,function(res){
        $('#content-pemetaan').append(res.data);
        $('select.select2-init-'+attemp_data).select2();
        attemp_data+=1;
        data_show+=res.count;

        if((attemp_data<=parseInt(total_data/data_perpage))||(total_data>data_show)){
          setTimeout(function(){
            get_content();
          },200);
        }


        if(total_data<=data_show){
          $('#loader-data-json').parent().parent().remove();
        }else{
          $('#loader-data-json').css('width',((data_show/total_data)*100)+'%');
          $('#loader-data-json span').html(((data_show/total_data)*100).toFixed(2)+'% Complate' );
        }


    });

  }

  $(function(){
    get_content();
  });


  function pemetaan_indikator_update(id,context,tipe,class_id){

    var value=[];
    $('.'+class_id).each(function(i,d){
      value=value.push($(d).val());
    });



    var data_request={
      id:id,
      context:context,
      data_ids:value,
      tipe:tipe
    };
    console.log(data_request);

     $.post('{{route('api.sipd.rkpd.pemetaan.api.update.indikator',['tahun'=>$tahun,'kodepemda'=>$kodepemda])}}',data_request,function(res){
       console.log(res);
    });
  }
</script>


@stop
