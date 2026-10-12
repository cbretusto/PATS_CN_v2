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

@section('title', 'Packing and Shipping')

@section('content_page')
<!-- <link href="{{ URL::asset('public/template/plugins/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet" media="all"> -->

<style type="text/css">
  #iframe_packingandshipping{
    position: absolute;
    width: 100%;
    height: 700px;
/*    width: 100%!important;
    height: 100%!important;
*/    border: none;
  }
  .hidden_scanner_input{
    position: absolute;
    opacity: 0;
  }
 </style>
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header" hidden>
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Packing and Shipping</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Packing and Shipping</li>
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
            <div class="card bg-dark mt-2 mb-2">
              <div class="card-header" hidden>
                <h3 class="card-title">Search P.O.</h3>
              </div>
              <!-- Start Page Content -->
              <div class="card-body p-2">
                <div class="row">
                  <div class="col-3">
                    <div class="input-group input-group-sm">
                      <div class="input-group-prepend">
                        <button type="button" class="btn btn-info" id="btn_scan_po"><i class="fa fa-qrcode"></i></button>
                      </div>
                      <input type="search" class="form-control" placeholder="Scan PO Number" id="txt_search_po_number"><!-- value="450198990900010" -->
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="input-group input-group-sm">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">PO No.</span>
                      </div>
                      <input id="txt_po_number" type="text" class="form-control" placeholder="---" readonly>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="input-group input-group-sm">
                      <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">Device</span>
                      </div>
                      <input id="txt_device_name" type="text" class="form-control" placeholder="---" readonly>
                    </div>
                  </div>
                  <div class="col-1">
                    <div class="dropdown">
                      <a class="btn btn-sm btn-info dropdown-toggle" href="#" role="button" id="dropdownMenuLink_action" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        ACTION
                      </a>
                      <!-- @if(Auth::user()->position == 4||Auth::user()->position == 5||Auth::user()->position == 1||Auth::user()->position == 2) -->
                      <!-- @endif -->

                      <div class="dropdown-menu" aria-labelledby="dropdownMenuLink_action" id="dropdown_links_container">
                        <a class="dropdown-item" href="http://rapid/ACDCS/prdn_home.php?packingandshipping=1"><i class="fas fa-file"></i> Search ACDCS Document</a>
                        <h6 class="dropdown-header dropdown-item-huawei">Huawei - PATS</h6>
                        <a class="dropdown-item dropdown-item-hpats" href="http://rapidx/pats/c3labelprinting?packingandshipping=1"><i class="fas fa-barcode"></i> Print C3 Label for Prod'n</a>
                        <a class="dropdown-item dropdown-item-hpats" href="http://rapidx/pats/prod_preliminary_inspection?packingandshipping=1"><i class="fas fa-box-open"></i> Prod'n Packing Inspection</a>
                        <a class="dropdown-item dropdown-item-hpats" href="http://rapidx/pats/oqc_packing_inspection?packingandshipping=1"><i class="fas fa-box-open"></i> OQC Packing Inspection</a>
                        <a class="dropdown-item dropdown-item-hpats" href="http://rapidx/pats/c3labelprintingforbox?packingandshipping=1"><i class="fas fa-barcode"></i> Print C3 Label for Accessories</a>
                        <a class="dropdown-item dropdown-item-hpats" href="http://rapidx/dlabel_cn_v2?packingandshipping=1"><i class="fas fa-qrcode"></i> Print D Label</a>
                        <a class="dropdown-item dropdown-item-hpats" href="http://rapidx/pats/dlabelchecker?packingandshipping=1"><i class="fas fa-tasks"></i> Check D Label</a>
                        <a class="dropdown-item dropdown-item-hpats" href="http://rapidx/pats/exportshippingrecord?packingandshipping=1"><i class="fa fa-file-excel"></i> Export Packing and Shipment Record</a>
                        <a class="dropdown-item dropdown-item-hpats" href="http://rapidx/pats/exportpartslotrecord?packingandshipping=1"><i class="fa fa-file-excel"></i> Export Parts Lot Record</a>

                        <h6 class="dropdown-header dropdown-item-hcnpts">Huawei - CNPTS</h6>
                        <a class="dropdown-item dropdown-item-hcnpts" href="http://rapid/C3_Labeller_YPICS4v2/pages/index.php?page=home.php"><i class="fas fa-barcode"></i> Print C3 Label for Prod'n</a>
                        <a class="dropdown-item dropdown-item-hcnpts" href="http://rapid/CN-PTS_YPICS4/index2.php?page=packing_trace.inc.php&fn=cn1"><i class="fas fa-box-open"></i> CN-1 Packing Traceability</a>
                        <a class="dropdown-item dropdown-item-hcnpts" href="http://rapid/CN-PTS_YPICS4/index2.php?page=packing_trace.inc.php&fn=cn2"><i class="fas fa-box-open"></i> CN-2 Packing Traceability</a>
                        <a class="dropdown-item dropdown-item-hcnpts" href="http://rapid/C3_Labeller_YPICS4v2/pages/index.php?page=home.php"><i class="fas fa-barcode"></i> Print C3 Label for Accessories</a>
                        <a class="dropdown-item dropdown-item-hcnpts" href="http://rapid/dlabel_cn_v2?packingandshipping=1"><i class="fas fa-qrcode"></i> Print D Label</a>
                        <a class="dropdown-item dropdown-item-hcnpts" href="http://rapidx/pats/dlabelchecker?packingandshipping=1"><i class="fas fa-tasks"></i> Check D Label</a>
                        <a class="dropdown-item dropdown-item-hcnpts" href="http://rapid/CN-PTS_YPICS4/index2.php?page=shipping_trace.inc.php&fn=cn1"><i class="fas fa-dolly"></i> CN1 Shipping Traceability</a>
                        <a class="dropdown-item dropdown-item-hcnpts" href="http://rapid/CN-PTS_YPICS4/index2.php?page=shipping_trace.inc.php&fn=cn2"><i class="fas fa-dolly"></i> CN2 Shipping Traceability</a>

                        <h6 class="dropdown-header dropdown-item-nhpats">Non Huawei - PATS</h6>
                        <a class="dropdown-item dropdown-item-nhpats" href="http://rapid/C3_Labeller_YPICS4v2/pages/index.php?page=home.php"><i class="fas fa-barcode"></i> Print C3 Label for Prod'n</a>
                        <a class="dropdown-item dropdown-item-nhpats" href="http://rapidx/pats/prod_preliminary_inspection?packingandshipping=1"><i class="fas fa-box-open"></i> Prod'n Packing Inspection</a>
                        <a class="dropdown-item dropdown-item-nhpats" href="http://rapidx/pats/oqc_packing_inspection?packingandshipping=1"><i class="fas fa-box-open"></i> OQC Packing Inspection</a>
                        <a class="dropdown-item dropdown-item-nhpats" href="http://rapid/CN-PTS_YPICS4/index2.php?page=packing_trace.inc.php&fn=cn1"><i class="fas fa-box-open"></i> CN-1 Packing Traceability</a>
                        <a class="dropdown-item dropdown-item-nhpats" href="http://rapid/CN-PTS_YPICS4/index2.php?page=packing_trace.inc.php&fn=cn2"><i class="fas fa-box-open"></i> CN-2 Packing Traceability</a>
                        <a class="dropdown-item dropdown-item-nhpats" href="http://rapid/C3_Labeller_YPICS4v2/pages/index.php?page=home.php"><i class="fas fa-barcode"></i> Print C3 Label for Accessories</a>
                        <a class="dropdown-item dropdown-item-nhpats" href="http://rapid/dlabel_cn_v2?packingandshipping=1"><i class="fas fa-qrcode"></i> Print D Label</a>
                        <a class="dropdown-item dropdown-item-nhpats" href="http://rapidx/pats/dlabelchecker?packingandshipping=1"><i class="fas fa-tasks"></i> Check D Label</a>
                        <a class="dropdown-item dropdown-item-nhpats" href="http://rapidx/pats/exportshippingrecord?packingandshipping=1"><i class="fa fa-file-excel"></i> Export Packing and Shipment Record</a>
                        <a class="dropdown-item dropdown-item-nhpats" href="http://rapidx/pats/exportpartslotrecord?packingandshipping=1"><i class="fa fa-file-excel"></i> Export Parts Lot Record</a>
                        <a class="dropdown-item dropdown-item-nhpats" href="http://rapid/CN-PTS_YPICS4/index2.php?page=shipping_trace.inc.php&fn=cn1"><i class="fas fa-dolly"></i> CN1 Shipping Traceability</a>
                        <a class="dropdown-item dropdown-item-nhpats" href="http://rapid/CN-PTS_YPICS4/index2.php?page=shipping_trace.inc.php&fn=cn2"><i class="fas fa-dolly"></i> CN2 Shipping Traceability</a>

                        <h6 class="dropdown-header dropdown-item-nhcnpts">Non Huawei - CNPTS</h6>
                        <a class="dropdown-item dropdown-item-nhcnpts" href="http://rapid/C3_Labeller_YPICS4v2/pages/index.php?page=home.php"><i class="fas fa-barcode"></i> Print C3 Label for Prod'n</a>
                        <a class="dropdown-item dropdown-item-nhcnpts" href="http://rapid/CN-PTS_YPICS4/index2.php?page=packing_trace.inc.php&fn=cn1"><i class="fas fa-box-open"></i> CN-1 Packing Traceability</a>
                        <a class="dropdown-item dropdown-item-nhcnpts" href="http://rapid/CN-PTS_YPICS4/index2.php?page=packing_trace.inc.php&fn=cn2"><i class="fas fa-box-open"></i> CN-2 Packing Traceability</a>
                        <a class="dropdown-item dropdown-item-nhcnpts" href="http://rapid/C3_Labeller_YPICS4v2/pages/index.php?page=home.php"><i class="fas fa-barcode"></i> Print C3 Label for Accessories</a>
                        <a class="dropdown-item dropdown-item-nhcnpts" href="http://rapid/dlabel_cn_v2?packingandshipping=1"><i class="fas fa-qrcode"></i> Print D Label</a>
                        <a class="dropdown-item dropdown-item-nhcnpts" href="http://rapidx/pats/dlabelchecker?packingandshipping=1"><i class="fas fa-tasks"></i> Check D Label</a>
                        <a class="dropdown-item dropdown-item-nhcnpts" href="http://rapid/CN-PTS_YPICS4/index2.php?page=shipping_trace.inc.php&fn=cn1"><i class="fas fa-dolly"></i> CN1 Shipping Traceability</a>
                        <a class="dropdown-item dropdown-item-nhcnpts" href="http://rapid/CN-PTS_YPICS4/index2.php?page=shipping_trace.inc.php&fn=cn2"><i class="fas fa-dolly"></i> CN2 Shipping Traceability</a>

                      </div>
                    </div>
                  </div>
                  <div class="col text-left">
                    <em class="text-warning font-weight-bold" id="lbl_huawei" style="display: none;">
                      * PATS CN - Huawei
                    </em>
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
        <div class="col-12">
          <!-- general form elements -->
          <div>
            <iframe id="iframe_packingandshipping" src=""></iframe>
            <!-- <iframe id="iframe_packingandshipping" src="http://rapid/dlabelv2_test/index.php?systemname=pats"></iframe> -->
          </div>
        </div>
      </div>
      <!-- /.row -->
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->

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

</div>
<!-- /.content-wrapper -->
@endsection

@section('js_content')

<!-- <script src="{{ URL::asset('public/template/plugins/jquery-ui/jquery-ui.min.js') }}"></script> -->
<!-- <script src="{{ URL::asset('public/template/plugins/qz-print-free_1.8.0_src/qz-print/js/deployJava.js') }}"></script> -->
<!-- <script type="text/javascript" src="dist/qz-print-free_1.8.0_src/qz-print/js/deployJava.js"></script>
<script type="text/javascript" src="dist/jquery/jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="dist/jquery/jquery-ui-1.12.1/jquery-ui.min.js"></script>
-->

<script type="text/javascript">
  $(document).ready(function(){
    setTimeout(function() {
      $('.nav-link').find('.fa-bars').closest('a').click();
    }, 100);

    search_po_number('1');

    $('#dropdown_links_container').on('click','a',function(e){
      $('#iframe_packingandshipping').attr('src', '' );
      e.preventDefault();

      var src = $(this).attr('href');
      if ((src).indexOf("rapidx") <= 0){
          src = 'http://rapid/NAAYES/api/session_check.php?urlx='+src;
      }
      $('#iframe_packingandshipping').attr('src', src );

    })
    //-----
    $(document).on('click','#btn_scan_po',function(e){
      $('#txt_qrcode_scanner').val('');
      $('#mdl_qrcode_scanner').attr('data-formid','fn_scan_po_number').modal('show');
    });

    $(document).on('keypress',function(e){
      if( ($("#mdl_qrcode_scanner").data('bs.modal') || {})._isShown ){
        $('#txt_qrcode_scanner').focus();
        if( e.keyCode == 13 ){
          $('#mdl_qrcode_scanner').modal('hide');
          var formid = $("#mdl_qrcode_scanner").attr('data-formid');

          if ( ( formid ).indexOf('#') > -1){
            $( formid ).submit();
          }
          else{
            switch( formid ){

              case 'fn_scan_po_number':
                var val = $('#txt_qrcode_scanner').val();
                $('#txt_search_po_number').val(val);
                search_po_number( $('#txt_search_po_number').val() );
              break;

              default:
              break;
            }
          }            
        }//key
      }
    });

    $('#txt_search_po_number').on('keypress',function(e){
        if( e.keyCode == 13 ){
            // $('#txt_search_po_number').val('');
            search_po_number( $('#txt_search_po_number').val() );
        }
    });

    $("#iframe_packingandshipping").on("load", function () {
      var urlx = ( document.getElementById("iframe_packingandshipping").contentWindow.location.href );
      if (urlx.indexOf("login") >= 0||urlx.indexOf("dashboard") >= 0){
        window.location.href = 'http://rapidx/pats/dashboard';
      }
    })

  });

  function search_po_number(element){
      $('#txt_po_number').val('');
      $('#txt_device_name').val('').attr('data-device_code','');
      $('#iframe_packingandshipping').attr('src', '' );

      $('#lbl_huawei').hide();
      $('#dropdown_links_container .dropdown-item-hpats').hide();
      $('#dropdown_links_container .dropdown-item-hcnpts').hide();
      $('#dropdown_links_container .dropdown-item-nhpats').hide();
      $('#dropdown_links_container .dropdown-item-nhcnpts').hide();


      var data = {
        'po'        : element,
      };
      $.ajax({
        'data'      : data,
        'type'      : 'get',
        'dataType'  : 'json',
        'url'       : 'http://rapid/NAAYES/api/ypics_po_details_for_dlabel.php',
        success : function(data){
          if($.trim(data)){
            $('#txt_po_number').val( data['ponum'] );
            $('#txt_device_name').val( data['prodname'] );
            fn_checklinks();
          }
        }
      });

      // var data = {
      //   'po_number' : element,
      // }
      // $.ajax({
      //   'data'      : data,
      //   'type'      : 'get',
      //   'dataType'  : 'json',
      //   'url'       : 'select_po_details',
      //   success     : function(data){
      //     if ($.trim(data)){
      //       $('#txt_po_number').val( data[0]['po'] );
      //       $('#txt_device_name').val( data[0]['kit_issuance']['device_name'] ).attr('data-device_code',data[0]['kit_issuance']['device_code']);
      //       fn_checklinks();
      //     }
      //   },
      //   completed     : function(data){
      //   },
      //   error     : function(data){
      //   },
      // });
  }

  function fn_checklinks(){
    var data = {
      'name' : $('#txt_device_name').val(),
    };
    $.ajax({
      'data'      : data,
      'type'      : 'get',
      'dataType'  : 'json',
      'url'       : 'http://rapidx/NAAYES/api/devices.php',
      success : function(data){
        var links = 4;//cnpts non huawei default
        if($.trim(data)){
          links = data[0]['product_type'];
        }
        assignlinks(links);
      }
    });
  }

  function assignlinks(product){
    if(product==1){
      $('#dropdown_links_container .dropdown-item-hpats').show();
      $('#lbl_huawei').show();
    }
    else if(product==2){
      $('#dropdown_links_container .dropdown-item-hcnpts').show();
      $('#lbl_huawei').show();
    }
    else if(product==3){
      $('#dropdown_links_container .dropdown-item-nhpats').show();
    }
    else{//also 4 - non huawei cn pts
      $('#dropdown_links_container .dropdown-item-nhcnpts').show();
    }
  }

  // function check_session_expired(){
  //     var data = {
  //       'a' : '',
  //     }
  //     $.ajax({
  //       'data'      : data,
  //       'type'      : 'get',
  //       'dataType'  : 'json',
  //       'url'       : 'check_session_expired',
  //       success     : function(data){
  //         if ($.trim(data)){
  //           if( data['expired'] == 0 ){
  //             window.location.reload();
  //           }
  //         }
  //       },
  //       completed     : function(data){
  //       },
  //       error     : function(data){
  //       },
  //     });
  // }

</script>
@endsection
@endauth