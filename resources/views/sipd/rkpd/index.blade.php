@extends('adminlte::page',['side_active'=>Hp::menus('sipd')])

@section('content')
@if(!isset($page_block))
<div class="box box-solid">
	<div class="box-header with-border">
		<h5 class="title">DOWNLOAD DATA</h5>
	</div>
	<div class="box-body">
		<form action="{{route('sipd.rkpd.data.download',['tahun'=>$tahun])}}" method="get">
			<div class="row">
			<div class="col-md-2">
				<label>PEMDA</label>
				<select class="form-control" name="pemda[]" multiple="">
						@foreach($pemda as $b)
						<option value="{{$b->id}}">{{$b->nama_pemda}}</option>
					@endforeach

				</select>
			</div>
			<div class="col-md-2">
				<label>URUSAN</label>
				<select class="form-control" name="urusan[]" multiple="">
					@foreach($urusan as $b)
						<option value="{{$b->id}}">{{$b->nama}}</option>
					@endforeach
				</select>
			</div>
			<div class="col-md-2">
				<label>BIDANG</label>
				<select class="form-control" name="bidang[]" multiple="">
					@foreach($bidang as $b)
						<option value="{{$b}}">{{$b}}</option>
					@endforeach

				</select>
			</div>
			<div class="col-md-2">
				<label>STATUS</label>
				<select name="status" class="form-control" >
						<option value="">SEMUA</option>
						@php
						for($i=0;$i<=5;$i++){
						@endphp
							<option value="{{$i}}" {{(!empty($request->status))?($request->status==$i?'selected':''):''}}>{{Hp::status_rkpd($i)}}</option>
						@php
						}

						@endphp
					</select>
			</div>
			<div class="col-md-2">
				<label>KECOCOKAN</label>
				<select name="match" class="form-control">
						<option value="">SEMUA</option>
							<option value="true" {{(!empty($request->match))?((string)$request->match=="true"?'selected':''):''}}>SESUAI</option>
						<option value="false" {{(!empty($request->match))?((string)$request->match=="false"?'selected':''):''}}>BELUM</option>


				</select>
			</div>
			<div class="col-md-2">
				<p><b>ACTION</b></p>
				<button type="submit" class="btn btn-primary btn-xs">DOWNLOAD</button>
			</div>

		</div>
		</form>
	</div>
</div>
@endif
<div class="box">
	<div class="box-header with-border">
		<div  class="float-left">
		<h5><b>DATA RKPD TAHUN {{$tahun}}</b> </h5>
		@isset($last_list_date)
			<small>UPDATED REKAP {{\Carbon\Carbon::parse($last_list_date)->format('d F Y H:i A')}}</small>
		@endisset
		</div>


		<div class="row">
			<div class="col-md-4">

				<div class="input-group">
					<select class="form-control input-sm"  onchange="window.location.href=(this.value)" >
						@php
							for($i=(\Carbon\Carbon::now()->format('Y')+1);$i>=(\Carbon\Carbon::now()->format('Y')-1);$i--){

						@endphp
						<option value="{{route('sipd.rkpd',['tahun'=>$i])}}" {{$i==$tahun?'selected':''}}>{{$i}}</option>
						@php

						}

						@endphp
					</select>
					@if(!isset($page_block))
					<div class="input-group-btn">
					<a {{isset(($page_block))?'area-disabled="true"':''}} href="{{route('sipd.rkpd.list.update',['tahun'=>$tahun])}}" class="btn btn-success   {{isset(($page_block))?'disabled':''}}">UPDATE REKAP RKPD {{$tahun}}</a>
					</div>
					@endif

				</div>




			</div>

		</div>
	</div>


	<div class="box-body table-responsive table-fix" >
				<table class="table table-bordered" id="table-data">
			<tbody>
				@foreach($data as $key=> $d)
				@php
					$bg='';
					if($d->pagu_store){
						$bg='bg-maroon';
					}else if(!$d->rkpd_match){
						$bg='bg-danger';

					}

					if($bg=='bg-danger'){
						if($d->stored){
							$bg='bg-maroon';
						}
					}



				@endphp
				<tr class="{{$d->rkpd_match?'':'bg '.$bg}}">
					<td>{{$key+1}}</td>
					<td>{{$d->kodepemda?$d->kodepemda:$d->kodepemda_m}}</td>
					<td><b>{{$d->nama_pemda}}</b></td>
					<td>{{Hp::status_rkpd($d->status)}}</td>
					<td> Rp. {{number_format($d->pagu)}}
						@if(!$d->rkpd_match)
						/ Rp. {{number_format($d->pagu_store)}}
						@endif
					</td>
					<td>{{$d->sumber_data}}</td>
					<td>{!!$d->nomenklatur!!}</td>
					<td>{!!$d->perkada!!}</td>

					<td>{{$d->tipe_pengambilan}}</td>
					<td>{{\Carbon\Carbon::parse($d->last_date)->format('d F Y h:i A')}}</td>
					<td>{{$d->rkpd_match?'SESUAI':'BELUM'}}</td>
					<td style="width:250px;">
						<div class="btn-group">
						@if(!$d->rkpd_match)
							<a href="{{route('sipd.rkpd.data.update',['tahun'=>$tahun,'kodepemda'=>$d->kodepemda?$d->kodepemda:$d->kodepemda_m,'status'=>$d->status,'transactioncode'=>$d->transactioncode])}}" class="btn btn-success btn-xs" >
							UPDATE

						</a>


						@elseif($d->rkpd_match)
						<button onclick="force('{{route('sipd.rkpd.data.update',['tahun'=>$tahun,'kodepemda'=>$d->kodepemda?$d->kodepemda:$d->kodepemda_m,'status'=>$d->status,'transactioncode'=>$d->transactioncode])}}','')" class="btn btn-warning btn-xs" >
							UPDATE FORCE
						</button>

						@endif
						@if(($d->attemp) OR ($d->pagu_store))
								<a href="{{route('sipd.rkpd.json',['tahun'=>$tahun,'json_id'=>$d->kodepemda?$d->kodepemda:$d->kodepemda_m.'.'.$d->status.'.'.$d->transactioncode])}}" class="btn btn-primary  btn-xs">JSON</a>
								<a href="{{route('sipd.rkpd.data.download',['tahun'=>$tahun,'kodepemda'=>$d->kodepemda?$d->kodepemda:$d->kodepemda_m])}}" class="btn btn-info  btn-xs"><i class="fa fa-download"></i> .</a>
								<a href="{{route('sipd.rkpd.pemetaan',['tahun'=>$tahun,'kodepemda'=>$d->kodepemda?$d->kodepemda:$d->kodepemda_m])}}" class="btn btn-navy bg-navy btn-xs">PEMETAAN</a>
						@endif

						</div>
					</td>


				</tr>
				@endforeach
			</tbody>
			<thead class="bg-navy">
				<form id="form-search" method="get" action="{{url()->full()}}">
					@if(!isset($page_block))
						<tr>
				<th>NO</th>
				<th colspan="2">
					<input type="text" name="q" class="form-control" placeholder="Search Pemda" value="{{$request->q}}" onchange="$('#form-search').submit()">
				</th>
				<th>
					<select name="status" class="form-control" onchange="$('#form-search').submit()">
						<option value="">SEMUA</option>
						@php
						for($i=0;$i<=5;$i++){
						@endphp
							<option value="{{$i}}" {{(($request->status==$i)and ($request->status!=null))?'selected':''}}>{{Hp::status_rkpd($i)}}</option>
						@php
						}

						@endphp
					</select>
				</th>
				<th colspan="5"></th>
				<th>
					<select name="match" class="form-control" onchange="$('#form-search').submit()">
						<option value="">SEMUA</option>
					<option value="true" {{(!empty($request->match))?((string)$request->match=="true"?'selected':''):''}}>SESUAI</option>
						<option value="false" {{(!empty($request->match))?((string)$request->match=="false"?'selected':''):''}}>BELUM</option>
					</select>
				</th>
				<th></th>
				<th></th>


			</tr>
			@endif
				</form>
				<tr>
				<th>NO</th>
				<th>KODEPEMDA</th>
				<th>NAMAPEMDA</th>
				<th>STATUS</th>
				<th>PAGU</th>
				<th>SUMBER DATA</th>
				<th>NOMENKLATUR</th>
				<th>PERKADA</th>
				<th>METODE PENGAMBILAN DATA (SIPD)</th>
				<th>UPDATED RKPD AT</th>
				<th>KECOCOKAN</th>
				<th>ACTION</th>

			</tr>

			</thead>
		</table>
	</div>
</div>
@if(count($data)>0)

@endif
@stop

@section('js')
	<script type="text/javascript">
		$('select').select2();
		// $('#table-data').dataTable();

	</script>
@stop
