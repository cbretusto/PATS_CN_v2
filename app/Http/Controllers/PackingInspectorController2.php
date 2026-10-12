<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\PackingInspector;
use App\Model\PackingOperator;
use App\Model\oqcLotApp;
use App\Model\oqcVIR;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use DataTables;

class PackingInspectorController extends Controller
{
	public function view_batches(Request $request)
	{
		$material_issuances = oqcLotApp::with(['oqcvir_details'])->where('po_no',$request['po_number'])->get();

		return DataTables::of($material_issuances)
		->addColumn('action',function($material_issuance) use($request)
		{
			
				$disabled = '';
				$disabled2 = "disabled";

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
				else if($judgement == 1){

                		$packop = PackingOperator::where('po_num',$material_issuance->po_no)->where('batch',$material_issuance->lot_batch_no)->orderBy('updated_at', 'DESC')->distinct('batch')->first();
							if($packop == null)
							{
								$disabled = 'disabled';
							}
							else
							{
								if($packop->oqc_judgement == '1')
								{
									$disabled = ''; //SYA LANG PWEDE PUMASOK KASI APPROVED SYA NI PACKING OPERATOR

										//KUNG APPROVED NA KAY PACKING OPERATOR, PROCEED TO $packin IF ELSE
										$packin = PackingInspector::where('po_num',$material_issuance->po_no)->where('batch',$material_issuance->lot_batch_no)->orderBy('updated_at', 'DESC')->distinct('batch')->first();

										if($packin == null)
										{
											$disabled = ''; //SYA LANG PWEDE PUMASOK KASI APPROVED SYA NI PACKING OPERATOR, tapos wala pa si packing inspector
										}
										else
										{
											if($packin->oqc_judgement == '1')
											{
												$disabled = 'disabled';
												$disabled2 = "";
											}
											else if($packin->oqc_judgement == '2')
											{
												$disabled = '';
												$disabled2 = "";
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

				$result = '<button type="button" class="px-2 py-1 btn btn-sm btn-info btn_update_packing_inspector" data-toggle="modal" data-target="#modalPackingInspector" title="Update Details" po-num="'.$material_issuance->po_no.'" batch-num="'.$material_issuance->lot_batch_no.'" mat-sub="'.$material_issuance->submission.'" oqclotapp-id="'.$material_issuance->id.'" '.$disabled.'><i class="fa fa-edit fa-sm"></i></button>';

				$result .= ' <button type="button" class="px-2 py-1 btn btn-sm btn-success btn_view_packin_history" data-toggle="modal" data-target="#modalPackingInspectorHistory" title="View Packing QC History" po-num="'.$material_issuance->po_no.'" batch-num="'.$material_issuance->lot_batch_no.'" oqclotapp-id="'.$material_issuance->id.'" '.$disabled2.'><i class="fa fa-history fa-sm"></i></button>';

				return $result;
		})
		->addColumn('submission', function($material_issuance){
				$result = '';

				if($material_issuance->submission == '1')
				{
					$result .= '<span class="badge badge-pill badge-success">1st Submission</span><br>';
				}
				else if($material_issuance->submission == '2')
				{
					$result .= '<span class="badge badge-pill badge-warning">2nd Submission</span><br>';
				}
				else if($material_issuance->submission == '3')
				{
					$result .= '<span class="badge badge-pill badge-danger">3rd Submission</span><br>';
				}

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
					$result .= '<span class="badge badge-pill badge-warning">For OQC Inspector</span><br>';
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
								$result .= '<br><span class="badge badge-pill badge-info">For Packing Inspection</span>';
							}
							else
							{
								if($packin->oqc_judgement == '1')
								{
									$result .= '<br><span class="badge badge-pill badge-success">Packing QC Accepted</span>';
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
				else{
					$result .= '<span class="badge badge-pill badge-danger">OQC Lot Rejected</span>';
				}
				
				return $result;
		})
		->rawColumns(['status','submission','action'])
		->make(true);

	}

	public function retrieve_packop_details(Request $request)
	{
		$packop = PackingOperator::with(['user_details'])->where('po_num',$request->po_num)->where('batch',$request->batch)->where('submission',$request->mat_sub)->get();

		return response()->json(['packop' => $packop]);
	}

	public function retrieve_pack_code_from_packop(Request $request)
	{
		$packop = PackingOperator::where('po_num',$request->po_num)->where('batch',$request->batch)->where('submission',$request->mat_sub)->get();

		return response()->json(['packop' => $packop]);
	}

	public function link_to_packing_code_packin(Request $request)
	{
		$link_packin = PackingInspector::with('batch_details')->where('pack_code_no',$request->pack_code_packin)->orderBy('updated_at','desc')->first();

		if($link_packin != null)
		{
			if($link_packin->oqc_judgement == 1)
			{
				return response()->json(['link_packin' => $link_packin,'result' => "1"]);
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


	public function retrieve_packin_history(Request $request)
	{
		$packin_history = PackingInspector::with(['user_details','user_details_oqc'])->where('po_num',$request->po_num)->where('batch',$request->batch)->where('lotapp_fkid',$request->oqclotapp_id)->orderBy('id','desc')->get();

		return DataTables::of($packin_history)
		->addColumn('oqc_inspected_by',function($packin)
		{
			$result = $packin->user_details_oqc->name;

			return $result;
		})
		->addColumn('2_2_1',function($packin)
		{
			switch($packin->radio2_2_1)
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
		->addColumn('2_2_2',function($packin)
		{
			switch($packin->radio2_2_2)
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
		->addColumn('2_2_3',function($packin)
		{
			switch($packin->radio2_2_3)
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
		->addColumn('2_2_4',function($packin)
		{
			switch($packin->radio2_2_4)
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
		->addColumn('2_2_5',function($packin)
		{
			$result = $packin->total_num_reels;

			return $result;
		})
		->addColumn('pack_condition',function($packin)
		{
			switch($packin->pac_man_doc_comp)
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
		->addColumn('accessory_requirement',function($packin)
		{
			switch($packin->accessories)
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
		->addColumn('pack_code',function($packin)
		{
			$result = $packin->pack_code_no;

			return $result;
		})
		->addColumn('packin_stamp',function($packin)
		{
			$result = $packin->user_details->name;
			$result .= " - ";

			if($packin->oqc_judgement == 1)
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
		->addColumn('packin_datetime',function($packin)
		{
			$result = $packin->updated_at;

			return $result;
		})

		->rawColumns(['2_2_1','2_2_2','2_2_3','2_2_4','2_2_5','packin_stamp','pack_condition','accessory_requirement'])
		->make(true);
	}

	public function retrieve_oqc_name(Request $request)
	{
  		$oqcins = oqcVIR::with(['user_details'])->where('fkid_oqclotapp',$request->oqclotapp_id)->where('submission',$request->mat_sub)->get();

		return response()->json(['oqcins' => $oqcins]);
	}

	public function submit_packin(Request $request)
	{
		date_default_timezone_set('Asia/Manila');

		 $data  = $request->all();


		 $validator = Validator::make($data, [

		 		'name_221' => ['required', 'max:255'],
		 		'name_222' => ['required', 'max:255'],
		 		'name_223' => ['required', 'max:255'],
		 		'name_224' => ['required', 'max:255'],
		 		'name_225' => ['required', 'max:255'],
		 		'name_23' => ['required', 'max:255'],
		 		'name_24' => ['required', 'max:255'],
		 		'name_packin_pack_code_no' => ['required', 'string', 'max:255'],
		 		'name_packin_judgement'=> ['required', 'max:255']
		 ]);

		 if($validator->fails())
		 {
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
                        ->where('employee_id', $request->employee_number_scanner)->get();

		 	if($user->count() > 0)
		 	{

				 	DB::beginTransaction();

					try
					{
						PackingInspector::insert([
							'pac_man_doc_comp' => $request->name_23,
							'accessories' => $request->name_24,
							'pack_code_no' => $request->name_packin_pack_code_no,
							'emp_id' => $user[0]->id,
							'oqc_judgement' => $request->name_packin_judgement,
							'updated_at' => date('Y-m-d H:i:s'),
				            'created_at' => date('Y-m-d H:i:s')
						]);

						DB::commit();

						return response()->json(['result' => "1"]);
					}

				catch(Exception $e)
				{
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
