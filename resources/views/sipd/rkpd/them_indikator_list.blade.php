<table class="table table-bordered">
  <thead>
    <tr class="bg-primary">
          <th colspan="{{count(Hp::tipe_indikator())}}">PENILAIAN</th>
          <th>JENIS</th>
          <th>INDIKATOR</th>
          <th>TARGET</th>
          <th>TARGET SATUAN</th>

          <th>PAGU</th>



    </tr>
  </thead>
  <tbody>
    @foreach($items as $item)
      @php
      $item=(array)$item;
      @endphp
         <tr >
            @foreach(Hp::tipe_indikator() as $ti)
              <td style="width:200px;" class="align-top" ><p><b>Indikator {{$ti}}</b></p>
              <select onchange="pemetaan_indikator_update({{$item['id']}},{{$context}},'{{$ti}}',this.value)" class="form-control {{str_replace(' ','_',$ti)}}" multiple=""  name="pemetaan_indikator[{{$item['id']}}][{{$ti}}]"></select>
              </td>
            @endforeach
           <td class="align-top">
             {{$jenis}}
           </td>
          <td class="align-top">
            <p>{{$item['tolokukur']}} </p>
          </td >
          <td class="align-top">
            {{$item['target']}} 
          </td>
          <td class="align-top">{{$item['satuan']??'-'}}</td>
          <td class="align-top">
            Rp. {{number_format($item['pagu'])}}
          </td>
        </tr>
    @endforeach
  </tbody>
</table>
<script type="text/javascript">
    function formatOption(repo){
      return  $.parseHTML(repo.text.trim());
    }

    setTimeout(function(){
         @foreach(Hp::tipe_indikator() as $ti)
            $('.{{str_replace(' ','_',$ti)}}').select2({
              "maximumSelectionLength":1,
             "ajax": {
              "delay": 250,
                "url": "{{route('api.sipd.rkpd.pemetaan.api.get.master.indikator',['tahun'=>$tahun,'kodepemda'=>$kodepemda,'context'=>$ti])}}",
                "data": function (params) {
                  var query = {
                    q: params.term,
                    id_urusan:{{$meta['id_urusan']}},
                    id_sub_urusan:{{$meta['id_sub_urusan']??'null'}},
                  };
                  return query;
                },
                "processResults": function (data) {
                  return {
                    results: data.results
                  };
                },

              },
              "templateResult":formatOption,
               "templateSelection":formatOption
            }) ;     
      @endforeach
    },300);
</script>