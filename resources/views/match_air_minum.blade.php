@extends('layouts.app')

@section('content')
<table class="table table-bordered" style="background: #fff">
	<thead>
		<tr>
			<th>KODEPEMDA</th>
			<th>PEMDA</th>
			<th>JUMLAH KEGIATAN AIR MINUM</th>

		</tr>
	</thead>
	<tbody id="append-dom">
		@foreach($data as $d)
			<tr>
				<td>{{$d->id}}</td>
				<td>{{$d->nama}}</td>
				<td>{{number_format($d->jumlah_kegiatan)}} Kegiatan</td>

			</tr>
		@endforeach
	</tbody>
</table>

<script>
	
	

</script>
@stop