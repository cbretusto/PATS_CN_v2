// Add AddOQCLotApp
function AddOQCLotApp(){
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
        url: "add_oqc_lot_app",
        method: "post",
        data: $('#formOQCLotApp').serialize(),
        dataType: "json",
        beforeSend: function(){
            $('#id_btn_AddOQCLotApp').prop('disabled', true);
            $("#btnAddOqcLotAppIcon").addClass('fa fa-spinner fa-pulse');
        },
        success: function(JsonObject){

            if(JsonObject['result'] == 1){
            	$("#modalOQCLotApp").modal('hide');
            	$("#formOQCLotApp")[0].reset();

            	dataTableOQCLotApp.draw();
              toastr.success('Lot Application was successfully saved!');
            } else if (JsonObject['result'] == '2'){
                toastr.error('Invalid Employee No. for final visual operator!');                
            } else if (JsonObject['result'] == '3'){
                toastr.error('Max of 3rd submission only!');                
            } 
            // else if (JsonObject['result'] == '4'){
            //     toastr.error('Output Quantity is not equal to Lot Quantity!');                
            // } 
            else{
                toastr.error('Saving lot application failed!');                
            }
            $('#id_btn_AddOQCLotApp').prop('disabled', false);
            $("#btnAddOqcLotAppIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddOqcLotAppIcon").addClass('fa fa-check fa-xs');
        

        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}


function ApprovedProd_OQCLotApp(){
  alert();
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
        url: "update_approved_prod",
        method: "post",
        data: $('#formApprovedByProd').serialize(),
        dataType: "json",
        beforeSend: function(){
        //
        },
        success: function(JsonObject){            
            if(JsonObject['result'] == 1){
              $("#modalApprovedByProd").modal('hide');
              $("#modalOQCLotApp").modal('hide');
              $("#formApprovedByProd")[0].reset();
              
              // $("#formOQCLotApp").submit();

              dataTableOQCLotApp.draw();
              toastr.success('Lot Application was succesfully approved by Prodn Supervisor!');
            }else if (JsonObject['result'] == 2){
                toastr.error('You are not registered as Prodn. Supervisor');                
            } else{
                toastr.error('Failed!');                
            }

        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function ApprovedOQC_OQCLotApp(){
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
        url: "update_approved_oqc",
        method: "post",
        data: $('#formApprovedByOQC').serialize(),
        dataType: "json",
        beforeSend: function(){
        //
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
              $("#modalApprovedByOQC").modal('hide');
              $("#modalOQCLotApp").modal('hide');
              $("#formApprovedByOQC")[0].reset();

              dataTableOQCLotApp.draw();
              toastr.success('Lot Application was succesfully approved by OQC Supervisor!');
            }else if (JsonObject['result'] == 2){
                toastr.error('You are not registered as OQC Supervisor');                
            } else{
                toastr.error('Failed!');                
            }
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}


