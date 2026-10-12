function SubmitInitialInspection()
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
     	url: "submit_initial_inspection",
     	method: "post",
     	data: $('#FormInitialInspection').serialize(),
     	dataType: 'json',
     	beforeSend: function()
     	{

     	},
     	success: function(JsonObject)
     	{
     		if(JsonObject['result'] == 1)
     		{
     			$('#FormInitialInspection')[0].reset();
     			$('#modalScanReelC3').modal('hide');
     			$('#btn_save_initial_inspection').attr('disabled','disabled');
     			toastr.success('Initial Inspection Scanning Success!<br>You may now print the C3 Labels.');

          dt_packinginspection.draw();
     		}
     		else if(JsonObject['result'] == 2)
     		{
     			toastr.error('Scanned ID is not an OQC Packing Inspector!');
     		}
     	},
     	error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
     	}
     });
}

function SubmitInspectionFillin()
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
     	url: "submit_inspection_fillin",
     	method: "post",
     	data: $('#FormInspectionFillin').serialize(),
     	dataType: 'json',
     	beforeSend: function()
     	{

     	},
     	success: function(JsonObject)
     	{
     		if(JsonObject['result'] == 1)
     		{
     			$('#FormInspectionFillin')[0].reset();
     			$('#modalCheckpointFillin').modal('hide');
     			$('#btn_save_inspection_result').prop('disabled',true);
     			toastr.success('Inspection Fill-in Success! Please Add Shipping Details for Packing Code');

     			dt_packinginspection.draw();
     		}
     		else if(JsonObject['result'] == 2)
     		{
     			toastr.error('Scanned ID is not an OQC Packing Inspector!');
     		}
     		else if(JsonObject['result'] == 3)
     		{
     			toastr.error('Scanned ID should be the same with Inspector: [' + JsonObject['oqc_inspector'] + ']');
     		}
     	},
     	error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
     	}
     });
}

function SubmitShippingDetails()
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
     	url: "submit_shipping_details",
     	method: "post",
     	data: $('#FormShippingDetails').serialize(),
     	dataType: 'json',
     	beforeSend: function()
     	{

     	},
     	success: function(JsonObject)
     	{
     		if(JsonObject['result'] == 1)
     		{
     			$('#FormShippingDetails')[0].reset();
     			$('#modalShippingDetails').modal('hide');

     			toastr.success('Shipping Details Done! You are all good for now.');

     			dt_packinginspection.draw();
     		}
     		else if(JsonObject['result'] == 2)
     		{
     			toastr.error('Scanned ID is not an OQC Packing Inspector!');
     		}
     	},
     	error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
     	}
     });


}


//checking of packing code

function CheckPackCodeExists(po_num, pack_code)
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

		url: "check_pack_code_exists",
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
				let device = $('#id_device_name').val();

				$('#AddPoNum').val(po_num);
				$('#AddPackCode').val(pack_code);
				$('#AddDeviceName').val(device);
				$('#AddLotQty').val(JsonObject['lotcount']);

				RetrievePackCodeData(po_num, pack_code, device);

				$('#modalScanReelC3').modal('show');
			}
			else if(JsonObject['result'] == '2')
			{
				toastr.error('Packing Code Already Inspected!');
			}
			else if(JsonObject['result'] == '3')
			{
				toastr.error('Packing Inspection not yet filled-in by Packing Operator!');
			}
			else if(JsonObject['result'] == '4')
			{
				toastr.error('Packing Code is not in P.O. Number!\nPlease check with OQC Visual Inspection');
			}
			else
			{
				toastr.error('There seems to be an error! Contact ISS @ 205.');
			}
		},
		error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
     	}
	});
}

function RetrievePackCodeData(po_num, pack_code, device)
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
    	url: 'retrieve_packcode_data',
    	method: 'get',
    	data:
    	{
    		po_num: po_num,
    		pack_code: pack_code,
    		device: device,
    	},
    	dataType: 'json',
    	beforeSend: function()
    	{

    	},
    	success: function(JsonObject)
    	{
    		html = '';

    		for (var i = 0; i < JsonObject['reellots'].length; i++)
	        {
	          html += '<tr>';
	                  html += '<td>P';
	                  html += JsonObject['device'].huawei_p_n;
	                  html += '</td>';
	                  html += '<td>1P';
	                  html += device;
	                  html += '</td>';
	                  html += '<td>Q';
	                  html += JsonObject['reellots'][i].oqclotapp_details.lot_qty;
	                  html += '</td>';
	                  html += '<td>1T';
	                  html += JsonObject['reellots'][i].oqclotapp_details.reel_lot;
	                  html += '</td>';
	                html += '</tr>';
	        }

         $('#tbl_reel_lots tbody').html(html);

    	},	
    	error: function(data, xhr, status)
		{
			toastr.error('Error Loading Data! Please Reload Page or contact ISS local 205/207.');
		}
    });
}

function CheckC3Label(barcode, row)
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

    let new_row = parseInt(row) - 1;
    let c3looper = 0;
    let toastr_trigger = 0;

	/*
		Add condition for detection of c3 huawei & c3 non-huawei Reel Lot #
		Added by -JD
    */
   	// 106567001         CA009-S101-001(01)                      14200358                           00002402206132613-003  PHYE00000060220613 R6      00100220613 R12     	00140               00000               00000               00000               00000               00000               00000
	console.log('txt_search_c3_label length: ', barcode.length);
	if(barcode.length > 40){
		console.log('txt_search_c3_label barcode: ', barcode);

		// Manufacture PN
		console.log('Manufacture PN----');
		var manufacturePNSubstring = barcode.substring(18);
		console.log('manufacturePNSubstring substring: ', manufacturePNSubstring);
		var manufacturePN = $.trim(manufacturePNSubstring.substr(manufacturePNSubstring, 40));
		console.log('manufacturePN: ', manufacturePN);

		// Customer PN
		console.log('Customer PN----');
		var customerPNSubstring = barcode.substring(58);
		console.log('customerPNSubstring substring: ', customerPNSubstring);
		var customerPN = $.trim(customerPNSubstring.substr(customerPNSubstring, 35));
		console.log('customerPN: ', customerPN);

		// Quantity
		console.log('Quantity----');
		var quantitySubstring = barcode.substring(93);
		console.log('quantitySubstring substring: ', quantitySubstring);
		var qty = $.trim(quantitySubstring.substr(quantitySubstring, 7));
		console.log('qty: ',Math.round(qty));

		// Reel Lot
		console.log('Reel Lot----');
		var reelLotSubstring = barcode.substring(106);
		console.log('reel no substring: ', reelLotSubstring);
		var reelLot = $.trim(reelLotSubstring.substr(reelLotSubstring, 10));
		console.log('reel_lot: ', reelLot);

		$('#tbl_reel_lots tbody tr:eq('+new_row+')').each(function(){
			for(var i = 0; i < 4; i++){
				let barcodeToScan = '';
				if(i == 0){
					barcodeToScan = 'P'+customerPN;
				}
				if(i == 1){
					barcodeToScan = '1P'+manufacturePN;
				}
				if(i == 2){ 
					barcodeToScan = 'Q'+Math.round(qty);
				}
				if(i == 3){ 
					barcodeToScan = '1T'+reelLot;
				}

				if($(this).find("td").eq(i).html() == barcodeToScan)
				{
					// $(this).find('td').eq(i).addClass('table-success'); // Same as below codes
					$(this).find('td:eq('+i+')').addClass('table-success');
	
					setTimeout(function (){
						$('#id_search_c3_label').trigger('click');
					}, 500);
	
					toastr_trigger = 0;
					console.log(`✅ ${$(this).find("td").eq(i).html()} and ${barcodeToScan} is matched`); // Added 05-04-2022 -JD
				}
				else
				{
					// toastr_trigger++;
					console.log(`${$(this).find("td").eq(i).html()} and ${barcodeToScan} is not matched`); // Added 05-04-2022 -JD
				}
			}
			
	
			if(toastr_trigger > 3)
			{
				toastr.error('Scanned Barcode not Available in Row ' + row);
			}
	
			for(var x = 0; x < 4; x++){
				if($(this).find("td").eq(x).hasClass('table-success'))
				{
					c3looper++;
				}
			}
	
			if(c3looper == 4){
				let myrow = parseInt(row) + 1;
				$('#txtRowScan').val(myrow);
				c3looper = 0;
			}
	
		});
	}
	else{
		$('#tbl_reel_lots tbody tr:eq('+new_row+')').each(function(){
			for(var i = 0; i < 4; i++){
				if($(this).find("td").eq(i).html() == barcode){
					$(this).find('td:eq('+i+')').addClass('table-success');
	
					setTimeout(function (){
						$('#id_search_c3_label').trigger('click');
					}, 400);
	
					toastr_trigger = 0;
				}
				else{
					toastr_trigger++;
				}
			}
	
			if(toastr_trigger > 3){
				toastr.error('Scanned Barcode not Available in Row ' + row);
			}
	
			for(var x = 0; x < 4; x++){
				if($(this).find("td").eq(x).hasClass('table-success')){
					c3looper++;
				}
			}
	
			if(c3looper == 4){
				let myrow = parseInt(row) + 1;
				$('#txtRowScan').val(myrow);
				c3looper = 0;
			}
	
		});
	}
}

function CountSuccessInTable()
{
    var mytable = document.getElementById('tbl_reel_lots');
    var tdcheck = mytable.tBodies[0].rows.length * 4; // The 4 is how many columns in the table so the computation will be (num of rows * 4)
    var successcount = 0;

	console.log('tdcheck', mytable.tBodies[0].rows.length);
     $('#tbl_reel_lots tbody tr').each(function(){

      for(var i = 0; i < 4; i++)
      {
        if($(this).find("td").eq(i).hasClass('table-success'))
        {
          successcount++;
        }
      }

      });

    if(successcount == tdcheck)
    {
      $('#id_search_c3_label').attr('disabled','disabled');
      $('#btn_save_initial_inspection').removeAttr('disabled');
      $('#txtRowScan').val('');
    }

}