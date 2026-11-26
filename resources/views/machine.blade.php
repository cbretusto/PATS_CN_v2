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

  @section('title', 'Machine')

  @section('content_page')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Machine</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Machine</li>
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
                <h3 class="card-title">Machine</h3>
              </div>

              <!-- Start Page Content -->
              <div class="card-body">
                  <div style="float: right;">
                    <button class="btn btn-primary" data-keyboard="false" id="btnShowModalPrintBatchMachine" disabled><i class="fa fa-print"></i> Print Batch QR Code (<span id="lblNoOfPrintBatchSelMachine">0</span>)</button>

                    @if(Auth::user()->user_level_id == 1)
                      <button class="btn btn-primary" data-toggle="modal" data-target="#modalImportMachine" id="btnShowImport" title="Import Machine"><i class="fa fa-file-excel"></i> Import</button>
                    @endif

                    <button class="btn btn-primary" data-toggle="modal" data-target="#modalAddMachine" id="btnShowAddMachineModal"><i class="fa fa-plus"></i> Add Machine</button>
                  </div> <br><br>
                  <div class="table responsive">
                    <table id="tblMachines" class="table table-sm table-bordered table-striped table-hover" style="width: 100%;">
                      <thead>
                        <tr>
                          <th colspan="4" style="text-align: center;" class="bg-info">PATS CN</th>
                          <th colspan="4" style="text-align: center;" class="bg-warning">EEDMS</th>
                          <th colspan="2"></th>
                        </tr>
                        <tr>
                          <th><center><input type="checkbox" name="check_all" id="chkSelAllMachines"></center></th>
                          <th>Name</th>
                          <th>Barcode</th>
                          <th>Status</th>
                          <th>Machine Code Number</th>
                          <th>General Logistics ID</th>
                          <th>Machine Name</th>
                          <th>Machine Status</th>
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
  <div class="modal fade" id="modalAddMachine">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-plus"></i> Add Machine</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formAddMachine">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label>Machine Name</label>
                    <input type="text" class="form-control" name="name" id="txtAddMachineName">
                </div>

                <div class="form-group">
                  <label>Barcode</label>
                    <input type="text" class="form-control" name="barcode" id="txtAddMachineBarcode">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" id="btnAddMachine" class="btn btn-primary"><i id="iBtnAddMachineIcon" class="fa fa-check"></i> Save</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modalEditMachine">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-default"></i> Edit Machine</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formEditMachine">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                <input type="hidden" class="form-control" name="machine_id" id="txtEditMachineId">
                <div class="form-group">
                  <label>Machine Name</label>
                    <input type="text" class="form-control" name="name" id="txtEditMachineName">
                </div>

                <div class="form-group">
                  <label>Barcode</label>
                    <input type="text" class="form-control" name="barcode" id="txtEditMachineBarcode">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" id="btnEditMachine" class="btn btn-primary"><i id="iBtnEditMachineIcon" class="fa fa-check"></i> Save</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modalChangeMachineStat">
    <div class="modal-dialog">
      <div class="modal-content modal-sm">
        <div class="modal-header">
          <h4 class="modal-title" id="h4ChangeMachineTitle"><i class="fa fa-default"></i> Change Status</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formChangeMachineStat">
          @csrf
          <div class="modal-body">
            <label id="lblChangeMachineStatLabel">Are you sure to ?</label>
            <input type="hidden" name="machine_id" placeholder="Machine Id" id="txtChangeMachineStatMachineId">
            <input type="hidden" name="status" placeholder="Status" id="txtChangeMachineStatMachineStat">
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            <button type="submit" id="btnChangeMachineStat" class="btn btn-primary"><i id="iBtnChangeMachineStatIcon" class="fa fa-check"></i> Yes</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modalImportMachine">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-file-excel"></i> Import Machine</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formImportMachine" enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label>File</label>
                    <input type="file" class="form-control" name="import_file" id="fileImportMachine">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" id="btnImportMachine" class="btn btn-primary"><i id="iBtnImportMachineIcon" class="fa fa-check"></i> Import</button>
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
    let dataTableMachines;
    let arrSelectedMachines = [];

    $(document).ready(function () {
      //Initialize Select2 Elements
      $('.select2').select2();

      //Initialize Select2 Elements
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });

      $(document).on('click','#tblMachines tbody tr',function(e){
        $(this).closest('tbody').find('tr').removeClass('table-active');
        $(this).closest('tr').addClass('table-active');
      });

      dataTableMachines = $("#tblMachines").DataTable({
        "processing" : false,
          "serverSide" : true,
          "ajax" : {
            url: "view_machines",
            // data: function (param){
            //     param.status = $("#selEmpStat").val();
            // }
          },
          
          "columns":[
            { "data" : "checkbox" },
            { "data" : "name" },
            { "data" : "barcode" },
            { "data" : "label1" },
            // { "data" : "eedms_machine_info.machine_code_number", orderable:false },
            { "data" : "raw_eedms_machine_code_number" },
            { "data" : "general_logistics_id", visible: false, },
            { "data" : "eedms_machine_info.machine_name", orderable:false },
            { "data" : "raw_eedms_status" },
            { "data" : "action1", orderable:false, searchable:false }
          ],
          "order": [[4, 'desc']],
          "columnDefs": [
            {
              "targets": [4, 5, 6],
              "data": null,
              "defaultContent": "--"
            },
          ],
          "initComplete": function(settings, json) {
                $(".chkMachine").each(function(index){
                    if(arrSelectedMachines.includes($(this).attr('machine-id'))){
                        $(this).attr('checked', 'checked');
                    }
                });
          },
          "drawCallback": function( settings ) {
                $(".chkMachine").each(function(index){
                    if(arrSelectedMachines.includes($(this).attr('machine-id'))){
                        $(this).attr('checked', 'checked');
                    }
                });
            }
        });//end of dataTableMachines

        // Add Machine 
        $("#formAddMachine").submit(function(event){
          event.preventDefault();
          AddMachine();
        });

        $("#btnShowAddMachineModal").click(function(){
          $("#txtAddMachineName").removeClass('is-invalid');
          $("#txtAddMachineName").attr('title', '');
          $("#txtAddMachineBarcode").removeClass('is-invalid');
          $("#txtAddMachineBarcode").attr('title', '');
        });

        // Edit Machine
        $(document).on('click', '.aEditMachine', function(){
          let machineId = $(this).attr('machine-id');
          $("#txtEditMachineId").val(machineId);
          GetMachineByIdToEdit(machineId);
          $("#txtEditMachineName").removeClass('is-invalid');
          $("#txtEditMachineName").attr('title', '');
          $("#txtEditMachineBarcode").removeClass('is-invalid');
          $("#txtEditMachineBarcode").attr('title', '');
        });

        $("#formEditMachine").submit(function(event){
          event.preventDefault();
          EditMachine();
        });

        $("#btnShowModalPrintBatchMachine").click(function(){
          PrintBatchMachine(arrSelectedMachines);
          // console.log(arrSelectedUsers);
        });

        $(document).on('click', '.chkMachine', function(){
            let machineId = $(this).attr('machine-id');

            if($(this).prop('checked')){
                // Checked
                if(!arrSelectedMachines.includes(machineId)){
                    arrSelectedMachines.push(machineId);
                }
            }
            else{  
                // Unchecked
                let index = arrSelectedMachines.indexOf(machineId);
                arrSelectedMachines.splice(index, 1);
            }
            $("#lblNoOfPrintBatchSelMachine").text(arrSelectedMachines.length);
            if(arrSelectedMachines.length <= 0){
                $("#btnShowModalPrintBatchMachine").prop('disabled', 'disabled');
                $("#btnSendTUVBatchEmail").prop('disabled', 'disabled');

            }
            else{
                $("#btnShowModalPrintBatchMachine").removeAttr('disabled');
                $("#btnSendTUVBatchEmail").removeAttr('disabled');

            }
        });

        $("#chkSelAllMachines").click(function(){
          if($(this).prop('checked')) {
              $(".chkMachine").prop('checked', 'checked');
              $("#btnShowModalPrintBatchMachine").removeAttr('disabled');
              $("#lblNoOfPrintBatchSelMachine").text('All');
              arrSelectedMachines = 0;
          }
          else{
              // $(".chkMachine").removeAttr('checked');
              dataTableMachines.draw();
              arrSelectedMachines = [];
              $("#btnShowModalPrintBatchMachine").prop('disabled', 'disabled');
              $("#lblNoOfPrintBatchSelMachine").text('0');
          }
        });

        // Change Machine Status
        $(document).on('click', '.aChangeMachineStat', function(){
          let machineStat = $(this).attr('status');
          let machineId = $(this).attr('machine-id');

          $("#txtChangeMachineStatMachineId").val(machineId);
          $("#txtChangeMachineStatMachineStat").val(machineStat);

          if(machineStat == 1){
            $("#lblChangeMachineStatLabel").text('Are you sure to activate?'); 
            $("#h4ChangeMachineTitle").html('<i class="fa fa-default"></i> Activate Machine');
          }
          else{
            $("#lblChangeMachineStatLabel").text('Are you sure to deactivate?');
            $("#h4ChangeMachineTitle").html('<i class="fa fa-default"></i> Deactivate Machine');
          }
        });

        $("#formChangeMachineStat").submit(function(event){
          event.preventDefault();
          ChangeMachineStatus();
        });

        $("#formImportMachine").submit(function(event){
            event.preventDefault();
            $.ajax({
                url: 'import_machine_updater',
                method: 'post',
                data: new FormData(this),
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function(){
                    // alert('Loading...');
                },
                success: function(JsonObject){
                    if(JsonObject['result'] == 1){
                      toastr.success('Importing Success!');
                      dataTableMachines.draw();
                      $("#modalImportMachine").modal('hide');
                    }
                    else{
                      toastr.error('Importing Failed!');
                    }
                },
                error: function(data, xhr, status){
                    console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
                }
            });
        });
      });
  </script>
  @endsection
@endauth