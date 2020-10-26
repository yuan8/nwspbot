@extends('adminlte::page',['side_active'=>Hp::menus('sat')])

@section('content')
<div class="box">
	
	<div class="box-body table-responsive">
		<table class="table table-bordered" id="table-data">
			<thead>
				<tr>
					<th style="width: 250px;"></th>

					<th>KODEPEMDA</th>
					<th>PEMDA</th>
					<th>NAMA PDAM</th>
					<th>PERODE LAPORAN</th>
					<th>ENTRY DATE</th>

					<th>ID LAPORAN</th>
				</tr>
			</thead>
			<tbody>
				@foreach($data as $d)
					<tr class="{{$d['pemda_id']==null?'bg-danger':''}}">
						<td style="width: 250px;">
							<select class="form-control" name="laporan[{{$d['id']}}]['pemda_id']" onchange="pemetaan_change(this.value)">
								<option value="||{{$d['id']}}">-</option>
								@foreach($pemda as $p)
								@php
								$p=(array)$p;
								@endphp
								<option value="{{$p['id']}}||{{$d['id']}}" {{$p['id']==$d['pemda_id']?'selected':''}}>{{$p['nama_pemda']}}</option>
								@endforeach
							</select>
						</td>
						<td>
							{{$d['pemda_id']}}
						</td>
						<td>{{$d['nama_pemda']}}</td>
						<td>
							{{$d['site_name'].' / '.$d['address'] }}
						</td>
						
							<td>
							{{\Carbon\Carbon::parse($d['entry_period_year'].'-'.$d['entry_period_month'].'-01')->format('Y F')}}
					</td>
							<td>
						{{\Carbon\Carbon::parse($d['insert_date'])->format('d F Y H:I A')}}
					</td>
					<td>
						{{$d['id']}}
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

	$('#table-data').dataTable();


	function pemetaan_change(id){
		var data={
			'id':id.split('||')[1],
			'pemda_id':parseInt(id.split('||')[0]),
		};

		$.post('{{route('api.sat.api.pemetaan.update',['tahun'=>$tahun])}}',data,function(res){
			console.log(res);
		});
	}

</script>
@stop