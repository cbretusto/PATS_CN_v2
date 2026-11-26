// Add Machine
function AddMachine(){
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
        url: "add_machine",
        method: "post",
        data: $('#formAddMachine').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnAddMachineIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnAddMachine").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
            	$("#modalAddMachine").modal('hide');
            	$("#formAddMachine")[0].reset();

            	dataTableMachines.draw();
              toastr.success('Machine was succesfully saved!');
            }
            else{
                toastr.error('Saving Machine Failed!');

                if(JsonObject['error']['name'] === undefined){
                    $("#txtAddMachineName").removeClass('is-invalid');
                    $("#txtAddMachineName").attr('title', '');
                }
                else{
                    $("#txtAddMachineName").addClass('is-invalid');
                    $("#txtAddMachineName").attr('title', JsonObject['error']['name']);
                }

                if(JsonObject['error']['barcode'] === undefined){
                    $("#txtAddMachineBarcode").removeClass('is-invalid');
                    $("#txtAddMachineBarcode").attr('title', '');
                }
                else{
                    $("#txtAddMachineBarcode").addClass('is-invalid');
                    $("#txtAddMachineBarcode").attr('title', JsonObject['error']['barcode']);
                }
            }

            $("#iBtnAddMachineIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddMachine").removeAttr('disabled');
            $("#iBtnAddMachineIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnAddMachineIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddMachine").removeAttr('disabled');
            $("#iBtnAddMachineIcon").addClass('fa fa-check');
        }
    });
}

// Edit Machine
function GetMachineByIdToEdit(machineId){
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
        url: "get_machine_by_id",
        method: "get",
        data: {
            machine_id: machineId
        },
        dataType: "json",
        beforeSend: function(){
            $("#txtEditMachineName").val("");
            $("#txtEditMachineBarcode").val("");
        },
        success: function(JsonObject){
            let result = JsonObject['machine'];
            if(result.length > 0){
                $("#txtEditMachineName").val(result[0].name);
                $("#txtEditMachineBarcode").val(result[0].barcode);
            }
            else{
                toastr.warning('No Machine Record Found!');
            }
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function EditMachine(){
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
        url: "edit_machine",
        method: "post",
        data: $('#formEditMachine').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnEditMachineIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnEditMachine").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
                $("#modalEditMachine").modal('hide');
                $("#formEditMachine")[0].reset();

                dataTableMachines.draw();
                toastr.success('Machine was succesfully saved!');
            }
            else{
                toastr.error('Updating Machine Failed!');

                if(JsonObject['error']['name'] === undefined){
                    $("#txtEditMachineName").removeClass('is-invalid');
                    $("#txtEditMachineName").attr('title', '');
                }
                else{
                    $("#txtEditMachineName").addClass('is-invalid');
                    $("#txtEditMachineName").attr('title', JsonObject['error']['name']);
                }

                if(JsonObject['error']['barcode'] === undefined){
                    $("#txtEditMachineBarcode").removeClass('is-invalid');
                    $("#txtEditMachineBarcode").attr('title', '');
                }
                else{
                    $("#txtEditMachineBarcode").addClass('is-invalid');
                    $("#txtEditMachineBarcode").attr('title', JsonObject['error']['barcode']);
                }
            }

            $("#iBtnEditMachineIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnEditMachine").removeAttr('disabled');
            $("#iBtnEditMachineIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnEditMachineIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnEditMachine").removeAttr('disabled');
            $("#iBtnEditMachineIcon").addClass('fa fa-check');
        }
    });
}

// Change Machine Status
function ChangeMachineStatus(){
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
        url: "change_machine_stat",
        method: "post",
        data: $('#formChangeMachineStat').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnChangeMachineStatIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnChangeMachineStat").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
                $("#modalChangeMachineStat").modal('hide');
                $("#formChangeMachineStat")[0].reset();

                dataTableMachines.draw();

                if($("#txtChangeMachineStatMachineStat").val() == 1){
                    toastr.success('Machine Activation Success!');
                }
                else{
                    toastr.success('Machine Deactivation Success!');
                }
            }
            else{
                if($("#txtChangeMachineStatMachineStat").val() == 1){
                    toastr.error('Machine Activation Failed!');
                }
                else{
                    toastr.error('Machine Deactivation Failed!');
                }
            }

            $("#iBtnChangeMachineStatIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnChangeMachineStat").removeAttr('disabled');
            $("#iBtnChangeMachineStatIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnChangeMachineStatIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnChangeMachineStat").removeAttr('disabled');
            $("#iBtnChangeMachineStatIcon").addClass('fa fa-check');
        }
    });
}

function PrintBatchMachine(selectedMachines){
  // console.log(selectedMachines);
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
        url: "get_machine_by_batch",
        method: "get",
        data: {
          machine_id: selectedMachines
        },
        dataType: "json",
        beforeSend: function(){
            // $("#iBtnEditUserIcon").addClass('fa fa-spinner fa-pulse');
            // $("#btnEditUser").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['machines'].length > 0){

                popup = window.open();
                  let content = '';
                  content += '<html>';
                  content += '<head>';
                    content += '<title></title>';
                    content += '<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">';
                    content += '<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>';
                    content += '<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>';
                    content += '<style type="text/css">';
                      content += '.divBorder{';
                        content += 'border: 2px solid black;';
                              content += 'min-width: 225px;';
                              content += 'margin-top: 10px;';
                      content += '}';
                    content += '</style>';
                  content += '</head>';
                  content += '<body>';
                    content += '<div class="container-fluid">';
                      content += '<div class="row">';

                        for(let index = 1; index <= JsonObject['machines'].length; index++) {
                          content += '<div class="col-sm-3" style="margin-top: 17px;">';
                            content += '<div class="divBorder">';
                              content += '<center>';
                                content += '<table>';
                                  content += '<tr>';
                                    content += '<td>';
                                      content += '<center>';
                                        content += '<img src="' + JsonObject['qrcode'][index - 1] + '" style="max-width: 120px;">';
                                    // content += '</td>';
                                    // content += '<td>';
                                      content += '<br>';
                                      content += '<label style="text-align: left; font-weight: bold; font-family: Arial; font-size: 18px;">' + JsonObject['machines'][index - 1].barcode + '</label>';
                                      // content += '<label style="text-align: left; font-family: Arial Narrow; font-size: 18px;">' + JsonObject['machines'][index - 1].name + '</label>';
                                      content += '</center>';
                                    content += '</td>';
                                  content += '</tr>';
                                content += '</table>';
                              content += '</center>';
                            content += '</div>';
                          content += '</div>';

                          // if(index % 3 == 0){
                          //   content += '<div class="col-sm-3">';
                          //   content += '</div>';
                          // }
                        }

                      content += '</div>';
                    content += '</div>';
                  content += '</body>';
                  content += '</html>';
                  popup.document.write(content);
                  // popup.focus(); //required for IE
                  // popup.print();
                  // popup.close();

                  // popup = window.open();
                  // let content = '';
                  // content += '<html>';
                  // content += '<head>';
                  //   content += '<title></title>';
                  //   content += '<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">';
                  //   content += '<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>';
                  //   content += '<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>';
                  //   content += '<style type="text/css">';
                  //     content += '.divBorder{';
                  //       content += 'border: 2px solid black;';
                  //             content += 'min-width: 225px;';
                  //             content += 'margin-top: 10px;';
                  //     content += '}';
                  //   content += '</style>';
                  // content += '</head>';
                  // content += '<body>';
                  //   content += '<div class="container-fluid">';
                  //     content += '<div class="row">';

                  //       for(let index = 1; index <= JsonObject['machines'].length; index++) {
                  //         content += '<div class="col-sm-4">';
                  //           content += '<div class="divBorder">';
                  //             // content += '<center>';
                  //               content += '<table>';
                  //                 content += '<tr>';
                  //                   content += '<td>';
                  //                     // content += '<center>';
                  //                       content += '<img src="' + JsonObject['qrcode'][index - 1] + '" style="max-width: 120px;">';
                  //                     // content += '</center>';
                  //                   content += '</td>';
                  //                   content += '<td>';
                  //                     content += '<label style="text-align: left; font-weight: bold; font-family: Arial; font-size: 18px;">' + JsonObject['machines'][index - 1].barcode + '</label>';
                  //                     content += '<br>';
                  //                     content += '<label style="text-align: left; font-family: Arial Narrow; font-size: 18px;">' + JsonObject['machines'][index - 1].name + '</label>';
                  //                   content += '</td>';
                  //                 content += '</tr>';
                  //               content += '</table>';
                  //             // content += '</center>';
                  //           content += '</div>';
                  //         content += '</div>';

                  //         // if(index % 3 == 0){
                  //         //   content += '<div class="col-sm-3">';
                  //         //   content += '</div>';
                  //         // }
                  //       }

                  //     content += '</div>';
                  //   content += '</div>';
                  // content += '</body>';
                  // content += '</html>';
                  // popup.document.write(content);
                  // // popup.focus(); //required for IE
                  // // popup.print();
                  // // popup.close();
            }
            else{
                // toastr.error('Failed!');
            }

            // $("#iBtnEditUserIcon").removeClass('fa fa-spinner fa-pulse');
            // $("#btnEditUser").removeAttr('disabled');
            // $("#iBtnEditUserIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            // $("#iBtnEditUserIcon").removeClass('fa fa-spinner fa-pulse');
            // $("#btnEditUser").removeAttr('disabled');
            // $("#iBtnEditUserIcon").addClass('fa fa-check');
        }
    });
}

function GetCboMachine(cboElement){
    let result = '<option value="" selected> N/A </option>';
    $.ajax({
        url: 'get_machines',
        method: 'get',
        dataType: 'json',
        beforeSend: function(){
            result = '<option value="0" selected disabled> -- Loading -- </option>';
            cboElement.html(result);
        },
        success: function(JsonObject){
            result = '';
            let machine_name = '';
            let disabled = '';
            let eedmsStatus;
            let style = 'color: red;';
            if(JsonObject['machines'].length > 0){
                result = '<option value=""> N/A </option>';
                for(let index = 0; index < JsonObject['machines'].length; index++){
                  let machine_code_number = JsonObject['machines'][index].barcode;
                  machine_name = JsonObject['machines'][index].name;
                  if(JsonObject['machines'][index]['eedms_machine_info'] != null){
                    machine_name = JsonObject['machines'][index]['eedms_machine_info']['machine_name'];
                    machine_code_number = JsonObject['machines'][index]['eedms_machine_info']['machine_code_number'];
                    if(JsonObject['machines'][index]['eedms_machine_info']['machine_status'] == 'Available'){
                      eedmsStatus = '';
                      disabled = '';
                    }
                    else{
                      eedmsStatus = ' (' + JsonObject['machines'][index]['eedms_machine_info']['machine_status'] + ') ';
                      disabled = 'disabled="disabled"';
                    }
                  }
                  else{
                    machine_name = JsonObject['machines'][index].name;
                    disabled = 'disabled="disabled"';
                    eedmsStatus = ' (Not Available) ';
                  }
                    // result += '<option value="' + JsonObject['machines'][index].id + '" data-code="' + JsonObject['machines'][index].barcode + '">' + JsonObject['machines'][index].name + '</option>';
                  result += '<option value="' + JsonObject['machines'][index].id + '" data-code="' + machine_code_number + '" ' + disabled + '><i style="color: red;">' + machine_name + eedmsStatus + ' (' + machine_code_number + ')</i></option>';
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