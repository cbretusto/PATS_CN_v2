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

  @section('title', 'Device')

  @section('content_page')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Device</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Device</li>
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
                <h3 class="card-title">Device</h3>
              </div>

              <!-- Start Page Content -->
              <div class="card-body">
                  <div style="float: right;">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modalAddDevice" id="btnShowAddDeviceModal"><i class="fa fa-user-plus"></i> Add Device</button>
                  </div> <br><br>
                  <div class="table responsive">
                    <table id="tblDevices" class="table table-bordered table-striped table-hover" style="width: 100%;">
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
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- MODALS -->
  <div class="modal fade" id="modalAddDevice">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-user-plus"></i> Add Device</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formAddDevice">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label>Device Name</label>
                    <input type="text" class="form-control" name="name" id="txtAddDeviceName">
                </div>

                <div class="form-group">
                  <label>Barcode</label>
                    <input type="text" class="form-control" name="barcode" id="txtAddDeviceBarcode">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" id="btnAddDevice" class="btn btn-primary"><i id="iBtnAddDeviceIcon" class="fa fa-check"></i> Save</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modalEditDevice">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-user"></i> Edit Device</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formEditDevice">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                <input type="hidden" class="form-control" name="device_id" id="txtEditDeviceId">
                <div class="form-group">
                  <label>Device Name</label>
                    <input type="text" class="form-control" name="name" id="txtEditDeviceName">
                </div>

                <div class="form-group">
                  <label>Barcode</label>
                    <input type="text" class="form-control" name="barcode" id="txtEditDeviceBarcode">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" id="btnEditDevice" class="btn btn-primary"><i id="iBtnEditDeviceIcon" class="fa fa-check"></i> Save</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modalChangeDeviceStat">
    <div class="modal-dialog">
      <div class="modal-content modal-sm">
        <div class="modal-header">
          <h4 class="modal-title" id="h4ChangeDeviceTitle"><i class="fa fa-user"></i> Change Status</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formChangeDeviceStat">
          @csrf
          <div class="modal-body">
            <label id="lblChangeDeviceStatLabel">Are you sure to ?</label>
            <input type="hidden" name="device_id" placeholder="Device Id" id="txtChangeDeviceStatDeviceId">
            <input type="hidden" name="status" placeholder="Status" id="txtChangeDeviceStatDeviceStat">
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            <button type="submit" id="btnChangeDeviceStat" class="btn btn-primary"><i id="iBtnChangeDeviceStatIcon" class="fa fa-check"></i> Yes</button>
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
    let dataTableDevices;
    $(document).ready(function () {
      //Initialize Select2 Elements
      $('.select2').select2();

      //Initialize Select2 Elements
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });

      dataTableDevices = $("#tblDevices").DataTable({
        "processing" : false,
          "serverSide" : true,
          "ajax" : {
            url: "view_devices",
            // data: function (param){
            //     param.status = $("#selEmpStat").val();
            // }
          },
          
          "columns":[
            { "data" : "name" },
            { "data" : "barcode" },
            { "data" : "label1" },
            { "data" : "action1", orderable:false, searchable:false }
          ],
        });//end of dataTableDevices

        // Add Device 
        $("#formAddDevice").submit(function(event){
          event.preventDefault();
          AddDevice();
        });

        $("#btnShowAddDeviceModal").click(function(){
          $("#txtAddDeviceName").removeClass('is-invalid');
          $("#txtAddDeviceName").attr('title', '');
          $("#txtAddDeviceBarcode").removeClass('is-invalid');
          $("#txtAddDeviceBarcode").attr('title', '');
        });

        // Edit Device
        $(document).on('click', '.aEditDevice', function(){
          let deviceId = $(this).attr('device-id');
          $("#txtEditDeviceId").val(deviceId);
          GetDeviceByIdToEdit(deviceId);
          $("#txtEditDeviceName").removeClass('is-invalid');
          $("#txtEditDeviceName").attr('title', '');
          $("#txtEditDeviceBarcode").removeClass('is-invalid');
          $("#txtEditDeviceBarcode").attr('title', '');
        });

        $("#chkEditUserWithEmail").click(function(){
          if($(this).prop('checked')) {
            $("#txtEditUserEmail").removeAttr('disabled');
            $("#txtEditUserEmail").val($("#txtEditUserCurrEmail").val());
          }
          else{
            $("#txtEditUserEmail").prop('disabled', 'disabled');
            $("#txtEditUserEmail").val('');
          }
        });

        $("#formEditDevice").submit(function(event){
          event.preventDefault();
          EditDevice();
        });

        // Change Device Status
        $(document).on('click', '.aChangeDeviceStat', function(){
          let deviceStat = $(this).attr('status');
          let deviceId = $(this).attr('device-id');

          $("#txtChangeDeviceStatDeviceId").val(deviceId);
          $("#txtChangeDeviceStatDeviceStat").val(deviceStat);

          if(deviceStat == 1){
            $("#lblChangeDeviceStatLabel").text('Are you sure to activate?'); 
            $("#h4ChangeDeviceTitle").html('<i class="fa fa-user"></i> Activate Device');
          }
          else{
            $("#lblChangeDeviceStatLabel").text('Are you sure to deactivate?');
            $("#h4ChangeDeviceTitle").html('<i class="fa fa-user"></i> Deactivate Device');
          }
        });

        $("#formChangeDeviceStat").submit(function(event){
          event.preventDefault();
          ChangeDeviceStatus();
        });
      });
  </script>
  @endsection
@endauth