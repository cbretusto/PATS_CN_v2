<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Model\oqcVIR;
use App\Model\oqcLotApp;
use App\Model\OQCDBM;
use App\Model\MaterialIssuanceSubSystem;
use App\User;
use App\Model\AssemblyLine;
use App\Model\Device;
use App\Model\Series;

use DataTables;
use QrCode;

class OQCVIRController extends Controller
{

    // Get added lotapp data
    public function get_oqclot_details(Request $request){
        $oqc_lotapp_details = oqcLotApp::with([
            'oqcvir_details' => function($query){
                $query->orderBy('submission','DESC')
                    ->orderBy('status','DESC');
            },
            'wbs_kitting',
            'wbs_kitting.device_info'

        ])
            ->where('po_no',$request['po_no'])
            ->where('status', 2)
            // ->orderBy('submission','DESC')
            // ->get();

            ->distinct('fkid_runcard')
            // ->get()->take(1);
             // ->groupBy('fkid_runcard')
            ->get();
        return DataTables::of($oqc_lotapp_details)
        
        ->addColumn('status_raw', function($oqc_lotapp){
            $result = "";

            if ( $oqc_lotapp->submission == 2 || $oqc_lotapp->submission == 3){
                if ($oqc_lotapp->oqcvir_details != null){
                    switch ($oqc_lotapp->oqcvir_details->status) {
                        case 1:
                            $result ='<span class="badge badge-pill badge-success">Lot Accepted</span>';
                            break;
                        case 2:
                            $result ='<span class="badge badge-pill badge-danger">Lot Rejected</span>';
                            break;
                        case 3:
                            $result ='<span class="badge badge-pill badge-warning">Pending - On-going Inspection</span>';
                            break;
                    }

                }else{
                    $result ='<span class="badge badge-pill badge-danger">Rejected - For Inspection </span>';
                }
            } else {
                if ($oqc_lotapp->oqcvir_details != null){
                    switch ($oqc_lotapp->oqcvir_details->status) {
                        case 1:
                            $result ='<span class="badge badge-pill badge-success">Lot Accepted</span>';
                            break;
                        case 2:
                            $result ='<span class="badge badge-pill badge-danger">Lot Rejected</span>';
                            break;
                        case 3:
                            $result ='<span class="badge badge-pill badge-warning">Pending - On-going Inspection</span>';
                            break;
                    }

                }else{
                    $result ='<span class="badge badge-pill badge-info">For Inspection</span>';
                }

            }

            return $result;
        })

        ->addColumn('subm_raw', function($oqc_lotapp){
            $result = null;

            if( $oqc_lotapp ) {
                switch ($oqc_lotapp->submission) {
                    case 1:
                        $result ='<span class="badge badge-pill s1 badge-success">1st Sub</span>';
                        break;
                    case 2:
                        $result ='<span class="badge badge-pill s2 badge-warning">2nd Sub</span>';
                        break;
                    case 3:
                        $result ='<span class="badge badge-pill s3 badge-danger">3rd Sub</span>';
                        break;
                }
            }

           /* if( $oqc_lotapp->submission == 1){
                if ($oqc_lotapp->oqcvir_details != null){
                    switch ($oqc_lotapp->oqcvir_details->submission) {
                        case 1:
                            $result ='<span class="badge badge-pill s1 badge-success">1st Sub</span>';
                            break;
                        case 2:
                            $result ='<span class="badge badge-pill s2 badge-warning">2nd Sub</span>';
                            break;
                        case 3:
                            $result ='<span class="badge badge-pill s3 badge-danger">3rd Sub</span>';
                            break;
                    }

                }else{
                    // $result ='<span class="badge badge-pill s1 badge-success">1st Sub</span>';
                    $result ='---';
                }
            }else if( $oqc_lotapp->submission == 2){
                if ($oqc_lotapp->oqcvir_details != null){
                    switch ($oqc_lotapp->oqcvir_details->submission) {
                        case 1:
                            $result ='<span class="badge badge-pill s1 badge-success">1st Sub</span>';
                            break;
                        case 2:
                            $result ='<span class="badge badge-pill s2 badge-warning">2nd Sub</span>';
                            break;
                        case 3:
                            $result ='<span class="badge badge-pill s3 badge-danger">3rd Sub</span>';
                            break;
                    }

                }else{
                    $result ='<span class="badge badge-pill s1 badge-success">1st Sub</span>';
                }
            }else{
                if ($oqc_lotapp->oqcvir_details != null){
                    switch ($oqc_lotapp->oqcvir_details->submission) {
                        case 1:
                            $result ='<span class="badge badge-pill s1 badge-success">1st Sub</span>';
                            break;
                        case 2:
                            $result ='<span class="badge badge-pill s2 badge-warning">2nd Sub</span>';
                            break;
                        case 3:
                            $result ='<span class="badge badge-pill s3 badge-danger">3rd Sub</span>';
                            break;
                    }

                }else{
                    $result ='<span class="badge badge-pill s2 badge-warning">2nd Sub</span>';
                }
            }*/

            return $result;
        })

        ->addColumn('sub_lot_raw', function($oqc_lotapp)use($oqc_lotapp_details){
            $result = "";     
                
            $lot_batch_no = ($oqc_lotapp->lot_batch_no);
            $newstring = substr($lot_batch_no, -3);

            $device_name = ($oqc_lotapp->wbs_kitting->device_name);
            $devices = Device::where('name', $device_name)->get()->take(1);
            
            $ctr = 1;
            if ( $devices ){

                $i_total = 1000;
                $q = $devices[0]->ship_boxing / $devices[0]->boxing;

                for($i=$q;$i<$i_total; $i=$i+$q){
                    if( $newstring <= $i ){
                        break;
                    }
                    $ctr++;
                }   

            }            

            return $ctr;
        })

        ->addColumn('packing_code_raw', function($oqc_lotapp){
            $result = "";

                if ( $oqc_lotapp->po_no == '450214397600010' ){
                    if ($oqc_lotapp->oqcvir_details != null && strlen($oqc_lotapp->oqcvir_details->packing_code)==8 ){

                        $new_pc = ($oqc_lotapp->oqcvir_details->packing_code);
                        $new_pc = substr($new_pc,0, 5);

                        $num = ($oqc_lotapp->oqcvir_details->packing_code);
                        $num2 = substr($num, -3);
                        $num3 = (int)$num2+2;

                        $result = $new_pc.str_pad($num3, 3, '0', STR_PAD_LEFT);

                    }else{
                        $result ='---';
                    }
                }else{
                    if ($oqc_lotapp->oqcvir_details != null){
                        $result = $oqc_lotapp->oqcvir_details->packing_code;
                    }else{
                        $result ='---';
                    }
                }
                

            return $result;
        })

        ->addColumn('action', function($oqc_lotapp){
            $result = "";

            $result.='<center><button type="button" class="px-2 py-1 btn btn-sm btn-success btn_update_vir" id="btn_update" data-toggle="modal" value="'.$oqc_lotapp['id'].'" title="View/Update Details"><i class="fa fa-edit fa-sm"></i></button>';
            
            $result.='  <button type="button" class="px-2 py-1 btn btn-sm btn-info btn_view_lotApp" id="btn_view" data-toggle="modal" value="'.$oqc_lotapp['lot_batch_no'].'" title="View Lot App Details"><i class="fa fa-eye fa-sm"></i></button>';

            if ($oqc_lotapp->oqcvir_details != null){
                if ( $oqc_lotapp->oqcvir_details->packing_code == null ){
                  $result.=' <button type="button" class="px-2 py-1 btn btn-sm btn-secondary btn_print_lot" id="btn_print" data-toggle="modal" title="No OQC Inspection result Details" disabled><i class="fa fa-print fa-sm"></i></button></center>';
                }else{
                  $result.=' <button type="button" class="px-2 py-1 btn btn-sm btn-warning btn_print_lot" id="btn_print" data-toggle="modal" value="'.$oqc_lotapp['id'].'" title="Print Barcode Packing Code"><i class="fa fa-print fa-sm"></i></button>';
                }
            }else{
                  $result.=' <button type="button" class="px-2 py-1 btn btn-sm btn-secondary btn_print_lot" id="btn_print" data-toggle="modal" title="No OQC Inspection result Details" disabled><i class="fa fa-print fa-sm"></i></button></center>';
            }

            return $result;
        })

        ->rawColumns(['action', 'status_raw','subm_raw','sub_lot_raw','packing_code_raw'])
        ->make(true);
    }

    // Get oqc insp by ID
    public function get_insp_result_by_id(Request $request){
        // $ins_result_by_id = oqcVIR::with(['oqclotapp_details','wbs_kitting'])
        // ->where('fkid_oqclotapp',$request['id'])
        // ->get();
        $ins_result_by_id = oqcLotApp::with(['oqcvir_details',
            'wbs_kitting',
            'wbs_kitting.device_info'
        ])
        ->where('id',$request['id'])
        ->get();

        $po_no = '';
        $packing_code = '';

        if($ins_result_by_id->count() > 0){
            $po_no = QrCode::format('png')
                            ->size(200)->errorCorrection('H')
                            ->generate($ins_result_by_id[0]->po_no);

            $po_no = "data:image/png;base64," . base64_encode($po_no);
        }

        if($ins_result_by_id->count() > 0){
            // $packing_code = QrCode::format('png')
            //                 ->size(200)->errorCorrection('H')
            //                 ->generate($ins_result_by_id[0]->oqcvir_details->packing_code);

            // $packing_code = "data:image/png;base64," . base64_encode($packing_code);

            //-03132020
            if ( $ins_result_by_id[0]->po_no == '450214397600010'){

                $new_pc = ($ins_result_by_id[0]->oqcvir_details->packing_code);
                $new_pc = substr($new_pc,0, 5);

                $num = ($ins_result_by_id[0]->oqcvir_details->packing_code);
                $num2 = substr($num, -3);
                $num3 = (int)$num2+2;

                $packing_code = QrCode::format('png')
                                ->size(200)->errorCorrection('H')
                                ->generate($new_pc.str_pad($num3, 3, '0', STR_PAD_LEFT));

                $packing_code = "data:image/png;base64," . base64_encode($packing_code);
                $dis_packing_code = $new_pc.str_pad($num3, 3, '0', STR_PAD_LEFT);

            }else{

                $packing_code = QrCode::format('png')
                                ->size(200)->errorCorrection('H')
                                ->generate($ins_result_by_id[0]->oqcvir_details->packing_code);

                $packing_code = "data:image/png;base64," . base64_encode($packing_code);
                $dis_packing_code = $ins_result_by_id[0]->oqcvir_details->packing_code;

            }
        }

        return response()->json(['ins_result_by_id' => $ins_result_by_id, 'packing_code' => $packing_code, 'po_no' => $po_no, 'dis_packing_code' => $dis_packing_code]); 
    }

    // Get PO Details
    public function get_po_details(Request $request){
        $po_details = ProductionRuncard::with(['wbs_kitting'])->where('po_no', $request->po)->get();
        return response()->json(['po_details' => $po_details]);
    }

    // Get oqc vir summary
    public function get_oqcvir_summary(Request $request){
        $oqc_vir = oqcVIR::with([
            'oqclotapp_details',
            'oqclotapp_details.user_details'])
        // ->where('fkid_oqclotapp',$request['fkid_oqclotapp'])
        ->where('c_lot_batch_no',$request['c_lot_batch_no'])
        ->where('status', '!=', '3')
        ->orderBy('submission','asc')->get();
        return DataTables::of($oqc_vir)
        
        ->addColumn('sub_raw', function($oqc_vir){
            $result = "";

                switch ($oqc_vir->submission) {
                    case 1:
                        $result ='<span class="badge badge-pill s1 badge-success">1st Sub</span>';
                        break;
                    case 2:
                        $result ='<span class="badge badge-pill s2 badge-warning">2nd Sub</span>';
                        break;
                    case 3:
                        $result ='<span class="badge badge-pill s3 badge-danger">3rd Sub</span>';
                        break;
                }

            return $result;
        })

        ->addColumn('okqty_raw', function($oqc_vir){
            $result = "";
                $result = '<font color="blue">'.$oqc_vir->ok_qty.'</font>';
            return $result;
        })

        ->addColumn('ngqty_raw', function($oqc_vir){
            $result = "";
                $result = '<font color="red">'.$oqc_vir->ng_qty.'</font>';
            return $result;
        })

        ->addColumn('acc_req_raw', function($oqc_vir){
            $result = "";

            switch ($oqc_vir->acc_req) {
                case 1:
                    $result ='Yes';
                    break;
                case 2:
                    $result ='No';
                    break;
            }
            return $result;

        })
        ->addColumn('coc_req_raw', function($oqc_vir){
            $result = "";

            switch ($oqc_vir->coc_req) {
                case 1:
                    $result ='Yes';
                    break;
                case 2:
                    $result ='No';
                    break;
            }
            return $result;

        })
        ->addColumn('result_raw', function($oqc_vir){
            $result = "";

            switch ($oqc_vir->result) {
                case 1:
                    $result ='<span class="badge badge-pill badge-success">No Defect Found</span>';
                    break;
                case 2:
                    $result ='<span class="badge badge-pill badge-danger">With defect Found/Details</span>';
                    break;
            }
            return $result;
        })

        ->addColumn('judgement_raw', function($oqc_vir){
            $result = "";

            switch ($oqc_vir->judgement) {
                case 1:
                    $result ='<span class="badge badge-pill badge-primary">Accepted</span>';
                    break;
                case 2:
                    $result ='<span class="badge badge-pill badge-danger">Rejected</span>';
                    break;
            }
            return $result;
        })

        ->addColumn('insp_date_raw', function($oqc_vir){
            $result = "";
            $result = date('F j, Y',strtotime($oqc_vir->insp_date));
            return $result;
        })

        ->addColumn('insp_setime_raw', function($oqc_vir){
            $result = "";
            $result .= date('h:i a',strtotime('2001-01-01'.$oqc_vir->insp_stime)).' - ';
            $result .= date('h:i a',strtotime('2001-01-01'.$oqc_vir->insp_etime));
            return $result;
        })

        ->addColumn('insp_name_raw', function($oqc_vir){
            $result = "";
            $judgement = "";

                switch ($oqc_vir->judgement) {
                    case 1:
                        $judgement ='Accept';
                        break;
                    case 2:
                        $judgement ='Reject';
                        break;
                }

                if ( $oqc_vir->judgement == 1) {
                    $result = '<span class="badge badge-pill badge-primary">'.$oqc_vir->user_details->name.' - '.$oqc_vir->insp_stamp.' ('.$judgement.')</span>';
                }else{
                    $result = '<span class="badge badge-pill badge-danger">'.$oqc_vir->user_details->name.' - '.$oqc_vir->insp_stamp.' ('.$judgement.')</span>';
                }

            return $result;
        })

        ->addColumn('action', function($oqc_vir){
            $result = "";
            return $result;
        })

        ->rawColumns(['action', 'acc_req_raw', 'coc_req_raw', 'result_raw', 'judgement_raw', 'insp_date_raw', 'insp_setime_raw','sub_raw','insp_name_raw','okqty_raw','ngqty_raw'])
        ->make(true);
    }

    //View
    public function get_oqcvir_details(Request $request){
        $oqc_details = oqcLotApp::with([
            'oqcvir_details' => function($query){
                $query->orderBy('submission','DESC')
                    ->orderBy('status','DESC');
            },
            'wbs_kitting',
            'oqcvir_details.user_details'
        ])
            ->where('id',$request['id'])
            ->get();

        $device_name = ($oqc_details[0]->wbs_kitting->device_name);
        $devices = Device::where('name', $device_name)->take(1)->get();
        $exp_series  = explode("-", $devices[0]->name)[0];
        $series = Series::where('series_name', 'like','%'.$exp_series.'%')->get()->take(1);

        $total = 0;
        if( $oqc_details ){
             $sub_lot = oqcVIR::with([
                    'oqclotapp_details'
                ])
                ->where('c_sub_lot_no',$oqc_details[0]->sub_lot_no)
                // ->whereIn('status', [1,3])
                ->where('status', 1)
                ->whereHas(
                    'oqclotapp_details',function($query) use ($oqc_details){
                        $query->where('po_no',$oqc_details[0]->po_no);
                    },
                )
                ->get()
                ->groupBy('c_lot_batch_no');
            $total = count($sub_lot);
        }

       $d_qty = $devices[0]->ship_boxing / $devices[0]->boxing;
        
        return response()->json(['oqc_details' => $oqc_details, 'devices' => $devices, 'series' => $series, 'total' => $total, 'd_qty' => $d_qty]);
    }

    public function get_oqclotapp_data(Request $request){
        $oqc_details = oqcLotApp::with(['wbs_kitting'])->where('lot_batch_no',$request['lot_batch_no'])->get();
        return $oqc_details;
    }

    // Add
    public function add_oqc_vir(Request $request){
        date_default_timezone_set('Asia/Manila');
        
        $user_exist = User::where('id', $request->name_hidden_OQCInsName_id)->get();
        if($user_exist->count() == 0){
            return response()->json(['result' => "2"]); //invalid employee no.
        }

        if ( $request->hidden_sub > 3 ){
            return response()->json(['result' => "3"]); // more than 3rd sub
        }

        $data = $request->all();

        $validator = Validator::make($data, [
            'hidden_OQCLotApp_name' => ['max:255'],
            'name_current_lotbatch_number' => ['max:255'],
            'hidden_OQCLotApp_name' => ['max:255'],
            'name_sample_size_result' => ['max:255'],
            'hidden_sub' => ['max:255'],
            'name_ok_qty' => ['max:255'],
            'name_ng_qty' => ['max:255'],
            'name_inspection_date' => ['max:255'],
            'name_inspection_stime' => ['max:255'],
            'name_inspection_etime' => ['max:255'],
            'name_hidden_OQCInsName_id' => ['max:255'],
            'name_OQCIns_stamp' => ['max:255'],
            'name_accessories_req' => ['max:255'],
            'name_coc_req' => ['max:255'],
            'name_oqc_result' => ['max:255'],
            'name_judgement' => ['max:255'],
            'name_oqc_vir_remarks' => ['max:255']
            
        ]);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{

            DB::beginTransaction();

            if ( $request->hidden_OQCVIR_id == '' ){
                //insert new data kapag wala pa sa OQC VIR
                try{
                    oqcVIR::insert([
                        'fkid_oqclotapp' => $request->hidden_OQCLotApp_name,
                        'c_lot_batch_no' => $request->name_current_lotbatch_number,
                        'c_sub_lot_no' => $request->hidden_sub_lot,
                        'packing_code' => $request->hidden_packing_code,
                        'submission' => $request->hidden_sub,
                        'status' => $request->hidden_OQCVIR_new_status,
                        'insp_date' => $request->name_inspection_date,
                        'insp_stime' => $request->name_inspection_stime,
                        'insp_name' => $request->name_hidden_OQCInsName_id,
                        'insp_stamp' => $request->name_OQCIns_stamp,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);

                    DB::commit();

                    return response()->json(['result' => "1"]);
                }
                catch(\Exception $e) {
                    DB::rollback();
                    return response()->json(['result' => $e]);
                }

            }else{

                if ( $request->hidden_OQCVIR_new_status == 1 ){ // lot accepted
                   
                    if ( $request->hidden_total_count_sub_lot == $request->hidden_d_qty){

                       // update = equal sa dlabel qty
                       try{
                            oqcVIR::where('c_sub_lot_no',$request->hidden_sub_lot)
                            ->where('status', '!=', 2)
                            ->update(
                                [
                                'status' => $request->hidden_OQCVIR_new_status,
                                'packing_code' => $request->hidden_packing_code,
                                'oqc_sample' => $request->name_sample_size_result,
                                'ok_qty' => $request->name_ok_qty,
                                'ng_qty' => $request->name_ng_qty,
                                'insp_etime' => date('H:i:s'),
                                'acc_req' => $request->name_accessories_req,
                                'coc_req' => $request->name_coc_req,
                                'result' => $request->name_oqc_result,
                                'judgement' => $request->name_judgement,
                                'remarks' => ($request->name_oqc_vir_remarks)?$request->name_oqc_vir_remarks:'N/A',
                                'updated_at' => date('Y-m-d H:i:s')
                            ]);

                            DB::commit();

                            return response()->json(['result' => "1"]);
                        }

                        catch(\Exception $e) {
                            DB::rollback();
                            return response()->json(['result' => $e]);
                        }
                    }else{
                        
                       // update = hindi pa equal sa dlabel qty
                       try{
                            oqcVIR::where('id',$request->hidden_OQCVIR_id)
                            ->update(
                                [
                                'status' => $request->hidden_OQCVIR_new_status,
                                'packing_code' => $request->hidden_packing_code,
                                'oqc_sample' => $request->name_sample_size_result,
                                'ok_qty' => $request->name_ok_qty,
                                'ng_qty' => $request->name_ng_qty,
                                'insp_etime' => date('H:i:s'),
                                'acc_req' => $request->name_accessories_req,
                                'coc_req' => $request->name_coc_req,
                                'result' => $request->name_oqc_result,
                                'judgement' => $request->name_judgement,
                                'remarks' => ($request->name_oqc_vir_remarks)?$request->name_oqc_vir_remarks:'N/A',
                                'updated_at' => date('Y-m-d H:i:s')
                            ]);

                            DB::commit();

                            return response()->json(['result' => "1"]);
                        }
                        catch(\Exception $e) {
                            DB::rollback();
                            return response()->json(['result' => $e]);
                        }                   
                    }

                } else if ( $request->hidden_OQCVIR_new_status == 2 ){ // lot rejected
                    
                   // update = equal sa dlabel qty
                   try{
                        oqcVIR::where('id',$request->hidden_OQCVIR_id)
                        ->update(
                            [
                            'status' => $request->hidden_OQCVIR_new_status,
                            'packing_code' => $request->hidden_packing_code,
                            'oqc_sample' => $request->name_sample_size_result,
                            'ok_qty' => $request->name_ok_qty,
                            'ng_qty' => $request->name_ng_qty,
                            'insp_etime' => date('H:i:s'),
                            'acc_req' => $request->name_accessories_req,
                            'coc_req' => $request->name_coc_req,
                            'result' => $request->name_oqc_result,
                            'judgement' => $request->name_judgement,
                            'remarks' => ($request->name_oqc_vir_remarks)?$request->name_oqc_vir_remarks:'N/A',
                            'updated_at' => date('Y-m-d H:i:s')
                        ]);

                        DB::commit();

                        return response()->json(['result' => "1"]);
                    }
                    catch(\Exception $e) {
                        DB::rollback();
                        return response()->json(['result' => $e]);
                    } 

                } else if ( $request->hidden_OQCVIR_new_status == 3 ){ // on-going inspection

                    try{
                        oqcVIR::insert([
                            'fkid_oqclotapp' => $request->hidden_OQCLotApp_name,
                            'c_lot_batch_no' => $request->name_current_lotbatch_number,
                            'c_sub_lot_no' => $request->hidden_sub_lot,
                            'packing_code' => $request->hidden_packing_code,
                            'submission' => $request->hidden_sub,
                            'status' => $request->hidden_OQCVIR_new_status,
                            'insp_date' => $request->name_inspection_date,
                            'insp_stime' => $request->name_inspection_stime,
                            'insp_name' => $request->name_hidden_OQCInsName_id,
                            'insp_stamp' => $request->name_OQCIns_stamp,
                            'created_at' => date('Y-m-d H:i:s')
                        ]);

                        DB::commit();

                        return response()->json(['result' => "1"]);
                    }
                    catch(\Exception $e) {
                        DB::rollback();
                        return response()->json(['result' => $e]);
                    } // on-going inspection

                }

            }

        }
    }

    // Get User details
    public function get_user_details_vir(Request $request){
        $user_details = User::with(['oqc_stamps'])
        ->where('employee_id', $request->employee_id)
        ->where('position', 5)->get();
        return response()->json(['user_details' => $user_details]);
    }

    public function get_oqc_lotapp_data_summary(Request $request){
        $oqc_inspections = oqcLotApp::where('lot_batch_no',$request['lot_batch_no'])
        ->where('status', 2)->get();
        return DataTables::of($oqc_inspections)

        ->addColumn('sub_raw', function($oqc_inspection){
            $result = "";

                switch ($oqc_inspection->submission) {
                    case 1:
                        $result ='<span class="badge badge-pill s1 badge-success">1st Sub</span>';
                        break;
                    case 2:
                        $result ='<span class="badge badge-pill s2 badge-warning">2nd Sub</span>';
                        break;
                    case 3:
                        $result ='<span class="badge badge-pill s3 badge-danger">3rd Sub</span>';
                        break;
                }

            return $result;
        })

        ->addColumn('devcat_raw', function($oqc_inspection){
            $result = "";

                switch ($oqc_inspection->device_cat) {
                    case 1:
                        $result ='Automotive';
                        break;
                    case 2:
                        $result ='Non-Automotive';
                        break;
                }

            return $result;
        })

        ->addColumn('certlot_raw', function($oqc_inspection){
            $result = "";

                switch ($oqc_inspection->cert_lot) {
                    case 1:
                        $result ='New Operator';
                        break;
                    case 2:
                        $result ='New product/model';
                        break;
                    case 3:
                        $result ='Evaluation lot';
                        break;
                    case 4:
                        $result ='Re-inspection';
                        break;
                    case 5:
                        $result ='Flexibility';
                        break;
                    case 6:
                        $result ='N/A';
                        break;
                }

            return $result;
        })

        ->addColumn('assy_raw', function($oqc_inspection){
        $result = "";
            
        $assy_line = AssemblyLine::where('id',$oqc_inspection['assy_line'])->get();
            if( $assy_line ){
                $result = $assy_line[0]->name;
            }

        return $result;

        })

        ->addColumn('guar_lot_raw', function($oqc_inspection){
            $result = "";

                switch ($oqc_inspection->guaranteed_lot) {
                    case 1:
                        $result = '<font color="red">With</font>';
                        break;
                    case 2:
                        $result = '<font color="black">Without</font>';
                        break;
                }

            return $result;
        })

        ->addColumn('fvo_raw', function($oqc_inspection){
            $result = null;

            if ( $oqc_inspection ){
                $empno_arr = array();
                $empno_arr = $oqc_inspection->FVO_empid;
                $empno_arr = explode(',', $empno_arr);  
                $user_details = array();
                if(count( $empno_arr )){
                    for ($i=0; $i < count($empno_arr) ; $i++) { 
                        $user_details_temp = array();
                        $user_details_temp = User::where('id',$empno_arr[$i])->get();
                        array_push($user_details, $user_details_temp);
                    }
                }

                if( count($user_details) ){
                    foreach ($user_details as $key => $value) {
                        $result .= '<span class="badge badge-pill badge-info"> '.$value[0]['name'].'</span> ';
                    }
                }
            }
           
            return $result;
        })

        ->addColumn('app_date_raw', function($oqc_inspection){
            $result = "";
                $result = date('F j, Y',strtotime($oqc_inspection->app_date));
            return $result;
        })

        ->addColumn('app_time_raw', function($oqc_inspection){
            $result = "";
                $result .= date('h:i a',strtotime('2001-01-01'.$oqc_inspection->app_time));
            return $result;
        })

        ->addColumn('action', function($oqc_inspection){
            $result = "";
            return $result;
        })

        ->rawColumns(['action','sub_raw','devcat_raw','certlot_raw','guar_lot_raw','assy_raw','fvo_raw','app_date_raw','app_time_raw'])
        ->make(true);
    }

}
