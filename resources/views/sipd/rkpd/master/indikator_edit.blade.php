@extends('adminlte::page',['side_active'=>Hp::menus('sipd')])
@section('content_header')

    <h1><span><a href="{{route('sipd.rkpd.ind.master')}}" class="btn btn-success btn-xs"><i class="fa fa-arrow-left"></i> Kembali</a> </span> EDIT MASTER PEMETAAN INDIKATOR</h1>
@stop
@section('content')

	<div class="row">
		<div class="col-md-6">
			<div class="box">
				<form action="{{route('sipd.rkpd.ind.master.update',['id'=>$data->id])}}" method="post">
					@csrf
					<div class="box-body">
					<div class="row">
						<div class="col-md-6">
							<label>URUSAN</label>
							<select class="form-control select2-init" id="urusan" name="urusan" required="">
								<option value="">-</option>
								@foreach($urusan as $u)
									<option value="{{$u->id}}" {{$data->id_urusan==$u->id?'selected':''}}>{{$u->nama}}</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-6">
							<label>URUSAN</label>
							<select class="form-control select2-init" id="sub_urusan" name="sub_urusan" required="">
								@foreach($sub_urusan as $su)
									<option value="{{$su->id}}" parent="{{$su->id_urusan}}" {{$data->id_urusan==$su->id_urusan?'':'disabled'}} {{$data->id_sub_urusan==$su->id?'selected':''}} >{{$su->nama}}</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-6">
							<label>TIPE</label>
							<select class="form-control select2-init" name="tipe" required="">
								
								@foreach(Hp::tipe_indikator() as $i)
									<option {{$i==$data->tipe?'seelcted':''}} value="{{$i}}">{{$i}}</option>
								@endforeach
							</select>

						</div>
						<div class="col-md-6">
							<label>FOLLOW <small></small></label>
							<select class="form-control select2-init" name="follow">
								
									<option  value="">-</option>
									<option  value="1" {{$data->follow==1?'selected':''}}><i class="fa fa-fa-angle-up" ></i> NAIK ATAU SAMA DENGAN</option>
									<option  value="0" {{$data->follow==0?'selected':''}}><i class="fa fa-equal"></i> SAMA DENGAN</option>
									<option  value="-1" {{$data->follow==-1?'selected':''}}><i class="fa fa-angle-down"></i> TURUN ATAU SAMA DENGAN</option>
							</select>
							<small>Hanya Digunakan Jika Indikator Merupakan kalkulasi Acceptable</small>

						</div>
						<div class="col-md-6">
							<label>TARGET</label>
							<input type="number" min="0" name="target" required="" class="form-control" value="{{$data->target}}">		
							
						</div>
						<div class="col-md-6">
							<label>SATUAN</label>
							<select class="form-control" name="satuan" id="satuan" required="">
									<option value="{{$data->satuan}}" selected="">{{$data->satuan}}</option>

									
							</select>
							
						</div>
						<div class="col-md-12">
							<label>URAIAN</label>
							<input type="text" name="uraian" class="form-control" required="" value="{{$data->nama}}">
						</div>
						<div class="col-md-12">
							<label>DESKRIPSI</label>
							<textarea class="form-control" name="deskripsi">{!!($data->deskripsi)!!}</textarea>
						</div>
					</div>
				</div>
				<div class="box-footer">
					<button class="btn btn-primary" type="submit">UPDATE</button>
				</div>

				</form>
			</div>

		</div>
	</div>
@stop


@section('js')
	<script type="text/javascript">
		$('select.select2-init').select2();
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

		$('#satuan').select2({
			"tags":true,
			 "ajax": {
			 	"delay": 250,
			   	"url": "{{route('sipd.rkpd.ind.master.satuan')}}",
			    "data": function (params) {
			      var query = {
			        q: params.term,
			      };
			      return query;
			    },
			    "processResults": function (data) {
			      return {
			        results: data.results
			      };
			    }
  			}
		});


	</script>

@stop