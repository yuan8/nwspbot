@extends('adminlte::page',['side_active'=>Hp::menus('sipd')])
@section('content_header')
    <h1>MASTER PEMETAAN INDIKATOR</h1>
@stop
@section('content')
	<div class="btn-group" style="margin-bottom: 10px;">
		<a href="{{route('sipd.rkpd.ind.master.create')}}" class="btn btn-primary">TAMBAH</a>
	</div>
	<div class="box">
	
		<div class="box-body">
			<table class="table table-bordered">
				<thead>
					<form action="{{url()->full()}}" method="get" id="form-search">
						<tr>
						<th></th>
						<th style="width: 250px;">
								<select class="form-control input-search" id="urusan" name="urusan">
									<option value="">SEMUA</option>
									@foreach($urusan as $u)
										<option value="{{$u->id}}" {{$request->urusan==$u->id?'selected':''}}>{{$u->nama}}</option>
									@endforeach
								</select></th>
								<th style="width: 250px;">
								<select class="form-control input-search" id="sub_urusan" name="sub_urusan" >
									<option value="" parent="">SEMUA</option>
									@foreach($sub_urusan as $su)
										<option value="{{$su->id}}" parent="{{$su->id_urusan}}" {{$request->sub_urusan==$su->id?'selected':($su->id_urusan==$request->urusan?'':'disabled="true"')}} >{{$su->nama}}</option>
									@endforeach
								</select></th>
								<th></th>
								<th style="min-width: 300px;"><input type="text" name="q" class="form-control" value="{{$request->q}}" placeholder="Search Uraian"></th>
								<th colspan="4"></th>
						</tr>
					</form>
					<tr>
						<th style="width:80px;">KODE</th>
						<th style="width: 250px;">URUSAN</th>
						<th style="width: 250px;">SUB URUSAN</th>
						<th style="width: 250px;">TIPE</th>
						<th style="width: 200px;">FOLLOW</th>
						<th style="min-width: 300px;">URAIAN</th>
						<th style="min-width: 300px;">TARGET PUSAT</th>

						<th>DESKRIPSI SINGKAT</th>
						<th style="width: 100px;">AKSI</th>

					</tr>
				</thead>
				<tbody>
					@foreach($data as $d)
					<tr>
						<td style="background: #ddd"><b>{{$d->id}}</b></td>
						<td>{{$d->nama_urusan}}</td>
						<td>{{$d->nama_sub_urusan}}</td>
						<td>{{$d->tipe}}</td>
						<td>
							@if($d->follow==1)
							<i class="fa fa-arrow-up"></i> NAIK ATAU SAMA DENGAN
							@elseif($d->follow==0)
							<i class="fa fa-equality"></i> <b>= </b>SAMA DENGAN

							@elseif($d->follow==-1)
							<i class="fa fa-arrow-down"></i> TURUN ATAU SAMA DENGAN


							@endif

						</td>

						<td>{{$d->nama}}</td>
						<td>{{number_format($d->target)}} {{$d->satuan}}</td>

						<td>{{$d->deskripsi}}</td>
						<td style="min-width: 100px!important;">
							<div class="btn-group">
								<a href="{{route('sipd.rkpd.ind.master.edit',['id'=>$d->id])}}" class="btn btn-primary"><i class="fa fa-edit"></i></a>
								<a href="" class="btn btn-danger"><i class="fa fa-trash"></i></a>

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
		$('select').select2();
		$('#urusan').on('change',function(){
			var val=this.value;
			$('#sub_urusan').val(null).trigger('change');
			$('#sub_urusan option').attr('disabled',true);
			$('#sub_urusan option').each(function(i,d){
				if($(d).attr('parent')==val){
					$(d).attr('disabled',false);
				}
			});
		});

		$('.input-search').on('change',function(){
			$('#form-search').submit();
		});



	</script>

@stop