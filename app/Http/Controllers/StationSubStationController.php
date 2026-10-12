<?php

namespace App\Http\Controllers;

use App\Model\StationSubStation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DataTables;
use Auth;

class StationSubStationController extends Controller
{
    //
    //View Stations
	public function view_station_sub_stations(Request $request){
    	$station_sub_stations = StationSubStation::with(['station', 'sub_station'])->get();

        return DataTables::of($station_sub_stations)
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
            ->addColumn('action1', function($station_sub_station){
                $result = '<center><div class="btn-group">
                          <button type="button" class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Action">
                            <i class="fa fa-cog"></i> 
                          </button>
                          <div class="dropdown-menu dropdown-menu-right">';
                if($station_sub_station->status == 1){
                	$result .= '<button class="dropdown-item aEditStation" type="button" station-id="' . $station_sub_station->id . '" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalEditStation" data-keyboard="false">Edit</button>';

                    $result .= '<button class="dropdown-item aChangeStationSubStationStat" type="button" station-sub-station-id="' . $station_sub_station->id . '" status="2" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalChangeStationSubStationStat" data-keyboard="false">Deactivate</button>';
                }
                else{
                    $result .= '<button class="dropdown-item aChangeStationSubStationStat" type="button" style="padding: 1px 1px; text-align: center;" station-sub-station-id="' . $station_sub_station->id . '" status="1" data-toggle="modal" data-target="#modalChangeStationSubStationStat" data-keyboard="false">Activate</button>';
                }
                            
                $result .= '</div>
                        </div></center>';

                return $result;
            })
            ->rawColumns(['label1', 'action1'])
            ->make(true);
    }

	public function add_station_sub_station(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'station_id' => ['required'],
            'sub_station_id' => ['required'],
        ]);

        if($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();

            $station_sub_stations = StationSubStation::where('station_id', $request->station_id)
            						->where('sub_station_id', $request->sub_station_id)
        							->get();

        	if(count($station_sub_stations) > 0) {
        		if($station_sub_stations[0]->status == 1){
        			return response()->json(['result' => "2"]);
        		}
        		else{
        			StationSubStation::where('id', $station_sub_stations[0]->id)
        					->increment('update_version', 1, [
        						'status' => 1,
			                    'created_by' => Auth::user()->id,
			                    'last_updated_by' => Auth::user()->id,
			                    'update_version' => 1,
			                    'updated_at' => date('Y-m-d H:i:s'),
			                    'created_at' => date('Y-m-d H:i:s')
        					]);
        			return response()->json(['result' => "1"]);
        		}
        	}
        	else{
	            try{
	                StationSubStation::insert([
	                    'station_id' => $request->station_id,
	                    'sub_station_id' => $request->sub_station_id,
	                    'status' => 1,
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
	                return response()->json(['result' => "0", 'error' => $e]);
	            }
        	}
        }
    }

    public function get_station_sub_stations_by_stat(Request $request){
        if($request->status == 0 || !isset($request->status)){
            $station_sub_stations = StationSubStation::with(['station', 'sub_station'])->get();
        }
        else{
            $station_sub_stations = StationSubStation::with(['station', 'sub_station'])
            						->where('status', $request->status)
                        			->get();
        }

        return response()->json(['station_sub_stations' => $station_sub_stations]);
    }

    public function change_station_sub_station_stat(Request $request){        
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'station_sub_station_id' => 'required',
            'status' => 'required',
        ]);

        if($validator->passes()){
            try{
                StationSubStation::where('id', $request->station_sub_station_id)
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
}
