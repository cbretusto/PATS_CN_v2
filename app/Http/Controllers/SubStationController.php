<?php

namespace App\Http\Controllers;

use App\Model\Station;
use App\Model\SubStation;
use App\Model\StationSubStation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DataTables;
use Auth;
use QrCode;

class SubStationController extends Controller
{
    //
    public function view_sub_stations(Request $request){
    	$sub_stations = SubStation::all();

        return DataTables::of($sub_stations)
            ->addColumn('label1', function($sub_station){
                $result = "";

                if($sub_station->status == 1){
                    $result .= '<span class="badge badge-pill badge-success">Active</span>';
                }
                else{
                    $result .= '<span class="badge badge-pill badge-danger">Inactive</span>';
                }

                return $result;
            })
            ->addColumn('action1', function($sub_station){
                $result = '<center><div class="btn-group">
                          <button type="button" class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Action">
                            <i class="fa fa-cog"></i> 
                          </button>
                          <div class="dropdown-menu dropdown-menu-right">';
                if($sub_station->status == 1){
                	$result .= '<button class="dropdown-item aEditSubStation" type="button" sub-station-id="' . $sub_station->id . '" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalEditSubStation" data-keyboard="false">Edit</button>';

                    $result .= '<button class="dropdown-item aChangeSubStationStat" type="button" sub-station-id="' . $sub_station->id . '" status="2" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalChangeSubStationStat" data-keyboard="false">Deactivate</button>';

                    // $result .= '<button class="dropdown-item aGenSubStationBarcode" sub-station-id="' . $sub_station->barcode . '" type="button" style="padding: 1px 1px; text-align: center;">Generate Barcode</button>';

                    $result .= '<button class="dropdown-item aGenerateBarcode" sub-station-id="' . $sub_station->id . '" barcode="' . $sub_station->barcode . '" title="Sub Station: " name="' . $sub_station->name . '" type="button" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalGenBarcode">Generate Barcode</button>';
                }
                else{
                    $result .= '<button class="dropdown-item aChangeSubStationStat" type="button" style="padding: 1px 1px; text-align: center;" sub-station-id="' . $sub_station->id . '" status="1" data-toggle="modal" data-target="#modalChangeSubStationStat" data-keyboard="false">Activate</button>';
                }
                            
                $result .= '</div>
                        </div></center>';

                return $result;
            })
            ->rawColumns(['label1', 'action1'])
            ->make(true);
    }

    // Add Sub Station
    public function add_sub_station(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255', 'unique:sub_stations'],
            'barcode' => ['required', 'string', 'max:255', 'unique:sub_stations'],
            // 'station_id' => ['required', 'numeric', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();

            try{
                SubStation::insert([
                    'name' => $request->name,
                    'status' => 1,
                    'barcode' => $request->barcode,
                    'created_by' => Auth::user()->id,
                    'last_updated_by' => Auth::user()->id,
                    'update_version' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                DB::commit();

                return response()->json(['result' => "1"]);
            }
            catch(\Exception $e) {
                DB::rollback();
                // throw $e;
                return response()->json(['result' => "0"]);
            }
        }
    }

    // Get Sub Station By Id
    public function get_sub_station_by_id(Request $request){
        $sub_station = SubStation::where('id', $request->sub_station_id)->get();
        return response()->json(['sub_station' => $sub_station]);
    }

    // Edit Sub Station
    public function edit_sub_station(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255|unique:sub_stations,name,'. $request->sub_station_id,
            'barcode' => 'required|string|max:255|unique:sub_stations,barcode,'. $request->sub_station_id,
            // 'station_id' => 'required|numeric|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();

            try{
                SubStation::where('id', $request->sub_station_id)
                ->increment('update_version', 1,
                [
                    'name' => $request->name,
                    'barcode' => $request->barcode,
                    // 'station_id' => $request->station_id,
                    'last_updated_by' => Auth::user()->id,
                    'update_version' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                DB::commit();

                return response()->json(['result' => "1"]);
            }
            catch(\Exception $e) {
                DB::rollback();
                // throw $e;
                return response()->json(['result' => "0"]);
            }
        }
    }

    // Change Sub Station Status
    public function change_sub_station_stat(Request $request){        
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'sub_station_id' => 'required',
            'status' => 'required',
        ]);

        if($validator->passes()){
            try{
                SubStation::where('id', $request->sub_station_id)
                    ->increment('update_version', 1, 
                        [
                            'status' => $request->status,
                            'last_updated_by' => Auth::user()->id,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]
                    );
                DB::commit();
                return response()->json(['result' => "1"]);
            }
            catch(\Exception $e) {
                DB::rollback();
                // throw $e;
                return response()->json(['result' => "0"]);
            }  
            
            return response()->json(['result' => 1]);
        }
        else{
            return response()->json(['result' => "0", 'error' => $validator->messages()]);
        }
    }

    // public function get_sub_stations_by_stat(Request $request){
    // 	$status = $request->status;
    // 	$sub_stations = Station::with([
				// 			'sub_stations' => function($query) use ($status){
				// 				$query->where('status', $status);
				// 			}
				// 		])
    // 				->where('status', 1)
    // 				->get();

    // 	return response()->json(['sub_stations' => $sub_stations]);
    // }

    public function get_sub_stations_by_stat(Request $request){
        if($request->status == 0 || !isset($request->status)){
            $sub_stations = SubStation::all();
        }
        else{
            $sub_stations = SubStation::where('status', $request->status)
                        ->get();
        }

        return response()->json(['sub_stations' => $sub_stations]);
    }

    public function generate_sub_station_qrcode(Request $request){
        $sub_station = [];
        if($request->action == "1"){
            $sub_station = SubStation::where('barcode', $request->qrcode)->get();
        }
        else if($request->action == "2"){
            $sub_station = SubStation::where('barcode', $request->qrcode)
                        ->where('id', '!=', $request->sub_station_id)
                        ->get();
        }
        $qrcode = "0";

        if(count($sub_station) <= 0){
            try{
                if(isset($request->qrcode)){
                    $qrcode = QrCode::format('png')
                            ->size(200)->errorCorrection('H')
                            ->generate($request->qrcode);

                    return response()->json(['result' => "1", 'qrcode' => "data:image/png;base64," . base64_encode($qrcode)]);
                }
                else{
                    return response()->json(['result' => "0"]);
                }
            }
            catch(\Exception $e){
                return response()->json(['result' => "0"]);
            }
        }
        else{
            return response()->json(['result' => "2"]);
        }
    }
}
