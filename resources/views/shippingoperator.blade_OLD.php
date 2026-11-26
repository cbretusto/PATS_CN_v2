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

  @section('title', 'Packing Operator')

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
            <h1>Final Production Packing Inspection</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Final Production Packing Inspection</li>
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
                    <!-- <div class="col-sm-3">
                      <label>Search PO Number</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fa fa-search"></i></span>
                        </div>
                        <input type="text" class="form-control" id="txt_search_po_number" placeholder="Scan PO Number Here">
                      </div>
                    </div>
                    <div class="col-sm-1">
                    </div>
                     <div class="col-sm-2">
                        <label>Current P.O. Number</label>
                          <input type="text" class="form-control" id="id_po_no" readonly="">
                      </div> -->
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
                <h3 class="card-title">Current P.O. Lot Application List</h3>  <button type="button" class="px-2 py-1 btn btn-sm btn-info btn_update_shipping_operator hidden_scanner_input" data-toggle="modal" data-target="#modalShippingOperator" title="Update Details" po-num="1" batch-num="1" mat-sub="1" oqclotapp-id="1"><i class="fa fa-edit fa-sm"></i></button>
              </div>

          

              <!-- Start Page Content -->
              <div class="card-body">

                <div class="table responsive">
                  <table id="tblShippingOperator" class="table table-bordered table-hover" style="width: 100%;">
                    <thead>
                      <tr>
                        <th>Action</th>
                        <th>Lot Application Number</th>
                        <th>Applied Lot Quantity</th>
                        <th>Packing Code</th>
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


    <div class="modal fade" id="modalShippingOperator">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">

          <div class="modal-header">
            <h4 class="modal-title"><i class="fa fa-edit"></i> Final Production Packing Inspection</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <!--START OF TABS-->

           <div class="row">
          <div class="col-12 col-md-12">
            <div class="card card-primary card-tabs">
              <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-one-packin-tab" data-toggle="pill" href="#custom-tabs-one-packin" role="tab" aria-controls="custom-tabs-one-packin" aria-selected="true">Packing Inspector</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-one-packop-tab" data-toggle="pill" href="#custom-tabs-one-packop" role="tab" aria-controls="custom-tabs-one-packop" aria-selected="true">Packing Operator</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">OQC Inspection</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">OQC Lot Application</a>
                  </li>
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                   <div class="tab-pane fade show active" id="custom-tabs-one-packin" role="tabpanel" aria-labelledby="custom-tabs-one-packin-tab">       
                     <!--PACKING INSPECTOR-->

                        <div class="row">
                            <h5><i class="fa fa-box"></i> &nbsp; Packing Inspector History</h5>
                             <br> 
                             <br> 
                              <div class="table-responsive">
                                <table id="tbl_packin_history" class="table table-bordered table-striped table-hover" style="min-width: 1500px!important;">
                                  <thead style="font-size:85%;">
                                    <tr align="center">
                                      <th>QC Judgement and Stamp</th>
                                      <th>Inspection Date/Time</th>
                                      <th>OQC Inspected By</th>
                                      <th>2.2.1 P.O. Number</th>
                                      <th>2.2.2 Device Name</th>
                                      <th>2.2.3 Quantity Per Lot</th>
                                      <th>2.2.4 Reel Lot No.</th>
                                      <th>2.2.5 Total No. of Reels</th>
                                      <th>Packing Condition Compliance</th>
                                      <th>Accessory Requirements</th>
                                      <th>Packing Code</th>
                                      
                                    </tr>
                                  </thead>
                                  <tbody style="font-size:85%;"></tbody>
                                </table>
                              </div>
                </div>            
                      
                     <!--PACKING INSPECTOR-->
                  </div>
                  <div class="tab-pane fade" id="custom-tabs-one-packop" role="tabpanel" aria-labelledby="custom-tabs-one-packop-tab">     
                    <!--PACKING OPERATOR-->

                     <div class="row">
                            <h5><i class="fa fa-box"></i> &nbsp; Packing Operator History</h5>
                             <br> 
                             <br> 
                              <div class="table-responsive">
                                <table id="tbl_packop_history" class="table table-bordered table-striped table-hover" style="min-width: 1500px!important;">
                                  <thead style="font-size:85%;">
                                    <tr align="center">
                                      <th>Packing Operator Judgement</th>
                                      <th>Judgement Date/Time</th>
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

                    <!--END OF PACKING OPERATOR-->
                  </div>
                  <div class="tab-pane fade" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                     
                       <!--OQC INSPECTION MODULE TABLE-->
                       <div class="row">
                            <div class="col-sm-12 p-2">
                              <h4><i class="fa fa-list"></i> &nbsp; OQC Lot Application</h4> 
                              <br> 
                                <div class="table-responsive">
                                  <table id="tblOQCVIR_summary" class="table table-bordered table-striped table-hover" style="min-width: 1500px!important;">
                                    <thead style="font-size:85%;">
                                      <tr align="center">
                                        <th>Submission</th>
                                        <th>Sample Size/Result</th>
                                        <th>OK Qty</th>
                                        <th>NG Qty</th>
                                        <th>Insp. Date</th>
                                        <th>Insp. Start-End Time</th>
                                        <th>Inspector Name/Stamp</th>
                                        <!--
                                        <th>Inspector Stamp</th>
                                        -->
                                        <th>Accessories Req.</th>
                                        <th>C.O.C Req.</th>
                                        <th>Result</th>
                                        <th>Judgement</th>
                                        <th>Remarks</th>
                                      </tr>
                                    </thead>
                                    <tbody style="font-size:85%;"></tbody>
                                  </table>
                                </div>
                            </div>
                          </div>

                     <!--END OF OQC INSPECTION MODULE TABLE-->
                  </div>
                  <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                    
                    <!--OQC LOT APPLICATION-->

                    <h4><i class="fa fa-list"></i> &nbsp; OQC Lot Application</h4> 

                  <div class="row">   

                  <div class="col-sm-4 p-2">

                      <input type="hidden" class="form-control form-control-sm" id="id_oqclotapp" name="name_oqclotapp" disabled>

                      <div class="row">
                        <div class="col">
                          <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100" id="basic-addon1">Current PO Number</span>
                            </div>
                            <input type="text" class="form-control form-control-sm" id="id_currentPONo" name="name_po_no" disabled>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col">
                          <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100" id="basic-addon1">Lot/Batch No.</span>
                            </div>
                            <input type="text" class="form-control form-control-sm" id="id_LotBatch" name="name_LotBatch" disabled>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col">
                          <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100" id="basic-addon1">Lot Quantity</span>
                            </div>
                            <input type="text" class="form-control form-control-sm" id="id_LotQty" name="name_LotQty" disabled>
                          </div>
                        </div>
                      </div>
                       <div class="row">
                        <div class="col">
                          <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100" id="basic-addon1">Output Quantity</span>
                            </div>
                            <input type="text" class="form-control form-control-sm" id="id_OutputQty" name="name_OutputQty" disabled>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col">
                          <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100" id="basic-addon1">A/G Drawing No./Revision</span>
                            </div>
                            <input type="text" class="form-control form-control-sm" id="id_Drawing" name="name_Drawing" disabled>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col">
                          <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100" id="basic-addon1">Application Date</span>
                            </div>
                            <input type="text" class="form-control form-control-sm" id="id_AppDate" name="name_AppDate" disabled>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col">
                          <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100" id="basic-addon1">Application Time</span>
                            </div>
                            <input type="text" class="form-control form-control-sm" id="id_AppTime" name="name_AppTime" disabled>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col">
                          <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100" id="basic-addon1">Production Supervisor</span>
                            </div>
                            <input type="text" class="form-control form-control-sm" id="id_prodn_supv" name="name_prodn_supv" autocomplete="off" disabled>
                          </div>
                        </div>
                      </div>
                    </div>

                   <div class="col-sm-4 p-2">
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Device Category</span>
                        </div>
                        <select class="form-control form-control-sm selectDevice" name="name_select_Device" id="id_select_Device" disabled>
                          <option value = "0" selected disabled>---</option>
                          <option value = "1">Automotive</option>
                          <option value = "2">Non-Automotive</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Certification Lot</span>
                        </div>
                        <select class="form-control form-control-sm selectCertLot" name="name_CertLot" id="id_CertLot" disabled>
                          <option value = "0" selected disabled>---</option>
                          <option value = "6">N/A</option>
                          <option value = "1">New Operator</option>
                          <option value = "2">New product/model</option>
                          <option value = "3">Evaluation lot</option>
                          <option value = "4">Re-inspection</option>
                          <option value = "5">Flexibility</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Assembly Line</span>
                        </div>
                        <input class="form-control form-control-sm" name="name_AssyLine" id="id_AssyLine" disabled>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Reel Lot No.</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_ReelNo" name="name_ReelNo" autocomplete="off" disabled>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Print Lot No.</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_PrintLotNo" name="name_PrintLotNo" autocomplete="off" disabled>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Total No. of Reels</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_TtlNoReels" name="name_TtlNoReels" autocomplete="off" disabled>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Urgent Directions No.</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_UrgentDirection" name="name_UrgentDirection" autocomplete="off" disabled>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">OQC Supervisor</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_oqc_supv" name="name_oqc_supv" autocomplete="off" disabled>
                      </div>
                    </div>
                  </div>
              </div>

               <div class="col-sm-4 p-2">
                   <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Guaranteed Lot</span>
                        </div>
                        <select class="form-control form-control-sm" name="name_GuaranteedLot" id="id_GuaranteedLot" disabled>
                          <option value = "0" selected>---</option>
                          <option value = "1">With</option>
                          <option value = "2">Without</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Problem</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_Problem" name="name_Problem" autocomplete="off" disabled>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Document No.</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_DocNo" name="name_DocNo" autocomplete="off" disabled>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Lot Applied By</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_search_name" name="name_search_name" autocomplete="off" disabled>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Remarks</span>
                        </div>
                        <textarea class="form-control form-control-sm" rows="3" id="id_OQCRemarks" name="name_OQCRemarks" disabled></textarea>
                      </div>
                    </div>
                  </div>             
              </div>

            </div>
                    <!--END OF OQC LOT APPLICATION-->


                  </div>        
                </div>
              </div>
              <!-- /.card -->
            </div>
          </div>
        </div>
         <!--end of tabs-->

          <!--END OF TABS-->

         <form method="post" id="formShippingOperator">
              @csrf


            <!--SEARCH PACK CODE-->
          <div class="row">

            <div class="col-sm-4 p-2">

            </div>

            <div class="col-sm-4 p-2">

            </div>

            <div class="col-sm-4 p-2">

                    <div class="col">
                      <div class="input-group input-group-sm mb-3">

                         <div class="input-group-prepend w-40">
                          <span class="input-group-text w-100" style="background-color: rgb(66,139,202); color: rgb(255, 255, 255); border-color: rgb(57, 179, 215);" id="basic-addon1">Link to Packing Code:</span>
                        </div>

                        <input type="text" class="form-control form-control-sm" id="id_link_packcode_shipop" name="name_link_packcode_shipop" autocomplete="off" placeholder="" readonly>

                        <span class="input-group-append">

                            <!-- <button type="button" class="btn btn-primary btn-flat btn_link_to_packcode_shipop" title="Link to Packing Code"><i class="fa fa-link"></i></button> -->
                        </span>
                      </div>
                    </div>
            </div>

          </div>
         <!--END OF SEARCH PACK CODE-->

              <div class="modal-body">

              <div class="row">

              <input type="hidden" id="id_myponum" name="name_myponum" readonly>

              <input type="hidden" id="id_mybatch" name="name_mybatch" readonly>

              <input type="hidden" id="id_mysub" name="name_mysub">

              <input type="hidden" id="id_mylotapp" name="name_mylotapp" readonly>

              </div>

              <!--SHIPPING OPERATOR-->

              <div class="row">   

                   <div class="col-sm-4 p-2">

                  <h6><strong>3.1 Check OQC Lot Application Versus Runcard / WEB EDI Sticker and Packing List</strong></h6>

                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">3.1.1 PO Number</span>
                        </div>
                        <select class="form-control form-control-sm select_shipop" name="name_311" id="id_311">
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
                          <span class="input-group-text w-100" id="basic-addon1">3.1.2 Device Name </span>
                        </div>
                        <select class="form-control form-control-sm select_shipop" name="name_312" id="id_312">
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
                          <span class="input-group-text w-100" id="basic-addon1">3.1.3 Drawing No. / Rev.</span>
                        </div>
                        <select class="form-control form-control-sm select_shipop" name="name_313" id="id_313">
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
                          <span class="input-group-text w-100" id="basic-addon1">3.1.4 U.R.P. of Box</span>
                        </div>
                        <select class="form-control form-control-sm select_shipop" name="name_314" id="id_314">
                          <option value = "1" selected>Yes</option>
                          <option value = "2">No</option>
                          <option value = "3">N/A</option>
                        </select>
                      </div>
                    </div>
                  </div>

                   <h6><strong>3.2 Quantity Written on Below Items</strong></h6>

                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">3.2.1 WEB EDI Sticker</span>
                        </div>
                    
                        <input type="text" class="form-control form-control-sm" id="id_321" name="name_321" autocomplete="off" placeholder="e.g. 100">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">3.2.2 OQC Lot Application </span>
                        </div>
                       
                        <input type="text" class="form-control form-control-sm" id="id_322" name="name_322" autocomplete="off" placeholder="e.g. 100">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">3.2.3 U.R.P of Box</span>
                        </div>
                       
                        <input type="text" class="form-control form-control-sm" id="id_323" name="name_323" autocomplete="off" placeholder="e.g. 100">
                      </div>
                    </div>
                  </div>

              </div>

               <div class="col-sm-4 p-2">

                   <h6><strong>3.3 Packing List Control No.</strong></h6>

                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">                      
                        <input type="text" class="form-control form-control-sm" id="id_plcn" name="name_plcn" autocomplete="off" placeholder="e.g. 100">
                      </div>
                    </div>
                  </div>

                     <h6><strong>3.4 Total Shipment Quantity</strong></h6>

                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">                      
                        <input type="text" class="form-control form-control-sm" id="id_tsq" name="name_tsq" autocomplete="off" placeholder="e.g. 100">
                      </div>
                    </div>
                  </div>

                    <h6><strong>3.5 Total Box Quantity</strong></h6>

                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">                      
                        <input type="text" class="form-control form-control-sm" id="id_tbq" name="name_tbq" autocomplete="off" placeholder="e.g. 100">
                      </div>
                    </div>
                  </div>
              </div>


              <div class="col-sm-4 p-2">

                <br><br><br><br><br><br><br><br><br><br><br>
                 <h6><strong>Shipping Operator Judgement</strong></h6><hr>
                    
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text primary w-100" style="background-color: rgb(57, 179, 215); color: rgb(255, 255, 255); border-color: rgb(57, 179, 215);" id="basic-addon1"><strong>JUDGEMENT</strong></span>
                        </div>
                        <select class="form-control form-control-sm selectCertLot" name="name_shipop_judgement" id="id_shipop_judgement" readonly>
                          <option disabled>---</option>
                          <option value = "1" selected="">ACCEPT</option>
                          <option value = "2">REJECT</option>
                        </select>
                      </div>
                    </div>

                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-success" id="btn_save_shipop">Save</button>
                    </div>

                    </div>

                  
              </div>


              <!--SHIPPING OPERATOR-->    

              <hr>

              <div class="row">
                            <h5><i class="fa fa-box"></i> &nbsp; Shipping Operator History</h5>
                             <br> 
                             <br> 
                              <div class="table-responsive">
                                <table id="tbl_shipop_history" class="table table-bordered table-striped table-hover" style="min-width: 1500px!important;">
                                  <thead style="font-size:85%;">
                                    <tr align="center">
                                      <th>Shipping Operator</th>
                                      <th>Inspection Date/Time</th>

                                      <th>3.1.1 P.O. Number</th>
                                      <th>3.1.2 Device Name</th>
                                      <th>3.1.3 Drawing Number / Revision</th>
                                      <th>3.1.4 U.R.P. of Box</th>

                                      <th>3.2.1 D-Label</th>
                                      <th>3.2.2 OQC Lot Application</th>
                                      <th>3.2.3 U.R.P. of Box</th>
                                      
                                      <th>Packing List Control No.</th>
                                      <th>Total Shipment Quantity</th>
                                      <th>Total Box Quantity</th>

                                      
                                    </tr>
                                  </thead>
                                  <tbody style="font-size:85%;"></tbody>
                                </table>
                              </div>
                </div>         


              </div> <!--Modal Body-->

        </div> <!--Modal Content-->
      </div> <!--Modal Dialog-->
    </div> <!--Modal-->
    <!-----------------------END OF MODAL--------------------------->

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
              <!-- <h1><i class="fa fa-barcode fa-lg"></i></h1> -->
              <h1><i class="fa fa-qrcode fa-lg"></i></h1>
            </div>
            <input type="text" id="txt_employee_number_scanner" name="employee_number_scanner" class="hidden_scanner_input">
          </div>
  <!--         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div> -->
        </div>
      </div>
    </div>
    <!-- /.Modal -->

</form> <!--FORM PRELIM SHIPPING OPERATOR-->


       <!--SHIPIN HISTORY MODAL-->

   <div class="modal fade" id="modalShippingOperatorHistory">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">

          <div class="modal-header">
            <h4 class="modal-title"><i class="fa fa-edit"></i> Final Production Packing Inspection History</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">

              <input type="hidden" id="id_myponum2" name="name_myponum2" readonly>

              <input type="hidden" id="id_mybatch2" name="name_mybatch2" readonly>

              <input type="hidden" id="id_mysub2" name="name_mysub2" readonly>

              <input type="hidden" id="id_mylotapp2" name="name_mylotapp2" readonly>

            <div class="row">
                            <h5><i class="fa fa-box"></i> Final Production Packing History</h5>
                             <br> 
                             <br> 
                              <div class="table-responsive">
                                <table id="tbl_shipop_history_only" class="table table-bordered table-striped table-hover" style="min-width: 1500px!important;">
                                  <thead style="font-size:85%;">
                                    <tr align="center">
                                      <th>Shipping Operator</th>
                                      <th>Inspection Date/Time</th>

                                      <th>3.1.1 P.O. Number</th>
                                      <th>3.1.2 Device Name</th>
                                      <th>3.1.3 Drawing Number / Revision</th>
                                      <th>3.1.4 U.R.P. of Box</th>

                                      <th>3.2.1 D-Label</th>
                                      <th>3.2.2 OQC Lot Application</th>
                                      <th>3.2.3 U.R.P. of Box</th>
                                      
                                      <th>Packing List Control No.</th>
                                      <th>Total Shipment Quantity</th>
                                      <th>Total Box Quantity</th>
                                      
                                    </tr>
                                  </thead>
                                  <tbody style="font-size:85%;"></tbody>
                                </table>
                              </div>
                </div>         

       
          </div> <!--Modal Body-->

        </div> <!--Modal Content-->
      </div> <!--Modal Dialog-->
    </div> <!--Modal-->


      <!--END OF SHIPIN HISTORY MODAL-->

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



  @endsection

  @section('js_content')
  <script type="text/javascript">

    $('#id_shipop_judgement').css("pointer-events","none");

    let dt_shippingoperator;
    let dataTableOQCVIR_summary;

    let dt_packop_history;
    let dt_packin_history;
    let dt_shipop_history;
    let dt_shipop_history_only;

      $(document).ready(function () {
      bsCustomFileInput.init();

       dt_shippingoperator = $("#tblShippingOperator").DataTable(
      {
        "processing":true,
        "serverSide":true,

        "ajax" : {
          url: "shipop_view_batches",
          data: function (param)
          {
            param.po_number = $("#txt_search_po_number").val();
          }
        },

        "columns":[
        { "data" : "action", orderable:false, searchable:false },
        { "data" : "lotapp" },
        { "data" : "lotqty" },
        { "data" : "packing_code" },
        { "data" : "status" }
        ]

      });//end of datatable of summary
    dataTableOQCVIR_summary = $("#tblOQCVIR_summary").DataTable({
            "processing"    : false,
            "serverSide"  : true,
            "ajax"        : {
              url: "get_oqcvir_summary",
                data: function (param){
                param.fkid_oqclotapp = $("#id_oqclotapp").val();
              }
            },
            
            "columns":[
              { "data" : "sub_raw" },
              { "data" : "oqc_sample" },
              { "data" : "okqty_raw" },
              { "data" : "ngqty_raw" },
              { "data" : "insp_date_raw" },
              { "data" : "insp_setime_raw" },
              { "data" : "insp_name_raw" },
              { "data" : "acc_req_raw" },
              { "data" : "coc_req_raw" },
              { "data" : "result_raw" },
              { "data" : "judgement_raw" },
              { "data" : "remarks" }
            ],
        });//end of dataTable of summary

     
          dt_packop_history = $("#tbl_packop_history").DataTable({
            "processing"    : false,
            "serverSide"  : true,
            "order": [[ 2, "desc" ]],
            "ajax"        : {
              url: "retrieve_packop_history",
            data: function (param){
                  
                  param.po_num = $("#id_myponum").val();
                  param.batch =  $("#id_mybatch").val();
                  param.oqclotapp_id = $("#id_mylotapp").val();              
              }
            },
            
            "columns":[
              { "data" : "packop_name" },
              { "data" : "packop_date" },
              { "data" : "pack_type" },
              { "data" : "unit_condition" },
              { "data" : "1_3_1" },
              { "data" : "1_3_2" },
              { "data" : "1_3_3" },
              { "data" : "1_3_4" },
              { "data" : "1_3_5" },
              { "data" : "pack_code" },
              { "data" : "1_5_1" },
              { "data" : "1_5_2" },
              { "data" : "1_5_3" },
              { "data" : "1_5_4" },
              { "data" : "1_5_5" },
              { "data" : "1_5_6" },

            ],
        });//end of dataTable of summary

          dt_packin_history = $("#tbl_packin_history").DataTable({
            "processing"    : false,
            "serverSide"  : true,
            "order": [[ 2, "desc" ]],
            "ajax"        : {
              url: "retrieve_packin_history",
                data: function (param){
                
                  param.po_num = $("#id_myponum").val();
                  param.batch =  $("#id_mybatch").val();
                  param.oqclotapp_id = $("#id_mylotapp").val();    
              }
            },
            
            "columns":[
              { "data" : "packin_stamp" },
              { "data" : "packin_datetime" },
              { "data" : "oqc_inspected_by" },
              { "data" : "2_2_1" },
              { "data" : "2_2_2" },
              { "data" : "2_2_3" },
              { "data" : "2_2_4" },
              { "data" : "2_2_5" },
              { "data" : "pack_condition" },
              { "data" : "accessory_requirement" },
              { "data" : "pack_code" },

            ],

        });//end of dataTable of summary


        dt_shipop_history = $("#tbl_shipop_history").DataTable({
            "processing"    : false,
            "serverSide"  : true,
            "order": [[ 2, "desc" ]],
            "ajax"        : {
              url: "retrieve_shipop_history",
                data: function (param){
                
                param.po_num = $("#id_myponum").val();
                param.batch =  $("#id_mybatch").val();
                param.oqclotapp_id = $("#id_mylotapp").val();  
              }
            },
            
            "columns":[
              { "data" : "shipop_name" },
              { "data" : "shipop_datetime" },
              { "data" : "3_1_1" },
              { "data" : "3_1_2" },
              { "data" : "3_1_3" },
              { "data" : "3_1_4" },
              { "data" : "3_2_1" },
              { "data" : "3_2_2" },
              { "data" : "3_2_3" },
              { "data" : "pack_list_con_no" },
              { "data" : "total_shipment_qty" },
              { "data" : "total_box_qty" },

            ],

        });//end of dataTable of summary

        dt_shipop_history_only = $("#tbl_shipop_history_only").DataTable({
            "processing"    : false,
            "serverSide"  : true,
            "order": [[ 2, "desc" ]],
            "ajax"        : {
              url: "retrieve_shipop_history",
                data: function (param){
                
                param.po_num = $("#id_myponum2").val();
                param.batch =  $("#id_mybatch2").val();
                param.oqclotapp_id = $("#id_mylotapp2").val();  
              }
            },
            
            "columns":[
              { "data" : "shipop_name" },
              { "data" : "shipop_datetime" },
              { "data" : "3_1_1" },
              { "data" : "3_1_2" },
              { "data" : "3_1_3" },
              { "data" : "3_1_4" },
              { "data" : "3_2_1" },
              { "data" : "3_2_2" },
              { "data" : "3_2_3" },
              { "data" : "pack_list_con_no" },
              { "data" : "total_shipment_qty" },
              { "data" : "total_box_qty" },

            ],

        });//end of dataTable of summary

         $(document).on('keyup','#txt_search_po_number',function(e){
        if( e.keyCode == 13 ){

          $('#id_po_no').val('');
          $('#id_device_name').val('');
          $('#id_po_qty').val('');

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

      $(document).on('click','.btn_update_shipping_operator',function(e){

          /*alert('burtoy');
       */
          let po_num = $(this).attr('po-num');
          let batch_num = $(this).attr('batch-num');
          let mat_sub = $(this).attr('mat-sub');
          let oqclotapp_id = $(this).attr('oqclotapp-id');

          let station = 2;

           $('#id_myponum').val(po_num);
           $('#id_mybatch').val(batch_num);
           $('#id_mysub').val(mat_sub);
           $('#id_oqclotapp').val(oqclotapp_id);
           $('#id_mylotapp').val(oqclotapp_id);

          RetrieveOQCDetails(po_num,batch_num,mat_sub,oqclotapp_id);
          RetrieveOQCName(oqclotapp_id,mat_sub);
          RetrievePackCodeFromPackop(po_num,batch_num,mat_sub,station);    

          dt_shipop_history.draw();
          dt_packin_history.draw();
          dt_packop_history.draw();      
      });

         //VIEW HISTORY
        $(document).on('click','.btn_view_shipop_history',function(e){
       
          let po_num = $(this).attr('po-num');
          let batch_num = $(this).attr('batch-num');
          let oqclotapp_id = $(this).attr('oqclotapp-id');

           $('#id_myponum2').val(po_num);
           $('#id_mybatch2').val(batch_num);
           $('#id_mylotapp2').val(oqclotapp_id);

          dt_shipop_history_only.draw();
      });


    });

    /*  $(document).on('click','.btn_link_to_packcode_shipop',function(e){
       
            //generate packing code here
            let pack_code_shipop = $("#id_link_packcode_shipop").val();
            ShipopLinkToPackingCode(pack_code_shipop);
          
      });*/

       $('#modalShippingOperator').on('hidden.bs.modal', function (e) {
        // and empty the modal-content element
          $('#id_311').val(1);
          $('#id_312').val(1);
          $('#id_313').val(1);
          $('#id_314').val(1);

          $('#id_321').val('');
          $('#id_322').val('');
          $('#id_323').val('');

          $('#id_plcn').val('');
          $('#id_tsq').val('');
          $('#id_tbq').val('');

          $('#id_shipop_judgement').val(1);
          $('#id_link_packcode_shipop').val('');
        

          //ENABLE AGAIN FIELDS
          $('#id_311').removeAttr('readonly');
          $('#id_312').removeAttr('readonly');
          $('#id_313').removeAttr('readonly');
          $('#id_314').removeAttr('readonly');

          $('#id_321').removeAttr('readonly');
          $('#id_322').removeAttr('readonly');
          $('#id_323').removeAttr('readonly');

          $('#id_plcn').removeAttr('readonly');
          $('#id_tsq').removeAttr('readonly');
          $('#id_tbq').removeAttr('readonly');

                    //UNCLICKABLE
          $('#id_311').css("pointer-events","auto");
          $('#id_312').css("pointer-events","auto");
          $('#id_313').css("pointer-events","auto");
          $('#id_314').css("pointer-events","auto");
    });

    $('#formShippingOperator').submit(function(event){

      event.preventDefault();

      SubmitShipop();

    });

     $(document).on('click','#btn_save_shipop',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','#formShippingOperator').modal('show');
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

       //ALL ON CHANGE DATA WILL BE HERE------------------------//

       $('.select_shipop').on('change', function() {

          let noCounter = 0;
          $('.select_shipop option:selected').each(function(i, obj) {
              if( $(this).val() == 2 ){
                noCounter++;
              }
          });

          if(noCounter > 0)
          {
            $('#id_shipop_judgement').val(2);
          }
          else
          {
            $('#id_shipop_judgement').val(1);
          }
        });

       //--------------------------------------------------------//

                     //- Search PO
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