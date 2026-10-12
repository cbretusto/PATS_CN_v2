@php $layout = 'layouts.super_user_layout'; @endphp
@auth
  @php
    if(Auth::user()->user_level_id == 1){
      $layout = 'layouts.super_user_layout';
    }
    else if(Auth::user()->user_level_id == 2){
      $layout = 'layouts.admin_layout';
    }
    else if(Auth::user()->user_level_id == 3){
      $layout = 'layouts.user_layout';
    }
  @endphp
@endauth

@auth
  @extends($layout)

  @section('title', 'Production Runcard')

  @section('content_page')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Export Packing and Shipment Record</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Export Packing and Shipment Record</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="card card-primary">

            <div class="card-header">
              <h3 class="card-title">Export Packing and Shipment Record</h3>
            </div>

            <!-- Start Page Content -->
            <div class="card-body">

              <div class="row">
                
                <div class="col-sm-6">
                  <label>Series Name:</label>
                   <div class="input-group input-group-sm mb-3 series_select">
                    <select class="form-control form-control-sm" id="id_series_name" name="name_series_name">
                    </select>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-3">
                    <label>Print Lot:</label>
                    <div class="input-group input-group-sm mb-3">
                          <input type="text" class="form-control form-control-sm" id="id_print_lot" name="name_print_lot">
                    </div>
                </div>

                <div class="col-sm-3">
                  <label>Reel Lot:</label>
                  <div class="input-group input-group-sm mb-3">
                        <input type="text" class="form-control form-control-sm" id="id_reel_lot" name="name_reel_lot">
                    </div>
                </div>
              </div>


              <div class="row">
                <div class="col-sm-3">

                  <label>Packing Start Date:</label>
                  <div class="input-group input-group-sm mb-3">
                    <input type="date" class="form-control form-control-sm" id="id_shipping_from" name="name_shipping_from">
                  </div>

                </div>

                 <div class="col-sm-3">

                  <label>Packing End Date:</label>
                  <div class="input-group input-group-sm mb-3">
                    <input type="date" class="form-control form-control-sm" id="id_shipping_to" name="name_shipping_to">
                  </div>

                </div>
              </div>

              <div class="row">
                <div class="col-sm-6">
                    <button type="button" class="btn btn-primary" id="btn_export_report" title="Click to Export Report"><i class="fa fa-file"></i> Export Record</button>
                    @if (Auth::user()->employee_id == 'Q121')
                    <button type="button" class="btn btn-primary" id="btnExportTest" title="Click to Export Report"><i class="fa fa-file"></i> Export Record test</button>
                      
                    @endif
                </div>
              </div>            

            </div>
            <!-- !-- End Page Content -->

          </div>
          <!-- /.card -->
        </div>
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

@section('js_content')
<script type="text/javascript">
//================= FOR IFRAME OF PACKING AND SHIPPING MODULE
var packingandshipping = 0;
<?php
  if(isset($_GET['packingandshipping'])){
   echo "packingandshipping = 1;";
  }
?>
if(packingandshipping==1){
  $('.main-sidebar').hide();
  $('.main-header').hide();
  $('.content-header .breadcrumb').hide();
  $('.main-footer').hide();
  $('.content-wrapper').removeClass('content-wrapper');
}
//=================
  $(document).ready(function () {
    bsCustomFileInput.init();

    $('#id_series_name').select2({
      theme: "bootstrap4",
    });

    $('#btnExportTest').on('click', function(e){
      e.preventDefault();
      var data = {
        'start_date' : $('#id_shipping_from').val(),
        'end_date' : $('#id_shipping_to').val(),
        'series_name': $('#id_series_name').val(),
      }

      window.open('export_packing_and_shipment_record_test?series_name='+ $('#id_series_name').val() + '&start_date=' + $('#id_shipping_from').val() + '&end_date=' + $('#id_shipping_to').val() + '&print_lot=' + $('#id_print_lot').val() + '&reel_lot=' + $('#id_reel_lot').val()  , '_blank  ');

    })

  });

  GetCboDevicesByStat($("#id_series_name"), 1);

$(document).on('click','#btn_export_report',function(e){

  var data = {
    'start_date' : $('#id_shipping_from').val(),
    'end_date' : $('#id_shipping_to').val(),
    'series_name': $('#id_series_name').val(),
  }

  window.open('export_packing_and_shipment_record?series_name='+ $('#id_series_name').val() + '&start_date=' + $('#id_shipping_from').val() + '&end_date=' + $('#id_shipping_to').val() + '&print_lot=' + $('#id_print_lot').val() + '&reel_lot=' + $('#id_reel_lot').val()  , '_blank  ');

});

</script>
@endsection
@endauth