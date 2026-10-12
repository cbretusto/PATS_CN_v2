<?php

namespace App\Http\Controllers;

use App\Model\ModeOfDefect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DataTables;
use Auth;
use Maatwebsite\Excel\Facades\Excel;
use QrCode;

class ModeOfDefectController extends Controller
{
    //
    //View MODS
	public function view_mods(){
    	$mods = ModeOfDefect::all();

        return DataTables::of($mods)
            ->addColumn('label1', function($mod){
                $result = "";

                if($mod->status == 1){
                    $result .= '<span class="badge badge-pill badge-success">Active</span>';
                }
                else{
                    $result .= '<span class="badge badge-pill badge-danger">Inactive</span>';
                }

                return $result;
            })
            ->addColumn('action1', function($mod){
                $result = '<center><div class="btn-group">
                          <button type="button" class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Action">
                            <i class="fa fa-cog"></i> 
                          </button>
                          <div class="dropdown-menu dropdown-menu-right">';
                if($mod->status == 1){
                	$result .= '<button class="dropdown-item aEditMOD" type="button" mod-id="' . $mod->id . '" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalEditMOD" data-keyboard="false">Edit</button>';

                    $result .= '<button class="dropdown-item aChangeMODStat" type="button" mod-id="' . $mod->id . '" status="2" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalChangeMODStat" data-keyboard="false">Deactivate</button>';

                 	$result .= '<button class="dropdown-item aGenerateBarcode" mod-id="' . $mod->id . '" barcode="' . $mod->barcode . '" title="mod: " name="' . $mod->name . '" type="button" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalGenBarcode">Generate Barcode</button>';
                }
                else{
                    $result .= '<button class="dropdown-item aChangeMODStat" type="button" style="padding: 1px 1px; text-align: center;" mod-id="' . $mod->id . '" status="1" data-toggle="modal" data-target="#modalChangeMODStat" data-keyboard="false">Activate</button>';
                }
                            
                $result .= '</div>
                        </div></center>';

                return $result;
            })
            ->addColumn('checkbox', function($mod){
                return '<center><input type="checkbox" class="chkMOD" mod-id="' . $mod->id . '"></center>';
            })
            ->rawColumns(['label1', 'action1', 'checkbox'])
            ->make(true);
    }

    // Add MOD
    public function add_mod(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255', 'unique:mode_of_defects'],
            'barcode' => ['required', 'string', 'max:255', 'unique:mode_of_defects'],
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();

            try{
                ModeOfDefect::insert([
                    'name' => $request->name,
                    'barcode' => $request->barcode,
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
                return response()->json(['result' => "0"]);
            }
        }
    }

    // Get MOD By Id
    public function get_mod_by_id(Request $request){
        $mod = ModeOfDefect::where('id', $request->mod_id)->get();
        return response()->json(['mod' => $mod]);
    }

    // Get MODS
    public function get_mods(Request $request){
        $mods = ModeOfDefect::all();
        return response()->json(['mods' => $mods]);
    }

    // Edit MOD
    public function edit_mod(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255|unique:mode_of_defects,name,'. $request->mod_id,
            'barcode' => 'required|string|max:255|unique:mode_of_defects,barcode,'. $request->mod_id,
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();

            try{
                ModeOfDefect::where('id', $request->mod_id)
                ->increment('update_version', 1,
                [
                    'name' => $request->name,
                    'barcode' => $request->barcode,
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

    // Change MOD Status
    public function change_mod_stat(Request $request){        
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'mod_id' => 'required',
            'status' => 'required',
        ]);

        if($validator->passes()){
            try{
                ModeOfDefect::where('id', $request->mod_id)
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
