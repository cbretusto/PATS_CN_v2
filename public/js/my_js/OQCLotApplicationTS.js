/*function AddOQCLotApplication
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

		url: "add_oqc_lotapplication_ts",
		method: "post",
		data: $('#formOQCLotApp').serialize(),
		dataType: "json",
		beforeSend: function()
		{

		},
		success: function(JsonObject)
		{
			if(JsonObject['result'] == 1)
			{
				$('#formOQCLotApp')[0].reset();
				$('#modalAddApplication').modal('hide');

				dt_oqclotapp.draw();

				toastr.success('OQC Lot Application Successfully Applied!');
			}
			else
			{

			}
		},
		error: function(data, xhr, status)
		{
			  toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
		}
	});
}*/