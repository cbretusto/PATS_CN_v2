<?php

namespace App\Http\Controllers;

use App\Model\AssemblyLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DataTables;
use Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CSVAssemblyLineImport;

class AssemblyLineController extends Controller
{
    //View Assembly Lines
	public function view_assembly_lines(){
    	$assembly_lines = AssemblyLine::all();

        return DataTables::of($assembly_lines)
            ->addColumn('label1', function($assembly_line){
                $result = "";

                if($assembly_line->status == 1){
                    $result .= '<span class="badge badge-pill badge-success">Active</span>';
                }
                else{
                    $result .= '<span class="badge badge-pill badge-danger">Inactive</span>';
                }

                return $result;
            })
            ->addColumn('action1', function($assembly_line){
                $result = '<center><div class="btn-group">
                          <button type="button" class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Action">
                            <i class="fa fa-cog"></i> 
                          </button>
                          <div class="dropdown-menu dropdown-menu-right">';
                if($assembly_line->status == 1){
                	$result .= '<button class="dropdown-item aEditAssemblyLine" type="button" assembly-line-id="' . $assembly_line->id . '" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalEditAssemblyLine" data-keyboard="false">Edit</button>';

                    $result .= '<button class="dropdown-item aChangeAssemblyLineStat" type="button" assembly-line-id="' . $assembly_line->id . '" status="2" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalChangeAssemblyLineStat" data-keyboard="false">Deactivate</button>';

                    // $result .= '<button class="dropdown-item aGenAssemblyLineBarcode" assembly-line-id="' . $assembly_line->barcode . '" type="button" style="padding: 1px 1px; text-align: center;">Generate Barcode</button>';
                }
                else{
                    $result .= '<button class="dropdown-item aChangeAssemblyLineStat" type="button" style="padding: 1px 1px; text-align: center;" assembly-line-id="' . $assembly_line->id . '" status="1" data-toggle="modal" data-target="#modalChangeAssemblyLineStat" data-keyboard="false">Activate</button>';
                }
                            
                $result .= '</div>
                        </div></center>';

                return $result;
            })
            ->rawColumns(['label1', 'action1'])
            ->make(true);
    }

    // Add Assembly Line
    public function add_assembly_line(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255', 'unique:assembly_lines'],
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();

            try{
                AssemblyLine::insert([
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

    // Get Assembly Line By Id
    public function get_assembly_line_by_id(Request $request){
        $assembly_line = AssemblyLine::where('id', $request->assembly_line_id)->get();
        return response()->json(['assembly_line' => $assembly_line]);
    }

    // Edit Assembly Line
    public function edit_assembly_line(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255|unique:assembly_lines,name,'. $request->assembly_line_id,
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();

            try{
                AssemblyLine::where('id', $request->assembly_line_id)
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

    // Change Assembly Line Status
    public function change_assembly_line_stat(Request $request){        
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'assembly_line_id' => 'required',
            'status' => 'required',
        ]);

        if($validator->passes()){
            try{
                AssemblyLine::where('id', $request->assembly_line_id)
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

    public function get_assembly_lines(Request $request){
        $assembly_lines = AssemblyLine::where('status', 1)->get();
        return response()->json(['assembly_lines' => $assembly_lines]);
    }

    public function import_assembly_line(Request $request)
    {
        $collections = Excel::toCollection(new CSVAssemblyLineImport, request()->file('import_file'));
        DB::beginTransaction();
        try{
            for($index = 0; $index < count($collections[0]); $index++){
                AssemblyLine::insert([
                    'name' => $collections[0][$index][0],
                    'status' => 1,
                    'created_by' => Auth::user()->id,
                    'last_updated_by' => Auth::user()->id,
                    'update_version' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }

            DB::commit();

            return response()->json(['result' => "1"]);
        }    
        catch(\Exception $e) {
            DB::rollback();
            return response()->json(['result' => $e]);
        }
    }
}
