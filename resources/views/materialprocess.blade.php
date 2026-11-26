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

  @section('title', 'Material Process')

  @section('content_page')

  <style type="text/css">
    .hidden_scanner_input{
      position: absolute;
      opacity: 0;
    }
    textarea{
      resize: none;
    }

    #colDevice, #colMaterialProcess{
      transition: .5s;
    }
  </style>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Material Process</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Material Process</li>
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
          <div class="col-md-6" id="colDevice">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Device (Production Matrix)</h3>
              </div>

              <!-- Start Page Content -->
              <div class="card-body">
                  <div style="float: right;">
                    @if(Auth::user()->user_level_id == 1)
                      <button class="btn btn-primary" data-toggle="modal" data-target="#modalImportPackingMatrix" id="btnShowImport" title="Import Packing Matrix"><i class="fa fa-file-excel"></i> Import</button>
                    @else
                      @if(Auth::user()->position == 7 || Auth::user()->position == 8)
                        <button class="btn btn-primary" data-toggle="modal" data-target="#modalImportPackingMatrix" id="btnShowImport" title="Import Packing Matrix"><i class="fa fa-file-excel"></i> Import</button>
                      @endif
                    @endif

                    <button class="btn btn-primary" data-toggle="modal" data-target="#modalAddDevice" id="btnShowAddDeviceModal"><i class="fa fa-initial-icon"></i> Add Device</button>
                  </div> <br><br>
                  <div class="table-responsive">
                     <!-- style="max-height: 600px; overflow-y: auto;" -->
                    <table id="tblDevices" class="table table-sm table-bordered table-striped table-hover" style="width: 100%;">
                      <thead>
                        <tr>
                          <th>Code</th>
                          <th>Name</th>
                          <th>Huawei P/N</th>
                          <th>Lot # Machine Code</th>
                          <th>MRP No.</th>
                          <th>Qty per Reel/Bundle of Trays</th>
                          <th>Qty Per Box</th>
                          <th>Product Type</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
              </div>
              <!-- !-- End Page Content -->

            </div>
            <!-- /.card -->
          </div>

          <div class="col-md-6" id="colMaterialProcess">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <button class="btn btn-sm btn-secondary float-right ml-3 py-0 px-1 " title="Maximize" id="btnMaximizeColMatProc"><i class="fas fa-arrows-alt-h"></i></button>
                <button class="btn btn-sm btn-secondary float-right ml-3 py-0 px-1 " title="Minimize" id="btnMinimizeColMatProc" style="display: none;"><i class="fas fa-arrows-alt-h"></i></button>
                <h3 class="card-title">Material Process</h3>
              </div>

              <!-- Start Page Content -->
              <div class="card-body">
                  <div style="float: right;">
                    <button class="btn btn-primary" id="btnShowAddMatProcModal"><i class="fa fa-initial-icon"></i> Add Material Process</button>
                  </div>
                  <div style="float: left;">
                    <label>Device: <u id="uSelectedDeviceName">No Selected Device</u></label>
                  </div>
                  <br><br>
                  <div class="row">
                    <div class="col-sm-4">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">Status</span>
                        </div>
                        <select class="form-control select2 select2bs4 selectUser" id="selFilterMatProcStat">
                          <option value="1"> Active </option>
                          <option value="2"> Inactive </option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table id="tblMatProcesses" class="table table-sm table-bordered table-striped table-hover" style="width: 100%;">
                      <thead>
                        <tr>
                          <th>Step</th>
                          <th>Station ID</th>
                          <th>Station</th>
                          <th>Process</th>
                          <th>Machine</th>
                          <th>Certified Operator (A-Shift)</th>
                          <th>Certified Operator (B-Shift)</th>
                          <th>Material</th>
                          <th>Action</th>
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
  <div class="modal fade" id="modalAddDevice">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-plus"></i> Add Device</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formAddDevice">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label>Code</label>
                    <input type="text" class="form-control" name="barcode" id="txtAddDeviceBarcode">
                </div>

                <div class="form-group">
                  <label>Device Name</label>
                    <input type="text" class="form-control" name="name" id="txtAddDeviceName">
                </div>

                <div class="form-group">
                  <label>Huawei P/N</label>
                    <input type="text" class="form-control" name="huawei_p_n" id="txtAddDeviceHuaweiPN">
                </div>

               <div class="form-group">
                  <label>Lot No. Machine Code</label>
                    <input type="text" class="form-control" name="lot_no_machine_code" id="txtAddDeviceLotNoMachineCode">
                </div>

                <div class="form-group">
                  <label>MRP No.</label>
                    <input type="text" class="form-control" name="mrp_no" id="txtAddDeviceMRPNo">
                </div>

                <div class="form-group">
                  <label>Qty per Reel/Bundle of Trays</label>
                    <input type="number" min="1" class="form-control" name="boxing" id="txtAddDeviceBoxing">
                </div>

                <div class="form-group">
                  <label>Qty per Box</label>
                    <input type="number" min="1" class="form-control" name="ship_boxing" id="txtAddDeviceShipBoxing">
                </div>

                <div class="form-group">
                  <label>Product Type</label>
                    <select class="form-control" name="product_type" id="selAddDeviceProductType">
                      <option value="1">HUAWEI - PATS CN</option>
                      <option value="2">HUAWEI - CN PTS</option>
                      <option value="3">OTHERS - PATS CN</option>
                      <option value="4">OTHERS - CN PTS</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Packaging type</label>
                    {{-- <input type="text" min="1" class="form-control" name="ship_boxing" id="txtAddDeviceShipBoxing"> --}}
                    <select class="form-control" name="packaging_type" id="packType">
                        <option value="0">Reel type</option>
                        <option value="1">Others</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Has UL Sticker?</label>
                    <select class="form-control" name="ul_requirement" id="selectULReqId">
                        <option value="0" selected>N/A</option>
                        <option value="1">Yes</option>
                    </select>
                </div>

              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" id="btnAddDevice" class="btn btn-primary"><i id="iBtnAddDeviceIcon" class="fa fa-check"></i> Save</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modalEditDevice">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-user"></i> Edit Device</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formEditDevice">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                <input type="hidden" class="form-control" name="device_id" id="txtEditDeviceId">
                <div class="form-group">
                  <label>Code</label>
                    <input type="text" class="form-control" name="barcode" id="txtEditDeviceBarcode" readonly>
                </div>

                <div class="form-group">
                  <label>Device Name</label>
                    <input type="text" class="form-control" name="name" id="txtEditDeviceName">
                </div>

                <div class="form-group">
                  <label>Huawei P/N</label>
                    <input type="text" class="form-control" name="huawei_p_n" id="txtEditDeviceHuaweiPN">
                </div>

               <div class="form-group">
                  <label>Lot No. Machine Code</label>
                    <input type="text" class="form-control" name="lot_no_machine_code" id="txtEditDeviceLotNoMachineCode">
                </div>

                <div class="form-group">
                  <label>MRP No.</label>
                    <input type="text" class="form-control" name="mrp_no" id="txtEditDeviceMRPNo">
                </div>

                <div class="form-group">
                  <label>Boxing</label>
                    <input type="number" min="1" class="form-control" name="boxing" id="txtEditDeviceBoxing">
                </div>

                <div class="form-group">
                  <label>Shipping Boxing</label>
                    <input type="number" min="1" class="form-control" name="ship_boxing" id="txtEditDeviceShipBoxing">
                </div>

                <div class="form-group">
                  <label>Product Type</label>
                    <select class="form-control" name="product_type" id="selEditDeviceProductType">
                      <option value="1">HUAWEI - PATS CN</option>
                      <option value="2">HUAWEI - CN PTS</option>
                      <option value="3">OTHERS - PATS CN</option>
                      <option value="4">OTHERS - CN PTS</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Packaging type</label>
                    {{-- <input type="text" min="1" class="form-control" name="ship_boxing" id="txtAddDeviceShipBoxing"> --}}
                    <select class="form-control" name="packaging_type" id="editPackType">
                        <option value="0">Reel type</option>
                        <option value="1">Others</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Has UL Sticker?</label>
                    <select class="form-control" name="ul_requirement" id="editSelectULReqId">
                        <option value="0" selected>N/A</option>
                        <option value="1">Yes</option>
                    </select>
                </div>

              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" id="btnEditDevice" class="btn btn-primary"><i id="iBtnEditDeviceIcon" class="fa fa-check"></i> Save</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modalChangeDeviceStat">
    <div class="modal-dialog">
      <div class="modal-content modal-sm">
        <div class="modal-header">
          <h4 class="modal-title" id="h4ChangeDeviceTitle"><i class="fa fa-user"></i> Change Status</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formChangeDeviceStat">
          @csrf
          <div class="modal-body">
            <label id="lblChangeDeviceStatLabel">Are you sure to ?</label>
            <input type="hidden" name="device_id" placeholder="Device Id" id="txtChangeDeviceStatDeviceId">
            <input type="hidden" name="status" placeholder="Status" id="txtChangeDeviceStatDeviceStat">
            <input type="hidden" name="barcode" placeholder="Status" id="txtChangeDeviceStatDeviceBarcode">
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            <button type="submit" id="btnChangeDeviceStat" class="btn btn-primary"><i id="iBtnChangeDeviceStatIcon" class="fa fa-check"></i> Yes</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->


  <!-- MATERIAL PROCESS MODALS -->
  <div class="modal fade" id="modalAddMatProc">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-initial-icon"></i> Add Material Process</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formAddMatProc">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                <input type="hidden" name="device_id" id="txtAddMatProcDevId">
                
                <div class="form-group">
                  <label>Device Name</label>
                    <input type="text" class="form-control" name="device_name" id="txtAddMatProcDeviceName" readonly>
                </div>

                <div class="form-group">
                  <label>Step</label>
                    <input type="text" min="1" class="form-control" name="step" id="txtAddMatProcStep">
                </div>

                <div class="form-group">
                  <div class="row">
                    <div class="col-sm-4">
                      <label>Process</label>
                    </div>
                    <div class="col-sm-3">
                      <label><input type="checkbox" name="has_emboss" value="1" id="chkAddMatProcHasEmboss"> For Emboss</label>
                    </div>
                    <div class="col-sm-5">
                      <label><input type="checkbox" name="require_oqc_before_emboss" value="1" id="chkAddMatProcReqOQCBeforeEmboss" disabled="true"> Require OQC Before Emboss</label>
                    </div> 
                  </div>

                  <select class="form-control select2bs4 selectSubStation" name="station_sub_station_id" id="selAddMatProcSubStationId" style="width: 100%;">
                    <!-- Code generated -->
                  </select>

                </div>

                <!-- <div class="form-group">
                  <label>Material</label>
                    <input type="text" class="form-control" name="material" id="txtAddMatProcMaterial">
                </div> -->

                <div class="form-group">
                  <label>Material Kitting & Issuance Item</label>
                  <div class="input-group input-group-sm mb-3">
                    <select class="form-control select2 select2bs4 selWBSMatKitItem" id="selAddMatProcMatKitItem" name="material_kitting_item[]" multiple="multiple">
                      <!-- <option value=""> N/A </option> -->
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label>Sakidashi Issuance Item</label>
                  <div class="input-group input-group-sm mb-3">
                    <select class="form-control select2 select2bs4 selWBSSakIssuItem" id="selAddMatProcSakIssuItem" name="sakidashi_item[]" multiple="multiple">
                      <!-- <option value=""> N/A </option> -->
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label>Emboss Item</label>
                  <div class="input-group input-group-sm mb-3">
                    <select class="form-control select2 select2bs4 selWBSEmbossIssuItem" id="selAddMatProcEmbossItem" name="emboss_item[]" multiple="multiple">
                      <!-- <option value=""> N/A </option> -->
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label>Machine</label>
                  <div class="input-group input-group-sm mb-3">
                    <select class="form-control select2 select2bs4 selectMachine" id="selAddMatProcMachine" name="machine_id[]" multiple="multiple">
                      <option value=""> N/A </option>
                    </select>
                    <div class="input-group-append">
                      <button class="btn btn-info" type="button" title="Scan code" id="btnAddMatProcScanMachineCode"><i class="fa fa-qrcode"></i></button>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label>Certified Operator (A-Shift)</label>
                  <div class="input-group input-group-sm mb-3">
                    <select class="form-control select2 select2bs4 selUser" id="selAddMatProcAShiftManpower" name="a_shift_user_id[]" multiple="multiple">
                      <!-- <option value=""> N/A </option> -->
                    </select>
                    <div class="input-group-append">
                      <button class="btn btn-info" type="button" title="Scan code" id="btnAddMatProcScanAShiftUserCode"><i class="fa fa-qrcode"></i></button>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label>Certified Operator (B-Shift)</label>
                  <div class="input-group input-group-sm mb-3">
                    <select class="form-control select2 select2bs4 selUser" id="selAddMatProcBShiftManpower" name="b_shift_user_id[]" multiple="multiple">
                      <!-- <option value=""> N/A </option> -->
                    </select>
                    <div class="input-group-append">
                      <button class="btn btn-info" type="button" title="Scan code" id="btnAddMatProcScanBShiftUserCode"><i class="fa fa-qrcode"></i></button>
                    </div>
                  </div>
                </div>
                
                <label>Additional Process</label>
                <div class="form-group">
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="selPrintLabel" name="print_label">
                    <label class="form-check-label" for="selPrintLabel">
                      Print of label
                    </label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="selInpReelLot" name="inp_reel_lot">
                    <label class="form-check-label" for="selInpReelLot">
                      Inputting of Reel Lot
                    </label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="selInpPrintLot" name="inp_print_lot">
                    <label class="form-check-label" for="selInpPrintLot">
                      Inputting of Print Lot
                    </label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="selInpGuaranteedLot" name="inp_guaranteed_lot">
                    <label class="form-check-label" for="selInpGuaranteedLot">
                      Inputting of Guaranteed Lot
                    </label>
                  </div>
                </div>

              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" id="btnAddMatProc" class="btn btn-primary"><i id="iBtnAddMatProcIcon" class="fa fa-check"></i> Save</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <!-- MATERIAL PROCESS MODALS -->
  <div class="modal fade" id="modalEditMatProc">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-initial-icon"></i> Edit Material Process</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formEditMatProc">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                <input type="hidden" name="device_id" id="txtEditMatProcDevId">
                <input type="hidden" name="material_process_id" id="txtEditMatProcId">
                
                <div class="form-group">
                  <label>Device Name</label>
                    <input type="text" class="form-control" name="device_name" id="txtEditMatProcDeviceName" readonly>
                </div>

                <div class="form-group">
                  <label>Step</label>
                    <input type="text" min="1" class="form-control" name="step" id="txtEditMatProcStep">
                </div>

                <div class="form-group">
                  <!-- <label>Process</label>
                  <div style="float: right">
                    <label><input type="checkbox" name="has_emboss" value="1" id="chkEditMatProcHasEmboss"> For Emboss</label>
                  </div>
                  <select class="form-control select2bs4 selectSubStation" name="station_sub_station_id" id="selEditMatProcSubStationId" style="width: 100%;">
                  </select> -->

                  <div class="row">
                    <div class="col-sm-4">
                        <label>Process</label>
                    </div>
                    <div class="col-sm-3">
                      <label><input type="checkbox" name="has_emboss" value="1" id="chkEditMatProcHasEmboss"> For Emboss</label>
                    </div>
                    <div class="col-sm-5">
                      <label><input type="checkbox" name="require_oqc_before_emboss" value="1" id="chkEditMatProcReqOQCBeforeEmboss"> Require OQC Before Emboss</label>
                    </div> 
                  </div>
                  <select class="form-control select2bs4 selectSubStation" name="station_sub_station_id" id="selEditMatProcSubStationId" style="width: 100%;">
                  </select>
                </div>

                <!-- <div class="form-group">
                  <label>Material</label>
                    <input type="text" class="form-control" name="material" id="txtEditMatProcMaterial">
                </div> -->

                <div class="form-group">
                  <label>Material Kitting & Issuance Item</label>
                  <div class="input-group input-group-sm mb-3">
                    <select class="form-control select2 select2bs4 selWBSMatKitItem" id="selEditMatProcMatKitItem" name="material_kitting_item[]" multiple="multiple">
                      <!-- <option value=""> N/A </option> -->
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label>Sakidashi Issuance Item</label>
                  <div class="input-group input-group-sm mb-3">
                    <select class="form-control select2 select2bs4 selWBSSakIssuItem" id="selEditMatProcSakIssuItem" name="sakidashi_item[]" multiple="multiple">
                      <!-- <option value=""> N/A </option> -->
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label>Emboss Item</label>
                  <div class="input-group input-group-sm mb-3">
                    <select class="form-control select2 select2bs4 selWBSEmbossIssuItem" id="selEditMatProcEmbossItem" name="emboss_item[]" multiple="multiple">
                      <!-- <option value=""> N/A </option> -->
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label>Machine</label>
                  <div class="input-group input-group-sm mb-3">
                    <select class="form-control select2 select2bs4 selectMachine" id="selEditMatProcMachine" name="machine_id[]" multiple="multiple">
                      <option value=""> N/A </option>
                    </select>
                    <div class="input-group-append">
                      <button class="btn btn-info" type="button" title="Scan code" id="btnEditMatProcScanMachineCode"><i class="fa fa-qrcode"></i></button>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label>Certified Operator (A-Shift)</label>
                  <div class="input-group input-group-sm mb-3">
                    <select class="form-control select2 select2bs4 selUser" id="selEditMatProcAShiftManpower" name="a_shift_user_id[]" multiple="multiple">
                      <!-- <option value=""> N/A </option> -->
                    </select>
                    <div class="input-group-append">
                      <button class="btn btn-info" type="button" title="Scan code" id="btnEditMatProcScanAUserCode"><i class="fa fa-qrcode"></i></button>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label>Certified Operator (B-Shift)</label>
                  <div class="input-group input-group-sm mb-3">
                    <select class="form-control select2 select2bs4 selUser" id="selEditMatProcBShiftManpower" name="b_shift_user_id[]" multiple="multiple">
                      <!-- <option value=""> N/A </option> -->
                    </select>
                    <div class="input-group-append">
                      <button class="btn btn-info" type="button" title="Scan code" id="btnEditMatProcScanBUserCode"><i class="fa fa-qrcode"></i></button>
                    </div>
                  </div>
                </div>

                <label>Additional Process</label>
                <div class="form-group">
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="selEditPrintLabel" name="print_label">
                    <label class="form-check-label" for="selEditPrintLabel">
                      Print of label
                    </label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="selEditInpReelLot" name="inp_reel_lot">
                    <label class="form-check-label" for="selEditInpReelLot">
                      Inputting of Reel Lot
                    </label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="selEditInpPrintLot" name="inp_print_lot">
                    <label class="form-check-label" for="selEditInpPrintLot">
                      Inputting of Print Lot
                    </label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="selEditInpGuaranteedLot" name="inp_guaranteed_lot">
                    <label class="form-check-label" for="selEditInpGuaranteedLot">
                      Inputting of Guaranteed Lot
                    </label>
                  </div>
                </div>

              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" id="btnEditMatProc" class="btn btn-primary"><i id="iBtnEditMatProcIcon" class="fa fa-check"></i> Save</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modalImportPackingMatrix">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-file-excel"></i> Import Packing Matrix</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formImportPackingMatrix" enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12">
                <div class="form-group">
                  <label>File</label>
                    <input type="file" class="form-control" name="import_file" id="fileImportPackingMatrix">
                </div>

                <div class="table-responsive">
                  <table class="table table-bordered" id="tblImportResult">
                    <thead>
                      <tr>
                        <th>No. of Inserted</th>
                        <th class="thNoOfInserted">0</th>
                      </tr>
                      <tr>
                        <th>No. of Updated</th>
                        <th class="thNoOfUpdated">0</th>
                      </tr>
                      <tr>
                        <th>No. of Failed</th>
                        <th class="thNoOfFailed">0</th>
                      </tr>
                      <tr>
                        <th colspan="2"><center>List of Failed Product Code</center></th>
                      </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" id="btnImportPackingMatrix" class="btn btn-primary"><i id="iBtnImportPackingMatrixIcon" class="fa fa-check"></i> Import</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modalChangeMatProcStat">
    <div class="modal-dialog">
      <div class="modal-content modal-sm">
        <div class="modal-header">
          <h4 class="modal-title" id="h4ChangeMatProcTitle"><i class="fa fa-default"></i> Change Status</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="formChangeMatProcStat">
          @csrf
          <div class="modal-body">
            <label id="lblChangeMatProcStatLabel">Are you sure to ?</label>
            <input type="hidden" name="material_process_id" placeholder="Material Process Id" id="txtChangeMatProcStatMatProcId">
            <input type="hidden" name="status" placeholder="Status" id="txtChangeMatProcStatMatProcStat">
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            <button type="submit" id="btnChangeMatProcStat" class="btn btn-primary"><i id="iBtnChangeMatProcStatIcon" class="fa fa-check"></i> Yes</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

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
  @endsection

  @section('js_content')
  <script type="text/javascript">
    // Device
    let dataTableDevices;
    
    // Material Process
    let dataTableMatProcess;
    let selectedDeviceId = 0;
    let selectedDeviceName = '';

    $(document).ready(function () {
      //Initialize Select2 Elements
      $('.select2').select2();

      //Initialize Select2 Elements
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });

      GetUserList($(".selUser"));

      GetCboMachine($(".selectMachine"));

      GetMaterialKittingList($(".selWBSMatKitItem"));
      GetSakidashiList($(".selWBSSakIssuItem"));
      GetEmbossList($(".selWBSEmbossIssuItem")); 

      $('#modalAddMatProc').on('hidden.bs.modal', function(){
        $('#formAddMatProc')[0].reset();
      })
      $('#modalEditMatProc').on('hidden.bs.modal', function(){
        $('#formEditMatProc')[0].reset();
      })

      $(document).on('click','#tblDevices tbody tr',function(e){
        $(this).closest('tbody').find('tr').removeClass('table-active');
        $(this).closest('tr').addClass('table-active');
        selectedDeviceId = $(this).closest('tbody tr.table-active').find('.td_device_id').val();
        selectedDeviceName = $(this).closest('tbody tr.table-active').find('.td_device_name').val();

        $("#uSelectedDeviceName").text(selectedDeviceName);
        $("#txtAddSubDeviceDeviceId").val(selectedDeviceId);
        $("#txtAddSubDeviceDeviceName").val(selectedDeviceName);

        dataTableMatProcess.draw();
      });

      $("#chkAddMatProcHasEmboss").click(function(){
        if($(this).prop('checked') == true){
          // $("#selAddMatProcEmbossItem").prop('disabled', false);
          // $("#selAddMatProcMatKitItem").prop('disabled', true);
          // $("#selAddMatProcSakIssuItem").prop('disabled', true);
          // alert('check');
          $("#chkAddMatProcReqOQCBeforeEmboss").prop('disabled', false);
          $("#chkAddMatProcReqOQCBeforeEmboss").prop('checked', false);
        }
        else{
          // $("#selAddMatProcEmbossItem").prop('disabled', true);
          // $("#selAddMatProcMatKitItem").prop('disabled', false);
          // $("#selAddMatProcSakIssuItem").prop('disabled', false);
          // alert('uncheck');
          $("#chkAddMatProcReqOQCBeforeEmboss").prop('disabled', true); 
          $("#chkAddMatProcReqOQCBeforeEmboss").prop('checked', false);
        }
      });

      $("#chkEditMatProcHasEmboss").click(function(){
        if($(this).prop('checked') == true){
          $("#chkEditMatProcReqOQCBeforeEmboss").prop('disabled', false);
        }
        else{
          $("#chkEditMatProcReqOQCBeforeEmboss").prop('disabled', true);
        }
      });

      $("#chkEditMatProcHasEmboss").click(function(){
        if($(this).prop('checked') == true){
          // $("#selEditMatProcEmbossItem").prop('disabled', false);
          // $("#selEditMatProcMatKitItem").prop('disabled', true);
          // $("#selEditMatProcSakIssuItem").prop('disabled', true);
          // alert('check');
        }
        else{
          // $("#selEditMatProcEmbossItem").prop('disabled', true);
          // $("#selEditMatProcMatKitItem").prop('disabled', false);
          // $("#selEditMatProcSakIssuItem").prop('disabled', false);
          // alert('uncheck');
        }
      });

      $(document).on('click','#tblMatProcesses tbody tr',function(e){
        $(this).closest('tbody').find('tr').removeClass('table-active');
        $(this).closest('tr').addClass('table-active');
      });

      dataTableDevices = $("#tblDevices").DataTable({
        "processing" : true,
          "serverSide" : true,
          "ajax" : {
            url: "view_devices",
            // data: function (param){
            //     param.status = $("#selEmpStat").val();
            // }
          },
          fixedHeader: true,
          "columns":[
            { "data" : "barcode" },
            { "data" : "name" },
            { "data" : "huawei_p_n" },
            { "data" : "lot_no_machine_code" },
            { "data" : "mrp_no" },
            { "data" : "boxing" },
            { "data" : "ship_boxing" },
            { "data" : "raw_product_type" },
            { "data" : "label1" },
            { "data" : "action1", orderable:false, searchable:false }
          ],
          "columnDefs": [
            { className: "bg-info", "targets": [ 5 ] }
          ],
          // "scrollY": "400px",
          // "scrollCollapse": true,
        });//end of dataTableDevices

        // Add Device 
        $("#formAddDevice").submit(function(event){
          event.preventDefault();
          AddDevice();
        });

        $("#btnShowAddDeviceModal").click(function(){
          $("#txtAddDeviceName").removeClass('is-invalid');
          $("#txtAddDeviceName").attr('title', '');
          $("#txtAddDeviceBarcode").removeClass('is-invalid');
          $("#txtAddDeviceBarcode").attr('title', '');
        });

        $("#btnShowImport").click(function(){
          $(".thNoOfInserted").text('0');
          $(".thNoOfUpdated").text('0');
          $(".thNoOfFailed").text('0');
          $("#tblImportResult tbody").html('');
        });

        // Edit Device
        $(document).on('click', '.aEditDevice', function(){
          let deviceId = $(this).attr('device-id');
          $("#txtEditDeviceId").val(deviceId);
          GetDeviceByIdToEdit(deviceId);
          $("#txtEditDeviceName").removeClass('is-invalid');
          $("#txtEditDeviceName").attr('title', '');
          $("#txtEditDeviceBarcode").removeClass('is-invalid');
          $("#txtEditDeviceBarcode").attr('title', '');
        });

        $("#chkEditUserWithEmail").click(function(){
          if($(this).prop('checked')) {
            $("#txtEditUserEmail").removeAttr('disabled');
            $("#txtEditUserEmail").val($("#txtEditUserCurrEmail").val());
          }
          else{
            $("#txtEditUserEmail").prop('disabled', 'disabled');
            $("#txtEditUserEmail").val('');
          }
        });

        $("#formEditDevice").submit(function(event){
          event.preventDefault();
          EditDevice();
        });

        // Change Device Status
        $(document).on('click', '.aChangeDeviceStat', function(){
          let deviceStat = $(this).attr('status');
          let deviceId = $(this).attr('device-id');
          let deviceBarcode = $(this).attr('device-barcode');

          $("#txtChangeDeviceStatDeviceId").val(deviceId);
          $("#txtChangeDeviceStatDeviceStat").val(deviceStat);
          $("#txtChangeDeviceStatDeviceBarcode").val(deviceBarcode);

          if(deviceStat == 1){
            $("#lblChangeDeviceStatLabel").text('Are you sure to activate?'); 
            $("#h4ChangeDeviceTitle").html('<i class="fa fa-user"></i> Activate Device');
          }
          else{
            $("#lblChangeDeviceStatLabel").text('Are you sure to deactivate?');
            $("#h4ChangeDeviceTitle").html('<i class="fa fa-user"></i> Deactivate Device');
          }
        });

        $("#formChangeDeviceStat").submit(function(event){
          event.preventDefault();
          ChangeDeviceStatus();
        });
      });


      // Material Process
      $(document).ready(function(){
        GetCboStationSubStation($(".selectSubStation"), 1);
        let groupColumnMatProc = 2;
        dataTableMatProcess = $("#tblMatProcesses").DataTable({
          "processing" : true,
          "serverSide" : true,
          "ajax" : {
            url: "view_material_process_by_device_id",
            data: function (param){
                param.device_id = selectedDeviceId;
                param.status = $("#selFilterMatProcStat").val();
            }
          },
          
          "columns":[
            { "data" : "step" },
            // { "data" : "station_id" },
            // { "data" : "station_name" },
            // { "data" : "sub_station_name" },
            { "data" : "station_sub_station.station.id" },
            { "data" : "station_sub_station.station.name" },
            { "data" : "station_sub_station.sub_station.name" },
            // { "data" : "machine_details", "render" : "[ / ].machine_info.name", orderable:false, searchable:false},
            {
                name: 'machine_details',
                data: 'machine_details',
                sortable: false,
                searchable: false,
                render: function (data) {
                  // console.log(data);
                    var result = '';
                    for(let index = 0; index < data.length; index++){
                      result += '<span class="badge badge-pill badge-secondary">' + data[index]['machine_info'].name + '</span>';

                      if(index <= parseInt(data.length) - 2){
                        result += '<br>';
                      }
                    }
                    return result;
                }
            },
            // { "data" : "a_shift_manpowers_details", "render" : "[ / ].manpower_info.name", orderable:false, searchable:false},
            {
                name: 'a_shift_manpowers_details',
                data: 'a_shift_manpowers_details',
                sortable: false,
                searchable: false,
                render: function (data) {
                  console.log('a_shift_manpowers_details', data);
                    var result = '';
                    for(let index = 0; index < data.length; index++){
                      if(data[index]['manpower_info'] != null){
                        result += '<span class="badge badge-pill badge-info">' + data[index]['manpower_info']['name'] + '</span>';

                      }
                      // console.log("data[index]['manpower_info']", data[index]['manpower_info'])
                      // result += '<span class="badge badge-pill badge-info">' + data[index]['manpower_info']+ '</span>';

                      if(index <= parseInt(data.length) - 2){
                        result += '<br>';
                      }
                    }
                    return result;
                }
            },
            // { "data" : "b_shift_manpowers_details", "render" : "[ / ].manpower_info.name", orderable:false, searchable:false},
            {
                name: 'b_shift_manpowers_details',
                data: 'b_shift_manpowers_details',
                sortable: false,
                searchable: false,
                render: function (data) {
                  // console.log(data);
                    var result = '';
                    for(let index = 0; index < data.length; index++){
                      if(data[index]['manpower_info'] != null){
                        result += '<span class="badge badge-pill badge-info">' + data[index]['manpower_info']['name'] + '</span>';

                      }
                      // result += '<span class="badge badge-pill badge-info">' + data[index]['manpower_info']['name'] + '</span>';

                      if(index <= parseInt(data.length) - 2){
                        result += '<br>';
                      }
                    }
                    return result;
                }
            },
            // { "data" : "material_details", "render" : "[ / ].item_desc", orderable:false, searchable:false},
            {
                name: 'material_details',
                data: 'material_details',
                sortable: false,
                searchable: false,
                render: function (data) {
                  // console.log(data);
                    var result = '';
                    for(let index = 0; index < data.length; index++){
                      result += '<span class="badge badge-pill badge-secondary">' + data[index]['item_desc'] + '</span>';

                      if(index <= parseInt(data.length) - 2){
                        result += '<br>';
                      }
                    }
                    return result;
                }
            },
            // { "data" : "label1" },
            { "data" : "action1", orderable:false, searchable:false }
          ],
          "columnDefs": [
              { "visible": false, "targets": groupColumnMatProc },
              { "visible": false, "targets": 1 },
              {
                "targets": [4, 8],
                "data": null,
                "defaultContent": "N/A"
              },
          ],
          "order": [[ 0, 'asc' ]],
          "displayLength": 10,
          // "scrollY": "400px",
          // "scrollCollapse": true,
          "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last = null;

            api.column(groupColumnMatProc, {page:'current'} ).data().each( function ( group, i ) {
                if ( last !== group ) {

                  let data = api.row(i).data();
                  let station_name = data.station_sub_station.station.name;

                    $(rows).eq( i ).before(
                        '<tr class="group bg-info"><td colspan="7" style="text-align:center;"><b>' + station_name + '</b></td></tr>'
                    );

                    last = group;
                }
            });
          }
        });//end of dataTableSubStations

        // $(document).on('click', '.aShowDeviceDevProc', function(){
        //   let deviceId = $(this).attr('device-id');
        //   let deviceName = $(this).attr('device-name');

        //   $("#uSelectedDeviceName").text(deviceName);
        //   selectedDeviceId = deviceId;
        //   $("#txtAddSubDeviceDeviceId").val(selectedDeviceId);
        //   selectedDeviceName = deviceName;
        //   $("#txtAddSubDeviceDeviceName").val(selectedDeviceName);
        //   dataTableMatProcess.draw();
        // });

        $("#selFilterMatProcStat").change(function(){
          dataTableMatProcess.draw();
        });

        $("#btnShowAddMatProcModal").click(function(){
          
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
          
          if(selectedDeviceId != 0){
            $("#modalAddMatProc").modal('show');
            $("#txtAddMatProcDevId").val(selectedDeviceId);
            $("#txtAddMatProcDeviceName").val(selectedDeviceName);

            $("#txtAddMatProcDevId").removeClass('is-invalid');
            $("#txtAddMatProcDevId").attr('title', '');
            $("#txtAddMatProcDeviceName").removeClass('is-invalid');
            $("#txtAddMatProcDeviceName").attr('title', '');
          }
          else{
            toastr.warning('No Selected Device!');
          }
        });

        $(document).on('click', '.aEditMatProc', function(){
          let matProcId = $(this).attr('material-process-id');
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
          
          if(selectedDeviceId != 0){
            $("#txtEditMatProcId").val(matProcId);
            $("#modalEditMatProc").modal('show');
            $("#txtEditMatProcDevId").val(selectedDeviceId);
            $("#txtEditMatProcDeviceName").val(selectedDeviceName);

            $("#txtEditMatProcDevId").removeClass('is-invalid');
            $("#txtEditMatProcDevId").attr('title', '');
            $("#txtEditMatProcDeviceName").removeClass('is-invalid');
            $("#txtEditMatProcDeviceName").attr('title', '');

            GetMatProcByIdToEdit(matProcId);
          }
          else{
            toastr.warning('No Selected Device!');
          }
        });

        // Add Material Process 
        $("#formAddMatProc").submit(function(event){
          event.preventDefault();
          AddMaterialProcess();
        });

        // Edit Material Process 
        $("#formEditMatProc").submit(function(event){
          event.preventDefault();
          EditMaterialProcess();
        });

        $("#formChangeMatProcStat").submit(function(event){
          event.preventDefault();
          ChangeMatProcStat();
        });

        // Change MatProc Status
        $(document).on('click', '.aChangeMatProcStat', function(){
          let matProcStat = $(this).attr('status');
          let matProcId = $(this).attr('material-process-id');

          $("#txtChangeMatProcStatMatProcId").val(matProcId);
          $("#txtChangeMatProcStatMatProcStat").val(matProcStat);

          if(matProcStat == 1){
            $("#lblChangeMatProcStatLabel").text('Are you sure to activate?'); 
            $("#h4ChangeMatProcTitle").html('<i class="fa fa-default"></i> Activate Material Process');
          }
          else{
            $("#lblChangeMatProcStatLabel").text('Are you sure to deactivate?');
            $("#h4ChangeMatProcTitle").html('<i class="fa fa-default"></i> Deactivate Material Process');
          }
        });

        $("#formImportPackingMatrix").submit(function(event){
            event.preventDefault();
            $.ajax({
                url: 'import_packing_matrix_updater',
                method: 'post',
                data: new FormData(this),
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function(){
                    // alert('Loading...');
                    $("#tblImportResult tbody").html('');
                },
                success: function(JsonObject){
                    if(JsonObject['result'] == 1){
                      // let importText = 'Importing Success!<br># of Inserted: ' + JsonObject['inserted_barcode'].length + '<br># of Updated: ' + JsonObject['updated_barcode'].length + '<br># of Failed: ' + JsonObject['failed_barcode'].length;
                      toastr.success('Importing Success!');
                      dataTableDevices.draw();
                      // $("#modalImportPackingMatrix").modal('hide');
                      $(".thNoOfInserted").text(JsonObject['inserted_barcode'].length);
                      $(".thNoOfUpdated").text(JsonObject['updated_barcode'].length);
                      $(".thNoOfFailed").text(JsonObject['failed_barcode'].length);

                      let trFailedResults = '';

                      for(let index = 0; index < JsonObject['failed_barcode'].length; index++){
                        trFailedResults += '<tr><td colspan="2" style="color: red;"><center>' + JsonObject['failed_barcode'][index] + '</center></td></tr>';                 
                      }

                      $("#tblImportResult tbody").html(trFailedResults);
                    }
                    else{
                      toastr.error('Importing Failed!');
                    }
                },
                error: function(data, xhr, status){
                    console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
                }
            });
        });

        $(document).on('keypress',function(e){
          if( ($("#mdl_qrcode_scanner").data('bs.modal') || {})._isShown ){
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
                    if ($('#selAddMatProcMachine').find("option[data-code='" + val + "']").length) {
                      $('#selAddMatProcMachine option[data-code="'+val+'"]').prop('selected', true).trigger('change');
                    }
                    else{
                      $('#selAddMatProcMachine').val("").trigger('change');
                      toastr.warning('Invalid Machine!');
                    }
                    $('#txt_qrcode_scanner').val("");
                  break;
                  case 'fn_scan_machine_code_edit':
                    var val = $('#txt_qrcode_scanner').val();
                    if ($('#selEditMatProcMachine').find("option[data-code='" + val + "']").length) {
                      $('#selEditMatProcMachine option[data-code="'+val+'"]').prop('selected', true).trigger('change');
                    }
                    else{
                      $('#selAddMatProcMachine').val("").trigger('change');
                      toastr.warning('Invalid Machine!');
                    }
                    $('#txt_qrcode_scanner').val("");
                  break;

                  case 'fn_scan_a_shift_user_code':
                    var val = $('#txt_qrcode_scanner').val();

                    if ($('#selAddMatProcAShiftManpower').find("option[data-code='" + val + "']").length) {
                      $("#selAddMatProcAShiftManpower option[data-code="+val+"]").prop("selected", true).trigger("change");
                    }
                    else{
                      toastr.warning('Invalid User!');
                    }
                    $('#txt_qrcode_scanner').val("0");
                  break;

                  case 'fn_scan_a_shift_user_code_edit':
                    var val = $('#txt_qrcode_scanner').val();

                    if ($('#selEditMatProcAShiftManpower').find("option[data-code='" + val + "']").length) {
                      $("#selEditMatProcAShiftManpower option[data-code="+val+"]").prop("selected", true).trigger("change");
                    }
                    else{
                      toastr.warning('Invalid User!');
                    }
                    $('#txt_qrcode_scanner').val("0");
                  break;

                  case 'fn_scan_b_shift_user_code':
                    var val = $('#txt_qrcode_scanner').val();

                    if ($('#selAddMatProcBShiftManpower').find("option[data-code='" + val + "']").length) {
                      $("#selAddMatProcBShiftManpower option[data-code="+val+"]").prop("selected", true).trigger("change");
                    }
                    else{
                      toastr.warning('Invalid User!');
                    }
                    $('#txt_qrcode_scanner').val("0");
                  break;
                  case 'fn_scan_b_shift_user_code_edit':
                    var val = $('#txt_qrcode_scanner').val();

                    if ($('#selEditMatProcBShiftManpower').find("option[data-code='" + val + "']").length) {
                      $("#selEditMatProcBShiftManpower option[data-code="+val+"]").prop("selected", true).trigger("change");
                    }
                    else{
                      toastr.warning('Invalid User!');
                    }
                    $('#txt_qrcode_scanner').val("0");
                  break;

                  default:
                  break;
                }
              }            
            }//key
          }
        });

        $(document).on('click','#btnAddMatProcScanMachineCode',function(e){
          $('#txt_qrcode_scanner').val('');
          $('#mdl_qrcode_scanner').attr('data-formid','fn_scan_machine_code').modal('show');
        });

        $(document).on('click','#btnAddMatProcScanAShiftUserCode',function(e){
          $('#txt_qrcode_scanner').val('');
          $('#mdl_qrcode_scanner').attr('data-formid','fn_scan_a_shift_user_code').modal('show');
        });

        $(document).on('click','#btnAddMatProcScanBShiftUserCode',function(e){
          $('#txt_qrcode_scanner').val('');
          $('#mdl_qrcode_scanner').attr('data-formid','fn_scan_b_shift_user_code').modal('show');
        });

        $("#btnMaximizeColMatProc").click(function(){
          $("#colDevice").removeClass('col-md-6').addClass('col-md-2');
          $("#colMaterialProcess").removeClass('col-md-6').addClass('col-md-10');
          $(this).css({display: 'none'});
          $("#btnMinimizeColMatProc").css({display: 'block'});
        });

        $("#btnMinimizeColMatProc").click(function(){
          $("#colDevice").removeClass('col-md-2').addClass('col-md-6');
          $("#colMaterialProcess").removeClass('col-md-10').addClass('col-md-6');
          $(this).css({display: 'none'});
          $("#btnMaximizeColMatProc").css({display: 'block'});
        });

      });

      $(document).on('click','#btnEditMatProcScanMachineCode',function(e){
        $('#txt_qrcode_scanner').val('');
        $('#mdl_qrcode_scanner').attr('data-formid','fn_scan_machine_code_edit').modal('show');
      });
      $(document).on('click','#btnEditMatProcScanAUserCode',function(e){
        $('#txt_qrcode_scanner').val('');
        $('#mdl_qrcode_scanner').attr('data-formid','fn_scan_a_shift_user_code_edit').modal('show');
      });

      $(document).on('click','#btnEditMatProcScanBUserCode',function(e){
        $('#txt_qrcode_scanner').val('');
        $('#mdl_qrcode_scanner').attr('data-formid','fn_scan_b_shift_user_code_edit').modal('show');
      });
  </script>
  @endsection
@endauth