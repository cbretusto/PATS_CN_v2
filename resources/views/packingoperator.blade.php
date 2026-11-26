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
                        <label>Boxing Quantity</label>
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
                <h3 class="card-title">Current P.O. Packing Code List</h3>

                <div class="float-right">
                      <button class="btn btn-primary btn-sm" id="id_add_packing_record" disabled><i class="fa fa-plus"></i> Add Packing & Shipment Record</button>
                    </div>
              </div>

              <!-- Start Page Content -->
              <div class="card-body">

                <div class="table responsive">
                  <table id="tblPackingOperator" class="table table-bordered table-hover" style="width: 100%;">
                    <thead>
                      <tr>
                        <th>Action</th>
                        <th>Packing Code</th>
                        <th>Box Quantity</th>
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

  <!--PACKING OPERATOR MODAL-->
  <div class="modal fade" id="modalPackingOperator">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">

        <div class="modal-header">
            <h4 class="modal-title"><i class="fa fa-edit"></i> Preliminary Production Packing Inspection</h4>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body">

          <div class="card card-primary">
            <div class="card-header">
              <h4 class="card-title"><i class="fa fa-link"></i> Add Reel Lot Numbers</h4>
            </div>

            <div class="card-body">
              
              <div class="row">
              <!--ADD LOT NUMBERS-->
               <div class="col-sm-3">
                      <label>Search Reel Lot</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                            <button type="button" class="btn btn-primary btn_search_reel_lot" title="Click to Scan Reel Lot"><i class="fa fa-barcode"></i></button>
                        </div>
                        <input type="text" class="form-control" id="id_reel_num" readonly="" placeholder="e.g. 0203-01X, 0203-02X">
                      </div>
                </div>

                 <div class="col-sm-9">
                      <div class="float-right">
                        <button class="btn btn-danger btn-sm" id="id_remove_reel_lots"><i class="fa fa-times"></i> Remove Selected Reel Lots </button>

                          <button class="btn btn-primary btn-sm" id="id_save_reel_lots" disabled><i class="fa fa-plus"></i> Save Reel Lots <span id="count_reel_lots"></span></button>
                      </div>
                </div>

              </div>

              <br>

            <div class="row">
              <div class="col-sm-12">
                <div class="table responsive">
                  <table id="tblSelectReelLotNo" class="table table-bordered table-hover" style="width: 100%; font-size: 75%">
                    <thead>
                      <tr>
                        <th>Action</th>
                        <th bgcolor="#FCF7DE">Reel Lot No</th>
                        <th>Customer P/N</th>
                        <th>Manufacture P/N</th>
                        <th>Quantity</th>
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

          <div class="card card-primary">
            <div class="card-header">
              <h4 class="card-title"><i class="fa fa-archive"></i> Packing Operator Fill-In</h4>
            </div>

            <div class="card-body">

            <div class="row">
              <div class="col-sm-4 p-2">

                <h6><strong>1.1 Packing Type</strong></h6>
                <div class="row">
                  <div class="col">
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
                          <span class="input-group-text w-100" id="basic-addon1">1.3.2 Device Name</span>
                        </div>
                        <select class="form-control form-control-sm select_packop" name="name_132" id="id_132">
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
                          <span class="input-group-text w-100" id="basic-addon1">1.3.3 Quantity per lot</span>
                        </div>
                        <select class="form-control form-control-sm select_packop" name="name_133" id="id_133">
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
                          <span class="input-group-text w-100" id="basic-addon1">1.3.4 Reel Lot No.</span>
                        </div>
                        <select class="form-control form-control-sm select_packop" name="name_134" id="id_134">
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
                          <span class="input-group-text w-100" id="basic-addon1">1.3.5 Total No. of Reels/Trays</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_135" name="name_135" autocomplete="off" placeholder="e.g. 1, 2">
                      </div>
                    </div>
                  </div>
              </div>

               <div class="col-sm-4 p-2">

                <h6><strong>1.4 Packing Code</strong></h6>
                 <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">

                        <input type="text" class="form-control form-control-sm" id="id_PackingCode" name="name_PackingCode" autocomplete="off" placeholder="Series Code + Month + Autoincrement" readonly>
                      </div>
                    </div>
                  </div>

                <h6><strong>1.5 Packing Units Compliance to Manual</strong></h6>
                   <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">1.5.1 Orientation of Units</span>
                        </div>
                        <select class="form-control form-control-sm select_packop" name="name_151" id="id_151">
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
                          <span class="input-group-text w-100" id="basic-addon1">1.5.2 Qty. Per Box / Tray</span>
                        </div>
                        <select class="form-control form-control-sm select_packop" name="name_152" id="id_152">
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
                          <span class="input-group-text w-100" id="basic-addon1">1.5.3 UL Sticker</span>
                        </div>
                        <select class="form-control form-control-sm select_packop" name="name_153" id="id_153">
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
                          <span class="input-group-text w-100" id="basic-addon1">1.5.4 Silica Gel</span>
                        </div>
                        <select class="form-control form-control-sm select_packop" name="name_154" id="id_154">
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
                          <span class="input-group-text w-100" id="basic-addon1">1.5.5 Accessories</span>
                        </div>
                        <select class="form-control form-control-sm select_packop" name="name_155" id="id_155">
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
                          <span class="input-group-text w-100" id="basic-addon1">1.5.6 ROHS Sticker</span>
                        </div>
                        <select class="form-control form-control-sm select_packop" name="name_156" id="id_156">
                          <option selected disabled>---</option>
                          <option value = "1">Yes</option>
                          <option value = "2">No</option>
                          <option value = "3">N/A</option>
                        </select>
                      </div>
                    </div>
                  </div>
                
              </div>

               <div style="position: relative;" class="col-sm-4 p-2">

                <div style="position: absolute; bottom: 0;width: 100%">
                <h6><strong>Packing Operator Judgement</strong></h6>
                    
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
                  </div>

              </div>
            </div>

            </div>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-success" id="btn_save_packop">Save</button>
        </div>

      </div>
    </div>
  </div>

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


  @endsection

  @section('js_content')
  <script type="text/javascript">

    let dt_packingoperator;
    let dt_reellotno;

    //array for reel lot
    var arrSelectedReelLot = [];

    $(document).ready(function(){
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
        { "data" : "packing_code"},
        { "data" : "box_qty" },
        { "data" : "status" }
        ]
      });//end of datatable

    });

       dt_reellotno = $("#tblSelectReelLotNo").DataTable(
      {
        "lengthChange": false,
        "processing":true,
        "serverSide":true,
        "filtering":false,
        /*"bFilter": false,*/
        'sDom': 't,p,r,i',
        "pageLength": 6,

        "ajax" : {
          url: "packop_view_reel_lot_no",
          data: function (param)
          {
            param.po_number = $("#txt_search_po_number").val();
            param.device = $("#id_device_name").val();
          }
        },

        "columns":[
        { "data" : "action", orderable:false, searchable:false },
        { "data" : "reel_lot"},
        { "data" : "customer"},
        { "data" : "manufacture" },
        { "data" : "quantity" }
        ],

        "initComplete": function(settings, json) {
          $(".chkReelLot").each(function(index){

                if(arrSelectedReelLot.includes($(this).attr('reel-id'))){
                $(this).attr('checked', 'checked');
              }
            });
          },

          "drawCallback": function( settings ) {
                $(".chkReelLot").each(function(index){

                $('#count_reel_lots').text('(' + arrSelectedReelLot.length + ')');

                    if(arrSelectedReelLot.includes($(this).attr('reel-id'))){
                        $(this).attr('checked', 'checked');
                    }
                });
            }

      });//end of datatable

    //- EVENTS ------------------------------------------------------
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


     $(document).on('keyup','#txt_search_po_number',function(e){
        if( e.keyCode == 13 ){

          $('#id_po_no').val('');
          $('#id_device_name').val('');
          $('#id_po_qty').val('');
          $('#id_box_qty').val('');
          $('#id_add_packing_record').attr('disabled','disabled');


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

            $('#id_add_packing_record').removeAttr('disabled');

            //$('#txt_search_po_number').val('');

          },error : function(data){

              }

            }); 
        }
      });


     //-----------------SELECT REEL LOT NUMBER BY SCAN---------------------

    $(document).on('click','.btn_search_reel_lot',function(e){
      $('#txt_search_reel_lot').val('');
      $('#modalScan_ReelLot').attr('data-formid', '').modal('show');
    });

    $(document).on('keypress',function(e){
      if( ($("#modalScan_ReelLot").data('bs.modal') || {})._isShown ){
        $('#txt_search_reel_lot').focus();

        if( e.keyCode == 13){
            $('#modalScan_ReelLot').modal('hide');
          }
        }
    });

       $(document).on('keyup','#txt_search_reel_lot',function(e){
        if( e.keyCode == 13 ){
          dt_reellotno.search($(this).val()).draw();

            if({data:"reel_lot"} != null)
            {
              return {data:"reel_lot"};
            }

        }
      });




     //-------------END OF SELECT REEL LOT NUMBR BY SCAN------------------

    $(document).on('click','#id_add_packing_record',function(e)
    {   
        let device = $("#id_device_name").val();
        GeneratePackingCode(device);

        dt_reellotno.draw();

        $('#modalPackingOperator').modal("show");
    });

    $('#modalPackingOperator').on('hidden.bs.modal', function (e) {
        // and empty the modal-content element
          arrSelectedReelLot = [];
          $('#id_save_reel_lots').attr('disabled','disabled');
          $('.chkReelLot').removeAttr('disabled');
    });


    $(document).on('click','.chkReelLot',function(){

      let reelId = $(this).attr('reel-id');

      if(!arrSelectedReelLot.includes(reelId))
      {
        arrSelectedReelLot.push(reelId);
      }
      else
      {
        let index = arrSelectedReelLot.indexOf(reelId);
        arrSelectedReelLot.splice(index, 1);
      }

      $('#count_reel_lots').text('(' + arrSelectedReelLot.length + ')');
      console.log(arrSelectedReelLot);

      CountReelLotNo(arrSelectedReelLot.length, $('#id_device_name').val());

    });

    $(document).on('click','#id_save_reel_lots',function(e)
    {

    });


  </script>
  @endsection
@endauth