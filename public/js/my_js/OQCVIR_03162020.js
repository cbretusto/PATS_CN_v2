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
            } else{
                toastr.error('Saving visual inspection result failed!');                
            }
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

