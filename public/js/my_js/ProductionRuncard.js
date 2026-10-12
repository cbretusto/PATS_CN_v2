function GetProductionRuncards(cboElement, status){
    let result = '<option value="0" selected> --- </option>';
    $.ajax({
        url: 'get_prod_runcards',
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
            if(JsonObject['prod_runcards'].length > 0){
                result = '<option value="0" selected> --- </option>';
                for(let index = 0; index < JsonObject['prod_runcards'].length; index++){
                    result += '<option value="' + JsonObject['prod_runcards'][index].id + '">' + JsonObject['prod_runcards'][index].runcard_no + '</option>';
                }
            }
            else{
                result = '<option value="0" selected> --- </option>';
            }

            cboElement.html(result);
        },
        error: function(data, xhr, status){
            result = '<option value="0" selected> --- </option>';
            cboElement.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function GetWHMatIssuIdToPrint(matWHMatIssuId, qrCodeType){
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
        url: "fn_get_warehouse_view_batches_by_id",
        method: "get",
        data: {
            material_issuance_id: matWHMatIssuId,
            qr_code_type: qrCodeType,
        },
        dataType: "json",
        beforeSend: function(){
                $("#lblGenWHMatIssuPoNo").text('...');
                $("#lblGenWHMatIssuPoDevName").text('...');
                $("#lblGenWHMatIssuPoKitNo").text('...');
                $("#lblGenWHMatIssuPoKitQty").text('...');
                $("#lblGenWHMatIssuTransSlip").text('...');
        },
        success: function(JsonObject){
            let result = JsonObject['tbl_wbs_material_kitting'];
            let poQRCode = JsonObject['po_qrcode'];
            let whsSlipQRcode = JsonObject['whs_slip_qrcode'];

            if(result.length > 0){
                $("#modalGenWHMatIssuIdToPrint").modal('show');
                $("#imgGenWHMatIssuIdPoNoBarcode").attr('src', poQRCode);
                $("#imgGenWHMatIssuIdBarcode").attr('src', whsSlipQRcode);
                $("#lblGenWHMatIssuPoNo").text(result[0].po_no);
                $("#lblGenWHMatIssuPoDevName").text(result[0].device_name);
                $("#lblGenWHMatIssuPoKitNo").text(result[0].kit_no);
                $("#lblGenWHMatIssuPoKitQty").text(result[0].kit_qty);
                $("#lblGenWHMatIssuTransSlip").text(result[0].issuance_no);

                imgResultMatIssuePoNoQrCode = poQRCode;
                imgResultMatIssueQrCode = whsSlipQRcode;
                lblGenWHMatIssuPoNo = result[0].po_no;
                lblGenWHMatIssuPoDevName = result[0].device_name;
                lblGenWHMatIssuPoKitNo = result[0].kit_no;
                lblGenWHMatIssuPoKitQty = result[0].kit_qty;
                lblGenWHMatIssuTransSlip = result[0].issuance_no;
            }
            else{
                toastr.error('QR Code Generation Failed!');
            }
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

// function GetWHSakIssuIdToPrint(matWHSakIssuId){
//     toastr.options = {
//       "closeButton": false,
//       "debug": false,
//       "newestOnTop": true,
//       "progressBar": true,
//       "positionClass": "toast-top-right",
//       "preventDuplicates": false,
//       "onclick": null,
//       "showDuration": "300",
//       "hideDuration": "3000",
//       "timeOut": "3000",
//       "extendedTimeOut": "3000",
//       "showEasing": "swing",
//       "hideEasing": "linear",
//       "showMethod": "fadeIn",
//       "hideMethod": "fadeOut",
//     };
//     $.ajax({
//         url: "get_warehouse_sakidashi_view_batches_by_id",
//         method: "get",
//         data: {
//             sakidashi_issuance_id: matWHSakIssuId
//         },
//         dataType: "json",
//         beforeSend: function(){
//                 $("#lblGenWHSakIssuPoNo").text('...');
//                 $("#lblGenWHSakIssuTransSlip").text('...');
//         },
//         success: function(JsonObject){
//             let result = JsonObject['tbl_sakidashi_kitting'];
//             let poQRCode = JsonObject['po_qrcode'];
//             let ctrlNoQRcode = JsonObject['ctrl_no_qrcode'];

//             if(result.length > 0){
//                 $("#modalGenWHSakIssuIdToPrint").modal('show');
//                 $("#imgGenWHSakIssuIdPoNoBarcode").attr('src', poQRCode);
//                 $("#imgGenWHSakIssuIdBarcode").attr('src', ctrlNoQRcode);
//                 $("#lblGenWHSakIssuPoNo").text(result[0].po_no);
//                 $("#lblGenWHSakIssuTransSlip").text(result[0].issuance_no);

//                 imgResultSakIssuePoNoQrCode = poQRCode;
//                 imgResultSakIssueQrCode = ctrlNoQRcode;
//                 lblGenWHSakIssuPoNo = result[0].po_no;
//                 lblGenWHSakIssuCtrlNo = result[0].issuance_no;
//             }
//             else{
//                 toastr.error('QR Code Generation Failed!');
//             }
//         },
//         error: function(data, xhr, status){
//             toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
//         }
//     });
// }
function GetWHSakIssuIdToPrint(matWHSakIssuId){
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
        url: "get_warehouse_sakidashi_view_batches_by_id",
        method: "get",
        data: {
            sakidashi_issuance_id: matWHSakIssuId
        },
        dataType: "json",
        beforeSend: function(){
                $("#lblGenWHSakIssuPoNo").text('...');
                $("#lblGenWHSakIssuTransSlip").text('...');
        },
        success: function(JsonObject){
            let result = JsonObject['tbl_sakidashi_kitting'];
            let poQRCode = JsonObject['po_qrcode'];
            let issuanceQr = JsonObject['issuance_no'];
            let lotNoQr = JsonObject['lot_no'];

            console.log(JsonObject);
            if(result.length > 0){
                $("#modalGenWHSakIssuIdToPrint").modal('show');
                // $("#imgGenWHSakIssuIdPoNoBarcode").attr('src', poQRCode);
                // $("#imgGenWHSakIssuIdBarcode").attr('src', ctrlNoQRcode);
                // $("#lblGenWHSakIssuPoNo").text(result[0].po_no);
                // $("#lblGenWHSakIssuTransSlip").text(result[0].issuance_no);

                // $('#imgGenWHSakIssuLotNo').attr('src', );
                // $('#lblGenWHSakIssuLotNo').text();

                $('#imgGenWHSakIssuIdPoNo').attr('src', poQRCode)
                $('#imgGenWHSakIssuLotNo').attr('src', lotNoQr)
                $('#imgGenWHSakIssuIdTransferslip').attr('src', issuanceQr)

                let sakiLotAndQty = result[0].tbl_wbs_sakidashi_issuance_item.lot_no + ' \n Qty: ' + result[0].tbl_wbs_sakidashi_issuance_item.issued_qty;
                $('#lblGenWHSakIssuPoNo').text(result[0].po_no)
                // $('#lblGenWHSakIssuLotNo').text(result[0].tbl_wbs_sakidashi_issuance_item.lot_no)
                $('#lblGenWHSakIssuLotNo').text(sakiLotAndQty)
                $('#lblGenWHSakIssuTransSlip').text(result[0].issuance_no)

                imgResultSakIssuePoNoQrCode = poQRCode;
                imgResultSakIssueQrCode = issuanceQr;
                imgResultSakLotQrCode = lotNoQr
                lblGenWHSakIssuPoNo = result[0].po_no;
                lblGenWHSakIssuCtrlNo = result[0].issuance_no;
                // lblGenWHSakLot = result[0].tbl_wbs_sakidashi_issuance_item.lot_no;
                lblGenWHSakLot = sakiLotAndQty;
                itemDescription = result[0].tbl_wbs_sakidashi_issuance_item.item_desc;
            }
            else{
                toastr.error('QR Code Generation Failed!');
            }
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function getDataForAddingProcess(runcardId, devName, devCode, status = 0){
    $.ajax({
        type: "get",
        url: "get_data_for_adding_process",
        data: {
            "runcard_id" : runcardId,
            "device_name": devName,
            "device_code": devCode
            
        },
        dataType: "json",
        beforeSend: function(){
            // $('#btnAddProcess').prop('disabled', true);
        },
        success: function (response) {
            let matrixData = response['matrixDetails'];
            let prodRuncardStationData = response['runcardStationDetails'];
            let steps = response['steps'];
            let stepNumber = 0;
            let processOption = "";
            processOption += `<option value='' selected disabled>- Select Process -</option>`
            for(let x = 0; x < matrixData['material_process'].length; x++){
                let concatUsers = matrixData['material_process'][x]['a_shift_manpowers_details'].concat(matrixData['material_process'][x]['b_shift_manpowers_details']);
                let certifiedUserArray = [];
                for(let y = 0; y < concatUsers.length; y++){
                    console.log(concatUsers, 'test', matrixData['material_process'][x]);
                    certifiedUserArray.push(concatUsers[y]['manpower_info']['employee_id'].toUpperCase())
                }

                let machineArray = [];
                for(let z = 0; z < matrixData['material_process'][x]['machine_details'].length; z++){
                    machineArray.push(matrixData['material_process'][x]['machine_details'][z]['machine_info']['id']);
                }
                processOption += `
                <option value='${matrixData['material_process'][x]['station_sub_station']['sub_station']['id']}'
                data-arr-emp-id='${certifiedUserArray}'
                data-arr-machine='${machineArray}'
                data-mat-process-id='${matrixData['material_process'][x]['id']}'
                data-mat-process-station-id='${matrixData['material_process'][x]['station_sub_station']['station_id']}'
                data-mat-process-step='${matrixData['material_process'][x]['step']}'
                data-mat-process-has-print-input='${matrixData['material_process'][x]['has_print_input']}'
                data-mat-process-has-reel-input='${matrixData['material_process'][x]['has_reel_input']}'
                data-mat-process-has-guaranteed-lot='${matrixData['material_process'][x]['has_guaranteed']}'
                >
                ${matrixData['material_process'][x]['station_sub_station']['sub_station']['name']}
                </option>`
            }
            $('.selSubStation').html(processOption);

            // To Check status parameter if 1, meaning editing data will be inputted on text and select.
            if(status == 1){
                return;
            }
            if(prodRuncardStationData.length > 0){
                stepNumber = parseInt(prodRuncardStationData[0]['step_num']) + 1;
                $('#txt_edit_prod_runcard_station_input').val(prodRuncardStationData[0]['qty_output']);
                $('#txt_edit_prod_runcard_station_input').prop('readonly', true);
            }
            else{
                stepNumber = steps[0]['step'];
            }

            console.log(stepNumber);

            //* Assigning of Values
            $('#process_flow_step_number').val(stepNumber);
            $('#btn_save_prod_runcard_station_stations').prop('disabled', false);
            // $('#btnAddProcess').prop('disabled', false);

        },
        error: function(data, xhr, status){
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function GetProdnRuncardIdToEdit(prodnRuncardID){
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
        url: "get_prodn_runcard_by_id",
        method: "get",
        data: {
            prodnRuncardID: prodnRuncardID
        },
        dataType: "json",
        beforeSend: function(){
        },
        success: function(response){
            let prodnRuncard = response['prodnRuncard'];

            if(prodnRuncard.length > 0){
                if(prodnRuncard[0].has_guaranteed_lot == 1){
                    $('#idEditGuaranteedLotWith').prop('checked', true)
                    $('#idEditGuaranteedLotWithout').prop('checked', false)
                    $('.glPDN').prop('readonly', false)
                }else{
                    $('#idEditGuaranteedLotWith').prop('checked', false)
                    $('#idEditGuaranteedLotWithout').prop('checked', true)
                    $('.glPDN').prop('readonly', true)
                }

                $('#idEditProblem').val(prodnRuncard[0].guaranteed_lot_problem)
                $('#idEditDocNum').val(prodnRuncard[0].guaranteed_lot_doc_no)
            }else{
                toastr.warning('No Record Found!');
            }
        },

            error: function(data, xhr, status){
                toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}


