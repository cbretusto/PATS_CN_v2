<?php

namespace App\Http\Controllers;

use App\Model\OQCPrelimInspection;

use App\Model\ProdPrelimInspection;
use App\Model\oqcLotApp;
use App\Model\PackingOperator;
use App\Model\RapidActiveDocs;
use App\Model\oqcVIR;
use App\Model\Device;
use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DataTables;
use Auth;

class OQCPrelimInspectionController extends Controller
{
	public function view_batches(Request $request)
	{
		$oqc_batches = OQCPrelimInspection::with(['oqcvir_details'])->where('po_num',$request->po_number)->select(DB::raw('distinct pack_code_no'))->get();

			return DataTables::of($oqc_batches)
			->addColumn('action',function($oqc_batch) use($request)
			{	
				$result = '';

				$oqc_data = OQCPrelimInspection::with(['oqcvir_details'])->where('po_num',$request->po_number)->where('pack_code_no',$oqc_batch->pack_code_no)->distinct('pack_code_no')->orderBy('updated_at','desc')->first();

				//check lot count
				$lot_count = 0;
				
				$packop = oqcVIR::with(['user_details','oqclotapp_details'])
				->whereHas('oqclotapp_details', function($query) use($request)
				{
					$query->where('po_no',$request->po_num);
				})
				->where('packing_code',$oqc_data->pack_code_no)->get();

				$lotcounter = oqcVIR::with('oqclotapp_details')->whereHas('oqclotapp_details',function($query) use($request)
				{
					$query->where('po_no',$request->po_number);

				})->where('packing_code',$oqc_batch->pack_code_no)->get();

				for($i = 0; $i < count($lotcounter); $i++)
				{
					$lot_count += $lotcounter[$i]->ok_qty;
				}

				switch($oqc_data->inspector_judgement) 
				{
					case 1:
					{
						$result = ' <button type="button" class="px-2 py-1 btn btn-sm btn-success btn_open_packin_history" data-toggle="modal" data-target="#modal_oqc_history" title="View OQC Preliminary Inspection History" po-num='.$oqc_data->po_num.' pack-code='.$oqc_data->pack_code_no.'><i class="fa fa-history fa-sm"></i></button>';

						if($oqc_data->shipping_date == null)
						{
							$result .= ' <button type="button" class="px-2 py-1 btn btn-sm btn-info btn_open_shipping_details" data-toggle="modal" data-target="#modal_shipping_details" title="Add Shipping Details" po-num='.$oqc_data->po_num.' lot-count='.$lot_count.' pack-code='.$oqc_data->pack_code_no.'><i class="fa fa-truck fa-sm"></i></button>';
						}

						break;
					}
					case 2:
					{	
						$result = ' <button type="button" class="px-2 py-1 btn btn-sm btn-success btn_open_packin_history" data-toggle="modal" data-target="#modal_oqc_history" title="View OQC Preliminary Inspection History" po-num='.$oqc_data->po_num.' pack-code='.$oqc_data->pack_code_no.'><i class="fa fa-history fa-sm"></i></button>';

						$result .= ' <button type="button" class="px-2 py-1 btn btn-sm btn-warning btn_open_oqc_supervisor" title="Load Supervisor Conformance" po-num='.$oqc_data->po_num.' pack-code='.$oqc_data->pack_code_no.' lot-count='.$lot_count.'><i class="fa fa-edit fa-sm"></i></button>';	


						break;
					}
					default:
					{
						break;
					}
				}

				return $result;
			})
			->addColumn('pack_code',function($oqc_batch) use($request)
			{
				$oqc_data = OQCPrelimInspection::with(['oqcvir_details'])->where('po_num',$request->po_number)->where('pack_code_no',$oqc_batch->pack_code_no)->distinct('pack_code_no')->orderBy('updated_at','desc')->first();
				
				$result = $oqc_data->pack_code_no;

				return $result;
			})
			->addColumn('output_qty',function($oqc_batch) use($request)
			{
				$oqc_data = OQCPrelimInspection::with(['oqcvir_details'])->where('po_num',$request->po_number)->where('pack_code_no',$oqc_batch->pack_code_no)->distinct('pack_code_no')->orderBy('updated_at','desc')->first();

				$lot_count = 0;
				$lotcounter = oqcVIR::with('oqclotapp_details')->whereHas('oqclotapp_details',function($query) use($request)
				{
					$query->where('po_no',$request->po_number);

				})->where('packing_code',$oqc_batch->pack_code_no)->get();

				for($i = 0; $i < count($lotcounter); $i++)
				{
					$lot_count += $lotcounter[$i]->ok_qty;
				}	
				

				return $lot_count;
			})
			->addColumn('status',function($oqc_batch) use($request)
			{
				$oqc_data = OQCPrelimInspection::with(['oqcvir_details'])->where('po_num',$request->po_number)->where('pack_code_no',$oqc_batch->pack_code_no)->distinct('pack_code_no')->orderBy('updated_at','desc')->first();

				switch($oqc_data->inspector_judgement)
				{
					case 1:
					{	
						$result = '<span class="badge badge-pill badge-success">Packing Lot QC Accept</span>';

						if($oqc_data->shipping_date == null)
						{
							$result .= '<br><span class="badge badge-pill badge-info">For Shipping Details Fill-in</span>';
						}
						else
						{
							$result .= '<br><span class="badge badge-pill badge-success">Ready for Shipment</span>';
						}

						break;
					}
					case 2:
					{
						$result = '<span class="badge badge-pill badge-danger">Packing Lot QC Reject</span>';
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

	public function view_history(Request $request)
	{
		$oqc_histories = OQCPrelimInspection::with('user_details')->where('po_num',$request->po_number)->where('pack_code_no',$request->pack_code)->orderBy('updated_at','desc')->get();

		return DataTables::of($oqc_histories)
		->addColumn('judgement',function($oqc_history){

			$result = $oqc_history->user_details->name;
			$result .= " - ";

			if($oqc_history->inspector_judgement == 1)
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
		->addColumn('datetime',function($oqc_history){
			
			$result = $oqc_history->created_at;

			return $result;
		})
		->addColumn('po_num',function($oqc_history){

			$result = $oqc_history->po_num;

			return $result;			
		})
		->addColumn('pack_code',function($oqc_history){

			$result = $oqc_history->pack_code_no;

			return $result;			
		})
		->addColumn('doc_compliance',function($oqc_history){
			
			switch($oqc_history->document_compliance)
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
		->addColumn('acc_req',function($oqc_history){
			
			switch($oqc_history->accessory_requirement)
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
		->addColumn('coc_req',function($oqc_history){

			switch($oqc_history->coc_requirement)
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
		->addColumn('ship_date',function($oqc_history){

			if($oqc_history->shipping_date == null)
			{
				$result = "<span class='badge badge-pill badge-info'>---</span>";
			}
			else
			{
				$result = $oqc_history->shipping_date;
			}

			return $result;			
		})
		->addColumn('ship_destination',function($oqc_history){

			if($oqc_history->shipping_destination == null)
			{
				$result = "<span class='badge badge-pill badge-info'>---</span>";
			}
			else
			{
				$result = $oqc_history->shipping_destination;
			}		
			
			return $result;
		})
		->addColumn('ship_remarks',function($oqc_history){
			
			if($oqc_history->shipping_remarks == null)
			{
				$result = " ";
			}
			else
			{
				$result = $oqc_history->shipping_remarks;
			}

			return $result;		
		})
		->rawColumns(['judgement','doc_compliance','acc_req','coc_req','ship_date','ship_destination','ship_remarks'])
		->make(true);
	}

	public function oqc_check_exist_pack_code(Request $request)
	{
		$result = '';

		$pack_code =  oqcVIR::with(['user_details','oqclotapp_details'])
			->whereHas('oqclotapp_details', function($query) use($request)
			{
				$query->where('po_no',$request->po_num);
			})
			->where('packing_code',$request->pack_code_no)->select(DB::raw('distinct fkid_oqclotapp'))->get();

		if(count($pack_code) > 0)
		{
			$prod = ProdPrelimInspection::where('po_num',$request->po_num)->where('pack_code_no',$request->pack_code)->get();

			if(count($prod) > 0)
			{
				$ppo = OQCPrelimInspection::where('po_num',$request->po_num)->where('pack_code_no',$request->pack_code)->get();

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
				$result = '4';
			}
		}
		else
		{
			$result = '2';
		}

		$lot_count = 0;

		for($i = 0; $i < count($pack_code); $i++)
		{
			$lotcounter = oqcVIR::where('fkid_oqclotapp',$pack_code[$i]->fkid_oqclotapp)->get();

			if(count($lotcounter) > 0)
			{
				$lot_count += $lotcounter[0]->ok_qty;
			}
		}

		return response()->json(['pack_code_details' => $pack_code, 'result' => $result, 'lotcount' => $lot_count]);
	}

	public function submit_oqc_inspection(Request $request)
	{
		date_default_timezone_set('Asia/Manila');

		$data = $request->all();

		$validator = Validator($data,[
			'name_document_compliance' => 'required',
			'name_accessory_req' => 'required',
			'name_coc_req' => 'required',
			'name_qc_judgement' => 'required',
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
	        		OQCPrelimInspection::insert([

	        			'po_num' => $request->name_modal_po_num,
	        			'pack_code_no' => $request->name_modal_pack_code,
	        			'document_compliance' => $request->name_document_compliance,
	        			'accessory_requirement' => $request->name_accessory_req,
	        			'coc_requirement' => $request->name_coc_req,
	        			'emp_id' => $user[0]->id,
	        			'inspector_judgement' => $request->name_qc_judgement,
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

	public function submit_shipping_details(Request $request)
	{
		date_default_timezone_set('Asia/Manila');

		$data = $request->all();

		$validator = Validator($data,[
			'name_shipping_date' => 'required',
			'name_shipping_destination' => 'required',
		]);

		if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else
        {
        	$user = User::with([
                            'oqc_stamps' => function($query) {
                                $query->where('status', 1);
                                $query->orderBy('id', 'desc');    
                            }
                        ])
        				->withCount([
                            'oqc_stamps' => function($query) {
                                $query->where('status', 1);
                                $query->orderBy('id', 'desc');    
                            }
                        ])
                        ->having('oqc_stamps_count', '>', 0)
                        ->where('employee_id', $request->shipping_number_scanner)->get();

        	if($user->count() > 0)
        	{
        		DB::beginTransaction();

	        	try
	        	{
	        		OQCPrelimInspection::where('po_num',$request->name_shipping_po_num)->where('pack_code_no',$request->name_shipping_pack_code)->where('inspector_judgement',1)
	        		->update([

	        			'shipping_date' => $request->name_shipping_date,
	        			'shipping_destination' => $request->name_shipping_destination,
	        			'shipping_remarks' =>$request->name_shipping_remarks
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

	public function submit_oqc_inspection_supervisor(Request $request)
	{
		date_default_timezone_set('Asia/Manila');

		$data = $request->all();

		$validator = Validator($data,[
			'name_supervisor_document_compliance' => 'required',
			'name_supervisor_accessory_req' => 'required',
			'name_supervisor_coc_req' => 'required',
			'name_supervisor_qc_judgement' => 'required',
		]);

		if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else
        {
        	// add ->whereIn('position',[1,2]) if only supervisor can save
        	$user = User::with([
                            'oqc_stamps' => function($query) {
                                $query->where('status', 1);
                                $query->orderBy('id', 'desc');    
                            }
                        ])
        				->withCount([
                            'oqc_stamps' => function($query) {
                                $query->where('status', 1);
                                $query->orderBy('id', 'desc');    
                            }
                        ])
                        ->having('oqc_stamps_count', '>', 0)
                        ->where('employee_id', $request->employee_supervisor_scanner)->get();


        	if($user->count() > 0)
        	{
        		DB::beginTransaction();

	        	try
	        	{
	        		OQCPrelimInspection::insert([

	        			'po_num' => $request->name_modal_supervisor_po_num,
	        			'pack_code_no' => $request->name_modal_supervisor_pack_code,
	        			'document_compliance' => $request->name_supervisor_document_compliance,
	        			'accessory_requirement' => $request->name_supervisor_accessory_req,
	        			'coc_requirement' => $request->name_supervisor_coc_req,
	        			'emp_id' => $user[0]->id,
	        			'inspector_judgement' => $request->name_supervisor_qc_judgement,
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

	public function oqc_check_if_supervisor(Request $request)
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


	public function oqc_retrieve_data_from_pack_code(Request $request)
	{
		$reellots = oqcVIR::with(['user_details','oqclotapp_details'])->whereHas('oqclotapp_details', function($query) use($request){
			$query->where('po_no',$request->po_num);
			})->where('packing_code',$request->pack_code)->select(DB::raw('distinct fkid_oqclotapp'))->get();

		$device = Device::where('name',$request->device)->first();

		return response()->json(['reellots' => $reellots,'device' => $device]);
	}

}