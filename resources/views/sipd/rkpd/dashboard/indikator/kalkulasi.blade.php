@extends('adminlte::page',['layoutBuild'=>['menuBuild'=>'RKPD','tahun'=>$tahun]])
@section('content_header')

    <h1 style="margin-bottom: 10px;"><span><a href="{{route('sipd.rkpd.d.indikator.detail',['tahun'=>$tahun,'tipe'=>array_search($data_ind->tipe, Hp::tipe_indikator())])}}" class="btn btn-success btn-xs"><i class="fa fa-arrow-left"></i> Kembali</a> </span> IMPLEMENTASI INDIKATOR {{$data_ind->tipe}} TAHUN {{$tahun}}</h1>
    <p>
    	{!!nl2br($data_ind->deskripsi)!!}
    </p>
@stop
@section('content')
<div class="box box-primary">
	<div class="box-body">
		<table class="table table-bordered">
	<thead>
		<tr>
			<th>INDIKATOR</th>
			<th>TIPE</th>
			<th>TARGET PUSAT</th>


		</tr>
	</thead>
	<tbody>
		<tr>
			<td>{{$data_ind->nama}}</td>
			<td>{{$data_ind->tipe}}</td>
			<td>{{number_format($data_ind->target)}} {{$data_ind->satuan}}</td>


		</tr>
		<tr>
			<td colspan="3">
				{!!nl2br($data_ind->deskripsi)!!}
			</td>
		</tr>
	</tbody>
</table>
	</div>
</div>
<h5><b>IMPLEMENTASI DAERAH</b></h5>
<div class="box box-success">
	<div class="box-body">
		<table class="table table-bordered" id="table-init-data">
	<thead>
		<tr>
			<th>KODEPEMDA</th>
			<th>NAMA PEMDA</th>
			<th>JUMLAH INDIKATOR TOTAL</th>
			<th>JUMLAH INDIKATOR OUTCOME</th>

			<th>JUMLAH INDIKATOR OUTPUT</th>

			<th>AKSI</th>


		</tr>
	</thead>
	<tbody>
		@foreach($data as $d)
			<tr>
				<td>{{($d->kodepemda)}}</td>

				<td>{{($d->nama_pemda)}}</td>
				<td>{{number_format($d->jumlah_indikator_outcome+$d->jumlah_indikator_output)}} INDIKATOR</td>

				<td>{{number_format($d->jumlah_indikator_outcome)}} INDIKATOR</td>
				<td>{{number_format($d->jumlah_indikator_output)}} INDIKATOR</td>


				<td><a href="" class="btn btn-primary btn-sm">DETAIL</a></td>

			</tr>
		@endforeach
	</tbody>
</table>
	</div>
</div>
@stop

@section('js')
	<script type="text/javascript">
		$('#table-init-data').dataTable();
	</script>
@stop