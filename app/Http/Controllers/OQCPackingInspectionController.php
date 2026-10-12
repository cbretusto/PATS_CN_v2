<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DataTables;
use Auth;

//models
use App\Model\OQCPackingInspection;
use App\Model\ProdPrelimInspection;
use App\Model\oqcVIR;
use App\oqcVIR1;
use App\Model\oqcLotApp;
use App\Model\Device;
use App\User;
use App\Model\RapidActiveDocs;

class OQCPackingInspectionController extends Controller
{
    public function view_packing_codes(Request $request)
    {
        $packing_codes = OQCPackingInspection::where('po_num',$request->po_num)->get();

        return DataTables::of($packing_codes)
        ->addColumn('pack_code', function($packing_code){
            return $packing_code->pack_code_no;
        })
        ->addColumn('box_qty', function($packing_code) use($request){

                $lot_count = 0;

                // $lotcounter = oqcVIR::with('oqclotapp_details')->whereHas('oqclotapp_details',function($query) use($request)
                $lotcounter = oqcVIR1::with('oqclotapp_details')->whereHas('oqclotapp_details',function($query) use($request)
                {
                    $query->where('po_no',$request->po_num);

                })->where('packing_code',$packing_code->pack_code_no)->get();

                for($i = 0; $i < count($lotcounter); $i++)
                {
                    $lot_count += $lotcounter[$i]->oqclotapp_details->output_qty;
                }   
                
                return $lot_count;

        })
        ->addColumn('status', function($packing_code){
            
            $result = '';

            switch($packing_code->status)
            {
                case 1:
                {   
                    $result = '<span class="badge badge-pill badge-info">Initial Inspection Done</span>';

                    $result .= '<br><span class="badge badge-pill badge-primary">For Printing of C3 Labels / Inspection Fill-in</span>';

                    break;
                }
                case 2:
                {   
                    $result = '<span class="badge badge-pill badge-success">Packing Code Accept</span>';

                    $result .= '<br><span class="badge badge-pill badge-info">Add Shipping Details</span>';

                    break;
                }
                case 3:
                {   
                    $result = '<span class="badge badge-pill badge-danger">Packing Code Reject</span>';

                    break;
                }
                case 4:
                {
                    $result = '<span class="badge badge-pill badge-success">Packing Code Accept</span>';

                    $result .= '<br><span class="badge badge-pill badge-success">Shipping Details Added</span>';

                    break;
                }
                default:
                {
                    $result = '<span class="badge badge-pill badge-info">Loading Error. Refresh Your Page or Contact ISS at local 205!</span>';

                    break;
                }
            }

            return $result;

        })
        ->addColumn('action', function($packing_code) use($request){

            $lot_count = 0;

                // $lotcounter = oqcVIR::with('oqclotapp_details')->whereHas('oqclotapp_details',function($query) use($request)
                $lotcounter = oqcVIR1::with('oqclotapp_details')->whereHas('oqclotapp_details',function($query) use($request)
                {
                    $query->where('po_no',$request->po_num);

                })->where('packing_code',$packing_code->pack_code_no)->get();

                for($i = 0; $i < count($lotcounter); $i++)
                {
                    $lot_count += $lotcounter[$i]->oqclotapp_details->output_qty;
                }   
                

            //switch here
            
            $result = '';

            switch($packing_code->status)
            {
                case 1: //C3 Scanning Done, Waiting for C3 Stickers and Inspection
                {   
                    $result = '<button type="button" class="px-2 py-1 btn btn-sm btn-primary btn_open_checkpoints" data-toggle="modal" data-target="#modalCheckpointFillin" title="Add Inspection Results" po-num='.$packing_code->po_num.' pack-code='.$packing_code->pack_code_no.' lot-count='.$lot_count.'><i class="fa fa-check-square fa-sm"></i></button>';

                    $result .= ' <button type="button" class="px-2 py-1 btn btn-sm btn-info btn_open_history" data-toggle="modal" data-target="#mdl_oqc_packing_history" title="View Inspection History" po-num='.$packing_code->po_num.' pack-code='.$packing_code->pack_code_no.' lot-count='.$lot_count.'><i class="fa fa-history fa-sm"></i></button>';

                    break;
                }
                case 2: //Inspection Done, For Adding of Shipping Details
                {   
                    $result = '<button type="button" class="px-2 py-1 btn btn-sm btn-info btn_open_shipping" data-toggle="modal" data-target="#modalShippingDetails" title="Add Shipping Details" po-num='.$packing_code->po_num.' pack-code='.$packing_code->pack_code_no.' lot-count='.$lot_count.'><i class="fa fa-plane fa-sm"></i></button>';

                    $result .= ' <button type="button" class="px-2 py-1 btn btn-sm btn-info btn_open_history" data-toggle="modal" data-target="#mdl_oqc_packing_history" title="View Inspection History" po-num='.$packing_code->po_num.' pack-code='.$packing_code->pack_code_no.' lot-count='.$lot_count.'><i class="fa fa-history fa-sm"></i></button>';

                    break;
                }
                case 3: //Inspection Done, NG Result
                {   
                    $result = '';

                    $result .= '<button type="button" class="px-2 py-1 btn btn-sm btn-info btn_open_history" data-toggle="modal" data-target="#mdl_oqc_packing_history" title="View Inspection History" po-num='.$packing_code->po_num.' pack-code='.$packing_code->pack_code_no.' lot-count='.$lot_count.'><i class="fa fa-history fa-sm"></i></button>';

                    break;
                }
                case 4: //Inspection Done, Shipping Details Done
                {
                    $result = '';

                    $result .= '<button type="button" class="px-2 py-1 btn btn-sm btn-info btn_open_history" data-toggle="modal" data-target="#mdl_oqc_packing_history" title="View Inspection History" po-num='.$packing_code->po_num.' pack-code='.$packing_code->pack_code_no.' lot-count='.$lot_count.'><i class="fa fa-history fa-sm"></i></button>';

                    break;
                }
                default:
                {
                    $result = '';

                    break;
                }
            }

            return $result;
        })
        ->rawColumns(['action','status'])
        ->make(true);
    }

    public function view_inspection_history(Request $request)
    {
        $histories = OQCPackingInspection::with(['employee_id','employee'])->where('po_num',$request->po_num)->where('pack_code_no',$request->pack_code)->orderBy('created_at','desc')->get();

        return DataTables::of($histories)
        ->addColumn('inspection_datetime', function($history){
            return $history->created_at;
        })
        ->addColumn('oqc_name', function($history){

           $user = User::where('id', $history->emp_id)->orWhere('employee_id', $history->emp_id)->orderBy('created_at','asc')->first();

           return $user->name;
        })
        ->addColumn('status', function($history){
            $result = '';

            switch($history->status)
            {
                case 1:
                {   
                    $result = '<span class="badge badge-pill badge-info">Initial Inspection Done</span>';
                    break;
                }
                case 2:
                {
                    $result = '<span class="badge badge-pill badge-success">Packing QC - ACCEPT</span>';
                    break;
                }
                case 3:
                {
                    $result = '<span class="badge badge-pill badge-danger">Packing QC - REJECT</span>';
                    break;
                }
                case 4:
                {
                    $result = '<span class="badge badge-pill badge-success">Packing QC - ACCEPT<br>Shipping Details - OK</span>';
                    break;
                }
                default:
                {
                    $result = '---';
                    break;
                }
            }

            return $result;
        })
        ->addColumn('document_compliance', function($history){

             $result = '';

            switch($history->document_compliance)
            {
                case 1:
                {
                    $result = '<span class="badge badge-pill badge-success">YES</span>';
                    break;
                }
                case 2:
                {
                    $result = '<span class="badge badge-pill badge-danger">NO</span>';
                    break;
                }
                case 3:
                {
                    $result = '<span class="badge badge-pill badge-secondary">N/A</span>';
                    break;
                }
                default:
                {
                    $result = '---';
                    break;
                }
            }

            return $result;
            
        })
        ->addColumn('accessory_requirement', function($history){

             $result = '';

            switch($history->accessory_requirement)
            {
                case 1:
                {
                    $result = '<span class="badge badge-pill badge-success">YES</span>';
                    break;
                }
                case 2:
                {
                    $result = '<span class="badge badge-pill badge-danger">NO</span>';
                    break;
                }
                case 3:
                {
                    $result = '<span class="badge badge-pill badge-secondary">N/A</span>';
                    break;
                }
                default:
                {
                    $result = '---';
                    break;
                }
            }

            return $result;
            
        })
        ->addColumn('coc_requirement', function($history){

             $result = '';

            switch($history->coc_requirement)
            {
                case 1:
                {
                    $result = '<span class="badge badge-pill badge-success">YES</span>';
                    break;
                }
                case 2:
                {
                    $result = '<span class="badge badge-pill badge-danger">NO</span>';
                    break;
                }
                case 3:
                {
                    $result = '<span class="badge badge-pill badge-secondary">N/A</span>';
                    break;
                }
                default:
                {
                    $result = '---';
                    break;
                }
            }

            return $result;
            
        })
        ->addColumn('shipping_date', function($history){
            return $history->shipping_date;
        })
        ->addColumn('shipping_destination', function($history){
            return $history->shipping_destination;
        })
        ->addColumn('shipping_remarks', function($history){
            return $history->shipping_remarks;
        })
        ->rawColumns(['status','document_compliance','accessory_requirement','coc_requirement'])
        ->make(true);
    }

    public function submit_initial_inspection(Request $request)
    {
        date_default_timezone_set('Asia/Manila');

        $user = User::with([
                'oqc_stamps' => function($query) {
                    $query->where('status', 1);
                    $query->orderBy('id', 'desc');    
                }
            ])
            ->withCount([
                'oqc_stamps' => function($query) {
                    $query->where('status', 1);
                    $query->orderBy('id', 'desc');    
                }
            ])
            ->having('oqc_stamps_count', '>', 0)
            ->where('employee_id', $request->employee_number_scanner)->get();

        if($user->count() > 0)
        {
            DB::beginTransaction();

            try
            {
                OQCPackingInspection::insert([
                    'po_num' => $request->add_po_num,
                    'pack_code_no' => $request->add_pack_code,
                    'status' => 1,
                    'emp_id' => $request->employee_number_scanner,
                    'created_at'   => date('Y-m-d H:i:s'),
                    'updated_at'   => date('Y-m-d H:i:s') 
                ]);

                DB::commit();

                return response()->json(['result' => 1]);            
            }
            catch(\Exception $e) {
                    DB::rollback();
                    // throw $e;
                    return response()->json(['result' => $e]);
            }
        }
        else
        {
            return response()->json(['result' => 2]);  
        }
    }

    public function submit_inspection_fillin(Request $request)
    {
            $user = User::with([
                'oqc_stamps' => function($query) {
                    $query->where('status', 1);
                    $query->orderBy('id', 'desc');    
                }
            ])
            ->withCount([
                'oqc_stamps' => function($query) {
                    $query->where('status', 1);
                    $query->orderBy('id', 'desc');    
                }
            ])
            ->having('oqc_stamps_count', '>', 0)
            ->where('employee_id', $request->fillin_number_scanner)->get();

        date_default_timezone_set('Asia/Manila');

        if($user->count() > 0)
        {
            $user_check = OQCPackingInspection::with(['employee'])->where('po_num', $request->check_po_num)->where('pack_code_no',$request->check_pack_code)->first();

            if($user_check != null)
            {
                if($user_check->emp_id == $request->fillin_number_scanner)
                {
                    DB::beginTransaction();

                    try
                    {
                        OQCPackingInspection::where('po_num', $request->check_po_num)->where('pack_code_no',$request->check_pack_code)->update([

                            'document_compliance' => $request->name_document_compliance,
                            'accessory_requirement' => $request->name_accessory_req,
                            'coc_requirement' => $request->name_coc_req,
                            'status' => $request->name_qc_judgement,
                            'emp_id' => $request->fillin_number_scanner,
                            'updated_at'   => date('Y-m-d H:i:s') 
                        ]);

                        DB::commit();

                        return response()->json(['result' => 1]);        
                    }

                    catch(\Exception $e) {
                            DB::rollback();
                            // throw $e;
                            return response()->json(['result' => $e]);
                    }
                }
                else
                {
                    return response()->json(['result' => 3, 'oqc_inspector' => $user_check->employee->name]);  
                }
            }
            else
            {
                return response()->json(['result' => 2]);  
            }
        }
        else
        {
            return response()->json(['result' => 2]);  
        }
    }

    public function submit_shipping_details(Request $request)
    {
        date_default_timezone_set('Asia/Manila');

        $user = User::with([
                'oqc_stamps' => function($query) {
                    $query->where('status', 1);
                    $query->orderBy('id', 'desc');    
                }
            ])
            ->withCount([
                'oqc_stamps' => function($query) {
                    $query->where('status', 1);
                    $query->orderBy('id', 'desc');    
                }
            ])
            ->having('oqc_stamps_count', '>', 0)
            ->where('employee_id', $request->shipping_number_scanner)->get();

        if($user->count() > 0)
        {
            DB::beginTransaction();

            try
            {
                OQCPackingInspection::where('po_num',$request->ship_po_num)->where('pack_code_no',$request->ship_pack_code)->update([

                    'shipping_date' => $request->shipping_date,
                    'shipping_destination' => $request->shipping_destination,
                    'shipping_remarks' => $request->shipping_remarks,
                    'status' => 4,
                    'updated_at'   => date('Y-m-d H:i:s')
                ]);

                DB::commit();

                return response()->json(['result' => 1]);            
            }
            catch(\Exception $e) {
                    DB::rollback();
                    // throw $e;
                    return response()->json(['result' => $e]);
            }
        }
        else
        {
            return response()->json(['result' => 2]);  
        }


    }


    public function check_pack_code_exists(Request $request)
    {
    	$result = '';
        $lot_count = 0;

    	// $pack_code = oqcVIR::with(['user_details','oqclotapp_details'])
    	$pack_code = oqcVIR1::with(['user_details','oqclotapp_details'])
    	->whereHas('oqclotapp_details',function($query) use($request){
    		$query->where('po_no',$request->po_num);
    	})
    	->where('packing_code',$request->pack_code)->select(DB::raw('distinct fkid_oqclotapp'))->get();

        //check if exists
    	if(count($pack_code) > 0)
        {
                $prod = ProdPrelimInspection::where('po_num',$request->po_num)->where('pack_code_no',$request->pack_code)->get();;

                if(count($prod) > 0)
                {
                        $oqc = OQCPackingInspection::where('po_num',$request->po_num)->where('pack_code_no',$request->pack_code)->get();

                        if(count($oqc) > 0)
                        {
                            $result = '2';
                        }
                        else
                        {
                            $result = '1';

                            for($i = 0; $i < count($pack_code); $i++)
                            {
                                // $lotcounter = oqcVIR::with('oqclotapp_details')->where('fkid_oqclotapp',$pack_code[$i]->fkid_oqclotapp)->get();
                                $lotcounter = oqcVIR1::with('oqclotapp_details')->where('fkid_oqclotapp',$pack_code[$i]->fkid_oqclotapp)->get();
                                
                                if(count($lotcounter) > 0)
                                {
                                    $lot_count += $lotcounter[0]->oqclotapp_details->output_qty;
                                }
                            }
                        }
                }      
                else
                {
                    $result = '3';
                }
        }
        else
        {
            $result = '4';
        }
        

        return response(['result' => $result, 'lotcount' => $lot_count]);
    }

    public function retrieve_packcode_data(Request $request)
    {
        // $reellots = oqcVIR::with(['user_details','oqclotapp_details'])->whereHas('oqclotapp_details', function($query) use($request){
        $reellots = oqcVIR1::with(['user_details','oqclotapp_details'])->whereHas('oqclotapp_details', function($query) use($request){
            // $query->where('po_no','<>','x');
            $query->where('po_no',$request->po_num);
            // })->where('packing_code',$request->pack_code)->select(DB::raw('distinct fkid_oqclotapp'))->orderBy('fkid_oqclotapp','desc')->get();
            })->where('packing_code',$request->pack_code)->where('status', 1)->select(DB::raw('distinct fkid_oqclotapp'))->orderBy('fkid_oqclotapp','desc')->get();

        $device = Device::where('name',$request->device)->first();


        return response()->json(['reellots' => $reellots, 'device' => $device]);
    }
}
