  function GetPartialLotUser()
  {
     $.ajax({
        url: "get_partial_lot_user",
        method: "get",
        data:
        {

        },
        dataType: "json",
        beforeSend: function(){

        },
        success: function(JsonObject)
        { 
          if(JsonObject['result'] == 1)
          {
/*            document.getElementById("type_batch").innerHTML='Scan Batch/Lot QR Code';
            document.getElementById("type_batch").innerHTML='Scan OQC Supervisor ID';*/

            $('#id_search_OQCSupervisor_partial').removeClass('hidden_scanner_input');
            $('#id_search_reellot_partial').removeClass('hidden_scanner_input');
          }
          else
          {
            
            $('#type_batch').text('Scan Batch/Lot QR Code');
            $('#type_supervisor').text('Scan OQC Supervisor ID');
            $('#id_search_OQCSupervisor_partial').addClass('hidden_scanner_input');
            $('#id_search_reellot_partial').addClass('hidden_scanner_input');
          }
        },
          error: function(data, xhr, status){
              toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
          }
      });
  }


// Add AddOQCVIR
function AddOQCVIR(){
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
        url: "add_oqc_vir",
        method: "post",
        data: $('#formOQCVIR').serialize(),
        dataType: "json",
        beforeSend: function(){
        //
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
            	$("#modalOQCVIR").modal('hide');
            	$("#formOQCVIR")[0].reset();

            	dataTableOQCVIR.draw();
              toastr.success('Visual Inspection Result was succesfully saved!');
            } else if (JsonObject['result'] == '2'){
                toastr.error('Invalid or No registered Employee No. for inspector!');                
            } else if (JsonObject['result'] == '3'){
                toastr.error('Max of 3rd submission only!');                
            } else if (JsonObject['result'] == '4'){
              $("#modalOQCVIR").modal('hide');

              dataTableOQCVIR.draw();
              // toastr.error('Packing Code is ready to Print!');  
              // $("#modalAlertPackingCode").modal('show');   

              // console.log( $("#hidden_OQCLotApp_id").val() );
              get_insp_result_by_id ( $("#hidden_OQCLotApp_id").val() );

              $("#formOQCVIR")[0].reset();

            } else{
                toastr.error('Saving visual inspection result failed!');                
            }
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

    function OQCGeneratePackingCode(device,lotbatchno,sublotno)
    {
      $.ajax({
        url: "oqc_generate_packing_code",
        method: "get",
        data:
        {
          device: device,
          lotbatchno: lotbatchno,
          sublotno: sublotno,
        },
        dataType: "json",
        beforeSend: function(){

        },
        success: function(JsonObject)
        {
          $('#hidden_packing_code').val(JsonObject['return_code']);
        },
          error: function(data, xhr, status){
              toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
          }
      });
    }

    function ViewPackingCodeDetails(lotbatchno)
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
          url: "view_packing_code_details",
          method: "get",
          data:
          {
            lotbatchno: lotbatchno
          },
          dataType: "json",
          beforeSend: function(){

          },
          success: function (JsonObject)
          {
            $('#id_change_lotbatch').val(JsonObject['lotbatch'][0].c_lot_batch_no);
            $('#id_change_reellot').val(JsonObject['oqclotbatch'][0].reel_lot);
            $('#id_change_cur_packing_code').val(JsonObject['lotbatch'][0].packing_code);
          },
          error: function(data, xhr, status){
              toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
          }

        });
    }

    function SubmitChangePackingCode()
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
          url: "submit_change_pack_code",
          method: "post",
          data: $('#formChangePackingCode').serialize(),
          dataType: "json",
          beforeSend: function(){

          },
          success: function (JsonObject)
          {
            switch(JsonObject['result'])
            {
              case 1:
              {
                toastr.success('Packing Code Successfully Changed!');

                $('#formChangePackingCode')[0].reset();
                $('#modalChangePackingCode').modal('hide');
                dataTableOQCVIR.draw();

                break;
              }
              case 2:
              {
                toastr.error('User ID Not OQC Supervisor!');
                break;
              }
              case 3:
              {
                 toastr.error('User ID Not Found!');
                break;
              }
              default:
              {
                toastr.error('Saving New Packing Code Error!');
                break;
              }
            }
          },
          error: function(data, xhr, status){
              toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
          }
        });
    }


    function CheckPartialReelLot(partiallot, arrayLot, po_num)
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

          url: "check_partial_reel_lot",
          method: "get",
          data: 
          {
            partiallot : partiallot,
            po_num: po_num,
          },
          dataType: "json",
          beforeSend: function(){

          },
          success: function (JsonObject)
          {
              if(JsonObject['result'] == 1)
              {
                  if(arrayLot.includes(partiallot))
                  {
                    toastr.error('Lot Already Scanned!');
                  }
                  else
                  {
                     arrayLot.push(partiallot);

                      $('#id_lots_partial').val(arrayLot.toString());

                      toastr.success('Added lot to the List!');
                  }
              }
              else if(JsonObject['result'] == 3)
              {
                toastr.error('Batch/Lot Not Included in PO!');
              }
              else
              {
                toastr.error('Lot Scanned Not Available!');
              }
          },
          error: function(data, xhr, status){
              toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
          }

        });
    }

    function LoadLatestPackingCode(device)
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

          url: "load_latest_packing_code",
          method: "get",
          data:
          {
            device: device
          },
          dataType: "json",
          beforeSend: function(){

          },
          success: function (JsonObject)
          {
              if(JsonObject['result'] == 1)
              {
                $('#id_pack_code_partial').val(JsonObject['return_code']);

                toastr.success('Refreshed Packing Code!');
              }
              else if(JsonObject['result'] == 2)
              {
                let series_name = JsonObject['series_name'];
                let series_msg = series_name + " Packing Code is not yet registered on CNPTS!";

                toastr.error(series_msg);
              }
              else
              {
                toastr.error('Error Loading Packing Code!');
              }
          },
          error: function(data, xhr, status){
              toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
          }

        });
    }

    function AssignPartialPackingCode()
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

          url: "assign_partial_packing_code",
          method: "post",
          data: $('#formPartialLots').serialize(),
          dataType: "json",
          beforeSend: function(){

          },
          success: function (JsonObject)
          {
              switch(JsonObject['result'])
              {
                case 1:
                {
                  $('#formPartialLots')[0].reset();
                  $('#modalPartialLots').modal('hide');
                  dataTableOQCVIR.draw();

                  toastr.success('Assigned Packing Code to Partial Lots');

                  break;
                }
                case 2:
                {
                  toastr.error('User ID Not OQC Supervisor!');
                  break;
                }
                case 3:
                {
                   toastr.error('User ID Not Found!');
                  break;
                }
                default:
                {
                  toastr.error('Error Saving Partial Lot!');
                  break;
                }
            }
          },
          error: function(data, xhr, status){
              toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
          }
        });
    }
  
