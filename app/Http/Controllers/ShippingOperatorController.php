<?php

namespace App\Http\Controllers;

use App\Model\oqcLotApp;
use App\Model\ShippingOperator;
use App\Model\PackingInspector;
use App\User;
use App\Model\PackingOperator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DataTables;

class ShippingOperatorController extends Controller
{
	public function view_batches(Request $request)
	{
		$packing_codes = PackingOperator::with(['lotapp_details'])->where('po_num',$request->po_number)->select(DB::raw('distinct pack_code_no'))->get();

		return DataTables::of($packing_codes)
		->addColumn('action',function($packing_code) use($request)
		{
			$packcode_data = PackingOperator::with(['lotapp_details'])->where('po_num',$request->po_number)->where('pack_code_no',$packing_code->pack_code_no)->distinct('pack_code_no')->first();

			//CHECK DISABLED
			$packin = PackingInspector::where('po_num',$request->po_number)->where('pack_code_no',$packing_code->pack_code_no)->distinct('pack_code_no')->orderBy('updated_at','desc')->first();

			if($packin != null)
			{
				switch($packin->oqc_judgement)
				{
				case 1:
					{
						$disabled = 'disabled';

						$shipop = ShippingOperator::where('po_num',$request->po_number)->where('pack_code_no',$packing_code->pack_code_no)->distinct('pack_code_no')->orderBy('updated_at','desc')->first();

						if($shipop != null)
						{
							switch($shipop->oqc_judgement)
							{
								case 1:
								{
									$disabled = 'disabled';
									$disabled2 = '';
									break;
								}
								case 2:
								{
									$disabled = '';
									$disabled2 = '';
									break;
								}
							}
						}
						else
						{
							$disabled = '';
							$disabled2 = 'disabled';
						}

						break;
					}
					case 2:
					{
						$disabled = 'disabled';
						break;
					}
				}
			}
			else
			{
				$disabled = 'disabled';
			}


			//END OF CHECK DISABLED



			$result = '<button type="button" class="px-2 py-1 btn btn-sm btn-info btn_update_shipping_operator" data-toggle="modal" data-target="#modalShippingOperator" title="Update Details" po-num='.$packcode_data->po_num.' packing-code='.$packcode_data->pack_code_no.' '.$disabled.'><i class="fa fa-edit fa-sm"></i></button>';

			$result .= ' <button type="button" class="px-2 py-1 btn btn-sm btn-success btn_open_shipop_history" data-toggle="modal" data-target="#modalShippingOperatorHistory" title="View Shipping Operator History" packing-code='.$packcode_data->pack_code_no.' po-num='.$packcode_data->po_num.' '.$disabled2.'><i class="fa fa-history fa-sm"></i></button>';

			return $result;
		})
		->addColumn('pack_code',function($packing_code) use($request)
		{
			$packcode_data = PackingOperator::with(['lotapp_details'])->where('po_num',$request->po_number)->where('pack_code_no',$packing_code->pack_code_no)->distinct('pack_code_no')->first();
			$result = $packcode_data->pack_code_no;

			return $result;
		})
		->addColumn('total_box_qty',function($packing_code) use($request)
		{
			$balance = PackingOperator::with(['lotapp_details'])->where('po_num',$request->po_number)->where('pack_code_no',$packing_code->pack_code_no)->get();
			$total_box_qty = 0;

			for($i = 0; $i < count($balance); $i++)
			{
				$total_box_qty += $balance[$i]->lotapp_details->output_qty;
			}

			return $total_box_qty;
		})
		->addColumn('status',function($packing_code) use($request)
		{
			$packin = PackingInspector::where('po_num',$request->po_number)->where('pack_code_no',$packing_code->pack_code_no)->distinct('pack_code_no')->orderBy('updated_at','desc')->first();

			if($packin != null)
			{
				switch($packin->oqc_judgement)
				{
				case 1:
					{
						$result = "<span class='badge badge-pill badge-success'>Packing QC Accept</span>";

						$shipop = ShippingOperator::where('po_num',$request->po_number)->where('pack_code_no',$packing_code->pack_code_no)->distinct('pack_code_no')->orderBy('updated_at','desc')->first();

						if($shipop != null)
						{
							switch($shipop->oqc_judgement)
							{
								case 1:
								{
									$result .= "<br><span class='badge badge-pill badge-success'>Shipping Operator Accept</span>";
									break;
								}
								case 2:
								{
									$result .= "<br><span class='badge badge-pill badge-danger'>Shipping Operator Reject</span>";
									break;
								}
							}
						}
						else
						{
							$result .= "<br><span class='badge badge-pill badge-info'>For Shipment</span>";
						}

						break;
					}
					case 2:
					{
						$result = "<span class='badge badge-pill badge-danger'>Packing QC Reject</span>";
						break;
					}
				}
			}
			else
			{
				$result = "<span class='badge badge-pill badge-warning'>For Packing QC Inspection</span>";
			}

			return $result;
		})
		->rawColumns(['action','status'])
		->make(true);
	}

	public function view_packin_history_by_packing_code(Request $request)
	{
		$packin_histories = PackingInspector::with(['user_details'])->where('po_num',$request->po_number)->where('pack_code_no',$request->packing_code)->orderBy('updated_at', 'DESC')->get();

		return DataTables::of($packin_histories)
		->addColumn('packin_judgement',function($packin_history)
		{
			$result = $packin_history->user_details->name;
			$result .= " - ";

			if($packin_history->oqc_judgement == 1)
			{
				$result .= "Accepted";
				$result2 = "<span class='badge badge-pill badge-success'>".$result."</span>";
			}
			else
			{	
				$result .= "Denied";
				$result2 = "<span class='badge badge-pill badge-danger'>".$result."</span>";
			}			

			return $result2;
		})
		->addColumn('packin_pack_code',function($packin_history)
		{
			$result = $packin_history->pack_code_no;
			
			return $result;
		})
		->addColumn('packin_datetime',function($packin_history)
		{
			$result = $packin_history->updated_at;
			
			return $result;
		})
		->addColumn('packin_c3_check',function($packin_history)
		{
			switch($packin_history->check_c3)
			{
				case 1:
				{
					$result = "<span class='badge badge-pill badge-success'>Yes</span>";
					break;
				}
				case 2:
				{
					$result = "<span class='badge badge-pill badge-danger'>No</span>";
					break;
				}
				case 3:
				{
					$result = "<span class='badge badge-pill badge-warning'>N/A</span>";
					break;
				}
				default:
				{
					$result = "<span class='badge badge-pill badge-info'>---</span>";
				}
			}
			return $result;
		})
		->addColumn('packin_doc_compliance',function($packin_history)
		{
			switch($packin_history->pac_man_doc_comp)
			{
				case 1:
				{
					$result = "<span class='badge badge-pill badge-success'>Yes</span>";
					break;
				}
				case 2:
				{
					$result = "<span class='badge badge-pill badge-danger'>No</span>";
					break;
				}
				case 3:
				{
					$result = "<span class='badge badge-pill badge-warning'>N/A</span>";
					break;
				}
				default:
				{
					$result = "<span class='badge badge-pill badge-info'>---</span>";
				}
			}
			return $result;
		})
		->addColumn('packin_accessory',function($packin_history)
		{
			switch($packin_history->accessories)
			{
				case 1:
				{
					$result = "<span class='badge badge-pill badge-success'>Yes</span>";
					break;
				}
				case 2:
				{
					$result = "<span class='badge badge-pill badge-danger'>No</span>";
					break;
				}
				case 3:
				{
					$result = "<span class='badge badge-pill badge-warning'>N/A</span>";
					break;
				}
				default:
				{
					$result = "<span class='badge badge-pill badge-info'>---</span>";
				}
			}
			return $result;
		})
		->rawColumns(['packin_judgement','packin_c3_check','packin_doc_compliance','packin_accessory'])
		->make(true);
	}

	public function submit_shipop(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [

			'name_311' => ['required', 'string', 'max:255'],
			'name_312' => ['required', 'string', 'max:255'],
			'name_313' => ['required', 'string', 'max:255'],
			'name_314' => ['required', 'string', 'max:255'],
			'name_321' => ['required', 'string', 'max:255'],
			'name_322' => ['required', 'string', 'max:255'],
			'name_323' => ['required', 'string', 'max:255'],
			'name_plcn' => ['required', 'string', 'max:255'],
			'name_tsq' => ['required', 'string', 'max:255'],
			'name_tbq' => ['required', 'string', 'max:255'],
			'name_shipop_judgement' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{

        	$user = User::where('employee_id', $request->employee_number_scanner)->get();

		 	if($user->count() > 0)
		 	{

            DB::beginTransaction();

            try{
                ShippingOperator::insert([
					
					'po_num' => $request->name_hidden_ponum,
					'pack_code_no' => $request->name_modal_packing_code,
					'radio3_1_1' => $request->name_311,
					'radio3_1_2' => $request->name_312,
					'radio3_1_3' => $request->name_313,
					'radio3_1_4' => $request->name_314,
					'radio3_2_1' => $request->name_321,
					'radio3_2_2' => $request->name_322,
					'radio3_2_3' => $request->name_323,
					'pack_list_con_no' => $request->name_plcn,
					'total_shipment_qty' => $request->name_tsq,
					'total_box_qty' => $request->name_tbq,
					'oqc_judgement' => $request->name_shipop_judgement,
					'emp_id' => $user[0]->id,
					'updated_at' => date('Y-m-d H:i:s'),
				    'created_at' => date('Y-m-d H:i:s')
                ]);

                DB::commit();
                return response()->json(['result' => "1"]);
            }
            catch(\Exception $e) {
                DB::rollback();
                // throw $e;
                return response()->json(['result' => $e]);
            }
        }
        else
        {
        	return response()->json(['result' => "2"]);
        }

        }
    }
}
