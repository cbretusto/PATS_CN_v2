{{-- @php $layout = 'layouts.super_user_layout'; @endphp --}}
@auth
@php
if(isset($iframe)){ // FOR IFRAME
  $layout = 'layouts.iframe_layout';
}
else if(Auth::user()->user_level_id == 1){
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

@section('title', 'Shipment Confirmation')

@section('content_page')
<link href="{{ URL::asset('public/template/plugins/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet" media="all">
<style type="text/css">
  .hidden_scanner_input{
    position: absolute;
    opacity: 0;
  }
  textarea{
    resize: none;
  }
  #mdl_edit_material_details>div{
    /*width: 2000px!important;*/
    /*min-width: 1400px!important;*/
  }
  .hidden_scanner_input{
    position: absolute;
    opacity: 0;
  }

  #div_layout_1, #div_layout_2{
    transition: .5s;
  }
/*    .table th:first-child{
      width: 900px!important;
    }

    // comment ka muna
    .table{
      min-width: 600px;
    }
    */

    .datepicker {
      z-index: 1600 !important; /* has to be larger than 1050 */
    }
  </style>
  @if (isset($iframe)){{-- FOR IFRAME --}}
    <style>
          /* * FOR IFRAME */
        @media (min-width: 768px){
            .content-wrapper{
                margin-left: 0 !important;
            }
        }
    </style>
  @endif
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    {{-- FOR IFRAME --}}

    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Shipment Confirmation </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Shipment Confirmation</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Upload Excel</h3>
                <a class="btn btn-info btn-sm float-right" href="{{ asset('public/storage/file_templates/shipment_confirmation_template.xlsx') }}" download> <i class="fa fa-file-excel"></i> Download Template </a>
              </div>

              <!-- Start Page Content -->
              <div class="card-body">
                <div class="row mb-2">
                  <div class="col-4">
                    <form id="frm_upload_file">
                      @csrf
                      <div class="input-group input-group-sm">
                        <div class="custom-file">
                          <input type="file" class="custom-file-input" id="txt_upload_file" name="txt_upload_file" accept=".xlsx,.xls">
                          <label class="custom-file-label overflow-hidden" for="txt_upload_file"></label>
                        </div>
                        <div class="input-group-append">
                          <button class="btn btn-info" type="submit">Upload</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <div class="table-responsive">
                      <table class="table table-sm table-bordered table-hover" id="tbl_uploaded_temp">
                        <thead>
                          <tr class="bg-light">
                            <th><input type="checkbox" id="txt_uploaded_temp_checkall"></th>
                            <th>PO Number</th>
                            <th>Shipment Date</th>
                            <th>Arrival Date</th>
                            <th>Delivery Place Name</th>
                            <th>Shipment Quantity</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col text-right">
                    <button class="btn btn-secondary btn-sm" id="btn_upload_file_clear" type="button">Clear</button>
                    <button class="btn btn-success btn-sm" type="button" id="btn_save">Save</button>
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


    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">

          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Uploaded</h3>
              </div>
              <!-- Start Page Content -->
              <div class="card-body">
                <div class="row">
                  <div class="col-12">
                    <div class="table-responsive">
                      <table class="table table-sm table-bordered table-hover" id="tbl_uploaded">
                        <thead>
                          <tr class="bg-light">
                            <th></th>
                            <th>PO Number</th>
                            <th>Shipment Date</th>
                            <th>Arrival Date</th>
                            <th>Delivery<br>Place<br>Name</th>
                            <th>Shipment<br>Quantity</th>
                            <th>Remarks</th>
                            <th>Uploaded by</th>
                            <th>Uploaded at</th>
                            <th>Updated by</th>
                            <th>Updated at</th>
                            <th>Delete<br>Remarks</th>
                            <!-- <th>Uploaded by</th> -->
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                    </div>
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


    <!-- Modal -->
    <div class="modal fade" id="mdl_delete" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Confirm Delete</h5>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1">Remarks</span>
                  </div>
                  <textarea type="text" class="form-control form-control-sm" id="txt_delete_remarks"></textarea>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-sm btn-danger" id="btn_delete_authorized">Scan ID to Delete</button>
            <button type="button" class="btn btn-sm btn-danger" id="btn_delete">Delete</button>
          </div>
        </div>
      </div>
    </div>
    <!-- /.Modal -->

    <!-- Modal -->
    <div class="modal fade" id="mdl_update" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Update Details</h5>
          </div>
          <div class="modal-body">
            <form id="frm_update">

              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">PO Number</span>
                    </div>
                    <input type="text" class="form-control form-control-sm" id="txt_update_po_no" name="txt_update_po_no" readonly>
                  </div>
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Shipment date (yyyy-mm-dd)</span>
                    </div>
                    <input type="text" class="form-control form-control-sm bg-light datepicker" id="txt_update_shipment_date" name="txt_update_shipment_date" readonly required>
                  </div>
                   <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Arrival date (yyyy-mm-dd)</span>
                    </div>
                    <input type="text" class="form-control form-control-sm bg-light datepicker" id="txt_update_arrival_date" name="txt_update_arrival_date" readonly required>
                  </div>
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Place</span>
                    </div>
                    <select class="form-control form-control-sm" id="txt_update_delivery_place_name" name="txt_update_delivery_place_name" required>
                      <option value="">--</option>
                      <option value="W021">W021</option>
                      <option value="W001">W001</option>
                      <option value="W004">W004</option>
                    </select>
                  </div>
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Shipment qty</span>
                    </div>
                    <input type="number" class="form-control form-control-sm" id="txt_update_shipment_qty" name="txt_update_shipment_qty" required>
                  </div>
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Remarks</span>
                    </div>
                    <textarea type="text" class="form-control form-control-sm" id="txt_update_remarks" name="txt_update_remarks" required></textarea>
                  </div>
                </div>
              </div>

            </form>
          </div>
          <div class="modal-footer">
            <input type="hidden" form="frm_update" id="txt_update_old_id" name="txt_update_old_id">
            <input type="hidden" form="frm_update" id="txt_update_old_po_no" name="txt_update_old_po_no">
            <input type="hidden" form="frm_update" id="txt_update_old_shipment_date" name="txt_update_old_shipment_date">
            <input type="hidden" form="frm_update" id="txt_update_old_arrival_date" name="txt_update_old_arrival_date">
            <input type="hidden" form="frm_update" id="txt_update_old_delivery_place_name" name="txt_update_old_delivery_place_name">
            <input type="hidden" form="frm_update" id="txt_update_old_shipment_qty" name="txt_update_old_shipment_qty">
            <input type="hidden" form="frm_update" id="txt_update_old_remarks" name="txt_update_old_remarks">
            <input type="hidden" form="frm_update" id="txt_update_old_created_by" name="txt_update_old_created_by">
            <input type="hidden" form="frm_update" id="txt_update_old_updated_by" name="txt_update_old_updated_by">
            <input type="hidden" form="frm_update" id="txt_update_old_created_at" name="txt_update_old_created_at">
            <input type="hidden" form="frm_update" id="txt_update_old_updated_at" name="txt_update_old_updated_at">

            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-sm btn-success" id="btn_update_authorized">Scan ID to Update</button>
            <button type="submit" form="frm_update" class="btn btn-sm btn-success" id="btn_update">Update</button>
          </div>
        </div>
      </div>
    </div>
    <!-- /.Modal -->

    <!-- Modal -->
    <div class="modal fade" id="mdl_history" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">CN PPTS v2 DLabel History</h5>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12">
                <div class="table-responsive">
                  <table class="table table table-bordered table-hover w-100" id="tbl_uploaded_history">
                    <thead>
                      <tr class="bg-light">
                        <th>PO Number</th>
                        <th>Shipment Date</th>
                        <th>Arrival Date</th>
                        <th>Delivery Place Name</th>
                        <th>Shipment Quantity</th>
                        <th>Remarks</th>
                        <th>Created by</th>
                        <th>Created at</th>
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
            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!-- /.Modal -->

    <!-- Modal -->
    <div class="modal fade" id="mdl_employee_number_scanner" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header border-bottom-0 pb-0">
            <!-- <h5 class="modal-title" id="exampleModalLongTitle"></h5> -->
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body pt-0">
            <div class="text-center text-secondary">
              Please scan your ID.
              <h1><i class="fa fa-barcode fa-lg"></i></h1>
            </div>
            <input type="text" id="txt_employee_number_scanner" class="hidden_scanner_input">
          </div>
        </div>
      </div>
    </div>
    <!-- /.Modal -->

    <!-- Modal -->
    <div class="modal fade" id="mdl_alert" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header border-bottom-0 pb-1">
            <h5 class="modal-title" id="mdl_alert_title"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" id="mdl_alert_body">
          </div>
        </div>
      </div>
    </div>
    <!-- /.Modal -->

  </div>
  <!-- /.content-wrapper -->
  @endsection

  @section('js_content')
  <script src="{{ URL::asset('public/template/plugins/jquery-ui/jquery-ui.min.js') }}"></script>

  <script type="text/javascript">
    var dt_uploaded = '';
    $(document).ready(function(){
      //-----
      //-----
      //-----
      $('input').each(function(i, obj) {
        if (!this.hasAttribute("placeholder")) {
          if( $(this).prop('type') == 'number' ){
            $(this).prop('placeholder','0');
            $(this).prop('min','1');
          }
          if( $(this).prop('type') == 'text' ){
            $(this).prop('placeholder','---');
          }
          if( $(this).prop('type') == 'search' ){
            $(this).prop('placeholder','---');
          }
        }
      });
      $('textarea').each(function(i, obj) {
        if (!this.hasAttribute("placeholder")) {
          $(this).prop('placeholder','Type here...');
        }
      });
      $(document).on('show.bs.modal', '.modal', function () {
        var zIndex = 1040 + (10 * $('.modal:visible').length);
        $(this).css('z-index', zIndex);
        setTimeout(function() {
          $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
        }, 0);
      });
      //-----
      //-----
      //-----
      $('.datepicker').datepicker({
        dateFormat: 'yy-mm-dd'
      });

      setTimeout(function() {
        $('.nav-link').find('.fa-bars').closest('a').click();
      }, 100);

       dt_uploaded      = $('#tbl_uploaded').DataTable({
         "processing" : true,
           "serverSide" : true,
           "ajax" : {
             url: "select_shipment_confirmation",
             data: function (param){
                 // param.issue_no          = $("#tbl_batches tbody tr.table-active").find('.tbl_wbs_material_kitting_issuance_no').val();
             }
           },
           bAutoWidth: false,
           "columns":[
             { "data" : "raw_action",searchable:false, "width":"100px" },
             { "data" : "po_no" },
             { "data" : "shipment_date" },
             { "data" : "arrival_date" },
             { "data" : "delivery_place_name" },
             { "data" : "shipment_qty" },
             { "data" : "remarks" },
             { "data" : "user_created_by.name" },
             { "data" : "created_at" },
             { "data" : "user_updated_by.name" },
             { "data" : "updated_at" },
             { "data" : "delete_remarks" },
           ],
          //  order: [[0, "asc"],[2, "desc"],[1, "desc"],[7, "desc"]],
           order: [[2, "desc"]],
           // paging: false,
           "rowCallback": function(row,data,index ){
            console.log('DATA: ', data.status);
             if( $(row).find('.badge-danger').length>0 ){
               $(row).addClass('table-danger');
             }
             else{
               if( $(row).find('.badge-success').length>0 ){
                 $(row).addClass('table-success');
               }
               if( $(row).find('.badge-warning').length>0 ){
                 $(row).addClass('table-warning');
               }
             }
           },
       });//end of DataTable
      //-----------------------

      $('#txt_upload_file').on('change',function(){
          //get the file name
          var fileName = $(this).val();
          //replace the "Choose a file" label
          $(this).next('.custom-file-label').html(fileName);
      })

      //-----------------------
      $(document).on('click','#tbl_uploaded tbody tr',function(e){
        $(this).closest('tbody').find('tr').removeClass('table-active');
        $(this).closest('tr').addClass('table-active');
      });

      $(document).on('click','#btn_save',function(e){
        var alert_body = '';
        var arr_rows = [];
        var ctr = 0;
        $('#tbl_uploaded_temp tbody .ckb_upload:checked').each(function(){
          arr_rows[ctr] = {
              'po_no' : $(this).closest('td').find('.hidden_po_no').val(),
              'shipment_date' : $(this).closest('td').find('.hidden_shipment_date').val(),
              'delivery_place_name' : $(this).closest('td').find('.hidden_delivery_place_name').val(),
              'shipment_qty' : $(this).closest('td').find('.hidden_shipment_qty').val(),
              'arrival_date' : $(this).closest('td').find('.hidden_arrival_date').val(),
            };

            if( $(this).closest('td').find('.hidden_shipment_date').val() == '1970-01-01' ){
              alert_body = 'Incorrect date format.';
            }
            if( $(this).closest('td').find('.hidden_po_no').val() == '' ){
              alert_body = 'Some cell is empty.';
            }
            if( $(this).closest('td').find('.hidden_shipment_date').val() == '' ){
              alert_body = 'Some cell is empty.';
            }
            if( $(this).closest('td').find('.hidden_delivery_place_name').val() == '' ){
              alert_body = 'Some cell is empty.';
            }
            if( $(this).closest('td').find('.hidden_shipment_qty').val() == '' ){
              alert_body = 'Some cell is empty.';
            }
            if( $(this).closest('td').find('.hidden_arrival_date').val() == '' ){
              alert_body = 'Some cell is empty.';
            }

            ctr++;
        });

        if( !jQuery.isEmptyObject(arr_rows) ){
        }else{
          alert_body = 'Nothing to save.';
        }
        if(alert_body){
          show_alert('<i class="fa fa-exclamation-triangle text-warning"></i>','Message',alert_body,0);
          return;
        }
        //-----
        // $('#txt_employee_number_scanner').val('');
        // $('#mdl_employee_number_scanner').attr('data-formid','insert_shipment_confirmation').modal('show');
        insert_shipment_confirmation();
      });
      $(document).on('click','#tbl_uploaded .td_btn_delete',function(e){
        // $('#txt_employee_number_scanner').val('');
        // $('#mdl_employee_number_scanner').attr('data-formid','delete_shipment_confirmation').modal('show');

        $('#txt_delete_remarks').val('');
        $('#mdl_delete').attr('data-id', $(this).closest('tr').find('.td_id').val() ).modal('show');
      });
       //----------
       //----------
       //----------
      $( ".modal" ).on('shown.bs.modal', function(){
        $(this).find('.hidden_scanner_input').focus();
      });
      $(document).on('keypress',function(e){
        if( ($("#mdl_employee_number_scanner").data('bs.modal') || {})._isShown ){
          $('#txt_employee_number_scanner').focus();
          if( e.keyCode == 13 ){
            $('#mdl_employee_number_scanner').modal('hide');
            var formid = $("#mdl_employee_number_scanner").attr('data-formid');

            if ( ( formid ).indexOf('#') > -1){
              $( formid ).submit();
            }
            else{
              switch( formid ){
                case 'insert_shipment_confirmation':
                  insert_shipment_confirmation();
                break;
                case 'delete_shipment_confirmation':
                  $('#btn_delete').trigger('click');
                break;
                case 'update_shipment_confirmation':
                  $('#btn_update').trigger('click');
                break;

                default:
                break;
              }
            }
          }
        }
      });
       //----------
       //----------
       //----------
      $(document).on('change','#txt_uploaded_temp_checkall',function(){
        var cval = $(this).prop('checked');
        $('#tbl_uploaded_temp tbody input[type="checkbox"]').not('[disabled]').prop('checked',cval);
      });

      $('#frm_upload_file').submit(function(e){
        e.preventDefault();

        $('#btn_upload_file_clear').click();
        var alert_body = '';
        if( !$('#txt_upload_file').val() ){
          alert_body = 'Please select a file';
        }
        if(alert_body){
          show_alert('<i class="fa fa-exclamation-triangle text-warning"></i>','Message',alert_body,0);
          return;
        }

        $.ajax({
          url       : 'upload_file_shipment_confirmation',
          method: 'post',
          data: new FormData(this),
          dataType: 'json',
          cache: false,
          contentType: false,
          processData: false,
          success : function(data){
            if($.trim(data)){

              var alert_body = '';
              if( !data['body'] ){
                alert_body = 'File is empty.';
              }
              if(alert_body){
                show_alert('<i class="fa fa-exclamation-triangle text-warning"></i>','Message',alert_body,0);
                return;
              }
              $('#tbl_uploaded_temp tbody').html( data['body'] );
            }
          }
        });
      });

      $('#btn_upload_file_clear').click(function(){
        $('#tbl_uploaded_temp tbody').html('');
      });

      $('#btn_delete').click(function(){
        var alert_body = '';
        if( $('#txt_delete_remarks').val() == '' ){
          alert_body = 'Remarks is required.';
        }
        if(alert_body){
          show_alert('<i class="fa fa-exclamation-triangle text-warning"></i>','Message',alert_body,0);
          return;
        }
        delete_shipment_confirmation();
      });

      $('#btn_delete_authorized').click(function(){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','delete_shipment_confirmation').modal('show');
      });

      $('#tbl_uploaded').on('click','.td_btn_edit',function(e){
        clear_shipment_confirmation_details();
        var data = {
          'td_id'         : $(this).closest('tr').find('.td_id').val(),
        };
        $.ajax({
          'data'      : data,
          'type'      : 'get',
          'dataType'  : 'json',
          'url'       : 'select_shipment_confirmation_details',
          success : function(data){
            var html = ''
            if($.trim(data)){
              $('#txt_update_po_no').val(data[0]['po_no']);
              $('#txt_update_shipment_date').val(data[0]['shipment_date']);
              $('#txt_update_arrival_date').val(data[0]['arrival_date']);
              $('#txt_update_delivery_place_name').val(data[0]['delivery_place_name']);
              $('#txt_update_shipment_qty').val(data[0]['shipment_qty']);
              $('#txt_update_remarks').val(data[0]['remarks']);

              $('#txt_update_old_id').val(data[0]['id']);
              $('#txt_update_old_po_no').val(data[0]['po_no']);
              $('#txt_update_old_shipment_date').val(data[0]['shipment_date']);
              $('#txt_update_old_delivery_place_name').val(data[0]['delivery_place_name']);
              $('#txt_update_old_shipment_qty').val(data[0]['shipment_qty']);
              $('#txt_update_old_remarks').val(data[0]['remarks']);
              $('#txt_update_old_created_by').val(data[0]['created_by']);
              $('#txt_update_old_updated_by').val(data[0]['updated_by']);
              $('#txt_update_old_created_at').val(data[0]['created_at']);
              $('#txt_update_old_updated_at').val(data[0]['updated_at']);
            }
            $('#mdl_update').modal('show');
          }
        });

      });

      $('#btn_update_authorized').click(function(){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','update_shipment_confirmation').modal('show');
      });

      $('#frm_update').submit(function(e){

        e.preventDefault();
        $.ajax({
          'data'      : $(this).serialize()+'&txt_employee_number_scanner='+$("#txt_employee_number_scanner").val()+'&_token={{ csrf_token() }}',
          'type'      : 'post',
          'dataType'  : 'json',
          'url'       : 'update_shipment_confirmation',
          success     : function(data){
            show_alert(data['icon'],data['title'],data['body'],data['i']);
            if( data['i'] == 2 ){
              return;
            }
            $("#txt_employee_number_scanner").val('');
            $("#mdl_update").modal('hide');
            dt_uploaded.ajax.reload();
          },
          completed     : function(data){
          },
          error     : function(data){
          },
        });

      });

      $(document).on('click', '.td_btn_open', function(e){
          e.preventDefault();
          let shipCon = $(this).attr('ship_con-id');

          // ======================= START DLABEL HISTORY DATA TABLE =======================
          dataTableProdPackingInspectionHistory = $("#tbl_uploaded_history").DataTable({
              "processing" : false,
              "serverSide" : true,
              "bDestroy": true,
              "ajax" : {
                  url: "select_shipment_confirmation_history",
                  data: function (pamparam){
                      pamparam.shipCon = shipCon;
                  },
              },

              "columns":[
                  // { "data" : "action", orderable:false, searchable:false },
                  { "data" : "po_no" },
                  { "data" : "arrival_date" },
                  { "data" : "shipment_date" },
                  { "data" : "delivery_place_name" },
                  { "data" : "ship_boxing" },
                  { "data" : "remarks" },
                  { "data" : "created_by" },
                  { "data" : "created_at" },
              ],
              "columnDefs": [
                  {className: "text-center", targets: "_all"},
                  // { className: "align-center", targets: [1, 2, 6] },
              ],
          });// END DLABEL HISTORY DATA TABLE
      });
      // $('#tbl_uploaded').on('click','.td_btn_open',function(e){
      //   $('#tbl_uploaded_history tbody').html('');
      //   var data = {
      //     'td_id'         : $(this).closest('tr').find('.td_id').val(),
      //   };
      //   $.ajax({
      //     'data'      : data,
      //     'type'      : 'get',
      //     'dataType'  : 'json',
      //     'url'       : 'select_shipment_confirmation_history',
      //     success : function(data){
      //       var html = ''
      //       if($.trim(data)){
      //         $('#tbl_uploaded_history tbody').html(data['tbody']);
      //       }
      //       $('#mdl_history').modal('show');
      //     }
      //   });

      // });


    });//doc



    //-----------------------
    function insert_shipment_confirmation(){
      var arr_rows = [];
      var ctr = 0;
      $('#tbl_uploaded_temp tbody .ckb_upload:checked').each(function(){
        arr_rows[ctr] = {
            'po_no' : $(this).closest('td').find('.hidden_po_no').val(),
            'shipment_date' : $(this).closest('td').find('.hidden_shipment_date').val(),
            'delivery_place_name' : $(this).closest('td').find('.hidden_delivery_place_name').val(),
            'shipment_qty' : $(this).closest('td').find('.hidden_shipment_qty').val(),
            'arrival_date' : $(this).closest('td').find('.hidden_arrival_date').val(),
          };
        ctr++;
      });
      var data = {
        '_token'                        : '{{ csrf_token() }}',
        'txt_employee_number_scanner'   : $("#txt_employee_number_scanner").val(),
        'arr_rows'               : arr_rows,
      }
      $.ajax({
        'data'      : data,
        'type'      : 'post',
        'dataType'  : 'json',
        'url'       : 'insert_shipment_confirmation',
        success     : function(data){
          show_alert(data['icon'],data['title'],data['body'],data['i']);
          if( data['i'] == 2 ){
            return;
          }
          $('#btn_upload_file_clear').click();
          dt_uploaded.ajax.reload();
        },
        completed     : function(data){
        },
        error     : function(data){
        },
      });
    };

    function delete_shipment_confirmation(){
      var data = {
        '_token'                        : '{{ csrf_token() }}',
        'txt_employee_number_scanner'   : $("#txt_employee_number_scanner").val(),
        'txt_delete_remarks'            : $("#txt_delete_remarks").val(),
        'td_id'                         : $("#mdl_delete").attr('data-id'),
      }
      $.ajax({
        'data'      : data,
        'type'      : 'post',
        'dataType'  : 'json',
        'url'       : 'delete_shipment_confirmation',
        success     : function(data){
          show_alert(data['icon'],data['title'],data['body'],data['i']);
          if( data['i'] == 2 ){
            return;
          }
          $("#txt_employee_number_scanner").val('');
          $("#mdl_delete").modal('hide');
          dt_uploaded.ajax.reload();
        },
        completed     : function(data){
        },
        error     : function(data){
        },
      });
    }
    function clear_shipment_confirmation_details(){
      $('#txt_update_po_no').val('');
      $('#txt_update_shipment_date').val('');
      $('#txt_update_arrival_date').val('');
      $('#txt_update_delivery_place_name').val('');
      $('#txt_update_shipment_qty').val('');
      $('#txt_update_remarks').val('');

      $('#txt_update_old_id').val('');
      $('#txt_update_old_po_no').val('');
      $('#txt_update_old_shipment_date').val('');
      $('#txt_update_old_arrival_date').val('');
      $('#txt_update_old_delivery_place_name').val('');
      $('#txt_update_old_shipment_qty').val('');
      $('#txt_update_old_remarks').val('');
      $('#txt_update_old_created_by').val('');
      $('#txt_update_old_updated_by').val('');
      $('#txt_update_old_created_at').val('');
      $('#txt_update_old_updated_at').val('');
    }

    //-----------------------
    //-----------------------
    //-----------------------
    var alert_timeout = '';
    function hide_alert(){
      alert_timeout = setTimeout(function()
      {
        $('#mdl_alert').modal('hide');
      }, 2000);
    }
    function show_alert(icon,title,body,i){
      $('#mdl_alert #mdl_alert_title').html(icon+' '+title);
      $('#mdl_alert #mdl_alert_body').html(body);
      $('#mdl_alert').modal('show');

      if(i == 1){//if ok, auto hide modal
        clearTimeout(alert_timeout);
        hide_alert();
      }
    }

  </script>
  @endsection
  @endauth
