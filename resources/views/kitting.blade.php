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

  @section('title', 'Warehouse Material Kitting')

  @section('content_page')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Warehouse Material Kitting</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Warehouse Material Kitting</li>
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

            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">WBS Material Kitting & Issuance</h3>
              </div>

              <!-- Start Page Content -->
              <div class="card-body">
                <form id="frmSearchKittingInfo">
                  <div class="row">

                      <div class="col-sm-4">
                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Issuance No.</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control form-control" name="kit_issuance_id" style="display: none;">
                              <!-- <input type="text" class="form-control form-control" name="issuance_no" value="WHS-1710-00396"> -->
                              <input type="text" class="form-control form-control" name="issuance_no" value="WHS-{{ date('y') }}{{ date('m') }}-" placeholder="Type and Press Enter" >
                            </div>
                          </div>

                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">PO No.</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control form-control" name="po_no" readonly="true">
                            </div>
                          </div>

                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Device Code</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control form-control" name="device_code" id="device_code" readonly="true">
                            </div>
                          </div>

                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Device Name</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control form-control" name="device_name" id="device_name" readonly="true">
                            </div>
                          </div>

                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">PO Qty</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control form-control" name="po_qty" readonly="true">
                            </div>
                          </div>
                      </div>

                      <div class="col-sm-4">
                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Kit Qty.</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control form-control" name="kit_qty" readonly="true">
                            </div>
                          </div>

                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Kit No.</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control form-control" name="kit_no" readonly="true">
                            </div>
                          </div>

                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Prep. By</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control form-control" name="prep_by" readonly="true">
                            </div>
                          </div>

                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Status</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control form-control" name="status" readonly="true">
                            </div>
                          </div>
                      </div>

                      <div class="col-sm-4">
                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Created By</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control form-control" name="created_by" readonly="true">
                            </div>
                          </div>

                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Created Date</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control form-control" name="created_date" readonly="true">
                            </div>
                          </div>

                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Updated By</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control form-control" name="updated_by" readonly="true">
                            </div>
                          </div>

                          <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Updated Date</label>
                            <div class="col-sm-8">
                              <input type="text" class="form-control form-control" name="updated_date" readonly="true">
                            </div>
                          </div>
                      </div>

                    </div>
                  </form>

                  <br><br>
                  <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" data-toggle="tab" href="#tabKitDetails" role="tab" aria-controls="tabKitDetails" aria-selected="true">Kit Details</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" data-toggle="tab" href="#tabIssuanceDetails" role="tab" aria-controls="tabIssuanceDetails" aria-selected="false">Issuance Details</a>
                    </li>
                  </ul>
                  <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="tabKitDetails" role="tabpanel">
                      <div class="table responsive">
                        <br>
                        <table id="tblKitDetails" class="table table-sm table-bordered table-striped table-hover" style="width: 100%;">
                          <thead>
                            <tr>
                              <th>Detail ID</th>
                              <th>Item/Part No.</th>
                              <th>Item Description</th>
                              <th>Usage</th>
                              <th>Rqd Qty.</th>
                              <th>Kit Qty.</th>
                              <th>Issued Qty.</th>
                              <th>Location</th>
                              <th>Drawing No.</th>
                              <th>Supplier</th>
                              <th>Whse100</th>
                              {{-- <th>Whse102</th> --}}
                            </tr>
                          </thead>
                        </table>
                      </div>

                    </div>

                    <div class="tab-pane fade" id="tabIssuanceDetails" role="tabpanel">
                      <div class="table responsive">
                        <br>
                        <table id="tblIssuanceDetails" class="table table-sm table-bordered table-striped table-hover" style="width: 100%;">
                          <thead>
                            <tr>
                              <th>Detail ID</th>
                              <th>Item/Part No.</th>
                              <th>Item Description</th>
                              <th>Kit Qty.</th>
                              <th>Issued Qty.</th>
                              <th>Lot No.</th>
                              <th>Location</th>
                              <th>Remarks</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                        </table>
                      </div>

                    </div>
                  </div>

                  </div>

                </div>

              </div>
            </div>



          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <div class="modal fade" id="mdlSubKitIssuance">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title"><i class="fa fa-list"></i> Sub Kitting</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" id="frmSubKitIssuance" enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
            <div class="row">

              <div class="col-sm-6">
                <div class="form-group">
                  <label>Issuance No</label>
                  <input type="text" class="form-control" name="kit_issuance_id" placeholder="Auto Generated" readonly="true" style="display: none;">
                  <input type="text" class="form-control" name="issuance_no" placeholder="Auto Generated" readonly="true">
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label>PO No</label>
                  <input type="text" class="form-control" name="po_no" placeholder="Auto Generated" readonly="true">
                </div>
              </div>


              <div class="col-sm-6">
                <div class="form-group">
                  <label>Item/Part No.</label>
                  <input type="text" class="form-control" name="item" placeholder="Auto Generated" readonly="true">
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label>Item Description</label>
                  <input type="text" class="form-control" name="item_desc" placeholder="Auto Generated" readonly="true">
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label>Lot No</label>
                  <input type="text" class="form-control" name="lot_no" placeholder="Auto Generated" readonly="true">
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label>Detail ID</label>
                  <input type="text" class="form-control" name="detail_id" placeholder="Auto Generated" readonly="true">
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label>Issued Qty</label>
                  <input type="number" class="form-control" name="issued_qty" placeholder="Auto Generated" readonly="true">
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <label>Sub Kit Quantity</label>
                  <div class="input-group mb-3">
                    <input type="number" class="form-control" name="sub_kit_qty" min=0 step="0.01" placeholder="Enter Sub Kit Qty" required>
                    <div class="input-group-append">
                      <button type="submit" class="btn btn-success" id="btnSubmitSubKitQty" title="Save Sub Kit Qty"><i class="fas fa-arrow-right"></i></button>
                    </div>
                  </div>
                </div>
              </div>

            </div>
            <hr>
            <div class="row">
              <div class="col-sm-12">
                <div class="table-responsive">
                  <table id="tblSubkits" class="table table-sm table-bordered table-hover w-100">
                    <thead>
                      <tr>
                        <th>Action</th>
                        <th>Description</th>
                        <th>Subkit Qty</th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            {{-- < type="submit" id="btnSubKitIssuance" class="btn btn-primary"><i class="fa fa-check"></i> Save<button> --}}
            <!-- <button type="button" id="btnPrintAllSubKitIssuance" class="btn btn-success" style="display: none;"><i class="fa fa-print"></i> Print All</button> -->
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  <div class="modal fade" id="modalQRCode">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fas fa-print"></i> QR Code</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                  <div class="col-sm-12">
                    <center><h5 id="QrHead"></h5></center>
                  </div>
                </div>
                <div class="row">
                    <div class="col-sm-4 text-center">
                      <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(150)->errorCorrection('H')->generate('0')) !!}" id="imgQRCodePo" style="max-width: 150px; min-width: 150px;">
                      <h6 id='labelPO'></h6>
                    </div>
                    <div class="col-sm-4 text-center">
                      <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(150)->errorCorrection('H')->generate('0')) !!}" id="imgQRCodeLotNo" style="max-width: 150px; min-width: 150px;">
                      <h6 id='labelLotNo'></h6>
                      <h6 id='labelSubkitQty'></h6>
                    </div>
                    <div class="col-sm-4 text-center">
                      <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(150)->errorCorrection('H')->generate('0')) !!}" id="imgQRCodeIssuanceNo" style="max-width: 150px; min-width: 150px;">
                      <h6 id='labelIssNo'></h6>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" id="btnPrintQr" class="btn btn-primary btn-sm"><i class="fa fa-print fa-xs"></i> Print</button>
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>


  @endsection

  @section('js_content')
    <script type="text/javascript">
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
      let dtKittingDetails, dtIssuanceDetails, prefixDesc;
      let dtSubkits;
      let splitted_desc, subKitDetails;
      let poPOQrCode, lotNoQrCode, issuanceQrCode
      
      
      $(document).ready(function(){
        $("input[name='issuance_no']", $("#frmSearchKittingInfo")).keypress(function(e){
          if(e.keyCode == 13){
            GetKittingInfoByIssuanceNo($(this).val());
            toastr.info('Loading data. Please wait...');
            $("input[name='po_no']", $("#frmSearchKittingInfo")).focus();
          }

        });

        dtKittingDetails = $("#tblKitDetails").DataTable({
          "processing" : true,
          "serverSide" : true,
          "ajax" : {
            url: "get_kitting_details_by_issuance_no",
            data: function (param){
                param.issuance_no = $("input[name='issuance_no']", $("#frmSearchKittingInfo")).val();
            }
          },
          
          "columns":[
            { "data" : "detailid" },
            { "data" : "item" },
            { "data" : "item_desc" },
            { "data" : "usage" },
            { "data" : "rqd_qty" },
            { "data" : "kit_qty" },
            { "data" : "issued_qty" },
            { "data" : "location" },
            { "data" : "drawing_no" },
            { "data" : "supplier" },
            { "data" : "whs100" },
            // { "data" : "whs102" },
            // { "data" : "action1", orderable:false, searchable:false }
          ],
        
        });//end of dataTableKittings

        dtIssuanceDetails = $("#tblIssuanceDetails").DataTable({
          "processing" : true,
            "serverSide" : true,
            "ajax" : {
              url: "get_issuance_details_by_issuance_no",
              data: function (param){
                  param.issuance_no = $("input[name='issuance_no']", $("#frmSearchKittingInfo")).val();
              }
            },
            
            "columns":[
              { "data" : "detailid" },
              { "data" : "item" },
              { "data" : "item_desc" },
              { "data" : "kit_qty" },
              { "data" : "issued_qty" },
              { "data" : "lot_no" },
              { "data" : "location" },
              { "data" : "remarks" },
              { "data" : "raw_action" },
              // { "data" : "action1", orderable:false, searchable:false }
            ],
            "columnDefs": [
                  {
                    "targets": [-1],
                    "data": null,
                    "defaultContent": "--"
                  },
            ],
        });//end of dataTableKittings

        dtSubkits = $("#tblSubkits").DataTable({
          "processing" : true,
          "serverSide" : true,
          "ajax" : {
            url: "get_sub_kits_by_detail_id",
            data: function (param){
                param.issuance_id = $("input[name='kit_issuance_id']", $("#frmSubKitIssuance")).val();
            }
          },
          "columns":[
            { "data" : "action" },
            { "data" : "sub_kit_desc" },
            { "data" : "sub_kit_qty" },
          ],
          'drawCallback': function( settings ) {
            // console.log(settings.json.sum);
            if(settings.json.sum == $('input[name="issued_qty"]', $('#frmSubKitIssuance')).val()){
              $('#btnSubmitSubKitQty').prop('disabled', true);
            }
          }
        });//end of dataTableKittings

        $("#tblIssuanceDetails").on('click', '.aSubKitIssuance', function () {
          $('#btnSubmitSubKitQty').prop('disabled', false);
          let kitIssuanceId = $(this).attr('kit-issuance-id');
          let issuedQty = $(this).attr('issued-qty');
          let issuanceNo = $(this).attr('issuance-no');
          let detailId = $(this).attr('detail-id');
          let item = $(this).attr('item');
          let itemDesc = $(this).attr('item-desc');
          let lotNo = $(this).attr('lot-no');
          let poNo = $(this).attr('po-no');

          $("input[name='kit_issuance_id']", $("#frmSubKitIssuance")).val(kitIssuanceId);
          $("input[name='po_no']", $("#frmSubKitIssuance")).val(poNo);
          $("input[name='issuance_no']", $("#frmSubKitIssuance")).val(issuanceNo);
          $("input[name='detail_id']", $("#frmSubKitIssuance")).val(detailId);
          $("input[name='item']", $("#frmSubKitIssuance")).val(item);
          $("input[name='item_desc']", $("#frmSubKitIssuance")).val(itemDesc);
          $("input[name='lot_no']", $("#frmSubKitIssuance")).val(lotNo);
          $("input[name='issued_qty']", $("#frmSubKitIssuance")).val(issuedQty);
          $("input[name='sub_kit_qty']", $("#frmSubKitIssuance")).val('');
          $("input[name='sub_kit_qty']", $("#frmSubKitIssuance")).prop('readonly', false);
          $(".divSubKittingContent").hide();
          $("#divSubKittingContentAddNew").show();

          $("#mdlSubKitIssuance").modal('show');
          $("#btnSubKitIssuance").show();
          $("#btnPrintAllSubKitIssuance").hide();
          dtSubkits.draw();
        });

        $('#frmSubKitIssuance').submit(function(e){
          e.preventDefault();
          // prefixDesc = $("input[name='kit_no']", $("#frmSearchKittingInfo")).val() + ' | ' + $("input[name='prep_by']", $("#frmSearchKittingInfo")).val() + ' | ' + $("input[name='po_no']", $("#frmSearchKittingInfo")).val() + ' | ' + $("input[name='kit_qty']", $("#frmSearchKittingInfo")).val() + ' | ' + $("input[name='lot_no']", $("#frmSubKitIssuance")).val() + ' | ' + $("input[name='item']", $("#frmSubKitIssuance")).val() + ' | ' + $("input[name='item_desc']", $("#frmSubKitIssuance")).val() + ' | ' + $("input[name='issuance_no']", $("#frmSubKitIssuance")).val() + ' | ' +  new Date().getFullYear() + '/' + (parseInt(new Date().getMonth()) + 1) + '/' + new Date().getDate();
          prefixDesc = $("input[name='kit_no']", $("#frmSearchKittingInfo")).val() + ' | ' + $("input[name='prep_by']", $("#frmSearchKittingInfo")).val() + ' | ' + $("input[name='po_no']", $("#frmSubKitIssuance")).val() + ' | ' + $("input[name='sub_kit_qty']", $("#frmSubKitIssuance")).val() + ' | ' + $("input[name='lot_no']", $("#frmSubKitIssuance")).val() + ' | ' + $("input[name='item']", $("#frmSubKitIssuance")).val() + ' | ' + $("input[name='item_desc']", $("#frmSubKitIssuance")).val() + ' | ' + $("input[name='issuance_no']", $("#frmSubKitIssuance")).val() + ' | ' +  new Date().getFullYear() + '/' + (parseInt(new Date().getMonth()) + 1) + '/' + new Date().getDate();
          console.log($("input[name='sub_kit_qty']", $("#frmSubKitIssuance")).val());
          console.log($("input[name='issued_qty']", $("#frmSubKitIssuance")).val());
          if(parseInt( ("input[name='sub_kit_qty']", $("#frmSubKitIssuance")).val() ) > parseInt( $("input[name='issued_qty']", $("#frmSubKitIssuance")).val() )){
            toastr.error('Sub Kit Qty is greater than Issued Qty.');
            return false;
          }
          SaveSubKitting(prefixDesc);
        });

        $(document).on('click', '.aPrintSubKit', function(){
          let subKitId = $(this).attr('sub-kit-id');
          generateQrCode(subKitId);
        });

        $('#btnPrintQr').on('click',function(){
          printQRCode();
        });
      });

      

      function GetKittingInfoByIssuanceNo(issuanceNo){

        $.ajax({
          url: "get_kitting_info_by_issuance_no",
          method: "get",
          data: {
              issuance_no: issuanceNo,
          },
          dataType: "json",
          beforeSend: function(){
              $("input[name='kit_issuance_id']", $("#frmSearchKittingInfo")).val('');
              // $("input[name='issuance_no']", $("#frmSearchKittingInfo")).val('');
              $("input[name='po_no']", $("#frmSearchKittingInfo")).val('');
              $("input[name='device_code']", $("#frmSearchKittingInfo")).val('');
              $("input[name='device_name']", $("#frmSearchKittingInfo")).val('');
              $("input[name='po_qty']", $("#frmSearchKittingInfo")).val('');
              $("input[name='kit_qty']", $("#frmSearchKittingInfo")).val('');
              $("input[name='kit_no']", $("#frmSearchKittingInfo")).val('');
              $("input[name='prep_by']", $("#frmSearchKittingInfo")).val('');
              $("input[name='status']", $("#frmSearchKittingInfo")).val('');
              $("input[name='created_by']", $("#frmSearchKittingInfo")).val('');
              $("input[name='created_date']", $("#frmSearchKittingInfo")).val('');
              $("input[name='updated_by']", $("#frmSearchKittingInfo")).val('');
              $("input[name='updated_date']", $("#frmSearchKittingInfo")).val('');
          },
          success: function(data){
              let result = data['kitting_info'];
              // console.log(result);
              dtKittingDetails.draw();
              dtIssuanceDetails.draw();
              if(result != null){
                  $("input[name='kit_issuance_id']", $("#frmSearchKittingInfo")).val(result['id']);
                  $("input[name='po_no']", $("#frmSearchKittingInfo")).val(result['po_no']);
                  $("input[name='device_code']", $("#frmSearchKittingInfo")).val(result['device_code']);
                  $("input[name='device_name']", $("#frmSearchKittingInfo")).val(result['device_name']);
                  $("input[name='po_qty']", $("#frmSearchKittingInfo")).val(result['po_qty']);
                  $("input[name='kit_qty']", $("#frmSearchKittingInfo")).val(result['kit_qty']);
                  $("input[name='kit_no']", $("#frmSearchKittingInfo")).val(result['kit_no']);
                  $("input[name='prep_by']", $("#frmSearchKittingInfo")).val(result['prepared_by']);
                  $("input[name='status']", $("#frmSearchKittingInfo")).val(result['status']);
                  $("input[name='created_by']", $("#frmSearchKittingInfo")).val(result['create_user']);
                  $("input[name='created_date']", $("#frmSearchKittingInfo")).val(result['created_at']);
                  $("input[name='updated_by']", $("#frmSearchKittingInfo")).val(result['update_user']);
                  $("input[name='updated_date']", $("#frmSearchKittingInfo")).val(result['updated_at']);
              }
              else{
                  $("input[name='kit_issuance_id']", $("#frmSearchKittingInfo")).val('');
                  $("input[name='po_no']", $("#frmSearchKittingInfo")).val('');
                  $("input[name='device_code']", $("#frmSearchKittingInfo")).val('');
                  $("input[name='device_name']", $("#frmSearchKittingInfo")).val('');
                  $("input[name='po_qty']", $("#frmSearchKittingInfo")).val('');
                  $("input[name='kit_qty']", $("#frmSearchKittingInfo")).val('');
                  $("input[name='kit_no']", $("#frmSearchKittingInfo")).val('');
                  $("input[name='prep_by']", $("#frmSearchKittingInfo")).val('');
                  $("input[name='status']", $("#frmSearchKittingInfo")).val('');
                  $("input[name='created_by']", $("#frmSearchKittingInfo")).val('');
                  $("input[name='created_date']", $("#frmSearchKittingInfo")).val('');
                  $("input[name='updated_by']", $("#frmSearchKittingInfo")).val('');
                  $("input[name='updated_date']", $("#frmSearchKittingInfo")).val('');
              }
          },
          error: function(data, xhr, status){
            $("input[name='kit_issuance_id']", $("#frmSearchKittingInfo")).val('');
            // $("input[name='issuance_no']", $("#frmSearchKittingInfo")).val('');
            $("input[name='po_no']", $("#frmSearchKittingInfo")).val('');
            $("input[name='device_code']", $("#frmSearchKittingInfo")).val('');
            $("input[name='device_name']", $("#frmSearchKittingInfo")).val('');
            $("input[name='po_qty']", $("#frmSearchKittingInfo")).val('');
            $("input[name='kit_qty']", $("#frmSearchKittingInfo")).val('');
            $("input[name='kit_no']", $("#frmSearchKittingInfo")).val('');
            $("input[name='prep_by']", $("#frmSearchKittingInfo")).val('');
            $("input[name='status']", $("#frmSearchKittingInfo")).val('');
            $("input[name='created_by']", $("#frmSearchKittingInfo")).val('');
            $("input[name='created_date']", $("#frmSearchKittingInfo")).val('');
            $("input[name='updated_by']", $("#frmSearchKittingInfo")).val('');
            $("input[name='updated_date']", $("#frmSearchKittingInfo")).val('');
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
          }
        });
      }

      function SaveSubKitting(prefix){
        let data = $.param({'prefix': prefix}) + "&" + $('#frmSubKitIssuance').serialize();
        $.ajax({
          url: "save_sub_kitting",
          method: "post",
          data: data,
          dataType: "json",
          success: function (response) {
            if(response.result == true){
              toastr.success(response.message);
              dtSubkits.draw();
            }
            else{
              toastr.error(response.message);
            }
          },
          error: function(data, xhr, status){
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
          }
        });
      }

      function generateQrCode(subKitId){
        $.ajax({
          url: "generate_qr_code",
          method: "get",
          data: {
            sub_kit_id: subKitId,
          },
          dataType: "json",
          success: function (response) {
            splitted_desc = response.sub_kit.sub_kit_desc.split(' | ');
            subKitDetails = response.sub_kit;
            console.log(splitted_desc);
            $('#QrHead').text(splitted_desc[6]);

            $('#labelPO').text(splitted_desc[2]);
            $('#labelLotNo').text(splitted_desc[4]);
            $('#labelIssNo').text(splitted_desc[7]);
            $('#labelSubkitQty').text(`Qty: ${response.sub_kit.sub_kit_qty}`);

            poPOQrCode = response.po_qr_code
            lotNoQrCode = response.lot_no_qr_code
            issuanceQrCode = response.issuance_qr_code

            $('#imgQRCodePo').attr('src',poPOQrCode);
            $('#imgQRCodeLotNo').attr('src',lotNoQrCode);
            $('#imgQRCodeIssuanceNo').attr('src',issuanceQrCode);
          
            $('#modalQRCode').modal('show');

          },
          error: function(data, xhr, status){
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
          }
        });
      }

      function printQRCode(){
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
        // content += 'qwewqe';
        content += '<center style="font-family: Arial; font-size: 0.75rem;">'+splitted_desc[6]+'</center>';
        content += '<table style="width: 100%;margin-left: -5px;">';
        content += '<tr>';
          content += '<td >';
          content += '<img src="' + poPOQrCode+ '" style="min-width: 60px; max-width: 60px;"><br>';
          content += '</td>';
          content += '<td >';
          content += '<img src="' + lotNoQrCode + '" style="min-width: 60px; max-width: 60px;">';
          content += '</td>';
          content += '<td >';
          content += '<img src="' + issuanceQrCode + '" style="min-width: 60px; max-width: 60px;">';
          content += '</td>';
        content += '</tr>';
        content += '<tr>';
          content += '<td >';
          content += '<center><label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 6px;">'+splitted_desc[2]+'</label></center>';
          content += '</td>';
          content += '<td >';
          content += '<center><label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 6px;">'+splitted_desc[4]+'</label><br><label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 6px;">Qty: '+subKitDetails.sub_kit_qty+'</label></center>';
          content += '</td>';
          content += '<td >';
          content += '<center><label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 6px;">'+splitted_desc[7]+'</label></center>';
          content += '</td>';
        content += '</tr>';
        content += '</table>';
        content += '<br>';
        content += '</body>';
        content += '</html>';
        popup.document.write(content);

        popup.focus(); //required for IE
        popup.print();
        popup.close();
      }
    </script>
  @endsection
@endauth