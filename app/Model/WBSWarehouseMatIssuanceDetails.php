<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\WBSWarehouseMatIssuance;
use App\Model\RequestDetail;
use App\Model\RequestSummary;

class WBSWarehouseMatIssuanceDetails extends Model
{
    protected $table = 'tbl_wbs_warehouse_mat_issuance_details';
    protected $connection = 'mysql_subsystem';

    public function tbl_wbs_warehouse_mat_issuance(){
    	return $this->hasOne(WBSWarehouseMatIssuance::class, 'issuance_no', 'issuance_no');
    }
    public function tbl_request_detail(){
    	return $this->hasOne(RequestDetail::class, 'whstransno', 'issuance_no');
    }
    public function tbl_request_summary(){
    	return $this->hasOne(RequestSummary::class, 'whstransno', 'issuance_no');
    }
}
