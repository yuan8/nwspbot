@extends('adminlte::page',['layoutBuild'=>['layout_topnav'=>true,'tahun'=>date('Y')]])

@section('content')
<div class="box">
	<div class="box-body">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>KODEPEMDA</th>
					<th>PEMDA</th>
					<th>NAMA PDAM</th>
					<th>NAMA PERODE LAPORAN</th>
					<th>ID LAPORAN</th>
					<th>OPRASIONAL</th>
					<th>KEUANGAN</th>
					<th>KEUANGAN</th>
					



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


					</tr>
				@endforeach

			</tbody>
		</table>
	</div>
</div>

@stop