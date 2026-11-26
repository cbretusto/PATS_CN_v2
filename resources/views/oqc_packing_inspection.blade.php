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

@extends($layout)

@section('title', 'OQC Packing Inspection')

@section('content_page')
<!-- Content Wrapper. Contains page content -->
  <style type="text/css">
    .hidden_scanner_input{
      position: absolute;
      opacity: 0;
    }
  </style>
  
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>OQC Packing Inspection</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">OQC Packing Inspection</li>
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

          <!--TOP CARD: PO NUM AND DETAILS-->
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">OQC Packing Inspection</h3>
            </div>

            <!-- Start Page Content -->
              <div class="card-body">
                <div class="row">
                  <div class="col-sm-2">
                    <label>P.O. Number</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <button type="button" class="btn btn-primary btn_search_POno" title="Click to Scan PO Code"><i class="fa fa-qrcode"></i></button>
                      </div>
                        <input type="text" class="form-control" id="id_po_no" readonly="">
                    </div>
                  </div>

                  <div class="col-sm-2">
                    <label>Device Name</label>
                    <input type="text" class="form-control" id="id_device_name" readonly="">
                  </div>

                  <div class="col-sm-2">
                    <label>P.O. Quantity</label>
                    <input type="text" class="form-control" id="id_po_qty" readonly="">
                  </div>

                  <div class="col-sm-2">
                    <label>Boxing Quantity</label>
                    <input type="text" class="form-control" id="id_box_qty" readonly="">
                  </div>
                </div>

                <br>

              </div>
            <!-- !-- End Page Content -->
          </div>
          <!-- /.card TOP-->


          <!--BOTTOM CARD-->
        </div>
      </div>
      <!-- /.row -->


      <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">

            <div class="card-header">
               <h3 class="card-title">Packing Inspection Results</h3>
            </div>

            <div class="card-body">
              <div class="row">
                <div class="col-sm-2">
                    <label>Scan Packing Code QR:</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <button type="button" class="btn btn-info btn_search_pack_code" title="Click to Scan Packing Code" disabled><i class="fa fa-qrcode"></i></button>
                      </div>
                      <input type="text" class="form-control" id="id_pack_code" readonly="" placeholder="">
                    </div>
                </div>
              </div>

              <br>

              <div class="row">
                <div class="col-sm-12">
                  <div class="table-responsive dt-responsive">

                     <table id="tbl_packinginspection" class="table table-bordered table-hover" style="width: 100%;">
                        <thead>
                          <tr>
                            <th>Packing Code</th>
                            <th>Box Quantity</th>
                            <th>Inspection Status</th>
                            <th>Action</th>
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

    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!--MODALS-->
  <!--PO MODAL-->
  <div class="modal fade" id="modalScan_PO" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header border-bottom-0 pb-0">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body pt-0">
            <div class="text-center text-secondary">
            Please scan the PO number.
            <br>
            <br>
            <h1><i class="fa fa-qrcode fa-lg"></i></h1>
            </div>
            <input type="text" id="txt_search_po_number" class="hidden_scanner_input" autocomplete="off">
          </div>
        </div>
      </div>
  </div>

  <!--SCAN PACKING CODE MODAL-->
  <div class="modal fade" id="modal_ScanPackCode" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header border-bottom-0 pb-0">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body pt-0">
            <div class="text-center text-secondary">
            Please scan Packing Code.
            <br>
            <br>
            <h1><i class="fa fa-qrcode fa-lg"></i></h1>
            </div>
            <input type="text" id="txt_search_pack_code" class="hidden_scanner_input" autocomplete="off">
          </div>
        </div>
      </div>
  </div>


  <div class="modal fade" id="modalScanReelC3" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-search"></i> Check C3 Label Contents</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <!-- start coding here -->

          <form id="FormInitialInspection" method="post">
          @csrf


          <div class="row">
            <div class="col-sm-3">
              <label>PO Number</label>
              <input type="text" class="form-control" id="AddPoNum" name="add_po_num" readonly="">
            </div>
            <div class="col-sm-3">
              <label>Packing Code</label>
              <input type="text" class="form-control" id="AddPackCode" name="add_pack_code" readonly="">
            </div>
            <div class="col-sm-3">
              <label>Device</label>
              <input type="text" class="form-control" id="AddDeviceName" name="add_device_name" readonly="">
            </div>
            <div class="col-sm-3">
              <label>Packing Lot Quantity</label>
              <input type="text" class="form-control" id="AddLotQty" name="add_lot_qty" readonly="">
            </div>  
          </div>

          <br>
          <hr>

          <div class="row">
            <div class="col-sm-9">
              <div class="input-group">
                  <div class="input-group-prepend">
                    <button type="button" class="btn btn-primary" id="id_search_c3_label" title="Click to Scan C3 Contents"><i class="fas fa-barcode"></i></button>
                  </div>
                  <input type="text" class="form-control" placeholder="Press the button to start scanning" readonly="">
              </div>
            </div>

            <div class="col-sm-3">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text">Scan Row No.:</span>
                </div>
                <input type="text" class="form-control" id="txtRowScan" value="1" readonly="">
              </div>
            </div>
          </div>

          <br>

          <div class="row">
            <div class="col-sm-12">
              <table id="tbl_reel_lots" class="table table-bordered table-hover" style="width: 100%; font-size: 85%">
                <thead>
                  <tr>
                    <th>Customer P/N</th>
                    <th>Manufacture P/N</th>
                    <th>Lot Qty</th>
                    <th>Reel Lot</th>
                  </tr>
                </thead>
                <tbody>
                  
                </tbody>
              </table>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button> 
          <button type="button" class="btn btn-success" id="btn_save_initial_inspection" disabled>Save Initial Inspection</button>
        </div>
      </div>
    </div>
  </div>


        <!-- Modal -->
    <div class="modal fade" id="mdl_employee_number_scanner" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header border-bottom-0 pb-0">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body pt-0">
            <div class="text-center text-secondary">
              Please scan your ID.
              <br><br>

              <h1><i class="fa fa-qrcode fa-lg"></i></h1>
            </div>
            <input type="text" id="txt_employee_number_scanner" name="employee_number_scanner" class="hidden_scanner_input">
          </div>
  
        </div>
      </div>
    </div>
    <!-- /.Modal -->

  </form> <!--FORM INITIAL INSPECTION-->

       <!--SCAN PACKING C3 MODAL-->
  <div class="modal fade" id="modal_scanC3Label" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header border-bottom-0 pb-0">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body pt-0">
            <div class="text-center text-secondary">
            Please scan C3 Label.
            <br>
            <br>
            <h1><i class="fa fa-barcode fa-lg"></i></h1>
            </div>
            <input type="text" id="txt_search_c3_label" class="hidden_scanner_input" autocomplete="off">
          </div>
        </div>
      </div>
  </div>


  <div class="modal fade" id="modalCheckpointFillin" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
       
        <div class="modal-header">
           <h4 class="modal-title"><i class="fa fa-check-square"></i> Inspection Checkpoints Fill-in</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        </div>

        <form id="FormInspectionFillin" method="post">
          @csrf

        <div class="modal-body">

          <div class="row">
            <div class="col-sm-3">
              <label>PO Number</label>
              <input type="text" class="form-control" id="CheckPoNum" name="check_po_num" readonly="">
            </div>
            <div class="col-sm-3">
              <label>Packing Code</label>
              <input type="text" class="form-control" id="CheckPackCode" name="check_pack_code" readonly="">
            </div>
            <div class="col-sm-3">
              <label>Device</label>
              <input type="text" class="form-control" id="CheckDeviceName" name="check_device_name" readonly="">
            </div>
            <div class="col-sm-3">
              <label>Packing Lot Quantity</label>
              <input type="text" class="form-control" id="CheckLotQty" name="check_lot_qty" readonly="">
            </div>  
          </div>

          <br>

                    <div class="card card-primary" id="id_second_point">
            <div class="card-header">
              <h3 class="card-title"> OQC Inspection Points Fill-In</h3>
            </div>

            <div class="card-body">

              <div class="row">

                <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-65">
                          <span class="input-group-text w-100" id="basic-addon1">Packing Manual Compliance</span>
                        </div>
                        <select class="form-control form-control-sm select_packin" name="name_document_compliance" id="id_document_compliance">
                          <option selected disabled>---</option>
                          <option value = "1">Yes</option>
                          <option value = "2">No</option>
                          <option value = "3">N/A</option>
                        </select>
                      </div>
                </div>

                <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Accessory Requirement</span>
                        </div>
                        <select class="form-control form-control-sm select_packin" name="name_accessory_req" id="id_accessory_req">
                          <option selected disabled>---</option>
                          <option value = "1">Yes</option>
                          <option value = "2">No</option>
                          <option value = "3">N/A</option>
                        </select>
                      </div>
                </div>

                <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">C.O.C. Requirement</span>
                        </div>
                        <select class="form-control form-control-sm select_packin" name="name_coc_req" id="id_coc_req">
                          <option selected disabled>---</option>
                          <option value = "1">Yes</option>
                          <option value = "2">No</option>
                          <option value = "3">N/A</option>
                        </select>
                      </div>
                </div>

              </div>

              <hr>

              <div class="row">

                <div class="col">
                </div>

                <div class="col">
                </div>

                 <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                           <span class="input-group-text primary w-100" style="background-color: rgb(57, 179, 215); color: rgb(255, 255, 255); border-color: rgb(57, 179, 215);" id="basic-addon1">QC JUDGEMENT</span>
                        </div>
                        <select class="form-control form-control-sm" name="name_qc_judgement" id="id_qc_judgement" readonly>
                          <option selected disabled>---</option>
                          <option value = "2">ACCEPT</option>
                          <option value = "3">REJECT</option>
                        </select>
                      </div>
                    </div>

              </div>

            </div>

          </div>


        </div>

        <div class="modal-footer">
           <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button> 
          <button type="button" class="btn btn-success" id="btn_save_inspection_result" disabled>Save Inspection Result</button>

        </div>

      </div>
    </div>
  </div>

  <!-- Modal -->
    <div class="modal fade" id="mdl_fillin_number_scanner" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header border-bottom-0 pb-0">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body pt-0">
            <div class="text-center text-secondary">
              Please scan your ID.
              <br><br>

              <h1><i class="fa fa-qrcode fa-lg"></i></h1>
            </div>
            <input type="text" id="txt_fillin_number_scanner" name="fillin_number_scanner" class="hidden_scanner_input">
          </div>
  
        </div>
      </div>
    </div>
    <!-- /.Modal -->
  </form>

  <!--shipping details-->
  <div class="modal fade" id="modalShippingDetails" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-plane"></i> Add Shipping Details</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        </div>

        <div class="modal-body">

          <form id="FormShippingDetails" method="post">
            @csrf

          <div class="row">
            <div class="col-sm-6">
              <label>PO Number</label>
              <input type="text" class="form-control" id="ShipPoNum" name="ship_po_num" readonly="">
            </div>
            <div class="col-sm-6">
              <label>Packing Code</label>
              <input type="text" class="form-control" id="ShipPackCode" name="ship_pack_code" readonly="">
            </div>
          </div>

          <div class="row">
            <div class="col-sm-6">
              <label>Device</label>
              <input type="text" class="form-control" id="ShipDeviceName" name="ship_device_name" readonly="">
            </div>
            <div class="col-sm-6">
              <label>Packing Lot Quantity</label>
              <input type="text" class="form-control" id="ShipLotQty" name="ship_lot_qty" readonly="">
            </div>  
          </div>

          <br>

          <div class="row">
            <div class="col-md-12">
              <div class="card card-primary">
                 <div class="card-header text-white" style="background-color:#5bC0de;">
                  Shipping Details
                </div>

                <div class="card-body">
                  <div class="row">
                    <div class="col-md-12">
                      <label>Shipping Date</label>
                      <input type="date" class="form-control" id="txtShippingDate" name="shipping_date">
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <label>Shipping Destination</label>
                      <input type="text" class="form-control" id="txtShippingDestination" name="shipping_destination">
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <label>Shipping Remarks</label>
                      <textarea class="form-control" id="txtShippingRemarks" name="shipping_remarks"></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>


        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button> 
          <button type="button" class="btn btn-success" id="btn_save_shipping">Save Shipping Details</button>

        </div>
      </div>
    </div>
   </div>

     <!-- Modal -->
    <div class="modal fade" id="mdl_shipping_number_scanner" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header border-bottom-0 pb-0">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body pt-0">
            <div class="text-center text-secondary">
              Please scan your ID.
              <br><br>

              <h1><i class="fa fa-qrcode fa-lg"></i></h1>
            </div>
            <input type="text" id="txt_shipping_number_scanner" name="shipping_number_scanner" class="hidden_scanner_input">
          </div>
  
        </div>
      </div>
    </div>
    <!-- /.Modal -->
  </form>

  <div class="modal fade" id="mdl_oqc_packing_history" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">

      <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-history"></i> Packing Inspection History</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      </div>

      <div class="modal-body">

          <div class="row">
            <div class="col-sm-6">
              <label>PO Number</label>
              <input type="text" class="form-control" id="historyPoNum" name="history_po_num" readonly="">
            </div>
            <div class="col-sm-6">
              <label>Packing Code</label>
              <input type="text" class="form-control" id="historyPackCode" name="history_pack_code" readonly="">
            </div>
          </div>

          <div class="row">
            <div class="col-sm-6">
              <label>Device</label>
              <input type="text" class="form-control" id="historyDeviceName" name="history_device_name" readonly="">
            </div>
            <div class="col-sm-6">
              <label>Packing Lot Quantity</label>
              <input type="text" class="form-control" id="historyLotQty" name="history_lot_qty" readonly="">
            </div>  
          </div>


        <br>
        <hr>

        <div class="row">

          <div class="col-sm-12">

            <div class="table-responsive dt-responsive">

              <table id="tbl_packinginspection_history" class="table table-bordered table-hover" style="width: 100%;">

                <thead>
                  <tr>
                    <th>Inspection Date/Time</th>
                    <th>OQC Packing Inspector</th>
                    <th>Status</th>
                    <th>Document Compliance</th>
                    <th>Accessory Requirement</th>
                    <th>COC Requirement</th>
                    <th>Shipping Date</th>
                    <th>Shipping Destination</th>
                    <th>Shipping Remarks</th>
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
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> 

      </div>

    </div>
    </div>
  </div>

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
  let dt_packinginspection;
  let dt_packinginspection_history;

  $(document).ready(function () {
    bsCustomFileInput.init();

     dt_packinginspection_history = $('#tbl_packinginspection_history').DataTable({

        "processing" : true,
        "serverSide" : true,
        "sDom" : 't,p,r',
        
        "ajax" :
        {
          url: "view_inspection_history",
          data: function(param)
          {
            param.po_num = $("#historyPoNum").val();
            param.pack_code = $("#historyPackCode").val();
          }
        },

        "columns":
        [
          { "data" : "inspection_datetime" },
          { "data" : "oqc_name" },
          { "data" : "status"},
          { "data" : "document_compliance"},
          { "data" : "accessory_requirement" },
          { "data" : "coc_requirement" },
          { "data" : "shipping_date"},
          { "data" : "shipping_destination" },
          { "data" : "shipping_remarks" },
          
        ]

      });

    dt_packinginspection = $('#tbl_packinginspection').DataTable({

        "processing" : true,
        "serverSide" : true,
        "sDom" : 't,p,r',
        
        "ajax" :
        {
          url: "view_packing_codes",
          data: function(param)
          {
            param.po_num = $("#txt_search_po_number").val();
          }
        },

        "columns":
        [
          { "data" : "pack_code"},
          { "data" : "box_qty"},
          { "data" : "status" },
          { "data" : "action", orderable:false, searchable:false },
        ],

        "order": [[0, "desc" ]]

      });
  });

      //ON CLICK po number
    $(document).on('click','.btn_search_POno',function(e){
      $('#txt_search_po_number').val('');
      $('#modalScan_PO').attr('data-formid', '').modal('show');
    });

    //-----
    $( "#modalScan_PO" ).on('shown.bs.modal', function(){
      $(this).find('#txt_search_po_number').focus();
    });
    //-----

    $(document).on('keypress',function(e){
      if( ($("#modalScan_PO").data('bs.modal') || {})._isShown ){
        $('#txt_search_po_number').focus();

        if( e.keyCode == 13 && $('#txt_search_po_number').val() !='' && ($('#txt_search_po_number').val().length >= 4) ){
            $('#modalScan_PO').modal('hide');
          }
        }
    });
    //END OF ON CLICK PO NUMBER

    $(document).on('keyup','#txt_search_po_number',function(e){

      $('.btn_search_pack_code').attr('disabled','disabled');

        if( e.keyCode == 13 ){

          $('#id_po_no').val('');
          $('#id_device_name').val('');
          $('#id_po_qty').val('');
          $('#id_box_qty').val('');

          var data = {
          'po'      : $('#txt_search_po_number').val()
          }

          data = $.param(data);
        $.ajax({
          type      : "get",
          dataType  : "json",
          data      : data,
          url       : "get_po_details",
          success : function(data){

            $('#id_po_no').val( data['po_details'][0]['po_no'] );
            $('#id_device_name').val( data['po_details'][0]['wbs_kitting']['device_name'] );
            $('#id_po_qty').val( data['po_details'][0]['wbs_kitting']['po_qty'] );
            $('#id_box_qty').val( data['po_details'][0]['wbs_kitting']['device_info']['ship_boxing'] );
            $('.btn_search_pack_code').removeAttr('disabled');


            dt_packinginspection.draw();

          },error : function(data){

              }

            }); 
        }
      });

    $(document).on('click','.btn_search_pack_code',function(e){
      $('#txt_search_pack_code').val('');
      $('#modal_ScanPackCode').attr('data-formid', '').modal('show');
    });
    //-----
    $( "#modal_ScanPackCode" ).on('shown.bs.modal', function(){
      $(this).find('#txt_search_pack_code').focus();
    });
    //-----
    $(document).on('keypress',function(e){
      if( ($("#modal_ScanPackCode").data('bs.modal') || {})._isShown ){
        $('#txt_search_pack_code').focus();

        if( e.keyCode == 13 && $('#txt_search_pack_code').val() !='' && ($('#txt_search_pack_code').val().length >= 4) ){
            $('#modal_ScanPackCode').modal('hide');
          }
        }
    });

    $(document).on('keyup','#txt_search_pack_code',function(e){
        if( e.keyCode == 13 ){

          $('#id_pack_code').val('');

          var po_num = $('#id_po_no').val();
          var pack_code = $('#txt_search_pack_code').val();

          //Insert Function to check if po_num and pack_code exists here
          CheckPackCodeExists(po_num,pack_code);
        }
    });

    //C3 Label Checking
      $(document).on('click','#id_search_c3_label',function(e)
        {   
          $('#txt_search_c3_label').val('');
          $('#modal_scanC3Label').attr('data-formid', '').modal('show');
        });
    //-----
    $( "#modal_scanC3Label" ).on('shown.bs.modal', function(){
      $(this).find('#txt_search_c3_label').focus();
    });
    //-----
      $(document).on('keypress',function(e){

          if( ($("#modal_scanC3Label").data('bs.modal') || {})._isShown ){
            $('#txt_search_c3_label').focus();

            if( e.keyCode == 13 && $('#txt_search_c3_label').val() !='' && ($('#txt_search_c3_label').val().length >= 4) ){
                $('#modal_scanC3Label').modal('hide');
              }
            }
        });

      $(document).on('keyup','#txt_search_c3_label',function(e){
        if( e.keyCode == 13 ){

            var barcode = $('#txt_search_c3_label').val();
            //var barcodeCase = barcode.toUpperCase();
            var row = $('#txtRowScan').val();

            CheckC3Label(barcode,row); // OQCPackingInspection.js 
            CountSuccessInTable();
          }
        });

      $('#btn_save_initial_inspection').click(function(){
        
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','#FormInitialInspection').modal('show');
      });
    //-----
    $( "#mdl_employee_number_scanner" ).on('shown.bs.modal', function(){
      $(this).find('#txt_employee_number_scanner').focus();
    });
    //-----
      $(document).on('keypress',function(e)
       {
        if( ($("#mdl_employee_number_scanner").data('bs.modal') || {})._isShown )
        {
          $('#txt_employee_number_scanner').focus();

          if( e.keyCode == 13 && $('#txt_employee_number_scanner').val() !='' && ($('#txt_employee_number_scanner').val().length >= 4) )
            {
              $('#mdl_employee_number_scanner').modal('hide');

               var formid = $("#mdl_employee_number_scanner").attr('data-formid');

                if ( ( formid ).indexOf('#') > -1)
                {
                  $( formid ).submit();
                }

            }
          }
      });


      $('#FormInitialInspection').submit(function(e){
        e.preventDefault();

        SubmitInitialInspection();
      });


  //functions for inspection points fill in
  $(document).on('click','.btn_open_checkpoints', function(e){

    let po_num = $(this).attr('po-num');
    let pack_code = $(this).attr('pack-code');
    let lot_count = $(this).attr('lot-count');
    let device = $('#id_device_name').val();

    $('#CheckPoNum').val(po_num);
    $('#CheckPackCode').val(pack_code);
    $('#CheckLotQty').val(lot_count);
    $('#CheckDeviceName').val(device);

    OpenRDrawing(device);
  });
    //-----
    $( "#mdl_fillin_number_scanner" ).on('shown.bs.modal', function(){
      $(this).find('#txt_fillin_number_scanner').focus();
    });
    //-----
      $('.select_packin').on('change', function() {

        $('#btn_save_inspection_result').prop('disabled',false);

          let noCounter = 0;
          $('.select_packin option:selected').each(function(i, obj) {
              if( $(this).val() == 2 ){
                noCounter++;
              }
          });

          if(noCounter > 0)
          {
            $('#id_qc_judgement').val(3);
          }
          else
          {
            $('#id_qc_judgement').val(2);
          }
    });

      $('#btn_save_inspection_result').click(function(){
        $('#txt_fillin_number_scanner').val('');
        $('#mdl_fillin_number_scanner').attr('data-formid','#FormInspectionFillin').modal('show');
      });

            $(document).on('keypress',function(e)
       {
        if( ($("#mdl_fillin_number_scanner").data('bs.modal') || {})._isShown )
        {
          $('#txt_fillin_number_scanner').focus();

          if( e.keyCode == 13 && $('#txt_fillin_number_scanner').val() !='' && ($('#txt_fillin_number_scanner').val().length >= 4) )
            {
              $('#mdl_fillin_number_scanner').modal('hide');

               var formid = $("#mdl_fillin_number_scanner").attr('data-formid');

                if ( ( formid ).indexOf('#') > -1)
                {
                  $( formid ).submit();
                }

            }
          }
      });

      $('#FormInspectionFillin').submit(function(e){
        e.preventDefault();

        SubmitInspectionFillin();
      });





      $(document).on('click','.btn_open_shipping', function(e){

            let po_num = $(this).attr('po-num');
            let pack_code = $(this).attr('pack-code');
            let lot_count = $(this).attr('lot-count');
            let device = $('#id_device_name').val();

            $('#ShipPoNum').val(po_num);
            $('#ShipPackCode').val(pack_code);
            $('#ShipLotQty').val(lot_count);
            $('#ShipDeviceName').val(device);
          });

        $(document).on('click','.btn_open_history', function(e){

            let po_num = $(this).attr('po-num');
            let pack_code = $(this).attr('pack-code');
            let lot_count = $(this).attr('lot-count');
            let device = $('#id_device_name').val();

            $('#historyPoNum').val(po_num);
            $('#historyPackCode').val(pack_code);
            $('#historyLotQty').val(lot_count);
            $('#historyDeviceName').val(device);
          });

      $('#btn_save_shipping').click(function(){
        
        $('#txt_shipping_number_scanner').val('');
        $('#mdl_shipping_number_scanner').attr('data-formid','#FormShippingDetails').modal('show');
      });

    //-----
    $( "#mdl_shipping_number_scanner" ).on('shown.bs.modal', function(){
      $(this).find('#txt_shipping_number_scanner').focus();
    });
    //-----
        $(document).on('keypress',function(e)
       {
        if( ($("#mdl_shipping_number_scanner").data('bs.modal') || {})._isShown )
        {
          $('#txt_shipping_number_scanner').focus();

          if( e.keyCode == 13 && $('#txt_shipping_number_scanner').val() !='' && ($('#txt_shipping_number_scanner').val().length >= 4) )
            {
              $('#mdl_shipping_number_scanner').modal('hide');

               var formid = $("#mdl_shipping_number_scanner").attr('data-formid');

                if ( ( formid ).indexOf('#') > -1)
                {
                  $( formid ).submit();
                }

            }
          }
      });


      $('#FormShippingDetails').submit(function(e){
        e.preventDefault();

        SubmitShippingDetails();
      });

      $(document).on('click','.btn_open_history', function(){
        dt_packinginspection_history.draw();
      });

  //


</script>
@endsection