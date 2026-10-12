<?php

namespace App\Http\Controllers;

use App\User;
use App\Model\Device;
use App\Model\MaterialProcess;
use App\Model\StationSubStation;
use App\Model\Station;
use App\Model\SubStation;
use Illuminate\Http\Request;
use App\Model\MaterialIssuance;
use App\Model\MaterialIssuanceSubSystem;//tbl_wbs_material_kitting
use App\Model\MaterialIssuanceDetails;
use App\Model\WBSKitIssuance;
use App\Model\PartsPrep;
use App\Model\PartsPrepStation;
use App\Model\Machine;
use App\Model\WBSSakidashiIssuance;
use App\Model\WBSSakidashiIssuanceItem;
use App\Model\WBSWarehouseMatIssuanceDetails;
use App\Model\ModeOfDefect;
use App\Model\RapidActiveDocs;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Auth;

use DataTables;

define('TIMEZONE', 'Asia/Manila');
date_default_timezone_set(TIMEZONE);

class PartsPreparatoryController extends Controller
{
    public function return_result($icon_i,$title_i,$body_i,$i){
        //result 1 = ok, result 0 = error, result 2 = invalid user
        $icon = array();
        $icon[0] = '<i class="fa fa-times-circle text-danger"></i>';
        $icon[1] = '<i class="fa fa-check-circle text-success"></i>';
        $icon[2] = '<i class="fa fa-exclamation-triangle text-warning"></i>';

        $title = array();
        $title[0] = 'Not saved';
        $title[1] = 'Saved';
        $title[2] = 'Invalid Employee No.';

        $body = array();
        $body[0] = 'An error occured. Record not saved.';
        $body[1] = 'Record has been saved.';
        $body[2] = 'Record not saved.';

        $body_str = is_numeric($body_i)?$body[$body_i]:$body_i;

        $return = array();
        $return['icon'] = $icon[$icon_i];
        $return['title'] = $title[$title_i];
        $return['body'] = $body_str;
        $return['i'] = $i;
        return $return;
    }

	public function fn_view_parts_preparatory_page(){


// $servername = "localhost";
// $username = "root";
// $password = "r@p1d0nl1n3";
// $dbname = "db_cnpts";

// // Create connection
// $conn = new mysql($servername, $username, $password, $dbname);
// // Check connection
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }


		return view('partspreparatory');
	}


    public function fn_select_po_details(Request $request){
        $lot_numbers = WBSKitIssuance::
            with('kit_issuance')
            ->where('po','=',$request['po_number'])
            ->get()->take(1);

        if(!$lot_numbers->count()){
            $lot_numbers = WBSSakidashiIssuance::
                where('po_no','=',$request['po_number'])
                ->get()->take(1);

            if($lot_numbers->count()){
                $lot_numbers['wbs_table'] = 2;
            }
            return $lot_numbers;
        }
        else{
            $lot_numbers['wbs_table'] = 1;
            return $lot_numbers;
        }



    }

	public function fn_view_materials(Request $request){
        $parts_preps_collect = PartsPrep::where([
                [
                    'wbs_table','=',
                    1
                ],
                [
                    'deleted_at','=',
                    null
                ],
            ])
            ->get();

        $lot_numbers = WBSKitIssuance::
            with('kit_issuance')
            ->where('po','=',$request['po_number'])
            ->where('issue_no','=',$request['txt_scan_transfer_slip'])
            ->get();

        return DataTables::of($lot_numbers)
            ->addColumn('raw_action', function($lot_number) use ($parts_preps_collect){
                $disabled = 'disabled';
                $title = '';
                $icon_partsprep = '';
                //---
                $devices = Device::where('barcode',$lot_number['kit_issuance']->device_code)->get();
                if(!$devices->count()){
                    $devices = array();
                    $devices[0]['id'] = 0;
                }

                $stations = MaterialProcess::with([
                    'station_sub_station',
                    'station_sub_station.station',
                    'station_sub_station.sub_station',
                    'material_details'
                    ])
                    ->where('status', 1)
                    ->where('device_id', $devices[0]['id'])
                    ->whereHas('station_sub_station.station',
                       function($query){
                            $query->where('status', 1);
                            $query->where('station_type', 1);
                        },
                    )
                    ->whereHas('station_sub_station.sub_station',
                       function($query){
                            $query->where('status', 1);
                        },
                    )
                    ->whereHas('material_details',
                       function($query) use ($lot_number){
                            $query->where('item', $lot_number->item)
                            ->where('item_desc', $lot_number->item_desc)
                            ->where('status', 1);
                        },
                    )
                    ->get();

                if ( $stations->count() ) {
                    $icon_partsprep = '<i title="Has partsprep" class="fa fa-wrench text-secondary pl-2 float-right"></i>';
                }
                //---
                $parts_preps = collect($parts_preps_collect)->where('wbs_kit_issuance_id', $lot_number->id)->flatten(1);
                if ( $parts_preps->count() ) {
                    // $icon_partsprep = ($parts_preps[0]->with_partsprep)?'<i title="Has partsprep" class="fa fa-wrench text-secondary pl-2"></i>':'';
                    // if( $parts_preps[0]->status == 1 || $parts_preps[0]->status > 2 ){
                    if( $parts_preps[0]->with_partsprep != 0 ){
                        $disabled = '';
                    }
                }


                $result='<button '.$disabled.' style="width:30px;" title="Open details" class="btn_material_action btn_material_open_details btn btn-info btn-sm py-0"><i class="fa fa-info-circle fa-sm"></i></button>';
                $result.=' '.$icon_partsprep;
                $result.=' <input type="hidden" class="col_material_id" value="'.$lot_number->id.'">';
                $result.=' <input type="hidden" class="col_material_code" value="'.$lot_number->item.'">';
                $result.=' <input type="hidden" class="col_device_code" value="'.$lot_number['kit_issuance']->device_code.'">';

                return $result;
            })
            ->addColumn('raw_status', function($lot_number) use ($parts_preps_collect){
                $result = '<span data-sorter="0" class="badge badge-secondary font-weight-normal">To receive</span>';
                $parts_preps = collect($parts_preps_collect)->where('wbs_kit_issuance_id', $lot_number->id)->flatten(1);
                if ( $parts_preps->count() ) {
                    switch ( $parts_preps[0]->status ) {
                        case 1:
                            $result = '<span data-sorter="6" class="badge badge-success font-weight-normal">Done: Received</span>';
                            if( $parts_preps[0]->with_partsprep ){
                                $result = '<span data-sorter="2" class="badge badge-secondary font-weight-normal">Received: For MH Fill-in</span>';
                            }
                            break;
                        case 2:
                            $result = '<span data-sorter="1" class="badge badge-warning font-weight-normal">Returned to WHS</span>';
                            break;
                        case 3:
                            $result = '<span data-sorter="3" class="badge badge-secondary font-weight-normal">For Parts Prep Fill-in</span>';
                            break;
                        case 4:
                            $result = '<span data-sorter="4" class="badge badge-secondary font-weight-normal">Ongoing in stations/for verification</span>';
                            break;
                        case 5:
                            $result = '<span data-sorter="7" class="badge badge-success font-weight-normal">Done: Verified</span>';
                            break;
                        case 6:
                            $result = '<span data-sorter="5" class="badge badge-secondary font-weight-normal">For checking</span>';
                            break;
                        case 7:
                            $result = '<span data-sorter="8" class="badge badge-success font-weight-normal">Done: Approved</span>';
                            break;
                        
                        default:
                            # code...
                            break;
                    }
                }

                return $result;
            })
            ->addColumn('raw_usage', function($lot_number){
                $result = '---';
                $material_kitting_details = MaterialIssuanceDetails::where('issue_no', $lot_number->issue_no )->where('item', $lot_number->item )->take(1)->get();
                if ( $material_kitting_details->count() ) {
                    $result = $material_kitting_details[0]->usage;
                }

                return $result;
            })
            ->addColumn('raw_rqd_qty', function($lot_number){
                $result = 0;
                $material_kitting_details = MaterialIssuanceDetails::where('issue_no', $lot_number->issue_no )->where('item', $lot_number->item )->take(1)->get();
                if ( $material_kitting_details->count() ) {
                    $result = $material_kitting_details[0]->usage;
                }
                $result = $lot_number['kit_issuance']->po_qty*$result;
                return $result;
            })
            ->rawColumns(['raw_action','raw_status'])
            ->make(true);
	}

    public function fn_view_sakidashi_parts_prep(Request $request){
        $parts_preps_collect = PartsPrep::where([
                [
                    'wbs_table','=',
                    2
                ],
                [
                    'deleted_at','=',
                    null
                ],
            ])
            ->get();
        $tbl_wbs_sakidashi_issuance = WBSSakidashiIssuance::with('tbl_wbs_sakidashi_issuance_item')
            ->where('po_no',$request['po_number'])
            ->whereHas('tbl_wbs_sakidashi_issuance_item',
               function($query) use ($request){
                    $query->where('lot_no', $request->txt_scan_sakidashi_lot);
                },
            )
            ->get();

        return DataTables::of($tbl_wbs_sakidashi_issuance)
            ->addColumn('status', function($tbl_wbs_sakidashi_issuance) use ($parts_preps_collect){
                $result = '<span data-sorter="0" class="badge badge-secondary font-weight-normal">To receive</span>';
                $parts_preps = collect($parts_preps_collect)->where('wbs_kit_issuance_id', $tbl_wbs_sakidashi_issuance->id)->flatten(1);
                if ( $parts_preps->count() ) {
                    switch ( $parts_preps[0]->status ) {
                        case 1:
                            $result = '<span data-sorter="6" class="badge badge-success font-weight-normal">Done: Received</span>';
                            if( $parts_preps[0]->with_partsprep ){
                                $result = '<span data-sorter="2" class="badge badge-secondary font-weight-normal">Received: For MH Fill-in</span>';
                            }
                            break;
                        case 2:
                            $result = '<span data-sorter="1" class="badge badge-warning font-weight-normal">Returned to WHS</span>';
                            break;
                        case 3:
                            $result = '<span data-sorter="3" class="badge badge-secondary font-weight-normal">For Parts Prep Fill-in</span>';
                            break;
                        case 4:
                            $result = '<span data-sorter="4" class="badge badge-secondary font-weight-normal">Ongoing in stations/for verification</span>';
                            break;
                        case 5:
                            $result = '<span data-sorter="7" class="badge badge-success font-weight-normal">Done: Verified</span>';
                            break;
                        case 6:
                            $result = '<span data-sorter="5" class="badge badge-secondary font-weight-normal">For checking</span>';
                            break;
                        case 7:
                            $result = '<span data-sorter="8" class="badge badge-success font-weight-normal">Done: Approved</span>';
                            break;
                        
                        default:
                            # code...
                            break;
                    }
                }

                return $result;
            })
            ->addColumn('action', function($tbl_wbs_sakidashi_issuance) use ($parts_preps_collect){
                $disabled = 'disabled';
                $title = '';
                $icon_partsprep = '';

                //---
                $devices = Device::where('barcode',$tbl_wbs_sakidashi_issuance->device_code)->get();
                if(!$devices->count()){
                    $devices = array();
                    $devices[0]['id'] = 0;
                }

                $stations = MaterialProcess::with([
                    'station_sub_station',
                    'station_sub_station.station',
                    'station_sub_station.sub_station',
                    'material_details'
                    ])
                    ->where('status', 1)
                    ->where('device_id', $devices[0]['id'])
                    ->whereHas('station_sub_station.station',
                       function($query){
                            $query->where('status', 1);
                            $query->where('station_type', 1);
                        },
                    )
                    ->whereHas('station_sub_station.sub_station',
                       function($query){
                            $query->where('status', 1);
                        },
                    )
                    ->whereHas('material_details',
                       function($query) use ($tbl_wbs_sakidashi_issuance){
                            $query->where('item', $tbl_wbs_sakidashi_issuance->tbl_wbs_sakidashi_issuance_item->item)
                            ->where('item_desc', $tbl_wbs_sakidashi_issuance->tbl_wbs_sakidashi_issuance_item->item_desc)
                            ->where('status', 1);
                        },
                    )
                    ->get();

                if ( $stations->count() ) {
                    $icon_partsprep = '<i title="Has partsprep" class="fa fa-wrench text-secondary pl-2 float-right"></i>';
                }
                //---
                $parts_preps = collect($parts_preps_collect)->where('wbs_kit_issuance_id', $tbl_wbs_sakidashi_issuance->id)->flatten(1);
                if ( $parts_preps->count() ) {
                    // $icon_partsprep = ($parts_preps[0]->with_partsprep)?'<i title="Has partsprep" class="fa fa-wrench text-secondary pl-2"></i>':'';
                    if( $parts_preps[0]->status == 1 || $parts_preps[0]->status > 2 ){
                        $disabled = '';
                    }
                }

                $result='<button '.$disabled.' style="width:30px;" title="Open details" class="btn_material_action btn_sakidashi_material_open_details btn btn-info btn-sm py-0" id="'.$parts_preps[0]->id.'"><i class="fa fa-info-circle fa-sm"></i></button>';
                $result.=$icon_partsprep;
                $result.=' <input type="hidden" class="col_material_id" value="'.$tbl_wbs_sakidashi_issuance->id.'">';
                // $result.=' <input type="hidden" class="col_material_code" value="'.$tbl_wbs_sakidashi_issuance->item.'">';
                $result.=' <input type="hidden" class="col_device_code" value="'.$tbl_wbs_sakidashi_issuance->device_code.'">';

                return $result;
            })
            ->rawColumns(['status','action'])
            ->make(true);
    }

    public function fn_view_warehouse_parts_prep(Request $request){
        $parts_preps_collect = PartsPrep::where([
                [
                    'wbs_table','=',
                    3
                ],
                [
                    'deleted_at','=',
                    null
                ],
            ])
            ->get();

        $tbl_wbs_warehouse_mat_issuance_details = WBSWarehouseMatIssuanceDetails::with(
            'tbl_request_summary',
            'tbl_request_detail')
        ->whereHas('tbl_request_summary',function($query) use ($request){
            $query->where('pono',$request['po_number']);
        })
        ->get();

        return DataTables::of($tbl_wbs_warehouse_mat_issuance_details)
            ->addColumn('status', function($tbl_wbs_warehouse_mat_issuance_details) use ($parts_preps_collect){
                $result = '<span data-sorter="0" class="badge badge-secondary font-weight-normal">To receive</span>';
                $parts_preps = collect($parts_preps_collect)->where('wbs_kit_issuance_id', $tbl_wbs_warehouse_mat_issuance_details->id)->flatten(1);

                if ( $parts_preps->count() ) {
                    switch ( $parts_preps[0]->status ) {
                        case 1:
                            $result = '<span data-sorter="6" class="badge badge-success font-weight-normal">Done: Received</span>';
                            if( $parts_preps[0]->with_partsprep ){
                                $result = '<span data-sorter="2" class="badge badge-secondary font-weight-normal">Received: For MH Fill-in</span>';
                            }
                            break;
                        case 2:
                            $result = '<span data-sorter="1" class="badge badge-warning font-weight-normal">Returned to WHS</span>';
                            break;
                        case 3:
                            $result = '<span data-sorter="3" class="badge badge-secondary font-weight-normal">For Parts Prep Fill-in</span>';
                            break;
                        case 4:
                            $result = '<span data-sorter="4" class="badge badge-secondary font-weight-normal">Ongoing in stations/for verification</span>';
                            break;
                        case 5:
                            $result = '<span data-sorter="7" class="badge badge-success font-weight-normal">Done: Verified</span>';
                            break;
                        case 6:
                            $result = '<span data-sorter="5" class="badge badge-secondary font-weight-normal">For checking</span>';
                            break;
                        case 7:
                            $result = '<span data-sorter="8" class="badge badge-success font-weight-normal">Done: Approved</span>';
                            break;
                        
                        default:
                            # code...
                            break;
                    }
                }

                return $result;
            })
            ->addColumn('action', function($tbl_wbs_warehouse_mat_issuance_details) use ($request, $parts_preps_collect){
                $disabled = 'disabled';
                $title = '';
                $icon_partsprep = '';

                //---
                $devices = Device::where('barcode',$request->device_code)->get();
                if(!$devices->count()){
                    $devices = array();
                    $devices[0]['id'] = 0;
                }

                $stations = MaterialProcess::with([
                    'station_sub_station',
                    'station_sub_station.station',
                    'station_sub_station.sub_station',
                    'material_details'
                    ])
                    ->where('status', 1)
                    ->where('device_id', $devices[0]['id'])
                    ->whereHas('station_sub_station.station',
                       function($query){
                            $query->where('status', 1);
                            $query->where('station_type', 1);
                        },
                    )
                    ->whereHas('station_sub_station.sub_station',
                       function($query){
                            $query->where('status', 1);
                        },
                    )
                    ->whereHas('material_details',
                       function($query) use ($tbl_wbs_warehouse_mat_issuance_details){
                            $query->where('item', $tbl_wbs_warehouse_mat_issuance_details->item)
                            ->where('item_desc', $tbl_wbs_warehouse_mat_issuance_details->item_desc)
                            ->where('status', 1);
                        },
                    )
                    ->get();

                if ( $stations->count() ) {
                    $icon_partsprep = '<i title="Has partsprep" class="fa fa-wrench text-secondary pl-2 float-right"></i>';
                }
                //---
                $parts_preps = collect($parts_preps_collect)->where('wbs_kit_issuance_id', $tbl_wbs_warehouse_mat_issuance_details->id)->flatten(1);
                if ( $parts_preps->count() ) {
                    // $icon_partsprep = ($parts_preps[0]->with_partsprep)?'<i title="Has partsprep" class="fa fa-wrench text-secondary pl-2"></i>':'';
                    if( $parts_preps[0]->status == 1 || $parts_preps[0]->status > 2 ){
                        $disabled = '';
                    }
                }

                $result='<button '.$disabled.' style="width:30px;" title="Open details" class="btn_material_action btn_warehouse_material_open_details btn btn-info btn-sm py-0"><i class="fa fa-info-circle fa-sm"></i></button>';
                $result.=$icon_partsprep;
                $result.=' <input type="hidden" class="col_material_id" value="'.$tbl_wbs_warehouse_mat_issuance_details->id.'">';
                // $result.=' <input type="hidden" class="col_material_code" value="'.$tbl_wbs_sakidashi_issuance->item.'">';
                $result.=' <input type="hidden" class="col_device_code" value="'.$request->device_code.'">';

                return $result;
            })
            ->rawColumns(['status','action'])
            ->make(true);
    }

	public function fn_view_setup_stations(Request $request){
        $parts_prep_id_query    = $request->parts_prep_id_query;
        $item                   = $request->item;
        $item_desc              = $request->item_desc;


        // $devices = Device::where('barcode',$request->device_code)->get();

        // if(!$devices->count()){
        //     $devices = array();
        //     $devices[0]['id'] = 0;
        // }

        // $stations = MaterialProcess::with([
        //     'station_sub_station',
        //     'station_sub_station.station',
        //     'station_sub_station.sub_station'
        //     ])
        //     ->where('status', 1)
        //     ->where('device_id', $devices[0]['id'])
        //     ->whereHas('station_sub_station.station',
        //        function($query){
        //             $query->where('status', 1);
        //             $query->where('station_type', 1);
        //         },
        //     )
        //     ->whereHas('station_sub_station.sub_station',
        //        function($query){
        //             $query->where('status', 1);
        //         },
        //     )
        //     ->get();


        //---
        $devices = Device::where('barcode',$request->device_code)->get();
        if(!$devices->count()){
            $devices = array();
            $devices[0]['id'] = 0;
        }
        $stations = MaterialProcess::with([
            'station_sub_station',
            'station_sub_station.station',
            'station_sub_station.sub_station',
            'material_details'
            ])
            ->where('status', 1)
            ->where('device_id', $devices[0]['id'])
            ->whereHas('station_sub_station.station',
               function($query){
                    $query->where('status', 1);
                    $query->where('name', 'LIKE', '%parts prep%');
                },
            )
            ->whereHas('station_sub_station.sub_station',
               function($query){
                    $query->where('status', 1);
                },
            )
            ->whereHas('material_details',
               function($query) use ($item,$item_desc){
                    $query->where('item', $item)
                    ->where('item_desc', $item_desc)
                    ->where('status', 1);
                },
            )
            ->get();


        //---
        return DataTables::of($stations)
            ->addColumn('raw_action', function($station) use ($parts_prep_id_query){
            	$disabled = '';
                $result = '';

                $station_id = ( $station['station_sub_station']['station'] )?$station['station_sub_station']['station']->id:'';
                $sub_station_id = ( $station['station_sub_station']['sub_station'] )?$station['station_sub_station']['sub_station']->id:'';

                $partsprepstation = PartsPrepStation::
                    where('parts_prep_id', $parts_prep_id_query )
                    ->where('station_id', $station_id )
                    ->where('sub_station_id', $sub_station_id )
                    ->get();

                if ( $partsprepstation->count() ) {
                    $disabled = 'disabled';
                }

                // $result='<button '.$disabled.' style="width:30px;" title="Edit" class="btn_material_action btn_edit_station btn btn-info btn-sm py-0"><i class="fa fa-edit"></i></button>';
                $result='<input type="checkbox" '.$disabled.' class="ckb_station">';
                $result.='<input type="hidden" class="col_station_step" value="'.$station->step.'">';
                $result.='<input type="hidden" class="col_station_id" value="'.$station_id.'">';
                $result.='<input type="hidden" class="col_sub_station_id" value="'.$sub_station_id.'">';
                return $result;
            })
            ->addColumn('raw_status', function($station){
                $result = "---";
                return $result;
            })
            ->addColumn('raw_date', function($station){
                $result = "---";
                return $result;
            })
            ->addColumn('raw_operator', function($station){
                $result = "---";
                return $result;
            })
            ->addColumn('raw_input', function($station){
                $result = "---";
                return $result;
            })
            ->addColumn('raw_output', function($station){
                $result = "---";
                return $result;
            })
            ->addColumn('raw_ng_qty', function($station){
                $result = "---";
                return $result;
            })
            ->addColumn('raw_mod', function($station){
                $result = "---";
                return $result;
            })
            ->rawColumns(['raw_action'])
            ->make(true);
	}

    public function fn_view_partspreparatory_stations(Request $request){
        $enable_edit = $request->enable_edit;

        $partsprepstations = PartsPrepStation::with([
                'station' =>
                function($query){
                    $query->where('status',1);
                },
                'sub_station' =>
                function($query){
                    $query->where('status',1);
                },
            ])
            ->where('parts_prep_id', $request->parts_prep_id_query )
            ->get();
        return DataTables::of($partsprepstations)
            ->addColumn('raw_action', function($partsprepstation) use ($enable_edit){
                $disabled = 'disabled';
                $user_position = Auth::user()->position;
                $position_array = [4,1,0];
                // if( $user_position == 4 ){
                if( in_array($user_position,$position_array) ){
                    // dd('if');
                    $disabled = '';
                }

                $result='<button '.$disabled.' style="width:30px;" title="Edit" class="btn_material_action btn_edit_partsprep_station btn btn-info btn-sm py-0"><i class="fa fa-edit"></i></button>';
                $result.='<input type="hidden" class="col_partsprep_station_id" value="'.$partsprepstation->id.'">';
                return $result;
            })
            ->addColumn('raw_machine', function($partsprepstation){
                $result = '';
                $machines = Machine::where('id', $partsprepstation->machine_id )->get();
                if ( $machines->count() ) {
                    $result = $machines[0]->name;
                }
                return $result;
            })
            ->addColumn('raw_qty_ng', function($partsprepstation){
                $result = 0;
                if( $partsprepstation->qty_ng ){
                    $qty_ng_arr = explode(',', $partsprepstation->qty_ng);
                    foreach ($qty_ng_arr as $key => $value) {
                        $result+=$value;
                    }
                }
                return $result;
            })
            ->addColumn('raw_mod', function($partsprepstation){
                $result = '';
                if( $partsprepstation->mod ){
                    $qty_ng_arr = explode(',', $partsprepstation->mod);
                    foreach ($qty_ng_arr as $key => $value) {
                        $mode_of_defects = ModeOfDefect::
                            where(
                                [
                                    [
                                        'id','=',
                                        $value
                                    ],
                                    [
                                        'status','=',
                                        1
                                    ],
                                ]
                            )
                            ->get();
                        if(!$mode_of_defects->isEmpty()){
                            $result.='*'.$mode_of_defects[0]->name.'<br>';
                        }
                    }
                }



                return $result;
            })
            ->addColumn('raw_created_by', function($partsprepstation){
                $result = '';
                $users = User::where('id', $partsprepstation->created_by )->get();
                if ( $users->count() ) {
                    $result = $users[0]->name;
                }
                return $result;
            })
            ->rawColumns(['raw_action','raw_mod'])
            ->make(true);
    }

    public function fn_select_partspreparatory_material_details(Request $request){
        switch ($request['wbs_table']) {
            case 1://kit

                $lot_number = WBSKitIssuance::with(['kit_issuance'])->where('id','=',$request['material_id'])
                    ->get();
                if($lot_number->isEmpty()){
                    $lot_number = array();
                }
                else{
                    //---
                    $lot_number[0]['drawing_no'] = NULL;
                    $lot_number[0]['drawing_no_revision_no'] = NULL;
                    $lot_number[0]['sgc_drawing_no'] = NULL;
                    $lot_number[0]['sgc_drawing_no_revision_no'] = NULL;

                    $lot_number_substr              = substr($lot_number[0]->item_desc, 0, strrpos($lot_number[0]->item_desc, ' ('));
                    $doc_details_query = RapidActiveDocs::where('doc_title', 'LIKE', '%' . $lot_number_substr . '%')
                        ->get();
                    $b_drawing = collect($doc_details_query)->where('doc_type', 'B Drawing')->flatten(1);
                    if( count($b_drawing) ){
                        $lot_number[0]['drawing_no'] = $b_drawing[0]->doc_no;
                        $lot_number[0]['drawing_no_revision_no'] = $b_drawing[0]->rev_no;
                    }

                    $device_name_query_substr       = substr($request->device_name_query, 0, strpos($request->device_name_query, '-', strpos($request->device_name_query, '-')+1));
                    $doc_details_query = RapidActiveDocs::where('doc_title', 'LIKE', '%' . $device_name_query_substr . '%')
                        ->get();
                        $lot_number[0]['device_name_query_substr'] = $device_name_query_substr;
                    $sgc_drawing = collect($doc_details_query)->where('doc_type', 'SGC Drawing')->flatten(1);;
                    if( count($sgc_drawing) ){
                        $lot_number[0]['sgc_drawing_no'] = $sgc_drawing[0]->doc_no;
                        $lot_number[0]['sgc_drawing_no_revision_no'] = $sgc_drawing[0]->rev_no;
                    }
                    //---
                    $parts_preps = PartsPrep::where([
                            [
                                'wbs_kit_issuance_id','=',
                                $lot_number[0]->id
                            ],
                            [
                                'wbs_table','=',
                                1
                            ],
                            [
                                'deleted_at','=',
                                null
                            ]
                        ])
                        ->get();

                    if($parts_preps->isEmpty()){
                        $lot_number[0]['parts_preps'] = array();
                    }
                    else{

                        $lot_number[0]['parts_preps'] = $parts_preps;
                        $lot_number[0]['parts_preps'][0]['raw_checked_by_prod'] = '';

                        $users = User::where('id', $parts_preps[0]->checked_by_prod )->get();
                        if ( $users->count() ) {
                            $result = $users[0]->name;
                            $lot_number[0]['parts_preps'][0]['raw_checked_by_prod'] = $users[0]->name;;
                        }
                        //-----
                        $lot_number[0]['user_position'] = Auth::user()->position;
                        //-----
                    }
                }
                return $lot_number;

                break;
            case 2://sakidashi

                $lot_number = WBSSakidashiIssuance::with(['tbl_wbs_sakidashi_issuance_item'])->where('id','=',$request['material_id'])
                    ->get();
                if($lot_number->isEmpty()){
                    $lot_number = array();
                }
                else{
                    //---
                    $lot_number[0]['drawing_no'] = NULL;
                    $lot_number[0]['drawing_no_revision_no'] = NULL;
                    $lot_number[0]['sgc_drawing_no'] = NULL;
                    $lot_number[0]['sgc_drawing_no_revision_no'] = NULL;

                    $lot_number_substr              = substr($lot_number[0]->tbl_wbs_sakidashi_issuance_item->item_desc, 0, strrpos($lot_number[0]->tbl_wbs_sakidashi_issuance_item->item_desc, ' ('));
                    $doc_details_query = RapidActiveDocs::where('doc_title', 'LIKE', '%' . $lot_number_substr . '%')
                        ->get();
                    $b_drawing = collect($doc_details_query)->where('doc_type', 'B Drawing')->flatten(1);
                    if( count($b_drawing) ){
                        $lot_number[0]['drawing_no'] = $b_drawing[0]->doc_no;
                        $lot_number[0]['drawing_no_revision_no'] = $b_drawing[0]->rev_no;
                    }

                    $device_name_query_substr       = substr($request->device_name_query, 0, strpos($request->device_name_query, '-', strpos($request->device_name_query, '-')+1));
                    $doc_details_query = RapidActiveDocs::where('doc_title', 'LIKE', '%' . $device_name_query_substr . '%')
                        ->get();
                        $lot_number[0]['device_name_query_substr'] = $device_name_query_substr;
                    $sgc_drawing = collect($doc_details_query)->where('doc_type', 'SGC Drawing')->flatten(1);;
                    if( count($sgc_drawing) ){
                        $lot_number[0]['sgc_drawing_no'] = $sgc_drawing[0]->doc_no;
                        $lot_number[0]['sgc_drawing_no_revision_no'] = $sgc_drawing[0]->rev_no;
                    }
                    //---
                    $parts_preps = PartsPrep::where([
                            [
                                'wbs_kit_issuance_id','=',
                                $lot_number[0]->id
                            ],
                            [
                                'wbs_table','=',
                                2
                            ],
                            [
                                'deleted_at','=',
                                null
                            ]
                        ])
                        ->get();

                    if($parts_preps->isEmpty()){
                        $lot_number[0]['parts_preps'] = array();
                    }
                    else{

                        $lot_number[0]['parts_preps'] = $parts_preps;
                        $lot_number[0]['parts_preps'][0]['raw_checked_by_prod'] = '';

                        $users = User::where('id', $parts_preps[0]->checked_by_prod )->get();
                        if ( $users->count() ) {
                            $result = $users[0]->name;
                            $lot_number[0]['parts_preps'][0]['raw_checked_by_prod'] = $users[0]->name;;
                        }
                        //-----
                        $lot_number[0]['user_position'] = Auth::user()->position;
                        //-----
                    }
                }
                return $lot_number;

                break;
            case 3://warehouse

                $lot_number = WBSWarehouseMatIssuanceDetails::with([
                        'tbl_request_summary',
                        'tbl_request_detail'
                    ])->where('id','=',$request['material_id'])
                    ->get();
                if($lot_number->isEmpty()){
                    $lot_number = array();
                }
                else{
                    //---
                    $lot_number[0]['drawing_no'] = NULL;
                    $lot_number[0]['drawing_no_revision_no'] = NULL;
                    $lot_number[0]['sgc_drawing_no'] = NULL;
                    $lot_number[0]['sgc_drawing_no_revision_no'] = NULL;

                    $lot_number_substr              = substr($lot_number[0]->item_desc, 0, strrpos($lot_number[0]->item_desc, ' ('));
                    $doc_details_query = RapidActiveDocs::where('doc_title', 'LIKE', '%' . $lot_number_substr . '%')
                        ->get();
                    $b_drawing = collect($doc_details_query)->where('doc_type', 'B Drawing')->flatten(1);
                    if( count($b_drawing) ){
                        $lot_number[0]['drawing_no'] = $b_drawing[0]->doc_no;
                        $lot_number[0]['drawing_no_revision_no'] = $b_drawing[0]->rev_no;
                    }

                    $device_name_query_substr       = substr($request->device_name_query, 0, strpos($request->device_name_query, '-', strpos($request->device_name_query, '-')+1));
                    $doc_details_query = RapidActiveDocs::where('doc_title', 'LIKE', '%' . $device_name_query_substr . '%')
                        ->get();
                    $sgc_drawing = collect($doc_details_query)->where('doc_type', 'SGC Drawing')->flatten(1);;
                    if( count($sgc_drawing) ){
                        $lot_number[0]['sgc_drawing_no'] = $sgc_drawing[0]->doc_no;
                        $lot_number[0]['sgc_drawing_no_revision_no'] = $sgc_drawing[0]->rev_no;
                    }
                    //---
                    $parts_preps = PartsPrep::where([
                            [
                                'wbs_kit_issuance_id','=',
                                $lot_number[0]->id
                            ],
                            [
                                'wbs_table','=',
                                3
                            ],
                            [
                                'deleted_at','=',
                                null
                            ]
                        ])
                        ->get();

                    if($parts_preps->isEmpty()){
                        $lot_number[0]['parts_preps'] = array();
                    }
                    else{

                        $lot_number[0]['parts_preps'] = $parts_preps;
                        $lot_number[0]['parts_preps'][0]['raw_checked_by_prod'] = '';

                        $users = User::where('id', $parts_preps[0]->checked_by_prod )->get();
                        if ( $users->count() ) {
                            $result = $users[0]->name;
                            $lot_number[0]['parts_preps'][0]['raw_checked_by_prod'] = $users[0]->name;;
                        }
                        //-----
                        $lot_number[0]['user_position'] = Auth::user()->position;
                        //-----
                    }
                }
                return $lot_number;

                break;
            default:
                # code...
                break;
        }

    }

    public function fn_generate_parts_prep_runcard_arr($po){
        $runcard_number = 1;
        $runcard_po = substr($po, 0, 10);
        $parts_preps = PartsPrep::where([
                [
                    'runcard_po','=',
                    $runcard_po
                ],
                [
                    'deleted_at','=',
                    null
                ]
            ])
            ->orderBy('runcard_number','desc')
            ->get();
            // return $parts_preps;
        if($parts_preps->count() > 0){
            $runcard_number = $parts_preps[0]->runcard_number+1;
        }
        
        $runcard_number_arr = array();
        $runcard_number_arr['runcard_number_str'] = $runcard_po.str_pad($runcard_number,3,'0',STR_PAD_LEFT);
        $runcard_number_arr['runcard_po'] = $runcard_po;
        $runcard_number_arr['runcard_number'] = $runcard_number;

        return $runcard_number_arr;
    }

    public function fn_insert_lot_pass_fail(Request $request){
        // return Auth::user();
        $result = $this->return_result(0,0,0,0);
        // $user = User::where('employee_id', $request->txt_employee_number_scanner)
        $user = User::where('employee_id', Auth::user()->employee_id)
            ->where(function($q){
                $q->where('position',3)
                    ->orWhere('position',1);
            })
            ->get();

        
        if($user->count() > 0){
            if( $request->col_parts_preps_id ){
                //update
                DB::beginTransaction();
                try {
                    PartsPrep::where('id',$request->col_parts_preps_id)
                        ->update(
                            [
                                'with_partsprep'=>$request->with_partsprep,
                                'status'=>$request->status,
                                'received_passed_by'=>$user[0]->id,
                                'updated_by'=>$user[0]->id,
                                'received_passed_at'=>date('Y-m-d H:i:s'),
                            ]
                        );
                    DB::commit();
                    $result = $this->return_result(1,1,1,1);
                } catch (Exception $e) {
                    DB::rollback();
                    $result = $this->return_result(0,0,0,0);
                }
            }
            else{
                //insert
                DB::beginTransaction();
                try {
                    // $runcard_po         = substr($request->txt_runcard_number, 0, 10);
                    // $runcard_number     = substr($request->txt_runcard_number, -3);

                    // $runcard_number_arr = $this->fn_generate_parts_prep_runcard_arr($request->txt_runcard_number);

                    // $runcard_number_changed = false;
                    // if( (int)$runcard_number < $runcard_number_arr['runcard_number'] ){
                    //     $runcard_number     = $runcard_number_arr['runcard_number'];
                    //     $runcard_number_changed = true;
                    // }


                    $runcard_number_arr = $this->fn_generate_parts_prep_runcard_arr($request->col_material_po);
                    $runcard_po         = $runcard_number_arr['runcard_po'];
                    $runcard_number     = $runcard_number_arr['runcard_number'];

                    PartsPrep::insert(
                        [
                            'wbs_kit_issuance_id'=>$request->col_material_id,
                            'wbs_table'=>1,
                            'runcard_po'=>$runcard_po,
                            'runcard_number'=>$runcard_number,
                            'with_partsprep'=>($request->with_partsprep)?$request->with_partsprep:0,
                            'status'=>$request->status,
                            'received_by'=>$user[0]->id,
                            'received_passed_by'=>$user[0]->id,
                            'updated_by'=>$user[0]->id,
                            'received_at'=>date('Y-m-d H:i:s'),
                            'received_passed_at'=>date('Y-m-d H:i:s'),
                        ]
                    );
                    DB::commit();
                    $result = $this->return_result(1,1,1,1);
                } catch (Exception $e) {
                    DB::rollback();
                    $result = $this->return_result(0,0,0,0);
                }
            }

        }
        else{
            $result = $this->return_result(2,2,2,2);
        }
        return $result;
    }

    public function fn_insert_sakidashi_lot_pass_fail(Request $request){

        // return $request->all();
        $result = $this->return_result(0,0,0,0);
        // $user = User::where('employee_id', $request->txt_employee_number_scanner)
        $user = User::where('employee_id', Auth::user()->employee_id)
            ->where(function($q){
                $q->where('position',3)
                    ->orWhere('position',1);
            })
            ->get();
        if($user->count() > 0){
            if( $request->col_parts_preps_id ){
                //update
                DB::beginTransaction();
                try {
                    PartsPrep::where('id',$request->col_parts_preps_id)
                        ->update(
                            [
                                'with_partsprep'=>$request->with_partsprep,
                                'status'=>$request->status,
                                'received_passed_by'=>$user[0]->id,
                                'updated_by'=>$user[0]->id,
                                'received_passed_at'=>date('Y-m-d H:i:s'),
                            ]
                        );
                    DB::commit();
                    $result = $this->return_result(1,1,1,1);
                } catch (Exception $e) {
                    DB::rollback();
                    $result = $this->return_result(0,0,0,0);
                }
            }
            else{
                //insert
                DB::beginTransaction();
                try {
                    // $runcard_po         = substr($request->txt_runcard_number, 0, 10);
                    // $runcard_number     = substr($request->txt_runcard_number, -3);

                    // $runcard_number_arr = $this->fn_generate_parts_prep_runcard_arr($request->txt_runcard_number);

                    // $runcard_number_changed = false;
                    // if( (int)$runcard_number < $runcard_number_arr['runcard_number'] ){
                    //     $runcard_number     = $runcard_number_arr['runcard_number'];
                    //     $runcard_number_changed = true;
                    // }


                    $runcard_number_arr = $this->fn_generate_parts_prep_runcard_arr($request->col_material_po);
                    $runcard_po         = $runcard_number_arr['runcard_po'];
                    $runcard_number     = $runcard_number_arr['runcard_number'];

                    PartsPrep::insert(
                        [
                            'wbs_kit_issuance_id'=>$request->col_material_id,
                            'wbs_table'=>2,
                            'runcard_po'=>$runcard_po,
                            'runcard_number'=>$runcard_number,
                            'with_partsprep'=>($request->with_partsprep)?$request->with_partsprep:0,
                            'status'=>$request->status,
                            'received_by'=>$user[0]->id,
                            'received_passed_by'=>$user[0]->id,
                            'updated_by'=>$user[0]->id,
                            'received_at'=>date('Y-m-d H:i:s'),
                            'received_passed_at'=>date('Y-m-d H:i:s'),
                        ]
                        );
                    DB::commit();
                    $result = $this->return_result(1,1,1,1);
                } catch (Exception $e) {
                    DB::rollback();
                    $result = $this->return_result(0,0,0,0);
                }
            }

        }
        else{
            $result = $this->return_result(2,2,2,2);
        }
        return $result;
    }


    public function fn_insert_warehouse_lot_pass_fail(Request $request){
        $result = $this->return_result(0,0,0,0);
        $user = User::where('employee_id', $request->txt_employee_number_scanner)
            ->where(function($q){
                $q->where('position',3)
                    ->orWhere('position',1);
            })
            ->get();
        if($user->count() > 0){
            if( $request->col_parts_preps_id ){
                //update
                DB::beginTransaction();
                try {
                    PartsPrep::where('id',$request->col_parts_preps_id)
                        ->update(
                            [
                                'with_partsprep'=>$request->with_partsprep,
                                'status'=>$request->status,
                                'received_passed_by'=>$user[0]->id,
                                'updated_by'=>$user[0]->id,
                                'received_passed_at'=>date('Y-m-d H:i:s'),
                            ]
                        );
                    DB::commit();
                    $result = $this->return_result(1,1,1,1);
                } catch (Exception $e) {
                    DB::rollback();
                    $result = $this->return_result(0,0,0,0);
                }
            }
            else{
                //insert
                DB::beginTransaction();
                try {
                    // $runcard_po         = substr($request->txt_runcard_number, 0, 10);
                    // $runcard_number     = substr($request->txt_runcard_number, -3);

                    // $runcard_number_arr = $this->fn_generate_parts_prep_runcard_arr($request->txt_runcard_number);

                    // $runcard_number_changed = false;
                    // if( (int)$runcard_number < $runcard_number_arr['runcard_number'] ){
                    //     $runcard_number     = $runcard_number_arr['runcard_number'];
                    //     $runcard_number_changed = true;
                    // }


                    $runcard_number_arr = $this->fn_generate_parts_prep_runcard_arr($request->col_material_po);
                    $runcard_po         = $runcard_number_arr['runcard_po'];
                    $runcard_number     = $runcard_number_arr['runcard_number'];

                    PartsPrep::insert(
                        [
                            'wbs_kit_issuance_id'=>$request->col_material_id,
                            'wbs_table'=>3,
                            'runcard_po'=>$runcard_po,
                            'runcard_number'=>$runcard_number,
                            'with_partsprep'=>($request->with_partsprep)?$request->with_partsprep:0,
                            'status'=>$request->status,
                            'received_by'=>$user[0]->id,
                            'received_passed_by'=>$user[0]->id,
                            'updated_by'=>$user[0]->id,
                            'received_at'=>date('Y-m-d H:i:s'),
                            'received_passed_at'=>date('Y-m-d H:i:s'),
                        ]
                        );
                    DB::commit();
                    $result = $this->return_result(1,1,1,1);
                } catch (Exception $e) {
                    DB::rollback();
                    $result = $this->return_result(0,0,0,0);
                }
            }

        }
        else{
            $result = $this->return_result(2,2,2,2);
        }
        return $result;
    }



    public function fn_insert_material_details(Request $request){
        // return $request->all();
        // with_parts_prep
        $result = $this->return_result(0,0,0,0);
        $user = User::where('employee_id', $request->txt_employee_number_scanner)
            ->where(function($q){
                $q->where('position',3)
                    ->orWhere('position',1);
            })
            ->get();
        if($user->count() > 0){
            $status = 3;
            if( $request->txt_parts_preps_id_query ){
                //update
                DB::beginTransaction();
                try {
                    if($request->with_parts_prep != 1){
                        $status = 1;
                    }
                    PartsPrep::where('id',$request->txt_parts_preps_id_query)
                        ->update(
                            [
                                // 'status'=>3,
                                'status'=> $status,
                                'assess_num'=>$request->txt_assessment_number,
                                'special_instruction'=>$request->txt_special_instruction,
                                'remarks'=>$request->txt_material_remarks,
                                'updated_by'=>$user[0]->id,
                            ]
                        );
                    DB::commit();
                    $result = $this->return_result(1,1,1,1);
                } catch (Exception $e) {
                    DB::rollback();
                    $result = $this->return_result(0,0,0,0);
                }
            }
            else{
                //insert
                DB::beginTransaction();
                try {
                    if($request->with_parts_prep != 1){
                        $status = 1;
                    }
                    $runcard_po         = substr($request->txt_runcard_number, 0, 10);
                    $runcard_number     = substr($request->txt_runcard_number, -3);

                    $runcard_number_arr = $this->fn_generate_parts_prep_runcard_arr($request->txt_runcard_number);

                    $runcard_number_changed = false;
                    if( (int)$runcard_number < $runcard_number_arr['runcard_number'] ){
                        $runcard_number     = $runcard_number_arr['runcard_number'];
                        $runcard_number_changed = true;
                    }

                    PartsPrep::insert(
                        [
                            // 'status'=>3,
                            'status'=> $status,
                            'wbs_kit_issuance_id'=>$request->txt_wbs_kit_issuance_id_query,
                            'runcard_po'=>$runcard_po,
                            'runcard_number'=>$runcard_number,
                            'assess_num'=>$request->txt_assessment_number,
                            'special_instruction'=>$request->txt_special_instruction,
                            'remarks'=>$request->txt_material_remarks,
                            'created_by'=>$user[0]->id,
                            'updated_by'=>$user[0]->id,
                            'created_at'=>date('Y-m-d H:i:s'),
                        ]
                        );
                    DB::commit();
                    $result = $this->return_result(1,1,1,1);
                    if($runcard_number_changed){
                        $result = $this->return_result(2,1,'Runcard has been changed. The new Runcard # is '.$runcard_po.str_pad($runcard_number,3,'0',STR_PAD_LEFT),1);
                    }
                } catch (Exception $e) {
                    DB::rollback();
                    $result = $this->return_result(0,0,0,0);
                }
            }

        }
        else{
            $result = $this->return_result(2,2,2,2);
        }
        return $result;
    }

    public function fn_insert_material_details_parts_prep(Request $request){
        $result = $this->return_result(0,0,0,0);
        $user = User::where('employee_id', $request->txt_employee_number_scanner)
            ->where(function($q){
                $q->where('position',4)
                    ->orWhere('position',1);
            })
            ->get();
        if($user->count() > 0){
            if( $request->txt_parts_preps_id_query ){
                //update
                DB::beginTransaction();
                try {
                    PartsPrep::where('id',$request->txt_parts_preps_id_query)
                        ->update(
                            [
                                'status'=>4,
                                'sgc_doc_number'=>$request->txt_sgc_docs_number,
                                'sgc_doc_number_revision_number'=>$request->txt_sgc_docs_number_revision_number,
                                'other_docs_num'=>$request->txt_other_docs_number,
                                'parts_prep_remarks'=>$request->txt_parts_prep_remarks,
                                'updated_by'=>$user[0]->id,
                            ]
                        );
                    DB::commit();
                    $result = $this->return_result(1,1,1,1);
                } catch (Exception $e) {
                    DB::rollback();
                    $result = $this->return_result(0,0,0,0);
                }
            }
            else{//no rec
                $result = $this->return_result(0,0,0,0);
            }
        }
        else{
            $result = $this->return_result(2,2,2,2);
        }
        return $result;
    }



    public function fn_insert_setup_stations(Request $request){
        $result = $this->return_result(0,0,0,0);
        //insert
        $user = User::where('employee_id', $request->txt_employee_number_scanner)
            ->where(function($q){
                $q->where('position',3)
                    ->orWhere('position',1);
            })
            ->get();
        if($user->count() > 0){
            foreach ($request->arr_substations as $key => $value) {
                DB::beginTransaction();
                try {
                    PartsPrepStation::insert(
                        [
                            'parts_prep_id'=>$request->txt_parts_preps_id_query,
                            'station_id'=>$value['station'],
                            'sub_station_id'=>$value['substation'],
                            'step_num'=>$value['step'],
                            'setup_by'=>$user[0]->id,
                            'setup_at'=>date('Y-m-d H:i:s'),
                            'updated_by'=>$user[0]->id,
                        ]
                        );
                    DB::commit();
                    $result = $this->return_result(1,1,1,1);
                } catch (Exception $e) {
                    DB::rollback();
                    $result = $this->return_result(0,0,0,0);
                }
            }
        }
        else{
            $result = $this->return_result(2,2,2,2);
        }
        return $result;
    }

    public function fn_select_partspreparatory_station_details(Request $request){

        $partsprep_stations = PartsPrepStation::
            with([
                'station',
                'sub_station',
            ])
            ->where(
                [
                    [
                        'deleted_at','=',
                        null
                    ],
                    [
                        'id','=',$request['col_partsprep_station_id']
                    ]
                ]
            )
            ->get();

        $device = Device::with([
            'material_process' => function ($query) use ($partsprep_stations) {
                $query->whereHas('station_sub_station', function ($q) use ($partsprep_stations) {
                    $q->where('station_id', $partsprep_stations[0]->station_id)
                    ->where('sub_station_id', $partsprep_stations[0]->sub_station_id);
                });
            }, 
            'material_process.station_sub_station',
            'material_process.manpowers_details',
            'material_process.machine_details',
            'material_process.machine_details.machine_info',
            'material_process.manpowers_details.manpower_info'
        ])
        ->where('barcode', $request->device_code)
        ->where('status', 1)
        ->get();

        $employeeNumbers = collect($device)
        ->pluck('material_process')
        ->flatten()
        ->pluck('manpowers_details')
        ->flatten()
        ->pluck('manpower_info.employee_id')
        ->filter()
        ->values();
        
    
        

        if($partsprep_stations->isEmpty()){
            $partsprep_stations = array();
        }
        else{
            $partsprep_stations[0]['created_by_name'] = '';
            $user = User::where('id', $partsprep_stations[0]->created_by)->get();
            if(!$user->isEmpty()){
                $partsprep_stations[0]['created_by_name'] = $user[0]['name'];
            }

            $partsprep_stations[0]['device_manpower_details'] = $employeeNumbers ? $employeeNumbers : null;
        }
        $partsprep_stations[0]['device'] = $device;
        return $partsprep_stations;
    }


    public function fn_update_partsprep_station_details(Request $request){
        $result = $this->return_result(0,0,0,0);
        $user = User::where('employee_id', $request->txt_employee_number_scanner)
            ->where(function($q){
                $q->where('position',4)
                    ->orWhere('position',1);
            })
            ->get();
        if($user->count() > 0){
            if( $request->txt_partsprep_station_id_query ){
                //update
                DB::beginTransaction();
                try {


                    PartsPrepStation::where('id',$request->txt_partsprep_station_id_query)
                        ->update(
                            [
                                // 'status'=>$request->0,
                                'machine_id'=>$request->txt_edit_partsprep_station_machine,
                                'qty_input'=>($request->txt_edit_partsprep_station_input)?$request->txt_edit_partsprep_station_input:0,
                                'qty_output'=>($request->txt_edit_partsprep_station_output)?$request->txt_edit_partsprep_station_output:0,
                                'qty_ng'=>$request->txt_edit_partsprep_station_ng,
                                'mod'=>$request->txt_edit_partsprep_station_mod,
                                'print_code'=>$request->txt_edit_partsprep_station_print_code,
                                'created_by'=>$user[0]->id,
                                'updated_by'=>$user[0]->id,
                                'created_at'=>date('Y-m-d H:i:s'),
                                // 'updated_by'=>$request->txt_material_remarks,
                            ]
                        );
                    DB::commit();
                    $result = $this->return_result(1,1,1,1);
                    
                } catch (Exception $e) {
                    DB::rollback();
                    $result = $this->return_result(0,0,0,0);
                }
            }
        }
        else{
            $result = $this->return_result(2,2,2,2);
        }

        if( isset($request->txt_edit_partsprep_station_ng) && $request->txt_edit_partsprep_station_ng != '' ){//update current and prev in and out qty
            $ng_total = 0;
            $txt_edit_partsprep_station_ng_arr = explode(',', $request->txt_edit_partsprep_station_ng);
            foreach ($txt_edit_partsprep_station_ng_arr as $key => $value) {
                $ng_total+=$value;
            }
            $result = $this->update_station_in_out($request->txt_partsprep_station_id_query, $ng_total);
        }

        return $result;
    }

    public function update_station_in_out($parts_prep_id, $ng_qty){
        $parts_prep_id = PartsPrepStation::where('id', $parts_prep_id )->get();
        if($parts_prep_id->count() > 0){
            $partsprepstations = PartsPrepStation::where('parts_prep_id', $parts_prep_id[0]->parts_prep_id )->get();
            foreach ($partsprepstations as $key => $value) {
                if( $value['created_at'] ){
                    $qty_input      = $value['qty_input']+$ng_qty;
                    $qty_output     = $value['qty_output']+$ng_qty;

                    DB::beginTransaction();
                    try {
                        PartsPrepStation::where('id',$value['id'])
                            ->update(
                                [
                                    'qty_input'=>$qty_input,
                                    'qty_output'=>$qty_output,
                                ]
                            );
                        DB::commit();
                        $result = $this->return_result(1,1,1,1);
                        
                    } catch (Exception $e) {
                        DB::rollback();
                        $result = $this->return_result(0,0,0,0);
                    }
                }
            }
        }
        return $result;
    }

    public function fn_update_material_details_secondary(Request $request){
        $result = $this->return_result(0,0,0,0);
        $user = User::where('employee_id', $request->txt_employee_number_scanner)
            ->where(function($q){
                $q->where('position',4)
                    ->orWhere('position',1);
            })
            ->get();
        if($user->count() > 0){
            if( $request->txt_parts_preps_id_query ){
                //update
                DB::beginTransaction();
                try {
                    PartsPrep::where('id',$request->txt_parts_preps_id_query)
                        ->update(
                            [
                                'status'=>$request->status,
                                'discrepant_qty'=>($request->txt_discrepant_qty)?$request->txt_discrepant_qty_sign.$request->txt_discrepant_qty:0,
                                'analysis'=>$request->txt_analysis,
                                'recount_ok'=>($request->txt_recount_ok)?$request->txt_recount_ok:0,
                                'recount_ng'=>($request->txt_recount_ng)?$request->txt_recount_ng:0,
                                'other_runcard_number'=>$request->txt_other_runcard_number,
                                'updated_by'=>$user[0]->id,
                                'verified_by'=>$user[0]->id,
                                'verified_at'=>date('Y-m-d H:i:s'),
                            ]
                        );
                    DB::commit();
                    $result = $this->return_result(1,1,1,1);
                    
                } catch (Exception $e) {
                    DB::rollback();
                    $result = $this->return_result(0,0,0,0);
                }
            }
        }
        else{
            $result = $this->return_result(2,2,2,2);
        }
        return $result;
    }


    public function fn_update_approval_prod(Request $request){
        $result = $this->return_result(0,0,0,0);
        $user = User::where('employee_id', $request->txt_employee_number_scanner)
            ->where(function($q){
                $q->where('position',1);
                    // ->orWhere('position',1);
            })
            ->get();
        if($user->count() > 0){
            if( $request->txt_parts_preps_id_query ){
                //update
                DB::beginTransaction();
                try {
                    PartsPrep::where('id',$request->txt_parts_preps_id_query)
                        ->update(
                            [
                                'status'=>$request->status,
                                'checked_by_prod'=>$user[0]->id,
                                'checked_at_prod'=>date('Y-m-d H:i:s'),
                            ]
                        );
                    DB::commit();
                    $result = $this->return_result(1,1,1,1);
                    
                } catch (Exception $e) {
                    DB::rollback();
                    $result = $this->return_result(0,0,0,0);
                }
            }
        }
        else{
            $result = $this->return_result(2,2,2,2);
        }
        return $result;
    }


    public function fn_update_approval_qc(Request $request){//remove this
        $result = $this->return_result(0,0,0,0);
        $user = User::where('employee_id', $request->txt_employee_number_scanner)->get();
        if($user->count() > 0){
            if( $request->txt_parts_preps_id_query ){
                //update
                DB::beginTransaction();
                try {
                    PartsPrep::where('id',$request->txt_parts_preps_id_query)
                        ->update(
                            [
                                // 'status'=>$request->0,
                                'checked_by_qc'=>$user[0]->id,
                                'checked_at_qc'=>date('Y-m-d H:i:s'),
                            ]
                        );
                    DB::commit();
                    $result = $this->return_result(1,1,1,1);
                    
                } catch (Exception $e) {
                    DB::rollback();
                    $result = $this->return_result(0,0,0,0);
                }
            }
        }
        else{
            $result = $this->return_result(2,2,2,2);
        }
        return $result;
    }

    public function fn_select_list_partsprep_station_machines(Request $request){
        $machines = Machine::
            where(
                [
                    [
                        'status','=',
                        1
                    ],
                ]
            )
            ->get();
        if($machines->isEmpty()){
            $machines = array();
        }
        return $machines;
    }

    public function fn_select_list_runcard_numbers(Request $request){
        $parts_preps = PartsPrep::
            where(
                [
                    [
                        'deleted_at','=',
                        null
                    ],
                ]
            )
            ->get();
        if($parts_preps->isEmpty()){
            $parts_preps = array();
        }
        return $parts_preps;
    }

    public function fn_select_list_partsprep_station_mod(Request $request){
        $mode_of_defects = ModeOfDefect::
            where(
                [
                    [
                        'status','=',
                        1
                    ],
                ]
            )
            ->get();
        if($mode_of_defects->isEmpty()){
            $mode_of_defects = array();
        }
        return $mode_of_defects;
    }





































    public function fn_view_stationsxx(Request $request){
        // $material_process = MaterialProcess::with(['sub_station', 'sub_station.station'])->where('device_id', $request->device_id)->get();
        $material_process = MaterialProcess::with(['sub_station', 'sub_station.station'])->where('device_id', $request->device_id)->get();

        return DataTables::of($material_process)
            ->addColumn('raw_action', function($station){
                $disabled = '';
                $result='<button '.$disabled.' style="width:30px;" title="Edit" class="btn_material_action btn_edit_material btn btn-info btn-sm py-0"><i class="fa fa-edit"></i></button>';
                return $result;
            })
            ->addColumn('raw_status', function($station){
                $result = "---";
                return $result;
            })
            ->addColumn('raw_date', function($station){
                $result = "---";
                return $result;
            })
            ->addColumn('raw_operator', function($station){
                $result = "---";
                return $result;
            })
            ->addColumn('raw_input', function($station){
                $result = "---";
                return $result;
            })
            ->addColumn('raw_output', function($station){
                $result = "---";
                return $result;
            })
            ->addColumn('raw_ng_qty', function($station){
                $result = "---";
                return $result;
            })
            ->addColumn('raw_mod', function($station){
                $result = "---";
                return $result;
            })
            ->rawColumns(['raw_action'])
            ->make(true);
    }

    public function testonlyxxx(){
        $device = Device::with([
            'material_process' => 
            function($query){
                $query->where('status', 1);
            },
            'material_process.station_sub_station' =>
            function($query){
                $query->where('status', 1);
            },
            'material_process.station_sub_station.station' =>
            function($query){
                $query->where('status', 1);
            },
            'material_process.station_sub_station.sub_station' =>
            function($query){
                $query->where('status', 1);
            },
            ])
            ->where('status', 1)
            ->where('barcode', '104184001')
            ->get();



        // $material_process = MaterialProcess::with([
        //                     'device' => function($query){
        //                         $query->where('barcode', 'CN121P-104-0003');
        //                     },
        //                     'station_sub_station'
        //                 ])->get();
        return $device;


        // $material_process = MaterialProcess::with(['device', 'device.barcode'])->where('fdf', $request->device_id)->get();
    }


    public function testonly(Request $request){

        // $devices = Device::where('barcode',$request->device_code);
        // return $devices;

        $stations = MaterialProcess::with([
            'station_sub_station' => 
            function($query){
                $query->where('status', 1);
            },
            'station_sub_station.station' =>
            function($query){
                $query->where('status', 1);
                $query->where('station_type', 1);
            },
            'station_sub_station.sub_station' =>
            function($query){
                $query->where('status', 1);
            },
            // 'material_process.station_sub_station.station' =>
            // function($query){
            //     $query->where('status', 1);
            // },
            // 'material_process.station_sub_station.sub_station' =>
            // function($query){
            //     $query->where('status', 1);
            // },
            ])
            ->withCount([
                'station_sub_station.station' =>
                    function($query){
                        $query->where('status', 1);
                        $query->where('station_type', 1);
                    }
            ])
            ->where('status', 1)
            ->where('device_id', '1')
            // ->having('station_sub_station.station_count' > 0)
            ->get();

        return $stations;

    }

}
