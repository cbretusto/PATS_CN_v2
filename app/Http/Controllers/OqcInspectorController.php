<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\oqcLotApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use DataTables;

class OqcInspectorController extends Controller
{
    public function view_runcards(Request $request)
    {
    	$runcards = oqcLotApp::where('runcard_no',$request['runcard_number'])->get();

    	return DataTables::of($runcards)
    		->addColumn('runcardno', function($runcard)
    		{
    			$result = 'wawawi';//$runcard->runcard_no;
    			return $result;
    		})
    		->addColumn('totallot', function($runcard)
    		{
    			$result = 'wew';//$runcard->total_lot;
    			return $result;
    		})

    		->addColumn('operator', function($runcard)
    		{
    			$result = 'wew talaga';//$runcard->operator_name;
    			return $result;
    		})

    		->addColumn('action', function($runcard)
    		{
    			$result = '<button type="button"><i class="fa fa-edit fa-sm"></i><button>';
    			return $result;
    		})
    }

    public function submit_oqcins(Request $request)
    {

    }
}
