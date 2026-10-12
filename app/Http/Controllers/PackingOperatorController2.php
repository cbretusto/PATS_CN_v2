<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\PackingOperator;
use App\Model\PackingInspector;
use App\Model\oqcLotApp;
use App\Model\oqcVIR;
use App\Model\RapidPackingCode;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use DataTables;

class PackingOperatorController extends Controller
{
	public function view_batches(Request $request)
	{
		//Search 
		
		$material_issuances = oqcLotApp::with(['oqcvir_details'])->where('po_no',$request['po_number'])
		->whereHas("oqcvir_details", function($q)
			{
				$q->where('judgement',1);
			})
			->where('shipping_date','!=', null)
			->select(DB::raw('distinct shipping_date'))
			->orderBy('updated_at','desc')->get();

		return DataTables::of($material_issuances)
		->addColumn('action', function($material_issuance) use($request){

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
				else if($judgement == 1){

                		$packop = PackingOperator::where('po_num',$material_issuance->po_no)->where('batch',$material_issuance->lot_batch_no)->orderBy('updated_at', 'DESC')->distinct('batch')->first();

							if($packop == null)
							{
								$disabled = '';
							}
							else
							{
								if($packop->oqc_judgement == '1')
								{
									$disabled = 'disabled';
									$disabled2 = '';
								}
								else if($packop->oqc_judgement == '2')
								{
									$disabled = '';
									$disabled2 = '';
								}
							}
				}
				else if($judgement == 2){
					$disabled = 'disabled';
				}

				$result = '<button type="button" class="px-2 py-1 btn btn-sm btn-info btn_update_packing_operator" data-toggle="modal" data-target="#modalPackingOperator" title="Update Details" po-num="'.$material_issuance->po_no.'" batch-num="'.$material_issuance->lot_batch_no.'" mat-sub="'.$material_issuance->submission.'" oqclotapp-id="'.$material_issuance->id.'" '.$disabled.'><i class="fa fa-edit fa-sm"></i></button>';

				$result .= ' <button type="button" class="px-2 py-1 btn btn-sm btn-success btn_view_packop_history" data-toggle="modal" data-target="#modalPackingOperatorHistory" title="View Packing History" po-num="'.$material_issuance->po_no.'" batch-num="'.$material_issuance->lot_batch_no.'" oqclotapp-id="'.$material_issuance->id.'" '.$disabled2.'><i class="fa fa-history fa-sm"></i></button>';

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
					$result .= '<span class="badge badge-pill badge-warning">For OQC Inspection</span><br>';
				}
				else if($judgement == 1){
						$result .= '<span class="badge badge-pill badge-success">OQC Lot Accepted</span>';

                		$packop = PackingOperator::where('po_num',$material_issuance->po_no)->where('batch',$material_issuance->lot_batch_no)->orderBy('updated_at', 'DESC')->distinct('batch')->first();


							if($packop == null)
							{
								$result .= '<br><span class="badge badge-pill badge-info">Lot for Packing</span>';
							}
							else
							{
								if($packop->oqc_judgement == '1')
								{
									$result .= '<br><span class="badge badge-pill badge-success">Packing Operator Accepted</span>';
								}
								else if($packop->oqc_judgement == '2')
								{
									$result .= '<br><span class="badge badge-pill badge-danger">Packing Operator Rejected</span>';
								}
							}
				}
				else if($judgement == 2){
					$result .= '<span class="badge badge-pill badge-danger"> OQC Lot Rejected</span>';
				}

				return $result;
			})
			->rawColumns(['status','submission','action'])
			->make(true);
	}

	public function generate_packing_code(Request $request)
	{
		$series_name = '';

		$series_name = explode("-", $request->device_name)[0];

		$pack_code = RapidPackingCode::where('cnpts_series_name','like','%'.$series_name. '%')->get();

		$autogenNum = 1;

		$month = date('m');

		$packcode_line = $pack_code[0]->id;
		$packcode_line .= $month;

		$packop = PackingOperator::where('pack_code_no','like','%'.$packcode_line.'%')->whereMonth('updated_at',$month)->distinct('pack_code_no')->count('pack_code_no');

		if($packop != null)
		{
			$autogenNum = $packop + 1;
		}

		return response()->json(['pack_code' => $pack_code, 'autogenNum' => $autogenNum]);
	}

	public function link_to_packing_code_packop(Request $request)
	{
		$link_packop = PackingOperator::where('pack_code_no',$request->pack_code_packop)->orderBy('updated_at','desc')->first();


		$packop_count = PackingOperator::with(['lotapp_details'])->where('pack_code_no',$request->pack_code_packop)->orderBy('updated_at','desc')->distinct('batch')->get();

		$check_packin = PackingInspector::where('pack_code_no',$request->pack_code_packop)->get();

	
			$packop_count_total = 0;

			if(count($packop_count) > 0)
			{
				for($i = 0; $i < count($packop_count); $i++)
				{
					$packop_count_total += $packop_count[$i]->lotapp_details->output_qty;
				}
				if(count($check_packin) == 0)
				{
					if($packop_count_total == $request->box_qty)
					{
						return response()->json(['result' => "3"]);
					}
					else
					{	
							if($link_packop != null)
							{
								return response()->json(['link_packop' => $link_packop,'result' => "1"]);
							}
							else
							{
								return response()->json(['result' => "2"]);
							}
					}
				}
				else
				{
					return response()->json(['result' => "4"]);
				}
			}
			else
			{
				return response()->json(['result' => "2"]);
			}

	}


	public function retrieve_oqc_details(Request $request)
	{
		$oqclot = oqcLotApp::with(['user_details','assy_details'])->where('po_no',$request->po_num)->where('lot_batch_no',$request->batch)->where('submission',$request->mat_sub)->get();

		return response()->json(['oqclot' => $oqclot]);
	}

	public function view_oqcvir_history(Request $request)
	{
		$oqcvir_histories = PackingOperator::with(['oqcvir_details','user_details'])->where('po_num',$request->po_number)->orderBy('updated_at', 'DESC')->get();

		return DataTables::of($oqcvir_histories)
			->addColumn('oqcvir_qc_judgement',function($oqcvir_history)
			{
				$result = $oqcvir_history->oqcvir_details->user_details->name;
				$result .= " (" .$oqcvir_history->oqcvir_details->insp_stamp. ")";

				switch($oqcvir_history->oqcvir_details->judgement)
				{
					case 1:
					{
						$result2 = "<span class='badge badge-pill badge-success'>".$result." - ACCEPT </span>";
						break;
					}
					case 2:
					{
						$result2 = "<span class='badge badge-pill badge-danger'>".$result." - REJECT </span>";
						break;	
					}
				}

				return $result2;
			})
			->addColumn('oqcvir_lot_no',function($oqcvir_history)
			{
				$result = "<span class='badge badge-pill badge-info'>".$oqcvir_history->batch."</span>";

				return $result;
			})
			->addColumn('oqcvir_submission',function($oqcvir_history)
			{
				 switch ($oqcvir_history->oqcvir_details->submission) 
				 {
                    case 1:
                        $result ='<span class="badge badge-pill s1 badge-success">1st Sub</span>';
                        break;
                    case 2:
                        $result ='<span class="badge badge-pill s2 badge-warning">2nd Sub</span>';
                        break;
                    case 3:
                        $result ='<span class="badge badge-pill s3 badge-danger">3rd Sub</span>';
                        break;
                }

			return $result;
			})
			->addColumn('oqcvir_sample_size',function($oqcvir_history)
			{
				$result = $oqcvir_history->oqcvir_details->oqc_sample;

				return $result;
			})
			->addColumn('oqcvir_ok_qty',function($oqcvir_history)
			{
				$result = $oqcvir_history->oqcvir_details->ok_qty;

				return $result;
			})
			->addColumn('oqcvir_ng_qty',function($oqcvir_history)
			{
				$result = $oqcvir_history->oqcvir_details->ng_qty;

				return $result;
			})
			->addColumn('oqcvir_insp_date',function($oqcvir_history)
			{
				$result = date('F j, Y',strtotime($oqcvir_history->oqcvir_details->insp_date));
            	
            	return $result;
			})
			->addColumn('oqcvir_insp_time',function($oqcvir_history)
			{
				$result = date('h:i a',strtotime('2001-01-01'.$oqcvir_history->oqcvir_details->insp_stime)).' - ';
	            $result .= date('h:i a',strtotime('2001-01-01'.$oqcvir_history->oqcvir_details->insp_etime));
	            return $result;
			})
			->addColumn('oqcvir_accessories',function($oqcvir_history)
			{
				 switch ($oqcvir_history->oqcvir_details->acc_req) 
				 {
                    case 1:
                        $result ='<span class="badge badge-pill s1 badge-success">Yes</span>';
                        break;
                    case 2:
                        $result ='<span class="badge badge-pill s2 badge-secondary">No</span>';
                        break;
                }

			return $result;
			})
			->addColumn('oqcvir_coc',function($oqcvir_history)
			{
				switch ($oqcvir_history->oqcvir_details->coc_req) 
				 {
                    case 1:
                        $result ='<span class="badge badge-pill s1 badge-success">Yes</span>';
                        break;
                    case 2:
                        $result ='<span class="badge badge-pill s2 badge-secondary">No</span>';
                        break;
                }

			return $result;
			})
			->addColumn('oqcvir_remarks',function($oqcvir_history)
			{
				$result = $oqcvir_history->oqcvir_details->remarks;

				return $result;
			})
			->rawColumns(['oqcvir_qc_judgement','oqcvir_lot_no','oqcvir_submission','oqcvir_accessories','oqcvir_coc'])
			->make(true);
	}

	public function retrieve_packop_history(Request $request)
	{
		$packop_history = PackingOperator::with(['user_details'])->where('po_num',$request->po_num)->where('batch',$request->batch)->where('lotapp_fkid',$request->oqclotapp_id)->orderBy('id','desc')->get();

		return DataTables::of($packop_history)
		->addColumn('pack_type', function($packop)
		{
			switch($packop->packop_packing_type)
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
				}

			}

			return $result;
		})
		->addColumn('unit_condition', function($packop)
		{
			switch($packop->packop_unit_condition)
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
				}

			}

			return $result;
		})
		->addColumn('1_3_1', function($packop)
		{
			switch($packop->radio1_3_1)
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
		->addColumn('1_3_2', function($packop)
		{
			switch($packop->radio1_3_2)
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
		->addColumn('1_3_3', function($packop)
		{
			switch($packop->radio1_3_3)
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
		->addColumn('1_3_4', function($packop)
		{
			switch($packop->radio1_3_4)
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
		->addColumn('1_3_5', function($packop)
		{
			$result = $packop->total_num_reels;

			return $result;
		})
		->addColumn('pack_code', function($packop)
		{
			$result = $packop->pack_code_no;

			return $result;
		})
		->addColumn('1_5_1', function($packop)
		{
			switch($packop->radio1_5_1)
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
		->addColumn('1_5_2', function($packop)
		{
			switch($packop->radio1_5_2)
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
		->addColumn('1_5_3', function($packop)
		{
			switch($packop->radio1_5_3)
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
		->addColumn('1_5_4', function($packop)
		{
			switch($packop->radio1_5_4)
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
		->addColumn('1_5_5', function($packop)
		{
			switch($packop->radio1_5_5)
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
		->addColumn('1_5_6', function($packop)
		{
			switch($packop->radio1_5_6)
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
		->addColumn('packop_name', function($packop)
		{
			$result = $packop->user_details->name;
			$result .= " - ";

			if($packop->oqc_judgement == 1)
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

		->addColumn('packop_date', function($packop)
		{
			$result = $packop->updated_at;

			return $result;
		})
		->rawColumns(['pack_type', 'unit_condition','1_3_1','1_3_2','1_3_3','1_3_4','1_5_1','1_5_2','1_5_3','1_5_4','1_5_5','1_5_6','packop_name'])
		->make(true);
	}

  //RETRIEVE OQC VIR SUMMARY

	public function submit_packop(Request $request)
	{
		date_default_timezone_set('Asia/Manila');

		 $data  = $request->all();


		 $validator = Validator::make($data, [

		 		'name_PackopPackingType' => ['required', 'string', 'max:255'],
		 		'name_PackopUnitCondition' => ['required', 'string', 'max:255'],
		 		'name_131' => ['required', 'max:255'],
		 		'name_132' => ['required', 'max:255'],
		 		'name_133' => ['required', 'max:255'],
		 		'name_134' => ['required', 'max:255'],
		 		'name_135' => ['required', 'max:255'],
		 		//'name_PackingCode' => ['required', 'string', 'max:255'],
		 		'name_151' => ['required', 'max:255'],
		 		'name_152' => ['required', 'max:255'],
		 		'name_153' => ['required', 'max:255'],
		 		'name_154' => ['required', 'max:255'],
		 		'name_155' => ['required', 'max:255'],
		 		'name_156' => ['required', 'max:255'],
		 		'name_judgement' => ['required', 'string', 'max:255']
		 ]);

		 if($validator->fails())
		 {
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
						PackingOperator::insert([
							'po_num' => $request->name_myponum,
							'batch' => $request->name_mybatch,
							'submission' => $request->name_mysub,
							'lotapp_fkid' => $request->name_mylotapp,
							'packop_packing_type' => $request->name_PackopPackingType,
							'packop_unit_condition' => $request->name_PackopUnitCondition,
							'radio1_3_1' => $request->name_131,
							'radio1_3_2' => $request->name_132,
							'radio1_3_3' => $request->name_133,
							'radio1_3_4' => $request->name_134,
							'total_num_reels' => $request->name_135,
							'pack_code_no' => $request->name_PackingCode,
							'radio1_5_1' => $request->name_151,
							'radio1_5_2' => $request->name_152,
							'radio1_5_3' => $request->name_153,
							'radio1_5_4' => $request->name_154,
							'radio1_5_5' => $request->name_155,
							'radio1_5_6' => $request->name_156,
							'emp_id' => $user[0]->id,
							'oqc_judgement' => $request->name_judgement,
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
