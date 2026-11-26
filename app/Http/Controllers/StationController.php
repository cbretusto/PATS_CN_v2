<?php

namespace App\Http\Controllers;

use App\Model\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DataTables;
use Auth;

class StationController extends Controller
{
    //View Stations
	public function view_stations(){
    	$stations = Station::all();

        return DataTables::of($stations)
            ->addColumn('label1', function($station){
                $result = "";

                if($station->status == 1){
                    $result .= '<span class="badge badge-pill badge-success">Active</span>';
                }
                else{
                    $result .= '<span class="badge badge-pill badge-danger">Inactive</span>';
                }

                return $result;
            })
            ->addColumn('action1', function($station){
                $result = '<center><div class="btn-group">
                          <button type="button" class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Action">
                            <i class="fa fa-cog"></i> 
                          </button>
                          <div class="dropdown-menu dropdown-menu-right">';
                if($station->status == 1){
                	$result .= '<button class="dropdown-item aEditStation" type="button" station-id="' . $station->id . '" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalEditStation" data-keyboard="false">Edit</button>';

                    $result .= '<button class="dropdown-item aChangeStationStat" type="button" station-id="' . $station->id . '" status="2" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalChangeStationStat" data-keyboard="false">Deactivate</button>';

                    // $result .= '<button class="dropdown-item aShowStationSubStations" station-id="' . $station->id . '" station-name="' . $station->name . '" type="button" style="padding: 1px 1px; text-align: center;">Show Sub Stations</button>';

                    $result .= '<button class="dropdown-item aGenerateBarcode" station-id="' . $station->id . '" barcode="' . $station->barcode . '" title="Station: " name="' . $station->name . '" type="button" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalGenBarcode">Generate Barcode</button>';
                }
                else{
                    $result .= '<button class="dropdown-item aChangeStationStat" type="button" style="padding: 1px 1px; text-align: center;" station-id="' . $station->id . '" status="1" data-toggle="modal" data-target="#modalChangeStationStat" data-keyboard="false">Activate</button>';
                }
                            
                $result .= '</div>
                        </div></center>';

                return $result;
            })
             ->addColumn('station_type_label', function($station){
                $station_type = '';

                if($station->station_type == 1){
                    $station_type = 'Parts Preparatory';
                }
                else if($station->station_type == 2){
                    $station_type = 'Production Line';
                }
                else if($station->station_type == 3){
                    $station_type = 'OQC';
                }
                else if($station->station_type == 4){
                    $station_type = 'Packing';
                }
                else if($station->station_type == 5){
                    $station_type = 'Shipping';
                }
                else if($station->station_type == 6){
                    $station_type = 'Pre-Assembly';
                }

                return $station_type;
            })
            ->rawColumns(['label1', 'action1'])
            ->make(true);
    }

    public function get_stations_by_stat(Request $request){
        if($request->status == 0 || !isset($request->status)){
            $stations = Station::all();
        }
        else{
            $stations = Station::where('status', $request->status)->get();
        }

        return response()->json(['stations' => $stations]);
    }

    // Add Station
    public function add_station(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255', 'unique:stations'],
            'barcode' => ['required', 'string', 'max:255', 'unique:stations'],
            'station_type' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();

            try{
                Station::insert([
                    'name' => $request->name,
                    'status' => 1,
                    'barcode' => $request->barcode,
                    'station_type' => $request->station_type,
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

    // Get Station By Id
    public function get_station_by_id(Request $request){
        $station = Station::where('id', $request->station_id)->get();
        return response()->json(['station' => $station]);
    }

    // Get Station By Status
    public function get_station_by_stat(Request $request){
        $stations = Station::where('status', $request->status)->get();
        return response()->json(['stations' => $stations]);
    }

    // Edit Station
    public function edit_station(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255|unique:stations,name,'. $request->station_id,
            'barcode' => 'required|string|max:255|unique:stations,barcode,'. $request->station_id,
            'station_type' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();

            try{
                Station::where('id', $request->station_id)
                ->increment('update_version', 1,
                [
                    'name' => $request->name,
                    'barcode' => $request->barcode,
                    'station_type' => $request->station_type,
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

    // Change Station Status
    public function change_station_stat(Request $request){        
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'station_id' => 'required',
            'status' => 'required',
        ]);

        if($validator->passes()){
            try{
                Station::where('id', $request->station_id)
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

    public function get_station_pre_assembly(Request $request){
        return Station::where('station_type', 6)->where('status', 1)->get();
    }
}
