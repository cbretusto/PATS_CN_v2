<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\PackingInspector;
use App\Model\PackingOperator;

use App\Model\oqcLotApp;
use App\Model\DlabelHistory;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Model\C3LabelHistory;


use App\Model\ProductionRuncard;
use App\Model\MaterialIssuanceSubSystem;
use App\Model\Device;

use App\Model\DLabelCheckerHistory;

//----
use App\Model\MaterialIssuance;
use App\Model\WBSKitIssuance;
// use App\Model\MaterialIssuanceSubSystem;
use App\Model\PartsPrep;


use GuzzleHttp\Client;


use DataTables;

define('TIMEZONE', 'Asia/Manila');
date_default_timezone_set(TIMEZONE);


class DLABELPrintingController extends Controller
{
	public function fn_view_dlabelprinting(Request $request)
	{
		return view('dlabelprinting');
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



	//--------------
	//-------------- D LABEL CHECKER
	//--------------
	public function fn_dlabelchecker(Request $request)
	{
		return view('dlabelchecker');
	}


    public function fn_insert_d_label_checker_history(Request $request){
        $result = $this->return_result(0,0,0,0);
        $user = User::where('employee_id', $request->txt_employee_number_scanner)
            ->where(function($q){
                $q->where('position',4)
                	->orWhere('position',5);
            })
            ->get();
        if($user->count() > 0){
            //insert
            DB::beginTransaction();
            try {

                DLabelCheckerHistory::insert(
                    [
                        'unique_no_start'=>$request->unique_no_start,
                        'po_no'=>$request->po_no,
                        'd_label_lot_no_arr'=>implode(',', $request->d_label_lot_no_arr),
                        'c3_label_lot_no_arr'=>implode(',', $request->c3_label_lot_no_arr),
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
        else{
            $result = $this->return_result(2,2,2,2);
        }
        return $result;
    }

	public function fn_select_d_label_checker_history(Request $request){
        $d_label_checker_history = DLabelCheckerHistory::
            where('deleted_at','=',null)
            ->orderBy('created_at','DESC')
            ->get();

        return DataTables::of($d_label_checker_history)
            ->addColumn('raw_action', function($d_label_checker_history){
            	$result = '';
                $hidden = '';

                $user_position = Auth::user()->position;
                if( $user_position == 2 ){
	                $hidden = 'hidden';
                }
                $result.='<button title="Open details" class="td_btn_action td_btn_open_details btn btn-info btn-sm py-0"><i class="fa fa-info-circle fa-sm"></i></button>';
                $result.=' <input type="hidden" class="td_id" value="'.$d_label_checker_history->id.'">';
                return $result;
            })
            ->addColumn('raw_status', function($d_label_checker_history){
            	$result = '';
                $result = '<span data-sorter="1" class="badge badge-success font-weight-normal">Passed</span>';

                if( $d_label_checker_history->d_label_lot_no_arr != $d_label_checker_history->c3_label_lot_no_arr ){
	                $result = '<span data-sorter="0" class="badge badge-danger font-weight-normal">Failed</span>';
                }
                return $result;
            })
            ->rawColumns(['raw_action','raw_status'])
            ->make(true);
	}

    public function fn_select_d_label_checker_details(Request $request){
        $d_label_checker_details = DLabelCheckerHistory::
        	where('id',$request->td_id)
            ->get()->take(1);
        return $d_label_checker_details;
    }

    // public function fn_check_session_expired(Request $request){
    //     $arr = array();
    //     $arr['expired'] = strval( Auth::check() );
    //     return $arr;
    // }


}
