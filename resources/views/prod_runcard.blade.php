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
    /*#mdl_edit_material_details>div{*/
      /*width: 2000px!important;*/
      /*min-width: 1400px!important;*/
    /*}*/

    .modal-xl-custom{
      width: 95%!important;
      min-width: 90%!important;
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
                  <!-- <div class="col-sm-3"> -->
                    <!-- <label>Scan PO Code</label> -->
                    <!-- <br> -->
                    <!-- <div class="input-group"> -->
                      <!-- <div class="input-group-prepend">
                        <span class="input-group-text btnSearchPoNo" data-toggle="modal"><i class="fa fa-search"></i></span>
                      </div>
                      <input type="text" class="form-control btnSearchPoNo" id="txt_search_po_number" placeholder="Scan PO Code" readonly="true"> -->
                      <!-- <button type="button" class="btn btn-success btnSearchPoNo" title="Scan PO Code"><i class="fa fa-qrcode"></i></button>
                    </div> -->
                  <!-- </div>
                  <div class="col-sm-1">
                  </div> -->

                 <div class="col-sm-3">
                    <label>PO Number</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <button type="button" class="btn btn-primary btnSearchPoNo" title="Scan PO Code"><i class="fa fa-qrcode"></i></button>
                      </div>
                      <input type="text" class="form-control" id="txt_po_number_lbl" readonly="">
                    </div>
                  </div>

                  <div class="col-sm-3">
                    <label>Device Name</label>
                      <input type="text" class="form-control" id="txt_device_name_lbl" readonly="">
                      <input type="hidden" class="form-control" id="txt_device_code_lbl" readonly="">
                  </div>
                  <div class="col-sm-3">
                    <label>PO Qty</label>
                      <input type="text" class="form-control" id="txt_po_qty_lbl" readonly="">
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
                <h3 class="card-title">List of Runcards</h3>
              </div>

              <!-- Start Page Content -->
              <div class="card-body">
                <div class="row">
                  <div class="col-12">
                    <div class="row">
                     <div class="col-sm-2">
                        <label>Total # of OK</label>
                          <input type="text" class="form-control" id="txt_total_no_of_ok" readonly="">
                      </div>
                      <div class="col-sm-2">
                        <label>Total # of NG</label>
                          <div class="input-group mb-3">
                            <input type="text" class="form-control" id="txt_total_no_of_ng" readonly="">
                            <div class="input-group-append">
                              <button type="button" class="btn btn-primary" title="Show NG Summary" id="btnShowNGSummary" disabled="true"><i class="fa fa-low-vision"></i></button>
                            </div>
                          </div>
                      </div>

                    </div>
                    <div class="float-right">
                      <button class="btn btn-primary btn-sm" id="btnShowAddProdRuncard"><i class="fa fa-plus"></i> Add Runcard</button>
                    </div>
                    <br><br>
                    <div class="table-responsive">
                      <table class="table table-sm table-bordered table-hover" id="tbl_prod_runcard" style="min-width: 900px;">
                        <thead>
                          <tr class="bg-light">
                            <th>Action</th>
                            <th>Status</th>
                            <th>Runcard #</th>
                            <th>Reel Lot #</th>
                            <th>Lot Qty</th>
                            <th class="bg-info">OK - Prod'n Runcard</th>
                            <th class="bg-info">NG</th>
                            <th class="bg-warning">OK - Emboss</th>
                            <th class="bg-warning">NG</th>
                            <!-- <th>Part #</th> -->
                            <!-- <th>Mat'l Lot #</th> -->
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
    <div class="modal fade" id="modalRuncardDetails" tabindex="-1" role="dialog" aria-labelledby="cnptsmodal" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog modal-xl modal-xl-custom modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i class="fa fa-info-circle text-info"></i> Production Runcard Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <!-- <div style="float: center;">
              <h5>Runcard # <span id="spanRuncardNo">---</span></h5>
            </div> -->
            <div class="row">
              <div class="col-sm-3 border px-4">
                <form id="frm_edit_material_details" method="post">
                  <div class="row">
                    <div class="col pt-3">
                      <button type="button" id="btn_edit_material_details_primary" class="btn btn-sm btn-link float-right"><i class="fa fa-edit"></i> Edit</button>
                      <span class="badge badge-secondary">1.</span> Details
                    </div>
                  </div>
                  <!-- <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Material Lot Number</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_lot_number" readonly>
                      </div>
                    </div>
                  </div> -->
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">PO #</span>
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
                  <!-- <br> -->
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
                        <input type="text" class="form-control form-control-sm" id="txt_device_code" name="txt_device_code" readonly>
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
                        <input type="text" class="form-control form-control-sm" id="txt_runcard_no" name="txt_runcard_no" placeholder="Auto generated" readonly="readonly" style="color: green; font-weight: bold; font-size: 15px;">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Reel Lot No.</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_reel_lot_no" name="txt_reel_lot_no" placeholder="Auto generated" readonly="true">
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
                        <input type="text" class="form-control form-control-sm" id="txt_a_drawing_no" name="txt_a_drawing_no" readonly="true">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Revision</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_a_drawing_rev" name="txt_a_drawing_rev" readonly="true">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">G Drawing Number</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_g_drawing_no" name="txt_g_drawing_no" readonly="true">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Revision</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_g_drawing_rev" name="txt_g_drawing_rev" readonly="true">
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
                      <input type="hidden" name="txt_material_details_emp_num" id="txt_material_details_emp_num">
                      <input type="hidden" name="txt_prod_runcard_id_query" id="txt_prod_runcard_id_query">
                      <input type="hidden" name="txt_prod_runcard_status" id="txt_prod_runcard_status">
                      <input type="hidden" name="require_oqc_before_emboss" id="txt_prod_runcard_require_oqc_before_emboss">
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
              <div class="col-sm-9">
                <div class="row">
                  <div class="col border py-3 px-4 border-left-0 border-bottom-0">
                    <span class="badge badge-secondary">2.</span> Material List
                    <div class="float-right">
                      <button type="button" class="btn btn-sm btn-info float-right mb-1" id="btnSaveSelectedMatSak" disabled="disabled"><i class="fa fa-save"></i> Save Material List <span id="spanNoOfSelectedMatSak"></span></button> 

                      <button type="button" class="btn btn-sm btn-success float-right mb-1" id="btnSaveSelectedEmboss" disabled="disabled" style="display: none;"><i class="fa fa-save"></i> Save Emboss Material List <span id="spanNoOfSelectedEmboss"></span></button>
                    </div>
                    <br><br>
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Material Kitting & Issuance</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Sakidashi Issuance</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="materialEmbossTabLink" data-toggle="tab" href="#materialEmbossTab" role="tab" aria-controls="emboss" aria-selected="false">Emboss Sealing</a>
                      </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                      <div class="tab-pane fade show active my-2" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <!-- <br>
                        <div class="row">
                          <div class="col-sm-3">
                            <label>Search Transfer Slip #</label>
                            <div class="input-group">
                              <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-search"></i></span>
                              </div>
                              <input type="text" class="form-control" id="txt_search_trans_slip_no" placeholder="---">
                            </div>
                          </div>
                        </div>
                        <br> -->
                        <!-- <div class="row">
                          <div class="col-sm-3">
                            <label>Search Lot #</label>
                            <div class="input-group">
                              <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-search"></i></span>
                              </div>
                              <input type="text" class="form-control" id="txt_search_material_lot_no" placeholder="Scan Lot # Code">
                            </div>
                          </div>
                          <div class="col-sm-6">
                          </div>
                          <div class="col-sm-3">
                              <label>Current Lot #</label>
                              <input type="text" class="form-control" id="txt_material_lot_no_lbl" readonly="">
                          </div>
                        </div>
                        <br> -->

                        <!-- <br>
                        <div class="row">
                          <div class="col-sm-3">
                            <label>Search Transfer Slip #</label>
                            <div class="input-group">
                              <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-search"></i></span>
                              </div>
                              <input type="text" class="form-control" id="txt_search_trans_slip_no" placeholder="---">
                            </div>
                          </div>
                        </div>
                        <br> -->
                        <div class="row">
                          <div class="col-sm-3">
                            <label>Search Transfer Slip #</label>
                            <div class="input-group">
                              <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-search"></i></span>
                              </div>
                              <input type="text" class="form-control" id="txt_search_material_transfer_slip" placeholder="Scan Transfer Slip # Code">
                            </div>
                          </div>
                          <div class="col-sm-6">
                          </div>
                          <div class="col-sm-3">
                              <label>Current Transfer Slip #</label>
                              <input type="text" class="form-control" id="txt_material_transfer_slip_lbl" readonly="">
                          </div>
                        </div>
                        <br>

                        <div class="table-responsive" style="max-height: 500px;">
                          <table class="table table-sm table-bordered table-hover small" id="tbl_materials" style="min-width: 900px;">
                            <thead>
                              <tr class="bg-light">
                                <th>Action</th>
                                <th>Status</th>
                                <th>Material Name</th>

                                <th>Transfer Slip #</th>
                                <th>Assessment #</th>
                                <th>Part #</th>
                                <th>Mat'l Lot #</th>
                                <!-- <th>Qty to comp.</th>
                                <th>Runcard Qty Used</th> -->
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
                          </table>
                        </div>

                      </div>

                      <!-- Sakidashi Tab -->
                      <div class="tab-pane fade my-2" id="profile" role="tabpanel" aria-labelledby="profile-tab">

                        <!-- <br>
                        <div class="row">
                          <div class="col-sm-3">
                            <label>Search Ctrl #</label>
                            <div class="input-group">
                              <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-search"></i></span>
                              </div>
                              <input type="text" class="form-control" id="txt_search_si_ctrl_no" placeholder="---">
                            </div>
                          </div>
                        </div>
                        <br>
 -->
                        <div class="row">
                          <div class="col-sm-3">
                            <label>Search Lot #</label>
                            <div class="input-group">
                              <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-search"></i></span>
                              </div>
                              <input type="text" class="form-control" id="txt_search_sakidashi_lot_no" placeholder="Scan Lot # Code">
                            </div>
                          </div>
                          <div class="col-sm-6">
                          </div>
                          <div class="col-sm-3">
                              <label>Current Lot #</label>
                              <input type="text" class="form-control" id="txt_sakidashi_lot_no_lbl" readonly="">
                          </div>
                        </div>
                        <br>

                        <!-- <br>
                        <div class="row">
                          <div class="col-sm-3">
                            <label>Search Ctrl #</label>
                            <div class="input-group">
                              <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-search"></i></span>
                              </div>
                              <input type="text" class="form-control" id="txt_search_si_ctrl_no" placeholder="---">
                            </div>
                          </div>
                        </div>
                        <br>
 -->
                        <!-- <div class="row">
                          <div class="col-sm-3">
                            <label>Search Ctrl #</label>
                            <div class="input-group">
                              <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-search"></i></span>
                              </div>
                              <input type="text" class="form-control" id="txt_search_sakidashi_ctrl_no" placeholder="Scan Ctrl # Code">
                            </div>
                          </div>
                          <div class="col-sm-6">
                          </div>
                          <div class="col-sm-3">
                              <label>Current Ctrl #</label>
                              <input type="text" class="form-control" id="txt_sakidashi_ctrl_no_lbl" readonly="">
                          </div>
                        </div> -->

                        <br>

                        <div class="table-responsive" style="max-height: 500px;">
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
                                <!-- <th>Qty to comp.</th>
                                <th>Runcard Qty Used</th> -->
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
                          </table>
                        </div>
                      </div>

                      <!-- Emboss Sealing -->
                      <div class="tab-pane fade my-2" id="materialEmbossTab" role="tabpanel" aria-labelledby="emboss-tab">
                        <div class="row">
                          <div class="col-sm-3">
                            <label>Search Lot #</label>
                            <div class="input-group">
                              <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-search"></i></span>
                              </div>
                              <input type="text" class="form-control" id="txt_search_emboss_lot_no" placeholder="Scan Lot # Code">
                            </div>
                          </div>
                          <div class="col-sm-6">
                          </div>
                          <div class="col-sm-3">
                              <label>Current Lot #</label>
                              <input type="text" class="form-control" id="txt_emboss_lot_no_lbl" readonly="">
                          </div>
                        </div>
                        <br>

                        <div class="table-responsive" style="max-height: 500px;">
                          <table class="table table-sm table-bordered table-hover small" id="tbl_emboss">
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
                                <!-- <th>Qty to comp.</th>
                                <th>Runcard Qty Used</th> -->
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
                <div class="row">
                  <div class="col border py-3 px-4 border-left-0 border-bottom-0">
                    <button type="button" class="btn btn-sm btn-info float-right mb-1" id="btn_setup_stations" disabled="disabled" style="display: none;"><i class="fa fa-cog"></i> Set-up stations</button>
                    <!-- <div style="float: right;">
                      <select class="form-control form-control-sm" id="sel_runcard_type" name="sel_runcard_type">
                          <option value="0">For Production Runcard</option>
                          <option value="1">For Emboss Sealing</option>
                        </select>
                    </div> -->
                    <div class="row align-items-center">
                      <div class="col-sm-8">
                        <span class="badge badge-secondary">3.</span> Stations 
                      </div>

                      <div class="input-group input-group-sm mb-3 col-sm-4" style="float: right;">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Runcard Type</span>
                        </div>
                        <select class="form-control form-control-sm" id="sel_runcard_type" name="sel_runcard_type">
                          <option value="0">For Production Runcard</option>
                          <option value="1">For Emboss Sealing</option>
                        </select>
                      </div>
                    </div>

                    <div class="table-responsive">
                      <table class="table table-sm small table-bordered table-hover" id="tbl_prod_runcard_stations" style="width: 100%;">
                        <thead>
                          <tr class="bg-light">
                            <th></th>
                            <th>Step</th>
                            <th>Station ID</th>
                            <th>Station</th>
                            <th>Process</th>
                            <th>Date Time</th>
                            <th>Operator</th>
                            <th>Machine</th>
                            <th>Input</th>
                            <th>Output</th>
                            <th>NG QTY</th>
                            <th>MOD</th>
                            <th>Selected MOD</th>
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
                          <span class="badge badge-secondary">4.</span> Verification
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
                                  <option value=""> N/A </option>
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
            <button type="button" class="btn btn-sm btn-success" id="btn_approve_prod" disabled><i class="fa fa-check-circle"></i> Prod Approved</button>
            <button type="button" class="btn btn-sm btn-success" id="btn_approve_qc" disabled><i class="fa fa-check-circle"></i> QC Approved</button>
            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
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
                        <th><input type="checkbox" id="chkCheckAllSetupStations"></th>
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
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content modal-lg">
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
                    <input type="text" class="form-control form-control-sm" id="txt_edit_prod_runcard_station_step" name="txt_edit_prod_runcard_station_step" readonly>
                    <input type="hidden" class="form-control form-control-sm" id="txt_edit_prod_runcard_station_has_emboss" name="has_emboss" readonly=true>
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
                      <span class="input-group-text w-100" id="basic-addon1">Machine</span>
                    </div>
                    <!-- <input type="text" class="form-control form-control-sm" id="txt_edit_prod_runcard_station_machine" name="txt_edit_prod_runcard_station_machine"> -->

                    <!-- <select class="form-control form-control-sm select2bs4 selectMachine" name="txt_edit_prod_runcard_station_machine" id="txt_edit_prod_runcard_station_machine" style="width: 100%;">
                    </select> -->

                    <select class="form-control select2 select2bs4 selectMachine" id="txt_edit_prod_runcard_station_machine" name="txt_edit_prod_runcard_station_machine[]" multiple="multiple">
                      <option value=""> N/A </option>
                    </select>
                    <div class="input-group-append">
                      <button class="btn btn-info" type="button" title="Scan code" id="btn_scan_machine_code"><i class="fa fa-qrcode"></i></button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row" style="display: none;">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Assigned Machine</span>
                    </div>

                    <select class="form-control select2 select2bs4 selectMachine" id="txt_edit_prod_runcard_station_assigned_machine" name="txt_edit_prod_runcard_station_assigned_machine" multiple="multiple" readonly="readonly">
                      <option value=""> N/A </option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="row" style="display: block;">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Assigned Machine</span>
                    </div>

                    <select class="form-control select2 select2bs4 selectMachine" id="txt_edit_prod_runcard_station_assigned_machine_visible" name="txt_edit_prod_runcard_station_assigned_machine_visible" multiple="multiple" disabled="true">
                      <option value=""> N/A </option>
                    </select>
                  </div>
                </div>
              </div>


              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Input</span>
                    </div>
                    <input type="number" class="form-control form-control-sm" id="txt_edit_prod_runcard_station_input" name="txt_edit_prod_runcard_station_input" readonly="true">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Output</span>
                    </div>
                    <input type="number" class="form-control form-control-sm" id="txt_edit_prod_runcard_station_output" name="txt_edit_prod_runcard_station_output" readonly="true">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">NG Qty</span>
                    </div>
                    <input type="number" class="form-control form-control-sm" id="txt_edit_prod_runcard_station_ng" name="txt_edit_prod_runcard_station_ng" min="0" value="0">
                  </div>
                </div>
              </div>
              <!-- <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">MOD</span>
                    </div>
                    <textarea class="form-control form-control-sm" id="txt_edit_prod_runcard_station_mod" name="txt_edit_prod_runcard_station_mod"></textarea>
                  </div>
                </div>
              </div> -->

              <!-- <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Operator</span>
                    </div>
                    <input type="text" class="form-control form-control-sm" id="txt_edit_prod_runcard_operator" name="txt_edit_prod_runcard_operator" readonly>
                  </div>
                </div>
              </div> -->

              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Operator</span>
                    </div>
                    <select class="form-control select2 select2bs4 selectUser" id="txt_edit_prod_runcard_operator" name="txt_edit_prod_runcard_operator[]" multiple="multiple">
                      <option value=""> N/A </option>
                    </select>
                    <div class="input-group-append">
                      <button class="btn btn-info" type="button" title="Scan code" id="btn_scan_add_runcard_operator_code"><i class="fa fa-qrcode"></i></button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row" style="display: none;">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Certified Operator</span>
                    </div>
                    <select class="form-control select2 select2bs4 selectUser" id="txt_edit_prod_runcard_cert_operator" name="txt_edit_prod_runcard_cert_operator[]" multiple="multiple">
                      <option value=""> N/A </option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="row" style="display: block;">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Certified Operator</span>
                    </div>
                    <select class="form-control select2 select2bs4 selectUser" id="txt_edit_prod_runcard_cert_operator_visible" name="txt_edit_prod_runcard_cert_operator[]" multiple="multiple" disabled="true">
                      <option value=""> N/A </option>
                    </select>
                  </div>
                </div>
              </div>

              <!-- MATERIAL KITTING -->
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Material Kitting Item</span>
                    </div>

                    <select class="form-control select2 select2bs4 selWBSMatKitItem" id="txt_edit_prod_runcard_station_material_kitting" name="txt_edit_prod_runcard_station_material_kitting[]" multiple="multiple">
                      <option value=""> N/A </option>
                    </select>
                    <div class="input-group-append">
                      <button class="btn btn-info" type="button" title="Scan code" id="btn_scan_add_runcard_material_kitting"><i class="fa fa-qrcode"></i></button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row" style="display: none;">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Assigned Material Kitting Item</span>
                    </div>

                    <select class="form-control select2 select2bs4 selWBSMatKitItem" id="txt_edit_prod_runcard_station_assigned_material_kitting" multiple="multiple">
                      <option value=""> N/A </option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="row" style="display: block;">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Assigned Material Kitting Item</span>
                    </div>

                    <select class="form-control select2 select2bs4 selWBSMatKitItem" id="txt_edit_prod_runcard_station_assigned_material_kitting_visible" multiple="multiple" disabled="true">
                      <option value=""> N/A </option>
                    </select>
                  </div>
                </div>
              </div>

              <!-- SAKIDASHI -->
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Sakidashi Item</span>
                    </div>

                    <select class="form-control select2 select2bs4 selWBSSakIssuItem" id="txt_edit_prod_runcard_station_sakidashi" name="txt_edit_prod_runcard_station_sakidashi[]" multiple="multiple">
                      <option value=""> N/A </option>
                    </select>
                    <div class="input-group-append">
                      <button class="btn btn-info" type="button" title="Scan code" id="btn_scan_add_runcard_sakidashi"><i class="fa fa-qrcode"></i></button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row" style="display: none;">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Assigned Sakidashi Item</span>
                    </div>

                    <select class="form-control select2 select2bs4 selWBSSakIssuItem" id="txt_edit_prod_runcard_station_assigned_sakidashi" multiple="multiple">
                      <option value=""> N/A </option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="row" style="display: block;">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Assigned Sakidashi Item</span>
                    </div>

                    <select class="form-control select2 select2bs4 selWBSSakIssuItem" id="txt_edit_prod_runcard_station_assigned_sakidashi_visible" multiple="multiple" disabled="true">
                      <option value=""> N/A </option>
                    </select>
                  </div>
                </div>
              </div>

              <!-- EMBOSS -->
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Emboss Item</span>
                    </div>

                    <select class="form-control select2 select2bs4 selWBSEmbossIssuItem" id="txt_edit_prod_runcard_station_emboss" name="txt_edit_prod_runcard_station_emboss[]" multiple="multiple">
                      <option value=""> N/A </option>
                    </select>
                    <div class="input-group-append">
                      <button class="btn btn-info" type="button" title="Scan code" id="btn_scan_add_runcard_emboss"><i class="fa fa-qrcode"></i></button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row" style="display: none;">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Assigned Emboss Item</span>
                    </div>

                    <select class="form-control select2 select2bs4 selWBSEmbossIssuItem" id="txt_edit_prod_runcard_station_assigned_emboss" multiple="multiple">
                      <option value=""> N/A </option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="row" style="display: block;">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Assigned Emboss Item</span>
                    </div>

                    <select class="form-control select2 select2bs4 selWBSEmbossIssuItem" id="txt_edit_prod_runcard_station_assigned_emboss_visible" multiple="multiple" disabled="true">
                      <option value=""> N/A </option>
                    </select>
                  </div>
                </div>
              </div>

              <!-- <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Materials</span>
                    </div>
                    <textarea rows="3" class="form-control form-control-sm" id="txt_edit_prod_runcard_station_materials" name="txt_edit_prod_runcard_station_materials"></textarea>
                  </div>
                </div>
              </div> -->
              <hr>
              <div class="table-responsive">
                <div style="float: left;">
                  <label>Total No. of NG: <span id="pRCStatTotNoOfNG" style="color: green;">0</span></label>
                </div>
                <div style="float: right;">
                  <button type="button" id="btnAddMODTable" class="btn btn-xs btn-info" title="Add MOD"><i class="fa fa-plus"></i> Add MOD</button>
                </div>
                <br><br>
                <table class="table table-sm" id="tblEditProdRunStaMOD">
                  <thead>
                    <tr>
                      <th style="width: 60%;">MOD</th>
                      <th style="width: 20%;">QTY</th>
                      <th style="width: 20%;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- <tr>
                      <td>
                        <select class="form-control select2 select2bs4 selectMOD" id="selEditProdRunMod" name="mod[]">
                          <option value="">N/A</option>
                        </select>
                      </td>
                      <td>
                        <input type="number" class="form-control txtEditProdRunStaMODQty" name="mod_qty[]">
                      </td>
                      <td></td>
                    </tr> -->
                  </tbody>
                </table>
              </div>

            </form>
          </div>
          <div class="modal-footer">
            <input type="hidden" id="txt_prod_runcard_station_id_query" name="txt_prod_runcard_station_id_query" form="frm_edit_prod_runcard_station_details">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-success" form="frm_edit_prod_runcard_station_details" id="btn_save_prod_runcard_station_stations" disabled="true">Save</button>
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

    <!-- Modal Scan PO -->
    <div class="modal fade" id="modalScanPOTransLotCode" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
              Please scan the PO code.
              <br>
              <br>
              <h1><i class="fa fa-qrcode fa-lg"></i></h1>
            </div>
            <input type="text" id="txtSearchPoTransLotNo" class="hidden_scanner_input">
          </div>
        </div>
      </div>
    </div>
    <!-- /.Modal -->

    <div class="modal fade" id="modalNGSummary">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">NG Summary</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <label style="float: right; font-size: 20px;">PO #: <span id="spanNGSummaryPoNo">--</span></label>
            <br><br>
            <div class="table-responsive">
              <table class="table table-sm table-bordered table-hover dataTable no-footer" id="tblNGSummary" style="width: 100%;">
                <thead>
                  <tr>
                    <th>Production Runcard ID.</th>
                    <th>Production Runcard No.</th>
                    <th>Mode of Defect</th>
                    <th>Qty</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="submit" class="btn btn-default" data-dismiss="modal">Close</button>
            <!-- <button type="submit" id="btnChangeDeviceStat" class="btn btn-primary"><i id="iBtnChangeDeviceStatIcon" class="fa fa-check"></i> Yes</button> -->
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

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
    <textarea id="txt_str" style="opacity: 0.01"></textarea>
  </div>
  <!-- /.content-wrapper -->
  @endsection

  @section('js_content')

  <!-- <script src="{{ URL::asset('public/template/plugins/jquery-ui/jquery-ui.min.js') }}"></script> -->
  <script src="{{ URL::asset('public/template/plugins/qz-print-free_1.8.0_src/qz-print/js/deployJava.js') }}"></script>
  <script type="text/javascript">
    let dt_materials, dt_setup_stations, dt_prod_runcard_stations, dt_sakidashi, dt_emboss, dt_ng_summary;
    let dt_prod_runcard;
    let currentPoNo = "";
    let currentTransSlipNo = "";
    let currentCtrlNo = "";
    let currentEmbossCtrlNo = "";
    let arrSelectedMaterial = [];
    let arrSelectedSakidashi = [];
    let arrSelectedEmboss = [];
    let totalNoOfOk = 0;
    let totalNoOfNG = 0;
    let boxing = "";
    let assessment = "";
    let aDrawing = "";
    let aDrawingRev = "";
    let gDrawing = "";
    let gDrawingRev = "";
    let materialKitIssuanceLotNo = "";
    let sakidashiIssuanceLotNo = "";
    let embossIssuanceLotNo = "";
    let materialKitTransferSlip = "";
    let sakidashiCtrlNo = "";
    let embossCtrlNo = "";
    let hasProdMatSakList = false;

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

    //-----
    var stickers=new Array();//creates an array to store the sticker ZPL string codes
    var java_enabled = navigator.javaEnabled();
    if(java_enabled){
      deployQZ();//deploys the QZapplet for printing
      $('#txt_str').attr('data-java_enabled',1);
    }else{
      $('#txt_str').attr('data-java_enabled',0);
    }
    function proceed_to_print(){
       var data_str = $('#txt_str').val();
       stickers=data_str.split(":-:");//the data returned by this ajax would be the sticker ZPL codes. If the sticker is more than one, the stickers are separated by the delimeter ":-:"
       findPrinter();//calls the findprinter function passing the string "ZPL" - ppc
    }
    /**
    * Deploys different versions of the applet depending on Java version.
    * Useful for removing warning dialogs for Java 6.  This function is optional
    * however, if used, should replace the <applet> method.  Needed to address 
    * MANIFEST.MF TrustedLibrary=true discrepency between JRE6 and JRE7.
    */
    function deployQZ() {
      var attributes = {id: "qz", code:'qz.PrintApplet.class', 
      archive:"{{ URL::asset('public/template/plugins/qz-print-free_1.8.0_src/qz-print/dist/qz-print.jar') }}", width:1, height:1};
      var parameters = {jnlp_href: "{{ URL::asset('public/template/plugins/qz-print-free_1.8.0_src/qz-print/dist/qz-print_jnlp.jnlp') }}", 
      cache_option:'plugin', disable_logging:'false', 
      initial_focus:'false'};
      if (deployJava.versionCheck("1.7+") == true) {}
        else if (deployJava.versionCheck("1.6+") == true) {
          attributes['archive'] = "{{ URL::asset('public/template/plugins/qz-print-free_1.8.0_src/qz-print/dist/jre6/qz-print.jar') }}";
          parameters['jnlp_href'] = "{{ URL::asset('public/template/plugins/qz-print-free_1.8.0_src/qz-print/dist/jre6/qz-print_jnlp.jnlp') }}";
        }
        deployJava.runApplet(attributes, parameters, '1.5');
      }

    /**
    * Returns whether or not the applet is not ready to print.
    * Displays an alert if not ready.
    */
    function notReady() {
      // If applet is not loaded, display an error
      if (!isLoaded()) {
        return true;
      }
      // If a printer hasn't been selected, display a message.
      else if (!qz.getPrinter()) {
        $("#messenger").html('Please select a printer first by using the "Detect Printer" button.');
        return true;
      }
      return false;
    }
    /**
    * Returns is the applet is not loaded properly
    */
    function isLoaded() {
      if (!qz) {
        $("#messenger").html('Error:\n\n\tPrint plugin is NOT loaded!');
        return false;
      } else {
        try {
          if (!qz.isActive()) {
            $("#messenger").html('Error:\n\n\tPrint plugin is loaded but NOT active!');
            return false;
          }
        } catch (err) {
          $("#messenger").html('Error:\n\n\tPrint plugin is NOT loaded properly!');
          return false;
        }
      }
      return true;
    }
    /***************************************************************************
    * Prototype function for finding the closest match to a printer name.
    * Usage:
    *    qz.findPrinter('zebra');
    *    window['qzDoneFinding'] = function() { alert(qz.getPrinter()); };
    ***************************************************************************/
    function findPrinter() {
      if (isLoaded()) {
         // var name = "ZDesigner ZT220-200dpi";//calls the findprinter function passing the string "ZPL" - ppc
         var name = "ZDesigner ZT230-200dpi ZPL ZT220";//calls the findprinter function passing the string "ZPL" - packing


          // Searches for locally installed printer with specified name
          qz.findPrinter(name);
          
          // Automatically gets called when "qz.findPrinter()" is finished.
          window['qzDoneFinding'] = function() {
          var printer = qz.getPrinter();
          printZPL();//printing ZPL
         // Remove reference to this function
         window['qzDoneFinding'] = null;
        };
      }
    }
    function printZPL() {//function for printing

      if (notReady()) { return; }
      $("#messenger").html("printing...");
      for(var i=0;i<stickers.length;i++)
      {
        qz.append(stickers[i]);//sets which printer to print
        qz.print();//prints the stickers
     }
     $("#messenger").html("Done.");
    }

    //-----

    $(document).ready(function () {
      //-----
      //-----
      //-----
      $( ".modal" ).on('shown.bs.modal', function(){
        $(this).find('.hidden_scanner_input').focus();
      });
      $('#tbl_prod_runcard').on('click','.btnPrintRuncardC3Label',function(){
        $('#txt_str').val('');
        if( $('#txt_str').attr('data-java_enabled')==1 ){
          var data = {
            'txt_runcard_id'                : $(this).attr('runcard-id'),
          };
          $.ajax({
            'data'      : data,
            'type'      : 'get',
            'dataType'  : 'json',
            'url'       : 'print_c3_label',
            success     : function(data){
              var html = '';
              if($.trim(data)){
                $('#txt_str').val(data['lbl']);
                if( data['result_code']==1 ){
                  proceed_to_print();
                  toastr.success('Sent to printer.');
                 }
                else{
                  toastr.warning('No lot number to print.');
                }
              }
            }
          });
        }
        else{
          toastr.warning('Java is not loaded in Chrome. Please contact ISS local 205.');
        }
      });
      //-----
      //-----
      //-----

      bsCustomFileInput.init();
      //Initialize Select2 Elements
      $('.select2').select2();

      //Initialize Select2 Elements
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });

      GetProductionRuncards($("#sel_comp_under_runcard_no"), 1);
      GetUserList($(".selectUser"));
      GetCboMOD($(".selectMOD"));

      // List of Materials in select2
      GetMaterialKittingList($(".selWBSMatKitItem"));
      GetSakidashiList($(".selWBSSakIssuItem"));
      GetEmbossList($(".selWBSEmbossIssuItem")); 

      GetCboMachine($(".selectMachine"));
      // $("#txt_search_po_number").focus();

       $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
          var target = $(e.target).attr("href") // activated tab
          // alert(target);
          
          if(target == "#home"){
            $("#txt_search_material_transfer_slip").focus();
            $("#btnSaveSelectedMatSak").css({'display': 'block'});
            $("#btnSaveSelectedEmboss").css({'display': 'none'});
          }
          else if(target == "#profile"){
            $("#txt_search_sakidashi_lot_no").focus();
            $("#btnSaveSelectedMatSak").css({'display': 'block'});
            $("#btnSaveSelectedEmboss").css({'display': 'none'});
          }
          else if(target == "#materialEmbossTab"){
            $("#txt_search_emboss_lot_no").focus();
            $("#btnSaveSelectedEmboss").css({'display': 'block'});
            $("#btnSaveSelectedMatSak").css({'display': 'none'});
          }
        });

      // $('input').each(function(i, obj) {
      //   if (!this.hasAttribute("placeholder")) {
      //     if( $(this).prop('type') == 'number' ){
      //       $(this).prop('placeholder','0');
      //     }
      //     if( $(this).prop('type') == 'text' ){
      //       $(this).prop('placeholder','---');
      //     }
      //   }
      // });

      $("#tblEditProdRunStaMOD").on('keyup', '.txtEditProdRunStaMODQty', function(){
        // console.log('wew');
        let totalNoOfMOD = 0;

        $('#tblEditProdRunStaMOD .txtEditProdRunStaMODQty').each(function(i, obj) {
          if($(this).val() == null || $(this).val() == ""){
          }
          else{
            totalNoOfMOD += parseFloat($(this).val());
          }
        });
        if($("#txt_edit_prod_runcard_station_ng").val() != totalNoOfMOD){
          toastr.warning('MOD NG Qty not Tally!');
          $("#pRCStatTotNoOfNG").css({color: 'red'});
          $("#btn_save_prod_runcard_station_stations").prop('disabled', true);
        }
        else{
          $("#pRCStatTotNoOfNG").css({color: 'green'});
          $("#btn_save_prod_runcard_station_stations").prop('disabled', false);
        }
        $("#pRCStatTotNoOfNG").text(totalNoOfMOD);
      });

      $("#tblEditProdRunStaMOD").on('change', '.txtEditProdRunStaMODQty', function(){
        // console.log('wew');
        let totalNoOfMOD = 0;

        $('#tblEditProdRunStaMOD .txtEditProdRunStaMODQty').each(function(i, obj) {
          if($(this).val() == null || $(this).val() == ""){
          }
          else{
            totalNoOfMOD += parseFloat($(this).val());
          }
        });
        if($("#txt_edit_prod_runcard_station_ng").val() != totalNoOfMOD){
          toastr.warning('MOD NG Qty not Tally!');
          $("#pRCStatTotNoOfNG").css({color: 'red'});
          $("#btn_save_prod_runcard_station_stations").prop('disabled', true);
        }
        else{
          $("#pRCStatTotNoOfNG").css({color: 'green'});
          $("#btn_save_prod_runcard_station_stations").prop('disabled', false);
        }
        $("#pRCStatTotNoOfNG").text(totalNoOfMOD);
      });

      $('textarea').each(function(i, obj) {
        if (!this.hasAttribute("placeholder")) {
          $(this).prop('placeholder','...');
        }
      });

      //---

      dt_prod_runcard      = $('#tbl_prod_runcard').DataTable({
        "processing" : true,
          "serverSide" : true,
          "ajax" : {
            url: "get_prod_runcard_by_po",
            data: function (param){
                param.po_number = currentPoNo;
            }
          },
          bAutoWidth: false,
          "columns":[
            { "data" : "raw_action", orderable:false, searchable:false },
            { "data" : "raw_status", orderable:false, searchable:false },
            { "data" : "runcard_no" },
            { "data" : "reel_lot_no" },
            { "data" : "lot_qty" },
            // { "data" : "prod_runcard_station_many_details.0.qty_output" },
            {
                name: 'prod_runcard_station_many_details',
                data: 'prod_runcard_station_many_details',
                sortable: false,
                searchable: false,
                render: function (data) {
                  console.log(data);
                    var result = '';
                    // if(data.length > 0){
                    //   for(let index = 0; index < data.length; index++){
                    //     result += '<span class="badge badge-pill badge-secondary">' + data[index]['step_num'] + ' - ' + data[index]['qty_output'] + '</span>';

                    //     if(index <= parseInt(data.length) - 2){
                    //       result += '<br>';
                    //     }
                    //   }
                    // }
                    // else{
                    //   result = null;
                    // }

                    if(data.length > 0){
                      for(let index = data.length - 1; index >= 0; index--){
                        // Get Not Emboss if OQC Required
                        if($("#txt_prod_runcard_require_oqc_before_emboss").val() == 1){
                          if(data[index]['has_emboss'] == 0){
                            // result += '<span class="badge badge-pill badge-secondary">' + data[index]['step_num'] + ' - ' + data[index]['qty_output'] + '</span>';
                            result = data[index]['qty_output'];
                            break;
                          }
                        }
                        // Get Emboss if OQC is NOT Required
                        else{
                          if(data[index]['has_emboss'] == 1){
                            result = data[index]['qty_output'];
                            break;
                          }
                        }
                        // else{
                        //   result += '<span class="badge badge-pill badge-secondary">' + data[index]['step_num'] + ' - ' + data[index]['qty_output'] + '</span>';

                        //   if(index >= 0){
                        //     result += '<br>';
                        //   }
                        // }
                      }
                    }
                    else{
                      result = null;
                    }
                    
                    return result;
                }
            },
            { "data" : "total_no_of_ng" },
            {
                name: 'prod_runcard_station_many_details',
                data: 'prod_runcard_station_many_details',
                sortable: false,
                searchable: false,
                render: function (data) {
                    var result = '';
                    // if(data.length > 0){
                    //   for(let index = 0; index < data.length; index++){
                    //     result += '<span class="badge badge-pill badge-secondary">' + data[index]['step_num'] + ' - ' + data[index]['qty_output'] + '</span>';

                    //     if(index <= parseInt(data.length) - 2){
                    //       result += '<br>';
                    //     }
                    //   }
                    // }
                    // else{
                    //   result = null;
                    // }

                    // Get Emboss
                    if(data.length > 0){
                      for(let index = data.length - 1; index >= 0; index--){
                        // Get Not Emboss
                        if(data[index]['has_emboss'] == 1){
                          // result += '<span class="badge badge-pill badge-secondary">' + data[index]['step_num'] + ' - ' + data[index]['qty_output'] + '</span>';

                          result = data[index]['qty_output'];
                          break;
                        }
                        // else{
                        //   result += '<span class="badge badge-pill badge-secondary">' + data[index]['step_num'] + ' - ' + data[index]['qty_output'] + '</span>';

                        //   if(index >= 0){
                        //     result += '<br>';
                        //   }
                        // }
                      }
                    }
                    else{
                      result = null;
                    }
                    
                    return result;
                }
            },
            { "data" : "total_no_of_emboss_ng" },
            // { "data" : "prod_runcard_station_many_details.0.qty_ng" },
          ],
          "columnDefs": [
            {
              "targets": [3, 4, 5, 6, 7, 8],
              "data": null,
              "defaultContent": "--"
            },
          ],
          order: [[2, "desc"]],
          "rowCallback": function(row,data,index ){
            currentPoNo = data['po_no'];
            $('#txt_po_number_lbl').val( data['po_no'] );
            $('#txt_device_name_lbl').val( data['wbs_kitting']['device_name'] );
            $('#txt_device_code_lbl').val( data['wbs_kitting']['device_code'] );
            $('#txt_po_qty_lbl').val( data['wbs_kitting']['po_qty'] );
            // $("#txt_search_po_number").val("");
          },
          "drawCallback": function(row,data,index ){
            // dt_setup_stations.draw();
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last = null;

            // Temporary -> switch comment
            GetMaterialKitting();

            if(api.rows().count() > 0){
              totalNoOfOk = 0;
              totalNoOfNG = 0;

              api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                  let data = api.row(i).data();

                  if(data['prod_runcard_station_many_details'].length > 0){

                    for(let index = data['prod_runcard_station_many_details'].length - 1; index >= 0; index--){
                        // Get Not Emboss
                        if(data['prod_runcard_station_many_details'][index]['has_emboss'] == 0){
                          if(data['prod_runcard_station_many_details'][index]['has_emboss'] == 0){
                            if(data['prod_runcard_station_many_details'][index]['qty_output'] != null){
                              totalNoOfOk += parseInt(data['prod_runcard_station_many_details'][index]['qty_output']);
                              break;
                            }
                          }
                        }
                      }
                  }

                  totalNoOfNG += data.total_no_of_ng;
              });
              
              $("#btnShowNGSummary").prop('disabled', false);
              $("#txt_total_no_of_ok").val(totalNoOfOk);
              $("#txt_total_no_of_ng").val(totalNoOfNG);

              // $("#btn_edit_material_details_verification").removeAttr('disabled');
            }
            else{
              // $("#btn_edit_material_details_verification").prop('disabled', 'disabled');
              $("#txt_total_no_of_ok").val("");
              $("#txt_total_no_of_ng").val("");
              $("#btnShowNGSummary").prop('disabled', true);
            }
          },
          paging: false,
      });//end of DataTable

      dt_materials      = $('#tbl_materials').DataTable({
        "processing" : true,
          "serverSide" : true,
          "ajax" : {
            url: "select_prod_runcard_materials",
            data: function (param){
                param.po_number = currentPoNo;
                param.runcard_status = $("#txt_prod_runcard_status").val();
                param.material_kit_list = arrSelectedMaterial;
                param.sakidsahi_list = arrSelectedSakidashi;
                param.prod_runcard_id_query = $("#txt_prod_runcard_id_query").val();
                // param.lot_no = materialKitIssuanceLotNo;
                param.transfer_slip = materialKitTransferSlip;
                // param.sakidashi_list = arrSelectedSakidashi;
                param.has_mat_sak_list = hasProdMatSakList;
            }
          },
          bAutoWidth: false,
          "columns":[
            { "data" : "raw_action", orderable:false, searchable:false, visible:false },
            { "data" : "raw_status", orderable:false, searchable:false },
            { "data" : "item_desc", orderable:true, searchable:true },

            { "data" : "kit_issuance.issuance_no", orderable:false, searchable:true },
            { "data" : "kit_issuance.assessment", orderable:true, searchable:true },
            { "data" : "item", orderable:true, searchable:true },
            { "data" : "lot_no", orderable:true, searchable:true },
            // { "data" : "lot_qty_to_complete" },
            // { "data" : "runcard_used_qty" },
          ],
          paging: false,
          info: false,
          // searching: false,
          pageLength: -1,
          order: [[2, "asc"]],
          "rowCallback": function(row,data,index ){
            $('#txt_po_number_lbl').val( data['po'] );
            // $('#txt_assessment_no').val( data['assessment'] );
            // console.log(data['kit_issuance']['assessment']);
            if($("#txt_prod_runcard_status").val() <= 2){
              // $("#txt_assessment_no").val(data['kit_issuance']['assessment']);
              assessment = data['kit_issuance']['assessment'];
              $("#txt_assessment_no").val(assessment);
            }
          },
          "drawCallback": function(row,data,index ){
            $(".chkSelMatKitIssue").each(function(index){
                if(arrSelectedMaterial.includes($(this).attr('material-kit-issue-id'))){
                  $(this).attr('checked', 'checked');
                }
            });
            // arrSelectedMaterial
            arrSelectedMaterial = [];
            $('#tbl_materials tbody .col_lot_id').each(function(){
              // if(!$(this).attr('disabled')){
              //   // console.log('disabled');
              //   arr_substations[ctr] = {
              //       'step' : $(this).closest('td').find('.col_station_step').val(),
              //       'station' : $(this).closest('td').find('.col_station_id').val(),
              //       'substation' : $(this).closest('td').find('.col_sub_station_id').val(),
              //     };
              // }
              // console.log($(this).val());
              arrSelectedMaterial.push($(this).val());
            });
            if(arrSelectedMaterial.length > 0 || arrSelectedSakidashi.length > 0){
              // $("#btnSaveSelectedMatSak").prop('disabled', false);
              $("#spanNoOfSelectedMatSak").text("(" + (parseInt(arrSelectedMaterial.length) + parseInt(arrSelectedSakidashi.length)) + ")");
            }
            else{
              // $("#btnSaveSelectedMatSak").prop('disabled', true);
              $("#spanNoOfSelectedMatSak").text("");
            }

            if(hasProdMatSakList){
              // $("#btnSaveSelectedMatSak").prop('disabled', true);
            }
            else{
              // $("#btnSaveSelectedMatSak").prop('disabled', false);
            }
            // console.log(arrSelectedMaterial);
          },
      });//end of DataTable

      dt_sakidashi      = $('#tbl_sakidashi').DataTable({
          "processing" : true,
          "serverSide" : true,
          "ajax" : {
            url: "view_sakidashi_prod",
            data: function (param){
                param.po_number = currentPoNo;
                param.runcard_status = $("#txt_prod_runcard_status").val();
                param.sakidashi_list = arrSelectedSakidashi;
                param.material_kit_list = arrSelectedMaterial;
                param.prod_runcard_id_query = $("#txt_prod_runcard_id_query").val();
                param.lot_no = sakidashiIssuanceLotNo;
                // param.control_no = sakidashiCtrlNo;
                param.has_mat_sak_list = hasProdMatSakList;
            }
          },
          bAutoWidth: false,
          paging: true,
          "columns":[
            { "data" : "action", orderable:false, searchable:false },
            { "data" : "status", orderable:false, searchable:false },
            { "data" : "issuance_no", orderable:true, searchable:true },
            { "data" : "tbl_wbs_sakidashi_issuance_item.item_desc", orderable:false, searchable:true },
            { "data" : "tbl_wbs_sakidashi_issuance_item.lot_no", orderable:false, searchable:true },
            { "data" : "device_code", orderable:false, searchable:true },
            { "data" : "tbl_wbs_sakidashi_issuance_item.required_qty", orderable:false, searchable:true },
            { "data" : "tbl_wbs_sakidashi_issuance_item.issued_qty", orderable:false, searchable:true },
            // { "data" : "lot_qty_to_complete" },
            // { "data" : "runcard_used_qty" },
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
            $(".chkSelSakidashiIssue").each(function(index){
                if(arrSelectedSakidashi.includes($(this).attr('sakidashi-issue-id'))){
                  $(this).attr('checked', 'checked');
                }
            });

            // arrSelectedSakidashi = [];
            // $('#tbl_sakidashi tbody .col_lot_id').each(function(){
            //   arrSelectedSakidashi.push($(this).val());
            // });

            // if(arrSelectedSakidashi.length > 0){
            //   // $("#btnSaveSelectedMatSak").prop('disabled', false);
            //   $("#spanNoOfSelectedMatSak").text("(" + (parseInt(arrSelectedMaterial.length) + parseInt(arrSelectedSakidashi.length)) + ")")
            // }
            // else{
            //   // $("#btnSaveSelectedMatSak").prop('disabled', true);
            //   // $("#spanNoOfSelectedMatSak").text("")
            // }
            // console.log(arrSelectedSakidashi);
          },
          // "columnDefs": [
          //   {
          //     "targets": [3, 4, 5, 6, 7],
          //     "data": null,
          //     "defaultContent": "--"
          //   },
          // ],
          paging: false,
          info: false,
          searching: true,
          pageLength: -1,
          order: [[2, "asc"]],
      });//end of DataTable

      dt_emboss      = $('#tbl_emboss').DataTable({
          "processing" : true,
          "serverSide" : true,
          "ajax" : {
            url: "view_emboss_prod",
            data: function (param){
                param.po_number = currentPoNo;
                param.runcard_status = $("#txt_prod_runcard_status").val();
                param.emboss_kit_list = arrSelectedEmboss;
                param.prod_runcard_id_query = $("#txt_prod_runcard_id_query").val();
                param.lot_no = embossIssuanceLotNo;
                // param.control_no = sakidashiCtrlNo;
                param.has_mat_sak_list = hasProdMatSakList;
                param.require_oqc_before_emboss = $("#txt_prod_runcard_require_oqc_before_emboss").val();
            }
          },
          bAutoWidth: false,
          paging: true,
          "columns":[
            { "data" : "action", orderable:false, searchable:false },
            { "data" : "status", orderable:false, searchable:false },
            { "data" : "issuance_no", orderable:true, searchable:true },
            { "data" : "tbl_wbs_sakidashi_issuance_item.item_desc", orderable:false, searchable:true },
            { "data" : "tbl_wbs_sakidashi_issuance_item.lot_no", orderable:false, searchable:true },
            { "data" : "device_code", orderable:false, searchable:true },
            { "data" : "tbl_wbs_sakidashi_issuance_item.required_qty", orderable:false, searchable:true },
            { "data" : "tbl_wbs_sakidashi_issuance_item.issued_qty", orderable:false, searchable:true },
            // { "data" : "lot_qty_to_complete" },
            // { "data" : "runcard_used_qty" },
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
            $(".chkSelEmbossIssue").each(function(index){
                if(arrSelectedEmboss.includes($(this).attr('emboss-issue-id'))){
                  $(this).attr('checked', 'checked');
                }
            });

            if(arrSelectedEmboss.length > 0){
              // $("#btnSaveSelectedMatSak").prop('disabled', false);
              $("#spanNoOfSelectedEmboss").text("(" + arrSelectedEmboss.length + ")")
            }
            else{
              // $("#btnSaveSelectedMatSak").prop('disabled', true);
              $("#spanNoOfSelectedEmboss").text("")
            }

            // arrSelectedSakidashi = [];
            // $('#tbl_sakidashi tbody .col_lot_id').each(function(){
            //   arrSelectedSakidashi.push($(this).val());
            // });

            // if(arrSelectedSakidashi.length > 0){
            //   // $("#btnSaveSelectedMatSak").prop('disabled', false);
            //   $("#spanNoOfSelectedMatSak").text("(" + (parseInt(arrSelectedMaterial.length) + parseInt(arrSelectedSakidashi.length)) + ")")
            // }
            // else{
            //   // $("#btnSaveSelectedMatSak").prop('disabled', true);
            //   // $("#spanNoOfSelectedMatSak").text("")
            // }
            // console.log(arrSelectedSakidashi);
          },
          // "columnDefs": [
          //   {
          //     "targets": [3, 4, 5, 6, 7],
          //     "data": null,
          //     "defaultContent": "--"
          //   },
          // ],
          paging: false,
          info: false,
          searching: true,
          pageLength: -1,
          order: [[2, "asc"]],
      });//end of DataTable

      let dtOutputStatus = 0;
      let groupStations = 2;
      dt_prod_runcard_stations = $('#tbl_prod_runcard_stations').DataTable({
        "processing" : true,
          "serverSide" : true,
          "ajax" : {
            url: "select_prod_runcard_stations",
            data: function (param){
                param.prod_runcard_id_query          = $("#txt_prod_runcard_id_query").val();
                param.prod_runcard_status          = $("#txt_prod_runcard_status").val();
                param.has_emboss          = $("#sel_runcard_type").val();
                param.prod_runcard_no          = $("#txt_runcard_no").val();
                param.require_oqc_before_emboss          = $("#txt_prod_runcard_require_oqc_before_emboss").val();
            }
          },
          
          "columns":[
            { "data" : "raw_action", orderable:false, searchable:false },
            { "data" : "step_num" , orderable:true, searchable:false},
            { "data" : "station.id" , orderable:false, searchable:false},
            { "data" : "station.name" , orderable:false, searchable:false},
            { "data" : "sub_station.name" , orderable:false, searchable:false},
            { "data" : "updated_at" , orderable:false, searchable:false},
            { "data" : "operators_info" , orderable:false, searchable:false},
            // { "data" : "machine_info.name" , orderable:false, searchable:false},
            { "data" : "machines_info" , orderable:false, searchable:false},
            { "data" : "qty_input" , orderable:false, searchable:false},
            { "data" : "qty_output" , orderable:false, searchable:false},
            { "data" : "qty_ng" , orderable:false, searchable:false},
            { "data" : "mod" , orderable:false, searchable:false, visible: false},
            {
                name: 'prod_runcard_station_mod_details',
                data: 'prod_runcard_station_mod_details',
                sortable: false,
                searchable: false,
                render: function (data) {
                  // console.log(data);
                    var result = '';
                    if(data.length > 0){
                      for(let index = 0; index < data.length; index++){
                        result += '<span class="badge badge-pill badge-danger">' + data[index]['mod_qty'] + ' - ' + data[index]['mod_details'].name + '</span>';

                        if(index <= parseInt(data.length) - 2){
                          result += '<br>';
                        }
                      }
                    }
                    else{
                      result = null;
                    }
                    return result;
                }
            },
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
                {
                  "targets": [12],
                  "data": null,
                  "defaultContent": "N/A"
                },
           ],
           "drawCallback": function ( settings ) {
              var api = this.api();
              var rows = api.rows( {page:'current'} ).nodes();
              var last = null;

              if(api.rows().count() > 0){
                // $("#btn_edit_material_details_verification").prop('disabled', false);
              }
              else{
                // $("#btn_edit_material_details_verification").prop('disabled', true);
              }

              OutputDataCounter(api);

              api.column(groupStations, {page:'current'} ).data().each( function ( group, i ) {
                  if ( last !== group ) {

                    let data = api.row(i).data();
                    let station_name = data.station.name;

                      $(rows).eq( i ).before(
                          '<tr class="group bg-info"><td colspan="11" style="text-align:center;"><b>' + station_name + '</b></td></tr>'
                      );

                      last = group;
                  }
              });

              // let lastProdStationStat = false;

              // console.log($("#tbl_prod_runcard_stations tr:last").find('.btn_edit_prod_runcard_station').attr('disabled'));
            }
      });//end of DataTable

      //----------
      dt_setup_stations      = $('#tbl_setup_stations').DataTable({
        "processing" : true,
          "serverSide" : true,
          "ajax" : {
            url: "select_prod_runcard_setup_stations",
            data: function (param){
                // param.po_number              = $("#txt_search_po_number").val();
                param.device_code                  = $("#txt_device_code_lbl").val();
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

      $("#sel_runcard_type").change(function(){
        dt_prod_runcard_stations.draw();
        // if($(this).val() == 0){
        //   $('#myTab a[href="#home"]').tab('show');
        //   $('#myTab a[href="#profile"]').tab('show');
        //   $('#myTab a[href="#materialEmbossTab"]').tab('hide'); 
        // }
        // else{
        //   $('#myTab a[href="#home"]').tab('hide');
        //   $('#myTab a[href="#profile"]').tab('hide');
        //   $('#myTab a[href="#materialEmbossTab"]').tab('show'); 
        // }
      });


      let groupMODRuncardNo = 0;
      dt_ng_summary = $('#tblNGSummary').DataTable({
        "processing" : true,
          "serverSide" : true,
          "ajax" : {
            url: "view_ng_summary",
            data: function (param){
                param.po_no          = currentPoNo;
            }
          },
          
          "columns":[
            { "data" : "production_runcard_id", orderable:false, searchable:false },
            { "data" : "production_runcard_details.runcard_no", orderable:false, searchable:true },
            { "data" : "mod_details.name" , orderable:true, searchable:false},
            { "data" : "mod_qty" , orderable:false, searchable:false},
          ],
          order: [[groupMODRuncardNo, "desc"]],
          paging: true,
          info: true,
          searching: true,
          // pageLength: -1,
          "columnDefs": [ 
                { "visible": false, "targets": groupMODRuncardNo },
                { "visible": false, "targets": 1 },
                // { "visible": false, "targets": groupMODRuncardNo + 1 },
                {
                  "targets": [0, 1, 2, 3],
                  "data": null,
                  "defaultContent": "--"
                },
           ],
           "drawCallback": function ( settings ) {
              var api = this.api();
              var rows = api.rows( {page:'current'} ).nodes();
              var last = null;

              api.column(groupMODRuncardNo, {page:'current'} ).data().each( function ( group, i ) {
                  if ( last !== group ) {

                    let data = api.row(i).data();
                    let production_runcard_id = '';
                    let production_runcard_no = '';

                    if(data.production_runcard_id != null){
                      production_runcard_id = data.production_runcard_id;
                    }
                    if(data.production_runcard_details != null){
                       production_runcard_no = data.production_runcard_details.runcard_no;
                    }
                    // let station_name = data.station.name;

                      $(rows).eq( i ).before(
                          '<tr class="group bg-info"><td colspan="2" style="text-align:center;" class="trNGSummaryRuncardNo">Runcard # : <b>' + production_runcard_no + '</b></td></tr>'
                      );

                      last = group;
                  }
              });
            }
      });//end of DataTable

      // $(document).on('keyup','#txt_search_po_number',function(e){
      //   if( e.keyCode == 13 ){
      //     currentPoNo = $(this).val();
      //     dt_prod_runcard.draw();
      //     // dt_sakidashi.draw();
      //     // dt_materials.draw();
      //     $('#tbl_materials tbody tr').removeClass('table-active');
      //     $('#txt_po_number_lbl').val('');
      //     $('#txt_device_name_lbl').val('');
      //     $('#txt_device_code_lbl').val('');
      //     $('#txt_po_qty_lbl').val('');
      //     $('#txt_device_name').val('');
      //     $(this).val('');
      //     $(this).focus();
      //   }
      // });

      // $("#tblNGSummary tbody").on('click', '.trNGSummaryRuncardNo', function(){
      //   dt_ng_summary.order([[1, 'desc']]).draw();
      // });

      $(document).on('keyup','#txt_search_material_lot_no',function(e){
        if( e.keyCode == 13 ){
          materialKitIssuanceLotNo = $(this).val();
          $("#txt_material_lot_no_lbl").val(materialKitIssuanceLotNo);
          dt_materials.draw();
          $(this).val('');
          $(this).focus();
        }
      });

      $(document).on('keyup','#txt_search_material_transfer_slip',function(e){
        if( e.keyCode == 13 ){
          materialKitTransferSlip = $(this).val().trim();
          $("#txt_material_transfer_slip_lbl").val(materialKitTransferSlip);
          dt_materials.draw();
          $(this).val('');
          $(this).focus();
        }
      });

      $(document).on('keyup','#txt_search_sakidashi_lot_no',function(e){
        if( e.keyCode == 13 ){
          sakidashiIssuanceLotNo = $(this).val().trim();
          $("#txt_sakidashi_lot_no_lbl").val(sakidashiIssuanceLotNo);
          dt_sakidashi.draw();
          $(this).val('');
          $(this).focus();
        }
      });

      $(document).on('keyup','#txt_search_emboss_lot_no',function(e){
        if( e.keyCode == 13 ){
          embossIssuanceLotNo = $(this).val().trim();
          $("#txt_emboss_lot_no_lbl").val(embossIssuanceLotNo);
          dt_emboss.draw();
          $(this).val('');
          $(this).focus();
        }
      });

      $(document).on('keyup','#txt_search_sakidashi_ctrl_no',function(e){
        if( e.keyCode == 13 ){
          sakidashiCtrlNo = $(this).val();
          $("#txt_sakidashi_ctrl_no_lbl").val(sakidashiCtrlNo);
          dt_sakidashi.draw();
          $(this).val('');
          $(this).focus();
        }
      });

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
                case 'SaveProdMaterialList':
                  if(arrSelectedMaterial.length > 0 || arrSelectedSakidashi > 0){
                    SaveProductMaterialList(arrSelectedMaterial, arrSelectedSakidashi, "{{ csrf_token() }}", $("#txt_employee_number_scanner").val(), $("#txt_prod_runcard_id_query").val());
                  }
                  else{
                    toastr.warning('No Materials to be saved.');
                  }
                break;
                case 'SaveProdEmbossMaterialList':
                  if(arrSelectedEmboss.length > 0){
                    SaveProductEmbossMaterialList(arrSelectedEmboss, "{{ csrf_token() }}", $("#txt_employee_number_scanner").val(), $("#txt_prod_runcard_id_query").val());
                  }
                  else{
                    toastr.warning('No Materials to be saved.');
                  }
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

                  // let scannedMachineId = $('#txt_edit_prod_runcard_station_machine').find("option[data-code='" + val + "']").val();
                  // let assignedMachine = $("#txt_edit_prod_runcard_station_assigned_machine").val();

                  // if(assignedMachine == scannedMachineId){
                  //   $('#txt_edit_prod_runcard_station_machine option[data-code="'+val+'"]').prop('selected', true).trigger('change');
                  // }
                  // else{
                  //   $('#txt_edit_prod_runcard_station_machine').val("").trigger('change');
                  //   toastr.warning('Invalid Machine!');
                  // }

                  // Replaced
                  // if ($('#txt_edit_prod_runcard_station_machine').find("option[data-code='" + val + "']").length) {
                  //   let scannedMachineId = $('#txt_edit_prod_runcard_station_machine').find("option[data-code='" + val + "']").val();
                  //   let assignedMachine = $("#txt_edit_prod_runcard_station_assigned_machine").val();

                  //   if(assignedMachine == scannedMachineId){
                  //     $('#txt_edit_prod_runcard_station_machine option[data-code="'+val+'"]').prop('selected', true).trigger('change');
                  //   }
                  //   else{
                  //     $('#txt_edit_prod_runcard_station_machine').val("").trigger('change');
                  //     toastr.warning('Wrong Machine!');
                  //   }
                  // }
                  // else{
                  //   $('#txt_edit_prod_runcard_station_machine').val("").trigger('change');
                  //   toastr.warning('Invalid Machine!');
                  // }
                  // $('#txt_qrcode_scanner').val("");

                  // with this
                  var val = $('#txt_qrcode_scanner').val();

                  if ($('#txt_edit_prod_runcard_station_machine').find("option[data-code='" + val + "']").length) {
                    let scannedUserId = $('#txt_edit_prod_runcard_station_machine').find("option[data-code='" + val + "']").val();

                    let assignedMachine = $("#txt_edit_prod_runcard_station_assigned_machine").val();

                    if(assignedMachine.includes(scannedUserId)){
                      // toastr.warning('Certified!');
                      $('#txt_edit_prod_runcard_station_machine option[data-code="'+val+'"]').prop('selected', true).trigger('change');
                    }
                    else{
                      toastr.warning('Not Assigned!');
                    }

                  }
                  else{
                    toastr.warning('Invalid Machine!');
                  }
                  $('#txt_qrcode_scanner').val("");
                break;

                case 'fn_scan_runcard_operator_code':
                  var val = $('#txt_qrcode_scanner').val();

                  if ($('#txt_edit_prod_runcard_operator').find("option[data-code='" + val + "']")) {
                    let scannedUserId = $('#txt_edit_prod_runcard_operator').find("option[data-code='" + val + "']").val();

                    let certifiedOperators = $("#txt_edit_prod_runcard_cert_operator").val();

                    if(certifiedOperators.includes(scannedUserId)){
                      // toastr.warning('Certified!');
                      $('#txt_edit_prod_runcard_operator option[data-code="'+val+'"]').prop('selected', true).trigger('change');
                    }
                    else{
                      toastr.warning('Not Certified!');
                    }

                  }
                  else{
                    toastr.warning('Invalid User!');
                  }
                  $('#txt_qrcode_scanner').val("");
                break;

                case 'fn_scan_material_kitting':
                  var val = $('#txt_qrcode_scanner').val();
                break;

                case 'fn_scan_sakidashi':
                  var val = $('#txt_qrcode_scanner').val();
                break;

                case 'fn_scan_emboss':
                  var val = $('#txt_qrcode_scanner').val();
                break;

                default:
                break;
              }
            }            
          }//key
        }
        // SCAN PO, LOT, TRANSFER SLIP CODE
        if( ($("#modalScanPOTransLotCode").data('bs.modal') || {})._isShown ){
          $('#txtSearchPoTransLotNo').focus();
          if( e.keyCode == 13 && $('#txtSearchPoTransLotNo').val() !='' && ($('#txtSearchPoTransLotNo').val().length >= 4) ){
            $('#modalScanPOTransLotCode').modal('hide');
            var formid = $("#modalScanPOTransLotCode").attr('data-formid');

            if ( ( formid ).indexOf('#') > -1){
              $( formid ).submit();
            }
            else{
              switch( formid ){
                case 'search_po_number':
                  currentPoNo = $("#txtSearchPoTransLotNo").val();
                  dt_prod_runcard.draw();
                  $('#tbl_materials tbody tr').removeClass('table-active');
                  $('#txt_po_number_lbl').val('');
                  $('#txt_device_name_lbl').val('');
                  $('#txt_device_code_lbl').val('');
                  $('#txt_po_qty_lbl').val('');
                  $('#txt_device_name').val('');
                  $(this).val('');
                  $(this).focus();
                break;
                default:
                break;
              }
            }
          }
        }
      });

      $(document).on('click','#tbl_prod_runcard tr',function(e){
        $(this).closest('tbody').find('tr').removeClass('table-active');
        $(this).closest('tr').addClass('table-active');
      });

      $("#tbl_sakidashi").on('click', '.chkSelSakidashiIssue', function(e){
        let sakidashiId = $(this).attr('sakidashi-issue-id');
        if($("#txt_prod_runcard_id_query").val() == "" || $("#txt_prod_runcard_id_query").val() == null){
          toastr.warning('Fill-out Material Details First');
          $(this).prop('checked', false);
        }
        else{
          if($(this).prop('checked')){
              // Checked
              if(!arrSelectedSakidashi.includes(sakidashiId)){
                arrSelectedSakidashi.push(sakidashiId);
              }
          }
          else{  
              // Unchecked
              let index = arrSelectedSakidashi.indexOf(sakidashiId);
              arrSelectedSakidashi.splice(index, 1);
          }

          let noOfSelected = parseInt(arrSelectedSakidashi.length) + parseInt(arrSelectedMaterial.length);
          if(noOfSelected > 0){
            $("#spanNoOfSelectedMatSak").text('(' + noOfSelected + ')');
            if($("#txt_prod_runcard_id_query").val() == "" || $("#txt_prod_runcard_id_query").val() == null){
            
            }
            else{
              $("#btnSaveSelectedMatSak").prop('disabled', false);
            }
          }
          else{
            $("#spanNoOfSelectedMatSak").text(''); 
            $("#btnSaveSelectedMatSak").prop('disabled', true);
          }
        }
        $("#txt_search_sakidashi_lot_no").focus();
      });

      $("#tbl_emboss").on('click', '.chkSelEmbossIssue', function(e){
        let embossId = $(this).attr('emboss-issue-id');
        if($("#txt_prod_runcard_id_query").val() == "" || $("#txt_prod_runcard_id_query").val() == null){
          toastr.warning('Fill-out Material Details First');
          $(this).prop('checked', false);
        }
        else{
          if($(this).prop('checked')){
              // Checked
              if(!arrSelectedEmboss.includes(embossId)){
                arrSelectedEmboss.push(embossId);
              }
          }
          else{  
              // Unchecked
              let index = arrSelectedEmboss.indexOf(embossId);
              arrSelectedEmboss.splice(index, 1);
          }

          let noOfSelected = parseInt(arrSelectedEmboss.length);
          if(noOfSelected > 0){
            // For button of adding material 
            $("#spanNoOfSelectedEmboss").text('(' + noOfSelected + ')');
            if($("#txt_prod_runcard_id_query").val() == "" || $("#txt_prod_runcard_id_query").val() == null){
            
            }
            else{
              $("#btnSaveSelectedEmboss").prop('disabled', false);
            }
          }
          else{
            // For button of adding material 
            $("#spanNoOfSelectedEmboss").text(''); 
            $("#btnSaveSelectedEmboss").prop('disabled', true);
          }
        }
        $("#txt_search_emboss_lot_no").focus();
      });

      $("#tbl_materials").on('click', '.chkSelMatKitIssue', function(e){
        let matKitIssue = $(this).attr('material-kit-issue-id');
        if($("#txt_prod_runcard_id_query").val() == "" || $("#txt_prod_runcard_id_query").val() == null){
          toastr.warning('Fill-out Material Details First');
          $(this).prop('checked', false);
        }
        else{
          if($(this).prop('checked')){
              // Checked
              if(!arrSelectedMaterial.includes(matKitIssue)){
                arrSelectedMaterial.push(matKitIssue);
              }
          }
          else{
              // Unchecked
              let index = arrSelectedMaterial.indexOf(matKitIssue);
              arrSelectedMaterial.splice(index, 1);
          }

          let noOfSelected = parseInt(arrSelectedSakidashi.length) + parseInt(arrSelectedMaterial.length);
          if(noOfSelected > 0){
            $("#spanNoOfSelectedMatSak").text('(' + noOfSelected + ')');
            if($("#txt_prod_runcard_id_query").val() == "" || $("#txt_prod_runcard_id_query").val() == null){
            
            }
            else{
              // $("#btnSaveSelectedMatSak").prop('disabled', false);
            }
          }
          else{
            $("#spanNoOfSelectedMatSak").text('');
            // $("#btnSaveSelectedMatSak").prop('disabled', true);
          }
        }
      });

      $("#btnShowAddProdRuncard").click(function(){
        if($("#txt_po_number_lbl").val() == "" || $("#txt_po_number_lbl").val() == null){
          toastr.warning('PO not found!');
        }
        else{
          readonly_material_details_primary(true);
          readonly_material_details_secondary(true);
          reset_material_details_primary();
          reset_material_details_secondary();
          HandleButtons(true);
          $("#btn_edit_material_details_primary").prop('disabled', false);
          // $("#btn_setup_stations").prop('disabled', true);
          $("#txt_lot_qty").val(boxing);
          $("#txt_assessment_no").val(assessment);
          $("#txt_a_drawing_no").val(aDrawing);
          $("#txt_a_drawing_rev").val(aDrawingRev);
          $("#txt_g_drawing_no").val(gDrawing);
          $("#txt_g_drawing_rev").val(gDrawingRev);
          $("#modalRuncardDetails").modal('show');
          arrSelectedMaterial = [];
          arrSelectedSakidashi = [];
          arrSelectedEmboss = [];
          materialKitIssuanceLotNo = "";
          sakidashiIssuanceLotNo = "";
          // materialKitTransferSlip = $("#txt_material_transfer_slip_lbl").val();
          sakidashiCtrlNo = "";
          $("#txt_material_lot_no_lbl").val("");
          $("#txt_sakidashi_lot_no_lbl").val("");
          // dt_materials.draw();
          GetMaterialKitting();
          // GetSakidashiIssuance();
          dt_materials.draw();
          dt_sakidashi.draw();
          dt_prod_runcard_stations.draw();
          $("#txt_prod_runcard_id_query").val('');

          $("#txt_po_number").val($("#txt_po_number_lbl").val());
          $("#txt_po_qty").val($("#txt_po_qty_lbl").val());
          $("#txt_use_for_device").val($("#txt_device_name_lbl").val());
          $("#txt_device_code").val($("#txt_device_code_lbl").val());
          // $("#txt_use_for_device").val($("#txt_device_name_lbl").val());

          let noOfSelected = parseInt(arrSelectedSakidashi.length) + parseInt(arrSelectedMaterial.length);
          if(noOfSelected > 0){
            $("#spanNoOfSelectedMatSak").text('(' + noOfSelected + ')');
            // $("#btnSaveSelectedMatSak").prop('disabled', false);
          }
          else{
            $("#spanNoOfSelectedMatSak").text(''); 
            // $("#btnSaveSelectedMatSak").prop('disabled', true);
          }

          if(parseInt($("#txt_total_no_of_ok").val()) >= parseInt($("#txt_po_qty_lbl").val())){
            toastr.warning('PO Quantity already reached!');
          }
          // $("#btn_edit_material_details_primary").prop('disabled', false);
        }
      });

      $("#btnAddMODTable").click(function(){
        // $('#txt_edit_prod_runcard_operator').val(25).trigger('change');
        let appendHTML = '<tr>';
        appendHTML += '<td>';
          appendHTML += '<select class="form-control select2 select2bs4 selectMOD" name="mod[]">';
            appendHTML += '<option value="">N/A</option>';
          appendHTML += '</select>';
        appendHTML += '</td>';
        appendHTML += '<td>';
          appendHTML += '<input type="number" class="form-control txtEditProdRunStaMODQty" name="mod_qty[]" value="1" min="1">';
        appendHTML += '</td>';
        appendHTML += '<td>';
          appendHTML += '<center><button class="btn btn-xs btn-danger btnRemoveMOD" title="Remove" type="button"><i class="fa fa-minus"></i></button></center>';
        appendHTML += '</td>';
        appendHTML += '</tr>'

        $("#tblEditProdRunStaMOD tbody").append(appendHTML);
        $('.select2bs4').select2({
          theme: 'bootstrap4'
        });

        GetCboMOD($("#tblEditProdRunStaMOD tr:last").find('.selectMOD'));

        let totalNoOfMOD = 0;

        $('#tblEditProdRunStaMOD .txtEditProdRunStaMODQty').each(function(i, obj) {
          if($(this).val() == null || $(this).val() == ""){
          }
          else{
            totalNoOfMOD += parseFloat($(this).val());
          }
        });
        if($("#txt_edit_prod_runcard_station_ng").val() != totalNoOfMOD){
          toastr.warning('MOD NG Qty not Tally!');
          $("#pRCStatTotNoOfNG").css({color: 'red'});
          $("#btn_save_prod_runcard_station_stations").prop('disabled', true);
        }
        else{
          $("#pRCStatTotNoOfNG").css({color: 'green'});
          $("#btn_save_prod_runcard_station_stations").prop('disabled', false);
        }
        $("#pRCStatTotNoOfNG").text(totalNoOfMOD);
      });

      $("#tblEditProdRunStaMOD").on('click', '.btnRemoveMOD', function(){
        $(this).closest ('tr').remove();
        let totalNoOfMOD = 0;

        $('#tblEditProdRunStaMOD .txtEditProdRunStaMODQty').each(function(i, obj) {
          if($(this).val() == null || $(this).val() == ""){
          }
          else{
            totalNoOfMOD += parseFloat($(this).val());
          }
        });
        if($("#txt_edit_prod_runcard_station_ng").val() != totalNoOfMOD){
          toastr.warning('MOD NG Qty not Tally!');
          $("#pRCStatTotNoOfNG").css({color: 'red'});
          $("#btn_save_prod_runcard_station_stations").prop('disabled', true);
        }
        else{
          $("#pRCStatTotNoOfNG").css({color: 'green'});
          $("#btn_save_prod_runcard_station_stations").prop('disabled', false);
        }
        $("#pRCStatTotNoOfNG").text(totalNoOfMOD);
      });

      // Insert Production Runcard
      $('#frm_edit_material_details').submit(function(e){
        e.preventDefault();

        $.ajax({
          'data'      : $(this).serialize() +'&txt_employee_number_scanner=' + $("#txt_employee_number_scanner").val() + '&device_name=' + $("#txt_device_name_lbl").val(),
          'type'      : 'post',
          'dataType'  : 'json',
          'url'       : 'insert_prod_runcard',
          success     : function(data){
              $('#mdl_alert #mdl_alert_title').html(data['title']);
              $('#mdl_alert #mdl_alert_body').html(data['body']);
              $('#mdl_alert').modal('show');

              $("#txt_prod_runcard_id_query").val(data['prod_runcard_id']);

              if(data['result']){
                GetProdRuncardById($("#txt_prod_runcard_id_query").val());
                dt_prod_runcard.draw();
                readonly_material_details_primary(true);
              }
              // if($("#txt_prod_runcard_id_query").val()){
              // }
              //---
              // var data_arr = [];
              // data_arr['material_id']     = $('#txt_wbs_kit_issuance_id_query').val();
              // // data_arr['sakidashi_id']     = $('#txt_wbs_sakidashi_issuance_id_query').val();
              // data_arr['material_code']   = $('#txt_part_number').val();
              // fn_select_material_details(data_arr);
              //---
          },
          completed     : function(data){

          },
          error     : function(data){

          },
        });
      });

      $("#tbl_prod_runcard").on('click','.btnOpenRuncardDetails',function(e){
        let prodRuncardId = $(this).attr('runcard-id');
        $("#txt_prod_runcard_id_query").val(prodRuncardId);
        materialKitIssuanceLotNo = "";
        $("#txt_material_lot_no_lbl").val("");
        sakidashiIssuanceLotNo = "";
        $("#txt_sakidashi_lot_no_lbl").val("");

        arrSelectedMaterial = [];
        arrSelectedSakidashi = [];
        arrSelectedEmboss = [];
        materialKitTransferSlip = "";
        $("#txt_material_transfer_slip_lbl").val("");
        sakidashiCtrlNo = "";
        $("#txt_sakidashi_ctrl_no_lbl").val("");
        GetProdRuncardById(prodRuncardId);
      });      

      $("#txt_edit_prod_runcard_operator").change(function(){
        let certifiedOperators = $("#txt_edit_prod_runcard_cert_operator").val();
        let selectedOperators = $("#txt_edit_prod_runcard_operator").val();
        // console.log(certifiedOperators);

        if(selectedOperators.length > 0){
          for(let index = 0; index < selectedOperators.length; index++){
            if(!certifiedOperators.includes(selectedOperators[index])){
              // toastr.warning('Not Certified!');
              $('#txt_edit_prod_runcard_operator option[value="'+selectedOperators[index]+'"]').prop('selected', false).trigger('change');
            }
          }
        }
      });

      $("#txt_edit_prod_runcard_station_machine").change(function(){
        let assignedMachine = $("#txt_edit_prod_runcard_station_assigned_machine").val();
        let selectedMachines = $("#txt_edit_prod_runcard_station_machine").val();
        // console.log(assignedMachine);
        if(selectedMachines.length > 0){
          for(let index = 0; index < selectedMachines.length; index++){
            if(!assignedMachine.includes(selectedMachines[index])){
              // toastr.warning('Not Assigned!');
              $('#txt_edit_prod_runcard_station_machine option[value="'+selectedMachines[index]+'"]').prop('selected', false).trigger('change');
            }
          }
        }
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

      $(document).on('click','#btn_setup_stations',function(e){
        let noOfSelected = parseInt(arrSelectedMaterial.length) + parseInt(arrSelectedSakidashi.length);
        if(noOfSelected > 0){
          dt_setup_stations.draw();
          $('#mdl_setup_stations').modal('show');
        }
        else{
          toastr.warning('Select Material List First');
          // toastr.warning('Fill-out Material Details First');
        }
      });

      $(".btnSearchPoNo").click(function(){
        $("#txtSearchPoTransLotNo").val('');
        $('#modalScanPOTransLotCode').attr('data-formid', 'search_po_number').modal('show');
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

      $("#chkCheckAllSetupStations").click(function(){
        if($(this).prop('checked')){
          $("#tbl_setup_stations .ckb_station").prop('checked', true);
        }
        else{
          $("#tbl_setup_stations .ckb_station").prop('checked', false);
        }
      });

      $("#tbl_setup_stations").on('click', '.ckb_station', function(){
        if(!$(this).prop('checked')){
          $("#chkCheckAllSetupStations").prop('checked', false);
        }
      });

      $("#txt_edit_prod_runcard_station_output").keyup(function(){
        $("#txt_edit_prod_runcard_station_ng").val(parseInt($("#txt_edit_prod_runcard_station_input").val()) - parseInt($(this).val()));
      });

      $("#txt_edit_prod_runcard_station_input").keyup(function(){
        $("#txt_edit_prod_runcard_station_ng").val(parseInt($("#txt_edit_prod_runcard_station_input").val()) - parseInt($("#txt_edit_prod_runcard_station_output").val()));
      });

      $("#txt_edit_prod_runcard_station_ng").keyup(function(){
        $("#txt_edit_prod_runcard_station_output").val(parseInt($("#txt_edit_prod_runcard_station_input").val()) - parseInt($("#txt_edit_prod_runcard_station_ng").val()));

        if($(this).val() > 0){
          $("#btnAddMODTable").prop('disabled', false);
        }
        else{
          $("#btnAddMODTable").prop('disabled', true);
        }
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
              data_arr['material_code']   = $('#txt_part_number').val();
              // fn_select_material_details(data_arr);
              //---

              GetProdRuncardById($("#txt_prod_runcard_id_query").val());

              dt_prod_runcard.draw();
              // dt_materials.ajax.reload();
              // dt_sakidashi.ajax.reload();

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

      readonly_material_details_primary(true);
      readonly_material_details_secondary(true);

      // function GetProdRuncardById(prodRuncardId){
      //   $.ajax({
      //     url: "get_prod_runcard_by_id",
      //     method: 'get',
      //     dataType: 'json',
      //     data: {
      //       prod_runcard_id: prodRuncardId
      //     },
      //     beforeSend: function(){
      //       arrSelectedMaterial = [];
      //       arrSelectedSakidashi = [];
      //       readonly_material_details_primary(true);
      //       readonly_material_details_secondary(true);
      //       // reset_material_details_primary();
      //       // reset_material_details_secondary();
      //     },
      //     success: function(data){
      //       if(data['prod_runcard'] != null){
      //         $("#modalRuncardDetails").modal('show');
      //         $("#txt_po_number").val($("#txt_po_number_lbl").val());
      //         $("#txt_po_qty").val($("#txt_po_qty_lbl").val());
      //         $("#txt_use_for_device").val($("#txt_device_name_lbl").val());
      //         $("#txt_device_code").val($("#txt_device_code_lbl").val());
      //         $("#txt_prod_runcard_id_query").val(data['prod_runcard']['id']);
      //         $("#txt_prod_runcard_status").val(data['prod_runcard']['status']);
      //         $("#txt_prod_runcard_verification_id_query").val(data['prod_runcard']['id']);
      //         $("#txt_runcard_no").val(data['prod_runcard']['runcard_no']);
      //         $("#txt_lot_qty").val(data['prod_runcard']['lot_qty']);
      //         $("#txt_assessment_no").val(data['prod_runcard']['assessment_no']);
      //         $("#txt_a_drawing_no").val(data['prod_runcard']['a_drawing_no']);
      //         $("#txt_a_drawing_rev").val(data['prod_runcard']['a_drawing_rev']);
      //         $("#txt_g_drawing_no").val(data['prod_runcard']['g_drawing_no']);
      //         $("#txt_g_drawing_rev").val(data['prod_runcard']['g_drawing_rev']);
      //         $("#txt_other_docs_no").val(data['prod_runcard']['other_docs_no']);
      //         $("#txt_other_docs_rev").val(data['prod_runcard']['other_docs_rev']);

      //         if(data['prod_runcard']['discrepant_qty'] != null){
      //           if(data['prod_runcard']['discrepant_qty'] < 0){
      //             $('#txt_discrepant_qty_sign').val("-");
      //             $('#txt_discrepant_qty').val(data['prod_runcard']['discrepant_qty'] * -1);
      //           }
      //           else if(data['prod_runcard']['discrepant_qty'] == 0){
      //             $('#txt_discrepant_qty_sign').val("0");
      //             $('#txt_discrepant_qty').val(data['prod_runcard']['discrepant_qty']);
      //           }
      //           else{
      //             $('#txt_discrepant_qty_sign').val("+");
      //             $('#txt_discrepant_qty').val(data['prod_runcard']['discrepant_qty']);
      //           }
      //         }
      //         else{
      //           $('#txt_discrepant_qty_sign').val("0");
      //           $('#txt_discrepant_qty').val("");
      //         }

      //         $('#txt_analysis').val(data['prod_runcard']['analysis']);
      //         $('#txt_recount_ok').val(data['prod_runcard']['recount_ok']);
      //         $('#txt_recount_ng').val(data['prod_runcard']['recount_ng']);

      //         if(data['prod_runcard']['supervisor_prod_info'] != null){
      //           $('#txt_prod_approval').val(data['prod_runcard']['supervisor_prod_info']['name']);
      //         }
      //         else{
      //           $('#txt_prod_approval').val("");
      //         }

      //         if(data['prod_runcard']['supervisor_qc_info'] != null){
      //           $('#txt_qc_approval').val(data['prod_runcard']['supervisor_qc_info']['name']);
      //         }
      //         else{
      //           $('#txt_qc_approval').val("");
      //         }

      //         if(data['prod_runcard']['prod_runcard_material_list'].length > 0){
      //           // $("#btn_edit_material_details_primary").prop('disabled', true);
      //           // $("#btnSaveSelectedMatSak").prop('disabled', true);

      //           for(let index = 0; index < data['prod_runcard']['prod_runcard_material_list'].length; index++){
      //             if(data['prod_runcard']['prod_runcard_material_list'][index]['tbl_wbs'] == 1){
      //               arrSelectedMaterial.push(data['prod_runcard']['prod_runcard_material_list'][index]['issuance_id']);
      //             }
      //             else{
      //               arrSelectedSakidashi.push(data['prod_runcard']['prod_runcard_material_list'][index]['issuance_id']);
      //             }
      //           }
      //         }
      //         else{
      //           // $("#btn_edit_material_details_primary").prop('disabled', false);
      //           // $("#btnSaveSelectedMatSak").prop('disabled', false);
      //         }

      //         if(data['prod_runcard']['prod_runcard_material_list'].length > 0){
      //           hasProdMatSakList = true;
      //           // $("#btn_setup_stations").prop('disabled', false);
      //             if(data['prod_runcard']['discrepant_qty'] != null) {
      //             // $("#btn_edit_material_details_verification").prop('disabled', true);
      //             // $("#btn_setup_stations").prop('disabled', true);
      //             // $("#btn_approve_prod").prop('disabled', false);
      //             // $("#btn_approve_qc").prop('disabled', false);

      //             if(data['prod_runcard']['supervisor_prod_info'] != null){
      //               // $("#btn_edit_material_details_verification").prop('disabled', true);
      //               // $("#btn_approve_prod").prop('disabled', true);
      //               // $("#btn_approve_prod").prop('disabled', true);
      //               if(data['prod_runcard']['supervisor_qc_info'] != null){
      //                 // $("#btn_approve_qc").prop('disabled', true);
      //               }
      //               else{
      //                 // $("#btn_approve_qc").prop('disabled', false); 
      //               }
      //             }
      //             else{
      //               // $("#btn_edit_material_details_verification").prop('disabled', false);
      //               // $("#btn_approve_prod").prop('disabled', false);
      //               // $("#btn_approve_qc").prop('disabled', true);
      //             }

      //             if(data['prod_runcard']['discrepant_qty'] == 0){
      //               // $("#btn_approve_prod").prop('disabled', true);
      //               // $("#btn_approve_qc").prop('disabled', true);
      //             }
      //           }
      //           else{
      //             // $("#btn_setup_stations").prop('disabled', false);
      //             // $("#btn_approve_prod").prop('disabled', true);
      //             // $("#btn_approve_qc").prop('disabled', true);
      //             // $("#btn_edit_material_details_verification").prop('disabled', false);
      //           }
      //         }
      //         else{
      //           // $("#btn_setup_stations").prop('disabled', true);
      //           hasProdMatSakList = true;
      //         }

      //         // if(data['prod_runcard']['discrepant_qty'] != null) {
      //           // $("#btn_edit_material_details_verification").prop('disabled', true);
      //           // $("#btn_setup_stations").prop('disabled', true);
      //         //   $("#btn_approve_prod").prop('disabled', false);
      //         //   $("#btn_approve_qc").prop('disabled', false);

      //         //   if(data['prod_runcard']['supervisor_prod_info'] != null){
      //             // $("#btn_edit_material_details_verification").prop('disabled', true);
      //         //     $("#btn_approve_prod").prop('disabled', true);
      //         //     $("#btn_approve_prod").prop('disabled', true);
      //         //     if(data['prod_runcard']['supervisor_qc_info'] != null){
      //         //       $("#btn_approve_qc").prop('disabled', true);
      //         //     }
      //         //     else{
      //         //       $("#btn_approve_qc").prop('disabled', false); 
      //         //     }
      //         //   }
      //         //   else{
      //             // $("#btn_edit_material_details_verification").prop('disabled', false);
      //         //     $("#btn_approve_prod").prop('disabled', false);
      //         //     $("#btn_approve_qc").prop('disabled', true);
      //         //   }

      //         //   if(data['prod_runcard']['discrepant_qty'] == 0){
      //         //     $("#btn_approve_prod").prop('disabled', true);
      //         //     $("#btn_approve_qc").prop('disabled', true);
      //         //   }
      //         // }
      //         // else{
      //           // $("#btn_setup_stations").prop('disabled', false);
      //         //   $("#btn_approve_prod").prop('disabled', true);
      //         //   $("#btn_approve_qc").prop('disabled', true);
      //           // $("#btn_edit_material_details_verification").prop('disabled', false);
      //         // }

      //         HandleButtons(true);
      //         let prodRuncardStat = data['prod_runcard']['status'];
      //         if(prodRuncardStat == 1){
      //           $("#btn_edit_material_details_primary").prop('disabled', true);  
      //           $("#btnSaveSelectedMatSak").prop('disabled', false);
      //           $("#btn_setup_stations").prop('disabled', true);
      //           $("#btn_edit_material_details_verification").prop('disabled', true);
      //           $("#btn_approve_prod").prop('disabled', true);
      //           $("#btn_approve_qc").prop('disabled', true);
      //         }
      //         else if(prodRuncardStat == 2){
      //           $("#btn_edit_material_details_primary").prop('disabled', true);  
      //           $("#btnSaveSelectedMatSak").prop('disabled', true);
      //           $("#btn_setup_stations").prop('disabled', false);
      //           $("#btn_edit_material_details_verification").prop('disabled', true);
      //           $("#btn_approve_prod").prop('disabled', true);
      //           $("#btn_approve_qc").prop('disabled', true);
      //         }
      //         else if(prodRuncardStat == 3){
      //           $("#btn_edit_material_details_primary").prop('disabled', true);  
      //           $("#btnSaveSelectedMatSak").prop('disabled', true);
      //           $("#btn_setup_stations").prop('disabled', true);
      //           $("#btn_edit_material_details_verification").prop('disabled', false);
      //           $("#btn_approve_prod").prop('disabled', true);
      //           $("#btn_approve_qc").prop('disabled', true);
      //         }
      //         else if(prodRuncardStat == 4){
      //           $("#btn_edit_material_details_primary").prop('disabled', true);  
      //           $("#btnSaveSelectedMatSak").prop('disabled', true);
      //           $("#btn_setup_stations").prop('disabled', true);
      //           $("#btn_edit_material_details_verification").prop('disabled', true);
      //           $("#btn_approve_prod").prop('disabled', false);
      //           $("#btn_approve_qc").prop('disabled', true);
      //         }
      //         else if(prodRuncardStat == 5){
      //           $("#btn_edit_material_details_primary").prop('disabled', true);  
      //           $("#btnSaveSelectedMatSak").prop('disabled', true);
      //           $("#btn_setup_stations").prop('disabled', true);
      //           $("#btn_edit_material_details_verification").prop('disabled', true);
      //           $("#btn_approve_prod").prop('disabled', true);
      //           $("#btn_approve_qc").prop('disabled', false);
      //         }
      //         else{
      //           HandleButtons(true);
      //         }

      //         // $("#btn_setup_stations").removeAttr('disabled');
      //         // $("#btn_edit_material_details_verification").removeAttr('disabled');
      //         // $("#btnSaveSelectedMatSak").removeAttr('disabled');
      //         // $("#btn_approve_prod").removeAttr('disabled');
      //         // $("#btn_approve_qc").removeAttr('disabled');
      //       }
      //       else{
      //         $("#txt_po_number").val('');
      //         $("#txt_po_qty").val('');
      //         $("#txt_use_for_device").val('');
      //         $("#txt_device_code").val('');
      //         $("#txt_runcard_no").val('');
      //         $("#txt_lot_qty").val('');
      //         $("#txt_assessment_no").val('');
      //         $("#txt_a_drawing_no").val('');
      //         $("#txt_a_drawing_rev").val('');
      //         $("#txt_g_drawing_no").val('');
      //         $("#txt_g_drawing_rev").val('');
      //         $("#txt_other_docs_no").val('');
      //         $("#txt_other_docs_rev").val('');

      //         // $("#btn_setup_stations").prop('disabled', 'disabled');
      //         // $("#btnSaveSelectedMatSak").prop('disabled', true);
      //         // $("#btn_edit_material_details_verification").prop('disabled', true);
      //         // $("#btn_setup_stations").prop('disabled', true);
      //         // $("#btn_approve_prod").prop('disabled', true);
      //         // $("#btn_approve_qc").prop('disabled', true);
      //       }

      //       let noOfSelected = parseInt(arrSelectedMaterial.length) + parseInt(arrSelectedSakidashi.length);
      //       if(noOfSelected > 0 && hasProdMatSakList){
      //         $("#spanNoOfSelectedMatSak").text("(" + noOfSelected + ")");
      //         // $("#btnSaveSelectedMatSak").prop('disabled', true);
      //       }
      //       else{
      //         $("#spanNoOfSelectedMatSak").text("");
      //         // $("#btnSaveSelectedMatSak").prop('disabled', false);
      //       }
      //       dt_materials.draw();
      //       dt_sakidashi.draw();
      //       dt_prod_runcard_stations.draw();
      //       // console.log(arrSelectedMaterial);
      //       // console.log(arrSelectedSakidashi);
      //     }
      //   });
      // }

      // function HandleButtons(status){
      //   $("#btn_edit_material_details_primary").prop('disabled', status);  
      //   $("#btnSaveSelectedMatSak").prop('disabled', status);
      //   $("#btn_setup_stations").prop('disabled', status);
      //   $("#btn_edit_material_details_verification").prop('disabled', status);
      //   $("#btn_approve_prod").prop('disabled', status);
      //   $("#btn_approve_qc").prop('disabled', status);
      // }

      // function save_approve_prod(){
      //   var data = 'txt_prod_runcard_id_query=' + $("#txt_prod_runcard_id_query").val()+'&txt_employee_number_scanner=' + $("#txt_employee_number_scanner").val() + '&_token=' + '{{ csrf_token() }}';

      //   $.ajax({
      //     'data'      : data,
      //     'type'      : 'post',
      //     'dataType'  : 'json',
      //     'url'       : 'update_prod_runcard_approval_prod',
      //     success     : function(data){
      //       $('#mdl_alert #mdl_alert_title').html(data['title']);
      //       $('#mdl_alert #mdl_alert_body').html(data['body']);
      //       $('#mdl_alert').modal('show');

      //       var data_arr = [];
      //       data_arr['material_id']     = $('#txt_wbs_kit_issuance_id_query').val();
      //       data_arr['material_code']   = $('#txt_part_number').val();
      //       // fn_select_material_details(data_arr);
      //       GetProdRuncardById($("#txt_prod_runcard_id_query").val());
      //     },
      //     completed     : function(data){
      //       var data_arr = [];
      //       data_arr['material_id']     = $('#txt_wbs_kit_issuance_id_query').val();
      //       data_arr['material_code']   = $('#txt_part_number').val();
      //       // fn_select_material_details(data_arr);
      //       $('#mdl_alert #mdl_alert_title').html(data['title']);
      //       $('#mdl_alert #mdl_alert_body').html(data['body']);
      //       $('#mdl_alert').modal('show');
      //       GetProdRuncardById($("#txt_prod_runcard_id_query").val());
      //     },
      //     error     : function(data){
      //     },
      //   });
      // }

      // function save_approve_qc(){
      //   var data = 'txt_prod_runcard_id_query=' + $("#txt_prod_runcard_id_query").val()+'&txt_employee_number_scanner=' + $("#txt_employee_number_scanner").val() + '&_token=' + '{{ csrf_token() }}';

      //   $.ajax({
      //     'data'      : data,
      //     'type'      : 'post',
      //     'dataTypesdsd'  : 'json',
      //     'url'       : 'update_prod_runcard_approval_qc',
      //     success     : function(data){
      //       $('#mdl_alert #mdl_alert_title').html(data['title']);
      //       $('#mdl_alert #mdl_alert_body').html(data['body']);
      //       $('#mdl_alert').modal('show');
      //       var data_arr = [];
      //       data_arr['material_id']     = $('#txt_wbs_kit_issuance_id_query').val();
      //       data_arr['material_code']   = $('#txt_part_number').val();
      //       // fn_select_material_details(data_arr);
      //       GetProdRuncardById($("#txt_prod_runcard_id_query").val());
      //     },
      //     completed     : function(data){
      //       var data_arr = [];
      //       data_arr['material_id']     = $('#txt_wbs_kit_issuance_id_query').val();
      //       data_arr['material_code']   = $('#txt_part_number').val();
      //       // fn_select_material_details(data_arr);
      //       $('#mdl_alert #mdl_alert_title').html(data['title']);
      //       $('#mdl_alert #mdl_alert_body').html(data['body']);
      //       $('#mdl_alert').modal('show');
      //       GetProdRuncardById($("#txt_prod_runcard_id_query").val());
      //     },
      //     error     : function(data){

      //     },
      //   });
      // }




      //-------------------------------------------------------

      // function OutputDataCounter(api) {
      //   let counter = 0;

      //   api.column().data(0, {page:'current'}).each( function (group, i) {

      //         let data = api.row(i).data();
      //         let output = data.qty_output;
      //         let status = data.status;
      //         // if(output != null){
      //         //   counter++;
      //         // }

      //         if(status == 1){
      //           counter++;
      //         }
      //   });

      //   if(api.rows().count() > 0 && (counter == api.rows().count())){
      //     dtOutputStatus = true;
      //   }
      //   else{
      //     dtOutputStatus = false;
      //   }

      //   if(api.rows().count() > 0){
      //     // $("#btnSaveSelectedMatSak").prop('disabled', true);
      //   }
      //   // else{
      //    // $("#btnSaveSelectedMatSak").prop('disabled', false);
      //   // }

      //   // alert(dtOutputStatus);
      //   if(dtOutputStatus){
      //     // $("#btn_edit_material_details_verification").prop('disabled', false);
      //     if($("#txt_prod_runcard_status").val() == 1){
      //       // $("#btn_edit_material_details_verification").prop('disabled', true);
      //     }
      //     else{
      //       // $("#btn_edit_material_details_verification").prop('disabled', false);
      //     }
      //   }
      //   else{ 
      //     // $("#btn_edit_material_details_verification").prop('disabled', true);
      //   }
      // }

      $("#btnShowNGSummary").click(function(){
        if($("#txt_po_number_lbl").val() != ""){
          $("#spanNGSummaryPoNo").text(currentPoNo);
          dt_ng_summary.draw();
          $("#modalNGSummary").modal('show');
        }
        else{
          toastr.warning('PO not found!');
          $("#spanNGSummaryPoNo").text('--');
        }
      });

      $(document).on('click','#btn_scan_machine_code',function(e){
        $('#txt_qrcode_scanner').val('');
        $('#mdl_qrcode_scanner').attr('data-formid','fn_scan_machine_code').modal('show');
      });

      $(document).on('click','#btn_scan_add_runcard_material_kitting',function(e){
        $('#txt_qrcode_scanner').val('');
        $('#mdl_qrcode_scanner').attr('data-formid','fn_scan_material_kitting').modal('show');
      });

      $(document).on('click','#btn_scan_add_runcard_sakidashi',function(e){
        $('#txt_qrcode_scanner').val('');
        $('#mdl_qrcode_scanner').attr('data-formid','fn_scan_sakidashi').modal('show');
      });

      $(document).on('click','#btn_scan_add_runcard_emboss',function(e){
        $('#txt_qrcode_scanner').val('');
        $('#mdl_qrcode_scanner').attr('data-formid','fn_scan_emboss').modal('show');
      });

      $(document).on('click','#btn_scan_add_runcard_operator_code',function(e){
        $('#txt_qrcode_scanner').val('');
        $('#mdl_qrcode_scanner').attr('data-formid','fn_scan_runcard_operator_code').modal('show');
      });

      $(document).on('click','.btn_edit_prod_runcard_station',function(e){
        var data_arr = [];
        data_arr['col_prod_runcard_station_id']     = $(this).closest('tr').find('.col_prod_runcard_station_id').val();
        data_arr['has_emboss']     = $(this).attr('has_emboss');
        // data_arr['material_code']   = $(this).closest('tr').find('.col_material_code').val();
        $("#txt_edit_prod_runcard_operator").val("0").trigger('change');
        let matProcId = $(this).attr('mat-proc-id');
        fn_select_prod_runcard_station_details(data_arr, matProcId);

        $("#tblEditProdRunStaMOD tbody").html('');
        $("#pRCStatTotNoOfNG").css({color: 'green'});
        $("#pRCStatTotNoOfNG").text('0');

        // let stepNumOnly = $(this).closest('td').siblings().text();
        let stepNumOnly = $(this).closest('td').siblings().eq(0).text().split('-')[0];
        let sameStepNumCounter = 0;
        // $('#tbl_prod_runcard_stations tbody tr').each(function(i, obj) {
        //   console.log($("#tbl_prod_runcard_stations tbody tr").closest('td').child().eq(0).text().split('-')[0] + 'wew');
        // });

        $('#tbl_prod_runcard_stations tbody').find('tr').each(function (key, val) {
              var this_row = $(this);
              let tdStepNum = $.trim(this_row.find('td:eq(1)').html()).split('-')[0];
              
              if(stepNumOnly == tdStepNum){
                sameStepNumCounter++;
              }

              // console.log(tdStepNum);
        });
        // console.log(sameStepNumCounter);
        $("#txt_edit_prod_runcard_station_input").val(parseInt($(
                "#txt_lot_qty").val() / sameStepNumCounter));
        $("#txt_edit_prod_runcard_station_output").val(parseInt($(
                "#txt_lot_qty").val() / sameStepNumCounter));
        $("#txt_edit_prod_runcard_station_ng").val(0);

        $("#btn_save_prod_runcard_station_stations").prop('disabled', false);
      });

      $('#frm_edit_prod_runcard_station_details').submit(function(e){
        e.preventDefault();

        $.ajax({
          'data'      : $(this).serialize() +'&txt_employee_number_scanner=' + $("#txt_employee_number_scanner").val() +'&txt_prod_runcard_id_query=' + $("#txt_prod_runcard_id_query").val() + "&require_oqc_before_emboss=" + $("#txt_prod_runcard_require_oqc_before_emboss").val(),
          'type'      : 'post',
          'dataType'  : 'json',
          'url'       : 'update_prod_runcard_station_details',
          success     : function(data){
            $('#mdl_alert #mdl_alert_title').html(data['title']);
            $('#mdl_alert #mdl_alert_body').html(data['body']);
            $('#mdl_alert').modal('show');

            dt_prod_runcard_stations.draw();
            dt_prod_runcard.draw();
            $('#mdl_edit_prod_runcard_station_details').modal('hide');
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

      $(document).on('click','#btnSaveSelectedMatSak',function(e){
        let noOfSelected = parseInt(arrSelectedSakidashi.length) + parseInt(arrSelectedMaterial.length);
        if($("#txt_prod_runcard_id_query").val() == "" || $("#txt_prod_runcard_id_query").val() == null){
          toastr.warning('Fill-out Material Details First!');
        }
        else{
          if($("#txt_prod_runcard_status").val() == 1){
            if(noOfSelected > 0){
              if($("#txt_prod_runcard_id_query").val() == "" || $("#txt_prod_runcard_id_query").val() == null){
                toastr.warning('Fill-out Material Details First');
              }
              else{
                $('#txt_employee_number_scanner').val('');
                $('#mdl_employee_number_scanner').attr('data-formid','SaveProdMaterialList').modal('show');
              }
            }
            else{
              toastr.warning('Please Select Material!');
            }
          }
          else{
            toastr.warning('Materials Already Saved!');
          }
        }

      });

      $(document).on('click','#btnSaveSelectedEmboss',function(e){
        let noOfSelected = parseInt(arrSelectedEmboss.length);
        if($("#txt_prod_runcard_id_query").val() == "" || $("#txt_prod_runcard_id_query").val() == null){
          toastr.warning('Fill-out Material Details First!');
        }
        else{
          if($("#txt_prod_runcard_status").val() == 7){
            if($("#txt_prod_runcard_require_oqc_before_emboss").val() == 1){
              if(noOfSelected > 0){
                if($("#txt_prod_runcard_id_query").val() == "" || $("#txt_prod_runcard_id_query").val() == null){
                  toastr.warning('Fill-out Material Details First');
                }
                else{
                  $('#txt_employee_number_scanner').val('');
                  $('#mdl_employee_number_scanner').attr('data-formid','SaveProdEmbossMaterialList').modal('show');
                }
              }
              else{
                toastr.warning('Please Select Material!');
              }
            }
            else{
              if($("#txt_prod_runcard_id_query").val() == "" || $("#txt_prod_runcard_id_query").val() == null){
                toastr.warning('Fill-out Material Details First');
              }
              else{
                $('#txt_employee_number_scanner').val('');
                $('#mdl_employee_number_scanner').attr('data-formid','SaveProdEmbossMaterialList').modal('show');
              }
            }
          }
          else{
            if($("#txt_prod_runcard_require_oqc_before_emboss").val() == 1){
              toastr.warning('Not Ready for Emboss!');
            }
            else{
              if($("#txt_prod_runcard_id_query").val() == "" || $("#txt_prod_runcard_id_query").val() == null){
                toastr.warning('Fill-out Material Details First');
              }
              else{
                $('#txt_employee_number_scanner').val('');
                $('#mdl_employee_number_scanner').attr('data-formid','SaveProdEmbossMaterialList').modal('show');
              }
            }
          }
        }

      });

      $(document).on('click','#btn_save_setup_stations',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','save_setup_stations').modal('show');
      });

      // $(document).on('click','.btn_edit_prod_runcard_station',function(e){
      //   var data_arr = [];
      //   data_arr['col_prod_runcard_station_id']     = $(this).closest('tr').find('.col_prod_runcard_station_id').val();
      //   // data_arr['material_code']   = $(this).closest('tr').find('.col_material_code').val();
      //   fn_select_prod_runcard_station_details(data_arr);
      // });

    });

function OutputDataCounter(api) {
        let counter = 0;

        api.column().data(0, {page:'current'}).each( function (group, i) {

              let data = api.row(i).data();
              let output = data.qty_output;
              let status = data.status;
              // if(output != null){
              //   counter++;
              // }

              if(status == 1){
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
          // $("#btnSaveSelectedMatSak").prop('disabled', true);
        }
        // else{
         // $("#btnSaveSelectedMatSak").prop('disabled', false);
        // }

        // alert(dtOutputStatus);
        if(dtOutputStatus){
          // $("#btn_edit_material_details_verification").prop('disabled', false);
          if($("#txt_prod_runcard_status").val() == 1){
            // $("#btn_edit_material_details_verification").prop('disabled', true);
          }
          else{
            // $("#btn_edit_material_details_verification").prop('disabled', false);
          }
        }
        else{ 
          // $("#btn_edit_material_details_verification").prop('disabled', true);
        }
      }


      function HandleButtons(status){
        $("#btn_edit_material_details_primary").prop('disabled', status);  
        $("#btnSaveSelectedMatSak").prop('disabled', status);
        $("#btnSaveSelectedEmboss").prop('disabled', status);
        $("#btn_setup_stations").prop('disabled', status);
        $("#btn_edit_material_details_verification").prop('disabled', status);
        $("#btn_approve_prod").prop('disabled', status);
        $("#btn_approve_qc").prop('disabled', status);
      }

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
            data_arr['material_code']   = $('#txt_part_number').val();
            // fn_select_material_details(data_arr);
            GetProdRuncardById($("#txt_prod_runcard_id_query").val());
          },
          completed     : function(data){
            var data_arr = [];
            data_arr['material_id']     = $('#txt_wbs_kit_issuance_id_query').val();
            data_arr['material_code']   = $('#txt_part_number').val();
            // fn_select_material_details(data_arr);
            $('#mdl_alert #mdl_alert_title').html(data['title']);
            $('#mdl_alert #mdl_alert_body').html(data['body']);
            $('#mdl_alert').modal('show');
            GetProdRuncardById($("#txt_prod_runcard_id_query").val());
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
            data_arr['material_code']   = $('#txt_part_number').val();
            // fn_select_material_details(data_arr);
            GetProdRuncardById($("#txt_prod_runcard_id_query").val());
          },
          completed     : function(data){
            var data_arr = [];
            data_arr['material_id']     = $('#txt_wbs_kit_issuance_id_query').val();
            data_arr['material_code']   = $('#txt_part_number').val();
            // fn_select_material_details(data_arr);
            $('#mdl_alert #mdl_alert_title').html(data['title']);
            $('#mdl_alert #mdl_alert_body').html(data['body']);
            $('#mdl_alert').modal('show');
            GetProdRuncardById($("#txt_prod_runcard_id_query").val());
          },
          error     : function(data){

          },
        });
      }

    // function GetMaterialKitting(){
    //   $.ajax({
    //     url: "get_wbs_material_kitting",
    //     method: 'get',
    //     dataType: 'json',
    //     data: {
    //       po_number: currentPoNo
    //     },
    //     beforeSend: function(){
    //       boxing = "";
    //       assessment = "";
    //       aDrawing = "";
    //       aDrawingRev = "";
    //       gDrawing = "";
    //       gDrawingRev = "";
    //     },
    //     success: function(data){
    //       if(data['material_kitting'] != null){
    //         $("#txt_po_number_lbl").val(data['material_kitting']['po_no']);
    //         $("#txt_device_name_lbl").val(data['material_kitting']['device_name']);
    //         $("#txt_device_code_lbl").val(data['material_kitting']['device_code']);
    //         $("#txt_po_qty_lbl").val(data['material_kitting']['po_qty']);
    //         if(data['material_kitting']['device_info'] != null){
    //           // $("#txt_lot_qty").val(data['material_kitting']['device_info']['boxing']);
    //           boxing = data['material_kitting']['device_info']['boxing'];
    //           // alert(boxing);
    //         }
    //         else{
    //           boxing = "";
    //           // $("#txt_lot_qty").val("");
    //         }

    //         if(data['material_kitting']['assessment'] != null){
    //           assessment = data['material_kitting']['device_info']['assessment'];
    //         }
    //         else{
    //           assessment = "";
    //         }

    //         // if(data['material_kitting']['documents_details'].length > 0){
    //         //   for(let index = 0; index < data['material_kitting']['documents_details'].length; index++){
    //         //     if(data['material_kitting']['documents_details'][index]['doc_no'].charAt(0).toUpperCase() == "A"){
    //         //       aDrawing = data['material_kitting']['documents_details'][index]['doc_no'];
    //         //       aDrawingRev = data['material_kitting']['documents_details'][index]['rev_no'];
    //         //     }
    //         //     else if(data['material_kitting']['documents_details'][index]['doc_no'].charAt(0).toUpperCase() == "G"){
    //         //       gDrawing = data['material_kitting']['documents_details'][index]['doc_no'];
    //         //       gDrawingRev = data['material_kitting']['documents_details'][index]['rev_no'];
    //         //     }
    //         //   }

    //         //   $("#txt_a_drawing_no").val(aDrawing);
    //         //   $("#txt_a_drawing_rev").val(aDrawingRev);
    //         //   $("#txt_g_drawing_no").val(gDrawing);
    //         //   $("#txt_g_drawing_rev").val(gDrawingRev);
    //         // }
    //         if(data['doc_a_drawing_query'].length > 0){
    //           aDrawing = data['doc_a_drawing_query'][0].doc_no;
    //           aDrawingRev = data['doc_a_drawing_query'][0].rev_no;
    //         }
    //         else{
    //           aDrawing = "";
    //           aDrawingRev = "";
    //         }
    //         $("#txt_a_drawing_no").val(aDrawing);
    //         $("#txt_a_drawing_rev").val(aDrawingRev);

    //         if(data['doc_g_drawing_query'].length > 0){
    //           gDrawing = data['doc_g_drawing_query'][0].doc_no;
    //           gDrawingRev = data['doc_g_drawing_query'][0].rev_no;
    //         }
    //         else{
    //           gDrawing = "";
    //           gDrawingRev = "";
    //         }
    //         $("#txt_g_drawing_no").val(gDrawing);
    //         $("#txt_g_drawing_rev").val(gDrawingRev);

    //         // materialKitTransferSlip = data['material_kitting']['issuance_no'];
    //         // $("#txt_material_transfer_slip_lbl").val(materialKitTransferSlip);
    //         // dt_materials.draw();

    //         // $("#txt_lot_qty").val(boxing);
    //       }
    //       else{
    //         $("#txt_po_number_lbl").val('');
    //         $("#txt_device_name_lbl").val('');
    //         $("#txt_device_code_lbl").val('');
    //         $("#txt_po_qty_lbl").val(''); 
    //         $("#txt_lot_qty").val("");
    //       }
    //     }
    //   });
    // }

    function GetMaterialKitting(){
      $.ajax({
        url: "get_wbs_material_kitting",
        method: 'get',
        dataType: 'json',
        data: {
          po_number: currentPoNo
        },
        beforeSend: function(){
          boxing = "";
          assessment = "";
          aDrawing = "";
          aDrawingRev = "";
          gDrawing = "";
          gDrawingRev = "";
        },
        success: function(data){
          if(data['material_kitting'] != null){
            $("#txt_po_number_lbl").val(data['material_kitting']['po_no']);
            $("#txt_device_name_lbl").val(data['material_kitting']['device_name']);
            $("#txt_device_code_lbl").val(data['material_kitting']['device_code']);
            $("#txt_po_qty_lbl").val(data['material_kitting']['po_qty']);
            if(data['material_kitting']['device_info'] != null){
              // $("#txt_lot_qty").val(data['material_kitting']['device_info']['boxing']);
              boxing = data['material_kitting']['device_info']['boxing'];
              // alert(boxing);
            }
            else{
              boxing = "";
              // $("#txt_lot_qty").val("");
            }

            if(data['material_kitting']['assessment'] != null){
              assessment = data['material_kitting']['device_info']['assessment'];
            }
            else{
              assessment = "";
            }

            // if(data['material_kitting']['documents_details'].length > 0){
            //   for(let index = 0; index < data['material_kitting']['documents_details'].length; index++){
            //     if(data['material_kitting']['documents_details'][index]['doc_no'].charAt(0).toUpperCase() == "A"){
            //       aDrawing = data['material_kitting']['documents_details'][index]['doc_no'];
            //       aDrawingRev = data['material_kitting']['documents_details'][index]['rev_no'];
            //     }
            //     else if(data['material_kitting']['documents_details'][index]['doc_no'].charAt(0).toUpperCase() == "G"){
            //       gDrawing = data['material_kitting']['documents_details'][index]['doc_no'];
            //       gDrawingRev = data['material_kitting']['documents_details'][index]['rev_no'];
            //     }
            //   }

            //   $("#txt_a_drawing_no").val(aDrawing);
            //   $("#txt_a_drawing_rev").val(aDrawingRev);
            //   $("#txt_g_drawing_no").val(gDrawing);
            //   $("#txt_g_drawing_rev").val(gDrawingRev);
            // }
            if(data['doc_a_drawing_query'].length > 0){
              aDrawing = data['doc_a_drawing_query'][0].doc_no;
              aDrawingRev = data['doc_a_drawing_query'][0].rev_no;
            }
            else{
              aDrawing = "";
              aDrawingRev = "";
            }
            $("#txt_a_drawing_no").val(aDrawing);
            $("#txt_a_drawing_rev").val(aDrawingRev);

            if(data['doc_g_drawing_query'].length > 0){
              gDrawing = data['doc_g_drawing_query'][0].doc_no;
              gDrawingRev = data['doc_g_drawing_query'][0].rev_no;
            }
            else{
              gDrawing = "";
              gDrawingRev = "";
            }
            $("#txt_g_drawing_no").val(gDrawing);
            $("#txt_g_drawing_rev").val(gDrawingRev);

            // materialKitTransferSlip = data['material_kitting']['issuance_no'];
            // $("#txt_material_transfer_slip_lbl").val(materialKitTransferSlip);
            // dt_materials.draw();

            // $("#txt_lot_qty").val(boxing);
          }
          else{
            $("#txt_po_number_lbl").val('');
            $("#txt_device_name_lbl").val('');
            $("#txt_device_code_lbl").val('');
            $("#txt_po_qty_lbl").val(''); 
            $("#txt_lot_qty").val("");
          }
        }
      });
    }

    function GetSakidashiIssuance(){
      $.ajax({
        url: "get_wbs_sakidashi_issuance",
        method: 'get',
        dataType: 'json',
        data: {
          po_number: currentPoNo
        },
        beforeSend: function(){
          // boxing = "";
        },
        success: function(data){
          if(data['sakidashi_issuance'] != null){
            sakidashiCtrlNo = data['sakidashi_issuance']['issuance_no'];
            $("#txt_sakidashi_ctrl_no_lbl").val(sakidashiCtrlNo);
            dt_sakidashi.draw();
          }
          else{
            sakidashiCtrlNo = "";
            $("#txt_sakidashi_ctrl_no_lbl").val("");
            dt_sakidashi.draw();
          }
        }
      });
    }

    function GetProdRuncardById(prodRuncardId){
      $.ajax({
        url: "get_prod_runcard_by_id",
        method: 'get',
        dataType: 'json',
        data: {
          prod_runcard_id: prodRuncardId
        },
        beforeSend: function(){
          arrSelectedMaterial = [];
          arrSelectedSakidashi = [];
          arrSelectedEmboss = [];
          readonly_material_details_primary(true);
          readonly_material_details_secondary(true);
          // reset_material_details_primary();
          // reset_material_details_secondary();
        },
        success: function(data){
          if(data['prod_runcard'] != null){
            $("#modalRuncardDetails").modal('show');
            $("#txt_po_number").val($("#txt_po_number_lbl").val());
            $("#txt_po_qty").val($("#txt_po_qty_lbl").val());
            $("#txt_use_for_device").val($("#txt_device_name_lbl").val());
            $("#txt_device_code").val($("#txt_device_code_lbl").val());
            $("#txt_prod_runcard_id_query").val(data['prod_runcard']['id']);
            $("#txt_prod_runcard_status").val(data['prod_runcard']['status']);
            $("#txt_prod_runcard_require_oqc_before_emboss").val(data['prod_runcard']['require_oqc_before_emboss']);
            $("#txt_prod_runcard_verification_id_query").val(data['prod_runcard']['id']);
            $("#txt_runcard_no").val(data['prod_runcard']['runcard_no']);
            $("#txt_reel_lot_no").val(data['prod_runcard']['reel_lot_no']);
            $("#txt_lot_qty").val(data['prod_runcard']['lot_qty']);
            $("#txt_assessment_no").val(data['prod_runcard']['assessment_no']);
            $("#txt_a_drawing_no").val(data['prod_runcard']['a_drawing_no']);
            $("#txt_a_drawing_rev").val(data['prod_runcard']['a_drawing_rev']);
            $("#txt_g_drawing_no").val(data['prod_runcard']['g_drawing_no']);
            $("#txt_g_drawing_rev").val(data['prod_runcard']['g_drawing_rev']);
            $("#txt_other_docs_no").val(data['prod_runcard']['other_docs_no']);
            $("#txt_other_docs_rev").val(data['prod_runcard']['other_docs_rev']);

            // alert(data['prod_runcard']['require_oqc_before_emboss']);

            if(data['prod_runcard']['discrepant_qty'] != null){
              if(data['prod_runcard']['discrepant_qty'] < 0){
                $('#txt_discrepant_qty_sign').val("-");
                $('#txt_discrepant_qty').val(data['prod_runcard']['discrepant_qty'] * -1);
              }
              else if(data['prod_runcard']['discrepant_qty'] == 0){
                $('#txt_discrepant_qty_sign').val("0");
                $('#txt_discrepant_qty').val(data['prod_runcard']['discrepant_qty']);
              }
              else{
                $('#txt_discrepant_qty_sign').val("+");
                $('#txt_discrepant_qty').val(data['prod_runcard']['discrepant_qty']);
              }
            }
            else{
              $('#txt_discrepant_qty_sign').val("0");
              $('#txt_discrepant_qty').val("");
            }

            $('#txt_analysis').val(data['prod_runcard']['analysis']);
            $('#txt_recount_ok').val(data['prod_runcard']['recount_ok']);
            $('#txt_recount_ng').val(data['prod_runcard']['recount_ng']);

            if(data['prod_runcard']['supervisor_prod_info'] != null){
              $('#txt_prod_approval').val(data['prod_runcard']['supervisor_prod_info']['name']);
            }
            else{
              $('#txt_prod_approval').val("");
            }

            if(data['prod_runcard']['supervisor_qc_info'] != null){
              $('#txt_qc_approval').val(data['prod_runcard']['supervisor_qc_info']['name']);
            }
            else{
              $('#txt_qc_approval').val("");
            }

            if(data['prod_runcard']['prod_runcard_material_list'].length > 0){
              // $("#btn_edit_material_details_primary").prop('disabled', true);
              // $("#btnSaveSelectedMatSak").prop('disabled', true);

              for(let index = 0; index < data['prod_runcard']['prod_runcard_material_list'].length; index++){
                if(data['prod_runcard']['prod_runcard_material_list'][index]['tbl_wbs'] == 1){
                  arrSelectedMaterial.push(data['prod_runcard']['prod_runcard_material_list'][index]['issuance_id']);
                }
                else if(data['prod_runcard']['prod_runcard_material_list'][index]['tbl_wbs'] == 2 && data['prod_runcard']['prod_runcard_material_list'][index]['for_emboss'] == 0){
                  arrSelectedSakidashi.push(data['prod_runcard']['prod_runcard_material_list'][index]['issuance_id']);
                }
                else if(data['prod_runcard']['prod_runcard_material_list'][index]['tbl_wbs'] == 2 && data['prod_runcard']['prod_runcard_material_list'][index]['for_emboss'] == 1){
                  arrSelectedEmboss.push(data['prod_runcard']['prod_runcard_material_list'][index]['issuance_id']);
                }
              }
            }
            else{
              // $("#btn_edit_material_details_primary").prop('disabled', false);
              // $("#btnSaveSelectedMatSak").prop('disabled', false);
            }

            if(data['prod_runcard']['prod_runcard_material_list'].length > 0){
              hasProdMatSakList = true;
              // $("#btn_setup_stations").prop('disabled', false);
                if(data['prod_runcard']['discrepant_qty'] != null) {
                // $("#btn_edit_material_details_verification").prop('disabled', true);
                // $("#btn_setup_stations").prop('disabled', true);
                // $("#btn_approve_prod").prop('disabled', false);
                // $("#btn_approve_qc").prop('disabled', false);

                if(data['prod_runcard']['supervisor_prod_info'] != null){
                  // $("#btn_edit_material_details_verification").prop('disabled', true);
                  // $("#btn_approve_prod").prop('disabled', true);
                  // $("#btn_approve_prod").prop('disabled', true);
                  if(data['prod_runcard']['supervisor_qc_info'] != null){
                    // $("#btn_approve_qc").prop('disabled', true);
                  }
                  else{
                    // $("#btn_approve_qc").prop('disabled', false); 
                  }
                }
                else{
                  // $("#btn_edit_material_details_verification").prop('disabled', false);
                  // $("#btn_approve_prod").prop('disabled', false);
                  // $("#btn_approve_qc").prop('disabled', true);
                }

                if(data['prod_runcard']['discrepant_qty'] == 0){
                  // $("#btn_approve_prod").prop('disabled', true);
                  // $("#btn_approve_qc").prop('disabled', true);
                }
              }
              else{
                // $("#btn_setup_stations").prop('disabled', false);
                // $("#btn_approve_prod").prop('disabled', true);
                // $("#btn_approve_qc").prop('disabled', true);
                // $("#btn_edit_material_details_verification").prop('disabled', false);
              }
            }
            else{
              // $("#btn_setup_stations").prop('disabled', true);
              hasProdMatSakList = true;
            }

            // if(data['prod_runcard']['discrepant_qty'] != null) {
              // $("#btn_edit_material_details_verification").prop('disabled', true);
              // $("#btn_setup_stations").prop('disabled', true);
            //   $("#btn_approve_prod").prop('disabled', false);
            //   $("#btn_approve_qc").prop('disabled', false);

            //   if(data['prod_runcard']['supervisor_prod_info'] != null){
                // $("#btn_edit_material_details_verification").prop('disabled', true);
            //     $("#btn_approve_prod").prop('disabled', true);
            //     $("#btn_approve_prod").prop('disabled', true);
            //     if(data['prod_runcard']['supervisor_qc_info'] != null){
            //       $("#btn_approve_qc").prop('disabled', true);
            //     }
            //     else{
            //       $("#btn_approve_qc").prop('disabled', false); 
            //     }
            //   }
            //   else{
                // $("#btn_edit_material_details_verification").prop('disabled', false);
            //     $("#btn_approve_prod").prop('disabled', false);
            //     $("#btn_approve_qc").prop('disabled', true);
            //   }

            //   if(data['prod_runcard']['discrepant_qty'] == 0){
            //     $("#btn_approve_prod").prop('disabled', true);
            //     $("#btn_approve_qc").prop('disabled', true);
            //   }
            // }
            // else{
              // $("#btn_setup_stations").prop('disabled', false);
            //   $("#btn_approve_prod").prop('disabled', true);
            //   $("#btn_approve_qc").prop('disabled', true);
              // $("#btn_edit_material_details_verification").prop('disabled', false);
            // }

            HandleButtons(true);
            let prodRuncardStat = data['prod_runcard']['status'];
            if(prodRuncardStat == 1){
              $("#btn_edit_material_details_primary").prop('disabled', true);  
              $("#btnSaveSelectedMatSak").prop('disabled', false);
              $("#btnSaveSelectedEmboss").prop('disabled', true);
              $("#btn_setup_stations").prop('disabled', true);
              $("#btn_edit_material_details_verification").prop('disabled', true);
              $("#btn_approve_prod").prop('disabled', true);
              $("#btn_approve_qc").prop('disabled', true);
            }
            else if(prodRuncardStat == 2){
              $("#btn_edit_material_details_primary").prop('disabled', true);  
              // $("#btnSaveSelectedMatSak").prop('disabled', true);
              $("#btnSaveSelectedMatSak").prop('disabled', false);
              $("#btnSaveSelectedEmboss").prop('disabled', true);
              $("#btn_setup_stations").prop('disabled', true);
              $("#btn_edit_material_details_verification").prop('disabled', false);
              $("#btn_approve_prod").prop('disabled', true);
              $("#btn_approve_qc").prop('disabled', true);
            }
            else if(prodRuncardStat == 3){
              $("#btn_edit_material_details_primary").prop('disabled', true);  
              $("#btnSaveSelectedMatSak").prop('disabled', false);
              $("#btnSaveSelectedEmboss").prop('disabled', true);
              $("#btn_setup_stations").prop('disabled', true);
              $("#btn_edit_material_details_verification").prop('disabled', false);
              $("#btn_approve_prod").prop('disabled', true);
              $("#btn_approve_qc").prop('disabled', true);
            }
            else if(prodRuncardStat == 4){
              $("#btn_edit_material_details_primary").prop('disabled', true);  
              $("#btnSaveSelectedMatSak").prop('disabled', true);
              $("#btnSaveSelectedEmboss").prop('disabled', true);
              $("#btn_setup_stations").prop('disabled', true);
              $("#btn_edit_material_details_verification").prop('disabled', true);
              $("#btn_approve_prod").prop('disabled', false);
              $("#btn_approve_qc").prop('disabled', true);
            }
            else if(prodRuncardStat == 5){
              $("#btn_edit_material_details_primary").prop('disabled', true);  
              $("#btnSaveSelectedMatSak").prop('disabled', true);
              $("#btnSaveSelectedEmboss").prop('disabled', true);
              $("#btn_setup_stations").prop('disabled', true);
              $("#btn_edit_material_details_verification").prop('disabled', true);
              $("#btn_approve_prod").prop('disabled', true);
              $("#btn_approve_qc").prop('disabled', false);
            }
            else if(prodRuncardStat == 7){
              $("#btnSaveSelectedEmboss").prop('disabled', false);
            }
            else if(prodRuncardStat == 8){
              $("#btnSaveSelectedEmboss").prop('disabled', true); 
            }
            else{
              HandleButtons(true);
            }

            // $("#btn_setup_stations").removeAttr('disabled');
            // $("#btn_edit_material_details_verification").removeAttr('disabled');
            // $("#btnSaveSelectedMatSak").removeAttr('disabled');
            // $("#btn_approve_prod").removeAttr('disabled');
            // $("#btn_approve_qc").removeAttr('disabled');
          }
          else{
            $("#txt_po_number").val('');
            $("#txt_po_qty").val('');
            $("#txt_use_for_device").val('');
            $("#txt_device_code").val('');
            $("#txt_runcard_no").val('');
            $("#txt_reel_lot_no").val('');
            $("#txt_lot_qty").val('');
            $("#txt_assessment_no").val('');
            $("#txt_a_drawing_no").val('');
            $("#txt_a_drawing_rev").val('');
            $("#txt_g_drawing_no").val('');
            $("#txt_g_drawing_rev").val('');
            $("#txt_other_docs_no").val('');
            $("#txt_other_docs_rev").val('');

            // $("#btn_setup_stations").prop('disabled', 'disabled');
            // $("#btnSaveSelectedMatSak").prop('disabled', true);
            // $("#btn_edit_material_details_verification").prop('disabled', true);
            // $("#btn_setup_stations").prop('disabled', true);
            // $("#btn_approve_prod").prop('disabled', true);
            // $("#btn_approve_qc").prop('disabled', true);
          }

          let noOfSelected = parseInt(arrSelectedMaterial.length) + parseInt(arrSelectedSakidashi.length);
          if(noOfSelected > 0 && hasProdMatSakList){
            $("#spanNoOfSelectedMatSak").text("(" + noOfSelected + ")");
            // $("#btnSaveSelectedMatSak").prop('disabled', true);
          }
          else{
            $("#spanNoOfSelectedMatSak").text("");
            // $("#btnSaveSelectedMatSak").prop('disabled', false);
          }
          dt_materials.draw();
          dt_sakidashi.draw();
          dt_emboss.draw();
          dt_prod_runcard_stations.draw();
          // console.log(arrSelectedMaterial);
          // console.log(arrSelectedSakidashi);
        }
      });
    }

    function SaveProductMaterialList(arrSelectedMaterials, arrSelectedSakidashis, _token, employeeId, prodRuncardId){
      // alert(prodRuncardId);
      // return 'wew';
      $.ajax({
        url : 'save_prod_material_list',
        method : 'post',
        data: {
          _token: _token,
          material_issuance: arrSelectedMaterials,
          sakidashi_issuance: arrSelectedSakidashis,
          employee_id: employeeId,
          prod_runcard_id: prodRuncardId
        },
        dataType : 'json',
        before: function(){
          alert("Loading...");
        },
        success     : function(data){
          $('#mdl_alert #mdl_alert_title').html(data['title']);
          $('#mdl_alert #mdl_alert_body').html(data['body']);
          $('#mdl_alert').modal('show');

          // $("#btnSaveSelectedMatSak").prop('disabled', true);
          // arrSelectedMaterial = arrSelectedMaterials;
          // arrSelectedSakidashi = arrSelectedSakidashis;
          // dt_prod_runcard.draw();
          // dt_materials.draw();
          // dt_sakidashi.draw();

          // alert('Saved!');
          GetProdRuncardById($("#txt_prod_runcard_id_query").val());
        },
        completed     : function(data){
          // alert('Saved!');
          GetProdRuncardById($("#txt_prod_runcard_id_query").val()); 
        },
        error     : function(data){

        },
      });
    }

    function SaveProductEmbossMaterialList(arrSelectedEmbosses, _token, employeeId, prodRuncardId){
      // alert(prodRuncardId);
      // return 'wew';
      $.ajax({
        url : 'save_prod_emboss_material_list',
        method : 'post',
        data: {
          _token: _token,
          emboss_issuance: arrSelectedEmbosses,
          employee_id: employeeId,
          prod_runcard_id: prodRuncardId
        },
        dataType : 'json',
        before: function(){
          alert("Loading...");
        },
        success     : function(data){
          $('#mdl_alert #mdl_alert_title').html(data['title']);
          $('#mdl_alert #mdl_alert_body').html(data['body']);
          $('#mdl_alert').modal('show');

          // $("#btnSaveSelectedMatSak").prop('disabled', true);
          // arrSelectedMaterial = arrSelectedMaterials;
          // arrSelectedSakidashi = arrSelectedSakidashis;
          // dt_prod_runcard.draw();
          // dt_materials.draw();
          // dt_sakidashi.draw();

          // alert('Saved!');
          GetProdRuncardById($("#txt_prod_runcard_id_query").val());
        },
        completed     : function(data){
          // alert('Saved!');
          GetProdRuncardById($("#txt_prod_runcard_id_query").val()); 
        },
        error     : function(data){

        },
      });
    }

    function save_setup_stations(){
      var arr_substations = [];
      var ctr = 0;
      $('#tbl_setup_stations tbody .ckb_station:checked').each(function(){
        if(!$(this).attr('disabled')){
          // console.log('disabled');
          arr_substations[ctr] = {
              'step' : $(this).closest('td').find('.col_station_step').val(),
              'station' : $(this).closest('td').find('.col_station_id').val(),
              'substation' : $(this).closest('td').find('.col_sub_station_id').val(),
            };
          ctr++;
        }
        // else{
        //   console.log('enabled');
        // }
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
                  dt_prod_runcard_stations.draw();
                }
                GetProdRuncardById($("#txt_prod_runcard_id_query").val());
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
    }

    function fn_select_prod_runcard_station_details(data_arr, matProcId){
      // reset_material_details_primary();
      // reset_material_details_secondary();
      var data = {
        'col_prod_runcard_station_id' : data_arr['col_prod_runcard_station_id'],
        'has_emboss' : data_arr['has_emboss'],
        '_token'                   : '{{ csrf_token() }}',
        'device_code': $("#txt_device_code").val(),
        'material_process_id': matProcId
      }
      $.ajax({
        'data'      : data,
        'type'      : 'get',
        'dataType'  : 'json',
        'url'       : 'select_prod_runcard_station_details',
        beforeSend: function(){
          $("#txt_edit_prod_runcard_cert_operator").val(0).trigger('change');
          $("#txt_edit_prod_runcard_cert_operator_visible").val(0).trigger('change');
          $("#txt_edit_prod_runcard_station_assigned_machine").val(0).trigger('change');
          $("#txt_edit_prod_runcard_station_assigned_machine_visible").val(0).trigger('change');
          $("#txt_edit_prod_runcard_station_materials").val("");
        },
        success     : function(jsonObject){
          // alert(data[0]['station']['name'])
          let data = jsonObject['prod_runcard_stations'];
          if ($.trim(data)){
            $('#txt_prod_runcard_station_id_query').val(data[0]['id']);
            $('#txt_edit_prod_runcard_station_step').val(data[0]['step_num']);
            $('#txt_edit_prod_runcard_station_has_emboss').val(data[0]['has_emboss']);
            $('#txt_edit_prod_runcard_station_station').val(data[0]['station']['name']);
            $('#txt_edit_prod_runcard_station_substation').val(data[0]['sub_station']['name']);
            $('#txt_edit_prod_runcard_station_date').val( getdate( data[0]['created_at']?data[0]['created_at']:getcurrentdate() ) );
            // $('#txt_edit_prod_runcard_station_date').val(data[0]['created_at']);
            
            // if(data[0]['operator_info'] != null){
            //   $('#txt_edit_prod_runcard_operator').val(data[0]['operator_info']['name']);
            // }
            // else{
            //   $('#txt_edit_prod_runcard_operator').val("");
            // }

            if(data[0]['qty_input'] != null){
              $('#txt_edit_prod_runcard_station_input').val(data[0]['qty_input']);
            }
            else{
              // $('#txt_edit_prod_runcard_station_input').val($("#txt_lot_qty").val());
            }

            if(data[0]['machine_id'] != null){
              $('#txt_edit_prod_runcard_station_machine').val(data[0]['machine_id']).trigger('change');
            }
            else{
              $('#txt_edit_prod_runcard_station_machine').val("").trigger('change');
            }
            if(data[0]['qty_output'] != null){
              $('#txt_edit_prod_runcard_station_output').val(data[0]['qty_output']);
            }
            if(data[0]['qty_ng'] != null){
              $('#txt_edit_prod_runcard_station_ng').val(data[0]['qty_ng']);
            }
            // $('#txt_edit_prod_runcard_station_output').val(data[0]['qty_output']);
            // $('#txt_edit_prod_runcard_station_ng').val(data[0]['qty_ng']);
            // $('#txt_edit_prod_runcard_station_mod').val(data[0]['mod']);

            if(data[0]['operator'] != null){
              let operators = data[0]['operator'].split(',');
              $('#txt_edit_prod_runcard_operator').val(operators).trigger('change');
              // console.log(operators);
            }
            else{
              $('#txt_edit_prod_runcard_operator').val("0").trigger('change');
            }

            let operatorsId = [];
            if(jsonObject['material_process'] != null){
              for(let index = 0; index < jsonObject['material_process']['manpowers_details'].length; index++){
                operatorsId.push(jsonObject['material_process']['manpowers_details'][index].manpower_id);
              }
              $("#txt_edit_prod_runcard_cert_operator").val(operatorsId).trigger('change');
              $("#txt_edit_prod_runcard_cert_operator_visible").val(operatorsId).trigger('change');
            }

            let machineId = [];
            if(jsonObject['material_process'] != null){
              for(let index = 0; index < jsonObject['material_process']['machine_details'].length; index++){
                machineId.push(jsonObject['material_process']['machine_details'][index].machine_id);
              }
              $("#txt_edit_prod_runcard_station_assigned_machine").val(machineId).trigger('change');
              $("#txt_edit_prod_runcard_station_assigned_machine_visible").val(machineId).trigger('change');
            }

            $("#txt_edit_prod_runcard_station_materials").val(jsonObject['material_process']['material']);

            if($("#txt_edit_prod_runcard_station_ng").val() == 0 || $("#txt_edit_prod_runcard_station_ng").val() == ""){
              $("#btnAddMODTable").prop('disabled', true);
            }
            else{
              $("#btnAddMODTable").prop('disabled', false);
            }

            // Get assigned materials (kitting, sakidashi, emboss)

            let arrMaterialKittingData = [];
            let arrSakidashiIssuanceData = [];
            let arrEmbossData = [];

            if(jsonObject['material_process'] != null){
              for(let index = 0; index < jsonObject['material_process']['material_details'].length; index++){
                if(jsonObject['material_process']['material_details'][index].tbl_wbs == 1){
                  arrMaterialKittingData.push(jsonObject['material_process']['material_details'][index].item + '--' + jsonObject['material_process']['material_details'][index].item_desc);
                }
                else if(jsonObject['material_process']['material_details'][index].tbl_wbs == 2 && jsonObject['material_process']['material_details'][index].has_emboss == 0){
                  arrSakidashiIssuanceData.push(jsonObject['material_process']['material_details'][index].item + '--' + jsonObject['material_process']['material_details'][index].item_desc); 
                }
                else if(jsonObject['material_process']['material_details'][index].tbl_wbs == 2 && jsonObject['material_process']['material_details'][index].has_emboss == 1){
                  arrEmbossData.push(jsonObject['material_process']['material_details'][index].item + '--' + jsonObject['material_process']['material_details'][index].item_desc);  
                }
              }
            }

            $("#txt_edit_prod_runcard_station_material_kitting").val(0).trigger('change');
            $("#txt_edit_prod_runcard_station_sakidashi").val(0).trigger('change');
            $("#txt_edit_prod_runcard_station_emboss").val(0).trigger('change');

            $("#txt_edit_prod_runcard_station_assigned_material_kitting").val(arrMaterialKittingData).trigger('change');
            $("#txt_edit_prod_runcard_station_assigned_material_kitting_visible").val(arrMaterialKittingData).trigger('change');
            $("#txt_edit_prod_runcard_station_assigned_sakidashi").val(arrSakidashiIssuanceData).trigger('change');
            $("#txt_edit_prod_runcard_station_assigned_sakidashi_visible").val(arrSakidashiIssuanceData).trigger('change');
            $("#txt_edit_prod_runcard_station_assigned_emboss").val(arrEmbossData).trigger('change');
            $("#txt_edit_prod_runcard_station_assigned_emboss_visible").val(arrEmbossData).trigger('change');

            // List the MOD Here

            // $("#txt_edit_prod_runcard_station_assigned_machine").val(jsonObject['material_process']['machine_id']).trigger('change');
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

    function reset_material_details_primary(){
      $('#txt_lot_qty').val('');
      $('#txt_assessment_no').val('');
      $('#txt_runcard_no').val('');
      $('#txt_reel_lot_no').val('');
      $('#txt_a_drawing_no').val('');
      $('#txt_a_drawing_rev').val('');
      $('#txt_g_drawing_no').val('');
      $('#txt_g_drawing_rev').val('');
      $('#txt_other_docs_no').val('');
      $('#txt_other_docs_rev').val('');
      $('#txt_other_docs_rev').val('');
      $('#txt_other_docs_rev').val('');

      $('#txt_material_details_emp_num').val('');
      $('#txt_prod_runcard_id_query').val('');
      $('#txt_prod_runcard_status').val('');
      $('#txt_prod_runcard_require_oqc_before_emboss').val('');
      $('#txt_prod_runcard_verification_id_query').val('');
      $('#txt_prod_runcard_verification_id_query').val('');
      $('#txt_wbs_kit_issuance_id_query').val('');
      $('#txt_wbs_kit_issuance_device_code_query').val('');

      readonly_material_details_primary(true);
    }

    function readonly_material_details_primary(v){
      $('#txt_assessment_no').prop('readonly',v);
      // $('#txt_lot_qty').prop('readonly',v);
      // $('#txt_runcard_no').prop('readonly',v);
      // $('#txt_assessed_qty').prop('readonly',v);
     
      // $('#txt_a_drawing_no').prop('readonly',v);
      // $('#txt_a_drawing_rev').prop('readonly',v);
      // $('#txt_g_drawing_no').prop('readonly',v);
      // $('#txt_g_drawing_rev').prop('readonly',v);
      $('#txt_other_docs_no').prop('readonly',v);
      $('#txt_other_docs_rev').prop('readonly',v);

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
      $("#sel_comp_under_runcard_no").val("").trigger('change');
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

    //---------------------

  </script>
  @endsection
@endauth