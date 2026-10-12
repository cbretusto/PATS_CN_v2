<?php

namespace App\Http\Controllers;

use App\Model\Device;
use App\Model\MaterialProcess;
use App\Model\MaterialProcessManpower;
use App\Model\MaterialProcessMachine;
use App\Model\MaterialProcessMaterial;
use App\Model\MaterialIssuanceDetails;
use App\Model\WBSSakidashiIssuanceItem;
use App\Model\Machine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DataTables;
use Auth;

class MaterialProcessController extends Controller
{
    //
    public function view_material_process_by_device_id(Request $request){
        date_default_timezone_set('Asia/Manila');
        $material_process = MaterialProcess::with([
                'station_sub_station',
                'station_sub_station.station',
                'station_sub_station.sub_station',
                // 'machine_info',
                'machine_details' => function($query){
                    $query->where('status', 1);
                },
                'machine_details.machine_info',
                'a_shift_manpowers_details' => function($query){
                    $query->where('status', 1);
                    $query->where('shift', 1);
                },
                'a_shift_manpowers_details.manpower_info',
                'b_shift_manpowers_details' => function($query){
                    $query->where('status', 1);
                    $query->where('shift', 2);
                },
                'b_shift_manpowers_details.manpower_info',
                // 'material_kitting_details' => function($query){
                //     $query->where('status', 1);
                //     $query->where('tbl_wbs', 1);
                // },
                // 'sakidashi_details' => function($query){
                //     $query->where('status', 1);
                //     $query->where('tbl_wbs', 2);
                // },
                'material_details' => function($query){
                    $query->where('status', 1);
                },
            ])
            ->where('device_id', $request->device_id)
            ->where('status', $request->status)
            ->get();
        // return $material_process[0]->station_sub_station->station->id;
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
                $btn_color = 'btn-primary';
                if($material_process->status == 2){
                    $btn_color = 'btn-danger';
                }

                $result = '<center><div class="btn-group">
                          <button type="button" class="btn ' . $btn_color . ' dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Action">
                            <i class="fa fa-cog"></i>
                          </button>
                          <div class="dropdown-menu dropdown-menu-right">';
                if($material_process->status == 1){
                    $result .= '<button class="dropdown-item aEditMatProc" type="button" material-process-id="' . $material_process->id . '" style="padding: 1px 1px; text-align: center;" data-keyboard="false">Edit</button>';

                    $result .= '<button class="dropdown-item aChangeMatProcStat" type="button" material-process-id="' . $material_process->id . '" status="2" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalChangeMatProcStat" data-keyboard="false">Deactivate</button>';
                }
                else{
                    $result .= '<button class="dropdown-item aChangeMatProcStat" type="button" style="padding: 1px 1px; text-align: center;" material-process-id="' . $material_process->id . '" status="1" data-toggle="modal" data-target="#modalChangeMatProcStat" data-keyboard="false">Activate</button>';
                }

                $result .= '</div>
                        </div></center>';

                return $result;
            })
            // ->addColumn('station_id', function($material_process){
            //     $result = "";
            //     $result .= $material_process->station_sub_station['station']['id'];
            //     // $result .= $material_process->station_sub_station['station'];
            //     return $result;
            // })
            // ->addColumn('station_name', function($material_process){
            //     $result = "";
            //     $result .= $material_process->station_sub_station['station']['name'];
            //     // $result .= $material_process->station_sub_station['station'];

            //     return $result;
            // })
            // ->addColumn('sub_station_name', function($material_process){
            //     $result = "";
            //     // $result .= $material_process->station_sub_station['sub_station']['name'];
            //     if($material_process->station_sub_station['sub_station'] != null){
            //         $result .= $material_process->station_sub_station['sub_station'];
            //     }

            //     return $result;
            // })
            // ->rawColumns(['label1', 'action1', 'station_id', 'station_name', 'sub_station_name'])
            ->rawColumns(['label1', 'action1'])
            ->make(true);
    }

    // Add Material Process
    public function add_material_process(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'step' => ['required', 'max:255'],
            // 'station_sub_station_id' => ['required', 'max:255'], // comment ko muna by Nessa
            'device_id' => ['required', 'numeric'],
            // 'a_shift_user_id' => ['required'],
            // 'b_shift_user_id' => ['required'],
            // 'material' => ['required'],
            // 'machine_id' => ['required', 'numeric', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            // $station_id = explode('--', $request->station_sub_station_id)[0];
            // $sub_station_id = explode('--', $request->station_sub_station_id)[1];

            // return $station_id . ' + ' . $sub_station_id;

            // $material_process = MaterialProcess::where('sub_station_id', $request->sub_station_id)
            //          ->where('step', $request->step)
            //          ->where('device_id', $request->device_id)
         //                ->where('status', 1)
            //          ->get();

         //    $material_process2 = MaterialProcess::where('sub_station_id', $request->sub_station_id)
         //                ->where('step', $request->step)
         //                ->where('status', 1)
         //                ->get();

            // if(count($material_process) <= 0 && count($material_process2) <= 0){
                DB::beginTransaction();

                try{
                    $has_emboss                = 0;
                    $require_oqc_before_emboss = 0;
                    $has_printing              = 0;
                    $has_reel_input            = 0;
                    $has_print_input           = 0;
                    $has_guaranteed            = 0;
                    if(isset($request->has_emboss)){
                        $has_emboss = 1;
                    }

                    if(isset($request->require_oqc_before_emboss)){
                        $require_oqc_before_emboss = 1;
                    }

                    if(isset($request->print_label)){
                        $has_printing = 1;
                    }
                    if(isset($request->inp_reel_lot)){
                        $has_reel_input = 1;
                    }
                    if(isset($request->inp_print_lot)){
                        $has_print_input = 1;
                    }
                    if(isset($request->inp_guaranteed_lot)){
                        $has_guaranteed = 1;
                    }

                    $material_process_id = MaterialProcess::insertGetId([
                        'step'                      => $request->step,
                        'status'                    => 1,
                        'device_id'                 => $request->device_id,
                        'has_emboss'                => $has_emboss,
                        'require_oqc_before_emboss' => $require_oqc_before_emboss,
                        'has_printing'              => $has_printing,
                        'has_reel_input'            => $has_reel_input,
                        'has_print_input'           => $has_print_input,
                        'has_guaranteed'            => $has_guaranteed,
                        'station_sub_station_id'    => $request->station_sub_station_id,
                        'created_by'                => Auth::user()->id,
                        'last_updated_by'           => Auth::user()->id,
                        'update_version'            => 1,
                        'updated_at'                => date('Y-m-d H:i:s'),
                        'created_at'                => date('Y-m-d H:i:s')
                    ]);

                    if(isset($request->a_shift_user_id)){
                        for($index = 0; $index < count($request->a_shift_user_id); $index++){
                            MaterialProcessManpower::insert([
                                'mat_proc_id' => $material_process_id,
                                'manpower_id' => $request->a_shift_user_id[$index],
                                'status' => 1,
                                'shift' => 1,
                                'created_by' => Auth::user()->id,
                                'last_updated_by' => Auth::user()->id,
                                'update_version' => 1,
                                'updated_at' => date('Y-m-d H:i:s'),
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
                        }

                    }

                    if(isset($request->b_shift_user_id)){
                        for($index = 0; $index < count($request->b_shift_user_id); $index++){
                            MaterialProcessManpower::insert([
                                'mat_proc_id' => $material_process_id,
                                'manpower_id' => $request->b_shift_user_id[$index],
                                'status' => 1,
                                'shift' => 2,
                                'created_by' => Auth::user()->id,
                                'last_updated_by' => Auth::user()->id,
                                'update_version' => 1,
                                'updated_at' => date('Y-m-d H:i:s'),
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
                        }
                    }

                    $error_counter = 0;
                    $trouble_counter = 0;

                    $machine_details;

                    $trouble_machines = [];

                    if(isset($request->machine_id)){
                        for($index = 0; $index < count($request->machine_id); $index++){

                            $machine_info = Machine::where('status', 1)
                                ->with([
                                    'eedms_machine_info',
                                    'eedms_machine_info.trouble_info'
                                ])
                                ->where('id', $request->machine_id[$index])
                                ->first();

                            $machine_details[] = $machine_info;

                            if($machine_info != null){
                                if($machine_info->eedms_machine_info != null && $machine_info->eedms_machine_info->machine_status != 'Available'){
                                    $error_counter++;
                                }
                                else{
                                    if($machine_info->eedms_machine_info->trouble_info != null){
                                        $trouble_counter++;
                                        $trouble_machines[] = $machine_info->eedms_machine_info->machine_name;
                                    }
                                    else{
                                        $trouble_counter = 0;
                                        // $error_counter++;
                                    }
                                }
                            }
                            else{
                                $error_counter++;
                            }

                            MaterialProcessMachine::insert([
                                'mat_proc_id' => $material_process_id,
                                'machine_id' => $request->machine_id[$index],
                                'status' => 1,
                                'created_by' => Auth::user()->id,
                                'last_updated_by' => Auth::user()->id,
                                'update_version' => 1,
                                'updated_at' => date('Y-m-d H:i:s'),
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
                        }
                    }

                    // return $machine_details;
                    // return $trouble_machines;
                    // return $trouble_counter;

                    if($error_counter > 0 || $trouble_counter > 0){
                        DB::rollback();
                        // throw $e;
                        return response()->json(['result' => 0, 'error_counter' => $error_counter, 'trouble_counter' => $trouble_counter, 'trouble_machines' => $trouble_machines, 'machine_details' => $machine_details]);
                    }

                    // if(isset($request->machine_id)){
                    //     for($index = 0; $index < count($request->machine_id); $index++){
                    //         MaterialProcessMachine::insert([
                    //             'mat_proc_id' => $material_process_id,
                    //             'machine_id' => $request->machine_id[$index],
                    //             'status' => 1,
                    //             'created_by' => Auth::user()->id,
                    //             'last_updated_by' => Auth::user()->id,
                    //             'update_version' => 1,
                    //             'updated_at' => date('Y-m-d H:i:s'),
                    //             'created_at' => date('Y-m-d H:i:s')
                    //         ]);
                    //     }
                    // }

                    if(isset($request->material_kitting_item)){
                        for($index = 0; $index < count($request->material_kitting_item); $index++){
                            MaterialProcessMaterial::insert([
                                'mat_proc_id' => $material_process_id,
                                'item' => explode('--', $request->material_kitting_item[$index])[0],
                                'item_desc' => explode('--', $request->material_kitting_item[$index])[1],
                                'tbl_wbs' => 1,
                                'status' => 1,
                                'created_by' => Auth::user()->id,
                                'last_updated_by' => Auth::user()->id,
                                'update_version' => 1,
                                'updated_at' => date('Y-m-d H:i:s'),
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
                        }
                    }

                    if(isset($request->sakidashi_item)){
                        for($index = 0; $index < count($request->sakidashi_item); $index++){
                            MaterialProcessMaterial::insert([
                                'mat_proc_id' => $material_process_id,
                                'item' => explode('--', $request->sakidashi_item[$index])[0],
                                'item_desc' => explode('--', $request->sakidashi_item[$index])[1],
                                'tbl_wbs' => 2,
                                'status' => 1,
                                'created_by' => Auth::user()->id,
                                'last_updated_by' => Auth::user()->id,
                                'update_version' => 1,
                                'updated_at' => date('Y-m-d H:i:s'),
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
                        }
                    }

                    if(isset($request->emboss_item)){
                        for($index = 0; $index < count($request->emboss_item); $index++){
                            MaterialProcessMaterial::insert([
                                'mat_proc_id' => $material_process_id,
                                'item' => explode('--', $request->emboss_item[$index])[0],
                                'item_desc' => explode('--', $request->emboss_item[$index])[1],
                                'tbl_wbs' => 2,
                                'status' => 1,
                                'has_emboss' => 1,
                                'created_by' => Auth::user()->id,
                                'last_updated_by' => Auth::user()->id,
                                'update_version' => 1,
                                'updated_at' => date('Y-m-d H:i:s'),
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
                        }
                    }

                    DB::commit();

                    return response()->json(['result' => "1"]);
                }
                catch(\Exception $e) {
                    DB::rollback();
                    // throw $e;
                    return response()->json(['result' => $e]);
                }
            // }
            // else{
            //  return response()->json(['result' => "2"]);
            // }
        }
    }

    // Get Material Process By Id
    public function get_mat_proc_by_id(Request $request){
        date_default_timezone_set('Asia/Manila');
        $material_process = MaterialProcess::with([
                                    'manpowers_details' => function($query){
                                        $query->where('status', 1);
                                    },
                                    'manpowers_details.manpower_info',
                                    'machine_details' => function($query){
                                        $query->where('status', 1);
                                    },
                                    'material_details' => function($query){
                                        $query->where('status', 1);
                                    },
                                ])
                                ->where('id', $request->material_process_id)
                                ->get();
        return response()->json(['material_process' => $material_process]);
    }

    // Edit Material Process
    public function edit_material_process(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        // return $data;

        $validator = Validator::make($data, [
            'step' => ['required'],
            'station_sub_station_id' => ['required'],
            'device_id' => ['required', 'numeric'],
            'material_process_id' => ['required', 'numeric'],
            // 'a_shift_user_id' => ['required'],
            // 'b_shift_user_id' => ['required'],
            // 'material' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
                DB::beginTransaction();

                try{
                    $has_emboss = 0;
                    $require_oqc_before_emboss = 0;
                    $has_printing = 0;
                    $has_reel_input = 0;
                    $has_print_input = 0;
                    $has_guaranteed = 0;

                    if(isset($request->has_emboss)){
                        $has_emboss = 1;
                    }

                    if(isset($request->require_oqc_before_emboss)){
                        $require_oqc_before_emboss = 1;
                    }

                    if(isset($request->print_label)){
                        $has_printing = 1;
                    }
                    if(isset($request->inp_reel_lot)){
                        $has_reel_input = 1;
                    }
                    if(isset($request->inp_print_lot)){
                        $has_print_input = 1;
                    }
                    if(isset($request->inp_guaranteed_lot)){
                        $has_guaranteed = 1;
                    }

                    MaterialProcess::where('id', $request->material_process_id)
                    ->increment('update_version', 1,
                        [
                            'step'                      => $request->step,
                            'device_id'                 => $request->device_id,
                            'has_emboss'                => $has_emboss,
                            'require_oqc_before_emboss' => $require_oqc_before_emboss,
                            'has_printing'              => $has_printing,
                            'has_reel_input'            => $has_reel_input,
                            'has_print_input'           => $has_print_input,
                            'has_guaranteed'            => $has_guaranteed,
                            'station_sub_station_id'    => $request->station_sub_station_id,
                            'last_updated_by'           => Auth::user()->id,
                            'updated_at'                => date('Y-m-d H:i:s'),
                        ]);

                    if(!isset($request->a_shift_user_id)){
                        $request->a_shift_user_id = [];
                    }
                    // A SHIFT
                    MaterialProcessManpower::where('mat_proc_id', $request->material_process_id)
                        ->whereNotIn('manpower_id', $request->a_shift_user_id)
                        ->where('shift', 1)
                        ->increment('update_version', 1,
                            [
                                'status' => 2,
                                'updated_at' => date('Y-m-d H:i:s'),
                                'last_updated_by' => Auth::user()->id,
                            ]
                        );

                    MaterialProcessManpower::where('mat_proc_id', $request->material_process_id)
                        ->whereIn('manpower_id', $request->a_shift_user_id)
                        ->where('status', 2)
                        ->where('shift', 1)
                        ->increment('update_version', 1,
                            [
                                'status' => 1,
                                'updated_at' => date('Y-m-d H:i:s'),
                                'last_updated_by' => Auth::user()->id,
                            ]
                        );


                    if(isset($request->a_shift_user_id)){
                        for($index = 0; $index < count($request->a_shift_user_id); $index++) {
                            $mat_proc_manpowers = MaterialProcessManpower::where('mat_proc_id', $request->material_process_id)
                                ->where('manpower_id', $request->a_shift_user_id[$index])
                                ->where('shift', 1)
                                ->get();

                            if(count($mat_proc_manpowers) <= 0) {
                                MaterialProcessManpower::insert([
                                    'mat_proc_id' => $request->material_process_id,
                                    'manpower_id' => $request->a_shift_user_id[$index],
                                    'status' => 1,
                                    'shift' => 1,
                                    'created_by' => Auth::user()->id,
                                    'last_updated_by' => Auth::user()->id,
                                    'update_version' => 1,
                                    'updated_at' => date('Y-m-d H:i:s'),
                                    'created_at' => date('Y-m-d H:i:s')
                                ]);
                            }
                        }
                    }

                    if(!isset($request->b_shift_user_id)){
                        $request->b_shift_user_id = [];
                    }

                    // B SHIFT
                    MaterialProcessManpower::where('mat_proc_id', $request->material_process_id)
                        ->whereNotIn('manpower_id', $request->b_shift_user_id)
                        ->where('shift', 2)
                        ->increment('update_version', 1,
                            [
                                'status' => 2,
                                'updated_at' => date('Y-m-d H:i:s'),
                                'last_updated_by' => Auth::user()->id,
                            ]
                        );

                    MaterialProcessManpower::where('mat_proc_id', $request->material_process_id)
                        ->whereIn('manpower_id', $request->b_shift_user_id)
                        ->where('status', 2)
                        ->where('shift', 2)
                        ->increment('update_version', 1,
                            [
                                'status' => 1,
                                'updated_at' => date('Y-m-d H:i:s'),
                                'last_updated_by' => Auth::user()->id,
                            ]
                        );

                    if(isset($request->b_shift_user_id)){
                        for($index = 0; $index < count($request->b_shift_user_id); $index++) {
                            $mat_proc_manpowers = MaterialProcessManpower::where('mat_proc_id', $request->material_process_id)
                                ->where('manpower_id', $request->b_shift_user_id[$index])
                                ->where('shift', 2)
                                ->get();

                            if(count($mat_proc_manpowers) <= 0) {
                                MaterialProcessManpower::insert([
                                    'mat_proc_id' => $request->material_process_id,
                                    'manpower_id' => $request->b_shift_user_id[$index],
                                    'status' => 1,
                                    'shift' => 2,
                                    'created_by' => Auth::user()->id,
                                    'last_updated_by' => Auth::user()->id,
                                    'update_version' => 1,
                                    'updated_at' => date('Y-m-d H:i:s'),
                                    'created_at' => date('Y-m-d H:i:s')
                                ]);
                            }
                        }
                    }

                    $material_kitting_item = [];
                    $material_kitting_item_desc = [];

                    $sakidashi_item = [];
                    $sakidashi_item_desc = [];

                    $emboss_item = [];
                    $emboss_item_desc = [];

                    if(isset($request->material_kitting_item)){
                        for($index = 0; $index < count($request->material_kitting_item); $index++){
                            $material_kitting_item[] = explode('--', $request->material_kitting_item[$index])[0];
                            $material_kitting_item_desc[] = explode('--', $request->material_kitting_item[$index])[1];
                        }
                    }

                    if(isset($request->sakidashi_item)){
                        for($index = 0; $index < count($request->sakidashi_item); $index++){
                            $sakidashi_item[] = explode('--', $request->sakidashi_item[$index])[0];
                            $sakidashi_item_desc[] = explode('--', $request->sakidashi_item[$index])[1];
                        }
                    }

                    if(isset($request->emboss_item)){
                        for($index = 0; $index < count($request->emboss_item); $index++){
                            $emboss_item[] = explode('--', $request->emboss_item[$index])[0];
                            $emboss_item_desc[] = explode('--', $request->emboss_item[$index])[1];
                        }
                    }

                    // Material Kitting
                    // MaterialProcessMaterial::where('mat_proc_id', $request->material_process_id)
                    //     ->whereNotIn('item', $material_kitting_item)
                    //     ->whereNotIn('item_desc', $material_kitting_item_desc)
                    //     ->where('tbl_wbs', 1)
                    //     ->increment('update_version', 1,
                    //         [
                    //             'status' => 2,
                    //             'updated_at' => date('Y-m-d H:i:s'),
                    //             'last_updated_by' => Auth::user()->id,
                    //         ]
                    //     );

                    // MaterialProcessMaterial::where('mat_proc_id', $request->material_process_id)
                    //     ->whereIn('item', $material_kitting_item)
                    //     ->whereIn('item_desc', $material_kitting_item_desc)
                    //     ->where('tbl_wbs', 1)
                    //     ->increment('update_version', 1,
                    //         [
                    //             'status' => 1,
                    //             'updated_at' => date('Y-m-d H:i:s'),
                    //             'last_updated_by' => Auth::user()->id,
                    //         ]
                    //     );
                    MaterialProcessMaterial::where('mat_proc_id', $request->material_process_id)
                    ->delete();

                    for($index = 0; $index < count($material_kitting_item); $index++) {
                        // $mat_proc_matarial_kitting = MaterialProcessMaterial::where('mat_proc_id', $request->material_process_id)
                        //     ->where('item', $material_kitting_item[$index])
                        //     ->where('item_desc', $material_kitting_item_desc[$index])
                        //     ->where('tbl_wbs', 1)
                        //     ->get();

                        // if(count($mat_proc_matarial_kitting) <= 0) {
                            MaterialProcessMaterial::insert([
                                'mat_proc_id' => $request->material_process_id,
                                'item' => $material_kitting_item[$index],
                                'item_desc' => $material_kitting_item_desc[$index],
                                'tbl_wbs' => 1,
                                'status' => 1,
                                'created_by' => Auth::user()->id,
                                'last_updated_by' => Auth::user()->id,
                                'update_version' => 1,
                                'updated_at' => date('Y-m-d H:i:s'),
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
                        // }
                    }

                    // Sakidashi
                    // MaterialProcessMaterial::where('mat_proc_id', $request->material_process_id)
                    //     ->whereNotIn('item', $sakidashi_item)
                    //     ->whereNotIn('item_desc', $sakidashi_item_desc)
                    //     ->where('tbl_wbs', 2)
                    //     ->where('has_emboss', 0)
                    //     ->increment('update_version', 1,
                    //         [
                    //             'status' => 2,
                    //             'updated_at' => date('Y-m-d H:i:s'),
                    //             'last_updated_by' => Auth::user()->id,
                    //         ]
                    //     );
                    // if(count($sakidashi_item) > 0){

                    //     MaterialProcessMaterial::where('mat_proc_id', $request->material_process_id)
                    //         ->whereIn('item', $sakidashi_item)
                    //         ->whereIn('item_desc', $sakidashi_item_desc)
                    //         ->where('tbl_wbs', 2)
                    //         ->where('has_emboss', 0)
                    //         ->increment('update_version', 1,
                    //             [
                    //                 'status' => 1,
                    //                 'updated_at' => date('Y-m-d H:i:s'),
                    //                 'last_updated_by' => Auth::user()->id,
                    //             ]
                    //         );

                        for($index = 0; $index < count($sakidashi_item); $index++) {
                            // $mat_proc_sakidashi = MaterialProcessMaterial::where('mat_proc_id', $request->material_process_id)
                            //     ->where('item', $sakidashi_item[$index])
                            //     ->where('item_desc', $sakidashi_item_desc[$index])
                            //     ->where('tbl_wbs', 2)
                            //     ->where('has_emboss', 0)
                            //     ->get();

                            // if(count($mat_proc_sakidashi) <= 0) {
                                MaterialProcessMaterial::insert([
                                    'mat_proc_id' => $request->material_process_id,
                                    'item' => $sakidashi_item[$index],
                                    'item_desc' => $sakidashi_item_desc[$index],
                                    'tbl_wbs' => 2,
                                    'status' => 1,
                                    'has_emboss' => 0,
                                    'created_by' => Auth::user()->id,
                                    'last_updated_by' => Auth::user()->id,
                                    'update_version' => 1,
                                    'updated_at' => date('Y-m-d H:i:s'),
                                    'created_at' => date('Y-m-d H:i:s')
                                ]);
                            // }
                        }
                    // }

                    // Emboss
                    MaterialProcessMaterial::where('mat_proc_id', $request->material_process_id)
                        ->whereNotIn('item', $emboss_item)
                        ->whereNotIn('item_desc', $emboss_item_desc)
                        ->where('tbl_wbs', 2)
                        ->where('has_emboss', 1)
                        ->increment('update_version', 1,
                            [
                                'status' => 2,
                                'updated_at' => date('Y-m-d H:i:s'),
                                'last_updated_by' => Auth::user()->id,
                            ]
                        );
                    if(count($emboss_item) > 0){

                        MaterialProcessMaterial::where('mat_proc_id', $request->material_process_id)
                            ->whereIn('item', $emboss_item)
                            ->whereIn('item_desc', $emboss_item_desc)
                            ->where('tbl_wbs', 2)
                            ->where('has_emboss', 1)
                            ->increment('update_version', 1,
                                [
                                    'status' => 1,
                                    'updated_at' => date('Y-m-d H:i:s'),
                                    'last_updated_by' => Auth::user()->id,
                                ]
                            );

                        for($index = 0; $index < count($emboss_item); $index++) {
                            $mat_proc_sakidashi = MaterialProcessMaterial::where('mat_proc_id', $request->material_process_id)
                                ->where('item', $emboss_item[$index])
                                ->where('item_desc', $emboss_item_desc[$index])
                                ->where('tbl_wbs', 2)
                                ->where('has_emboss', 1)
                                ->get();

                            if(count($mat_proc_sakidashi) <= 0) {
                                MaterialProcessMaterial::insert([
                                    'mat_proc_id' => $request->material_process_id,
                                    'item' => $emboss_item[$index],
                                    'item_desc' => $emboss_item_desc[$index],
                                    'tbl_wbs' => 2,
                                    'status' => 1,
                                    'has_emboss' => 1,
                                    'created_by' => Auth::user()->id,
                                    'last_updated_by' => Auth::user()->id,
                                    'update_version' => 1,
                                    'updated_at' => date('Y-m-d H:i:s'),
                                    'created_at' => date('Y-m-d H:i:s')
                                ]);
                            }
                        }
                    }

                    // MACHINE
                    if(isset($request->machine_id)){
                        MaterialProcessMachine::where('mat_proc_id', $request->material_process_id)
                            ->whereNotIn('machine_id', $request->machine_id)
                            ->increment('update_version', 1,
                                [
                                    'status' => 2,
                                    'updated_at' => date('Y-m-d H:i:s'),
                                    'last_updated_by' => Auth::user()->id,
                                ]
                            );

                        MaterialProcessMachine::where('mat_proc_id', $request->material_process_id)
                            ->whereIn('machine_id', $request->machine_id)
                            ->where('status', 2)
                            ->increment('update_version', 1,
                                [
                                    'status' => 1,
                                    'updated_at' => date('Y-m-d H:i:s'),
                                    'last_updated_by' => Auth::user()->id,
                                ]
                            );

                        $error_counter = 0;
                        $trouble_counter = 0;

                        $machine_details;

                        $trouble_machines = [];

                        for($index = 0; $index < count($request->machine_id); $index++) {
                            $mat_proc_machines = MaterialProcessMachine::where('mat_proc_id', $request->material_process_id)
                                ->where('machine_id', $request->machine_id[$index])
                                ->get();

                            if(count($mat_proc_machines) <= 0) {
                                MaterialProcessMachine::insert([
                                    'mat_proc_id' => $request->material_process_id,
                                    'machine_id' => $request->machine_id[$index],
                                    'status' => 1,
                                    'created_by' => Auth::user()->id,
                                    'last_updated_by' => Auth::user()->id,
                                    'update_version' => 1,
                                    'updated_at' => date('Y-m-d H:i:s'),
                                    'created_at' => date('Y-m-d H:i:s')
                                ]);
                            }

                            $machine_info = Machine::where('status', 1)
                                ->with([
                                    'eedms_machine_info',
                                    'eedms_machine_info.trouble_info'
                                ])
                                ->where('id', $request->machine_id[$index])
                                ->first();

                            $machine_details[] = $machine_info;

                            if($machine_info != null){
                                if($machine_info->eedms_machine_info != null && $machine_info->eedms_machine_info->machine_status != 'Available'){
                                    $error_counter++;
                                }
                                else{
                                    if($machine_info->eedms_machine_info->trouble_info != null){
                                        $trouble_counter++;
                                        $trouble_machines[] = $machine_info->eedms_machine_info->machine_name;
                                    }
                                    else{
                                        $trouble_counter = 0;
                                        // $error_counter++;
                                    }
                                }
                            }
                            else{
                                $error_counter++;
                            }
                        }

                        if($error_counter > 0 || $trouble_counter > 0){
                            DB::rollback();
                            // throw $e;
                            return response()->json(['result' => 0, 'error_counter' => $error_counter, 'trouble_counter' => $trouble_counter, 'trouble_machines' => $trouble_machines, 'machine_details' => $machine_details]);
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
    }

    // Change Material Process Status
    public function change_material_process_stat(Request $request){
        date_default_timezone_set('Asia/Manila');
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        // return $data;

        $validator = Validator::make($data, [
            'material_process_id' => ['required', 'numeric'],
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
                DB::beginTransaction();

                try{
                    MaterialProcess::where('id', $request->material_process_id)
                    ->increment('update_version', 1,
                        [
                            'status' => $request->status,
                            'last_updated_by' => Auth::user()->id,
                            'updated_at' => date('Y-m-d H:i:s'),
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

    // Test Material Process Sort
    public function test_material_process_sort(Request $request){
        date_default_timezone_set('Asia/Manila');
        $material_process = MaterialProcess::where('device_id', 85)
                                ->where('status', 1)
                                ->orderBy('step', 'asc')
                                ->get();
        return response()->json(['material_process' => $material_process]);
    }
}
