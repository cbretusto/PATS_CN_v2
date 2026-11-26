<?php

namespace App\Http\Controllers;

use App\User;
use DataTables;
use QrCode;
use App\Model\Device;
use Illuminate\Http\Request;
use App\Model\ProdPreAssembly;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StationRequest;
use App\Model\ProdPreAssemblyStation;
use App\Model\ProdPreAssemblyStationMod;
use App\Model\ProdPreAssemblyStationMachine;
use App\Model\ProdPreAssemblyStationMaterial;
use App\Model\ProdPreAssemblyStationOperator;
use App\Http\Requests\PreAssemblyDetailsRequest;

class ProdPreAssemblyController extends Controller
{
    public function dt_get_pre_assy(Request $request){
        $prod_assy = ProdPreAssembly::with([
            'stationDetails' => function($query){
                $query->whereNull('deleted_at');
                $query->orderBy('id', 'desc');
            }
        ])
        ->where('po_no', $request->po)->where('pre_assy_station_code', $request->pre_assy_code);

        return DataTables::of($prod_assy)
        ->addColumn('action', function($prod_assy){
            $result = "";
            $disabled = '';
            $btn = 'btn-warning';
            if($prod_assy->status == 1){
                $disabled = true;
            }
            if($prod_assy->print_status == 1){
                $btn = 'btn-info';
                
            }
            $result .= "<button class='btn btn-sm btn-primary btnViewPreAssy' data-id='{$prod_assy->id}' data-disable='{$disabled}' title='View Pre-Assembly Details'><i class='fas fa-info-circle'></i></button>";
            
            if($prod_assy->status == 1){
                $result.= "<button class='btn btn-sm {$btn} btnPrintPreAssySticker ml-1'  
                data-pre-assy-id='{$prod_assy->id}' 
                data-qty={$prod_assy->stationDetails[0]->qty_output}
                data-pre-assy-no='{$prod_assy->pre_assy_no}'
                data-pre-assy-print-status='{$prod_assy->print_status}'
                title='Print Sticker'><i class='fas fa-print'></i></button>";
            }
            return $result;
        })
        ->addColumn('raw_status', function($prod_assy){
            $result = "";
            switch($prod_assy->status){
                case 0:
                    $result = "<span class='badge badge-secondary'>Pending</span>";
                    break;
                case 1:
                    if($prod_assy->print_status == 1){
                        $result = "<span class='badge badge-success mb-1'>Done</span><br><span class='badge badge-success'>Printed</span>";
                    }
                    else{
                        $result = "<span class='badge badge-success mb-1'>Done</span><br><span class='badge badge-warning'>For Print</span>";
                    }
                    break;
                default:
                    $result = "";
                    break;
            }
            return $result;
        })
        ->addColumn('raw_ok_details', function($prod_assy){
            $result = null;
            if($prod_assy->status == 1){
                $result = "{$prod_assy->stationDetails[0]->qty_output}";
            }
            return $result;
        })
        ->addColumn('raw_ng_details', function($prod_assy){
            $result = null;
            if($prod_assy->status == 1){
                $result = "{$prod_assy->stationDetails[0]->qty_ng}";

                // $result = collect($prod_assy->stationDetails)->sum('qty_input') - collect($prod_assy->stationDetails)->sum('qty_output');
            }
            return $result;
        })
        ->rawColumns(['action', 'raw_status', 'raw_ok_details', 'raw_ng_details'])
        ->make(true);
    }

    public function save_pre_assy_details(PreAssemblyDetailsRequest $request){
        date_default_timezone_set('Asia/Manila');
        $data = $request->validated();
        DB::beginTransaction();
        $pre_assembly_details = "";
        try{
            if(isset($request->assy_id)){ // Update
                $data['updated_by'] = $request->scanned_user_id;
                ProdPreAssembly::where('id', $request->assy_id)
                ->update($data);
                $result = 2;
            }
            else{ // Create
                $pre_assy_number = $this->generate_pre_assy_number($data['po_no'], $data['pre_assy_station_code']);
                $data['created_at'] = NOW();
                $data['created_by'] = $request->scanned_user_id;
                $data['pre_assy_no'] = $pre_assy_number;

                // Insert data and retrieve the model instance
                $pre_assembly = ProdPreAssembly::create($data);
                // Now, $preAssembly contains the inserted record, including the ID
                $pre_assembly_details = ProdPreAssembly::find($pre_assembly->id);
                $result = 1;

            }

            DB::commit();
            return response()->json([
                'result' => $result,
                'msg' => 'Transaction Success',
                'preAssemblyDetails' => $pre_assembly_details
            ]);
        }catch(Exemption $e){
            DB::rollback();
            return $e;
        }
    }

    public function get_pre_assy_details_by_id(Request $request){
        return ProdPreAssembly::find($request->id);
    }

    public function dt_get_pre_assy_station(Request $request){
        $prod_pre_assy_stations = ProdPreAssemblyStation::with([
            'preAssemblyDetails',
            'stationDetails',
            'subStationDetails',
            'materialProcessDetails',
            'materialDetails' => function($query){
                $query->whereNull('deleted_at');
            },
            'operators' => function($query){
                $query->whereNull('deleted_at');
            },
            'operators.operatorDetails',

            'machines' => function($query){
                $query->whereNull('deleted_at');
            },
            'machines.machineDetails',
            'MOD' => function($query){
                $query->whereNull('deleted_at');
            },
            'MOD.modDetails'
        ])
        ->where('pre_assembly_id', $request->pre_assy_id)->whereNull('deleted_at')->get();

        return DataTables::of($prod_pre_assy_stations)
        ->addColumn('action', function($prod_pre_assy_stations){
            $result = "";
            $disabled = '';
            if($prod_pre_assy_stations->preAssemblyDetails->status == 1){
                $disabled = 'disabled';                
            }
            $result .= "<button class='btn btn-sm btn-primary small btnEditPreAssyProcessDetails' title='Edit Process' data-station-id='{$prod_pre_assy_stations->id}' {$disabled}'><i class='fas fa-edit'></i></button>";
            return $result;
        })
        ->addColumn('raw_materials', function($prod_pre_assy_stations){
            $result = "";
            foreach($prod_pre_assy_stations->materialDetails as $material){
                $result .= "<span class='badge badge-secondary mb-1'>{$material->item_name}</span><br>";
            }
            return $result;
        })
        ->addColumn('raw_operator', function($prod_pre_assy_stations){
            $result = "";
            foreach($prod_pre_assy_stations->operators as $operator){
                $result .= "<span class='badge badge-secondary mb-1'>{$operator->operatorDetails->name}</span><br>";
            }
            return $result;
        })
        ->addColumn('raw_machine', function($prod_pre_assy_stations){
            $result = "";
            foreach($prod_pre_assy_stations->machines as $machine){
                $result .= "<span class='badge badge-secondary mb-1'>{$machine->machineDetails->machine_name}</span><br>";
            }
            return $result;
        })
        ->addColumn('ng_qty', function($prod_pre_assy_stations){
            return $prod_pre_assy_stations->qty_input - $prod_pre_assy_stations->qty_output;
        })
        ->addColumn('raw_mod', function($prod_pre_assy_stations){
            $result = "";
            foreach($prod_pre_assy_stations->MOD as $mod){
                $result .= "<span class='badge badge-secondary mb-1'>{$mod->modDetails->name}</span><br>";
            }
            return $result;
        })
        ->rawColumns(['action', 'ng_qty', 'raw_materials', 'raw_operator', 'raw_machine', 'raw_mod'])
        ->make(true);
    }

    public function get_data_for_adding_process_pre_assy(Request $request){
        $matrix_details =  Device::with([
            'material_process'  => function($query){
                $query->where('status', 1);
            },
            'material_process.manpowers_details',
            'material_process.material_details' => function($query) use($request){
                $query->where('status', 1);
            },
            'material_process.station_sub_station.station',
            'material_process.station_sub_station.sub_station',
            'material_process.a_shift_manpowers_details' => function($query){
                $query->where('status', 1);
                $query->where('shift', 1);
            },
            'material_process.a_shift_manpowers_details.manpower_info',
            'material_process.b_shift_manpowers_details' => function($query){
                $query->where('status', 1);
                $query->where('shift', 2);
            },
            'material_process.b_shift_manpowers_details.manpower_info',

            'material_process.machine_details' => function($query){
                $query->where('status', 1);
            },
            'material_process.machine_details.machine_info'

        ])
        ->where('name', $request->device_name)
        ->where('barcode', $request->device_code)
        ->first();

        $steps = collect($matrix_details['material_process'])
        ->filter(function ($process) use ($request) {
            return $process['station_sub_station']['station']['barcode'] == $request->station_pre_assy_code;
        })
        ->sortBy('step')
        ->values();

        $prod_pre_assy_stations = ProdPreAssemblyStation::where('pre_assembly_id', $request->prod_pres_assy_id)
        ->whereNull('deleted_at')
        ->orderBy('id', 'desc')
        ->get();

        return response()->json([
            'matrixDetails'       => $matrix_details,
            'prodPreAssyStations' => $prod_pre_assy_stations,
            'steps'               => $steps
        ]);
    }

    public function save_pre_assembly_station(StationRequest $request){
        
        date_default_timezone_set('Asia/Manila');
        DB::beginTransaction();
        $user_details = User::where('employee_id',$request->scanned_id)->first();

        $prod_pre_assy_station_id;
        try{
            if(isset($request->pre_assy_station_id)){ // Update
                ProdPreAssemblyStation::where('id', $request->pre_assy_station_id)
                ->update([
                    'pre_assembly_id' => $request->pre_assy_id,
                    'mat_proc_id'     => $request->mat_process_id,
                    'station_id'      => $request->mat_process_station_id,
                    'sub_station_id'  => $request->substation,
                    'step_num'        => $request->steps,
                    'qty_input'       => $request->input_qty,
                    'qty_output'      => $request->output_qty,
                    'remarks'         => $request->remarks,
                    'created_by'      => $user_details->id,
                ]);
                $prod_pre_assy_station_id = $request->pre_assy_station_id;
                ProdPreAssemblyStationMachine::where('pre_assembly_station_id', $request->pre_assy_station_id)->delete();
                ProdPreAssemblyStationOperator::where('pre_assembly_station_id', $request->pre_assy_station_id)->delete();
                ProdPreAssemblyStationMaterial::where('pre_assembly_station_id', $request->pre_assy_station_id)->delete();
                ProdPreAssemblyStationMod::where('pre_assembly_station_id', $request->pre_assy_station_id)->delete();

            }
            else{ // Create
                /*
                 * Insert data and retrieve the model instance
                */
                $prod_pre_assy_station_id = ProdPreAssemblyStation::insertGetId([
                    'pre_assembly_id' => $request->pre_assy_id,
                    'mat_proc_id'     => $request->mat_process_id,
                    'station_id'      => $request->mat_process_station_id,
                    'sub_station_id'  => $request->substation,
                    'step_num'        => $request->steps,
                    'qty_input'       => $request->input_qty,
                    'qty_output'      => $request->output_qty,
                    'qty_ng'          => $request->ng_qty,
                    'remarks'         => $request->remarks,
                    'created_by'      => $user_details->id,
                ]);

                
                
            }
            /*
                * Insert Machines 
            */
            if(isset($request->machine)){
                foreach($request->machine AS $machine){
                    ProdPreAssemblyStationMachine::insert([
                        'pre_assembly_id' => $request->pre_assy_id,
                        'pre_assembly_station_id' => $prod_pre_assy_station_id,
                        'machine_id' => $machine,
                        'created_by' => $user_details->id,
                    ]);
                }
            }
            

            /*
                * Insert Operators 
            */
            if(isset($request->operators)){
                foreach ($request->operators as $operator) {
                    ProdPreAssemblyStationOperator::insert([
                        'pre_assembly_id'         => $request->pre_assy_id,
                        'pre_assembly_station_id' => $prod_pre_assy_station_id,
                        'operator_id'             => $operator,
                        'created_by'              => $user_details->id,
                    ]);
                }
            }

            

            /*
                * Insert Materials
            */
            if(isset($request->materialArray)){
                foreach ($request->materialArray as $material) {
                    ProdPreAssemblyStationMaterial::insert([
                        'pre_assembly_id'         => $request->pre_assy_id,
                        'pre_assembly_station_id' => $prod_pre_assy_station_id,
                        'item_code'               => $material['material_part_no'],
                        'item_name'               => $material['material_name'],
                        'item_lot_no'             => $material['lot_no'],
                        'tbl_wbs'                 => $material['material_type'],
                        'created_by'              => $user_details->id
                    ]);
                }
            }
           
            /*
                * Insert MOD 
            */
            if(isset($request->mod)){
                for ($i=0; $i < count($request->mod); $i++) { 
                    ProdPreAssemblyStationMod::insert([
                        'pre_assembly_id'         => $request->pre_assy_id,
                        'pre_assembly_station_id' => $prod_pre_assy_station_id,
                        'mod_id'                  => $request->mod[$i],
                        'mod_qty'                 => $request->mod_qty[$i],
                        'created_by'              => $user_details->id
                    ]);
                }
            }

            DB::commit();
            return response()->json([
                'result' => true,
                'msg' => 'Transaction Success'
            ]);
        }catch(Exemption $e){
            DB::rollback();
            return $e;
        }
    }

    public function get_pre_assy_station_details_by_id(Request $request){
        $prod_pre_assy_station = ProdPreAssemblyStation::with([
            'preAssemblyDetails',
            'stationDetails',
            'subStationDetails',
            // 'materialProcessDetails',
            'materialDetails' => function($query){
                $query->whereNull('deleted_at');
            },
            'operators' => function($query){
                $query->whereNull('deleted_at');
            },
            // 'operators.operatorDetails',

            'machines' => function($query){
                $query->whereNull('deleted_at');
            },
            // 'machines.machineDetails',
            'MOD' => function($query){
                $query->whereNull('deleted_at');
            },
            // 'MOD.modDetails'
        ])
        ->where('id', $request->id)
        ->first();

        return response()->json([
            'prodPreAssyStation' => $prod_pre_assy_station
        ]);
    }

    public function dt_get_pre_assy_materials(Request $request){
        $prod_pre_assy_materials = ProdPreAssemblyStation::with([
            'materialDetails'
        ])
        ->where('pre_assembly_id', $request->pre_assy_id)
        ->whereNull('deleted_at')->get();

        return DataTables::of($prod_pre_assy_materials)
        ->make(true);
    }

    public function done_pre_assy(Request $request){
        // return $request->all();
        date_default_timezone_set('Asia/Manila');
        $scanned_user_details = User::where('employee_id', $request->scanned_user_id)->where('status', 1)->first();

        DB::beginTransaction();
        try{
            ProdPreAssembly::where('id', $request->id)->update([
                'status' => 1
            ]);
            DB::commit();
            return response()->json([
                'result' => true,
                'msg' => 'Transaction Success'
            ]);
        }catch(Exemption $e){
            DB::rollback();
            return $e;
        }
    }

    public function generate_qr_code_pre_assy(Request $request){

        $data = $request->all();
        $qrcode = QrCode::format('png')
        ->size(200)->errorCorrection('H')
        ->generate(json_encode($data));

        $final_qr = "data:image/png;base64," . base64_encode($qrcode);
        $label = "
        <table class='w-100'>
            <tr>
                <td><strong>Pre Assembly No.</strong></td>
                <td>: {$request->pre_assy_no}</td>
            </tr>
            <tr>
                <td><strong>Quantity</strong></td>
                <td>: {$request->qty}</td>
            </tr>
        </table>
        ";

        return response()->json([
            'qrCode' => $final_qr,
            'label' => $label,
            'request' => $data
        ]);
    }

    public function change_print_status(Request $request){
        date_default_timezone_set('Asia/Manila');
        DB::beginTransaction();
        try{
            ProdPreAssembly::where('id', $request->id)->update([
                'print_status' => 1
            ]);
            DB::commit();
            return response()->json([
                'result' => true,
                'msg' => 'Transaction Success'
            ]);
        }catch(Exemption $e){
            DB::rollback();
            return $e;
        }
    }

    function generate_pre_assy_number($po_no, $pre_assy_station_code){
        $query = new ProdPreAssembly();
        $query->where('po_no', $po_no);

        $prod_assy_count = $query->count('po_no');
        $counter = $prod_assy_count + 1;
        $final_counter = str_pad($counter, 3, '0', STR_PAD_LEFT); 
        $substring_po = substr($po_no, 0, -5);

        $pre_assy_number = "{$pre_assy_station_code}-{$substring_po}-{$final_counter}";
        return $pre_assy_number;
    }
    
}
