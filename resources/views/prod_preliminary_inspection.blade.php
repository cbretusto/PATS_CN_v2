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
  <!--START OF PRELIMINARY INSPECTION-->
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
            <h1>Production Packing Inspection</h1>
          </div>

          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Production Packing Inspection</li>
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
                      <table id="tblProdPrelimInspection" class="table table-bordered table-hover" style="width: 100%;">

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


  <form id="formProdPrelimInspection" method="post">
    @csrf
  <!--MODALS -->
  <div class="modal fade" id="modalProdPrelimInspection">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">

        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-edit"></i> Production Preliminary Inspection</h4>
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
              <input type="text" class="form-control" id="id_modal_device" name="name_modal_device" readonly="">
            </div>

            <div class="col-sm-2">
              <label>Packing Lot Quantity</label>
              <input type="text" class="form-control" id="id_modal_box_qty" readonly="">
            </div>
          </div>

          <br>

          <!--TOP CARD FOR CHECKING OF REEL LOT NUM VS PACKING CODE-->
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title"><span class="badge badge-secondary">1.</span> Check Reel Lot Numbers vs Packing Code</h3>
            </div>

            <div class="card-body">

              <div class="row">
                <div class="col-sm-3">
                  <label>Scan C3 Reel Lot Barcode</label>
                  <div class="input-group">
                      <div class="input-group-prepend">
                        <button type="button" class="btn btn-primary btn_search_c3_label" id="btn_search_c3_label" title="Click to Scan C3 Reel Lot Barcode"><i class="fa fa-barcode"></i></button>
                      </div>
                      <input type="text" class="form-control" id="id_c3_scan" readonly="">
                  </div>
                </div>

                <div class="col-sm-9">
                  <div class="float-right">
                    <button class="btn btn-success" id="btn_check_reel_lots" type="button" disabled><i class="fa fa-check-square" aria-hidden="true" ></i>&nbsp;&nbsp;View R Drawing & Proceed</button>
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
                            <th>Packing Code Reel Lotss</th>
                            <th>User-Scanned Reel Lots</th>
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

          <!--BOTTOM CARD FOR INSPECTION CHECKPOINTS-->
          <div class="card card-primary" id="id_second_point">
            <div class="card-header">
              <h3 class="card-title"><span class="badge badge-secondary">2.</span> Inspection Checkpoints fill-in</h3>
            </div>

            <div class="card-body">
              <div class="row">
                  
                <div class="col">
                  <h6><strong>Packing Type</strong></h6>
                  <div class="input-group input-group-sm mb-3">
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

                <div class="col">
                  <h6><strong>Unit Condition</strong></h6>
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

              <h6><strong>Packing Units Compliance to Manual</strong></h6>

              <div class="row">

                 <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1"> Orientation of Units</span>
                        </div>
                        <select class="form-control form-control-sm select_packop" name="name_ppi_orientation" id="id_ppi_orientation">
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
                          <span class="input-group-text w-100" id="basic-addon1">Qty. Per Box / Tray</span>
                        </div>
                        <select class="form-control form-control-sm select_packop" name="name_ppi_qty" id="id_ppi_qty">
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
                          <span class="input-group-text w-100" id="basic-addon1">UL Sticker</span>
                        </div>
                        <select class="form-control form-control-sm select_packop" name="name_ppi_ulsticker" id="id_ppi_ulsticker">
                          <option selected disabled>---</option>
                          <option value = "1">Yes</option>
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
                          <span class="input-group-text w-100" id="basic-addon1">Silica Gel</span>
                        </div>
                        <select class="form-control form-control-sm select_packop" name="name_ppi_silicagel" id="id_ppi_silicagel">
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
                          <span class="input-group-text w-100" id="basic-addon1">Acessories</span>
                        </div>
                        <select class="form-control form-control-sm select_packop" name="name_ppi_accessories" id="id_ppi_accessories">
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
                          <span class="input-group-text w-100" id="basic-addon1">ROHS Sticker</span>
                        </div>
                        <select class="form-control form-control-sm select_packop" name="name_ppi_rohs" id="id_ppi_rohs">
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
                           <span class="input-group-text primary w-100" style="background-color: rgb(57, 179, 215); color: rgb(255, 255, 255); border-color: rgb(57, 179, 215);" id="basic-addon1"><strong>OPERATOR JUDGEMENT</strong></span>
                        </div>
                        <select class="form-control form-control-sm" name="name_operator_judgement" id="id_operator_judgement" readonly>
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
            <button type="button" class="btn btn-success" id="btn_save_prodpreliminspection">Save Results</button>
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

</form> <!--END OF FORM PRELIM PACKING INSPECTION-->


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

   <!--SCAM PACKING CODE MODAL-->
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

   <!--SCAM PACKING CODE MODAL-->
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

  <!--SUPERVISOR INPUT-->

  <form id="formProdPrelimInspectionSupervisor" method="post">
    @csrf

  <div class="modal fade" id="modalProdPrelimInspectionSupervisor">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">

      <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-edit"></i> Production Preliminary Inspection (Supervisor)</h4>
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
              <input type="text" class="form-control" id="id_modal_supervisor_device" name="name_modal_supervisor_device" readonly="">
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

          <!--SUPERVIEROS FOR INSPECTION CHECKPOINTS-->
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Supervisor Inspection Checkpoints fill-in</h3>
            </div>

            <div class="card-body">
              <div class="row">
                  
                <div class="col">
                  <h6><strong>Packing Type</strong></h6>
                  <div class="input-group input-group-sm mb-3">
                      <select class="form-control form-control-sm" name="name_supervisor_PackopPackingType" id="id_supervisor_PackopPackingType">
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

                <div class="col">
                  <h6><strong>Unit Condition</strong></h6>
                  <div class="input-group input-group-sm mb-3">
                        <select class="form-control form-control-sm" name="name_supervisor_PackopUnitCondition" id="id_supervisor_PackopUnitCondition">
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

              <h6><strong>Packing Units Compliance to Manual</strong></h6>

              <div class="row">

                 <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1"> Orientation of Units</span>
                        </div>
                        <select class="form-control form-control-sm select_packop_supervisor" name="name_supervisor_ppi_orientation" id="idsupervisor__ppi_orientation">
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
                          <span class="input-group-text w-100" id="basic-addon1">Qty. Per Box / Tray</span>
                        </div>
                        <select class="form-control form-control-sm select_packop_supervisor" name="name_supervisor_ppi_qty" id="id_supervisor_ppi_qty">
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
                          <span class="input-group-text w-100" id="basic-addon1">UL Sticker</span>
                        </div>
                        <select class="form-control form-control-sm select_packop_supervisor" name="name_supervisor_ppi_ulsticker" id="id_supervisor_ppi_ulsticker">
                          <option selected disabled>---</option>
                          <option value = "1">Yes</option>
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
                          <span class="input-group-text w-100" id="basic-addon1">Silica Gel</span>
                        </div>
                        <select class="form-control form-control-sm select_packop_supervisor" name="name_supervisor_ppi_silicagel" id="id_supervisor_ppi_silicagel">
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
                          <span class="input-group-text w-100" id="basic-addon1">Acessories</span>
                        </div>
                        <select class="form-control form-control-sm select_packop_supervisor" name="name_supervisor_ppi_accessories" id="id_supervisor_ppi_accessories">
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
                          <span class="input-group-text w-100" id="basic-addon1">ROHS Sticker</span>
                        </div>
                        <select class="form-control form-control-sm select_packop_supervisor" name="name_supervisor_ppi_rohs" id="id_supervisor_ppi_rohs">
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
                        <select class="form-control form-control-sm" name="name_supervisor_judgement" id="id_supervisor_judgement" readonly>
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
            <button type="button" class="btn btn-success" id="btn_save_prodpreliminspectionsupervisor">Save Results</button>
        
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

  </form>

  <!--END OF SUPERVISOR INPUT-->

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


  <div class="modal fade" id="modal_ppo_history">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">

        <div class="modal-header">
           <h4 class="modal-title"><i class="fa fa-history"></i> Production Preliminary Inspection History</h4>
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>

        <div class="modal-body">

           <input type="hidden" id="id_history_ponum" name="name_history_ponum" readonly>
           <input type="hidden" id="id_history_pack_code" name="name_history_pack_code" readonly>

           <div class="row">
                  <div class="col-sm-12">
                    <div class="table-responsive">

                      <!--start of table for Finished Production Preliminary Inspection-->
                      <table id="tblProdPrelimInspectionHistory" class="table table-bordered table-hover" style="width: 100%; font-size: 85%">

                        <thead>
                          <tr>
                            <th>Operator/Supervisor Judgement</th>
                            <th>Inspection Date/Time</th>
                            <th>P.O. Number</th>
                            <th>Packing Code</th>
                            <th>Packing Type</th>
                            <th>Unit Condition</th>
                            <th>Orientation of Units</th>
                            <th>Qty. per Box/Tray</th>
                            <th>UL Sticker</th>
                            <th>Silica Gel</th>
                            <th>Accessories</th>
                            <th>ROHS Sticker</th>
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

    $('#id_operator_judgement').css("pointer-events","none");
    $('#id_supervisor_judgement').css("pointer-events","none");

    let dt_prodpreliminspection;

    let arrPackCodeReelLots = [];
    let arrUserScanReelLots = [];


    $(document).ready(function()
    {
      bsCustomFileInput.init();

      $("#id_second_point *").prop("disabled", true);
    });

    dt_prodpreliminspection = $("#tblProdPrelimInspection").DataTable({

      "processing" : true,
      "serverSide" : true,
      "sDom" : 't,p,r',
      
      "ajax" :
      {
        url: "ppo_view_batches",
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
      ],

      "order": [[1, "desc" ]]

    }); //end of datatable of finished inspections

    dt_prodpreliminspectionhistory = $("#tblProdPrelimInspectionHistory").DataTable({

      "processing" : true,
      "serverSide" : true,
      "sDom" : 't,p,r',
      
      "ajax" :
      {
        url: "ppo_view_pack_code_history",
        data: function(param)
        {
            param.po_num = $("#id_history_ponum").val();
            param.pack_code = $("#id_history_pack_code").val();
        }
      },

      "columns":
      [
        { "data" : "judgement", orderable:false, searchable:false },
        { "data" : "datetime" },
        { "data" : "po_num"},
        { "data" : "pack_code" },
        { "data" : "pack_type" },
        { "data" : "condition" },
        { "data" : "orientation" },
        { "data" : "qty_per_box" },
        { "data" : "ul_sticker" },
        { "data" : "silicagel" },
        { "data" : "accessories" },
        { "data" : "rohs" }
      ]

    }); //end of datatable of finished inspections



      $(document).on('click','.btn_open_packop_history',function(e)
      {
        let po_num = $(this).attr('po-num');
        let pack_code = $(this).attr('pack-code');

        $('#id_history_ponum').val(po_num);
        $('#id_history_pack_code').val(pack_code);

        dt_prodpreliminspectionhistory.draw();
      });

    //ON CLICK
    $(document).on('click','.btn_search_POno',function(e){
      $('#txt_search_po_number').val('');
      $('#modalScan_PO').attr('data-formid', '').modal('show');
    });
//-----
    $( "#modalScan_PO" ).on('shown.bs.modal', function(){
      $(this).find('#txt_search_po_number').focus();
    });
//-----
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

            dt_prodpreliminspection.draw();

          },error : function(data){

              }

            }); 
        }
      });

//-----
    $( "#modal_ScanPackCode" ).on('shown.bs.modal', function(){
      $(this).find('#txt_search_pack_code').focus();
    });
//-----

    $(document).on('click','.btn_search_pack_code',function(e)
    {   
      $('#txt_search_pack_code').val('');
      $('#modal_ScanPackCode').attr('data-formid', '').modal('show');
    });

      $(document).on('keypress',function(e){
      if( ($("#modal_ScanPackCode").data('bs.modal') || {})._isShown ){
        $('#txt_search_pack_code').focus();

        if( e.keyCode == 13 && $('#txt_search_pack_code').val() !='' && ($('#txt_search_pack_code').val().length >= 8) ){
            $('#modal_ScanPackCode').modal('hide');
          }
        }
    });

    $(document).on('keyup','#txt_search_pack_code',function(e){
        if( e.keyCode == 13 ){

          $('#id_pack_code').val('');

          var po_num = $('#id_po_no').val();
          var pack_code = $('#txt_search_pack_code').val();

          CheckExistPackingCode(po_num,pack_code);
          }
      });



        //--------------SCANNING OF C3 LABEL---------------//
        $(document).on('click','.btn_search_c3_label',function(e)
        {   
          $('#txt_search_c3_label').val('');
          $('#modal_scanC3Label').attr('data-formid', '').modal('show');
        });
//-----
    $( "#modal_scanC3Label" ).on('shown.bs.modal', function(){
      $(this).find('#txt_search_c3_label').focus();
    });
//-----
        $(document).on('keypress',function(e){

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

          if( ($("#modal_scanC3Label").data('bs.modal') || {})._isShown ){
            $('#txt_search_c3_label').focus();

            if( e.keyCode == 13 && $('#txt_search_c3_label').val() !='' && ($('#txt_search_c3_label').val().length >= 10) ){
                $('#modal_scanC3Label').modal('hide');
              }
            }
        });


        $(document).on('keyup','#txt_search_c3_label',function(e){
        if( e.keyCode == 13 ){

          $('#id_c3_scan').val('');
          var barcode = $('#txt_search_c3_label').val();
          //var barcodeCase = barcode.toUpperCase();

          CheckC3LabelExists(barcode); // ProdPrelimInspection.js
          }
        });

       //--------------SCANNING OF C3 LABEL---------------//



      $(document).on('keypress',function(e){
      if( ($("#modal_ScanPackCode").data('bs.modal') || {})._isShown ){
        $('#txt_search_pack_code').focus();

        if( e.keyCode == 13 && $('#txt_search_pack_code').val() !='' && ($('#txt_search_pack_code').val().length >= 8) ){
            $('#modal_ScanPackCode').modal('hide');
          }
        }
    });

      $(document).on('click','#btn_check_reel_lots', function(e)
      {
        $("#id_second_point *").prop("disabled", false);

        let device = $("#id_modal_device").val();

        OpenRDrawing(device);
      });

       $(document).on('click','#btn_supervisor_check_drawing',function(e){

          let device = $("#id_modal_supervisor_device").val();
          OpenRDrawing(device);
      });


    //BTN SAVE PPO

//-----
    $( "#mdl_employee_number_scanner" ).on('shown.bs.modal', function(){
      $(this).find('#txt_employee_number_scanner').focus();
    });
//-----

     $(document).on('click','#btn_save_prodpreliminspection',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','#formProdPrelimInspection').modal('show');
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

      $(document).on('click','#btn_save_prodpreliminspectionsupervisor',function(e){
        $('#txt_supervisor_number_scanner').val('');
        $('#mdl_supervisor_number_scanner').attr('data-formid','#formProdPrelimInspectionSupervisor').modal('show');
      });


//-----
    $( "#mdl_supervisor_number_scanner" ).on('shown.bs.modal', function(){
      $(this).find('#txt_supervisor_number_scanner').focus();
    });
//-----
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

    //--------------------------------------------------------------CHECK IF SCANNED ID IS SUPERVISOR----------------------------------------------------------//
        let global_po_num = '';
        let global_pack_code = '';
        let global_lot_count = '';


    $(document).on('click','.btn_open_ppo_supervisor',function(e)
    {
        global_po_num = $(this).attr('po-num');
        global_pack_code = $(this).attr('pack-code');
        global_lot_count = $(this).attr('lot-count');

        $('#txt_checksupervisor_number_scanner').val('');
        $('#mdl_checksupervisor_number_scanner').modal('show');
    });


//-----
    $( "#mdl_checksupervisor_number_scanner" ).on('shown.bs.modal', function(){
      $(this).find('#txt_checksupervisor_number_scanner').focus();
    });
//-----
    $(document).on('keypress',function(e){
      if( ($("#mdl_checksupervisor_number_scanner").data('bs.modal') || {})._isShown ){
        $('#txt_checksupervisor_number_scanner').focus();

        if( e.keyCode == 13 && $('#txt_checksupervisor_number_scanner').val() !='' && ($('#txt_checksupervisor_number_scanner').val().length >= 4) ){
            $('#mdl_checksupervisor_number_scanner').modal('hide');

            let employee = $('#txt_checksupervisor_number_scanner').val();
            console.log(global_lot_count);
            CheckIfSupervisor(employee,global_po_num,global_pack_code,global_lot_count);

          }
        }
    });



    //-------------------------------------------------------------END OF CHECK IF SCANNED ID IS SUPERVISOR----------------------------------------------------//



    $('.select_packop').on('change', function() {

          let noCounter = 0;
          $('.select_packop option:selected').each(function(i, obj) {
              if( $(this).val() == 2 ){
                noCounter++;
              }
          });

          if(noCounter > 0)
          {
            $('#id_operator_judgement').val(2);
          }
          else
          {
            $('#id_operator_judgement').val(1);
          }
    });

    $('#formProdPrelimInspection').submit(function(event){
      event.preventDefault();
      SubmitPrelimInspection();

    });

    $('#formProdPrelimInspectionSupervisor').submit(function(event){
      event.preventDefault();
      SubmitPrelimInspectionSupervisor();

    });

     $('.select_packop_supervisor').on('change', function() {

          let noCounter = 0;
          $('.select_packop_supervisor option:selected').each(function(i, obj) {
              if( $(this).val() == 2 ){
                noCounter++;
              }
          });

          if(noCounter > 0)
          {
            $('#id_supervisor_judgement').val(2);
          }
          else
          {
            $('#id_supervisor_judgement').val(1);
          }
    });



    $('#modalProdPrelimInspection').on('hidden.bs.modal', function (e) {
        // and empty the modal-content element
         $("#tblReelLotsForPackingCode tbody").remove(); 
         $("#tblReelLotsForPackingCode").append("<tbody></tbody>");
         $('#btn_check_reel_lots').attr('disabled','disabled');
         $('.btn_search_c3_label').removeAttr('disabled');
         $("#id_second_point *").prop("disabled", true);

         $("#formProdPrelimInspection")[0].reset();
         dt_prodpreliminspection.draw();


          arrPackCodeReelLots = [];
          arrUserScanReelLots = [];
    });

     $('#modalProdPrelimInspectionSupervisor').on('hidden.bs.modal', function (e) {
        // and empty the modal-content element
        $('#id_modal_supervisor_device').val('');
        $('#id_modal_supervisor_box_qty').val('');
        $('#id_modal_supervisor_pack_code').val('');
        $('#id_modal_supervisor_po_num').val('');

        global_po_num = '';
        global_pack_code = '';
        global_lot_count = '';


        $("#formProdPrelimInspectionSupervisor")[0].reset();

    });


  </script>
  @endsection
@endauth