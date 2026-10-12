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

  @section('title', 'Parts Preparatory')

  @section('content_page')
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

    .class-disabled{
      pointer-events: none;
    }
  </style>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Parts Preparatory</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Parts Preparatory</li>
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
                  <div class="col-3">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend">
                        <button type="button" class="btn btn-info" id="btn_scan_po"><i class="fa fa-qrcode"></i></button>
                      </div>
                      <input type="search" class="form-control" placeholder="Scan PO Number" id="txt_search_po_number" readonly><!-- value="450198990900010" -->
                    </div>
                  </div>
                  <div class="col-3 offset-3">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">PO No.</span>
                      </div>
                      <input id="txt_po_number_lbl" type="text" class="form-control" placeholder="---" readonly>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">Device</span>
                      </div>
                      <input id="txt_device_name" type="text" class="form-control" placeholder="---" readonly>
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


    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Lot Numbers</h3>
              </div>

              <!-- Start Page Content -->
              <div class="card-body">
                <div class="row">
                  <div class="col-12">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="kitting-tab" data-toggle="tab" href="#kitting" role="tab" aria-controls="kitting" aria-selected="true">Material Kitting & Issuance
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="sakidashi-tab" data-toggle="tab" href="#sakidashi" role="tab" aria-controls="sakidashi" aria-selected="false">Sakidashi Issuance
                        </a>
                      </li>
                      <li class="nav-item" hidden>
                        <a class="nav-link" id="warehouse-tab" data-toggle="tab" href="#warehouse" role="tab" aria-controls="warehouse" aria-selected="false">Warehouse Material Issuance
                        </a>
                      </li>
                    </ul>

                    <div class="tab-content" id="myTabContent">
                      <div class="tab-pane fade show active my-2" id="kitting" role="tabpanel" aria-labelledby="kitting-tab">
                        <div class="row">
                          <div class="col-3">
                            <div class="input-group input-group-sm mb-3">
                              <div class="input-group-prepend">
                                <button type="button" class="btn btn-info" id="btn_scan_transfer_slip"><i class="fa fa-qrcode"></i></button>
                              </div>
                              <input type="search" class="form-control" placeholder="Scan Transfer slip" id="txt_scan_transfer_slip" readonly><!-- value="450198990900010" -->
                            </div>
                          </div>
                        </div>
                        <div class="table-responsive">
                          <table class="table table-sm table-bordered table-hover" id="tbl_lot_numbers" style="min-width: 600px;">
                            <thead>
                              <tr class="bg-light">
                                <th>Action</th>
                                <th>Status</th>
                                <th>Transfer Slip</th>
                                <th>Item</th>
                                <th>Part Name/Code</th>
                                <th>USG</th>
                                <th>RQD</th>
                                <th>QTY</th>
                                <th>LOT</th>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
                          </table>
                        </div>
                      </div>
                      <div class="tab-pane fade my-2" id="sakidashi" role="tabpanel" aria-labelledby="sakidashi-tab">
                        <div class="row">
                          <div class="col-3">
                            <div class="input-group input-group-sm mb-3">
                              <div class="input-group-prepend">
                                <button type="button" class="btn btn-info" id="btn_scan_sakidashi_lot"><i class="fa fa-qrcode"></i></button>
                              </div>
                              <input type="search" class="form-control" placeholder="Scan lot/pair no." id="txt_scan_sakidashi_lot" readonly><!-- value="450198990900010" -->
                            </div>
                          </div>
                        </div>
                        <div class="table-responsive">
                          <table class="table table-sm table-bordered table-hover" id="tbl_sakidashi">
                            <thead>
                              <tr class="bg-light">
                                <th>Action</th>
                                <th>Status</th>
                                <th>Ctrl No.</th>
                                <th>Item Code</th>
                                <th>Contact type</th>
                                <th>Lot #/Pair #</th>
                                <th>Device Code</th>
                                <th>Req issuance qty</th>
                                <th>Reel qty</th>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
                          </table>
                        </div>
                      </div>
                      <div class="tab-pane fade my-2" id="warehouse" role="tabpanel" aria-labelledby="warehouse-tab" hidden>
                        <div class="table-responsive">
                          <table class="table table-sm table-bordered table-hover" id="tbl_warehouse" style="min-width: 1400px;">
                            <thead>
                              <tr class="bg-light">
                                <th>Action</th>
                                <th>Status</th>
                                <th>Issuance No.</th>
                                <th>Request No.</th>
                                <th>WHS Status</th>
                                <th>Product Destination</th>
                                <th>Line Destination</th>
                                <th>Item Code</th>
                                <th>Item Description</th>
                                <th>Classification</th>
                                <th>Issued Qty</th>
                                <th>Requested Qty</th>
                                <th>Served Qty</th>
                                <th>Requested By</th>
                                <th>Requested Date</th>
                                <th>Last Served By</th>
                                <th>Last Served Date</th>
                                <th>Remarks</th>
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
    <div class="modal fade" id="mdl_edit_material_details" tabindex="-1" role="dialog" aria-labelledby="cnptsmodal" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i class="fa fa-info-circle text-info"></i> Parts Preparation</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col text-right p3">
                <h4>Runcard #:<input type="text" id="txt_runcard_number" class="border-0 bg-transparent text-right" style="outline: none;width: 170px;" readonly></h4>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6 p-3 border rounded">
                <form id="frm_edit_material_details" method="post">
                  <div class="row">
                    <div class="col py-2">
                      <button hidden type="button" id="btn_edit_material_details_primary" class="btn btn-sm btn-link float-right"><i class="fa fa-edit"></i> Edit</button>
                      <span class="badge badge-secondary">1.</span> Material Handler Fill-in
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Material Name</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_material_name" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Use for Product (Name)</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_use_for_device" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">PO Number</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_po_number_label" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Assessment #</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_assessment_number" name="txt_assessment_number">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Assessed Qty</span>
                        </div>
                        <input type="number" class="form-control form-control-sm" id="txt_assessed_qty" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Special Instruction</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_special_instruction" name="txt_special_instruction">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Remarks</span>
                        </div>
                        <textarea class="form-control form-control-sm" rows="3" id="txt_material_remarks" name="txt_material_remarks"></textarea>
                      </div>
                    </div>
                  </div>
                  <div class="row" style="display: none;">
                    <div class="col text-right">
                      <button type="button" class="btn btn-sm btn-success" id="btn_save_material_details_primary">Save</button>
                      <button type="button" class="btn btn-sm btn-secondary" id="btn_cancel_material_details_primary">Cancel</button>
                    </div>
                  </div>
                </form>
                <br>
              </div><!-- col -->
              <div class="col-sm-6 p-3">
                <form id="frm_edit_material_details_for_parts_prep_member" method="post">
                  <div class="row">
                    <div class="col py-2">
                      <button type="button" id="btn_edit_material_details_for_parts_prep_member" class="btn btn-sm btn-link float-right"><i class="fa fa-edit"></i> Edit</button>
                      <span class="badge badge-secondary">2.</span> Parts Prep Fill-in
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Material Lot Number</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_lot_number" readonly>
                        <button type="button" class='btn btn-sm btn-info' id="btnScanQRCode" disabled><i class='fas fa-qrcode'></i></button>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Material Drawing Number</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_drawing_number" readonly>
                        {{-- <button type="button" class="btn btn-sm btn-dark" id="btnVIewSGCDrawing" disabled><i class="fas fa-eye"></i></button> --}}
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Revision Number</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_drawing_number_revision_number" name="txt_drawing_number_revision_number" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">SG-C Docs Number</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_sgc_docs_number" name="txt_sgc_docs_number" readonly>
                        <button type="button" class="btn btn-sm btn-dark" id="btnVIewSGCDrawing" disabled><i class="fas fa-eye"></i></button>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Revision Number</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_sgc_docs_number_revision_number" name="txt_sgc_docs_number_revision_number" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Other Docs Number</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_other_docs_number" name="txt_other_docs_number">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Remarks</span>
                        </div>
                        <textarea class="form-control form-control-sm" rows="3" id="txt_parts_prep_remarks" name="txt_parts_prep_remarks"></textarea>
                      </div>
                    </div>
                  </div>
                  <div class="row" style="display: none;">
                    <div class="col text-right">
                      <button type="button" class="btn btn-sm btn-success" id="btn_save_material_details_primary_parts_prep">Save</button>
                      <button type="button" class="btn btn-sm btn-secondary" id="btn_cancel_material_details_primary_parts_prep">Cancel</button>
                    </div>
                  </div>
                </form>
                <br>
              </div><!-- col -->
            </div>
            <div class="row">
              <div class="col p-3">
                <!-- <button hidden type="button" class="btn btn-sm btn-info float-right mb-1" id="btn_setup_stations"><i class="fa fa-cog"></i> Set-up stations</button> -->
                <span class="badge badge-secondary">3.</span> Stations
                <div class="table-responsive">
                  <table class="table table-sm small table-bordered table-hover" id="tbl_parts_prep_stations" style="min-width: 600px;width: 100%;">
                    <thead>
                      <tr class="bg-light">
                        <th>Action</th>
                        <th>Step</th>
                        <th>Station</th>
                        <th>Sub-station</th>
                        <th>Print code&nbsp;&nbsp; <i class="fa fa-info-circle" title="Only for Printing station"></th>
                        <th>Machine</th>
                        <th>Date</th>
                        <th>Operator</th>
                        <th>Input</th>
                        <th>Output</th>
                        <th>NG QTY</th>
                        <th>MOD</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div><!-- col -->
            </div>
            <br>
            <div class="row">
              <div class="col">
                <form id="frm_edit_material_details_secondary">
                  @csrf
                  <div class="row">
                    <div class="col px-3 pb-0">
                      <button type="button" class="btn btn-sm btn-link float-right" id="btn_edit_material_details_secondary"><i class="fa fa-edit"></i> Edit</button>
                      <span class="badge badge-secondary">4.</span> Verification
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-6 px-3">
                      Output + NG not tally?
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Discrepant qty</span>
                        </div>
                        <select class="form-control form-control-sm" id="txt_discrepant_qty_sign" name="txt_discrepant_qty_sign">
                          <option>+</option>
                          <option>-</option>
                        </select>
                        <input type="number" class="form-control form-control-sm" id="txt_discrepant_qty" name="txt_discrepant_qty">
                      </div>
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Analysis</span>
                        </div>
                        <textarea class="form-control form-control-sm" rows="2" id="txt_analysis" name="txt_analysis"></textarea>
                      </div>
                    </div>
                    <div class="col-6 px-3">
                      Treatment
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-25">
                          <span class="input-group-text w-100" id="basic-addon1">Recount</span>
                        </div>
                        <input type="number" class="form-control form-control-sm" id="txt_recount_ok" name="txt_recount_ok">
                        <label class="form-control form-control-sm" style="background-color: #eee;font-weight: normal;">OK</label>
                        <input type="number" class="form-control form-control-sm" id="txt_recount_ng" name="txt_recount_ng">
                        <label class="form-control form-control-sm" style="background-color: #eee;font-weight: normal;">NG</label>
                      </div>
                      If short qty: complete the lot under 
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100 text-left" id="basic-addon1">Runcard #:</span>
                        </div>
                        <input maxlength="13" class="form-control form-control-sm" rows="2" id="txt_other_runcard_number" name="txt_other_runcard_number" list="dl_other_runcard_number">
                      </div>
                      <datalist id="dl_other_runcard_number"></datalist>
                      <div class="row">
                        <div class="col text-right">
                          <button type="button" class="btn btn-sm btn-success" id="btn_save_material_details_secondary">Save</button>
                          <button type="button" class="btn btn-sm btn-secondary" id="btn_cancel_material_details_secondary">Cancel</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <br>
            <br>
            <div class="row">
              <div class="col-6 offset-6 p-3">
                <div class="input-group input-group-sm mb-3">
                  <div class="input-group-prepend w-50">
                    <span class="input-group-text w-100" id="basic-addon1"><i class="fa fa-check-circle"></i>&nbsp; Prod. Supervisor</span>
                  </div>
                  <input type="text" class="form-control form-control-sm" id="txt_checked_by_prod" readonly>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="txt_wbs_table" id="txt_wbs_table">
            <input type="hidden" name="txt_parts_preps_id_query" id="txt_parts_preps_id_query">
            <input type="hidden" name="txt_wbs_kit_issuance_id_query" id="txt_wbs_kit_issuance_id_query">
            <input type="hidden" name="txt_wbs_kit_issuance_device_code_query" id="txt_wbs_kit_issuance_device_code_query">
            <button type="button" class="btn btn-sm btn-success" id="btn_approve_prod"><i class="fa fa-check-circle"></i> Prod Approved</button>
            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!-- /.Modal -->

    <!-- Modal -->
    <div class="modal fade" id="mdl_edit_partsprep_station_details" tabindex="-1" role="dialog" aria-labelledby="cnptsmodal" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i class="fas fa-object-group text-info"></i> Stations</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="frm_edit_partsprep_station_details">
              @csrf
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Step</span>
                    </div>
                    <input type="number" class="form-control form-control-sm" id="txt_edit_partsprep_station_step" readonly>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Station</span>
                    </div>
                    <input type="text" class="form-control form-control-sm" id="txt_edit_partsprep_station_station" readonly>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Sub-station</span>
                    </div>
                    <input type="text" class="form-control form-control-sm" id="txt_edit_partsprep_station_substation" readonly>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Date</span>
                    </div>
                    <input type="date" class="form-control form-control-sm" id="txt_edit_partsprep_station_date" name="txt_edit_partsprep_station_date" readonly>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Operator</span>
                    </div>
                    <input type="text" class="form-control form-control-sm" id="txt_edit_partsprep_station_operator" name="txt_edit_partsprep_station_operator" readonly>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Machine</span>
                    </div>
                    <select class="form-control form-control-sm class-disabled" id="txt_edit_partsprep_station_machine" name="txt_edit_partsprep_station_machine" required></select>
                    <div class="input-group-append">
                      <button class="btn btn-info" type="button" title="Scan code" id="btn_scan_machine_code"><i class="fa fa-qrcode"></i></button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Print Code&nbsp;&nbsp; <i class="fa fa-info-circle" title="Required only for Printing station"></i></span>
                    </div>
                    <input type="text" class="form-control form-control-sm" id="txt_edit_partsprep_station_print_code" name="txt_edit_partsprep_station_print_code">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Input</span>
                    </div>
                    <input type="number" class="form-control form-control-sm" id="txt_edit_partsprep_station_input" name="txt_edit_partsprep_station_input" readonly>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Output</span>
                    </div>
                    <input type="number" class="form-control form-control-sm" id="txt_edit_partsprep_station_output" name="txt_edit_partsprep_station_output" readonly>
                  </div>
                </div>
              </div>
              <hr class="row">
              <div class="row" id="row_ng" data-mod_options = "">
                <div class="col">
                  <div class="row">
                    <div class="col">

              <!--         <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-25">
                          <span class="input-group-text w-100">NG qty</span>
                        </div>
                        <input type="number" class="form-control form-control-sm txt_edit_partsprep_station_ng" id="txt_edit_partsprep_station_ng" name="txt_edit_partsprep_station_ng">
                        <label class="form-control form-control-sm" style="background-color: #eee;font-weight: normal;">MOD</label>
                        <select class="form-control form-control-sm txt_edit_partsprep_station_mod" id="txt_edit_partsprep_station_mod" name="txt_edit_partsprep_station_mod"></select>
                        <div class="input-group-append">
                          <button type="button" class="btn btn-danger bnt-sm txt_edit_partsprep_station_remove_ng"><i class="fa fa-times"></i></button>
                        </div>
                      </div> -->

           <!--            <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">NG Qty</span>
                        </div>
                      </div>
                    </div>
                    <div class="col-7">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">MOD</span>
                        </div>
                      </div>
                    </div> -->
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <button type="button" id="btn_edit_partsprep_station_add_ng" class="btn btn-link btn-sm"><i class="fa fa-plus"></i> Add NG</button>
              </div>
            </div>
            </form>
          </div>
          <div class="modal-footer">
            <input type="hidden" id="txt_partsprep_station_id_query" name="txt_partsprep_station_id_query" form="frm_edit_partsprep_station_details">
            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-sm btn-success" id="btn_save_partsprep_station_stations" form="frm_edit_partsprep_station_details">Save</button>
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
            <input type="text" id="txt_employee_number_scanner" class="hidden_scanner_input" autocomplete="off">
          </div>
        </div>
      </div>
    </div>
    <!-- /.Modal -->

    <!-- Modal -->
    <div class="modal fade" id="mdl_qrcode_scanner" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
              Please scan the code.
              <br>
              <br>
              <h1><i class="fa fa-qrcode fa-lg"></i></h1>
            </div>
            <input type="text" id="txt_qrcode_scanner" class="hidden_scanner_input" autocomplete="off">
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
  <script type="text/javascript">
    let matLotNumber;
    let employeeNumberAllowed;
    //--------------------
    //prevent typing of barcode/qr code
    //--------------------
    $(document).on('keydown',function(e){
      if ($("#txt_employee_number_scanner").is(":focus")) {
        setTimeout(function() {
          $("#txt_employee_number_scanner").val('');
        }, 500);
      }
    });
    //--------------------
    //prevent typing of barcode/qr code
    //--------------------
    
    let dt_lot_numbers, dt_setup_stations, dt_parts_prep_stations,dt_sakidashi,dt_warehouse;
    $(document).ready(function () {
      setTimeout(function() {
        $('.nav-link').find('.fa-bars').closest('a').click();
      }, 100);
  
      $(document).on('show.bs.modal', '.modal', function () {
          var zIndex = 1040 + (10 * $('.modal:visible').length);
          $(this).css('z-index', zIndex);
          setTimeout(function() {
              $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
          }, 0);
      });

      $('input').each(function(i, obj) {
        if (!this.hasAttribute("placeholder")) {
          if( $(this).prop('type') == 'number' ){
            $(this).prop('placeholder','0');
          }
          if( $(this).prop('type') == 'text' ){
            $(this).prop('placeholder','--');
          }
        }
      });
      $('textarea').each(function(i, obj) {
        if (!this.hasAttribute("placeholder")) {
          $(this).prop('placeholder','...');
        }
      });

      bsCustomFileInput.init();
      // fn_select_list_partsprep_station_machines();
      fn_select_list_partsprep_station_mod();
      //-----------------------
      // $(document).on('keyup','#txt_search_po_number',function(e){
      //   if( e.keyCode == 13 ){
      //     search_po_number($(this));
      //   }
      // });
      //-----------------------
      dt_lot_numbers      = $('#tbl_lot_numbers').DataTable({
        "processing" : true,
          "serverSide" : true,
          "ajax" : {
            url: "select_partspreparatory_materials",
            data: function (param){
                param.po_number                       = $("#txt_po_number_lbl").val();
                param.txt_scan_transfer_slip          = $("#txt_scan_transfer_slip").val();
            }
          },
          "columns":[
            { "data" : "raw_action", orderable:false, searchable:false, width: '50px' },
            { "data" : "raw_status" },
            { "data" : "issue_no" },
            { "data" : "item" },
            { "data" : "item_desc" },
            { "data" : "raw_usage" },
            { "data" : "raw_rqd_qty" },
            { "data" : "issued_qty" },
            { "data" : "lot_no" },
          ],
          paging: false,
          order: [[1, "asc"]],
          "rowCallback": function(row,data,index ){
            if( $(row).html().toLowerCase().indexOf('done verification')>0 ){
              $(row).addClass('table-success');
            }
            if( $(row).html().toLowerCase().indexOf('returned')>0 ){
              $(row).addClass('table-warning');
            }
          },
          "drawCallback": function(row,data,index ){
          },
      });//end of DataTable
      //-----------------------
      //-----------------------
      //-----------------------
      dt_sakidashi      = $('#tbl_sakidashi').DataTable({
        "processing" : true,
          "serverSide" : true,
          "ajax" : {
            url: "view_sakidashi_parts_prep",
            data: function (param){
                param.po_number               = $("#txt_po_number_lbl").val();
                param.txt_scan_sakidashi_lot  = $("#txt_scan_sakidashi_lot").val();
            }
          },
          bAutoWidth: false,
          paging: false,
          "columns":[
            { "data" : "action", orderable:false, searchable:false },
            { "data" : "status" },
            { "data" : "issuance_no" },
            { "data" : "tbl_wbs_sakidashi_issuance_item.item" },
            { "data" : "tbl_wbs_sakidashi_issuance_item.item_desc" },
            { "data" : "tbl_wbs_sakidashi_issuance_item.lot_no" },
            { "data" : "device_code" },
            { "data" : "tbl_wbs_sakidashi_issuance_item.required_qty" },
            { "data" : "tbl_wbs_sakidashi_issuance_item.issued_qty" },
          ],
          order: [[1, "asc"]],
          "rowCallback": function(row,data,index ){
            if( $(row).html().toLowerCase().indexOf('done verification')>0 ){
              $(row).addClass('table-success');
            }
            if( $(row).html().toLowerCase().indexOf('returned')>0 ){
              $(row).addClass('table-warning');
            }
          },
          "drawCallback": function(row,data,index ){
          },
      });//end of DataTable
      //-----------------------
      //-----------------------
      //-----------------------
      // dt_warehouse      = $('#tbl_warehouse').DataTable({
      //   "processing" : true,
      //     "serverSide" : true,
      //     "ajax" : {
      //       url: "view_warehouse_parts_prep",
      //       data: function (param){
      //           param.po_number = $("#txt_po_number_lbl").val();
      //           param.device_code = $("#txt_device_name").attr('data-device_code');
      //       }
      //     },
      //     bAutoWidth: false,
      //     paging: false,
      //     "columns":[
      //       { "data" : "action", orderable:false, searchable:false, width: '30px' },
      //       { "data" : "status" },
      //       { "data" : "issuance_no" },
      //       { "data" : "request_no" },
      //       { "data" : "tbl_request_summary.status" },
      //       { "data" : "tbl_request_summary.destination" },
      //       { "data" : "tbl_request_summary.line" },

      //       { "data" : "item" },
      //       { "data" : "item_desc" },

      //       { "data" : "tbl_request_detail.classification" },
      //       { "data" : "tbl_request_detail.issuedqty" },
      //       { "data" : "tbl_request_detail.requestqty" },
      //       { "data" : "tbl_request_detail.servedqty" },
      //       { "data" : "tbl_request_detail.requestedby" },
      //       { "data" : "tbl_request_detail.created_at" },
      //       { "data" : "tbl_request_detail.last_served_by" },
      //       { "data" : "tbl_request_detail.last_served_date" },
      //       { "data" : "tbl_request_detail.remarks" },
      //     ],
      //     order: [[1, "asc"]],
      //     "rowCallback": function(row,data,index ){
      //       if( $(row).html().toLowerCase().indexOf('done verification')>0 ){
      //         $(row).addClass('table-success');
      //       }
      //       if( $(row).html().toLowerCase().indexOf('returned')>0 ){
      //         $(row).addClass('table-warning');
      //       }
      //     },
      //     "drawCallback": function(row,data,index ){
      //     },
      // });//end of DataTable
      //-----------------------
      //-----------------------
      //-----------------------
      $(document).on('click','.btn_material_open_details',function(e){
        var data_arr = [];
        data_arr['material_id']     = $(this).closest('tr').find('.col_material_id').val();
        data_arr['wbs_table']       = 1;
        fn_select_material_details(data_arr);
      });
      $(document).on('click','.btn_sakidashi_material_open_details',function(e){
        var data_arr = [];
        data_arr['material_id']     = $(this).closest('tr').find('.col_material_id').val();
        data_arr['wbs_table']       = 2;
        fn_select_material_details(data_arr);
      });
      $(document).on('click','.btn_warehouse_material_open_details',function(e){
        var data_arr = [];
        data_arr['material_id']     = $(this).closest('tr').find('.col_material_id').val();
        data_arr['wbs_table']       = 3;
        fn_select_material_details(data_arr);
      });
      $(document).on('click','#btn_edit_material_details_primary',function(e){
        readonly_material_details_primary(false);
      });
      $(document).on('click','#btn_cancel_material_details_primary',function(e){
        readonly_material_details_primary(true);
      });
      $(document).on('click','#btn_edit_material_details_for_parts_prep_member',function(e){
        readonly_material_details_for_parts_prep(false);
        getSGCDrawing($('#txt_wbs_kit_issuance_device_code_query').val(), $('#txt_use_for_device').val());
        if($('#txt_sgc_docs_number').val() == ''){
          $('#btnVIewSGCDrawing').prop('disabled', true).val('1')
        }else{
          $('#btnVIewSGCDrawing').prop('disabled', false).val('0')
        }
      });
      $(document).on('click','#btn_cancel_material_details_primary_parts_prep',function(e){
        readonly_material_details_for_parts_prep(true);
      });
      $(document).on('click','#btn_edit_material_details_secondary',function(e){
        readonly_material_details_secondary(false);
        fn_select_list_runcard_numbers();
      });
      $(document).on('click','#btn_cancel_material_details_secondary',function(e){
        readonly_material_details_secondary(true);
      });
      //-----------------------
      $('#btnVIewSGCDrawing').click(function (e) { 
        e.preventDefault();
        $('#btnVIewSGCDrawing').val('1')
        window.open("http://rapid/ACDCS/prdn_home_cnppts?doc_no=" + $('#txt_sgc_docs_number').val())
        $('#txt_sgc_docs_number').addClass('bg-success')
      });
      $(document).on('click','#btn_save_material_details_primary',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','#frm_edit_material_details').modal('show');
      });
      $(document).on('click','#btn_save_material_details_primary_parts_prep',function(e){
        $('#txt_employee_number_scanner').val('');
        
        console.log($('#txt_sgc_docs_number').val());
        if($('#txt_sgc_docs_number').val() != '' && $('#btnVIewSGCDrawing').val() == 1){
          $('#mdl_employee_number_scanner').attr('data-formid','#frm_edit_material_details_for_parts_prep_member').modal('show');
        }else{
            alert('View SG-C Document Number to proceed!')
        }
      });
      $('#mdl_edit_material_details').on('hidden.bs.modal', function(event) {
          $('#txt_sgc_docs_number').removeClass('bg-success');
      });

      $(document).on('click','#btn_save_setup_stations',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','save_setup_stations').modal('show');
      });
      $(document).on('click','#btn_save_material_details_secondary',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','#frm_edit_material_details_secondary').modal('show');
      });
      $(document).on('click','#btn_approve_prod',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','save_approve_prod').modal('show');
      });
      $(document).on('click','#btn_save_partsprep_station_stations',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','#frm_edit_partsprep_station_details').modal('show');
      });
      //-----------------------
      dt_parts_prep_stations = $('#tbl_parts_prep_stations').DataTable({
        "processing" : true,
          "serverSide" : true,
          "ajax" : {
            url: "select_partspreparatory_stations",
            data: function (param){
                param.parts_prep_id_query          = $("#txt_parts_preps_id_query").val();
                param.enable_edit                  = false;//prev true
            }
          },
          
          "columns":[
            { "data" : "raw_action", orderable:false, searchable:false },
            { "data" : "step_num" },
            { "data" : "station.name" },
            { "data" : "sub_station.name" },
            { "data" : "print_code" },
            { "data" : "raw_machine" },
            { "data" : "created_at" },
            { "data" : "raw_created_by" },
            { "data" : "qty_input" },
            { "data" : "qty_output" },
            { "data" : "raw_qty_ng" },
            { "data" : "raw_mod" },
          ],
          order: [[1, "asc"]],
          paging: false,
          info: false,
          searching: false,
          "rowCallback": function(row,data,index ){
            if( $('#mdl_edit_material_details').attr('data-status') == 4 ){
              if( $(row).find('.btn_edit_partsprep_station').prop('disabled') == false ){
                $(row).find('.btn_edit_partsprep_station').prop('disabled',false);
              }
            }
            if( $(row).find('td:eq(6)').text() ){
              $(row).find('.btn_edit_partsprep_station').prop('disabled',true);
            }
            else{
              $('#btn_edit_material_details_secondary').prop('disabled',true);
            }
          },
          "drawCallback": function(row,data,index ){
            if( dt_parts_prep_stations.rows().count() < 1 ){
              $('#btn_edit_material_details_secondary').prop('disabled',true);
            }
          },
      });//end of DataTable
      //-----------------------
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
                case 'save_approve_prod':
                  save_approve_prod();
                break;

                default:
                break;
              }
            }
          }
        }
        else if( ($("#mdl_qrcode_scanner").data('bs.modal') || {})._isShown ){
          $('#txt_qrcode_scanner').focus();
          if( e.keyCode == 13 ){
            $('#mdl_qrcode_scanner').modal('hide');
            var formid = $("#mdl_qrcode_scanner").attr('data-formid');

            if ( ( formid ).indexOf('#') > -1){
              $( formid ).submit();
            }
            else{
              switch( formid ){
                case 'fn_scan_machine_code':
                  $('#txt_edit_partsprep_station_machine option').attr('selected',false);
                  var val = $('#txt_qrcode_scanner').val().toLowerCase();
                  var option = $('#txt_edit_partsprep_station_machine option[data-code="'+val+'"]');
                  if (option.length > 0) {
                    option.attr('selected',true);
                  } else {
                    alert('Scanned machine code not found.');
                  }
                break;

                case 'fn_scan_po_number':
                  var val = $('#txt_qrcode_scanner').val();
                  $('#txt_search_po_number').val(val);
                  search_po_number( $('#txt_search_po_number') );
                break;

                case 'fn_scan_transfer_slip':
                  var val = $('#txt_qrcode_scanner').val();
                  $('#txt_scan_transfer_slip').val(val);
                  dt_lot_numbers.ajax.reload();
                break;

                case 'fn_scan_sakidashi_lot':
                  var val = $('#txt_qrcode_scanner').val();
                  $('#txt_scan_sakidashi_lot').val(val);
                  dt_sakidashi.ajax.reload();
                break;

                case 'fn_scan_qr_code':
                  var val = $('#txt_qrcode_scanner').val();
                  console.log('txt_qrcode_scanner', val);
                  console.log('matLotNumber', matLotNumber);
                  if(matLotNumber == val){
                    $('#txt_lot_number').val(val);
                    toastr.success('Lot number matched');

                  }
                  else{
                    toastr.error('Material lot number does not match.');
                  }
                break;

                default:
                break;
              }
            }            
          }//key
        }
      });
      //-----------------------
      $('#frm_edit_material_details').submit(function(e){
        e.preventDefault();

        $.ajax({
          'data'      : $(this).serialize()+'&txt_parts_preps_id_query='+$("#txt_parts_preps_id_query").val()+'&txt_wbs_kit_issuance_id_query='+$("#txt_wbs_kit_issuance_id_query").val()+'&txt_runcard_number='+$("#txt_runcard_number").val()+'&txt_employee_number_scanner='+$("#txt_employee_number_scanner").val()+'&_token={{ csrf_token() }}',
          'type'      : 'post',
          'dataType'  : 'json',
          'url'       : 'insert_material_details',
          success     : function(data){
            // toastr.success('Material Process was succesfully saved!');
            show_alert(data['icon'],data['title'],data['body'],data['i']);
            if( data['i'] == 2 ){
              return;
            }
            readonly_material_details_primary(true);
            //---
            var data_arr = [];
            data_arr['material_id']     = $('#txt_wbs_kit_issuance_id_query').val();
            data_arr['wbs_table']       = $('#txt_wbs_table').val();
            fn_select_material_details(data_arr);
            //---
            dt_lot_numbers.ajax.reload();
          },
          completed     : function(data){
          },
          error     : function(data){
          },
        });
      });

      $('#frm_edit_material_details_for_parts_prep_member').submit(function(e){
        
        e.preventDefault();

        if($('#txt_lot_number').val() === ''){
          toastr.error('Please scan the material lot number.');
          return;
        }
        $.ajax({
          'data'      : $(this).serialize()+'&txt_parts_preps_id_query='+$("#txt_parts_preps_id_query").val()+'&txt_employee_number_scanner='+$("#txt_employee_number_scanner").val()+'&_token={{ csrf_token() }}',
          'type'      : 'post',
          'dataType'  : 'json',
          'url'       : 'insert_material_details_parts_prep',
          success     : function(data){
            // toastr.success('Material Process was succesfully saved!');
            show_alert(data['icon'],data['title'],data['body'],data['i']);
            if( data['i'] == 2 ){
              return;
            }
            readonly_material_details_primary(true);
            //---
            var data_arr = [];
            data_arr['material_id']     = $('#txt_wbs_kit_issuance_id_query').val();
            data_arr['wbs_table']       = $('#txt_wbs_table').val();
            fn_select_material_details(data_arr);
            dt_lot_numbers.ajax.reload();
          },
          completed     : function(data){
          },
          error     : function(data){
          },
        });
      });

      $('#frm_edit_material_details_secondary').submit(function(e){
        e.preventDefault();

        var status = 5;
        if( $('#txt_discrepant_qty').val() > 0 ){
          status = 6;
          if( !$('#txt_analysis').val() ){
            show_alert('<i class="fa fa-exclamation-triangle text-warning"></i>','Message','Analysis is required.',0);
            return;
          }
        }

        if( $('#txt_discrepant_qty_sign').val() == '-' ){
          if( !$('#txt_other_runcard_number').val() ){
            show_alert('<i class="fa fa-exclamation-triangle text-warning"></i>','Message','Runcard number in Process #4 is required.',0);
            return;
          }
        }

        $.ajax({
          'data'      : $(this).serialize()+'&txt_parts_preps_id_query=' + $("#txt_parts_preps_id_query").val()+'&txt_employee_number_scanner=' + $("#txt_employee_number_scanner").val()+'&status=' + status+'&_token={{ csrf_token() }}',
          'type'      : 'post',
          'dataType'  : 'json',
          'url'       : 'update_material_details_secondary',
          success     : function(data){
            show_alert(data['icon'],data['title'],data['body'],data['i']);
            if( data['i'] == 2 ){
              return;
            }
            readonly_material_details_secondary(true);
            //---
            var data_arr = [];
            data_arr['material_id']     = $('#txt_wbs_kit_issuance_id_query').val();
            data_arr['wbs_table']       = $('#txt_wbs_table').val();
            fn_select_material_details(data_arr);
            dt_lot_numbers.ajax.reload();
            dt_sakidashi.ajax.reload();
          },
          completed     : function(data){
          },
          error     : function(data){
          },
        });
      });

















      //-----------------------
      //-----------------------
      //-----------------------

      $(document).on('click','.btn_edit_partsprep_station',function(e){
        var data_arr = [];
        data_arr['col_partsprep_station_id']     = $(this).closest('tr').find('.col_partsprep_station_id').val();
        // data_arr['material_code']   = $(this).closest('tr').find('.col_material_code').val();
        fn_select_partsprep_station_details(data_arr);
      });
      //-----------------------
      $(document).on('click','#btn_scan_machine_code',function(e){
        $('#txt_qrcode_scanner').val('');
        $('#mdl_qrcode_scanner').attr('data-formid','fn_scan_machine_code').modal('show');
      });
      $(document).on('click','#btn_scan_po',function(e){
        $('#txt_search_po_number').val('');
        $('#txt_qrcode_scanner').val('');
        $('#mdl_qrcode_scanner').attr('data-formid','fn_scan_po_number').modal('show');
      });
      $(document).on('click','#btn_scan_transfer_slip',function(e){
        $('#txt_scan_transfer_slip').val('');
        dt_lot_numbers.ajax.reload();
        $('#txt_qrcode_scanner').val('');
        $('#mdl_qrcode_scanner').attr('data-formid','fn_scan_transfer_slip').modal('show');
      });
      $(document).on('click','#btn_scan_sakidashi_lot',function(e){
        $('#txt_scan_sakidashi_lot').val('');
        dt_sakidashi.ajax.reload();
        $('#txt_qrcode_scanner').val('');
        $('#mdl_qrcode_scanner').attr('data-formid','fn_scan_sakidashi_lot').modal('show');
      });
      //-----------------------
      $(document).on('click','#btn_save_partsprep_station_stations',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','#frm_edit_partsprep_station_details').modal('show');
      });

      $('#btn_edit_partsprep_station_add_ng').click(function(){
        partsprep_station_add_ng('',0,0);
      })

      $(document).on('click','.btn_edit_partsprep_station_remove_ng',function(){
        $(this).closest('.row').remove();
      });

      $('#frm_edit_partsprep_station_details').submit(function(e){
        e.preventDefault();
        if ($("#txt_edit_partsprep_station_machine option:selected").text().toLowerCase().indexOf('print') > -1){
          if( !$('#txt_edit_partsprep_station_print_code').val() ){
            show_alert('<i class="fa fa-exclamation-triangle text-warning"></i>','Message','Print code is required.',0);
            return;
          }
        }
        if( $('#txt_edit_partsprep_station_output').val() < 0 ){
            show_alert('<i class="fa fa-exclamation-triangle text-warning"></i>','Message','Invalid output qty.',0);
            return;
        }
        console.log('$("#txt_employee_number_scanner").val() ', $("#txt_employee_number_scanner").val());
        console.log('employeeNumberAllowed ', employeeNumberAllowed);
        console.log('in array ', $.inArray($("#txt_employee_number_scanner").val(), employeeNumberAllowed));
        if ($.inArray($("#txt_employee_number_scanner").val(), employeeNumberAllowed) == -1) {
          show_alert('<i class="fa fa-exclamation-triangle text-warning"></i>','Message','Employee number is not allowed.',0);
          return;
        }

        var txt_edit_partsprep_station_ng = [];
        var txt_edit_partsprep_station_mod = [];
        var is_invalid = 0;
        $('#row_ng .txt_edit_partsprep_station_ng').each(function(){
          if( $(this).val() < 1 ){
            is_invalid = 1;
            return;
          }
          if( $(this).closest('.row').find('.txt_edit_partsprep_station_mod').val() < 1 ){
            is_invalid = 1;
            return;
          }
          txt_edit_partsprep_station_ng.push( $(this).val() );
          txt_edit_partsprep_station_mod.push( $(this).closest('.row').find('.txt_edit_partsprep_station_mod').val() );
        });

        if(is_invalid){
            show_alert('<i class="fa fa-exclamation-triangle text-warning"></i>','Message','Invalid NG qty/No MOD',0);
            return;
        }


        $.ajax({
          'data'      : $(this).serialize()+'&txt_employee_number_scanner=' + $("#txt_employee_number_scanner").val() + '&txt_edit_partsprep_station_ng=' + txt_edit_partsprep_station_ng + '&txt_edit_partsprep_station_mod=' + txt_edit_partsprep_station_mod,
          'type'      : 'post',
          'dataType'  : 'json',
          'url'       : 'update_partsprep_station_details',
          success     : function(data){
            show_alert(data['icon'],data['title'],data['body'],data['i']);
            if( data['i'] == 2 ){
              return;
            }
            //---
            var data_arr = [];
            data_arr['material_id']     = $('#txt_wbs_kit_issuance_id_query').val();
            data_arr['wbs_table']       = $('#txt_wbs_table').val();
            fn_select_material_details(data_arr);
            dt_lot_numbers.ajax.reload();
            $('#mdl_edit_partsprep_station_details').modal('hide');
          },
          completed     : function(data){
          },
          error     : function(data){
          },
        });
      });

      $(document).on('keyup change click','.txt_edit_partsprep_station_ng, .btn_edit_partsprep_station_remove_ng',function(e){
        var vin = $('#txt_edit_partsprep_station_input').val();
        var vng = 0;
        $('#row_ng .txt_edit_partsprep_station_ng').each(function(){
          vng = (vng*1)+($(this).val()*1);
        });

        var vout = vin-vng;
        $('#txt_edit_partsprep_station_output').val(vout);
      });

      $('#btnScanQRCode').on('click', function(){
        $('#txt_qrcode_scanner').val('');
        $('#mdl_qrcode_scanner').attr('data-formid','fn_scan_qr_code').modal('show');
      })

    });//doc
    //-----------------------
    //-----------------------
    //-----------------------
    function search_po_number(element){
        $('#txt_po_number').val('');
        $('#txt_device_name').val('').attr('data-device_code','');
        var data = {
          'po_number' : $('#txt_search_po_number').val(),
        }
        $.ajax({
          'data'      : data,
          'type'      : 'get',
          'dataType'  : 'json',
          'url'       : 'select_po_details',
          success     : function(data){
            if ($.trim(data)){
              $('#txt_po_number_lbl').val( data[0]['po'] );
              $('#txt_device_name').val( data[0]['kit_issuance']['device_name'] ).attr('data-device_code',data[0]['kit_issuance']['device_code']);
            }
            dt_lot_numbers.ajax.reload();
            dt_sakidashi.ajax.reload();
            // dt_warehouse.ajax.reload();
          },
          completed     : function(data){
          },
          error     : function(data){
          },
        });
    }
    function reset_material_details_primary(){
      $('#txt_material_name').val('');
      $('#txt_use_for_device').val('');
      $('#txt_po_number_label').val('');
      $('#txt_assessment_number').val('');
      $('#txt_assessed_qty').val('');
      $('#txt_special_instruction').val('');
      $('#txt_material_remarks').val('');

      readonly_material_details_primary(true);
    }
    function readonly_material_details_primary(v){
      $('#txt_assessment_number').prop('readonly',v);
      $('#txt_special_instruction').prop('readonly',v);
      $('#txt_material_remarks').prop('readonly',v);

      $('#btn_save_material_details_primary').closest('.row').hide();
      if(!v){
        $('#btn_save_material_details_primary').closest('.row').show();
      }
    }
    //---
    function reset_material_details_for_parts_prep(){
      $('#txt_lot_number').val('');
      $('#txt_drawing_number').val('');
      $('#txt_drawing_number_revision_number').val('');
      $('#txt_sgc_docs_number').val('');
      $('#txt_sgc_docs_number_revision_number').val('');
      $('#txt_other_docs_number').val('');
      $('#txt_parts_prep_remarks').val('');

      readonly_material_details_for_parts_prep(true);
    }
    function readonly_material_details_for_parts_prep(v){
      // $('#txt_sgc_docs_number').prop('readonly',v);
      // $('#txt_sgc_docs_number_revision_number').prop('readonly',v);
      $('#txt_other_docs_number').prop('readonly',v);
      $('#txt_parts_prep_remarks').prop('readonly',v);
      $('#txt_parts_prep_remarks').prop('readonly',v);
      $('#btnScanQRCode').prop('disabled',v);

      $('#btn_save_material_details_primary_parts_prep').closest('.row').hide();
      if(!v){
        $('#btn_save_material_details_primary_parts_prep').closest('.row').show();
      }
    }
    //---
    function reset_material_details_secondary(){
      $('#txt_discrepant_qty_sign').val('0');
      $('#txt_discrepant_qty').val('');
      $('#txt_analysis').val('');
      $('#txt_recount_ok').val('');
      $('#txt_recount_ng').val('');
      $('#txt_other_runcard_number').val('');

      readonly_material_details_secondary(true);
    }
    function readonly_material_details_secondary(v){
      $('#txt_discrepant_qty_sign').prop('disabled',v);
      $('#txt_discrepant_qty').prop('readonly',v);
      $('#txt_analysis').prop('readonly',v);
      $('#txt_recount_ok').prop('readonly',v);
      $('#txt_recount_ng').prop('readonly',v);
      $('#txt_other_runcard_number').prop('readonly',v);

      $('#btn_save_material_details_secondary').closest('.row').hide();
      if(!v){
        $('#btn_save_material_details_secondary').closest('.row').show();
      }
    }
    //---
    function fn_select_material_details(data_arr){
      $('#btn_edit_material_details_for_parts_prep_member').prop('hidden',true);//user access
      $('#btn_edit_material_details_secondary').prop('hidden',true);//user access
      $('#btn_approve_prod').prop('hidden',true);//user access

      $('#txt_wbs_table').val( data_arr['wbs_table'] );
      $('#txt_parts_preps_id_query').val('');
      $('#txt_wbs_kit_issuance_id_query').val('');
      $('#txt_wbs_kit_issuance_device_code_query').val('');

      $('#txt_runcard_number').val('');

      $('#btn_edit_material_details_for_parts_prep_member').prop('disabled',true);
      $('#btn_edit_material_details_secondary').prop('disabled',true);
      $('#btn_setup_stations').prop('disabled',false);

      $('#txt_checked_by_prod').val('');
      $('#btn_approve_prod').prop('disabled',true);

      reset_material_details_primary();
      reset_material_details_for_parts_prep();
      reset_material_details_secondary();

      var data = {
        'material_id'     : data_arr['material_id'],
        'wbs_table'       : data_arr['wbs_table'],
        'device_name_query'   : $('#txt_device_name').val(),//for acdcs drawing only
      }
      $.ajax({
        'data'      : data,
        'type'      : 'get',
        'dataType'  : 'json',
        'url'       : 'select_partspreparatory_material_details',
        success     : function(data){
          if ($.trim(data)){

            if( data_arr['wbs_table'] == 1 ){//kit
              if( $.trim(data[0]['parts_preps']) ){
                if( data[0]['user_position'] == 4 ){//operator
                  $('#btn_edit_material_details_for_parts_prep_member').prop('hidden',false);//user access
                  $('#btn_edit_material_details_secondary').prop('hidden',false);//user access
                }
                if( data[0]['user_position'] == 1 ){//production supervisor
                  $('#btn_approve_prod').prop('hidden',false);//user access
                }
                $('#mdl_edit_material_details').attr('data-status', data[0]['parts_preps'][0]['status'] );
                $('#txt_parts_preps_id_query').val(data[0]['parts_preps'][0]['id']);

                var runcard_number  = data[0]['parts_preps'][0]['runcard_number'];
                var runcard_number = "" + runcard_number;
                var pad = "000";
                runcard_number      = pad.substring(runcard_number.length) + runcard_number;
                $('#txt_runcard_number').val( data[0]['parts_preps'][0]['runcard_po'] + '-' +runcard_number );

                // $('#txt_assessment_number').val(data[0]['parts_preps'][0]['assess_num']);
                $('#txt_special_instruction').val(data[0]['parts_preps'][0]['special_instruction']);
                $('#txt_material_remarks').val(data[0]['parts_preps'][0]['remarks']);
                //---
                $('#txt_other_docs_number').val(data[0]['parts_preps'][0]['other_docs_num']);
                $('#txt_parts_prep_remarks').val(data[0]['parts_preps'][0]['parts_prep_remarks']);
                if( data[0]['parts_preps'][0]['status'] == 3 ){
                  $('#btn_edit_material_details_for_parts_prep_member').prop('disabled',false);
                }
                if( data[0]['parts_preps'][0]['status'] == 4 ){
                  $('#btn_edit_material_details_secondary').prop('disabled',false);
                }
                if( data[0]['parts_preps'][0]['status'] == 6 ){
                  $('#btn_approve_prod').prop('disabled',false);
                }
                //---
                $('#txt_discrepant_qty_sign').val('+');
                if( data[0]['parts_preps'][0]['discrepant_qty'] < 0 ){
                  $('#txt_discrepant_qty_sign').val('-');
                }
                // $('#txt_lot_number').val(data[0]['lot_no']);
                matLotNumber = data[0]['lot_no'];

                $('#txt_discrepant_qty').val( Math.abs( data[0]['parts_preps'][0]['discrepant_qty']?data[0]['parts_preps'][0]['discrepant_qty']:'+' ) );
                $('#txt_analysis').val(data[0]['parts_preps'][0]['analysis']);
                $('#txt_recount_ok').val(data[0]['parts_preps'][0]['recount_ok']);
                $('#txt_recount_ng').val(data[0]['parts_preps'][0]['recount_ng']);
                $('#txt_other_runcard_number').val(data[0]['parts_preps'][0]['other_runcard_number']);

                $('#txt_checked_by_prod').val(data[0]['parts_preps'][0]['raw_checked_by_prod']);

                $('#txt_sgc_docs_number').val(data[0]['parts_preps'][0]['sgc_doc_number']);
                $('#txt_sgc_docs_number_revision_number').val(data[0]['parts_preps'][0]['sgc_doc_number_revision_number']);

              }
              else{
                $('#txt_sgc_docs_number').val(data[0]['sgc_drawing_no']);
                $('#txt_sgc_docs_number_revision_number').val(data[0]['sgc_drawing_no_revision_no']);
              }
              $('#mdl_edit_material_details').attr('data-issued_qty', data[0]['issued_qty'] );
              $('#txt_wbs_kit_issuance_id_query').val(data[0]['id']);
              $('#txt_wbs_kit_issuance_device_code_query').val(data[0]['kit_issuance']['device_code']);
              $('#txt_material_name').val(data[0]['item_desc']);
              $('#txt_part_number').val(data[0]['item']);


              // $('#txt_lot_number').val(data[0]['lot_no']);
              matLotNumber = data[0]['lot_no'];

              $('#txt_po_number_label').val(data[0]['po']);
              $('#txt_use_for_device').val(data[0]['kit_issuance']['device_name']);
              $('#txt_assessment_number').val(data[0]['kit_issuance']['assessment']);
              $('#txt_assessed_qty').val(data[0]['kit_qty']);
              $('#txt_drawing_number').val(data[0]['drawing_no']);
              $('#txt_drawing_number_revision_number').val(data[0]['drawing_no_revision_no']);
              // if(data[0]['parts_preps'])
              // $('#txt_sgc_docs_number').val(data[0]['sgc_drawing_no']);
              // $('#txt_sgc_docs_number_revision_number').val(data[0]['sgc_drawing_no_revision_no']);
            }
            else if( data_arr['wbs_table'] == 2 ){//sakidashi
              if( $.trim(data[0]['parts_preps']) ){
                matLotNumber = data[0]['lot_no'];

                if( data[0]['user_position'] == 4 || data[0]['user_position'] == 0){//operator
                  $('#btn_edit_material_details_for_parts_prep_member').prop('hidden',false);//user access
                  $('#btn_edit_material_details_secondary').prop('hidden',false);//user access
                }
                if( data[0]['user_position'] == 1 || data[0]['user_position'] == 0){//production supervisor
                  $('#btn_approve_prod').prop('hidden',false);//user access
                }
                $('#mdl_edit_material_details').attr('data-status', data[0]['parts_preps'][0]['status'] );
                $('#txt_parts_preps_id_query').val(data[0]['parts_preps'][0]['id']);

                var runcard_number  = data[0]['parts_preps'][0]['runcard_number'];
                var runcard_number = "" + runcard_number;
                var pad = "000";
                runcard_number      = pad.substring(runcard_number.length) + runcard_number;
                $('#txt_runcard_number').val( data[0]['parts_preps'][0]['runcard_po'] + '' +runcard_number );

                // $('#txt_assessment_number').val(data[0]['parts_preps'][0]['assess_num']);
                $('#txt_special_instruction').val(data[0]['parts_preps'][0]['special_instruction']);
                $('#txt_material_remarks').val(data[0]['parts_preps'][0]['remarks']);
                //---
                $('#txt_other_docs_number').val(data[0]['parts_preps'][0]['other_docs_num']);
                $('#txt_parts_prep_remarks').val(data[0]['parts_preps'][0]['parts_prep_remarks']);
                if( data[0]['parts_preps'][0]['status'] == 3 ){
                  $('#btn_edit_material_details_for_parts_prep_member').prop('disabled',false);
                }
                if( data[0]['parts_preps'][0]['status'] == 4 ){
                  $('#btn_edit_material_details_secondary').prop('disabled',false);
                }
                if( data[0]['parts_preps'][0]['status'] == 6 ){
                  $('#btn_approve_prod').prop('disabled',false);
                }
                //---
                $('#txt_discrepant_qty_sign').val('+');
                if( data[0]['parts_preps'][0]['discrepant_qty'] < 0 ){
                  $('#txt_discrepant_qty_sign').val('-');
                }
                $('#txt_discrepant_qty').val( Math.abs( data[0]['parts_preps'][0]['discrepant_qty']?data[0]['parts_preps'][0]['discrepant_qty']:'+' ) );
                $('#txt_analysis').val(data[0]['parts_preps'][0]['analysis']);
                $('#txt_recount_ok').val(data[0]['parts_preps'][0]['recount_ok']);
                $('#txt_recount_ng').val(data[0]['parts_preps'][0]['recount_ng']);
                $('#txt_other_runcard_number').val(data[0]['parts_preps'][0]['other_runcard_number']);

                $('#txt_checked_by_prod').val(data[0]['parts_preps'][0]['raw_checked_by_prod']);
              }
              $('#mdl_edit_material_details').attr('data-issued_qty', data[0]['tbl_wbs_sakidashi_issuance_item']['issued_qty'] );
              $('#txt_wbs_kit_issuance_id_query').val(data[0]['id']);
              $('#txt_wbs_kit_issuance_device_code_query').val(data[0]['device_code']);
              $('#txt_material_name').val(data[0]['tbl_wbs_sakidashi_issuance_item']['item_desc']);
              $('#txt_part_number').val(data[0]['tbl_wbs_sakidashi_issuance_item']['item']);
              // $('#txt_lot_number').val(data[0]['tbl_wbs_sakidashi_issuance_item']['lot_no']);
              matLotNumber = data[0]['tbl_wbs_sakidashi_issuance_item']['lot_no'];

              $('#txt_po_number_label').val(data[0]['po_no']);
              $('#txt_use_for_device').val(data[0]['device_name']);
              $('#txt_assessment_number').val(data[0]['assessment']);
              $('#txt_assessed_qty').val(data[0]['tbl_wbs_sakidashi_issuance_item']['issued_qty']);
              $('#txt_drawing_number').val(data[0]['drawing_no']);
              $('#txt_drawing_number_revision_number').val(data[0]['drawing_no_revision_no']);
              $('#txt_sgc_docs_number').val(data[0]['sgc_drawing_no']);
              $('#txt_sgc_docs_number_revision_number').val(data[0]['sgc_drawing_no_revision_no']);
            }
            else if( data_arr['wbs_table'] == 3 ){//warehouse
              if( $.trim(data[0]['parts_preps']) ){
                if( data[0]['user_position'] == 3 ){//material handler 
                  $('#btn_edit_material_details_primary').prop('hidden',false);//user access
                  $('#btn_setup_stations').prop('hidden',false);//user access
                }
                if( data[0]['parts_preps'][0]['status'] == 1 ){
                  $('#btn_edit_material_details_primary').prop('disabled',false);
                }
                if( data[0]['parts_preps'][0]['status'] < 5 ){
                  $('#btn_setup_stations').prop('disabled',false);
                }
                $('#txt_parts_preps_id_query').val(data[0]['parts_preps'][0]['id']);

                var runcard_number  = data[0]['parts_preps'][0]['runcard_number'];
                var runcard_number = "" + runcard_number;
                var pad = "000";
                runcard_number      = pad.substring(runcard_number.length) + runcard_number;
                $('#txt_runcard_number').val( data[0]['parts_preps'][0]['runcard_po'] + '-' +runcard_number );

                $('#txt_assessment_number').val(data[0]['parts_preps'][0]['assess_num']);
                $('#txt_special_instruction').val(data[0]['parts_preps'][0]['special_instruction']);
                $('#txt_material_remarks').val(data[0]['parts_preps'][0]['remarks']);
                //---
                $('#txt_other_docs_number').val(data[0]['parts_preps'][0]['other_docs_num']);
                $('#txt_parts_prep_remarks').val(data[0]['parts_preps'][0]['parts_prep_remarks']);
                //---
                $('#txt_discrepant_qty_sign').val('+');
                if( data[0]['parts_preps'][0]['discrepant_qty'] < 0 ){
                  $('#txt_discrepant_qty_sign').val('-');
                }
                $('#txt_discrepant_qty').val( Math.abs( data[0]['parts_preps'][0]['discrepant_qty']?data[0]['parts_preps'][0]['discrepant_qty']:'+' ) );
                $('#txt_analysis').val(data[0]['parts_preps'][0]['analysis']);
                $('#txt_recount_ok').val(data[0]['parts_preps'][0]['recount_ok']);
                $('#txt_recount_ng').val(data[0]['parts_preps'][0]['recount_ng']);
                $('#txt_other_runcard_number').val(data[0]['parts_preps'][0]['other_runcard_number']);

                $('#txt_checked_by_prod').val(data[0]['parts_preps'][0]['raw_checked_by_prod']);
              }
              $('#mdl_edit_material_details').attr('data-issued_qty', data[0]['tbl_request_detail']['issuedqty'] );
              $('#txt_wbs_kit_issuance_id_query').val(data[0]['id']);
              $('#txt_wbs_kit_issuance_device_code_query').val( $('#txt_device_name').attr('data-device_code') );//?
              $('#txt_wbs_kit_issuance_item_query').val(data[0]['item']);
              $('#txt_wbs_kit_issuance_item_desc_query').val(data[0]['item_desc']);
              $('#txt_material_name').val(data[0]['item_desc']);
              // $('#txt_lot_number').val(data[0]['lot_no']);
              matLotNumber = data[0]['lot_no'];

              $('#txt_po_number_label').val(data[0]['tbl_request_summary']['pono']);
              $('#txt_use_for_device').val( $('#txt_device_name').val() );//?
              $('#txt_assessed_qty').val(data[0]['tbl_request_detail']['issuedqty']);
              $('#txt_drawing_number').val(data[0]['drawing_no']);//?
              $('#txt_drawing_number_revision_number').val(data[0]['drawing_no_revision_no']);//?
              $('#txt_sgc_docs_number').val(data[0]['sgc_drawing_no']);
              $('#txt_sgc_docs_number_revision_number').val(data[0]['sgc_drawing_no_revision_no']);
            }
          }
          $('#txt_lot_number').val(matLotNumber)
          dt_parts_prep_stations.ajax.reload();
          $('#mdl_edit_material_details').modal('show');
        },
        completed     : function(data){
        },
        error     : function(data){
        },
      });
    }
    
    function readonly_station_details(v){
      $('#txt_edit_partsprep_station_machine').prop('disabled',v);
      // $('#txt_edit_partsprep_station_input').prop('readonly',v);
      // $('#txt_edit_partsprep_station_output').prop('readonly',v);
      $('#txt_edit_partsprep_station_print_code').prop('readonly',v);
      $('#btn_edit_partsprep_station_add_ng').prop('disabled',v);//uncommentthis
      $('#btn_scan_machine_code').prop('disabled',v);//uncommentthis

      $('#row_ng>div').html('');

      $('#btn_save_partsprep_station_stations').hide();
      if(!v){
        $('#btn_save_partsprep_station_stations').show();
      }
    }

    function fn_select_partsprep_station_details(data_arr){
      readonly_station_details(true);
      var data = {
        'col_partsprep_station_id': data_arr['col_partsprep_station_id'],
        'device_name'             : $('#txt_use_for_device').val(),
        'device_code'             : $('#txt_wbs_kit_issuance_device_code_query').val(),
        '_token'                  : '{{ csrf_token() }}',
      }
      $.ajax({
        'data'      : data,
        'type'      : 'get',
        'dataType'  : 'json',
        'url'       : 'select_partspreparatory_station_details',
        success     : function(data){
          // alert(data[0]['station']['name'])
          if ($.trim(data)){
            employeeNumberAllowed = data[0]['device_manpower_details'];
            $('#txt_partsprep_station_id_query').val(data[0]['id']);
            $('#txt_edit_partsprep_station_step').val(data[0]['step_num']);
            $('#txt_edit_partsprep_station_station').val(data[0]['station']['name']);
            $('#txt_edit_partsprep_station_substation').val(data[0]['sub_station']['name']);
            $('#txt_edit_partsprep_station_date').val( getdate( data[0]['created_at'] ) );//?data[0]['created_at']:getcurrentdate()
            // $('#txt_edit_partsprep_station_date').val(data[0]['created_at']);
            $('#txt_edit_partsprep_station_operator').val(data[0]['created_by_name']);
            // $('#txt_edit_partsprep_station_machine').val(data[0]['machine_id']?data[0]['machine_id']:0);

            var html = '<option value="0">---</option>';
            for (var i = 0; i < data[0]['device'][0].material_process[0].machine_details.length; i++) {
              html+='<option value="'+data[0]['device'][0].material_process[0].machine_details[i]['machine_id']+'" data-code="'+(data[0]['device'][0].material_process[0].machine_details[i]['machine_info']['barcode']).toLowerCase()+'">'+data[0]['device'][0].material_process[0].machine_details[i]['machine_info']['barcode']+' '+data[0]['device'][0].material_process[0].machine_details[i]['machine_info']['barcode']+'</option>';
            }
            $('#txt_edit_partsprep_station_machine').html(html);

            $('#txt_edit_partsprep_station_input').val(data[0]['qty_input']);
            $('#txt_edit_partsprep_station_output').val(data[0]['qty_output']);
            $('#txt_edit_partsprep_station_print_code').val(data[0]['print_code']);


            var qty_str = ($.trim(data[0]['qty_ng']))?data[0]['qty_ng'].toString():'';
            var qty_arr = qty_str.split(',');
            if(qty_str){
              var mod_str = ($.trim(data[0]['mod']))?data[0]['mod'].toString():'';
              var mod_arr = mod_str.split(',');
              for (var i = 0; i < qty_arr.length; i++) {
                partsprep_station_add_ng(qty_arr[i],mod_arr[i],1);
              }
            }
            $('.txt_edit_partsprep_station_ng:first').change();


            if(!data[0]['created_by_name']){
              readonly_station_details(false);
              $('#txt_edit_partsprep_station_input').val( $('#mdl_edit_material_details').attr('data-issued_qty') );
              $('#txt_edit_partsprep_station_output').val( $('#mdl_edit_material_details').attr('data-issued_qty') );
            }
          }

          $('#mdl_edit_partsprep_station_details').modal('show');
        },
        completed     : function(data){

        },
        error     : function(data){

        },
      });
    }


    // function fn_select_list_partsprep_station_machines(){
    //   var data = {
    //     '_token'                   : '{{ csrf_token() }}',
    //   }
    //   $.ajax({
    //     'data'      : data,
    //     'type'      : 'get',
    //     'dataType'  : 'json',
    //     'url'       : 'select_list_partsprep_station_machines',
    //     success     : function(data){
    //       if ($.trim(data)){
    //         var html = '<option value="0">---</option>';
    //         for (var i = 0; i < data.length; i++) {
    //           html+='<option value="'+data[i]['id']+'" data-code="'+(data[i]['barcode']).toLowerCase()+'">'+data[i]['name']+' '+data[i]['barcode']+'</option>';
    //         }
    //         $('#txt_edit_partsprep_station_machine').html(html);
    //       }
    //     },
    //     completed     : function(data){

    //     },
    //     error     : function(data){

    //     },
    //   });

    // }


    function fn_select_list_runcard_numbers(){
      $('#dl_other_runcard_number').html('');
      var data = {
        '_token'                   : '{{ csrf_token() }}',
      }
      $.ajax({
        'data'      : data,
        'type'      : 'get',
        'dataType'  : 'json',
        'url'       : 'select_list_runcard_numbers',
        success     : function(data){
          var html = '';//<option value="0">---</option>';
          if ($.trim(data)){
            for (var i = 0; i < data.length; i++) {
              var runcard_number  = data[0]['runcard_number'];
              var runcard_number = "" + runcard_number;
              var pad = "000";
              runcard_number      = pad.substring(runcard_number.length) + runcard_number;
              runcard_number_str =  data[0]['runcard_po'] + '' +runcard_number;

              html+='<option>'+runcard_number_str+'</option>';
              // html+='<option value="'+data[i]['id']+'" data-code="'+data[i]['id']+'">'+runcard_number_str+'</option>';
            }
          }
          $('#dl_other_runcard_number').html(html);
        },
        completed     : function(data){

        },
        error     : function(data){

        },
      });

    }

    function fn_select_list_partsprep_station_mod(){
      var data = {
        '_token'                   : '{{ csrf_token() }}',
      }
      $.ajax({
        'data'      : data,
        'type'      : 'get',
        'dataType'  : 'json',
        'url'       : 'select_list_partsprep_station_mod',
        success     : function(data){
          if ($.trim(data)){
            var html = '<option value="0">---</option>';
            for (var i = 0; i < data.length; i++) {
              html+='<option value="'+data[i]['id']+'" data-code="'+(data[i]['barcode']).toLowerCase()+'">'+data[i]['name']+' '+data[i]['barcode']+'</option>';
            }
            $('#row_ng').attr('data-mod_options',html);
          }
        },
        completed     : function(data){

        },
        error     : function(data){

        },
      });

    }

    function partsprep_station_add_ng(qty,mod,is_readonly){
      var html    = '';
      html        += '<div class="row">';
      html        +=   '<div class="col">';
      html        +=     '<div class="input-group input-group-sm mb-3">';
      html        +=       '<div class="input-group-prepend w-25">';
      html        +=         '<span class="input-group-text w-100">NG qty</span>';
      html        +=       '</div>';
      html        +=       '<input '+((is_readonly)?'readonly':'')+' value="'+qty+'" type="number" class="form-control form-control-sm txt_edit_partsprep_station_ng" placeholder="0">';
      html        +=       '<label class="form-control form-control-sm" style="background-color: #eee;font-weight: normal;">MOD</label>';
      html        +=       '<select '+((is_readonly)?'disabled':'')+' class="form-control form-control-sm txt_edit_partsprep_station_mod">';
      html        +=       $('#row_ng').attr('data-mod_options');
      html        +=       '</select>';
      html        +=       '<div class="input-group-append">';
      html        +=         '<button '+((is_readonly)?'disabled':'')+' type="button" class="btn btn-danger bnt-sm btn_edit_partsprep_station_remove_ng"><i class="fa fa-times"></i></button>';
      html        +=       '</div>';
      html        +=     '</div>';
      html        +=   '</div>';
      html        += '</div>';

      $('#row_ng>div').append(html);
      $('#row_ng>div').find('.txt_edit_partsprep_station_mod:last').val(mod);
    }



    //-----------------------
    function save_approve_prod(){
      $.ajax({
        'data'      : 'txt_parts_preps_id_query=' + $("#txt_parts_preps_id_query").val()+'&txt_employee_number_scanner=' + $("#txt_employee_number_scanner").val() +'&status=7' + '&_token=' + '{{ csrf_token() }}',
        'type'      : 'post',
        'dataType'  : 'json',
        'url'       : 'update_approval_prod',
        success     : function(data){
          show_alert(data['icon'],data['title'],data['body'],data['i']);
          if( data['i'] == 2 ){
            return;
          }
          //---
          var data_arr = [];
          data_arr['material_id']     = $('#txt_wbs_kit_issuance_id_query').val();
          data_arr['wbs_table']       = $('#txt_wbs_table').val();
          fn_select_material_details(data_arr);
          dt_lot_numbers.ajax.reload();
        },
        completed     : function(data){
        },
        error     : function(data){
        },
      });
    }
    //-----------------------
    //-----------------------
    //-----------------------
    function show_alert(icon,title,body,i){
      $('#mdl_alert #mdl_alert_title').html(icon+' '+title);
      $('#mdl_alert #mdl_alert_body').html(body);
      $('#mdl_alert').modal('show');
    }
    function getdate(datetime){
      if(!datetime){
        return '';
      }
      var d = new Date(datetime);
      // var d = new Date();

      var month = d.getMonth()+1;
      var day = d.getDate();

      var output = d.getFullYear() + '-' +
          (month<10 ? '0' : '') + month + '-' +
          (day<10 ? '0' : '') + day;

      // alert(d+'xxxxxx'+output);
      return output;
    }
    function getcurrentdate(){
      var d = new Date();
      return d;

      // var month = d.getMonth()+1;
      // var day = d.getDate();

      // var output = d.getFullYear() + '-' +
      //     (month<10 ? '0' : '') + month + '-' +
      //     (day<10 ? '0' : '') + day;
      // return output;
    }

    function getSGCDrawing(deviceCode, deviceName){
      $.ajax({
        type: "GET",
        url: "{{ route('get_ppts_sgc_drawing') }}",
        data: {
          device_name : deviceName,
          device_code : deviceCode
        },
        dataType: "json",
        success: function (response) {
          $.each(response, function( index, value ) {
            $('#txt_sgc_docs_number').val(index)
            $('#txt_sgc_docs_number_revision_number').val(value)
          });
        },
      });
    }
  </script>
  @endsection
@endauth