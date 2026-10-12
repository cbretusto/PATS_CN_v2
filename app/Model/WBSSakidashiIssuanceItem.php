<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\ProductionRuncard;
use App\Model\PartsPrep;
use App\Model\WBSSakidashiIssuance;

class WBSSakidashiIssuanceItem extends Model
{
    //
    protected $table = 'tbl_wbs_sakidashi_issuance_item';
    protected $connection = 'mysql_subsystem';

    public function wbs_sakidashi_issuance() {
    	return $this->belongsTo(WBSSakidashiIssuance::class);
    }
}
