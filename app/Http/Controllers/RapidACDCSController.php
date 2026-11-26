<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Model\RapidActiveDocs;



use DataTables;

class RapidACDCSController extends Controller
{

    public function get_docNo_details(Request $request){
        $docNo_details = RapidActiveDocs::where('doc_no', $request->doc_no)->get();
        return response()->json(['docNo_details' => $docNo_details]);
    }

     public function get_acdcs_data(Request $request){
        $acdcs_docs = RapidActiveDocs::limit(1000)
        ->where('doc_no','LIKE', '%' . $request['doc_no'] . '%')
        ->where('logdel', 0)
        ->where('model', 'CA009')
        ->get();   
        return DataTables::of($acdcs_docs)
    
        ->addColumn('action', function($acdcs_docs){
            $result = "";

            $result.='<center><button type="button" class="px-2 py-1 btn btn-sm btn-success btn_view_docs" id="btn_view" data-toggle="modal" value="'.$acdcs_docs['fkid_document'].'" data-file_type="'.$acdcs_docs['file_type'].'"  title="View Document"><i class="fa fa-eye fa-sm"></i></button>';
            
            return $result;
        })

        ->rawColumns(['action'])
        ->make(true);
    }
    
}



