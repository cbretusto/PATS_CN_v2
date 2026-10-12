function SubmitPackin(){

  //Alert
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
        url: "submit_packin",
        method: "post",
        data: $('#formPackingInspector').serialize(),
        dataType: "json",
        beforeSend: function(){

        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
            	$("#modalPackingInspector").modal('hide');
            	$("#formPackingInspector")[0].reset();

            	  dt_packinginspector.draw();
                toastr.success('Packing Inspector Form was succesfully saved!');
                
            }
            else if(JsonObject['result'] == 2)
            {
              toastr.error('Scanned ID Does not have OQC Stamp!');
            }


            else{
                toastr.error('Saving Packing Inspector Form Failed!');
            }

        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

  function RetrieveOQCName(oqclotapp_id, mat_sub)
  {
     $.ajax({
      url: "retrieve_oqc_name",
      method: "get",
      data: {

        oqclotapp_id: oqclotapp_id,
        mat_sub: mat_sub

      },
      dataType: "json",
      beforeSend: function(){
      },
      success: function(JsonObject)
      {
          if(JsonObject['oqcins'].length > 0) 
          {
              $("#id_OQCAcceptedBy").val(JsonObject['oqcins'][0].user_details.name);

              $("#id_oqc_name").val(JsonObject['oqcins'][0].user_details.id);
          }
      },
      error: function(data, xhr, status){
          console.log()
      }
    });  
   }

   function RetrievePackCodeFromPackop(po_num, batch, mat_sub, station)
  {
     $.ajax({
      url: "retrieve_pack_code_from_packop",
      method: "get",
      data: {

        po_num: po_num,
        batch: batch,
        mat_sub: mat_sub,
        station: station

      },
      dataType: "json",
      beforeSend: function(){
      },
      success: function(JsonObject)
      {
          if(JsonObject['packop'].length > 0) 
          {
              $("#id_packin_pack_code_no").val(JsonObject['packop'][0].pack_code_no);
              $('#id_link_packcode_packin').val(JsonObject['packop'][0].pack_code_no);
              $('#id_link_packcode_shipop').val(JsonObject['packop'][0].pack_code_no);
              $('#id_link_packcode_shipin').val(JsonObject['packop'][0].pack_code_no);

              if(station == 1)
              {
                PackinLinkToPackingCode(JsonObject['packop'][0].pack_code_no);
              }
              else if(station == 2)
              {
                ShipopLinkToPackingCode(JsonObject['packop'][0].pack_code_no);
              }
              else if(station == 3)
              {
                ShipinLinkToPackingCode(JsonObject['packop'][0].pack_code_no);
              }
          }
      },
      error: function(data, xhr, status){
          console.log()
      }
    });  
   }

  function RetrievePackopDetails(po_num, batch, mat_sub)
  {
    $.ajax({
      url: "retrieve_packop_details",
      method: "get",
      data: {
        po_num: po_num,
        batch: batch,
        mat_sub: mat_sub
      },
      dataType: "json",
      beforeSend: function(){
      },
      success: function(JsonObject)
      {
          if(JsonObject['packop'].length > 0) 
          {
              $("#id_PackopPackingType").val(JsonObject['packop'][0].packop_packing_type);

              $("#id_PackopUnitCondition").val(JsonObject['packop'][0].packop_unit_condition);

              $("#id_myponum").val(JsonObject['packop'][0].po_no);
              $("#id_mybatch").val(JsonObject['packop'][0].lot_batch_no);
              $("#id_mysub").val(JsonObject['packop'][0].submission);

              $("#id_131").val(JsonObject['packop'][0].radio1_3_1);

              $("#id_132").val(JsonObject['packop'][0].radio1_3_2);

              $("#id_133").val(JsonObject['packop'][0].radio1_3_3);

              $("#id_134").val(JsonObject['packop'][0].radio1_3_4);

              $("#id_135").val(JsonObject['packop'][0].total_num_reels);
              $("#id_225").val(JsonObject['packop'][0].total_num_reels);

              $("#id_PackingCode").val(JsonObject['packop'][0].pack_code_no);
              $("#id_packin_pack_code_no").val(JsonObject['packop'][0].total_num_reels);
             
              $("#id_151").val(JsonObject['packop'][0].radio1_5_1);
              $("#id_152").val(JsonObject['packop'][0].radio1_5_2);
              $("#id_153").val(JsonObject['packop'][0].radio1_5_3);
              $("#id_154").val(JsonObject['packop'][0].radio1_5_4);
              $("#id_155").val(JsonObject['packop'][0].radio1_5_5);
              $("#id_156").val(JsonObject['packop'][0].radio1_5_6);

              $("#id_judgement").val(JsonObject['packop'][0].oqc_judgement);

              $("#id_PackopApprovedName").val(JsonObject['packop'][0].user_details.name);

              $("#id_PackopApprovedDate").val(JsonObject['packop'][0].updated_at);
          }
      },
      error: function(data, xhr, status){
          console.log()
      }
    });
  }

  function PackinLinkToPackingCode(pack_code_packin)
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
      url: "link_to_packing_code_packin",
      method: "get",
      data: {
        pack_code_packin: pack_code_packin
      },
      dataType: "json",
      beforeSend: function(){
      },
      success: function(JsonObject)
      {
        if(JsonObject['result'] == 1)
        {
          $('#id_221').val(JsonObject['link_packin'].radio2_2_1);
          $('#id_222').val(JsonObject['link_packin'].radio2_2_2);
          $('#id_223').val(JsonObject['link_packin'].radio2_2_3);
          $('#id_224').val(JsonObject['link_packin'].radio2_2_4);

          $('#id_23').val(JsonObject['link_packin'].pac_man_doc_comp);    
          $('#id_24').val(JsonObject['link_packin'].accessories);

          $('#id_packin_pack_code_no').val(JsonObject['link_packin'].pack_code_no);

          $('#id_packin_judgement').val(JsonObject['link_packin'].oqc_judgement);

          //$('#id_link_packcode_packin').val('');

          //DISABLE IF LINKED
          $('#id_221').attr('readonly','readonly');
          $('#id_222').attr('readonly','readonly');
          $('#id_223').attr('readonly','readonly');
          $('#id_224').attr('readonly','readonly');
          $('#id_225').attr('readonly','readonly');
          $('#id_23').attr('readonly','readonly');    
          $('#id_24').attr('readonly','readonly');
          $('#id_link_packcode_packin').attr('readonly','readonly');


          $('#id_btn_link_packcode_packin').attr('disabled','disabled');

          //unclickable
          $('#id_221').css("pointer-events","none");
          $('#id_222').css("pointer-events","none");
          $('#id_223').css("pointer-events","none");
          $('#id_224').css("pointer-events","none");
          $('#id_225').css("pointer-events","none");
          $('#id_23').css("pointer-events","none");    
          $('#id_24').css("pointer-events","none");

          toastr.success('Packing Code <strong>' + pack_code_packin + '</strong> Data Linked! Scan your ID to Finish.');
          $('#btn_save_packin').click();
        }
        else if(JsonObject['result'] == 2)
        {
          //toastr.warning('Packing Code <strong>' + $pack + '</strong> is not available!');
        }
      },
     error: function(data, xhr, status){
          toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
      }
    });
  }
