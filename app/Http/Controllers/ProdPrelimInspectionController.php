<?php

namespace App\Http\Controllers;

use App\Model\ProdPrelimInspection;
use App\Model\oqcLotApp;
use App\Model\PackingOperator;
use App\Model\RapidActiveDocs;
use App\Model\oqcVIR;
use App\oqcVIR1;
use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DataTables;
use Auth;

class ProdPrelimInspectionController extends Controller
{
	public function view_batches(Request $request)
	{
		$ppo_batches = ProdPrelimInspection::with(['oqcvir_details'])->where('po_num',$request->po_number)->select(DB::raw('distinct pack_code_no'))->get();

		return DataTables::of($ppo_batches)
		->addColumn('action', function($ppo_batch) use($request)
		{
			$result = '';

			$ppo_data = ProdPrelimInspection::with(['oqcvir_details'])->where('po_num',$request->po_number)->where('pack_code_no',$ppo_batch->pack_code_no)->distinct('pack_code_no')->orderBy('updated_at','desc')->first();

			//check lot count
			$lot_count = 0;
			//$packop = PackingOperator::with(['oqcvir_details'])->where('pack_code_no', $ppo_data->pack_code_no)->get();
			// $oqcvir = oqcVIR::with(['user_details','oqclotapp_details'])
			$oqcvir = oqcVIR1::with(['user_details','oqclotapp_details'])
			->whereHas('oqclotapp_details', function($query) use($request)
			{
				$query->where('po_no',$request->po_num);
			})
			->where('packing_code',$ppo_data->pack_code_no)->select(DB::raw('distinct fkid_oqclotapp'))->get();


			// $lotcounter = oqcVIR::with('oqclotapp_details')->whereHas('oqclotapp_details',function($query) use($request)
			$lotcounter = oqcVIR1::with('oqclotapp_details')->whereHas('oqclotapp_details',function($query) use($request)
			{
				$query->where('po_no',$request->po_number);

			})->where('packing_code',$ppo_batch->pack_code_no)->get();

			for($i = 0; $i < count($lotcounter); $i++)
			{
				$lot_count += $lotcounter[$i]->oqclotapp_details->output_qty;
			}	
			
			if($ppo_data->operator_judgement == 1)
			{
				$result = ' <button type="button" class="px-2 py-1 btn btn-sm btn-success btn_open_packop_history" data-toggle="modal" data-target="#modal_ppo_history" title="View Production Preliminary Inspection History" po-num='.$ppo_data->po_num.' pack-code='.$ppo_data->pack_code_no.'><i class="fa fa-history fa-sm"></i></button>';
			}
			else
			{
				$result = ' <button type="button" class="px-2 py-1 btn btn-sm btn-success btn_open_packop_history" data-toggle="modal" data-target="#modal_ppo_history" title="View Production Preliminary Inspection History" po-num='.$ppo_data->po_num.' pack-code='.$ppo_data->pack_code_no.'><i class="fa fa-history fa-sm"></i></button>';

				$result .= ' <button type="button" class="px-2 py-1 btn btn-sm btn-warning btn_open_ppo_supervisor" title="Load Supervisor Conformance" po-num='.$ppo_data->po_num.' pack-code='.$ppo_batch->pack_code_no.' lot-count='.$lot_count.'><i class="fa fa-edit fa-sm"></i></button>';	
			}

			return $result;	
		})
		->addColumn('pack_code', function($ppo_batch) use($request)
		{
			$ppo_data = ProdPrelimInspection::with(['oqcvir_details'])->where('po_num',$request->po_number)->where('pack_code_no',$ppo_batch->pack_code_no)->distinct('pack_code_no')->orderBy('updated_at','desc')->first();
			$result = $ppo_data->pack_code_no;

			return $result;	
		})
		->addColumn('output_qty', function($ppo_batch) use($request)
		{
			$ppo_data = ProdPrelimInspection::with(['oqcvir_details'])->where('po_num',$request->po_number)->where('pack_code_no',$ppo_batch->pack_code_no)->distinct('pack_code_no')->orderBy('updated_at','desc')->first();

			$lot_count = 0;
			// $lotcounter = oqcVIR::with('oqclotapp_details')->whereHas('oqclotapp_details',function($query) use($request)
			$lotcounter = oqcVIR1::with('oqclotapp_details')->whereHas('oqclotapp_details',function($query) use($request)
			{
				$query->where('po_no',$request->po_number);

			})->where('packing_code',$ppo_batch->pack_code_no)->get();

			for($i = 0; $i < count($lotcounter); $i++)
			{
				$lot_count += $lotcounter[$i]->oqclotapp_details->output_qty;
			}	

			return $lot_count;
		})
		->addColumn('status', function($ppo_batch) use($request)
		{
			$ppo_data = ProdPrelimInspection::with(['oqcvir_details'])->where('po_num',$request->po_number)->where('pack_code_no',$ppo_batch->pack_code_no)->distinct('pack_code_no')->orderBy('updated_at','desc')->first();

			$result = '';

			switch($ppo_data->operator_judgement)
			{
				case 1:
				{	
					$result = '<span class="badge badge-pill badge-success">Packing Lot Accept</span>';
					break;
				}
				case 2:
				{
					$result = '<span class="badge badge-pill badge-danger">Packing Lot Reject</span>';
					break;
				}
				default:
				{
					$result = '<span class="badge badge-pill badge-secondary">---</span>';
					break;
				}
			}

			return $result;
		})
		->rawColumns(['action','status'])
		->make(true);
	}

	public function ppo_view_pack_code_history(Request $request)
	{
		$ppo_histories = ProdPrelimInspection::with(['user_details'])->where('po_num',$request->po_num)->where('pack_code_no', $request->pack_code)->orderBy('updated_at','desc')->get();

		return DataTables::of($ppo_histories)
		->addColumn('judgement', function($ppo_history)
		{
			$result = $ppo_history->user_details->name;
			$result .= " - ";

			if($ppo_history->operator_judgement == 1)
			{
				$result .= "Accepted";
				$result2 = "<span class='badge badge-pill badge-success'>".$result."</span>";
			}
			else
			{	
				$result .= "Rejected";
				$result2 = "<span class='badge badge-pill badge-danger'>".$result."</span>";
			}			

			return $result2;
		})
		->addColumn('datetime', function($ppo_history)
		{
			$result = $ppo_history->created_at;

			return $result;
		})
		->addColumn('po_num', function($ppo_history)
		{
			$result = $ppo_history->po_num;

			return $result;
		})
		->addColumn('pack_code', function($ppo_history)
		{
			$result = $ppo_history->pack_code_no;

			return $result;
		})
		->addColumn('pack_type', function($ppo_history)
		{
			switch($ppo_history->packing_type)
			{
				case 1:
				{
					$result = "<span class='badge badge-pill badge-info'>Esafoam</span>";
					break;
				}
				case 2:
				{
					$result = "<span class='badge badge-pill badge-info'>Magazine Tube</span>";
					break;
				}
				case 3:
				{
					$result = "<span class='badge badge-pill badge-info'>Tray</span>";
					break;
				}
				case 4:
				{
					$result = "<span class='badge badge-pill badge-info'>Bubble Sheet</span>";
					break;
				}
				case 5:
				{
					$result = "<span class='badge badge-pill badge-info'>Emboss Reel</span>";
					break;
				}
				case 6:
				{
					$result = "<span class='badge badge-pill badge-info'>Polybag</span>";
					break;
				}
				default:
				{
					$result = "<span class='badge badge-pill badge-info'>---</span>";
					break;
				}

			}

			return $result;
		})
		->addColumn('condition', function($ppo_history)
		{
			switch($ppo_history->packing_unit_condition)
			{
				case 1:
				{
					$result = "<span class='badge badge-pill badge-info'>Terminal Up</span>";
					break;
				}
				case 2:
				{
					$result = "<span class='badge badge-pill badge-info'>Terminal Down</span>";
					break;
				}
				case 3:
				{
					$result = "<span class='badge badge-pill badge-info'>Terminal Mounted on Esafoam</span>";
					break;
				}
				case 4:
				{
					$result = "<span class='badge badge-pill badge-info'>Terminal Side</span>";
					break;
				}
				case 5:
				{
					$result = "<span class='badge badge-pill badge-info'>Unit Mounted on Esafoam</span>";
					break;
				}
				case 6:
				{
					$result = "<span class='badge badge-pill badge-info'>Wrap on Bubble Sheet</span>";
					break;
				}
				default:
				{
					$result = "<span class='badge badge-pill badge-info'>---</span>";
					break;
				}

			}

			return $result;

		})
		->addColumn('orientation', function($ppo_history)
		{
			switch($ppo_history->orientation_of_units)
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
		->addColumn('qty_per_box', function($ppo_history)
		{
			switch($ppo_history->qty_per_box_tray)
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
		->addColumn('ul_sticker', function($ppo_history)
		{
			switch($ppo_history->ul_sticker)
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
		->addColumn('silicagel', function($ppo_history)
		{
			switch($ppo_history->silica_gel)
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
		->addColumn('accessories', function($ppo_history)
		{
			switch($ppo_history->accessories)
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
		->addColumn('rohs', function($ppo_history)
		{
			switch($ppo_history->rohs_sticker)
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
		->rawColumns(['judgement','pack_type','condition','orientation','qty_per_box','ul_sticker','silicagel','accessories','rohs'])
		->make(true);
	}

	public function retrieve_reel_lot_no_from_packing_code(Request $request)
	{
		//$reellots = PackingOperator::with(['user_details','oqcvir_details','lotapp_details'])->where('po_num',$request->po_num)->where('pack_code_no',$request->pack_code)->get();
		// $reellots = oqcVIR::with(['user_details','oqclotapp_details'])->whereHas('oqclotapp_details', function($query) use($request){
		$reellots = oqcVIR1::with(['user_details','oqclotapp_details'])->whereHas('oqclotapp_details', function($query) use($request){
			// $query->where('po_no','<>','x');
			$query->where('po_no',$request->po_num);
			// })->where('packing_code',$request->pack_code)->select(DB::raw('distinct fkid_oqclotapp'))->get();
			})->where('packing_code',$request->pack_code)->where('status', 1)->select(DB::raw('distinct fkid_oqclotapp'))->get();

		return response()->json(['reellots' => $reellots]);
	}

	public function check_exist_pack_code(Request $request)
	{
		$result = '';

		//$pack_code = PackingOperator::with(['oqcvir_details'])->where('po_num',$request->po_num)->where('pack_code_no',$request->pack_code)->get();

		// $pack_code = oqcVIR::with(['user_details','oqclotapp_details'])->whereHas('oqclotapp_details', function($query) use($request){
		$pack_code = oqcVIR1::with(['user_details','oqclotapp_details'])->whereHas('oqclotapp_details', function($query) use($request){
			$query->where('po_no',$request->po_num);
			})->where('packing_code',$request->pack_code)->select(DB::raw('distinct fkid_oqclotapp'))->get();

		if(count($pack_code) > 0)
		{
			// 3-if existed in ProdPrelimInspection, it means it is already inspected
			$ppo = ProdPrelimInspection::where('po_num',$request->po_num)->where('pack_code_no',$request->pack_code)->get();
			if(count($ppo) > 0)
			{
				$result = '3';
			}
			else
			{
				$result = '1';
			}
		}
		else
		{
			$result = '2';
		}

		$lot_count = 0;

		for($i = 0; $i < count($pack_code); $i++)
		{
			// $lotcounter = oqcVIR::with('oqclotapp_details')->where('fkid_oqclotapp',$pack_code[$i]->fkid_oqclotapp)->get();
			$lotcounter = oqcVIR1::with('oqclotapp_details')->where('fkid_oqclotapp',$pack_code[$i]->fkid_oqclotapp)->get();

            if(count($lotcounter) > 0)
            {
                $lot_count += $lotcounter[0]->oqclotapp_details->output_qty;
            }
		}


		return response()->json(['pack_code_details' => $pack_code, 'result' => $result, 'lotcount' => $lot_count]);
	}

	public function ppo_retrieve_r_drawing(Request $request)
	{
		$documents = RapidActiveDocs::where('doc_title',$request->device)->where('doc_type','R Drawing')->get();

		return response()->json(['documents' => $documents]);
	}

	public function check_if_supervisor(Request $request)
	{
		$user = User::where('employee_id',$request->employee)->get();

		if($user->count() > 0)
		{
			if($user[0]->position == '1' || $user[0]->position == '2')
			{
				$result = '1';
			}
			else
			{
				$result = '2';
			}
		}
		else
		{
			$result = '3';
		}

		return response()->json(['result' => $result]);
	}


	public function submit_prelim_inspection(Request $request)
	{
		date_default_timezone_set('Asia/Manila');

		$data = $request->all();

		$validator = Validator($data,[
			'name_PackopPackingType' => 'required',
			'name_PackopUnitCondition' => 'required',
			'name_ppi_orientation' => 'required',
			'name_ppi_qty' => 'required',
			'name_ppi_ulsticker' => 'required',
			'name_ppi_silicagel' => 'required',
			'name_ppi_accessories' => 'required',
			'name_ppi_rohs' => 'required',
			'name_operator_judgement' => 'required',
		]);

		if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else
        {
        	$user = User::where('employee_id', $request->employee_number_scanner)->get();


        	if($user->count() > 0)
        	{
        		DB::beginTransaction();

	        	try
	        	{
	        		ProdPrelimInspection::insert([

	        			'po_num' => $request->name_modal_po_num,
	        			'device_name' => $request->name_modal_device,
	        			'pack_code_no' => $request->name_modal_pack_code,
	        			'packing_type' => $request->name_PackopPackingType,
	        			'packing_unit_condition' => $request->name_PackopUnitCondition,
	        			'orientation_of_units' => $request->name_ppi_orientation,
	        			'qty_per_box_tray' => $request->name_ppi_qty,
	        			'ul_sticker' => $request->name_ppi_ulsticker,
	        			'silica_gel' => $request->name_ppi_silicagel,
	        			'accessories'=> $request->name_ppi_accessories,
	        			'rohs_sticker'=> $request->name_ppi_rohs,
	        			'emp_id' => $user[0]->id,
	        			'operator_judgement' => $request->name_operator_judgement,
	        			'created_at' => date('Y-m-d H:i:s'),
	        			'updated_at' => date('Y-m-d H:i:s')
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

	public function submit_prelim_inspection_supervisor(Request $request)
	{
		date_default_timezone_set('Asia/Manila');

		$data = $request->all();

		$validator = Validator($data,[
			'name_supervisor_PackopPackingType' => 'required',
			'name_supervisor_PackopUnitCondition' => 'required',
			'name_supervisor_ppi_orientation' => 'required',
			'name_supervisor_ppi_qty' => 'required',
			'name_supervisor_ppi_ulsticker' => 'required',
			'name_supervisor_ppi_silicagel' => 'required',
			'name_supervisor_ppi_accessories' => 'required',
			'name_supervisor_ppi_rohs' => 'required',
			'name_supervisor_judgement' => 'required',
		]);

		if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else
        {
        	//->whereIn('position',[1,2]) add if supervisor can save only
        	$user = User::where('employee_id', $request->employee_supervisor_scanner)->get();

        	if($user->count() > 0)
        	{
        		DB::beginTransaction();

	        	try
	        	{
	        		ProdPrelimInspection::insert([

	        			'po_num' => $request->name_modal_supervisor_po_num,
	        			'device_name' => $request->name_modal_supervisor_device,  
	        			'pack_code_no' => $request->name_modal_supervisor_pack_code,
	        			'packing_type' => $request->name_supervisor_PackopPackingType,
	        			'packing_unit_condition' => $request->name_supervisor_PackopUnitCondition,
	        			'orientation_of_units' => $request->name_supervisor_ppi_orientation,
	        			'qty_per_box_tray' => $request->name_supervisor_ppi_qty,
	        			'ul_sticker' => $request->name_supervisor_ppi_ulsticker,
	        			'silica_gel' => $request->name_supervisor_ppi_silicagel,
	        			'accessories'=> $request->name_supervisor_ppi_accessories,
	        			'rohs_sticker'=> $request->name_supervisor_ppi_rohs,
	        			'emp_id' => $user[0]->id,
	        			'operator_judgement' => $request->name_supervisor_judgement,
	        			'created_at' => date('Y-m-d H:i:s'),
	        			'updated_at' => date('Y-m-d H:i:s')
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
