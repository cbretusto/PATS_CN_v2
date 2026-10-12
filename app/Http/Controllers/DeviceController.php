<?php

namespace App\Http\Controllers;

use App\Model\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DataTables;
use Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CSVPackingMatrixImport;
use App\Imports\CSVPackingMatrixUpdaterImport;
use App\PPTSDevice;

class DeviceController extends Controller
{
    //View Devices
	public function view_devices(){
    	$devices = Device::all();

        return DataTables::of($devices)
            ->addColumn('label1', function($device){
                $result = "";

                if($device->status == 1){
                    $result .= '<span class="badge badge-pill badge-success">Active</span>';
                }
                else{
                    $result .= '<span class="badge badge-pill badge-danger">Inactive</span>';
                }

                return $result;
            })
            ->addColumn('action1', function($device){
                $result = '<center><div class="btn-group">
                          <button type="button" class="btn btn-primary dropdown-toggle btn-xs" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Action">
                            <i class="fa fa-cog"></i> 
                          </button>
                          <div class="dropdown-menu dropdown-menu-right">';
                if($device->status == 1){
                	$result .= '<button class="dropdown-item aEditDevice" type="button" device-id="' . $device->id . '" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalEditDevice" data-keyboard="false">Edit</button>';

                    $result .= '<button class="dropdown-item aChangeDeviceStat" type="button" device-id="' . $device->id . '" status="2" device-barcode="'.$device->barcode.'" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalChangeDeviceStat" data-keyboard="false">Deactivate</button>';

                    // $result .= '<button class="dropdown-item aShowDeviceDevProc" device-id="' . $device->id . '" device-name="' . $device->name . '" type="button" style="padding: 1px 1px; text-align: center;">Show Device Process</button>';

                    // $result .= '<button class="dropdown-item aGenDeviceBarcode" device-id="' . $device->barcode . '" type="button" style="padding: 1px 1px; text-align: center;">Generate Barcode</button>';

                    // Removed temporarily - undefined value from barcode
                    // $result .= '<button class="dropdown-item aGenerateBarcode" device-id="' . $device->id . '" barcode="' . $device->barcode . '" type="button" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-target="#modalGenBarcode">Generate Barcode</button>';
                }
                else{
                    $result .= '<button class="dropdown-item aChangeDeviceStat" type="button" style="padding: 1px 1px; text-align: center;" device-id="' . $device->id . '" status="1" device-barcode="'.$device->barcode.'" data-toggle="modal" data-target="#modalChangeDeviceStat" data-keyboard="false">Activate</button>';
                }

                $result .= '<input type="hidden" value="' . $device->id . '" class="form-control td_device_id">';
                $result .= '<input type="hidden" value="' . $device->name . '" class="form-control td_device_name">';
                            
                $result .= '</div>
                        </div></center>';

                return $result;
            })
            ->addColumn('raw_product_type', function($device){
                $result = "";

                if($device->product_type == 1){
                    $result .= 'HUAWEI - PATS CN';
                }
                else if($device->product_type == 2){
                    $result .= 'HUAWEI - CN PTS';
                }
                else if($device->product_type == 3){
                    $result .= 'OTHERS - PATS CN';
                }
                else if($device->product_type == 4){
                    $result .= 'OTHERS - CN PTS';
                }
                else{
                    $result .= 'NOT SET';
                }

                return $result;
            })
            ->rawColumns(['label1', 'action1'])
            ->make(true);
    }

    // Add Device
    public function add_device(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            // 'name' => ['required', 'string', 'max:255', 'unique:devices'],
            'name' => ['required', 'string', 'max:255'],
            'barcode' => ['required', 'string', 'max:255', 'unique:devices'],
            'mrp_no' => ['required', 'string', 'max:255'],
            'boxing' => ['numeric', 'required'],
            'ship_boxing' => ['numeric', 'required'],
            'product_type' => ['numeric', 'required'],
            'packaging_type' => ['required'],
            'ul_requirement' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();

            try{
                Device::insert([
                    'name' => $request->name,
                    'huawei_p_n' => $request->huawei_p_n,
                    'lot_no_machine_code' => $request->lot_no_machine_code,
                    'mrp_no' => $request->mrp_no,
                    'boxing' => $request->boxing,
                    'ship_boxing' => $request->ship_boxing,
                    'product_type' => $request->product_type,
                    'status' => 1,
                    'barcode' => $request->barcode,
                    'created_by' => Auth::user()->id,
                    'last_updated_by' => Auth::user()->id,
                    'update_version' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                $ppts_prod_type = 0;
                if($request->product_type == 1){ // Huawei pats
                    $ppts_prod_type = 0;
                }
                else if($request->product_type == 2){// huawei others
                    $ppts_prod_type = 1;

                }
                else{ // others
                    $ppts_prod_type = 2;
                }
                PPTSDevice::insert([
                    'name'           => $request->name,
                    'boxing'         => $request->boxing,
                    'ship_boxing'    => $request->ship_boxing,
                    'ul_requirement' => $request->ul_requirement,
                    'status'         => 1,
                    'customer_pn'    => $request->huawei_p_n,
                    'barcode'        => $request->barcode,
                    'type'           => $request->packaging_type,
                    'product_type'   => $ppts_prod_type,
                    'created_by'     => Auth::user()->username,
                    'updated_by'     => Auth::user()->username,
                    'update_version' => 1,
                    'updated_at'     => date('Y-m-d H:i:s'),
                    'created_at'     => date('Y-m-d H:i:s')
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

    // Get Device By Id
    public function get_device_by_id(Request $request){
        $device = Device::with(['ppts_device_details'])
        ->where('id', $request->device_id)->get();
        return response()->json(['device' => $device]);
    }

    // Edit Device
    public function edit_device(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            // 'name' => 'required|string|max:255|unique:devices,name,'. $request->device_id,
            'name' => 'required|string|max:255',
            'barcode' => 'required|string|max:255|unique:devices,barcode,'. $request->device_id,
            'mrp_no' => ['required', 'string', 'max:255'],
            'boxing' => ['numeric', 'required'],
            'ship_boxing' => ['numeric', 'required'],
            'product_type' => ['numeric', 'required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();

            try{
                Device::where('id', $request->device_id)
                ->increment('update_version', 1,
                [
                    'name' => $request->name,
                    'huawei_p_n' => $request->huawei_p_n,
                    'lot_no_machine_code' => $request->lot_no_machine_code,
                    'barcode' => $request->barcode,
                    'mrp_no' => $request->mrp_no,
                    'boxing' => $request->boxing,
                    'ship_boxing' => $request->ship_boxing,
                    'product_type' => $request->product_type,
                    'last_updated_by' => Auth::user()->id,
                    'update_version' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                $ppts_prod_type = 0;
                if($request->product_type == 1){ // Huawei pats
                    $ppts_prod_type = 0;
                }
                else if($request->product_type == 2){// huawei others
                    $ppts_prod_type = 1;

                }
                else{ // others
                    $ppts_prod_type = 2;
                }
                PPTSDevice::where('barcode', $request->barcode)
                ->increment('update_version', 1,
                [
                    'name'           => $request->name,
                    'boxing'         => $request->boxing,
                    'ship_boxing'    => $request->ship_boxing,
                    'ul_requirement' => $request->ul_requirement,
                    'status'         => 1,
                    'customer_pn'    => $request->huawei_p_n,
                    'type'           => $request->packaging_type,
                    'product_type'   => $ppts_prod_type,
                    'updated_by'     => Auth::user()->username,
                    'created_at'     => date('Y-m-d H:i:s')
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

    // Change Device Status
    public function change_device_stat(Request $request){        
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $validator = Validator::make($data, [
            'device_id' => 'required',
            'status' => 'required',
            'barcode' => 'required',
        ]);

        if($validator->passes()){
            try{
                Device::where('id', $request->device_id)
                    ->increment('update_version', 1, 
                        [
                            'status' => $request->status,
                            'last_updated_by' => Auth::user()->id,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]
                    );

                PPTSDevice::where('barcode', $request->barcode)
                ->increment('update_version', 1, 
                        [
                            'status' => $request->status,
                            'updated_by' => Auth::user()->username,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]
                );
                DB::commit();
                return response()->json(['result' => "1"]);
            }
            catch(Exception $e) {
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

    public function import_packing_matrix(Request $request)
    {

        $collections = Excel::toCollection(new CSVPackingMatrixImport, request()->file('import_file'));
        DB::beginTransaction();
        try{
            for($index = 0; $index < count($collections[0]); $index++){
                Device::insert([
                    'name' => $collections[0][$index][1],
                    'mrp_no' => $collections[0][$index][4],
                    'boxing' => $collections[0][$index][5],
                    'ship_boxing' => $collections[0][$index][6],
                    'barcode' => $collections[0][$index][9],
                    'lot_no_machine_code' => $collections[0][$index][3],
                    'product_type' => $collections[0][$index][8],
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

    public function import_packing_matrix_updater(Request $request)
    {

        $collections = Excel::toCollection(new CSVPackingMatrixUpdaterImport, request()->file('import_file'));

        // return $collections;
        DB::beginTransaction();
        try{

            $inserted_barcode = [];
            $updated_barcode = [];
            $failed_barcode = [];

            for($index = 1; $index < count($collections[0]); $index++){
                $id = $collections[0][$index][0];
                $name = $collections[0][$index][1];
                $barcode = $collections[0][$index][2];
                $huawei_pn = $collections[0][$index][3];
                $lot_no_machine_code = $collections[0][$index][4];
                $mrp_no = $collections[0][$index][5];
                $boxing = $collections[0][$index][6];
                $ship_boxing = $collections[0][$index][7];
                $product_type = $collections[0][$index][8];
                $c3_label = $collections[0][$index][9];

                if($id == null || $id == ""){
                    $device_details = Device::where('barcode', $barcode)->get();
                    if(count($device_details) <= 0){
                        $query_result = Device::insert([
                            'name' => $name,
                            'mrp_no' => $mrp_no,
                            'boxing' => $boxing,
                            'ship_boxing' => $ship_boxing,
                            'barcode' => $barcode,
                            'lot_no_machine_code' => $lot_no_machine_code,
                            'product_type' => $product_type,
                            'c3_label' => $c3_label,
                            'status' => 1,
                            'created_by' => Auth::user()->id,
                            'last_updated_by' => Auth::user()->id,
                            'update_version' => 1,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                        if($query_result){
                            $inserted_barcode[] = $barcode;
                        }
                        else{
                            $failed_barcode[] = $barcode;
                        }
                    }
                    else{
                        $query_result = Device::where('barcode', $barcode)->increment('update_version', 1,
                        [
                            'name' => $name,
                            'mrp_no' => $mrp_no,
                            'boxing' => $boxing,
                            'ship_boxing' => $ship_boxing,
                            'lot_no_machine_code' => $lot_no_machine_code,
                            'product_type' => $product_type,
                            'c3_label' => $c3_label,
                            'last_updated_by' => Auth::user()->id,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                        if($query_result){
                            $updated_barcode[] = $barcode;
                        }
                        else{
                            $failed_barcode[] = $barcode;
                        }
                    }

                }
                else{
                    $query_result = Device::where('id', $id)->increment('update_version', 1,
                        [
                            'name' => $name,
                            'mrp_no' => $mrp_no,
                            'boxing' => $boxing,
                            'ship_boxing' => $ship_boxing,
                            'lot_no_machine_code' => $lot_no_machine_code,
                            'product_type' => $product_type,
                            'c3_label' => $c3_label,
                            'last_updated_by' => Auth::user()->id,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                    if($query_result){
                        $updated_barcode[] = $barcode;
                    }
                    else{
                        $failed_barcode[] = $barcode;
                    }
                }

                // $device_details1 = Device::where('id', $id)->get();

                // if(count($device_details1) > 0){

                // }
                // else{
                // }

            }
            DB::commit();
            // DB::rollback();
            return response()->json(['result' => "1", 'inserted_barcode' => $inserted_barcode, 'updated_barcode' => $updated_barcode, 'failed_barcode' => $failed_barcode]);
        }    
        catch(\Exception $e) {
            DB::rollback();
            return response()->json(['result' => $e]);
        }
    }

    // public function import_packing_matrix_updater(Request $request)
    // {

    //     $collections = Excel::toCollection(new CSVPackingMatrixUpdaterImport, request()->file('import_file'));

    //     // return $collections;
    //     DB::beginTransaction();
    //     try{

    //         for($index = 1; $index < count($collections[0]); $index++){
    //             $device_details = Device::where('barcode', $collections[0][$index][9])->get();
    //             if(count($device_details) <= 0){
    //                 Device::insert([
    //                     'name' => $collections[0][$index][1],
    //                     'mrp_no' => $collections[0][$index][4],
    //                     'boxing' => $collections[0][$index][5],
    //                     'ship_boxing' => $collections[0][$index][6],
    //                     'barcode' => $collections[0][$index][9],
    //                     'lot_no_machine_code' => $collections[0][$index][3],
    //                     'product_type' => $collections[0][$index][8],
    //                     'status' => 1,
    //                     'created_by' => Auth::user()->id,
    //                     'last_updated_by' => Auth::user()->id,
    //                     'update_version' => 1,
    //                     'updated_at' => date('Y-m-d H:i:s'),
    //                     'created_at' => date('Y-m-d H:i:s')
    //                 ]);
    //             }
    //             else{
    //                 Device::where('barcode', $collections[0][$index][9])->increment('update_version', 1,
    //                 [
    //                     'name' => $collections[0][$index][1],
    //                     'mrp_no' => $collections[0][$index][4],
    //                     'boxing' => $collections[0][$index][5],
    //                     'ship_boxing' => $collections[0][$index][6],
    //                     'lot_no_machine_code' => $collections[0][$index][3],
    //                     'product_type' => $collections[0][$index][8],
    //                     'last_updated_by' => Auth::user()->id,
    //                     'updated_at' => date('Y-m-d H:i:s'),
    //                 ]);
    //             }
    //         }
    //         DB::commit();

    //         return response()->json(['result' => "1"]);
    //     }    
    //     catch(\Exception $e) {
    //         DB::rollback();
    //         return response()->json(['result' => $e]);
    //     }
    // }

}
