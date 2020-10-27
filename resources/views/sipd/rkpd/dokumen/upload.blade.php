@extends('adminlte::page',['side_active'=>Hp::menus('sipd')])

@section('content')
<div class="box box-solid">
	<form action="{{route('sipd.rkpd.dokumen.store',['tahun'=>$tahun])}}" method="post" enctype='multipart/form-data'>
		@csrf
		<div class="box-header with-border">
			<h5 class="title">UPLOAD DATA RKPD {{$tahun}}</h5>
		</div>
		<div class="box-body">
			<div class="row">
				<div class="col-md-6">
					<label>PEMDA</label>
					<select class="form-control" required="" name='kodepemda'>
						@foreach($daerah as $d)
						<option value="{{$d->id}}">{{$d->nama_pemda}}</option>
						@endforeach
					</select>
				</div>
				<div class="col-md-6">
					<label>FILE RKPD EXCEL</label>
					<input type="file" name="file" class="form-control" required="" accept=".xls,.xlsx">
				</div>

			</div>
		</div>
		<div class="box-footer">
			<button class="btn btn-primary" type="submit">UPLOAD</button>
		</div>
	</form>
</div>

@stop