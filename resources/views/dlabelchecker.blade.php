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

@section('title', 'D Label Checker')

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
  .hidden_scanner_input{
    position: absolute;
    opacity: 0;
  }

  .table{
    min-width: 600px;
  }

  ul.lotnumbers {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 5px 0;
  }
  .lotnumbers:not(.lotnumbersheader) li {
    border: 1px solid #aaa;
  }
  .lotnumbers li {
    padding: 10px 25px;
    margin: 0 100px;
    position: relative;
    width: 140px;
    text-align: center;
  }

  .lotnumbers li:first-child {
    margin-left: 0;
  }
  .lotnumbers:not(.lotnumbersheader) li:not(:last-child):after {
    content: '';
    height: 1px;
    background: #aaa;
    width: 200px;
    position: absolute;
    right: -200px;
    top: 50%;
  }

  .lotnumbers li:nth-child(3) {
    padding: 10px 10px;
    border: 1px solid #aaa;
    margin: 0 100px;
    position: relative;
    height: 50px;
    width: 50px;
    border-radius: 50%;
  }


  /* Added by -JD */
  #txt_scan_c3_label{
    text-transform: uppercase;
  }



  </style>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>D Label Checker</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">D Label Checker</li>
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
                <h3 class="card-title">Scan D Label</h3>
              </div>

              <!-- Start Page Content -->
              <div class="card-body">
                <div class="row mb-2">
                  <div class="col-6">

                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend">
                        <button type="button" class="btn btn-info" id="btn_scan_d_label"><i class="fa fa-qrcode"></i></button>
                      </div>
                      <span class="form-control bg-light">Scan D Label</span>
                      <input type="search" class="form-control d-none" placeholder="Scan D Label" id="txt_scan_d_label" readonly><!-- value="450198990900010" -->
                    </div>

                  </div>

                  <div class="col-6">

                    <div class="input-group input-group-sm mb-3">
                      <div class="input-group-prepend">
                        <button type="button" class="btn btn-info" id="btn_scan_c3_label"><i class="fa fa-qrcode"></i></button>
                      </div>
                      <span class="form-control bg-light">Scan C3 Label</span>
                      <input type="search" class="form-control" placeholder="XXXX-XXX" id="txt_scan_c3_label"><!-- value="450198990900010" -->
                    </div>

                  </div>

                </div>
                <div class="row">
                  <div class="col-12">
                    <div class="table-responsive" style="overflow: auto;">
                      <table class="table table-sm table-bordered table-hover" id="tbl_d_label_checker">
                        <thead>
                          <tr>
                            <th colspan="3">LOT NUMBERS</th>
                          </tr>
                          <tr>
                            <th>#</th>
                            <th>Lot numbers from D Label</th>
                            <th>Lot numbers from C3 Label</th>
                          </tr>
                        </thead>
                        <tbody></tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col text-right">
                    <button type="button" class="btn btn-sm btn-secondary" id="btn_clear_checker">Clear</button>
                    <button type="button" class="btn btn-sm btn-success" id="btn_save_checker">Save</button>
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
                <h3 class="card-title">Checked</h3>
              </div>
              <!-- Start Page Content -->
              <div class="card-body">
                <div class="row">
                  <div class="col-12">
                    <div class="table-responsive">
                      <table class="table table-sm table-bordered table-hover" id="tbl_d_label_checker_history">
                        <thead>
                          <tr class="bg-light">
                            <th></th>
                            <th>Result</th>
                            <th>PO Number</th>
                            <th>Unique number</th>
                            <th>Checked at</th>
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
    <div class="modal fade" id="mdl_select_history" tabindex="-1" role="dialog" aria-labelledby="cnptsmodal" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i class="fas fa-info-circle text-info"></i> Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12">
                <div class="table-responsive" style="overflow: auto;">
                  <table class="table table-sm table-bordered table-hover text-center" id="tbl_d_label_checker_details" style="min-width: auto;">
                    <thead>
                      <tr>
                        <th colspan="3">LOT NUMBERS</th>
                      </tr>
                      <tr>
                        <th>#</th>
                        <th>D Label</th>
                        <th>C3 Label</th>
                      </tr>
                    </thead>
                    <tbody></tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
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
             <h1><i class="fa fa-barcode fa-lg"></i></h1>
           </div>
           <input type="text" id="txt_employee_number_scanner" class="hidden_scanner_input" autocomplete="off">
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
   <div class="modal fade" id="mdl_alert" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
     <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
       <div class="modal-content">
         <div class="modal-header border-bottom-0 pb-1">
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

  </div>
  <!-- /.content-wrapper -->
  @endsection

  @section('js_content')
  <script type="text/javascript">
//================= FOR IFRAME OF PACKING AND SHIPPING MODULE

//--------------------
//prevent typing of barcode/qr code
//--------------------
$(document).on('keydown',function(e){
  if ($("#txt_employee_number_scanner").is(":focus")) {
    setTimeout(function() {
      $("#txt_employee_number_scanner").val('');
    }, 50);
  }
});
//--------------------
//prevent typing of barcode/qr code
//--------------------


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


    let dt_d_label_checker_history;

    $(document).ready(function(){


     dt_d_label_checker_history      = $('#tbl_d_label_checker_history').DataTable({
       "processing" : true,
         "serverSide" : true,
         "ajax" : {
           url: "select_d_label_checker_history",
           data: function (param){
           }
         },
         bAutoWidth: false,
         "columns":[
           { "data" : "raw_action", orderable:false, searchable:false, "width":"60px" },
           { "data" : "raw_status" },
           { "data" : "po_no" },
           { "data" : "unique_no_start" },
           { "data" : "created_at" },
           // { "data" : "created_by" },
         ],
         order: [[4, "desc"]],
         "rowCallback": function(row,data,index ){
         },
     });//end of DataTable



      $('#tbl_d_label_checker_history').on('click','.td_btn_open_details',function(e){
        $('#tbl_d_label_checker_details tbody').html('');
        var data = {
          'td_id'        : $(this).closest('td').find('.td_id').val(),
        };
        $.ajax({
          'data'      : data,
          'type'      : 'get',
          'dataType'  : 'json',
          'url'       : 'select_d_label_checker_details',
          success : function(data){
            var html = ''
            if($.trim(data)){
              var data_arr_a = data[0]['d_label_lot_no_arr'].split(",");
              var data_arr_b = data[0]['c3_label_lot_no_arr'].split(",");
              for (var i = 0 ; i < data_arr_a.length; i++) {
                html += '<tr>';
                  html += '<td>';
                  html += i+1;
                  html += '</td>';
                  html += '<td>';
                  html += data_arr_a[i];
                  html += '</td>';
                  html += '<td>';
                  html += data_arr_b[i];
                  html += '</td>';
                html += '</tr>';
              }
              $('#tbl_d_label_checker_details tbody').html(html);
              check_matched( $('#tbl_d_label_checker_details') );
            }
          }
        });
        $('#mdl_select_history').modal('show');
      });


      $(document).on('click','#btn_scan_d_label',function(e){
        $('#txt_scan_d_label').val('');
        $('#txt_scan_c3_label').val('');
        $('#btn_clear_checker').click();

        $('#txt_qrcode_scanner').val('');
        $('#mdl_qrcode_scanner').attr('data-formid','fn_scan_d_label').modal('show');
      });
      $(document).on('click','#btn_scan_c3_label',function(e){
        $('#txt_qrcode_scanner').val('');
        $('#mdl_qrcode_scanner').attr('data-formid','fn_scan_c3_label').modal('show');
      });

      $(document).on('keyup','#txt_scan_c3_label',function(e){
        if( e.keyCode == 13 ){
          fn_scan_c3_label();
        }
      });


      $('#btn_clear_checker').click(function(){
        $('#tbl_d_label_checker tbody').html('').attr('data-unique_no_start','').attr('data-po_no','');
      });

      $('#btn_save_checker').click(function(){

        var invalid_input_msg = '';
        if( $('#tbl_d_label_checker tbody tr').length < 1 ){
          invalid_input_msg = 'Nothing to save.';
        }

        if(invalid_input_msg){
          show_alert('<i class="fa fa-exclamation-triangle text-warning"></i>','Message',invalid_input_msg,0);
          return;
        }
          //-----
        $('#txt_employee_number_scanner').val('');
        $('#mdl_employee_number_scanner').attr('data-formid','insert_d_label_checker_history').modal('show');

      });

       //----------
       //----------
       //----------
      $( ".modal" ).on('shown.bs.modal', function(){
        $(this).find('.hidden_scanner_input').focus();
      });
      $(document).on('keypress',function(e){
         if( ($("#mdl_employee_number_scanner").data('bs.modal') || {})._isShown ){ // check if modal is open, another option $('#mdl_employee_number_scanner').is(':visible');
           if( e.keyCode == 13 ){
             $('#mdl_employee_number_scanner').modal('hide');
             var formid = $("#mdl_employee_number_scanner").attr('data-formid');

             if ( ( formid ).indexOf('#') > -1){
               $( formid ).submit();
             }
             else{
               switch( formid ){
                 case 'insert_d_label_checker_history':
                   insert_d_label_checker_history();
                 break;
     
                 default:
                 break;
               }
             }
           }
         }
        else if( ($("#mdl_qrcode_scanner").data('bs.modal') || {})._isShown ){
          $('#txt_scan_c3_label').val('');
          if( e.keyCode == 13 ){
            $('#mdl_qrcode_scanner').modal('hide');
            var formid = $("#mdl_qrcode_scanner").attr('data-formid');

            if ( ( formid ).indexOf('#') > -1){
              $( formid ).submit();
            }
            else{
              switch( formid ){
                case 'fn_scan_d_label':
                var val = $('#txt_qrcode_scanner').val();
                $('#txt_scan_d_label').val(val);
                fn_scan_d_label();
                break;

                case 'fn_scan_c3_label':
                var val = $('#txt_qrcode_scanner').val();
                $('#txt_scan_c3_label').val(val);
                fn_scan_c3_label();
                break;

                default:
                break;
              }
            }
          }//key
        }
      });
       //----------
       //----------
       //----------





    });//doc

 // d_label  105617901         4502334273     G                    000001400R2022072800000332714 2714-01Y2       2714 2714-02Y2                                                                                                                                                                                                                                                                           
 // d_label  106794101         4502329196     G                    000001800R2022072800000342727 2727-14Y       2727 2727-13Y       2727 2727-16Y       2727 2727-15Y                                                                                                                                                                                                                                   

    function fn_scan_d_label(){
  
      var d_label = $('#txt_scan_d_label').val();

      d_label      

      var lot_numbers_str = d_label.substring(79);
      lot_numbers_str = $.trim(lot_numbers_str);
      console.log('lot_numbers_str', lot_numbers_str);
      // var lot_numbers_arr = lot_numbers_str.match(/.{1,20}/g);

      let lot_numbers_arr = [];
      let lot_numbers_str_splitted = lot_numbers_str.split('       ')
      for (let index = 0; index < lot_numbers_str_splitted.length; index++) {
        lot_numbers_arr.push(lot_numbers_str_splitted[index].substring(5));
      }
      console.log('lot_numbers_arr', lot_numbers_arr);

      var html = '';
      for (var i = 0 ; i < lot_numbers_arr.length; i++) {
        var lot_substr = lot_numbers_arr[i];
        html += '<tr>';
          html += '<td>';
          html += i+1;
          html += '</td>';
          html += '<td>';
          html += lot_substr;
          html += '</td>';
          html += '<td>';
          html += '</td>';
        html += '</tr>';
      }
      $('#tbl_d_label_checker tbody').html(html).attr('data-unique_no_start', d_label.substring(64,79)).attr('data-po_no',d_label.substring(18,28));
      check_matched( $('#tbl_d_label_checker') );

      
      // Old
      // var d_label = $('#txt_scan_d_label').val();

      // d_label      

      // var lot_numbers_str = d_label.substring(79);
      // lot_numbers_str = $.trim(lot_numbers_str);
      // var lot_numbers_arr = lot_numbers_str.match(/.{1,20}/g);


      // var html = '';
      // for (var i = 0 ; i < lot_numbers_arr.length; i++) {
      //   var lot_substr = lot_numbers_arr[i].substring(5,13);
      //   html += '<tr>';
      //     html += '<td>';
      //     html += i+1;
      //     html += '</td>';
      //     html += '<td>';
      //     html += lot_substr;
      //     html += '</td>';
      //     html += '<td>';
      //     html += '</td>';
      //   html += '</tr>';
      // }
      // $('#tbl_d_label_checker tbody').html(html).attr('data-unique_no_start', d_label.substring(64,79)).attr('data-po_no',d_label.substring(18,28));
      // check_matched( $('#tbl_d_label_checker') );
    }

    function fn_scan_c3_label(){
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
      var matched = 0;
      var txt_scan_c3_label = $('#txt_scan_c3_label').val();

      /*
        Old Code
      */
      // if( txt_scan_c3_label.length > 8 ){
      //   txt_scan_c3_label = $('#txt_scan_c3_label').val().substring(2);
      // }
      
      /*
        Add checker for format 27
        * lets say the c3 label QR Code content is "450206436900010 1Y09-01A 106567001 240"
        * then start from index 0 to index 16 since we pass 16, then the rest of 16 below will be excluded 
        * and the 16 will be included as first index to the rest of the string and returns the substring(so in that case, the substring is "1Y09-01A 106567001")
        * after we returned the substring, we slice it from 0 to the last index of the returned substring values(1Y09-01A 106567001) of the first occurence of specified value the space(" ").
        * and will return a value something like "1Y09-01A" which is the lot number

        ? modified by JD 11-09-2021
      */
      if(txt_scan_c3_label.length >= 50){
        // Reel Lot QR Code for C3 Label
        var reelLotSubstring = txt_scan_c3_label.substring(106);
        console.log('reel no substring: ', reelLotSubstring);
        var reelLot = $.trim(reelLotSubstring.substr(reelLotSubstring, 10));
        console.log('reel_lot: ', reelLot);
        txt_scan_c3_label = reelLot;
        console.log('Format 28 with revised C3 Label');
      }
      else if(txt_scan_c3_label.length == 38 || txt_scan_c3_label.length == 39 || txt_scan_c3_label.length >= 40 || txt_scan_c3_label.length == 35 || txt_scan_c3_label.length == 34){
        txt_scan_c3_label = $('#txt_scan_c3_label').val().substring(16);
        console.log(txt_scan_c3_label); // 1Y09-01A 106567001 240
        txt_scan_c3_label = txt_scan_c3_label.slice(0, txt_scan_c3_label.indexOf(' '));
        console.log(txt_scan_c3_label); // 1Y09-01A
      }
      // else if( txt_scan_c3_label.length > 8 ){
      //   txt_scan_c3_label = $('#txt_scan_c3_label').val().substring(2);
      // }
      else if( txt_scan_c3_label.length > 9 ){ //03132023 
        txt_scan_c3_label = $('#txt_scan_c3_label').val().substring(2);
      }

      $('#tbl_d_label_checker tbody tr').each(function(){
        if($(this).find('td:eq(1)').text() == txt_scan_c3_label){
          $(this).find('td:eq(2)').text(txt_scan_c3_label);
          matched = 1;
        }
      });

      if(matched == 0){
        toastr.error('Please scan again!');
      }
      
      /**
       * Old code
       * Commented by -JD 06-29-2022
      */
      // if(matched == 0){
      //   var html = '';
      //   html += '<tr>';
      //     html += '<td>';
      //     html += $('#tbl_d_label_checker tbody tr').length+1;
      //     html += '</td>';
      //     html += '<td>';
      //     html += '</td>';
      //     html += '<td>';
      //     html += txt_scan_c3_label;
      //     html += '</td>';
      //   html += '</tr>';
      //   $('#tbl_d_label_checker tbody').append(html);
      // }
      
      setTimeout(function() {
        $('#btn_scan_c3_label').trigger('click');
      }, 500);
      check_matched( $('#tbl_d_label_checker') );
    }

    function check_matched(table){
      $(table).find('tbody tr').each(function(){
        $(this).removeClass('table-success table-danger');
        if($(this).find('td:eq(1)').text() == $(this).find('td:eq(2)').text()){
          $(this).addClass('table-success');
        }
        else{
          $(this).addClass('table-danger');
        }
      });
    }


    function insert_d_label_checker_history(){
      var data_arr_a = [];
      var data_arr_b = [];
      $('#tbl_d_label_checker tbody tr').each(function(){
        data_arr_a.push( $(this).find('td:eq(1)').text() );
        data_arr_b.push( $(this).find('td:eq(2)').text() );
      });


     var data = {
      'unique_no_start'             : $('#tbl_d_label_checker tbody').attr('data-unique_no_start'),
      'po_no'                       : $('#tbl_d_label_checker tbody').attr('data-po_no'),
      'd_label_lot_no_arr'          : data_arr_a,
      'c3_label_lot_no_arr'         : data_arr_b,
      '_token'                      : '{{ csrf_token() }}',
      'txt_employee_number_scanner' : $("#txt_employee_number_scanner").val(),
     };
     $.ajax({
      'data'      : data,
      'type'      : 'post',
      'dataType'  : 'json',
      'url'       : 'insert_d_label_checker_history',
      success : function(data){
        if($.trim(data)){
          show_alert(data['icon'],data['title'],data['body'],data['i']);
          if( data['i'] == 2 ){
            return;
          }
          //---
          $('#btn_clear_checker').click();
          dt_d_label_checker_history.ajax.reload();
        }
      }
     });


    }

 //-----------------------
 //-----------------------
 //-----------------------
 var alert_timeout = '';
 function hide_alert(){
   alert_timeout = setTimeout(function()
   {
     $('#mdl_alert').modal('hide');
   }, 2000);
 }
 function show_alert(icon,title,body,i){
   $('#mdl_alert #mdl_alert_title').html(icon+' '+title);
   $('#mdl_alert #mdl_alert_body').html(body);
   $('#mdl_alert').modal('show');

   if(i == 1){//if ok, auto hide modal
     clearTimeout(alert_timeout);
     hide_alert();
   }
 }

  </script>
  @endsection
  @endauth