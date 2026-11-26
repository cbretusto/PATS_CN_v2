function AddStationSubStation(){
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
        url: "add_station_sub_station",
        method: "post",
        data: $('#formAddStationSubStation').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnAddStationSubStationIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnAddStationSubStation").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            $("#iBtnAddStationSubStationIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddStationSubStation").removeAttr('disabled');
            $("#iBtnAddStationSubStationIcon").addClass('fa fa-check');

            if(JsonObject['result'] == 1){
            	// $("#modalAddStationSubStation").modal('hide');
            	// $("#formAddStationSubStation")[0].reset();
            	// $("#selAddStationSubStationStationId").select2('val', '0');
            	// $("#selAddStationSubStationId").select2('val', '0');
            	dataTableStationSubStations.draw();
              	toastr.success('Station was succesfully saved!');
            }
            else if(JsonObject['result'] == 2){
            	toastr.warning('Already exists!');
            }
            else if(JsonObject['result'] == 0){
                toastr.error('Saving Station Failed!');

                if(JsonObject['error']['station_id'] === undefined){
                    $("#selAddStationSubStationStationId").removeClass('is-invalid');
                    $("#selAddStationSubStationStationId").attr('title', '');
                }
                else{
                    $("#selAddStationSubStationStationId").addClass('is-invalid');
                    $("#selAddStationSubStationStationId").attr('title', JsonObject['error']['station_id']);
                }

                if(JsonObject['error']['sub_station_id'] === undefined){
                    $("#selAddStationSubStationId").removeClass('is-invalid');
                    $("#selAddStationSubStationId").attr('title', '');
                }
                else{
                    $("#selAddStationSubStationId").addClass('is-invalid');
                    $("#selAddStationSubStationId").attr('title', JsonObject['error']['sub_station_id']);
                }
            }

        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnAddStationSubStationIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddStationSubStation").removeAttr('disabled');
            $("#iBtnAddStationSubStationIcon").addClass('fa fa-check');
        }
    });
}

// Get Station Sub Station in combobox
function GetCboStationSubStation(cboElement, status){
    let result = '<option value="0" selected disabled> -- Select Station -- </option>';
    $.ajax({
        url: 'get_station_sub_stations_by_stat',
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
            let station_sub_stations = JsonObject['station_sub_stations'];
            let unique_station_id = [];
            let unique_station_sub_stations = [];
            let used_station_id = [];

            result = '';
            if(station_sub_stations.length > 0){
                result = '<option value="0" selected disabled> -- Select Station -- </option>';
                for(let index = 0; index < station_sub_stations.length; index++){
                    if(!unique_station_id.includes(station_sub_stations[index].station_id)){
                        unique_station_id.push(station_sub_stations[index].station_id);
                    }
                    // result += '<optgroup label="' + stations[index].station.name + '">'
                    // let subStations = stations[index].sub_stations;
                    // for(let index2 = 0; index2 < subStations.length; index2++){
                    //   result += '<option value="' + subStations[index2].id + '">' + subStations[index2].name + '</option>';
                    // }
                    // result += '</optgroup>';
                }

                // for(let index = 0; index < unique_station_id.length; index++){
                //     if(!used_station_id.includes(unique_station_id[index])){
                //         used_station_id.push(unique_station_id[index]);

                //         for(let index2 = 0; index2 < station_sub_stations.length; index2++){
                //             if(!used_station_id.includes(unique_station_id[index])){
                //                 if(unique_station_id[index] == station_sub_stations[index2].station_id){
                //                     result += '<optgroup label="' + station_sub_stations[index2].station.name + '">';

                //                     for(let index3 = 0; index3 < station_sub_stations.length; index3++){
                //                         if(station_sub_stations[index2].station_id == station_sub_stations[index3].station_id){
                //                             result += '<option value="' + station_sub_stations[index3].sub_station_id + '">' + station_sub_stations[index3].sub_station.name + '</option>';
                //                         }
                //                     }
                //                 }
                //             }

                //             result += '</optgroup>';
                //         }
                //     }

                //     console.log(used_station_id);
                // }

                for(let index = 0; index < unique_station_id.length; index++) {
                    let current_unique_station_id = unique_station_id[index];

                    let get_sub_stations = [];

                    for(let index2 = 0; index2 < station_sub_stations.length; index2++) {
                        if(current_unique_station_id == station_sub_stations[index2].station_id){
                            get_sub_stations.push(station_sub_stations[index2]);
                        }
                    }

                    let station_sub_station = {
                        'station_id' : current_unique_station_id,
                        'sub_stations' : get_sub_stations
                    };

                    unique_station_sub_stations.push(station_sub_station);

                    // for(let index2 = 0; index2 < station_sub_stations.length; index2++) {
                    //     unique_station_sub_stations.push(station_sub_station);
                    // }
                    // unique_station_sub_stations
                }

                for(let index = 0; index < unique_station_sub_stations.length; index++) {
                    result += '<optgroup label="' + unique_station_sub_stations[index]['sub_stations'][0]['station'].name + '">'
                    // result += '<option>wew</option>';
                    for(let index2 = 0; index2 < unique_station_sub_stations[index]['sub_stations'].length; index2++){
                        // result += '<option value="' + unique_station_sub_stations[index]['sub_stations'][index2]['station_id'] + '--' + unique_station_sub_stations[index]['sub_stations'][index2]['sub_station_id'] + '">' + unique_station_sub_stations[index]['sub_stations'][index2]['sub_station'].name + '</option>';
                        result += '<option value="' + unique_station_sub_stations[index]['sub_stations'][index2]['id'] + '">' + unique_station_sub_stations[index]['sub_stations'][index2]['sub_station'].name + '</option>';
                    }
                    //   result += '<option value="' + subStations[index2].id + '">' + subStations[index2].name + '</option>';
                    // }
                    
                    result += '</optgroup>';
                }

                // console.log(unique_station_sub_stations.length);

                // console.log(unique_station_sub_stations);
            }
            else{
                result = '<option value="0" selected disabled> -- No record found -- </option>';
            }
            // // console.log(unique_station_id);

            // const groupBy = key => array =>
            //   array.reduce((objectsByKeyValue, obj) => {
            //     const value = obj[key];
            //     objectsByKeyValue[value] = (objectsByKeyValue[value] || []).concat(obj);
            //     return objectsByKeyValue;
            //   }, {});

            //   let groupByStation = groupBy('station_id');

            //   let jsonGroupByStation = JSON.stringify({
            //         groupByStation: groupByStation(station_sub_stations),
            //       }, null, 2);

            //   jsonGroupByStation = JSON.parse(jsonGroupByStation);

            //   console.log(jsonGroupByStation);

              // for(let index = 0; index < jsonGroupByStation.length; index++){
              //   console.log(index);
              // }

                // console.log(jsonGroupByStation);
              
              // console.log(
              //     JSON.stringify({
              //       groupByStation: groupByStation(station_sub_stations),
              //     }, null, 2)
              //   );

            cboElement.html(result);
        },
        error: function(data, xhr, status){
            result = '<option value="0" selected disabled> -- Reload Again -- </option>';
            cboElement.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function ChangeStationSubStationStatus(){
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
        url: "change_station_sub_station_stat",
        method: "post",
        data: $('#formChangeStationSubStationStat').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnChangeStationSubStationStatIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnChangeStationSubStationStat").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
                $("#modalChangeStationSubStationStat").modal('hide');
                $("#formChangeStationSubStationStat")[0].reset();

                dataTableStationSubStations.draw();

                if($("#txtChangeStationSubStationStatStationStat").val() == 1){
                    toastr.success('Station Activation Success!');
                }
                else{
                    toastr.success('Station Deactivation Success!');
                }
            }
            else{
                if($("#txtChangeStationStatStationStat").val() == 1){
                    toastr.error('Station Activation Failed!');
                }
                else{
                    toastr.error('Station Deactivation Failed!');
                }
            }

            $("#iBtnChangeStationSubStationStatIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnChangeStationSubStationStat").removeAttr('disabled');
            $("#iBtnChangeStationSubStationStatIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnChangeStationSubStationStatIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnChangeStationSubStationStat").removeAttr('disabled');
            $("#iBtnChangeStationSubStationStatIcon").addClass('fa fa-check');
        }
    });
}