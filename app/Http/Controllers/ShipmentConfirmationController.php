<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\User;
use App\Model\CnPptsDLabel;
use App\Model\CnPptsDLabelHistories;
use App\Model\RapidDLabelHistory;
use App\Model\ShipmentConfirmation;
use App\Model\ShipmentConfirmationHistory;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CSVShipmentConfirmationImport;

use Auth;
use DataTables;

define('TIMEZONE', 'Asia/Manila');
date_default_timezone_set(TIMEZONE);

class ShipmentConfirmationController extends Controller
{
	public function fn_view_shipmentconfirmation(Request $request)
	{
		return view('shipmentconfirmation');
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


	public function fn_select_shipment_confirmation(Request $request){
        $qty_left_arr = array();
        // $qty_left_arr = $this->fn_select_d_label_created();
                // print_r($qty_left_arr['qty_left']);

        $shipment_confirmation = ShipmentConfirmation::
            with('user_created_by','user_updated_by')
        	// with('dlabel_history','user_created_by','user_updated_by')
            // ->where('deleted_at','=',null)
            // ->orderBy('shipment_date','DESC')
            // ->orderBy('po_no','DESC')
            // ->orderBy('shipment_qty','DESC')
            // ->orderBy('status','ASC')
            ->whereNull('deleted_at')
            ->get();
        // return  $shipment_confirmation;
        // $shipment_confirmations = DB::connection('mysql')->table('shipment_confirmation')->get();

        // $shipment_confirmationss = DB::connection('mysql')
        // ->select("SELECT
        //     shipment_confirmation
        // ");
        // return $shipment_confirmation;
        return DataTables::of($shipment_confirmation)
            ->addColumn('raw_action', function($shipment_confirmation)use($qty_left_arr){
            	$result = '';
                $disabled = 'disabled';
                // $badge_deleted = '';
                // $badge_completed = '';
                $user_position = Auth::user()->position;
                $sorter = '3'.$shipment_confirmation->shipment_date;

                if( $user_position == 8 || $user_position == 7 || $user_position == 1 || $user_position == 2 || $user_position == 5 || $user_position == 4 ){
	                $disabled ='';
                }

                if($shipment_confirmation['deleted_at']){
                    $result  .= ' <span class="badge badge-danger">Deleted</span>';
                    $disabled = 'disabled';
                    $sorter = '0'.$shipment_confirmation->shipment_date;
                }

                $result.='<span data-sorter="'.$sorter.'"></span>';
                $result.='<div class="dropdown float-right">';
                $result.='  <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $result.='  </button>';
                $result.='  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                $result.='      <button type="button" title="History" class="dropdown-item td_btn_open btn btn-sm" ship_con-id="'. $shipment_confirmation->id .'" data-toggle="modal" data-target="#mdl_history">History</button> ';
                $result.='      <button '.$disabled.' type="button" title="Edit" class="dropdown-item td_btn_edit btn btn-sm">Edit</button> ';
                $result.='      <button '.$disabled.' type="button" title="Delete" class="dropdown-item td_btn_delete btn btn-sm">Delete</button>';
                $result.='  </div>';
                $result.='</div>';
                $result.=' <input type="hidden" class="td_id" value="'.$shipment_confirmation->id.'">';

                // CHAN - 10-17-2024 - START
                if($shipment_confirmation->balance == ''){
                    // $result .= '<span class="badge badge-light"></span>';
                }else if($shipment_confirmation->balance == 'Completed'){
                    $result .= '<span class="badge badge-success">Completed</span>';
                }else if($shipment_confirmation->balance == 'Expired'){
                    $result .= '<span class="badge badge-danger">Expired</span>';
                }else{
                    $result .= '<span class="badge badge-warning">'.$shipment_confirmation->balance.' Left</span>';
                } // CHAN - 10-17-2024 - END

                // |================================================================================================================================|
                // |================================================================================================================================|
                // |================================================================================================================================|
                // $in_d_label_history = array_search($shipment_confirmation->id, $qty_left_arr['shipment_confirmation_id']);
                // // return $in_d_label_history;

                // if($in_d_label_history){
                //     $qty_left = ($shipment_confirmation->shipment_qty)-($qty_left_arr['qty_left'][$in_d_label_history]);
                //     if($qty_left==0){
                //         $badge_completed = '<span class="badge badge-success">Completed</span>';
                //     }
                //     else{
                //         $badge_completed = '<span class="badge badge-warning">'.$qty_left.' Left</span>';
                //     }
                // }

                // CHAN - CN_PPTS_V2 25-07-2024
                // $test = CnPptsDLabel::with(['d_label_history_details'])->where('pats_shipment_confirmation_id', $shipment_confirmation->id)->first();
                // for ($i=0; $i < count($test['d_label_history_details']); $i++) {
                //     # code...
                        // $result.= $test['d_label_history_details'][$i]->ship_boxing;
                // }

               //---

                //        	if($shipment_confirmation['dlabel_history']){
                                // $btn_action 	= ' <span class="badge badge-success">Printed</span>';
                //        	}
                // $result.=$badge_completed;
                // $result.='<br>'.$badge_deleted;
                return $result;
            })
            ->rawColumns(['raw_action'])
            ->make(true);
	}


	public function fn_select_shipment_confirmation_dlabel(Request $request){
        $shipment_confirmation = ShipmentConfirmation::
        	// with('dlabel_history')
            where('po_no',$request->po_no)
            ->where('deleted_at','=',null)
            ->orderBy('created_at','DESC')
            ->get();

        return DataTables::of($shipment_confirmation)
            ->addColumn('raw_action', function($shipment_confirmation){

				$checkbox 	= '';
     //        	if($shipment_confirmation['dlabel_history']){
					// $checkbox 	= ' <span class="badge badge-success">Printed</span>';
     //        	}
            	$result = '';
                $result.= $checkbox;
                $result.=' '.$shipment_confirmation->po_no.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$shipment_confirmation->shipment_date.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$shipment_confirmation->delivery_place_name.'&nbsp;&nbsp;&nbsp;&nbsp;'.$shipment_confirmation->shipment_qty.'pcs';
                $result.=' <input type="hidden" class="txt_sc_id" value="'.$shipment_confirmation->id.'">';
                $result.=' <input type="hidden" class="txt_sc_po_no" value="'.$shipment_confirmation->po_no.'">';
                $result.=' <input type="hidden" class="txt_sc_shipment_date" value="'.$shipment_confirmation->shipment_date.'">';
                $result.=' <input type="hidden" class="txt_sc_delivery_place_name" value="'.$shipment_confirmation->delivery_place_name.'">';
                $result.=' <input type="hidden" class="txt_sc_shipment_qty" value="'.$shipment_confirmation->shipment_qty.'">';
                return $result;
            })
            ->rawColumns(['raw_action'])
            ->make(true);
	}

    public function fn_upload_file_shipment_confirmation(Request $request)
    {
        $result = array();
        $collections = Excel::toCollection(new CSVShipmentConfirmationImport, request()->file('txt_upload_file'));
        
        $tbody = '';

        try{
            for($index = 1; $index < count($collections[0]); $index++){
            	if(!$collections[0][$index][0]){
            		continue;
            	}
		        $tbody .= '<tr>';
			        $tbody .= '<td>';
			        $tbody .= '<input type="checkbox" class="ckb_upload" checked>';
			        $tbody .= '<input type="hidden" class="hidden_po_no" value="'.$collections[0][$index][0].'">';
			        $tbody .= '<input type="hidden" class="hidden_shipment_date" value="'.date('Y-m-d',strtotime($collections[0][$index][1])).'">';
			        $tbody .= '<input type="hidden" class="hidden_delivery_place_name" value="'.$collections[0][$index][3].'">';
			        $tbody .= '<input type="hidden" class="hidden_shipment_qty" value="'.$collections[0][$index][4].'">';
			        $tbody .= '<input type="hidden" class="hidden_arrival_date" value="'.date('Y-m-d',strtotime($collections[0][$index][2])).'">';
			        $tbody .= '</td>';

			        $tbody .= '<td>';
			        $tbody .= $collections[0][$index][0];
			        $tbody .= '</td>';

			        $tbody .= '<td>';
			        $tbody .= date('Y-m-d',strtotime($collections[0][$index][1]));
			        $tbody .= '</td>';

			        $tbody .= '<td>';
			        $tbody .= date('Y-m-d',strtotime($collections[0][$index][2]));
			        $tbody .= '</td>';

			        $tbody .= '<td>';
			        $tbody .= $collections[0][$index][3];
			        $tbody .= '</td>';

			        $tbody .= '<td>';
			        $tbody .= $collections[0][$index][4];
			        $tbody .= '</td>';

		        $tbody .= '</tr>';
            }
            $result = $this->return_result(1,1,$tbody,1);
        }
        catch(\Exception $e) {
	        $result = $this->return_result(0,0,0,0);
        }
        return $result;
    }


    public function fn_insert_shipment_confirmation(Request $request){
        $result = $this->return_result(0,0,0,0);
        //insert
        // $user = User::where('employee_id', $request->txt_employee_number_scanner)
        $user = User::where('employee_id', Auth::user()->employee_id)
            ->where(function($q){
                $q->where('position',5)
                    ->orwhere('position',7)
                    ->orwhere('position',6)
                    ->orwhere('position',8)
                    ->orwhere('position',1)
                    ->orwhere('position',2);
            })
            ->get();
        if($user->count() > 0){
            foreach ($request->arr_rows as $key => $value) {
                DB::beginTransaction();
                try {
                    ShipmentConfirmation::insert(
                        [
                            'po_no'=>$value['po_no'],
                            'shipment_date'=>$value['shipment_date'],
                            'arrival_date'=>$value['arrival_date'],
                            'delivery_place_name'=>$value['delivery_place_name'],
                            'shipment_qty'=>$value['shipment_qty'],
                            'created_by'=>$user[0]->id,
                            'updated_by'=>$user[0]->id,
                            'created_at'=>date('Y-m-d H:i:s'),
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

    public function fn_delete_shipment_confirmation(Request $request){
        $result = $this->return_result(0,0,0,0);
        //insert
        $user = User::where('employee_id', $request->txt_employee_number_scanner)
            ->where(function($q){
                $q->where('position',5)
                    ->orwhere('position',7)
                    ->orwhere('position',8)
                    ->orwhere('position',1)
                    ->orwhere('position',2);
            })
            ->get();
        if($user->count() == 0){
            $user = User::where('employee_id', Auth::user()->employee_id)
                ->where(function($q){
                    $q->where('position',7)
                        ->orwhere('position',8);
                })
                ->get();
        }

        if($user->count() > 0){
            DB::beginTransaction();
            try {
                ShipmentConfirmation::where('id',$request->td_id)
                    ->update(
                    [
                        'delete_remarks'=>$request->txt_delete_remarks,
                        'updated_by'=>$user[0]->id,
                        'deleted_at'=>date('Y-m-d H:i:s'),
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
            $result = $this->return_result(2,2,"Logged in user or Scanned ID is not authorized to delete.",2);
        }
        return $result;
    }

    public function fn_select_shipment_confirmation_details(Request $request){
        $table_records_arr = array();
        $table_records = ShipmentConfirmation::
            where(
                [
                    [
                        'id','=',
                        $request->td_id
                    ],
                ]
            )
            ->get()
            ->take(1);

        if(!$table_records->isEmpty()){
            $table_records_arr = $table_records;
        }
        return $table_records_arr;
    }

    public function fn_update_shipment_confirmation(Request $request){
        $result = $this->return_result(0,0,0,0);
        $check_record = ShipmentConfirmation::where('id',$request->txt_update_old_id)->get(); // CHAN - 10-23-2024
        $shipment_qty = intval($check_record[0]->shipment_qty); // CHAN - 10-23-2024

        $user = User::where('employee_id', $request->txt_employee_number_scanner)
            ->where(function($q){
                $q->where('position',5)
                    ->orwhere('position',7)
                    ->orwhere('position',8)
                    ->orwhere('position',1)
                    ->orwhere('position',2);
            })
            ->get();

           
        if($user->count() == 0){
            $user = User::where('employee_id', Auth::user()->employee_id)
                ->where(function($q){
                    $q->where('position',7)
                        ->orwhere('position',8);
                })
                ->get();
        }
        else{
            $result = $this->return_result(2,2,"Scanned ID is not Supervisor/Inspector.",2);
        }

        
        if($user->count() > 0){
            DB::beginTransaction();
             
            try {
                // START - CHAN - 11-14-2024
                $count = 0; 
                $check_ppts_data = 
                CnPptsDLabel::with(
                    'cn_ppts_device_info',
                    'cn_ppts_d_label_history_details.pats_user_info'
                )
                ->where('pats_shipment_confirmation_id', $request->txt_update_old_id)
                ->whereNull('deleted_at')->get();

                if($check_ppts_data->isNotEmpty()){
                    if($check_ppts_data[0]->cn_ppts_d_label_history_details->isNotEmpty()){
                        for ($i=0; $i <count($check_ppts_data[0]->cn_ppts_d_label_history_details) ; $i++) { 
                            $count += $check_ppts_data[0]->cn_ppts_d_label_history_details[$i]->ship_boxing;
                        }
                    }
                    
                    if($request->txt_update_shipment_qty - $count){
                        CnPptsDLabel::where('pats_shipment_confirmation_id', $request->txt_update_old_id)
                        ->update([
                            'delivery_place_name'   => $request->txt_update_delivery_place_name,
                            // 'shipment_box'          => $request->txt_update_shipment_qty/$check_ppts_data[0]->cn_ppts_device_info->ship_boxing,
                        ]);
                    }
                }// END - CHAN - 11-14-2024

                ShipmentConfirmation::where('id',$request->txt_update_old_id)
                ->update([
                    'shipment_date'         => $request->txt_update_shipment_date,
                    'arrival_date'          => $request->txt_update_arrival_date,
                    'delivery_place_name'   => $request->txt_update_delivery_place_name,
                    'shipment_qty'          => $request->txt_update_shipment_qty,
                    'remarks'               => $request->txt_update_remarks,
                    'balance'               => $request->txt_update_shipment_qty-$count,
                    'updated_by'            => $user[0]->id,
                ]);

                DB::commit();
                $this->fn_insert_shipment_confirmation_history($request);
                $result = $this->return_result(1,1,1,1);
            } catch (Exception $e) {
                DB::rollback();
                $result = $this->return_result(0,0,0,0);
            }
        }
        else{
            $result = $this->return_result(2,2,"Logged in user is not authorized to update. Try scanning your ID instead.",2);
        }
        return $result;
    }


    public function fn_insert_shipment_confirmation_history($request){
        $result = $this->return_result(0,0,0,0);
        DB::beginTransaction();
        try {
            ShipmentConfirmationHistory::insert(
                [
                    'shipment_confirmation_id'=>$request['txt_update_old_id'],
                    'po_no'=>$request['txt_update_old_po_no'],
                    'shipment_date'=>$request['txt_update_old_shipment_date'],
                    'delivery_place_name'=>$request['txt_update_old_delivery_place_name'],
                    'shipment_qty'=>$request['txt_update_old_shipment_qty'],
                    'remarks'=>$request['txt_update_old_remarks'],
                    'created_by'=>$request['txt_update_old_created_by'],
                    'updated_by'=>$request['txt_update_old_updated_by'],
                    'created_at'=>$request['txt_update_old_created_at'],
                    'updated_at'=>$request['txt_update_old_updated_at'],
                ]
                );
            DB::commit();
            $result = $this->return_result(1,1,1,1);
        } catch (Exception $e) {
            DB::rollback();
            $result = $this->return_result(0,0,0,0);
        }
    }

    // CHAN - 10-17-2024 - START
    public function fn_select_shipment_confirmation_history(Request $request){
        $table_records = CnPptsDLabel::with('cn_ppts_d_label_history_details.pats_user_info')
            ->where('pats_shipment_confirmation_id', $request->shipCon)
            ->whereNull('deleted_at')
            ->get();

            if($table_records->isNotEmpty()){
                return DataTables::of($table_records[0]->cn_ppts_d_label_history_details)
                ->addColumn('po_no', function($shipment_confirmation) use($table_records) {
                    $result = '<center>';
                    $result .= $table_records[0]->po_no;
                    $result .= '</center>';
                    return $result;
                })
                ->addColumn('delivery_place_name', function($shipment_confirmation) use($table_records) {
                    $result = '<center>';
                    $result .= $table_records[0]->delivery_place_name;
                    $result .= '</center>';
                    return $result;
                })

                ->addColumn('created_by', function($shipment_confirmation) use($table_records) {
                    $result = '<center>';
                    $result .= optional(
                        $table_records[0]->cn_ppts_d_label_history_details[0]->pats_user_info
                    )->name ?? '';                    
                    $result .= '</center>';
                    return $result;
                })
                ->rawColumns(['po_no', 'delivery_place_name', 'created_by'])
                ->make(true);
            }else{
                return DataTables::of($table_records)
                ->make(true);
            }
    }
    // CHAN - 10-17-2024 - END

    // public function fn_select_shipment_confirmation_history(Request $request){
    //     $return = array();
    //     $tbody = '';
    //     $table_records = ShipmentConfirmationHistory::with('user_created_by','user_updated_by')
    //         ->where(
    //             [
    //                 [
    //                     'shipment_confirmation_id','=',
    //                     $request->td_id
    //                 ],
    //                 [
    //                     'deleted_at','=',
    //                     null
    //                 ],
    //             ]
    //         )
    //         ->get();

    //     if(!$table_records->isEmpty()){
    //         $ctr = 0;
    //         foreach ($table_records as $key => $value) {
    //             $tbody .= '<tr>';
    //                 $tbody .= '<td>';
    //                 $tbody .= $value->po_no;
    //                 $tbody .= '</td>';
    //                 $tbody .= '<td>';
    //                 $tbody .= $value->shipment_date;
    //                 $tbody .= '</td>';
    //                 $tbody .= '<td>';
    //                 $tbody .= $value->delivery_place_name;
    //                 $tbody .= '</td>';
    //                 $tbody .= '<td>';
    //                 $tbody .= $value->shipment_qty;
    //                 $tbody .= '</td>';
    //                 $tbody .= '<td>';
    //                 $tbody .= $value->remarks;
    //                 $tbody .= '</td>';
    //                 $tbody .= '<td>';
    //                 $tbody .= ($value->user_created_by)?$value->user_created_by->name:'';
    //                 $tbody .= '</td>';
    //                 $tbody .= '<td>';
    //                 $tbody .= $value->created_at;
    //                 $tbody .= '</td>';
    //                 $tbody .= '<td>';
    //                 $tbody .= ($value->user_received_by)?$value->user_received_by->name:'';
    //                 $tbody .= '</td>';
    //                 $tbody .= '<td>';
    //                 $tbody .= $value->received_at;
    //                 $tbody .= '</td>';
    //             $tbody .= '</tr>';
    //             $ctr++;
    //         }
    //     }
    //     $return['tbody'] = $tbody;
    //     return $return;
    // }


    public function fn_select_d_label_created(){
        $return = array();

        $return['d_label_id'] = array();
        $return['qty_left'] = array();
        $return['shipment_confirmation_id'] = array();

        $table_records = RapidDLabelHistory::selectRaw('d_label_id, SUM(ship_boxing) AS sum_ship_boxing')
            ->with('d_label')
            ->where(
                [
                    [
                        'removed','=',
                        0
                    ],
                    [
                        'deleted_at','=',
                        null
                    ],
                ]
            )
            ->groupBy('d_label_id')
            ->get();
            // ->lists('d_label_id', 'sum_shipment_qty');

        // return \DB::table('itemcosts')
        //     ->selectRaw('costType, sum(amountCost) as sum')
        //     ->where('itemid', $id)
        //     ->groupBy('costType')
        //     ->lists('sum', 'costType');

        if(!$table_records->isEmpty()){
            foreach ($table_records as $key => $value) {
                // echo $value['d_label']['po_no'].' ';
                // echo $value['sum_ship_boxing'].'"\n"';
                if( $value['d_label']['deleted_at'] == '0000-00-00 00:00:00' ){
                    array_push($return['d_label_id'], $value['d_label_id']);
                    array_push($return['qty_left'], $value['sum_ship_boxing']);
                    array_push($return['shipment_confirmation_id'], $value['d_label']['shipment_confirmation_id']);
                }
            }
        }
        $return['d_label_id'] = array_reverse($return['d_label_id']);
        $return['qty_left'] = array_reverse($return['qty_left']);
        $return['shipment_confirmation_id'] = array_reverse($return['shipment_confirmation_id']);
        // print_r( $return);
        return $return;
    }

}
