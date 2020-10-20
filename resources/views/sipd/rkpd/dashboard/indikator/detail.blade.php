@extends('adminlte::page',['layoutBuild'=>['menuBuild'=>'RKPD','tahun'=>$tahun]])
@section('content_header')

    <h1><span><a href="{{route('sipd.rkpd.d.indikator',['tahun'=>$tahun])}}" class="btn btn-success btn-xs"><i class="fa fa-arrow-left"></i> Kembali</a> </span> INDIKATOR {{$tipe}} TAHUN {{$tahun}}</h1>
@stop
@section('content')

<div class="box">
	<div class="box-body">
		<table class="table table-bordered" id="table-init-data">
	<thead>
		<tr>
			<th>URUSAN</th>
			<th>SUB URUSAN</th>
			<th>INDIKATOR</th>
			<th>TARGET PUSAT</th>
			<th>SATUAN</th>
			<th>DESKRIPSI</th>
			<th>JUMLAH PEMDA TERIMPLEMENTASI</th>

			<th>AKSI</th>
		</tr>
	</thead>
	<tbody>
		@foreach($data as $d)
			<tr>
				<td>{{$d->nama_urusan}}</td>
				<td>{{$d->nama_sub_urusan}}</td>


				<td>{{$d->nama}}</td>
				<td>{{number_format($d->target)}}</td>
				<td>{{($d->satuan)}}</td>
				<td>{!!nl2br($d->deskripsi)!!}</td>
				<td>{!!number_format($d->jumlah_pemda)!!} PEMDA</td>

				<td><a href="{{route('sipd.rkpd.d.indikator.kelkulasi.detail',['tahun'=>$tahun,'id'=>$d->id])}}" class="btn btn-primary btn-sm">HASIL KALKULASI </a></td>

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