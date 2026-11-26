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
		$material_issuances = oqcLotApp::with(['oqcvir_details'])->where('po_no',$request['po_number'])->get();

		return DataTables::of($material_issuances)
		->addColumn('action',function($material_issuance) use($request)
		{
				$disabled = '';
				$disabled2 = 'disabled';

				$judgement = 0;

				if($material_issuance->oqcvir_details != null){
					if($material_issuance->oqcvir_details->judgement == '1'){
						$judgement = 1;
					}
					else{
						$judgement = 2;
					}
				}
				else{
					$judgement = 0;

				}

				if($judgement == 0){
					$disabled = 'disabled';
				}
				else if($judgement == 1)
				{
					$packop = PackingOperator::where('po_num',$material_issuance->po_no)->where('batch',$material_issuance->lot_batch_no)->orderBy('updated_at', 'DESC')->distinct('batch')->first();

					if($packop == null)
					{
						$disabled = 'disabled';
					}
					else
					{
						if($packop->oqc_judgement == '1')
						{
							$disabled = 'disabled';

							//KUNG APPROVED NA KAY PACKING OPERATOR, PROCEED TO $packin IF ELSE
							$packin = PackingInspector::where('po_num',$material_issuance->po_no)->where('batch',$material_issuance->lot_batch_no)->orderBy('updated_at', 'DESC')->distinct('batch')->first();

							if($packin == null)
							{
								$disabled = 'disabled';
							}
							else
							{
								if($packin->oqc_judgement == '1')
								{
									$disabled = 'disabled';

									//KUNG APPROVED NA KAY PACKING INSPECTOR, PROCEED TO $shipop IF ELSE
									$shipop = ShippingOperator::where('po_num',$material_issuance->po_no)->where('batch',$material_issuance->lot_batch_no)->orderBy('updated_at', 'DESC')->distinct('batch')->first();

									if($shipop == null)
									{
										$disabled = '';
									}
									else
									{
										if($shipop->oqc_judgement == '1')
										{
											$disabled = 'disabled';
											$disabled2 = "";
										}
										else if($shipop->oqc_judgement == '2')
										{
											$disabled = '';
											$disabled2 = "";
										}
									}
								}
								else if($packin->oqc_judgement == '2')
								{
									$disabled = 'disabled';
								}
							}

						}
						else if($packop->oqc_judgement == '2')
						{
							$disabled = 'disabled';
						}
					}
				}
				else if($judgement == 2){
					$disabled = 'disabled';
				}

					$result = '<button type="button" class="px-2 py-1 btn btn-sm btn-info btn_update_shipping_operator" data-toggle="modal" data-target="#modalShippingOperator" title="Update Details" po-num="'.$material_issuance->po_no.'" batch-num="'.$material_issuance->lot_batch_no.'" mat-sub="'.$material_issuance->submission.'" oqclotapp-id="'.$material_issuance->id.'" '.$disabled.'><i class="fa fa-edit fa-sm"></i></button>';

					$result .= ' <button type="button" class="px-2 py-1 btn btn-sm btn-success btn_view_shipop_history" data-toggle="modal" data-target="#modalShippingOperatorHistory" title="View Shipping Operator History" po-num="'.$material_issuance->po_no.'" batch-num="'.$material_issuance->lot_batch_no.'" oqclotapp-id="'.$material_issuance->id.'" '.$disabled2.'><i class="fa fa-history fa-sm"></i></button>';

				

				return $result;
		})
		
		->addColumn('lotapp', function($material_issuance){
				$result = $material_issuance->lot_batch_no;
				return $result;
		})
		->addColumn('lotqty', function($material_issuance){
				$result = $material_issuance->lot_qty;
				return $result;
		})
		->addColumn('packing_code', function($material_issuance){

				$packop = PackingOperator::where('po_num',$material_issuance->po_no)->where('batch',$material_issuance->lot_batch_no)->get();

							if(count($packop) > 0)
							{
								$result = $packop[0]->pack_code_no;
							}
							else
							{
								$result = "---";
							}

				return $result;
			})
		->addColumn('status', function($material_issuance) use($request){

			
				 $result = '';
                $judgement = 0;

				if($material_issuance->oqcvir_details != null){
					if($material_issuance->oqcvir_details->judgement == '1'){
						$judgement = 1;
					}
					else{
						$judgement = 2;
					}
				}
				else{
					$judgement = 0;

				}

				if($judgement == 0){
					$result .= '<span class="badge badge-pill badge-warning">For OQC Inspection</span><br>';
				}
				else if($judgement == 1)
				{
						$result .= '<span class="badge badge-pill badge-success">OQC Lot Accepted</span>';


					$packop = PackingOperator::where('po_num',$material_issuance->po_no)->where('batch',$material_issuance->lot_batch_no)->orderBy('updated_at', 'DESC')->distinct('batch')->first();

					if($packop == null)
					{
						$result .= '<br><span class="badge badge-pill badge-warning">Lot for Packing</span>';
					}
					else
					{
						if($packop->oqc_judgement == '1')
						{
							$result .= '<br><span class="badge badge-pill badge-success">Packing Operator Accepted</span>';

							//KUNG APPROVED NA KAY PACKING OPERATOR, PROCEED TO $packin IF ELSE
							$packin = PackingInspector::where('po_num',$material_issuance->po_no)->where('batch',$material_issuance->lot_batch_no)->orderBy('updated_at', 'DESC')->distinct('batch')->first();

							if($packin == null)
							{
								$result .= '<br><span class="badge badge-pill badge-warning">For Packing Inspection</span>';
							}
							else
							{
								if($packin->oqc_judgement == '1')
								{
									$result .= '<br><span class="badge badge-pill badge-success">Packing QC Accepted</span>';

									//KUNG APPROVED NA KAY PACKING INSPECTOR, PROCEED TO $shipop IF ELSE
									$shipop = ShippingOperator::where('po_num',$material_issuance->po_no)->where('batch',$material_issuance->lot_batch_no)->orderBy('updated_at', 'DESC')->distinct('batch')->first();

									if($shipop == null)
									{
										$result .= '<br><span class="badge badge-pill badge-info">Lot for Shipment</span>';
									}
									else
									{
										if($shipop->oqc_judgement == '1')
										{
											$result .= '<br><span class="badge badge-pill badge-success">Shipping Operator Accepted</span>';
										}
										else if($shipop->oqc_judgement == '2')
										{
											$result .= '<br><span class="badge badge-pill badge-danger">Shipping Operator Rejected</span>';
										}
									}
								}
								else if($packin->oqc_judgement == '2')
								{
									$result .= '<br><span class="badge badge-pill badge-danger">Packing QC Rejected</span>';
								}
							}

						}
						else if($packop->oqc_judgement == '2')
						{
							$result .= '<br><span class="badge badge-pill badge-danger">Packing Operator Rejected</span>';
						}
					}
				}
				else if($judgement == 2)
				{
					$result .= '<span class="badge badge-pill badge-danger">OQC Lot Denied</span>';
				}
				
				return $result;
		})
		->rawColumns(['status','submission','action'])
		->make(true);

	}

	public function link_to_packing_code_shipop(Request $request)
	{
		$link_shipop = ShippingOperator::where('pack_code_no',$request->pack_code_shipop)->orderBy('updated_at','desc')->first();

		if($link_shipop != null)
		{
			if($link_shipop->oqc_judgement == 1)
			{
				return response()->json(['link_shipop' => $link_shipop,'result' => "1"]);
			}
			else
			{
				return response()->json(['result' => "2"]);
			}
		}
		else
		{
			return response()->json(['result' => "2"]);
		}
	}



	public function retrieve_shipop_history(Request $request)
	{
		$shipop_history = ShippingOperator::with(['user_details'])->where('po_num',$request->po_num)->where('batch',$request->batch)->where('lotapp_fkid',$request->oqclotapp_id)->orderBy('id','desc')->get();

		return DataTables::of($shipop_history)
		->addColumn('3_1_1',function($shipop)
		{
			switch($shipop->radio3_1_1)
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
		->addColumn('3_1_2',function($shipop)
		{
			switch($shipop->radio3_1_2)
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
		->addColumn('3_1_3',function($shipop)
		{
			switch($shipop->radio3_1_3)
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
		->addColumn('3_1_4',function($shipop)
		{
			switch($shipop->radio3_1_4)
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
		->addColumn('3_2_1',function($shipop)
		{
			$result = $shipop->radio3_2_1;

			return $result;
		})
		->addColumn('3_2_2',function($shipop)
		{
			$result = $shipop->radio3_1_1;

			return $result;
		})
		->addColumn('3_2_3',function($shipop)
		{
			$result = $shipop->radio3_1_1;

			return $result;
		})
		->addColumn('pack_list_con_no',function($shipop)
		{
			$result = $shipop->pack_list_con_no;

			return $result;
		})
		->addColumn('total_shipment_qty',function($shipop)
		{
			$result = $shipop->total_shipment_qty;

			return $result;
		})
		->addColumn('total_box_qty',function($shipop)
		{
			$result = $shipop->total_box_qty;

			return $result;
		})
		->addColumn('shipop_name',function($shipop)
		{
			$result = $shipop->user_details->name;
			$result .= " - ";

			if($shipop->oqc_judgement == 1)
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
		->addColumn('shipop_datetime',function($shipop)
		{
			$result = $shipop->created_at;

			return $result;
		})
		->rawColumns(['3_1_1','3_1_2','3_1_3','3_1_4', 'shipop_name'])
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
					'po_num' => $request->name_myponum,
					'batch' => $request->name_mybatch,
					'submission' => $request->name_mysub,
					'lotapp_fkid'=> $request->name_mylotapp,
					'pack_code_no' => $request->name_link_packcode_shipop,
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
