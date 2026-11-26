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
  <!--start content here-->
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
                    <div class="col-sm-3">
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
                        <label>PO No</label>
                          <input type="text" class="form-control" id="id_po_no" readonly="">
                      </div>
                      <div class="col-sm-2">
                        <label>Device Name</label>
                          <input type="text" class="form-control" id="id_device_name" readonly="">
                      </div>
                      <div class="col-sm-2">
                        <label>PO Qty</label>
                          <input type="text" class="form-control" id="id_po_qty" readonly="">
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
                <h3 class="card-title">Current P.O. Lot Application List</h3>

                <button type="button" class="px-2 py-1 btn btn-sm btn-info btn_update_packing_operator hidden_scanner_input" data-toggle="modal" data-target="#modalPackingOperator" title="Update Details" po-num="1" batch-num="1" mat-sub="1" oqclotapp-id="1"><i class="fa fa-edit fa-sm"></i></button>
              </div>



              <!-- Start Page Content -->
              <div class="card-body">

                <div class="table responsive">
                  <table id="tblPackingOperator" class="table table-bordered table-hover" style="width: 100%;">
                    <thead>
                      <tr>
                        <th>Action</th>
                        <th>Lot Application Submission</th>
                        <th>Lot Application Number</th>
                        <th>Applied Lot Quantity</th>
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
    <!----------------------------MODAL----------------------------->

    <div class="modal fade" id="modalPackingOperator">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">

          <div class="modal-header">
            <h4 class="modal-title"><i class="fa fa-edit"></i> Preliminary Production Packing Inspection</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <!--TABS-->
        <!-- ./row -->
        <div class="row">
          <div class="col-12 col-md-12">
            <div class="card card-primary card-tabs">
              <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">OQC Inspection</a>
                  </li>
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                  <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                     
                     <!--OQC INSPECTION MODULE TABLE-->
                       <div class="row">
                            <div class="col-sm-12 p-2">
                              <h4><i class="fa fa-list"></i> &nbsp;Add Lots to Pack</h4> 
                              <br> 
                                <div class="table-responsive">
                                  <table id="tbl_add_lotapp" class="table table-bordered table-striped table-hover" style="min-width: 1500px!important;">
                                    <thead style="font-size:85%;">
                                      <tr align="center">
                                        <th> </th>
                                        <th>Lot Application Number</th>
                                        <th>Applied Lot Quantiy</th>
                                        <th>Status</th>
                                      </tr>
                                    </thead>
                                    <tbody style="font-size:85%;"></tbody>
                                  </table>
                                </div>
                            </div>
                          </div>

                     <!--END OF OQC INSPECTION MODULE TABLE-->
                  </div>
       
                </div>
              </div>
              <!-- /.card -->
            </div>
          </div>
        </div>

         <!--end of tabs-->
            
            <form method="post" id="formPrelimPackingOperator">
              @csrf
              <div class="modal-body">

              <div class="row">

              <input type="hidden" id="id_myponum" name="name_myponum" readonly>

              <input type="hidden" id="id_mybatch" name="name_mybatch" readonly>

              <input type="hidden" id="id_mysub" name="name_mysub" readonly>

              <input type="hidden" id="id_mylotapp" name="name_mylotapp" readonly>

              </div>
           
            <div class="row">

                <div class="col-sm-4 p-2">

                   <h6><strong>1.1 Packing Type</strong></h6>

                  <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                       <!--  <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">1.1 Packing Type</span>
                        </div> -->

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
                          <option value = "5">Unit Mounted on Esafoam</option>
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
                        <select class="form-control form-control-sm selectCertLot" name="name_131" id="id_131">
                          <option value = "1" selected>Yes</option>
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
                        <select class="form-control form-control-sm selectCertLot" name="name_132" id="id_132">
                          <option value = "1" selected>Yes</option>
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
                        <select class="form-control form-control-sm selectCertLot" name="name_133" id="id_133">
                          <option value = "1" selected>Yes</option>
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
                        <select class="form-control form-control-sm selectCertLot" name="name_134" id="id_134">
                          <option value = "1" selected>Yes</option>
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
                          <span class="input-group-text w-100" id="basic-addon1">1.3.5 Total No. of Reels</span>
                        </div>
                        <input type="text" class="form-control form-control-sm" id="id_135" name="name_135" autocomplete="off" placeholder="e.g. 1, 2">
                      </div>
                    </div>
                  </div>
              </div>
              
              <!-------NEXT COLUMN-------->

              <div class="col-sm-4 p-2">

                <h6><strong>1.4 Packing Code</strong></h6>

                 <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">

                        <input type="text" class="form-control form-control-sm" id="id_PackingCode" name="name_PackingCode" autocomplete="off" placeholder="e.g.">
                      </div>
                    </div>
                  </div>

                  <br>
                   <h6><strong>1.5 Does the Packed Units comply with the Packing Manual?</strong></h6>
              
                   <div class="row">
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text w-100" id="basic-addon1">1.5.1 Orientation of Units</span>
                        </div>
                        <select class="form-control form-control-sm selectCertLot" name="name_151" id="id_151">
                          <option value = "1" selected>Yes</option>
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
                        <select class="form-control form-control-sm selectCertLot" name="name_152" id="id_152">
                          <option value = "1" selected>Yes</option>
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
                        <select class="form-control form-control-sm selectCertLot" name="name_153" id="id_153">
                          <option value = "1" selected>Yes</option>
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
                        <select class="form-control form-control-sm selectCertLot" name="name_154" id="id_154">
                          <option value = "1" selected>Yes</option>
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
                        <select class="form-control form-control-sm selectCertLot" name="name_155" id="id_155">
                          <option value = "1" selected>Yes</option>
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
                        <select class="form-control form-control-sm selectCertLot" name="name_156" id="id_156">
                          <option value = "1" selected>Yes</option>
                          <option value = "2">No</option>
                          <option value = "3">N/A</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  
                  </div>

                     <!-------NEXT COLUMN-------->

              <div class="col-sm-4 p-2">

                <br><br><br><br><br><br><br><br><br><br><br>
                 <h6><strong>Packing Operator Judgement</strong></h6><hr>
                    
                    <div class="col">
                      <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend w-50">
                          <span class="input-group-text primary w-100" style="background-color: rgb(57, 179, 215); color: rgb(255, 255, 255); border-color: rgb(57, 179, 215);" id="basic-addon1"><strong>JUDGEMENT</strong></span>
                        </div>
                        <select class="form-control form-control-sm selectCertLot" name="name_judgement" id="id_judgement">
                          <option disabled selected>---</option>
                          <option value = "1">ACCEPT</option>
                          <option value = "2">REJECT</option>
                        </select>
                      </div>
                    </div>

                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-success" id="btn_save_packin">Save</button>
                    </div>

                    </div>

            </div>

            <hr>

              <div class="row">
                            <h5><i class="fa fa-box"></i> &nbsp; Packing Operator History</h5>
                             <br> 
                             <br> 
                              <div class="table-responsive">
                                <table id="tbl_packop_history" class="table table-bordered table-striped table-hover" style="min-width: 1500px!important;">
                                  <thead style="font-size:85%;">
                                    <tr align="center">
                                      <th>Packing Operator Judgement</th>
                                      <th>Judgement Date/Time</th>
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
                              </div>
                </div>         


              </div> <!--Modal Body-->

        </div> <!--Modal Content-->
      </div> <!--Modal Dialog-->
    </div> <!--Modal-->
    <!-----------------------END OF MODAL--------------------------->

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
            <input type="text" id="txt_employee_number_scanner" name="employee_number_scanner" class="hidden_scanner_input">
          </div>
  <!--         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div> -->
        </div>
      </div>
    </div>
    <!-- /.Modal -->

      </form> <!--FORM PRELIM PACKING OPERATOR-->



  @endsection

  @section('js_content')

  <script type="text/javascript">

    let dt_packingoperator;
    let dataTableOQCVIR_summary;
    let dt_packop_history;
    
    $(document).ready(function () {
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
        { "data" : "submission", orderable:false, searchable:false},
        { "data" : "lotapp" },
        { "data" : "lotqty" },
        { "data" : "status" }
        ]
      });//end of datatable

      dataTableOQCVIR_summary = $("#tblOQCVIR_summary").DataTable({
            "processing"    : false,
            "serverSide"  : true,
            "ajax"        : {
              url: "get_oqcvir_summary",
                data: function (param){
                param.fkid_oqclotapp = $("#id_oqclotapp").val();
              }
            },
            
            "columns":[
              { "data" : "sub_raw" },
              { "data" : "oqc_sample" },
              { "data" : "okqty_raw" },
              { "data" : "ngqty_raw" },
              { "data" : "insp_date_raw" },
              { "data" : "insp_setime_raw" },
              { "data" : "insp_name_raw" },
              { "data" : "acc_req_raw" },
              { "data" : "coc_req_raw" },
              { "data" : "result_raw" },
              { "data" : "judgement_raw" },
              { "data" : "remarks" }
            ],
        });//end of dataTable of summary

       dt_packop_history = $("#tbl_packop_history").DataTable({
            "processing"    : false,
            "serverSide"  : true,
            "ajax"        : {
              url: "retrieve_packop_history",
            data: function (param){
                  
                  param.po_num = $("#id_myponum").val();
                  param.batch =  $("#id_mybatch").val();
                  param.oqclotapp_id = $("#id_mylotapp").val();              
              }
            },
            
            "columns":[
              { "data" : "packop_name" },
              { "data" : "packop_date" },
              { "data" : "pack_type" },
              { "data" : "unit_condition" },
              { "data" : "1_3_1" },
              { "data" : "1_3_2" },
              { "data" : "1_3_3" },
              { "data" : "1_3_4" },
              { "data" : "1_3_5" },
              { "data" : "pack_code" },
              { "data" : "1_5_1" },
              { "data" : "1_5_2" },
              { "data" : "1_5_3" },
              { "data" : "1_5_4" },
              { "data" : "1_5_5" },
              { "data" : "1_5_6" },
              
            ],
        });//end of dataTable of summary

        

         $(document).on('keyup','#txt_search_po_number',function(e){
        if( e.keyCode == 13 ){

          $('#id_po_no').val('');
          $('#id_device_name').val('');
          $('#id_po_qty').val('');

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

            //$('#txt_search_po_number').val('');

          },error : function(data){

              }

            }); 
        }
      });

        $(document).on('click','.btn_update_packing_operator',function(e){
       
          let po_num = $(this).attr('po-num');
          let batch_num = $(this).attr('batch-num');
          let mat_sub = $(this).attr('mat-sub');
          let oqclotapp_id = $(this).attr('oqclotapp-id');

           $('#id_myponum').val(po_num);
           $('#id_mybatch').val(batch_num);
           $('#id_mysub').val(mat_sub);
           $('#id_mylotapp').val(oqclotapp_id);

          RetrieveOQCDetails(po_num,batch_num,mat_sub,oqclotapp_id);

          dt_packop_history.draw();
          
      });

    });

    $('#formPrelimPackingOperator').submit(function(event){

      event.preventDefault();

      // alert('burtoy');
      SubmitPackop();

    });

     $(document).on('click','#btn_save_packin',function(e){
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','#formPrelimPackingOperator').modal('show');
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


    </script>

  @endsection
@endauth