


const validateScannedUser = async (empId, position) => {
    let validated = {
        result : false,
        msg : '',
        user_id : ''
    };

    await $.ajax({
        type: "get",
        url: "get_user_by_emp_id",
        data: {
            emp_id : empId
        },
        dataType: "json",
        beforeSend: function(){
        },
        success: function (response) {
            console.log(position);
            let userDetails = response['userDetails'];
            if(userDetails == null){
                console.error('Employee Not Found');
                validated.result = false;
                validated.msg = 'Employee Not Found';
            }

            if(jQuery.inArray(userDetails.position, position) !== -1){
                console.log('Employee ID valid');
                validated.result = true;
                validated.user_id = userDetails.id;
                
            }
            else{
                console.error('Employee ID not valid');
                validated.result = false;
                validated.msg = 'Employee ID not valid';
            }
        },
        error: function(data, xhr, status){
            toastr.error('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            validated.result = false;
        }
    });
    return validated;
}


const savePreAssemblyDetails = (form, scannedUserID) => {
    let data = $.param({'scanned_user_id': scannedUserID}) + "&" + form.serialize();
    $.ajax({
        type: "post",
        url: "save_pre_assy_details",
        data: data,
        dataType: "json",
        beforeSend: function(){
        },
        success: function (response) {
            if(response['result'] == 1){
                toastr.success(response['msg']);
                $('.divDetailsActions', $('#formPreAssy')).hide();
                $('#btnEditPreAssyDetails', $('#formPreAssy')).prop('disabled', false);
                $('#txtOtherDocs', $('#formPreAssy')).prop('readonly', true);
                $('#txtRevision', $('#formPreAssy')).prop('readonly', true);
                $('#txtRemarks', $('#formPreAssy')).prop('readonly', true);

                $('#txtCreatedAt', $('#formPreAssy')).val(response.preAssemblyDetails.created_at);
                $('#txtPreAssyNumber', $('#formPreAssy')).val(response.preAssemblyDetails.pre_assy_no);
                $('#txtPreAssyId', $('#formPreAssy')).val(response.preAssemblyDetails.id);
                dtPreAssy.draw()
            }
            else if(response['result'] == 2){
                toastr.success(response['msg']);
                $('.divDetailsActions', $('#formPreAssy')).hide();
                $('#btnEditPreAssyDetails', $('#formPreAssy')).prop('disabled', false);
                $('#txtOtherDocs', $('#formPreAssy')).prop('readonly', true);
                $('#txtRevision', $('#formPreAssy')).prop('readonly', true);
                $('#txtRemarks', $('#formPreAssy')).prop('readonly', true);
                dtPreAssy.draw()
            }
            else{
                toastr.error('Saving Failed. Please reload and try again.');
            }
        },
        error: function(data, xhr, status){
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            // console.log(data.responseJSON.errors);
            if(data.status == 422){
                handleValidatorErrors(data.responseJSON.errors, form);
            }
        }
    });
}


const getAssyStationSelect = async (cboElement) => {
    let result = "";
    await $.ajax({
        type: "get",
        url: "get_station_pre_assembly",
        // data: ,
        dataType: "json",
        beforeSend: function(){
            result = '<option value=""> -- Loading -- </option>';
            cboElement.html(result);
        },
        success: function (response) {
            result = "";
            result = `<option value='' selected disabled>--Select--</option>`

            response.forEach(response => {
                result += `<option value='${response.barcode}'>${response.name}</option>`;
            });
            cboElement.html(result);
        },
        error: function(data, xhr, status){
            result = '<option value=""> -- Please Reload! -- </option>';
            cboElement.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

const getDataForAddingProcessPreAssy = () => {
    $.ajax({
        type: "get",
        url: "get_data_for_adding_process_pre_assy",
        data: {
            prod_pres_assy_id    : $('#txtPreAssyId', $('#formPreAssy')).val(),
            device_name          : $('#txtDeviceName', $('#formPreAssy')).val(),
            device_code          : $('#txtDeviceCode', $('#formPreAssy')).val(),
            station_pre_assy_code: $('#txtSelPreAssyCode',$('#formPreAssy')).val()
        },
        dataType: "json",
        success: function (response) {
            let matrixDetails = response['matrixDetails'];
            let steps = response['steps'];
            stepCount = steps.length;
            let prodPreAssyStations = response['prodPreAssyStations'];
            let processOption = "";
        
            /**
             * Generation of step number
            */
            if(prodPreAssyStations.length > 0){
                stepNumber = parseInt(prodPreAssyStations[0]['step_num']) + 1;
                // $('#txt_edit_prod_runcard_station_input').val(prodPreAssyStations[0]['qty_output']);
                // $('#txt_edit_prod_runcard_station_input').prop('readonly', true);
            }
            else{
                stepNumber = steps[0]['step'];
            }
            $('#txtSteps', $('#formPreAssyStation')).val(stepNumber);

            /**
             * Generation of process option
             */
            processOption += "<option value='' selected disabled>--Select Process--</option>";
            for(let x = 0; x < steps.length; x++){
                let concatUsers = steps[x]['a_shift_manpowers_details'].concat(steps[x]['b_shift_manpowers_details']);
                let certifiedUserArray = [];
                for(let y = 0; y < concatUsers.length; y++){
                    certifiedUserArray.push(concatUsers[y]['manpower_info']['employee_id'].toUpperCase())
                }

                let machineArray = [];
                for(let z = 0; z < steps[x]['machine_details'].length; z++){
                    machineArray.push(steps[x]['machine_details'][z]['machine_info']['barcode']);
                }
                processOption += `
                <option value='${steps[x]['station_sub_station']['sub_station']['id']}'
                data-arr-emp-id='${certifiedUserArray}'
                data-arr-machine='${machineArray}'
                data-mat-process-id='${steps[x]['id']}'
                data-mat-process-station-id='${steps[x]['station_sub_station']['station_id']}'
                data-mat-process-step='${steps[x]['step']}'
                data-mat-process-has-guaranteed-lot='${steps[x]['has_guaranteed']}'
                >
                ${steps[x]['station_sub_station']['sub_station']['name']}
                </option>`
            }
            $('#selSubStation', $('#formPreAssyStation')).html(processOption);
        },
        error: function(data, xhr, status){
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

const donePreAssy = (id, scannedId, token) => {
    $.ajax({
        type: "post",
        url: "done_pre_assy",
        data: {
            id             : id,
            scanned_user_id: scannedId,
            _token         : token
        },
        dataType: "json",
        beforeSend: function(){
        },
        success: function (response) {
            if(response.result == true){
                dtPreAssy.draw();
                toastr.success(response.msg);
                $('#modalPreAssy').modal('hide');
            }
        },
        error: function(data, xhr, status){
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

const generateQRCode = (preAssyId, preAssyQty, preAssyNo) => {
    $.ajax({
        type: "get",
        url: "generate_qr_code_pre_assy",
        data: {
            // id         : preAssyId,
            qty        : preAssyQty,
            pre_assy_no: preAssyNo,
            fn         : 'pre_assy'
        },
        dataType: "json",
        beforeSend: function(){
        },
        success: function (response) {
            hiddenQrCodePreAssy = response.qrCode;
            hiddenQrCodePreAssyLabel = response.label;
            hiddenQrCodePreAssyRequest = response.request;

            $('#hiddenPreAssyId').val(preAssyId);
            $("#preAssyQrCode").attr('src', response.qrCode);
            $("#preAssyQrLabel").html(response.label);
            $('#modalPreAssyQRCode').modal('show');
        },
        error: function(data, xhr, status){
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

const changePrintStatus = (form) => {
    $.ajax({
        type: "post",
        url: "change_print_status",
        data: form.serialize(),
        dataType: "json",
        success: function (response) {
            console.log(response);
            dtPreAssy.draw();
            $('#modalPreAssyQRCode').modal('hide');
        },
        error: function(data, xhr, status){
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}