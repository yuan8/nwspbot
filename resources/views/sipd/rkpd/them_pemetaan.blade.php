 @foreach($data as $key=>$d)
	<tr>
	  <td><B>{{$key+1}}.</B></td>
	  <td>{{$d->p_kodebidang}}</td>
	  <td style="width: 250px;">{{$d->p_uraibidang}}</td>
	  <td>{{$d->p_kodeskpd}}</td>
	  <td style="width: 250px;">{{$d->p_uraiskpd}}</td>
	  <td>{{$d->p_kodeprogram}}</td>
	  <td>{{$d->p_uraiprogram}}
	    <br>
	    <br>

	    <button class="btn btn-xs btn-primary" onclick="pemetaan_indikator(1,'{{$d->p_ids}}','{{$d->ids}}',$('#{{'urusan'.$d->ids}}').val(),$('#{{'sub_urusan'.$d->ids}}').val())">Pemetaan Indikator</button>
	  </td>

	  <td>{{$d->kodekegiatan}}</td>
	  <td>{{$d->uraikegiatan}}
	  <br>
	  <br>

	    <button class="btn btn-xs btn-primary " onclick="pemetaan_indikator(2,'{{$d->ids}}','',$('#{{'urusan'.$d->ids}}').val(),$('#{{'sub_urusan'.$d->ids}}').val())">Pemetaan Indikator</button></td>
	  <td>Rp. {{number_format($d->pagu)}}</td>

	  <td style="width:250px;">
	    <select class="form-control select2-init-{{$page}}" id={{'urusan'.$d->ids}} name="kegiatan[{{$d->ids}}][id_urusan]" onchange="change_pemetaan('U','{{$d->ids}}',({id_urusan:this.value,id_sub_urusan:null}))">
	      <option value="">-</option>
	      @foreach($urusan as $u)
	        <option value="{{$u->id}}" {{$d->id_urusan==$u->id?'selected':''}}>{{$u->nama}}</option>
	      @endforeach
	    </select>
	  </td>
	  <td style="width: 250px;">
	    <select class="form-control select2-init-{{$page}}" id={{'sub_urusan'.$d->ids}} name="kegiatan[{{$d->ids}}][id_sub_urusan]" def-parent="{{$d->id_urusan}}"  onchange="change_pemetaan('S','{{$d->ids}}',({id_urusan:this.value.split('||')[0],id_sub_urusan:this.value.split('||')[1]}))">
	       <option value="||">-</option>
	        @foreach($sub_urusan as $u)
	        <option value="{{$u->id_urusan}}||{{$u->id}}" data-parent="{{$u->id_urusan}}" {{$d->id_urusan==$u->id_urusan?'':'disabled'}} {{$d->id_sub_urusan==$u->id?'selected':''}}>{{$u->nama}}</option>
	      @endforeach
	    </select>
	  </td>

	</tr>
@endforeach