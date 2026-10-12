<?php

namespace App\Http\Controllers;

use App\Model\Device;
use App\Model\MaterialProcess;
use Illuminate\Http\Request;
use App\Model\MaterialIssuance;
use App\Model\MaterialIssuanceSubSystem;
use App\Model\WBSKitIssuance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Auth;

use DataTables;

class PartsPreparatoryController2 extends Controller
{
	public function fn_view_parts_preparatory_page(){
		return view('partspreparatory');
	}


	public function fn_view_materials(Request $request){
		// $lot_numbers = MaterialIssuanceSubSystem::all();
		$lot_numbers = WBSKitIssuance::where('po','=',$request['po_number'])
			->get();
			// ->where('issue_no','=',$request['issue_no'])->get();

		// $lot_numbers = WBSKitIssuance::all();
        return DataTables::of($lot_numbers)
            ->addColumn('raw_action', function($lot_numbers){
            	$disabled = '';
                $result='<button '.$disabled.' style="width:30px;" title="Edit" class="btn_material_action btn_edit_material btn btn-info btn-sm py-0"><i class="fa fa-edit"></i></button>';
                return $result;
            })
            ->addColumn('raw_assess_no', function($lot_numbers){
                $result = "---";
                return $result;
            })
            ->addColumn('raw_use_for_device_name', function($lot_numbers){
                $result = "---";
                return $result;
            })
            ->addColumn('raw_assess_qty', function($lot_numbers){
                $result = "---";
                return $result;
            })
            ->addColumn('raw_x_drawing', function($lot_numbers){
                $result = "---";
                return $result;
            })
            ->addColumn('raw_other_docs', function($lot_numbers){
                $result = "---";
                return $result;
            })
            ->rawColumns(['raw_action'])
            ->make(true);
	}

	public function fn_view_stations(Request $request){
        $material_process = MaterialProcess::with(['sub_station', 'sub_station.station'])->get();

        return DataTables::of($material_process)
            ->addColumn('label1', function($material_process){
                $result = "";

                if($material_process->status == 1){
                    $result .= '<span class="badge badge-pill badge-success">Active</span>';
                }
                else{
                    $result .= '<span class="badge badge-pill badge-danger">Inactive</span>';
                }

                return $result;
            })
            ->addColumn('action1', function($material_process){
                $result = '<div class="btn-group">
                          <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Action">
                            <i class="fa fa-cog"></i> 
                          </button>
                          <div class="dropdown-menu dropdown-menu-right">';
                if($material_process->status == 1){
                    $result .= '<button class="dropdown-item aEditMatProc" type="button" sub-station-id="' . $material_process->id . '" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalEditMatProc" data-keyboard="false">Edit</button>';

                    $result .= '<button class="dropdown-item aChangeMatProcStat" type="button" sub-station-id="' . $material_process->id . '" status="2" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalChangeMatProcStat" data-keyboard="false">Deactivate</button>';
                }
                else{
                    $result .= '<button class="dropdown-item aChangeMatProcStat" type="button" style="padding: 1px 1px; text-align: center;" sub-station-id="' . $material_process->id . '" status="1" data-toggle="modal" data-target="#modalChangeMatProcStat" data-keyboard="false">Activate</button>';
                }
                            
                $result .= '</div>
                        </div>';

                return $result;
            })
            ->rawColumns(['label1', 'action1'])
            ->make(true);

		// $lot_numbers = MaterialIssuanceSubSystem::all();
    	// $stations = MaterialProcess::with(['sub_station', 'sub_station.station'])->get();
    	// $stations = MaterialProcess::with(['sub_station', 'sub_station.station'])->where('device_id', $request->device_id)->get();
		// $lot_numbers = WBSKitIssuance::where('po','=',$request['po_number'])
			// ->get();
			// ->where('issue_no','=',$request['issue_no'])->get();

		// $lot_numbers = WBSKitIssuance::all();
        // return DataTables::of($stations)
        //     ->addColumn('raw_action', function($station){
        //     	$disabled = '';
        //         $result='<button '.$disabled.' style="width:30px;" title="Edit" class="btn_material_action btn_edit_material btn btn-info btn-sm py-0"><i class="fa fa-edit"></i></button>';
        //         return $result;
        //     })
        //     ->addColumn('raw_status', function($station){
        //         $result = "---";
        //         return $result;
        //     })
        //     ->addColumn('raw_date', function($station){
        //         $result = "---";
        //         return $result;
        //     })
        //     ->addColumn('raw_operator', function($station){
        //         $result = "---";
        //         return $result;
        //     })
        //     ->addColumn('raw_input', function($station){
        //         $result = "---";
        //         return $result;
        //     })
        //     ->addColumn('raw_output', function($station){
        //         $result = "---";
        //         return $result;
        //     })
        //     ->addColumn('raw_ng_qty', function($station){
        //         $result = "---";
        //         return $result;
        //     })
        //     ->addColumn('raw_mod', function($station){
        //         $result = "---";
        //         return $result;
        //     })
        //     ->rawColumns(['raw_action'])
        //     ->make(true);
	}

}
