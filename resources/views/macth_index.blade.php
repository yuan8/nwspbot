@extends('layouts.app')

@section('content')
<table class="table table-bordered" style="background: #fff">
	<thead>
		<tr>
			<th>KODEPEMDA</th>
			<th>PEMDA</th>
			<th>DB LAMA</th>
			<th>TERCOPY</th>

		</tr>
	</thead>
	<tbody id="append-dom">
		
	</tbody>
</table>

<script>
	
	function get_data(link){
		$.get(link,function(res){
			if(res.link){
				$('#append-dom').append(res.data);
				get_data(res.link);
			}else{
				$('#append-dom').append(res.data);
				
			}
		});
	}

	setTimeout(function(){
		get_data('{{route('init-match',['tahun'=>$tahun,'kodepemda'=>0])}}');

	},500);

</script>
@stop