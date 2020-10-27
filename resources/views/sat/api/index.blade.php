@extends('adminlte::page',['side_active'=>Hp::menus('sat')])

@section('content')
<div class="row">
	<div class="col-md-5">
		<div class="input-group" style="margin-bottom: 10px;">
					<select class="form-control input-sm"  onchange="window.location.href=(this.value)" >
						@php
							for($i=(\Carbon\Carbon::now()->format('Y')+1);$i>=(\Carbon\Carbon::now()->format('Y')-1);$i--){

						@endphp
						<option value="{{route('nuwsp.sat',['tahun'=>$i])}}" {{$i==$tahun?'selected':''}}>{{$i}}</option>
						@php

						}

						@endphp
					</select>
				</div>
	</div>
</div>
<div class="box">
	
	<div class="box-body">
		<table class="table table-bordered" id="table-data">
			<thead>
				<tr>
					<th>KODEPEMDA</th>
					<th>PEMDA</th>
					<th>NAMA PDAM</th>
					<th>NAMA PERODE LAPORAN</th>
					<th>ID LAPORAN</th>
					<th>KINERJA</th>
					<th>KEUANGAN</th>

					<th>OPRASIONAL</th>
					<th>SDM</th>
					<th>ACTION</th>

				</tr>
			</thead>
			<tbody>
				@foreach($data as $d)
					<tr>
						<td>{{$d->pemda_id}}</td>
						<td>{{$d->nama_pemda}}</td>

						<td>{{$d->nama_pdam}}</td>
						<td>{{\Carbon\Carbon::parse($d->entry_period_year.'-'.$d->entry_period_month.'-01')->format('Y F')}}</td>
						<td>{{$d->id}}</td>
						<td>{{number_format($d->laporan_kinerja)}}</td>
						<td>{{number_format($d->laporan_keuangan)}}</td>
						<td>{{number_format($d->laporan_oprasional)}}</td>
						<td>{{number_format($d->laporan_sdm)}}</td>
						<td>
							<div class="btn-group">
								<a href="{{route('nuwsp.sat.detail',['tahun'=>$tahun,'kodelaporan'=>$d->id])}}" class="btn btn-primary btn-sm">DETAIL</a>
							</div>
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
	$('#table-data').dataTable();
</script>
@stop