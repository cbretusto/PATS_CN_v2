<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Model\oqcLotApp;
use App\Model\DlabelHistory;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;
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
use App\oqcVIR1;
use App\Model\oqcVIR;

//-----


use App\Model\C3Label;
use App\Model\C3LabelHistory;
use App\Model\C3LabelHistoryDetails;


// ---- for c3 huawei
use App\C3LabelHuawei;


use DateTime;

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
                    'created_at','>',
                    date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s').' -30 days'))//max days a po runs, use for reprinting for void
                ],
                [
                    'deleted_at','=',
                    null
                ],
            ])
            ->get();
        //---
        $parts_prep = PartsPrep::where([
                [
                    'received_passed_at','>',
                    date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s').' -30 days'))//max days a po runs, use for reprinting for void
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
            // return $table_records;
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
        $result = $this->return_result(0,0,0,0);
        $position_required = 5;
        $user = User::where('employee_id', $request->txt_employee_number_scanner)
            ->where(function($q)use($position_required){
                $q->where('position',$position_required)
                    ->orWhere('position',7)
                    ->orWhere('position',8)
                    ->orWhere('position',2)
                    ->orWhere('position',1);
            })
            ->get();
        if($user->count() <= 0){
            $return['tbody'] = '';
            $return['alert_msg'] = $this->return_result(2,2,"Needs QC or Prod. Supervisor / PPC / Inspector's ID.",2);
            return $return;
        }

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
                // $data_to_pass['machine_code']   = $devices['lot_no_machine_code'];
                //----------
                if( $devices['boxing'] ){
                    $ctr_total = ceil($request->kit_qty/$devices['boxing']);
                }

                $issuance_no            = $request->issuance_no;
                $customer_pn            = $devices['huawei_p_n'];
                $manufacture_pn         = $devices['name'];
                $lot_qty                = $devices['boxing'];
                $lot_no_machine_code    = $devices['lot_no_machine_code'];

                // Pass the $request->device_name that came from $('#tbl_devices').on('click','.td_btn_print',function(){} with the 'device_name': $(this).closest('tr').find('td:eq(4)').text()
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
                    // $data_to_pass['machine_code']   = $devices['lot_no_machine_code'];
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
        /*
            Auto generated Lot Number column in tbl_devices(Table)
        */
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
        // $date_code = date('y',strtotime($packing_date_required)). str_pad( ( floor($datediff / $one_week) ),2,0, STR_PAD_LEFT );
        //-----
        $datecode_date = new DateTime($packing_date_required);
        $datecode_ww = $datecode_date->format("W");

        // $date_code = date('y',strtotime($packing_date_required)). str_pad( ( floor($datecode_ww) ),2,0, STR_PAD_LEFT );
        //-----
        $day_current = date('Y-m-d');
        $day_start_ww1 = date('Y-01-01',strtotime($day_current));
        $day_end_ww1=$day_start_ww1;
        for ($day_end_ww1=$day_start_ww1; date('w',strtotime($day_end_ww1)) != 6; $day_end_ww1 = date('Y-m-d',strtotime($day_end_ww1.'+ 1 day'))) { 
        }
        $ww = 1;
        for ($day_ctr=$day_end_ww1; $day_ctr < $day_current; $day_ctr = date('Y-m-d',strtotime($day_ctr.'+ 7 days'))) { 
            $ww++;
        }
        if($ww>5){
            if( date('m',strtotime($day_ctr))==1 ){
                $ww--;
            }
        }
        $date_code = date('y',strtotime($packing_date_required)). str_pad( ( $ww ),2,0, STR_PAD_LEFT );
        //-----
        $country                = 'PHILIPPINES';

        $c3_label_history = $this->select_c3_label_history_record($request->td_id);

        $lot_qty_temp = $lot_qty;
        $date_code_temp = $date_code;
        if($request->td_id!='0'&&$request->td_id!='x'){
            $ctr_total = $ctr_total+10;
        }
        // $counter = 1; // connected to static reel_lot_no
        for ($i=0; $i < $ctr_total; $i++) {//10 is just in case manual add/partial
            if($request->td_id!='0'&&$request->td_id!='x'){
                if(!isset($c3_label_history[$i])){
                    continue;
                }
            }

            $badge = '';
            $print_type_next = 1;
            if($i<count($c3_label_history)){ // This will get the details of c3_label_history -JD/CP
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
            else{ // This will execute if c3_label_history data is empty for auto generated Reel Lot -JD/CP
                $ctr++;
                $td_id = 0;
                $lot_qty = $lot_qty_temp;
                $date_code = $date_code_temp;
                //---
                $serial_no = str_pad($ctr,2,"0", STR_PAD_LEFT);
                $final_reel_lot_no = $last_no_of_the_year . $packing_month . $packing_date . '-'. $serial_no . $lot_no_machine_code;
                $reel_lot_no            = $final_reel_lot_no;

                // For static value of Reel Lot # to resolve late printing 02-09-2022 -JD/CP
                // $reel_lot_no            = "2202-0". $counter ."X";
                // $counter++;

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


    // To insert in c3_label table and c3_label_history(Note: this function is used in c3labelprinting & c3labelprintingforbox)
    public function fn_insert_c3_label_history(Request $request){
        $result = $this->return_result(0,0,0,0);
        $position_required = 4; // 4-Material Handler
        if($request->print_mode == 3){ // what is 3?
            $position_required = 2; // 2-QC Supervisor
        }
        if($request->print_type == 2){ // 1-normal print 2-for void
            $position_required = 5; // 5-Inspector
        }
        $user = User::where('employee_id', $request->txt_employee_number_scanner)
            ->where(function($q)use($position_required){
                $q->where('position',$position_required)
                    ->orWhere('position',7)
                    ->orWhere('position',8)
                    ->orWhere('position',2)
                    ->orWhere('position',1);
            })
            ->get();
        if($user->count() > 0){
            $c3_label_id = $request['c3_label_id'];  // default 0 for insert in C3Label -JD
            $date_time = date('Y-m-d H:i:s');

            // This c3_label_id(variable) will be use in c3labelprinting(views) blade -JD
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

            // This will be use in c3labelprintingforbox(views) blade -JD
            // data_arr is in function insert_c3_label_history() in c3labelprintingforbox(Views) -JD
            foreach ($request['data_arr'] as $key => $value) {
                // This will be use in c3labelprintingforbox(views) blade always set to 1 value for update -JD
                if($c3_label_id!=0){
                    $c3_label_history_id = $value['td_id']; // id of the data in c3_label_history
                    if($c3_label_history_id==0){
                        /*
                            old comment c3_label_history_id(variable) Set default to 0, this is mutable/changeable if the below query get at least one data then that id will be use to update -JD
                        */
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
                                    'is_huawei'=>0, // Added by -JD
                                ]
                                );
                            DB::commit();
                            //---
                        } catch (Exception $e) {
                            DB::rollback();
                            $result = $this->return_result(0,0,0,0);
                        }
                    }
                    
                    // If the data is available after the query on the top c3_label_history_id(variable) then that id will be use to update the packing_code(column) in the c3_label_history  -JD
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


                            C3LabelHistory::where('c3_label_huawei_id',$c3_label_history_id)
                            ->update(
                                [
                                    'packing_code' => $request['packing_code'],
                                ]
                            );
                            DB::commit();
                            $result = $this->return_result(1,1,1,1);
                        } catch (Exception $e) {
                            DB::rollback();
                            $result = $this->return_result(0,0,0,0);
                        }


                        /*
                            After btn_print(Normal Print(button)) is clicked in the c3labelprintingforbox(views)
                            this will be executed to update the packing_code(column) in the c3_label_history(table)
                            For accessories
                        */

                        // Commented 05/17/2022 -- CP

                        // if($print_type_next==4){
                        //     //update
                        //     DB::beginTransaction();
                        //     try {
                        //         C3LabelHistory::where('c3_label_huawei_id',$c3_label_history_id)
                        //             ->update(
                        //                 [
                        //                     'packing_code'=>$request['packing_code'],
                        //                 ]
                        //             );
                        //         DB::commit();
                        //         $result = $this->return_result(1,1,1,1);
                        //     } catch (Exception $e) {
                        //         DB::rollback();
                        //         $result = $this->return_result(0,0,0,0);
                        //     }
                        // }
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
        // This $packing_codes_arr(variable) will be used in "Print Again" button, packing_code(c3_label_history) to match in OQCPackingInspection -JD
        $packing_codes_arr = array();
        $packing_codes_obj = C3LabelHistory::select('packing_code')->where([
                // [
                //     'packing_code','!=',
                //     null
                // ],
                // [
                //     'is_huawei','=',
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
        $packing_codes_arr = array_unique( $packing_codes_arr ); // Get unique packing codes only to match in OQCPackingInspection packing codes
        // return $packing_codes_arr;

        /*
            Use this logic -JD
        */
        // $c3_label_is_huawei = C3LabelHistory::where([
        //         [
        //             'is_huawei','=',
        //             1
        //         ],
        //         [
        //             'deleted_at','=',
        //             null
        //         ],
        //     ])
        //     ->value('is_huawei');
        // return $c3_label_is_huawei;

        $packing_codes = OQCPackingInspection:: // Get OQCPackingInspection packing codes
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
        ->addColumn('raw_action', function($packing_code)use($packing_codes_arr){ // Add this $c3_label_is_huawei(variable) in use() function for another condition
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

                // -JD
                // if($c3_label_is_huawei == 1){
                //     $title = 'Print for Huawei';
                //     $btn = 'secondary';
                // }

                // Uncomment this for another condition -JD
                // if($c3_label_is_huawei[0]->is_huawei == 0){
                //     $title = 'Print for Huawei';
                //     $btn = 'secondary';
                // }

                $result.=' <button title="'.$title.'" type="button" class="btn td_btn_print btn-'.$btn.' btn-sm py-0 px-1"><i class="fa fa-print"></i></button>';
                $result.=' <input type="hidden" class="td_packincode" value="'.$packing_code->pack_code_no.'">'; // this will be use to search packing_code in select_c3_label_content_to_print_accessories
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
            ->where('is_huawei', null)
            ->get();

        if(!$table_records->isEmpty()){
            $table_records_arr = $table_records;
        }

        return $table_records_arr;
    }

    public function select_c3_label_record_by_lot_number_for_new_huawei($arr){
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
            ->where('is_huawei', 1) // Commented 05-17-2022
            ->get();

        if(!$table_records->isEmpty()){
            $table_records_arr = $table_records;
        }

        return $table_records_arr;
    }


    public function fn_select_c3_label_content_to_print_accessories(Request $request){
        $tbody = '';
        $data_to_pass = array();
        $td_po_no = substr($request->td_po_no, 0, 10);

        $get_huawei_data = C3LabelHuawei::where('po_no', $request->td_po_no)->get(); //query para makuha ang data galing sa c3_label_huaweis table

        if(isset($get_huawei_data) && count($get_huawei_data) > 0){
            // return $get_huawei_data;
            $huawei_po_no = substr($get_huawei_data[0]->po_no, 0, 10); // 4502295702
            $get_huawei_data_with_oqcvir = oqcVIR1::with('oqclotapp_details')
            ->where([
                [
                    'packing_code','=',
                    $request->td_packincode
                ],
                [
                    'c_lot_batch_no','like',
                    '%'.$huawei_po_no.'%'
                ],
            ])
            // ->whereHas('oqclotapp_details', function($query) use($userId){
            //     $query->where('user_id', $userId);
            // })
            ->has('oqclotapp_details', '>', 0) // -JD 7-18-2022
            ->get();

            // return $get_huawei_data_with_oqcvir; // -JD 7-18-2022

            /*
                Check if isset to avoid error if no PO
            */
            if(isset($get_huawei_data_with_oqcvir)){
                if(count($get_huawei_data_with_oqcvir)>0){
                    // huawei only
                    
                    $lot_number_huawei = array();
                    $po_huawei = array();
                    foreach ($get_huawei_data_with_oqcvir as $get_lot_number_from_huawei_data_with_oqcvir) {
                        array_push($lot_number_huawei, $get_lot_number_from_huawei_data_with_oqcvir['oqclotapp_details']->reel_lot);
                        array_push($po_huawei, $get_lot_number_from_huawei_data_with_oqcvir['oqclotapp_details']->po_no);
                    }
                    // return $lot_number_huawei;
                    // return $po_huawei;
    
                    $table_records = C3LabelHistory::with([
                        'c3_label_huawei_info',
                    ])
                    ->whereIn(
                        'lot_number',
                        $lot_number_huawei
                    )
                    ->where(
                        [
                            [
                                'deleted_at','=',
                                null
                            ],
                        ]
                    )
                    ->where('is_huawei', 1)
                    ->get();
    
                    $table_records = collect($table_records)->whereIn('c3_label_huawei_info.po_no', $po_huawei);
                    
                    // return $table_records;
    
                    $i = 0;
                    foreach($table_records as $huawei){
                        $data_arr['country']                = 'PHILIPPINES';
                        $data_arr['item_code']              = $huawei['c3_label_huawei_info']['item_code'];
                        $data_arr['pn']                     = $huawei['c3_label_huawei_info']['pn'];
                        $data_arr['mpn']                    = $huawei['c3_label_huawei_info']['mpn'];
                        $data_arr['qty']                    = $huawei['c3_label_huawei_info']['qty'];
                        $data_arr['date']                   = $huawei['c3_label_huawei_info']['date'];
                        $data_arr['lotno']                  = $huawei['c3_label_huawei_info']['lotno'].'-'.$huawei['c3_label_huawei_info']['lotno_extension'];
                        $data_arr['print_lot']              = $huawei['c3_label_huawei_info']['print_lot'];
                        $data_arr['print_lot_quantity']     = $huawei['c3_label_huawei_info']['print_lot_quantity'];
                        $data_arr['unique_number']          = $huawei['c3_label_huawei_info']['unique_number'];
                        $data_arr['max_row']                = $huawei['c3_label_huawei_info']['max_row'];
            
                        $print_type_next = 4;
                        $data_arr['print_type_next']    = $print_type_next; // Accessories
                        $data_arr['huawei_id']              = $huawei['c3_label_huawei_info']['id'];
                        // $data_arr['reel_lot_no']    = $reel_lot_no;
                        $data_arr['i']                    = $i;
                        //---
                        $tbody .= $this->fn_generate_sticker_for_huawei($data_arr);
                        $i++;
                    }
    
                    $lot_numbers_rec = oqcVIR1::with('oqclotapp_details')
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
                    ->has('oqclotapp_details', '>', 0) // -JD 7-18-2022
                    ->get();
                    
                    /*
                        For mixing of Huawei & Non-Huawei
                    */
                    // if(count($lot_numbers_rec) != 0 && count($get_huawei_data_with_oqcvir) < 1){
                    if(count($lot_numbers_rec) > 0){
                        /*
                            Loop the $lot_numbers_rec then push the reel_lot to $lot_numbers(array) and search in c3_label_history that match with that $lot_numbers
                        */
                        // return response()->json(['huawei sya pero' => 'walang laman']);
                        //START para madisplay yung mga not huawei data.
                        $lot_numbers = array();
                        foreach ($lot_numbers_rec as $key => $value) {
                            array_push($lot_numbers, $value['oqclotapp_details']->reel_lot);
                        }
    
                        $c3_label_arr = $this->select_c3_label_record_by_lot_number_for_new_huawei($lot_numbers); // Get C3LabelHistory with(C3Label) details
                        // $i = 0; // Use to count the total c3_label that will be printed
                        
                        // return $c3_label_arr;
                        $data_to_pass = array();
                        $data_to_pass['trying'] = 'tried';
                        foreach ($c3_label_arr as $key => $c3_label) {
                            $data_arr = array();
                            /*
                                Get c3_label details
                                -JD
                            */
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
    
                            /*
                                Get c3_label_history details
                                -JD
                            */
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
                            // $badge .= '<span class="badge badge-light ml-1" title="Total Print">'.$print_ctr_total.'</span>';
                            $badge .= '</button>';
                
                            //---
                            $data_arr['country']                = 'PHILIPPINES';
                            $data_arr['issuance_no']            = $c3_label['c3_label']['issuance_no'];
                            $data_arr['customer_pn']            = $c3_label['c3_label']['customer_pn'];
                            $data_arr['manufacture_pn']         = $c3_label['c3_label']['device_name'];
                            $data_arr['lot_qty']                = $lot_qty;//$c3_label['c3_label']['boxing'];
                            $data_arr['lot_no_machine_code']    = $c3_label['c3_label']['machine_code'];
                
                            $data_arr['badge']    = $badge;
                            $data_arr['td_id']    = $td_id;
                            $data_arr['td_lot_number_ctr']    = $td_lot_number_ctr;
                            $data_arr['print_type_next']    = $print_type_next;
                            $data_arr['date_code']    = $date_code;
                            $data_arr['reel_lot_no']    = $reel_lot_no;
                            $data_arr['i']                    = $i;
                            //---
                            // $tbody .= $this->fn_generate_sticker($data_arr); //commented 05/17/2022
                            $i++;
                        }

                        // return response()->json(['tbody' => $tbody, 'c3_label' => $data_to_pass, 'huawei sya pero' => 'walang laman', 'heyClark' =>  $c3_label_arr]);
                    }
                    $return['tbody'] = $tbody;
                    $return['c3_label'] = $data_to_pass;
                    // return $return;
                    return response()->json([
                        'tbody' => $tbody, 
                        'c3_label' => $data_to_pass, 
                        'huawei sya pero' => 'walang laman', 
                        'lot_number' => $lot_numbers,
                        'c3_label_arr' => $c3_label_arr
                    ]);

                    
                }
                else{ // Get yung mga not huawei product - CP
                    // Search packing_code in OQCVIR then get the reel_lot in OQCLotApp based on the fkid_oqclotapp(column)
                    $td_po_no = substr($request->td_po_no, 0, 10);
                    
                    $lot_numbers_rec = oqcVIR1::with('oqclotapp_details')
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
                        // return $lot_numbers_rec;
        
                    $lot_numbers = array();
                    foreach ($lot_numbers_rec as $key => $value) {
                        array_push($lot_numbers, $value['oqclotapp_details']->reel_lot);
                    }
        
                    // return $lot_numbers_rec;
        
                    $tbody = '';
                    $data_to_pass = array();
        
                    $i = 0; // Use to count the total c3_label that will be printed
                    $c3_label_arr = $this->select_c3_label_record_by_lot_number($lot_numbers); // Get C3LabelHistory with(C3Label) details
                    // $c3_label_for_huawei_arr = $this->select_c3_label_record_by_lot_number($c3_label_huawei_lot_numbers); // Get C3LabelHistory with(C3Label) details
        
                    // return $c3_label;
                    foreach ($c3_label_arr as $key => $c3_label) {
                        $data_arr = array();
        
                        /*
                            Get c3_label details
                            -JD
                        */
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
        
                        /*
                            Get c3_label_history details
                            -JD
                        */
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
                        $data_arr['lot_qty']                = $lot_qty;//$c3_label['c3_label']['boxing'];
                        $data_arr['lot_no_machine_code']    = $c3_label['c3_label']['machine_code'];
        
                        $data_arr['badge']    = $badge;
                        $data_arr['td_id']    = $td_id;
                        $data_arr['td_lot_number_ctr']    = $td_lot_number_ctr;
                        $data_arr['print_type_next']    = $print_type_next;
                        $data_arr['date_code']    = $date_code;
                        $data_arr['reel_lot_no']    = $reel_lot_no;
                        $data_arr['i']                    = $i; 
        
                        $tbody .= $this->fn_generate_sticker($data_arr);
                        $i++;
                    }
        
                    $return['tbody'] = $tbody;
                    $return['c3_label'] = $data_to_pass;
                    return response()->json(['tbody' => $tbody, 'c3_label' => $data_to_pass, 'not huawei' => 'not huawei']);
                    // return $return;
                }
            }
        }else{
            $td_po_no = substr($request->td_po_no, 0, 10);
                    
            $lot_numbers_rec = oqcVIR1::with('oqclotapp_details')
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
                // return $lot_numbers_rec;

            $lot_numbers = array();
            foreach ($lot_numbers_rec as $key => $value) {
                array_push($lot_numbers, $value['oqclotapp_details']->reel_lot);
            }

            // return $lot_numbers_rec;

            $tbody = '';
            $data_to_pass = array();

            $i = 0; // Use to count the total c3_label that will be printed
            $c3_label_arr = $this->select_c3_label_record_by_lot_number($lot_numbers); // Get C3LabelHistory with(C3Label) details
            // $c3_label_for_huawei_arr = $this->select_c3_label_record_by_lot_number($c3_label_huawei_lot_numbers); // Get C3LabelHistory with(C3Label) details

            // return $c3_label;
            foreach ($c3_label_arr as $key => $c3_label) {
                $data_arr = array();

                /*
                    Get c3_label details
                    -JD
                */
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

                /*
                    Get c3_label_history details
                    -JD
                */
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
                $data_arr['lot_qty']                = $lot_qty;//$c3_label['c3_label']['boxing'];
                $data_arr['lot_no_machine_code']    = $c3_label['c3_label']['machine_code'];

                $data_arr['badge']    = $badge;
                $data_arr['td_id']    = $td_id;
                $data_arr['td_lot_number_ctr']    = $td_lot_number_ctr;
                $data_arr['print_type_next']    = $print_type_next;
                $data_arr['date_code']    = $date_code;
                $data_arr['reel_lot_no']    = $reel_lot_no;
                $data_arr['i']                    = $i; 

                $tbody .= $this->fn_generate_sticker($data_arr);
                $i++;
            }

            $return['tbody'] = $tbody;
            $return['c3_label'] = $data_to_pass;
            return response()->json(['tbody' => $tbody, 'c3_label' => $data_to_pass, 'not huawei' => 'not huawei']);
        }
        // $get_huawei_data_from_history = C3LabelHistory::where('is_huawei', $get_huawei_data[0]->id)->get();
        
        //start IF para malaman kung may makukuhang data galing sa $get_huawei_data para malaman kung may data na huawei at wala(ELSE sa baba) 
        // if(count($get_huawei_data) >= 1){
        //     $lot_numbers_rec = oqcVIR1::with('oqclotapp_details')
        //     ->where([
        //         [
        //             'packing_code','=',
        //             $request->td_packincode
        //         ],
        //         [
        //             'c_lot_batch_no','like',
        //             '%'.$td_po_no.'%'
        //         ],
        //     ])
        //     ->get();
            
        //     $tbody = '';
            
        //     /*
        //         Check if there's no lot_numbers_rec that match in the oqcVIR table then fetch the c3_label_huawei only -JD
        //     */
        //     $data_to_pass = array();
        //     if(count($lot_numbers_rec) == 0){
        //         $i = 0;
        //         foreach($get_huawei_data as $huawei){
        //             $data_arr['country']                = 'PHILIPPINES';
        //             $data_arr['item_code']              = $huawei['item_code'];
        //             $data_arr['pn']                     = $huawei['pn'];
        //             $data_arr['mpn']                    = $huawei['mpn'];
        //             $data_arr['qty']                    = $huawei['qty'];
        //             $data_arr['date']                   = $huawei['date'];
        //             $data_arr['lotno']                  = $huawei['lotno'].'-'.$huawei['lotno_extension'];
        //             $data_arr['print_lot']              = $huawei['print_lot'];
        //             $data_arr['print_lot_quantity']     = $huawei['print_lot_quantity'];
        //             $data_arr['unique_number']          = $huawei['unique_number'];
        //             $data_arr['max_row']                = $huawei['max_row'];
        
        //             $print_type_next = 4;
        //             $data_arr['print_type_next']    = $print_type_next; // Accessories
        //             $data_arr['huawei_id']              = $huawei['id'];
        //             // $data_arr['reel_lot_no']    = $reel_lot_no;
        //             $data_arr['i']                    = $i;
        //             //---
        //             $tbody .= $this->fn_generate_sticker_for_huawei($data_arr);
        //             $i++;
        //         }

        //         $return['tbody'] = $tbody;
        //         $return['c3_label'] = $data_to_pass;
        //         return $return;

        //         /*
        //             Old code -CP
        //         */
        //         // foreach($get_huawei_data as $huawei){
        //         //     $tbody .= '<tr>';
        //         //     $tbody .= '<td>';
        //         //     $tbody .= '<input type="checkbox" class="td_ckb mr-1">';
        //         //     // $tbody .= $data_arr['i']+1;
        //         //     // $tbody .= ' '.$data_arr['badge'];
        //         //     // $tbody .= '<input type="hidden" class="td_id" value="'.$data_arr['td_id'].'">';
        //         //     // $tbody .= '<input type="hidden" class="td_lot_number_ctr" value="'.$data_arr['td_lot_number_ctr'].'">';
        //         //     // $tbody .= '<input type="hidden" class="td_print_type_next" value="'.$data_arr['print_type_next'].'">';
        //         //     // $tbody .= '<input type="hidden" class="td_lbl_str" value="'.$lbl.'">';
        //         //     $tbody .= '</td>';
        //         //     $tbody .= '<td>';
        //         //     $tbody .= '';
        //         //     $tbody .= '</td>';
        //         //     $tbody .= '<td>';
        //         //     $tbody .= $huawei['pn'];
        //         //     $tbody .= '</td>';
        //         //     $tbody .= '<td>';
        //         //     $tbody .= $huawei['mpn'];
        //         //     $tbody .= '</td>';
        //         //     $tbody .= '<td>';
        //         //     $tbody .= $huawei['qty'];
        //         //     $tbody .= '</td>';
        //         //     $tbody .= '<td>';
        //         //     $tbody .= $huawei['date'];
        //         //     $tbody .= '</td>';
        //         //     $tbody .= '<td>';
        //         //     $tbody .= $huawei['lotno'].'-'.$huawei['lotno_extension'];
        //         //     $tbody .= '</td>';
        //         //     $tbody .= '</tr>';
        //         // }

        //         // $return['tbody'] = $tbody;
        //         // $return['c3_label'] = "data";
        //         // return $return;
        //     }
        //     else{ 
        //         /*
        //             Mixed the c3_label_huaweis(table) and c3_label(table) data in the table -JD
        //         */

        //         //START FOREACH para madisplay yung mga is_huawei data -CP
        //         $i = 0; // Use to count the total c3_label that will be printed -JD
        //         foreach($get_huawei_data as $huawei){
        //             $data_arr['country']                = 'PHILIPPINES';
        //             $data_arr['item_code']              = $huawei['item_code'];
        //             $data_arr['pn']                     = $huawei['pn'];
        //             $data_arr['mpn']                    = $huawei['mpn'];
        //             $data_arr['qty']                    = $huawei['qty'];
        //             $data_arr['date']                   = $huawei['date'];
        //             $data_arr['lotno']                  = $huawei['lotno'].'-'.$huawei['lotno_extension'];
        //             $data_arr['print_lot']              = $huawei['print_lot'];
        //             $data_arr['print_lot_quantity']     = $huawei['print_lot_quantity'];
        //             $data_arr['unique_number']          = $huawei['unique_number'];
        //             $data_arr['max_row']                = $huawei['max_row'];
        
        //             $print_type_next = 4; // accessories
        //             $data_arr['print_type_next']    = $print_type_next;
        //             $data_arr['huawei_id']              = $huawei['id'];
        //             // $data_arr['reel_lot_no']    = $reel_lot_no;
        //             $data_arr['i']                    = $i;
        //             //---
        //             $tbody .= $this->fn_generate_sticker_for_huawei($data_arr);
        //             $i++;
        //         }

        //         /*
        //             Old code -CP
        //         */
        //         // foreach($get_huawei_data as $huawei){
        //         //     $tbody .= '<tr>';
        //         //     $tbody .= '<td>';
        //         //     $tbody .= '<input type="checkbox" class="td_ckb mr-1">';
        //         //     // $tbody .= $data_arr['i']+1;
        //         //     // $tbody .= ' '.$data_arr['badge'];
        //         //     // $tbody .= '<input type="hidden" class="td_id" value="'.$data_arr['td_id'].'">';
        //         //     // $tbody .= '<input type="hidden" class="td_lot_number_ctr" value="'.$data_arr['td_lot_number_ctr'].'">';
        //         //     // $tbody .= '<input type="hidden" class="td_print_type_next" value="'.$data_arr['print_type_next'].'">';
        //         //     // $tbody .= '<input type="hidden" class="td_lbl_str" value="'.$lbl.'">';
        //         //     $tbody .= '</td>';
        //         //     $tbody .= '<td>';
        //         //     $tbody .= '';
        //         //     $tbody .= '</td>';
        //         //     $tbody .= '<td>';
        //         //     $tbody .= $huawei['pn'];
        //         //     $tbody .= '</td>';
        //         //     $tbody .= '<td>';
        //         //     $tbody .= $huawei['mpn'];
        //         //     $tbody .= '</td>';
        //         //     $tbody .= '<td>';
        //         //     $tbody .= $huawei['qty'];
        //         //     $tbody .= '</td>';
        //         //     $tbody .= '<td>';
        //         //     $tbody .= $huawei['date'];
        //         //     $tbody .= '</td>';
        //         //     $tbody .= '<td>';
        //         //     $tbody .= $huawei['lotno'].'-'.$huawei['lotno_extension'];
        //         //     $tbody .= '</td>';
        //         //     $tbody .= '</tr>';
        //         // }


        //         /*
        //             Loop the $lot_numbers_rec then push the reel_lot to $lot_numbers(array) and search in c3_label_history that match with that $lot_numbers
        //         */
        //         //START para madisplay yung mga not huawei data.
        //         $lot_numbers = array();
        //         foreach ($lot_numbers_rec as $key => $value) {
        //             array_push($lot_numbers, $value['oqclotapp_details']->reel_lot);
        //         }

        //         $c3_label_arr = $this->select_c3_label_record_by_lot_number($lot_numbers); // Get C3LabelHistory with(C3Label) details
        //         // $i = 0; // Use to count the total c3_label that will be printed

        //         $data_to_pass = array();
        //         foreach ($c3_label_arr as $key => $c3_label) {
        //             $data_arr = array();
        //             /*
        //                 Get c3_label details
        //                 -JD
        //             */
        //             $data_to_pass['c3_label_id']    = 1;//$request->td_id;//need 1 so it will not be saved again in history details
        //             $data_to_pass['issuance_no']    = $c3_label['c3_label']['issuance_no'];
        //             $data_to_pass['po_no']          = $c3_label['c3_label']['po_no'];
        //             $data_to_pass['device_code']    = $c3_label['c3_label']['device_code'];
        //             $data_to_pass['device_name']    = $c3_label['c3_label']['device_name'];
        //             $data_to_pass['kit_qty']        = $c3_label['c3_label']['kit_qty'];
        //             $data_to_pass['customer_pn']    = $c3_label['c3_label']['customer_pn'];
        //             $data_to_pass['boxing']         = $c3_label['c3_label']['boxing'];
        //             $data_to_pass['machine_code']   = $c3_label['c3_label']['machine_code'];
        //             $data_to_pass['packing_code']   = $request->td_packincode;

        //             $badge = '';
        //             $print_type_next = 4;

        //             /*
        //                 Get c3_label_history details
        //                 -JD
        //             */
        //             $td_id = $c3_label['id'];
        //             $lot_qty = $c3_label['lot_qty'];
        //             $date_code = $c3_label['date_code'];
        //             $reel_lot_no = $c3_label['lot_number'];
        //             $td_lot_number_ctr = $c3_label['lot_number_ctr'];
        //             //---
        //             $print_ctr_total = 0;
        //             foreach ($c3_label['c3_label_history_details'] as $key => $value) {
        //                 if($value['print_type']>=4){
        //                     $print_ctr_total++;
        //                 }
        //             }
        //             if( $print_ctr_total > 0 ){
        //                 // $print_type_next = 5;//still 4 since all are accessories
        //             }
        //             $badge = '<button type="button" class="btn btn-sm btn-info px-1 py-0 float-right td_btn_open">History';
        //             $badge .= '<span class="badge badge-light ml-1" title="Total Print">'.$print_ctr_total.'</span>';
        //             $badge .= '</button>';
        
        //             //---
        //             $data_arr['country']                = 'PHILIPPINES';
        //             $data_arr['issuance_no']            = $c3_label['c3_label']['issuance_no'];
        //             $data_arr['customer_pn']            = $c3_label['c3_label']['customer_pn'];
        //             $data_arr['manufacture_pn']         = $c3_label['c3_label']['device_name'];
        //             $data_arr['lot_qty']                = $lot_qty;//$c3_label['c3_label']['boxing'];
        //             $data_arr['lot_no_machine_code']    = $c3_label['c3_label']['machine_code'];
        
        //             $data_arr['badge']    = $badge;
        //             $data_arr['td_id']    = $td_id;
        //             $data_arr['td_lot_number_ctr']    = $td_lot_number_ctr;
        //             $data_arr['print_type_next']    = $print_type_next;
        //             $data_arr['date_code']    = $date_code;
        //             $data_arr['reel_lot_no']    = $reel_lot_no;
        //             $data_arr['i']                    = $i;
        //             //---
        //             $tbody .= $this->fn_generate_sticker($data_arr);
        //             $i++;
        //         }

        //         $return['tbody'] = $tbody;
        //         $return['c3_label'] = $data_to_pass;
        //         return $return;
        //     }
        // }
    }

    // public function fn_select_c3_label_content_to_print_accessories(Request $request){
    //     $td_po_no = substr($request->td_po_no, 0, 10);

    //     $get_huawei_data = C3LabelHuawei::where('po_no', $request->td_po_no)->get(); //query para makuha ang data galing sa c3_label_huaweis table

    //     // $huawei_po_no = substr($get_huawei_data[0]->po_no, 0, 10); // 4502295702
       
    //     // $get_huawei_data_with_oqcvir = oqcVIR1::with('oqclotapp_details')
    //     // ->where([
    //     //     [
    //     //         'packing_code','=',
    //     //         $request->td_packincode
    //     //     ],
    //     //     [
    //     //         'c_lot_batch_no','like',
    //     //         '%'.$huawei_po_no.'%'
    //     //     ],
    //     // ])
    //     // ->get();

        
    //     // if(count($get_huawei_data_with_oqcvir)>0){

    //     //     $lot_number_huawei = array();
    //     //     foreach ($get_huawei_data_with_oqcvir as $get_lot_number_from_huawei_data_with_oqcvir) {
    //     //         array_push($lot_number_huawei, $get_lot_number_from_huawei_data_with_oqcvir['oqclotapp_details']->reel_lot);
    //     //     }

    //     //     $table_records = C3LabelHistory::with([
    //     //         'c3_label_huawei_info',
    //     //     ])
    //     //     ->whereIn(
    //     //         'lot_number',
    //     //         $lot_number_huawei
    //     //     )
    //     //     ->where(
    //     //         [
    //     //             [
    //     //                 'deleted_at','=',
    //     //                 null
    //     //             ],
    //     //         ]
    //     //     )
    //     //     ->get();

    //     //     return $table_records;

    //     // }



    //     // $get_huawei_data_from_history = C3LabelHistory::where('is_huawei', $get_huawei_data[0]->id)->get();
        
    //     //start IF para malaman kung may makukuhang data galing sa $get_huawei_data para malaman kung may data na huawei at wala(ELSE sa baba) 
    //     if(count($get_huawei_data) >= 1){
    //         $lot_numbers_rec = oqcVIR1::with('oqclotapp_details')
    //         ->where([
    //             [
    //                 'packing_code','=',
    //                 $request->td_packincode
    //             ],
    //             [
    //                 'c_lot_batch_no','like',
    //                 '%'.$td_po_no.'%'
    //             ],
    //         ])
    //         ->get();
            
    //         $tbody = '';
            
    //         /*
    //             Check if there's no lot_numbers_rec that match in the oqcVIR table then fetch the c3_label_huawei only -JD
    //         */
    //         $data_to_pass = array();
    //         if(count($lot_numbers_rec) == 0){
    //             $i = 0;
    //             foreach($get_huawei_data as $huawei){
    //                 $data_arr['country']                = 'PHILIPPINES';
    //                 $data_arr['item_code']              = $huawei['item_code'];
    //                 $data_arr['pn']                     = $huawei['pn'];
    //                 $data_arr['mpn']                    = $huawei['mpn'];
    //                 $data_arr['qty']                    = $huawei['qty'];
    //                 $data_arr['date']                   = $huawei['date'];
    //                 $data_arr['lotno']                  = $huawei['lotno'].'-'.$huawei['lotno_extension'];
    //                 $data_arr['print_lot']              = $huawei['print_lot'];
    //                 $data_arr['print_lot_quantity']     = $huawei['print_lot_quantity'];
    //                 $data_arr['unique_number']          = $huawei['unique_number'];
    //                 $data_arr['max_row']                = $huawei['max_row'];
        
    //                 $print_type_next = 4;
    //                 $data_arr['print_type_next']    = $print_type_next; // Accessories
    //                 $data_arr['huawei_id']              = $huawei['id'];
    //                 // $data_arr['reel_lot_no']    = $reel_lot_no;
    //                 $data_arr['i']                    = $i;
    //                 //---
    //                 $tbody .= $this->fn_generate_sticker_for_huawei($data_arr);
    //                 $i++;
    //             }

    //             $return['tbody'] = $tbody;
    //             $return['c3_label'] = $data_to_pass;
    //             return $return;

    //             /*
    //                 Old code -CP
    //             */
    //             // foreach($get_huawei_data as $huawei){
    //             //     $tbody .= '<tr>';
    //             //     $tbody .= '<td>';
    //             //     $tbody .= '<input type="checkbox" class="td_ckb mr-1">';
    //             //     // $tbody .= $data_arr['i']+1;
    //             //     // $tbody .= ' '.$data_arr['badge'];
    //             //     // $tbody .= '<input type="hidden" class="td_id" value="'.$data_arr['td_id'].'">';
    //             //     // $tbody .= '<input type="hidden" class="td_lot_number_ctr" value="'.$data_arr['td_lot_number_ctr'].'">';
    //             //     // $tbody .= '<input type="hidden" class="td_print_type_next" value="'.$data_arr['print_type_next'].'">';
    //             //     // $tbody .= '<input type="hidden" class="td_lbl_str" value="'.$lbl.'">';
    //             //     $tbody .= '</td>';
    //             //     $tbody .= '<td>';
    //             //     $tbody .= '';
    //             //     $tbody .= '</td>';
    //             //     $tbody .= '<td>';
    //             //     $tbody .= $huawei['pn'];
    //             //     $tbody .= '</td>';
    //             //     $tbody .= '<td>';
    //             //     $tbody .= $huawei['mpn'];
    //             //     $tbody .= '</td>';
    //             //     $tbody .= '<td>';
    //             //     $tbody .= $huawei['qty'];
    //             //     $tbody .= '</td>';
    //             //     $tbody .= '<td>';
    //             //     $tbody .= $huawei['date'];
    //             //     $tbody .= '</td>';
    //             //     $tbody .= '<td>';
    //             //     $tbody .= $huawei['lotno'].'-'.$huawei['lotno_extension'];
    //             //     $tbody .= '</td>';
    //             //     $tbody .= '</tr>';
    //             // }

    //             // $return['tbody'] = $tbody;
    //             // $return['c3_label'] = "data";
    //             // return $return;
    //         }
    //         else{ 
    //             /*
    //                 Mixed the c3_label_huaweis(table) and c3_label(table) data in the table -JD
    //             */

    //             //START FOREACH para madisplay yung mga is_huawei data -CP
    //             $i = 0; // Use to count the total c3_label that will be printed -JD
    //             foreach($get_huawei_data as $huawei){
    //                 $data_arr['country']                = 'PHILIPPINES';
    //                 $data_arr['item_code']              = $huawei['item_code'];
    //                 $data_arr['pn']                     = $huawei['pn'];
    //                 $data_arr['mpn']                    = $huawei['mpn'];
    //                 $data_arr['qty']                    = $huawei['qty'];
    //                 $data_arr['date']                   = $huawei['date'];
    //                 $data_arr['lotno']                  = $huawei['lotno'].'-'.$huawei['lotno_extension'];
    //                 $data_arr['print_lot']              = $huawei['print_lot'];
    //                 $data_arr['print_lot_quantity']     = $huawei['print_lot_quantity'];
    //                 $data_arr['unique_number']          = $huawei['unique_number'];
    //                 $data_arr['max_row']                = $huawei['max_row'];
        
    //                 $print_type_next = 4; // accessories
    //                 $data_arr['print_type_next']    = $print_type_next;
    //                 $data_arr['huawei_id']              = $huawei['id'];
    //                 // $data_arr['reel_lot_no']    = $reel_lot_no;
    //                 $data_arr['i']                    = $i;
    //                 //---
    //                 $tbody .= $this->fn_generate_sticker_for_huawei($data_arr);
    //                 $i++;
    //             }

    //             /*
    //                 Old code -CP
    //             */
    //             // foreach($get_huawei_data as $huawei){
    //             //     $tbody .= '<tr>';
    //             //     $tbody .= '<td>';
    //             //     $tbody .= '<input type="checkbox" class="td_ckb mr-1">';
    //             //     // $tbody .= $data_arr['i']+1;
    //             //     // $tbody .= ' '.$data_arr['badge'];
    //             //     // $tbody .= '<input type="hidden" class="td_id" value="'.$data_arr['td_id'].'">';
    //             //     // $tbody .= '<input type="hidden" class="td_lot_number_ctr" value="'.$data_arr['td_lot_number_ctr'].'">';
    //             //     // $tbody .= '<input type="hidden" class="td_print_type_next" value="'.$data_arr['print_type_next'].'">';
    //             //     // $tbody .= '<input type="hidden" class="td_lbl_str" value="'.$lbl.'">';
    //             //     $tbody .= '</td>';
    //             //     $tbody .= '<td>';
    //             //     $tbody .= '';
    //             //     $tbody .= '</td>';
    //             //     $tbody .= '<td>';
    //             //     $tbody .= $huawei['pn'];
    //             //     $tbody .= '</td>';
    //             //     $tbody .= '<td>';
    //             //     $tbody .= $huawei['mpn'];
    //             //     $tbody .= '</td>';
    //             //     $tbody .= '<td>';
    //             //     $tbody .= $huawei['qty'];
    //             //     $tbody .= '</td>';
    //             //     $tbody .= '<td>';
    //             //     $tbody .= $huawei['date'];
    //             //     $tbody .= '</td>';
    //             //     $tbody .= '<td>';
    //             //     $tbody .= $huawei['lotno'].'-'.$huawei['lotno_extension'];
    //             //     $tbody .= '</td>';
    //             //     $tbody .= '</tr>';
    //             // }


    //             /*
    //                 Loop the $lot_numbers_rec then push the reel_lot to $lot_numbers(array) and search in c3_label_history that match with that $lot_numbers
    //             */
    //             //START para madisplay yung mga not huawei data.
    //             $lot_numbers = array();
    //             foreach ($lot_numbers_rec as $key => $value) {
    //                 array_push($lot_numbers, $value['oqclotapp_details']->reel_lot);
    //             }

    //             $c3_label_arr = $this->select_c3_label_record_by_lot_number($lot_numbers); // Get C3LabelHistory with(C3Label) details
    //             // $i = 0; // Use to count the total c3_label that will be printed

    //             $data_to_pass = array();
    //             foreach ($c3_label_arr as $key => $c3_label) {
    //                 $data_arr = array();
    //                 /*
    //                     Get c3_label details
    //                     -JD
    //                 */
    //                 $data_to_pass['c3_label_id']    = 1;//$request->td_id;//need 1 so it will not be saved again in history details
    //                 $data_to_pass['issuance_no']    = $c3_label['c3_label']['issuance_no'];
    //                 $data_to_pass['po_no']          = $c3_label['c3_label']['po_no'];
    //                 $data_to_pass['device_code']    = $c3_label['c3_label']['device_code'];
    //                 $data_to_pass['device_name']    = $c3_label['c3_label']['device_name'];
    //                 $data_to_pass['kit_qty']        = $c3_label['c3_label']['kit_qty'];
    //                 $data_to_pass['customer_pn']    = $c3_label['c3_label']['customer_pn'];
    //                 $data_to_pass['boxing']         = $c3_label['c3_label']['boxing'];
    //                 $data_to_pass['machine_code']   = $c3_label['c3_label']['machine_code'];
    //                 $data_to_pass['packing_code']   = $request->td_packincode;

    //                 $badge = '';
    //                 $print_type_next = 4;

    //                 /*
    //                     Get c3_label_history details
    //                     -JD
    //                 */
    //                 $td_id = $c3_label['id'];
    //                 $lot_qty = $c3_label['lot_qty'];
    //                 $date_code = $c3_label['date_code'];
    //                 $reel_lot_no = $c3_label['lot_number'];
    //                 $td_lot_number_ctr = $c3_label['lot_number_ctr'];
    //                 //---
    //                 $print_ctr_total = 0;
    //                 foreach ($c3_label['c3_label_history_details'] as $key => $value) {
    //                     if($value['print_type']>=4){
    //                         $print_ctr_total++;
    //                     }
    //                 }
    //                 if( $print_ctr_total > 0 ){
    //                     // $print_type_next = 5;//still 4 since all are accessories
    //                 }
    //                 $badge = '<button type="button" class="btn btn-sm btn-info px-1 py-0 float-right td_btn_open">History';
    //                 $badge .= '<span class="badge badge-light ml-1" title="Total Print">'.$print_ctr_total.'</span>';
    //                 $badge .= '</button>';
        
    //                 //---
    //                 $data_arr['country']                = 'PHILIPPINES';
    //                 $data_arr['issuance_no']            = $c3_label['c3_label']['issuance_no'];
    //                 $data_arr['customer_pn']            = $c3_label['c3_label']['customer_pn'];
    //                 $data_arr['manufacture_pn']         = $c3_label['c3_label']['device_name'];
    //                 $data_arr['lot_qty']                = $lot_qty;//$c3_label['c3_label']['boxing'];
    //                 $data_arr['lot_no_machine_code']    = $c3_label['c3_label']['machine_code'];
        
    //                 $data_arr['badge']    = $badge;
    //                 $data_arr['td_id']    = $td_id;
    //                 $data_arr['td_lot_number_ctr']    = $td_lot_number_ctr;
    //                 $data_arr['print_type_next']    = $print_type_next;
    //                 $data_arr['date_code']    = $date_code;
    //                 $data_arr['reel_lot_no']    = $reel_lot_no;
    //                 $data_arr['i']                    = $i;
    //                 //---
    //                 $tbody .= $this->fn_generate_sticker($data_arr);
    //                 $i++;
    //             }

    //             $return['tbody'] = $tbody;
    //             $return['c3_label'] = $data_to_pass;
    //             return $return;
    //         }
    //     }
    //     else{ // Get yung mga not huawei product - CP
    //         // Search packing_code in OQCVIR then get the reel_lot in OQCLotApp based on the fkid_oqclotapp(column)
    //         $td_po_no = substr($request->td_po_no, 0, 10);
            
    //         $lot_numbers_rec = oqcVIR1::with('oqclotapp_details')
    //             ->where([
    //                 [
    //                     'packing_code','=',
    //                     $request->td_packincode
    //                 ],
    //                 [
    //                     'c_lot_batch_no','like',
    //                     '%'.$td_po_no.'%'
    //                 ],
    //             ])
    //             ->get();
    //             // return $lot_numbers_rec;

    //         $lot_numbers = array();
    //         foreach ($lot_numbers_rec as $key => $value) {
    //             array_push($lot_numbers, $value['oqclotapp_details']->reel_lot);
    //         }

    //         // return $lot_numbers_rec;

    //         $tbody = '';
    //         $data_to_pass = array();

    //         $i = 0; // Use to count the total c3_label that will be printed
    //         $c3_label_arr = $this->select_c3_label_record_by_lot_number($lot_numbers); // Get C3LabelHistory with(C3Label) details
    //         // $c3_label_for_huawei_arr = $this->select_c3_label_record_by_lot_number($c3_label_huawei_lot_numbers); // Get C3LabelHistory with(C3Label) details

    //         // return $c3_label;
    //         foreach ($c3_label_arr as $key => $c3_label) {
    //             $data_arr = array();

    //             /*
    //                 Get c3_label details
    //                 -JD
    //             */
    //             $data_to_pass['c3_label_id']    = 1;//$request->td_id;//need 1 so it will not be saved again in history details
    //             $data_to_pass['issuance_no']    = $c3_label['c3_label']['issuance_no'];
    //             $data_to_pass['po_no']          = $c3_label['c3_label']['po_no'];
    //             $data_to_pass['device_code']    = $c3_label['c3_label']['device_code'];
    //             $data_to_pass['device_name']    = $c3_label['c3_label']['device_name'];
    //             $data_to_pass['kit_qty']        = $c3_label['c3_label']['kit_qty'];
    //             $data_to_pass['customer_pn']    = $c3_label['c3_label']['customer_pn']; 
    //             $data_to_pass['boxing']         = $c3_label['c3_label']['boxing'];
    //             $data_to_pass['machine_code']   = $c3_label['c3_label']['machine_code'];
    //             $data_to_pass['packing_code']   = $request->td_packincode;

    //             $badge = '';
    //             $print_type_next = 4;

    //             /*
    //                 Get c3_label_history details
    //                 -JD
    //             */
    //             $td_id = $c3_label['id'];
    //             $lot_qty = $c3_label['lot_qty'];
    //             $date_code = $c3_label['date_code'];
    //             $reel_lot_no = $c3_label['lot_number'];
    //             $td_lot_number_ctr = $c3_label['lot_number_ctr'];
    //             //---
    //             $print_ctr_total = 0;
    //             foreach ($c3_label['c3_label_history_details'] as $key => $value) {
    //                 if($value['print_type']>=4){
    //                     $print_ctr_total++;
    //                 }
    //             }
    //             if( $print_ctr_total > 0 ){
    //                 // $print_type_next = 5;//still 4 since all are accessories
    //             }
    //             $badge = '<button type="button" class="btn btn-sm btn-info px-1 py-0 float-right td_btn_open">History';
    //             $badge .= '<span class="badge badge-light ml-1" title="Total Print">'.$print_ctr_total.'</span>';
    //             $badge .= '</button>';

    //             //---
    //             $data_arr['country']                = 'PHILIPPINES';
    //             $data_arr['issuance_no']            = $c3_label['c3_label']['issuance_no'];
    //             $data_arr['customer_pn']            = $c3_label['c3_label']['customer_pn'];
    //             $data_arr['manufacture_pn']         = $c3_label['c3_label']['device_name'];
    //             $data_arr['lot_qty']                = $lot_qty;//$c3_label['c3_label']['boxing'];
    //             $data_arr['lot_no_machine_code']    = $c3_label['c3_label']['machine_code'];

    //             $data_arr['badge']    = $badge;
    //             $data_arr['td_id']    = $td_id;
    //             $data_arr['td_lot_number_ctr']    = $td_lot_number_ctr;
    //             $data_arr['print_type_next']    = $print_type_next;
    //             $data_arr['date_code']    = $date_code;
    //             $data_arr['reel_lot_no']    = $reel_lot_no;
    //             $data_arr['i']                    = $i; 

    //             $tbody .= $this->fn_generate_sticker($data_arr);
    //             $i++;
    //         }

    //         $return['tbody'] = $tbody;
    //         $return['c3_label'] = $data_to_pass;
    //         return $return;
    //     }
    // }

    public function fn_generate_sticker($data_arr){
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

    public function fn_generate_sticker_for_huawei($data_arr){
        $left_space = 0;
		$top_space = 0;
        $font_style_name = "ARI002";
        // return json_encode($data_arr);
        // $zebra_code  = '^XA';

        // $zebra_code  .= '^MMT';
        // $zebra_code  .= '^PW1140';
        // $zebra_code  .= '^LL750';
        // $zebra_code  .= '^LS0';

        // $zebra_code  .= '^FT89,115^A0N,47,38^FH\^CI28^FD(1P) Manufacure P/N: '.$data_arr['mpn'];

        // /**
        //  * Commented by -JD
        //  */
        // // $zebra_code  = '^XA';

        // // $zebra_code  = '^MMT';
        // // $zebra_code  = '^PW1140';
        // // $zebra_code  = '^LL750';
        // // $zebra_code  = '^LS0';

        // // $zebra_code  = '^FT89,115^A0N,47,38^FH\^CI28^FD(1P) Manufacure P/N: '.$data_arr['mpn'];
        // $zebra_code .= '^FS^CI27';

        // $zebra_code .= '^BY3,3,43^FT92,178^BCN,,N,N';
        // $zebra_code .= '^FH\^FD> '.$data_arr['mpn'];
        // $zebra_code .= '^FS';

        // $zebra_code .= '^FT89,234^A0N,47,38^FH\^CI28^FD(Q) QTY: '.$data_arr['qty'];
        // $zebra_code .= '^FS^CI27';
        // $zebra_code .= '^BY3,3,43^FT92,292^BCN,,N,N';
        // $zebra_code .= '^FH\^FD> '.$data_arr['qty'];
        // $zebra_code .= '^FS';

        // $zebra_code .= '^FT92,353^A0N,47,38^FH\^CI28^FD(10D) Oldest Lot Date: '.$data_arr['date'];
        // $zebra_code .= '^FS^CI27';
        // $zebra_code .= '^BY3,3,43^FT92,407^BCN,,N,N';
        // $zebra_code .= '^FH\^FD> '.$data_arr['date'];
        // $zebra_code .= '^FS';

        // $zebra_code .= '^FT92,470^A0N,47,38^FH\^CI28^FD(1T) LOT No.: '.$data_arr['lotno'];
        // $zebra_code .= '^FS^CI27';
        // $zebra_code .= '^BY3,3,43^FT92,530^BCN,,N,N';
        // $zebra_code .= '^FH\^FD> '.$data_arr['lotno'];
        // $zebra_code .= '^FS';

        // $zebra_code .= '^FT89,589^A0N,47,53^FH\^CI28^FD '.$data_arr['unique_number'];
        // $zebra_code .= '^FS^CI27';

        // // QR Code
        // $zebra_code .= '^FT675,570';
        // $zebra_code .= '^BQN,2,6';

        // $printLotArr = explode(', ', $data_arr['print_lot']);
        // $printLotQuantityArr = explode(', ', $data_arr['print_lot_quantity']);
        // $printLotAndQtyForZebraCodeArr = array();
        // if($data_arr['max_row'] > 0){
        //  for ($i=0; $i < $data_arr['max_row']; $i++) {
        //      $printLotAndQtyForZebraCode = $printLotArr[$i] . ' ' .  '_0D_0A'. $printLotQuantityArr[$i] . ' ' . '_0D_0A';
        //      array_push($printLotAndQtyForZebraCodeArr, $printLotAndQtyForZebraCode);
        //  }
        //  $printLotAndQtyForZebraCodeArrReplaced = str_replace(',', '', implode(',', $printLotAndQtyForZebraCodeArr));
        // }

        // $zebra_code .= '^FH\^FDLA,' . $data_arr['item_code'] .'\0D\0A'. $data_arr['pn'] .' '.'\0D\0A' . $data_arr['mpn'] .' '.'\0D\0A' . $data_arr['qty'] .' '.'\0D\0A' . $data_arr['date'] . ' '.'\0D\0A' . 'PH ' . '\0D\0A' . $data_arr['lotno'] . ' ' . '\0D\0A' . $data_arr['unique_number']. ' ' . '\0D\0A' . $printLotAndQtyForZebraCodeArrReplaced;
        // $zebra_code .= '^FS';
        // $zebra_code .= '^PQ1,0,1,Y';


        $zebra_code  = '^XA';
        $zebra_code .= '^FO'.( 40+ $left_space ).', '.( 40+ $top_space );
		$zebra_code .= '^CF 0,30';
		$zebra_code .= '^FD(1P) Manufacture P/N: '.$data_arr['mpn'];
		$zebra_code .= '^FS';
		
		$zebra_code .= '^FO'.( 40+ $left_space ).', '.( 75+ $top_space );
		$zebra_code .= '^BY2';
		$zebra_code .= '^BCN, 26, N, N, N';
		// $zebra_code .= '^^FD1P'.$data_arr['mpn']; /07-07-2022
		$zebra_code .= '^FD1P'.$data_arr['mpn'];
		$zebra_code .= '^FS';
		
		$zebra_code .= '^FO'.( 40+ $left_space ).', '.( 120+ $top_space );
		$zebra_code .= '^CF 0,30';
		$zebra_code .= '^FD(Q) QTY: '.$data_arr['qty'];
		$zebra_code .= '^FS';
		
		$zebra_code .= '^FO'.( 40+ $left_space ).', '.( 150+ $top_space );
		$zebra_code .= '^BY2';
		$zebra_code .= '^BCN, 26, N, N, N';
		$zebra_code .= '^FDQ'.$data_arr['qty'];
		$zebra_code .= '^FS';
		
		$zebra_code .= '^FO'.( 40+ $left_space ).', '.( 197+ $top_space );
		$zebra_code .= '^CF 0,30';
		$zebra_code .= '^FD(10D) Oldest Lot Date: '.$data_arr['date'];
		$zebra_code .= '^FS';
		
		$zebra_code .= '^FO'.( 40+ $left_space ).', '.( 227+ $top_space );
		$zebra_code .= '^BY2';
		$zebra_code .= '^BCN, 26, N, N, N';
		$zebra_code .= '^FD10D'.$data_arr['date'];
		$zebra_code .= '^FS';
		
		$zebra_code .= '^FO'.( 40+ $left_space ).', '.( 275+ $top_space );
		$zebra_code .= '^CF 0,30';
		$zebra_code .= '^FD(1T) LOT No: '.$data_arr['lotno'];
		$zebra_code .= '^FS';
		
		$zebra_code .= '^FO'.( 40+ $left_space ).', '.( 305+ $top_space );
		$zebra_code .= '^BY2';
		$zebra_code .= '^BCN, 26, N, N, N';
		$zebra_code .= '^FD1T'.$data_arr['lotno'];
		$zebra_code .= '^FS';

        // QR Code
		$zebra_code .= '^FO'.( 465+ $left_space ).', '.( 115+ $top_space );
		$zebra_code .= '^BQN,2, 4^FH^'; // add ^FH^ to allow the "_0D_0A" to be used for new line or line breaks
		// $zebra_code .= '^BQN,2, 4'; // add ^FH^ to allow the "_0D_0A" to be used for new line or line breaks

        // Material Code
		$item_code_padded 	= str_pad($data_arr['item_code'], 18, " ", STR_PAD_RIGHT);

		// MPN - Manufacture P/N
		$mpn_padded = str_pad($data_arr['mpn'], 40, " ", STR_PAD_RIGHT);

		// PN - Customer P/N
		$pn_padded = str_pad($data_arr['pn'], 35, " ", STR_PAD_RIGHT);

		// Quantity
		$qty_padded = str_pad($data_arr['qty'], 7, "0", STR_PAD_LEFT);

		// Reel Lot Number
		$reel_lot_number_padded = str_pad($data_arr['lotno'], 10, " ", STR_PAD_RIGHT);

        $printLotArr = explode(', ', $data_arr['print_lot']);
        $printLotQuantityArr = explode(', ', $data_arr['print_lot_quantity']);
		$printLotAndQtyForZebraCodeArr = array();
        $printLotAndQtyForZebraCodeArrReplaced = "";
		if($data_arr['max_row'] > 0){
            for ($i=0; $i < $data_arr['max_row']; $i++) {
				$printLotAndQtyForZebraCode = str_pad($printLotArr[$i], 15, " ", STR_PAD_RIGHT) . str_pad($printLotQuantityArr[$i], 5, "0", STR_PAD_LEFT);
				array_push($printLotAndQtyForZebraCodeArr, $printLotAndQtyForZebraCode);
			}

			$requiredPrintLots = 8;
			$currentPrintLots = $data_arr['max_row'];
			$remainingPrintLots = $requiredPrintLots - $currentPrintLots;
			$emptySpacesForPrintLots = "               ";
			$zerosForPrintLotQuantities = "00000";
			for ($i=1; $i <= $remainingPrintLots; $i++) {
				$remainingPrintLotAndQtyForZebraCode = str_pad($emptySpacesForPrintLots, 15, " ", STR_PAD_RIGHT) . str_pad($zerosForPrintLotQuantities, 5, "0", STR_PAD_LEFT);
				array_push($printLotAndQtyForZebraCodeArr, $remainingPrintLotAndQtyForZebraCode);
			}
			$printLotAndQtyForZebraCodeArrReplaced = str_replace(',', '', implode(',', $printLotAndQtyForZebraCodeArr));
		}
        
        $dataForZebraCode = $item_code_padded . $mpn_padded . $pn_padded . $qty_padded . $data_arr['date'] . $reel_lot_number_padded . 'PH' . $data_arr['unique_number'] . $printLotAndQtyForZebraCodeArrReplaced;

		// $zebra_code .= '^FDM,' . $dataForZebraCode; // 07-07-2022
		$zebra_code .= '^FD,,' . $dataForZebraCode; // FDM means M-Manual, this will accept parentheses, numbers, letters
		$zebra_code .= '^FS';

		// Unique Number
		$zebra_code .= '^FO'.( 40+ $left_space ).', '.( 354+ $top_space );
		$zebra_code .= '^FD'.$data_arr['unique_number'];
		$zebra_code .= '^FX - color (R)';
		$zebra_code .= '^CF 0,35'; // font size
		$zebra_code .= '^FS';

        $zebra_code .= "^XZ";

        $tbody = "";
        $tbody .= '<tr>';
        $tbody .= '<td>';
        $tbody .= '<input type="checkbox" class="td_ckb mr-1">';
        $tbody .= $data_arr['i']+1;
        // $tbody .= ' '.$data_arr['badge'];
        $tbody .= '<input type="hidden" class="td_id" value="'.$data_arr['huawei_id'].'">';
        // $tbody .= '<input type="hidden" class="td_lot_number_ctr" value="'.$data_arr['td_lot_number_ctr'].'">';
        $tbody .= '<input type="hidden" class="td_print_type_next" value="'.$data_arr['print_type_next'].'">'; // Use for accessories
        $tbody .= '<input type="hidden" class="td_lbl_str" value="'.$zebra_code.'">';
        $tbody .= '</td>';
        $tbody .= '<td>';
        $tbody .= '';
        $tbody .= '</td>';
        $tbody .= '<td>';
        $tbody .= $data_arr['pn'];
        $tbody .= '</td>';
        $tbody .= '<td>';
        $tbody .= $data_arr['mpn'];
        $tbody .= '</td>';
        $tbody .= '<td>';
        $tbody .= $data_arr['qty'];
        $tbody .= '</td>';
        $tbody .= '<td>';
        $tbody .= $data_arr['date'];
        $tbody .= '</td>';
        $tbody .= '<td>';
        $tbody .= $data_arr['lotno'];
        $tbody .= '</td>';
        $tbody .= '</tr>';

        return $tbody;
    }

    /**
     * Old code
     * This is because the accessories is for packing not in production. Production printer is different.
     * Commented by -JD
     * 05-11-2022
     */
//     public function fn_generate_sticker_for_huawei($data_arr){
//         $left_space = 0;
// 		$top_space = 0;
//         $font_style_name = "ARI002";
        
//         $zebra_code  = '^XA';

//         $zebra_code  .= '^MMT';
//         $zebra_code  .= '^PW1140';
//         $zebra_code  .= '^LL750';
//         $zebra_code  .= '^LS0';

//         $zebra_code  .= '^FT89,115^A0N,47,38^FH\^CI28^FD(1P) Manufacure P/N: '.$data_arr['mpn'];

//         /**
//          * Commented by -JD
//          */
//         // $zebra_code  = '^XA';

//         // $zebra_code  = '^MMT';
//         // $zebra_code  = '^PW1140';
//         // $zebra_code  = '^LL750';
//         // $zebra_code  = '^LS0';

//         // $zebra_code  = '^FT89,115^A0N,47,38^FH\^CI28^FD(1P) Manufacure P/N: '.$data_arr['mpn'];
//         $zebra_code .= '^FS^CI27';

//         $zebra_code .= '^BY3,3,43^FT92,178^BCN,,N,N';
//         $zebra_code .= '^FH\^FD> '.$data_arr['mpn'];
//         $zebra_code .= '^FS';

//         $zebra_code .= '^FT89,234^A0N,47,38^FH\^CI28^FD(Q) QTY: '.$data_arr['qty'];
//         $zebra_code .= '^FS^CI27';
//         $zebra_code .= '^BY3,3,43^FT92,292^BCN,,N,N';
//         $zebra_code .= '^FH\^FD> '.$data_arr['qty'];
//         $zebra_code .= '^FS';

//         $zebra_code .= '^FT92,353^A0N,47,38^FH\^CI28^FD(10D) Oldest Lot Date: '.$data_arr['date'];
//         $zebra_code .= '^FS^CI27';
//         $zebra_code .= '^BY3,3,43^FT92,407^BCN,,N,N';
//         $zebra_code .= '^FH\^FD> '.$data_arr['date'];
//         $zebra_code .= '^FS';

//         $zebra_code .= '^FT92,470^A0N,47,38^FH\^CI28^FD(1T) LOT No.: '.$data_arr['lotno'];
//         $zebra_code .= '^FS^CI27';
//         $zebra_code .= '^BY3,3,43^FT92,530^BCN,,N,N';
//         $zebra_code .= '^FH\^FD> '.$data_arr['lotno'];
//         $zebra_code .= '^FS';

//         $zebra_code .= '^FT89,589^A0N,47,53^FH\^CI28^FD '.$data_arr['unique_number'];
//         $zebra_code .= '^FS^CI27';

//         // QR Code
//         $zebra_code .= '^FT675,570';
//         $zebra_code .= '^BQN,2,6';

//         $printLotArr = explode(', ', $data_arr['print_lot']);
//         $printLotQuantityArr = explode(', ', $data_arr['print_lot_quantity']);
//         $printLotAndQtyForZebraCodeArr = array();
//         if($data_arr['max_row'] > 0){
//          for ($i=0; $i < $data_arr['max_row']; $i++) {
//              $printLotAndQtyForZebraCode = $printLotArr[$i] . ' ' .  '_0D_0A'. $printLotQuantityArr[$i] . ' ' . '_0D_0A';
//              array_push($printLotAndQtyForZebraCodeArr, $printLotAndQtyForZebraCode);
//          }
//          $printLotAndQtyForZebraCodeArrReplaced = str_replace(',', '', implode(',', $printLotAndQtyForZebraCodeArr));
//         }

//         $zebra_code .= '^FH\^FDLA,' . $data_arr['item_code'] .'\0D\0A'. $data_arr['pn'] .' '.'\0D\0A' . $data_arr['mpn'] .' '.'\0D\0A' . $data_arr['qty'] .' '.'\0D\0A' . $data_arr['date'] . ' '.'\0D\0A' . 'PH ' . '\0D\0A' . $data_arr['lotno'] . ' ' . '\0D\0A' . $data_arr['unique_number']. ' ' . '\0D\0A' . $printLotAndQtyForZebraCodeArrReplaced;
//         $zebra_code .= '^FS';
//         $zebra_code .= '^PQ1,0,1,Y';


//   //       $zebra_code  = '^XA';
//   //       $zebra_code .= '^FO'.( 40+ $left_space ).', '.( 40+ $top_space );
// 		// $zebra_code .= '^CF 0,30';
// 		// $zebra_code .= '^FD(1P) Manufacture P/N: '.$data_arr['mpn'];
// 		// $zebra_code .= '^FS';
		
// 		// $zebra_code .= '^FO'.( 40+ $left_space ).', '.( 75+ $top_space );
// 		// $zebra_code .= '^BY2';
// 		// $zebra_code .= '^BCN, 26, N, N, N';
// 		// $zebra_code .= '^^FD1P'.$data_arr['mpn'];
// 		// $zebra_code .= '^FS';
		
// 		// $zebra_code .= '^FO'.( 40+ $left_space ).', '.( 120+ $top_space );
// 		// $zebra_code .= '^CF 0,30';
// 		// $zebra_code .= '^FD(Q) QTY: '.$data_arr['qty'];
// 		// $zebra_code .= '^FS';
		
// 		// $zebra_code .= '^FO'.( 40+ $left_space ).', '.( 150+ $top_space );
// 		// $zebra_code .= '^BY2';
// 		// $zebra_code .= '^BCN, 26, N, N, N';
// 		// $zebra_code .= '^FDQ'.$data_arr['qty'];
// 		// $zebra_code .= '^FS';
		
// 		// $zebra_code .= '^FO'.( 40+ $left_space ).', '.( 197+ $top_space );
// 		// $zebra_code .= '^CF 0,30';
// 		// $zebra_code .= '^FD(10D) Oldest Lot Date: '.$data_arr['date'];
// 		// $zebra_code .= '^FS';
		
// 		// $zebra_code .= '^FO'.( 40+ $left_space ).', '.( 227+ $top_space );
// 		// $zebra_code .= '^BY2';
// 		// $zebra_code .= '^BCN, 26, N, N, N';
// 		// $zebra_code .= '^FD10D'.$data_arr['date'];
// 		// $zebra_code .= '^FS';
		
// 		// $zebra_code .= '^FO'.( 40+ $left_space ).', '.( 275+ $top_space );
// 		// $zebra_code .= '^CF 0,30';
// 		// $zebra_code .= '^FD(1T) LOT No: '.$data_arr['lotno'];
// 		// $zebra_code .= '^FS';
		
// 		// $zebra_code .= '^FO'.( 40+ $left_space ).', '.( 305+ $top_space );
// 		// $zebra_code .= '^BY2';
// 		// $zebra_code .= '^BCN, 26, N, N, N';
// 		// $zebra_code .= '^FD1T'.$data_arr['lotno'];
// 		// $zebra_code .= '^FS';

//         // QR Code
// 		// $zebra_code .= '^FO'.( 465+ $left_space ).', '.( 115+ $top_space );
// 		// $zebra_code .= '^BQN,2, 4^FH^'; // add ^FH^ to allow the "_0D_0A" to be used for new line or line breaks

//   //       $printLotArr = explode(', ', $data_arr['print_lot']);
//   //       $printLotQuantityArr = explode(', ', $data_arr['print_lot_quantity']);
// 		// $printLotAndQtyForZebraCodeArr = array();
// 		// if($data_arr['max_row'] > 0){
// 		// 	for ($i=0; $i < $data_arr['max_row']; $i++) {
// 		// 		$printLotAndQtyForZebraCode = $printLotArr[$i] . ' ' .  '_0D_0A'. $printLotQuantityArr[$i] . ' ' . '_0D_0A';
// 		// 		array_push($printLotAndQtyForZebraCodeArr, $printLotAndQtyForZebraCode);
// 		// 	}
// 		// 	$printLotAndQtyForZebraCodeArrReplaced = str_replace(',', '', implode(',', $printLotAndQtyForZebraCodeArr));
// 		// }

// 		// $zebra_code .= '^FDM,' . $data_arr['item_code'] .' ' . '_0D_0A'. $data_arr['pn'] .' ' . "_0D_0A" . $data_arr['mpn'] .' ' . "_0D_0A" . $data_arr['qty'] .' ' . "_0D_0A" . $data_arr['date'] . ' ' .'_0D_0A' . 'PH ' . '_0D_0A' . $data_arr['lotno'] . ' ' . '_0D_0A' . $data_arr['unique_number'] . ' ' . '_0D_0A' . $printLotAndQtyForZebraCodeArrReplaced;		
// 		// $zebra_code .= '^FS';

// 		// Unique Number
// 		// $zebra_code .= '^FO'.( 40+ $left_space ).', '.( 354+ $top_space );
// 		// $zebra_code .= '^FD'.$data_arr['unique_number'];
// 		// $zebra_code .= '^FX - color (R)';
// 		// $zebra_code .= '^CF 0,35'; // font size
// 		// $zebra_code .= '^FS';

//         $zebra_code .= "^XZ";

//         $tbody = "";
//         $tbody .= '<tr>';
//         $tbody .= '<td>';
//         $tbody .= '<input type="checkbox" class="td_ckb mr-1">';
//         $tbody .= $data_arr['i']+1;
//         // $tbody .= ' '.$data_arr['badge'];
//         $tbody .= '<input type="hidden" class="td_id" value="'.$data_arr['huawei_id'].'">';
//         // $tbody .= '<input type="hidden" class="td_lot_number_ctr" value="'.$data_arr['td_lot_number_ctr'].'">';
//         $tbody .= '<input type="hidden" class="td_print_type_next" value="'.$data_arr['print_type_next'].'">'; // Use for accessories
//         $tbody .= '<input type="hidden" class="td_lbl_str" value="'.$zebra_code.'">';
//         $tbody .= '</td>';
//         $tbody .= '<td>';
//         $tbody .= '';
//         $tbody .= '</td>';
//         $tbody .= '<td>';
//         $tbody .= $data_arr['pn'];
//         $tbody .= '</td>';
//         $tbody .= '<td>';
//         $tbody .= $data_arr['mpn'];
//         $tbody .= '</td>';
//         $tbody .= '<td>';
//         $tbody .= $data_arr['qty'];
//         $tbody .= '</td>';
//         $tbody .= '<td>';
//         $tbody .= $data_arr['date'];
//         $tbody .= '</td>';
//         $tbody .= '<td>';
//         $tbody .= $data_arr['lotno'];
//         $tbody .= '</td>';
//         $tbody .= '</tr>';

//         return $tbody;
//     }

}
