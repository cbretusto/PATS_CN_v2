<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\PackingInspector;
use App\Model\PackingOperator;
use App\Model\oqcLotApp;
use App\Model\oqcVIR;
use App\Model\AssemblyLine;
use App\Model\Device;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use DataTables;

class PackingInspectorController extends Controller
{
	public function view_batches(Request $request)
	{
		$packing_codes = PackingOperator::with(['lotapp_details'])->where('po_num',$request->po_number)->select(DB::raw('distinct pack_code_no'))->orderBy('pack_code_no','asc')->get();

		return DataTables::of($packing_codes)
		->addColumn('action',function($packing_code) use($request)
		{
			$packcode_data = PackingOperator::with(['lotapp_details'])->where('po_num',$request->po_number)->where('pack_code_no',$packing_code->pack_code_no)->distinct('pack_code_no')->first();

			//CHECK IF ALREADY FILLED IN
			$packin = PackingInspector::where('po_num',$request->po_number)->where('pack_code_no',$packing_code->pack_code_no)->distinct('pack_code_no')->orderBy('updated_at','desc')->first();

			if($packin != null)
			{
				switch($packin->oqc_judgement)
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
					default:
					{
						break;
					}
				}
			}
			else
			{
				$disabled = '';
				$disabled2 = 'disabled';
			}

			$result = '<button type="button" class="px-2 py-1 btn btn-sm btn-info btn_update_packing_inspector" data-toggle="modal" data-target="#modalPackingInspector" title="Update Details" packing-code='.$packcode_data->pack_code_no.' po-num='.$packcode_data->po_num.' '.$disabled.'><i class="fa fa-edit fa-sm"></i></button>';

			$result .= ' <button type="button" class="px-2 py-1 btn btn-sm btn-success btn_open_packin_history" data-toggle="modal" data-target="#modalPackingInspectorHistory" title="View Packing QC Inspection History" packing-code='.$packcode_data->pack_code_no.' po-num='.$packcode_data->po_num.' '.$disabled2.'><i class="fa fa-history fa-sm"></i></button>';

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
						break;
					}
					case 2:
					{
						$result = "<span class='badge badge-pill badge-danger'>Packing QC Reject</span>";
						break;
					}
					default:
					{
						break;
					}
				}
			}
			else
			{
				$result = "<span class='badge badge-pill badge-info'>For Packing QC Inspection</span>";
			}

			return $result;
		})
		->rawColumns(['action','status'])
		->make(true);
	}

	public function view_packop_history_by_packing_code(Request $request)
	{
		$packop_histories = PackingOperator::with(['lotapp_details','user_details'])->where('po_num',$request->po_number)->where('po_num',$request->po_number)->where('pack_code_no',$request->packing_code)->orderBy('updated_at', 'DESC')->get();

		return DataTables::of($packop_histories)
		->addColumn('packop_judgement',function($packop_history)
		{
			$result = $packop_history->user_details->name;

			$result .= " - ";

			if($packop_history->oqc_judgement == 1)
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
		->addColumn('packop_datetime',function($packop_history)
		{
			$result = $packop_history->updated_at;

			return $result;
		})
		->addColumn('packop_lot_no',function($packop_history)
		{
			$result = "<span class='badge badge-pill badge-info'>".$packop_history->batch."</span>";

			return $result;
		})
		->addColumn('packop_packing_type',function($packop_history)
		{
			switch($packop_history->packop_packing_type)
			{
				case 1:
				{
					$result = "<span class='badge badge-pill badge-secondary'>Esafoam</span>";
					break;
				}
				case 2:
				{
					$result = "<span class='badge badge-pill badge-secondary'>Magazine Tube</span>";
					break;
				}
				case 3:
				{
					$result = "<span class='badge badge-pill badge-secondary'>Tray</span>";
					break;
				}
				case 4:
				{
					$result = "<span class='badge badge-pill badge-secondary'>Bubble Sheet</span>";
					break;
				}
				case 5:
				{
					$result = "<span class='badge badge-pill badge-secondary'>Emboss Reel</span>";
					break;
				}
				case 6:
				{
					$result = "<span class='badge badge-pill badge-secondary'>Polybag</span>";
					break;
				}
				default:
				{
					$result = "<span class='badge badge-pill badge-secondary'>---</span>";
				}

			}
			return $result;
		})
		->addColumn('packop_unit_condition',function($packop_history)
		{
			switch($packop_history->packop_unit_condition)
			{
				case 1:
				{
					$result = "<span class='badge badge-pill badge-secondary'>Terminal Up</span>";
					break;
				}
				case 2:
				{
					$result = "<span class='badge badge-pill badge-secondary'>Terminal Down</span>";
					break;
				}
				case 3:
				{
					$result = "<span class='badge badge-pill badge-secondary'>Terminal Mounted on Esafoam</span>";
					break;
				}
				case 4:
				{
					$result = "<span class='badge badge-pill badge-secondary'>Terminal Side</span>";
					break;
				}
				case 5:
				{
					$result = "<span class='badge badge-pill badge-secondary'>Unit Mounted on Emboss Pocket</span>";
					break;
				}
				case 6:
				{
					$result = "<span class='badge badge-pill badge-secondary'>Wrap on Bubble Sheet</span>";
					break;
				}
				default:
				{
					$result = "<span class='badge badge-pill badge-secondary'>---</span>";
				}

			}
			return $result;
		})
		->addColumn('packop_po_num',function($packop_history)
		{
			switch($packop_history->radio1_3_1)
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
		->addColumn('packop_device_name',function($packop_history)
		{
			switch($packop_history->radio1_3_2)
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
		->addColumn('packop_qty_per_lot',function($packop_history)
		{
			switch($packop_history->radio1_3_3)
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
		->addColumn('packop_reel_lot_no',function($packop_history)
		{
			switch($packop_history->radio1_3_4)
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
		->addColumn('packop_total_num_reels',function($packop_history)
		{
			$result = $packop_history->total_num_reels;

			return $result;
		})
		->addColumn('packop_packing_code',function($packop_history)
		{
			$result = $packop_history->pack_code_no;

			return $result;
		})
		->addColumn('packop_orientation_of_units',function($packop_history)
		{
			switch($packop_history->radio1_5_1)
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
		->addColumn('packop_qty_per_box',function($packop_history)
		{
			switch($packop_history->radio1_5_2)
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
		->addColumn('packop_ul_sticker',function($packop_history)
		{
			switch($packop_history->radio1_5_3)
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
		->addColumn('packop_silica_gel',function($packop_history)
		{
			switch($packop_history->radio1_5_4)
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
		->addColumn('packop_accessories',function($packop_history)
		{
			switch($packop_history->radio1_5_5)
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
		->addColumn('packop_rohs_sticker',function($packop_history)
		{
			switch($packop_history->radio1_5_6)
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
		->rawColumns(['packop_po_num','packop_po_num','packop_device_name','packop_qty_per_lot','packop_reel_lot_no','packop_orientation_of_units','packop_qty_per_box','packop_ul_sticker','packop_silica_gel','packop_accessories','packop_rohs_sticker','packop_unit_condition','packop_packing_type','packop_lot_no','packop_judgement'])
		->make(true);
	}

	public function view_oqcvir_history_by_packing_code(Request $request)
	{
		$oqcvir_histories = PackingOperator::with(['oqcvir_details','user_details'])->where('po_num',$request->po_number)->where('pack_code_no',$request->packing_code)->orderBy('updated_at', 'DESC')->get();

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

	public function view_lotapp_history_by_packing_code(Request $request)
	{
		$lotapp_histories = PackingOperator::with(['lotapp_details','user_details'])->where('po_num',$request->po_number)->where('pack_code_no',$request->packing_code)->orderBy('batch','desc')->get();

		return DataTables::of($lotapp_histories)
		->addColumn('lotapp_lot_no',function($lotapp_history)
		{
			$result = "<span class='badge badge-pill badge-info'>".$lotapp_history->lotapp_details->lot_batch_no."</span>";

			return $result;
		})
		->addColumn('lotapp_applied_by',function($lotapp_history)
		{
			$result = "<span class='badge badge-pill badge-secondary'>".$lotapp_history->lotapp_details->user_details->name."</span>";
			
			return $result;
		})
		->addColumn('lotapp_submission',function($lotapp_history)
		{
			switch ($lotapp_history->lotapp_details->submission) 
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
		->addColumn('lotapp_datetime',function($lotapp_history)
		{
			$result = $lotapp_history->lotapp_details->app_date;
			$result .= " - ".$lotapp_history->lotapp_details->app_time;
			
			return $result;
		})
		->addColumn('lotapp_device_cat',function($lotapp_history)
		{
			 switch ($lotapp_history->lotapp_details->device_cat) {
                    case 1:
                        $result ='Automotive';
                        break;
                    case 2:
                        $result ='Non-Automotive';
                        break;
                }
			
			return $result;
		})
		->addColumn('lotapp_cert_lot',function($lotapp_history)
		{
			 switch ($lotapp_history->lotapp_details->cert_lot) {
                    case 1:
                        $result ='New Operator';
                        break;
                    case 2:
                        $result ='New product/model';
                        break;
                    case 3:
                        $result ='Evaluation lot';
                        break;
                    case 4:
                        $result ='Re-inspection';
                        break;
                    case 5:
                        $result ='Flexibility';
                        break;
                    case 6:
                        $result ='N/A';
                        break;
                }
			
			return $result;
		})
		->addColumn('lotapp_assy_line',function($lotapp_history)
		{
			$assy_line = AssemblyLine::where('id',$lotapp_history->lotapp_details->assy_line)->get();
            
            if( $assy_line ){
                $result = $assy_line[0]->name;
            }
            else
            {
            	$result = "---";
            }
			
			return $result;
		})
		->addColumn('lotapp_reel_lot',function($lotapp_history)
		{
			$result = $lotapp_history->lotapp_details->reel_lot;
			
			return $result;
		})
		->addColumn('lotapp_print_lot',function($lotapp_history)
		{
			$result = $lotapp_history->lotapp_details->print_lot;
			
			return $result;
		})
		->addColumn('lotapp_ttl_reel',function($lotapp_history)
		{
			$result = $lotapp_history->lotapp_details->ttl_reel;
			
			return $result;
		})

		->addColumn('lotapp_output_qty',function($lotapp_history)
		{
			$result = $lotapp_history->lotapp_details->output_qty;
			
			return $result;
		})
		->addColumn('lotapp_direction',function($lotapp_history)
		{
			$result = $lotapp_history->lotapp_details->direction;
			
			return $result;
		})
		->addColumn('lotapp_adrawing',function($lotapp_history)
		{
			$result = $lotapp_history->lotapp_details->Adrawing;
			
			return $result;
		})
		->addColumn('lotapp_gdrawing',function($lotapp_history)
		{
			$result = $lotapp_history->lotapp_details->Gdrawing;
			
			return $result;
		})
		->addColumn('lotapp_guaranteed_lot',function($lotapp_history)
		{
			switch($lotapp_history->lotapp_details->guaranteed_lot)
			{
				case 1:
				{
					$result ='<span class="badge badge-pill s1 badge-danger">WITH</span>';
				}
				case 2:
				{
					$result ='<span class="badge badge-pill s1 badge-secondary">WITHOUT</span>';
				}
			}
			
			return $result;
		})
		->addColumn('lotapp_problem',function($lotapp_history)
		{
			$result = $lotapp_history->lotapp_details->problem;
			
			return $result;
		})
		->addColumn('lotapp_doc_no',function($lotapp_history)
		{
			$result = $lotapp_history->lotapp_details->doc_no;
			
			return $result;
		})
		->addColumn('lotapp_remarks',function($lotapp_history)
		{
			$result = $lotapp_history->lotapp_details->remarks;
			
			return $result;
		})
		->addColumn('lotapp_prod_supervisor',function($lotapp_history)
		{
			$result = $lotapp_history->lotapp_details->prodn_supervisor;

			if($result)
			{
				$result2 = $result;
			}
			else
			{
				$result2 = "---";
			}
			
			return $result2;
		})
		->addColumn('lotapp_oqc_supervisor',function($lotapp_history)
		{
			$result = $lotapp_history->lotapp_details->oqc_supervisor;
			
			if($result)
			{
				$result2 = $result;
			}
			else
			{
				$result2 = "---";
			}

			return $result2;
		})
		->rawColumns(['lotapp_lot_no','lotapp_applied_by','lotapp_submission','lotapp_guaranteed_lot'])
		->make(true);
	}


	public function retrieve_c3_label_details(Request $request)
	{	
		$label_details = PackingOperator::with(['lotapp_details','user_details'])->where('po_num',$request->po_number)->where('pack_code_no',$request->packing_code)->orderBy('batch','desc')->get();

		return DataTables::of($label_details)
		->addColumn('customer',function($label_detail) use($request)
		{
				$device = Device::where('name',$request->manufacture)->first();

				$result = 'P';
				$result .= $device->huawei_p_n;

				return $result;
		})
		->addColumn('manufacture',function($label_detail) use($request)
		{
				$result = '1P';
				$result .= $request->manufacture;

				return $result;
		})
		->addColumn('quantity',function($label_detail)
		{
				$result = 'Q';
				$result .= $label_detail->lotapp_details->lot_qty;

				return $result;
		})
		->addColumn('lot_no',function($label_detail)
		{
				$result = '1T';
				$result .= $label_detail->lotapp_details->reel_lot;

				return $result;
		})
		->make(true);
	}

	public function retrieve_c3_label_checker(Request $request)
	{
		$label_details = PackingOperator::with(['lotapp_details','user_details'])->where('po_num',$request->po_number)->where('pack_code_no',$request->packing_code)->orderBy('batch','desc')->get();

		return DataTables::of($label_details)
		->addColumn('customer',function($label_detail) use($request)
		{
				$device = Device::where('name',$request->manufacture)->first();

				$result = $device->huawei_p_n;

				$result2 = '<input type="hidden" id="id_hidden_device_'.$label_detail->id.'"" name="name_hidden_device_'.$label_detail->id.'"  value="P'.$result.'" hidden-text-device-id='.$label_detail->id.' readonly>';

				$result2 .= '<input type="hidden" class="hidden_c3_check" id="id_status_device_'.$label_detail->id.'"  name="name_status_device_'.$label_detail->id.'" hidden-text-device-id='.$label_detail->id.' hidden-all-id='.$label_detail->id.' readonly>';

				$result2 .= "<input class='c_label_check_device' style='font-size:70%; margin: 0;' type='text' id='id_device_".$label_detail->id."'  name='name_device_".$label_detail->id."' text-device-id=".$label_detail->id.">";

				return $result2;
		})
		->addColumn('manufacture',function($label_detail) use($request)
		{
				$result = $request->manufacture;

				$result2 = '<input type="hidden" id="id_hidden_manufacture_'.$label_detail->id.'"  name="name_hidden_manufacture_'.$label_detail->id.'"  value="1P'.$result.'" hidden-text-manufacture-id='.$label_detail->id.' readonly>';

				$result2 .= '<input type="hidden" class="hidden_c3_check" id="id_status_manufacture_'.$label_detail->id.'"  name="name_status_manufacture_'.$label_detail->id.'" hidden-text-manufacture-id='.$label_detail->id.' hidden-all-id='.$label_detail->id.' readonly>';

				$result2 .= "<input class='c_label_check_manufacture' style='font-size:70%;' type='text' id='id_manufacture_".$label_detail->id."'  name='name_manufacture_".$label_detail->id."' text-manufacture-id=".$label_detail->id.">";

				return $result2;
		})
		->addColumn('quantity',function($label_detail)
		{
				$result = $label_detail->lotapp_details->lot_qty;

				$result2 = '<input type="hidden" id="id_hidden_quantity_'.$label_detail->id.'"  name="name_hidden_quantity_'.$label_detail->id.'"  value="Q'.$result.'"" hidden-text-quantity-id='.$label_detail->id.' readonly>';

				$result2 .= '<input type="hidden" class="hidden_c3_check" id="id_status_quantity_'.$label_detail->id.'"  name="name_status_quantity_'.$label_detail->id.'" hidden-text-quantity-id='.$label_detail->id.' hidden-all-id='.$label_detail->id.' readonly>';

				$result2 .= "<input class='c_label_check_quantity' style='font-size:70%;' type='text' id='id_quantity_".$label_detail->id."'  name='name_quantity_".$label_detail->id."' text-quantity-id=".$label_detail->id.">";

				return $result2;
		})
		->addColumn('lot_no',function($label_detail)
		{
				$result = $label_detail->lotapp_details->reel_lot;

				$result2 = '<input type="hidden" id="id_hidden_lot_no_'.$label_detail->id.'"  name="name_hidden_lot_no_'.$label_detail->id.'"  value="1T'.$result.'" hidden-text-lot-no-id='.$label_detail->id.' readonly>';

				$result2 .= '<input type="hidden" class="hidden_c3_check" id="id_status_lot_no_'.$label_detail->id.'"  name="name_status_lot_no_'.$label_detail->id.'" hidden-text-lot-no-id='.$label_detail->id.' hidden-all-id='.$label_detail->id.' readonly>';

				$result2 .= "<input class='c_label_check_lot_no' style='font-size:70%;' type='text' id='id_lot_no_".$label_detail->id."'  name='name_lot_no_".$label_detail->id."' text-lot-no-id=".$label_detail->id.">";

				return $result2;
		})
		->rawColumns(['customer','manufacture','quantity','lot_no'])
		->make(true);
	}

	public function submit_packin(Request $request)
	{
		date_default_timezone_set('Asia/Manila');

		$data = $request->all();

		$validator = Validator::make($data, [

		 		'name_c3_label_check' => ['required', 'max:255'],
		 		'name_check_pack_condition' => ['required', 'max:255'],
		 		'name_require_accessories' => ['required', 'max:255'],
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
							'po_num' => $request->name_hidden_ponum,
							'check_c3'	=> $request->name_c3_label_check,
							'pac_man_doc_comp' => $request->name_check_pack_condition,
							'accessories' => $request->name_require_accessories,
							'pack_code_no' => $request->name_packin_pack_code_no,
							'emp_id' => $user[0]->id,
							'oqc_judgement' => $request->name_packin_judgement,
							'created_at' => date('Y-m-d H:i:s'),
				            'updated_at' => date('Y-m-d H:i:s')
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
