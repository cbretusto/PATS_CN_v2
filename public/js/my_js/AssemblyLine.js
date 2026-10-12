// Add AssemblyLine
function AddAssemblyLine(){
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
        url: "add_assembly_line",
        method: "post",
        data: $('#formAddAssemblyLine').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnAddAssemblyLineIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnAddAssemblyLine").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
            	$("#modalAddAssemblyLine").modal('hide');
            	$("#formAddAssemblyLine")[0].reset();

            	dataTableAssemblyLines.draw();
              toastr.success('Assembly Line was succesfully saved!');
            }
            else{
                toastr.error('Saving Assembly Line Failed!');

                if(JsonObject['error']['name'] === undefined){
                    $("#txtAddAssemblyLineName").removeClass('is-invalid');
                    $("#txtAddAssemblyLineName").attr('title', '');
                }
                else{
                    $("#txtAddAssemblyLineName").addClass('is-invalid');
                    $("#txtAddAssemblyLineName").attr('title', JsonObject['error']['name']);
                }
            }

            $("#iBtnAddAssemblyLineIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddAssemblyLine").removeAttr('disabled');
            $("#iBtnAddAssemblyLineIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnAddAssemblyLineIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddAssemblyLine").removeAttr('disabled');
            $("#iBtnAddAssemblyLineIcon").addClass('fa fa-check');
        }
    });
}

// Edit AssemblyLine
function GetAssemblyLineByIdToEdit(assemblyLineId){
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
        url: "get_assembly_line_by_id",
        method: "get",
        data: {
            assembly_line_id: assemblyLineId
        },
        dataType: "json",
        beforeSend: function(){
            $("#txtEditAssemblyLineName").val("");
        },
        success: function(JsonObject){
            let result = JsonObject['assembly_line'];
            if(result.length > 0){
                $("#txtEditAssemblyLineName").val(result[0].name);
            }
            else{
                toastr.warning('No Assembly Line Record Found!');
            }
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function EditAssemblyLine(){
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
        url: "edit_assembly_line",
        method: "post",
        data: $('#formEditAssemblyLine').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnEditAssemblyLineIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnEditAssemblyLine").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
                $("#modalEditAssemblyLine").modal('hide');
                $("#formEditAssemblyLine")[0].reset();

                dataTableAssemblyLines.draw();
                toastr.success('Assembly Line was succesfully saved!');
            }
            else{
                toastr.error('Updating Assembly Line Failed!');

                if(JsonObject['error']['name'] === undefined){
                    $("#txtEditAssemblyLineName").removeClass('is-invalid');
                    $("#txtEditAssemblyLineName").attr('title', '');
                }
                else{
                    $("#txtEditAssemblyLineName").addClass('is-invalid');
                    $("#txtEditAssemblyLineName").attr('title', JsonObject['error']['name']);
                }
            }

            $("#iBtnEditAssemblyLineIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnEditAssemblyLine").removeAttr('disabled');
            $("#iBtnEditAssemblyLineIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnEditAssemblyLineIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnEditAssemblyLine").removeAttr('disabled');
            $("#iBtnEditAssemblyLineIcon").addClass('fa fa-check');
        }
    });
}

// Change Assembly Line Status
function ChangeAssemblyLineStatus(){
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
        url: "change_assembly_line_stat",
        method: "post",
        data: $('#formChangeAssemblyLineStat').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnChangeAssemblyLineStatIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnChangeAssemblyLineStat").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
                $("#modalChangeAssemblyLineStat").modal('hide');
                $("#formChangeAssemblyLineStat")[0].reset();

                dataTableAssemblyLines.draw();

                if($("#txtChangeAssemblyLineStatAssemblyLineStat").val() == 1){
                    toastr.success('Assembly Line Activation Success!');
                }
                else{
                    toastr.success('Assembly Line Deactivation Success!');
                }
            }
            else{
                if($("#txtChangeAssemblyLineStatAssemblyLineStat").val() == 1){
                    toastr.error('Assembly Line Activation Failed!');
                }
                else{
                    toastr.error('Assembly Line Deactivation Failed!');
                }
            }

            $("#iBtnChangeAssemblyLineStatIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnChangeAssemblyLineStat").removeAttr('disabled');
            $("#iBtnChangeAssemblyLineStatIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnChangeAssemblyLineStatIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnChangeAssemblyLineStat").removeAttr('disabled');
            $("#iBtnChangeAssemblyLineStatIcon").addClass('fa fa-check');
        }
    });
}

function GetAssemblyLines(cboElement){
    let result = '<option value="0" selected disabled> --- </option>';
    $.ajax({
        url: 'get_assembly_lines',
        method: 'get',
        dataType: 'json',
        beforeSend: function(){
            result = '<option value="0" selected disabled> -- Loading -- </option>';
            cboElement.html(result);
        },
        success: function(JsonObject){
            result = '';
            if(JsonObject['assembly_lines'].length > 0){
                result = '<option value="0" selected disabled> --- </option>';
                for(let index = 0; index < JsonObject['assembly_lines'].length; index++){
                    result += '<option value="' + JsonObject['assembly_lines'][index].id + '">' + JsonObject['assembly_lines'][index].name + '</option>';
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