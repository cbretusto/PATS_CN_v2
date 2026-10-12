@auth
  @php
    $layout = '';
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

@extends($layout)

@section('title', 'CN Line')

@section('content_page')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>CN Line</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">CN Line</li>
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
              <h3 class="card-title">CN Line</h3>
            </div>

            <!-- Start Page Content -->
            <div class="card-body">
                <div style="float: right;">
                  <button class="btn btn-primary" data-toggle="modal" data-target="#modalAddCNLine" id="btnShowAddCNLineModal"><i class="fa fa-user-plus"></i> Add CN Line</button>
                </div> <br><br>
                <div class="table responsive">
                  <table id="tblCNLines" class="table table-bordered table-striped table-hover" style="width: 100%;">
                    <thead>
                      <tr>
                        <th>Name</th>
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
<div class="modal fade" id="modalAddCNLine">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><i class="fa fa-user-plus"></i> Add CN Line</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" id="formAddCNLine">
        @csrf
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <label>CN Line Name</label>
                  <input type="text" class="form-control" name="name" id="txtAddCNLineName">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" id="btnAddCNLine" class="btn btn-primary"><i id="iBtnAddCNLineIcon" class="fa fa-check"></i> Save</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="modalEditCNLine">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><i class="fa fa-user"></i> Edit CN Line</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" id="formEditCNLine">
        @csrf
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12">
              <input type="hidden" class="form-control" name="cn_line_id" id="txtEditCNLineId">
              <div class="form-group">
                <label>CN Line Name</label>
                  <input type="text" class="form-control" name="name" id="txtEditCNLineName">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" id="btnEditCNLine" class="btn btn-primary"><i id="iBtnEditCNLineIcon" class="fa fa-check"></i> Save</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="modalChangeCNLineStat">
  <div class="modal-dialog">
    <div class="modal-content modal-sm">
      <div class="modal-header">
        <h4 class="modal-title" id="h4ChangeCNLineTitle"><i class="fa fa-user"></i> Change Status</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" id="formChangeCNLineStat">
        @csrf
        <div class="modal-body">
          <label id="lblChangeCNLineStatLabel">Are you sure to ?</label>
          <input type="hidden" name="cn_line_id" placeholder="CNLine Id" id="txtChangeCNLineStatCNLineId">
          <input type="hidden" name="status" placeholder="Status" id="txtChangeCNLineStatCNLineStat">
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
          <button type="submit" id="btnChangeCNLineStat" class="btn btn-primary"><i id="iBtnChangeCNLineStatIcon" class="fa fa-check"></i> Yes</button>
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
  let dataTableCNLines;
  $(document).ready(function () {
    //Initialize Select2 Elements
    $('.select2').select2();

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    });

    dataTableCNLines = $("#tblCNLines").DataTable({
      "processing" : false,
        "serverSide" : true,
        "ajax" : {
          url: "view_cn_lines",
          // data: function (param){
          //     param.status = $("#selEmpStat").val();
          // }
        },
        
        "columns":[
          { "data" : "name" },
          { "data" : "label1" },
          { "data" : "action1", orderable:false, searchable:false }
        ],
      });//end of dataTableCNLines

      // Add CN Line 
      $("#formAddCNLine").submit(function(event){
        event.preventDefault();
        AddCNLine();
      });

      $("#btnShowAddCNLineModal").click(function(){
        $("#txtAddCNLineName").removeClass('is-invalid');
        $("#txtAddCNLineName").attr('title', '');
      });

      // Edit CN Line
      $(document).on('click', '.aEditCNLine', function(){
        let cnLineId = $(this).attr('cn-line-id');
        $("#txtEditCNLineId").val(cnLineId);
        GetCNLineByIdToEdit(cnLineId);
        $("#txtEditCNLineName").removeClass('is-invalid');
        $("#txtEditCNLineName").attr('title', '');
      });

      $("#formEditCNLine").submit(function(event){
        event.preventDefault();
        EditCNLine();
      });

      // Change CNLine Status
      $(document).on('click', '.aChangeCNLineStat', function(){
        let cnLineStat = $(this).attr('status');
        let cnLineId = $(this).attr('cn-line-id');

        $("#txtChangeCNLineStatCNLineId").val(cnLineId);
        $("#txtChangeCNLineStatCNLineStat").val(cnLineStat);

        if(cnLineStat == 1){
          $("#lblChangeCNLineStatLabel").text('Are you sure to activate?'); 
          $("#h4ChangeCNLineTitle").html('<i class="fa fa-user"></i> Activate CN Line');
        }
        else{
          $("#lblChangeCNLineStatLabel").text('Are you sure to deactivate?');
          $("#h4ChangeCNLineTitle").html('<i class="fa fa-user"></i> Deactivate CN Line');
        }
      });

      $("#formChangeCNLineStat").submit(function(event){
        event.preventDefault();
        ChangeCNLineStatus();
      });
    });
</script>
@endsection