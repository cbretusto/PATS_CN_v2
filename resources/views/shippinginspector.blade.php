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

  @section('title', 'Shipping Inspector')

  @section('content_page')

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
            <h1>Final QC Packing Inspection</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Final QC Packing Inspection</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Search P.O.</h3>
              </div>
              <div class="card-body">
                   <div class="row">
                   

                      <div class="col-sm-2">
                      <label>Search PO Number</label>
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
                        <label>Device Boxing Quantity</label>
                          <input type="text" class="form-control" id="id_box_qty" readonly="">
                      </div>
                  </div>
                  <br>
              </div>
              </div>
            </div>
          </div>
        </div>

     
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Current Packing Code List</h3> 


              </div>

              <!-- Start Page Content -->
              <div class="card-body">
             
                <div class="table responsive">
                  <table id="tblShippingInspector" class="table table-bordered table-hover" style="width: 100%;">
                    <thead>
                      <tr>
                        <th>Action</th>
                        <th>Packing Code</th>
                        <th>Box Quantity</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>

                      </tbody>
                    </table>
                  </div>

                </div>
                <!-- !-- End Page Content -->
              </div>
              <!-- /.card -->
            </div>
          </div>
          <!-- /.row -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <div class="modal fade" id="modalShippingInspector">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">

          <div class="modal-header">
            <h4 class="modal-title"><i class="fa fa-edit"></i> Final QC Packing Inspection</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">

            <div class="row">
              <div class="col-12 col-md-12">
                <div class="card card-info card-tabs">

                  <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="packing_shipping_inspector_tabs" role="tablist">

                     <li class="nav-item">
                      <a class="nav-link active" id="custom-tabs-one-shipop-tab" data-toggle="pill" href="#custom-tabs-one-shipop" role="tab" aria-controls="custom-tabs-one-shipop" aria-selected="true">Shipping Operator</a>
                     </li>

                     <li class="nav-item">
                      <a class="nav-link" id="custom-tabs-one-packin-tab" data-toggle="pill" href="#custom-tabs-one-packin" role="tab" aria-controls="custom-tabs-one-packin" aria-selected="true">Packing Inspector</a>
                     </li>

                     <li class="nav-item">
                      <a class="nav-link" id="custom-tabs-one-packop-tab" data-toggle="pill" href="#custom-tabs-one-packop" role="tab" aria-controls="custom-tabs-one-packop" aria-selected="true">Packing Operator</a>
                     </li>

                      <li class="nav-item">
                      <a class="nav-link" id="custom-tabs-one-oqcvir-tab" data-toggle="pill" href="#custom-tabs-one-oqcvir" role="tab" aria-controls="custom-tabs-one-oqcvir" aria-selected="true">OQC Inspection</a>
                     </li>

                      <li class="nav-item">
                      <a class="nav-link" id="custom-tabs-one-oqclotapp-tab" data-toggle="pill" href="#custom-tabs-one-oqclotapp" role="tab" aria-controls="custom-tabs-one-oqclotapp" aria-selected="true">OQC Lot Application</a>
                     </li>
                    </ul>
                  </div>

                  <div class="card-body">
                    <div class="tab-content">

                        <div class="tab-pane fade show active" id="custom-tabs-one-shipop" role="tabpanel">
                        <!--OQC VIR CONTENT-->
                         <div class="row">
                            <h5><i class="fa fa-truck"></i> &nbsp;Shipping Operator History</h5>

                            <div class="table-responsive">
                              <table id="tbl_shipop_history" class="table table-bordered table-striped table-hover">

                                 <thead style="font-size:85%;">
                                    <tr align="center">
                                      <th>Shipping Operator Judgement</th>
                                      <th>Packing Code</th>
                                      <th>Inspection Date/Time</th>
                                      <th bgcolor="#FCF7DE">Check P.O. Number</th>
                                      <th bgcolor="#FCF7DE">Check Device Name</th>
                                      <th bgcolor="#FCF7DE">Check Drawing No./Rev.</th>
                                      <th bgcolor="#FCF7DE">Check U.R.P. of Box</th>
                                      <th bgcolor="#DEFDE0">Quantity (D-Label)</th>
                                      <th bgcolor="#DEFDE0">Quantity (OQC Lot App)</th>
                                      <th bgcolor="#DEFDE0">Quantity (U.R.P of Box)</th>
                                      <th bgcolor="#DEF3FD">Packing List Control No.</th>
                                      <th bgcolor="#DEF3FD">Total Shipment Quantity</th>
                                      <th bgcolor="#DEF3FD">Total Box Quantity</th>
                                    </tr>
                                  </thead>
                                  <tbody style="font-size:85%;"></tbody>
                                </table>
                                
                            </div>
                          </div>
                        <!--END OF OQC VIR CONTENT-->
                      </div>

                       <div class="tab-pane fade" id="custom-tabs-one-packin" role="tabpanel">
                        <!--OQC VIR CONTENT-->
                         <div class="row">
                            <h5><i class="fa fa-box"></i> &nbsp; Packing QC Inspection History</h5>

                            <div class="table-responsive">
                              <table id="tbl_packin_history" class="table table-bordered table-striped table-hover">

                                 <thead style="font-size:85%;">
                                    <tr align="center">
                                      <th>QC Judgement</th>
                                      <th>Packing Code</th>
                                      <th>Inspection Date/Time</th>
                                      <th>C3 Label Check</th>
                                      <th>Packing Manual Document Compliance</th>
                                      <th>Accessory Requirement</th>
                                    </tr>
                                  </thead>
                                  <tbody style="font-size:85%;"></tbody>
                                </table>
                                
                            </div>
                          </div>
                        <!--END OF OQC VIR CONTENT-->
                      </div>


                       <div class="tab-pane fade" id="custom-tabs-one-packop" role="tabpanel">
                        <!--PACKING OPERATOR CONTENT-->

                          <div class="row">
                            <h5><i class="fa fa-box"></i> &nbsp; Packing Operator History</h5>

                            <div class="table-responsive">
                              <table id="tbl_packop_history" class="table table-bordered table-striped table-hover">

                                 <thead style="font-size:85%;">
                                    <tr align="center">
                                      <th>Packing Operator Judgement</th>
                                      <th>Judgement Date/Time</th>
                                      <th>Lot Number</th>
                                      <th>Packing Type</th>
                                      <th>Unit Condition</th>
                                      <th>1.3.1 P.O. Number</th>
                                      <th>1.3.2 Device Name</th>
                                      <th>1.3.3 Quantity Per Lot</th>
                                      <th>1.3.4 Reel Lot No.</th>
                                      <th>1.3.5 Total No. of Reels</th>
                                      <th>Packing Code</th>
                                      <th>1.5.1 Orientation of Units</th>
                                      <th>1.5.2 Qty Per Box/Tray</th>
                                      <th>1.5.3 UL Sticker</th>
                                      <th>1.5.4 Silica Gel</th>
                                      <th>1.5.5 Accessories</th>
                                      <th>1.5.6 ROHS Sticker</th>
                                      
                                    </tr>
                                  </thead>
                                  <tbody style="font-size:85%;"></tbody>
                                </table>
                                
                            </div>
                          </div>
                        <!--END OF PACKING OPERATOR CONTENT-->
                      </div>

                      <div class="tab-pane fade" id="custom-tabs-one-oqcvir" role="tabpanel">
                        <!--OQC VIR CONTENT-->
                         <div class="row">
                            <h5><i class="fa fa-list"></i> &nbsp; OQC Visual Inspection Result History</h5>

                            <div class="table-responsive">
                              <table id="tbl_oqcvir_history" class="table table-bordered table-striped table-hover">

                                 <thead style="font-size:85%;">
                                    <tr align="center">
                                      <th>QC Judgement</th>
                                      <th>Lot Number</th>
                                      <th>Submission</th>
                                      <th>Sample Size/Result</th>
                                      <th>OK Qty</th>
                                      <th>NG Qty</th>
                                      <th>Inspection Date</th>
                                      <th>Inspection Start-End Time</th>
                                      <th>Accessories Requirement</th>
                                      <th>C.O.C Requirement</th>
                                      <th>Remarks</th>
                                    </tr>
                                  </thead>
                                  <tbody style="font-size:85%;"></tbody>
                                </table>
                                
                            </div>
                          </div>
                        <!--END OF OQC VIR CONTENT-->
                      </div>

                      <div class="tab-pane fade" id="custom-tabs-one-oqclotapp" role="tabpanel">
                        <!--OQC LOT APP CONTENT-->
                         <div class="row">
                            <h5><i class="fa fa-list"></i> &nbsp; OQC Lot Application History</h5>

                            <div class="table-responsive">
                              <table id="tbl_lotapp_history" class="table table-bordered table-striped table-hover">

                                 <thead style="font-size:85%;">
                                    <tr align="center">
                                      <th>Lot Number</th>
                                      <th>Lot Applied By</th>
                                      <th>Submission</th>
                                      <th>Application Date/Time</th>
                                      <th>Device Category</th>
                                      <th>Certification Lot</th>
                                      <th>Assembly Line</th>
                                      <th>Reel Lot Number</th>
                                      <th>Print Lot Number</th>
                                      <th>Total Number of Reels</th>
                                      <th>Output Quantity</th>
                                      <th>Directions</th>
                                      <th>A Drawing</th>
                                      <th>G Drawing</th>
                                      <th>Guaranteed Lot</th>
                                      <th>Problem</th>
                                      <th>Document Number</th>
                                      <th>Remarks</th>
                                      <th>Production Supervisor</th>
                                      <th>OQC Supervisor</th>
                                    </tr>
                                  </thead>
                                  <tbody style="font-size:85%;"></tbody>
                                </table>
                            </div>
                          </div>
                        <!--END OF OQC LOT APP CONTENT-->
                      </div>

                    </div>
                  </div>
                </div>
                <!--END OF TABS-->

                <form method="post" id="formShippingInspector">
                  @csrf

                  <!--SHIPPING INSPECTOR-->
                  <input type="hidden" id="id_hidden_ponum" name="name_hidden_ponum" readonly>

                  <input type="hidden" id="id_modal_packing_code" name="name_modal_packing_code" readonly>

                  <h5 class="title"><i class="fa fa-truck"></i> Shipment Schedule</h5>
                  <div class="row">

                    <div class="col-sm-4 p-2">

                      <div class="row">
                        <div class="col">
                          <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100" id="basic-addon1">Shipment Day</span>
                            </div>
                              <input type="date" class="form-control form-control-sm" id="id_shipment_date" name="name_shipment_date">
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col">
                          <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100" id="basic-addon1">Shipping Destination</span>
                            </div>
                              <input type="text" class="form-control form-control-sm" id="id_shipment_destination" name="name_shipment_destination" placeholder="Destination">
                          </div>
                        </div>
                      </div>                      

                  </div>

                  <div class="col-sm-8 p-2">

                     <div class="row">
                        <div class="col">
                          <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100" id="basic-addon1">Remarks</span>
                            </div>
                              <textarea class="form-control form-control-sm" rows="3" id="id_shipment_remarks" name="name_shipment_remarks" placeholder="Remarks / Special Instruction / UD#"></textarea>
                          </div>
                        </div>
                      </div>

                  </div>


                </div>

                  <hr>

                  <h5 class="title"><i class="fa fa-list"></i> Final QC Packing Inspection</h5>

                  <div class="row">
                    <div class="col-sm-4 p-2">

                  <h6><strong>4.1 Does the product require ROHS Sticker?</strong></h6>

                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <select class="form-control form-control-sm selectCertLot" name="name_41" id="id_41">
                          <option value = "1" selected>Yes</option>
                          <option value = "2">No</option>
                          <option value = "3">N/A</option>
                        </select>
                      </div>
                    </div>
                  </div>

                   <h6><strong>4.2 Check OQC Lot Application versus Runcard / WEB EDI Sticker and Packing List</strong></h6>

                    <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">4.2.1 PO Number</span>
                        </div>
                        <select class="form-control form-control-sm select_shipin" name="name_421" id="id_421">
                          <option value = "1" selected>Yes</option>
                          <option value = "2">No</option>
                          <option value = "3">N/A</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">4.2.2 Device Name </span>
                        </div>
                        <select class="form-control form-control-sm select_shipin" name="name_422" id="id_422">
                          <option value = "1" selected>Yes</option>
                          <option value = "2">No</option>
                          <option value = "3">N/A</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">4.2.3 Drawing No. / Rev.</span>
                        </div>
                        <select class="form-control form-control-sm select_shipin" name="name_423" id="id_423">
                          <option value = "1" selected>Yes</option>
                          <option value = "2">No</option>
                          <option value = "3">N/A</option>
                        </select>
                      </div>
                    </div>
                  </div>

                   <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">4.2.4 U.R.P. of Box</span>
                        </div>
                        <select class="form-control form-control-sm select_shipin" name="name_424" id="id_424">
                          <option value = "1" selected>Yes</option>
                          <option value = "2">No</option>
                          <option value = "3">N/A</option>
                        </select>
                      </div>
                    </div>
                  </div>

                    <h6><strong>4.3 Write Quantity on Below Items</strong></h6>

                    <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">4.3.1 D-Label</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_431" name="name_431" autocomplete="off" placeholder="e.g. 100">
                      </div>
                    </div>
                  </div>

                    <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">4.3.2 OQC Lot Application</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_432" name="name_432" autocomplete="off" placeholder="e.g. 100">
                      </div>
                    </div>
                  </div>

                   <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">4.3.3 U.R.P. of Box</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_433" name="name_433" autocomplete="off" placeholder="e.g. 100">
                      </div>
                    </div>
                  </div>

                </div>

                <div class="col-sm-4 p-2">

                  <h6><strong>4.4 Does the product require COC?</strong></h6>

                    <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                      
                        <select class="form-control form-control-sm select_shipin" name="name_44" id="id_44">
                          <option value = "1" selected>Yes</option>
                          <option value = "2">No</option>
                          <option value = "3">N/A</option>
                        </select>
                      </div>
                    </div>
                  </div>

                   <h6><strong>4.5 Check Packing List Versus WEB EDI Sticker / Casemark & Shipping Instruction</strong></h6>

                    <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">4.5.1 PO Number</span>
                        </div>
                        <select class="form-control form-control-sm select_shipin" name="name_451" id="id_451">
                          <option value = "1" selected>Yes</option>
                          <option value = "2">No</option>
                          <option value = "3">N/A</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">4.5.2 Device Name </span>
                        </div>
                        <select class="form-control form-control-sm select_shipin" name="name_452" id="id_452">
                          <option value = "1" selected>Yes</option>
                          <option value = "2">No</option>
                          <option value = "3">N/A</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">4.5.3 Total Quantity</span>
                        </div>
                        <select class="form-control form-control-sm select_shipin" name="name_453" id="id_453">
                          <option value = "1" selected>Yes</option>
                          <option value = "2">No</option>
                          <option value = "3">N/A</option>
                        </select>
                      </div>
                    </div>
                  </div>

                   <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">4.5.4 Destination</span>
                        </div>
                        <select class="form-control form-control-sm select_shipin" name="name_454" id="id_454">
                          <option value = "1" selected>Yes</option>
                          <option value = "2">No</option>
                          <option value = "3">N/A</option>
                        </select>
                      </div>
                    </div>
                  </div>

                   <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">4.5.5 Carton Box No.</span>
                        </div>
                        <select class="form-control form-control-sm select_shipin" name="name_455" id="id_455">
                          <option value = "1" selected>Yes</option>
                          <option value = "2">No</option>
                          <option value = "3">N/A</option>
                        </select>
                      </div>
                    </div>
                  </div>

                   <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">4.5.6 PMI Trans. No.</span>
                        </div>
                        <select class="form-control form-control-sm select_shipin" name="name_456" id="id_456">
                          <option value = "1" selected>Yes</option>
                          <option value = "2">No</option>
                          <option value = "3">N/A</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>


              <div class="col-sm-4 p-2">

                 <h6><strong>4.6 Checking of Actual Box Quantity endorsed to traffic</strong></h6>

                    <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">4.6.1 Packing List Control No.</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_461" name="name_461" autocomplete="off" placeholder="e.g. 100">
                      </div>
                    </div>
                  </div>

                    <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">4.6.2 Total Shipment Qty.</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_462" name="name_462" autocomplete="off" placeholder="e.g. 100">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">4.6.3 Total Box Qty.</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_463" name="name_463" autocomplete="off" placeholder="e.g. 100">
                      </div>
                    </div>
                  </div>

                  <h6><strong>4.7 Does the casemark sticker has corresponding OQC Stamp as evidence that QC Completed the checking of product on Master Box?</strong></h6>

                    <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <select class="form-control form-control-sm select_shipin" name="name_47" id="id_47">
                          <option value = "1" selected>Yes</option>
                          <option value = "2">No</option>
                          <option value = "3">N/A</option>
                        </select>
                      </div>
                    </div>
                  </div>


                  <hr>
                      
                    <br><br>

                    <h6><strong>SHIPPING QC JUDGEMENT</strong></h6>

                   <div class="row">
                    <div class="col">
                     <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text primary w-100" style="background-color: rgb(57, 179, 215); color: rgb(255, 255, 255); border-color: rgb(57, 179, 215);" id="basic-addon1"><strong>JUDGEMENT</strong></span>
                        </div>
                        <select class="form-control form-control-sm selectCertLot" name="name_shipin_judgement" id="id_shipin_judgement" readonly>
                          <option disabled>---</option>
                          <option value = "1" selected>ACCEPT</option>
                          <option value = "2">REJECT</option>
                        </select>
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
            <button type="button" class="btn btn-success" id="btn_save_shipin">Save</button>
          </div>

        </div>
      </div>
    </div>

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
              <br><br>
              <h1><i class="fa fa-qrcode fa-lg"></i></h1>
            </div>
            <input type="text" id="txt_employee_number_scanner" name="employee_number_scanner" class="hidden_scanner_input">
          </div>
        </div>
      </div>
    </div>
    <!-- /.Modal -->

  </form> <!--END OF SHIPPING INSPECTOR-->

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

  <div class="modal fade" id="modalShippingInspectorHistory">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">

         <div class="modal-header">
            <h4 class="modal-title"><i class="fa fa-history"></i> Final QC Packing Inspection History</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">

              <input type="hidden" id="id_history_ponum" name="name_history_ponum" readonly>
              <input type="hidden" id="id_history_pack_code" name="name_history_pack_code" readonly>

             <div class="row">
                              <div class="table-responsive">
                                 <table id="tbl_shipin_history" class="table table-bordered table-striped table-hover">

                                 <thead style="font-size:85%;">
                                    <tr align="center">
                                      <th>Shipping Inspector Judgement</th>
                                      <th>Packing Code</th>
                                      <th>Inspection Date/Time</th>
                                      <th>ROHS Sticker Requirement</th>
                                      <th bgcolor="#FCF7DE">Check P.O. Number</th>
                                      <th bgcolor="#FCF7DE">Check Device Name</th>
                                      <th bgcolor="#FCF7DE">Check Drawing No./Rev.</th>
                                      <th bgcolor="#FCF7DE">Check U.R.P. of Box</th>
                                      <th bgcolor="#DEFDE0">Quantity (D-Label)</th>
                                      <th bgcolor="#DEFDE0">Quantity (OQC Lot App)</th>
                                      <th bgcolor="#DEFDE0">Quantity (U.R.P of Box)</th>

                                      <th>C.O.C. Requirement</th>

                                      <th bgcolor="#FDDFDF">Check P.O. Number</th>
                                      <th bgcolor="#FDDFDF">Check Device Name</th>
                                      <th bgcolor="#FDDFDF">Total Quantity</th>
                                      <th bgcolor="#FDDFDF">Destination</th>
                                      <th bgcolor="#FDDFDF">Carton Box No.</th>
                                      <th bgcolor="#FDDFDF">PMI Transaction No.</th>

                                      <th bgcolor="#F0DEFD">Packing List Control No.</th>
                                      <th bgcolor="#F0DEFD">Total Shipment Quantity</th>
                                      <th bgcolor="#F0DEFD">Total Box Quantity</th>

                                      <th>OQC Stamp Correspondence</th>
                                    </tr>
                                  </thead>
                                  <tbody style="font-size:85%;"></tbody>
                                </table>
                              </div>
                </div> 
       
          </div> <!--Modal Body-->
      </div>
    </div>
  </div>  
 
@endsection

  @section('js_content')
  <script type="text/javascript">

    let dt_shippinginspector;

    let dt_shipop_history;
    let dt_packin_history;
    let dt_packop_history;
    let dt_oqcvir_history;
    let dt_lotapp_history;

    let dt_shipin_history; 

     $(document).ready(function () {
      bsCustomFileInput.init();

     });

    dt_shippingoperator =  $("#tblShippingInspector").DataTable(
      {
        "processing":true,
        "serverSide":true,

        "ajax" : {
          url: "shipin_view_batches",
          data: function (param)
          {
            param.po_number = $("#txt_search_po_number").val();
          }
        },

        "columns":[
        { "data" : "action", orderable:false, searchable:false },
        { "data" : "pack_code" },
        { "data" : "total_box_qty"},
        { "data" : "status" }
        ]

      });//end of datatable of summary


       dt_shipin_history = $("#tbl_shipin_history").DataTable({
            "processing"    : false,
            "serverSide"  : true,
            "ajax"        : {
              url: "view_shipin_history_by_packing_code",
                data: function (param){
                
            param.po_number = $("#id_history_ponum").val();
            param.packing_code = $("#id_history_pack_code").val();
              }
            },
            
            "columns":[
              { "data" : "shipin_judgement" },
              { "data" : "shipin_pack_code" },
              { "data" : "shipin_datetime" },
              { "data" : "shipin_rohs" },
              { "data" : "shipin_check_ponum" },
              { "data" : "shipin_check_device" },
              { "data" : "shipin_check_drawing" },
              { "data" : "shipin_check_urp" },
              { "data" : "shipin_dlabel_qty" },
              { "data" : "shipin_lotapp_qty" },
              { "data" : "shipin_urp_qty" },
              { "data" : "shipin_coc" },

              { "data" : "shipin_ponum" },
              { "data" : "shipin_device" },
              { "data" : "shipin_qty" },
              { "data" : "shipin_destination" },
              { "data" : "shipin_carton" },
              { "data" : "shipin_transaction" },

              { "data" : "shipin_plcn" },
              { "data" : "shipin_tsq" },
              { "data" : "shipin_tbq" },
              { "data" : "shipin_oqc_correspondence" },
            ],

        });//end of dataTable of summary


         $(document).on('click','.btn_open_shipin_history',function(e)
         {
              let packing_code = $(this).attr('packing-code');
              let po_num = $(this).attr('po-num');

              $('#id_history_ponum').val(po_num);
              $('#id_history_pack_code').val(packing_code);

              dt_shipin_history.draw();

         });

   
        dt_shipop_history = $("#tbl_shipop_history").DataTable({
            "processing"    : false,
            "serverSide"  : true,
            "ajax"        : {
              url: "view_shipop_history_by_packing_code",
                data: function (param){
                
            param.po_number = $("#id_hidden_ponum").val();
            param.packing_code = $("#id_modal_packing_code").val();
              }
            },
            
            "columns":[
              { "data" : "shipop_judgement" },
              { "data" : "shipop_pack_code" },
              { "data" : "shipop_datetime" },
              { "data" : "shipop_ponum" },
              { "data" : "shipop_device" },
              { "data" : "shipop_drawing" },
              { "data" : "shipop_urp" },
              { "data" : "shipop_dlabel_qty" },
              { "data" : "shipop_lotapp_qty" },
              { "data" : "shipop_urp_qty" },
              { "data" : "shipop_plcn" },
              { "data" : "shipop_tsq" },
              { "data" : "shipop_tbq" }
            ],

        });//end of dataTable of summary

         dt_packin_history =  $("#tbl_packin_history").DataTable(
      {
        "processing":true,
        "serverSide":true,

        "ajax" : {
          url: "view_packin_history_by_packing_code",
          data: function (param)
          {
            param.po_number = $("#id_hidden_ponum").val();
            param.packing_code = $("#id_modal_packing_code").val();
          } 
        },

        "columns":[
        { "data" : "packin_judgement", orderable:false, searchable:false },
        { "data" : "packin_pack_code" },
        { "data" : "packin_datetime" },
        { "data" : "packin_c3_check" },
        { "data" : "packin_doc_compliance" },
        { "data" : "packin_accessory" }
        ]

      });//end of datatable of packop history

    dt_packop_history =  $("#tbl_packop_history").DataTable(
      {
        "processing":true,
        "serverSide":true,

        "ajax" : {
          url: "view_packop_history_by_packing_code",
          data: function (param)
          {
            param.po_number = $("#id_hidden_ponum").val();
            param.packing_code = $("#id_modal_packing_code").val();
          }
        },

        "columns":[
        { "data" : "packop_judgement", orderable:false, searchable:false },
        { "data" : "packop_datetime" },
        { "data" : "packop_lot_no" },
        { "data" : "packop_packing_type" },
        { "data" : "packop_unit_condition" },
        { "data" : "packop_po_num" },
        { "data" : "packop_device_name" },
        { "data" : "packop_qty_per_lot" },
        { "data" : "packop_reel_lot_no" },
        { "data" : "packop_total_num_reels" },
        { "data" : "packop_packing_code" },
        { "data" : "packop_orientation_of_units" },
        { "data" : "packop_qty_per_box" },
        { "data" : "packop_ul_sticker" },
        { "data" : "packop_silica_gel" },
        { "data" : "packop_accessories" },
        { "data" : "packop_rohs_sticker" }
        ]

      });//end of datatable of packop history


      dt_oqcvir_history =  $("#tbl_oqcvir_history").DataTable(
      {
        "processing":true,
        "serverSide":true,

        "ajax" : {
          url: "view_oqcvir_history_by_packing_code",
          data: function (param)
          {
            param.po_number = $("#id_hidden_ponum").val();
            param.packing_code = $("#id_modal_packing_code").val();
          }
        },

        "columns":[
        { "data" : "oqcvir_qc_judgement", orderable:false, searchable:false },
        { "data" : "oqcvir_lot_no" },
        { "data" : "oqcvir_submission" },
        { "data" : "oqcvir_sample_size" },
        { "data" : "oqcvir_ok_qty" },
        { "data" : "oqcvir_ng_qty" },
        { "data" : "oqcvir_insp_date" },
        { "data" : "oqcvir_insp_time" },
        { "data" : "oqcvir_accessories" },
        { "data" : "oqcvir_coc" },
        { "data" : "oqcvir_remarks" }
        ]

      });//end of datatable of oqcvir history


      dt_lotapp_history =  $("#tbl_lotapp_history").DataTable(
      {
        "processing":true,
        "serverSide":true,

        "ajax" : {
          url: "view_lotapp_history_by_packing_code",
          data: function (param)
          {
            param.po_number = $("#id_hidden_ponum").val();
            param.packing_code = $("#id_modal_packing_code").val();
          }
        },

        "columns":[
        { "data" : "lotapp_lot_no", orderable:false, searchable:false },
        { "data" : "lotapp_applied_by" },
        { "data" : "lotapp_submission" },
        { "data" : "lotapp_datetime" },
        { "data" : "lotapp_device_cat" },
        { "data" : "lotapp_cert_lot" },
        { "data" : "lotapp_assy_line" },
        { "data" : "lotapp_reel_lot" },
        { "data" : "lotapp_print_lot" },
        { "data" : "lotapp_ttl_reel" },
        { "data" : "lotapp_output_qty" },
        { "data" : "lotapp_direction" },
        { "data" : "lotapp_adrawing" },
        { "data" : "lotapp_gdrawing" },
        { "data" : "lotapp_guaranteed_lot" },
        { "data" : "lotapp_problem" },
        { "data" : "lotapp_doc_no" },
        { "data" : "lotapp_remarks" },
        { "data" : "lotapp_prod_supervisor" },
        { "data" : "lotapp_oqc_supervisor" }
        ]

      });//end of datatable of oqcvir history


       $(document).on('click','.btn_update_shipping_inspector',function(e){

          /*alert('burtoy');
       */
          let po_num = $(this).attr('po-num');
          let packing_code = $(this).attr('packing-code');

           $('#id_hidden_ponum').val(po_num);
           $('#id_modal_packing_code').val(packing_code);

          dt_shipop_history.draw();
          dt_packin_history.draw();
          dt_packop_history.draw();
          dt_oqcvir_history.draw();
          dt_lotapp_history.draw();
      });


      $(document).on('keyup','#txt_search_po_number',function(e){
        if( e.keyCode == 13 ){

          $('#id_po_no').val('');
          $('#id_device_name').val('');
          $('#id_po_qty').val('');
          $('#id_box_qty').val('');

          var data = {
          'po'      : $('#txt_search_po_number').val()
          }

          dt_shippingoperator.draw();


          data = $.param(data);
        $.ajax({
          type      : "get",
          dataType  : "json",
          data      : data,
          url       : "get_po_details",
          success : function(data){
            
            //$('#txt_search_po_number').val('');

            $('#id_po_no').val( data['po_details'][0]['po_no'] );
            $('#id_device_name').val( data['po_details'][0]['wbs_kitting']['device_name'] );
            $('#id_po_qty').val( data['po_details'][0]['wbs_kitting']['po_qty'] );
            $('#id_box_qty').val( data['po_details'][0]['wbs_kitting']['device_info']['ship_boxing'] );

          },error : function(data){

              }

            }); 
        }
      });


     $(document).on('click','#btn_save_shipin',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','#formShippingInspector').modal('show');
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

    $('#formShippingInspector').submit(function(event){

      event.preventDefault();

      SubmitShipin();

    });



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

  </script>
  @endsection


@endauth