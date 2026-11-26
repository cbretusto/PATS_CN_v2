<?php

namespace App\Http\Controllers;

use App\Model\CNLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DataTables;
use Auth;

class CNLineController extends Controller
{
    //View CN Lines
	public function view_cn_lines(){
    	$cn_lines = CNLine::all();

        return DataTables::of($cn_lines)
            ->addColumn('label1', function($cn_line){
                $result = "";

                if($cn_line->status == 1){
                    $result .= '<span class="badge badge-pill badge-success">Active</span>';
                }
                else{
                    $result .= '<span class="badge badge-pill badge-danger">Inactive</span>';
                }

                return $result;
            })
            ->addColumn('action1', function($cn_line){
                $result = '<div class="btn-group">
                          <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Action">
                            <i class="fa fa-cog"></i> 
                          </button>
                          <div class="dropdown-menu dropdown-menu-right">';
                if($cn_line->status == 1){
                	$result .= '<button class="dropdown-item aEditCNLine" type="button" cn-line-id="' . $cn_line->id . '" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalEditCNLine" data-keyboard="false">Edit</button>';

                    $result .= '<button class="dropdown-item aChangeCNLineStat" type="button" cn-line-id="' . $cn_line->id . '" status="2" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalChangeCNLineStat" data-keyboard="false">Deactivate</button>';

                    // $result .= '<button class="dropdown-item aGenCNLineBarcode" cn-line-id="' . $cn_line->barcode . '" type="button" style="padding: 1px 1px; text-align: center;">Generate Barcode</button>';
                }
                else{
                    $result .= '<button class="dropdown-item aChangeCNLineStat" type="button" style="padding: 1px 1px; text-align: center;" cn-line-id="' . $cn_line->id . '" status="1" data-toggle="modal" data-target="#modalChangeCNLineStat" data-keyboard="false">Activate</button>';
                }
                            
                $result .= '</div>
                        </div>';

                return $result;
            })
            ->rawColumns(['label1', 'action1'])
            ->make(true);
    }

    // Add CN Line
    public function add_cn_line(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255', 'unique:cn_lines'],
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();

            try{
                CNLine::insert([
                    'name' => $request->name,
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
                return response()->json(['result' => $e]);
            }
        }
    }

    // Get CN Line By Id
    public function get_cn_line_by_id(Request $request){
        $cn_line = CNLine::where('id', $request->cn_line_id)->get();
        return response()->json(['cn_line' => $cn_line]);
    }

    // Edit CN Line
    public function edit_cn_line(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255|unique:cn_lines,name,'. $request->cn_line_id,
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();

            try{
                CNLine::where('id', $request->cn_line_id)
                ->increment('update_version', 1,
                [
                    'name' => $request->name,
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

    // Change CN Line Status
    public function change_cn_line_stat(Request $request){        
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'cn_line_id' => 'required',
            'status' => 'required',
        ]);

        if($validator->passes()){
            try{
                CNLine::where('id', $request->cn_line_id)
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
