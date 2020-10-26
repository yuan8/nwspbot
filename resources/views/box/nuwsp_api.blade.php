
<div class="box box-widget widget-user">
    <!-- Add the bg color to the header using any of the bg-* classes -->
    <div class="widget-user-header bg-primary">
      <h3 class="widget-user-username">NUWSP</h3>
      <h5 class="widget-user-desc">SAT - {{$data['tahun']}} - VIA API</h5>
    </div>
    <div class="widget-user-image">
      <img class="img-circle elevation-2" src="{{asset('api.png')}}" alt="User Avatar">
    </div>
    <div class="box-footer">
      <div class="row">
        <div class="col-sm-4 border-right">
          <div class="description-block">
            <h5 class="description-header">{{\Carbon\Carbon::parse($data['last_date'])->format('d M Y')}}</h5>
            <span class="description-text">LAST UPDATED</span>
          </div>
          <!-- /.description-block -->
        </div>
        <!-- /.col -->
        <div class="col-sm-4 border-right">
          <div class="description-block">
            <h5 class="description-header">{{number_format($data['count'])}}</h5>
            <span class="description-text">DATA</span>
          </div>
          <!-- /.description-block -->
        </div>
        <!-- /.col -->
        <div class="col-sm-4">
          <div class="description-block">
            <h5 class="description-header"><a href="{{route('nuwsp.sat',['tahun'=>$data['tahun']])}}"  class="btn btn-default">DETAIL</a></h5>
          </div>
          <!-- /.description-block -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
  </div>
