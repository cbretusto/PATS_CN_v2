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

  @section('title', 'Warehouse')

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
            <h1>Warehouse - QR Code Issuance</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item">Warehouse</li>
              <li class="breadcrumb-item active">QR Code Issuance</li>
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
                  <div class="col-4">
                    <!-- <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1"><i class="fa fa-search"></i></span>
                      </div>
                      <input type="search" class="form-control" placeholder="Search PO Number here..." id="txt_search_po_number">
                    </div> -->
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <button type="button" class="btn btn-primary btnSearchPoNo" title="Scan PO Code"><i class="fa fa-qrcode"></i></button>
                      </div>
                      <input type="text" class="form-control" id="txt_po_number" readonly="" placeholder="Scan PO Code.">
                    </div>
                  </div>
                  <!-- <div class="col-2 offset-4">
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">PO No.</span>
                      </div>
                      <input id="txt_po_number" type="text" class="form-control" placeholder="---" readonly>
                    </div>
                  </div> -->
                  <div class="col-4">
                    <div class="input-group mb-3">
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
                <h3 class="card-title">List of Batch</h3>
              </div>

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
                          <table class="table table-sm table-bordered table-hover" id="tbl_batches" style="min-width: 900px;">
                            <thead>
                              <tr class="bg-light">
                                <th>Action</th>
                                <th>Transfer Slip</th>
                                <th>Asessment Ctrl #</th>
                                <th>Mat'l Lot #</th>
                                <th>Part #</th>
                                <th>Kit Qty</th>
                                <th>Kit No.</th>
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
                                <!-- <th>Contact type</th> -->
                                <th>Lot #/Pair #</th>
                                <th>Item Description</th>
                                <th>Item</th>
                                <!-- <th>PO #</th> -->
                                <th>Device Code</th>
                                <th>Device Name</th>
                                <th>Req issuance qty</th>
                                <th>Created At</th>
                                <!-- <th>Reel qty</th> -->
                                <!-- <th>Qty for return</th> -->
                                <!-- <th>Sched date for return</th> -->
                                <!-- <th>Issued<br>DT</th> -->
                                <!-- <th>Issued<br>By</th> -->
                                <!-- <th class="alert-info">Received<br>DT</th> -->
                                <!-- <th class="alert-info">Received<br>By</th> -->
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

              <!-- Start Page Content -->
              <!-- <div class="card-body">
                <div class="row">
                  <div class="col-12">
                    <div class="table-responsive">
                      <table class="table table-sm table-bordered table-hover" id="tbl_batches" style="min-width: 900px;">
                        <thead>
                          <tr class="bg-light">
                            <th>Action</th>
                            <th>Transfer Slip</th>
                            <th>Kit Qty</th>
                            <th>Kit No.</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div> -->



              <!-- !-- End Page Content -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    <!-- MODALS -->
  <div class="modal fade" id="modalGenWHMatIssuIdToPrint">
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
                            ->size(150)->errorCorrection('H')
                            ->generate('0')) !!}" id="imgGenWHMatIssuIdPoNoBarcode" style="max-width: 200px; min-width: 200px;">
                  <br>
                  <label id="lblGenWHMatIssuPoNo">...</label> <br>
                  <label id="lblGenWHMatIssuPoDevName">...</label> <br>
              </div>
              <div class="col-sm-6">
                  <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                            ->size(150)->errorCorrection('H')
                            ->generate('0')) !!}" id="imgGenWHMatIssuIdBarcode" style="max-width: 200px; min-width: 200px;">
                  <br>
                  <label id="lblGenWHMatIssuTransSlip">...</label> <br>
                  <label id="lblGenWHMatIssuPoKitNo">...</label> - 
                  <label id="lblGenWHMatIssuPoKitQty">...</label> 
              </div>
            </div>
          </center>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" id="btnPrintWHMatIssuIdBarcode" class="btn btn-primary"><i id="iBtnPrintWHMatIssuIdBarcodeIcon" class="fa fa-print"></i> Print</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  <div class="modal fade" id="modalGenWHSakIssuIdToPrint">
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
                <div class="col-sm-4">
                <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                          ->size(150)->errorCorrection('H')
                          ->generate('0')) !!}" id="imgGenWHSakIssuIdPoNo" style="min-width: 100px; max-width: 100px;">
                <br>
                <label id="lblGenWHSakIssuPoNo">...</label> <br>
                </div>

                <div class="col-sm-4">
                  <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                          ->size(150)->errorCorrection('H')
                          ->generate('0')) !!}" id="imgGenWHSakIssuLotNo" style="min-width: 100px; max-width: 100px;">
                  <br>
                  <label id="lblGenWHSakIssuLotNo">...</label> <br>
                </div>

                <div class="col-sm-4">
                  <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')
                          ->size(150)->errorCorrection('H')
                          ->generate('0')) !!}" id="imgGenWHSakIssuIdTransferslip" style="min-width: 100px; max-width: 100px;">
                  <br>
                  <label id="lblGenWHSakIssuTransSlip">...</label> <br>
                </div>
              </div>
            </center>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" id="btnPrintWHSakIssuIdBarcode" class="btn btn-primary"><i id="iBtnPrintWHSakIssuIdBarcodeIcon" class="fa fa-print"></i> Print</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

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

  </div>
  <!-- /.content-wrapper -->
  @endsection

  @section('js_content')
  <script type="text/javascript">
    let currentPoNo = "";
    let dt_batches;
    let imgResultMatIssueQrCode = "";
    let imgResultMatIssuePoNoQrCode = "";
    let lblGenWHMatIssuPoNo = "";
    let lblGenWHMatIssuTransSlip = "";
    let lblGenWHMatIssuPoDevName = "";
    let lblGenWHMatIssuPoKitNo = "";
    let lblGenWHMatIssuPoKitQty = "";

    let dt_sakidashi;
    let imgResultSakIssuePoNoQrCode = "";
    let imgResultSakLotQrCode = "";
    let imgResultSakIssueQrCode = "";
    let lblGenWHSakIssuPoNo = "";
    let itemDescription = "";
    let lblGenWHSakLot = "";
    let lblGenWHSakIssuCtrlNo = "";

    $(document).ready(function () {
      bsCustomFileInput.init();
      //-----------------------
      $(document).on('keyup','#txt_search_po_number',function(e){
        if( e.keyCode == 13 ){
          $('#tbl_batches tbody tr').removeClass('table-active');
          $('#tbl_sakidashi tbody tr').removeClass('table-active');
          $('#tbl_lot_numbers tbody tr').removeClass('table-active');
          $('#txt_po_number').val('');
          $('#txt_device_name').val('');
          dt_batches.ajax.reload();
          dt_sakidashi.ajax.reload();
        }
      });

      dt_batches      = $('#tbl_batches').DataTable({
        "processing" : true,
          "serverSide" : true,
          "ajax" : {
            url: "warehouse_view_batches",
            data: function (param){
                param.po_number = currentPoNo;
            }
          },
          bAutoWidth: false,
          "columns":[
            { "data" : "action", orderable:false, searchable:false, },
            { "data" : "issuance_no" },
            { "data" : "assessment" },
            { "data" : "material_kitting_info.lot_no" },
            { "data" : "material_kitting_info.item_desc" },
            { "data" : "kit_qty" },
            { "data" : "kit_no" },
          ],
          order: [[1, "desc"]],
          "columnDefs": [
            {
              "targets": [2, 3, 4],
              "data": null,
              "defaultContent": "--"
            },
          ],
          "rowCallback": function(row,data,index ){
            $('#txt_po_number').val( data['po_no'] );
            $('#txt_device_name').val( data['device_name'] );
          },
          "drawCallback": function(row,data,index ){
            
          },
      });//end of DataTable

      $(document).on('click','#tbl_batches tbody tr',function(e){
        $(this).closest('tbody').find('tr').removeClass('table-active');
        $(this).closest('tr').addClass('table-active');
      });

      $(document).on('click', '.btnPrintWHMatIssu', function(){
        let matWHMatIssuId = $(this).attr('material-issuance-id');
        let productQualification = $(this).attr('product-qualification');
        
        if(productQualification == '0'){
            alert('This item has no product qualification. Please contact your supervisor.');
            return
        }else{
          GetWHMatIssuIdToPrint(matWHMatIssuId, 1);
        }
      });

      $(document).on('click', '.btnPrintWHPONo', function(){
        let matWHMatIssuId = $(this).attr('material-issuance-id');
        GetWHMatIssuIdToPrint(matWHMatIssuId, 2);
      });

      $(".btnSearchPoNo").click(function(){
        $("#txtSearchPoTransLotNo").val('');
        $('#modalScanPOTransLotCode').attr('data-formid', 'search_po_number').modal('show');
      });

      $(document).on('keypress',function(e){
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
                  dt_batches.draw();
                  dt_sakidashi.draw();
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

      $("#btnPrintWHMatIssuIdBarcode").click(function(){
        // PrintWHMatIssu();
          popup = window.open();
          // popup.document.write('<br><br><div style="border: 2px solid black; padding: 1px 1px; max-width: 100px;" class="rotated"><img src="' + imgResultUserQrCode + '" style="max-width: 100px;"><br><center><label style="text-align: center; font-weight: bold; font-family: Arial;">' + qrcode + '</label></center></div>');
          let content = '';
          content += '<html>';
          content += '<head>';
            content += '<title></title>';
            content += '<style type="text/css">';
              content += '.rotated {';
                // content += 'transform: rotate(270deg); /* Equal to rotateZ(45deg) */';
                // content += 'border: 2px solid black;';
                content += 'width: 160px;';
                content += 'position: absolute;';
                content += 'left: 8.5px;';
                content += 'top: 12px;';
              content += '}';

              content += 'td {';
                // content += 'transform: rotate(270deg); /* Equal to rotateZ(45deg) */';
                content += 'padding: 1px 1px;';
                content += 'margin: 1px 1px;';
                content += 'width: 70px;';
              content += '}';

              content += '.vl {';
                content += 'border-right: 1px dashed black;';
                content += 'height: 60px;';
                content += 'float: right;';
              content += '}';

              content += '.vl1 {';
                content += 'border-right: 1px dashed black;';
                content += 'height: 15px;';
                content += 'float: right;';
              content += '}';

              content += '.title {';
                content += 'font-size: 7px;';
                content += 'float: center;';
                content += 'font-weight: bold;';
                content += 'font-family: arial;';
              content += '}';

            content += '</style>';
          content += '</head>';
          content += '<body>';
            content += '<center>';
            content += '<div class="rotated">';
            content += '<table style="width: 100%;">';
            content += '<tr>';
            content += '<td class="vl">';
            content += '<center>';
            content += '<span class="title">PO #</span>';
            content += '<img src="' + imgResultMatIssuePoNoQrCode + '" style="min-width: 55px; max-width: 55px;">';
            content += '</center>';
            content += '</td>';

            content += '<td>';
            content += '<center>';
            content += '<span class="title">WHS SLIP #</span>';
            content += '<img src="' + imgResultMatIssueQrCode + '" style="min-width: 55px; max-width: 55px;">';
            content += '</center>';
            content += '</td>';
            content += '</tr>';

            content += '<tr>';
            content += '<td class="vl1">';
            content += '<center>';
            content += '<label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 8px;">' + lblGenWHMatIssuPoNo + '</label>';
            content += '<br>';
            content += '<label style="text-align: center; font-weight: bold; font-family: Arial Narrow; font-size: 6px;">' + lblGenWHMatIssuPoDevName + ' <br> </label>';
            content += '</center>';
            content += '</td>';

            content += '<td>';
            content += '<center>';
            content += '<label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 8px;">' + lblGenWHMatIssuTransSlip + '</label>';
            content += '<br>';
            content += '<label style="text-align: center; font-weight: bold; font-family: Arial Narrow; font-size: 6px;">' + lblGenWHMatIssuPoKitNo + " - " + lblGenWHMatIssuPoKitQty + ' <br> </label>';
            content += '</center>';
            content += '</td>';
            content += '</tr>';

            content += '</table>';
            content += '</div>';
            content += '</center>';
          content += '</body>';
          content += '</html>';
          popup.document.write(content);
          popup.focus(); //required for IE
          popup.print();
          popup.close();
        });
      //----------

      dt_sakidashi      = $('#tbl_sakidashi').DataTable({
        "processing" : true,
          "serverSide" : true,
          "ajax" : {
            url: "view_warehouse_sakidashi_issuance",
            data: function (param){
                param.po_number = currentPoNo;
            }
          },
          bAutoWidth: false,
          paging: false,
          "columns":[
            { "data" : "action", orderable:false, searchable:false, width: '20px' },
            { "data" : "status", orderable:false, searchable:false, visible: false },
            { "data" : "issuance_no" },
            // { "data" : "tbl_wbs_sakidashi_issuance_item.item_desc" },
            // { "data" : "tbl_wbs_sakidashi_issuance_item.lot_no" },
            // { "data" : "tbl_wbs_sakidashi_issuance_item.item_desc" },
            { "data" : "tbl_wbs_sakidashi_issuance_item.lot_no", defaultContent: "--" },

            { "data" : "tbl_wbs_sakidashi_issuance_item.item_desc", defaultContent: "--" },

            { "data" : "tbl_wbs_sakidashi_issuance_item.item", defaultContent: "--" },
            // { "data" : "po_no" },
            { "data" : "device_code" },
            { "data" : "device_name" },
            // { "data" : "tbl_wbs_sakidashi_issuance_item.required_qty" },
            { "data" : "tbl_wbs_sakidashi_issuance_item.issued_qty", defaultContent: "--" },
            { "data" : "created_at",  orderable:false, searchable:false, visible: false },
            // { "data" : "tbl_wbs_sakidashi_issuance_item.return_qty" },
            // { "data" : "tbl_wbs_sakidashi_issuance_item.sched_return_date" },
            // { "data" : "created_at" },
            // { "data" : "incharge" },
            // { "data" : "received_dt" },
            // { "data" : "received_by" },
          ],
          order: [[7, "desc"]],
          // order: [[1, "desc"]],
          "rowCallback": function(row,data,index ){
            $('#txt_po_number').val( data['po_no'] );
            $('#txt_device_name').val( data['device_name'] );

            if( $(row).find('.col_parts_preps_id').val()!=0 ){
              $(row).addClass('table-success');
            }
            if( $(row).html().toLowerCase().indexOf('returned')>0 ){
              $(row).addClass('table-warning');
            }
          },
          "drawCallback": function(row,data,index ){
          },
      });//end of DataTable

      $(document).on('click', '.btnPrintWHSakIssu', function(){
        let matWHSakIssuId = $(this).attr('sakidashi-issuance-id');
        let productQualification = $(this).attr('product-qualification');

        if(productQualification == '0'){
            alert('This item has no product qualification. Please contact your supervisor.');
            return;
        }else{
          GetWHSakIssuIdToPrint(matWHSakIssuId, 1);
        }

        // alert(matWHSakIssuId);
      });

      $("#btnPrintWHSakIssuIdBarcode").click(function(){
        // PrintWHMatIssu();
          popup = window.open();
          // popup.document.write('<br><br><div style="border: 2px solid black; padding: 1px 1px; max-width: 100px;" class="rotated"><img src="' + imgResultUserQrCode + '" style="max-width: 100px;"><br><center><label style="text-align: center; font-weight: bold; font-family: Arial;">' + qrcode + '</label></center></div>');
          let content = '';
          content += '<html>';
          content += '<head>';
            content += '<title></title>';
            content += '<style type="text/css">';
              content += '.rotated {';
                // content += 'transform: rotate(270deg); /* Equal to rotateZ(45deg) */';
                // content += 'border: 2px solid black;';
                content += 'width: 160px;';
                content += 'position: absolute;';
                content += 'left: 8.5px;';
                content += 'top: 12px;';
              content += '}';

              content += 'td {';
                // content += 'transform: rotate(270deg); /* Equal to rotateZ(45deg) */';
                content += 'padding: 1px 1px;';
                content += 'margin: 1px 1px;';
                content += 'width: 70px;';
              content += '}';

              content += '.vl {';
                content += 'border-right: 1px dashed black;';
                content += 'height: 60px;';
                content += 'float: right;';
              content += '}';

              content += '.vl1 {';
                content += 'border-right: 1px dashed black;';
                content += 'height: 10px;';
                content += 'float: right;';
              content += '}';

              content += '.title {';
                content += 'font-size: 7px;';
                content += 'float: center;';
                content += 'font-weight: bold;';
                content += 'font-family: arial;';
              content += '}';

            content += '</style>';
          content += '</head>';
          content += '<body>';
            content += '<center>';
              content += '<span class="title" style="width: 100%;">'+itemDescription+'</span>';
            content += '<div class="rotated">';

            content += '<table style="width: 100% !important;">';
            content += '<tr>';
            content += '<td>';
            content += '<center>';
            /* content += '<span class="title">PO #</span>'; */
            content += '<img src="' + imgResultSakIssuePoNoQrCode + '" style="min-width: 60px; max-width: 60px;">';
            content += '</center>';
            content += '</td>';

            content += '<td>';
            content += '<center>';
            content += '<img src="' + imgResultSakLotQrCode + '" style="min-width: 60px; max-width: 60px;">';
            content += '</center>';
            content += '</td>';

            content += '<td>';
            content += '<center>';
            /* content += '<span class="title">Issuance #</span>'; */
            content += '<img src="' + imgResultSakIssueQrCode + '" style="min-width: 60px; max-width: 60px;">';
            content += '</center>';
            content += '</td>';
            content += '</tr>';

            content += '<tr>';
            content += '<td>';
            content += '<center>';
            content += '<label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 6px;">' + lblGenWHSakIssuPoNo + '</label>';
            content += '</center>';
            content += '</td>';
            content += '<td>';
            content += '<center>';
            content += '<label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 6px;">' + lblGenWHSakLot + '</label>';
            content += '</center>';
            content += '</td>';
            content += '<td>';
            content += '<center>';
            content += '<label style="text-align: center; font-weight: bold; font-family: Arial; font-size: 6px;">' + lblGenWHSakIssuCtrlNo + '</label>';
            content += '</center>';
            content += '</td>';
            content += '</tr>';
            content += '</table>';
            content += '</div>';
            content += '</center>';
          content += '</body>';
          content += '</html>';
          popup.document.write(content);
          popup.focus(); //required for IE
          popup.print();
          popup.close();
        });

    });
  </script>
  @endsection
@endauth