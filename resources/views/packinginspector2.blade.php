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

  @section('title', 'Packing Inspector')

  @section('content_page')
  <!-- Content Wrapper. Contains page content -->
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
            <h1>Preliminary QC Packing Inspection</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Preliminary QC Packing Inspection</li>
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
                        <label>Device Boxing Quantity</label>
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
                <h3 class="card-title">Current Packing Code List</h3> 


              </div>

          

              <!-- Start Page Content -->
              <div class="card-body">
             
                <div class="table responsive">
                  <table id="tblPackingInspector" class="table table-bordered table-hover" style="width: 100%;">
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




    <!----MODALS---->

    <div class="modal fade" id="modalPackingInspector">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">

           <div class="modal-header">
            <h4 class="modal-title"><i class="fa fa-edit"></i> Preliminary QC Packing Inspection</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">

            <input type="hidden" id="id_modal_packing_code" name="name_modal_packing_code" readonly>

            <!--START OF TABS-->

            <div class="row">
              <div class="col-12 col-md-12">
                <div class="card card-info card-tabs">

                  <div class="card-header p-0 pt-1">
                    <ul class="nav nav-tabs" id="packing_inspector_modal_tabs" role="tablist">

                     <li class="nav-item">
                      <a class="nav-link active" id="custom-tabs-one-packop-tab" data-toggle="pill" href="#custom-tabs-one-packop" role="tab" aria-controls="custom-tabs-one-packop" aria-selected="true">Packing Operator</a>
                     </li>

                      <li class="nav-item">
                      <a class="nav-link" id="custom-tabs-one-oqcvir-tab" data-toggle="pill" href="#custom-tabs-one-oqcvir" role="tab" aria-controls="custom-tabs-one-oqcvir" aria-selected="true">OQC Inspection</a>
                     </li>

                      <li class="nav-item">
                      <a class="nav-link" id="custom-tabs-one-oqclotapp-tab" data-toggle="pill" href="#custom-tabs-one-oqclotapp" role="tab" aria-controls="custom-tabs-one-oqclotapp" aria-selected="true">OQC Lot Application</a>
                     </li>
                    </ul>
                  </div>

                  <div class="card-body">

                    <div class="tab-content">

                      <div class="tab-pane fade show active" id="custom-tabs-one-packop" role="tabpanel">
                        <!--PACKING OPERATOR CONTENT-->

                          <div class="row">
                            <h5><i class="fa fa-box"></i> &nbsp; Packing Operator History</h5>

                            <div class="table-responsive">
                              <table id="tbl_packop_history" class="table table-bordered table-striped table-hover">

                                 <thead style="font-size:85%;">
                                    <tr align="center">
                                      <th>Packing Operator Judgement</th>
                                      <th>Judgement Date/Time</th>
                                      <th>Lot Number</th>
                                      <th>Packing Type</th>
                                      <th>Unit Condition</th>
                                      <th>1.3.1 P.O. Number</th>
                                      <th>1.3.2 Device Name</th>
                                      <th>1.3.3 Quantity Per Lot</th>
                                      <th>1.3.4 Reel Lot No.</th>
                                      <th>1.3.5 Total No. of Reels</th>
                                      <th>Packing Code</th>
                                      <th>1.5.1 Orientation of Units</th>
                                      <th>1.5.2 Qty Per Box/Tray</th>
                                      <th>1.5.3 UL Sticker</th>
                                      <th>1.5.4 Silica Gel</th>
                                      <th>1.5.5 Accessories</th>
                                      <th>1.5.6 ROHS Sticker</th>
                                      
                                    </tr>
                                  </thead>
                                  <tbody style="font-size:85%;"></tbody>
                                </table>
                                
                              </table>
                            </div>
                          </div>
                        <!--END OF PACKING OPERATOR CONTENT-->
                      </div>

                      <div class="tab-pane fade" id="custom-tabs-one-oqcvir" role="tabpanel">
                        <!--OQC VIR CONTENT-->
                         <div class="row">
                            <h5><i class="fa fa-list"></i> &nbsp; OQC Visual Inspection Result History</h5>

                            <div class="table-responsive">
                              <table id="tbl_oqcvir_history" class="table table-bordered table-striped table-hover">

                                 <thead style="font-size:85%;">
                                    <tr align="center">
                                      <th>QC Judgement</th>
                                      <th>Lot Number</th>
                                      <th>Submission</th>
                                      <th>Sample Size/Result</th>
                                      <th>OK Qty</th>
                                      <th>NG Qty</th>
                                      <th>Inspection Date</th>
                                      <th>Inspection Start-End Time</th>
                                      <th>Accessories Requirement</th>
                                      <th>C.O.C Requirement</th>
                                      <th>Remarks</th>
                                    </tr>
                                  </thead>
                                  <tbody style="font-size:85%;"></tbody>
                                </table>
                                
                              </table>
                            </div>
                          </div>
                        <!--END OF OQC VIR CONTENT-->
                      </div>

                      <div class="tab-pane fade" id="custom-tabs-one-oqclotapp" role="tabpanel">
                        <!--OQC LOT APP CONTENT-->
                         <div class="row">
                            <h5><i class="fa fa-list"></i> &nbsp; OQC Lot Application History</h5>

                            <div class="table-responsive">
                              <table id="tbl_lotapp_history" class="table table-bordered table-striped table-hover">

                                 <thead style="font-size:85%;">
                                    <tr align="center">
                                      <th>Lot Number</th>
                                      <th>Lot Applied By</th>
                                      <th>Submission</th>
                                      <th>Application Date/Time</th>
                                      <th>Device Category</th>
                                      <th>Certification Lot</th>
                                      <th>Assembly Line</th>
                                      <th>Reel Lot Number</th>
                                      <th>Print Lot Number</th>
                                      <th>Total Number of Reels</th>
                                      <th>Output Quantity</th>
                                      <th>Directions</th>
                                      <th>A Drawing</th>
                                      <th>G Drawing</th>
                                      <th>Guaranteed Lot</th>
                                      <th>Problem</th>
                                      <th>Document Number</th>
                                      <th>Remarks</th>
                                      <th>Production Supervisor</th>
                                      <th>OQC Supervisor</th>
                                    </tr>
                                  </thead>
                                  <tbody style="font-size:85%;"></tbody>
                                </table>
                                
                              </table>
                            </div>
                          </div>
                        <!--END OF OQC LOT APP CONTENT-->
                      </div>

                    </div>

                  </div>

                </div>
              </div>
            </div>

            <!--END OF TABS-->

            <form method="post" id="formPackingInspector">
              @csrf

              <div class="row">
              <div class="col-sm-4 p-2">

                <h6><strong>Check OQC Lot Application Versus Runcard / Web EDI Sticker and Packing List</strong></h6>
                      <div class="row">
                        <div class="col">
                          <div class="input-group input-group-sm mb-3">

                            <select class="form-control form-control-sm select_packin" name="name_c3_label_check" id="id_c3_label_check" readonly>
                              <option selected disabled>---</option>
                                <option value = "1">Accept</option>
                                <option value = "2">Reject</option>
                                <option value = "3">N/A</option>
                            </select>

                            <span class="input-group-append"><button type="button" class="btn btn-info btn-flat btn_open_c3_label_check" id="id_btn_open_c3_label_check" title="Check C3 Label" data-toggle="modal" data-target="#modalC3LabelChecker"><i class="fa fa-location-arrow"></i></button></span>
                          </div>
                        </div>
                      </div>

                   <h6><strong>Does the actual packing condition and the packaging materials used comply with the packing manual document?</strong></h6>
              
                   <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                      <!--   <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">1.5.1 Orientation of Units</span>
                        </div> -->
                        <select class="form-control form-control-sm select_packin" name="name_check_pack_condition" id="id_check_pack_condition">
                          <option selected value = "1">Yes</option>
                          <option value = "2">No</option>
                          <option value = "3">N/A</option>
                        </select>
                      </div>
                    </div>
                  </div>

              </div>

              <div class="col-sm-4 p-2">

                <h6><strong><br>Does the product require accessories?</strong></h6>
              
                   <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                      <!--   <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">1.5.1 Orientation of Units</span>
                        </div> -->
                        <select class="form-control form-control-sm" name="name_require_accessories" id="id_require_accessories">
                          <option value = "1" selected>Yes</option>
                          <option value = "2">No</option>
                          <option value = "3">N/A</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  
                   <h6><strong><br><br>Packing Code No.</strong></h6>
              
                   <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">

                        <input type="text" class="form-control form-control-sm" id="id_packin_pack_code_no" name="name_packin_pack_code_no" placeholder="Auto-inserted Data" readonly>
                      </div>
                    </div>
                  </div>
              </div>


                <div class="col-sm-4 p-2">

                  <br><br>
                  <h6><strong><br>Packing Inspector Judgement</strong></h6><hr>
              
                   <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                     <div class="input-group-prepend w-50">
                          <span class="input-group-text primary w-100" style="background-color: rgb(57, 179, 215); color: rgb(255, 255, 255); border-color: rgb(57, 179, 215);" id="basic-addon1"><strong>JUDGEMENT</strong></span>
                        </div>
                        <select class="form-control form-control-sm" name="name_packin_judgement" id="id_packin_judgement" readonly>
                          <option disabled selected>---</option>
                          <option value = "1">ACCEPTED</option>
                          <option value = "2">REJECTED</option>
                        </select>
                      </div>
                    </div>
                  </div>

                  <hr>
                </div>

                  
              </div>


                <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-success" id="btn_save_packin">Save</button>
                </div>

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

      </form> <!--FORM PRELIM PACKING OPERATOR-->


    <div class="modal fade" id="modalC3LabelChecker" data-formid="" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-xl modal-dialog-centered" style="width:110%">
         <div class="modal-content">

           <div class="modal-header">
            <h4 class="modal-title"><i class="fa fa-checklist"></i> C3 Label Checker</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">

             <input type="hidden" id="id_manufacture" name="name_manufacture" readonly>

             <!--C3 LABEL CHECKER-->
                <div class="row">

                  <div class="col-md-6">

                            <div class="table-responsive">
                              <table id="tbl_c3_label_details" class="table table-bordered table-striped table-hover">
                                <h5><strong>SYSTEM-RETRIEVED DETAILS</strong></h5>
                                 <thead style="font-size:85%;">
                                    <tr align="center">
                                      <th>Customer P/N</th>
                                      <th>Manufacture P/N</th>
                                      <th>Quantity</th>
                                      <th>Lot Number</th>
                                    </tr>
                                  </thead>
                                  <tbody style="font-size:85%;"></tbody>
                                
                              </table>
                            </div>

                          </div>


                          <div class="col-md-6">

                            <div class="table-responsive">
                              <table id="tbl_c3_label_checker" class="table table-bordered table-striped table-hover">
                                <h5><strong>LABEL DETAILS</strong></h5>
                                 <thead style="font-size:85%;">
                                    <tr align="center">
                                      <th>Customer P/N</th>
                                      <th>Manufacture P/N</th>
                                      <th>Quantity</th>
                                      <th>Lot Number</th>
                                    </tr>
                                  </thead>
                                  <tbody style="font-size:85%;"></tbody>
                                
                              </table>
                            </div>

                          </div>
                  </div>

                   
          </div>

           <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-secondary" id="btn_return_c3" disabled>Return <span id="validate_c3"></span></button>
                </div>
         </div> 
      </div>
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
            <input type="text" id="txt_search_po_number" class="hidden_scanner_input" autocomplete="off">
          </div>
        </div>
      </div>
  </div>

    
  @endsection

  @section('js_content')

  <script type="text/javascript">

 $('#id_c3_label_check').css("pointer-events","none");
 $('#id_packin_judgement').css("pointer-events","none");

  let dt_packinginspector;
  let dt_packop_history;
  let dt_oqcvir_history;
  let dt_lotapp_history;

  let c3_counter = 0;

  //C3 LABEL
  let dt_c3label_details;
  let dt_c3label_checker;

   $(document).ready(function () {
      bsCustomFileInput.init();

  });

   dt_packinginspector =  $("#tblPackingInspector").DataTable(
      {
        "processing":true,
        "serverSide":true,

        "ajax" : {
          url: "packin_view_batches",
          data: function (param)
          {
            param.po_number = $("#txt_search_po_number").val();
          }
        },

        "columns":[
        { "data" : "action", orderable:false, searchable:false },
        { "data" : "pack_code" },
        { "data" : "total_box_qty"},
        { "data" : "status" }
        ]

      });//end of datatable of summary

    dt_c3label_details =  $("#tbl_c3_label_details").DataTable(
      {
        "processing":true,
        "serverSide":true,
        "paging":   false,
        "ordering": false,
        "info":     false,
        "searching": false,

        "ajax" : {
          url: "retrieve_c3_label_details",
          data: function (param)
          {
            param.po_number = $("#txt_search_po_number").val();
            param.packing_code = $("#id_modal_packing_code").val();
            param.manufacture = $("#id_device_name").val();
          }
        },

        "columns":[
        { "data" : "customer", orderable:false, searchable:false },
        { "data" : "manufacture", orderable:false, searchable:false },
        { "data" : "quantity", orderable:false, searchable:false },
        { "data" : "lot_no", orderable:false, searchable:false }
        ]

      });//end of datatable of summary

    dt_c3label_checker =  $("#tbl_c3_label_checker").DataTable(
      {
        "processing":true,
        "serverSide":true,
        "paging":   false,
        "ordering": false,
        "info":     false,
        "searching": false,

        "ajax" : {
          url: "retrieve_c3_label_checker",
          data: function (param)
          {
            param.po_number = $("#txt_search_po_number").val();
            param.packing_code = $("#id_modal_packing_code").val();
            param.manufacture = $("#id_device_name").val();
          }
        },

        "columns":[
        { "data" : "customer", orderable:false, searchable:false },
        { "data" : "manufacture", orderable:false, searchable:false },
        { "data" : "quantity", orderable:false, searchable:false },
        { "data" : "lot_no", orderable:false, searchable:false }
        ]

      });//end of datatable of summary

      dt_packop_history =  $("#tbl_packop_history").DataTable(
      {
        "processing":true,
        "serverSide":true,

        "ajax" : {
          url: "view_packop_history_by_packing_code",
          data: function (param)
          {
            param.packing_code = $("#id_modal_packing_code").val();
          }
        },

        "columns":[
        { "data" : "packop_judgement", orderable:false, searchable:false },
        { "data" : "packop_datetime" },
        { "data" : "packop_lot_no" },
        { "data" : "packop_packing_type" },
        { "data" : "packop_unit_condition" },
        { "data" : "packop_po_num" },
        { "data" : "packop_device_name" },
        { "data" : "packop_qty_per_lot" },
        { "data" : "packop_reel_lot_no" },
        { "data" : "packop_total_num_reels" },
        { "data" : "packop_packing_code" },
        { "data" : "packop_orientation_of_units" },
        { "data" : "packop_qty_per_box" },
        { "data" : "packop_ul_sticker" },
        { "data" : "packop_silica_gel" },
        { "data" : "packop_accessories" },
        { "data" : "packop_rohs_sticker" }
        ]

      });//end of datatable of packop history


      dt_oqcvir_history =  $("#tbl_oqcvir_history").DataTable(
      {
        "processing":true,
        "serverSide":true,

        "ajax" : {
          url: "view_oqcvir_history_by_packing_code",
          data: function (param)
          {
            param.packing_code = $("#id_modal_packing_code").val();
          }
        },

        "columns":[
        { "data" : "oqcvir_qc_judgement", orderable:false, searchable:false },
        { "data" : "oqcvir_lot_no" },
        { "data" : "oqcvir_submission" },
        { "data" : "oqcvir_sample_size" },
        { "data" : "oqcvir_ok_qty" },
        { "data" : "oqcvir_ng_qty" },
        { "data" : "oqcvir_insp_date" },
        { "data" : "oqcvir_insp_time" },
        { "data" : "oqcvir_accessories" },
        { "data" : "oqcvir_coc" },
        { "data" : "oqcvir_remarks" }
        ]

      });//end of datatable of oqcvir history


      dt_lotapp_history =  $("#tbl_lotapp_history").DataTable(
      {
        "processing":true,
        "serverSide":true,

        "ajax" : {
          url: "view_lotapp_history_by_packing_code",
          data: function (param)
          {
            param.packing_code = $("#id_modal_packing_code").val();
          }
        },

        "columns":[
        { "data" : "lotapp_lot_no", orderable:false, searchable:false },
        { "data" : "lotapp_applied_by" },
        { "data" : "lotapp_submission" },
        { "data" : "lotapp_datetime" },
        { "data" : "lotapp_device_cat" },
        { "data" : "lotapp_cert_lot" },
        { "data" : "lotapp_assy_line" },
        { "data" : "lotapp_reel_lot" },
        { "data" : "lotapp_print_lot" },
        { "data" : "lotapp_ttl_reel" },
        { "data" : "lotapp_output_qty" },
        { "data" : "lotapp_direction" },
        { "data" : "lotapp_adrawing" },
        { "data" : "lotapp_gdrawing" },
        { "data" : "lotapp_guaranteed_lot" },
        { "data" : "lotapp_problem" },
        { "data" : "lotapp_doc_no" },
        { "data" : "lotapp_remarks" },
        { "data" : "lotapp_prod_supervisor" },
        { "data" : "lotapp_oqc_supervisor" }
        ]

      });//end of datatable of oqcvir history



  //BUTTON CLICKS
   $(document).on('keyup','#txt_search_po_number',function(e){
        if( e.keyCode == 13 ){

          $('#id_po_no').val('');
          $('#id_device_name').val('');
          $('#id_po_qty').val('');

          var data = {
          'po'      : $('#txt_search_po_number').val()
          }

          dt_packinginspector.draw();


          data = $.param(data);
        $.ajax({
          type      : "get",
          dataType  : "json",
          data      : data,
          url       : "get_po_details",
          success : function(data){
            
            //$('#txt_search_po_number').val('');

            $('#id_po_no').val( data['po_details'][0]['po_no'] );
            $('#id_device_name').val( data['po_details'][0]['wbs_kitting']['device_name'] );
            $('#id_po_qty').val( data['po_details'][0]['wbs_kitting']['po_qty'] );
            $('#id_box_qty').val( data['po_details'][0]['wbs_kitting']['device_info']['ship_boxing'] );

          },error : function(data){

              }

            }); 
        }
      });

    $(document).on('click','.btn_update_packing_inspector',function(e){

          let packing_code = $(this).attr('packing-code');
          $('#id_modal_packing_code').val(packing_code);
          $('#id_packin_pack_code_no').val(packing_code);

          dt_packop_history.draw();
          dt_oqcvir_history.draw();
          dt_lotapp_history.draw();
      });

    //C3 LABEL CHECKER
     $(document).on('click','.btn_open_c3_label_check',function(e){

            $('#id_c3_label_check').val('---');
            $('#id_packin_judgement').val('---');
        
            dt_c3label_checker.draw();
            dt_c3label_details.draw();
      });


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

     $(document).on('click','#btn_save_packin',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','#formPackingInspector').modal('show');
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

          $('#modalPackingInspector').on('hidden.bs.modal', function (e) {
        // and empty the modal-content element
          $('#id_c3_label_check').val('---');
          $('#id_packin_judgement').val('---');
          $('#id_check_pack_condition').val('1');
          $('#id_require_accessories').val('1');
          $('#id_packin_pack_code_no').val('');

          //$('#id_btn_link_packcode_packin').removeAttr('disabled');

    });

      //CHECK C3 DEVICE
       $(document).on('keyup', '.c_label_check_device', function(e) {

        if( e.keyCode == 13 ){
            var id = $(this).attr('text-device-id');

            var input_device = "#id_device_" + id;
            var hidden_device = "#id_hidden_device_" + id;
            var status_device = "#id_status_device_" + id;

            if($(input_device).val() != '')
            {
              if($(input_device).val() == $(hidden_device).val())
              {
                $(status_device).val('1');
                $(status_device).change();
              }
              else
              {
                $(status_device).val('2');
                $(status_device).change();
              }
            }
            else
            {
              $(status_device).val('');
              $(status_device).change();
            }
            
          }
        });

       //CHECK C3 MANUFACTURE
        $(document).on('keyup', '.c_label_check_manufacture', function(e) {

        if( e.keyCode == 13 ){
            var id = $(this).attr('text-manufacture-id');

            var input_manufacture = "#id_manufacture_" + id;
            var hidden_manufacture = "#id_hidden_manufacture_" + id;
            var status_manufacture = "#id_status_manufacture_" + id;

            if($(input_manufacture).val() != '')
            {
                if($(input_manufacture).val() == $(hidden_manufacture).val())
                {
                  $(status_manufacture).val('1');
                  $(status_manufacture).change();
                }
                else
                {
                  $(status_manufacture).val('2');
                  $(status_manufacture).change();
                }
            }
            else
            {
              $(status_manufacture).val('');
              $(status_manufacture).change();
            }

          }
        });

        //CHECK C3 QUANTITY
         $(document).on('keyup', '.c_label_check_quantity', function(e) {

        if( e.keyCode == 13 ){
            var id = $(this).attr('text-quantity-id');

            var input_quantity = "#id_quantity_" + id;
            var hidden_quantity = "#id_hidden_quantity_" + id;
            var status_quantity = "#id_status_quantity_" + id;

              if($(input_quantity).val() != '')
              {
                if($(input_quantity).val() == $(hidden_quantity).val())
                {
                  $(status_quantity).val('1');
                  $(status_quantity).change();
                }
                else
                {
                  $(status_quantity).val('2');
                  $(status_quantity).change();
                }
              }
              else
              {
                $(status_quantity).val('');
                $(status_quantity).change();
              }
            }
        });

         //CHECK C3 LOT NO
        $(document).on('keyup', '.c_label_check_lot_no', function(e) {

        if( e.keyCode == 13 ){
            var id = $(this).attr('text-lot-no-id');

            var input_lot_no = "#id_lot_no_" + id;
            var hidden_lot_no = "#id_hidden_lot_no_" + id;
            var status_lot_no = "#id_status_lot_no_" + id;

            if($(input_lot_no).val() != '')
            {
              if($(input_lot_no).val() == $(hidden_lot_no).val())
              {
                  $(status_lot_no).val('1');
                  $(status_lot_no).change();
              }
              else
              {
                  $(status_lot_no).val('2');
                  $(status_lot_no).change();
              }
            }
            else
            {
              $(status_lot_no).val('');
              $(status_lot_no).change();
            }
            
          }
        });

        var global_c3 = 0;

        $(document).on('change','.hidden_c3_check', function(e)
        {
          var c3_counter = 0;
          var c3_blank = 0;

          var $c3_check = $('.hidden_c3_check').filter(function()
          { 
              if(this.value != '')
              {
                if(this.value == '2')
                {
                  c3_counter++;
                }
              }
              else
              {
                c3_blank++;
              }

          });

          global_c3 = c3_counter;

          if(c3_counter > 0)
          {
            $('#validate_c3').text('(Reject)');
            $('#btn_return_c3').removeClass('btn-secondary');
            $('#btn_return_c3').removeClass('btn-success');
            $('#btn_return_c3').addClass('btn-danger');
            document.getElementById("btn_return_c3").disabled = false;
          }
          else
          {
            if(c3_blank > 0)
            {
              $('#validate_c3').text('');
              $('#btn_return_c3').removeClass('btn-danger');
              $('#btn_return_c3').removeClass('btn-success');
              $('#btn_return_c3').addClass('btn-secondary');
              document.getElementById("btn_return_c3").disabled = true;
            }
            else
            {
              $('#validate_c3').text('(Accept)');
              $('#btn_return_c3').removeClass('btn-secondary');
              $('#btn_return_c3').removeClass('btn-danger');
              $('#btn_return_c3').addClass('btn-success');
              document.getElementById("btn_return_c3").disabled = false;
            }
          }
        });


      $('.select_packin').on('change', function() {

          let noCounter = 0;
          $('.select_packin option:selected').each(function(i, obj) {
              if( $(this).val() == 2 ){
                noCounter++;
              }
          });

          if(noCounter > 0)
          {
            $('#id_packin_judgement').val(2);
          }
          else
          {
            $('#id_packin_judgement').val(1);
          }
        });

          $(document).on('click','#btn_return_c3',function(e)
          {
               if(global_c3 > 0)
               {
                  $('#id_c3_label_check').val(2);
                  $('#id_c3_label_check').change();
               }
               else
               {
                  $('#id_c3_label_check').val(1);
                  $('#id_c3_label_check').change();
               }

               $('#modalC3LabelChecker').modal('hide');
          });

           $('#modalC3LabelChecker').on('hidden.bs.modal', function (e) {

              $('#btn_return_c3').removeClass('btn-danger');
              $('#btn_return_c3').removeClass('btn-success');
              $('#btn_return_c3').addClass('btn-secondary');
              global_c3 = 0;
              $('#validate_c3').text('');
              document.getElementById("btn_return_c3").disabled = true;

           });


    $('#formPackingInspector').submit(function(event){

      event.preventDefault();

      SubmitPackin();
    });

  </script>
  @endsection
@endauth