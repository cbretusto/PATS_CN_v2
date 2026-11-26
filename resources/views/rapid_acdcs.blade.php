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

  @section('title', 'Rapid - ACDCS')

  @section('content_page')
  <!-- Content Wrapper. Contains page content -->
  <style>
    .hidden_scanner_input{
      position: absolute;
      opacity: 0;
    } 
  </style>

  <div class="content-wrapper">
    <?php 
      date_default_timezone_set('Asia/Manila');
    ?>

    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Rapid - ACDCS</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Rapid - ACDCS</li>
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
                <h3 class="card-title">Drawing No.</h3>
              </div>

              <!-- Start Page Content -->
              <div class="card-body">
                  <div class="row">
                    <div class="col-sm-2">
                      <label>Seach Drawing Number</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                            <button type="button" class="btn btn-primary btn_search_drawingNo" title="Click to Scan PO Code"><i class="fa fa-qrcode"></i></button>
                        </div>
                        <input type="text" class="form-control" id="txt_drawing_number" readonly="">
                      </div>
                    </div>
                      <div class="col-sm-2">
                        <label>Device Name</label>
                          <input type="text" class="form-control" id="id_device_name" readonly="">
                      </div>
                  </div>
                  <br>
              </div>
              </div>
              <!-- /.card -->

              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Rapid - ACDCS (Drawing Reference)</h3>
                </div>

                 <div class="card-body">
                  <div class="table responsive">
                    <table id="tbl_acdcs" class="table table-bordered table-striped table-hover" style="width: 100%;">
                      <thead>
                        <tr align="center">
                          <th>View</th>
                          <th>Model</th>
                          <th>Title</th>
                          <th>Document No.</th>
                          <th>Rev. No.</th>
                          <th>Document Type</th>
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

   <div class="modal fade" id="modalScan_drawingNo" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header border-bottom-0 pb-0">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-0">
          <div class="text-center text-secondary">
          Please scan drawing number.
          <br>
          <br>
          <h1><i class="fa fa-qrcode fa-lg"></i></h1>
          </div>
          <!--
            <input type="text" id="txt_search_drawing_number" class="hidden_scanner_input">
          -->
          <input type="hidden" id="txt_search_drawing_number">
        </div>
      </div>
    </div>
  </div>

  @endsection

  @section('js_content')
  <script type="text/javascript">
    let dataTableRapidACDCS;
    $(document).ready(function () {

        dataTableRapidACDCS = $("#tbl_acdcs").DataTable({
          "processing"    : false,
            "serverSide"  : true,
            "ajax"        : {
              url         : "get_acdcs_data",
                data: function (param){
                param.doc_no = $("#txt_search_drawing_number").val();
              }
            },
            
            "columns":[
              { "data" : "action", orderable:false, searchable:false },
              { "data" : "model" },
              { "data" : "doc_title" },
              { "data" : "doc_no" },
              { "data" : "rev_no" },
              { "data" : "doc_type" }
            ],
        });//end of dataTable 
    });

    //- search Drawing no
    $(document).on('keypress','#txt_search_drawing_number',function(e){

        $('#tbl_acdcs tbody tr').removeClass('table-active');

        var data = {
          'doc_no'      : $('#txt_search_drawing_number').val()
        }

        dataTableRapidACDCS.draw();  

        data = $.param(data);
        $.ajax({
          type      : "get",
          dataType  : "json",
          data      : data,
          url       : "get_docNo_details",
          success : function(data){
          
            if ( data['docNo_details'].length > 0 ){

              $('#txt_drawing_number').val( data['docNo_details'][0]['doc_no'] );
              $('#id_device_name').val( data['docNo_details'][0]['doc_title'] );
            }

          },error : function(data){

          }

        }); 
    });

    //- Drawing No
    $(document).on('click','.btn_search_drawingNo',function(e){
      $('#txt_search_drawing_number').val('');
      $('#modalScan_drawingNo').attr('data-formid', '').modal('show');
    });

    $(document).on('keypress',function(e){
      if( ($("#modalScan_drawingNo").data('bs.modal') || {})._isShown ){
        $('#txt_search_drawing_number').focus();

        if( e.keyCode == 13 && $('#txt_search_drawing_number').val() !='' && ($('#txt_search_drawing_number').val().length >= 4) ){
            $('#modalScan_drawingNo').modal('hide');
          }
        }
    }); 

    $(document).on('click','.btn_view_docs', function(){
        fkid_doc_no = $(this).val();
        
            // window.open("http://192.168.3.235/ACDCS/pdf_viewer_document.php?pkid_doc_stat="+fkid_doc_no+'_1', '_blank');
            window.open("http://192.168.3.235/ACDCS/pdf_viewer_dquick_document.php?pkid_doc_stat="+fkid_doc_no+'_1', '_blank');    
    });


  


  </script>
  @endsection
@endauth