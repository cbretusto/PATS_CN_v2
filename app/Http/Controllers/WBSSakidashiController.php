<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\WBSSakidashiIssuanceItem;

class WBSSakidashiController extends Controller
{
    //
    public function get_wbs_sakidashi_details(Request $request) {
    	$sakidashi_details = WBSSakidashiIssuanceItem::select('item', 'item_desc')->distinct('item')->get();

    	return response()->json(['sakidashi_details' => $sakidashi_details]);
    }
}
