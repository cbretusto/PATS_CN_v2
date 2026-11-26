// Add MOD
function AddMOD(){
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
        url: "add_mod",
        method: "post",
        data: $('#formAddMOD').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnAddMODIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnAddMOD").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
            	$("#modalAddMOD").modal('hide');
            	$("#formAddMOD")[0].reset();
              $("#txtAddMODName").removeClass('is-invalid');
              $("#txtAddMODBarcode").removeClass('is-invalid');

            	dataTableMODS.draw();
              toastr.success('Mode of Defect was succesfully saved!');
            }
            else{
                toastr.error('Saving Mode of Defect Failed!');

                if(JsonObject['error']['name'] === undefined){
                    $("#txtAddMODName").removeClass('is-invalid');
                    $("#txtAddMODName").attr('title', '');
                }
                else{
                    $("#txtAddMODName").addClass('is-invalid');
                    $("#txtAddMODName").attr('title', JsonObject['error']['name']);
                }

                if(JsonObject['error']['barcode'] === undefined){
                    $("#txtAddMODBarcode").removeClass('is-invalid');
                    $("#txtAddMODBarcode").attr('title', '');
                }
                else{
                    $("#txtAddMODBarcode").addClass('is-invalid');
                    $("#txtAddMODBarcode").attr('title', JsonObject['error']['barcode']);
                }
            }

            $("#iBtnAddMODIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddMOD").removeAttr('disabled');
            $("#iBtnAddMODIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnAddMODIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddMOD").removeAttr('disabled');
            $("#iBtnAddMODIcon").addClass('fa fa-check');
        }
    });
}

// Edit MOD
function GetMODByIdToEdit(modId){
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
        url: "get_mod_by_id",
        method: "get",
        data: {
            mod_id: modId
        },
        dataType: "json",
        beforeSend: function(){
            $("#txtEditMODName").val("");
            $("#txtEditMODBarcode").val("");
            $("#txtEditMODName").removeClass('is-invalid');
            $("#txtEditMODBarcode").removeClass('is-invalid');
        },
        success: function(JsonObject){
            let result = JsonObject['mod'];
            if(result.length > 0){
                $("#txtEditMODName").val(result[0].name);
                $("#txtEditMODBarcode").val(result[0].barcode);
            }
            else{
                toastr.warning('No Mode of Defect Record Found!');
            }
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function EditMOD(){
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
        url: "edit_mod",
        method: "post",
        data: $('#formEditMOD').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnEditMODIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnEditMOD").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
                $("#modalEditMOD").modal('hide');
                $("#formEditMOD")[0].reset();

                $("#txtEditMODName").removeClass('is-invalid');
                $("#txtEditMODBarcode").removeClass('is-invalid');
                dataTableMODS.draw();
                toastr.success('MOD was succesfully saved!');
            }
            else{
                toastr.error('Updating Mode of Defect Failed!');

                if(JsonObject['error']['name'] === undefined){
                    $("#txtEditMODName").removeClass('is-invalid');
                    $("#txtEditMODName").attr('title', '');
                }
                else{
                    $("#txtEditMODName").addClass('is-invalid');
                    $("#txtEditMODName").attr('title', JsonObject['error']['name']);
                }

                if(JsonObject['error']['barcode'] === undefined){
                    $("#txtEditMODName").removeClass('is-invalid');
                    $("#txtEditMODBarcode").attr('title', '');
                }
                else{
                    $("#txtEditMODName").attr('title', JsonObject['error']['barcode']);
                    $("#txtEditMODBarcode").addClass('is-invalid');
                }
            }

            $("#iBtnEditMODIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnEditMOD").removeAttr('disabled');
            $("#iBtnEditMODIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnEditMODIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnEditMOD").removeAttr('disabled');
            $("#iBtnEditMODIcon").addClass('fa fa-check');
        }
    });
}

// Change MOD Status
function ChangeMODStatus(){
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
        url: "change_mod_stat",
        method: "post",
        data: $('#formChangeMODStat').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnChangeMODStatIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnChangeMODStat").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
                $("#modalChangeMODStat").modal('hide');
                $("#formChangeMODStat")[0].reset();

                dataTableMODS.draw();

                if($("#txtChangeMODStatMODStat").val() == 1){
                    toastr.success('Mode of Defect Activation Success!');
                }
                else{
                    toastr.success('Mode of Defect Deactivation Success!');
                }
            }
            else{
                if($("#txtChangeMODStatMODStat").val() == 1){
                    toastr.error('Mode of Defect Activation Failed!');
                }
                else{
                    toastr.error('Mode of Defect Deactivation Failed!');
                }
            }

            $("#iBtnChangeMODStatIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnChangeMODStat").removeAttr('disabled');
            $("#iBtnChangeMODStatIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnChangeMODStatIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnChangeMODStat").removeAttr('disabled');
            $("#iBtnChangeMODStatIcon").addClass('fa fa-check');
        }
    });
}

function GetCboMOD(cboElement){
    let result = '<option value="" selected> N/A </option>';
    $.ajax({
        url: 'get_mods',
        method: 'get',
        dataType: 'json',
        beforeSend: function(){
            result = '<option value="0" selected disabled> -- Loading -- </option>';
            cboElement.html(result);
        },
        success: function(JsonObject){
            result = '';
            if(JsonObject['mods'].length > 0){
                result = '<option value=""> N/A </option>';
                for(let index = 0; index < JsonObject['mods'].length; index++){
                    result += '<option value="' + JsonObject['mods'][index].id + '" data-code="' + JsonObject['mods'][index].barcode + '">' + JsonObject['mods'][index].name + '</option>';
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