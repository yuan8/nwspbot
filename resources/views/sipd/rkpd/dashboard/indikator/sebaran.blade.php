@extends('adminlte::page',['side_active'=>Hp::menus('sipd')])
@section('content_header')
<a href="{{route('sipd.rkpd.d.indikator.kelkulasi.detail',['tahun'=>$tahun,'id'=>$data_ind->id])}}" class="btn btn-success btn-xs"><i class="fa fa-arrow-left"></i> Kembali</a>
<h3 class="text-center"><b>{{$daerah->nama_pemda}} {{$tahun}}</b></h3>
<div class="box box-primary">
	<div class="box-body">
		<table class="table table-bordered">
	<thead>
		<tr>
			<th>INDIKATOR</th>
			<th>TIPE</th>
			<th>FOLLOW</th>

			<th>TARGET PUSAT</th>


		</tr>
	</thead>
	<tbody>
		<tr>
			<td>{{$data_ind->nama}}</td>
			<td>{{$data_ind->tipe}}</td>
			<td>
				@if($data_ind->follow==null)

				@elseif($data_ind->follow==1)

				<i class="fa fa-arrow-up"></i> NAIK ATAU SAMA DENGAN
				@elseif($data_ind->follow==99)
				<i class="fa fa-equality"></i> <b>= </b>SAMA DENGAN

				@elseif($data_ind->follow==-1)
				<i class="fa fa-arrow-down"></i> TURUN ATAU SAMA DENGAN

				@endif

			</td>
			<td>{{number_format($data_ind->target)}} {{$data_ind->satuan}}</td>


		</tr>
		<tr>
			<td colspan="4">
				{!!nl2br($data_ind->deskripsi)!!}
			</td>
		</tr>
	</tbody>
</table>
	</div>
</div>
<hr>
<h3><b>KALKULASI</b></h3>

  <div class="row">
  	@foreach($kalkulasi as $key=>$d)

	<div class="col-lg-3 col-xs-6">
	          <!-- small box -->
	          <div class="small-box bg-aqua">
	            <div class="inner">
	              <h3>SATUAN {{$key}}</h3>

	              <p>TOTAL {{number_format($d)}}</p>
	            </div>
	            <div class="icon">
	              <i class="ion ion-file"></i>
	            </div>
	          </div>
	        </div>
	@endforeach

	@if(count($kalkulasi)==0)
	<p class="col-md-12 text-danger"><b>TIDAK TERDAPAT DATA YANG DAPAT DI KALKULASI</b></p>
	@endif
  </div>

@stop
@section('content')

<h3><b>SEBARAN</b></h3>
<div class="box box-primary">
	<div class="box-body">
		<div class="row">
			<div class="col-md-4">
				<label>JENIS</label>
				<select class="form-control" name="jenis">
					<option value="">-</option>
					<option value="OUTCOME">OUTCOME</option>
					<option value="OUTPUT">OUTPUT</option>


				</select>
			</div>
			<div class="col-md-4">
				<label>PROGRAM</label>
				<select class="form-control" name="jenis">
					<option value="">-</option>

					@foreach($program as $p)
						<option>{{$p}}</option>
					@endforeach

				</select>
			</div>
		</div>
	</div>
</div>
<div class="box">
	<div class="box-body">
		<table class="table table-bordered" id="table-init-data">
	<thead>
		<tr>
			<th>BIDANG</th>
			<th>SKPD</th>

			<th>PROGRAM</th>
			<th>KEGIATAN</th>
			<th>JENIS</th>

			<th>INDIKATOR</th>
			<th>TARGET</th>
			<th>TIPE NUMERIC</th>

			<th>SATUAN</th>
		</tr>
	</thead>
	<tbody>
		@foreach($data as $d)
			<tr>
				<td>{{$d->nama_bidang}}</td>
				<td>{{$d->nama_skpd}}</td>

				<td>{{$d->nama_program}}</td>
				<td>{{$d->nama_kegiatan}}</td>
				<td>{{$d->jenis}}</td>
				<td>{{$d->tolokukur}}</td>

				<td>{{is_numeric($d->target)?number_format($d->target):$d->target}}</td>
				<td>{!!$d->can_calculate?'<i class="fa fa-check text-success"></i>':'<i class="text-danger fa fa-times"></i>'!!}</td>

				<td>{{($d->satuan)}}</td>
			
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