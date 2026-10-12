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
  <!--start content here-->
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
            <h1>Preliminary Production Packing Inspection</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Preliminary Production Packing Inspection</li>
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
                <h3 class="card-title">Current P.O. Lot Application List</h3>

                <button type="button" class="px-2 py-1 btn btn-sm btn-info btn_update_packing_operator hidden_scanner_input" data-toggle="modal" data-target="#modalPackingOperatorHistory" title="Update Details" po-num="1" batch-num="1" mat-sub="1" oqclotapp-id="1"><i class="fa fa-edit fa-sm"></i></button>
              </div>



              <!-- Start Page Content -->
              <div class="card-body">

                <div class="table responsive">
                  <table id="tblPackingOperator" class="table table-bordered table-hover" style="width: 100%;">
                    <thead>
                      <tr>
                        <th>Action</th>
                        <th>Lot Application Submission</th>
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
    <!----------------------------MODAL----------------------------->

    <div class="modal fade" id="modalPackingOperator">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">

          <div class="modal-header">
            <h4 class="modal-title"><i class="fa fa-edit"></i> Preliminary Production Packing Inspection</h4>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <!--TABS-->
        <!-- ./row -->
        <div class="row">
          <div class="col-12 col-md-12">
            <div class="card card-primary card-tabs">
              <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">OQC Inspection</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">OQC Lot Application</a>
                  </li>
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                  <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                     
                     <!--OQC INSPECTION MODULE TABLE-->
                       <div class="row">
                            <div class="col-sm-12 p-2">
                              <h4><i class="fa fa-list"></i> &nbsp; OQC Visual Inspection Result</h4> 
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


         <!--SEARCH PACK CODE-->
          <div class="row">

            <div class="col-sm-4 p-2">

            </div>

            <div class="col-sm-4 p-2">

            </div>

            <div class="col-sm-4 p-2">

                 
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">

                         <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" style="background-color: rgb(66,139,202); color: rgb(255, 255, 255); border-color: rgb(57, 179, 215);" id="basic-addon1">Link to Packing Code:</span>
                        </div>

                        <input type="text" class="form-control form-control-sm" id="id_link_packcode_packop" name="name_link_packcode_packop" autocomplete="off" placeholder="">

                        <span class="input-group-append">

                            <button type="button" id="id_btn_link_packcode_packop" class="btn btn-primary btn-flat btn_link_to_packcode_packop" title="Link to Packing Code"><i class="fa fa-link"></i></button>
                        </span>
                      </div>
                    </div>
                  

            </div>

          </div>
         <!--END OF SEARCH PACK CODE-->
            
            <form method="post" id="formPrelimPackingOperator">
              @csrf
              <div class="modal-body">

              <div class="row">

              <input type="hidden" id="id_myponum" name="name_myponum" readonly>

              <input type="hidden" id="id_mybatch" name="name_mybatch" readonly>

              <input type="hidden" id="id_mysub" name="name_mysub" readonly>

              <input type="hidden" id="id_mylotapp" name="name_mylotapp" readonly>

              <input type="hidden" id="id_hidden_box_qty" name="name_hidden_box_qty" readonly>

              </div>
           
            <div class="row">

                <div class="col-sm-4 p-2">

                   <h6><strong>1.1 Packing Type</strong></h6>

                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                       <!--  <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">1.1 Packing Type</span>
                        </div> -->

                        <select class="form-control form-control-sm" name="name_PackopPackingType" id="id_PackopPackingType">
                          <option selected disabled>---</option>
                          <option value = "1">Box/Esafoam</option>
                          <option value = "2">Magazine Tube</option>
                          <option value = "3">Tray</option>
                          <option value = "4">Bubble Sheet</option>
                          <option value = "5">Emboss Reel</option>
                          <option value = "6">Polybag</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <h6><strong>1.2 Unit Condition</strong></h6>

                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <select class="form-control form-control-sm" name="name_PackopUnitCondition" id="id_PackopUnitCondition">
                          <option selected disabled>---</option>
                          <option value = "1">Terminal Up</option>
                          <option value = "2">Terminal Down</option>
                          <option value = "3">Terminal Mounted on Esafoam</option>
                          <option value = "4">Terminal Side</option>
                          <option value = "5">Unit Mounted on Emboss Pocket</option>
                          <option value = "6">Wrap on Bubble Sheet</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <h6><strong>1.3 Check OQC Lot Application / Runcard / Product Label Versus Actual Units</strong></h6>

                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">1.3.1 PO Number</span>
                        </div>
                        <select class="form-control form-control-sm select_packop" name="name_131" id="id_131">
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
                          <span class="input-group-text w-100" id="basic-addon1">1.3.2 Device Name</span>
                        </div>
                        <select class="form-control form-control-sm select_packop" name="name_132" id="id_132">
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
                          <span class="input-group-text w-100" id="basic-addon1">1.3.3 Quantity per lot</span>
                        </div>
                        <select class="form-control form-control-sm select_packop" name="name_133" id="id_133">
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
                          <span class="input-group-text w-100" id="basic-addon1">1.3.4 Reel Lot No.</span>
                        </div>
                        <select class="form-control form-control-sm select_packop" name="name_134" id="id_134">
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
                          <span class="input-group-text w-100" id="basic-addon1">1.3.5 Total No. of Reels/Trays</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_135" name="name_135" autocomplete="off" placeholder="e.g. 1, 2">
                      </div>
                    </div>
                  </div>
              </div>
              
              <!-------NEXT COLUMN-------->

              <div class="col-sm-4 p-2">

                <h6><strong>1.4 Packing Code</strong></h6>

                 <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">

                        <input type="text" class="form-control form-control-sm" id="id_PackingCode" name="name_PackingCode" autocomplete="off" placeholder="Series Code + Month + Autoincrement">
                      </div>
                    </div>
                  </div>


                  <br>
                   <h6><strong>1.5 Does the Packed Units comply with the Packing Manual?</strong></h6>
              
                   <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">1.5.1 Orientation of Units</span>
                        </div>
                        <select class="form-control form-control-sm select_packop" name="name_151" id="id_151">
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
                          <span class="input-group-text w-100" id="basic-addon1">1.5.2 Qty. Per Box / Tray</span>
                        </div>
                        <select class="form-control form-control-sm select_packop" name="name_152" id="id_152">
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
                          <span class="input-group-text w-100" id="basic-addon1">1.5.3 UL Sticker</span>
                        </div>
                        <select class="form-control form-control-sm select_packop" name="name_153" id="id_153">
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
                          <span class="input-group-text w-100" id="basic-addon1">1.5.4 Silica Gel</span>
                        </div>
                        <select class="form-control form-control-sm select_packop" name="name_154" id="id_154">
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
                          <span class="input-group-text w-100" id="basic-addon1">1.5.5 Accessories</span>
                        </div>
                        <select class="form-control form-control-sm select_packop" name="name_155" id="id_155">
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
                          <span class="input-group-text w-100" id="basic-addon1">1.5.6 ROHS Sticker</span>
                        </div>
                        <select class="form-control form-control-sm select_packop" name="name_156" id="id_156">
                          <option value = "1" selected>Yes</option>
                          <option value = "2">No</option>
                          <option value = "3">N/A</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  
                  </div>

                     <!-------NEXT COLUMN-------->

              <div class="col-sm-4 p-2">

                <br><br><br><br><br><br><br><br><br><br><br>
                 <h6><strong>Packing Operator Judgement</strong></h6><hr>
                    
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text primary w-100" style="background-color: rgb(57, 179, 215); color: rgb(255, 255, 255); border-color: rgb(57, 179, 215);" id="basic-addon1"><strong>JUDGEMENT</strong></span>
                        </div>
                        <select class="form-control form-control-sm selectCertLot" name="name_judgement" id="id_judgement" readonly>
                          <option disabled>---</option>
                          <option value = "1" selected>ACCEPT</option>
                          <option value = "2">REJECT</option>
                        </select>
                      </div>
                    </div>

                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-success" id="btn_save_packop">Save</button>
                    </div>

                    </div>

            </div>

            <hr>

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

      </form> <!--FORM PRELIM PACKING OPERATOR-->


      <!--PACKOP HISTORY MODAL-->

   <div class="modal fade" id="modalPackingOperatorHistory">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">

          <div class="modal-header">
            <h4 class="modal-title"><i class="fa fa-edit"></i> Preliminary Production Packing Inspection History</h4>
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
                            <h5><i class="fa fa-box"></i> &nbsp; Packing Operator History</h5>
                             <br> 
                             <br> 
                              <div class="table-responsive">
                                <table id="tbl_packop_history_only" class="table table-bordered table-striped table-hover" style="min-width: 1500px!important;">
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
       
          </div> <!--Modal Body-->

        </div> <!--Modal Content-->
      </div> <!--Modal Dialog-->
    </div> <!--Modal-->


      <!--END OF PACKOP HISTORY MODAL-->

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

 $('#id_judgement').css("pointer-events","none");

    let dt_packingoperator;
    let dataTableOQCVIR_summary;
    let dt_packop_history;
    let dt_packop_history_only;
    
    $(document).ready(function () {
      bsCustomFileInput.init();

      dt_packingoperator = $("#tblPackingOperator").DataTable(
      {
        "processing":true,
        "serverSide":true,
        "ajax" : {
          url: "packop_view_batches",
          data: function (param)
          {
            param.po_number = $("#txt_search_po_number").val();
          }
        },

        "columns":[
        { "data" : "action", orderable:false, searchable:false },
        { "data" : "submission", orderable:false, searchable:false},
        { "data" : "lotapp" },
        { "data" : "lotqty" },
        { "data" : "packing_code"},
        { "data" : "status" }
        ]
      });//end of datatable

      dataTableOQCVIR_summary = $("#tblOQCVIR_summary").DataTable({
            "processing"    : false,
            "serverSide"  : true,
            "ajax"        : {
              url: "get_oqcvir_summary",
                data: function (param){
                param.c_lot_batch_no = $("#id_mybatch").val();
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

        dt_packop_history_only = $("#tbl_packop_history_only").DataTable({
            "processing"    : false,
            "serverSide"  : true,
            "order": [[ 2, "desc" ]],
            "ajax"        : {
              url: "retrieve_packop_history",
            data: function (param){
                  
                  param.po_num = $("#id_myponum2").val();
                  param.batch =  $("#id_mybatch2").val();
                  param.oqclotapp_id = $("#id_mylotapp2").val();              
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

        

        $(document).on('keyup','#txt_search_po_number',function(e){
        if( e.keyCode == 13 ){

          $('#id_po_no').val('');
          $('#id_device_name').val('');
          $('#id_po_qty').val('');
          $('#id_box_qty').val('');


          var data = {
          'po'      : $('#txt_search_po_number').val()
          }

          dt_packingoperator.draw();


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

            //$('#txt_search_po_number').val('');

          },error : function(data){

              }

            }); 
        }
      });

        $(document).on('click','.btn_generate_pack_code',function(e){
       
            //generate packing code here
            let device_name = $("#id_device_name").val();

            GeneratePackingCode(device_name);
          
      });

      $(document).on('click','.btn_link_to_packcode_packop',function(e){
       
            //generate packing code here
            let pack_code_packop = $("#id_link_packcode_packop").val();
            let box_qty = $("#id_box_qty").val();
            PackopLinkToPackingCode(pack_code_packop, box_qty);
          
      });


        $(document).on('click','.btn_update_packing_operator',function(e){
       
          let po_num = $(this).attr('po-num');
          let batch_num = $(this).attr('batch-num');
          let mat_sub = $(this).attr('mat-sub');
          let oqclotapp_id = $(this).attr('oqclotapp-id');

          let box_qty = $('#id_box_qty').val();
          // let device_name = $("#id_device_name").val();

           $('#id_myponum').val(po_num);
           $('#id_mybatch').val(batch_num);
           $('#id_mysub').val(mat_sub);
           $('#id_mylotapp').val(oqclotapp_id);
           $('#id_hidden_box_qty').val(box_qty);

          RetrieveOQCDetails(po_num,batch_num,mat_sub,oqclotapp_id);
          

          dt_packop_history.draw();
      });

        //VIEW HISTORY
        $(document).on('click','.btn_view_packop_history',function(e){
       
          let po_num = $(this).attr('po-num');
          let batch_num = $(this).attr('batch-num');
          let oqclotapp_id = $(this).attr('oqclotapp-id');

           $('#id_myponum2').val(po_num);
           $('#id_mybatch2').val(batch_num);
           $('#id_mylotapp2').val(oqclotapp_id);

          dt_packop_history_only.draw();
      });

    })

     $('#modalPackingOperator').on('hidden.bs.modal', function (e) {
        // and empty the modal-content element
        $('#id_PackingCode').val('');

        $("#id_link_packcode_packop").val('');

          $('#id_PackopPackingType').val("---");
          $('#id_PackopUnitCondition').val("---");
          $('#id_131').val(1);
          $('#id_132').val(1);
          $('#id_133').val(1);
          $('#id_134').val(1);
          $('#id_135').val(1);

          $('#id_PackingCode').val('');

          $('#id_151').val(1);
          $('#id_152').val(1);
          $('#id_153').val(1);
          $('#id_154').val(1);
          $('#id_155').val(1);
          $('#id_156').val(1);

          $('#id_judgement').val(1);

          //ENABLE AGAIN FIELDS
          $("#id_link_packcode_packop").removeAttr('readonly');
          $('#id_PackopPackingType').removeAttr('readonly');
          $('#id_PackopUnitCondition').removeAttr('readonly');
          $('#id_131').removeAttr('readonly');
          $('#id_132').removeAttr('readonly');
          $('#id_133').removeAttr('readonly');
          $('#id_134').removeAttr('readonly');
          $('#id_135').removeAttr('readonly');
          $('#id_151').removeAttr('readonly');
          $('#id_152').removeAttr('readonly');
          $('#id_153').removeAttr('readonly');
          $('#id_154').removeAttr('readonly');
          $('#id_155').removeAttr('readonly');
          $('#id_156').removeAttr('readonly');
          $('#id_btn_link_packcode_packop').removeAttr('disabled');
          $('#id_btn_generate_pack_code').removeAttr('disabled');

          //CLICKABLE POINTER EVENTS
          $('#id_PackopPackingType').css("pointer-events","auto");
          $('#id_PackopPackingType').css("pointer-events","auto");
          $('#id_PackopUnitCondition').css("pointer-events","auto");
          $('#id_131').css("pointer-events","auto");
          $('#id_132').css("pointer-events","auto");
          $('#id_133').css("pointer-events","auto");
          $('#id_134').css("pointer-events","auto");
          $('#id_135').css("pointer-events","auto");
          $('#id_PackingCode').css("pointer-events","auto");
          $('#id_151').css("pointer-events","auto");
          $('#id_152').css("pointer-events","auto");
          $('#id_153').css("pointer-events","auto");
          $('#id_154').css("pointer-events","auto");
          $('#id_155').css("pointer-events","auto");
          $('#id_156').css("pointer-events","auto");
          $("#id_link_packcode_packop").css("pointer-events","auto");
    });

    $('#formPrelimPackingOperator').submit(function(event){

      event.preventDefault();

      // alert('burtoy');
      SubmitPackop();

    });

     $(document).on('click','#btn_save_packop',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','#formPrelimPackingOperator').modal('show');
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

       $('.select_packop').on('change', function() {

          let noCounter = 0;
          $('.select_packop option:selected').each(function(i, obj) {
              if( $(this).val() == 2 ){
                noCounter++;
              }
          });

          if(noCounter > 0)
          {
            $('#id_judgement').val(2);
          }
          else
          {
            $('#id_judgement').val(1);
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