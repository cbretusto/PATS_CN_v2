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

    .class-disabled{
      pointer-events: none;
    }
    /*#mdl_edit_material_details>div{*/
      /*width: 2000px!important;*/
      /*min-width: 1400px!important;*/
    /*}*/


    .modal-xl-custom{
      width: 95%!important;
      min-width: 90%!important;
    }

    /* select[readonly].select2+.select2-container--default .select2-selection--multiple {
          background-color: #eceeef;
      }

      select[readonly].select2+.select2-container--default .select2-selection--single {
          background-color: #aaa;
          border: 1px solid #aaa;
          border-radius: 4px;
      }

      select[readonly].select2+.select2-container .select2-selection--single {
          background-color: #eceeef;
      } */

      /* select[readonly].select2+.select2-container {
        pointer-events: none;
        touch-action: none;
        background: #eceeef;
      } */


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
              <li class="breadcrumb-item">Runcard</li>
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
                            {{-- <th class="bg-warning">OK - Emboss</th>
                            <th class="bg-warning">NG</th> --}}
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
                        <input type="text" class="form-control form-control-sm" id="txt_use_for_device" name="txt_device_name" readonly>
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
                  <div class="row" style="display: none;">
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
                        <input type="text" class="form-control form-control-sm" id="txt_assessment_no" name="txt_assessment_no" readonly="true" placeholder="" readonly="true">
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
                          <span class="input-group-text w-100" id="basic-addon1">Revision.</span>
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
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Assembly Line</span>
                        </div>
                        <select name="sel_assembly_line" id="selAssemblyLine" class="form-control form-control-sm select2bs4"></select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Product Category</span>
                        </div>
                        <select name="sel_product_category" id="selProdCategory" class="form-control form-control-sm select2bs4"></select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Certification Lot</span>
                        </div>
                        <select name="sel_certification_lot" id="selCertLot" class="form-control form-control-sm select2bs4"></select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Created At</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="txt_created_at" name="txt_created_at" readonly="true" placeholder="Auto generated">
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
                      <input type="hidden" name="txt_prod_runcard_has_emboss" id="txt_prod_runcard_has_emboss">
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
                    <br><br>
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                      <div class="table-responsive" style="max-height: 500px;">

                        <table class="table table-sm table-bordered table-hover small" id="tbl_materials" style="min-width: 900px;">
                          <thead>
                            <tr class="bg-light">
                              <th>Step</th>
                              <th>Material</th>
                              <th>Lot No.</th>
                              <th>Type</th>

                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col border py-3 px-4 border-left-0 border-bottom-0">

                    <div style="float: left;">
                      <span class="badge badge-secondary">3.</span> Process Stations
                    </div>

                    <div style="float: right;">
                      <button class="btn btn-primary btn-sm" id="btnAddProcess" data-toggle="modal" ><i class="fa fa-plus" ></i> Add Process</button>
                    </div>
                    <br>
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
                            <th>Materials</th>
                            <th>Operator</th>
                            <th>Machine</th>
                            <th>Input</th>
                            <th>Output</th>
                            <th>NG QTY</th>
                            <th>MOD</th>
                            <th>MOD</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>

              </div><!-- col -->
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
            {{-- <button type="button" class="btn btn-sm btn-success" id="btnDone">Done</button> --}}
          </div>
        </div>
      </div>
    </div>
    <!-- /.Modal -->

    <div class="modal fade" id="mdl_edit_prod_runcard_station_details" tabindex="-1" role="dialog" aria-labelledby="cnptsmodal" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content modal-lg">
          <div class="modal-header">
            <h5 class="modal-title"><i class="fas fa-object-group text-info"></i> Stations</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <!-- <br> -->
            <!-- <h5 class="modal-title h5OIHeaderTitle">Overall Inspection - <span>0</span> Selected Runcards</h5> -->
          </div>
          <div class="modal-body">
            <form id="frm_edit_prod_runcard_station_details">
              @csrf
              <input type="text" name="station_id" id="stationId" hidden>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Step</span>
                    </div>
                    <input type="text" class="form-control form-control-sm" id="process_flow_step_number" name="step_no" placeholder="(Auto Generated)" readonly>
                    <input type="hidden" name="process_flow_id" id="process_flow_id">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Process</span>
                    </div>
                    <select class="form-control select2 select2bs4 selSubStation" id="txt_edit_prod_runcard_substation" name="sub_station_id">
                      <option value=""> N/A </option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Date</span>
                    </div>
                    <input type="date" class="form-control form-control-sm" id="txt_edit_prod_runcard_station_date" name="date" readonly value="<?php echo date('Y-m-d'); ?>">
                  </div>
                </div>
              </div>

              {{-- CHAN - COMMENT KA MUNA - JAN. 19, 2026 --}}
              {{-- <div class="row" style="display: none;">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Certified Operator</span>
                    </div>
                    <select class="form-control select2bs4 selectUser" id="txt_edit_prod_runcard_cert_operator" name="txt_edit_prod_runcard_cert_operator[]" multiple="multiple">
                      <option value=""> N/A </option>
                    </select>
                  </div>
                </div>
              </div> --}}

              {{-- CHAN - COMMENT KA MUNA - JAN. 19, 2026 --}}
              {{-- <div class="row" style="display: none;">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Certified Operator</span>
                    </div>
                    <select class="form-control select2bs4 selectUser" id="txt_edit_prod_runcard_cert_operator_visible" name="txt_edit_prod_runcard_cert_operator[]" multiple="multiple" disabled="true">
                      <option value=""> N/A </option>
                    </select>
                  </div>
                </div>
              </div> --}}

              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Operator</span>
                    </div>
                    <select class="form-control class-disabled select2bs4 selectUser" id="txt_edit_prod_runcard_operator" name="txt_edit_prod_runcard_operator[]" multiple="multiple" readonly disabled>
                      <option value=""> N/A </option>
                    </select>
                    <div class="input-group-append">
                      <button class="btn btn-info" type="button" title="Scan code" id="btn_scan_add_runcard_operator_code" disabled><i class="fa fa-qrcode"></i></button>
                      <button class="btn btn-danger" type="button" title="Remove Selection" id="btnRemoveSelection" onClick="removeSelection('txt_edit_prod_runcard_operator');"><i class="fa fa-undo"></i></button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Machine</span>
                    </div>
                    <select class="form-control class-disabled select2bs4 selectMachine" id="txt_edit_prod_runcard_station_machine" name="txt_edit_prod_runcard_station_machine[]" multiple="multiple" readonly disabled>
                      <option value="N/A"> N/A </option>
                    </select>
                    <div class="input-group-append">
                      <button class="btn btn-info" type="button" title="Scan code" id="btn_scan_machine_code" disabled><i class="fa fa-qrcode"></i></button>
                      <button class="btn btn-danger" type="button" title="Remove Selection" id="btnRemoveSelection" onClick="removeSelection('txt_edit_prod_runcard_station_machine');"><i class="fa fa-undo"></i></button>
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
                    <input type="text" class="form-control form-control-sm" id="txt_edit_prod_runcard_station_input" name="qty_input" min="0">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Output</span>
                    </div>
                    <input type="text" class="form-control form-control-sm" id="txt_edit_prod_runcard_station_output" name="qty_output" min="0" required>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">NG Qty</span>
                    </div>
                    <input type="text" class="form-control form-control-sm" id="txt_edit_prod_runcard_station_ng" name="qty_ng" min="0" value="0" readonly="true">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Magnification <i> (For Auto Insertion, Fist Visual & Final Visual)</i></span>
                    </div>
                    <input type="text" class="form-control form-control-sm" id="txt_edit_prod_runcard_station_magnification" name="magni" min="0" value="0">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Remarks</span>
                    </div>
                    <input type="text" class="form-control form-control-sm" id="txt_edit_prod_runcard_station_remarks" name="remarks">
                  </div>
                </div>
              </div>

              <div class="row" id="divReelLot" style="display: none;">
                <div class="col">
                  <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Reel Lot Number</span>
                    </div>
                    <input type="text" class="form-control form-control-sm" id="txt_reel_lot_number" name="reel_lot" required>
                  </div>
                     <div class="input-group input-group-sm mb-3">
                    <div class="input-group-prepend w-50">
                      <span class="input-group-text w-100" id="basic-addon1">Oldest Lot</span>
                    </div>
                    <input type="text" class="form-control form-control-sm" id="txt_oldest_lot" name="oldest_lot" minlength="6" maxlength="6" required>
                  </div>
                </div>
              </div>

              <div class="row" id="divGuaranteedLot" style="display: none;">
                <div class="col">
                  <div class="card">
                    <div class="card-body p-2">
                      <div class="input-group input-group-sm mb-3 justify-content-center" id="idDivGuaranteedLot">
                        <div class="input-group-prepend w-100">
                            <span class="input-group-text w-100 font-style text-wrap" id="basic-addon1"><h5>Guaranteed Lot (Containtment actions from in-process defects, OQC lot-out, internal & customer claim)</h5></span>
                        </div>
                        <input class="form-control-sm" type="radio" id="idGuaranteedLotWith" name="name_guaranteed_lot" value="1"> &nbsp;WITH &nbsp;&nbsp;
                        <input class="form-control-sm" type="radio" id="idGuaranteedLotWithout" name="name_guaranteed_lot" value="2"> &nbsp;WITHOUT
                      </div>
                      <div class="input-group input-group-sm mb-3">
                          <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100 font-style">Problem</span>
                          </div>
                          <input type="text" class="form-control form-control-sm" id="idProblem" name="problem" readonly="">
                      </div>

                      <div class="input-group input-group-sm mb-3">
                          <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100 font-style">Document No.</span>
                          </div>
                          <input type="text" class="form-control form-control-sm" id="idDocNum" name="document_number" readonly="">
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row" id="divPrintLot" style="display: none;">
                <div class="col">
                  <hr>
                  <div class="input-group input-group-sm">
                    <button type="button" class="btn btn-sm btn-primary ml-auto my-2" data-toggle="modal" data-target="#modalAddPrintLot" id=""><i class="fa fa-plus"></i> Add Print Lot</button>
                    <button type="button" class="btn btn-sm btn-danger my-2" id="btnRemoveLastPrintLot" ><i class="fa fa-times"></i> Remove Last Row</button>
                  </div>
                  <div class="table-responsive">
                    <table class="table table-sm table-bordered table-hover small w-100" id="tablePrintLots">
                      <thead>
                        <tr class="bg-light">
                          <th>Print Lot Number</th>
                          <th>Quantity</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col">
                  <div class="input-group input-group-sm">
                    <button type="button" class="btn btn-sm btn-primary ml-auto my-2" data-toggle="modal" data-target="#mdlAddMaterial" id="btnAddNewMaterial" disabled><i class="fa fa-plus"></i> Scan Material</button>
                    <button type="button" class="btn btn-sm btn-danger my-2" id="removeLastMaterial" disabled><i class="fa fa-times"></i> Remove Last Row</button>
                  </div>
                  <div class="table-responsive">
                    <table class="table table-sm table-bordered table-hover small" id="tbl_materials_for_scanning" style="min-width: 900px;">
                      <thead>
                        <tr class="bg-light">
                          <th>Material Code</th>
                          <th>Material Name</th>
                          <th>Lot No.</th>
                          <th>Type</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <hr>
              <div class="table-responsive">
                <div style="float: left;">
                  <label>Total No. of NG: <span id="pRCStatTotNoOfNG" style="color: green;">0</span></label>
                </div>
                <div style="float: right;">
                  <button type="button" id="btnAddMODTable" class="btn btn-sm btn-info" title="Add MOD"><i class="fa fa-plus"></i> Add MOD</button>
                </div>
                <br><br>
                <table class="table table-sm" id="tblEditProdRunStaMOD">
                  <thead>
                    <tr>
                      <th style="width: 55%;">Mode of Defect</th>
                      <th style="width: 15%;">QTY</th>
                      <th style="width: 20%;">Type of NG</th>
                      <th style="width: 10%;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>

            </form>
          </div>
          <div class="modal-footer justify-content-between">
            <input type="hidden" id="txt_prod_runcard_station_id_query" name="txt_prod_runcard_station_id_query" form="frm_edit_prod_runcard_station_details">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-success" form="frm_edit_prod_runcard_station_details" id="btn_save_prod_runcard_station_stations" disabled="false">Save</button>
          </div>
        </div>
      </div>
    </div>

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
              <span id="scanPOTransLotBody">Please scan the PO code.</span>
              <br>
              <br>
              <h1><i id="scanPOTransLotIcon" class="fa fa-qrcode fa-lg"></i></h1>
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
            <label style="float: left; font-size: 20px; color: green;">PO #: <span id="spanNGSummaryPoNo">--</span></label>
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

    {{-- Edit status -JD --}}
    <div class="modal fade" id="modalEditStatus" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Status</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body pt-0">
            <form id="form_edit_status">
              @csrf
              <div class="text-center text-secondary">
                <br>
                <h5 id="scanPOTransLotBody">Edit status to done?</h5>
                <br>
                <input type="hidden" id="status" value="7">
                <input type="hidden" id="prod_runcard_id">
              </div>

              <div class="modal-footer">
                <button type="submit" class="btn btn-primary my-1">Yes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="mdlAddMaterial">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title"><i class="fa fa-initial-icon"></i> Add Material</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="frmAddMaterial">
            <input type="hidden" name="issuance_id" id="issuance_id">

            <div class="modal-body">
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    {{-- <label>Lot No.</label>
                      <input type="text" class="form-control form-control-sm" name="lot_no" id="addProcess_lot_no" readonly="true">
                      <div class="input-group-append">
                        <button class="btn btn-info" type="button" title="Scan Mat'l Lot No" id="btnScanMaterialLotNo"><i class="fa fa-qrcode"></i></button>
                      </div> --}}

                      <label>Lot No.</label>
                      <div class="input-group input-group-sm mb-3">
                        <input type="text" class="form-control form-control-sm" name="lot_no" id="addProcess_lot_no" readonly="true">
                        <div class="input-group-append">
                          <button class="btn btn-info" type="button" title="Scan Mat'l Lot No" id="btnScanMaterialLotNo"><i class="fa fa-qrcode"></i></button>
                        </div>
                      </div>
                  </div>

                  <div class="form-group">
                    <label>Material Code</label>
                    <div class="input-group input-group-sm mb-3">
                      <input type="text" class="form-control" name="material_part_no" id="material_part_no" readonly="true">
                    </div>
                  </div>

                  <div class="form-group">
                    <label>Material Name</label>
                    <div class="input-group input-group-sm mb-3">
                      {{-- <input type="text" class="form-control form-control-sm" name="material_id" id="addEditSelMaterial" readonly> --}}
                      <input type="text" class="form-control" name="material_name" id="material_name" readonly>
                    </div>
                  </div>

                  <div class="form-group">
                    <label>Material Type</label>
                      <input type="text" class="form-control form-control-sm" id="addProcess_material_type_holder" readonly="true">
                      <input type="hidden" class="form-control form-control-sm" name="material_type" id="addProcess_material_type" readonly>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" id="btnAddMaterial" class="btn btn-primary"><i id="btnAddMaterialIcon" class="fa fa-check"></i> Add</button>
            </div>
          </form>
        </div>
      </div>
    </div>
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

    {{-- Modal For Reel Input --}}
    <div class="modal fade" id="modalInputReels" data-formid="" tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title"><i class="fa fa-initial-icon"></i> Input Reel</h4>

                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form id="formInputReel" autocomplete="off">
                  @csrf
                  <div class="modal-body">
                    <input type="hidden" id="txtruncardId" name="runcard_id">
                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend w-50">
                        <span class="input-group-text w-100">Reel Lot Number</span>
                      </div>
                      <input type="text" class="form-control form-control-sm" name="reel_lot_num" id="txtReelLotNo" required>
                      {{-- <div class="input-group-append">
                        <button class="btn btn-info" type="button" title="Scan Mat'l Lot No" id="btnScanMaterialLotNo"><i class="fa fa-qrcode"></i></button>
                      </div> --}}
                    </div>
                  </div>
                  <div class="modal-footer justify-content-between">
                    <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-default">Cancel</button>
                    <button class="btn btn-success" type="submit" id="btnSaveReel">Save</button>
                  </div>
                </form>
            </div>
        </div>
    </div>
    {{-- /Modal For Reel Input --}}

    {{-- MODAL FOR PRINTING  --}}
    <div class="modal fade" id="modalRuncardQrCode">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title"><i class="fas fa-print"></i> QR Code - Runcard</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <div class="row">
                      <!-- PO 1 -->
                      <div class="col-sm-12">

                      <center><img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(150)->margin(5)->errorCorrection('H')->generate('0')) !!}" id="img_barcode" style="max-width: 200px;"><br></center>
                      <div id="barCodeLabel"></div>
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="submit" id="btnPrintRuncardQr" class="btn btn-primary btn-sm"><i class="fa fa-print fa-xs"></i> Print</button>
                  <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
              </div>
          </div>
      </div>
    </div>

     {{-- MODAL FOR PRINTING  --}}
    <div class="modal fade" id="modalRuncardProcessQrCode">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title"><i class="fas fa-print"></i> QR Code - Runcard</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <div class="row">
                      <div class="col-sm-4 text-center">
                        <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(150)->margin(5)->errorCorrection('H')->generate('0')) !!}" id="img_qr_po_no_on_process" style="max-width: 10rem;"><br>
                        <div id="qrLabelFromProcessPoNumber"></div>
                      </div>
                      <div class="col-sm-4 text-center">
                        <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(150)->margin(5)->errorCorrection('H')->generate('0')) !!}" id="img_qr_reel_lot_nos_on_process" style="max-width: 10rem;"><br>
                        <div id="qrLabelFromProcessReelLotNo"></div>
                      </div>
                      <div class="col-sm-4 text-center">
                        <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(150)->margin(5)->errorCorrection('H')->generate('0')) !!}" id="img_qr_runcard_no_on_process" style="max-width: 10rem;"><br>
                        <div id="qrLabelFromProcessRuncardNo"></div>
                      </div>
                      
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="submit" id="btnPrintRuncardQrFromProcess" class="btn btn-primary btn-sm"><i class="fa fa-print fa-xs"></i> Print</button>
                  <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
              </div>
          </div>
      </div>
    </div>
     {{-- MODAL FOR PRINTING  --}}
    <div class="modal fade" id="modalAddPrintLot">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title"><i class="fas fa-info-circle"></i> Print Lots</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <form id="formPrintLot">
                <div class="modal-body">
                    <div class="row mb-2">
                      <div class="col-sm-12">
                        <div class="input-group input-group-sm">
                          <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100 font-style">Print Lot Number</span>
                          </div>
                          <input type="text" class="form-control form-control-sm" id="txtPrintLotNo">
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="input-group input-group-sm">
                          <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100 font-style">Print Lot Quantity</span>
                          </div>
                          <input type="number" class="form-control form-control-sm" id="txtPrintLotQty" min="0">
                        </div>
                      </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btnSavePrintLot" class="btn btn-primary btn-sm">Add</button>
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                </div>
              </form>
          </div>
      </div>
    </div>
    <textarea id="txt_str" style="opacity: 0.01"></textarea>

      <div class="modal fade" id="modalCreateGuaranteedLot">
        <div class="modal-dialog modal-xl-custom">
          <div class="modal-content">
              <div class="modal-header bg-dark">
                  <h5 class="modal-title"><strong>Update Guaranteed Lot</strong></h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                <div class="card p-3">
                  <form id="formEditGuaranteedLot" method="post">
                    @csrf
                    <div class="row" id="divEditGuaranteedLot">
                      <input type="hidden" id="txtProdnRuncardId" name="production_runcard_id">
                      <div class="col">
                        <div class="card">
                          <div class="card-body p-2">
                            <div class="input-group input-group-sm mb-3 justify-content-center" id="idDivEditGuaranteedLot">
                              <div class="input-group-prepend w-100">
                                  <span class="input-group-text w-100 font-style text-wrap" id="basic-addon1"><h5>Guaranteed Lot (Containtment actions from in-process defects, OQC lot-out, internal & customer claim)</h5></span>
                              </div>
                              <input class="form-control-sm radioButton" type="radio" id="idEditGuaranteedLotWith" name="name_guaranteed_lot" value="1"> &nbsp;WITH &nbsp;&nbsp;
                              <input class="form-control-sm radioButton" type="radio" id="idEditGuaranteedLotWithout" name="name_guaranteed_lot" value="2"> &nbsp;WITHOUT
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100 font-style">Problem</span>
                                </div>
                                <input type="text" class="form-control form-control-sm glPDN" id="idEditProblem" name="problem" readonly="">
                            </div>

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100 font-style">Document No.</span>
                                </div>
                                <input type="text" class="form-control form-control-sm glPDN" id="idEditDocNum" name="document_number" readonly="">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card-footer">
                      <button type="submit" class="btn btn-dark float-right" id="btnSaveEditGuaranteedLot">
                          <i class="fa fa-save" aria-hidden="true"></i>
                          Save
                      </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /.content-wrapper -->
  @endsection

  @section('js_content')

  <!-- <script src="{{ URL::asset('public/template/plugins/jquery-ui/jquery-ui.min.js') }}"></script> -->
  <script src="{{ URL::asset('public/template/plugins/qz-print-free_1.8.0_src/qz-print/js/deployJava.js') }}"></script>
  <script type="text/javascript">
    let _token = "{{ csrf_token() }}";
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
    let viewMatKitAction = 1;
    let viewMatKitActionLotNo = null;
    let viewSakidashiAction = 1;
    let viewSakidashiActionLotNo = null;
    let viewEmbossAction = 1;
    let viewEmbossActionLotNo = null;
    let saveMatKitLotIssuanceId = null;
    let saveMatKitLotItem = null;
    let saveMatKitLotItemDesc = null;

    let saveSakidashiLotIssuanceId = null;
    let saveSakidashiLotItem = null;
    let saveSakidashiLotItemDesc = null;

    let saveEmbossLotIssuanceId = null;
    let saveEmbossLotItem = null;
    let saveEmbossLotItemDesc = null;

    let materialArray = [];
    let splittedMachine = [];
    let splittedEmpId = [];
    let dtMaterials;
    let runcardQRCode;
    let processStationPrintingQrData;
    let printLotArray = [];
    let dtPrintLots;

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
         var name = "ZDesigner ZT220-200dpi";//calls the findprinter function passing the string "ZPL" - ppc
         // var name = "ZDesigner ZT230-200dpi ZPL ZT220";//calls the findprinter function passing the string "ZPL" - packing


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

    getPPTSDropdowns();

    $(document).ready(function () {
      //-----
      //-----
      //-----
      $('#tbl_prod_runcard').on('click','.btnPrintRuncardC3Label',function(){
        $('#txt_str').val('');
        if( $('#txt_str').attr('data-java_enabled')==1 ){
          var data = {
            'txt_runcard_id'  : $(this).attr('runcard-id'),
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

      /**
       * Edit status
       * -JD
      */
      $('#tbl_prod_runcard').on('click','.btnEditStatus',function(){
        let txt_runcard_id= $(this).attr('runcard-id');
        console.log('asd', txt_runcard_id);

        $('#prod_runcard_id').val(txt_runcard_id);
        $('#modalEditStatus').modal('show');
        // editStatus(txt_runcard_id);
      });

      $('#form_edit_status').submit(function(e){
        e.preventDefault();

        $.ajax({
          'data'      : $(this).serialize() +'&txt_runcard_id=' + $("#prod_runcard_id").val(),
          'type'      : 'get',
          'dataType'  : 'json',
          'url'       : 'edit_status',
          success     : function(data){
            $('#modalEditStatus').modal('hide');
            $('#mdl_alert #mdl_alert_title').html(data['title']);
            $('#mdl_alert #mdl_alert_body').html(data['body']);
            $('#mdl_alert').modal('show');
            dt_prod_runcard.draw();
          }
        });
      });



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



      $("#tblEditProdRunStaMOD").on('keyup', '.txtEditProdRunStaMODQty', function(){
        // console.log('wew');
        let totalNoOfMOD = 0;

        if($(this).val() > $("#txt_edit_prod_runcard_station_ng").val()){
          // toastr.warning('NG Qty limitation reached!');
          $("#pRCStatTotNoOfNG").css({color: 'red'});
          $("#btn_save_prod_runcard_station_stations").prop('disabled', true);
        }
        else{
          $('#tblEditProdRunStaMOD .txtEditProdRunStaMODQty').each(function(i, obj) {
            if($(this).val() == null || $(this).val() == ""){
            }
            else{
              totalNoOfMOD += parseFloat($(this).val());
            }
          });

          setTimeout(function(){
            console.log('totalNoOfMOD', totalNoOfMOD);
            if($("#txt_edit_prod_runcard_station_ng").val() != totalNoOfMOD){
              toastr.warning('MOD NG Qty not Tally!');
              $("#pRCStatTotNoOfNG").css({color: 'red'});
              $("#btn_save_prod_runcard_station_stations").prop('disabled', true);
            }
            else{
              $("#pRCStatTotNoOfNG").css({color: 'green'});
              $("#btn_save_prod_runcard_station_stations").prop('disabled', false);
            }
          }, 300);
          
          $("#pRCStatTotNoOfNG").text(totalNoOfMOD);
        }

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
            { "data" : "reel_lot_no"},
            { "data" : "lot_qty" },
            { "data" : "last_runcard_po_qty" },

            { "data" : "total_no_of_ng" },
            // {
            //     name: 'prod_runcard_station_many_details',
            //     data: 'prod_runcard_station_many_details',
            //     sortable: false,
            //     searchable: false,
            //     render: function (data) {
            //         var result = '';


            //         // Get Emboss
            //         if(data.length > 0){
            //           for(let index = data.length - 1; index >= 0; index--){
            //             // Get Not Emboss
            //             if(data[index]['has_emboss'] == 1){
            //               // result += '<span class="badge badge-pill badge-secondary">' + data[index]['step_num'] + ' - ' + data[index]['qty_output'] + '</span>';

            //               result = data[index]['qty_output'];
            //               break;
            //             }
            //             // else{
            //             //   result += '<span class="badge badge-pill badge-secondary">' + data[index]['step_num'] + ' - ' + data[index]['qty_output'] + '</span>';

            //             //   if(index >= 0){
            //             //     result += '<br>';
            //             //   }
            //             // }
            //           }
            //         }
            //         else{
            //           result = null;
            //         }

            //         return result;
            //     }
            // },
            // { "data" : "total_no_of_emboss_ng" },
            // { "data" : "prod_runcard_station_many_details.0.qty_ng" },

            // total_no_of_ng
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
          "columnDefs": [
            {
              "targets": [3, 4, 5, 6],
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
            // GetSakidashiIssuance();
            // if(api.rows().count() <= 0){
            //   GetMaterialKitting();
            // }

            if(api.rows().count() > 0){
              totalNoOfOk = 0;
              totalNoOfNG = 0;
              // let data = api.data();
              // let recount_ok = data.recount_ok;
              // let recount_ng = data.recount_ng;

              api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                  let data = api.row(i).data();

                  if(data['last_runcard_po_qty'] != null){
                    totalNoOfOk += parseInt(data['last_runcard_po_qty']);
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
              totalNoOfOk = 0;
              totalNoOfNG = 0;
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
          "searching"  : false,
          "info"       : false,
          "pagination" : false,
          "ordering"   : false,
          "ajax" : {
            url: "view_manual_material_kitting_by_runcard",
            data: function (param){
                param.po_number = currentPoNo;
                param.runcard_status = $("#txt_prod_runcard_status").val();
                param.prod_runcard_id_query = $("#txt_prod_runcard_id_query").val();
                param.action = viewMatKitAction;
                param.lot_no = viewMatKitActionLotNo;
            }
          },
          bAutoWidth: false,
          "columns":[
            // { "data" : "raw_action", orderable:false, searchable:false, visible:false },
            // { "data" : "raw_status", orderable:false, searchable:false },
            // { "data" : "lot_no", orderable:true, searchable:true },
            // { "data" : "item_desc", orderable:true, searchable:true },

            // { "data" : "item", orderable:true, searchable:true },
            // { "data" : "kit_issuance.assessment", orderable:true, searchable:true }, // here
            // { "data" : "kit_issuance.issuance_no", orderable:false, searchable:true },
            // { "data" : "lot_qty_to_complete" },
            // { "data" : "runcard_used_qty" },


            { "data" : "step_num" },
            {
              "data" : "prod_runcard_station_material_details",
              render: function (data) {
                    var result = '';
                    if(data.length > 0){
                      for(let x = 0; x < data.length; x++){
                        result += '<span class="badge badge-pill badge-secondary">' + data[x]['item_desc'] + '</span>';

                        if(x <= parseInt(data.length) - 2){
                          result += '<br>';
                        }
                      }
                    }
                    else{
                      result = '<span class="badge badge-pill badge-secondary">N/A</span>';
                    }
                    return result;
                }
            },
            {
              "data" : "prod_runcard_station_material_details",
              render: function (data) {

                    var result = '';
                    if(data.length > 0){
                      for(let x = 0; x < data.length; x++){
                        result += '<span class="badge badge-pill badge-secondary">' + data[x]['lot_no'] + '</span>';

                        if(x <= parseInt(data.length) - 2){
                          result += '<br>';
                        }
                      }
                    }
                    else{
                      result = '<span class="badge badge-pill badge-secondary">N/A</span>';
                    }
                    return result;
                }
            },
            {
              "data" : "prod_runcard_station_material_details",
              render: function (data) {

                    var result = '';
                    if(data.length > 0){
                      for(let x = 0; x < data.length; x++){
                        if(data[x]['tbl_wbs'] == 1){
                          result += '<span class="badge badge-pill badge-secondary"> Material Kitting & Issuance</span>';
                        }
                        else if(data[x]['tbl_wbs'] == 2){
                          result += '<span class="badge badge-pill badge-secondary"> Sakidashi Issuance</span>';
                        }
                        else if(data[x]['tbl_wbs'] == 3){
                          result += '<span class="badge badge-pill badge-secondary"> Pre Assembly</span>';
                        }

                        if(x <= parseInt(data.length) - 2){
                          result += '<br>';
                        }
                      }
                    }
                    else{
                      result = '<span class="badge badge-pill badge-secondary">N/A</span>';
                    }
                    return result;
                }
            },
          ],
          "columnDefs": [
            {
              "targets": [1, 2, 3],
              "data": null,
              "defaultContent": "N/A"
            },
            { className: "text-center", "targets": "_all" }
          ],
          paging: false,
          info: false,
          // searching: false,
          pageLength: -1,
          // order: [[2, "asc"]],
          // "rowCallback": function(row,data,index ){
          //   $('#txt_po_number_lbl').val( data['po'] );
          //   // $('#txt_assessment_no').val( data['assessment'] );
          //   // console.log(data['kit_issuance']['assessment']);
          //   if($("#txt_prod_runcard_status").val() <= 2){
          //     // $("#txt_assessment_no").val(data['kit_issuance']['assessment']);
          //     if(data['kit_issuance'] != null){
          //       assessment = data['kit_issuance']['assessment'];

          //       if(assessment != null){
          //         $("#txt_assessment_no").val(assessment);
          //       }
          //     }
          //     else{
          //       $("#txt_assessment_no").val('N/A');
          //     }
          //   }
          // },
          // "drawCallback": function(row,data,index ){
          //   $(".chkSelMatKitIssue").each(function(index){
          //       if(arrSelectedMaterial.includes($(this).attr('material-kit-issue-id'))){
          //         $(this).attr('checked', 'checked');
          //       }
          //   });
          //   // arrSelectedMaterial
          //   arrSelectedMaterial = [];
          //   $('#tbl_materials tbody .col_lot_id').each(function(){
          //     // if(!$(this).attr('disabled')){
          //     //   // console.log('disabled');
          //     //   arr_substations[ctr] = {
          //     //       'step' : $(this).closest('td').find('.col_station_step').val(),
          //     //       'station' : $(this).closest('td').find('.col_station_id').val(),
          //     //       'substation' : $(this).closest('td').find('.col_sub_station_id').val(),
          //     //     };
          //     // }
          //     // console.log($(this).val());
          //     arrSelectedMaterial.push($(this).val());
          //   });
          //   if(arrSelectedMaterial.length > 0 || arrSelectedSakidashi.length > 0){
          //     // $("#btnSaveSelectedMatSak").prop('disabled', false);
          //     $("#spanNoOfSelectedMatSak").text("(" + (parseInt(arrSelectedMaterial.length) + parseInt(arrSelectedSakidashi.length)) + ")");
          //   }
          //   else{
          //     // $("#btnSaveSelectedMatSak").prop('disabled', true);
          //     $("#spanNoOfSelectedMatSak").text("");
          //   }

          //   if(hasProdMatSakList){
          //     // $("#btnSaveSelectedMatSak").prop('disabled', true);
          //   }
          //   else{
          //     // $("#btnSaveSelectedMatSak").prop('disabled', false);
          //   }
          //   // console.log(arrSelectedMaterial);
          // },
      });//end of DataTable

      dt_sakidashi      = $('#tbl_sakidashi').DataTable({
          "processing" : true,
          "serverSide" : true,
          "ajax" : {
            url: "view_manual_sakidashi_by_runcard",
            data: function (param){
                param.po_number = currentPoNo;
                param.runcard_status = $("#txt_prod_runcard_status").val();
                param.sakidashi_list = arrSelectedSakidashi;
                param.material_kit_list = arrSelectedMaterial;
                param.prod_runcard_id_query = $("#txt_prod_runcard_id_query").val();
                param.lot_no = sakidashiIssuanceLotNo;
                // param.control_no = sakidashiCtrlNo;
                param.has_mat_sak_list = hasProdMatSakList;
                param.action = viewSakidashiAction;
                param.lot_no = viewSakidashiActionLotNo;
            }
          },
          bAutoWidth: false,
          paging: true,
          "columns":[
            // { "data" : "action", orderable:false, searchable:false },
            { "data" : "btn_save_material", orderable:false, searchable:false, visible:true },
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
            url: "view_manual_emboss_by_runcard",
            data: function (param){
                // param.po_number = currentPoNo;
                // param.runcard_status = $("#txt_prod_runcard_status").val();
                // param.emboss_kit_list = arrSelectedEmboss;
                // param.prod_runcard_id_query = $("#txt_prod_runcard_id_query").val();
                // param.lot_no = embossIssuanceLotNo;
                // // param.control_no = sakidashiCtrlNo;
                // param.has_mat_sak_list = hasProdMatSakList;
                // param.require_oqc_before_emboss = $("#txt_prod_runcard_require_oqc_before_emboss").val();

                param.po_number = currentPoNo;
                param.runcard_status = $("#txt_prod_runcard_status").val();
                param.emboss_kit_list = arrSelectedEmboss;
                param.prod_runcard_id_query = $("#txt_prod_runcard_id_query").val();
                param.lot_no = embossIssuanceLotNo;
                // param.control_no = sakidashiCtrlNo;
                param.has_mat_sak_list = hasProdMatSakList;
                param.action = viewEmbossAction;
                param.lot_no = viewEmbossActionLotNo;
            }
          },
          bAutoWidth: false,
          paging: true,
          "columns":[
            // { "data" : "action", orderable:false, searchable:false },
            { "data" : "btn_save_material", orderable:false, searchable:false },
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
            url: "view_prod_runcard_stations",
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
            {
                name: 'prod_runcard_station_material_details',
                data: 'prod_runcard_station_material_details',
                sortable: false,
                searchable: false,
                render: function (data) {
                  // console.log(data);
                    var result = '';
                    if(data.length > 0){
                      for(let index = 0; index < data.length; index++){
                        result += '<span class="badge badge-pill badge-secondary">' + data[index]['item_desc'] + '</span>';

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
            // { "data" : "operators_info" , orderable:false, searchable:false},
            {
                name: 'prod_runcard_station_operator_details',
                data: 'prod_runcard_station_operator_details',
                sortable: false,
                searchable: false,
                render: function (data) {
                  // console.log(data);
                    var result = '';
                    if(data.length > 0){
                      for(let index = 0; index < data.length; index++){
                        result += '<span class="badge badge-pill badge-secondary">' + data[index]['operator_info']['name'] + '</span>';

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
            // { "data" : "machine_info.name" , orderable:false, searchable:false},
            // { "data" : "machines_info" , orderable:false, searchable:false},
            {
                name: 'prod_runcard_station_machine_details',
                data: 'prod_runcard_station_machine_details',
                sortable: false,
                searchable: false,
                render: function (data) {
                  // console.log(data);
                    var result = '';
                    if(data.length > 0){
                      for(let index = 0; index < data.length; index++){
                        result += '<span class="badge badge-pill badge-secondary">' + data[index]['machine_info']['name'] + '</span>';

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
                  "targets": [4, 5, 6, 7, 8, 9, 10, 11, 12],
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
                    let groupRowClass = 'bg-info';

                    if($("#sel_runcard_type").val() == 1){
                      groupRowClass = 'bg-warning';
                    }

                      $(rows).eq( i ).before(
                          '<tr class="group ' + groupRowClass + '"><td colspan="11" style="text-align:center;"><b>' + station_name + '</b></td></tr>'
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
            { "data" : "mod_qty" , orderable:true, searchable:false},
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

      $('#mdl_employee_number_scanner').on('shown.bs.modal', function (e) {
          $('#txt_employee_number_scanner').focus();

      });

      $('#txt_employee_number_scanner').on('keyup', function(e){
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

              case 'saveDoneRuncard':
                saveDoneRuncard($('#frm_edit_material_details'), $("#txt_employee_number_scanner").val());
              break;
              default:
              break;
            }
          }
        }
      })
      $('#mdl_qrcode_scanner').on('shown.bs.modal', function (e) {
        $('#txt_qrcode_scanner').focus();
          // if( e.keyCode == 13 ){
          //   $('#mdl_qrcode_scanner').modal('hide');
          //   // alert($("#txt_qrcode_scanner").val());
          //   var formid = $("#mdl_qrcode_scanner").attr('data-formid');

          //   if ( ( formid ).indexOf('#') > -1){
          //     $( formid ).submit();
          //   }
          //   else{
          //     switch( formid ){
          //       case 'fn_scan_machine_code':
          //         var val = $('#txt_qrcode_scanner').val();
          //         // with this
          //         var val = $('#txt_qrcode_scanner').val();

          //         if ($('#txt_edit_prod_runcard_station_machine').find("option[data-code='" + val + "']").length) {
          //           let scannedUserId = $('#txt_edit_prod_runcard_station_machine').find("option[data-code='" + val + "']").val();

          //           let assignedMachine = $("#txt_edit_prod_runcard_station_assigned_machine").val();

          //           if(assignedMachine.includes(scannedUserId)){
          //             // toastr.warning('Certified!');
          //             $('#txt_edit_prod_runcard_station_machine option[data-code="'+val+'"]').prop('selected', true).trigger('change');
          //           }
          //           else{
          //             toastr.warning('Not Assigned!');
          //           }

          //         }
          //         else{
          //           toastr.warning('Invalid Machine!');
          //         }
          //         $('#txt_qrcode_scanner').val("");
          //       break;

          //       case 'fn_scan_runcard_operator_code':
          //         var val = $('#txt_qrcode_scanner').val();
          //         let selectedProcess = $('#txt_edit_prod_runcard_substation').find(':selected').attr('data-arr-emp-id');
          //         let splittedEmpId = selectedProcess.split(',');

          //         console.log(splittedEmpId);
          //         if ($('#txt_edit_prod_runcard_operator').find("option[data-code='" + val + "']")) {

          //           console.log(jQuery.inArray(val.toUpperCase(), splittedEmpId));
          //           if(jQuery.inArray(val.toUpperCase(), splittedEmpId) != -1){
          //             // toastr.warning('Certified!');
          //             $('#txt_edit_prod_runcard_operator option[data-code="'+val+'"]').prop('selected', true).trigger('change');
          //           }
          //           else{
          //             toastr.warning('Not Certified!');
          //           }

          //         }
          //         else{
          //           toastr.warning('Invalid User!');
          //         }
          //         $('#txt_qrcode_scanner').val("");
          //       break;

          //       case 'fn_scan_material_kitting':
          //         var val = $('#txt_qrcode_scanner').val();
          //       break;

          //       case 'fn_scan_sakidashi':
          //         var val = $('#txt_qrcode_scanner').val();
          //       break;

          //       case 'fn_scan_emboss':
          //         var val = $('#txt_qrcode_scanner').val();
          //       break;

          //       default:
          //       break;
          //     }
          //   }
          // }//key
      });

      $('#txt_edit_prod_runcard_substation').on('change', function(){
        $('#btn_scan_add_runcard_operator_code').prop('disabled', false);
        $('#btn_scan_machine_code').prop('disabled', false);
        $('#btnAddNewMaterial').prop('disabled', false);

        let selectedProcessMachine = $('#txt_edit_prod_runcard_substation').find(':selected').attr('data-arr-machine');
        $('#process_flow_id').val($('#txt_edit_prod_runcard_substation').find(':selected').attr('data-mat-process-id'));
        $('#txt_prod_runcard_station_id_query').val($('#txt_edit_prod_runcard_substation').find(":selected").attr('data-mat-process-station-id'));

        console.log('selectedProcessMachine', selectedProcessMachine);
        if (selectedProcessMachine != '' && selectedProcessMachine != undefined) {
          console.log('split');

          splittedMachine = selectedProcessMachine.split(',');
        }

        let selectedProcessEmployee = $('#txt_edit_prod_runcard_substation').find(':selected').attr('data-arr-emp-id');
        console.log('splittedEmpId', splittedEmpId);
        if (selectedProcessEmployee) {
          console.log('split1');

          splittedEmpId = selectedProcessEmployee.split(',');

        }

        /**
         * This script will determine if process had the following inputs to be shown
        */
        if($('#txt_edit_prod_runcard_substation').find(':selected').attr('data-mat-process-has-print-input') == 1){
          $('#divPrintLot').css('display', '');
        }
        else{
          $('#divPrintLot').css('display', 'none');
        }
        if($('#txt_edit_prod_runcard_substation').find(':selected').attr('data-mat-process-has-reel-input') == 1){
          $('#divReelLot').css('display', '');
        }
        else{
          $('#divReelLot').css('display', 'none');
        }
        if($('#txt_edit_prod_runcard_substation').find(':selected').attr('data-mat-process-has-guaranteed-lot') == 1){
          $('#divGuaranteedLot').css('display', '');
        }
        else{
          $('#divGuaranteedLot').css('display', 'none');
        }
      })

      $('#txt_qrcode_scanner').on('keyup', function(e){
        // console.log('object');
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
                  var val = $('#txt_qrcode_scanner').val().toUpperCase();

                  console.log('val', val);
                  console.log('fn_scan_machine_code splittedMachine', splittedMachine);


                  if ($('#txt_edit_prod_runcard_station_machine').find("option[data-code='" + val + "']").length) {
                    let scannedStationMachine = $('#txt_edit_prod_runcard_station_machine').find("option[data-code='" + val + "']").val();

                    console.log('scannedStationMachine', scannedStationMachine);
                    if(jQuery.inArray(scannedStationMachine, splittedMachine) != -1){
                      console.log('if');
                      console.log('scannedStationMachine', scannedStationMachine);
                      $('#txt_edit_prod_runcard_station_machine option[data-code="'+val+'"]').prop('selected', true).trigger('change');
                      // $('#txt_edit_prod_runcard_station_machine').val(scannedUserId).trigger('change');
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
                  // let selectedProcessEmployee = $('#txt_edit_prod_runcard_substation').find(':selected').attr('data-arr-emp-id');
                  // let splittedEmpId = selectedProcessEmployee.split(',');

                  console.log('splittedEmpId', splittedEmpId);
                  if ($('#txt_edit_prod_runcard_operator').find("option[data-code='" + val + "']")) {

                    console.log(jQuery.inArray(val.toUpperCase(), splittedEmpId));
                    if(jQuery.inArray(val.toUpperCase(), splittedEmpId) != -1){
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

                case 'fn_scan_materials':
                  var val = $(this).val().toUpperCase();

                  console.log('scanned material lot no: ', val);
                  getScannedMaterialDetails(val);

                break;

                default:
                break;
              }
            }
            $('#txt_qrcode_scanner').val("");

          }//
      })


      $(document).on('keypress',function(e){
        // if( ($("#mdl_employee_number_scanner").data('bs.modal') || {})._isShown ){
        //   $('#txt_employee_number_scanner').focus();
        //   if( e.keyCode == 13 && $('#txt_employee_number_scanner').val() !='' && ($('#txt_employee_number_scanner').val().length >= 4) ){
        //     $('#mdl_employee_number_scanner').modal('hide');
        //     var formid = $("#mdl_employee_number_scanner").attr('data-formid');

        //     if ( ( formid ).indexOf('#') > -1){
        //       $( formid ).submit();
        //     }
        //     else{
        //       switch( formid ){
        //         case 'save_setup_stations':
        //         save_setup_stations();
        //         break;
        //         case 'save_approve_qc':
        //         save_approve_qc();
        //         break;
        //         case 'save_approve_prod':
        //         save_approve_prod();
        //         break;
        //         case 'SaveProdMaterialList':
        //           if(arrSelectedMaterial.length > 0 || arrSelectedSakidashi > 0){
        //             SaveProductMaterialList(arrSelectedMaterial, arrSelectedSakidashi, "{{ csrf_token() }}", $("#txt_employee_number_scanner").val(), $("#txt_prod_runcard_id_query").val());
        //           }
        //           else{
        //             toastr.warning('No Materials to be saved.');
        //           }
        //         break;
        //         case 'SaveProdEmbossMaterialList':
        //           if(arrSelectedEmboss.length > 0){
        //             SaveProductEmbossMaterialList(arrSelectedEmboss, "{{ csrf_token() }}", $("#txt_employee_number_scanner").val(), $("#txt_prod_runcard_id_query").val());
        //           }
        //           else{
        //             toastr.warning('No Materials to be saved.');
        //           }
        //         break;
        //         default:
        //         break;
        //       }
        //     }
        //   }
        // }
        // else if( ($("#mdl_qrcode_scanner").data('bs.modal') || {})._isShown ){
        //   $('#txt_qrcode_scanner').focus();
        //   if( e.keyCode == 13 ){
        //     $('#mdl_qrcode_scanner').modal('hide');
        //     // alert($("#txt_qrcode_scanner").val());
        //     var formid = $("#mdl_qrcode_scanner").attr('data-formid');

        //     if ( ( formid ).indexOf('#') > -1){
        //       $( formid ).submit();
        //     }
        //     else{
        //       switch( formid ){
        //         case 'fn_scan_machine_code':
        //           var val = $('#txt_qrcode_scanner').val();
        //           // with this
        //           var val = $('#txt_qrcode_scanner').val();

        //           if ($('#txt_edit_prod_runcard_station_machine').find("option[data-code='" + val + "']").length) {
        //             let scannedUserId = $('#txt_edit_prod_runcard_station_machine').find("option[data-code='" + val + "']").val();

        //             let assignedMachine = $("#txt_edit_prod_runcard_station_assigned_machine").val();

        //             if(assignedMachine.includes(scannedUserId)){
        //               // toastr.warning('Certified!');
        //               $('#txt_edit_prod_runcard_station_machine option[data-code="'+val+'"]').prop('selected', true).trigger('change');
        //             }
        //             else{
        //               toastr.warning('Not Assigned!');
        //             }

        //           }
        //           else{
        //             toastr.warning('Invalid Machine!');
        //           }
        //           $('#txt_qrcode_scanner').val("");
        //         break;

        //         case 'fn_scan_runcard_operator_code':
        //           var val = $('#txt_qrcode_scanner').val();
        //           let selectedProcess = $('#txt_edit_prod_runcard_substation').find(':selected').attr('data-arr-emp-id');
        //           let splittedEmpId = selectedProcess.split(',');

        //           console.log(splittedEmpId);
        //           if ($('#txt_edit_prod_runcard_operator').find("option[data-code='" + val + "']")) {

        //             console.log(jQuery.inArray(val.toUpperCase(), splittedEmpId));
        //             if(jQuery.inArray(val.toUpperCase(), splittedEmpId) != -1){
        //               // toastr.warning('Certified!');
        //               $('#txt_edit_prod_runcard_operator option[data-code="'+val+'"]').prop('selected', true).trigger('change');
        //             }
        //             else{
        //               toastr.warning('Not Certified!');
        //             }

        //           }
        //           else{
        //             toastr.warning('Invalid User!');
        //           }
        //           $('#txt_qrcode_scanner').val("");
        //         break;

        //         case 'fn_scan_material_kitting':
        //           var val = $('#txt_qrcode_scanner').val();
        //         break;

        //         case 'fn_scan_sakidashi':
        //           var val = $('#txt_qrcode_scanner').val();
        //         break;

        //         case 'fn_scan_emboss':
        //           var val = $('#txt_qrcode_scanner').val();
        //         break;

        //         default:
        //         break;
        //       }
        //     }
        //   }//key
        // }
        $('#txtSearchPoTransLotNo').on('keyup', function(e){
          setTimeout(() => {
              $(this).val('');
          }, 500);
        });
        // SCAN PO, LOT, TRANSFER SLIP CODE
        if( ($("#modalScanPOTransLotCode").data('bs.modal') || {})._isShown ){
          $('#txtSearchPoTransLotNo').focus();
          if( e.keyCode == 13 && $('#txtSearchPoTransLotNo').val() !='' && ($('#txtSearchPoTransLotNo').val().length >= 4) ){
            // $('#modalScanPOTransLotCode').modal('hide');
            $("#scanPOTransLotIcon").removeClass('fa-spinner fa-pulse');
            $("#scanPOTransLotIcon").addClass('fa-qrcode');

            var formid = $("#modalScanPOTransLotCode").attr('data-formid');

            if ( ( formid ).indexOf('#') > -1){
              $( formid ).submit();
            }
            else{
              let scannedValue = "";
              switch( formid ){
                case 'search_po_number':
                  currentPoNo = $("#txtSearchPoTransLotNo").val();
                  console.log('currentPoNo ', currentPoNo);
                  dt_prod_runcard.draw();
                  $('#tbl_materials tbody tr').removeClass('table-active');
                  $('#txt_po_number_lbl').val('');
                  $('#txt_device_name_lbl').val('');
                  $('#txt_device_code_lbl').val('');
                  $('#txt_po_qty_lbl').val('');
                  $('#txt_device_name').val('');
                  $(this).val('');
                  $(this).focus();
                  $("#modalScanPOTransLotCode").modal('hide');
                break;

                case 'scan_mat_kit':
                  scannedValue = $("#txtSearchPoTransLotNo").val();
                  // alert(scannedValue);
                  $(this).focus();
                  viewMatKitAction = 1;
                  viewMatKitActionLotNo = null;
                  ScanProdRuncardMaterialKiting(scannedValue);
                  $("#txtScannedMatKitLot").val(scannedValue.trim());
                break;

                case 'scan_sakidashi':
                  scannedValue = $("#txtSearchPoTransLotNo").val();
                  // alert(scannedValue);
                  $(this).focus();
                  ScanProdRuncardSakidashiIssuance(scannedValue);
                  $("#txtScannedSakidashiLot").val(scannedValue.trim());
                break;

                case 'scan_emboss':
                  scannedValue = $("#txtSearchPoTransLotNo").val();
                  // alert(scannedValue);
                  $(this).focus();
                  ScanProdRuncardEmboss(scannedValue);
                  $("#txtScannedEmbossLot").val(scannedValue.trim());
                break;

                case 'scan_employee_no':
                  scannedValue = $("#txtSearchPoTransLotNo").val();
                  // alert(scannedValue);
                  $(this).focus();
                  ScanProdRuncardEmployee(scannedValue);
                  $("#txtScannedEmployeeNo").val(scannedValue.trim());
                break;

                case 'search_mat_kit':
                  scannedValue = $("#txtSearchPoTransLotNo").val();
                  // alert(scannedValue);
                  $(this).focus();
                  $("#txtSearchedMatKitLot").val('');
                  viewMatKitAction = 2;
                  viewMatKitActionLotNo = scannedValue;
                  $("#txtSearchedMatKitLot").val(scannedValue.trim());
                  dt_materials.draw();
                  $("#modalScanPOTransLotCode").modal('hide');
                break;

                case 'search_sakidashi':
                  scannedValue = $("#txtSearchPoTransLotNo").val();
                  // alert(scannedValue);
                  $(this).focus();
                  $("#txtSearchedSakidashiLotNo").val('');
                  viewSakidashiAction = 2;
                  viewSakidashiActionLotNo = scannedValue;
                  $("#txtSearchedSakidashiLot").val(scannedValue.trim());
                  dt_sakidashi.draw();
                  $("#modalScanPOTransLotCode").modal('hide');
                break;

                case 'search_emboss':
                  scannedValue = $("#txtSearchPoTransLotNo").val();
                  // alert(scannedValue);
                  $(this).focus();
                  $("#txtSearchedEmbossLotNo").val('');
                  viewEmbossAction = 2;
                  viewEmbossActionLotNo = scannedValue;
                  $("#txtSearchedEmbossLot").val(scannedValue.trim());
                  dt_emboss.draw();
                  $("#modalScanPOTransLotCode").modal('hide');
                break;

                case 'save_mat_kit':
                  scannedValue = $("#txtSearchPoTransLotNo").val();
                  SaveSakidashiLotIssuanceId(saveMatKitLotIssuanceId, saveMatKitLotItem, saveMatKitLotItemDesc, scannedValue);
                  saveMatKitLotIssuanceId = null;
                  saveMatKitLotItem = null;
                  saveMatKitLotItemDesc = null;
                break;

                case 'save_sakidashi_lot_issuance':
                  if($("#txt_prod_runcard_id_query").val() == null || $("#txt_prod_runcard_id_query").val() == ''){
                    toastr.error('No Runcard Details.');
                  }
                  else{
                    scannedValue = $("#txtSearchPoTransLotNo").val();
                    SaveSakidashiLotIssuanceId(saveSakidashiLotIssuanceId, saveSakidashiLotItem, saveSakidashiLotItemDesc, scannedValue);
                    saveSakidashiLotIssuanceId = null;
                    saveSakidashiLotItem = null;
                    saveSakidashiLotItemDesc = null;
                  }
                break;

                case 'save_emboss_lot_issuance':
                  if($("#txt_prod_runcard_id_query").val() == null || $("#txt_prod_runcard_id_query").val() == ''){
                    toastr.error('No Runcard Details.');
                  }
                  else{
                    scannedValue = $("#txtSearchPoTransLotNo").val();
                    SaveEmbossLotIssuanceId(saveEmbossLotIssuanceId, saveEmbossLotItem, saveEmbossLotItemDesc, scannedValue);
                    saveEmbossLotIssuanceId = null;
                    saveEmbossLotItem = null;
                    saveEmbossLotItemDesc = null;
                  }
                break;

                // case 'scan_whs_slip_no':
                //   scannedValue = $("#txtSearchPoTransLotNo").val();
                //   $("#txtScannedWHSSlipNo").val(scannedValue);
                // break;

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
          if(parseInt($("#txt_total_no_of_ok").val()) >= parseInt($("#txt_po_qty_lbl").val())){
            toastr.warning('PO Quantity already reached!');
            return;
          }
          $("#btn_edit_material_details_primary").prop('disabled', false);
          viewMatKitAction = 1;
          viewMatKitActionLotNo = null;
          viewSakidashiAction = 1;
          viewSakidashiActionLotNo = null;
          viewEmbossAction = 1;
          viewEmbossActionLotNo = null;
          $("#txtScannedMatKitLot").val('')
          $("#txtScannedSakidashiLot").val('')
          $("#txtScannedEmbossLot").val('')
          $("#txtScannedEmployeeNo").val('')
          $("#txtSearchedMatKitLot").val('');
          $("#txtSearchedSakidashiLotNo").val('');
          $("#txtSearchedEmbossLotNo").val('');
          dt_materials.draw();
          dt_sakidashi.draw();
          dt_emboss.draw();
          readonly_material_details_primary(true);
          readonly_material_details_secondary(true);
          reset_material_details_primary();
          reset_material_details_secondary();
          HandleButtons(true);
          // $("#btn_edit_material_details_primary").prop('disabled', false);
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


          // $("#btn_edit_material_details_primary").prop('disabled', false);
        }
      });

      $("#btnAddMODTable").click(function(){
        // console.log('test');

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
          appendHTML += '<center><button class="btn btn-xs btn-danger btnRemoveMOD" title="Remove" type="button"><i class="fa fa-times"></i></button></center>';
        appendHTML += '</td>';
        appendHTML += '</tr>'

        $("#tblEditProdRunStaMOD tbody").append(appendHTML);
        $('.select2bs4').select2({
          theme: 'bootstrap4'
        });

        GetCboMOD($("#tblEditProdRunStaMOD tr:last").find('.selectMOD'));

        let totalNoOfMOD = 0;

        //clark added code 02/14/2026
        $("#pRCStatTotNoOfNG").text(totalNoOfMOD);

        $('#tblEditProdRunStaMOD .txtEditProdRunStaMODQty').each(function(i, obj) {
          console.log('this testing', $(this));
          if($(this).val() == null || $(this).val() == ""){

          }else{
            totalNoOfMOD += parseFloat($(this).val());
          }
        });

        if($("#txt_edit_prod_runcard_station_ng").val() != totalNoOfMOD){
          toastr.warning('MOD NG Qty not Tally!');
          $("#pRCStatTotNoOfNG").css({color: 'red'});
          $("#btn_save_prod_runcard_station_stations").prop('disabled', true);
          $("#pRCStatTotNoOfNG").css({color: 'red'});
        }
        else{
          $("#pRCStatTotNoOfNG").css({color: 'green'});
          $("#btn_save_prod_runcard_station_stations").prop('disabled', false);
          $("#pRCStatTotNoOfNG").css({color: 'green'});
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
          'url'       : 'save_prod_runcard_details',
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
          },
          completed     : function(data){

          },
          error     : function(data){

          },
        });
      });

      $("#tbl_prod_runcard").on('click','.btnOpenRuncardDetails',function(e){
        viewMatKitAction = 1;
        viewMatKitActionLotNo = null;
        viewSakidashiAction = 1;
        viewSakidashiActionLotNo = null;
        viewEmbossAction = 1;
        viewEmbossActionLotNo = null;
        $("#txtScannedMatKitLot").val('')
        $("#txtScannedSakidashiLot").val('')
        $("#txtScannedEmbossLot").val('')
        $("#txtScannedEmployeeNo").val('')
        $("#txtSearchedMatKitLot").val('');
        $("#txtSearchedSakidashiLotNo").val('');
        $("#txtSearchedEmbossLotNo").val('');
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
        $("#scanPOTransLotBody").text('Please Scan the PO code.');
        $('#modalScanPOTransLotCode').attr('data-formid', 'search_po_number').modal('show');
      });

      $(".btnScanMatKitLot").click(function(){
        $("#txtSearchPoTransLotNo").val('');
        $("#scanPOTransLotBody").text('Please Scan the Material Lot code.');
        $('#modalScanPOTransLotCode').attr('data-formid', 'scan_mat_kit').modal('show');
        $("#txtScannedMatKitLot").val('');
      });

      $(".btnSearchMatKitLot").click(function(){
        $("#txtSearchPoTransLotNo").val('');
        $("#scanPOTransLotBody").text('Please Scan the Material Lot code to search.');
        $('#modalScanPOTransLotCode').attr('data-formid', 'search_mat_kit').modal('show');
        $("#txtSearchedMatKitLot").val('');
      });

      $(".btnShowSavedMatKitLot").click(function(){
        viewMatKitAction = 1;
        viewMatKitActionLotNo = null;
        dt_materials.draw();
        $("#txtSearchedMatKitLot").val('');
      });

      $(".btnScanWhsSlipNo").click(function(){
        $("#txtSearchPoTransLotNo").val('');
        $("#scanPOTransLotBody").text('Please Scan the Material Lot code.');
        $('#modalScanPOTransLotCode').attr('data-formid', 'scan_whs_slip_no').modal('show');
      });

      $(".btnScanSakidashiLot").click(function(){
        $("#txtSearchPoTransLotNo").val('');
        $("#scanPOTransLotBody").text('Please Scan the Sakidashi Lot code.');
        $('#modalScanPOTransLotCode').attr('data-formid', 'scan_sakidashi').modal('show');
        $("#txtScannedSakidashiLot").val('');
      });

      $(".btnSearchSakidashiLotNo").click(function(){
        $("#txtSearchPoTransLotNo").val('');
        $("#scanPOTransLotBody").text('Please Scan the Sakidashi Lot code to search.');
        $('#modalScanPOTransLotCode').attr('data-formid', 'search_sakidashi').modal('show');
        $("#txtScannedSakidashiLot").val('');
      });

      $(".btnShowSavedSakidashiLot").click(function(){
        viewSakidashiAction = 1;
        viewSakidashiActionLotNo = null;
        dt_sakidashi.draw();
        $("#txtSearchedSakidashiLot").val('');
      });

      $(".btnScanEmbossLot").click(function(){
        $("#txtSearchPoTransLotNo").val('');
        $("#scanPOTransLotBody").text('Please Scan the Emboss Lot code.');
        $('#modalScanPOTransLotCode').attr('data-formid', 'scan_emboss').modal('show');
        $("#txtScannedEmbossLot").val('');
      });

      $(".btnSearchEmbossLotNo").click(function(){
        $("#txtSearchPoTransLotNo").val('');
        $("#scanPOTransLotBody").text('Please Scan the Emboss Lot code to search.');
        $('#modalScanPOTransLotCode').attr('data-formid', 'search_emboss').modal('show');
        $("#txtScannedEmbossLot").val('');
      });

      $(".btnSearchManualEmbossLot").click(function(){
        let lotNo = $("#txtSearchedManualEmbossLot").val();
        $(this).focus();
        viewEmbossAction = 2;
        viewEmbossActionLotNo = lotNo;
        $("#txtSearchedEmbossLot").val(lotNo.trim());
        dt_emboss.draw();
      });

      $("#txtSearchedManualEmbossLot").keypress(function(e){

        if(e.keyCode == 13){
          let lotNo = $("#txtSearchedManualEmbossLot").val();
          $(this).focus();
          viewEmbossAction = 2;
          viewEmbossActionLotNo = lotNo;
          $("#txtSearchedEmbossLot").val(lotNo.trim());
          dt_emboss.draw();
        }

      });

      $(".btnShowSavedEmbossLot").click(function(){
        viewEmbossAction = 1;
        viewEmbossActionLotNo = null;
        dt_emboss.draw();
        $("#txtSearchedEmbossLot").val('');
        $("#txtSearchedManualEmbossLot").val('');
      });

      $(".btnScanEmpNo").click(function(){
        $("#txtSearchPoTransLotNo").val('');
        $("#scanPOTransLotBody").text('Please Scan the Operator Code.');
        $('#modalScanPOTransLotCode').attr('data-formid', 'scan_employee_no').modal('show');
        $("#txtScannedEmployeeNo").val('');
      });

      $(document).on('click','#btn_save_prod_runcard_station_stations',function(e){
        /*
         - Step Validation
        */
        if($('.selSubStation').find(":selected").attr('data-mat-process-step') != $('#process_flow_step_number').val()){
          toastr.error('Step/Process is not matched.<br> Please check on matrix.')
          return;
        }
        // ! End of Validation

        /**
         * NG Qty validation
        */
        let ttlNoOfNg = parseInt($('#pRCStatTotNoOfNG').text(), 10);
        let registeredNgQty = parseInt($('#txt_edit_prod_runcard_station_ng').val(), 10);

        if(ttlNoOfNg != registeredNgQty){
          toastr.error('Invalid! NG Quantity not match')
          return;

        }

        /*
          - Machine Validation;
          - To Make sure that all required machine from matrix is scanned.
        */
        console.log('btn_save_prod_runcard_station_stations splittedMachine', splittedMachine);
        let scannedMachine = $('#txt_edit_prod_runcard_station_machine').val();
        console.log('scannedMachine', scannedMachine);
        if(splittedMachine.length == scannedMachine.length){
          $(splittedMachine).each(function(index){
            console.log('splittedMachine[index]', splittedMachine[index]);
            if(jQuery.inArray(splittedMachine[index], scannedMachine) == '-1'){
              toastr.error('Invalid Machine. Please check process matrix.')
              return;
            }

          })
        }
        else{
          toastr.error('Please scan required machines.');
          return;
        }
        // ! END of Validation

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

      // $("#txt_edit_prod_runcard_station_output").keyup(function(){
      //   $("#txt_edit_prod_runcard_station_ng").val(parseInt($("#txt_edit_prod_runcard_station_input").val()) - parseInt($(this).val()));
      // });

      // $("#txt_edit_prod_runcard_station_input").keyup(function(){
      //   $("#txt_edit_prod_runcard_station_ng").val(parseInt($("#txt_edit_prod_runcard_station_input").val()) - parseInt($("#txt_edit_prod_runcard_station_output").val()));
      // });

      $("#txt_edit_prod_runcard_station_output").on('keyup', function(){
        $("#txt_edit_prod_runcard_station_ng").val(parseInt($("#txt_edit_prod_runcard_station_input").val()) - parseInt($(this).val())).trigger('change');
      });

      $("#txt_edit_prod_runcard_station_input").on('keyup', function(){
        $("#txt_edit_prod_runcard_station_ng").val(parseInt($("#txt_edit_prod_runcard_station_input").val()) - parseInt($("#txt_edit_prod_runcard_station_output").val()));
      });

      // $("#txt_edit_prod_runcard_station_ng").keyup(function(){
      $("#txt_edit_prod_runcard_station_ng").on('change', function(){
        $("#txt_edit_prod_runcard_station_output").val(parseInt($("#txt_edit_prod_runcard_station_input").val()) - parseInt($("#txt_edit_prod_runcard_station_ng").val()));

        if($(this).val() > 0){
          $("#btnAddMODTable").prop('disabled', false);
        }
        else{
          $("#btnAddMODTable").prop('disabled', true);
        }

        if($(this).val() != $("#pRCStatTotNoOfNG").text()){
          console.log($(this).val() + " = " + $("#pRCStatTotNoOfNG").text());
          // toastr.warning('MOD NG Qty not Tally!');
          $("#pRCStatTotNoOfNG").css({color: 'red'});
          $("#btn_save_prod_runcard_station_stations").prop('disabled', true);
        }
        else{
          $("#pRCStatTotNoOfNG").css({color: 'green'});
          $("#btn_save_prod_runcard_station_stations").prop('disabled', false);
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



      $('#frm_edit_prod_runcard_station_details').submit(function(e){
        e.preventDefault();
        $('#txt_employee_number_scanner').prop('readonly', true);
        console.log('materialArray', materialArray);

        $('.class-disabled').prop('disabled', false)

        setTimeout(() => {
          /**
           * Validate print lot, reel lot, guaranteed lot if process has input of it
          */
          if($('#txt_edit_prod_runcard_substation').find(':selected').attr('data-mat-process-has-print-input') == 1){
            if(printLotArray.length == 0){
              toastr.error('Please add print lot details.');
              $('#txt_employee_number_scanner').prop('readonly', false);
              return;
            }
          }
          if($('#txt_edit_prod_runcard_substation').find(':selected').attr('data-mat-process-has-reel-input') == 1){
            if($('#txt_reel_lot_number').val() == '' || $('#txt_oldest_lot').val() == ''){
              toastr.error('Please input reel lot and oldest lot number.');
              $('#txt_employee_number_scanner').prop('readonly', false);
              return;
            }

            if($('#txt_oldest_lot').val().length != 6){
              // toastr.error('Reel lot number must be 6 characters.'); // CHRIS CHOUPUL
              toastr.error('Oldest Lot must be 6 characters.');
              $('#txt_employee_number_scanner').prop('readonly', false);
              return;
            }
          }
          if($('#txt_edit_prod_runcard_substation').find(':selected').attr('data-mat-process-has-guaranteed-lot') == 1){
            if($('input[name="name_guaranteed_lot"]:checked').val() == undefined){
              toastr.error('Please select guaranteed lot option.');
              $('#txt_employee_number_scanner').prop('readonly', false);
              return;
            }
          }

          /**
           * validate operator
          */
          if($('#txt_edit_prod_runcard_operator').val().length == 0){
            toastr.error('Please select operator.');
            $('#txt_employee_number_scanner').prop('readonly', false);
            return;
          }
          /*
            Validation for scanned user
          */
          if(jQuery.inArray($("#txt_employee_number_scanner").val(), splittedEmpId) == -1){
            console.log(splittedEmpId);
            toastr.error('ID is not certified.');
            $('#txt_employee_number_scanner').prop('readonly', false);
            return;
          }
          // End of validation
          $.ajax({
            'data'      : $(this).serialize()
                          +'&txt_employee_number_scanner=' + $("#txt_employee_number_scanner").val()
                          +'&txt_prod_runcard_id_query=' + $("#txt_prod_runcard_id_query").val()
                          + "&require_oqc_before_emboss=" + $("#txt_prod_runcard_require_oqc_before_emboss").val()
                          +"&"+ $.param({materialArray})
                          +"&"+ $.param({printLotArray})
                          +"&device_name="+ $("#txt_use_for_device").val()
                          +"&device_code="+ $("#txt_device_code").val()
                          +"&po_qty="+ $("#txt_po_qty_lbl").val()
                          +"&total_ok_qty="+$('#txt_total_no_of_ok').val(),
            'type'      : 'post',
            'dataType'  : 'json',
            'url'       : 'edit_prod_runcard_station',
            success     : function(data){
              $('#mdl_alert #mdl_alert_title').html(data['title']);
              $('#mdl_alert #mdl_alert_body').html(data['body']);
              $('#mdl_alert').modal('show');

              dt_prod_runcard_stations.draw();
              //
              dt_materials.draw();
              if(data['done_runcard']){
                dt_prod_runcard.draw();
              }
              $('#txt_employee_number_scanner').prop('readonly', false);

              $('#mdl_edit_prod_runcard_station_details').modal('hide');
            },
            error     : function(data){
              if(data['status'] == 422){
                toastr.error(data['responseJSON']['msg']);
              }
              if(data['status'] == 406){
                Swal.fire({
                  title: "Error!",
                  html: `${data['responseJSON']['msg']}`,
                  icon: "error",
                })
              }
              $('#txt_employee_number_scanner').prop('readonly', false);
            },
          });
        }, 500);
      });

      $('#modalScanPOTransLotCode').on('shown.bs.modal', function () {
        $('#txtSearchPoTransLotNo').val('')
        $('#txtSearchPoTransLotNo').trigger('focus');
      })

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

      $("#tbl_sakidashi").on('click','.btnSaveSakidashiLot',function(e){
        let issuanceId = $(this).attr('issuance-id');
        let itemDesc = $(this).attr('item-desc');
        let item = $(this).attr('item');

        saveSakidashiLotIssuanceId = issuanceId;
        saveSakidashiLotItem = item;
        saveSakidashiLotItemDesc = itemDesc;

        // console.log(saveSakidashiLotIssuanceId + "\n" + saveSakidashiLotItem + "\n" + saveSakidashiLotItemDesc);
        // console.log(issuanceId);
        $("#txtSearchPoTransLotNo").val('');
        $("#scanPOTransLotBody").text('Please Scan your ID.');
        $('#modalScanPOTransLotCode').attr('data-formid', 'save_sakidashi_lot_issuance').modal('show');
      });

      $("#tbl_emboss").on('click','.btnSaveEmbossLot',function(e){
        let issuanceId = $(this).attr('issuance-id');
        let itemDesc = $(this).attr('item-desc');
        let item = $(this).attr('item');

        saveEmbossLotIssuanceId = issuanceId;
        saveEmbossLotItem = item;
        saveEmbossLotItemDesc = itemDesc;

        $("#txtSearchPoTransLotNo").val('');
        $("#scanPOTransLotBody").text('Please Scan your ID.');
        $('#modalScanPOTransLotCode').attr('data-formid', 'save_emboss_lot_issuance').modal('show');
      });

      // $(document).on('click','.btn_edit_prod_runcard_station',function(e){
      //   var data_arr = [];
      //   data_arr['col_prod_runcard_station_id']     = $(this).closest('tr').find('.col_prod_runcard_station_id').val();
      //   // data_arr['material_code']   = $(this).closest('tr').find('.col_material_code').val();
      //   fn_select_prod_runcard_station_details(data_arr);
      // });

      // CHRIS - Runcard Modification

      $('#btnAddProcess').on('click', function(){
        getDataForAddingProcess($('#txt_prod_runcard_id_query').val(), $('#txt_use_for_device').val(), $('#txt_device_code').val());
        $('#mdl_edit_prod_runcard_station_details').modal('show');
      })
      $('#mdl_edit_prod_runcard_station_details').on('hidden.bs.modal', function(){
        $('.class-disabled').prop('disabled', true)
        $('#btn_scan_add_runcard_operator_code').prop('disabled', true);
        $('#btn_scan_machine_code').prop('disabled', true);
        $('#btnAddNewMaterial').prop('disabled', true);

        $('#frm_edit_prod_runcard_station_details')[0].reset();
        $('#txt_edit_prod_runcard_operator').val(0).trigger('change')
        $('#txt_edit_prod_runcard_station_machine').val(0).trigger('change')
        $('#txt_edit_prod_runcard_station_input').prop('readonly', false);


        $("#tblEditProdRunStaMOD tbody").empty();

        materialArray = [];
        printLotArray = [];
        dtMaterials.clear().draw();
        dtPrintLots.clear().draw();
        splittedMachine = [];
        $('#idProblem').prop('readonly', true);
        $('#idDocNum').prop('readonly', true);
        $("input:radio", $('#frm_edit_prod_runcard_station_details')).attr("checked", false);


      })


      $('#btnScanMaterialLotNo').on('click', function(e){
        $('#mdl_qrcode_scanner').modal('show');
        $('#mdl_qrcode_scanner').attr('data-formid', 'fn_scan_materials')
      })

      dtMaterials = $('#tbl_materials_for_scanning').DataTable({
        // rowId: 'id', // optional
        "ordering": false,
        "searching": false,
        "paging": false,
        "info" : false,
        "columns": [
          { "data": "material_part_no" },
          { "data": "material_name" },
          { "data": "lot_no" },
          {
            "data": "material_type",
            render: function (data) {
              if(data == 1){
                return "Material Kitting & Issuance";
              }
              else if(data == 2){
                return "Sakidashi Issuance";
              }
              else if(data == 3){
                return "Pre Assembly";
              }

            }
          },
        ],
      });

      $('#btnAddMaterial').on('click', function(e){

        var data = {};
        let result = false;
        $("#frmAddMaterial").serializeArray().map(function(x){data[x.name] = x.value;});

        console.log('data', data);
        console.log('materialArray before push', materialArray);

        $.each(materialArray, function(key, value){
          if(value.lot_no == data.lot_no && value.material_part_no == data.lot_no){
            result = true;
          }
        })

        if(result){
          toastr.error('Lot Number already exist!');
          return;
        }
        materialArray.push(data);
        console.log('materialArray after push', materialArray);

        dtMaterials.clear().draw();
        dtMaterials.rows.add(materialArray).draw();

        if(materialArray.length > 0){
          $('#removeLastMaterial').prop('disabled', false);
        }
        $('#mdlAddMaterial').modal('hide');
      });

      $('#mdlAddMaterial').on('hidden.bs.modal', function(){
        $('#frmAddMaterial')[0].reset();
      })

      $('#removeLastMaterial').on('click', function(e){
        e.preventDefault();
        materialArray.pop();
        dtMaterials.clear().draw();
        dtMaterials.rows.add(materialArray).draw();

        if(materialArray.length == 0){
          $('#removeLastMaterial').prop('disabled', true);
        }

      })

      $('#btnDone').on('click', function(e){
        e.preventDefault();
        $('#mdl_employee_number_scanner').attr('data-formid','saveDoneRuncard').modal('show');
      })

      $(document).on('click', '.btn_edit_prod_runcard_station', async function(e){
        e.preventDefault();
        let runcardStationId = $(this).attr('prod_runcard_station_id')
        let hasGuaranteedLot = $(this).attr('has-guaranteed')
        let hasReelInput = $(this).attr('has-reel-input')
        let hasPrintInput = $(this).attr('has-print-input')
        $('#btn_save_prod_runcard_station_stations').prop('disabled', false);

        try {
          // ✅ Wait for getDataForAddingProcess to complete before continuing
          await getDataForAddingProcess(
            $('#txt_prod_runcard_id_query').val(),
            $('#txt_use_for_device').val(),
            $('#txt_device_code').val(),
            1
          );

          // ✅ Once the above finishes, call getProcessStationDetails
          getProcessStationDetails(runcardStationId, hasGuaranteedLot, hasReelInput, hasPrintInput);
        } catch (error) {
          console.error('Error while getting process data:', error);
        }
        // getDataForAddingProcess($('#txt_prod_runcard_id_query').val(), $('#txt_use_for_device').val(), $('#txt_device_code').val(), 1);

        // getProcessStationDetails(runcardStationId, hasGuaranteedLot, hasReelInput, hasPrintInput);
      })

      $(document).on('click','.btnInputReel', function(e){
        let runcardId = $(this).attr('runcard-id');
        $('#txtruncardId').val(runcardId);
        $('#modalInputReels').modal('show');
      });

      /*
        * For Inputing for Reel Lot #
      */
      $('#formInputReel').submit( function(e){
        e.preventDefault();
        Swal.fire({
          title: "Are you sure?",
          text: "You won't be able to revert this!",
          icon: "question",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes"
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              type: "post",
              url: "save_reel_number",
              data: $(this).serialize(),
              dataType: "json",
              beforeSend: function(){
                $(this).prop('disabled', true);
              },
              success: function (response) {
                $('#modalInputReels').modal('hide');
                dt_prod_runcard.draw();
                toastr.success('Transaction Successful!');
                $(this).prop('disabled', false);

              },
              error: function(data, xhr, status){
                console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
              }
            });
          }
        });
      })

      $('#modalInputReels').on('hidden.bs.modal', function(){
        $('#formInputReel')[0].reset();
      })
      /*
        ! END
      */


      /*
        * For Printing
      */

      $(document).on('click', '.btnPrintRuncard', function(){
        let runcardId = $(this).attr('runcard-id');
        generateQrCode(runcardId)
      });

      $('#btnPrintRuncardQr').on('click', function(){
        console.log(runcardQRCode);
        popup = window.open();
        let content = '';

        content += '<html>';
        content += '<head>';
        content += '<title></title>';
        content += '<style type="text/css">';
        content += '@media print { .pagebreak { page-break-before: always; } }';
        content += '</style>';
        content += '</head>';
        content += '<body>';


        content += '<table style="margin-left: -5px; margin-top: 5px;">';
        content += '<tr style="width: 290px;">';
        content += '<td >';
        content += '<img src="' + runcardQRCode['img'] + '" style="min-width: 75px; max-width: 75px;">';
        content += '</td>';
        content += '<td style="font-size: 10px; font-family: Calibri;">' + runcardQRCode['text'] + '</td>';

        content += '</tr>';
        content += '</table>';
        content += '<br>';
        content += '</body>';
        content += '</html>';
        popup.document.write(content);

        popup.focus(); //required for IE
        popup.print();
        popup.close();
      })

      $(document).on('click', '.btnPrintQrCode', function(){
        let runcardNo = $('#txt_runcard_no').val();
        let poNumber = $('#txt_po_number').val();
        let reelLotNo = $(this).attr('data-prod-reel-no');
        let oldestLotNo = $(this).attr('data-prod-oldest-lot-no');
        let printLotNo = $(this).attr('data-prod-print-lot-no');
        if(reelLotNo == null){
          alert('Please insert reel lot first!');
          return;
        }

        $.ajax({
          type: "get",
          url: "generate_qr_code_process_station",
          data: {
            runcard_number: runcardNo,
            po_number     : poNumber,
            reel_lot_no   : reelLotNo,
            print_lot_no   : printLotNo,
            oldest_lot_no   : oldestLotNo,

          },
          dataType: "json",
          success: function (response) {
            console.log(response);
            processStationPrintingQrData = {
              poNoQr        : response['img_po_number'],
              runNoQr       : response['img_runcard_number'],
              reelLotNoQr   : response['img_reel_lot_no'],
              poNoLabel     : poNumber,
              runNoLabel    : runcardNo,
              reelLotNoLabel: reelLotNo
            }
            $("#img_qr_po_no_on_process").attr('src', response['img_po_number']);
            $("#img_qr_runcard_no_on_process").attr('src', response['img_runcard_number']);
            $("#img_qr_reel_lot_nos_on_process").attr('src', response['img_reel_lot_no']);
            $("#qrLabelFromProcessPoNumber").html(poNumber);
            $("#qrLabelFromProcessRuncardNo").html(runcardNo);
            $("#qrLabelFromProcessReelLotNo").html(reelLotNo);

            $('#modalRuncardProcessQrCode').modal('show');
          },
          error: function(data, xhr, status){
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
          }
        });

      });

       $(document).on('click', '.btnPrintQrCode1', function(){
        let runcardNo = $('#txt_runcard_no').val();
        let poNumber = $('#txt_po_number').val();
        let reelLotNo = $(this).attr('data-prod-reel-no');
        let oldestLotNo = $(this).attr('data-prod-oldest-lot-no');
        let printLotNo = $(this).attr('data-prod-print-lot-no');
        if(reelLotNo == null){
          alert('Please insert reel lot first!');
          return;
        }

        $.ajax({
          type: "get",
          url: "generate_qr_code_process_station1",
          data: {
            runcard_number: runcardNo,
            po_number     : poNumber,
            reel_lot_no   : reelLotNo,
            print_lot_no   : printLotNo,
            oldest_lot_no   : oldestLotNo,

          },
          dataType: "json",
          success: function (response) {
            console.log(response);
            processStationPrintingQrData = {
              poNoQr        : response['img_po_number'],
              runNoQr       : response['img_runcard_number'],
              reelLotNoQr   : response['img_reel_lot_no'],
              poNoLabel     : poNumber,
              runNoLabel    : runcardNo,
              reelLotNoLabel: reelLotNo
            }
            $("#img_qr_po_no_on_process").attr('src', response['img_po_number']);
            $("#img_qr_runcard_no_on_process").attr('src', response['img_runcard_number']);
            $("#img_qr_reel_lot_nos_on_process").attr('src', response['img_reel_lot_no']);
            $("#qrLabelFromProcessPoNumber").html(poNumber);
            $("#qrLabelFromProcessRuncardNo").html(runcardNo);
            $("#qrLabelFromProcessReelLotNo").html(reelLotNo);

            $('#modalRuncardProcessQrCode').modal('show');
          },
          error: function(data, xhr, status){
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
          }
        });

      });

      $('#btnPrintRuncardQrFromProcess').on('click', function(){
        popup = window.open();
        let content = '';

        content += '<html>';
        content += '<head>';
        content += '<title></title>';
        content += '<style type="text/css">';
        content += '@media print { .pagebreak { page-break-before: always; } }';
        content += '</style>';
        content += '</head>';
        content += '<body>';


        content += '<table style="margin-top: 5px; width:100%; text-align: center;">';
        content += '<tr>';
        content += '<td >';
          content += '<img src="' + processStationPrintingQrData.poNoQr + '" style="min-width: 90px; max-width: 90px;">';
        content += '</td>';
         content += '<td >';
          content += '<img src="' + processStationPrintingQrData.reelLotNoQr + '" style="min-width: 140px; max-width: 140px;">';
        content += '</td>';
        content += '<td >';
          content += '<img src="' + processStationPrintingQrData.runNoQr + '" style="min-width: 90px; max-width: 90px;">';
        content += '</td>';
       
        content += '</tr>';
        content += '<tr>';
          content += '<td style="font-size: 10px; font-family: Calibri;">' + processStationPrintingQrData.poNoLabel + '</td>';
          content += '<td style="font-size: 10px; font-family: Calibri;">' + processStationPrintingQrData.reelLotNoLabel + '</td>';
          content += '<td style="font-size: 10px; font-family: Calibri;">' + processStationPrintingQrData.runNoLabel + '</td>';

        content += '</tr>';
        content += '</table>';
        content += '<br>';
        content += '</body>';
        content += '</html>';
        popup.document.write(content);

        popup.focus(); //required for IE
        popup.print();
        popup.close();
      })
      $("input[name='name_guaranteed_lot']").change(function () {
        name_GuaranteedLot = this.value;
        if (name_GuaranteedLot == 1) {
            $('#idProblem').attr('readonly', false);
            $('#idDocNum').attr('readonly', false);
        } else {
            $('#idProblem').attr('readonly', true);
            $('#idDocNum').attr('readonly', true);
            $('#idProblem').val('');
            $('#idDocNum').val('');
        }
      });

      dtPrintLots = $('#tablePrintLots').DataTable({
        "ordering": false,
        "searching": false,
        "paging": false,
        "info" : false,
        "columns": [
          { "data": "print_lot" },
          { "data": "print_lot_qty" },
        ],
      });
      $('#formPrintLot').submit(function(e){
        e.preventDefault();

        let printLotObject = {
          print_lot : $('#txtPrintLotNo', $(this)).val(),
          print_lot_qty : $('#txtPrintLotQty', $(this)).val()
        }
        printLotArray.push(printLotObject)

        dtPrintLots.clear().draw();
        dtPrintLots.rows.add(printLotArray).draw();

        $('#modalAddPrintLot').modal('hide')
      })

      $('#modalAddPrintLot').on('hidden.bs.modal', function(){
        $('#formPrintLot')[0].reset();
      })

      $('#btnRemoveLastPrintLot').on('click', function(e){
        e.preventDefault();
        printLotArray.pop();
        dtPrintLots.clear().draw();
        dtPrintLots.rows.add(printLotArray).draw();
      })

      $(document).on('click', '.btn_edit_guaranteed_lot', function(e){
        e.preventDefault();

        prodnRuncardID = $(this).attr('prodn_runcard-id');
        $('#txtProdnRuncardId').val(prodnRuncardID)
        GetProdnRuncardIdToEdit(prodnRuncardID)
      });

      $('.radioButton').change(function (e) {
        e.preventDefault();
        let glValue = $(this).val()
          if(glValue == 1){
            $('#idEditProblem').prop('readonly', false)
            $('#idEditDocNum').prop('readonly', false)
          }else{
            $('#idEditProblem').prop('readonly', true).val('')
            $('#idEditDocNum').prop('readonly', true).val('')
          }
      });

      $('#modalCreateGuaranteedLot').on('hidden.bs.modal', function(event) {
        $('.radioButton').prop('checked', false)
      });

      $('#formEditGuaranteedLot').submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: "post",
            url: "update_prodn_runcard",
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if(response['result'] == 1){
                    toastr.success('Save!');
                    $('#modalCreateGuaranteedLot').modal('hide');
                    $('#formEditGuaranteedLot')[0].reset();
                }
            }
        });
      });

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
        // $("#btn_edit_material_details_primary").prop('disabled', status);
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
          $('#btn_edit_material_details_primary').prop('disabled', true);
          // toastr.info('Loading runcard details. Please wait...');
        },
        success: function(data){
          let deta = data['material_kitting']
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
            }
            $("#txt_lot_qty").val(boxing);

            if(data['material_kitting']['device_info'] != null){
              assessment = data['material_kitting']['device_info']['assessment'];
            }
            else{
              assessment = "";
            }
            console.log('chan bugok', deta)
            // CHAN
            $("#txt_a_drawing_no").val(deta['ppts_device_info']['a_drawing_no']);
            $("#txt_a_drawing_rev").val(deta['ppts_device_info']['a_drawing_rev']);
            $("#txt_g_drawing_no").val(deta['ppts_device_info']['g_drawing_no']);
            $("#txt_g_drawing_rev").val(deta['ppts_device_info']['g_drawing_rev']);

            // ========================================================================================================================================================
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

            // CHAN
            // if(data['doc_a_drawing_query'].length > 0){
            //   aDrawing = data['doc_a_drawing_query'][0].doc_no;
            //   aDrawingRev = data['doc_a_drawing_query'][0].rev_no;
            // }
            // else{
            //   aDrawing = "";
            //   aDrawingRev = "";
            // }
            // $("#txt_a_drawing_no").val(aDrawing);
            // $("#txt_a_drawing_rev").val(aDrawingRev);

            // if(data['doc_g_drawing_query'].length > 0){
            //   gDrawing = data['doc_g_drawing_query'][0].doc_no;
            //   gDrawingRev = data['doc_g_drawing_query'][0].rev_no;
            // }
            // else{
            //   gDrawing = "";
            //   gDrawingRev = "";
            // }
            // $("#txt_g_drawing_no").val(gDrawing);
            // $("#txt_g_drawing_rev").val(gDrawingRev);
            // CHAN

            // materialKitTransferSlip = data['material_kitting']['issuance_no'];
            // $("#txt_material_transfer_slip_lbl").val(materialKitTransferSlip);
            // dt_materials.draw();

            // $("#txt_lot_qty").val(boxing);

            $('#btn_edit_material_details_primary').prop('disabled', false);
            // toastr.info('Runcard details loaded.');
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
            $("#txt_prod_runcard_has_emboss").val(data['prod_runcard']['has_emboss']);
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

            $("#selAssemblyLine").val(data['prod_runcard']['sel_assembly_line']).trigger('change');
            $("#selProdCategory").val(data['prod_runcard']['sel_product_category']).trigger('change');
            $("#selCertLot").val(data['prod_runcard']['sel_certification_lot']).trigger('change');

            $("#txt_created_at").val(data['prod_runcard']['created_at']);

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
                  }
                }
                else{
                }

                if(data['prod_runcard']['discrepant_qty'] == 0){
                }
              }
            }
            else{
              // $("#btn_setup_stations").prop('disabled', true);
              hasProdMatSakList = true;
            }


            HandleButtons(true);
            let prodRuncardStat = data['prod_runcard']['status'];
            if(prodRuncardStat == 0){
              $('#btnDone').prop('disabled', false);
            }
            else{
              $('#btnDone').prop('disabled', true);

            }

            if(prodRuncardStat == 1){
              $("#btn_edit_material_details_primary").prop('disabled', false);
              $("#btnSaveSelectedMatSak").prop('disabled', false);
              $("#btnSaveSelectedEmboss").prop('disabled', true);
              $("#btn_setup_stations").prop('disabled', true);
              $("#btn_edit_material_details_verification").prop('disabled', true);
              $("#btn_approve_prod").prop('disabled', true);
              $("#btn_approve_qc").prop('disabled', true);
            }
            else if(prodRuncardStat == 2){
              $("#btn_edit_material_details_primary").prop('disabled', false);
              // $("#btnSaveSelectedMatSak").prop('disabled', true);
              $("#btnSaveSelectedMatSak").prop('disabled', false);
              $("#btnSaveSelectedEmboss").prop('disabled', true);
              $("#btn_setup_stations").prop('disabled', true);
              $("#btn_edit_material_details_verification").prop('disabled', false);
              $("#btn_approve_prod").prop('disabled', true);
              $("#btn_approve_qc").prop('disabled', true);
            }
            else if(prodRuncardStat == 3){
              $("#btn_edit_material_details_primary").prop('disabled', false);
              $("#btnSaveSelectedMatSak").prop('disabled', false);
              $("#btnSaveSelectedEmboss").prop('disabled', true);
              $("#btn_setup_stations").prop('disabled', true);
              $("#btn_edit_material_details_verification").prop('disabled', false);
              $("#btn_approve_prod").prop('disabled', true);
              $("#btn_approve_qc").prop('disabled', true);
            }
            else if(prodRuncardStat == 4){
              $("#btn_edit_material_details_primary").prop('disabled', false);
              $("#btnSaveSelectedMatSak").prop('disabled', true);
              $("#btnSaveSelectedEmboss").prop('disabled', true);
              $("#btn_setup_stations").prop('disabled', true);
              $("#btn_edit_material_details_verification").prop('disabled', true);
              $("#btn_approve_prod").prop('disabled', false);
              $("#btn_approve_qc").prop('disabled', true);
            }
            else if(prodRuncardStat == 5){
              $("#btn_edit_material_details_primary").prop('disabled', false);
              $("#btnSaveSelectedMatSak").prop('disabled', true);
              $("#btnSaveSelectedEmboss").prop('disabled', true);
              $("#btn_setup_stations").prop('disabled', true);
              $("#btn_edit_material_details_verification").prop('disabled', true);
              $("#btn_approve_prod").prop('disabled', true);
              $("#btn_approve_qc").prop('disabled', false);
            }
            else if(prodRuncardStat == 7){
              $("#btn_edit_material_details_primary").prop('disabled', true);
              $("#btnSaveSelectedEmboss").prop('disabled', false);
            }
            else if(prodRuncardStat == 8){
              $("#btn_edit_material_details_primary").prop('disabled', true);
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
            $("#txt_created_at").val('');

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

    function SaveSakidashiLotIssuanceId(issuanceId, item, itemDesc, operator){

      // console.log("item : " + item + '\n' + 'item desc: ' + itemDesc);
      $.ajax({
        url : 'save_sakidashi_lot_issuance',
        method : 'post',
        data: {
          _token: _token,
          issuance_id: issuanceId,
          item: item,
          item_desc: itemDesc,
          operator: operator,
          prod_runcard_id: $("#txt_prod_runcard_id_query").val(),
          device_name: $("#txt_device_name_lbl").val(),
        },
        dataType : 'json',
        before: function(){

        },
        success: function(data){

          $("#modalScanPOTransLotCode").modal('hide');

          if(data['final_result'] == 1){
            toastr.success('Material Saved!');
            if(data['runcard_id'] != null){
              viewSakidashiAction = 1;
              viewSakidashiActionLotNo = null;
              $("#txtSearchedSakidashiLot").val('');
              GetProdRuncardById(data['runcard_id']);
            }
          }
          else{
           toastr.error(data['remarks']);
          }
        },
        completed: function(data){
          GetProdRuncardById($("#txt_prod_runcard_id_query").val());
        },
        error: function(data){

        },
      });
    }

    function SaveEmbossLotIssuanceId(issuanceId, item, itemDesc, operator){

      // console.log("item : " + item + '\n' + 'item desc: ' + itemDesc);
      $.ajax({
        url : 'save_emboss_lot_issuance',
        method : 'post',
        data: {
          _token: _token,
          issuance_id: issuanceId,
          item: item,
          item_desc: itemDesc,
          operator: operator,
          prod_runcard_id: $("#txt_prod_runcard_id_query").val(),
          device_name: $("#txt_device_name_lbl").val(),
        },
        dataType : 'json',
        before: function(){

        },
        success: function(data){

          $("#modalScanPOTransLotCode").modal('hide');

          if(data['final_result'] == 1){
            toastr.success('Material Saved!');
            if(data['runcard_id'] != null){
              viewEmbossAction = 1;
              viewEmbossActionLotNo = null;
              $("#txtSearchedEmbossLot").val('');
              GetProdRuncardById(data['runcard_id']);
            }
          }
          else{
           toastr.error(data['remarks']);
          }
        },
        completed: function(data){
          GetProdRuncardById($("#txt_prod_runcard_id_query").val());
        },
        error: function(data){

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

    // SCAN MATERIAL LOT # CODE
    function ScanProdRuncardMaterialKiting(materialLotNo){
      $.ajax({
        url: 'scan_material_kitting_lot_no',
        method: 'get',
        dataType: 'json',
        data: {
          _token: "{{ csrf_token() }}",
          po_number: currentPoNo,
          lot_no: materialLotNo,
          runcard_id: $("#txt_prod_runcard_id_query").val(),
          device_name: $("#txt_device_name_lbl").val(),
          lot_qty: $("#txt_lot_qty").val(),
          a_drawing: $("#txt_a_drawing_no").val(),
          a_drawing_rev: $("#txt_a_drawing_rev").val(),
          g_drawing: $("#txt_g_drawing_no").val(),
          g_drawing_rev: $("#txt_g_drawing_rev").val(),
          other_docs_no: $("#txt_other_docs_no").val(),
          other_docs_rev: $("#txt_other_docs_rev").val(),
          employee_number_scanner: $("#txt_employee_number_scanner").val(),
          has_emboss: $("#txt_prod_runcard_has_emboss").val(),
          // whs_slip_no: $("#txtScannedWHSSlipNo").val(),
        },
        beforeSend: function(){
          $("#scanPOTransLotIcon").removeClass('fa-qrcode');
          $("#scanPOTransLotIcon").addClass('fa-spinner fa-pulse');
        },
        success: function(data){
          // $("#modal")

          $("#scanPOTransLotIcon").addClass('fa-qrcode');
          $("#scanPOTransLotIcon").removeClass('fa-spinner fa-pulse');
          $("#txtSearchPoTransLotNo").val('');
          $("#txtSearchPoTransLotNo").focus();

          if(data['final_result'] == 1){
            console.log('DATA: ', data);
            if(data['prep_status']){
              if(data['runcard_material_status']){
                if(data['material_certified']){
                    dt_prod_runcard.draw();
                    toastr.success('Material Saved!');
                    if(data['runcard_id'] != null){
                      GetProdRuncardById(data['runcard_id']);
                    }
                }
                else{
                  toastr.error(data['material_certified_label']);
                }
              }
              else{
                toastr.error(data['runcard_material_status_label']);
              }
            }
            else{
              toastr.error(data['prep_status_label']);
            }
          }
          else{
            // toastr.error('Saving Failed!');
            console.log('DATA: ELSE ',data['prep_status']);
            if(!data['prep_status']){
              toastr.error(data['prep_status_label']);
            }
            else{
              if(!data['runcard_material_status']){
                toastr.error(data['runcard_material_status_label']);
              }
              else{
                if(!data['material_certified']){
                  toastr.error(data['material_certified_label']);
                }
              }
            }
          }
        },
        error: function(data, xhr, status){
          $("#scanPOTransLotIcon").addClass('fa-qrcode');
          $("#scanPOTransLotIcon").removeClass('fa-spinner fa-pulse');
          $("#txtSearchPoTransLotNo").val('');
          $("#txtSearchPoTransLotNo").focus();
        }
      });
    }

    // SCAN SAKIDASHI ISSUANCE LOT # CODE
    function ScanProdRuncardSakidashiIssuance(sakidashiLotNo){
      $.ajax({
        url: 'scan_sakidashi_issuance_lot_no',
        method: 'get',
        dataType: 'json',
        data: {
          _token: "{{ csrf_token() }}",
          po_number: currentPoNo,
          lot_no: sakidashiLotNo,
          runcard_id: $("#txt_prod_runcard_id_query").val(),
          device_name: $("#txt_device_name_lbl").val(),
        },
        beforeSend: function(){
          $("#scanPOTransLotIcon").removeClass('fa-qrcode');
          $("#scanPOTransLotIcon").addClass('fa-spinner fa-pulse');
        },
        success: function(data){
          $("#scanPOTransLotIcon").addClass('fa-qrcode');
          $("#scanPOTransLotIcon").removeClass('fa-spinner fa-pulse');
          $("#txtSearchPoTransLotNo").val('');
          $("#txtSearchPoTransLotNo").focus();
          // if(data['final_result'] == 1){
          //   dt_prod_runcard.draw();
          //   toastr.success('Material Saved!');
          //   if(data['runcard_id'] != null){
          //     GetProdRuncardById(data['runcard_id']);
          //   }
          // }
          // else{
          //   toastr.error('Saving Failed!');
          // }

          if(data['final_result'] == 2){
            toastr.error(data['remarks']);
          }
          else if(data['final_result'] == 1){
            if(data['prep_status']){
              if(data['runcard_material_status']){
                if(data['material_certified']){
                    dt_prod_runcard.draw();
                    toastr.success('Material Saved!');
                    if(data['runcard_id'] != null){
                      GetProdRuncardById(data['runcard_id']);
                    }
                }
                else{
                  toastr.error(data['material_certified_label']);
                }
              }
              else{
                toastr.error(data['runcard_material_status_label']);
              }
            }
            else{
              toastr.error(data['prep_status_label']);
            }
          }
          else{
            // toastr.error('Saving Failed!');
            if(!data['prep_status']){
              toastr.error(data['prep_status_label']);
            }
            else{
              if(!data['runcard_material_status']){
                toastr.error(data['runcard_material_status_label']);
              }
              else{
                if(!data['material_certified']){
                  toastr.error(data['material_certified_label']);
                }
              }
            }
          }
        },
        error: function(data, xhr, status){
          $("#scanPOTransLotIcon").addClass('fa-qrcode');
          $("#scanPOTransLotIcon").removeClass('fa-spinner fa-pulse');
          $("#txtSearchPoTransLotNo").val('');
          $("#txtSearchPoTransLotNo").focus();
        }
      });
    }

    // SCAN EMBOSS LOT # CODE
    function ScanProdRuncardEmboss(embossLotNo){
      $.ajax({
        url: 'scan_emboss_lot_no',
        method: 'get',
        dataType: 'json',
        data: {
          _token: "{{ csrf_token() }}",
          po_number: currentPoNo,
          lot_no: embossLotNo,
          runcard_id: $("#txt_prod_runcard_id_query").val(),
          device_name: $("#txt_device_name_lbl").val(),
        },
        beforeSend: function(){
          $("#scanPOTransLotIcon").removeClass('fa-qrcode');
          $("#scanPOTransLotIcon").addClass('fa-spinner fa-pulse');
        },
        success: function(data){
          // if(data['final_result'] == 1){
          //   dt_prod_runcard.draw();
          //   toastr.success('Material Saved!');
          //   if(data['runcard_id'] != null){
          //     GetProdRuncardById(data['runcard_id']);
          //   }
          // }
          // else{
          //   toastr.error('Saving Failed!');
          // }

          $("#scanPOTransLotIcon").addClass('fa-qrcode');
          $("#scanPOTransLotIcon").removeClass('fa-spinner fa-pulse');
          $("#txtSearchPoTransLotNo").val('');
          $("#txtSearchPoTransLotNo").focus();

          if(data['final_result'] == 2){
            toastr.error(data['remarks']);
          }
          else if(data['final_result'] == 1){
            if(data['prep_status']){
              if(data['runcard_material_status']){
                if(data['material_certified']){
                    dt_prod_runcard.draw();
                    toastr.success('Material Saved!');
                    if(data['runcard_id'] != null){
                      GetProdRuncardById(data['runcard_id']);
                    }
                }
                else{
                  toastr.warning(data['material_certified_label']);
                }
              }
              else{
                toastr.warning(data['runcard_material_status_label']);
              }
            }
            else{
              toastr.warning(data['prep_status_label']);
            }
          }
          else{
            // toastr.error('Saving Failed!');
            if(!data['prep_status']){
              toastr.error(data['prep_status_label']);
            }
            else{
              if(!data['runcard_material_status']){
                toastr.error(data['runcard_material_status_label']);
              }
              else{
                if(!data['material_certified']){
                  toastr.error(data['material_certified_label']);
                }
              }
            }
          }
        },
        error: function(data, xhr, status){
          $("#scanPOTransLotIcon").addClass('fa-qrcode');
          $("#scanPOTransLotIcon").removeClass('fa-spinner fa-pulse');
          $("#txtSearchPoTransLotNo").val('');
          $("#txtSearchPoTransLotNo").focus();
        }
      });
    }

    // SCAN EMPLOYEE # CODE
    function ScanProdRuncardEmployee(employeeNo){
      $.ajax({
        url: 'scan_employee_no',
        method: 'get',
        dataType: 'json',
        data: {
          _token: "{{ csrf_token() }}",
          po_number: currentPoNo,
          employee_no: employeeNo,
          runcard_id: $("#txt_prod_runcard_id_query").val(),
          device_name: $("#txt_device_name_lbl").val(),
        },
        beforeSend: function(){
          $("#scanPOTransLotIcon").removeClass('fa-qrcode');
          $("#scanPOTransLotIcon").addClass('fa-spinner fa-pulse');
        },
        success: function(data){
          $("#scanPOTransLotIcon").addClass('fa-qrcode');
          $("#scanPOTransLotIcon").removeClass('fa-spinner fa-pulse');
          $("#txtSearchPoTransLotNo").val('');
          $("#txtSearchPoTransLotNo").focus();

          if(data['final_result'] == 1){
            if(data['user_status']){
              if(data['certified_status']){
                dt_prod_runcard.draw();
                toastr.success('Operator Saved!');
                if(data['runcard_id'] != null){
                  GetProdRuncardById(data['runcard_id']);
                }
              }
              else{
                toastr.warning(data['certified_status_label']);
              }
            }
            else{
              toastr.warning(data['user_status_label']);
            }
          }
          else{
            // toastr.error('Saving Failed!');
            if(!data['user_status']){
              toastr.error(data['user_status_label']);
            }
            else{
              if(!data['certified_status']){
                toastr.error(data['certified_status_label']);
              }
              else{
                toastr.success('Operator Saved!');
              }
            }
          }
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
      $('#txt_created_at').val('');

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

      $('#selProdCategory').prop('disabled', v);
      $('#selAssemblyLine').prop('disabled', v);
      $('#selCertLot').prop('disabled', v);

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

    // Chris
    function getScannedMaterialDetails(scannedMaterial){
      $.ajax({
        type: "get",
        url: "get_scanned_material_details",
        data: {
            'scanned_mat_lot_no': scannedMaterial,
            'po_number': $('#txt_po_number').val(),
            'scanned_materials' : materialArray,
            'selected_process_id': $('#txt_edit_prod_runcard_substation').find(':selected').attr('data-mat-process-id')
        },
        dataType: "json",
        beforeSend: function () {
            Swal.fire({
                width: '20rem',
                // html: '<div class="spinner-border text-dark" role="status"><span class="sr-only">Loading...</span></div>',
                allowOutsideClick: false,
                onRender: function () {
                    $('.swal2-content').prepend('<div class="spinner-border text-dark" role="status" style="width: 3rem; height: 3rem;"><span class="sr-only">Loading...</span></div>');
                },
                showConfirmButton: false,
            });
        },
        success: function (response) {
            if (response['result']) {
              $('#addProcess_lot_no').val(response['lot_no']);
              $('#material_part_no').val(response['material_code']);
              $('#material_name').val(response['material_name']);
              $('#issuance_id').val(response['issuance_id']);
              let type = "";
              if(response['type'] == 1){
                type = "Material Kitting & Issuance";
              }
              else if(response['type'] == 2){
                type = "Sakidashi Issuance";
              }
              else if(response['type'] == 3){
                type = "Pre Assembly";
              }
              $('#addProcess_material_type').val(response['type']);
              $('#addProcess_material_type_holder').val(type);

              swal.fire({
                  icon: "success",
                  text: "Success",
                  timer: 800,
                  showConfirmButton: false,
              });
            } else {
                swal.fire({
                    icon: "error",
                    text: response['msg'],
                    timer: 1500,
                    showConfirmButton: false,
                });
            }
        },
        error: function (data, xhr, status) {
            swal.fire({
                icon: "error",
                text: "error",
                timer: 800,
                showConfirmButton: false,
            });
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
      });
    }

    function saveDoneRuncard(formRuncardDetails, scannedId){
      $.ajax({
        type: "post",
        url: "save_done_runcard",
        data: formRuncardDetails.serialize()+"&scanned_id="+scannedId,
        dataType: "json",
        beforeSend: function(){
        },
        success: function (response) {
          toastr.success('Successfully Updated!');
          $('#modalRuncardDetails').modal('hide');
          dt_prod_runcard.draw();
        },
        error: function(data, xhr, status){
          console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
          if(data.status == 401){
            toastr.error(data.responseJSON.msg);
          }
        }
      });
    }

    function getProcessStationDetails(runcardStationId, hasGuaranteedLot, hasReelInput, hasPrintInput){


      $.ajax({
        type: "get",
        url: "get_process_station_details",
        data: {
          runcard_station_id : runcardStationId,
        },
        dataType: "json",
        beforeSend: function(){
          // $('.btn_edit_prod_runcard_station').prop('disabled', true);
        },
        success: function (response) {
          let stationDetails = response['runcardStationDetails'][0];
          let machineArray = [];
          let operatorArray = [];

          $('#stationId').val(runcardStationId);
          $('#process_flow_step_number').val(stationDetails['step_num']);
          $('#txt_edit_prod_runcard_station_date').val(stationDetails[''])

          for(let x = 0; x < stationDetails['prod_runcard_station_operator_details'].length; x++){
            operatorArray.push(stationDetails['prod_runcard_station_operator_details'][x]['operator_id']);
          }
          $('#txt_edit_prod_runcard_operator').val(operatorArray).trigger('change');

          for(let y = 0; y < stationDetails['prod_runcard_station_machine_details'].length; y++){
            machineArray.push(stationDetails['prod_runcard_station_machine_details'][y]['machine_id'])
          }

          for(let z = 0; z < stationDetails['prod_runcard_station_material_details'].length; z++){
            let data = {
              issuance_id : stationDetails['prod_runcard_station_material_details'][z]['issuance_id'],
              lot_no : stationDetails['prod_runcard_station_material_details'][z]['lot_no'],
              material_name : stationDetails['prod_runcard_station_material_details'][z]['item_desc'],
              material_part_no : stationDetails['prod_runcard_station_material_details'][z]['item'],
              material_type : stationDetails['prod_runcard_station_material_details'][z]['tbl_wbs']
            }
            materialArray.push(data);
          }



          $('#txt_edit_prod_runcard_station_machine').val(machineArray).trigger('change');

          $('#txt_edit_prod_runcard_station_input').val(stationDetails['qty_input'])
          $('#txt_edit_prod_runcard_station_output').val(stationDetails['qty_output'])
          $('#txt_edit_prod_runcard_station_ng').val(stationDetails['qty_ng'])
          // $('#txt_edit_prod_runcard_station_magnification').val(stationDetails[''])
        //   $('#txt_edit_prod_runcard_station_remarks').val(stationDetails[''])

          // let date = $.datepicker.formatDate('dd M yy', new Date(stationDetails['created_at']))

          let date = new Date(stationDetails['created_at']);
          let formattedDate = date.toLocaleDateString('en-CA')
          $('#txt_edit_prod_runcard_station_date').val(formattedDate)

          dtMaterials.clear().draw();
          dtMaterials.rows.add(materialArray).draw();

        //   console.log('test123', stationDetails['prod_runcard_station_mod_details'].length);

          if(stationDetails['prod_runcard_station_mod_details'].length > 0){

            for(let i = 0; i < stationDetails['prod_runcard_station_mod_details'].length; i++){
                // $('#btnAddMODTable').click();
                // // console.log(object);
                // setTimeout(() => {
                //     $("#tblEditProdRunStaMOD tr:last").find('.selectMOD').val(stationDetails['prod_runcard_station_mod_details'][i]['mod_id']).trigger('change');
                //     $("#tblEditProdRunStaMOD tr:last").find('.txtEditProdRunStaMODQty').val(stationDetails['prod_runcard_station_mod_details'][i]['mod_qty']).trigger('change');
                // }, 800);

                //clark comment 02/14/2026
                $('#btnAddMODTable').click();

                // capture the row immediately
                let $row = $("#tblEditProdRunStaMOD tr:last");

                setTimeout(() => {
                    $row.find('.selectMOD')
                        .val(stationDetails['prod_runcard_station_mod_details'][i]['mod_id'])
                        .trigger('change');

                    $row.find('.txtEditProdRunStaMODQty')
                        .val(stationDetails['prod_runcard_station_mod_details'][i]['mod_qty'])
                        .trigger('change');

                }, 800);
            }
          }


          $('.btn_edit_prod_runcard_station').prop('disabled', false);
          $('#removeLastMaterial').prop('disabled', false);

          if(hasReelInput == 1){
            $('#divReelLot').css('display', '');
            $('#txt_reel_lot_number', $('#frm_edit_prod_runcard_station_details')).val(stationDetails.prod_runcard_details.reel_lot_no)
            $('#txt_oldest_lot', $('#frm_edit_prod_runcard_station_details')).val(stationDetails.prod_runcard_details.oldest_lot_no)
          }
          else{
            $('#divReelLot').css('display', 'none');
          }

          if(hasPrintInput == 1){
            if(stationDetails.prod_runcard_details.print_lots != null){
              printLotArray = JSON.parse(stationDetails.prod_runcard_details.print_lots);
              console.log(printLotArray);
              dtPrintLots.clear().draw();
              dtPrintLots.rows.add(printLotArray).draw();
            }


            $('#divPrintLot').css('display', '');
          }
          else{
            $('#divPrintLot').css('display', 'none');
          }

          if(hasGuaranteedLot == 1){
            $('#divGuaranteedLot').css('display', '');
            $('input:radio[name="name_guaranteed_lot"]', $('#frm_edit_prod_runcard_station_details')).filter(`[value="${stationDetails.prod_runcard_details.has_guaranteed_lot}"]`).attr('checked', true).trigger('change');
            $('#idProblem', $('#frm_edit_prod_runcard_station_details')).val(stationDetails.prod_runcard_details.guaranteed_lot_problem)
            $('#idDocNum', $('#frm_edit_prod_runcard_station_details')).val(stationDetails.prod_runcard_details.guaranteed_lot_doc_no)
          }
          else{
            $('#divGuaranteedLot').css('display', 'none');
          }

          setTimeout(() => {

            $('#txt_edit_prod_runcard_substation').val(stationDetails['sub_station_id']).trigger('change');
          }, 400);

          $('#mdl_edit_prod_runcard_station_details').modal('show');
        },
        error: function(data, xhr, status){
          console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
      });
    }

    function removeSelection(cboElement){
      $(`#${cboElement}`).val(0).trigger('change')

    }

    function generateQrCode(runcardId){
      $.ajax({
        type: "get",
        url: "generate_qr_code_runcard",
        data: {
          runcard_id : runcardId
        },
        dataType: "json",
        beforeSend: function(){
        },
        success: function (response) {
          runcardQRCode = response['qr_hidden'];

          $("#img_barcode").attr('src', response['qrCode']);
          $("#barCodeLabel").html(response['label']);

          $('#modalRuncardQrCode').modal('show');
        },
        error: function(data, xhr, status){
          console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
      });
    }

    function getPPTSDropdowns(){
      $.ajax({
        type: "get",
        url: "get_ppts_dropdown",
        // data: "data",
        dataType: "json",

        success: function (response) {
          /**
           * 1 = product category
           * 2 = certification lot
           * 4 = assembly line
           */
          let prodCatOption, assemblyLineOption, certLotOption;

          prodCatOption += `<option selected disabled value=''>--SELECT--</option>`;
          certLotOption += `<option selected disabled value=''>--SELECT--</option>`;
          assemblyLineOption += `<option selected disabled value=''>--SELECT--</option>`;

          $.each(response[1], function (key, value) {
            prodCatOption += `<option value='${value.dropdown_description}'>${value.dropdown_description}</option>`;
          })
          $.each(response[2], function (key, value) {
            certLotOption += `<option value='${value.id}'>${value.dropdown_description}</option>`;
          })
          $.each(response[4], function (key, value) {
            assemblyLineOption += `<option value='${value.dropdown_description}'>${value.dropdown_description}</option>`;
          })
          $('#selProdCategory').append(prodCatOption);
          $('#selAssemblyLine').append(assemblyLineOption);
          $('#selCertLot').append(certLotOption);
        },
        error: function(data, xhr, status){
          console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
      });
    }
  </script>
  @endsection
@endauth
