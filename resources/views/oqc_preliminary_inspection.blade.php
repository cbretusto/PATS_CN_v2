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

  @section('title', 'OQC Packing Inspection')

  @section('content_page')
    <!--START OF OQC PACKING INSPECTION-->
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
            <h1>OQC Preliminary Packing Inspection</h1>
          </div>

          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">OQC Preliminary Packing Inspection</li>
            </ol>
          </div>

        </div>
      </div>
    </section>

   <!--MAIN CONTENT-->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary">

              <div class="card-header">
                <h3 class="card-title">Search P.O. Number</h3>
              </div>

              <div class="card-body">

                <!--TOP CARD: PO NUM AND DETAILS-->
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

            </div>
          </div>

       <!--BOTTOM CARD: list of finished packing inspection-->

          <div class="col-md-12">
            <div class="card card-primary">

              <div class="card-header">
                <h3 class="card-title">Packing Inspection Results (per Packing Code)</h3>
              </div>

              <div class="card-body">

                <!--CONTENT STARTS HERE-->

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
                    <div class="table responsive">

                      <!--start of table for Finished Production Preliminary Inspection-->
                      <table id="tblOQCPrelimInspection" class="table table-bordered table-hover" style="width: 100%;">

                        <thead>
                          <tr>
                            <th>Action</th>
                            <th>Packing Code</th>
                            <th>Box Quantity</th>
                            <th>Inspection Status</th>
                          </tr>
                        </thead>

                        <tbody>

                        </tbody>

                      </table>

                      <!--end of table for Finished Production Preliminary Inspection-->

                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>
    </section>
  </div>

<form id="formOQCPrelimInspection" method="post">
  @csrf

<div class="modal fade" id="modalOQCPrelimInspection">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">

      <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-edit"></i> OQC Preliminary Inspection</h4>
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>

      <div class="modal-body">

        <div class="row">
            <div class="col-sm-2">
              <label>P.O. Number</label>
              <input type="text" class="form-control" id="id_modal_po_num" name="name_modal_po_num" readonly="">
            </div>

            <div class="col-sm-2">
              <label>Packing Code</label>
              <input type="text" class="form-control" id="id_modal_pack_code" name="name_modal_pack_code"readonly="">
            </div>

            <div class="col-sm-2">
              <label>Device</label>
              <input type="text" class="form-control" id="id_modal_device" readonly="">
            </div>

            <div class="col-sm-2">
              <label>Packing Lot Quantity</label>
              <input type="text" class="form-control" id="id_modal_box_qty" readonly="">
            </div>
          </div>

          <br>

          <!--TOP CARD FOR CHECKING C3 LABEL VS RETRIEVED DATA-->
            <div class="card card-primary">
             <div class="card-header">
              <h3 class="card-title"><span class="badge badge-secondary">1.</span> Scan & Check C3 Labels</h3>
              </div>

               <div class="card-body">

                    <div class="row">
                      <div class="col-sm-3">
                        <label>Lot-Attached C3 Labels</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                              <button type="button" class="btn btn-primary btn_search_c3_label" bt-check="1" title="Click to Scan C3 Reel Lot Barcode" id="btn_modal_lot"><i class="fa fa-barcode"></i></button>
                            </div>
                            <input type="text" class="form-control" id="id_c3_scan_lot" readonly="">
                        </div>
                      </div>

                      <div class="col-sm-3">
                        <label>Box-Attached C3 Labels</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                              <button type="button" class="btn btn-primary btn_search_c3_label" bt-check="2" title="Click to Scan C3 Reel Lot Barcode"  id="btn_modal_box"><i class="fa fa-barcode"></i></button>
                            </div>
                            <input type="text" class="form-control" id="id_c3_scan_box" readonly="">
                        </div>
                      </div>

                      <div class="col-sm-3">
                        <label>Accessory C3 Labels (1st Copy)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                              <button type="button" class="btn btn-primary btn_search_c3_label" bt-check="3" title="Click to Scan C3 Reel Lot Barcode"  id="btn_modal_acc_1"><i class="fa fa-barcode"></i></button>
                            </div>
                            <input type="text" class="form-control" id="id_c3_scan_accessory_1st" readonly="">
                        </div>
                      </div>

                      <div class="col-sm-3">
                        <label>Accessory C3 Labels (2nd Copy)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                              <button type="button" class="btn btn-primary btn_search_c3_label" bt-check="4" title="Click to Scan C3 Reel Lot Barcode"  id="btn_modal_acc_2"><i class="fa fa-barcode"></i></button>
                            </div>
                            <input type="text" class="form-control" id="id_c3_scan_accessory_2nd" readonly="">
                        </div>
                      </div>

                    </div>

              </div>

              <div class="card-footer">
                <div class="float-right">
                   <button type="button" class="btn btn-success" id="btn_continue_inspection" disabled><i class="fa fa-file"></i> View R Drawing & Proceed</button>
                </div>
              </div>

            </div>
          <!--END OF TOP CARD FOR CHECKING C3 LABEL VS RETRIEVED DATA-->

          <div class="card card-primary" id="id_second_point">
            <div class="card-header">
              <h3 class="card-title"><span class="badge badge-secondary">2.</span> OQC Inspection Points Fill-In</h3>
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
                          <option value = "1">ACCEPT</option>
                          <option value = "2">REJECT</option>
                          <option value = "3">N/A</option>
                        </select>
                      </div>
                    </div>

              </div>

            </div>

          </div>

      </div>

      <div class="modal-footer">

         <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-success" id="btn_save_oqc_prelim_inspection">Save Results</button>

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


</form> <!--END OF FORM OQC INSPECTION RESULT-->


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

<form id="formShippingDetails" method="post">
  @csrf

    <div class="modal fade" id="modal_shipping_details">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">

          <div class="modal-header">
             <h4 class="modal-title"><i class="fa fa-truck"></i> Add Shipping Details</h4>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>

          <div class="modal-body">
            <div class="row">
            <div class="col-sm-3">
              <label>P.O. Number</label>
              <input type="text" class="form-control" id="id_shipping_po_num" name="name_shipping_po_num" readonly="">
            </div>

            <div class="col-sm-3">
              <label>Packing Code</label>
              <input type="text" class="form-control" id="id_shipping_pack_code" name="name_shipping_pack_code"readonly="">
            </div>

            <div class="col-sm-3">
              <label>Device</label>
              <input type="text" class="form-control" id="id_shipping_device" readonly="">
            </div>

            <div class="col-sm-3">
              <label>Lot Quantity</label>
              <input type="text" class="form-control" id="id_shipping_box_qty" readonly="">
            </div>
          </div>

          <br>

            <div class="card card-primary">

            <div class="card-header">
              <h3 class="card-title">Shipping Details</h3>
            </div>

            <div class="modal-body">

            <div class="row">
              <div class="col-sm-4">

                 <div class="row">

                    <div class="col">
                      <label>Shipping Date:</label>
                      <div class="input-group input-group-sm mb-3">
                        <input type="date" class="form-control form-control-sm" id="id_shipping_date" name="name_shipping_date">
                      </div>
                    </div>
                  </div>

              <div class="row">

                <div class="col">
                      <label>Shipping Destination:</label>
                      <div class="input-group input-group-sm mb-3">
                       <!--  <select class="form-control form-control-sm" name="name_shipping_destination" id="id_shipping_destination">
                              <option selected disabled>---</option>
                        </select> -->

                         <input type="text" class="form-control form-control-sm" id="id_shipping_destination" name="name_shipping_destination">
                      </div>
                      </div>
                    </div>

                  </div>

                  <div class="col-sm-8">
                
                 <div class="row">

                    <div class="col">
                      <label>Shipping Remarks:</label>
                      <div class="input-group input-group-sm mb-3">
                        <textarea  class="form-control form-control-sm" id="id_shipping_remarks" name="name_shipping_remarks" rows="5"></textarea>
                      </div>
                    </div>

                  </div>
              </div>
            </div>

              

            </div>

            </div>

          </div>

          <div class="modal-footer">

            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-success" id="btn_save_shipping_details">Save Details</button>
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

</form> <!--END OF FORM SHIPPING DETAILS-->

<!--MODAL CHECK C3-->
<div class="modal fade" id="modalCheckC3">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
         <h4 class="modal-title"><i class="fa fa-history"></i> Check C3 Label</h4>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="txt_id_c3_part">
        <input type="hidden" id="txt_id_c3_po_num">
        <input type="hidden" id="txt_id_c3_pack_code">

         <div class="row">
                <div class="col-sm-3">
                  <label>Scan C3 Reel Lot Barcode</label>
                  <div class="input-group">
                      <div class="input-group-prepend">
                        <button type="button" class="btn btn-primary " id="id_search_c3_label" title="Click to Scan C3 Reel Lot Barcode"><i class="fa fa-barcode"></i></button>
                      </div>
                      <input type="text" class="form-control" id="id_c3_scan" readonly="">
                  </div>
                </div>

                <div class="col-sm-2">
                  <label>Row Number</label>
                  <div class="input-group">
                      <input type="text" class="form-control" id="id_row_number" readonly value='1'>
                  </div>
                </div>

                 <div class="col-sm-7">
                       <div class="float-right">
                            <button type="button" class="btn btn-secondary" id="btn_modal_success" disabled><i class="fa fa-file"></i> Return Result <span id="id_modal_result"></span></button>
                       </div>
                </div>
          </div>

          <br>

         <div class="row">
                <div class="col-sm-12">
                   <div class="table responsive">

                      <!--start of table for Scanning Reel Lots-->
                      <table id="tblReelLotsForPackingCode" class="table table-bordered table-hover" style="width: 100%; font-size: 85%">

                        <thead>
                          <tr>
                            <th>Customer P/N</th>
                            <th>Manufacture P/N</th>
                            <th>Lot Qty</th>
                            <th>Reel Lot No</th>
                          </tr>
                        </thead>

                        <tbody>

                        </tbody>

                      </table>

                      <!--end of table for Scanning Reel Lots-->

                    </div>
                </div>
          </div>

      </div>

    </div>
  </div>
</div>

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

          <!-- Modal -->
    <div class="modal fade" id="mdl_checksupervisor_number_scanner" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header border-bottom-0 pb-0">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>


          <input type="hidden" id="id_hidden_ponum" name="name_hidden_ponum" readonly>
          <input type="hidden" id="id_hidden_pack_code" name="name_hidden_pack_code" readonly>

          <div class="modal-body pt-0">
            <div class="text-center text-secondary">
              Please scan Supervisor ID.
              <br><br>

              <h1><i class="fa fa-qrcode fa-lg"></i></h1>
            </div>
            <input type="text" id="txt_checksupervisor_number_scanner" name="employee_checksupervisor_scanner" class="hidden_scanner_input" autocomplete="off">
          </div>
  
        </div>
      </div>
    </div>
    <!-- /.Modal -->

  <!--SUPERVISOR MODAL-->

  <form id="formSupervisorOQCInspection" method="post">
  @csrf

  <div class="modal fade" id="modalOQCSupervisorInspection">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">

        <div class="modal-header">
           <h4 class="modal-title"><i class="fa fa-edit"></i> OQC Preliminary Inspection (Supervisor)</h4>
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>

        <div class="modal-body">

           <div class="row">
            <div class="col-sm-2">
              <label>P.O. Number</label>
              <input type="text" class="form-control" id="id_modal_supervisor_po_num" name="name_modal_supervisor_po_num" readonly="">
            </div>

            <div class="col-sm-2">
              <label>Packing Code</label>
              <input type="text" class="form-control" id="id_modal_supervisor_pack_code" name="name_modal_supervisor_pack_code"readonly="">
            </div>

            <div class="col-sm-2">
              <label>Device</label>
              <input type="text" class="form-control" id="id_modal_supervisor_device" readonly="">
            </div>

            <div class="col-sm-2">
              <label>Packing Lot Quantity</label>
              <input type="text" class="form-control" id="id_modal_supervisor_box_qty" readonly="">
            </div>

            <div class="col-sm-4">
               <div class="float-right">
                    <button class="btn btn-success" id="btn_supervisor_check_drawing" type="button"><i class="fa fa-file" aria-hidden="true" ></i>&nbsp;&nbsp;View R Drawing</button>
                </div>
            </div>
          </div>

          <br>

          <!--SUPERVISOR OQC INSPECTION CHECKPOINTS-->
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Supervisor Inspection Checkpoints fill-in</h3>
            </div>

          
            <div class="card-body">

              <div class="row">

                <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-65">
                          <span class="input-group-text w-100" id="basic-addon1">Packing Manual Compliance</span>
                        </div>
                        <select class="form-control form-control-sm select_packin_supervisor" name="name_supervisor_document_compliance" id="id_supervisor_document_compliance">
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
                        <select class="form-control form-control-sm select_packin_supervisor" name="name_supervisor_accessory_req" id="id_supervisor_accessory_req">
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
                        <select class="form-control form-control-sm select_packin_supervisor" name="name_supervisor_coc_req" id="id_supervisor_coc_req">
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
                           <span class="input-group-text primary w-100" style="background-color: rgb(57, 179, 215); color: rgb(255, 255, 255); border-color: rgb(57, 179, 215);" id="basic-addon1">SUPERVISOR JUDGEMENT</span>
                        </div>
                        <select class="form-control form-control-sm" name="name_supervisor_qc_judgement" id="id_supervisor_qc_judgement" readonly>
                          <option selected disabled>---</option>
                          <option value = "1">ACCEPT</option>
                          <option value = "2">REJECT</option>
                          <option value = "3">N/A</option>
                        </select>
                      </div>
                    </div>

              </div>

            </div>

          </div>
          </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-success" id="btn_save_oqcpreliminspectionsupervisor">Save Results</button>
        </div>

      </div>
    </div>
  </div>


        <!-- Modal -->
    <div class="modal fade" id="mdl_supervisor_number_scanner" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header border-bottom-0 pb-0">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body pt-0">
            <div class="text-center text-secondary">
              Please scan Supervisor ID.
              <br><br>

              <h1><i class="fa fa-qrcode fa-lg"></i></h1>
            </div>
            <input type="text" id="txt_supervisor_number_scanner" name="employee_supervisor_scanner" class="hidden_scanner_input" autocomplete="off">
          </div>
  
        </div>
      </div>
    </div>
    <!-- /.Modal -->


</form> <!--END OF FORM-->





<!--OQC INSPECTION HISTORY-->
    <div class="modal fade" id="modal_oqc_history">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">

        <div class="modal-header">
           <h4 class="modal-title"><i class="fa fa-history"></i> OQC Preliminary Packing Inspection History</h4>
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>

        <div class="modal-body">

           <input type="hidden" id="id_history_ponum" name="name_history_ponum" readonly>
           <input type="hidden" id="id_history_pack_code" name="name_history_pack_code" readonly>

           <div class="row">
                  <div class="col-sm-12">
                    <div class="table-responsive">

                      <!--start of table for Finished Production Preliminary Inspection-->
                      <table id="tblOQCPrelimInspectionHistory" class="table table-bordered table-hover" style="width: 100%; font-size: 85%">

                        <thead>
                          <tr>
                            <th>Inspector/Supervisor Judgement</th>
                            <th>Inspection Date/Time</th>
                            <th>P.O. Number</th>
                            <th>Packing Code</th>
                            <th>Packing Manual Compliance</th>
                            <th>Accessory Requirement</th>
                            <th>C.O.C. Requirement</th>
                            <th>Shipping Date</th>
                            <th>Shipping Destination</th>
                            <th>Shipping Remarks</th>
                          </tr>
                        </thead>

                        <tbody>

                        </tbody>

                      </table>

                      <!--end of table for Finished Production Preliminary Inspection-->

                    </div>
                  </div>
                </div>

        </div>

        <br>

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

    $('#id_qc_judgement').css("pointer-events","none");
    $('#id_supervisor_qc_judgement').css("pointer-events","none");

    let dt_oqcpreliminspection;
    let dt_oqcpreliminspectionhistory;

    //multidimensional array vs system retrieved
    let arrReelC3Stickers = [];


    $(document).ready(function()
    {
      bsCustomFileInput.init();

      $("#id_second_point *").prop("disabled", true);
    });

     dt_oqcpreliminspection = $("#tblOQCPrelimInspection").DataTable({

      "processing" : true,
      "serverSide" : true,
      "sDom" : 't,p,r',
      
      "ajax" :
      {
        url: "oqc_view_batches",
        data: function(param)
        {
          param.po_number = $("#txt_search_po_number").val();
        }
      },

      "columns":
      [
        { "data" : "action", orderable:false, searchable:false },
        { "data" : "pack_code" },
        { "data" : "output_qty"},
        { "data" : "status" }
      ]

    }); //end of datatable of finished inspections

     dt_oqcpreliminspectionhistory = $("#tblOQCPrelimInspectionHistory").DataTable({

      "processing" : true,
      "serverSide" : true,
      "sDom" : 't,p,r',
      
      "ajax" :
      {
        url: "oqc_view_history",
        data: function(param)
        {
          param.po_number = $("#id_history_ponum").val();
          param.pack_code = $("#id_history_pack_code").val();
        }
      },

      "columns":
      [
        { "data" : "judgement", orderable:false, searchable:false },
        { "data" : "datetime" },
        { "data" : "po_num"},
        { "data" : "pack_code" },
        { "data" : "doc_compliance" },
        { "data" : "acc_req" },
        { "data" : "coc_req" },
        { "data" : "ship_date" },
        { "data" : "ship_destination" },
        { "data" : "ship_remarks" }
      ]

    }); //end of datatable of finished inspections


    //ON CLICK FUNCTIONS----------------------------------------------------------

       $(document).on('click','.btn_open_packin_history',function(e)
      {
        let po_num = $(this).attr('po-num');
        let pack_code = $(this).attr('pack-code');

        $('#id_history_ponum').val(po_num);
        $('#id_history_pack_code').val(pack_code);

        dt_oqcpreliminspectionhistory.draw();
      });

    //ON CLICK
    $(document).on('click','.btn_search_POno',function(e){
      $('#txt_search_po_number').val('');
      $('#modalScan_PO').attr('data-formid', '').modal('show');
    });

    $(document).on('keypress',function(e){
      if( ($("#modalScan_PO").data('bs.modal') || {})._isShown ){
        $('#txt_search_po_number').focus();

        if( e.keyCode == 13 && $('#txt_search_po_number').val() !='' && ($('#txt_search_po_number').val().length >= 4) ){
            $('#modalScan_PO').modal('hide');
          }
        }
    });

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

            dt_oqcpreliminspection.draw();

          },error : function(data){

              }

            }); 
        }

        dt_oqcpreliminspection.draw();
      });


    $(document).on('click','.btn_search_pack_code',function(e)
    {   
      $('#txt_search_pack_code').val('');
      $('#modal_ScanPackCode').attr('data-formid', '').modal('show');
    });

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

          OQCCheckExistPackingCode(po_num,pack_code);
          }
      });

    $(document).on('click','#btn_continue_inspection',function(e)
    {    
        let device = $('#id_modal_device').val();

        $("#id_second_point *").prop("disabled", false);
        OpenRDrawing(device);
    });

    $(document).on('click', '.btn_open_packin_history', function(e)
    {
        let po_num = $(this).attr('po-num');
        let pack_code = $(this).attr('pack-code');

        $('#id_history_ponum').val(po_num);
        $('#id_history_pack_code').val(pack_code);
    });


     $(document).on('click','#btn_save_oqc_prelim_inspection',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','#formOQCPrelimInspection').modal('show');
      });

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

    $('#formOQCPrelimInspection').submit(function(event){
      event.preventDefault();
      SubmitOQCPrelimInspection();
    });


    //----------------------------SHIPPING DETAILS ACTIONS-----------------------------//


         $(document).on('click','.btn_open_shipping_details',function(e)
        {
            shipping_po_num = $(this).attr('po-num');
            shipping_pack_code = $(this).attr('pack-code');
            shipping_lot_count = $(this).attr('lot-count');
            shipping_device = $('#id_device_name').val();

            $('#id_shipping_po_num').val(shipping_po_num);
            $('#id_shipping_pack_code').val(shipping_pack_code);
            $('#id_shipping_box_qty').val(shipping_lot_count);
            $('#id_shipping_device').val(shipping_device);

            $('#modal_shipping_details').modal('show');
        });

    $(document).on('click','#btn_save_shipping_details',function(e){
        $('#txt_shipping_number_scanner').val('');
        $('#mdl_shipping_number_scanner').attr('data-formid','#formShippingDetails').modal('show');
      });

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

    $('#formShippingDetails').submit(function(event){
      event.preventDefault();
      SubmitShippingDetails();
    });


    $('.select_packin').on('change', function() {

          let noCounter = 0;
          $('.select_packin option:selected').each(function(i, obj) {
              if( $(this).val() == 3 ){
                noCounter++;
              }
          });

          if(noCounter > 0)
          {
            $('#id_qc_judgement').val(2);
          }
          else
          {
            $('#id_qc_judgement').val(1);
          }
    });


    //--------------------------END OF SHIPPING DETAILS ACTIONS------------------------//


    //C3 LABEL CHECKER---------------------------------------------------------------//

    $('.btn_search_c3_label').on('click',function(e)
    {
      let part = $(this).attr('bt-check');
      let po_num = $('#id_modal_po_num').val();
      let pack_code = $('#id_modal_pack_code').val();
      let device = $('#id_modal_device').val();

      $('#txt_id_c3_part').val(part);
      $('#txt_id_c3_po_num').val(po_num);
      $('#txt_id_c3_pack_code').val(pack_code);

      OQCRetrieveDataFromPackCode(part,po_num,pack_code,device)
      $('#modalCheckC3').modal('show');
    });

     $(document).on('click','#id_search_c3_label',function(e)
        {   
          $('#txt_search_c3_label').val('');
          $('#modal_scanC3Label').attr('data-formid', '').modal('show');
        });

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

          $('#id_c3_scan').val('');
          var part = $('#txt_id_c3_part').val();
          var barcode = $('#txt_search_c3_label').val();
          var row = $('#id_row_number').val();

          OQCCheckC3Label(barcode,row);
          CountSuccessInTable();
          }
        });

      $(document).on('click','#btn_modal_success',function(e){

        let part = $('#txt_id_c3_part').val();

        switch(part)
        {
          case '1':
          {
            $('#id_c3_scan_lot').val('ACCEPT');
            $('#modalCheckC3').modal('hide');
            $('#btn_modal_lot').attr('disabled','disabled');
            break;
          }
          case '2':
          {
            $('#id_c3_scan_box').val('ACCEPT');
            $('#modalCheckC3').modal('hide');
            $('#btn_modal_box').attr('disabled','disabled');
            break;
          }
          case '3':
          {
            $('#id_c3_scan_accessory_1st').val('ACCEPT');
            $('#modalCheckC3').modal('hide');
            $('#btn_modal_acc_1').attr('disabled','disabled');
            break;
          }
          case '4':
          {
            $('#id_c3_scan_accessory_2nd').val('ACCEPT');
            $('#modalCheckC3').modal('hide');
            $('#btn_modal_acc_2').attr('disabled','disabled');
            break;
          }
          default:
          {
            break;
          }
        }

        if($('#id_c3_scan_lot').val() == 'ACCEPT' && $('#id_c3_scan_box').val() == 'ACCEPT' && $('#id_c3_scan_accessory_1st').val() == 'ACCEPT' && $('#id_c3_scan_accessory_2nd').val() == 'ACCEPT') 
        {
          $('#btn_continue_inspection').removeAttr('disabled');
        }

      });

    //END OF C3 LABEL CHECKER--------------------------------------------------------//


    //MODALS CLOSE ACTIONS
     $('#modalOQCPrelimInspection').on('hidden.bs.modal', function (e) {
        // and empty the modal-content element
        $("#formOQCPrelimInspection")[0].reset();
        $("#id_second_point *").prop("disabled", true);

    });

       $('#modalOQCSupervisorInspection').on('hidden.bs.modal', function (e) {
        // and empty the modal-content element
        $("#formSupervisorOQCInspection")[0].reset();
    });

      $('#modal_shipping_details').on('hidden.bs.modal', function (e) {
        // and empty the modal-content element
        $("#formShippingDetails")[0].reset();

    });

      $('#modalCheckC3').on('hidden.bs.modal', function (e) {

         $("#tblReelLotsForPackingCode tbody").remove(); 
         $("#tblReelLotsForPackingCode").append("<tbody></tbody>");
         $('#id_search_c3_label').removeAttr('disabled');
         $('#btn_modal_success').attr('disabled','disabled');

         $('#btn_modal_success').removeClass('btn-success');
         $('#btn_modal_success').addClass('btn-secondary');
         $('#id_modal_result').text();
    });

      //open oqc supervisor

        let global_po_num = '';
        let global_pack_code = '';
        let global_lot_count = '';


    $(document).on('click','.btn_open_oqc_supervisor',function(e)
    {
        global_po_num = $(this).attr('po-num');
        global_pack_code = $(this).attr('pack-code');
        global_lot_count = $(this).attr('lot-count');

        $('#txt_checksupervisor_number_scanner').val('');
        $('#mdl_checksupervisor_number_scanner').modal('show');
    });

       $(document).on('keypress',function(e){
      if( ($("#mdl_checksupervisor_number_scanner").data('bs.modal') || {})._isShown ){
        $('#txt_checksupervisor_number_scanner').focus();

        if( e.keyCode == 13 && $('#txt_checksupervisor_number_scanner').val() !='' && ($('#txt_checksupervisor_number_scanner').val().length >= 4) ){
            $('#mdl_checksupervisor_number_scanner').modal('hide');

            let employee = $('#txt_checksupervisor_number_scanner').val();
            OQCCheckIfSupervisor(employee,global_po_num,global_pack_code,global_lot_count);

          }
        }
    });


     $(document).on('click','#btn_supervisor_check_drawing',function(e){

          let device = $("#id_modal_supervisor_device").val();
          OpenRDrawing(device);
      });

       $(document).on('click','#btn_save_oqcpreliminspectionsupervisor',function(e){
        $('#txt_supervisor_number_scanner').val('');
        $('#mdl_supervisor_number_scanner').attr('data-formid','#formSupervisorOQCInspection').modal('show');
      });

       $(document).on('keypress',function(e)
       {
        if( ($("#mdl_supervisor_number_scanner").data('bs.modal') || {})._isShown )
        {
          $('#txt_supervisor_number_scanner').focus();

          if( e.keyCode == 13 && $('#txt_supervisor_number_scanner').val() !='' && ($('#txt_supervisor_number_scanner').val().length >= 4) )
            {
              $('#mdl_supervisor_number_scanner').modal('hide');

               var formid = $("#mdl_supervisor_number_scanner").attr('data-formid');

                if ( ( formid ).indexOf('#') > -1)
                {
                  $( formid ).submit();
                }

            }
          }
      });

    $(document).on('keypress',function(e){
      if( ($("#mdl_supervisor_number_scanner").data('bs.modal') || {})._isShown ){
        $('#txt_supervisor_number_scanner').focus();

        if( e.keyCode == 13 && $('#txt_supervisor_number_scanner').val() !='' && ($('#txt_supervisor_number_scanner').val().length >= 4) ){
            $('#mdl_supervisor_number_scanner').modal('hide');
          }
        }
    });

    $('#formSupervisorOQCInspection').submit(function(event){
      event.preventDefault();

        SubmitOQCInspectionSupervisor();
    });

    $('.select_packin_supervisor').on('change', function() {

          let noCounter = 0;
          $('.select_packin_supervisor option:selected').each(function(i, obj) {
              if( $(this).val() == 2 ){
                noCounter++;
              }
          });

          if(noCounter > 0)
          {
            $('#id_supervisor_qc_judgement').val(2);
          }
          else
          {
            $('#id_supervisor_qc_judgement').val(1);
          }
    });




  </script>
  @endsection
@endauth

