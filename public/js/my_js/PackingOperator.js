function SubmitPackop()
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

      url: "submit_packop",
      method: "post",
      data: $('#formPrelimPackingOperator').serialize(),
      dataType: "json",
      beforeSend: function()
      {

      },
      success: function(JsonObject)
      {
        if(JsonObject['result'] == 1)
        {
          $("#modalPackingOperator").modal('hide');
          $("#formPrelimPackingOperator")[0].reset();


          dt_packingoperator.draw();
          toastr.success('Packing Operator Form was succesfully saved!');
        }

        else if(JsonObject['result'] == 2)
        {
          toastr.error('Scanned ID Does not have OQC Stamp!');
        }

        else
        {
          toastr.error('Saving Packing Operator Form Failed!');
        }


      },
       error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
  });
}

function RetrieveOQCDetails(po_num, batch, mat_sub, oqclotapp_id)
{
     $.ajax({
      url: "retrieve_oqc_details",
      method: "get",
      data: {
        po_num: po_num,
        batch: batch,
        mat_sub: mat_sub,
        oqclotapp_id: oqclotapp_id
      },
      dataType: "json",
      beforeSend: function(){
      },
      success: function(JsonObject)
      {
        if(JsonObject['oqclot'].length > 0)
        {
            $("#id_oqclotapp").val(JsonObject['oqclot'][0].id);

            $("#id_currentPONo").val(JsonObject['oqclot'][0].po_no);

            $("#id_myponum").val(JsonObject['oqclot'][0].po_no);
            $("#id_mybatch").val(JsonObject['oqclot'][0].lot_batch_no);
            $("#id_mysub").val(JsonObject['oqclot'][0].submission);

             $("#id_select_Device").val(JsonObject['oqclot'][0].device_cat);
             
             $("#id_CertLot").val(JsonObject['oqclot'][0].cert_lot);

             $("#id_AssyLine").val(JsonObject['oqclot'][0].assy_details.name);

             $("#id_LotBatch").val(JsonObject['oqclot'][0].lot_batch_no);

             $("#id_search_name").val(JsonObject['oqclot'][0].user_details.name);

             $("#id_ReelNo").val(JsonObject['oqclot'][0].reel_lot);

             $("#id_PrintLotNo").val(JsonObject['oqclot'][0].print_lot);

             $("#id_LotQty").val(JsonObject['oqclot'][0].lot_qty);

             $("#id_OutputQty").val(JsonObject['oqclot'][0].output_qty);

             $("#id_GuaranteedLot").val(JsonObject['oqclot'][0].guaranteed_lot);

             $("#id_UrgentDirection").val(JsonObject['oqclot'][0].direction);

             $("#id_Drawing").val(JsonObject['oqclot'][0].Adrawing + " / " + JsonObject['oqclot'][0].Gdrawing);

             $("#id_TtlNoReels").val(JsonObject['oqclot'][0].ttl_reel);
             $("#id_135").val(JsonObject['oqclot'][0].ttl_reel);
             $("#id_225").val(JsonObject['oqclot'][0].ttl_reel);

            $("#id_AppDate").val(JsonObject['oqclot'][0].app_date);

             $("#id_AppTime").val(JsonObject['oqclot'][0].app_time);

            $("#id_Problem").val(JsonObject['oqclot'][0].problem);

            $("#id_DocNo").val(JsonObject['oqclot'][0].doc_no);

            $("#id_ConfirmBy").val(JsonObject['oqclot'][0].partial_lot_confirm);

            $("#id_OQCRemarks").val(JsonObject['oqclot'][0].remarks);

            if(JsonObject['oqclot'][0].prodn_supervisor == null)
            {
              $('#id_prodn_supv').val('---');
            }
            else
            {
              $('#id_prodn_supv').val(JsonObject['oqclot'][0].user_details.name);
            }

            if(JsonObject['oqclot'][0].oqc_supervisor == null)
            {
              $('#id_oqc_supv').val('---');
            }
            else
            {
              $('#id_oqc_supv').val(JsonObject['oqclot'][0].user_details.name);
            }

            dataTableOQCVIR_summary.draw();
          }
      },
     error: function(data, xhr, status){
          console.log()
      }
    });
  }
  
  function PackopOQCVIRDetails(po_num, batch)
  {
     $.ajax({
      url: "packop_oqc_vir_details",
      method: "get",
      data: {
        po_num: po_num,
        batch: batch
      },
      dataType: "json",
      beforeSend: function(){
      },
      success: function(JsonObject)
      {
        if(JsonObject['oqclot'].length > 0)
        {

        }
      },
     error: function(data, xhr, status){
          console.log()
      }
    });
  }

  function GeneratePackingCode(device_name)
  {
    $.ajax({
      url: "generate_packing_code",
      method: "get",
      data: {
        device_name: device_name
      },
      dataType: "json",
      beforeSend: function(){
      },
      success: function(JsonObject)
      {
        if(JsonObject['pack_code'].length > 0)
        {
          let packingcode = JsonObject['pack_code'][0].cnpts_series_code;
          let date = new Date().toISOString().substr(5, 2);
          let autogenNum = (JsonObject['autogenNum']).toString();
          let autogen = autogenNum.padStart(3, "0");

          $('#id_PackingCode').val(packingcode + date + "-" + autogen);
        }
      },
     error: function(data, xhr, status){
          console.log()
      }
    });
  }

  function CountReelLotNo(array_length, device_name)
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
              type      : "get",
              dataType  : "json",
              data      : {
                array_length : array_length,
                device_name : device_name
              },
              url       : "get_reel_lot_count",

              success   :  function(JsonObject)
              {
                if(JsonObject['result'] == 1)
                {
                  $('#id_save_reel_lots').removeAttr('disabled');
                  $('.chkReelLot').removeAttr('disabled');
                }
                else if(JsonObject['result'] == 2)
                {
                  $('.chkReelLot').attr('disabled','disabled');
                }
                else if(JsonObject['result'] == 0)
                {
                  $('#id_save_reel_lots').attr('disabled','disabled');
                }
                else
                {
                  toastr.error('Adding Reel Lot Failed!');
                }
              },
           error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            }
     });
  }


  function PackopLinkToPackingCode(pack_code_packop, box_qty)
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
      url: "link_to_packing_code_packop",
      method: "get",
      data: {
        pack_code_packop: pack_code_packop,
        box_qty: box_qty
      },
      dataType: "json",
      beforeSend: function(){
      },
      success: function(JsonObject)
      {
        if(JsonObject['result'] == 1)
        {
          $('#id_PackopPackingType').val(JsonObject['link_packop'].packop_packing_type);
          $('#id_PackopUnitCondition').val(JsonObject['link_packop'].packop_unit_condition);
          $('#id_131').val(JsonObject['link_packop'].radio1_3_1);
          $('#id_132').val(JsonObject['link_packop'].radio1_3_2);
          $('#id_133').val(JsonObject['link_packop'].radio1_3_3);
          $('#id_134').val(JsonObject['link_packop'].radio1_3_4);

          $('#id_PackingCode').val(JsonObject['link_packop'].pack_code_no);

          $('#id_151').val(JsonObject['link_packop'].radio1_5_1);
          $('#id_152').val(JsonObject['link_packop'].radio1_5_2);
          $('#id_153').val(JsonObject['link_packop'].radio1_5_3);
          $('#id_154').val(JsonObject['link_packop'].radio1_5_4);
          $('#id_155').val(JsonObject['link_packop'].radio1_5_5);
          $('#id_156').val(JsonObject['link_packop'].radio1_5_6);

          $('#id_judgement').val(JsonObject['link_packop'].oqc_judgement);

          //$('#id_link_packcode_packop').val('');

          //DISABLE ALL FIELDS IF LINKED
          $('#id_PackopPackingType').attr('readonly','readonly');
          $('#id_PackopPackingType').attr('readonly','readonly');
          $('#id_PackopUnitCondition').attr('readonly','readonly');
          $('#id_131').attr('readonly','readonly');
          $('#id_132').attr('readonly','readonly');
          $('#id_133').attr('readonly','readonly');
          $('#id_134').attr('readonly','readonly');
          $('#id_135').attr('readonly','readonly');
          $('#id_PackingCode').attr('readonly','readonly');
          $('#id_151').attr('readonly','readonly');
          $('#id_152').attr('readonly','readonly');
          $('#id_153').attr('readonly','readonly');
          $('#id_154').attr('readonly','readonly');
          $('#id_155').attr('readonly','readonly');
          $('#id_156').attr('readonly','readonly');
          $("#id_link_packcode_packop").attr('readonly','readonly');
          $('#id_btn_link_packcode_packop').attr('disabled','disabled');
          $('#id_btn_generate_pack_code').attr('disabled','disabled');

          $('#id_PackopPackingType').css("pointer-events","none");
          $('#id_PackopPackingType').css("pointer-events","none");
          $('#id_PackopUnitCondition').css("pointer-events","none");
          $('#id_131').css("pointer-events","none");
          $('#id_132').css("pointer-events","none");
          $('#id_133').css("pointer-events","none");
          $('#id_134').css("pointer-events","none");
          $('#id_135').css("pointer-events","none");
          $('#id_PackingCode').css("pointer-events","none");
          $('#id_151').css("pointer-events","none");
          $('#id_152').css("pointer-events","none");
          $('#id_153').css("pointer-events","none");
          $('#id_154').css("pointer-events","none");
          $('#id_155').css("pointer-events","none");
          $('#id_156').css("pointer-events","none");
          $("#id_link_packcode_packop").css("pointer-events","none");
          $('#id_btn_link_packcode_packop').attr('disabled','disabled');
          $('#id_btn_generate_pack_code').attr('disabled','disabled');


          toastr.success('Packing Code <strong>' + pack_code_packop + '</strong> Data Linked! Scan your ID to Finish.');
          $('#btn_save_packop').click();

        }
        else if(JsonObject['result'] == 2)
        {
          toastr.error('Packing Code <strong>' + pack_code_packop + '</strong> is not available!');
        }
        else if(JsonObject['result'] == 3)
        {
          toastr.error('Packing Code already reached maximum boxing quantity!');
        }
         else if(JsonObject['result'] == 4)
        {
          toastr.error('Packing Code already done at QC Inspection!');
        }
      },
     error: function(data, xhr, status){
          toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
      }
    });
  }