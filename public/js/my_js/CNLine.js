// Not Included

// Add CNLine
function AddCNLine(){
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
        url: "add_cn_line",
        method: "post",
        data: $('#formAddCNLine').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnAddCNLineIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnAddCNLine").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
            	$("#modalAddCNLine").modal('hide');
            	$("#formAddCNLine")[0].reset();

            	dataTableCNLines.draw();
              toastr.success('CN Line was succesfully saved!');
            }
            else{
                toastr.error('Saving CN Line Failed!');

                if(JsonObject['error']['name'] === undefined){
                    $("#txtAddCNLineName").removeClass('is-invalid');
                    $("#txtAddCNLineName").attr('title', '');
                }
                else{
                    $("#txtAddCNLineName").addClass('is-invalid');
                    $("#txtAddCNLineName").attr('title', JsonObject['error']['name']);
                }
            }

            $("#iBtnAddCNLineIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddCNLine").removeAttr('disabled');
            $("#iBtnAddCNLineIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnAddCNLineIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddCNLine").removeAttr('disabled');
            $("#iBtnAddCNLineIcon").addClass('fa fa-check');
        }
    });
}

// Edit CNLine
function GetCNLineByIdToEdit(cnLineId){
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
        url: "get_cn_line_by_id",
        method: "get",
        data: {
            cn_line_id: cnLineId
        },
        dataType: "json",
        beforeSend: function(){
            $("#txtEditCNLineName").val("");
        },
        success: function(JsonObject){
            let result = JsonObject['cn_line'];
            if(result.length > 0){
                $("#txtEditCNLineName").val(result[0].name);
            }
            else{
                toastr.warning('No CN Line Record Found!');
            }
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function EditCNLine(){
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
        url: "edit_cn_line",
        method: "post",
        data: $('#formEditCNLine').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnEditCNLineIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnEditCNLine").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
                $("#modalEditCNLine").modal('hide');
                $("#formEditCNLine")[0].reset();

                dataTableCNLines.draw();
                toastr.success('CN Line was succesfully saved!');
            }
            else{
                toastr.error('Updating CN Line Failed!');

                if(JsonObject['error']['name'] === undefined){
                    $("#txtEditCNLineName").removeClass('is-invalid');
                    $("#txtEditCNLineName").attr('title', '');
                }
                else{
                    $("#txtEditCNLineName").addClass('is-invalid');
                    $("#txtEditCNLineName").attr('title', JsonObject['error']['name']);
                }
            }

            $("#iBtnEditCNLineIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnEditCNLine").removeAttr('disabled');
            $("#iBtnEditCNLineIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnEditCNLineIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnEditCNLine").removeAttr('disabled');
            $("#iBtnEditCNLineIcon").addClass('fa fa-check');
        }
    });
}

// Change CN Line Status
function ChangeCNLineStatus(){
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
        url: "change_cn_line_stat",
        method: "post",
        data: $('#formChangeCNLineStat').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnChangeCNLineStatIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnChangeCNLineStat").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
                $("#modalChangeCNLineStat").modal('hide');
                $("#formChangeCNLineStat")[0].reset();

                dataTableCNLines.draw();

                if($("#txtChangeCNLineStatCNLineStat").val() == 1){
                    toastr.success('CN Line Activation Success!');
                }
                else{
                    toastr.success('CN Line Deactivation Success!');
                }
            }
            else{
                if($("#txtChangeCNLineStatCNLineStat").val() == 1){
                    toastr.error('CN Line Activation Failed!');
                }
                else{
                    toastr.error('CN Line Deactivation Failed!');
                }
            }

            $("#iBtnChangeCNLineStatIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnChangeCNLineStat").removeAttr('disabled');
            $("#iBtnChangeCNLineStatIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnChangeCNLineStatIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnChangeCNLineStat").removeAttr('disabled');
            $("#iBtnChangeCNLineStatIcon").addClass('fa fa-check');
        }
    });
}