@extends('adminlte::page',['layoutBuild'=>['layout_topnav'=>true,'tahun'=>date('Y')]])

@section('content')

<div class="row " id="list-bot">



</div>

@stop


@section('js')
<script type="text/javascript">
  	$.get('{{route('box.nuwsp.api',['tahun'=>$tahun])}}',function(res){
      $('#list-bot').append('<div class="col-md-4">'+res+'</div>');
 	 });

	$.get('{{route('box.sipd',['tahun'=>$tahun])}}',function(res){
		$('#list-bot').append('<div class="col-md-4">'+res+'</div>');
		});
	$.get('{{route('box.sirup',['tahun'=>$tahun])}}',function(res){
			$('#list-bot').append('<div class="col-md-4">'+res+'</div>');
	});




</script>
@stop
