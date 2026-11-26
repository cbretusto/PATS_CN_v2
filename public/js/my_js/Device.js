// Add Device
function AddDevice(){
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
        url: "add_device",
        method: "post",
        data: $('#formAddDevice').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnAddDeviceIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnAddDevice").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
            	$("#modalAddDevice").modal('hide');
            	$("#formAddDevice")[0].reset();

            	dataTableDevices.draw();
              toastr.success('Device was succesfully saved!');
            }
            else{
                toastr.error('Saving Device Failed!');

                if(JsonObject['error']['name'] === undefined){
                    $("#txtAddDeviceName").removeClass('is-invalid');
                    $("#txtAddDeviceName").attr('title', '');
                }
                else{
                    $("#txtAddDeviceName").addClass('is-invalid');
                    $("#txtAddDeviceName").attr('title', JsonObject['error']['name']);
                }

                if(JsonObject['error']['barcode'] === undefined){
                    $("#txtAddDeviceBarcode").removeClass('is-invalid');
                    $("#txtAddDeviceBarcode").attr('title', '');
                }
                else{
                    $("#txtAddDeviceBarcode").addClass('is-invalid');
                    $("#txtAddDeviceBarcode").attr('title', JsonObject['error']['barcode']);
                }

                if(JsonObject['error']['mrp_no'] === undefined){
                    $("#txtAddDeviceMRPNo").removeClass('is-invalid');
                    $("#txtAddDeviceMRPNo").attr('title', '');
                }
                else{
                    $("#txtAddDeviceMRPNo").addClass('is-invalid');
                    $("#txtAddDeviceMRPNo").attr('title', JsonObject['error']['mrp_no']);
                }

                if(JsonObject['error']['boxing'] === undefined){
                    $("#txtAddDeviceBoxing").removeClass('is-invalid');
                    $("#txtAddDeviceBoxing").attr('title', '');
                }
                else{
                    $("#txtAddDeviceBoxing").addClass('is-invalid');
                    $("#txtAddDeviceBoxing").attr('title', JsonObject['error']['boxing']);
                }

                if(JsonObject['error']['ship_boxing'] === undefined){
                    $("#txtAddDeviceShipBoxing").removeClass('is-invalid');
                    $("#txtAddDeviceShipBoxing").attr('title', '');
                }
                else{
                    $("#txtAddDeviceShipBoxing").addClass('is-invalid');
                    $("#txtAddDeviceShipBoxing").attr('title', JsonObject['error']['ship_boxing']);
                }

                if(JsonObject['error']['product_type'] === undefined){
                    $("#selEditDeviceProductType").removeClass('is-invalid');
                    $("#selEditDeviceProductType").attr('title', '');
                }
                else{
                    $("#selEditDeviceProductType").addClass('is-invalid');
                    $("#selEditDeviceProductType").attr('title', JsonObject['error']['product_type']);
                }

                if(JsonObject['error']['packaging_type'] === undefined){
                    $("#packType").removeClass('is-invalid');
                    $("#packType").attr('title', '');
                }
                else{
                    $("#packType").addClass('is-invalid');
                    $("#packType").attr('title', JsonObject['error']['product_type']);
                }

                if(JsonObject['error']['ul_requirement'] === undefined){
                    $("#selectULReqId").removeClass('is-invalid');
                    $("#selectULReqId").attr('title', '');
                }
                else{
                    $("#selectULReqId").addClass('is-invalid');
                    $("#selectULReqId").attr('title', JsonObject['error']['product_type']);
                }
            }

            $("#iBtnAddDeviceIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddDevice").removeAttr('disabled');
            $("#iBtnAddDeviceIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnAddDeviceIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddDevice").removeAttr('disabled');
            $("#iBtnAddDeviceIcon").addClass('fa fa-check');
        }
    });
}

// Edit Device
function GetDeviceByIdToEdit(deviceId){
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
        url: "get_device_by_id",
        method: "get",
        data: {
            device_id: deviceId
        },
        dataType: "json",
        beforeSend: function(){
            $("#txtEditDeviceName").val("");
            $("#txtEditDeviceBarcode").val("");
        },
        success: function(JsonObject){
            let result = JsonObject['device'];
            if(result.length > 0){
                $("#txtEditDeviceName").val(result[0].name);
                $("#txtEditDeviceBarcode").val(result[0].barcode);
                $("#txtEditDeviceMRPNo").val(result[0].mrp_no);
                $("#txtEditDeviceBoxing").val(result[0].boxing);
                $("#txtEditDeviceShipBoxing").val(result[0].ship_boxing);
                $("#txtEditDeviceHuaweiPN").val(result[0].huawei_p_n);
                $("#txtEditDeviceLotNoMachineCode").val(result[0].lot_no_machine_code);
                $("#selEditDeviceProductType").val(result[0].product_type);
                

                $("#editPackType").val(result[0].ppts_device_details.type).trigger('change');
                $("#editSelectULReqId").val(result[0].ppts_device_details.ul_requirement).trigger('change');
            }
            else{
                toastr.warning('No Device Record Found!');
            }
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function EditDevice(){
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
        url: "edit_device",
        method: "post",
        data: $('#formEditDevice').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnEditDeviceIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnEditDevice").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
                $("#modalEditDevice").modal('hide');
                $("#formEditDevice")[0].reset();

                dataTableDevices.draw();
                toastr.success('Device was succesfully saved!');
            }
            else{
                toastr.error('Updating Device Failed!');

                if(JsonObject['error']['name'] === undefined){
                    $("#txtEditDeviceName").removeClass('is-invalid');
                    $("#txtEditDeviceName").attr('title', '');
                }
                else{
                    $("#txtEditDeviceName").addClass('is-invalid');
                    $("#txtEditDeviceName").attr('title', JsonObject['error']['name']);
                }

                if(JsonObject['error']['barcode'] === undefined){
                    $("#txtEditDeviceBarcode").removeClass('is-invalid');
                    $("#txtEditDeviceBarcode").attr('title', '');
                }
                else{
                    $("#txtEditDeviceBarcode").addClass('is-invalid');
                    $("#txtEditDeviceBarcode").attr('title', JsonObject['error']['barcode']);
                }

                if(JsonObject['error']['mrp_no'] === undefined){
                    $("#txtEditDeviceMRPNo").removeClass('is-invalid');
                    $("#txtEditDeviceMRPNo").attr('title', '');
                }
                else{
                    $("#txtEditDeviceMRPNo").addClass('is-invalid');
                    $("#txtEditDeviceMRPNo").attr('title', JsonObject['error']['mrp_no']);
                }

                if(JsonObject['error']['boxing'] === undefined){
                    $("#txtEditDeviceBoxing").removeClass('is-invalid');
                    $("#txtEditDeviceBoxing").attr('title', '');
                }
                else{
                    $("#txtEditDeviceBoxing").addClass('is-invalid');
                    $("#txtEditDeviceBoxing").attr('title', JsonObject['error']['boxing']);
                }

                if(JsonObject['error']['ship_boxing'] === undefined){
                    $("#txtEditDeviceShipBoxing").removeClass('is-invalid');
                    $("#txtEditDeviceShipBoxing").attr('title', '');
                }
                else{
                    $("#txtEditDeviceShipBoxing").addClass('is-invalid');
                    $("#txtEditDeviceShipBoxing").attr('title', JsonObject['error']['ship_boxing']);
                }
            }

            $("#iBtnEditDeviceIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnEditDevice").removeAttr('disabled');
            $("#iBtnEditDeviceIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnEditDeviceIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnEditDevice").removeAttr('disabled');
            $("#iBtnEditDeviceIcon").addClass('fa fa-check');
        }
    });
}

// Change Device Status
function ChangeDeviceStatus(){
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
        url: "change_device_stat",
        method: "post",
        data: $('#formChangeDeviceStat').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnChangeDeviceStatIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnChangeDeviceStat").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
                $("#modalChangeDeviceStat").modal('hide');
                $("#formChangeDeviceStat")[0].reset();

                dataTableDevices.draw();

                if($("#txtChangeDeviceStatDeviceStat").val() == 1){
                    toastr.success('Device Activation Success!');
                }
                else{
                    toastr.success('Device Deactivation Success!');
                }
            }
            else{
                if($("#txtChangeDeviceStatDeviceStat").val() == 1){
                    toastr.error('Device Activation Failed!');
                }
                else{
                    toastr.error('Device Deactivation Failed!');
                }
            }

            $("#iBtnChangeDeviceStatIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnChangeDeviceStat").removeAttr('disabled');
            $("#iBtnChangeDeviceStatIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnChangeDeviceStatIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnChangeDeviceStat").removeAttr('disabled');
            $("#iBtnChangeDeviceStatIcon").addClass('fa fa-check');
        }
    });
}