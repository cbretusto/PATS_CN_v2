@extends('layouts.super_user_layout')

@section('title', 'Packing List')

@section('content_page')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Packing List</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Packing List</li>
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
              <h3 class="card-title">Select Series</h3>
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

                <div class="col-sm-2">
                  <label>&nbsp;</label>
                   <button type="button" class="form-control btn-info" id="btn_generate">Generate</button>
                </div>

              </div>

              <div class="row">
                <div class="col-sm-4">
                  <input type="hidden" class="form-control" id="id_series_name_selected" readonly>
                </div>
                 
              </div>

            </div>
            <!-- !-- End Page Content -->

          </div>
          <!-- /.card -->


          <!---TABLE FOR PACKING LIST--->

          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Packing List for Series Selected</h3>
            </div>

            <div class="card-body">
              <div class="row">
                <div class="col-sm-12">

                  <div class="table-responsive dt-responsive">

                     <table id="tbl_packinglist" class="table table-bordered table-hover" style="width: 100%;">
                        <thead>
                          <tr>
                            <th>Action</th>
                            <th>Shipment Date</th>
                            <th>Packing Codes</th>
                            <th>Shipment Quantity</th>
                            <th>Shipping Destination</th>
                          </tr>
                        </thead>

                        <tbody>
                        </tbody>
                      </table>

                  </div>
                </div>

                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<div class="modal fade" id="modalPackingList">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">

      <div class="modal-header">
        <h3 class="modal-title">Packing List Details</h3>
      </div>

      <div class="modal-body">

        <div class="row">
          <div class="col-sm-4">

            <div class="row">
              <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Shipment Date</span>
                  </div>
                  <input type="text" class="form-control form-control-sm" id="id_ship_date" name="name_ship_date" readonly>
                </div>
              </div>
            </div>

          </div>

          <div class="col-sm-4">

            <div class="row">
              <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Shipment Destination</span>
                  </div>
                  <input type="text" class="form-control form-control-sm" id="id_ship_dest" name="name_ship_dest" readonly>
                </div>
              </div>
            </div>

          </div>

          <div class="col-sm-4">

            <div class="row">
              <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Shipment Quantity</span>
                  </div>
                  <input type="text" class="form-control form-control-sm" id="id_currentPONo" name="name_po_no" readonly>
                </div>
              </div>
            </div>

          </div>
        </div>

          <!--TABLE-->
      <div class="row">
        <div class="col-sm-12">

          <div class='dt-responsive table-responsive'>

             <table id="tbl_packinglist_details" class="table table-bordered table-hover" style="width: 100%; font-size: 80%;">
                  <thead>
                     <tr>
                        <th>PO Number</th>
                        <th>Packing Code</th>
                        <th>Lot Number(s)</th>
                        <th>Total Lot Quantity</th>
                     </tr>
                 </thead>

                 <tbody>
                 </tbody>
            </table>
            
          </div>

        </div>
      </div>



      </div>

    
      <div class="modal-footer">
      </div>

    </div>
  </div>
</div>

@endsection

@section('js_content')
<script type="text/javascript">

  let dt_packinglist;
  let dt_packinglist_details;

  $(document).ready(function () {
    bsCustomFileInput.init();

    $('#id_series_name').select2({
      theme: "bootstrap4",
    });

    GetCboDevicesByStat($("#id_series_name"), 1);

    dt_packinglist = $('#tbl_packinglist').DataTable({
        "processing" : true,
        "serverSide" : true,
        
        "ajax" :
        {
          url: "view_packing_list",
          data: function(param)
          {
            param.device_name = $('#id_series_name_selected').val();
          }
        },

        "columns":
        [
          { "data" : "action" },
          { "data" : "ship_date" },
          { "data" : "pack_codes" },
          { "data" : "ship_qty"},
          { "data" : "ship_dest"}          
        ]
    });

    dt_packinglist_details = $('#tbl_packinglist_details').DataTable({
        "processing" : true,
        "serverSide" : true,
        
        "ajax" :
        {
          url: "view_packing_list_details",
          data: function(param)
          {
            param.device_name = $('#id_series_name_selected').val();
            param.ship_date = $('#id_ship_date').val();
          }
        },

        "columns":
        [
          { "data" : "po_num" },
          { "data" : "pack_code" },
          { "data" : "lot_no"},
          { "data" : "lot_qty"}          
        ]
    });
  });

  $('#btn_generate').on('click',function(e){

    $('#id_series_name_selected').val($('#id_series_name').val());

    dt_packinglist.draw();
  });

  $(document).on('click','.btn_view_packing_list', function(e){

    $device = $('#id_series_name_selected').val();
    $('#id_ship_date').val($(this).attr('ship-date'));

    dt_packinglist_details.draw();
  });

  
</script>
@endsection