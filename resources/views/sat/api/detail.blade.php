@extends('adminlte::page',['side_active'=>Hp::menus('sat')])

@section('content')
<div class="box box-solid">
	<div class="box-body">
		<table class="table-bordered table">
			<thead>
				<tr>
					<td colspan="5">
						<b>ID LAPORAN : </b>{{$data['id']}}
					</td>
				</tr>
				<tr>
					<th>KODEPEMDA</th>
					<th>NAMA PEMDA</th>
					<th>NAMA PDAM</th>
					<th>PERIODE LAPORAN</th>
					<th>PERIODE INPUT</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>{{$data['_pdam']['pemda_id']}}</td>
					<td>{{$data['nama_pemda']}}</td>
					<td>{{$data['_pdam']['name']}}</td>
					<td>
							{{\Carbon\Carbon::parse($data['entry_period_year'].'-'.$data['entry_period_month'].'-01')->format('Y F')}}
					</td>
					<td>
						{{\Carbon\Carbon::parse($data['insert_date'])->format('d F Y H:I A')}}
					</td>



				</tr>
			</tbody>
		</table>
	</div>
</div>
<div class="box">
	<div class="box-body">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>INDIKATOR</th>
					<th>NILAI</th>
					<th>SATUAN</th>
					<th>TAHUN</th>
					<th>KETERANGAN</th>
					
				</tr>
			</thead>
			<tbody>
				<tr data-tt-id='_ketegori' class="bg-success">
					<td colspan="5"><b>ASPEK KINERJA</b></td>
				</tr>

				@foreach($data['_ketegori'] as $d)
				<tr data-tt-parent-id='_ketegori'>
					<td>{{$d['_question']['question']}}</td>
					<td>{{in_array($d['_question']['uom'], ['nilai','jiwa','sambungan','Rp','m3','Rp / Kwh','Liter','tahun','orang'])?number_format($d['data']):$d['data']}}</td>
					<td>{{strtoupper($d['_question']['uom'])}}</td>
					<td></td>
					<td>{{str_replace('Silahkan masukkan','',$d['_question']['remark'])}}</td>

				</tr>
				@endforeach

				<tr data-tt-id='_keuangan' class="bg-success">
					<td colspan="5"><b>ASPEK KEUANGAN</b></td>
				</tr>

				@foreach($data['_keuangan'] as $d)
				<tr data-tt-parent-id='_keuangan'>
					<td>{{$d['_question']['question']}}</td>
					<td>{{in_array($d['_question']['uom'], ['nilai','jiwa','sambungan','Rp','m3','Rp / Kwh','Liter','tahun','orang'])?number_format($d['data']):$d['data']}}</td>
					<td>{{strtoupper($d['_question']['uom'])}}</td>
					<td></td>
					<td>{{str_replace('Silahkan masukkan','',$d['_question']['remark'])}}</td>

				</tr>

				@endforeach

				<tr data-tt-id='_operasional' class="bg-success">
					<td colspan="5"><b>ASPEK OPRASIONAL</b></td>
				</tr>

				@foreach($data['_operasional'] as $d)
				<tr data-tt-parent-id='_operasional'>
					<td>{{$d['_question']['question']}}</td>
					<td>{{in_array($d['_question']['uom'], ['nilai','jiwa','sambungan','Rp','m3','Rp / Kwh','Liter','tahun','orang'])?number_format($d['data']):$d['data']}}</td>
					<td>{{strtoupper($d['_question']['uom'])}}</td>
					<td></td>
					<td>{{str_replace('Silahkan masukkan','',$d['_question']['remark'])}}</td>

				</tr>

				@endforeach
				<tr data-tt-id='_pelayanan' class="bg-success">
					<td colspan="5"><b>ASPEK PELAYANAN</b></td>
				</tr>

				@foreach($data['_pelayanan'] as $d)
				<tr data-tt-parent-id='_pelayanan'>
					<td>{{$d['_question']['question']}}</td>
					<td>{{in_array($d['_question']['uom'], ['nilai','jiwa','sambungan','Rp','m3','Rp / Kwh','liter','tahun','orang','l/d','jam/hari'])?number_format($d['data']):$d['data']}}</td>
					<td>{{strtoupper($d['_question']['uom'])}}</td>
					<td></td>
					<td>{{str_replace('Silahkan masukkan','',$d['_question']['remark'])}}</td>

				</tr>

				@endforeach
				<tr data-tt-id='_pemerintah_daerah' class="bg-success">
					<td colspan="5"><b>ASPEK PEMDA</b></td>
				</tr>

				@foreach($data['_pemerintah_daerah'] as $d)
				<tr data-tt-parent-id='_pemerintah_daerah'>
					<td>{{$d['_question']['question']}}</td>
					<td>{{in_array($d['_question']['uom'], ['nilai','jiwa','sambungan','Rp','m3','Rp / Kwh','Liter','tahun','orang'])?number_format($d['data']):$d['data']}}</td>
					<td>{{strtoupper($d['_question']['uom'])}}</td>
					<td></td>
					<td>{{str_replace('Silahkan masukkan','',$d['_question']['remark'])}}</td>

				</tr>

				@endforeach
			</tbody>
		</table>
	</div>
</div>



@stop

@section('right-sidebar')

<ul>
	<li><p><b>LAPORAN LAINYA</b></p></li>
@foreach($data_else as $l)
	<li>
		<a href="{{route('nuwsp.sat.detail',['tahun'=>$tahun,'kodelaporan'=>$l['id']])}}">	{{\Carbon\Carbon::parse($l['entry_period_year'].'-'.$l['entry_period_month'].'-01')->format('Y F')}}</a>
	</li>
</ul>
@endforeach
@stop