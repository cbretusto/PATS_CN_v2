//Retrieve OQC Lot App Reel Lot Numbers from Packing Code
function RetrieveReelLotNumFromPackingCode(po_num, pack_code)
{
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
		url: 'retrieve_reel_lot_no_from_packing_code',
		method: 'get',
		data: {
			po_num: po_num,
			pack_code: pack_code
		},
		dataType: 'json',
		beforeSend: function()
		{

		},  
		success: function(JsonObject)
		{
			if(JsonObject['reellots'].length > 0)
			{
				for(var i = 0; i < JsonObject['reellots'].length; i++)
				{
					arrPackCodeReelLots.push(JsonObject['reellots'][i].oqclotapp_details.reel_lot);
				}

        console.log('arrPackCodeReelLots ',arrPackCodeReelLots);


				    var html = '';

			        for (var i = 0; i < arrPackCodeReelLots.length; i++)
			        {
			          html += '<tr>';
			            html += '<td align="center">';
			            html += arrPackCodeReelLots[i];
			            html += '</td>';
			            html += '<td>';
			            html += '</td>';
			          html += '</tr>';
			        }

			        $('#tblReelLotsForPackingCode tbody').html(html);
			}
		},
		error: function(data, xhr, status)
		{
			toastr.error('Error Loading Data! Please Reload Page or contact ISS local 205/207.');
		}
	});
}

function CheckExistPackingCode(po_num,pack_code)
{
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
     	url: "check_exist_pack_code",
     	method: "get",
     	data:
     	{
     		po_num: po_num,
     		pack_code: pack_code
     	},

     	dataType: 'json',

     	beforeSend: function()
     	{

     	},
     	success: function(JsonObject)
     	{
     		if(JsonObject['result'] == '1')
     		{
     			let total_lot_qty = parseInt(JsonObject['lotcount']);

     			let device_name = $('#id_device_name').val();

     			$('#id_modal_po_num').val(po_num);
     			$('#id_modal_pack_code').val(pack_code);
     			$('#id_modal_device').val(device_name);
     			$('#id_modal_box_qty').val(total_lot_qty);

            RetrieveReelLotNumFromPackingCode(po_num,pack_code);
            $('#modalProdPrelimInspection').modal('show');
          
     		}
        else if(JsonObject['result'] == '3')
        {
          toastr.error('Packing Code already inspected!');
        }
     		else
     		{
     			toastr.error('Packing Code is not in P.O. Number!');
     		}
     	},
      error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);

     	}

     });
}

function CheckC3LabelExists(barcode)
{
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

    let scanError =0;
    var matched = 0;
    var text_scanned_c3;

    /*
      Add condition for detection of c3 huawei & c3 non-huawei Reel Lot #
      Added by -JD
    */
    console.log('barcode', barcode);
    if(barcode.length < 10){
      console.log('txt_search_c3_label length: ', barcode.length);
      console.log('c3 not huawei');

      var text_scanned_c3 = barcode.substring(2);
      $('#tblReelLotsForPackingCode tbody tr').each(function(){
        if($(this).find('td:eq(0)').text() == text_scanned_c3){ 
          for(var x = 0; x < arrUserScanReelLots.length; x++){
            if(text_scanned_c3 == arrUserScanReelLots[x]){
              matched++;
            }
          }

          if(matched > 0){
            toastr.error('Reel Lot Number Already Scanned!');
          }
          else{
            $(this).find('td:eq(1)').text(text_scanned_c3);
            $(this).addClass('table-success');
            arrUserScanReelLots.push(text_scanned_c3);

            setTimeout(function (){
              $('#btn_search_c3_label').trigger('click');
            },400);
          }
        }
      });

      for(var y = 0; y < arrPackCodeReelLots.length; y++){
        if(text_scanned_c3 != arrPackCodeReelLots[y])
        {
          scanError++;
          console.log(scanError);
        }
      }

      if(scanError == arrPackCodeReelLots.length){
        Swal.fire({
          position: 'top-end',
          icon: 'error',
          title: 'Scanned Item not in Reel Lots List',
          showConfirmButton: false,
        })
      }

      if(arrUserScanReelLots.length == arrPackCodeReelLots.length){
        $('.btn_search_c3_label').attr('disabled','disabled');
        $('#btn_check_reel_lots').removeAttr('disabled');	
      }
      console.log(arrPackCodeReelLots.length);
    }else{
      console.log('txt_search_c3_label length: ',barcode.length);
      console.log('c3 huawei');
      
      var reelLotSubstring = barcode.substring(106);
      console.log('reel no substring: ', reelLotSubstring);
      var reelLot = $.trim(reelLotSubstring.substr(reelLotSubstring, 10));
      console.log('reel_lot: ', reelLot);
      
      $('#tblReelLotsForPackingCode tbody tr').each(function(){
        console.log('td:eq reel lot that will be use to match', $(this).find('td:eq(0)').text());
      
          if($(this).find('td:eq(0)').text() == reelLot){
            for(var x = 0; x < arrUserScanReelLots.length; x++){
              if(reelLot == arrUserScanReelLots[x]){
                matched++;
              }
            }

            if(matched > 0){
              toastr.error('Reel Lot Number Already Scanned!');
            }
            else{
              $(this).find('td:eq(1)').text(reelLot);
              $(this).addClass('table-success');
              arrUserScanReelLots.push(reelLot);
              console.log('arrUserScanReelLots ', arrUserScanReelLots);

              setTimeout(function ()
              {
                $('#btn_search_c3_label').trigger('click');
              // }, 500);
              }, 600);
            }
          }else{
            scanError++;
            console.log('scanError ', scanError);
          }
      });
      console.log('arrPackCodeReelLots.length ', arrPackCodeReelLots.length);
  
      if(scanError == arrPackCodeReelLots.length){
        Swal.fire({
          position: 'top-end',
          icon: 'error',
          title: 'Scanned Item not in Reel Lots List',
          showConfirmButton: false,
        })
      }
  
      if(arrUserScanReelLots.length == arrPackCodeReelLots.length){
        $('.btn_search_c3_label').attr('disabled','disabled');
        $('#btn_check_reel_lots').removeAttr('disabled');	
      }
    }
}

/**
 * Old code before the last revision
 * 06-22-2022 -JD
 */
// function CheckC3LabelExists(barcode)
// {
// 	toastr.options = {
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

//     let scanError =0;
//     var matched = 0;
//     var text_scanned_c3;

//     /*
//       Add condition for detection of c3 huawei & c3 non-huawei Reel Lot #
//       Added by -JD
//     */
//     console.log('barcode', barcode);
//     if(barcode.length < 10){
//       console.log('txt_search_c3_label length: ', barcode.length);
//       console.log('c3 not huawei');

//       var text_scanned_c3 = barcode.substring(2);
//       $('#tblReelLotsForPackingCode tbody tr').each(function(){
//         if($(this).find('td:eq(0)').text() == text_scanned_c3){ 
//           for(var x = 0; x < arrUserScanReelLots.length; x++){
//             if(text_scanned_c3 == arrUserScanReelLots[x]){
//               matched++;
//             }
//           }

//           if(matched > 0){
//             toastr.error('Reel Lot Number Already Scanned!');
//           }
//           else{
//             $(this).find('td:eq(1)').text(text_scanned_c3);
//             $(this).addClass('table-success');
//             arrUserScanReelLots.push(text_scanned_c3);

//             setTimeout(function (){
//               $('#btn_search_c3_label').trigger('click');
//             },400);
//           }
//         }
//       });

//       for(var y = 0; y < arrPackCodeReelLots.length; y++){
//         if(text_scanned_c3 != arrPackCodeReelLots[y])
//         {
//           scanError++;
//           console.log(scanError);
//         }
//       }

//       if(scanError == arrPackCodeReelLots.length){
//         Swal.fire({
//           position: 'top-end',
//           icon: 'error',
//           title: 'Scanned Item not in Reel Lots List',
//           showConfirmButton: false,
//         })
//       }

//       if(arrUserScanReelLots.length == arrPackCodeReelLots.length){
//         $('.btn_search_c3_label').attr('disabled','disabled');
//         $('#btn_check_reel_lots').removeAttr('disabled');	
//       }
//       console.log(arrPackCodeReelLots.length);
//     }else{
//       console.log('txt_search_c3_label length: ',barcode.length);
//       console.log('c3 huawei');
//       text_scanned_c3_substring = barcode.substring(9);
//       text_scanned_c3 = text_scanned_c3_substring.trim();
//       console.log('text_scanned_c3 with substring() & trim():', text_scanned_c3);
      
//       let explodedBarcode = text_scanned_c3.split(' ');
//       explodedBarcode.filter(Boolean);
//       console.log('explodedBarcode ',explodedBarcode);
//       $('#tblReelLotsForPackingCode tbody tr').each(function(){
//         console.log('td:eq that will be use to match', $(this).find('td:eq(0)').text());
        
//         // barcodeDataPushed = [];
//         // console.log('explodedBarcode 5th index ', explodedBarcode[5]);
//         // explodedBarcode.forEach(index => {
//           if($(this).find('td:eq(0)').text() == explodedBarcode[5]){
//             for(var x = 0; x < arrUserScanReelLots.length; x++){
//               if(index == arrUserScanReelLots[x]){
//                 matched++;
//               }
//             }
    
//             if(matched > 0){
//               toastr.error('Reel Lot Number Already Scanned!');
//             }
//             else{
//               $(this).find('td:eq(1)').text(explodedBarcode[5]);
//               $(this).addClass('table-success');
//               arrUserScanReelLots.push(explodedBarcode[5]);
//               console.log('arrUserScanReelLots ', arrUserScanReelLots);

//               setTimeout(function ()
//               {
//                 $('#btn_search_c3_label').trigger('click');
//               }, 500);

//               // for(var y = 0; y < arrPackCodeReelLots.length; y++){
//               //   if(index != arrPackCodeReelLots[y]){
//               //     scanError++;
//               //     console.log('scanError ', scanError);
//               //   }
//               //   console.log('scanError ', scanError);
//               // }
//             }
//           }else{
//             scanError++;
//             console.log('scanError ', scanError);
//           }
//           // barcodeDataPushed.push(index);
//         // });
//         // console.log('barcodeDataPushed ',barcodeDataPushed);
//       });
//       console.log('arrPackCodeReelLots.length ', arrPackCodeReelLots.length);
  
//       if(scanError == arrPackCodeReelLots.length){
//         Swal.fire({
//           position: 'top-end',
//           icon: 'error',
//           title: 'Scanned Item not in Reel Lots List',
//           showConfirmButton: false,
//         })
//       }
  
//       if(arrUserScanReelLots.length == arrPackCodeReelLots.length){
//         $('.btn_search_c3_label').attr('disabled','disabled');
//         $('#btn_check_reel_lots').removeAttr('disabled');	
//       }
//     }
// }

// function CheckC3LabelExists(barcode)
// {
// 	toastr.options = {
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

//     var scanError = 0;
//     var matched = 0;
//     var text_scanned_c3;

//     /*
//       Add condition for detection of c3 huawei & c3 non-huawei Reel Lot #
//       Added by -JD
//     */
//     if(barcode.length < 10){
//       console.log('txt_search_c3_label length: ', barcode.length);
//       text_scanned_c3 = barcode;
//       console.log('c3 huawei');
//     }else{
//       console.log('txt_search_c3_label length: ',barcode.length);
//       text_scanned_c3_not_trimmeed = barcode.substring(9);
//       console.log('text_scanned_c3_not_trimmeed:', text_scanned_c3_not_trimmeed);
//       text_scanned_c3 = text_scanned_c3_not_trimmeed.trim();
//       console.log('text_scanned_c3:', text_scanned_c3);
//       console.log('c3 not huawei');
//       console.log('barcode', barcode);
//     }


//     $('#tblReelLotsForPackingCode tbody tr').each(function(){
//       console.log('td eq ',$(this).find('td:eq(0)').text());
//       // console.log(text_scanned_c3);

//     	if($(this).find('td:eq(0)').text() == $(this).find('td:eq(0)').text())
//     	//if($(this).find('td:eq(0)').text() == barcode)
//     	{ //
//         console.log('td eq is equal to text scanned c3');
//         for(var x = 0; x < arrUserScanReelLots.length; x++)
//         {
//           if(text_scanned_c3 == arrUserScanReelLots[x])
//           //if(barcode == arrUserScanReelLots[x])
//           {
//             matched++;
//           }
//         }

//         if(matched > 0)
//           {
//               toastr.error('Reel Lot Number Already Scanned!');
//           }
//           else
//           {
//              // $(this).find('td:eq(1)').text(barcode);
//               $(this).find('td:eq(1)').text($(this).find('td:eq(0)').text());
//               $(this).addClass('table-success');
//           arrUserScanReelLots.push($(this).find('td:eq(0)').text());
//             //  arrUserScanReelLots.push(barcode);
//               console.log('matched is not greater than 0');
//               console.log('arrUserScanReelLots ', arrUserScanReelLots);
//               setTimeout(function () 
//               {
//                 $('#btn_search_c3_label').trigger('click');
//               },400);
//           }
//     	}

//     });

//     console.log('arrPackCodeReelLots ', arrPackCodeReelLots);
//     for(var y = 0; y < arrPackCodeReelLots.length; y++)
//     {
//       if(text_scanned_c3 != arrPackCodeReelLots[y])
//       //if(barcode != arrPackCodeReelLots[y])
//       {
//         scanError++;
//         console.log('scanError',scanError);
//       }
//     }

//     if(scanError == arrPackCodeReelLots.length)
//       {
//         Swal.fire({
//           position: 'top-end',
//           icon: 'error',
//           title: 'Scanned Item not in Reel Lots List',
//           showConfirmButton: false,
//         })
//       }



//     if(arrUserScanReelLots.length == arrPackCodeReelLots.length)
//     {
//     	$('.btn_search_c3_label').attr('disabled','disabled');
//     	$('#btn_check_reel_lots').removeAttr('disabled');	
//     }

//     //console.log(scanError);
//     console.log(arrPackCodeReelLots.length);
// }

/**
 * Old code from 1st
 */
  // function CheckC3LabelExists(barcode)
  // {
  //   toastr.options = {
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
  
  //     var scanError = 0;
  //     var matched = 0;
  
  //     var text_scanned_c3 = barcode.substring(2);
  
  //     $('#tblReelLotsForPackingCode tbody tr').each(function(){
  
  //       if($(this).find('td:eq(0)').text() == text_scanned_c3)
  //       { 
  //         for(var x = 0; x < arrUserScanReelLots.length; x++)
  //         {
  //           if(text_scanned_c3 == arrUserScanReelLots[x])
  //           {
  //             matched++;
  //           }
  //         }
  
  //         if(matched > 0)
  //           {
  //               toastr.error('Reel Lot Number Already Scanned!');
  //           }
  //           else
  //           {
  //               $(this).find('td:eq(1)').text(text_scanned_c3);
  //               $(this).addClass('table-success');
  //               arrUserScanReelLots.push(text_scanned_c3);
  
  //               setTimeout(function () 
  //               {
  //                 $('#btn_search_c3_label').trigger('click');
  //               },400);
  //           }
  //       }
  
  //     });
  
  
  //     for(var y = 0; y < arrPackCodeReelLots.length; y++)
  //         {
  //           if(text_scanned_c3 != arrPackCodeReelLots[y])
  //           {
  //             scanError++;
  //             console.log(scanError);
  //           }
  //         }
  
  //     if(scanError == arrPackCodeReelLots.length)
  //       {
  //         Swal.fire({
  //           position: 'top-end',
  //           icon: 'error',
  //           title: 'Scanned Item not in Reel Lots List',
  //           showConfirmButton: false,
  //         })
  //       }
  
  
  
  //     if(arrUserScanReelLots.length == arrPackCodeReelLots.length)
  //     {
  //       $('.btn_search_c3_label').attr('disabled','disabled');
  //       $('#btn_check_reel_lots').removeAttr('disabled');	
  //     }
  
  //     //console.log(scanError);
  //     console.log(arrPackCodeReelLots.length);
  // }

function OpenRDrawing(device)
{
	$.ajax({
		url: 'ppo_retrieve_r_drawing',
		method: 'get',
		data: {
			device: device
		},

		dataType: 'json',

		beforeSend: function()
		{

		},
		success: function(JsonObject)
		{
			var fkid_doc_no = JsonObject['documents'][0].fkid_document;

			window.open("http://192.168.3.235/ACDCS/pdf_viewer_dquick_document.php?pkid_doc_stat="+fkid_doc_no+'_1', '_blank');    
		},
		error: function()
		{

		}

	});
}

function SubmitPrelimInspection()
{
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

        url: 'submit_prelim_inspection',
        method: 'post',
        data: $("#formProdPrelimInspection").serialize(),
        dataType: 'json',
        beforeSend: function()
        {

        },
        success: function(JsonObject)
        {
          if(JsonObject['result'] == 1)
          {
            $("#modalProdPrelimInspection").modal('hide');
            $("#formProdPrelimInspection")[0].reset();

            dt_prodpreliminspection.draw();

            toastr.success('Packing Inspection was successfully saved!');
          }
          else if(JsonObject['result'] == 2)
          {
            toastr.error('User ID does not exist!');
          }
          else
          {
            toastr.error('Saving Packing Inspection Failed!');
          }
        },
         error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }

    });
}

function SubmitPrelimInspectionSupervisor()
{
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

        url: 'submit_prelim_inspection_supervisor',
        method: 'post',
        data: $("#formProdPrelimInspectionSupervisor").serialize(),
        dataType: 'json',
        beforeSend: function()
        {

        },
        success: function(JsonObject)
        {
          if(JsonObject['result'] == 1)
          {
            $("#modalProdPrelimInspectionSupervisor").modal('hide');
            $("#formProdPrelimInspectionSupervisor")[0].reset();

            dt_prodpreliminspection.draw();

            toastr.success('Packing Inspection was successfully saved!');
          }
          else if(JsonObject['result'] == 2)
          {
            toastr.error('User is not Supervisor!');
          }
          else
          {
            toastr.error('Saving Packing Inspection Failed!');
          }
        },
         error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }

    });
}

function CheckIfSupervisor(employee,po_num,pack_code,lot_count)
{
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

        url: 'check_if_supervisor',
        method: 'get',
        data: {
          lot_count: lot_count,
          po_num: po_num,
          pack_code: pack_code,
          employee: employee
        },
        dataType: 'json',

          beforeSend: function()
          {

          },
          success: function(JsonObject)
          {
            if(JsonObject['result'] == '1')
            {
                let device = $('#id_device_name').val();

                $('#id_modal_supervisor_device').val(device);
                $('#id_modal_supervisor_box_qty').val(lot_count);
                $('#id_modal_supervisor_pack_code').val(pack_code);
                $('#id_modal_supervisor_po_num').val(po_num);

                OpenRDrawing(device);

              $('#modalProdPrelimInspectionSupervisor').modal('show');
            }
            else if(JsonObject['result'] == '2')
            {
              toastr.error('User is not supervisor!');
            }
            else
            {
              toastr.error('User ID not found!');
            }
          },
          error: function()
          {

          }

    })
}

