<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
})->name('login');

Route::get('/test', function () {
    return view('test');
})->name('test');

Route::get('/login', function () {
    return view('index');
})->name('login');

// ADMINISTRATOR MODULE
Route::get('/change_pass_view', function () {
    return view('change_password');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/blank', function () {
    return view('blank');
})->name('blank');

Route::get('/user', function () {
    return view('user');
})->name('user');

Route::get('/machine', function () {
    return view('machine');
})->name('machine');

Route::get('/mode_of_defect', function () {
    return view('mode_of_defect');
})->name('mode_of_defect');

// Route::get('/cn_lines', function () {
//     return view('cn_lines');
// })->name('cn_lines');

Route::get('/assembly_line', function () {
    return view('assembly_line');
})->name('assembly_line');

Route::get('/device', function () {
    return view('device');
})->name('device');

Route::get('/station', function () {
    return view('station');
})->name('station');

Route::get('/materialprocess', function () {
    return view('materialprocess');
})->name('materialprocess');

Route::get('/processtimeline', function () {
    return view('processtimeline');
})->name('processtimeline');


//PRELIMINARY PACKING
Route::get('/packingoperator', function () {
    return view('packingoperator');
})->name('packingoperator');

Route::get('/packinginspector', function () {
    return view('packinginspector');
})->name('packinginspector');

//PRELIMINARY PACKING
Route::get('/shippingoperator', function () {
    return view('shippingoperator');
})->name('shippingoperator');

Route::get('/shippinginspector', function () {
    return view('shippinginspector');
})->name('shippinginspector');

// Route::get('/prod_runcard', function () {
//     return view('prod_runcard_manual');
// })->name('prod_runcard');

// Route::get('/prod_runcard_automatic', function () {
//     return view('prod_runcard_manual');
// })->name('prod_runcard_automatic');

// Route::get('/prod_runcard_manual', function () {
//     return view('prod_runcard_manual');
// })->name('prod_runcard_manual');

Route::get('/prod_runcard_new', function () {
    // return view('prod_runcard_manual');
    return view('prod_runcard_manual_v2');
})->name('prod_runcard_new');

Route::get('/warehouse', function () {
    return view('warehouse');
})->name('warehouse');

Route::get('/rapid_acdcs', function () {
    return view('rapid_acdcs');
})->name('rapid_acdcs');

Route::get('/exportshippingrecord', function () {
    return view('exportshippingrecord');
})->name('exportshippingrecord');

Route::get('/packingandshipment', function () {
    return view('packingandshipment');
})->name('packingandshipment');

//FEBRUARY 27, 2020 NEW VIEWS
Route::get('/prod_preliminary_inspection', function () {
    return view('prod_preliminary_inspection');
})->name('prod_preliminary_inspection');

Route::get('/oqc_preliminary_inspection', function () {
    return view('oqc_preliminary_inspection');
})->name('oqc_preliminary_inspection');

Route::get('/oqc_packing_inspection', function () {
    return view('oqc_packing_inspection');
})->name('oqc_packing_inspection');

Route::get('/packingseeder', function () {
    return view('packingseeder');
})->name('packingseeder');

// CN PPTS V2
// Route::get('/ppts_user', function () {
//     return view('cn_ppts_v2_1/ppts_user');
// })->name('ppts_user');

Route::get('/ppts_ppc_matrix', function () {
    return view('cn_ppts_v2_1/ppts_ppc_matrix');
})->name('ppts_ppc_matrix');

Route::get('/ppts_dropdown_maintenance', function () {
    return view('cn_ppts_v2_1/ppts_dropdown_maintenance');
})->name('ppts_dropdown_maintenance');

Route::get('/ppts_oqc_series', function () {
    return view('cn_ppts_v2_1/ppts_oqc_series');
})->name('ppts_oqc_series');

Route::get('/ppts_production_registry', function () {
    return view('cn_ppts_v2_1/ppts_production_registry');
})->name('ppts_production_registry');

// OQC MODULE
Route::get('/ppts_oqc_lot_app', function () {
    return view('cn_ppts_v2_1/ppts_oqc_lot_app');
})->name('ppts_oqc_lot_app');

Route::get('/ppts_oqc_inspection', function () {
    return view('cn_ppts_v2_1/ppts_oqc_inspection');
})->name('ppts_oqc_inspection');

// PACKING AND SHIPPING MODULE 
Route::get('/ppts_packing_and_shipping', function () {
    return view('cn_ppts_v2_1/ppts_packing_and_shipping');
})->name('ppts_packing_and_shipping');

Route::get('/ppts_packing_and_shipping_export', function () {
    return view('cn_ppts_v2_1/ppts_packing_and_shipping_export');
})->name('ppts_packing_and_shipping_export');

Route::get('/kitting', function () {
    return view('kitting');
})->name('kitting');

Route::get('/prod_pre_assy', function () {
    return view('prod_pre_assy');
})->name('prod_pre_assy');

// C3 Label
Route::get('/c3_label', function () {
    return view('c3_label');
})->name('c3_label');

// C3 Label - Huawei
Route::get('/c3_label_huawei', function () {
    return view('c3_label_huawei');
})->name('c3_label_huawei');

// ============================================================================================================ 
// ===  @@@@@@ ================================================================================================ 
// ===  @@    @@ ===  @@@@  =====    @@@@@@    ==    @@@@@@    ==   @@@@@@   ==   @@@   @@  ===  @@@@@@@@ ===== 
// ===  @@@@@@ ===  @@    @@  =    @@@    =====    @@@    =========   @@   ====   @@ @  @@  ==   @@ =====  ==== 
// ===  @@    @@ =  @@    @@  ===    @@@@    ====    @@@@    ======   @@   ====   @@  @ @@  ==   @@ @@@@@  ==== 
// ===  @@    @@ =  @@    @@  ======    @@@    =====    @@@    ====   @@   ====   @@   @@@  ==   @@ == @@  ==== 
// ===  @@@@@@ =====  @@@@  ===    @@@@@@    ==    @@@@@@    ====   @@@@@@   ==   @@    @@  ===  @@@@@@@@  ==== 
// ============================================================================================================ 
// ============================================================================================================ 

// COMMON CONTROLLER
Route::get('/generate_qrcode', 'CommonController@generate_qrcode')->name('generate_qrcode');
Route::get('/get_ppts_dropdown', 'CommonController@get_ppts_dropdown')->name('get_ppts_dropdown');


// USER CONTROLLER
Route::post('/sign_in', 'UserController@sign_in')->name('sign_in');
Route::post('/sign_out', 'UserController@sign_out')->name('sign_out');
Route::post('/change_pass', 'UserController@change_pass')->name('change_pass');
Route::post('/change_user_stat', 'UserController@change_user_stat')->name('change_user_stat');
Route::get('/view_users', 'UserController@view_users');
Route::post('/add_user', 'UserController@add_user');
Route::get('/get_user_by_id', 'UserController@get_user_by_id');
Route::get('/get_user_list', 'UserController@get_user_list');
Route::get('/get_user_by_batch', 'UserController@get_user_by_batch');
Route::get('/get_user_by_stat', 'UserController@get_user_by_stat');
Route::post('/edit_user', 'UserController@edit_user');
Route::post('/reset_password', 'UserController@reset_password');
Route::get('/generate_user_qrcode', 'UserController@generate_user_qrcode');
Route::post('/import_user', 'UserController@import_user');
Route::post('/update_certify', 'UserController@update_certify')->name('update_certify');

// MACHINE CONTROLLER
Route::post('/change_machine_stat', 'MachineController@change_machine_stat')->name('change_machine_stat');
Route::get('/view_machines', 'MachineController@view_machines');
Route::post('/add_machine', 'MachineController@add_machine');
Route::get('/get_machine_by_id', 'MachineController@get_machine_by_id');
Route::post('/edit_machine', 'MachineController@edit_machine');
Route::post('/import_machine', 'MachineController@import_machine');
Route::post('/import_machine_updater', 'MachineController@import_machine_updater');
Route::get('/get_machines', 'MachineController@get_machines');
Route::get('/get_machine_by_batch', 'MachineController@get_machine_by_batch');
Route::get('/export_machines', 'MachineController@export_machines');
Route::get('/view_eedms_general_logistics', 'MachineController@view_eedms_general_logistics');

// MODE OF DEFECT CONTROLLER
Route::post('/change_mod_stat', 'ModeOfDefectController@change_mod_stat')->name('change_mod_stat');
Route::get('/view_mods', 'ModeOfDefectController@view_mods');
Route::post('/add_mod', 'ModeOfDefectController@add_mod');
Route::get('/get_mod_by_id', 'ModeOfDefectController@get_mod_by_id');
Route::post('/edit_mod', 'ModeOfDefectController@edit_mod');
Route::post('/import_mod', 'ModeOfDefectController@import_mod');
Route::get('/get_mods', 'ModeOfDefectController@get_mods');
Route::get('/get_mod_by_batch', 'ModeOfDefectController@get_mod_by_batch');

// // CN LINE CONTROLLER
// Route::post('/change_cn_line_stat', 'CNLineController@change_cn_line_stat')->name('change_cn_line_stat');
// Route::get('/view_cn_lines', 'CNLineController@view_cn_lines');
// Route::post('/add_cn_line', 'CNLineController@add_cn_line');
// Route::get('/get_cn_line_by_id', 'CNLineController@get_cn_line_by_id');
// Route::post('/edit_cn_line', 'CNLineController@edit_cn_line');

// ASSEMBLY LINE CONTROLLER
Route::post('/change_assembly_line_stat', 'AssemblyLineController@change_assembly_line_stat')->name('change_assembly_line_stat');
Route::get('/view_assembly_lines', 'AssemblyLineController@view_assembly_lines');
Route::post('/add_assembly_line', 'AssemblyLineController@add_assembly_line');
Route::get('/get_assembly_line_by_id', 'AssemblyLineController@get_assembly_line_by_id');
Route::post('/edit_assembly_line', 'AssemblyLineController@edit_assembly_line');
Route::get('/get_assembly_lines', 'AssemblyLineController@get_assembly_lines');
Route::post('/import_assembly_line', 'AssemblyLineController@import_assembly_line');

// DEVICE CONTROLLER
Route::post('/change_device_stat', 'DeviceController@change_device_stat')->name('change_device_stat');
Route::get('/view_devices', 'DeviceController@view_devices');
Route::post('/add_device', 'DeviceController@add_device');
Route::get('/get_device_by_id', 'DeviceController@get_device_by_id');
Route::post('/edit_device', 'DeviceController@edit_device');
Route::post('/import_packing_matrix', 'DeviceController@import_packing_matrix');
Route::post('/import_packing_matrix_updater', 'DeviceController@import_packing_matrix_updater');

// STATION CONTROLLER
Route::post('/change_station_stat', 'StationController@change_station_stat')->name('change_station_stat');
Route::get('/view_stations', 'StationController@view_stations');
Route::post('/add_station', 'StationController@add_station');
Route::get('/get_station_by_id', 'StationController@get_station_by_id');
Route::post('/edit_station', 'StationController@edit_station');
Route::get('/get_station_by_stat', 'StationController@get_station_by_stat');
Route::get('/get_stations_by_stat', 'StationController@get_stations_by_stat');

Route::get('/get_station_pre_assembly', 'StationController@get_station_pre_assembly')->name('get_station_pre_assembly');

// SUB STATION CONTROLLER
Route::post('/change_sub_station_stat', 'SubStationController@change_sub_station_stat')->name('change_sub_station_stat');
// Route::get('/view_sub_stations_by_station_id', 'SubStationController@view_sub_stations_by_station_id');
Route::get('/view_sub_stations', 'SubStationController@view_sub_stations');
Route::post('/add_sub_station', 'SubStationController@add_sub_station');
Route::get('/get_sub_station_by_id', 'SubStationController@get_sub_station_by_id');
Route::post('/edit_sub_station', 'SubStationController@edit_sub_station');
Route::get('/get_sub_stations_by_stat', 'SubStationController@get_sub_stations_by_stat');
Route::get('/generate_sub_station_qrcode', 'SubStationController@generate_sub_station_qrcode');

// STATION SUB STATION CONTROLLER
Route::get('/view_station_sub_stations', 'StationSubStationController@view_station_sub_stations')->name('view_station_sub_stations');
Route::post('/add_station_sub_station', 'StationSubStationController@add_station_sub_station')->name('add_station_sub_station');
Route::get('/get_station_sub_stations_by_stat', 'StationSubStationController@get_station_sub_stations_by_stat')->name('get_station_sub_stations_by_stat');
Route::post('/change_station_sub_station_stat', 'StationSubStationController@change_station_sub_station_stat')->name('change_station_sub_station_stat');

// MATERIAL PROCESS CONTROLLER
Route::get('/view_material_process_by_device_id', 'MaterialProcessController@view_material_process_by_device_id');
Route::post('/add_material_process', 'MaterialProcessController@add_material_process');
Route::post('/edit_material_process', 'MaterialProcessController@edit_material_process');
Route::get('/get_mat_proc_by_id', 'MaterialProcessController@get_mat_proc_by_id');
Route::post('/change_material_process_stat', 'MaterialProcessController@change_material_process_stat');
Route::get('/test_material_process_sort', 'MaterialProcessController@test_material_process_sort');

// USER LEVEL CONTROLLER
Route::get('/get_user_levels', 'UserLevelController@get_user_levels');

// OQC CONTROLLER
Route::get('/oqclotapp', function () {
    return view('oqclotapp');
})->name('oqclotapp');

Route::get('/get_oqc_inspection_data_test', 'OQCLotAppController@get_oqc_inspection_data_test');
Route::get('/test_data', 'OQCLotAppController@test_data');

Route::get('/get_oqc_inspection_data', 'OQCLotAppController@get_oqc_inspection_data');
Route::get('/get_assy_lines', 'OQCLotAppController@get_assy_lines');
Route::get('/get_assy_lines', 'OQCLotAppController@get_assy_lines');
Route::get('/get_po_details', 'OQCLotAppController@get_po_details');
Route::post('/add_oqc_lot_app', 'OQCLotAppController@add_oqc_lot_app');
Route::post('/update_approved_prod', 'OQCLotAppController@update_approved_prod');
Route::post('/update_approved_oqc', 'OQCLotAppController@update_approved_oqc');
Route::get('/get_oqc_lot_app_data', 'OQCLotAppController@get_oqc_lot_app_data');
Route::get('/get_oqc_lot_app_data_summary', 'OQCLotAppController@get_oqc_lot_app_data_summary');
Route::get('/get_oqc_lot_app_details', 'OQCLotAppController@get_oqc_lot_app_details');
Route::get('/get_user_details_lotapp', 'OQCLotAppController@get_user_details_lotapp');
Route::get('/get_prod_user_details', 'OQCLotAppController@get_prod_user_details');
Route::get('/get_oqc_user_details', 'OQCLotAppController@get_oqc_user_details');
Route::get('/get_runcard_details', 'OQCLotAppController@get_runcard_details');
Route::get('/get_user_details_lotapp_arr', 'OQCLotAppController@get_user_details_lotapp_arr');
Route::get('/get_lot_app_by_id', 'OQCLotAppController@get_lot_app_by_id');
Route::get('/fn_get_lot_app_by_id', 'OQCLotAppController@fn_get_lot_app_by_id');
Route::get('/check_existing', 'OQCLotAppController@check_existing');

// OQC Visual Inspection CONTROLLER
Route::get('/oqclvisualinspection', function () {
    return view('oqclvisualinspection');
})->name('oqclvisualinspection');

Route::get('/get_oqc_lotapp_details', 'OQCVIRController@get_oqc_lotapp_details');
Route::get('/get_oqclot_details', 'OQCVIRController@get_oqclot_details');
Route::post('/add_oqc_vir', 'OQCVIRController@add_oqc_vir');
Route::get('/get_oqcvir_details', 'OQCVIRController@get_oqcvir_details');
Route::get('/get_user_details_vir', 'OQCVIRController@get_user_details_vir');
Route::get('/get_oqcvir_summary', 'OQCVIRController@get_oqcvir_summary');
Route::get('/get_oqclotapp_data', 'OQCVIRController@get_oqclotapp_data');
Route::get('/get_oqc_lotapp_data_summary', 'OQCVIRController@get_oqc_lotapp_data_summary');
Route::get('/get_insp_result_by_id', 'OQCVIRController@get_insp_result_by_id');

// Rapid ACDCS
Route::get('/get_acdcs_data', 'RapidACDCSController@get_acdcs_data');
Route::get('/get_docNo_details', 'RapidACDCSController@get_docNo_details');



// MATERIAL ISSUANCE CONTROLLER
Route::get('/materialissuance', 'MaterialIssuanceController@fn_view_material_issuance_page')->name('materialissuancealias');
Route::get('/view_batches', 'MaterialIssuanceController@fn_view_batches');
Route::get('/view_lot_numbers', 'MaterialIssuanceController@fn_view_lot_numbers');
Route::post('/insert_materialissuance', 'MaterialIssuanceController@fn_insert_materialissuance');
Route::get('/view_sakidashi_issuance', 'MaterialIssuanceController@fn_view_sakidashi_issuance');
Route::get('/view_warehouse_issuance', 'MaterialIssuanceController@fn_view_warehouse_issuance');


Route::get('/test_get_data', 'MaterialIssuanceController@test_get_data');

// PARTS PREP CONTROLLER
Route::get('/partspreparatory', 'PartsPreparatoryController@fn_view_parts_preparatory_page')->name('partspreparatoryalias');
Route::get('/select_partspreparatory_materials', 'PartsPreparatoryController@fn_view_materials');
Route::get('/view_sakidashi_parts_prep', 'PartsPreparatoryController@fn_view_sakidashi_parts_prep');
Route::get('/view_warehouse_parts_prep', 'PartsPreparatoryController@fn_view_warehouse_parts_prep');
Route::get('/select_partspreparatory_setup_stations', 'PartsPreparatoryController@fn_view_setup_stations');
Route::get('/select_partspreparatory_stations', 'PartsPreparatoryController@fn_view_partspreparatory_stations');
Route::get('/select_partspreparatory_station_details', 'PartsPreparatoryController@fn_select_partspreparatory_station_details');
Route::get('/select_partspreparatory_material_details', 'PartsPreparatoryController@fn_select_partspreparatory_material_details');
Route::post('/insert_lot_pass_fail', 'PartsPreparatoryController@fn_insert_lot_pass_fail');
Route::post('/insert_sakidashi_lot_pass_fail', 'PartsPreparatoryController@fn_insert_sakidashi_lot_pass_fail');
Route::post('/insert_warehouse_lot_pass_fail', 'PartsPreparatoryController@fn_insert_warehouse_lot_pass_fail');
Route::post('/insert_material_details', 'PartsPreparatoryController@fn_insert_material_details');
Route::post('/insert_material_details_parts_prep', 'PartsPreparatoryController@fn_insert_material_details_parts_prep');
Route::post('/insert_setup_stations', 'PartsPreparatoryController@fn_insert_setup_stations');
Route::post('/update_partsprep_station_details', 'PartsPreparatoryController@fn_update_partsprep_station_details');
Route::post('/update_material_details_secondary', 'PartsPreparatoryController@fn_update_material_details_secondary');
Route::post('/update_approval_prod', 'PartsPreparatoryController@fn_update_approval_prod');
Route::post('/update_approval_qc', 'PartsPreparatoryController@fn_update_approval_qc');
Route::get('/select_list_partsprep_station_machines', 'PartsPreparatoryController@fn_select_list_partsprep_station_machines');
Route::get('/select_list_runcard_numbers', 'PartsPreparatoryController@fn_select_list_runcard_numbers');
Route::get('/select_list_partsprep_station_mod', 'PartsPreparatoryController@fn_select_list_partsprep_station_mod');
Route::get('/select_po_details', 'PartsPreparatoryController@fn_select_po_details');

//C3LABEL
Route::get('/c3labelprinting', 'C3LabelPrintingController@fn_view_c3labelprinting')->name('c3labelprintingalias');
Route::get('/c3labelprintingtest', 'C3LabelPrintingController@fn_view_c3labelprintingtest')->name('c3labelprintingaliastest');
Route::get('/select_c3_devices', 'C3LabelPrintingController@fn_select_c3_devices');
Route::get('/select_c3_label_content_to_print', 'C3LabelPrintingController@fn_select_c3_label_content_to_print');
Route::get('/select_c3_label_content_to_print_partial', 'C3LabelPrintingController@fn_select_c3_label_content_to_print_partial');
Route::post('/insert_c3_label_history', 'C3LabelPrintingController@fn_insert_c3_label_history');
Route::get('/select_c3_label_dt', 'C3LabelPrintingController@fn_select_c3_label_dt');
Route::get('/select_c3_label_content_to_reprint', 'C3LabelPrintingController@fn_select_c3_label_content_to_reprint');
Route::get('/select_c3_label_history_details_dt', 'C3LabelPrintingController@fn_select_c3_label_history_details_dt');
Route::post('/update_c3_label_history_details_receive', 'C3LabelPrintingController@fn_update_c3_label_history_details_receive');
Route::get('/select_to_receive_dt', 'C3LabelPrintingController@fn_select_to_receive_dt');

Route::get('/c3labelprintingforbox', 'C3LabelPrintingController@fn_view_c3labelprintingforbox')->name('c3labelprintingforboxalias');
Route::get('/select_packing_code_dt', 'C3LabelPrintingController@fn_select_packing_code_dt');
Route::get('/select_c3_label_content_to_print_accessories', 'C3LabelPrintingController@fn_select_c3_label_content_to_print_accessories');
Route::get('/select_c3_label_history_details_accessories_dt', 'C3LabelPrintingController@fn_select_c3_label_history_details_accessories_dt');




Route::get('/print_c3_label', 'DLABELPrintingController@fn_print_c3_label');


// DLABEL PRINTING
Route::get('/dlabelprinting', 'DLABELPrintingController@fn_view_dlabelprinting')->name('dlabelprintingalias');
Route::get('/select_dlabel_content', 'DLABELPrintingController@fn_select_dlabel_content');
Route::get('/select_dlabel_content_to_print', 'DLABELPrintingController@fn_select_dlabel_content_to_print');
Route::post('/insert_dlabel_history', 'DLABELPrintingController@fn_insert_dlabel_history');
Route::get('/select_recent_print', 'DLABELPrintingController@fn_select_recent_print');
Route::get('/select_shipment_confirmation_dlabel', 'ShipmentConfirmationController@fn_select_shipment_confirmation_dlabel');
Route::post('/delete_dlabel_history', 'DLABELPrintingController@fn_delete_dlabel_history');

// DLABEL CHECKER
Route::get('/dlabelchecker', 'DLABELPrintingController@fn_dlabelchecker')->name('dlabelcheckeralias');
Route::post('/insert_d_label_checker_history', 'DLABELPrintingController@fn_insert_d_label_checker_history');
Route::get('/select_d_label_checker_history', 'DLABELPrintingController@fn_select_d_label_checker_history');
Route::get('/select_d_label_checker_details', 'DLABELPrintingController@fn_select_d_label_checker_details');


//PPC - SHIPMENT CONFIRMATION
Route::get('/shipmentconfirmation', 'ShipmentConfirmationController@fn_view_shipmentconfirmation')->name('shipmentconfirmationalias');
Route::post('/upload_file_shipment_confirmation', 'ShipmentConfirmationController@fn_upload_file_shipment_confirmation');
Route::post('/insert_shipment_confirmation', 'ShipmentConfirmationController@fn_insert_shipment_confirmation');
Route::post('/delete_shipment_confirmation', 'ShipmentConfirmationController@fn_delete_shipment_confirmation');
Route::get('/select_shipment_confirmation', 'ShipmentConfirmationController@fn_select_shipment_confirmation');
Route::get('/select_shipment_confirmation_details', 'ShipmentConfirmationController@fn_select_shipment_confirmation_details');
Route::post('/update_shipment_confirmation', 'ShipmentConfirmationController@fn_update_shipment_confirmation');
Route::get('/select_shipment_confirmation_history', 'ShipmentConfirmationController@fn_select_shipment_confirmation_history');


// PACKING AND SHIPPING
Route::get('/packingandshipping', 'PackingAndShippingController@fn_packingandshipping')->name('packingandshippingalias');
Route::get('/packingandshippingtest', 'PackingAndShippingController@fn_packingandshippingtest')->name('packingandshippingtestalias');
// Route::get('/check_session_expired', 'DLABELPrintingController@fn_check_session_expired');


// PRODUCTION RUNCARD CONTROLLER
Route::get('/select_prod_runcard_materials', 'ProductionRuncardController@fn_view_materials');
Route::get('/get_prod_runcard_by_po', 'ProductionRuncardController@get_prod_runcard_by_po');
Route::get('/view_sakidashi_prod', 'ProductionRuncardController@fn_view_sakidashi_prod');
Route::get('/view_emboss_prod', 'ProductionRuncardController@fn_view_emboss_prod');
Route::get('/select_prod_runcard_setup_stations', 'ProductionRuncardController@fn_view_setup_stations');
Route::get('/select_prod_runcard_stations', 'ProductionRuncardController@fn_view_prod_runcard_stations');
Route::get('/select_prod_runcard_station_details', 'ProductionRuncardController@fn_select_prod_runcard_station_details');
Route::get('/select_prod_runcard_material_details', 'ProductionRuncardController@fn_select_prod_runcard_material_details');
Route::post('/insert_prod_runcard', 'ProductionRuncardController@fn_insert_prod_runcard');
Route::post('/update_prod_runcard_secondary', 'ProductionRuncardController@fn_update_prod_runcard_secondary');
Route::post('/insert_prod_runcard_setup_stations', 'ProductionRuncardController@fn_insert_setup_stations');
Route::post('/update_prod_runcard_station_details', 'ProductionRuncardController@fn_update_prod_runcard_station_details');
Route::get('/get_prod_runcards', 'ProductionRuncardController@fn_get_prod_runcards');
Route::post('/update_prod_runcard_approval_prod', 'ProductionRuncardController@fn_update_prod_runcard_approval_prod');
Route::post('/update_prod_runcard_approval_prod', 'ProductionRuncardController@fn_update_prod_runcard_approval_prod');
Route::post('/update_prod_runcard_approval_qc', 'ProductionRuncardController@fn_update_prod_runcard_approval_qc');
Route::get('/view_warehouse_sakidashi_issuance', 'ProductionRuncardController@fn_view_warehouse_sakidashi_issuance');
Route::get('/get_wbs_material_kitting', 'ProductionRuncardController@get_wbs_material_kitting');
Route::get('/get_wbs_sakidashi_issuance', 'ProductionRuncardController@get_wbs_sakidashi_issuance');
Route::get('/get_prod_runcard_by_id', 'ProductionRuncardController@get_prod_runcard_by_id');
Route::post('/save_prod_material_list', 'ProductionRuncardController@save_prod_material_list');
Route::post('/save_prod_emboss_material_list', 'ProductionRuncardController@save_prod_emboss_material_list');

// Edit status -JD
Route::get('/edit_status', 'ProductionRuncardController@edit_status');

Route::get('/test_prod_runcard_station_step_num', 'ProductionRuncardController@test_prod_runcard_station_step_num');

Route::get('/testonly', 'PartsPreparatoryController@testonly');

// PRODUCTION RUNCARD CONTROLLER
Route::get('/select_prod_runcards_stations', 'ProductionRuncardController@select_prod_runcards_stations');

Route::get('/warehouse_view_batches', 'ProductionRuncardController@fn_warehouse_view_batches');
Route::get('/fn_get_warehouse_view_batches_by_id', 'ProductionRuncardController@fn_fn_get_warehouse_view_batches_by_id');
Route::get('/get_warehouse_sakidashi_view_batches_by_id', 'ProductionRuncardController@fn_get_warehouse_sakidashi_view_batches_by_id');
Route::get('/view_ng_summary', 'ProductionRuncardController@view_ng_summary');

// AUTOMATIC PRODUCTION RUNCARD CONTROLLER
Route::get('/view_material_kitting_by_runcard', 'ProductionRuncardController@view_material_kitting_by_runcard');
Route::get('/view_sakidashi_by_runcard', 'ProductionRuncardController@view_sakidashi_by_runcard');
Route::get('/view_emboss_by_runcard', 'ProductionRuncardController@view_emboss_by_runcard');
Route::get('/scan_material_kitting_lot_no', 'ProductionRuncardController@scan_material_kitting_lot_no');
Route::get('/scan_sakidashi_issuance_lot_no', 'ProductionRuncardController@scan_sakidashi_issuance_lot_no');
Route::get('/scan_emboss_lot_no', 'ProductionRuncardController@scan_emboss_lot_no');
Route::get('/scan_employee_no', 'ProductionRuncardController@scan_employee_no');
Route::get('/view_prod_runcard_stations', 'ProductionRuncardController@view_prod_runcard_stations');
Route::post('/edit_prod_runcard_station', 'ProductionRuncardController@edit_prod_runcard_station');
Route::get('/get_prodn_runcard_by_id', 'ProductionRuncardController@get_prodn_runcard_by_id');
Route::post('/update_prodn_runcard', 'ProductionRuncardController@update_prodn_runcard');

// MANUAL PRODUCTION RUNCARD CONTROLLER
Route::post('/save_prod_runcard_details', 'ProductionRuncardController@save_prod_runcard_details');
Route::get('/view_manual_material_kitting_by_runcard', 'ProductionRuncardController@view_manual_material_kitting_by_runcard');
Route::get('/view_manual_sakidashi_by_runcard', 'ProductionRuncardController@view_manual_sakidashi_by_runcard');
Route::get('/view_manual_emboss_by_runcard', 'ProductionRuncardController@view_manual_emboss_by_runcard');
Route::post('/save_material_kitting_lot_issuance', 'ProductionRuncardController@save_material_kitting_lot_issuance');
Route::post('/save_sakidashi_lot_issuance', 'ProductionRuncardController@save_sakidashi_lot_issuance');
Route::post('/save_emboss_lot_issuance', 'ProductionRuncardController@save_emboss_lot_issuance');

// PATS-CN RUNCARD MODIFICATION - Chris 073024
Route::get('/get_data_for_adding_process', 'ProductionRuncardController@get_data_for_adding_process');
Route::get('/get_scanned_material_details', 'ProductionRuncardController@get_scanned_material_details');
Route::post('/save_done_runcard', 'ProductionRuncardController@save_done_runcard');
Route::get('/get_process_station_details', 'ProductionRuncardController@get_process_station_details');
Route::post('/save_reel_number', 'ProductionRuncardController@save_reel_number');
Route::get('/generate_qr_code_runcard', 'ProductionRuncardController@generate_qr_code_runcard');
Route::get('/generate_qr_code_process_station', 'ProductionRuncardController@generate_qr_code_process_station');
Route::get('/generate_qr_code_process_station1', 'ProductionRuncardController@generate_qr_code_process_station1');


//OQC INSPECTOR CONTROLLER
Route::get('/oqc_view_runcards', 'OqcInspectorController@view_runcardss');




//------------------------------------------------------

Route::get('/packop_oqc_vir_details', 'PackingOperatorController@packop_oqc_vir_details');
Route::get('/packop_get_oqcvir_summary', 'PackingOperatorController@packop_get_oqcvir_summary');

//------------------------------------------------------

//PACKING OPERATOR CONTROLLER
Route::get('/packop_view_batches', 'PackingOperatorController@view_batches');
Route::post('/submit_packop', 'PackingOperatorController@submit_packop');
Route::get('/retrieve_oqc_details', 'PackingOperatorController@retrieve_oqc_details');
Route::get('/retrieve_packop_history', 'PackingOperatorController@retrieve_packop_history');
Route::get('/generate_packing_code', 'PackingOperatorController@generate_packing_code');
Route::get('/link_to_packing_code_packop', 'PackingOperatorController@link_to_packing_code_packop');


//PACKING INSPECTOR CONTROLLER
Route::get('/packin_view_batches', 'PackingInspectorController@view_batches');
Route::post('/submit_packin', 'PackingInspectorController@submit_packin');
Route::get('/retrieve_pack_code_from_packop', 'PackingInspectorController@retrieve_pack_code_from_packop');
Route::get('/link_to_packing_code_packin', 'PackingInspectorController@link_to_packing_code_packin');

Route::get('/retrieve_oqc_name', 'PackingInspectorController@retrieve_oqc_name');
Route::get('/retrieve_packin_history', 'PackingInspectorController@retrieve_packin_history');

//SHIPPING OPERATOR CONTROLLER
Route::get('/shipop_view_batches', 'ShippingOperatorController@view_batches');
Route::post('/submit_shipop', 'ShippingOperatorController@submit_shipop');
Route::get('/retrieve_shipop_history', 'ShippingOperatorController@retrieve_shipop_history');
Route::get('/link_to_packing_code_shipop', 'ShippingOperatorController@link_to_packing_code_shipop');

//SHIPPING INSPECTOR CONTROLLER
Route::get('/shipin_view_batches', 'ShippingInspectorController@view_batches');
Route::post('/submit_shipin', 'ShippingInspectorController@submit_shipin');
Route::get('/retrieve_shipin_history', 'ShippingInspectorController@retrieve_shipin_history');
Route::get('/link_to_packing_code_shipin', 'ShippingInspectorController@link_to_packing_code_shipin');

// WBS MATERIAL KITTING CONTROLLER
Route::get('/get_wbs_kitting_details', 'WBSMaterialKittingController@get_wbs_kitting_details');

// WBS SAKIDASHI CONTROLLER
Route::get('/get_wbs_sakidashi_details', 'WBSSakidashiController@get_wbs_sakidashi_details');

// Additonal Routes - Laravel Test
Route::get('/generate_reel_lot_no', 'ProductionRuncardController@generate_reel_lot_no');

//NEW CONTROLLER FOR PACKIN
Route::get('/view_packop_history_by_packing_code', 'PackingInspectorController@view_packop_history_by_packing_code');
Route::get('/view_oqcvir_history_by_packing_code', 'PackingInspectorController@view_oqcvir_history_by_packing_code');
Route::get('/view_lotapp_history_by_packing_code', 'PackingInspectorController@view_lotapp_history_by_packing_code');
Route::get('/retrieve_c3_label_checker', 'PackingInspectorController@retrieve_c3_label_checker');
Route::get('/retrieve_c3_label_details', 'PackingInspectorController@retrieve_c3_label_details');

//NEW CONTROLLER FOR SHIPOP
Route::get('/view_packin_history_by_packing_code', 'ShippingOperatorController@view_packin_history_by_packing_code');

//NEW CONTROLLER FOR SHIPIN
Route::get('/view_shipop_history_by_packing_code', 'ShippingInspectorController@view_shipop_history_by_packing_code');
Route::get('/view_shipin_history_by_packing_code', 'ShippingInspectorController@view_shipin_history_by_packing_code');

//PACKING SEEDER
Route::post('/submit_seeder', 'ShippingInspectorController@submit_seeder');

Route::get('/export_packing_and_shipment_record', 'ShippingInspectorController@export_packing_and_shipment_record');
Route::get('/export_packing_and_shipment_record_test', 'ShippingInspectorController@export_packing_and_shipment_record_test');

// Added by Anjo
Route::get('/excel_export_packing_and_shipment', 'ProductionRuncardController@excel_export_packing_and_shipment');

//PACKOP ADDITIONAL CONTROLLERS
Route::get('/packop_view_reel_lot_no','PackingOperatorController@packop_view_reel_lot_no');
Route::get('/get_reel_lot_count','PackingOperatorController@get_reel_lot_count');

Route::get('/search_device_to_export_report','ShippingInspectorController@search_device_to_export_report');

//prod prelim inspection controller
Route::get('/retrieve_reel_lot_no_from_packing_code','ProdPrelimInspectionController@retrieve_reel_lot_no_from_packing_code');
Route::get('/check_exist_pack_code','ProdPrelimInspectionController@check_exist_pack_code');
Route::get('/ppo_retrieve_r_drawing','ProdPrelimInspectionController@ppo_retrieve_r_drawing');
Route::get('/ppo_view_batches','ProdPrelimInspectionController@view_batches');
Route::get('/ppo_view_pack_code_history','ProdPrelimInspectionController@ppo_view_pack_code_history');
Route::get('/check_if_supervisor','ProdPrelimInspectionController@check_if_supervisor');
Route::post('/submit_prelim_inspection','ProdPrelimInspectionController@submit_prelim_inspection');
Route::post('/submit_prelim_inspection_supervisor','ProdPrelimInspectionController@submit_prelim_inspection_supervisor');

//oqc prelim inspection controller
Route::get('/oqc_view_batches','OQCPrelimInspectionController@view_batches');
Route::get('/oqc_view_history','OQCPrelimInspectionController@view_history');
Route::get('/oqc_check_exist_pack_code','OQCPrelimInspectionController@oqc_check_exist_pack_code');
Route::get('/oqc_retrieve_data_from_pack_code','OQCPrelimInspectionController@oqc_retrieve_data_from_pack_code');
Route::get('/oqc_check_if_supervisor','OQCPrelimInspectionController@oqc_check_if_supervisor');
Route::post('/submit_oqc_inspection','OQCPrelimInspectionController@submit_oqc_inspection');
Route::post('/submit_shipping_details','OQCPrelimInspectionController@submit_shipping_details');
Route::post('/submit_oqc_inspection_supervisor','OQCPrelimInspectionController@submit_oqc_inspection_supervisor');

//- 03042020
Route::get('/exportpartslotrecord', function () {
    return view('exportpartslotrecord');
})->name('exportpartslotrecord');

//- Packing List
Route::get('/packinglist', function () {
    return view('packinglist');
})->name('packinglist');

Route::get('/export_parts_lot_record', 'OQCLotAppController@export_parts_lot_record');
Route::get('/export_parts_lot_record1', 'OQCLotAppController@export_parts_lot_record1');


//NEW OQC PACKING INSPECTION REVISED FUNCTIONS
Route::get('/check_pack_code_exists', 'OQCPackingInspectionController@check_pack_code_exists');
Route::get('/view_packing_codes', 'OQCPackingInspectionController@view_packing_codes');
Route::get('/view_inspection_history', 'OQCPackingInspectionController@view_inspection_history');
Route::get('/retrieve_packcode_data', 'OQCPackingInspectionController@retrieve_packcode_data');
Route::post('/submit_initial_inspection', 'OQCPackingInspectionController@submit_initial_inspection');
Route::post('/submit_inspection_fillin', 'OQCPackingInspectionController@submit_inspection_fillin');
Route::post('/submit_shipping_details', 'OQCPackingInspectionController@submit_shipping_details');

//NEW OQCVIR FUNCTIONS
Route::get('/oqc_generate_packing_code', 'OQCVIRController@oqc_generate_packing_code');


//PACKING LIST FUNCTIONS
Route::get('/view_packing_list', 'PackingListController@view_packing_list');
Route::get('/view_packing_list_details', 'PackingListController@view_packing_list_details');


//OQC VIR NEw Functions
Route::get('/view_packing_code_details','OQCVIRController@view_packing_code_details');
Route::post('/submit_change_pack_code','OQCVIRController@submit_change_pack_code');
Route::post('/assign_partial_packing_code','OQCVIRController@assign_partial_packing_code');

Route::get('/check_partial_reel_lot','OQCVIRController@check_partial_reel_lot');
Route::get('/load_latest_packing_code','OQCVIRController@load_latest_packing_code');

Route::get('/get_partial_lot_user','OQCVIRController@get_partial_lot_user');

Route::get('/iframe_request','CommonController@iframe_request');

// Kitting Controller
Route::post('/save_sub_kitting', 'KittingController@save_sub_kitting');
Route::get('/get_kitting_info_by_issuance_no', 'KittingController@get_kitting_info_by_issuance_no');
Route::get('/get_issuance_details_by_issuance_no', 'KittingController@get_issuance_details_by_issuance_no');
Route::get('/get_kitting_details_by_issuance_no', 'KittingController@get_kitting_details_by_issuance_no');
Route::get('/get_sub_kits_by_detail_id', 'KittingController@get_sub_kits_by_detail_id');
Route::get('/generate_qr_code', 'KittingController@generate_qr_code');

Route::get('/view_sub_kitting_by_kitting_id', 'KittingController@view_sub_kitting_by_kitting_id');
Route::get('/generate_sub_kit_qrcode', 'KittingController@generate_sub_kit_qrcode');

//PRODUCTION PRE ASSEMBLY
Route::get('/dt_get_pre_assy', 'ProdPreAssemblyController@dt_get_pre_assy')->name('dt_get_pre_assy');
Route::get('/get_user_by_emp_id', 'CommonController@get_user_by_emp_id')->name('get_user_by_emp_id');
Route::post('/save_pre_assy_details', 'ProdPreAssemblyController@save_pre_assy_details')->name('save_pre_assy_details');
Route::get('/get_pre_assy_details_by_id', 'ProdPreAssemblyController@get_pre_assy_details_by_id')->name('get_pre_assy_details_by_id');
Route::get('/dt_get_pre_assy_station', 'ProdPreAssemblyController@dt_get_pre_assy_station')->name('dt_get_pre_assy_station');
Route::get('/get_data_for_adding_process_pre_assy', 'ProdPreAssemblyController@get_data_for_adding_process_pre_assy')->name('get_data_for_adding_process_pre_assy');
Route::post('/save_pre_assembly_station', 'ProdPreAssemblyController@save_pre_assembly_station')->name('save_pre_assembly_station');
Route::get('/get_pre_assy_station_details_by_id', 'ProdPreAssemblyController@get_pre_assy_station_details_by_id')->name('get_pre_assy_station_details_by_id');
Route::get('/dt_get_pre_assy_materials', 'ProdPreAssemblyController@dt_get_pre_assy_materials')->name('dt_get_pre_assy_materials');
Route::post('/done_pre_assy', 'ProdPreAssemblyController@done_pre_assy')->name('done_pre_assy');
Route::get('/generate_qr_code_pre_assy', 'ProdPreAssemblyController@generate_qr_code_pre_assy')->name('generate_qr_code_pre_assy');
Route::post('/change_print_status', 'ProdPreAssemblyController@change_print_status')->name('change_print_status');

Route::get('/get_ppts_sgc_drawing', 'CommonController@get_ppts_sgc_drawing')->name('get_ppts_sgc_drawing');
Route::get('/get_user_details', 'CommonController@get_user_details')->name('get_user_details');