<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Model\oqcLotApp;
use App\Model\DlabelHistory;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Auth;


use App\Model\ProductionRuncard;
use App\Model\MaterialIssuanceSubSystem;
use App\Model\Device;

use App\Model\DLabelCheckerHistory;

//----
use App\Model\MaterialIssuance;
use App\Model\WBSKitIssuance;
// use App\Model\MaterialIssuanceSubSystem;
use App\Model\PartsPrep;


//----- for accessories
use App\Model\OQCPackingInspection;
use App\Model\oqcVIR;

//-----


use App\Model\C3Label;
use App\Model\C3LabelHistory;
use App\Model\C3LabelHistoryDetails;


use DataTables;
define('TIMEZONE', 'Asia/Manila');
date_default_timezone_set(TIMEZONE);


class C3LabelPrintingController extends Controller
{
    public function fn_view_c3labelprinting(Request $request)
    {
        return view('c3labelprinting');
    }
    public function fn_view_c3labelprintingtest(Request $request)
    {
        return view('c3labelprintingtest');
    }

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

	public function fn_select_c3_label_dt(Request $request)
	{

        $c3_label_history = C3Label::with('c3_label_history_hasone')
            ->where([
                [
                    'deleted_at','=',
                    null
                ],
            ])
            ->orderBy('created_at','desc')
            ->get();


        $lot_number = isset($request->lot_number)?$request->lot_number:'';
        if($lot_number){

            $c3_label_history = C3Label::with('c3_label_history_hasone')
                ->where([
                    [
                        'deleted_at','=',
                        null
                    ],
                ])
                ->whereHas('c3_label_history_hasone',function($query)use($lot_number){
                    $query->where('lot_number','like','%'.$lot_number.'%')
                        ->where('deleted_at',null);
                })
                ->orderBy('created_at','desc')
                ->get();

            
        }


        return DataTables::of($c3_label_history)
            ->addColumn('raw_action', function($c3_label_history){
            	$result = '';

                $title = 'Reprint';
                $btn = 'info';
                if ( $c3_label_history->print_type == 2 ) {
                    $title = 'Reprint Void Stickers';
                    $btn = 'primary';
                }

                $result.=' <button title="'.$title.'" type="button" class="btn td_btn_print btn-'.$btn.' btn-sm mb-1 py-0 px-1"><i class="fa fa-print"></i></button>';
                $result.=' <input type="hidden" class="td_id" value="'.$c3_label_history->id.'">';
                return $result;
            })
            ->rawColumns(['raw_action'])
            ->make(true);
	}

	public function fn_select_c3_devices(Request $request)
	{
        $c3_label = C3Label::where([
                [
                    'deleted_at','=',
                    null
                ],
            ])
            ->get();
        //---
        $parts_prep = PartsPrep::where([
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

        $parts_prep_temp = array();
        foreach ($parts_prep as $key => $value) {
        	array_push($parts_prep_temp, $value['wbs_kit_issuance_id']);

        }

        $transfer_slips = array();
        foreach ($parts_prep_temp as $key => $value) {
	        $wbs_kit_issuance = WBSKitIssuance::where([
	                [
	                    'id','=',
	                    $value
	                ],
	            ])
	            ->get()->take(1);
                if( count($wbs_kit_issuance) ){
        	          array_push($transfer_slips, $wbs_kit_issuance[0]['issue_no']);
                }
        }
		$transfer_slips = array_unique($transfer_slips);
		$transfer_slips = array_values($transfer_slips);


		$devices = array();
		foreach ($transfer_slips as $key => $value) {
	        $transfer_slips = MaterialIssuanceSubSystem::where([
	                [
	                    'issuance_no','=',
	                    $value
	                ],
	            ])
	            ->get()->take(1);
                if( count($transfer_slips) ){
                        array_push($devices, $transfer_slips[0]);
                }
		}

        return DataTables::of($devices)
            ->addColumn('raw_action', function($devices) use ($c3_label){
            	$result = '';

                $title = 'Print';
                $btn = 'info';
                $print_type = 1;
                $c3_label_collect = collect($c3_label)->where('issuance_no', $devices->issuance_no)->flatten(1);
                if ( $c3_label_collect->count() ) {
                    $title = 'Print for Void Stickers';
                    $btn = 'primary';
                    $sorter ='0'.$devices->issuance_no;
                    $print_type = 2;
                }
                else{
                    $sorter ='1'.$devices->issuance_no;
                }

                $result.=' <i data-sorter="'.$sorter.'"></i>';
                $result.=' <button title="'.$title.'" type="button" class="btn td_btn_print btn-'.$btn.' btn-sm py-0 px-1"><i class="fa fa-print"></i></button>';
                $result.=' <input type="hidden" class="td_print_type" value="'.$print_type.'">';
                return $result;
            })
            ->rawColumns(['raw_action'])
            ->make(true);
	}

    public function fn_select_whs_details($po_no)
    {
        $c3_label = C3Label::where([
                [
                    'deleted_at','=',
                    null
                ],
            ])
            ->get();
        //---
        $transfer_slips = MaterialIssuanceSubSystem::where([
                [
                    'po_no','=',
                    $po_no
                ],
            ])
            ->get()->take(1);
        return $transfer_slips;

        // return DataTables::of($transfer_slips)
        //     ->addColumn('raw_action', function($transfer_slips) use ($c3_label){
        //         $result = '';

        //         $title = 'Print';
        //         $btn = 'info';
        //         $print_type = 1;
        //         $c3_label_collect = collect($c3_label)->where('issuance_no', $transfer_slips->issuance_no)->flatten(1);
        //         if ( $c3_label_collect->count() ) {
        //             $title = 'Reprint for Void';
        //             $btn = 'warning';
        //             $sorter ='0'.$transfer_slips->issuance_no;
        //             $print_type = 2;
        //         }
        //         else{
        //             $sorter ='1'.$transfer_slips->issuance_no;
        //         }

        //         $result.=' <i data-sorter="'.$sorter.'"></i>';
        //         $result.=' <button title="'.$title.'" type="button" class="btn td_btn_print btn-'.$btn.' btn-sm"><i class="fa fa-print"></i></button>';
        //         $result.=' <input type="hidden" class="td_print_type" value="'.$print_type.'">';
        //         return $result;
        //     })
        //     ->rawColumns(['raw_action'])
        //     ->make(true);
    }

	public function select_devices_record($device_name){
		$table_records_arr = array();
        $table_records = Device::
            where(
                [
                    [
                        'name','=',
                        $device_name
                    ],
                    [
                        'status','=',
                        1
                    ],
                ]
            )
            ->get()->take(1);

        if(!$table_records->isEmpty()){
        	$table_records_arr['name'] = $table_records[0]->name;
        	$table_records_arr['huawei_p_n'] = $table_records[0]->huawei_p_n;
        	$table_records_arr['boxing'] = $table_records[0]->boxing;
        	$table_records_arr['lot_no_machine_code'] = $table_records[0]->lot_no_machine_code;
        }
        return $table_records_arr;
	}

    public function select_c3_label_record($id){
        $table_records_arr = array();
        $table_records = C3Label::
            where(
                [
                    [
                        'id','=',
                        $id
                    ],
                    [
                        'deleted_at','=',
                        null
                    ],
                ]
            )
            ->get();

        if(!$table_records->isEmpty()){
            $table_records_arr['issuance_no'] = $table_records[0]->issuance_no;
            $table_records_arr['po_no'] = $table_records[0]->po_no;
            $table_records_arr['device_code'] = $table_records[0]->device_code;
            $table_records_arr['device_name'] = $table_records[0]->device_name;
            $table_records_arr['kit_qty'] = $table_records[0]->kit_qty;
            $table_records_arr['customer_pn'] = $table_records[0]->customer_pn;
            $table_records_arr['boxing'] = $table_records[0]->boxing;
            $table_records_arr['machine_code'] = $table_records[0]->machine_code;
            $table_records_arr['created_at'] = $table_records[0]->created_at;
        }

        return $table_records_arr;
    }


    public function select_c3_label_history_record($c3_label_id){
        $table_records_arr = array();
        $table_records = C3LabelHistory::
            with('c3_label_history_details')
            ->where(
                [
                    [
                        'c3_label_id','=',
                        $c3_label_id
                    ],
                    [
                        'deleted_at','=',
                        null
                    ],
                ]
            )
            ->get();

        if(!$table_records->isEmpty()){
            $table_records_arr = $table_records;
        }
        return $table_records_arr;
    }

    public function fn_select_c3_label_history_details_dt(Request $request){
        $return = array();
        $tbody = '';
        $table_records = C3LabelHistoryDetails::with('user_created_by','user_received_by')
            ->where(
                [
                    [
                        'c3_label_history_id','=',
                        $request->td_id
                    ],
                    [
                        'deleted_at','=',
                        null
                    ],
                ]
            )
            ->get();

        if(!$table_records->isEmpty()){
            $ctr = 0;
            foreach ($table_records as $key => $value) {

                if( $request->module == 1 ){
                    if( $value->print_type >=4 ){
                        continue;
                    }
                }
                else{
                    if( $value->print_type <=3 ){
                        continue;
                    }
                }

                $print_type_str = '';
                switch ($value->print_type) {
                    case 1:
                        $print_type_str = '1st print';
                        break;
                    case 2:
                        $print_type_str = 'Reprint';
                        break;
                    case 3:
                        $print_type_str = 'Reprint for NG';
                        break;                    
                    default:
                        break;
                }
                $disabled = '';
                if( $value->received_by ){
                    $disabled = 'disabled';
                }

                $tbody .= '<tr>';
                    $tbody .= '<td>';
                    $tbody .= $ctr+1;
                    $tbody .= '</td>';
                    $tbody .= '<td>';
                    $tbody .= $print_type_str;
                    $tbody .= '</td>';
                    $tbody .= '<td>';
                    $tbody .= ($value->user_created_by)?$value->user_created_by->name:'';
                    $tbody .= '</td>';
                    $tbody .= '<td>';
                    $tbody .= $value->created_at;
                    $tbody .= '</td>';
                    $tbody .= '<td>';
                    $tbody .= $value->remarks;
                    $tbody .= '</td>';
                    $tbody .= '<td>';
                    $tbody .= ($value->user_received_by)?$value->user_received_by->name:'';
                    $tbody .= '</td>';
                    $tbody .= '<td>';
                    $tbody .= $value->received_at;
                    $tbody .= '</td>';
                    $tbody .= '<td>';
                    $tbody .= $value->remarks_received;
                    $tbody .= '</td>';
                $tbody .= '</tr>';
                $ctr++;
            }
        }
        $return['tbody'] = $tbody;
        return $return;
    }


    public function fn_select_c3_label_history_details_accessories_dt(Request $request){
        $return = array();
        $tbody = '';
        $table_records = C3LabelHistoryDetails::with('user_created_by','user_received_by')
            ->where(
                [
                    [
                        'c3_label_history_id','=',
                        $request->td_id
                    ],
                    [
                        'deleted_at','=',
                        null
                    ],
                ]
            )
            ->get();

        if(!$table_records->isEmpty()){
            $ctr = 0;
            foreach ($table_records as $key => $value) {

                if( $request->module == 1 ){
                    if( $value->print_type >=4 ){
                        continue;
                    }
                }
                else{
                    if( $value->print_type <=3 ){
                        continue;
                    }
                }

                $print_type_str = 'Print for accessories';
                $disabled = '';
                if( $value->received_by ){
                    $disabled = 'disabled';
                }

                $tbody .= '<tr>';
                    $tbody .= '<td>';
                    $tbody .= $ctr+1;
                    $tbody .= '</td>';
                    $tbody .= '<td>';
                    $tbody .= $print_type_str;
                    $tbody .= '</td>';
                    $tbody .= '<td>';
                    $tbody .= ($value->user_created_by)?$value->user_created_by->name:'';
                    $tbody .= '</td>';
                    $tbody .= '<td>';
                    $tbody .= $value->created_at;
                    $tbody .= '</td>';
                    $tbody .= '<td>';
                    $tbody .= $value->remarks;
                    $tbody .= '</td>';
                    $tbody .= '<td>';
                    $tbody .= $value->copies;
                    $tbody .= '</td>';
                $tbody .= '</tr>';
                $ctr++;
            }
        }
        $return['tbody'] = $tbody;
        return $return;
    }
    public function select_lot_number_ctr_last($device_name){
        $lot_number_ctr_last = 0;
        $table_records_arr = array();
        $table_records = C3LabelHistory::
            with('c3_label')
            ->where(
                [
                    [
                        'deleted_at','=',
                        null
                    ],
                ]
            )
            ->whereRaw(
                ' date(created_at) = "'.date('Y-m-d').'" '
            )
            ->whereHas('c3_label',function($query)use($device_name){
                $query->where('device_name',$device_name)
                    ->where('deleted_at',null);
            })
            ->orderBy('lot_number_ctr','desc')
            ->get()->take(1);
        if(!$table_records->isEmpty()){
            $lot_number_ctr_last = $table_records[0]['lot_number_ctr'];
        }

        return $lot_number_ctr_last;
    }

    public function fn_select_c3_label_content_to_print_partial(Request $request){
        $packing_date_required = date('Y-m-d H:i:s');

        $issuance_no            = $request->issuance_no;
        $customer_pn            = $request->customer_pn;
        $manufacture_pn         = $request->manufacture_pn;
        $lot_qty                = $request->lot_qty;
        $date_code              = $request->date_code;
        $country                = 'PHILIPPINES';
        $reel_lot_no            = $request->reel_lot_no;
        $numbering            = $request->numbering;


        $ctr = $this->select_lot_number_ctr_last($manufacture_pn);
        $ctr++;
        $td_lot_number_ctr = $ctr;

        //-------
        $arr_packing_months = [1,2,3,4,5,6,7,8,9,'X','Y','Z'];
        $last_no_of_the_year = date('Y',strtotime($packing_date_required))[3];
        $packing_month = $arr_packing_months[(integer)date('m',strtotime($packing_date_required)) - 1];
        $packing_date = date('d',strtotime($packing_date_required));
        //-----
        $reel_lot_no_date = substr($reel_lot_no, 0, 5);
        $machine_code = substr($reel_lot_no, -1);

        $serial_no = str_pad($ctr,2,"0", STR_PAD_LEFT);
        $reel_lot_no = $last_no_of_the_year . $packing_month . $packing_date . '-'. $serial_no . $machine_code;


        //-----
        $lbl = "";

        $lbl .= "^XA";
        $lbl .= "^FO100,40";
        $lbl .= "^CF 0,30";
        $lbl .= "^FD(P) Customer P/N: ".$customer_pn;
        $lbl .= "^FS";
        $lbl .= "^FO100,65";
        $lbl .= "^BY2";
        $lbl .= "^BCN, 26, N, N, N";
        $lbl .= "^FDP".$customer_pn;
        $lbl .= "^FS";

        $lbl .= "^FO100,95";
        $lbl .= "^CF 0,30";
        $lbl .= "^FD(1P) Manufacture P/N: ".$manufacture_pn;
        $lbl .= "^FS";

        $lbl .= "^FO100,120";
        $lbl .= "^BY2";
        $lbl .= "^BCN, 26, N, N, N";
        $lbl .= "^FD1P".$manufacture_pn;
        $lbl .= "^FS";
        $lbl .= "^FO100,150";
        $lbl .= "^CF 0,30";
        $lbl .= "^FD(Q) QTY: ".$lot_qty;
        $lbl .= "^FS";

        $lbl .= "^FO100,175";
        $lbl .= "^BY2";
        $lbl .= "^BCN, 26, N, N, N";
        $lbl .= "^FDQ".$lot_qty;
        $lbl .= "^FS";

        $lbl .= "^FO100,205";
        $lbl .= "^CF 0,30";
        $lbl .= "^FD(10D) Date Code: ".$date_code;
        $lbl .= "^FS";

        $lbl .= "^FO100,230";
        $lbl .= "^BY2";
        $lbl .= "^BCN, 26, N, N, N";
        $lbl .= "^FD10D".$date_code;
        $lbl .= "^FS";

        $lbl .= "^FO100,260";
        $lbl .= "^CF 0,30";
        $lbl .= "^FD(4L) Country of Origin : ".$country;
        $lbl .= "^FS";

        $lbl .= "^FO100,285";
        $lbl .= "^BY2";
        $lbl .= "^BCN, 26, N, N, N";
        $lbl .= "^FD4L".$country;
        $lbl .= "^FS";

        $lbl .= "^FO100,315";
        $lbl .= "^CF 0,30";
        $lbl .= "^FD(1T) LOT No: ".$reel_lot_no;
        $lbl .= "^FS";

        $lbl .= "^FO100,340";
        $lbl .= "^BY2";
        $lbl .= "^BCN, 26, N, N, N";
        $lbl .= "^FD1T".$reel_lot_no;
        $lbl .= "^FS";

        $lbl .= "^FO100,370";
        $lbl .= "^CF 0,30";
        $lbl .= "^FDManufacture: YAMAICHI Electronics";
        $lbl .= "^FS";

        $lbl .= "^FO100,395";
        $lbl .= "^CF 0,30";
        $lbl .= "^FDVendor: YAMAICHI Electronics";
        $lbl .= "^FS";

        $lbl .= "^XZ";

        // $label_str .= $lbl.(( $ctr +1==$ctr_total)? "":":-:");//adds a delimiter ":-:" if it is not the final sticker
        $tbody = '';
        $badge = '';
        $td_id = '';

        $print_type_next = 1;
        //---------
        $tbody .= '<tr>';
            $tbody .= '<td>';
            $tbody .= '<input type="checkbox" class="td_ckb mr-1">';
            $tbody .= $numbering+1;
            $tbody .= ' '.$badge;
            $tbody .= '<input type="hidden" class="td_id" value="'.$td_id.'">';
            $tbody .= '<input type="hidden" class="td_lot_number_ctr" value="'.$td_lot_number_ctr.'">';
            $tbody .= '<input type="hidden" class="td_print_type_next" value="'.$print_type_next.'">';
            $tbody .= '<input type="hidden" class="td_lbl_str" value="'.$lbl.'">';
            $tbody .= '</td>';
            $tbody .= '<td>';
            $tbody .= $issuance_no;
            $tbody .= '</td>';
            $tbody .= '<td>';
            $tbody .= $customer_pn;
            $tbody .= '</td>';
            $tbody .= '<td>';
            $tbody .= $manufacture_pn;
            $tbody .= '</td>';
            $tbody .= '<td>';
            $tbody .= $lot_qty;
            $tbody .= '</td>';
            $tbody .= '<td>';
            $tbody .= $date_code;
            $tbody .= '</td>';
            $tbody .= '<td>';
            $tbody .= $reel_lot_no;
            $tbody .= '</td>';
        $tbody .= '</tr>';

        $return['tbody'] = $tbody;
        // $return['to_receive_total'] = $to_receive_total;
        return $return;

    }


	public function fn_select_c3_label_content_to_print(Request $request){
        $to_receive_total = 0;
		$tbody = '';
        $data_to_pass = array();
		//--------
		//--------
		//--------
        $ctr_total = 0;
        $lot_qty = 0;
        $packing_date_required = date('Y-m-d H:i:s');
        // $packing_date_required = date('Y-m-d H:i:s',strtotime(date('Y-m-d').' +15 days'));

        if($request->td_id=='0'){
            $devices = $this->select_devices_record($request->device_name);
            if($devices){
                $data_to_pass['c3_label_id']    = 0;
                $data_to_pass['issuance_no']    = $request->issuance_no;
                $data_to_pass['po_no']          = $request->po_no;
                $data_to_pass['device_code']    = $request->device_code;
                $data_to_pass['device_name']    = $request->device_name;
                $data_to_pass['kit_qty']        = $request->kit_qty;
                $data_to_pass['customer_pn']    = $devices['huawei_p_n'];
                $data_to_pass['boxing']         = $devices['boxing'];
                $data_to_pass['machine_code']   = $devices['lot_no_machine_code'];
                //----------
                if( $devices['boxing'] ){
                    $ctr_total = ceil($request->kit_qty/$devices['boxing']);
                }

                $issuance_no            = $request->issuance_no;
                $customer_pn            = $devices['huawei_p_n'];
                $manufacture_pn         = $devices['name'];
                $lot_qty                = $devices['boxing'];
                $lot_no_machine_code    = $devices['lot_no_machine_code'];

                $ctr = $this->select_lot_number_ctr_last($request->device_name);
            }
        }
        elseif($request->td_id=='x'){
            $whs_details  = $this->fn_select_whs_details($request->po_no);
            if(!$whs_details->isEmpty()){
                $devices = $this->select_devices_record($whs_details[0]['device_name']);
                if($devices){
                    $data_to_pass['c3_label_id']    = 0;
                    $data_to_pass['issuance_no']    = $request->issuance_no;
                    $data_to_pass['po_no']          = $request->po_no;
                    $data_to_pass['device_code']    = $whs_details[0]['device_code'];
                    $data_to_pass['device_name']    = $whs_details[0]['device_name'];
                    $data_to_pass['kit_qty']        = $whs_details[0]['kit_qty'];
                    $data_to_pass['customer_pn']    = $devices['huawei_p_n'];
                    $data_to_pass['boxing']         = $devices['boxing'];
                    $data_to_pass['machine_code']   = $devices['lot_no_machine_code'];
                    //----------
                    // if( $devices['boxing'] ){
                    //     $ctr_total = ceil($whs_details[0]['kit_qty']/$devices['boxing']);
                    // }
                    $ctr_total = $request->ctr_total;

                    $issuance_no            = $request->issuance_no;
                    $customer_pn            = $devices['huawei_p_n'];
                    $manufacture_pn         = $devices['name'];
                    $lot_qty                = $devices['boxing'];
                    $lot_no_machine_code    = $devices['lot_no_machine_code'];

                    $ctr = $this->select_lot_number_ctr_last($whs_details[0]['device_name']);
                }
            }
        }
        else{
            $c3_label = $this->select_c3_label_record($request->td_id);
            if($c3_label){
                $data_to_pass['c3_label_id']    = $request->td_id;
                $data_to_pass['issuance_no']    = $c3_label['issuance_no'];
                $data_to_pass['po_no']          = $c3_label['po_no'];
                $data_to_pass['device_code']    = $c3_label['device_code'];
                $data_to_pass['device_name']    = $c3_label['device_name'];
                $data_to_pass['kit_qty']        = $c3_label['kit_qty'];
                $data_to_pass['customer_pn']    = $c3_label['customer_pn'];
                $data_to_pass['boxing']         = $c3_label['boxing'];
                $data_to_pass['machine_code']   = $c3_label['machine_code'];
                //----------
                if( $c3_label['boxing'] ){
                    $ctr_total = ceil($c3_label['kit_qty']/$c3_label['boxing']);
                }
                // $packing_date_required = $c3_label['created_at'];

                $issuance_no            = $c3_label['issuance_no'];
                $customer_pn            = $c3_label['customer_pn'];
                $manufacture_pn         = $c3_label['device_name'];
                $lot_qty                = $c3_label['boxing'];
                $lot_no_machine_code    = $c3_label['machine_code'];

                $ctr = $this->select_lot_number_ctr_last($c3_label['device_name']);
            }
        }
        //-------
        $arr_packing_months = [1,2,3,4,5,6,7,8,9,'X','Y','Z'];
        $last_no_of_the_year = date('Y',strtotime($packing_date_required))[3];
        $packing_month = $arr_packing_months[(integer)date('m',strtotime($packing_date_required)) - 1];
        $packing_date = date('d',strtotime($packing_date_required));
        //-----

        //-----
        $now            = strtotime( date('Y-m-d',strtotime($packing_date_required)) );
        $fy_start       = date('Y',strtotime($packing_date_required))."-01-01";
        $fy_start       = strtotime($fy_start);
        $datediff       = $now - $fy_start;
        $one_day    = (60 * 60) * 24;
        $one_week   = $one_day * 7;
        $new_year_day_start = date ( 'D', $fy_start );
  
        switch ($new_year_day_start){
          case "Mon"  : $additional_day = ($one_day * 0); break;
          case "Tue"  : $additional_day = ($one_day * 1); break;
          case "Wed"  : $additional_day = ($one_day * 2); break;
          case "Thu"  : $additional_day = ($one_day * 3); break;
          case "Fri"  : $additional_day = ($one_day * 4); break;
          case "Sat"  : $additional_day = ($one_day * 5); break;
          case "Sun"  : $additional_day = ($one_day * 6); break;
        }

        $datediff = $datediff + $additional_day;
        $date_code = date('y',strtotime($packing_date_required)). str_pad( ( floor($datediff / $one_week) ),2,0, STR_PAD_LEFT );
        //-----
        $country                = 'PHILIPPINES';

        $c3_label_history = $this->select_c3_label_history_record($request->td_id);

        $lot_qty_temp = $lot_qty;
        $date_code_temp = $date_code;
        for ($i=0; $i < $ctr_total; $i++) {
            $badge = '';
            $print_type_next = 1;
            if($i<count($c3_label_history)){
                $td_id = $c3_label_history[$i]['id'];
                $lot_qty = $c3_label_history[$i]['lot_qty'];
                $date_code = $c3_label_history[$i]['date_code'];
                $reel_lot_no = $c3_label_history[$i]['lot_number'];
                $td_lot_number_ctr = $c3_label_history[$i]['lot_number_ctr'];
                //---
                $to_receive = 0;
                $badge_code = 'badge-light';
                $print_ctr_total = 0;
                foreach ($c3_label_history[$i]['c3_label_history_details'] as $key => $value) {
                    $print_ctr_total++;

                    if($value['received_by']==null){
                        $to_receive++;
                        $badge_code = 'badge-warning';
                    }
                }
                $to_receive_total+=$to_receive;
                $print_type_next = 2;
                $badge = '<button type="button" class="btn btn-sm btn-info px-1 py-0 float-right td_btn_open">History';
                $badge .= '<span class="badge badge-light ml-1" title="Total Print">'.$print_ctr_total.'</span>';
                $badge .= '<span class="badge '.$badge_code.' ml-1" title="To Receive">'.$to_receive.'</span>';
                $badge .= '</button>';
            }
            else{
                $ctr++;
                $td_id = 0;
                $lot_qty = $lot_qty_temp;
                $date_code = $date_code_temp;
                //---
                $serial_no = str_pad($ctr,2,"0", STR_PAD_LEFT);
                $final_reel_lot_no = $last_no_of_the_year . $packing_month . $packing_date . '-'. $serial_no . $lot_no_machine_code;
                $reel_lot_no            = $final_reel_lot_no;
                $td_lot_number_ctr = $ctr;
            }


            //-----
            $lbl = "";

            $lbl .= "^XA";
            $lbl .= "^FO100,40";
            $lbl .= "^CF 0,30";
            $lbl .= "^FD(P) Customer P/N: ".$customer_pn;
            $lbl .= "^FS";
            $lbl .= "^FO100,65";
            $lbl .= "^BY2";
            $lbl .= "^BCN, 26, N, N, N";
            $lbl .= "^FDP".$customer_pn;
            $lbl .= "^FS";

            $lbl .= "^FO100,95";
            $lbl .= "^CF 0,30";
            $lbl .= "^FD(1P) Manufacture P/N: ".$manufacture_pn;
            $lbl .= "^FS";

            $lbl .= "^FO100,120";
            $lbl .= "^BY2";
            $lbl .= "^BCN, 26, N, N, N";
            $lbl .= "^FD1P".$manufacture_pn;
            $lbl .= "^FS";
            $lbl .= "^FO100,150";
            $lbl .= "^CF 0,30";
            $lbl .= "^FD(Q) QTY: ".$lot_qty;
            $lbl .= "^FS";

            $lbl .= "^FO100,175";
            $lbl .= "^BY2";
            $lbl .= "^BCN, 26, N, N, N";
            $lbl .= "^FDQ".$lot_qty;
            $lbl .= "^FS";

            $lbl .= "^FO100,205";
            $lbl .= "^CF 0,30";
            $lbl .= "^FD(10D) Date Code: ".$date_code;
            $lbl .= "^FS";

            $lbl .= "^FO100,230";
            $lbl .= "^BY2";
            $lbl .= "^BCN, 26, N, N, N";
            $lbl .= "^FD10D".$date_code;
            $lbl .= "^FS";

            $lbl .= "^FO100,260";
            $lbl .= "^CF 0,30";
            $lbl .= "^FD(4L) Country of Origin : ".$country;
            $lbl .= "^FS";

            $lbl .= "^FO100,285";
            $lbl .= "^BY2";
            $lbl .= "^BCN, 26, N, N, N";
            $lbl .= "^FD4L".$country;
            $lbl .= "^FS";

            $lbl .= "^FO100,315";
            $lbl .= "^CF 0,30";
            $lbl .= "^FD(1T) LOT No: ".$reel_lot_no;
            $lbl .= "^FS";

            $lbl .= "^FO100,340";
            $lbl .= "^BY2";
            $lbl .= "^BCN, 26, N, N, N";
            $lbl .= "^FD1T".$reel_lot_no;
            $lbl .= "^FS";

            $lbl .= "^FO100,370";
            $lbl .= "^CF 0,30";
            $lbl .= "^FDManufacture: YAMAICHI Electronics";
            $lbl .= "^FS";

            $lbl .= "^FO100,395";
            $lbl .= "^CF 0,30";
            $lbl .= "^FDVendor: YAMAICHI Electronics";
            $lbl .= "^FS";

            $lbl .= "^XZ";

            // $label_str .= $lbl.(( $ctr +1==$ctr_total)? "":":-:");//adds a delimiter ":-:" if it is not the final sticker
            //---------
            $tbody .= '<tr>';
                $tbody .= '<td>';
                $tbody .= '<input type="checkbox" class="td_ckb mr-1">';
                $tbody .= $i+1;
                $tbody .= ' '.$badge;
                $tbody .= '<input type="hidden" class="td_id" value="'.$td_id.'">';
                $tbody .= '<input type="hidden" class="td_lot_number_ctr" value="'.$td_lot_number_ctr.'">';
                $tbody .= '<input type="hidden" class="td_print_type_next" value="'.$print_type_next.'">';
                $tbody .= '<input type="hidden" class="td_lbl_str" value="'.$lbl.'">';
                $tbody .= '</td>';
                $tbody .= '<td>';
                $tbody .= $issuance_no;
                $tbody .= '</td>';
                $tbody .= '<td>';
                $tbody .= $customer_pn;
                $tbody .= '</td>';
                $tbody .= '<td>';
                $tbody .= $manufacture_pn;
                $tbody .= '</td>';
                $tbody .= '<td>';
                $tbody .= $lot_qty;
                $tbody .= '</td>';
                $tbody .= '<td>';
                $tbody .= $date_code;
                $tbody .= '</td>';
                $tbody .= '<td>';
                $tbody .= $reel_lot_no;
                $tbody .= '</td>';
            $tbody .= '</tr>';
        }


        $return['tbody'] = $tbody;
        $return['to_receive_total'] = $to_receive_total;
		$return['c3_label'] = $data_to_pass;
		return $return;
	}

    public function fn_select_c3_label_content_to_reprint(Request $request){
        $c3_label_history = C3LabelHistory::with('c3_label')
            ->where(
                [
                    [
                        'c3_label_id','=',
                        $request->td_id
                    ],
                    [
                        'deleted_at','=',
                        null
                    ],
                ]
            )
            ->get();

        if(!$table_records->isEmpty()){
            $table_records_arr['name'] = $table_records[0]->name;
            $table_records_arr['huawei_p_n'] = $table_records[0]->huawei_p_n;
            $table_records_arr['boxing'] = $table_records[0]->boxing;
            $table_records_arr['lot_no_machine_code'] = $table_records[0]->lot_no_machine_code;
        }
        return $table_records_arr;
    }

    public function fn_insert_c3_label_history(Request $request){
        $result = $this->return_result(0,0,0,0);
        $position_required = 4;
        if($request->print_mode == 3){//what is 3?
            $position_required = 2;//supervisor, any
        }
        if($request->print_type == 2){//for void
            $position_required = 5;//qc
        }
        $user = User::where('employee_id', $request->txt_employee_number_scanner)
            ->where(function($q)use($position_required){
                $q->where('position',$position_required)
                    ->orWhere('position',2)
                    ->orWhere('position',1);
            })
            ->get();
        if($user->count() > 0){
            $c3_label_id = $request['c3_label_id'];
            $date_time = date('Y-m-d H:i:s');

            if( $c3_label_id == 0 ){
                //insert
                DB::beginTransaction();
                try {
                    $c3_label_id = C3Label::insertGetId(
                        [
                            'issuance_no'=>$request['issuance_no'],
                            'po_no'=>$request['po_no'],
                            'device_code'=>$request['device_code'],
                            'device_name'=>$request['device_name'],
                            'kit_qty'=>$request['kit_qty'],
                            'customer_pn'=>$request['customer_pn'],
                            'boxing'=>$request['boxing'],
                            'machine_code'=>$request['machine_code'],
                            'print_type'=>$request['print_type'],
                            'created_at'=>$date_time,
                        ]
                        );
                    DB::commit();
                    //---
                } catch (Exception $e) {
                    DB::rollback();
                    $result = $this->return_result(0,0,0,0);
                }
            }
            foreach ($request['data_arr'] as $key => $value) {
                //---
                if($c3_label_id!=0){
                    $c3_label_history_id = $value['td_id'];
                    if($c3_label_history_id==0){
                        //insert
                        DB::beginTransaction();
                        try {
                            $c3_label_history_id = C3LabelHistory::insertGetId(
                                [
                                    'c3_label_id'=>$c3_label_id,
                                    'lot_qty'=>$value['lot_qty'],
                                    'date_code'=>$value['date_code'],
                                    'lot_number'=>$value['lot_number'],
                                    'lot_number_ctr'=>$value['td_lot_number_ctr'],
                                    'created_at'=>$date_time,
                                ]
                                );
                            DB::commit();
                            //---
                        } catch (Exception $e) {
                            DB::rollback();
                            $result = $this->return_result(0,0,0,0);
                        }
                    }
                    //---
                    if($c3_label_history_id!=0){
                        $print_type_next = $value['td_print_type_next'];
                        if($request->print_mode == 3){
                            $print_type_next = 3;
                        }
                        $copies = isset($request->copies)?$request->copies:1;
                        //insert
                        DB::beginTransaction();
                        try {
                            C3LabelHistoryDetails::insert(
                                [
                                    'c3_label_history_id'=>$c3_label_history_id,
                                    'print_type'=>$print_type_next,
                                    'copies'=>$copies,
                                    'remarks'=>$request->txt_print_remarks,
                                    // 'received_by'=>$user[0]->id,
                                    'created_by'=>$user[0]->id,
                                    'updated_by'=>$user[0]->id,
                                    'created_at'=>$date_time,
                                ]
                                );
                            DB::commit();
                            $result = $this->return_result(1,1,1,1);
                        } catch (Exception $e) {
                            DB::rollback();
                            $result = $this->return_result(0,0,0,0);
                        }
                        //---
                        //for accessories

                        if($print_type_next==4){
                            //update
                            DB::beginTransaction();
                            try {
                                C3LabelHistory::where('id',$c3_label_history_id)
                                    ->update(
                                        [
                                            'packing_code'=>$request['packing_code'],
                                        ]
                                    );
                                DB::commit();
                                $result = $this->return_result(1,1,1,1);
                            } catch (Exception $e) {
                                DB::rollback();
                                $result = $this->return_result(0,0,0,0);
                            }
                        }
                        //---
                    }
                    //---
                }
                //---
            }
        }
        else{
            $result = $this->return_result(2,2,"Needs Supervisor or OC staff's ID. Record not saved.",2);
        }
        return $result;
    }

    public function fn_insert_c3_label_history_orig(Request $request){
        $result = $this->return_result(0,0,0,0);
        $position_required = 4;
        if($request->print_mode == 3){//what is 3?
            $position_required = 2;//supervisor, any
        }
        if($request->print_type == 2){//for void
            $position_required = 5;//qc
        }
        $user = User::where('employee_id', $request->txt_employee_number_scanner)
            ->where(function($q)use($position_required){
                $q->where('position',$position_required)
                    ->orWhere('position',2)
                    ->orWhere('position',1);
            })
            ->get();
        if($user->count() > 0){
            $c3_label_id = $request['c3_label_id'];
            $date_time = date('Y-m-d H:i:s');

            if( $c3_label_id == 0 ){
                //insert
                DB::beginTransaction();
                try {
                    $c3_label_id = C3Label::insertGetId(
                        [
                            'issuance_no'=>$request['issuance_no'],
                            'po_no'=>$request['po_no'],
                            'device_code'=>$request['device_code'],
                            'device_name'=>$request['device_name'],
                            'kit_qty'=>$request['kit_qty'],
                            'customer_pn'=>$request['customer_pn'],
                            'boxing'=>$request['boxing'],
                            'machine_code'=>$request['machine_code'],
                            'print_type'=>$request['print_type'],
                            'created_at'=>$date_time,
                        ]
                        );
                    DB::commit();
                    //---
                } catch (Exception $e) {
                    DB::rollback();
                    $result = $this->return_result(0,0,0,0);
                }
            }
	        foreach ($request['data_arr'] as $key => $value) {
                //---
                if($c3_label_id!=0){
                    $c3_label_history_id = $value['td_id'];
                    if($c3_label_history_id==0){
                        //insert
                        DB::beginTransaction();
                        try {
                            $c3_label_history_id = C3LabelHistory::insertGetId(
                                [
                                    'c3_label_id'=>$c3_label_id,
                                    'lot_qty'=>$value['lot_qty'],
                                    'date_code'=>$value['date_code'],
                                    'lot_number'=>$value['lot_number'],
                                    'lot_number_ctr'=>$value['td_lot_number_ctr'],
                                    'created_at'=>$date_time,
                                ]
                                );
                            DB::commit();
                            //---
                        } catch (Exception $e) {
                            DB::rollback();
                            $result = $this->return_result(0,0,0,0);
                        }
                    }
                    //---
                    if($c3_label_history_id!=0){
                        $print_type_next = $value['td_print_type_next'];
                        if($request->print_mode == 3){
                            $print_type_next = 3;
                        }
                        //insert
                        DB::beginTransaction();
                        try {
                            C3LabelHistoryDetails::insert(
                                [
                                    'c3_label_history_id'=>$c3_label_history_id,
                                    'print_type'=>$print_type_next,
                                    'remarks'=>$request->txt_print_remarks,
                                    // 'received_by'=>$user[0]->id,
                                    'created_by'=>$user[0]->id,
                                    'updated_by'=>$user[0]->id,
                                    'created_at'=>$date_time,
                                ]
                                );
                            DB::commit();
                            $result = $this->return_result(1,1,1,1);
                        } catch (Exception $e) {
                            DB::rollback();
                            $result = $this->return_result(0,0,0,0);
                        }
                    }
                    //---
                }
                //---
	        }
        }
        else{
            $result = $this->return_result(2,2,"Needs Supervisor or OC staff's ID. Record not saved.",2);
        }
        return $result;
    }
    //-----
    //-----
    //-----
    public function fn_select_to_receive_dt(Request $request)
    {
        $datatable = C3LabelHistoryDetails::
            with('c3_label_history','c3_label_history.c3_label','user_created_by','user_received_by')
            ->where([
                [
                    'deleted_at','=',
                    null
                ],
            ])
            ->whereHas('c3_label_history', function($query)use($request){
                $query->where([
                    [
                        'c3_label_id','=',
                        $request->c3_label_id
                    ],
                ]);
            })
            ->orderBy('received_at','asc')
            ->orderBy('created_at','desc')
            ->get();
        return DataTables::of($datatable)
            ->addColumn('raw_action', function($datatable){
                $result = '';

                $disabled = '';
                $badge = '';
                if($datatable->received_by != null){
                    $disabled = 'disabled';
                    $badge = '<span class="badge badge-success">Received</span>';
                }

                $result.= '<input '.$disabled.' type="checkbox" class="td_ckb mr-1">';
                $result.=$badge;
                $result.=' <input type="hidden" class="td_id" value="'.$datatable->id.'">';
                return $result;
            })
            ->addColumn('raw_created_by', function($datatable){
                $result = '';

                if($datatable->user_created_by){
                    $result = $datatable->user_created_by->name;
                }

                return $result;
            })
            ->addColumn('raw_received_by', function($datatable){
                $result = '';

                if($datatable->user_received_by){
                    $result = $datatable->user_received_by->name;
                }

                return $result;
            })
            ->addColumn('raw_print_type', function($datatable){
                $result = '';

                switch ($datatable->print_type) {
                    case 1:
                        $result = '1st print';
                        break;
                    case 2:
                        $result = 'Reprint';
                        break;
                    case 3:
                        $result = 'Reprint for NG';
                        break;

                    default:
                        break;
                }
                return $result;
            })
            ->rawColumns(['raw_action'])
            ->make(true);
    }

    public function fn_update_c3_label_history_details_receive(Request $request){

        $result = $this->return_result(0,0,0,0);
        $user = User::where('employee_id', $request->txt_employee_number_scanner)
            // ->where(function($q){
            //     $q->where('position',4)
            //         ->orWhere('position',2)
            //         ->orWhere('position',1);
            // })
            ->get();
        if($user->count() > 0){
            $date_time = date('Y-m-d H:i:s');
            foreach ($request['data_arr'] as $key => $value) {
                //update
                DB::beginTransaction();
                try {
                    C3LabelHistoryDetails::where('id',$value['td_id'])
                        ->update(
                            [
                                'received_by'=>$user[0]->id,
                                'received_at'=>$date_time,
                                'remarks_received'=>$request->txt_receive_remarks,
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
	//-----------------------------
	//----------------------------- FOR ACCESSORIES
	//-----------------------------

    public function fn_view_c3labelprintingforbox(Request $request)
    {
        return view('c3labelprintingforbox');
    }


    public function fn_select_packing_code_dt(Request $request)
    {
        $packing_codes_arr = array();
        $packing_codes_obj = C3LabelHistory::select('packing_code')->where([
                // [
                //     'packing_code','!=',
                //     null
                // ],
                [
                    'deleted_at','=',
                    null
                ],
            ])
            ->get();

        foreach ($packing_codes_obj as $key => $value) {
            array_push($packing_codes_arr, $value['packing_code']);
        }
        $packing_codes_arr = array_unique( $packing_codes_arr );

        $packing_codes = OQCPackingInspection::
            where([
                [
                    'status','=',
                    1
                ],
            ])
            ->orWhere([
                [
                    'status','=',
                    2
                ],
            ])
            ->orWhere([
                [
                    'status','=',
                    4
                ],
            ])
            ->orderBy('pack_code_no','desc')
            ->get();

        return DataTables::of($packing_codes)
        ->addColumn('pack_code', function($packing_code){
                $result = '';

                return $result;

        })
        ->addColumn('raw_action', function($packing_code)use($packing_codes_arr){
                $result = '';

                $title = 'Print';
                $btn = 'info';
                // if ( $c3_label_history->print_type == 2 ) {
                //     $title = 'Reprint Void Stickers';
                //     $btn = 'primary';
                // }

                if( in_array($packing_code->pack_code_no, $packing_codes_arr) ){
                    $title = 'Print again';
                    $btn = 'primary';
                }

                $result.=' <button title="'.$title.'" type="button" class="btn td_btn_print btn-'.$btn.' btn-sm py-0 px-1"><i class="fa fa-print"></i></button>';
                $result.=' <input type="hidden" class="td_packincode" value="'.$packing_code->pack_code_no.'">';
                return $result;
        })
        ->rawColumns(['raw_action'])
        ->make(true);
    }


    public function select_c3_label_record_by_lot_number($arr){
        $table_records_arr = array();
        $table_records = C3LabelHistory::with('c3_label','c3_label_history_details')
            ->whereIn(
                'lot_number',
                $arr
            )
            ->where(
                [
                    [
                        'deleted_at','=',
                        null
                    ],
                ]
            )
            ->get();

        if(!$table_records->isEmpty()){
            $table_records_arr = $table_records;
        }

        return $table_records_arr;
    }

    public function fn_select_c3_label_content_to_print_accessories(Request $request){
        $td_po_no = substr($request->td_po_no, 0, 10);
        $lot_numbers_rec = oqcVIR::with('oqclotapp_details')
            ->where([
                [
                    'packing_code','=',
                    $request->td_packincode
                ],
                [
                    'c_lot_batch_no','like',
                    '%'.$td_po_no.'%'
                ],
            ])
            ->get();

        $lot_numbers = array();
        foreach ($lot_numbers_rec as $key => $value) {
            array_push($lot_numbers, $value['oqclotapp_details']->reel_lot);

        }
        //-----
        $tbody = '';
        $data_to_pass = array();

        $i = 0;
        $c3_label_arr = $this->select_c3_label_record_by_lot_number($lot_numbers);
        // return $c3_label;
        foreach ($c3_label_arr as $key => $c3_label) {
            $data_arr = array();

            $data_to_pass['c3_label_id']    = 1;//$request->td_id;//need 1 so it will not be saved again in history details
            $data_to_pass['issuance_no']    = $c3_label['c3_label']['issuance_no'];
            $data_to_pass['po_no']          = $c3_label['c3_label']['po_no'];
            $data_to_pass['device_code']    = $c3_label['c3_label']['device_code'];
            $data_to_pass['device_name']    = $c3_label['c3_label']['device_name'];
            $data_to_pass['kit_qty']        = $c3_label['c3_label']['kit_qty'];
            $data_to_pass['customer_pn']    = $c3_label['c3_label']['customer_pn'];
            $data_to_pass['boxing']         = $c3_label['c3_label']['boxing'];
            $data_to_pass['machine_code']   = $c3_label['c3_label']['machine_code'];
            $data_to_pass['packing_code']   = $request->td_packincode;

            $badge = '';
            $print_type_next = 4;

            $td_id = $c3_label['id'];
            $lot_qty = $c3_label['lot_qty'];
            $date_code = $c3_label['date_code'];
            $reel_lot_no = $c3_label['lot_number'];
            $td_lot_number_ctr = $c3_label['lot_number_ctr'];
            //---
            $print_ctr_total = 0;
            foreach ($c3_label['c3_label_history_details'] as $key => $value) {
                if($value['print_type']>=4){
                    $print_ctr_total++;
                }
            }
            if( $print_ctr_total > 0 ){
                // $print_type_next = 5;//still 4 since all are accessories
            }
            $badge = '<button type="button" class="btn btn-sm btn-info px-1 py-0 float-right td_btn_open">History';
            $badge .= '<span class="badge badge-light ml-1" title="Total Print">'.$print_ctr_total.'</span>';
            $badge .= '</button>';

            //---
            $data_arr['country']                = 'PHILIPPINES';
            $data_arr['issuance_no']            = $c3_label['c3_label']['issuance_no'];
            $data_arr['customer_pn']            = $c3_label['c3_label']['customer_pn'];
            $data_arr['manufacture_pn']         = $c3_label['c3_label']['device_name'];
            $data_arr['lot_qty']                = $c3_label['c3_label']['boxing'];
            $data_arr['lot_no_machine_code']    = $c3_label['c3_label']['machine_code'];

            $data_arr['badge']    = $badge;
            $data_arr['td_id']    = $td_id;
            $data_arr['td_lot_number_ctr']    = $td_lot_number_ctr;
            $data_arr['print_type_next']    = $print_type_next;
            $data_arr['date_code']    = $date_code;
            $data_arr['reel_lot_no']    = $reel_lot_no;



            $data_arr['i']                    = $i;

            //---
            $tbody .= $this->fn_generate_sticker($data_arr);
            $i++;

        }

        $return['tbody'] = $tbody;
        $return['c3_label'] = $data_to_pass;
        return $return;




    }

    public function fn_generate_sticker($data_arr){

        //-----
        $lbl = "";

        $lbl .= "^XA";
        $lbl .= "^FO100,40";
        $lbl .= "^CF 0,30";
        $lbl .= "^FD(P) Customer P/N: ".$data_arr['customer_pn'];
        $lbl .= "^FS";
        $lbl .= "^FO100,65";
        $lbl .= "^BY2";
        $lbl .= "^BCN, 26, N, N, N";
        $lbl .= "^FDP".$data_arr['customer_pn'];
        $lbl .= "^FS";

        $lbl .= "^FO100,95";
        $lbl .= "^CF 0,30";
        $lbl .= "^FD(1P) Manufacture P/N: ".$data_arr['manufacture_pn'];
        $lbl .= "^FS";

        $lbl .= "^FO100,120";
        $lbl .= "^BY2";
        $lbl .= "^BCN, 26, N, N, N";
        $lbl .= "^FD1P".$data_arr['manufacture_pn'];
        $lbl .= "^FS";
        $lbl .= "^FO100,150";
        $lbl .= "^CF 0,30";
        $lbl .= "^FD(Q) QTY: ".$data_arr['lot_qty'];
        $lbl .= "^FS";

        $lbl .= "^FO100,175";
        $lbl .= "^BY2";
        $lbl .= "^BCN, 26, N, N, N";
        $lbl .= "^FDQ".$data_arr['lot_qty'];
        $lbl .= "^FS";

        $lbl .= "^FO100,205";
        $lbl .= "^CF 0,30";
        $lbl .= "^FD(10D) Date Code: ".$data_arr['date_code'];
        $lbl .= "^FS";

        $lbl .= "^FO100,230";
        $lbl .= "^BY2";
        $lbl .= "^BCN, 26, N, N, N";
        $lbl .= "^FD10D".$data_arr['date_code'];
        $lbl .= "^FS";

        $lbl .= "^FO100,260";
        $lbl .= "^CF 0,30";
        $lbl .= "^FD(4L) Country of Origin : ".$data_arr['country'];
        $lbl .= "^FS";

        $lbl .= "^FO100,285";
        $lbl .= "^BY2";
        $lbl .= "^BCN, 26, N, N, N";
        $lbl .= "^FD4L".$data_arr['country'];
        $lbl .= "^FS";

        $lbl .= "^FO100,315";
        $lbl .= "^CF 0,30";
        $lbl .= "^FD(1T) LOT No: ".$data_arr['reel_lot_no'];
        $lbl .= "^FS";

        $lbl .= "^FO100,340";
        $lbl .= "^BY2";
        $lbl .= "^BCN, 26, N, N, N";
        $lbl .= "^FD1T".$data_arr['reel_lot_no'];
        $lbl .= "^FS";

        $lbl .= "^FO100,370";
        $lbl .= "^CF 0,30";
        $lbl .= "^FDManufacture: YAMAICHI Electronics";
        $lbl .= "^FS";

        $lbl .= "^FO100,395";
        $lbl .= "^CF 0,30";
        $lbl .= "^FDVendor: YAMAICHI Electronics";
        $lbl .= "^FS";

        $lbl .= "^XZ";

        //---------
        $tbody = "";
        $tbody .= '<tr>';
            $tbody .= '<td>';
            $tbody .= '<input type="checkbox" class="td_ckb mr-1">';
            $tbody .= $data_arr['i']+1;
            $tbody .= ' '.$data_arr['badge'];
            $tbody .= '<input type="hidden" class="td_id" value="'.$data_arr['td_id'].'">';
            $tbody .= '<input type="hidden" class="td_lot_number_ctr" value="'.$data_arr['td_lot_number_ctr'].'">';
            $tbody .= '<input type="hidden" class="td_print_type_next" value="'.$data_arr['print_type_next'].'">';
            $tbody .= '<input type="hidden" class="td_lbl_str" value="'.$lbl.'">';
            $tbody .= '</td>';
            $tbody .= '<td>';
            $tbody .= $data_arr['issuance_no'];
            $tbody .= '</td>';
            $tbody .= '<td>';
            $tbody .= $data_arr['customer_pn'];
            $tbody .= '</td>';
            $tbody .= '<td>';
            $tbody .= $data_arr['manufacture_pn'];
            $tbody .= '</td>';
            $tbody .= '<td>';
            $tbody .= $data_arr['lot_qty'];
            $tbody .= '</td>';
            $tbody .= '<td>';
            $tbody .= $data_arr['date_code'];
            $tbody .= '</td>';
            $tbody .= '<td>';
            $tbody .= $data_arr['reel_lot_no'];
            $tbody .= '</td>';
        $tbody .= '</tr>';

        return $tbody;
    }

}
