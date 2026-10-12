<?php

namespace App\Http\Controllers;

use App\User;
use App\Model\Device;
use App\Model\MaterialProcess;
use Illuminate\Http\Request;
use App\Model\PartsPrep;
use App\Model\MaterialIssuance;
use App\Model\MaterialIssuanceSubSystem;
use App\Model\MaterialIssuanceDetails;
use App\Model\WBSKitIssuance;
use App\Model\WBSSakidashiIssuance;
use App\Model\WBSSakidashiIssuanceItem;
use App\Model\WBSWarehouseMatIssuance;
use App\Model\WBSWarehouseMatIssuanceDetails;
use App\Model\RequestSummary;


// use App\Model\User;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

use DataTables;

define('TIMEZONE', 'Asia/Manila');
date_default_timezone_set(TIMEZONE);

class MaterialIssuanceController extends Controller
{
    public function return_result($icon_i,$title_i,$body_i,$i){
        //result 1 = ok, result 0 = error, result 2 = invalid user
        $title = array();
        $title[0] = 'Not saved';
        $title[1] = 'Saved';
        $title[2] = 'Invalid Employee No.';


        $body = array();
        $body[0] = 'An error occured. Record not saved.';
        $body[1] = 'Record has been saved.';
        $body[2] = 'Record not saved.';

        $icon = array();
        $icon[0] = '<i class="fa fa-times-circle text-danger"></i>';
        $icon[1] = '<i class="fa fa-check-circle text-success"></i>';
        $icon[2] = '<i class="fa fa-exclamation-triangle text-warning"></i>';

        $return = array();
        $return['icon'] = $icon[$icon_i];
        $return['title'] = $title[$title_i];
        $return['body'] = $body[$body_i];
        $return['i'] = $i;
        return $return;
    }

	public function fn_view_material_issuance_page(){
		return view('materialissuance');
	}

	public function fn_view_batches(Request $request){
        $tbl_wbs_material_kitting = array();
        if( $request['po_number'] && $request['txt_scan_transfer_slip'] ){
            $tbl_wbs_material_kitting = MaterialIssuanceSubSystem::
            where('po_no',$request['po_number'])
            ->where('issuance_no',$request['txt_scan_transfer_slip'])
            ->get();
        }


        // $sakidashi_table_sql = WBSSakidashiIssuance::where('po_no',$request['po_number'])->get();


        // $sakidashi_table = array();
        // $sakidashi_table = (object)$sakidashi_table;
        // foreach ($sakidashi_table_sql as $key => $value) {
        //     // return $value;
        //     $sakidashi_table->id = $value->id;
        //     $sakidashi_table->issuance_no = $value->issuance_no;
        //     $sakidashi_table->po_no = '';
        //     $sakidashi_table->device_code = '';
        //     $sakidashi_table->device_name = '';
        //     $sakidashi_table->po_qty = '';
        //     $sakidashi_table->kit_qty = '';
        //     $sakidashi_table->kit_no = '';
        //     $sakidashi_table->prepared_by = '';
        //     // $sakidashi_table->status = '';
        //     $sakidashi_table->issuance_date = '';
        //     $sakidashi_table->create_user = '';
        //     $sakidashi_table->update_user = '';
        //     $sakidashi_table->created_at = '';
        //     $sakidashi_table->updated_at = '';
        //     // $sakidashi_table->issued_qty = '';
        //     // $sakidashi_table->received_dt = '';
        //     $sakidashi_table->received_by = '';
        //     // $sakidashi_table->action = 'test';

        //     if( count($tbl_wbs_material_kitting) ){
        //         $tbl_wbs_material_kitting[ count($tbl_wbs_material_kitting) ] = $sakidashi_table;
        //     }
        // }

        return DataTables::of($tbl_wbs_material_kitting)
            ->addColumn('issued_qty', function(){
                $result = "---";
                return $result;
            })
            ->addColumn('received_dt', function($tbl_wbs_material_kitting){
				$material_issuances = MaterialIssuance::where('tbl_wbs_material_kitting_id', $tbl_wbs_material_kitting->id )->get();
                $result = "---";
				if ( $material_issuances->count() ) {
	                $result = $material_issuances[0]->created_at;
				}
                return $result;
            })
            ->addColumn('received_by', function($tbl_wbs_material_kitting){
				$material_issuances = MaterialIssuance::where('tbl_wbs_material_kitting_id', $tbl_wbs_material_kitting->id )->get();
                $result = "---";
				if ( $material_issuances->count() ) {
					$users = User::where('id', $material_issuances[0]->created_by )->get();
					if ( $users->count() ) {
		                $result = $users[0]->name;
		            }
    	        }
                return $result;
            })
            ->addColumn('status', function($tbl_wbs_material_kitting){
				$material_issuances = MaterialIssuance::where('tbl_wbs_material_kitting_id', $tbl_wbs_material_kitting->id )->get();
                $result = '<i title="Pending" class="far fa-clock text-secondary px-2"></i>';
				if ( $material_issuances->count() ) {
					$status = $material_issuances[0]->status;
					if($status==1){
		                $result = '<i title="Received" class="fa fa-check text-success px-2"></i>';
					}
					if($status==2){
		                $result = '<i title="Returned" class="fa fa-exclamation-triangle text-warning px-2"></i>';
					}
				}
                return $result;
            })
            ->addColumn('action', function($tbl_wbs_material_kitting){
				$material_issuances = MaterialIssuance::where('tbl_wbs_material_kitting_id', $tbl_wbs_material_kitting->id )->get();
                $disabled = '';
				if ( $material_issuances->count() ) {
					$disabled = 'disabled';
				}

                //-----
                $hidden = ' hidden ';
                if( Auth::user()->position == 3 ){
                    $hidden = '';
                }
                //-----

                $result = "";
                $result.='<button '.$disabled.' '.$hidden.' style="width:30px;" title="Checked" class="btn_batch_action btn btn-info btn-sm  py-0 btn_batch_passed"><i class="fa fa-clipboard-check"></i></button>';
                $result.=' <input type="hidden" class="tbl_wbs_material_kitting_id" value="'.$tbl_wbs_material_kitting->id.'">';
                $result.=' <input type="hidden" class="tbl_wbs_material_kitting_issuance_no" value="'.$tbl_wbs_material_kitting->issuance_no.'">';
                return $result;
            })
            ->addColumn('kit_raw', function($tbl_wbs_material_kitting){
                $material_issuances = MaterialIssuance::where('tbl_wbs_material_kitting_id', $tbl_wbs_material_kitting->id )->get();
                $disabled = '';
                if ( $material_issuances->count() ) {
                    $disabled = 'disabled';
                }

                //-----
                $hidden = ' hidden ';
                if( Auth::user()->position == 3 ){
                    $hidden = '';
                }
                //-----

                $result = "";
                $result = $tbl_wbs_material_kitting->kit_no;
                // $result.='<button '.$disabled.' '.$hidden.' style="width:30px;" title="Checked" class="btn_batch_action btn btn-info btn-sm  py-0 btn_batch_passed"><i class="fa fa-clipboard-check"></i></button>';
                $result.=' <input type="hidden" class="tbl_wbs_material_kitting_id" value="'.$tbl_wbs_material_kitting->id.'">';
                $result.=' <input type="hidden" class="tbl_wbs_material_kitting_issuance_no" value="'.$tbl_wbs_material_kitting->issuance_no.'">';
                return $result;
            })
            ->rawColumns(['status','action','kit_raw'])
            ->make(true);
	}

	public function fn_view_lot_numbers(Request $request){
        $lot_numbers = array();
        if( $request['issue_no'] ){
    		$lot_numbers = WBSKitIssuance::
                with('kit_issuance')
                ->where('issue_no','=',$request['issue_no'])
    			->get();
        }

        return DataTables::of($lot_numbers)
            ->addColumn('raw_action', function($lot_number){
                $parts_preps_id = 0;
                $disabled = 'disabled';
                $icon_partsprep = '';
                $with_partsprep = 0;
                // $disabled_passed_btn = 'disabled';
                // $disabled_failed_btn = 'disabled';
            	$title = '';

                // $material_kitting = MaterialIssuanceSubSystem::where('issuance_no', $lot_number->issue_no )->get();
                // if ( $material_kitting->count() ) {
                    // $material_issuances = MaterialIssuance::where('tbl_wbs_material_kitting_id', $material_kitting[0]['id'] )->get();
                    // if ( $material_issuances->count() ) {
                        // switch ($material_issuances[0]->status) {
                        //     case 1:
                $disabled_passed_btn = '';
                $disabled_failed_btn = '';

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
                            $query->where('name', 'LIKE', '%parts prep%');
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
                $collection = collect($stations)->filter(function ($item) {
                    return strpos(strtolower($item['station_sub_station']['station']['name']), 'parts prep') !== false;
                });
                
                if ( $collection->count() ) {
                    $icon_partsprep = '<i title="Has partsprep" class="fa fa-wrench text-secondary pl-2 float-right"></i>';
                    $with_partsprep = 1;
                }
                //---

                $parts_preps = PartsPrep::where([
                        [
                            'wbs_kit_issuance_id','=',
                            $lot_number->id
                        ],
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
                if ( $parts_preps->count() ) {
                    $parts_preps_id = $parts_preps[0]->id;
                    // $icon_partsprep = ($parts_preps[0]->with_partsprep)?'<i title="Has partsprep" class="fa fa-wrench text-secondary pl-2 float-right"></i>':'';
                    $disabled_passed_btn = 'disabled';
                    $disabled_failed_btn = 'disabled';
                    if( $parts_preps[0]->status == 2 ){
                        $disabled_passed_btn = '';
                    }
                    if( $parts_preps[0]->status == 1 || $parts_preps[0]->status > 2 ){
                        $disabled = '';
                    }
                }
                        //         break;
                            
                        //     default:
                        //         $title = '';//'Pending in Material Input';
                        //         break;
                        // }
                    // }
                    // else{
                    //     $title = '';//'Pending in Material Input';
                    // }
                // }
                // else{
                //     $title = '';//'Not found in WBS';
                // }
                //-----
                $hidden = ' hidden ';
                if( Auth::user()->position == 3 ){
                    $hidden = '';
                }
                //-----

                $result ='';
                $result.=$icon_partsprep;
                $result.='<div class="dropdown">';
                  $result.='<button class="btn btn-sm btn-secondary dropdown-toggle qwe" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                  // $result.='<i class="fas fa-ellipsis-v fa-sm"></i>';
                  $result.='</button>';
                  $result.='<div class="dropdown-menu qwe" aria-labelledby="dropdownMenuButton">';

                    $result.='<button '.$disabled.' title="Open details" class="dropdown-item btn_material_action btn_material_open_details btn btn-info btn-sm py-0"><i class="fa fa-info-circle fa-sm"></i> Details</button>';
                    $result.=' <button '.$disabled_passed_btn.' '.$hidden.' title="Receive" class="dropdown-item btn_material_action btn_material_pass_details btn btn-success btn-sm py-0" data-lot-no="'.$lot_number->lot_no.'"><i class="fa fa-check-circle fa-sm"></i> Receive</button>';
                    // $result.=' <button '.$disabled_failed_btn.' '.$hidden.' title="Failed" class="dropdown-item btn_material_action btn_material_fail_details btn btn-warning btn-sm py-0"><i class="fa fa-exclamation-triangle fa-sm"></i> Failed</button>';

                  $result.='</div>';
                $result.='</div>';



                $result.=' <input type="hidden" class="col_material_id" value="'.$lot_number->id.'">';
                $result.=' <input type="hidden" class="col_material_code" value="'.$lot_number->item.'">';
                $result.=' <input type="hidden" class="col_device_code" value="'.$lot_number['kit_issuance']->device_code.'">';
                $result.=' <input type="hidden" class="col_material_po" value="'.$lot_number->po.'">';
                $result.=' <input type="hidden" class="col_parts_preps_id" value="'.$parts_preps_id.'">';
                $result.=' <input type="hidden" class="col_with_partsprep" value="'.$with_partsprep.'">';
                return $result;
            })
            ->addColumn('raw_status', function($lot_number){
                $result = '<span data-sorter="0" class="badge badge-secondary font-weight-normal">To receive</span>';

				$parts_preps = PartsPrep::where([
	                    [
	                        'wbs_kit_issuance_id','=',
	                    	$lot_number->id
	                    ],
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

                if ( $parts_preps->count() ) {
                    switch ( $parts_preps[0]->status ) {
                        case 1:
                            $result = '<span data-sorter="6" class="badge badge-success font-weight-normal">Done: Received</span>';
                            // if( $parts_preps[0]->with_partsprep || is_null($parts_preps[0]->assess_num)){
                            if( $parts_preps[0]->with_partsprep != 0){

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
                $material_kitting_details = MaterialIssuanceDetails::where('issue_no', $lot_number->issue_no )->where('item', $lot_number->item )->get();
                if ( $material_kitting_details->count() ) {
                	$result = $material_kitting_details[0]->usage;
                }

                return $result;
            })
            ->addColumn('raw_rqd_qty', function($lot_number){
                $result = 0;
                $material_kitting_details = MaterialIssuanceDetails::where('issue_no', $lot_number->issue_no )->where('item', $lot_number->item )->get();
                if ( $material_kitting_details->count() ) {
                    $result = $material_kitting_details[0]->usage;
                }
                $result = $lot_number['kit_issuance']->po_qty*$result;
                return $result;
            })
            ->rawColumns(['raw_action','raw_status'])
            ->make(true);
	}


    public function fn_view_sakidashi_issuance(Request $request){
        $tbl_wbs_sakidashi_issuance = array();
        if( $request['po_number'] && $request->txt_scan_sakidashi_lot ){
            $tbl_wbs_sakidashi_issuance = WBSSakidashiIssuance::with('tbl_wbs_sakidashi_issuance_item')
                ->where('po_no',$request['po_number'])
                ->whereHas('tbl_wbs_sakidashi_issuance_item',
                   function($query) use ($request){
                        $query->where('lot_no', $request->txt_scan_sakidashi_lot);
                    },
                )
                ->get();
        }

        return DataTables::of($tbl_wbs_sakidashi_issuance)
            ->addColumn('received_dt', function($tbl_wbs_sakidashi_issuance){
                $parts_preps = PartsPrep::where([
                        [
                            'wbs_kit_issuance_id','=',
                            $tbl_wbs_sakidashi_issuance->id
                        ],
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

                $result = "---";
                if ( $parts_preps->count() ) {
                    $result = $parts_preps[0]->received_at;
                }
                return $result;
            })
            ->addColumn('received_by', function($tbl_wbs_sakidashi_issuance){
                $parts_preps = PartsPrep::where([
                        [
                            'wbs_kit_issuance_id','=',
                            $tbl_wbs_sakidashi_issuance->id
                        ],
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
                $result = "---";
                if ( $parts_preps->count() ) {
                    $users = User::where('id', $parts_preps[0]->received_passed_by )->get();
                    if ( $users->count() ) {
                        $result = $users[0]->name;
                    }
                }
                return $result;
            })
            ->addColumn('status', function($tbl_wbs_sakidashi_issuance){
                $result = '<span data-sorter="0" class="badge badge-secondary font-weight-normal">To receive</span>';

                $parts_preps = PartsPrep::where([
                        [
                            'wbs_kit_issuance_id','=',
                            $tbl_wbs_sakidashi_issuance->id
                        ],
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
            ->addColumn('action', function($tbl_wbs_sakidashi_issuance){
                $parts_preps_id = 0;
                $disabled = 'disabled';
                $disabled_passed_btn = 'disabled';
                $disabled_failed_btn = 'disabled';
                $icon_partsprep = '';
                $with_partsprep = 0;
                $title = '';


                $disabled_passed_btn = '';
                $disabled_failed_btn = '';

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
                            $query->where('name', 'LIKE', '%parts prep%');
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

                // return $stations;

                if ( $stations->count() ) {
                    $icon_partsprep = '<i title="Has partsprep" class="fa fa-wrench text-secondary pl-2 float-right"></i>';
                    $with_partsprep = 1;
                }
                //---

                $parts_preps = PartsPrep::where([
                        [
                            'wbs_kit_issuance_id','=',
                            $tbl_wbs_sakidashi_issuance->id
                        ],
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

                if ( $parts_preps->count() ) {
                    $parts_preps_id = $parts_preps[0]->id;
                    // $icon_partsprep = ($parts_preps[0]->with_partsprep)?'<i title="Has partsprep" class="fa fa-wrench text-secondary pl-2 float-right"></i>':'';
                    $disabled_passed_btn = 'disabled';
                    $disabled_failed_btn = 'disabled';
                    if( $parts_preps[0]->status == 2 ){
                        $disabled_passed_btn = '';
                    }
                    if( $parts_preps[0]->status == 1 || $parts_preps[0]->status > 2 ){
                        $disabled = '';
                    }
                }
                

                //-----
                $hidden = ' hidden ';
                if( Auth::user()->position == 3 ){
                    $hidden = '';
                }
                //-----
                $result ='';
                $result.=$icon_partsprep;
                $result.='<div class="dropdown">';
                  $result.='<button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                  // $result.='<i class="fas fa-ellipsis-v fa-sm"></i>';
                  $result.='</button>';
                  $result.='<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';

                    $result.='<button '.$disabled.' title="Open details" class="dropdown-item btn_material_action btn_sakidashi_material_open_details btn btn-info btn-sm py-0"><i class="fa fa-info-circle fa-sm"></i> Details</button>';
                    $result.=' <button '.$disabled_passed_btn.' '.$hidden.' title="Received" class="dropdown-item btn_material_action btn_sakidashi_material_pass_details btn btn-success btn-sm py-0" data-lot-no="'.$tbl_wbs_sakidashi_issuance->tbl_wbs_sakidashi_issuance_item->lot_no.'"><i class="fa fa-check-circle fa-sm"></i> Received</button>';
                    // $result.=' <button '.$disabled_failed_btn.' '.$hidden.' title="Failed" class="dropdown-item btn_material_action btn_sakidashi_material_fail_details btn btn-warning btn-sm py-0"><i class="fa fa-exclamation-triangle fa-sm"></i> Failed</button>';

                  $result.='</div>';
                $result.='</div>';

                $result.=' <input type="hidden" class="col_material_id" value="'.$tbl_wbs_sakidashi_issuance->id.'">';
                // $result.=' <input type="hidden" class="col_material_code" value="'.$tbl_wbs_sakidashi_issuance->item.'">';
                $result.=' <input type="hidden" class="col_device_code" value="'.$tbl_wbs_sakidashi_issuance->device_code.'">';
                $result.=' <input type="hidden" class="col_material_po" value="'.$tbl_wbs_sakidashi_issuance->po_no.'">';
                $result.=' <input type="hidden" class="col_parts_preps_id" value="'.$parts_preps_id.'">';
                $result.=' <input type="hidden" class="col_with_partsprep" value="'.$with_partsprep.'">';
                return $result;
            })
            ->rawColumns(['status','action'])
            ->make(true);
    }

    public function fn_view_warehouse_issuance(Request $request){
        // $request_summary = RequestSummary::where('pono',$request['po_number'])
        // ->get();

        // if(!$request_summary){
        //     return "[]";
        // }

        $tbl_wbs_warehouse_mat_issuance_details = WBSWarehouseMatIssuanceDetails::with(
            'tbl_request_summary',
            'tbl_request_detail')
        ->whereHas('tbl_request_summary',function($query) use ($request){
            $query->where('pono',$request['po_number']);
        })
        ->get();

        return DataTables::of($tbl_wbs_warehouse_mat_issuance_details)
            ->addColumn('received_dt', function($tbl_wbs_warehouse_mat_issuance_details){
                $parts_preps = PartsPrep::where([
                        [
                            'wbs_kit_issuance_id','=',
                            $tbl_wbs_warehouse_mat_issuance_details->id
                        ],
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

                $result = "---";
                if ( $parts_preps->count() ) {
                    $result = $parts_preps[0]->received_at;
                }
                return $result;
            })
            ->addColumn('received_by', function($tbl_wbs_warehouse_mat_issuance_details){
                $parts_preps = PartsPrep::where([
                        [
                            'wbs_kit_issuance_id','=',
                            $tbl_wbs_warehouse_mat_issuance_details->id
                        ],
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
                $result = "---";
                if ( $parts_preps->count() ) {
                    $users = User::where('id', $parts_preps[0]->received_passed_by )->get();
                    if ( $users->count() ) {
                        $result = $users[0]->name;
                    }
                }
                return $result;
            })
            ->addColumn('status', function($tbl_wbs_warehouse_mat_issuance_details){
                $result = '<span data-sorter="0" class="badge badge-secondary font-weight-normal">To receive</span>';

                $parts_preps = PartsPrep::where([
                        [
                            'wbs_kit_issuance_id','=',
                            $tbl_wbs_warehouse_mat_issuance_details->id
                        ],
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
            ->addColumn('action', function($tbl_wbs_warehouse_mat_issuance_details) use ($request){
                $parts_preps_id = 0;
                $disabled = 'disabled';
                $disabled_passed_btn = 'disabled';
                $disabled_failed_btn = 'disabled';
                $icon_partsprep = '';
                $with_partsprep = 0;
                $title = '';


                $disabled_passed_btn = '';
                $disabled_failed_btn = '';

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
                    $with_partsprep = 1;
                }
                //---
                $parts_preps = PartsPrep::where([
                        [
                            'wbs_kit_issuance_id','=',
                            $tbl_wbs_warehouse_mat_issuance_details->id
                        ],
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

                if ( $parts_preps->count() ) {
                    $parts_preps_id = $parts_preps[0]->id;
                    // $icon_partsprep = ($parts_preps[0]->with_partsprep)?'<i title="Has partsprep" class="fa fa-wrench text-secondary pl-2 float-right"></i>':'';
                    $disabled_passed_btn = 'disabled';
                    $disabled_failed_btn = 'disabled';
                    if( $parts_preps[0]->status == 2 ){
                        $disabled_passed_btn = '';
                    }
                    if( $parts_preps[0]->status == 1 || $parts_preps[0]->status > 2 ){
                        $disabled = '';
                    }
                }
                

                //-----
                $hidden = ' hidden ';
                if( Auth::user()->position == 3 ){
                    $hidden = '';
                }
                //-----
                $result ='';
                $result.=$icon_partsprep;
                $result.='<div class="dropdown">';
                  $result.='<button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                  // $result.='<i class="fas fa-ellipsis-v fa-sm"></i>';
                  $result.='</button>';
                  $result.='<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';

                    $result.='<button '.$disabled.' title="Open details" class="dropdown-item btn_material_action btn_warehouse_material_open_details btn btn-info btn-sm py-0"><i class="fa fa-info-circle fa-sm"></i> Details</button>';
                    $result.=' <button '.$disabled_passed_btn.' '.$hidden.' title="Received" class="dropdown-item btn_material_action btn_warehouse_material_pass_details btn btn-success btn-sm py-0"><i class="fa fa-check-circle fa-sm"></i> Received</button>';
                    // $result.=' <button '.$disabled_failed_btn.' '.$hidden.' title="Failed" class="dropdown-item btn_material_action btn_warehouse_material_fail_details btn btn-warning btn-sm py-0"><i class="fa fa-exclamation-triangle fa-sm"></i> Failed</button>';

                  $result.='</div>';
                $result.='</div>';

                $result.=' <input type="hidden" class="col_material_id" value="'.$tbl_wbs_warehouse_mat_issuance_details->id.'">';
                // $result.=' <input type="hidden" class="col_device_code" value="'.$request_summary->device_code.'">';
                $result.=' <input type="hidden" class="col_material_po" value="'.$tbl_wbs_warehouse_mat_issuance_details->tbl_request_summary->pono.'">';
                $result.=' <input type="hidden" class="col_parts_preps_id" value="'.$parts_preps_id.'">';
                $result.=' <input type="hidden" class="col_with_partsprep" value="'.$with_partsprep.'">';
                return $result;
            })
            ->rawColumns(['status','action'])
            ->make(true);
    }

	public function fn_insert_materialissuance(Request $request){
        $result = $this->return_result(0,0,0,0);
        $user = User::where('employee_id', $request->txt_employee_number_scanner)
            ->where(function($q){
                $q->where('position',3)
                    ->orWhere('position',1);
            })
            ->get();
        if($user->count() > 0){
            DB::beginTransaction();
            try {
				$table = new MaterialIssuance;

				$table->tbl_wbs_material_kitting_id = $request->tbl_wbs_material_kitting_id;
				$table->created_by = $user[0]->id;
				$table->updated_by = $user[0]->id;
				$table->status = 1;

				$table->save();
                $result = $this->return_result(1,1,1,1);
                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                $result = $this->return_result(0,0,0,0);
            }
        }
        else{
            $result = $this->return_result(2,2,2,2);
        }
        return $result;
	}


}
