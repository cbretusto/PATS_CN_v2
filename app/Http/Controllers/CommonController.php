<?php

namespace App\Http\Controllers;

use Auth;
use QrCode;
use App\User;
use DataTables;
use App\SystemOneHRIS;
use App\SystemOneSubcon;
use App\Model\CnPptsDevice;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\CnPptsDropdownDetails;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;

class CommonController extends Controller
{
    public function generate_qrcode(Request $request){
        try{
            if(isset($request->qrcode)){
                $qrcode = QrCode::format('png')
                        ->size(200)->errorCorrection('H')
                        ->generate($request->qrcode);

                return response()->json(['result' => "1", 'qrcode' => "data:image/png;base64," . base64_encode($qrcode)]);
            }
            else{
                return response()->json(['result' => "0"]);
            }
        }
        catch(\Exception $e){
            return response()->json(['result' => "0"]);
        }
    }

    public function iframe_request(Request $request){ // FOR IFRAME
        $user = User::where('employee_id', $request->emp_id)
        ->where('username', $request->username)
        ->first();


        if(isset($user->id)){
            Auth::loginUsingId($user->id);
		    return View::make('shipmentconfirmation')->with('iframe', 1);
        }
        else{
            return View::make('welcome');
        }
    }

    public function get_ppts_dropdown(Request $request){
        $dropdowns = CnPptsDropdownDetails::where('logdel', 0)
        ->get();


        return collect($dropdowns)->groupBy('dropdown_title_pkid');
    }

    public function get_user_by_emp_id(Request $request){
        $user_details =  User::where('employee_id', $request->emp_id)
        ->where('status', 1)
        ->first();

        return response()->json([
            'userDetails' => $user_details
        ]);
    }

    public function get_ppts_sgc_drawing(Request $request){
        $ppts_device = CnPptsDevice::where('barcode', $request->device_code)
        ->where('name', $request->device_name)
        ->pluck('sgc_drawing_rev','sgc_drawing_no');

        return $ppts_device;
    }

    public function get_user_details(Request $request){
        // $user = null;
        $user = SystemOneHRIS::where('EmpNo', $request->emp_id)->first(['FirstName', 'MiddleName', 'LastName']);

        // return $user;
        if(!$user){
            $user = SystemOneSubcon::where('EmpNo', $request->emp_id)->first(['FirstName', 'MiddleName', 'LastName']);
        }
        // return $user;
        return response()->json([
            'result' => true,
            'details' => $user
        ]);
    }
}
