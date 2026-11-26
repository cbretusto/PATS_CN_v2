function OQCCheckExistPackingCode(po_num,pack_code)
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
     	url: "oqc_check_exist_pack_code",
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
     			let total_lot_qty = JsonObject['lotcount'];

     			let device_name = $('#id_device_name').val();

     			$('#id_modal_po_num').val(po_num);
     			$('#id_modal_pack_code').val(pack_code);
     			$('#id_modal_device').val(device_name);
     			$('#id_modal_box_qty').val(total_lot_qty);

     			$('#modalOQCPrelimInspection').modal('show');
     		}
     		else if(JsonObject['result'] == '3')
	        {
	          toastr.error('Packing Code already inspected!');
	        }
        else if(JsonObject['result'] == '4')
          {
            toastr.error('Packing Inspection not yet filled-in by Packing Operator!');
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

function SubmitOQCPrelimInspection()
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

    	url: 'submit_oqc_inspection',
    	method: 'post',
    	data: $("#formOQCPrelimInspection").serialize(),
    	dataType: 'json',
    	beforeSend: function()
        {

        },
        success: function(JsonObject)
        {
          if(JsonObject['result'] == 1)
          {
            $("#modalOQCPrelimInspection").modal('hide');
            $("#formOQCPrelimInspection")[0].reset();

            dt_oqcpreliminspection.draw();

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

    	url: 'submit_shipping_details',
    	method: 'post',
    	data: $("#formShippingDetails").serialize(),
    	dataType: 'json',
    	beforeSend: function()
        {

        },
        success: function(JsonObject)
        {
          if(JsonObject['result'] == 1)
          {
            $("#modal_shipping_details").modal('hide');
            $("#formShippingDetails")[0].reset();

            dt_oqcpreliminspection.draw();

            toastr.success('Shipping Details was successfully saved!');
          }
          else if(JsonObject['result'] == 2)
          {
            toastr.error('User ID does not exist!');
          }
          else
          {
            toastr.error('Saving Shipping Details Failed!');
          }
        },
         error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }

    });
}

function OQCRetrieveDataFromPackCode(part,po_num,pack_code,device)
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
		url: 'oqc_retrieve_data_from_pack_code',
		method: 'get',
		data:
		{
			po_num: po_num,
			pack_code: pack_code,
      device: device
		},
		dataType: 'json',
		beforeSend: function()
		{

		},
		success: function(JsonObject)
		{
			if(JsonObject['reellots'].length > 0)
			{
        var html = '';

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

         $('#tblReelLotsForPackingCode tbody').html(html);
			}
		},
		error: function(data, xhr, status)
		{
			toastr.error('Error Loading Data! Please Reload Page or contact ISS local 205/207.');
		}

	});
}

function OQCCheckC3Label(barcode,row)
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


    $('#tblReelLotsForPackingCode tbody tr:eq('+ new_row +')').each(function(){

      for(var i = 0; i < 4; i++)
      {
        if($(this).find("td").eq(i).html() == barcode)
        {
          $(this).find('td:eq('+i+')').addClass('table-success');

          setTimeout(function () 
          {
            $('#id_search_c3_label').trigger('click');
          }, 400);

          toastr_trigger = 0;
        }
        else
        {
          toastr_trigger++;
        }
      }

      if(toastr_trigger > 3)
      {
        toastr.error('Scanned Barcode not Available in Row ' + row);
      }

      for(var x = 0; x < 4; x++)
      {
        if($(this).find("td").eq(x).hasClass('table-success'))
        {
          c3looper++;
        }
      }
          if(c3looper == 4)
          {
            let myrow = parseInt(row) + 1;
            $('#id_row_number').val(myrow);
            console.log($('#id_row_number').val());
            c3looper = 0;
          }
    });
  }

function CountSuccessInTable()
{
    var mytable = document.getElementById('tblReelLotsForPackingCode');
    var tdcheck = mytable.tBodies[0].rows.length * 4; // 3
    var successcount = 0;

     $('#tblReelLotsForPackingCode tbody tr').each(function(){

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
      $('#btn_modal_success').removeAttr('disabled');
      $('#btn_modal_success').removeClass('btn-secondary');
      $('#btn_modal_success').addClass('btn-success');

      $('#id_modal_result').text(' - ACCEPT');
    }

}

function SubmitOQCInspectionSupervisor()
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

        url: 'submit_oqc_inspection_supervisor',
        method: 'post',
        data: $("#formSupervisorOQCInspection").serialize(),
        dataType: 'json',
        beforeSend: function()
        {

        },
        success: function(JsonObject)
        {
          if(JsonObject['result'] == 1)
          {
            $("#modalOQCSupervisorInspection").modal('hide');
            $("#formSupervisorOQCInspection")[0].reset();

            dt_oqcpreliminspection.draw();

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

function OQCCheckIfSupervisor(employee,po_num,pack_code,lot_count)
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

        url: 'oqc_check_if_supervisor',
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

                $('#id_modal_supervisor_po_num').val(global_po_num);
                $('#id_modal_supervisor_pack_code').val(global_pack_code);
                $('#id_modal_supervisor_box_qty').val(global_lot_count);
                $('#id_modal_supervisor_device').val(device);

                OpenRDrawing(device);

                $('#modalOQCSupervisorInspection').modal('show');
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
