function GetPODetails(){
    let result = '<option value="0" selected disabled> --- </option>';
    $.ajax({
        url: 'get_assy_lines',
        method: 'get',
        dataType: 'json',
        beforeSend: function(){
            result = '<option value="0" selected disabled> -- Loading -- </option>';
            cboElement.html(result);
        },
        success: function(JsonObject){
            result = '';
            if(JsonObject['assembly_lines'].length > 0){
                result = '<option value="0" selected disabled> -- Select User Level -- </option>';
                for(let index = 0; index < 3; index++){
                    result += '<option value="' + JsonObject['assembly_lines'][index].id + '">' + JsonObject['assembly_lines'][index].name + '</option>';
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