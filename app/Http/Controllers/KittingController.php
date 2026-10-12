<?php

namespace App\Http\Controllers;

use DataTables;
use App\Model\Kitting;
use App\Model\SubKitting;
use Illuminate\Http\Request;
use App\Model\WBSKitIssuance;
use Illuminate\Support\Facades\DB;
use App\Model\MaterialIssuanceDetails;
use App\Model\MaterialIssuanceSubSystem;
use Illuminate\Support\Facades\Validator;
use QrCode;


class KittingController extends Controller
{
    public function get_kitting_details_by_issuance_no(Request $request){
    	date_default_timezone_set('Asia/Manila');

    	$kitting_details = MaterialIssuanceDetails::where('issue_no', $request->issuance_no)->get();

    	return DataTables::of($kitting_details)
        ->make(true);
    }

    public function get_kitting_info_by_issuance_no(Request $request){
    	date_default_timezone_set('Asia/Manila');

    	$kitting_info = MaterialIssuanceSubSystem::where('issuance_no', $request->issuance_no)->first();

    	return response()->json(['kitting_info' => $kitting_info]);
    }

    public function get_issuance_details_by_issuance_no(Request $request){
        date_default_timezone_set('Asia/Manila');

        $issuance_details = WBSKitIssuance::with(['pats_kitting_info' => function($query){
                            $query->where('status', 1);
                            }])
        ->where('issue_no', $request->issuance_no)
        ->get();

        return DataTables::of($issuance_details)
            ->addColumn('raw_action', function($row){
                $result = "<center>";

                // if($row->pats_kitting_info == null){
                    $result .= '<button class="aSubKitIssuance btn btn-xs btn-primary" style="padding: 1px 5px;" type="button" kit-issuance-id="' . $row->id . '" issued-qty="' . $row->issued_qty . '" issuance-no="' . $row->issue_no . '"  po-no="' . $row->po . '" item="' . $row->item . '" item-desc="' . $row->item_desc . '" detail-id="' . $row->detailid . '" lot-no="' . $row->lot_no . '" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-keyboard="false" title="Sub Kit"><i class="fa fa-list"></i></button>';
                // }
                // else{
                    // $result .= '<button class="aViewSubKitIssuance btn btn-xs btn-success" style="padding: 1px 5px;" type="button" pats-kitting-id="' . $row->pats_kitting_info->id . '" pats-kitting-status="' . $row->pats_kitting_info->status . '" kit-issuance-id="' . $row->id . '" issued-qty="' . $row->issued_qty . '" issuance-no="' . $row->issue_no . '"  po-no="' . $row->po . '" item="' . $row->item . '" item-desc="' . $row->item_desc . '" detail-id="' . $row->detailid . '" lot-no="' . $row->lot_no . '" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-keyboard="false" title="View Sub Kit Details"><i class="fa fa-file-alt"></i></button>';
                // }

                $result .= "</center>";
                return $result;
            })
            ->rawColumns(['raw_action'])
            ->make(true);
    }

    public function save_sub_kitting(Request $request){
        date_default_timezone_set('Asia/Manila');

        DB::beginTransaction();
        
        try{
            $kitting_id = Kitting::firstOrCreate(
                ['kit_issuance_id' => $request->kit_issuance_id],
                [
                    'po_no'           => $request->po_no,
                    'issue_no'        => $request->issuance_no,
                    'item'            => $request->item,
                    'item_desc'       => $request->item_desc,
                    'sub_kit_qty'     => $request->sub_kit_qty,
                    'status'          => 1,
                    'created_by'      => auth()->user()->id,
                    'last_updated_by' => auth()->user()->id,
                    'created_at'      => NOW()
                ]
            );

            SubKitting::insert([
                'pats_kitting_id' => $kitting_id->id,
                'sub_kit_desc'    => $request->prefix,
                'sub_kit_qty'     => $request->sub_kit_qty,
                'status'          => 1,
                'created_at'      => NOW()
            ]);

            DB::commit();
            return response()->json([
                'result' => true,
                'message' => 'Successfully saved.'
            ]);
        }catch(Exemption $e){
            DB::rollback();
            return $e;
        }
    }

    public function get_sub_kits_by_detail_id(Request $request){
        date_default_timezone_set('Asia/Manila');

        $kitting = Kitting::where('kit_issuance_id', $request->issuance_id)->first();
        $sub_kits = array();
        $sums = 0;
        if(isset($kitting)){
            $sub_kits = SubKitting::where('pats_kitting_id', $kitting->id)->get();
            $sums = collect($sub_kits)->sum('sub_kit_qty');
        }
        return DataTables::of($sub_kits)
        ->addColumn('action', function($sub_kits){
            $result = "<center>";
            $result .= '<button class="aPrintSubKit btn btn-xs btn-primary" style="padding: 1px 5px;" type="button" style="padding: 1px 1px; text-align: center;" data-toggle="modal" data-keyboard="false" sub-kit-desc="' . $sub_kits->sub_kit_desc . '" sub-kit-id="' . $sub_kits->id . '"  title="Print"><i class="fa fa-qrcode"></i></button>';

            $result .= "</center>";
            return $result;
        })
        ->with('sum', $sums)
        ->rawColumns(['action'])
        ->make(true);
    }

    public function generate_qr_code(Request $request){
        $sub_kits = SubKitting::where('id', $request->sub_kit_id)->first();
        $po_qr_code = "";
        $lot_no_qr_code = "";
        $issuance_qr_code = "";

        if(isset($sub_kits)){
            $po_qr = QrCode::format('png')
                        ->size(500)->errorCorrection('H')
                        ->generate(explode(' | ', $sub_kits->sub_kit_desc)[2]);
            $po_qr_code = "data:image/png;base64," . base64_encode($po_qr);
            

            $lot_no_qr = QrCode::format('png')
                        ->size(200)->errorCorrection('H')
                        ->generate(explode(' | ', $sub_kits->sub_kit_desc)[4]);
            $lot_no_qr_code = "data:image/png;base64," . base64_encode($lot_no_qr);

            $issuance_qr = QrCode::format('png')
                        ->size(200)->errorCorrection('H')
                        ->generate(explode(' | ', $sub_kits->sub_kit_desc)[7]);
            $issuance_qr_code = "data:image/png;base64," . base64_encode($issuance_qr);
        }

        return response()->json([
            'po_qr_code' => $po_qr_code,
            'lot_no_qr_code' => $lot_no_qr_code,
            'issuance_qr_code' => $issuance_qr_code,
            'sub_kit' => $sub_kits
        ]);
    }


}
