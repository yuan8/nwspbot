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

		$.ajax({
			   url: link,
			   success: function(data){
			        //...
			        res=data;
			        if(res.link){
						$('#append-dom').append(res.data);
						get_data(res.link);
					}else{
						$('#append-dom').append(res.data);

					}
			   },
			   timeout: 1000000 //in milliseconds
			});
		
	}

	setTimeout(function(){
		get_data('{{route('init-match',['tahun'=>$tahun,'kodepemda'=>isset($_GET['kodepemda'])?$_GET['kodepemda']:0])}}');

	},500);

</script>
@stop