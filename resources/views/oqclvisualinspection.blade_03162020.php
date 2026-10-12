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

  @section('title', 'OQC Inspection Result')

  @section('content_page')
  <!-- Content Wrapper. Contains page content -->
  <style>
    #id_currentPONo, #id_current_lotbatch_number, #id_current_device_name, #id_lot_qty, #id_cPONo, #id_cLotBatch, #id_cDeviceName, #id_cLotQty {
      color: #0000FF;
      font-weight: bold;
    }

    #id_cOutputQty, #id_output_qty {
      color: #D35400;
      font-weight: bold;
    }

    .hidden_scanner_input{
      position: absolute;
      opacity: 0;
    }
  </style>

  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <?php 
      date_default_timezone_set('Asia/Manila');
    ?>

    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>OQC Inspection Result</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">OQC Inspection Result</li>
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
                <h3 class="card-title">Search P.O.</h3>
              </div>

              <!-- Start Page Content -->
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
                    <!--
                    <div class="col-sm-1">
                    </div>
                     <div class="col-sm-2">
                        <label>PO No</label>
                          <input type="text" class="form-control" id="id_po_no" readonly="">
                      </div>
                    -->
                      <div class="col-sm-2">
                        <label>Device Name</label>
                          <input type="text" class="form-control" id="id_device_name" readonly="">
                      </div>
                      <div class="col-sm-1">
                        <label>PO Qty</label>
                          <input type="text" class="form-control" id="id_po_qty" readonly="">
                      </div>
                  </div>
                  <br>
                  <!--
                  <div style="float: right;">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#modalOQCVIR" id="btnShowAddOQCVIRModal"><i class="fa fa-plus"></i> Add OQC VIR</button>
                  </div> <br><br>
                  -->
              </div>
              <!-- !-- End Page Content -->
              </div>
              <!-- /.card -->

              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">2 OQC Inspection Result (Responsible: OQC Inspector)</h3>
                </div>

                 <div class="card-body">
                  <div class="table responsive">
                    <table id="tblOQCVIR" class="table table-bordered table-striped table-hover" style="width: 100%;">
                      <thead>
                        <tr align="center">
                          <th>Action</th>
                          <th>Status</th>
                          <th>Submission from Lot App</th>
                          <th>Lot # / Batch #</th>
                          <th>Sub Lot #</th>
                          <th>Reel Lot #</th>
                          <th>Total Lot Qty</th>
                          <th>Total Output Qty</th>
                          <th>Packing Code</th>
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
  <div class="modal fade" id="modalOQCVIR">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-plus"></i> &nbsp; Add OQC Inspection Result</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formOQCVIR">
          @csrf

          <div class="modal-body">
            <div class="row">
              <div class="col-sm-4 p-2">
                <input type="hidden" class="form-control" id="hidden_OQCLotApp_id" name="hidden_OQCLotApp_name">
                <input type="hidden" class="form-control" id="hidden_sub" name="hidden_sub">
                <input type="hidden" class="form-control" id="hidden_OQCVIR_id" name="hidden_OQCVIR_id">
                <input type="hidden" class="form-control" id="hidden_OQCVIR_current_judgement" name="hidden_OQCVIR_current_judgement">
                <input type="hidden" class="form-control" id="hidden_OQCVIR_current_status" name="hidden_OQCVIR_current_status">
                <input type="hidden" class="form-control" id="hidden_OQCVIR_new_status" name="hidden_OQCVIR_new_status">
                <input type="hidden" class="form-control" id="hidden_sub_lot" name="hidden_sub_lot">
                <input type="hidden" class="form-control" id="hidden_d_qty" name="hidden_d_qty">
                <input type="hidden" class="form-control" id="hidden_total_count_sub_lot" name="hidden_total_count_sub_lot">
                <input type="hidden" class="form-control" id="hidden_packing_code" name="hidden_packing_code">

                <div class="row">
                  <div class="col">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">Current PO Number</span>
                      </div>
                      <input type="text" class="form-control form-control-sm" id="id_currentPONo" name="name_currentPONo" readonly>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">Device Name</span>
                      </div>
                      <input type="text" class="form-control form-control-sm" id="id_current_device_name" name="name_current_device_name" readonly>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">Lot/Batch No.</span>
                      </div>
                      <input type="text" class="form-control form-control-sm" id="id_current_lotbatch_number" name="name_current_lotbatch_number" readonly>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">Lot Quality</span>
                      </div>
                      <input type="text" class="form-control form-control-sm" id="id_lot_qty" name="name_lot_qty" readonly>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">Output Quality</span>
                      </div>
                      <input type="text" class="form-control form-control-sm" id="id_output_qty" name="name_output_qty" readonly>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">Inspection Date</span>
                      </div>
                      <input type="text" class="form-control form-control-sm" id="id_inspection_date" name="name_inspection_date" value="<?php echo date("Y-m-d");?>" readonly>
                    </div>
                  </div>
                </div>                
                <div class="row">
                  <div class="col">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">Inspection Start Time</span>
                      </div>
                      <input type="txt" class="form-control form-control-sm" id="id_inspection_stime" name="name_inspection_stime" readonly>
                    </div>
                  </div>
                </div>                
                <div class="row">
                  <div class="col">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">Sample Size/Result</span>
                      </div>
                      <input type="number" class="form-control form-control-sm" id="id_sample_size_result" name="name_sample_size_result" autocomplete="off">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">OK Quantity</span>
                      </div>
                      <input type="number" class="form-control form-control-sm" id="id_ok_qty" name="name_ok_qty" autocomplete="off">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">NG Quantity</span>
                      </div>
                      <input type="number" class="form-control form-control-sm" id="id_ng_qty" name="name_ng_qty" autocomplete="off">
                    </div>
                  </div>
                </div>

              </div>

              <div class="col-sm-8 p-2">
                <div class="row">
                  <div class="col">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend w-25">
                        <span class="input-group-text w-100" id="basic-addon1">Accessories Requirement</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      </div>
                        <input class="form-control-sm" type="radio" id="id_accessories_req_yes" name="name_accessories_req" value="1"> &nbsp; YES &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input class="form-control-sm" type="radio" id="id_accessories_req_no" name="name_accessories_req" value="2"> &nbsp; NO
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend w-25">
                        <span class="input-group-text w-100" id="basic-addon1">C.O.C Requirement</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      </div>
                        <input class="form-control-sm" type="radio" id="id_coc_req_yes" name="name_coc_req" value="1"> &nbsp; YES &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input class="form-control-sm" type="radio" id="id_coc_req_no" name="name_coc_req" value="2"> &nbsp; NO
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend w-25">
                        <span class="input-group-text w-100" id="basic-addon1">Result</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      </div>
                        <input class="form-control-sm" type="radio" id="id_oqc_nodefect" name="name_oqc_result" value="1"> &nbsp; NO DEFECT FOUND &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input class="form-control-sm" type="radio" id="id_oqc_withdefect" name="name_oqc_result" value="2"> &nbsp; WITH DEFECT FOUND/DETAILS
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend w-25">
                        <span class="input-group-text w-100" id="basic-addon1">Judgement</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                      </div>
                        <input class="form-control-sm" type="radio" id="id_judgement_accepted" name="name_judgement" value="1"> &nbsp; ACCEPTED &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input class="form-control-sm" type="radio" id="id_judgement_rejected" name="name_judgement" value="2"> &nbsp; REJECTED
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-6">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend w-50">
                        <button type="button" class="btn btn-primary btn-sm btn_search_Inspector" title="Click to Scan ID"><i class="fa fa-qrcode"></i></button>
                        <span class="input-group-text w-100" id="basic-addon1">Inspector</span>
                      </div>
                      <input type="text" class="form-control form-control-sm" id="id_hidden_OQCInsName" readonly>
                      <input type="hidden" class="form-control form-control-sm" id="id_hidden_OQCInsName_id" name="name_hidden_OQCInsName_id" readonly>
                      <!--
                      <input type="text" class="form-control form-control-sm" id="id_search_OQCInsName" name="name_search_OQCInsName" placeholder="Please scan your ID." autocomplete="off" onkeyup="this.value = this.value.toUpperCase();">
                      
                      <input type="hidden" class="form-control form-control-sm" id="id_hidden_OQCInsName" placeholder="Inspector Name" readonly>
                      <input type="hidden" class="form-control form-control-sm" id="id_hidden_OQCInsName_id" name="name_hidden_OQCInsName_id" placeholder="Inspector user's id" readonly>
                    -->
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-6">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">Stamp</span>
                      </div>
                      <input type="text" class="form-control form-control-sm" id="id_OQCIns_stamp" name="name_OQCIns_stamp" readonly>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-6">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100" id="basic-addon1">Remarks</span>
                      </div>
                      <textarea class="form-control form-control-sm" rows="3" id="id_oqc_vir_remarks" name="name_oqc_vir_remarks"></textarea>
                    </div>
                  </div>
                </div>             

              </div>

            </div>

            <div class="row">
              <div class="col-sm-12 p-2">
                <span class="fa fa-list"></span> OQC Inspection Result Summary
                <br> 
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
         
          </div>

          <div class="modal-footer">
            <button type="submit" id="id_btn_AddOQCVIR" class="btn btn-primary btn-sm"><i class="fa fa-check fa-xs"></i> Save</button>
            <!--
            <button type="button" id="id_btn_ApprovedOQC" class="btn btn-success btn-sm"><i class="fa fa-thumbs-up fa-xs"></i> OQC Approved</button>
          -->
            <button type="button" id="id_btn_close_oqcvir" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  <div class="modal fade" id="modalViewOQCLotApp">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-list"></i> &nbsp; OQC Lot Application Summary</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

          <div class="modal-body">

              <div class="row">
                <div class="col-sm-4 p-2">
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Current PO Number</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_cPONo" name="name_cPONo" readonly>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-4 p-2">
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Device Name</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_cDeviceName" name="name_cDeviceName" readonly>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-4 p-2">
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Lot/Batch No.</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_cLotBatch" name="name_cLotBatch" readonly>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-3 p-2">
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Lot Qty</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_cLotQty" name="name_cLotQty" readonly>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-3 p-2">
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Output Qty</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_cOutputQty" name="name_cOutputQty" readonly>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            <div class="row">
              <div class="col-sm-12 p-2">
                  <div class="table-responsive">
                    <table id="tblOQCLotApp_summary" class="table table-bordered table-striped table-hover" style="min-width: 1500px!important;">
                      <thead style="font-size:85%;">
                        <tr align="center">
                          <th>Submission</th>
                          <th>Device Cat.</th>
                          <th>Cert. Lot</th>
                          <th>Assembly Line</th>
                          <th>Reel Lot No.</th>
                          <th>Print Lot No.</th>
                          <th>Total No. of Reels</th>
                          <th>Urgent Directions No.</th>
                          <th>Guaranteed Lot</th>
                          <th>Problem</th>
                          <th>Document No.</th>
                          <th>Lot Applied By</th>
                          <th>App. Date</th>
                          <th>App. Time</th>
                          <th>Remarks</th>
                        </tr>
                      </thead>
                      <tbody style="font-size:85%;"></tbody>
                    </table>
                  </div>
              </div>
            </div>
         
          </div>

          <div class="modal-footer">
            <button type="button" id="id_btn_close_vwoqclotapp" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
          </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

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
            <input type="text" id="txt_search_po_number" class="hidden_scanner_input">
          </div>
        </div>
      </div>
  </div>

  <div class="modal fade" id="modalScan_Inspector" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
            <br>
            <br>
            <h1><i class="fa fa-qrcode fa-lg"></i></h1>
            </div>
            <input type="text" id="id_search_OQCInsName" class="hidden_scanner_input">
          </div>
        </div>
      </div>
  </div>

  <div class="modal fade" id="modal_LotApp_QRcode">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-qrcode"></i> Generate QR Code</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <center>
              <div class="row">
                <div class="col-sm-6">
                  <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                            ->size(150)->margin(5)->errorCorrection('H')
                            ->generate('0')) !!}" id="img_barcode_po_no" style="max-width: 200px;">
                  <br>
                  <label id="lbl_po_no"></label> <br>
                  <label id="lbl_device_name"></label> <br>
                </div>

                <div class="col-sm-6">
                  <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                            ->size(150)->margin(5)->errorCorrection('H')
                            ->generate('0')) !!}" id="img_barcode_packing_code" style="max-width: 200px;">
                  <br>
                  <label id="lbl_packing_code"></label> <br>
                </div>

              </div>
            </center>
        </div>
        <div class="modal-footer">
            <button type="submit" id="btn_print_barcode" class="btn btn-primary btn-sm"><i class="fa fa-print fa-xs"></i> Print</button>
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>


  @endsection

  @section('js_content')
  <script type="text/javascript">
    let dataTableOQCVIR;
    let dataTableOQCVIR_summary;
    let dataTableOQCLotAppSummary;

    let img_barcode_packing_code;
    let img_barcode_po_no;
    let lbl_packing_code;
    let lbl_po_no;
    let lbl_device_name;

    $(document).ready(function () {

        dataTableOQCVIR = $("#tblOQCVIR").DataTable({
          "processing"    : false,
            "serverSide"  : true,
            "ajax"        : {
              url: "get_oqclot_details",
                data: function (param){
                param.po_no = $("#txt_search_po_number").val();
              }
            },
            
            "columns":[
              { "data" : "action", orderable:false, searchable:false },
              { "data" : "status_raw" },
              { "data" : "subm_raw" },
              { "data" : "lot_batch_no" },
              { "data" : "sub_lot_raw" },
              { "data" : "reel_lot" },
              { "data" : "lot_qty" },
              { "data" : "output_qty" },
              { "data" : "packing_code_raw" }
            ],

            "columnDefs": [ 
              {
                "targets": [2,8],
                "data": null,
                "defaultContent": "---",
                "width": "10%", "targets": 0
              } 
            ],

             // order:[[3,'asc']]
             order:[[3,'desc']]
        });//end of dataTable 

        dataTableOQCVIR_summary = $("#tblOQCVIR_summary").DataTable({
            "processing"    : false,
            "serverSide"  : true,
            "ajax"        : {
              url: "get_oqcvir_summary",
                data: function (param){
                // param.fkid_oqclotapp = $("#hidden_OQCLotApp_id").val();
                param.c_lot_batch_no = $("#id_current_lotbatch_number").val();
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

        dataTableOQCLotAppSummary = $("#tblOQCLotApp_summary").DataTable({
          "processing"    : false,
            "serverSide"  : true,
            "ajax"        : {
              url: "get_oqc_lotapp_data_summary",
                data: function (param){
                  param.lot_batch_no = $("#id_cLotBatch").val();
                }
            },
            
            "columns":[
              { "data" : "sub_raw" },
              { "data" : "devcat_raw" },
              { "data" : "certlot_raw" },
              { "data" : "assy_raw" },
              { "data" : "reel_lot" },
              { "data" : "print_lot" },
              { "data" : "ttl_reel" },
              { "data" : "direction" },
              { "data" : "guar_lot_raw" },
              { "data" : "problem" },
              { "data" : "doc_no" },
              { "data" : "fvo_raw" },
              { "data" : "app_date_raw" },
              { "data" : "app_time_raw" },
              { "data" : "remarks" }
            ],
            
            order:[[0,'asc']]

          }); //end of dataTable of Lot App

        $("#id_btn_close_oqcvir").click(function(){
          dataTableOQCVIR.ajax.reload();
          // location.reload(false);
        }); 

        $("#id_btn_close_vwoqclotapp").click(function(){
          dataTableOQCVIR.ajax.reload();
        }); 

    });

    // Add
    $("#formOQCVIR").submit(function(event){
      event.preventDefault();

        if ( $('#hidden_OQCVIR_id').val() == '' ) {
          AddOQCVIR();

        } else {

          if ( $('#hidden_OQCVIR_current_judgement').val() == '2' ){
            AddOQCVIR();

          }else{

            if ($('#id_sample_size_result').val() == ''){
                alert('Sample Size/Result is required.');
                return;
            } 
            else if ($('#id_ok_qty').val() == ''){
                alert('OK Qty is required.');
                return;
            } 
            else if ($('#id_ng_qty').val() == ''){
                alert('NG Qty is required.');
                return;
            } 

            var name_accessories_req = $("input[name='name_accessories_req']:checked").val(); 
            if ( !$("input[name='name_accessories_req']:checked").val()){
                  alert('Accessories Requirement is required.');
                  return;
            } 

            var name_coc_req = $("input[name='name_coc_req']:checked").val(); 
            if ( !$("input[name='name_coc_req']:checked").val()){
                  alert('C.O.C Requirement is required.');
                  return;
            } 

            var name_oqc_result = $("input[name='name_oqc_result']:checked").val(); 
            if ( !$("input[name='name_oqc_result']:checked").val()){
                  alert('Result is required.');
                  return;
            } else if ( name_oqc_result == 2 ){ 
                if($('#id_oqc_vir_remarks').val() == ''){
                  alert('Result is with defect found/details, Remarks is required.');
                  return;
                }
            }

            var name_judgement = $("input[name='name_judgement']:checked").val(); 
            if ( !$("input[name='name_judgement']:checked").val()){
                  alert('Judgement is required.');
                  return;
            } 

            AddOQCVIR();

            
        }    
          }
    });

    function pad(number, length) {
        var str = '' + number;
        while (str.length < length) {
            str = '0' + str;
        }
       
        return str;
    }

    // Update
    $(document).on('click', '.btn_update_vir', function(){

        var dt = new Date();
        var time = (('0' + dt.getHours()).slice(-2)) + ":" + (('0' + dt.getMinutes()).slice(-2)) + ":" + (('0' + dt.getSeconds()).slice(-2));

        $('#modalOQCVIR').modal({
          backdrop: 'static',
          keyboard: false, 
          show: true
        });

        id = $(this).val();
        
          var data = {
              "id"          : id
          }
          
          data = $.param(data);
          $.ajax({
              data        : data,
              type        : 'get',
              dataType    : 'json',
              url         : "get_oqcvir_details",
              success     : function (data) {

                $('#hidden_OQCLotApp_id').val(data['oqc_details'][0]['id']);
                $('#id_currentPONo').val( data['oqc_details'][0]['po_no'] );
                $('#id_current_device_name').val( data['oqc_details'][0]['wbs_kitting']['device_name'] );
                $('#id_current_lotbatch_number').val( data['oqc_details'][0]['lot_batch_no'] );
                $('#id_lot_qty').val( data['oqc_details'][0]['lot_qty'] );
                $('#id_output_qty').val( data['oqc_details'][0]['output_qty'] );
                $('#hidden_sub_lot').val( data['oqc_details'][0]['sub_lot_no'] );
                $('#hidden_d_qty').val( data['d_qty'] );
                $('#hidden_total_count_sub_lot').val( data['total'] + 1);

                $("input[name='name_oqc_result']").click(function() { 
                      name_oqc_result = this.value;
                      if ( name_oqc_result == 2 ){ 
                        $('#id_judgement_rejected').prop('checked', true);
                        $('#hidden_OQCVIR_new_status').val(2);
                        $('#hidden_packing_code').val('');
                      }
                      else{
                        $('#id_judgement_accepted').prop('checked', true);
                        $('#hidden_OQCVIR_new_status').val(1);

                            if ( data['d_qty'] == (data['total'] + 1) ){
                              //-packing code
                              var series_code   = data['series'][0]['series_code'];

                              var myDate = new Date();
                              var month = myDate.getMonth() + 1;
                              if(month <= 9){
                                  month = '0'+month;
                              }
                              var current_month = month;

                              var sub_lot_no = data['oqc_details'][0]['sub_lot_no'];
                              var counter_sub_lot_no = pad(sub_lot_no, 3);
                              var packing_code = series_code +''+ current_month +'-'+ counter_sub_lot_no;
                              $('#hidden_packing_code').val(packing_code);
                            }else{
                              $('#hidden_packing_code').val('');
                            }

                      }
                });

                $('#id_sample_size_result, #id_ng_qty, #id_ok_qty').on('input',function() { 
                  var sample_size_result   = $('#id_sample_size_result').val();
                  var ng_qty               = $('#id_ng_qty').val();

                  var ok_qty = ( parseFloat(sample_size_result) - parseFloat(ng_qty) );
                  $('#id_ok_qty').val((ok_qty).toFixed(0));
                });

                dataTableOQCVIR_summary.draw();

                if(data['oqc_details'][0]['oqcvir_details'] != null) {

                  // if ( data['total'] >= (data['d_qty']) ){
                  //     $('#id_btn_AddOQCVIR').hide();
                  // }else{
                  //     $('#id_btn_AddOQCVIR').show();
                  // }

                  if ( data['oqc_details'][0]['oqcvir_details']['status'] == 2 || data['oqc_details'][0]['oqcvir_details']['status'] == 3 ){
                      $('#id_btn_AddOQCVIR').show();
                  }else{
                      $('#id_btn_AddOQCVIR').hide();
                  }

                  $('#hidden_OQCVIR_id').val( data['oqc_details'][0]['oqcvir_details']['id'] );
                  $('#hidden_OQCVIR_current_judgement').val( data['oqc_details'][0]['oqcvir_details']['judgement'] );
                  $('#hidden_OQCVIR_current_status').val( data['oqc_details'][0]['oqcvir_details']['status'] );

                  if ( data['oqc_details'][0]['oqcvir_details']['status'] == '3'){
                        $('#id_accessories_req_yes').prop('checked', false);
                        $('#id_accessories_req_no').prop('checked', false);
                        $('#id_coc_req_yes').prop('checked', false);
                        $('#id_coc_req_no').prop('checked', false);
                        $('#id_judgement_rejected').prop('checked', false);
                        $('#id_judgement_accepted').prop('checked', false);
                        $('#id_oqc_nodefect').prop('checked', false);
                        $('#id_oqc_withdefect').prop('checked', false);
                        
                        $('#hidden_OQCVIR_new_status').val('');
                        $('#id_sample_size_result').val('');
                        $('#id_ok_qty').val('');
                        $('#id_ng_qty').val('');

                        hidden_sub = (data['oqc_details'][0]['oqcvir_details']['submission']);
                        $('#hidden_sub').val(hidden_sub);

                        $('#id_inspection_date').val(data['oqc_details'][0]['oqcvir_details']['insp_date']);
                        $('#id_inspection_stime').val(data['oqc_details'][0]['oqcvir_details']['insp_stime']);
                        $('#id_search_OQCInsName').val(data['oqc_details'][0]['oqcvir_details']['user_details']['employee_id']);
                        // $('#id_search_OQCInsName').attr('readonly',true);
                        $('#id_hidden_OQCInsName').val(data['oqc_details'][0]['oqcvir_details']['user_details']['name']);
                        $('#id_hidden_OQCInsName_id').val(data['oqc_details'][0]['oqcvir_details']['insp_name']);
                        $('#id_OQCIns_stamp').val(data['oqc_details'][0]['oqcvir_details']['insp_stamp']);

                  }else{
                        hidden_sub = (data['oqc_details'][0]['oqcvir_details']['submission'] * 1 + 1);
                        $('#hidden_sub').val(hidden_sub);

                        $('#id_inspection_date').val('<?php echo date("Y-m-d");?>');
                        $('#id_inspection_stime').val(time);
                        $('#hidden_OQCVIR_new_status').val(3);

                  }

                  // if ( data['oqc_details'][0]['oqcvir_details']['status'] == 3 ){
                  //   hidden_sub = (data['oqc_details'][0]['oqcvir_details']['submission']);
                  //   $('#hidden_sub').val(hidden_sub);
                  // }else{
                  //   hidden_sub = (data['oqc_details'][0]['oqcvir_details']['submission'] * 1 + 1);
                  //   $('#hidden_sub').val(hidden_sub);
                  // }

                  // if( data['oqc_details'][0]['oqcvir_details']['status'] == 1 ) {
                  //     $('#id_btn_AddOQCVIR').hide();
                  // }else if ( data['oqc_details'][0]['oqcvir_details']['status'] == 2 || data['oqc_details'][0]['oqcvir_details']['packing_code'] == null){
                  //     $('#id_btn_AddOQCVIR').hide();
                  // }

                  // if (data['oqc_details'][0]['oqcvir_details']['status'] == 3){
                    // $('#id_inspection_date').val(data['oqc_details'][0]['oqcvir_details']['insp_date']);
                    // $('#id_inspection_stime').val(data['oqc_details'][0]['oqcvir_details']['insp_stime']);
                    // $('#id_search_OQCInsName').val(data['oqc_details'][0]['oqcvir_details']['user_details']['employee_id']);
                    // $('#id_search_OQCInsName').attr('readonly',true);
                    // $('#id_hidden_OQCInsName').val(data['oqc_details'][0]['oqcvir_details']['user_details']['name']);
                    // $('#id_hidden_OQCInsName_id').val(data['oqc_details'][0]['oqcvir_details']['insp_name']);
                    // $('#id_OQCIns_stamp').val(data['oqc_details'][0]['oqcvir_details']['insp_stamp']);

                  // }else{

                    // $('#id_inspection_date').val('<?php //echo date("Y-m-d");?>');
                    // $('#id_inspection_stime').val(time);
                    // $('#id_search_OQCInsName').val('');
                    // $('#id_search_OQCInsName').attr('readonly',false);
                    // $('#id_hidden_OQCInsName').val('');
                    // $('#id_hidden_OQCInsName_id').val('');
                    // $('#id_OQCIns_stamp').val('');
                    // $('#hidden_OQCVIR_new_status').val(3);
                  // }

                  if ( $('#hidden_OQCVIR_current_judgement').val() == '2'){
                    $('#id_sample_size_result').attr('readonly',true);
                    $('#id_ok_qty').attr('readonly',true);
                    $('#id_ng_qty').attr('readonly',true);
                    $("input:radio[name=name_accessories_req]").attr("disabled",true);
                    $("input:radio[name=name_coc_req]").attr("disabled",true);
                    $("input:radio[name=name_oqc_result]").attr("disabled",true);
                    $("input:radio[name=name_judgement]").attr("disabled",true);
                    // $('#id_btn_AddOQCVIR').hide();

                  }else{

                    $('#id_sample_size_result').attr('readonly',false);
                    $('#id_ok_qty').attr('readonly',true);
                    $('#id_ng_qty').attr('readonly',false);
                    $("input:radio[name=name_accessories_req]").attr("disabled",false);
                    $("input:radio[name=name_coc_req]").attr("disabled",false);
                    $("input:radio[name=name_oqc_result]").attr("disabled",false);
                    $("input:radio[name=name_judgement]").attr("disabled",false);
                  }         

                }else{

                  if ( data['oqc_details'][0]['oqcvir_details'] == null){
                    $('#hidden_sub').val(1);                    
                  }

                  $('#id_btn_AddOQCVIR').show();
                  $('#hidden_OQCVIR_id').val('');
                  $('#hidden_OQCVIR_new_status').val(3);
                  $('#id_inspection_date').val('<?php echo date("Y-m-d");?>');
                  $('#id_inspection_stime').val(time);
                  $('#id_search_OQCInsName').val('');
                  $('#id_hidden_OQCInsName').val('');
                  $('#id_hidden_OQCInsName_id').val('');
                  $('#id_OQCIns_stamp').val('');
                  $('#hidden_packing_code').val('');

                  $('#id_sample_size_result').attr('readonly',true);
                  $('#id_ok_qty').attr('readonly',true);
                  $('#id_ng_qty').attr('readonly',true);
                  $("input:radio[name=name_accessories_req]").attr("disabled",true);
                  $("input:radio[name=name_coc_req]").attr("disabled",true);
                  $("input:radio[name=name_oqc_result]").attr("disabled",true);
                  $("input:radio[name=name_judgement]").attr("disabled",true);

                }  
                  
              }, error    : function (data) {
              alert('ERROR: '+data);
              }
          });
    });

    //- View Lot App Details
    $(document).on('click', '.btn_view_lotApp', function(){

        $('#modalViewOQCLotApp').modal({
          backdrop: 'static',
          keyboard: false, 
          show: true
        });

        lot_batch_no = $(this).val();
        
          var data = {
            "lot_batch_no"  : lot_batch_no
          }
          
          data = $.param(data);
          // alert(data);
          $.ajax({
              data        : data,
              type        : 'get',
              dataType    : 'json',
              url         : "get_oqclotapp_data",
              success     : function (data) {

                $('#id_cPONo').val( data[0]['po_no'] );
                $('#id_cDeviceName').val( data[0]['wbs_kitting']['device_name'] );
                $('#id_cLotBatch').val( data[0]['lot_batch_no'] );
                $('#id_cLotQty').val( data[0]['lot_qty'] );
                $('#id_cOutputQty').val( data[0]['output_qty'] );

                dataTableOQCLotAppSummary.draw();
                  
              }, error    : function (data) {
              alert('ERROR: '+data);
              }
          });
    });

    //- search PO no.
    $(document).on('keypress','#txt_search_po_number',function(e){

        $('#tblOQCVIR tbody tr').removeClass('table-active');
        $('#id_po_no').val('');
        $('#id_device_name').val('');
        $('#id_po_qty').val('');
        $('#id_currentPONo').val('');

        var data = {
          'po'      : $('#txt_search_po_number').val()
        }

        dataTableOQCVIR.draw();  

        data = $.param(data);
        $.ajax({
          type      : "get",
          dataType  : "json",
          data      : data,
          url       : "get_po_details",
          success : function(data){
            
            if ( data['po_details'].length > 0 ){
              $('#id_po_no').val( data['po_details'][0]['po_no'] );
              $('#id_device_name').val( data['po_details'][0]['wbs_kitting']['device_name'] );
              $('#id_po_qty').val( data['po_details'][0]['wbs_kitting']['po_qty'] );
              $('#id_currentPONo').val( data['po_details'][0]['po_no'] );        
            }

          },error : function(data){

          }

        }); 
    });

    //- search user empno.
    $(document).on('keypress','#id_search_OQCInsName',function(e){
      $('#id_hidden_OQCInsName').val('');
      $('#id_hidden_OQCInsName_id').val('');
      $('#id_OQCIns_stamp').val('');

      var data = {
        'employee_id'      : $('#id_search_OQCInsName').val()
      }

      dataTableOQCVIR.draw();  
     
      data = $.param(data);
      // alert(data);
      $.ajax({
        type      : "get",
        dataType  : "json",
        data      : data,
        url       : "get_user_details_vir",
        success : function(data){
          
          if ($.trim(data['user_details'])){
            $('#id_hidden_OQCInsName').val( data['user_details'][0]['name'] );
            $('#id_hidden_OQCInsName_id').val( data['user_details'][0]['id'] );
            $('#id_OQCIns_stamp').val( data['user_details'][0]['oqc_stamps'][0]['oqc_stamp'] );
          }

        },error : function(data){

        }

      }); 
    });

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

    //- Inspector
    $(document).on('click','.btn_search_Inspector',function(e){
      $('#id_search_OQCInsName').val('');
      $('#modalScan_Inspector').attr('data-formid', '').modal('show');
    });

    $(document).on('keypress',function(e){
      if( ($("#modalScan_Inspector").data('bs.modal') || {})._isShown ){
        $('#id_search_OQCInsName').focus();

        if( e.keyCode == 13 && $('#id_search_OQCInsName').val() !='' && ($('#id_search_OQCInsName').val().length >= 4) ){
            $('#modalScan_Inspector').modal('hide');
          }
        }
    });

     //- Print lot
    $(document).on('click', '.btn_print_lot', function(){
      var id = $(this).val();
    
        var data = {
            "id"          : id
        }

        data = $.param(data);
        $.ajax({
            data        : data,
            type        : 'get',
            dataType    : 'json',
            url         : "get_insp_result_by_id",
            success     : function (data) {
              console.log(data);

                $("#img_barcode_po_no").attr('src', data['po_no']);
                $('#lbl_po_no').text( data['ins_result_by_id'][0]['po_no'] );
                $('#lbl_device_name').text( data['ins_result_by_id'][0]['wbs_kitting']['device_name'] );
                
                $("#img_barcode_packing_code").attr('src', data['packing_code']);
                // $('#lbl_packing_code').text( data['ins_result_by_id'][0]['oqcvir_details']['packing_code'] );  
                $('#lbl_packing_code').text( data['dis_packing_code'] );  

                img_barcode_po_no         = data['po_no'];
                lbl_po_no                 = data['ins_result_by_id'][0]['po_no'];
                lbl_device_name           = data['ins_result_by_id'][0]['wbs_kitting']['device_name'];

                img_barcode_packing_code  = data['packing_code'];    
                lbl_packing_code          = data['dis_packing_code'];
     

                $('#modal_LotApp_QRcode').modal({
                  backdrop: 'static',
                  keyboard: false, 
                  show: true
                });

                
            }, error    : function (data) {
            alert('ERROR: '+data);
            }
        });

      });

    //- Print Barcode
    $("#btn_print_barcode").click(function(){
      popup = window.open();
        let content = '';
        
        content += '<html>';
        content += '<head>';
        content += '<title></title>';
        content += '</head>';
        content += '<body>';

        content += '<table style="width:100%;">';
	        content += '<tr>';
	            content += '<td style="text-align: center;">';
			        
			        content += '<table style="width:100%;">';
				        content += '<tr>';
				            content += '<td style="text-align: center;">';
				            content += '<img src="' + img_barcode_po_no + '" style="min-width: 55px; max-width: 55px;">';
				            content += '</td>';
				        content += '</tr>';

				        content += '<tr>';
				            content += '<td style="font-family: Arial; font-size: 8px; text-align: center; vertical-align:top;">';
                    content += '<label>' + lbl_po_no + '</label><br>';
				            content += '<label>' + lbl_device_name + '</label>';
				            content += '</td>';
				        content += '</tr>';
			        content += '</table>';

	            content += '</td>';

	            content += '<td style="text-align: center;">';
			        content += '<table style="width:100%;">';
				        content += '<tr>';
				            content += '<td style="text-align: center;">';
				            content += '<img src="' + img_barcode_packing_code + '" style="min-width: 55px; max-width: 55px;">';
				            content += '</td>';
				        content += '</tr>';

				        content += '<tr>';
				            content += '<td style="font-family: Arial; font-size: 8px; text-align: center; vertical-align:top;">';
				            content += '<label>' + lbl_packing_code + '</label>';
				            content += '</td>';
				        content += '</tr>';
			        content += '</table>';


	            content += '</td>';
	        content += '</tr>';
        content += '</table>';

        content += '</body>';
        content += '</html>';
        popup.document.write(content);
        popup.focus(); //required for IE
        popup.print();
        popup.close();
    });



  </script>
  @endsection
@endauth