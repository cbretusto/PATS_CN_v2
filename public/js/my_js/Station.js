// Add Station
function AddStation(){
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
        url: "add_station",
        method: "post",
        data: $('#formAddStation').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnAddStationIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnAddStation").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
              $("#modalAddStation").modal('hide');
              $("#formAddStation")[0].reset();
              $("#selAddStationType").val('0').trigger('change');

              dataTableStations.draw();
              toastr.success('Station was succesfully saved!');
              GetCboStationByStat($(".selectStation"), 1);
            }
            else{
                toastr.error('Saving Station Failed!');

                if(JsonObject['error']['name'] === undefined){
                    $("#txtAddStationName").removeClass('is-invalid');
                    $("#txtAddStationName").attr('title', '');
                }
                else{
                    $("#txtAddStationName").addClass('is-invalid');
                    $("#txtAddStationName").attr('title', JsonObject['error']['name']);
                }

                if(JsonObject['error']['barcode'] === undefined){
                    $("#txtAddStationBarcode").removeClass('is-invalid');
                    $("#txtAddStationBarcode").attr('title', '');
                }
                else{
                    $("#txtAddStationBarcode").addClass('is-invalid');
                    $("#txtAddStationBarcode").attr('title', JsonObject['error']['barcode']);
                }

                if(JsonObject['error']['station_type'] === undefined){
                    $("#selAddStationType").removeClass('is-invalid');
                    $("#selAddStationType").attr('title', '');
                }
                else{
                    $("#selAddStationType").addClass('is-invalid');
                    $("#selAddStationType").attr('title', JsonObject['error']['station_type']);
                }

            }

            $("#iBtnAddStationIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddStation").removeAttr('disabled');
            $("#iBtnAddStationIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnAddStationIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddStation").removeAttr('disabled');
            $("#iBtnAddStationIcon").addClass('fa fa-check');
        }
    });
}

// Edit Station
function GetStationByIdToEdit(stationId){
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
        url: "get_station_by_id",
        method: "get",
        data: {
            station_id: stationId
        },
        dataType: "json",
        beforeSend: function(){
            $("#txtEditStationName").val("");
            $("#txtEditStationBarcode").val("");
        },
        success: function(JsonObject){
            let result = JsonObject['station'];
            if(result.length > 0){
                $("#txtEditStationName").val(result[0].name);
                $("#txtEditStationBarcode").val(result[0].barcode);
                $("#selEditStationType").val(result[0].station_type).trigger('change');
            }
            else{
                toastr.warning('No Station Record Found!');
            }
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function EditStation(){
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
        url: "edit_station",
        method: "post",
        data: $('#formEditStation').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnEditStationIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnEditStation").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
                $("#uSelectedStationName").text($("#txtEditStationName").val());
                selectedStationName = $("#txtEditStationName").val()
                $("#modalEditStation").modal('hide');
                $("#formEditStation")[0].reset();
                dataTableStations.draw();
                toastr.success('Station was succesfully saved!');
                GetCboStationByStat($(".selectStation"), 1);
            }
            else{
                toastr.error('Updating Station Failed!');

                if(JsonObject['error']['name'] === undefined){
                    $("#txtEditStationName").removeClass('is-invalid');
                    $("#txtEditStationName").attr('title', '');
                }
                else{
                    $("#txtEditStationName").addClass('is-invalid');
                    $("#txtEditStationName").attr('title', JsonObject['error']['name']);
                }

                if(JsonObject['error']['barcode'] === undefined){
                    $("#txtEditStationBarcode").removeClass('is-invalid');
                    $("#txtEditStationBarcode").attr('title', '');
                }
                else{
                    $("#txtEditStationBarcode").addClass('is-invalid');
                    $("#txtEditStationBarcode").attr('title', JsonObject['error']['barcode']);
                }
            }

            $("#iBtnEditStationIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnEditStation").removeAttr('disabled');
            $("#iBtnEditStationIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnEditStationIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnEditStation").removeAttr('disabled');
            $("#iBtnEditStationIcon").addClass('fa fa-check');
        }
    });
}

// Change Station Status
function ChangeStationStatus(){
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
        url: "change_station_stat",
        method: "post",
        data: $('#formChangeStationStat').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnChangeStationStatIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnChangeStationStat").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
                $("#modalChangeStationStat").modal('hide');
                $("#formChangeStationStat")[0].reset();

                dataTableStations.draw();

                if($("#txtChangeStationStatStationStat").val() == 1){
                    toastr.success('Station Activation Success!');
                }
                else{
                    toastr.success('Station Deactivation Success!');
                }
            }
            else{
                if($("#txtChangeStationStatStationStat").val() == 1){
                    toastr.error('Station Activation Failed!');
                }
                else{
                    toastr.error('Station Deactivation Failed!');
                }
            }

            $("#iBtnChangeStationStatIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnChangeStationStat").removeAttr('disabled');
            $("#iBtnChangeStationStatIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnChangeStationStatIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnChangeStationStat").removeAttr('disabled');
            $("#iBtnChangeStationStatIcon").addClass('fa fa-check');
        }
    });
}

function GetCboStationByStat(cboElement, status){
    let result = '<option value="0" selected disabled> -- Select Station -- </option>';
    $.ajax({
        url: 'get_station_by_stat',
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
            if(JsonObject['stations'].length > 0){
                result = '<option value="0" selected disabled> -- Select Station -- </option>';
                for(let index = 0; index < JsonObject['stations'].length; index++){
                    result += '<option value="' + JsonObject['stations'][index].id + '">' + JsonObject['stations'][index].name + '</option>';
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

// Get Station by status in combobox
function GetCbStationByStat(cboElement, status){
    let result = '<option value="0" selected disabled> -- Select Station -- </option>';
    $.ajax({
        url: 'get_stations_by_stat',
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
            let stations = JsonObject['stations'];
            result = '';
            if(stations.length > 0){
                result = '<option value="0" selected disabled> -- Select Station -- </option>';
                for(let index = 0; index < stations.length; index++){
                    result += '<option value="' + stations[index].id + '">' + stations[index].name + '</option>';
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