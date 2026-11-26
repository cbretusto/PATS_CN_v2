// Add Material Process
function AddMaterialProcess(){
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
        url: "add_material_process",
        method: "post",
        data: $('#formAddMatProc').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnAddMatProcIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnAddMatProc").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
              // $("#modalAddMatProc").modal('hide');
              // $("#formAddMatProc")[0].reset();
              // $("#selAddMatProcMachine").val('0').trigger('change');
              // $("#selAddMatProcAShiftManpower").val('0').trigger('change');
              // $("#selAddMatProcBShiftManpower").val('0').trigger('change');
              // $("#selAddMatProcSubStationId").val('0').trigger('change');

              // $("#selAddMatProcMatKitItem").val('0').trigger('change');
              // $("#selAddMatProcSakIssuItem").val('0').trigger('change');
              // $("#selAddMatProcEmbossItem").val('0').trigger('change');
              $("#txtAddMatProcStep").focus();
              
              $("#txtAddMatProcDevId").val(selectedDeviceId);
              $("#txtAddMatProcDeviceName").val(selectedDeviceName);

              dataTableMatProcess.draw();
              toastr.success('Material Process was succesfully saved!');
            }
            else if(JsonObject['result'] == 0){

                if(JsonObject['error'] != null){
                  toastr.error('Saving Material Process Failed!');
                  if(JsonObject['error']['step'] === undefined){
                      $("#txtAddMatProcStep").removeClass('is-invalid');
                      $("#txtAddMatProcStep").attr('title', '');
                  }
                  else{
                      $("#txtAddMatProcStep").addClass('is-invalid');
                      $("#txtAddMatProcStep").attr('title', JsonObject['error']['step']);
                  }

                  if(JsonObject['error']['sub_station_id'] === undefined){
                      $("#selAddMatProcSubStationId").removeClass('is-invalid');
                      $("#selAddMatProcSubStationId").attr('title', '');
                  }
                  else{
                      $("#selAddMatProcSubStationId").addClass('is-invalid');
                      $("#selAddMatProcSubStationId").attr('title', JsonObject['error']['sub_station_id']);
                  }
                }
                else{
                  let troubleMachines = '';
                  if(JsonObject['trouble_machines'].length > 0){
                    troubleMachines = JsonObject['trouble_machines'].join();
                    // for(let index = 0; index < JsonObject['trouble_machines'].length; index++){
                    //   troubleMachines += JsonObject['trouble_machines'][index] + '';
                    // }

                    toastr.options = {
                      "closeButton": false,
                      "debug": false,
                      "newestOnTop": true,
                      "progressBar": true,
                      "positionClass": "toast-top-right",
                      "preventDuplicates": false,
                      "onclick": null,
                      "showDuration": "900",
                      "hideDuration": "3000",
                      "timeOut": "10000",
                      "extendedTimeOut": "1000",
                      "showEasing": "swing",
                      "hideEasing": "linear",
                      "showMethod": "fadeIn",
                      "hideMethod": "fadeOut",
                    };
                    toastr.error('Machine/Equipment (' + troubleMachines + ') is for repair/damaged. <br>Kindly coordinate with Eng\'g. Thank you!');

                    // toastr.warning(troubleMachines);
                  }
                }
            }
            else if(JsonObject['result'] == 2){
                toastr.error('Duplicate Material Process!');
                $("#txtAddMatProcStep").addClass('is-invalid');
                $("#selAddMatProcSubStationId").addClass('is-invalid');
            }
            else{
              toastr.error('Saving Material Process Failed!');
            }

            $("#iBtnAddMatProcIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddMatProc").removeAttr('disabled');
            $("#iBtnAddMatProcIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnAddMatProcIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddMatProc").removeAttr('disabled');
            $("#iBtnAddMatProcIcon").addClass('fa fa-check');
        }
    });
}

// Edit Material Process
function EditMaterialProcess(){
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
        url: "edit_material_process",
        method: "post",
        data: $('#formEditMatProc').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnEditMatProcIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnEditMatProc").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
              $("#modalEditMatProc").modal('hide');
              $("#formEditMatProc")[0].reset();

              $("#selEditMatProcMatKitItem").val('0').trigger('change');
              $("#selEditMatProcSakIssuItem").val('0').trigger('change');
              $("#selEditMatProcEmbossItem").val('0').trigger('change');

              dataTableMatProcess.draw();
              toastr.success('Material Process was succesfully saved!');
            }
            else if(JsonObject['result'] == 0){

                if(JsonObject['error'] != null){
                  toastr.error('Saving Material Process Failed!');
                  if(JsonObject['error']['step'] === undefined){
                      $("#txtEditMatProcStep").removeClass('is-invalid');
                      $("#txtEditMatProcStep").attr('title', '');
                  }
                  else{
                      $("#txtEditMatProcStep").addClass('is-invalid');
                      $("#txtEditMatProcStep").attr('title', JsonObject['error']['step']);
                  }

                  if(JsonObject['error']['sub_station_id'] === undefined){
                      $("#selEditMatProcSubStationId").removeClass('is-invalid');
                      $("#selEditMatProcSubStationId").attr('title', '');
                  }
                  else{
                      $("#selEditMatProcSubStationId").addClass('is-invalid');
                      $("#selEditMatProcSubStationId").attr('title', JsonObject['error']['sub_station_id']);
                  }
                }
                else{
                  let troubleMachines = '';
                  if(JsonObject['trouble_machines'].length > 0){
                    troubleMachines = JsonObject['trouble_machines'].join();
                    // for(let index = 0; index < JsonObject['trouble_machines'].length; index++){
                    //   troubleMachines += JsonObject['trouble_machines'][index] + '';
                    // }
                    toastr.options = {
                      "closeButton": false,
                      "debug": false,
                      "newestOnTop": true,
                      "progressBar": true,
                      "positionClass": "toast-top-right",
                      "preventDuplicates": false,
                      "onclick": null,
                      "showDuration": "900",
                      "hideDuration": "3000",
                      "timeOut": "10000",
                      "extendedTimeOut": "1000",
                      "showEasing": "swing",
                      "hideEasing": "linear",
                      "showMethod": "fadeIn",
                      "hideMethod": "fadeOut",
                    };

                    toastr.error('Machine/Equipment (' + troubleMachines + ') is for repair/damaged. <br>Kindly coordinate with Eng\'g. Thank you!');
                    // toastr.warning(troubleMachines);
                  }
                }
            }
            else if(JsonObject['result'] == 2){
                toastr.error('Duplicate Material Process!');
                $("#txtEditMatProcStep").addClass('is-invalid');
                $("#selEditMatProcSubStationId").addClass('is-invalid');
            }

            $("#iBtnEditMatProcIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnEditMatProc").removeAttr('disabled');
            $("#iBtnEditMatProcIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnEditMatProcIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnEditMatProc").removeAttr('disabled');
            $("#iBtnEditMatProcIcon").addClass('fa fa-check');
        }
    });
}

// Change Material Process Status
function ChangeMatProcStat(){
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
        url: "change_material_process_stat",
        method: "post",
        data: $('#formChangeMatProcStat').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnChangeMatProcStatIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnChangeMatProcStat").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
                $("#modalChangeMatProcStat").modal('hide');
                $("#formChangeMatProcStat")[0].reset();

                dataTableMatProcess.draw();

                if($("#txtChangeMatProcStatMatProcStat").val() == 1){
                    toastr.success('Material Process Activation Success!');
                }
                else{
                    toastr.success('Material Process Deactivation Success!');
                }
            }
            else{
                if($("#txtChangeMatProcStatMatProcStat").val() == 1){
                    toastr.error('Material Process Activation Failed!');
                }
                else{
                    toastr.error('Material Process Deactivation Failed!');
                }
            }

            $("#iBtnChangeMatProcStatIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnChangeMatProcStat").removeAttr('disabled');
            $("#iBtnChangeMatProcStatIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnChangeMatProcStatIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnChangeMatProcStat").removeAttr('disabled');
            $("#iBtnChangeMatProcStatIcon").addClass('fa fa-check');
        }
    });
}

// Edit Station
function GetMatProcByIdToEdit(matProcId){
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
        url: "get_mat_proc_by_id",
        method: "get",
        data: {
            material_process_id: matProcId
        },
        dataType: "json",
        beforeSend: function(){
            $("#txtEditMatProcStep").val("");
            $("#txtEditMatProcMaterial").val("");
            $("#selEditMatProcSubStationId").val("").trigger('change');
            $("#selEditMatProcMachine").val("").trigger('change');
            $("#selEditMatProcAShiftManpower").val("").trigger('change');
            $("#selEditMatProcBShiftManpower").val("").trigger('change');
        },
        success: function(JsonObject){
            let result = JsonObject['material_process'];
            if(result.length > 0){
                $("#txtEditMatProcStep").val(result[0].step);
                $("#txtEditMatProcMaterial").val(result[0].material);
                $("#selEditMatProcSubStationId").val(result[0].station_sub_station_id).trigger('change');
                $("#selEditMatProcMachine").val(result[0].machine_id).trigger('change');
                if(result[0].has_emboss == 1){
                  $("#chkEditMatProcHasEmboss").prop('checked', true);
                  if(result[0].require_oqc_before_emboss == 1){
                    $("#chkEditMatProcReqOQCBeforeEmboss").prop('checked', true);
                  }
                  else{
                    $("#chkEditMatProcReqOQCBeforeEmboss").prop('checked', false);
                  }
                  $("#chkEditMatProcReqOQCBeforeEmboss").prop('disabled', false);
                }
                else{
                  $("#chkEditMatProcHasEmboss").prop('checked', false); 
                  $("#chkEditMatProcReqOQCBeforeEmboss").prop('disabled', true);
                  $("#chkEditMatProcReqOQCBeforeEmboss").prop('checked', false);
                }
                
                
                if(result[0].has_printing == 1){
                  $("#selEditPrintLabel").prop('checked', true);
                }
                if(result[0].has_reel_input == 1){
                  $("#selEditInpReelLot").prop('checked', true);
                }
                if(result[0].has_print_input == 1){
                  $("#selEditInpPrintLot").prop('checked', true);
                }
                if(result[0].has_guaranteed == 1){
                  $("#selEditInpGuaranteedLot").prop('checked', true);
                }


                let arrAShiftManpowersId = [];
                let arrBShiftManpowersId = [];
                let arrMachineId = [];
                let arrMaterialKittingData = [];
                let arrSakidashiIssuanceData = [];
                let arrEmbossData = [];

                for(let index = 0; index < result[0].manpowers_details.length; index++){
                  if(result[0].manpowers_details[index].shift == 1){
                    arrAShiftManpowersId.push(result[0].manpowers_details[index].manpower_id);
                  }
                  else{
                    arrBShiftManpowersId.push(result[0].manpowers_details[index].manpower_id); 
                  }
                }

                for(let index = 0; index < result[0].machine_details.length; index++){
                  arrMachineId.push(result[0].machine_details[index].machine_id);
                }

                for(let index = 0; index < result[0].material_details.length; index++){
                  if(result[0].material_details[index].tbl_wbs == 1){
                    arrMaterialKittingData.push(result[0].material_details[index].item + '--' + result[0].material_details[index].item_desc);
                  }
                  else if(result[0].material_details[index].tbl_wbs == 2 && result[0].material_details[index].has_emboss == 0){
                    arrSakidashiIssuanceData.push(result[0].material_details[index].item + '--' + result[0].material_details[index].item_desc); 
                  }
                  else if(result[0].material_details[index].tbl_wbs == 2 && result[0].material_details[index].has_emboss == 1){
                    arrEmbossData.push(result[0].material_details[index].item + '--' + result[0].material_details[index].item_desc);  
                  }
                    // if(result[0].material_details[index].has_emboss == 0){
                    //   $("#selEditMatProcEmbossItem").prop('disabled', false);
                    //   $("#selEditMatProcMatKitItem").prop('disabled', true);
                    //   $("#selEditMatProcSakIssuItem").prop('disabled', true);
                    //   // alert('check');
                    // }
                    // else{
                    //   $("#selEditMatProcEmbossItem").prop('disabled', true);
                    //   $("#selEditMatProcMatKitItem").prop('disabled', false);
                    //   $("#selEditMatProcSakIssuItem").prop('disabled', false);
                    //   // alert('uncheck');
                    // }
                }

                // console.log(arrSakidashiIssuanceData);
                // console.log(arrMaterialKittingData);
                // console.log(arrEmbossData);

                $("#selEditMatProcAShiftManpower").val(arrAShiftManpowersId).trigger('change');
                $("#selEditMatProcBShiftManpower").val(arrBShiftManpowersId).trigger('change');
                $("#selEditMatProcMachine").val(arrMachineId).trigger('change');
                $("#selEditMatProcMatKitItem").val(arrMaterialKittingData).trigger('change');
                $("#selEditMatProcSakIssuItem").val(arrSakidashiIssuanceData).trigger('change');
                $("#selEditMatProcEmbossItem").val(arrEmbossData).trigger('change');

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