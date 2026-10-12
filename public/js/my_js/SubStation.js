// Add SubStation
function AddSubStation(){
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
        url: "add_sub_station",
        method: "post",
        data: $('#formAddSubStation').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnAddSubStationIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnAddSubStation").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
            	$("#modalAddSubStation").modal('hide');
            	$("#formAddSubStation")[0].reset();
              $("#lblAddSubStationQRCodeVal").text('0');
            	dataTableSubStations.draw();
              toastr.success('Process was succesfully saved!');
              GetCboSubStationByStat($(".selSubStation"), 1);
            }
            else{
                toastr.error('Saving Process Failed!');

                if(JsonObject['error']['name'] === undefined){
                    $("#txtAddSubStationName").removeClass('is-invalid');
                    $("#txtAddSubStationName").attr('title', '');
                }
                else{
                    $("#txtAddSubStationName").addClass('is-invalid');
                    $("#txtAddSubStationName").attr('title', JsonObject['error']['name']);
                }

                if(JsonObject['error']['barcode'] === undefined){
                    $("#txtAddSubStationBarcode").removeClass('is-invalid');
                    $("#txtAddSubStationBarcode").attr('title', '');
                }
                else{
                    $("#txtAddSubStationBarcode").addClass('is-invalid');
                    $("#txtAddSubStationBarcode").attr('title', JsonObject['error']['barcode']);
                }
            }

            $("#iBtnAddSubStationIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddSubStation").removeAttr('disabled');
            $("#iBtnAddSubStationIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnAddSubStationIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddSubStation").removeAttr('disabled');
            $("#iBtnAddSubStationIcon").addClass('fa fa-check');
        }
    });
}

// Edit SubStation
function GetSubStationByIdToEdit(subStationId){
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
        url: "get_sub_station_by_id",
        method: "get",
        data: {
            sub_station_id: subStationId
        },
        dataType: "json",
        beforeSend: function(){
            $("#txtEditSubStationName").val("");
            $("#txtEditSubStationBarcode").val("");
        },
        success: function(JsonObject){
            let result = JsonObject['sub_station'];
            if(result.length > 0){
                $("#txtEditSubStationName").val(result[0].name);
                $("#txtEditSubStationBarcode").val(result[0].barcode);
            }
            else{
                toastr.warning('No SubStation Record Found!');
            }
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function EditSubStation(){
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
        url: "edit_sub_station",
        method: "post",
        data: $('#formEditSubStation').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnEditSubStationIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnEditSubStation").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
                $("#modalEditSubStation").modal('hide');
                $("#formEditSubStation")[0].reset();

                dataTableSubStations.draw();
                toastr.success('SubStation was succesfully saved!');
            }
            else{
                toastr.error('Updating SubStation Failed!');

                if(JsonObject['error']['name'] === undefined){
                    $("#txtEditSubStationName").removeClass('is-invalid');
                    $("#txtEditSubStationName").attr('title', '');
                }
                else{
                    $("#txtEditSubStationName").addClass('is-invalid');
                    $("#txtEditSubStationName").attr('title', JsonObject['error']['name']);
                }

                if(JsonObject['error']['barcode'] === undefined){
                    $("#txtEditSubStationBarcode").removeClass('is-invalid');
                    $("#txtEditSubStationBarcode").attr('title', '');
                }
                else{
                    $("#txtEditSubStationBarcode").addClass('is-invalid');
                    $("#txtEditSubStationBarcode").attr('title', JsonObject['error']['barcode']);
                }
            }

            $("#iBtnEditSubStationIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnEditSubStation").removeAttr('disabled');
            $("#iBtnEditSubStationIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnEditSubStationIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnEditSubStation").removeAttr('disabled');
            $("#iBtnEditSubStationIcon").addClass('fa fa-check');
        }
    });
}

// Change SubStation Status
function ChangeSubStationStatus(){
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
        url: "change_sub_station_stat",
        method: "post",
        data: $('#formChangeSubStationStat').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnChangeSubStationStatIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnChangeSubStationStat").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
                $("#modalChangeSubStationStat").modal('hide');
                $("#formChangeSubStationStat")[0].reset();

                dataTableSubStations.draw();

                if($("#txtChangeSubStationStatSubStationStat").val() == 1){
                    toastr.success('SubStation Activation Success!');
                }
                else{
                    toastr.success('SubStation Deactivation Success!');
                }
            }
            else{
                if($("#txtChangeSubStationStatSubStationStat").val() == 1){
                    toastr.error('SubStation Activation Failed!');
                }
                else{
                    toastr.error('SubStation Deactivation Failed!');
                }
            }

            $("#iBtnChangeSubStationStatIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnChangeSubStationStat").removeAttr('disabled');
            $("#iBtnChangeSubStationStatIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnChangeSubStationStatIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnChangeSubStationStat").removeAttr('disabled');
            $("#iBtnChangeSubStationStatIcon").addClass('fa fa-check');
        }
    });
}

// Get Process in combobox
// function GetCboSubStation(cboElement, status){
//     let result = '<option value="0" selected disabled> -- Select Station -- </option>';
//     $.ajax({
//         url: 'get_sub_stations_by_stat',
//         method: 'get',
//         data: {
//           status: status
//         },
//         dataType: 'json',
//         beforeSend: function(){
//             result = '<option value="0" selected disabled> -- Loading -- </option>';
//             cboElement.html(result);
//         },
//         success: function(JsonObject){
//             let stations = JsonObject['sub_stations'];
//             result = '';
//             if(stations.length > 0){
//                 result = '<option value="0" selected disabled> -- Select Station -- </option>';
//                 for(let index = 0; index < stations.length; index++){
//                     result += '<optgroup label="' + stations[index].name + '">'
//                     let subStations = stations[index].sub_stations;
//                     for(let index2 = 0; index2 < subStations.length; index2++){
//                       result += '<option value="' + subStations[index2].id + '">' + subStations[index2].name + '</option>';
//                     }
//                     result += '</optgroup>';
//                 }
//             }
//             else{
//                 result = '<option value="0" selected disabled> -- No record found -- </option>';
//             }

//             cboElement.html(result);
//         },
//         error: function(data, xhr, status){
//             result = '<option value="0" selected disabled> -- Reload Again -- </option>';
//             cboElement.html(result);
//             console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
//         }
//     });
// }

// Get Process by status in combobox
function GetCboSubStationByStat(cboElement, status){
    let result = '<option value="0" selected disabled> -- Select Process -- </option>';
    $.ajax({
        url: 'get_sub_stations_by_stat',
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
            let sub_stations = JsonObject['sub_stations'];
            result = '';
            if(sub_stations.length > 0){
                result = '<option value="0" selected disabled> -- Select Process -- </option>';
                for(let index = 0; index < sub_stations.length; index++){
                    result += '<option value="' + sub_stations[index].id + '">' + sub_stations[index].name + '</option>';
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

// Generate Process QR Code
function GenerateSubStationQRCode(qrcode, action, subStationId){
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
      url: "generate_sub_station_qrcode",
      method: "get",
      data: {
        qrcode: qrcode,
        action: action,
        sub_station_id: subStationId,
      },
      dataType: "json",
      beforeSend: function(){
          
      },
      success: function(JsonObject){
        if(action == 1){
          if(JsonObject['result'] == '1'){
             $("#imgAddSubStationBarcode").attr("src", JsonObject['qrcode']);
             $("#lblAddSubStationQRCodeVal").text(qrcode);
          }
          else if(JsonObject['result'] == '0'){
              toastr.error('Generating QR Code Failed!');
             $("#imgAddSubStationBarcode").attr("src", JsonObject['qrcode']);
             $("#lblAddSubStationQRCodeVal").text('0');
          }
          else if(JsonObject['result'] == '2'){
              toastr.warning('Cannot Generate Duplicate QR Code!');
             $("#imgAddSubStationBarcode").attr("src", JsonObject['qrcode']);
             $("#lblAddSubStationQRCodeVal").text('0');
          }
        }
        else if(action == 2){
          if(JsonObject['result'] == '1'){
             $("#imgEditSubStationBarcode").attr("src", JsonObject['qrcode']);
             $("#lblEditSubStationQRCodeVal").text(qrcode);
          }
          else if(JsonObject['result'] == '0'){
              toastr.error('Generating QR Code Failed!');
          }
          else if(JsonObject['result'] == '2'){
              toastr.warning('Cannot Generate Duplicate QR Code!');
          }
        }
      },
      error: function(data, xhr, status){
          alert('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
      }
  });
}