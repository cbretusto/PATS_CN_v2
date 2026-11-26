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

  @section('title', 'Station')

  @section('content_page')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Station</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Station</li>
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
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header bg-info">
                <h3 class="card-title">Station</h3>
              </div>

              <!-- Start Page Content -->
              <div class="card-body">
                  <div style="float: right;">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modalAddStation" id="btnShowAddStationModal"><i class="fa fa-plus"></i> Add Station</button>
                  </div> <br><br>
                  <div class="table responsive">
                    <table id="tblStations" class="table table-sm table-bordered table-striped table-hover" style="width: 100%;">
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th>Barcode</th>
                          <th>Station Type</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
              </div>
              <!-- !-- End Page Content -->

            </div>
            <!-- /.card -->
          </div>


          <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Process</h3>
              </div>

              <!-- Start Page Content -->
              <div class="card-body">
                  <div style="float: right;">
                    <button class="btn btn-primary" id="btnShowAddSubStationModal" data-toggle="modal" data-target="#modalAddSubStation"><i class="fa fa-plus"></i> Add Process</button>
                  </div>
                  <!-- <div style="float: left;">
                    <label>Station: <u id="uSelectedStationName">No Selected Station</u></label>
                  </div> -->
                  <br><br>
                  <div class="table responsive">
                    <table id="tblSubStations" class="table table-sm table-bordered table-striped table-hover" style="width: 100%;">
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th>Barcode</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
              </div>
              <!-- !-- End Page Content -->

            </div>
            <!-- /.card -->
          </div>
          <!-- /.col-md-6 -->

          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Station with Processs</h3>
              </div>

              <!-- Start Page Content -->
              <div class="card-body">
                  <div style="float: right;">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modalAddStationSubStation" id="btnShowAddStationSubStationModal"><i class="fa fa-plus"></i> Add</button>
                  </div> <br><br>
                  <div class="table responsive">
                    <table id="tblStationSubStations" class="table table-sm table-bordered table-striped table-hover" style="width: 100%;">
                      <thead>
                        <tr>
                          <th>Station</th>
                          <th>Process</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
              </div>
              <!-- !-- End Page Content -->

            </div>
            <!-- /.card -->
          </div>
          <!-- /.col-md-6 -->

        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- STATION MODALS -->
  <div class="modal fade" id="modalAddStation">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-plus"></i> Add Station Name</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formAddStation">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label>Device Name</label>
                    <input type="text" class="form-control" name="name" id="txtAddStationName">
                </div>

                <div class="form-group">
                  <label>Product Code</label>
                    <input type="text" class="form-control" name="barcode" id="txtAddStationBarcode">
                </div>

                <div class="form-group">
                  <label>Station Type</label>
                  <select class="form-control select2bs4" name="station_type" id="selAddStationType" style="width: 100%;">
                    <option value="0" selected disabled>-- Select Station Type --</option>
                    <option value="1">Parts Preparatory</option>
                    <option value="2">Production Line</option>
                    <option value="3">OQC</option>
                    <option value="4">Packing</option>
                    <option value="5">Shipping</option>
                    <option value="6">Pre-Assembly</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" id="btnAddStation" class="btn btn-primary"><i id="iBtnAddStationIcon" class="fa fa-check"></i> Save</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modalEditStation">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-default"></i> Edit Station</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formEditStation">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                <input type="hidden" class="form-control" name="station_id" id="txtEditStationId">
                <div class="form-group">
                  <label>Station Name</label>
                    <input type="text" class="form-control" name="name" id="txtEditStationName">
                </div>

                <div class="form-group">
                  <label>Barcode</label>
                    <input type="text" class="form-control" name="barcode" id="txtEditStationBarcode">
                </div>

                <div class="form-group">
                  <label>Station Type</label>
                  <select class="form-control select2bs4" name="station_type" id="selEditStationType" style="width: 100%;">
                    <option value="0" selected disabled>-- Select Station Type --</option>
                    <option value="1">Parts Preparatory</option>
                    <option value="2">Production Line</option>
                    <option value="3">OQC</option>
                    <option value="4">Packing</option>
                    <option value="5">Shipping</option>
                    <option value="6">Pre-Assembly</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" id="btnEditStation" class="btn btn-primary"><i id="iBtnEditStationIcon" class="fa fa-check"></i> Save</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modalChangeStationStat">
    <div class="modal-dialog">
      <div class="modal-content modal-sm">
        <div class="modal-header">
          <h4 class="modal-title" id="h4ChangeStationTitle"><i class="fa fa-default"></i> Change Status</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formChangeStationStat">
          @csrf
          <div class="modal-body">
            <label id="lblChangeStationStatLabel">Are you sure to ?</label>
            <input type="hidden" name="station_id" placeholder="Station Id" id="txtChangeStationStatStationId">
            <input type="hidden" name="status" placeholder="Status" id="txtChangeStationStatStationStat">
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            <button type="submit" id="btnChangeStationStat" class="btn btn-primary"><i id="iBtnChangeStationStatIcon" class="fa fa-check"></i> Yes</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modalChangeStationSubStationStat">
    <div class="modal-dialog">
      <div class="modal-content modal-sm">
        <div class="modal-header">
          <h4 class="modal-title" id="h4ChangeStationSubStationTitle"><i class="fa fa-default"></i> Change Status</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formChangeStationSubStationStat">
          @csrf
          <div class="modal-body">
            <label id="lblChangeStationSubStationStatLabel">Are you sure to ?</label>
            <input type="hidden" name="station_sub_station_id" placeholder="Station Id" id="txtChangeStationSubStationStatStationId">
            <input type="hidden" name="status" placeholder="Status" id="txtChangeStationSubStationStatStationStat">
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            <button type="submit" id="btnChangeStationSubStationStat" class="btn btn-primary"><i id="iBtnChangeStationSubStationStatIcon" class="fa fa-check"></i> Yes</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <!-- PROCESS MODALS -->
  <div class="modal fade" id="modalAddSubStation">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-plus"></i> Add Process</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formAddSubStation">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                <!-- <input type="hidden" name="station_id" id="txtAddSubStationStationId"> -->
                
                <!-- <div class="form-group">
                  <label>Station Name</label>
                    <input type="text" class="form-control" name="station_name" id="txtAddSubStationStationName" readonly>
                </div> -->

                <div class="form-group">
                  <label>Process Name</label>
                    <input type="text" class="form-control" name="name" id="txtAddSubStationName">
                </div>

                <div class="form-group">
                  <label>QR Code</label>
                    <!-- <input type="text" class="form-control" name="barcode" id="txtAddSubStationBarcode"> -->

                    <div class="input-group">
                      <input type="text" class="form-control" name="barcode" id="txtAddSubStationBarcode">
                      <!-- <div class="input-group-append">
                        <button class="btn btn-primary" type="button" id="btnAddSubStationGenBarcode" title="Generate"><i class="fas fa-qrcode"></i></button>
                      </div> -->
                    </div>
                    <!-- <div>
                      <center>
                        <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                          ->size(150)->errorCorrection('H')
                          ->generate('0')) !!}" id="imgAddSubStationBarcode"> <br>
                          <label id="lblAddSubStationQRCodeVal">0</label>
                      </center>
                    </div> -->
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" id="btnAddSubStation" class="btn btn-primary"><i id="iBtnAddSubStationIcon" class="fa fa-check"></i> Save</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modalEditSubStation">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-default"></i> Edit Process</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formEditSubStation">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                <input type="hidden" class="form-control" name="sub_station_id" id="txtEditSubStationId">

                <!-- <div class="form-group">
                  <label>Station</label>
                    <select class="form-control select2bs4 selectStation" name="station_id" id="selEditSubStationStationId" style="width: 100%;">

                    </select>
                </div> -->

                <div class="form-group">
                  <label>Process Name</label>
                    <input type="text" class="form-control" name="name" id="txtEditSubStationName">
                </div>

                <div class="form-group">
                  <label>Barcode</label>
                    <input type="text" class="form-control" name="barcode" id="txtEditSubStationBarcode">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" id="btnEditSubStation" class="btn btn-primary"><i id="iBtnEditSubStationIcon" class="fa fa-check"></i> Save</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modalChangeSubStationStat">
    <div class="modal-dialog">
      <div class="modal-content modal-sm">
        <div class="modal-header">
          <h4 class="modal-title" id="h4ChangeSubStationTitle"><i class="fa fa-default"></i> Change Status</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formChangeSubStationStat">
          @csrf
          <div class="modal-body">
            <label id="lblChangeSubStationStatLabel">Are you sure to ?</label>
            <input type="hidden" name="sub_station_id" placeholder="SubStation Id" id="txtChangeSubStationStatSubStationId">
            <input type="hidden" name="status" placeholder="Status" id="txtChangeSubStationStatSubStationStat">
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            <button type="submit" id="btnChangeSubStationStat" class="btn btn-primary"><i id="iBtnChangeSubStationStatIcon" class="fa fa-check"></i> Yes</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <!-- STATION PROCESS MODALS -->
  <div class="modal fade" id="modalAddStationSubStation">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-plus"></i> Add</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formAddStationSubStation">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label>Station</label>
                  <select class="form-control select2bs4 selectStation" name="station_id" id="selAddStationSubStationStationId" style="width: 100%;">
                    <!-- Code generated -->
                  </select>
                </div>

                <div class="form-group">
                  <label>Process</label>
                  <select class="form-control select2bs4 selSubStation" name="sub_station_id" id="selAddStationSubStationId" style="width: 100%;">
                    <!-- Code generated -->
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" id="btnAddStationSubStation" class="btn btn-primary"><i id="iBtnAddStationSubStationIcon" class="fa fa-check"></i> Save</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
  @endsection

  @section('js_content')
  <script type="text/javascript">
    let dataTableStations;
    $(document).ready(function () {
      //Initialize Select2 Elements
      $('.select2').select2();

      //Initialize Select2 Elements
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });

      $(document).on('click','#tblStations tbody tr',function(e){
        $(this).closest('tbody').find('tr').removeClass('table-active');
        $(this).closest('tr').addClass('table-active');
      });

      $(document).on('click','#tblSubStations tbody tr',function(e){
        $(this).closest('tbody').find('tr').removeClass('table-active');
        $(this).closest('tr').addClass('table-active');
      });

      $(document).on('click','#tblStationSubStations tbody tr',function(e){
        $(this).closest('tbody').find('tr').removeClass('table-active');
        $(this).closest('tr').addClass('table-active');
      });

      dataTableStations = $("#tblStations").DataTable({
        "processing" : false,
          "serverSide" : true,
          "ajax" : {
            url: "view_stations",
            // data: function (param){
            //     param.status = $("#selEmpStat").val();
            // }
          },
          
          "columns":[
            { "data" : "name" },
            { "data" : "barcode" },
            { "data" : "station_type_label" },
            { "data" : "label1" },
            { "data" : "action1", orderable:false, searchable:false }
          ],
        });//end of dataTableStations

        // GetCboStationByStat($(".selectStation"), 1);

        GetCboSubStationByStat($(".selSubStation"), 1);
        GetCboStationByStat($(".selectStation"), 1);

        // Add Station 
        $("#formAddStation").submit(function(event){
          event.preventDefault();
          AddStation();
        });

        $("#btnShowAddStationModal").click(function(){
          $("#txtAddStationName").removeClass('is-invalid');
          $("#txtAddStationName").attr('title', '');
          $("#txtAddStationBarcode").removeClass('is-invalid');
          $("#txtAddStationBarcode").attr('title', '');
          $("#selAddStationType").removeClass('is-invalid');
          $("#selAddStationType").attr('title', '');
        });

        // Edit Station
        $(document).on('click', '.aEditStation', function(){
          let stationId = $(this).attr('station-id');
          $("#txtEditStationId").val(stationId);
          GetStationByIdToEdit(stationId);
          $("#txtEditStationName").removeClass('is-invalid');
          $("#txtEditStationName").attr('title', '');
          $("#txtEditStationBarcode").removeClass('is-invalid');
          $("#txtEditStationBarcode").attr('title', '');
        });

        $("#formEditStation").submit(function(event){
          event.preventDefault();
          EditStation();
        });

        // Change Station Status
        $(document).on('click', '.aChangeStationStat', function(){
          let stationStat = $(this).attr('status');
          let stationId = $(this).attr('station-id');

          $("#txtChangeStationStatStationId").val(stationId);
          $("#txtChangeStationStatStationStat").val(stationStat);

          if(stationStat == 1){
            $("#lblChangeStationStatLabel").text('Are you sure to activate?'); 
            $("#h4ChangeStationTitle").html('<i class="fa fa-default"></i> Activate Station');
          }
          else{
            $("#lblChangeStationStatLabel").text('Are you sure to deactivate?');
            $("#h4ChangeStationTitle").html('<i class="fa fa-default"></i> Deactivate Station');
          }
        });

        $("#formChangeStationStat").submit(function(event){
          event.preventDefault();
          ChangeStationStatus();
        });

        $(document).on('click', '.aChangeStationSubStationStat', function(){
          let stationSubStationStat = $(this).attr('status');
          let stationId = $(this).attr('station-sub-station-id');

          $("#txtChangeStationSubStationStatStationId").val(stationId);
          $("#txtChangeStationSubStationStatStationStat").val(stationSubStationStat);

          if(stationSubStationStat == 1){
            $("#lblChangeStationSubStationStatLabel").text('Are you sure to activate?'); 
            $("#h4ChangeStationSubStationTitle").html('<i class="fa fa-default"></i> Activate Station');
          }
          else{
            $("#lblChangeStationSubStationStatLabel").text('Are you sure to deactivate?');
            $("#h4ChangeStationSubStationTitle").html('<i class="fa fa-default"></i> Deactivate Station');
          }
        });

        $("#formChangeStationSubStationStat").submit(function(event){
          event.preventDefault();
          ChangeStationSubStationStatus();
        });

      });

      // PROCESSES
      let dataTableSubStations;
      // let selectedStationId = 0;
      // let selectedStationName = '';
      $(document).ready(function () {
        dataTableSubStations = $("#tblSubStations").DataTable({
          "processing" : false,
          "serverSide" : true,
          "ajax" : {
            // url: "view_sub_stations_by_station_id",
            url: "view_sub_stations",
            data: function (param){
                // param.station_id = selectedStationId;
            }
          },
          
          "columns":[
            { "data" : "name" },
            { "data" : "barcode" },
            { "data" : "label1" },
            { "data" : "action1", orderable:false, searchable:false }
          ],
        });//end of dataTableSubStations

        // $(document).on('click', '.aShowStationSubStations', function(){
        //   let stationId = $(this).attr('station-id');
        //   let stationName = $(this).attr('station-name');

        //   $("#uSelectedStationName").text(stationName);
        //   selectedStationId = stationId;
        //   $("#txtAddSubStationStationId").val(selectedStationId);
        //   selectedStationName = stationName;
        //   $("#txtAddSubStationStationName").val(selectedStationName);
        //   dataTableSubStations.draw();
        // });

        $("#btnShowAddSubStationModal").click(function(){

          $("#txtAddSubStationName").removeClass('is-invalid');
          $("#txtAddSubStationName").attr('title', '');
          $("#txtAddSubStationBarcode").removeClass('is-invalid');
          $("#txtAddSubStationBarcode").attr('title', '');
          
          // toastr.options = {
          //   "closeButton": false,
          //   "debug": false,
          //   "newestOnTop": true,
          //   "progressBar": true,
          //   "positionClass": "toast-top-right",
          //   "preventDuplicates": false,
          //   "onclick": null,
          //   "showDuration": "300",
          //   "hideDuration": "3000",
          //   "timeOut": "3000",
          //   "extendedTimeOut": "3000",
          //   "showEasing": "swing",
          //   "hideEasing": "linear",
          //   "showMethod": "fadeIn",
          //   "hideMethod": "fadeOut",
          // };

          // if(selectedStationId != 0){
          //   $("#modalAddSubStation").modal('show');
          //   $("#txtAddSubStationStationId").val(selectedStationId);
          //   $("#txtAddSubStationStationName").val(selectedStationName);

          //   $("#txtAddSubStationName").removeClass('is-invalid');
          //   $("#txtAddSubStationName").attr('title', '');
          //   $("#txtAddSubStationBarcode").removeClass('is-invalid');
          //   $("#txtAddSubStationBarcode").attr('title', '');
          // }
          // else{
          //   toastr.warning('No Selected Station!');
          // }
        });

        // Add Process 
        $("#btnAddSubStationGenBarcode").click(function(){
          let qrcode = $("#txtAddSubStationBarcode").val();
          GenerateSubStationQRCode(qrcode, 1, 0); // For Add
        });

        // Add Process 
        $("#formAddSubStation").submit(function(event){
          event.preventDefault();
          AddSubStation();
        });

        // Edit SubStation
        $(document).on('click', '.aEditSubStation', function(){
          let subStationId = $(this).attr('sub-station-id');
          $("#txtEditSubStationId").val(subStationId);
          // $("#selEditSubStationStationId").select2('val', selectedStationId);
          GetSubStationByIdToEdit(subStationId);
          $("#txtEditSubStationName").removeClass('is-invalid');
          $("#txtEditSubStationName").attr('title', '');
          $("#txtEditSubStationBarcode").removeClass('is-invalid');
          $("#txtEditSubStationBarcode").attr('title', '');
        });

        $("#formEditSubStation").submit(function(event){
          event.preventDefault();
          EditSubStation();
        });

        // Change SubStation Status
        $(document).on('click', '.aChangeSubStationStat', function(){
          let subStationStat = $(this).attr('status');
          let subStationId = $(this).attr('sub-station-id');

          $("#txtChangeSubStationStatSubStationId").val(subStationId);
          $("#txtChangeSubStationStatSubStationStat").val(subStationStat);

          if(subStationStat == 1){
            $("#lblChangeSubStationStatLabel").text('Are you sure to activate?'); 
            $("#h4ChangeSubStationTitle").html('<i class="fa fa-default"></i> Activate SubStation');
          }
          else{
            $("#lblChangeSubStationStatLabel").text('Are you sure to deactivate?');
            $("#h4ChangeSubStationTitle").html('<i class="fa fa-default"></i> Deactivate SubStation');
          }
        });

        $("#formChangeSubStationStat").submit(function(event){
          event.preventDefault();
          ChangeSubStationStatus();
        });
      });

      // STATION PROCESS
      let dataTableStationSubStations;

      $(document).ready(function(){
        let groupColumnStationSubStation = 0;
        dataTableStationSubStations = $("#tblStationSubStations").DataTable({
          "processing" : false,
          "serverSide" : true,
          "ajax" : {
            // url: "view_sub_stations_by_station_id",
            url: "view_station_sub_stations",
            data: function (param){
                // param.station_id = selectedStationId;
            }
          },
          
          "columns":[
            { "data" : "station.name" },
            { "data" : "sub_station.name" },
            { "data" : "label1" },
            { "data" : "action1", orderable:false, searchable:false }
          ],
          "columnDefs": [
              { "visible": false, "targets": groupColumnStationSubStation },
              { "visible": false, "targets": 0 },
          ],
          "order": [[ groupColumnStationSubStation, 'asc' ]],
          "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last = null;

            api.column(groupColumnStationSubStation, {page:'current'} ).data().each( function ( group, i ) {
              console.log('group ', group);
              console.log('i ', i);
                if ( last !== group ) {

                  let data = api.row(i).data();
                  let station_name = data.station.name;

                    $(rows).eq( i ).before(
                        '<tr class="group bg-info"><td colspan="5" style="text-align:center;"><b>' + station_name + '</b></td></tr>'
                    );

                    last = group;
                }
            });
          }
        });//end of dataTableStationSubStations

        $("#formAddStationSubStation").submit(function(event){
          event.preventDefault();
          AddStationSubStation();
        });
      });
  </script>
  @endsection
@endauth