<?php

namespace App\Http\Controllers;

use App\Model\Machine;
use App\Model\EEDMSGeneralLogistics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DataTables;
use Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CSVMachineImport;
use App\Imports\CSVMachineImportUpdater;
use QrCode;
use App\Exports\MachineExport;

class MachineController extends Controller
{
    public function export_machines(Request $request){
        $data = Machine::with([
            'eedms_machine_info',
        ])->get();
        // return $data;
        return Excel::download(new MachineExport($data), 'Machine.xlsx');
    }

    //View Machines
	public function view_machines(){
        $seniors = [0,1,2,8,13]; // Senior position

    	$machines = Machine::with([
            'eedms_machine_info',
        ])->get();

        return DataTables::of($machines)
            ->addColumn('label1', function($machine){
                $result = "";

                if($machine->status == 1){
                    $result .= '<span class="badge badge-pill badge-success">Active</span>';
                }
                else{
                    $result .= '<span class="badge badge-pill badge-danger">Inactive</span>';
                }

                return $result;
            })
            ->addColumn('action1', function($machine) use($seniors){
                $result = '<center><div class="btn-group">
                          <button type="button" class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Action">
                            <i class="fa fa-cog"></i> 
                          </button>
                          <div class="dropdown-menu dropdown-menu-right">';
                if($machine->status == 1){
                	$result .= '<button class="dropdown-item aEditMachine" type="button" machine-id="' . $machine->id . '" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalEditMachine" data-keyboard="false">Edit</button>';

                    $result .= '<button class="dropdown-item aChangeMachineStat" type="button" machine-id="' . $machine->id . '" status="2" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalChangeMachineStat" data-keyboard="false">Deactivate</button>';

                    // $result .= '<button class="dropdown-item aGenMachineBarcode" machine-id="' . $machine->barcode . '" type="button" style="padding: 1px 1px; text-align: center;">Generate Barcode</button>';

                    $barcode = $machine->barcode;
                    $machine_name = $machine->name;

                    if($machine->barcode == null || $machine->barcode == ''){
                        $barcode = $machine->eedms_machine_info->machine_code_number;
                    }

                    if($machine->name == null || $machine->name == ''){
                        $machine_name = $machine->eedms_machine_info->machine_name;
                    }

                    if($machine->eedms_machine_info != null){
                        $machine_name = $machine->eedms_machine_info->machine_name;
                        $barcode = $machine->eedms_machine_info->machine_code_number;
                    }
                    if(in_array(Auth::user()->position, $seniors)){
                        $result .= '<button class="dropdown-item aGenerateBarcode" machine-id="' . $machine->id . '" barcode="' . $barcode . '" title="'.$machine->name.'" name="' . $barcode . '" type="button" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalGenBarcode">Generate Barcode</button>';
                    }

                }
                else{
                    $result .= '<button class="dropdown-item aChangeMachineStat" type="button" style="padding: 1px 1px; text-align: center;" machine-id="' . $machine->id . '" status="1" data-toggle="modal" data-target="#modalChangeMachineStat" data-keyboard="false">Activate</button>';
                }
                            
                $result .= '</div>
                        </div></center>';

                return $result;
            })
            ->addColumn('checkbox', function($machine){
                return '<center><input type="checkbox" class="chkMachine" machine-id="' . $machine->id . '"></center>';
            })
            ->addColumn('raw_eedms_status', function($machine){
                $result = "";

                if($machine->eedms_machine_info != null){
                    if($machine->eedms_machine_info->machine_status == 'Available'){
                        $result .= '<span class="badge badge-pill badge-success">Available</span>';
                    }
                    else{
                        $result .= '<span class="badge badge-pill badge-danger">Not Available</span>';
                    }
                }
                else{
                    $result .= '<span class="badge badge-pill badge-warning">Not Linked</span>';
                }

                return $result;
            })
            ->addColumn('raw_eedms_machine_code_number', function($machine){
                $result = "XXXXX";

                if($machine->eedms_machine_info != null){
                    $result = $machine->eedms_machine_info->machine_code_number;
                }

                return $result;
            })
            ->rawColumns(['label1', 'action1', 'checkbox', 'raw_eedms_status', 'raw_eedms_machine_code_number'])
            ->make(true);
    }

    // Get Machine By Batch
    public function get_machine_by_batch(Request $request){
        $machines;

        if($request->machine_id == 0){
            $machines = Machine::all();
        }
        else{
            $machines = Machine::whereIn('id', $request->machine_id)->get();
        }
        $qrcode = [];

        if($machines->count() > 0){
            for($index = 0; $index < $machines->count(); $index++){
                $qrcode[] = "data:image/png;base64," . base64_encode(QrCode::format('png')
                                    ->size(200)->errorCorrection('H')
                                    ->generate($machines[$index]->barcode));
            }
        }

        return response()->json(['machines' => $machines, 'qrcode' => $qrcode]);
    }

    // Add Machine
    public function add_machine(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            // 'name' => ['required', 'string', 'max:255', 'unique:machines'],
            'name' => ['required', 'string', 'max:255'],
            'barcode' => ['required', 'string', 'max:255', 'unique:machines'],
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();

            try{
                Machine::insert([
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

    // Get Machine By Id
    public function get_machine_by_id(Request $request){
        $machine = Machine::where('id', $request->machine_id)
            ->with([
                'eedms_machine_info',
            ])
            ->get();
        return response()->json(['machine' => $machine]);
    }

    // Get Machines
    public function get_machines(Request $request){
        $machines = Machine::with([
            'eedms_machine_info',
        ])->get();
        return response()->json(['machines' => $machines]);
    }

    // Edit Machine
    public function edit_machine(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            // 'name' => 'required|string|max:255|unique:machines,name,'. $request->machine_id,
            'barcode' => 'required|string|max:255|unique:machines,barcode,'. $request->machine_id,
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();

            try{
                Machine::where('id', $request->machine_id)
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

    // Change Machine Status
    public function change_machine_stat(Request $request){        
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'machine_id' => 'required',
            'status' => 'required',
        ]);

        if($validator->passes()){
            try{
                Machine::where('id', $request->machine_id)
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

    public function import_machine(Request $request)
    {

        $collections = Excel::toCollection(new CSVMachineImport, request()->file('import_file'));

        DB::beginTransaction();
        try{
            for($index = 0; $index < count($collections[0]); $index++){
                Machine::insert([
                    'name' => $collections[0][$index][0],
                    'barcode' => $collections[0][$index][1],
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

    public function import_machine_updater(Request $request)
    {
        $collections = Excel::toCollection(new CSVMachineImportUpdater, request()->file('import_file'));

        // return $collections;

        DB::beginTransaction();
        try{
            for($index = 2; $index < count($collections[0]); $index++){
                $name = $collections[0][$index][1];
                $barcode = $collections[0][$index][2];
                $machine_code_number = $collections[0][$index][3];
                $general_logistics_id = null;
                $machine_name = null;

                $device_details = Machine::where('barcode', $barcode)->get();

                $eedms_general_logistics = EEDMSGeneralLogistics::select('id', 'machine_code_number', 'machine_name')
                        ->where('machine_section', 'CN')
                        ->where('g_logdel', 0)
                        ->where('machine_code_number', $machine_code_number)
                        ->first();

                if($eedms_general_logistics != null){
                    $general_logistics_id = $eedms_general_logistics->id;
                    $machine_name = $eedms_general_logistics->machine_name;
                }

                if(count($device_details) <= 0){
                    Machine::insert([
                        'name' => $name,
                        'barcode' => $barcode,
                        'machine_code_number' => $machine_code_number,
                        'general_logistics_id' => $general_logistics_id,
                        'machine_name' => $machine_name,
                        'status' => 1,
                        'created_by' => Auth::user()->id,
                        'last_updated_by' => Auth::user()->id,
                        'update_version' => 1,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }
                else{
                    Machine::where('barcode', $barcode)
                        ->increment('update_version', 1,
                        [
                            'machine_code_number' => $machine_code_number,
                            'general_logistics_id' => $general_logistics_id,
                            'machine_name' => $machine_name,
                            'last_updated_by' => Auth::user()->id,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                    }
            }

            DB::commit();

            return response()->json(['result' => "1"]);
        }    
        catch(\Exception $e) {
            DB::rollback();
            return response()->json(['result' => $e]);
        }
    }

    // View Machine EEDMS
    public function view_eedms_general_logistics(Request $request){
        $machines = [];
        $machines = EEDMSGeneralLogistics::select('id', 'machine_code_number', 'machine_name')->where('machine_section', 'CN')->where('g_logdel', 0)->limit(10)->get();

        return response()->json(['machines' => $machines]);
    }
}