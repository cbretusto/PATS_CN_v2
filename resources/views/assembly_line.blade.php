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

  @section('title', 'Assembly Lines')

  @section('content_page')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Assembly Lines</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Assembly Lines</li>
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
                <h3 class="card-title">Assembly Lines</h3>
              </div>

              <!-- Start Page Content -->
              <div class="card-body">
                  <div style="float: right;">
                    @if(Auth::user()->user_level_id == 1)
                      <button class="btn btn-primary" data-toggle="modal" data-target="#modalImportAssemblyLine" id="btnShowImport" title="Import Assembly Lines"><i class="fa fa-file-excel"></i> Import</button>
                    @endif

                    <button class="btn btn-primary" data-toggle="modal" data-target="#modalAddAssemblyLine" id="btnShowAddAssemblyLineModal"><i class="fa fa-plus"></i> Add Assembly Line</button>
                  </div> <br><br>
                  <div class="table responsive">
                    <table id="tblAssemblyLines" class="table table-sm table-bordered table-striped table-hover" style="width: 100%;">
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
  <div class="modal fade" id="modalAddAssemblyLine">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-plus"></i> Add Assembly Line</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formAddAssemblyLine">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label>Assembly Line Name</label>
                    <input type="text" class="form-control" name="name" id="txtAddAssemblyLineName">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" id="btnAddAssemblyLine" class="btn btn-primary"><i id="iBtnAddAssemblyLineIcon" class="fa fa-check"></i> Save</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modalEditAssemblyLine">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-default"></i> Edit Assembly Line</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formEditAssemblyLine">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                <input type="hidden" class="form-control" name="assembly_line_id" id="txtEditAssemblyLineId">
                <div class="form-group">
                  <label>Assembly Line Name</label>
                    <input type="text" class="form-control" name="name" id="txtEditAssemblyLineName">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" id="btnEditAssemblyLine" class="btn btn-primary"><i id="iBtnEditAssemblyLineIcon" class="fa fa-check"></i> Save</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modalChangeAssemblyLineStat">
    <div class="modal-dialog">
      <div class="modal-content modal-sm">
        <div class="modal-header">
          <h4 class="modal-title" id="h4ChangeAssemblyLineTitle"><i class="fa fa-default"></i> Change Status</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formChangeAssemblyLineStat">
          @csrf
          <div class="modal-body">
            <label id="lblChangeAssemblyLineStatLabel">Are you sure to ?</label>
            <input type="hidden" name="assembly_line_id" placeholder="AssemblyLine Id" id="txtChangeAssemblyLineStatAssemblyLineId">
            <input type="hidden" name="status" placeholder="Status" id="txtChangeAssemblyLineStatAssemblyLineStat">
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            <button type="submit" id="btnChangeAssemblyLineStat" class="btn btn-primary"><i id="iBtnChangeAssemblyLineStatIcon" class="fa fa-check"></i> Yes</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modalImportAssemblyLine">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-file-excel"></i> Import Assembly Line</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formImportAssemblyLine" enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label>File</label>
                    <input type="file" class="form-control" name="import_file" id="fileImportAssemblyLine">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" id="btnImportAssemblyLine" class="btn btn-primary"><i id="iBtnImportAssemblyLineIcon" class="fa fa-check"></i> Import</button>
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
    let dataTableAssemblyLines;
    $(document).ready(function () {
      //Initialize Select2 Elements
      $('.select2').select2();

      //Initialize Select2 Elements
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });

      dataTableAssemblyLines = $("#tblAssemblyLines").DataTable({
        "processing" : false,
          "serverSide" : true,
          "ajax" : {
            url: "view_assembly_lines",
            // data: function (param){
            //     param.status = $("#selEmpStat").val();
            // }
          },
          
          "columns":[
            { "data" : "name" },
            { "data" : "label1" },
            { "data" : "action1", orderable:false, searchable:false }
          ],
        });//end of dataTableAssemblyLines

        $(document).on('click','#tblAssemblyLines tbody tr',function(e){
          $(this).closest('tbody').find('tr').removeClass('table-active');
          $(this).closest('tr').addClass('table-active');
        });

        // Add Assembly Line 
        $("#formAddAssemblyLine").submit(function(event){
          event.preventDefault();
          AddAssemblyLine();
        });

        $("#btnShowAddAssemblyLineModal").click(function(){
          $("#txtAddAssemblyLineName").removeClass('is-invalid');
          $("#txtAddAssemblyLineName").attr('title', '');
        });

        // Edit Assembly Line
        $(document).on('click', '.aEditAssemblyLine', function(){
          let assemblyLineId = $(this).attr('assembly-line-id');
          $("#txtEditAssemblyLineId").val(assemblyLineId);
          GetAssemblyLineByIdToEdit(assemblyLineId);
          $("#txtEditAssemblyLineName").removeClass('is-invalid');
          $("#txtEditAssemblyLineName").attr('title', '');
        });

        $("#formEditAssemblyLine").submit(function(event){
          event.preventDefault();
          EditAssemblyLine();
        });

        // Change AssemblyLine Status
        $(document).on('click', '.aChangeAssemblyLineStat', function(){
          let assemblyLineStat = $(this).attr('status');
          let assemblyLineId = $(this).attr('assembly-line-id');

          $("#txtChangeAssemblyLineStatAssemblyLineId").val(assemblyLineId);
          $("#txtChangeAssemblyLineStatAssemblyLineStat").val(assemblyLineStat);

          if(assemblyLineStat == 1){
            $("#lblChangeAssemblyLineStatLabel").text('Are you sure to activate?'); 
            $("#h4ChangeAssemblyLineTitle").html('<i class="fa fa-default"></i> Activate Assembly Line');
          }
          else{
            $("#lblChangeAssemblyLineStatLabel").text('Are you sure to deactivate?');
            $("#h4ChangeAssemblyLineTitle").html('<i class="fa fa-default"></i> Deactivate Assembly Line');
          }
        });

        $("#formChangeAssemblyLineStat").submit(function(event){
          event.preventDefault();
          ChangeAssemblyLineStatus();
        });

        $("#formImportAssemblyLine").submit(function(event){
            event.preventDefault();
            $.ajax({
                url: 'import_assembly_line',
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
                      dataTableAssemblyLines.draw();
                      $("#modalImportAssemblyLine").modal('hide');
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