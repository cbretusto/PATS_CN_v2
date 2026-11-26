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

  @section('title', 'Production Runcard')

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
  </style>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Production Runcard</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item">Production</li>
              <li class="breadcrumb-item active">Runcard</li>
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
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><i class="fa fa-search"></i></span>
                      </div>
                      <input type="text" class="form-control" placeholder="Search PO Number here..." id="txt_search_po_number"><!-- value="450198990900010" -->
                    </div>
                  </div>
                  <div class="col-3 offset-6">
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">PO No.</span>
                      </div>
                      <input id="txt_po_number_lbl" type="text" class="form-control" placeholder="---" readonly>
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
                <h3 class="card-title">List of Materials</h3>
              </div>

              <!-- Start Page Content -->
              <div class="card-body">
                <div class="row">
                  <div class="col-12">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Material Kitting & Issuance</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Sakidashi Issuance</a>
                      </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                      <div class="tab-pane fade show active my-2" id="home" role="tabpanel" aria-labelledby="home-tab">
                            
                        <div class="table-responsive">
                          <table class="table table-sm table-bordered table-hover" id="tbl_materials" style="min-width: 900px;">
                            <thead>
                              <tr class="bg-light">
                                <th>Action</th>
                                <th>Status</th>
                                <th>Material Name</th>

                                <th>Transfer Slip #</th>
                                <th>Part #</th>
                                <th>Mat'l Lot #</th>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
                          </table>
                        </div>

                      </div>
                      <div class="tab-pane fade my-2" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="table-responsive">
                          <table class="table table-sm table-bordered table-hover small" id="tbl_sakidashi">
                            <thead>
                              <tr class="bg-light">
                                <th>Action</th>
                                <th>Status</th>
                                <th>Ctrl No.</th>
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

                    </div>
                  </div>
                </div>

              </div>
              <!-- !-- End Page Content -->

            </div>
            <!-- /.card -->
          </div>

          <!-- right column -->
          <div class="col-md-5" hidden>
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Stations</h3>
              </div>

              <!-- Start Page Content -->
              <div class="card-body">
                <div class="row">
                  <div class="col-12">
                    <div class="table-responsive">
                      <table class="table table-sm table-bordered table-hover" id="tbl_stationsx" style="min-width: 600px;">
                        <thead>
                          <tr class="bg-light">
                            <th></th>
                            <th></th>
                            <th>Station</th>
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
      <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i class="fa fa-info-circle text-info"></i> Material Details (Production Runcard)</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-4 border px-4">
                <form id="frm_edit_material_details" method="post">
                  <div class="row">
                    <div class="col pt-3">
                      <button type="button" id="btn_edit_material_details_primary" class="btn btn-sm btn-link float-right"><i class="fa fa-edit"></i> Edit</button>
                      <span class="badge badge-secondary">1.</span> Details
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
                          <span class="input-group-text w-100" id="basic-addon1">Part Number</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_part_number" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Material Lot Number</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_lot_number" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">PO Number</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_po_number" name="txt_po_number" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">PO Qty</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_po_qty" readonly>
                      </div>
                    </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Device Name</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_use_for_device" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Device Code</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_device_code" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Lot Qty</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_lot_qty" name="txt_lot_qty" readonly>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Runcard No.</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_runcard_no" name="txt_runcard_no" placeholder="Auto generated" readonly="readonly">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Assessment No.</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_assessment_no" name="txt_assessment_no">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">A Drawing Number</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_a_drawing_no" name="txt_a_drawing_no">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Revision</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_a_drawing_rev" name="txt_a_drawing_rev">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">B Drawing Number</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_g_drawing_no" name="txt_g_drawing_no">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Revision</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_g_drawing_rev" name="txt_g_drawing_rev">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Other Docs Number</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_other_docs_no" name="txt_other_docs_no">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Revision</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_other_docs_rev" name="txt_other_docs_rev">
                      </div>
                    </div>
                  </div>
                  <!-- <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Comp. Under Runcard No. </span>
                        </div>
                        <select class="form-control select2 select2bs4" id="sel_comp_under_runcard_no" name="sel_comp_under_runcard_no" disabled>
                          <option value="0"> N/A </option>
                        </select>
                      </div>
                    </div>
                  </div> -->
                  <div class="row" style="display: none;">
                    <div class="col text-right">
                      @csrf
                      <input type="hidden" name="txt_wbs_table" id="txt_wbs_table">
                      <input type="hidden" name="txt_material_details_emp_num" id="txt_material_details_emp_num">
                      <input type="hidden" name="txt_prod_runcard_id_query" id="txt_prod_runcard_id_query">
                      <input type="hidden" name="txt_wbs_kit_issuance_id_query" id="txt_wbs_kit_issuance_id_query">
                      <!-- <input type="hidden" name="txt_wbs_sakidashi_issuance_id_query" id="txt_wbs_sakidashi_issuance_id_query"> -->
                      <input type="hidden" name="txt_wbs_kit_issuance_device_code_query" id="txt_wbs_kit_issuance_device_code_query">
                      <button type="button" class="btn btn-sm btn-success" id="btn_save_material_details_primary">Save</button>
                      <button type="button" class="btn btn-sm btn-secondary" id="btn_cancel_material_details_primary">Cancel</button>
                    </div>
                  </div>
                </form>
                <br>
              </div><!-- col -->
              <div class="col-sm-8">
                <div class="row">
                  <div class="col border py-3 px-4 border-left-0 border-bottom-0">
                    <button type="button" class="btn btn-sm btn-info float-right mb-1" id="btn_setup_stations" disabled="disabled"><i class="fa fa-cog"></i> Set-up stations</button>
                    <span class="badge badge-secondary">2.</span> Stations
                    <div class="table-responsive">
                      <table class="table table-sm small table-bordered table-hover" id="tbl_prod_runcard_stations" style="width: 100%;">
                        <thead>
                          <tr class="bg-light">
                            <th></th>
                            <th>Step</th>
                            <th>Station ID</th>
                            <th>Station</th>
                            <th>Sub-station</th>
                            <th>Date Time</th>
                            <th>Operator</th>
                            <th>Machine</th>
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
                  </div>
                </div>
                <div class="row">
                  <div class="col border py-3 px-4 border-left-0">
                    <form id="frm_edit_material_details_secondary" method="post">
                      @csrf
                      <input type="hidden" name="txt_prod_runcard_id_query" id="txt_prod_runcard_verification_id_query">
                      <div class="row">
                        <div class="col">
                          <button type="button" class="btn btn-sm btn-link float-right" id="btn_edit_material_details_verification"><i class="fa fa-edit"></i> Edit</button>
                          <span class="badge badge-secondary">3.</span> Verification
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-6">
                          Output + NG not tally?
                          <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100" id="basic-addon1">Discrepant qty</span>
                            </div>
                            <select class="form-control form-control-sm" id="txt_discrepant_qty_sign" name="txt_discrepant_qty_sign" min="0">
                              <option value="0">---</option>
                              <option>+</option>
                              <option>-</option>
                            </select>
                            <input type="number" class="form-control form-control-sm" id="txt_discrepant_qty" name="txt_discrepant_qty" min="0">
                          </div>
                          <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100" id="basic-addon1">Analysis</span>
                            </div>
                            <textarea class="form-control form-control-sm" rows="2" id="txt_analysis" name="txt_analysis"></textarea>
                          </div>
                        </div>
                        <div class="col-6">
                          Treatment
                          <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend w-25">
                              <span class="input-group-text w-100" id="basic-addon1">Recount</span>
                            </div>
                            <input type="number" class="form-control form-control-sm" id="txt_recount_ok" name="txt_recount_ok" min="0">
                            <label class="form-control form-control-sm" style="background-color: #eee;">OK</label>
                            <input type="number" class="form-control form-control-sm" id="txt_recount_ng" name="txt_recount_ng" min="0">
                            <label class="form-control form-control-sm" style="background-color: #eee;">NG</label>
                          </div>
                          <!-- <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100" id="basic-addon1">If short qty - complete the <br> batch under Runcard No.</span>
                            </div>
                            <div class="input-group-prepend w-50">
                              <select class="form-control form-control-sm select2bs4" id="txt_comp_under_runcard_no" name="txt_comp_under_runcard_no">
                                <option value="0" selected="selected">N/A</option>
                              </select>
                              
                            </div>
                          </div> -->

                          <div class="row">
                            <div class="col">
                              <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend w-50">
                                  <span class="input-group-text w-100" id="basic-addon1">Comp. Under Runcard No. </span>
                                </div>
                                <select class="form-control select2 select2bs4" id="sel_comp_under_runcard_no" name="sel_comp_under_runcard_no" disabled>
                                  <option value="0"> N/A </option>
                                </select>
                              </div>
                            </div>
                          </div>

                          <!-- <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100" id="basic-addon1">If short qty - complete the <br> batch under Runcard No.</span>
                            </div>
                            <div class="input-group-prepend w-50">
                              <select class="form-control select2 select2bs4" id="sel_comp_under_runcard_no" name="sel_comp_under_runcard_no" disabled>
                                  <option value="0"> N/A </option>
                                </select>
                              
                            </div>
                          </div> -->

                          <div class="input-group input-group-sm mb-3" hidden>
                            <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100" id="basic-addon1">Quantity</span>
                            </div>
                            <select class="form-control form-control-sm">
                              <option>Short</option>
                              <option>Excess</option>
                            </select>
                          </div>
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
                <div class="row">
                  <div class="col pt-5 px-4">
                    <div class="row">
                      <div class="col">
                        <div class="input-group input-group-sm mb-3">
                          <div class="input-group-prepend w-50">
                            <span class="input-group-text w-100" id="basic-addon1"><i class="fa fa-check-circle"></i>&nbsp; Prod. Supervisor</span>
                          </div>
                          <input type="text" class="form-control form-control-sm" name="txt_prod_approval" readonly id="txt_prod_approval" placeholder="---">
                        </div>
                      </div>
                      <div class="col">
                        <div class="input-group input-group-sm mb-3">
                          <div class="input-group-prepend w-50">
                            <span class="input-group-text w-100" id="basic-addon1"><i class="fa fa-check-circle"></i>&nbsp; QC Supervisor</span>
                          </div>
                          <input type="text" class="form-control form-control-sm" name="txt_qc_approval" readonly id="txt_qc_approval" placeholder="---">
                        </div>
                      </div><!-- col -->
                    </div>
                  </div>
                </div>
              </div><!-- col -->
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-success" id="btn_approve_prod"><i class="fa fa-check-circle"></i> Prod Approved</button>
            <button type="button" class="btn btn-sm btn-success" id="btn_approve_qc"><i class="fa fa-check-circle"></i> QC Approved</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!-- /.Modal -->

    <!-- Modal -->
    <div class="modal fade" id="mdl_setup_stations" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="mdl_alert_title">Set-up stations</h5>
          </div>
          <div class="modal-body" id="mdl_alert_body">
            <div class="row">
              <div class="col">
                <div class="table-responsive">
                  <table class="table table-sm small table-bordered table-hover w-100" id="tbl_setup_stations" style="min-width: 400px;">
                    <thead>
                      <tr class="bg-light">
                        <th></th>
                        <th>Step</th>
                        <th>Station</th>
                        <th>Sub-station</th>
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
            <button type="submit" class="btn btn-success" id="btn_save_setup_stations">Save</button>
          </div>
        </div>
      </div>
    </div>
    <!-- /.Modal -->

    <!-- Modal -->
    <div class="modal fade" id="mdl_edit_prod_runcard_station_details" tabindex="-1" role="dialog" aria-labelledby="cnptsmodal" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i class="fas fa-object-group text-info"></i> Stations</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="frm_edit_prod_runcard_station_details">
              @csrf
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Step</span>
                    </div>
                    <input type="number" class="form-control form-control-sm" id="txt_edit_prod_runcard_station_step" readonly>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Station</span>
                    </div>
                    <input type="text" class="form-control form-control-sm" id="txt_edit_prod_runcard_station_station" readonly>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Sub-station</span>
                    </div>
                    <input type="text" class="form-control form-control-sm" id="txt_edit_prod_runcard_station_substation" readonly>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Date</span>
                    </div>
                    <input type="date" class="form-control form-control-sm" id="txt_edit_prod_runcard_station_date" name="txt_edit_prod_runcard_station_date" readonly>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Operator</span>
                    </div>
                    <input type="text" class="form-control form-control-sm" id="txt_edit_prod_runcard_station_operator" name="txt_edit_prod_runcard_station_operator" readonly>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Machine</span>
                    </div>
                    <!-- <input type="text" class="form-control form-control-sm" id="txt_edit_prod_runcard_station_machine" name="txt_edit_prod_runcard_station_machine"> -->

                    <!-- <select class="form-control form-control-sm select2bs4 selectMachine" name="txt_edit_prod_runcard_station_machine" id="txt_edit_prod_runcard_station_machine" style="width: 100%;">
                    </select> -->

                    <select class="form-control select2 select2bs4 selectMachine" id="txt_edit_prod_runcard_station_machine" name="txt_edit_prod_runcard_station_machine">
                      <option value=""> N/A </option>
                    </select>
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
                      <span class="input-group-text w-100" id="basic-addon1">Input</span>
                    </div>
                    <input type="number" class="form-control form-control-sm" id="txt_edit_prod_runcard_station_input" name="txt_edit_prod_runcard_station_input">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Output</span>
                    </div>
                    <input type="number" class="form-control form-control-sm" id="txt_edit_prod_runcard_station_output" name="txt_edit_prod_runcard_station_output">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">NG Qty</span>
                    </div>
                    <input type="number" class="form-control form-control-sm" id="txt_edit_prod_runcard_station_ng" name="txt_edit_prod_runcard_station_ng">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">MOD</span>
                    </div>
                    <textarea class="form-control form-control-sm" id="txt_edit_prod_runcard_station_mod" name="txt_edit_prod_runcard_station_mod"></textarea>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <input type="hidden" id="txt_prod_runcard_station_id_query" name="txt_prod_runcard_station_id_query" form="frm_edit_prod_runcard_station_details">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-success" form="frm_edit_prod_runcard_station_details" id="btn_save_prod_runcard_station_stations">Save</button>
          </div>
        </div>
      </div>
    </div>
    <!-- /.Modal -->

    <!-- Modal -->
    <div class="modal fade" id="mdl_alert" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
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
            <input type="text" id="txt_qrcode_scanner" class="hidden_scanner_input">
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
              <br><br>
              <!-- <h1><i class="fa fa-barcode fa-lg"></i></h1> -->
              <h1><i class="fa fa-qrcode fa-lg"></i></h1>
            </div>
            <input type="text" id="txt_employee_number_scanner" class="hidden_scanner_input">
          </div>
  <!--         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div> -->
        </div>
      </div>
    </div>
    <!-- /.Modal -->


  </div>
  <!-- /.content-wrapper -->
  @endsection

  @section('js_content')
  <script type="text/javascript">
    let dt_materials, dt_setup_stations, dt_prod_runcard_stations, dt_sakidashi;
    toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": true,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "3000",
      "timeOut": "3000",
      "extendedTimeOut": "3000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut",
    };
    $(document).ready(function () {
      bsCustomFileInput.init();
      //Initialize Select2 Elements
      $('.select2').select2();

      //Initialize Select2 Elements
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });

      GetProductionRuncards($("#sel_comp_under_runcard_no"), 1);

      GetCboMachine($(".selectMachine"));

      $('input').each(function(i, obj) {
        if (!this.hasAttribute("placeholder")) {
          if( $(this).prop('type') == 'number' ){
            $(this).prop('placeholder','0');
          }
          if( $(this).prop('type') == 'text' ){
            $(this).prop('placeholder','---');
          }
        }
      });
      $('textarea').each(function(i, obj) {
        if (!this.hasAttribute("placeholder")) {
          $(this).prop('placeholder','...');
        }
      });

      //---

      dt_materials      = $('#tbl_materials').DataTable({
        "processing" : true,
          "serverSide" : true,
          "ajax" : {
            url: "select_prod_runcard_materials",
            data: function (param){
                param.po_number = $("#txt_search_po_number").val();
            }
          },
          bAutoWidth: false,
          "columns":[
            { "data" : "raw_action", orderable:false, searchable:false },
            { "data" : "raw_status", orderable:false, searchable:false },
            { "data" : "item_desc" },

            { "data" : "kit_issuance.issuance_no" },
            { "data" : "item" },
            { "data" : "lot_no" },
            // {
            //     name: '',
            //     data: null,
            //     sortable: false,
            //     searchable: false,
            //     render: function (data) {
            //         var actions = '';
            //         actions += '<a href="/transaksi-masuk/tambah/:id"><span class="label label-primary">TAMBAH</span></a>';
            //         actions += '<a href="/transaksi-masuk/edit/:id"><span class="label label-warning">EDIT</span></a>';
            //         return actions.replace(/:id/g, data.lot_no);
            //     }
            // }
          ],
          order: [[2, "asc"]],
          "rowCallback": function(row,data,index ){
            $('#txt_po_number_lbl').val( data['po'] );
          },
          "drawCallback": function(row,data,index ){
            // dt_setup_stations.ajax.reload();

          },
      });//end of DataTable

      $(document).on('keyup','#txt_search_po_number',function(e){
        if( e.keyCode == 13 ){
          $('#tbl_materials tbody tr').removeClass('table-active');
          $('#txt_po_number_lbl').val('');
          $('#txt_device_name').val('');
          dt_materials.ajax.reload();
          dt_sakidashi.ajax.reload();
        }
      })

      //----------
      dt_setup_stations      = $('#tbl_setup_stations').DataTable({
        "processing" : true,
          "serverSide" : true,
          "ajax" : {
            url: "select_prod_runcard_setup_stations",
            data: function (param){
                // param.po_number              = $("#txt_search_po_number").val();
                param.device_code                  = $("#txt_wbs_kit_issuance_device_code_query").val();
                param.prod_runcard_id_query          = $("#txt_prod_runcard_id_query").val();
            }
          },
          
          "columns":[
            { "data" : "raw_action", orderable:false, searchable:false },
            // { "data" : "raw_status", orderable:false, searchable:false },
            { "data" : "step" },
            { "data" : "station_sub_station.station.name" },
            { "data" : "station_sub_station.sub_station.name" },
            // { "data" : "qty_input" },
            // { "data" : "raw_operator" },
            // { "data" : "raw_input" },
            // { "data" : "raw_output" },
            // { "data" : "raw_ng_qty" },
            // { "data" : "raw_mod" },
          ],
          order: [[1, "asc"]],
          paging: false,
          info: false,
          searching: false,
      });//end of DataTable

      dt_sakidashi      = $('#tbl_sakidashi').DataTable({
          "processing" : true,
          "serverSide" : true,
          "ajax" : {
            url: "view_sakidashi_prod",
            data: function (param){
                param.po_number = $("#txt_search_po_number").val();
            }
          },
          bAutoWidth: false,
          paging: false,
          "columns":[
            { "data" : "action", orderable:false, searchable:false },
            { "data" : "status", orderable:false, searchable:false },
            { "data" : "issuance_no" },
            { "data" : "tbl_wbs_sakidashi_issuance_item.item_desc" },
            { "data" : "tbl_wbs_sakidashi_issuance_item.lot_no" },
            { "data" : "device_code" },
            { "data" : "tbl_wbs_sakidashi_issuance_item.required_qty" },
            { "data" : "tbl_wbs_sakidashi_issuance_item.issued_qty" },
          ],
          order: [[2, "asc"]],
          "rowCallback": function(row,data,index ){
            // Check for production done verification
            // if( $(row).html().toLowerCase().indexOf('parts prep. done verification')>0 ){
            //   $(row).addClass('table-success');
            // }
            // if( $(row).html().toLowerCase().indexOf('returned')>0 ){
            //   $(row).addClass('table-warning');
            // }
          },
          "drawCallback": function(row,data,index ){
          },
      });//end of DataTable

      $(document).on('click','#tbl_materials tr',function(e){
        $(this).closest('tbody').find('tr').removeClass('table-active');
        $(this).closest('tr').addClass('table-active');
        // if( $(this).hasClass('btn_view_lot_numbers') ){
          // dt_setup_stations.ajax.reload();
        // }
      });

      $(document).on('click','#tbl_sakidashi tr',function(e){
        $(this).closest('tbody').find('tr').removeClass('table-active');
        $(this).closest('tr').addClass('table-active');
      });

      $(document).on('click','#tbl_prod_runcard_stations tr',function(e){
        $(this).closest('tbody').find('tr').removeClass('table-active');
        $(this).closest('tr').addClass('table-active');
        // if( $(this).hasClass('btn_view_lot_numbers') ){
          // dt_setup_stations.ajax.reload();
        // }
      });

      $(document).on('click','.btn_view_lot_numbers',function(e){
      });

      $(document).on('click','.btn_material_open_details',function(e){
        var data_arr = [];
        data_arr['material_id']     = $(this).closest('tr').find('.col_material_id').val();
        data_arr['material_code']   = $(this).closest('tr').find('.col_material_code').val();

        $("#txt_wbs_table").val('1');

        fn_select_material_details(data_arr);
        GetProductionRuncards($("#sel_comp_under_runcard_no"), 1);
      });

      $(document).on('click','.btn_sakidashi_material_open_details',function(e){
        var data_arr = [];
        data_arr['material_id']     = $(this).closest('tr').find('.col_material_id').val();
        data_arr['material_code']   = $(this).closest('tr').find('.col_material_code').val();

        $("#txt_wbs_table").val('2');

        fn_select_material_details(data_arr);
        GetProductionRuncards($("#sel_comp_under_runcard_no"), 1);
      });

      $(document).on('click','#btn_edit_material_details_primary',function(e){
        readonly_material_details_primary(false);
      });

      $(document).on('click','#btn_cancel_material_details_primary',function(e){
        readonly_material_details_primary(true);
      });

      $(document).on('click','#btn_edit_material_details_verification',function(e){
        readonly_material_details_secondary(false);
      });

      $(document).on('click','#btn_cancel_material_details_secondary',function(e){
        readonly_material_details_secondary(true);
      });


      $('#frm_edit_material_details').submit(function(e){
        e.preventDefault();

        $.ajax({
          'data'      : $(this).serialize() +'&txt_employee_number_scanner=' + $("#txt_employee_number_scanner").val(),
          'type'      : 'post',
          'dataType'  : 'json',
          'url'       : 'insert_prod_runcard',
          success     : function(data){
            if(data == '0'){
              toastr.error('User not found!');
            }
            else{
              $('#mdl_alert #mdl_alert_title').html(data['title']);
              $('#mdl_alert #mdl_alert_body').html(data['body']);
              $('#mdl_alert').modal('show');
              readonly_material_details_primary(true);
              //---
              var data_arr = [];
              data_arr['material_id']     = $('#txt_wbs_kit_issuance_id_query').val();
              // data_arr['sakidashi_id']     = $('#txt_wbs_sakidashi_issuance_id_query').val();
              data_arr['material_code']   = $('#txt_part_number').val();
              fn_select_material_details(data_arr);
              //---

              dt_materials.ajax.reload();
            }
          },
          completed     : function(data){

          },
          error     : function(data){

          },
        });
      });

      $('#frm_edit_material_details_secondary').submit(function(e){
        e.preventDefault();

        $.ajax({
          'data'      : $(this).serialize() + '&txt_employee_number_scanner=' + $("#txt_employee_number_scanner").val(),
          'type'      : 'post',
          'dataType'  : 'json',
          'url'       : 'update_prod_runcard_secondary',
          success     : function(data){
            if(data == '0'){
              toastr.error('User not found!');
            }
            else{
              $('#mdl_alert #mdl_alert_title').html(data['title']);
              $('#mdl_alert #mdl_alert_body').html(data['body']);
              $('#mdl_alert').modal('show');
              readonly_material_details_secondary(true);
              //---
              var data_arr = [];
              data_arr['material_id']     = $('#txt_wbs_kit_issuance_id_query').val();
              // data_arr['sakidashi_id']     = $('#txt_wbs_sakidashi_issuance_id_query').val();
              data_arr['material_code']   = $('#txt_part_number').val();
              fn_select_material_details(data_arr);
              //---

              dt_materials.ajax.reload();

              $('#mdl_setup_stations').modal('hide');
              $('#mdl_alert #mdl_alert_title').html(data['title']);
              $('#mdl_alert #mdl_alert_body').html(data['body']);
              $('#mdl_alert').modal('show');
              
              if(data['result'] != '0'){
                dt_prod_runcard_stations.ajax.reload();
              }
            }
          },
          completed     : function(data){

          },
          error     : function(data){

          },
        });
      });

      $(document).on('click','#btn_save_material_details_primary',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','#frm_edit_material_details').modal('show');
      });

      $(document).on('click','#btn_save_setup_stations',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','save_setup_stations').modal('show');
      });

      $(document).on('click','#btn_save_prod_runcard_station_stations',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','#frm_edit_prod_runcard_station_details').modal('show');
      });

      $(document).on('click','#btn_save_material_details_secondary',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','#frm_edit_material_details_secondary').modal('show');
      });

      $(document).on('click','#btn_approve_prod',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','save_approve_prod').modal('show');
      });

      $(document).on('click','#btn_approve_qc',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','save_approve_qc').modal('show');
      });

      //-------------------------------------------------------
      $(document).on('keypress',function(e){
        if( ($("#mdl_employee_number_scanner").data('bs.modal') || {})._isShown ){
          $('#txt_employee_number_scanner').focus();
          if( e.keyCode == 13 && $('#txt_employee_number_scanner').val() !='' && ($('#txt_employee_number_scanner').val().length >= 4) ){
            $('#mdl_employee_number_scanner').modal('hide');
            var formid = $("#mdl_employee_number_scanner").attr('data-formid');

            if ( ( formid ).indexOf('#') > -1){
              $( formid ).submit();
            }
            else{
              switch( formid ){
                case 'save_setup_stations':
                save_setup_stations();
                break;
                case 'save_approve_qc':
                save_approve_qc();
                break;
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
            // alert($("#txt_qrcode_scanner").val());
            var formid = $("#mdl_qrcode_scanner").attr('data-formid');

            if ( ( formid ).indexOf('#') > -1){
              $( formid ).submit();
            }
            else{
              switch( formid ){
                case 'fn_scan_machine_code':
                  var val = $('#txt_qrcode_scanner').val();
                  $('#txt_edit_prod_runcard_station_machine option[data-code="'+val+'"]').attr('selected','selected').trigger('change');
                  $('#txt_qrcode_scanner').val("");
                break;

                default:
                break;
              }
            }            
          }//key
        }
      });
      //-------------------------------------------------------

      //----------------------------

      $(document).on('click','#btn_setup_stations',function(e){
        if($("#txt_runcard_no").val() != ""){
          dt_setup_stations.ajax.reload();
          $('#mdl_setup_stations').modal('show');
        }
        else{
          toastr.warning('Fill-out Material Details First');
        }
      });

      // $(document).on('click','#btn_save_setup_stations',function(e){
      //   var arr_substations = [];
      //   var ctr = 0;
      //   $('#tbl_setup_stations tbody .ckb_station:checked').each(function(){
      //     // alert('wew');
      //     arr_substations[ctr] = {
      //         'step' : $(this).closest('td').find('.col_station_step').val(),
      //         'station' : $(this).closest('td').find('.col_station_id').val(),
      //         'substation' : $(this).closest('td').find('.col_sub_station_id').val(),
      //       };
      //     ctr++;
      //   });
      //   if( !jQuery.isEmptyObject(arr_substations) ){
      //     console.log(arr_substations);

      //     var data = {
      //       'txt_prod_runcard_id_query'      : $("#txt_prod_runcard_id_query").val(),
      //       'txt_material_details_emp_num'  : $("#txt_material_details_emp_num").val(),
      //       '_token'                        : '{{ csrf_token() }}',
      //       'arr_substations'               : arr_substations,
      //     }
      //     $.ajax({
      //       'data'      : data,
      //       'type'      : 'post',
      //       'dataType'  : 'json',
      //       'url'       : 'insert_prod_runcard_setup_stations',
      //       success     : function(data){
      //         $('#mdl_setup_stations').modal('hide');
      //         $('#mdl_alert #mdl_alert_title').html(data['title']);
      //         $('#mdl_alert #mdl_alert_body').html(data['body']);
      //         $('#mdl_alert').modal('show');
      //         dt_prod_runcard_stations.ajax.reload();
      //       },
      //       completed     : function(data){

      //       },
      //       error     : function(data){

      //       },
      //     });

      //   }
      //   else{
      //     // alert('Nothing to save.');
      //     toastr.error('Nothing to save!');
      //   }
      // });

      function save_setup_stations(){
        var arr_substations = [];
        var ctr = 0;
        $('#tbl_setup_stations tbody .ckb_station:checked').each(function(){
          arr_substations[ctr] = {
              'step' : $(this).closest('td').find('.col_station_step').val(),
              'station' : $(this).closest('td').find('.col_station_id').val(),
              'substation' : $(this).closest('td').find('.col_sub_station_id').val(),
            };
          ctr++;
        });
        if( !jQuery.isEmptyObject(arr_substations) ){
          // console.log(arr_substations);

          if($("#txt_prod_runcard_id_query").val() != ""){
            var data = {
              'txt_prod_runcard_id_query'      : $("#txt_prod_runcard_id_query").val(),
              'txt_employee_number_scanner'   : $("#txt_employee_number_scanner").val(),
              '_token'                        : '{{ csrf_token() }}',
              'arr_substations'               : arr_substations,
            }
            $.ajax({
              'data'      : data,
              'type'      : 'post',
              'dataType'  : 'json',
              'url'       : 'insert_prod_runcard_setup_stations',
              success     : function(data){
                  $('#mdl_setup_stations').modal('hide');
                  $('#mdl_alert #mdl_alert_title').html(data['title']);
                  $('#mdl_alert #mdl_alert_body').html(data['body']);
                  $('#mdl_alert').modal('show');
                  
                  if(data['result'] != '0'){
                    dt_prod_runcard_stations.ajax.reload();
                  }
              },
              completed     : function(data){

              },
              error     : function(data){

              },
            });
          }
          else{
            toastr.error('Runcard No not found!', 'Set-up Stations');
          }

        }
        else{
          $('#mdl_alert #mdl_alert_title').html('<i class="fa fa-exclamation-triangle text-warning"></i> Message');
          $('#mdl_alert #mdl_alert_body').html('Nothing to save.');
          $('#mdl_alert').modal('show');
        }
      };


      function save_approve_prod(){
        var data = 'txt_prod_runcard_id_query=' + $("#txt_prod_runcard_id_query").val()+'&txt_employee_number_scanner=' + $("#txt_employee_number_scanner").val() + '&_token=' + '{{ csrf_token() }}';

        $.ajax({
          'data'      : data,
          'type'      : 'post',
          'dataType'  : 'json',
          'url'       : 'update_prod_runcard_approval_prod',
          success     : function(data){
            $('#mdl_alert #mdl_alert_title').html(data['title']);
            $('#mdl_alert #mdl_alert_body').html(data['body']);
            $('#mdl_alert').modal('show');

            var data_arr = [];
            data_arr['material_id']     = $('#txt_wbs_kit_issuance_id_query').val();
            // data_arr['sakidashi_id']     = $('#txt_wbs_sakidashi_issuance_id_query').val();
            data_arr['material_code']   = $('#txt_part_number').val();
            fn_select_material_details(data_arr);
          },
          completed     : function(data){
            var data_arr = [];
            data_arr['material_id']     = $('#txt_wbs_kit_issuance_id_query').val();
            // data_arr['sakidashi_id']     = $('#txt_wbs_sakidashi_issuance_id_query').val();
            data_arr['material_code']   = $('#txt_part_number').val();
            fn_select_material_details(data_arr);
            $('#mdl_alert #mdl_alert_title').html(data['title']);
            $('#mdl_alert #mdl_alert_body').html(data['body']);
            $('#mdl_alert').modal('show');
          },
          error     : function(data){
          },
        });
      }

      function save_approve_qc(){
        var data = 'txt_prod_runcard_id_query=' + $("#txt_prod_runcard_id_query").val()+'&txt_employee_number_scanner=' + $("#txt_employee_number_scanner").val() + '&_token=' + '{{ csrf_token() }}';

        $.ajax({
          'data'      : data,
          'type'      : 'post',
          'dataTypesdsd'  : 'json',
          'url'       : 'update_prod_runcard_approval_qc',
          success     : function(data){
            $('#mdl_alert #mdl_alert_title').html(data['title']);
            $('#mdl_alert #mdl_alert_body').html(data['body']);
            $('#mdl_alert').modal('show');
            var data_arr = [];
            data_arr['material_id']     = $('#txt_wbs_kit_issuance_id_query').val();
            // data_arr['sakidashi_id']     = $('#txt_wbs_sakidashi_issuance_id_query').val();
            data_arr['material_code']   = $('#txt_part_number').val();
            fn_select_material_details(data_arr);
          },
          completed     : function(data){
            var data_arr = [];
            data_arr['material_id']     = $('#txt_wbs_kit_issuance_id_query').val();
            // data_arr['sakidashi_id']     = $('#txt_wbs_sakidashi_issuance_id_query').val();
            data_arr['material_code']   = $('#txt_part_number').val();
            fn_select_material_details(data_arr);
            $('#mdl_alert #mdl_alert_title').html(data['title']);
            $('#mdl_alert #mdl_alert_body').html(data['body']);
            $('#mdl_alert').modal('show');
          },
          error     : function(data){

          },
        });
      }



      //----------------------------
      let groupStations = 2;
      let dtOutputStatus = 0;
      dt_prod_runcard_stations = $('#tbl_prod_runcard_stations').DataTable({
        "processing" : true,
          "serverSide" : true,
          "ajax" : {
            url: "select_prod_runcard_stations",
            data: function (param){
                param.prod_runcard_id_query          = $("#txt_prod_runcard_id_query").val();
            }
          },
          
          "columns":[
            { "data" : "raw_action", orderable:false, searchable:false },
            { "data" : "step_num" },
            { "data" : "station.id" },
            { "data" : "station.name" },
            { "data" : "sub_station.name" },
            { "data" : "updated_at" },
            { "data" : "operator_info.name" },
            { "data" : "machine_info.name" },
            { "data" : "qty_input" },
            { "data" : "qty_output" },
            { "data" : "qty_ng" },
            { "data" : "mod" },
          ],
          order: [[1, "asc"]],
          paging: false,
          info: false,
          searching: false,
          pageLength: -1,
          "columnDefs": [ 
                { "visible": false, "targets": groupStations },
                { "visible": false, "targets": groupStations + 1 },
                {
                  "targets": [4, 5, 6, 7, 8, 9, 10, 11],
                  "data": null,
                  "defaultContent": "--"
                },
           ],
           "drawCallback": function ( settings ) {
              var api = this.api();
              var rows = api.rows( {page:'current'} ).nodes();
              var last = null;

              if(api.rows().count() > 0){
                $("#btn_edit_material_details_verification").removeAttr('disabled');
              }
              else{
                $("#btn_edit_material_details_verification").prop('disabled', 'disabled');
              }

              OutputDataCounter(api);

              api.column(groupStations, {page:'current'} ).data().each( function ( group, i ) {
                  if ( last !== group ) {

                    let data = api.row(i).data();
                    let station_name = data.station.name;
                    // let id = data.id;

                    // console.log(id);

                      $(rows).eq( i ).before(
                          '<tr class="group bg-info"><td colspan="11" style="text-align:center;"><b>' + station_name + '</b></td></tr>'
                      );

                      last = group;
                  }
              });
            }
      });//end of DataTable

      //----------------------------

      function OutputDataCounter(api) {
        let counter = 0;

        api.column().data(0, {page:'current'}).each( function (group, i) {

              let data = api.row(i).data();
              let output = data.qty_output;
              if(output != null){
                counter++;
              }
        });

        if(api.rows().count() > 0 && (counter == api.rows().count())){
          dtOutputStatus = true;
        }
        else{
          dtOutputStatus = false;
        }

        if(api.rows().count() > 0){
          $("#btn_edit_material_details_primary").prop('disabled', 'disabled');
        }
        else{
         $("#btn_edit_material_details_primary").removeAttr('disabled');
        }

        // alert(dtOutputStatus);
        if(dtOutputStatus){
          $("#btn_edit_material_details_verification").removeAttr('disabled');
        }
        else{ 
          $("#btn_edit_material_details_verification").prop('disabled', 'disabled');
        }
      }

      $(document).on('click','#btn_scan_machine_code',function(e){
        $('#txt_qrcode_scanner').val('');
        $('#mdl_qrcode_scanner').attr('data-formid','fn_scan_machine_code').modal('show');
      });

      $(document).on('click','.btn_edit_prod_runcard_station',function(e){
        var data_arr = [];
        data_arr['col_prod_runcard_station_id']     = $(this).closest('tr').find('.col_prod_runcard_station_id').val();
        // data_arr['material_code']   = $(this).closest('tr').find('.col_material_code').val();
        fn_select_prod_runcard_station_details(data_arr);
      });

      $('#frm_edit_prod_runcard_station_details').submit(function(e){
        e.preventDefault();

        $.ajax({
          'data'      : $(this).serialize() +'&txt_employee_number_scanner=' + $("#txt_employee_number_scanner").val(),
          'type'      : 'post',
          'dataType'  : 'json',
          'url'       : 'update_prod_runcard_station_details',
          success     : function(data){
            $('#mdl_alert #mdl_alert_title').html(data['title']);
            $('#mdl_alert #mdl_alert_body').html(data['body']);
            $('#mdl_alert').modal('show');

            dt_prod_runcard_stations.ajax.reload();
            $('#mdl_edit_prod_runcard_station_details').modal('hide');
          },
          completed     : function(data){

          },
          error     : function(data){

          },
        });
      });




    });

    function reset_material_details_primary(){
      $('#txt_assessment_no').val('');
      $('#txt_runcard_no').val('');
      // $('#txt_assessed_qty').val('');
      $('#txt_a_drawing_no').val('');
      $('#txt_a_drawing_rev').val('');
      $('#txt_g_drawing_no').val('');
      $('#txt_g_drawing_rev').val('');
      $('#txt_other_docs_no').val('');
      $('#txt_other_docs_rev').val('');
      // $('#txt_material_remarks').val('');

      $('#txt_material_details_emp_num').val('');
      $('#txt_prod_runcard_id_query').val('');
      $('#txt_prod_runcard_verification_id_query').val('');
      $('#txt_wbs_kit_issuance_id_query').val('');
      // $('#txt_wbs_sakidashi_issuance_id_query').val('');
      $('#txt_wbs_kit_issuance_device_code_query').val('');

      readonly_material_details_primary(true);
    }

    function readonly_material_details_primary(v){
      $('#txt_assessment_no').prop('readonly',v);
      // $('#txt_runcard_no').prop('readonly',v);
      // $('#txt_assessed_qty').prop('readonly',v);
      $('#txt_a_drawing_no').prop('readonly',v);
      $('#txt_a_drawing_rev').prop('readonly',v);
      $('#txt_g_drawing_no').prop('readonly',v);
      $('#txt_g_drawing_rev').prop('readonly',v);
      $('#txt_other_docs_no').prop('readonly',v);
      $('#txt_other_docs_rev').prop('readonly',v);
      // $('#sel_comp_under_runcard_no').prop('disabled',v);
      // $('#txt_material_remarks').prop('readonly',v);

      $('#btn_save_material_details_primary').closest('.row').hide();
      if(!v){
        $('#btn_save_material_details_primary').closest('.row').show();
      }
    }

    function reset_material_details_secondary(){
      $('#txt_discrepant_qty_sign').val('0');
      $('#txt_discrepant_qty').val('');
      $('#txt_analysis').val('');
      $('#txt_recount_ok').val('');
      $('#txt_recount_ng').val('');
      $('#txt_prod_approval').val('');
      $('#txt_qc_approval').val('');

      readonly_material_details_secondary(true);
    }

    function readonly_material_details_secondary(v){
      $('#txt_discrepant_qty_sign').prop('disabled',v);
      $('#txt_discrepant_qty').prop('readonly',v);
      $('#txt_analysis').prop('readonly',v);
      $('#txt_recount_ok').prop('readonly',v);
      $('#txt_recount_ng').prop('readonly',v);
      $('#sel_comp_under_runcard_no').prop('disabled',v);

      $('#btn_save_material_details_secondary').closest('.row').hide();
      if(!v){
        $('#btn_save_material_details_secondary').closest('.row').show();
      }
    }






    // Get details from tbl_wbs_kit_issuance table
    function fn_select_material_details(data_arr){
      reset_material_details_primary();
      reset_material_details_secondary();
      var data = {
        'material_id'     : data_arr['material_id'],
        'material_code'   : data_arr['material_code'],
        'txt_wbs_table'   : $("#txt_wbs_table").val(),
        '_token'                        : '{{ csrf_token() }}',
      }
      $.ajax({
        'data'      : data,
        'type'      : 'get',
        'dataType'  : 'json',
        'url'       : 'select_prod_runcard_material_details',
        success     : function(dataJSON){
          let data = dataJSON['lot_number'];
          let wbs_table = dataJSON['txt_wbs_table'];

          // Material Issuance
          if(wbs_table == 1){
            if ($.trim(data)){
              if( $.trim(data[0]['prod_runcards']) ){
                // console.log(data[0]['prod_runcards']['status']);
                $('#txt_prod_runcard_id_query').val(data[0]['prod_runcards']['id']);
                $('#txt_prod_runcard_verification_id_query').val(data[0]['prod_runcards']['id']);

                $('#txt_runcard_no').val(data[0]['prod_runcards']['runcard_no']);
                $('#txt_lot_qty').val(data[0]['prod_runcards']['lot_qty']);
                // alert(data[0]['prod_runcards']['lot_qty']);
                $('#txt_assessment_no').val(data[0]['prod_runcards']['assessment_no']);
                // $('#txt_assessed_qty').val(data[0]['prod_runcards']['assessed_qty']);
                $('#txt_a_drawing_no').val(data[0]['prod_runcards']['a_drawing_no']);
                $('#txt_a_drawing_rev').val(data[0]['prod_runcards']['a_drawing_rev']);
                $('#txt_g_drawing_no').val(data[0]['prod_runcards']['g_drawing_no']);
                $('#txt_g_drawing_rev').val(data[0]['prod_runcards']['g_drawing_rev']);
                $('#txt_other_docs_no').val(data[0]['prod_runcards']['other_docs_no']);
                $('#txt_other_docs_rev').val(data[0]['prod_runcards']['other_docs_rev']);

                $('#sel_comp_under_runcard_no').val(data[0]['prod_runcards']['comp_under_runcard_no']).trigger('change');

                // 3. Verification
                if(data[0]['prod_runcards']['discrepant_qty'] < 0){
                  $('#txt_discrepant_qty_sign').val("-");
                  $('#txt_discrepant_qty').val(data[0]['prod_runcards']['discrepant_qty'] * -1);
                }
                else if(data[0]['prod_runcards']['discrepant_qty'] == 0){
                  $('#txt_discrepant_qty_sign').val("0");
                  $('#txt_discrepant_qty').val(data[0]['prod_runcards']['discrepant_qty']);
                }
                else{
                  $('#txt_discrepant_qty_sign').val("+");
                  $('#txt_discrepant_qty').val(data[0]['prod_runcards']['discrepant_qty']);
                }
                $('#txt_analysis').val(data[0]['prod_runcards']['analysis']);
                $('#txt_recount_ok').val(data[0]['prod_runcards']['recount_ok']);
                $('#txt_recount_ng').val(data[0]['prod_runcards']['recount_ng']);

                if(data[0]['prod_runcards']['supervisor_prod_info'] != null){
                  $('#txt_prod_approval').val(data[0]['prod_runcards']['supervisor_prod_info']['name']);
                }

                if(data[0]['prod_runcards']['supervisor_qc_info'] != null){
                  $('#txt_qc_approval').val(data[0]['prod_runcards']['supervisor_qc_info']['name']);
                }

                $("#btn_setup_stations").removeAttr('disabled');
                $("#btn_edit_material_details_verification").removeAttr('disabled');
                $("#btn_approve_prod").removeAttr('disabled');
                $("#btn_approve_qc").removeAttr('disabled');
              }
              else{

                if(data[0]['kit_issuance']['device_info'] != null || data[0]['kit_issuance']['device_info'] != undefined){
                  $('#txt_lot_qty').val(data[0]['kit_issuance']['device_info']['boxing']);
                }
                $("#btn_setup_stations").prop('disabled', 'disabled');
                $("#btn_edit_material_details_verification").prop('disabled', 'disabled');
                $("#btn_approve_prod").prop('disabled', 'disabled');
                $("#btn_approve_qc").prop('disabled', 'disabled');
              }
              $('#txt_wbs_kit_issuance_id_query').val(data[0]['id']);
              $('#txt_wbs_kit_issuance_device_code_query').val(data[0]['kit_issuance']['device_code']);
              $('#txt_material_name').val(data[0]['item_desc']);
              $('#txt_part_number').val(data[0]['item']);
              $('#txt_lot_number').val(data[0]['lot_no']);
              $('#txt_po_number').val(data[0]['po']);
              $('#txt_po_qty').val(data[0]['kit_issuance']['po_qty']);
              $('#txt_device_code').val(data[0]['kit_issuance']['device_code']);
              $('#txt_use_for_device').val(data[0]['kit_issuance']['device_name']);
            }

            if($("#txt_runcard_no").val() != ""){
              if($("#txt_qc_approval").val() != ""){
                $("#btn_approve_qc").prop("disabled", "disabled");
              }
              else{
                $("#btn_approve_qc").removeAttr("disabled");
                if($("#txt_prod_approval").val() != ""){
                  $("#btn_approve_qc").removeAttr("disabled");
                }
                else{
                  $("#btn_approve_qc").prop("disabled", "disabled");
                }
              }

              if($("#txt_prod_approval").val() != ""){
                $("#btn_approve_prod").prop("disabled", "disabled");
              }
              else{
                $("#btn_approve_prod").removeAttr("disabled");
              }
            }
            else{
              $("#btn_approve_qc").prop("disabled", "disabled");
              $("#btn_approve_prod").prop("disabled", "disabled");
            }

            if($("#txt_discrepant_qty").val() == 0){
              $("#btn_approve_qc").prop("disabled", "disabled");
              $("#btn_approve_prod").prop("disabled", "disabled");
            }



            dt_prod_runcard_stations.ajax.reload();
            // dt_setup_stations.ajax.reload();
            $('#mdl_edit_material_details').modal('show');

          }
          // SAKIDASHI
          else {
            if ($.trim(data)){
              if( $.trim(data[0]['prod_runcards']) ){
                // console.log(data[0]['prod_runcards']['status']);
                $('#txt_prod_runcard_id_query').val(data[0]['prod_runcards']['id']);
                $('#txt_prod_runcard_verification_id_query').val(data[0]['prod_runcards']['id']);

                $('#txt_runcard_no').val(data[0]['prod_runcards']['runcard_no']);
                $('#txt_lot_qty').val(data[0]['prod_runcards']['lot_qty']);
                // alert(data[0]['prod_runcards']['lot_qty']);
                $('#txt_assessment_no').val(data[0]['prod_runcards']['assessment_no']);
                // $('#txt_assessed_qty').val(data[0]['prod_runcards']['assessed_qty']);
                $('#txt_a_drawing_no').val(data[0]['prod_runcards']['a_drawing_no']);
                $('#txt_a_drawing_rev').val(data[0]['prod_runcards']['a_drawing_rev']);
                $('#txt_g_drawing_no').val(data[0]['prod_runcards']['g_drawing_no']);
                $('#txt_g_drawing_rev').val(data[0]['prod_runcards']['g_drawing_rev']);
                $('#txt_other_docs_no').val(data[0]['prod_runcards']['other_docs_no']);
                $('#txt_other_docs_rev').val(data[0]['prod_runcards']['other_docs_rev']);

                $('#sel_comp_under_runcard_no').val(data[0]['prod_runcards']['comp_under_runcard_no']).trigger('change');

                // 3. Verification
                if(data[0]['prod_runcards']['discrepant_qty'] < 0){
                  $('#txt_discrepant_qty_sign').val("-");
                  $('#txt_discrepant_qty').val(data[0]['prod_runcards']['discrepant_qty'] * -1);
                }
                else if(data[0]['prod_runcards']['discrepant_qty'] == 0){
                  $('#txt_discrepant_qty_sign').val("0");
                  $('#txt_discrepant_qty').val(data[0]['prod_runcards']['discrepant_qty']);
                }
                else{
                  $('#txt_discrepant_qty_sign').val("+");
                  $('#txt_discrepant_qty').val(data[0]['prod_runcards']['discrepant_qty']);
                }
                $('#txt_analysis').val(data[0]['prod_runcards']['analysis']);
                $('#txt_recount_ok').val(data[0]['prod_runcards']['recount_ok']);
                $('#txt_recount_ng').val(data[0]['prod_runcards']['recount_ng']);

                if(data[0]['prod_runcards']['supervisor_prod_info'] != null){
                  $('#txt_prod_approval').val(data[0]['prod_runcards']['supervisor_prod_info']['name']);
                }

                if(data[0]['prod_runcards']['supervisor_qc_info'] != null){
                  $('#txt_qc_approval').val(data[0]['prod_runcards']['supervisor_qc_info']['name']);
                }

                if($("#txt_discrepant_qty").val() == ""){
                  $("#btn_setup_stations").removeAttr('disabled');
                  $("#tbl_prod_runcard_stations .btn_edit_prod_runcard_station").removeAttr('disabled');
                  // alert('enable');
                }
                else{
                  $("#btn_setup_stations").prop('disabled', 'disabled');
                  $("#tbl_prod_runcard_stations .btn_edit_prod_runcard_station").prop('disabled', 'disabled');
                  // alert('disable');
                }
                // $("#btn_setup_stations").removeAttr('disabled');

                $("#btn_edit_material_details_verification").removeAttr('disabled');
                $("#btn_approve_prod").removeAttr('disabled');
                $("#btn_approve_qc").removeAttr('disabled');
              }
              else{

                if(data[0]['device_info'] != null || data[0]['device_info'] != undefined){
                  $('#txt_lot_qty').val(data[0]['device_info']['boxing']);
                }
                $("#btn_setup_stations").prop('disabled', 'disabled');
                $("#btn_edit_material_details_verification").prop('disabled', 'disabled');
                $("#btn_approve_prod").prop('disabled', 'disabled');
                $("#btn_approve_qc").prop('disabled', 'disabled');
              }
              $('#txt_wbs_kit_issuance_id_query').val(data[0]['id']);
              $('#txt_wbs_kit_issuance_device_code_query').val(data[0]['device_code']);
              $('#txt_material_name').val(data[0]['tbl_wbs_sakidashi_issuance_item']['item_desc']);
              $('#txt_part_number').val(data[0]['tbl_wbs_sakidashi_issuance_item']['item']);
              $('#txt_lot_number').val(data[0]['tbl_wbs_sakidashi_issuance_item']['lot_no']);
              $('#txt_po_number').val(data[0]['po_no']);
              $('#txt_po_qty').val(data[0]['po_qty']);
              $('#txt_device_code').val(data[0]['device_code']);
              $('#txt_use_for_device').val(data[0]['device_name']);
            }

            if($("#txt_runcard_no").val() != ""){
              if($("#txt_qc_approval").val() != ""){
                $("#btn_approve_qc").prop("disabled", "disabled");
              }
              else{
                $("#btn_approve_qc").removeAttr("disabled");
                if($("#txt_prod_approval").val() != ""){
                  $("#btn_approve_qc").removeAttr("disabled");
                }
                else{
                  $("#btn_approve_qc").prop("disabled", "disabled");
                }
              }

              if($("#txt_prod_approval").val() != ""){
                $("#btn_approve_prod").prop("disabled", "disabled");
              }
              else{
                $("#btn_approve_prod").removeAttr("disabled");
              }
            }
            else{
              $("#btn_approve_qc").prop("disabled", "disabled");
              $("#btn_approve_prod").prop("disabled", "disabled");
            }

            if($("#txt_discrepant_qty").val() == 0){
              $("#btn_approve_qc").prop("disabled", "disabled");
              $("#btn_approve_prod").prop("disabled", "disabled");
            }

            dt_prod_runcard_stations.ajax.reload();
            // dt_setup_stations.ajax.reload();
            $('#mdl_edit_material_details').modal('show');
          }
        },
        completed     : function(data){

        },
        error     : function(data){

        },
      });
    }
    //---------------------
    function fn_select_prod_runcard_station_details(data_arr){
      // reset_material_details_primary();
      // reset_material_details_secondary();
      var data = {
        'col_prod_runcard_station_id' : data_arr['col_prod_runcard_station_id'],
        '_token'                   : '{{ csrf_token() }}',
      }
      $.ajax({
        'data'      : data,
        'type'      : 'get',
        'dataType'  : 'json',
        'url'       : 'select_prod_runcard_station_details',
        success     : function(data){
          // alert(data[0]['station']['name'])
          if ($.trim(data)){
            $('#txt_prod_runcard_station_id_query').val(data[0]['id']);
            $('#txt_edit_prod_runcard_station_step').val(data[0]['step_num']);
            $('#txt_edit_prod_runcard_station_station').val(data[0]['station']['name']);
            $('#txt_edit_prod_runcard_station_substation').val(data[0]['sub_station']['name']);
            $('#txt_edit_prod_runcard_station_date').val( getdate( data[0]['created_at']?data[0]['created_at']:getcurrentdate() ) );
            // $('#txt_edit_prod_runcard_station_date').val(data[0]['created_at']);
            
            if(data[0]['operator_info'] != null){
              $('#txt_edit_prod_runcard_station_operator').val(data[0]['operator_info']['name']);
            }
            else{
              $('#txt_edit_prod_runcard_station_operator').val("");
            }

            if(data[0]['qty_input'] != null){
              $('#txt_edit_prod_runcard_station_input').val(data[0]['qty_input']);
            }
            else{
              $('#txt_edit_prod_runcard_station_input').val($("#txt_lot_qty").val());
            }

            if(data[0]['machine_id'] != null){
              $('#txt_edit_prod_runcard_station_machine').val(data[0]['machine_id']).trigger('change');
            }
            else{
              $('#txt_edit_prod_runcard_station_machine').val("").trigger('change');
            }
            $('#txt_edit_prod_runcard_station_output').val(data[0]['qty_output']);
            $('#txt_edit_prod_runcard_station_ng').val(data[0]['qty_ng']);
            $('#txt_edit_prod_runcard_station_mod').val(data[0]['mod']);
          }

          // if($('#txt_prod_runcard_station_id_query').val() == ""){
          //   $('#txt_edit_prod_runcard_station_input').val($("#txt_lot_qty").val());
          // }

          $('#mdl_edit_prod_runcard_station_details').modal('show');
        },
        completed     : function(data){

        },
        error     : function(data){

        },
      });

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

  </script>
  @endsection
@endauth