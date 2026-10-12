<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Model\ProdPrelimInspection;
use App\Model\OQCPackingInspection;
use App\Model\oqcVIR;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use DataTables;

class PackingListController extends Controller
{
    public function view_packing_list(Request $request)
    {
    	$packing_lists = OQCPackingInspection::with(['prod_prelim_details'])
    	->whereHas("prod_prelim_details", function($q) use ($request)
			{
				$q->where('device_name',$request->device_name);
			})
    	->where('shipping_date','!=', null)
    	->select(DB::raw('distinct shipping_date'))
    	->orderBy('shipping_date', 'desc')
    	->get();

    	//return $packing_lists;

    	return DataTables::of($packing_lists)
    	->addColumn('action', function($packing_list) use ($request)
    	{
    		$result = '<button type="button" class="px-2 py-1 btn btn-sm btn-info btn_view_packing_list" data-toggle="modal" data-target="#modalPackingList" title="View Packing List" ship-date='.$packing_list->shipping_date.' device='.$request->device_name.'><i class="fa fa-search fa-sm"></i></button>';

    		return $result;
    	})
    	->addColumn('ship_date', function($packing_list)
    	{
    		return $packing_list->shipping_date;
    	})
    	->addColumn('pack_codes',function($packing_list)
    	{
    		$arrPackCodes = [];

    		$result = '';

    		$packing_lots = ProdPrelimInspection::with(['oqc_packing_details'])
    	   	->whereHas("oqc_packing_details", function($q) use ($packing_list)
			{
				$q->where('shipping_date',$packing_list->shipping_date);
			})->get();

			//return $packing_lots;

			if(count($packing_lots) > 0)
			{
				for($i = 0; $i < count($packing_lots); $i++)
				{
					$result .= "<span class='badge badge-pill badge-secondary'>".$packing_lots[$i]->pack_code_no."</span> ";
				}
			}
			else
			{
				$result = "<span class='badge badge-pill badge-info'>No Packing Codes Found. Contact ISS at local 205</span>";
			}

			return $result;
    	})
    	->addColumn('ship_qty', function($packing_list) use ($request)
    	{
    		$arrPackCodes = [];


    		$total = 0;

    		$packing_lots = ProdPrelimInspection::with(['oqc_packing_details'])
    	   	->whereHas("oqc_packing_details", function($q) use ($packing_list)
			{
				$q->where('shipping_date',$packing_list->shipping_date);
			})->get();

			//return $packing_lots;

			if(count($packing_lots) > 0)
			{
				for($i = 0; $i < count($packing_lots); $i++)
				{
					$arrPackCodes[] = $packing_lots[$i]->pack_code_no;
				}
			}
			else
			{
				$arrPackCodes[] = 0;
			}

			$total_qty = oqcVIR::with('oqclotapp_details')->whereIn('packing_code',$arrPackCodes)->get();

			if(count($total_qty) > 0)
			{
				for($x = 0; $x < count($total_qty); $x++)
				{
					$total += $total_qty[$x]->oqclotapp_details->output_qty;
				}
			}
			else
			{
				$total = 0;
			}

    		return $total;
    	})
    	->addColumn('ship_dest', function($packing_list)
    	{
    		$ship_dest = OQCPackingInspection::where('shipping_date', $packing_list->shipping_date)->get();

    		$arrShipping = [];

    		if(count($ship_dest) > 0)
    		{
    			for($i = 0; $i < count($ship_dest); $i++)
    			{
    				$arrShipping[] = $ship_dest[$i]->shipping_destination;
    			}
    		}

    		return array_unique($arrShipping);
    	})
    	->rawColumns(['action','pack_codes'])
    	->make(true);
    }

    public function view_packing_list_details(Request $request)
    {
        
        $details = OQCPackingInspection::with(['prod_prelim_details'])
        ->whereHas("prod_prelim_details", function($q) use ($request)
            {
                $q->where('device_name',$request->device_name);
            })
        ->where('shipping_date',$request->ship_date)->get();

        return DataTables::of($details)
        ->addColumn('po_num', function($detail){
            return $detail->po_num;
        })
        ->addColumn('pack_code', function($detail){
            return $detail->pack_code_no;
        })
        ->addColumn('lot_no', function($detail) use($request){

            $result = '';

           $lot_nos = OQCPackingInspection::with(['prod_prelim_details.oqcvir_details.oqclotapp_details'])->where('shipping_date',$request->ship_date)->where('pack_code_no', $detail->pack_code_no)->get();

            if(count($lot_nos) > 0)
            {
                for($i = 0; $i < count($lot_nos); $i++)
                {
                    $result .= $lot_nos[$i]->id;
                }
            }
            else
            {
                $result = "wew";
            }

            return $result;
        })
        ->addColumn('lot_qty', function($detail){
            return 'wew';
        })
        ->make(true);
    }
}
