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

  @section('title', 'Mode of Defect')

  @section('content_page')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Mode of Defect</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Mode of Defect</li>
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
                <h3 class="card-title">Mode of Defect</h3>
              </div>

              <!-- Start Page Content -->
              <div class="card-body">
                  <div style="float: right;">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modalAddMOD" id="btnShowAddModeOfDefectModal"><i class="fa fa-plus"></i> Add Mode of Defect</button>
                  </div> <br><br>
                  <div class="table responsive">
                    <table id="tblMODS" class="table table-sm table-bordered table-striped table-hover" style="width: 100%;">
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
  <div class="modal fade" id="modalAddMOD">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-plus"></i> Add Mode of Defect</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formAddMOD">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label>Mode of Defect</label>
                    <input type="text" class="form-control" name="name" id="txtAddMODName">
                </div>
              </div>

              <div class="col-sm-12">
                <div class="form-group">
                  <label>Barcode</label>
                    <input type="text" class="form-control" name="barcode" id="txtAddMODBarcode">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" id="btnAddMOD" class="btn btn-primary"><i id="iBtnAddMODIcon" class="fa fa-check"></i> Save</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modalEditMOD">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-default"></i> Edit Mode of Defect</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formEditMOD">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                <input type="hidden" class="form-control" name="mod_id" id="txtEditMODId">
                <div class="form-group">
                  <label>Mode of Defect</label>
                    <input type="text" class="form-control" name="name" id="txtEditMODName">
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group">
                  <label>Barcode</label>
                    <input type="text" class="form-control" name="barcode" id="txtEditMODBarcode">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" id="btnEditMOD" class="btn btn-primary"><i id="iBtnEditMODIcon" class="fa fa-check"></i> Save</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modalChangeMODStat">
    <div class="modal-dialog">
      <div class="modal-content modal-sm">
        <div class="modal-header">
          <h4 class="modal-title" id="h4ChangeMODTitle"><i class="fa fa-default"></i> Change Status</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formChangeMODStat">
          @csrf
          <div class="modal-body">
            <label id="lblChangeMODStatLabel">Are you sure to ?</label>
            <input type="hidden" name="mod_id" placeholder="MOD Id" id="txtChangeMODStatMODId">
            <input type="hidden" name="status" placeholder="Status" id="txtChangeMODStatMODStat">
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            <button type="submit" id="btnChangeMODStat" class="btn btn-primary"><i id="iBtnChangeMODStatIcon" class="fa fa-check"></i> Yes</button>
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
    let dataTableMODS;
    let arrSelectedMODS = [];

    $(document).ready(function () {
      //Initialize Select2 Elements
      $('.select2').select2();

      //Initialize Select2 Elements
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });

      $(document).on('click','#tblMODS tbody tr',function(e){
        $(this).closest('tbody').find('tr').removeClass('table-active');
        $(this).closest('tr').addClass('table-active');
      });

      dataTableMODS = $("#tblMODS").DataTable({
        "processing" : false,
          "serverSide" : true,
          "ajax" : {
            url: "view_mods",
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
          "initComplete": function(settings, json) {
                $(".chkMOD").each(function(index){
                    if(arrSelectedMOD.includes($(this).attr('mod-id'))){
                        $(this).attr('checked', 'checked');
                    }
                });
          },
          "drawCallback": function( settings ) {
                $(".chkMOD").each(function(index){
                    if(arrSelectedMOD.includes($(this).attr('mod-id'))){
                        $(this).attr('checked', 'checked');
                    }
                });
            }
        });//end of dataTableMODS

        // Add MOD 
        $("#formAddMOD").submit(function(event){
          event.preventDefault();
          AddMOD();
        });

        $("#btnShowAddMODModal").click(function(){
          $("#txtAddMODName").removeClass('is-invalid');
          $("#txtAddMODName").attr('title', '');
        });

        // Edit MOD
        $(document).on('click', '.aEditMOD', function(){
          let modId = $(this).attr('mod-id');
          $("#txtEditMODId").val(modId);
          GetMODByIdToEdit(modId);
          $("#txtEditMODName").removeClass('is-invalid');
          $("#txtEditMODName").attr('title', '');
        });

        $("#formEditMOD").submit(function(event){
          event.preventDefault();
          EditMOD();
        });

        $(document).on('click', '.chkMOD', function(){
            let modId = $(this).attr('mod-id');

            if($(this).prop('checked')){
                // Checked
                if(!arrSelectedMOD.includes(modId)){
                    arrSelectedMOD.push(modId);
                }
            }
            else{  
                // Unchecked
                let index = arrSelectedMOD.indexOf(modId);
                arrSelectedMOD.splice(index, 1);
            }
            $("#lblNoOfPrintBatchSelMOD").text(arrSelectedMOD.length);
            if(arrSelectedMOD.length <= 0){
                $("#btnShowModalPrintBatchMOD").prop('disabled', 'disabled');
                $("#btnSendTUVBatchEmail").prop('disabled', 'disabled');

            }
            else{
                $("#btnShowModalPrintBatchMOD").removeAttr('disabled');
                $("#btnSendTUVBatchEmail").removeAttr('disabled');

            }
        });

        $("#chkSelAllMODs").click(function(){
          if($(this).prop('checked')) {
              $(".chkMOD").prop('checked', 'checked');
              $("#btnShowModalPrintBatchMOD").removeAttr('disabled');
              $("#lblNoOfPrintBatchSelMOD").text('All');
              arrSelectedMOD = 0;
          }
          else{
              // $(".chkMOD").removeAttr('checked');
              dataTableMODS.draw();
              arrSelectedMOD = [];
              $("#btnShowModalPrintBatchMOD").prop('disabled', 'disabled');
              $("#lblNoOfPrintBatchSelMOD").text('0');
          }
        });

        // Change MOD Status
        $(document).on('click', '.aChangeMODStat', function(){
          let modStat = $(this).attr('status');
          let modId = $(this).attr('mod-id');

          $("#txtChangeMODStatMODId").val(modId);
          $("#txtChangeMODStatMODStat").val(modStat);

          if(modStat == 1){
            $("#lblChangeMODStatLabel").text('Are you sure to activate?'); 
            $("#h4ChangeMODTitle").html('<i class="fa fa-default"></i> Activate Mode of Defect');
          }
          else{
            $("#lblChangeMODStatLabel").text('Are you sure to deactivate?');
            $("#h4ChangeMODTitle").html('<i class="fa fa-default"></i> Deactivate Mode of Defect');
          }
        });

        $("#formChangeMODStat").submit(function(event){
          event.preventDefault();
          ChangeMODStatus();
        });
      });
  </script>
  @endsection
@endauth