<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\PackingOperator;
use App\Model\PackingOperator2;
use App\Model\PackingInspector;
use App\Model\oqcLotApp;
use App\Model\oqcVIR;
use App\Model\RapidPackingCode;
use App\Model\Device;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use DataTables;

class PackingOperatorController extends Controller
{
	public function view_batches(Request $request)
	{
		$packing_records = PackingOperator2::where('po_num',$request->po_number)->get();

		return DataTables::of($packing_records)
		->addColumn('action', function($packing_record)  use($request)
		{
			return 1;
		})
		->addColumn('packing_code', function($packing_record) use($request)
		{
			return 1;
		})
		->addColumn('box_qty', function($packing_record) use($request)
		{
			return 1;
		})
		->addColumn('status', function($packing_record) use($request)
		{
			return 1;
		})
		->make(true);
	}

	public function packop_view_reel_lot_no(Request $request)
	{
		$AvailableReels = oqcLotApp::where('po_no', $request->po_number)->get();
		
		return DataTables::of($AvailableReels)
		->addColumn('action', function($available_reel){

			$result = '<input type="checkbox" class="chkReelLot" id='. $available_reel->id .' value='. $available_reel->id .' reel-id='.$available_reel->id.'>';

			return $result;
		})
		->addColumn('reel_lot', function($available_reel){

			$result = '1T';
			$result .= $available_reel->reel_lot;

			return $result;
		})
		->addColumn('customer', function($available_reel) use($request){

			$device = Device::where('name',$request->device)->first();

				$result = 'P';
				$result .= $device->huawei_p_n;

			return $result;
		})
		->addColumn('manufacture', function($available_reel) use($request){

			$result = '1P';
			$result .= $request->device;

			return $result;
		})
		->addColumn('quantity', function($available_reel){

			$result = 'Q';
			$result .= $available_reel->output_qty;

			return $result;
		})
		->rawColumns(['action'])
		->make(true);

	}

	public function get_reel_lot_count(Request $request)
	{
		$result = 1;

		$devices = Device::where('name', $request->device_name)->first();

		$box_lot_count = $devices->ship_boxing / $devices->boxing;

		if($request->array_length != 0)
		{
			if($request->array_length != $box_lot_count)
			{
				$result =  1;
			}
			else
			{
				$result =  2;
			}
		}
		else
		{
			$result = 0;
		}

		return response()->json(['result' => $result]);
	}

	public function generate_packing_code(Request $request)
	{
		$series_name = '';

		$series_name = explode("-", $request->device_name)[0];

		$pack_code = RapidPackingCode::where('cnpts_series_name','like',$series_name. '%')->get();

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
