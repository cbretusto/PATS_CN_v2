function GetSakidashiList(cboElement){
    let result = '<option value="">N/A</option>';
    $.ajax({
        url: 'get_wbs_sakidashi_details',
        method: 'get',
        dataType: 'json',
        beforeSend: function(){
            result = '<option disabled value=""> -- Loading -- </option>';
            cboElement.html(result);
        },
        success: function(JsonObject){
            result = '';
            if(JsonObject['sakidashi_details'].length > 0){
                // result = '<option value="">N/A</option>';
                for(let index = 0; index < JsonObject['sakidashi_details'].length; index++){
                    result += '<option data-code="' + JsonObject['sakidashi_details'][index].item + '--' + JsonObject['sakidashi_details'][index].item_desc + '" value="' + JsonObject['sakidashi_details'][index].item + '--' + JsonObject['sakidashi_details'][index].item_desc + '" ' + '>' + JsonObject['sakidashi_details'][index].item_desc + '(' + JsonObject['sakidashi_details'][index].item + ')</option>';
                }
            }
            else{
                result = '<option value=""> -- No record found -- </option>';
            }

            cboElement.html(result);
        },
        error: function(data, xhr, status){
            result = '<option value=""> -- Reload Again -- </option>';
            cboElement.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function GetEmbossList(cboElement){
    let result = '<option value="">N/A</option>';
    $.ajax({
        url: 'get_wbs_sakidashi_details',
        method: 'get',
        dataType: 'json',
        beforeSend: function(){
            result = '<option disabled value=""> -- Loading -- </option>';
            cboElement.html(result);
        },
        success: function(JsonObject){
            result = '';
            if(JsonObject['sakidashi_details'].length > 0){
                // result = '<option value="">N/A</option>';
                for(let index = 0; index < JsonObject['sakidashi_details'].length; index++){
                    result += '<option data-code="' + JsonObject['sakidashi_details'][index].item + '--' + JsonObject['sakidashi_details'][index].item_desc + '" value="' + JsonObject['sakidashi_details'][index].item + '--' + JsonObject['sakidashi_details'][index].item_desc + '" ' + '>' + JsonObject['sakidashi_details'][index].item_desc + '(' + JsonObject['sakidashi_details'][index].item + ')</option>';
                }
            }
            else{
                result = '<option value=""> -- No record found -- </option>';
            }

            cboElement.html(result);
        },
        error: function(data, xhr, status){
            result = '<option value=""> -- Reload Again -- </option>';
            cboElement.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}