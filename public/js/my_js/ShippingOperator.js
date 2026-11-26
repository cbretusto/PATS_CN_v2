function SubmitShipop()
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
        url: "submit_shipop",
        method: "post",
        data: $('#formShippingOperator').serialize(),
        dataType: "json",
        beforeSend: function(){
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
            	$("#modalShippingOperator").modal('hide');
            	$("#formShippingOperator")[0].reset();

            	  dt_shippingoperator.draw();
                toastr.success('Shipping Operator was succesfully saved!');
                
            }

            else{
                toastr.error('Saving Shipping Operator Form Failed!');
            }

        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);

        }
    });
}

  function ShipopLinkToPackingCode(pack_code_shipop)
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
      url: "link_to_packing_code_shipop",
      method: "get",
      data: {
        pack_code_shipop: pack_code_shipop
      },
      dataType: "json",
      beforeSend: function(){
      },
      success: function(JsonObject)
      {
        if(JsonObject['result'] == 1)
        {
          $('#id_311').val(JsonObject['link_shipop'].radio3_1_1);
          $('#id_312').val(JsonObject['link_shipop'].radio3_1_2);
          $('#id_313').val(JsonObject['link_shipop'].radio3_1_3);
          $('#id_314').val(JsonObject['link_shipop'].radio3_1_4);

          $('#id_321').val(JsonObject['link_shipop'].radio3_2_1);
          $('#id_322').val(JsonObject['link_shipop'].radio3_2_2);
          $('#id_323').val(JsonObject['link_shipop'].radio3_2_3);

          $('#id_plcn').val(JsonObject['link_shipop'].pack_list_con_no);
          $('#id_tsq').val(JsonObject['link_shipop'].total_shipment_qty);
          $('#id_tbq').val(JsonObject['link_shipop'].total_box_qty);

          $('#id_shipop_judgement').val(JsonObject['link_shipop'].oqc_judgement);

          //DISABLE FIELDS
          $('#id_311').attr('readonly','readonly');
          $('#id_312').attr('readonly','readonly');
          $('#id_313').attr('readonly','readonly');
          $('#id_314').attr('readonly','readonly');

          $('#id_321').attr('readonly','readonly');
          $('#id_322').attr('readonly','readonly');
          $('#id_323').attr('readonly','readonly');

          $('#id_plcn').attr('readonly','readonly');
          $('#id_tsq').attr('readonly','readonly');
          $('#id_tbq').attr('readonly','readonly');
          $('#id_link_packcode_shipop').attr('readonly','readonly');

          //UNCLICKABLE
          $('#id_311').css("pointer-events","none");
          $('#id_312').css("pointer-events","none");
          $('#id_313').css("pointer-events","none");
          $('#id_314').css("pointer-events","none");


          toastr.success('Packing Code <strong>' + pack_code_shipop + '</strong> Data Linked! Scan your ID to Finish.');
          $('#btn_save_shipop').click();
        }
        else if(JsonObject['result'] == 2)
        {
          //toastr.error('Packing Code Does not have data yet!');
        }
      },
     error: function(data, xhr, status){
          toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
      }
    });
  }