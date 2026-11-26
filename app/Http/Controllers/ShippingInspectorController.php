<?php

namespace App\Http\Controllers;

use App\User;
use DataTables;
use App\Model\Device;
use App\Model\oqcLotApp;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exports\ReportExport;
use App\Model\PackingOperator;
use App\Model\PackingInspector;


use App\Model\RapidPackingCode;



use App\Model\ShippingOperator;
use App\Model\ShippingInspector;
use App\Model\OQCPrelimInspection;
use Illuminate\Support\Facades\DB;
use App\Model\OQCPackingInspection;
use App\Model\ProdPrelimInspection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PackingAndShipmentExport;

use Illuminate\Support\Facades\Validator;
use App\Exports\PackingAndShipmentExportTest;

class ShippingInspectorController extends Controller
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
									$shipin = ShippingInspector::where('po_num',$request->po_number)->where('pack_code_no',$packing_code->pack_code_no)->distinct('pack_code_no')->orderBy('updated_at','desc')->first();


									if($shipin != null)
									{	
										switch($shipin->oqc_judgement)
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
										break;
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



			$result = '<button type="button" class="px-2 py-1 btn btn-sm btn-info btn_update_shipping_inspector" data-toggle="modal" data-target="#modalShippingInspector" title="Update Details" po-num='.$packcode_data->po_num.' packing-code='.$packcode_data->pack_code_no.' '.$disabled.'><i class="fa fa-edit fa-sm"></i></button>';

			$result .= ' <button type="button" class="px-2 py-1 btn btn-sm btn-success btn_open_shipin_history" data-toggle="modal" data-target="#modalShippingInspectorHistory" title="View Shipping QC Inspection History" packing-code='.$packcode_data->pack_code_no.' po-num='.$packcode_data->po_num.' '.$disabled2.'><i class="fa fa-history fa-sm"></i></button>';

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


									$shipin = ShippingInspector::where('po_num',$request->po_number)->where('pack_code_no',$packing_code->pack_code_no)->distinct('pack_code_no')->orderBy('updated_at','desc')->first();


									if($shipin != null)
									{
										switch($shipin->oqc_judgement)
										{
										
											case 1:
											{
												$result .= "<br><span class='badge badge-pill badge-success'>Shipping QC Inspection Accept</span>";
												break;
											}
											case 2:
											{
												$result .= "<br><span class='badge badge-pill badge-danger'>Shipping QC Inspection Reject</span>";
												break;
											}
										}
									}
									else
									{
										$result .= "<br><span class='badge badge-pill badge-info'>For Shipping QC Inspection</span>";
										break;
									}

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
							$result .= "<br><span class='badge badge-pill badge-warning'>For Shipment</span>";
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

	public function view_shipin_history_by_packing_code(Request $request)
	{
		$shipin_histories = ShippingInspector::with(['user_details'])->where('po_num',$request->po_number)->where('pack_code_no',$request->packing_code)->orderBy('updated_at', 'DESC')->get();

		return Datatables::of($shipin_histories)
		->addColumn('shipin_judgement',function($shipin_history)
		{
			$result = $shipin_history->user_details->name;
			$result .= " - ";

			if($shipin_history->oqc_judgement == 1)
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
		->addColumn('shipin_pack_code',function($shipin_history)
		{
			$result = $shipin_history->pack_code_no;
			
			return $result;
		})
		->addColumn('shipin_datetime',function($shipin_history)
		{
			$result = $shipin_history->updated_at;
			
			return $result;
		})
		->addColumn('shipin_rohs',function($shipin_history)
		{
			switch($shipin_history->radio4_1)
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
		->addColumn('shipin_check_ponum',function($shipin_history)
		{
			switch($shipin_history->radio4_2_1)
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
		->addColumn('shipin_check_device',function($shipin_history)
		{
			switch($shipin_history->radio4_2_2)
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
		->addColumn('shipin_check_drawing',function($shipin_history)
		{
			switch($shipin_history->radio4_2_3)
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
		->addColumn('shipin_check_urp',function($shipin_history)
		{
			switch($shipin_history->radio4_2_4)
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
		->addColumn('shipin_dlabel_qty',function($shipin_history)
		{
			$result = $shipin_history->input4_3_1;
			
			return $result;
		})
		->addColumn('shipin_lotapp_qty',function($shipin_history)
		{
			$result = $shipin_history->input4_3_2;
			
			return $result;
		})
		->addColumn('shipin_urp_qty',function($shipin_history)
		{
			$result = $shipin_history->input4_3_3;
			
			return $result;
		})
		->addColumn('shipin_coc',function($shipin_history)
		{
			switch($shipin_history->radio4_4)
			{
				case 1:
				{
					$result = "<span class='badge badge-pill badge-success'>Yes</span>";
					break;
				}
				case 2:
				{
					$result = "<span class='badge badge-pill badge-secondary'>No</span>";
					break;
				}
				case 3:
				{
					$result = "<span class='badge badge-pill badge-secondary'>N/A</span>";
					break;
				}
				default:
				{
					$result = "<span class='badge badge-pill badge-info'>---</span>";
				}
			}
			return $result;
		})
		->addColumn('shipin_ponum',function($shipin_history)
		{
			switch($shipin_history->input4_5_1)
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
		->addColumn('shipin_device',function($shipin_history)
		{
			switch($shipin_history->input4_5_2)
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
		->addColumn('shipin_qty',function($shipin_history)
		{
			switch($shipin_history->input4_5_3)
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
		->addColumn('shipin_destination',function($shipin_history)
		{
			switch($shipin_history->input4_5_4)
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
		->addColumn('shipin_carton',function($shipin_history)
		{
			switch($shipin_history->input4_5_5)
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
		->addColumn('shipin_transaction',function($shipin_history)
		{
			switch($shipin_history->input4_5_6)
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
		->addColumn('shipin_plcn',function($shipin_history)
		{
			$result = $shipin_history->input4_6_1;
			
			return $result;
		})
		->addColumn('shipin_tsq',function($shipin_history)
		{
			$result = $shipin_history->input4_6_2;
			
			return $result;
		})
		->addColumn('shipin_tbq',function($shipin_history)
		{
			$result = $shipin_history->input4_6_3;
			
			return $result;
		})
		->addColumn('shipin_oqc_correspondence',function($shipin_history)
		{
			switch($shipin_history->input4_5_6)
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
		->rawColumns(['shipin_judgement','shipin_rohs','shipin_check_ponum','shipin_check_device','shipin_check_drawing','shipin_check_urp','shipin_coc','shipin_ponum','shipin_device','shipin_qty','shipin_destination','shipin_carton','shipin_transaction','shipin_oqc_correspondence'])
		->make(true);
	}


	public function view_shipop_history_by_packing_code(Request $request)
	{
		$shipop_histories = ShippingOperator::with(['user_details'])->where('po_num',$request->po_number)->where('pack_code_no',$request->packing_code)->orderBy('updated_at', 'DESC')->get();

		return DataTables::of($shipop_histories)
		->addColumn('shipop_judgement',function($shipop_history)
		{
			$result = $shipop_history->user_details->name;
			$result .= " - ";

			if($shipop_history->oqc_judgement == 1)
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
		->addColumn('shipop_pack_code',function($shipop_history)
		{
			$result = $shipop_history->pack_code_no;
			
			return $result;
		})
		->addColumn('shipop_datetime',function($shipop_history)
		{
			$result = $shipop_history->updated_at;
			
			return $result;
		})
		->addColumn('shipop_ponum',function($shipop_history)
		{
			switch($shipop_history->radio3_1_1)
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
		->addColumn('shipop_device',function($shipop_history)
		{
			switch($shipop_history->radio3_1_2)
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
		->addColumn('shipop_drawing',function($shipop_history)
		{
			switch($shipop_history->radio3_1_3)
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
		->addColumn('shipop_urp',function($shipop_history)
		{
			switch($shipop_history->radio3_1_4)
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
		->addColumn('shipop_dlabel_qty',function($shipop_history)
		{
			$result = $shipop_history->radio3_2_1;
			
			return $result;
		})
		->addColumn('shipop_lotapp_qty',function($shipop_history)
		{
			$result = $shipop_history->radio3_2_2;
			
			return $result;
		})
		->addColumn('shipop_urp_qty',function($shipop_history)
		{
			$result = $shipop_history->radio3_2_3;
						
			return $result;
		})
		->addColumn('shipop_plcn',function($shipop_history)
		{
			$result = $shipop_history->pack_list_con_no;
			
			return $result;
		})
		->addColumn('shipop_tsq',function($shipop_history)
		{
			$result = $shipop_history->total_shipment_qty;
			
			return $result;
		})
		->addColumn('shipop_tbq',function($shipop_history)
		{
			$result = $shipop_history->total_box_qty;
			
			return $result;
		})
		->rawColumns(['shipop_judgement','shipop_ponum','shipop_device','shipop_drawing','shipop_urp'])
		->make(true);

	}

		
	public function submit_shipin(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [

        ]);

        if ($validator->fails()) {
            return response()->json(['result' => 0, 'error' => $validator->messages()]);
        }
        else{

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

            try{
            	ShippingInspector::insert([

            		'shipment_date' => $request->name_shipment_date,
            		'shipment_destination' => $request->name_shipment_destination,
            		'shipment_remarks' => $request->name_shipment_remarks,

            		'po_num' => $request->name_hidden_ponum,
            		'pack_code_no' => $request->name_modal_packing_code,

            		'radio4_1' => $request->name_41,

            		'radio4_2_1' => $request->name_421,
            		'radio4_2_2' => $request->name_422,
            		'radio4_2_3' => $request->name_423,
            		'radio4_2_4' => $request->name_424,

            		'input4_3_1' => $request->name_431,
            		'input4_3_2' => $request->name_432,
            		'input4_3_3' => $request->name_433,

            		'radio4_4' => $request->name_44,

            		'input4_5_1' => $request->name_451,
            		'input4_5_2' => $request->name_452,
            		'input4_5_3' => $request->name_453,
            		'input4_5_4' => $request->name_454,
            		'input4_5_5' => $request->name_455,
            		'input4_5_6' => $request->name_456,

            		'input4_6_1' => $request->name_461,
            		'input4_6_2' => $request->name_462,
            		'input4_6_3' => $request->name_463,

            		'radio4_7' => $request->name_47,

					'emp_id' => $user[0]->id,
					'oqc_judgement' => $request->name_shipin_judgement,
                    
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
            	]);

            	DB::commit();
                
                return response()->json(['result' => 1]);
            }
            catch(\Exception $e) {
                DB::rollback();
                // throw $e;
                return response()->json(['result' => $e]);
            }
        }
        else
        {
        	return response()->json(['result' => 2]);
        }
        }
    }

        // Get Station By Status
    public function search_device_to_export_report(Request $request){
        $devices = Device::where('status', $request->status)->get();
        
        return response()->json(['devices' => $devices]);
    }

    //EXPORT EXCEL
    public function export_packing_and_shipment_record(Request $request)
    {
    	$device_name = $request->series_name;
		
		// return $device_name;
    	$myStartDate = $request->start_date;
    	$myStartDate .= " 00:00:00";

    	$myEndDate = $request->end_date;
    	$myEndDate .= " 23:59:59";

    	/*$shipping_records = ShippingInspector::with(['user_details','packop_details','packin_details','shipop_details','packop_details.lotapp_details'])->whereHas('packop_details',function($x) use($myStartDate,$myEndDate,$request)
    		{
    			$x->whereBetween('updated_at',[$myStartDate,$myEndDate])->where('device_name',$request->series_name);
    		})->get();
*/
    	$col_shipping_records = OQCPackingInspection::with([
    		'employee_id',
    		'prod_prelim_details',
    		'prod_prelim_details.user_details',
    		'oqcvir_details',
 			'oqcvir_details.oqclotapp_details' => function($query) use ($request)
 			{
 				if(isset($request->reel_lot) && isset($request->print_lot))
 				{
 					$query->where('reel_lot','LIKE', '%' . $request->reel_lot . '%')->orWhere('print_lot','LIKE', '%' . $request->print_lot . '%');
 				}

 				else
 				{
 					if(isset($request->print_lot))
 					{
 						$query->where('print_lot','LIKE', '%' . $request->print_lot . '%');
 					}

	 				if(isset($request->reel_lot))
	 				{
	 						$query->where('reel_lot','LIKE', '%' . $request->reel_lot . '%');
	 				}
 				}
 			},

 			'oqcvir_details.oqclotapp_details.wbs_kitting' => function($query2) use ($device_name)
 			{
 				$query2->where('device_name',$device_name);
 			}

    	])
		->whereHas('prod_prelim_details', function($x) use ($myStartDate,$myEndDate,$device_name)	
    		{
    			$x->whereBetween('updated_at',[$myStartDate,$myEndDate]);
    			$x->where('device_name',$device_name);
    		})
		->get();


		// return $col_shipping_records;

    	$final_data = [];

    	$shipping_records = collect($col_shipping_records);

    	// return $shipping_records;

    	$arrayShippingRecords = [];

    	for($i = 0; $i < count($shipping_records); $i++)
    	{	
    		if($shipping_records[$i]->oqcvir_details != null)
    		{
    			for($x = 0; $x < count($shipping_records[$i]->oqcvir_details); $x++)
	    		{
	    			if($shipping_records[$i]->oqcvir_details[$x]->oqclotapp_details != null && $shipping_records[$i]->oqcvir_details[$x]->oqclotapp_details->po_no == $shipping_records[$i]->po_num && $shipping_records[$i]->oqcvir_details[$x]->oqclotapp_details->wbs_kitting != null)
	    			{
	    				$arrayShippingRecords[] = $shipping_records[$i]->id;
	    			}
	    		}
    		}
    	}

        $shipping_records = $col_shipping_records->whereIn('id',$arrayShippingRecords)->flatten(1);

        // return $shipping_records;

      /*  $shipping_records = OQCPackingInspection::with(['employee_id',
    		'prod_prelim_details',
    		'prod_prelim_details.user_details',
    		'oqcvir_details',
 			'oqcvir_details.oqclotapp_details',
 			'oqcvir_details.oqclotapp_details.wbs_kitting'])->whereIn('id',$arrayShippingRecords)->get();*/

 			// return $shipping_records;

    	if(count($shipping_records) > 0)
    	{
    		// return view('exports.packingandshipment')->with(compact('shipping_records','device_name'));

    		// return $col_shipping_records;

	        return Excel::download(new PackingAndShipmentExport($shipping_records, $device_name), $request->series_name . ' - Shipment Record (PATS generated).xlsx');
    	}
    	else
    	{
     		echo "<script>";
			echo "alert('No data found for " . $device_name . " from " . $request->start_date . " to " . $request->end_date."');";
			echo "window.close();";
			echo "</script>";
    	}
    	
    }




    //SEEDER INSERT FUNCTION
    public function submit_seeder(Request $request)
    {
    	date_default_timezone_set('Asia/Manila');

		$data = $request->all();

		$inspector = User::where('employee_id', $request->name_inspector_id)->get();
		$operator = User::where('employee_id', $request->name_operator_id)->get();

		DB::beginTransaction();

		try
		{
			ProdPrelimInspection::insert([

	        			'po_num' => $request->name_po_no,
	        			'device_name' => $request->name_device_name,
	        			'pack_code_no' => $request->name_packing_code,
	        			'packing_type' => 5,
	        			'packing_unit_condition' => 5,
	        			'orientation_of_units' => 1,
	        			'qty_per_box_tray' => 1,
	        			'ul_sticker' => 3,
	        			'silica_gel' => 3,
	        			'accessories'=> 3,
	        			'rohs_sticker'=> 3,
	        			'emp_id' => $operator[0]->id,
	        			'operator_judgement' => 1,
	        			'created_at' => $request->name_packing_day,
	        			'updated_at' => $request->name_packing_day
	        		]);

			OQCPackingInspection::insert([

	        			'po_num' => $request->name_po_no,
	        			'pack_code_no' => $request->name_packing_code,
	        			'document_compliance' => 1,
	        			'accessory_requirement' => 1,
	        			'coc_requirement' => 1,
	        			'emp_id' => $inspector[0]->employee_id,
	        			'status' => 4,
	        			'created_at' => date('Y-m-d H:i:s'),
	        			'updated_at' => date('Y-m-d H:i:s'),
	        			'shipping_date' => $request->name_shipment_day,
	        			'shipping_destination' => $request->name_shipment_destination,
	        			'shipping_remarks' => $request->name_shipment_remarks
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

	public function export_packing_and_shipment_record_test(Request $request){
		$device_name = $request->series_name;
   	
    	$myStartDate = $request->start_date;
    	$myStartDate .= " 00:00:00";

    	$myEndDate = $request->end_date;
    	$myEndDate .= " 23:59:59";

    	$col_shipping_records = OQCPackingInspection::with([
    		'employee_id',
    		'prod_prelim_details',
    		'prod_prelim_details.user_details',
    		'oqcvir_details',
 			'oqcvir_details.oqclotapp_details' => function($query) use ($request)
 			{
 				if(isset($request->reel_lot) && isset($request->print_lot))
 				{
 					$query->where('reel_lot','LIKE', '%' . $request->reel_lot . '%')->orWhere('print_lot','LIKE', '%' . $request->print_lot . '%');
 				}

 				else
 				{
 					if(isset($request->print_lot))
 					{
 						$query->where('print_lot','LIKE', '%' . $request->print_lot . '%');
 					}

	 				if(isset($request->reel_lot))
	 				{
	 						$query->where('reel_lot','LIKE', '%' . $request->reel_lot . '%');
	 				}
 				}
 			},

 			'oqcvir_details.oqclotapp_details.wbs_kitting' => function($query2) use ($device_name)
 			{
 				$query2->where('device_name',$device_name);
 			}

    	])->whereHas('prod_prelim_details',function($x) use($myStartDate,$myEndDate,$device_name)	
    		{
    			$x->whereBetween('updated_at',[$myStartDate,$myEndDate]);
    			$x->where('device_name','LIKE', "%" . $device_name . "%");
    		})
		->get();

		// return $col_shipping_records;

    	$final_data = [];

    	$shipping_records = collect($col_shipping_records);

    	$arrayShippingRecords = [];

    	for($i = 0; $i < count($shipping_records); $i++)
    	{	
    		if($shipping_records[$i]->oqcvir_details != null)
    		{
    			for($x = 0; $x < count($shipping_records[$i]->oqcvir_details); $x++)
	    		{
	    			if($shipping_records[$i]->oqcvir_details[$x]->oqclotapp_details != null && $shipping_records[$i]->oqcvir_details[$x]->oqclotapp_details->po_no == $shipping_records[$i]->po_num && $shipping_records[$i]->oqcvir_details[$x]->oqclotapp_details->wbs_kitting != null)
	    			{
	    				$arrayShippingRecords[] = $shipping_records[$i]->id;
	    			}
	    		}
    		}
    	}

		// return $arrayShippingRecords;
        $shipping_records = $col_shipping_records->whereIn('id',$arrayShippingRecords)->flatten(1);

		// return $shipping_records;

    	if(count($shipping_records) > 0)
    	{
    		// return view('exports.packingandshipment')->with(compact('shipping_records','device_name'));

    		// return $col_shipping_records;

	        return Excel::download(new PackingAndShipmentExportTest($shipping_records, $device_name), $request->series_name . ' - Shipment Record (PATS generated).xlsx');
    	}
    	else
    	{
     		echo "<script>";
			echo "alert('No data found for " . $device_name . " from " . $request->start_date . " to " . $request->end_date."');";
			echo "window.close();";
			echo "</script>";
    	}
	}
}
