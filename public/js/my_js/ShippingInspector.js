function SubmitShipin(){
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


	$.ajax({
        url: "submit_shipin",
        method: "post",
        data: $('#formShippingInspector').serialize(),
        dataType: "json",
        beforeSend: function(){
            
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
            	$("#modalShippingInspector").modal('hide');
            	$("#formShippingInspector")[0].reset();

            	  dt_shippinginspector.draw();
                toastr.success('Shipping Inspector Form was succesfully saved!');
                
            }
            else if(JsonObject['result'] == 2)
            {
              toastr.error('Scanned ID Does not have OQC Stamp!');
            }

            else{
                toastr.error('Saving Shipping Inspector Form Failed!');
            }
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
           
        }
    });
}

function SubmitSeeder(){
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


  $.ajax({
        url: "submit_seeder",
        method: "post",
        data: $('#formSeeder').serialize(),
        dataType: "json",
        beforeSend: function(){
            
        },
        success: function(JsonObject){
            if(JsonObject['result'] == "1"){
              //$("#formSeeder")[0].reset();

                toastr.success('Packing Seeder Success');
                
            }
            else{
                toastr.error('Packing Seeder Fail');
            }
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
           
        }
    });
}

  function ShipinLinkToPackingCode(pack_code_shipin)
  {
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

     $.ajax({
      url: "link_to_packing_code_shipin",
      method: "get",
      data: {
        pack_code_shipin: pack_code_shipin
      },
      dataType: "json",
      beforeSend: function(){
      },
      success: function(JsonObject)
      {
        if(JsonObject['result'] == 1)
        {
          $('#id_41').val(JsonObject['link_shipin'].radio4_1);
          $('#id_421').val(JsonObject['link_shipin'].radio4_2_1);
          $('#id_422').val(JsonObject['link_shipin'].radio4_2_2);
          $('#id_423').val(JsonObject['link_shipin'].radio4_2_3);
          $('#id_424').val(JsonObject['link_shipin'].radio4_2_4);
          
          $('#id_431').val(JsonObject['link_shipin'].input4_3_1);
          $('#id_432').val(JsonObject['link_shipin'].input4_3_2);
          $('#id_433').val(JsonObject['link_shipin'].input4_3_3);

          $('#id_44').val(JsonObject['link_shipin'].radio4_4);

          $('#id_451').val(JsonObject['link_shipin'].input4_5_1);
          $('#id_452').val(JsonObject['link_shipin'].input4_5_2);
          $('#id_453').val(JsonObject['link_shipin'].input4_5_3);
          $('#id_454').val(JsonObject['link_shipin'].input4_5_4);
          $('#id_455').val(JsonObject['link_shipin'].input4_5_5);
          $('#id_456').val(JsonObject['link_shipin'].input4_5_6);

          $('#id_461').val(JsonObject['link_shipin'].input4_6_1);
          $('#id_462').val(JsonObject['link_shipin'].input4_6_2);
          $('#id_463').val(JsonObject['link_shipin'].input4_6_3);

          $('#id_47').val(JsonObject['link_shipin'].radio4_7);

          $('#id_shipin_judgement').val(JsonObject['link_shipin'].oqc_judgement);

          //DISABLE IF LINKED
          $('#id_41').attr('readonly','readonly');
          $('#id_421').attr('readonly','readonly');
          $('#id_422').attr('readonly','readonly');
          $('#id_423').attr('readonly','readonly');
          $('#id_424').attr('readonly','readonly');
          
          $('#id_431').attr('readonly','readonly');
          $('#id_432').attr('readonly','readonly');
          $('#id_433').attr('readonly','readonly');

          $('#id_44').attr('readonly','readonly');

          $('#id_451').attr('readonly','readonly');
          $('#id_452').attr('readonly','readonly');
          $('#id_453').attr('readonly','readonly');
          $('#id_454').attr('readonly','readonly');
          $('#id_455').attr('readonly','readonly');
          $('#id_456').attr('readonly','readonly');

          $('#id_461').attr('readonly','readonly');
          $('#id_462').attr('readonly','readonly');
          $('#id_463').attr('readonly','readonly');

          $('#id_47').attr('readonly','readonly');

          //UNCLICKABLE
          $('#id_421').css("pointer-events","none");
          $('#id_422').css("pointer-events","none");
          $('#id_423').css("pointer-events","none");
          $('#id_424').css("pointer-events","none");

          $('#id_44').css("pointer-events","none");

          $('#id_451').css("pointer-events","none");
          $('#id_452').css("pointer-events","none");
          $('#id_453').css("pointer-events","none");
          $('#id_454').css("pointer-events","none");
          $('#id_455').css("pointer-events","none");
          $('#id_456').css("pointer-events","none");

          $('#id_47').css("pointer-events","none");

          toastr.success('Packing Code <strong>' + pack_code_shipin + '</strong> Data Linked! Scan your ID to Finish.');
          $('#btn_save_shipin').click();
        }
        else if(JsonObject['result'] == 2)
        {
          //toastr.error('Packing Code Does not have data yet!');
        }
      },
     error: function(data, xhr, status){
          toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
      }
    });
  }

  function GetCboDevicesByStat(cboElement, status){
    let result = '<option value="0" selected disabled> -- Select Series -- </option>';
    $.ajax({
        url: 'search_device_to_export_report',
        method: 'get',
        data: {
          status: status
        },
        dataType: 'json',
        beforeSend: function(){
            result = '<option value="0" selected disabled> -- Loading -- </option>';
            cboElement.html(result);
        },
        success: function(JsonObject){
            result = '';
            if(JsonObject['devices'].length > 0){
                result = '<option value="0" selected disabled> -- Select Series -- </option>';
                for(let index = 0; index < JsonObject['devices'].length; index++){
                    result += '<option value="' + JsonObject['devices'][index].name + '">' + JsonObject['devices'][index].name + '</option>';
                }
            }
            else{
                result = '<option value="0" selected disabled> -- No record found -- </option>';
            }

            cboElement.html(result);
        },
        error: function(data, xhr, status){
            result = '<option value="0" selected disabled> -- Reload Again -- </option>';
            cboElement.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}